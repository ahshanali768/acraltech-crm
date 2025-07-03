<?php

namespace App\Http\Controllers;

use App\Models\Did;
use App\Models\Campaign;
use App\Models\CallLog;
use App\Services\TelephonyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PayPerCallController extends Controller
{
    protected $telephonyService;

    public function __construct(TelephonyService $telephonyService)
    {
        $this->telephonyService = $telephonyService;
    }

    /**
     * Show Pay-per-Call settings dashboard
     */
    public function index()
    {
        $settings = $this->getPayPerCallSettings();
        $statistics = $this->getPayPerCallStatistics();
        
        return view('admin.pay-per-call.index', compact('settings', 'statistics'));
    }

    /**
     * Update Pay-per-Call settings
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'default_payout_rate' => 'required|numeric|min:0',
            'qualification_criteria' => 'required|array',
            'minimum_call_duration' => 'required|integer|min:0',
            'quality_score_threshold' => 'required|integer|min:0|max:100',
            'auto_lead_creation' => 'required|boolean',
            'webhook_notifications' => 'required|boolean',
            'fraud_detection' => 'required|boolean',
            'call_recording' => 'required|boolean',
        ]);

        // Update CRM configuration
        $config = config('crm');
        $config['pay_per_call'] = array_merge($config['pay_per_call'] ?? [], $validated);
        
        // Save to config file (in production, this would be stored in database)
        $this->updateConfigFile('crm.pay_per_call', $config['pay_per_call']);
        
        return redirect()->back()->with('success', 'Pay-per-Call settings updated successfully!');
    }

    /**
     * Test telephony provider connection
     */
    public function testProvider(Request $request)
    {
        $provider = $request->input('provider');
        
        try {
            $result = $this->telephonyService->testConnection($provider);
            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'details' => $result['details'] ?? null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection test failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set up dynamic number insertion
     */
    public function setupDNI(Request $request)
    {
        $validated = $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'tracking_pool_size' => 'required|integer|min:1|max:100',
            'session_duration' => 'required|integer|min:300|max:7200',
            'dni_script_enabled' => 'required|boolean',
        ]);

        $campaign = Campaign::findOrFail($validated['campaign_id']);
        
        // Create tracking pool of DIDs for the campaign
        $availableDids = Did::where('status', 'available')
            ->where('provider', $campaign->preferred_provider ?? 'twilio')
            ->limit($validated['tracking_pool_size'])
            ->get();

        if ($availableDids->count() < $validated['tracking_pool_size']) {
            return redirect()->back()->with('error', 'Not enough available DIDs for the requested pool size.');
        }

        // Assign DIDs to campaign for DNI
        foreach ($availableDids as $did) {
            $did->update([
                'campaign_id' => $campaign->id,
                'status' => 'assigned',
                'assignment_type' => 'dni_pool',
                'assignment_metadata' => [
                    'pool_id' => 'dni_' . $campaign->id . '_' . time(),
                    'session_duration' => $validated['session_duration'],
                    'created_at' => now()
                ]
            ]);
        }

        // Update campaign with DNI settings
        $campaign->update([
            'dni_enabled' => true,
            'dni_pool_size' => $validated['tracking_pool_size'],
            'dni_session_duration' => $validated['session_duration'],
            'dni_script_enabled' => $validated['dni_script_enabled'],
        ]);

        return redirect()->back()->with('success', 'Dynamic Number Insertion configured successfully!');
    }

    /**
     * Generate DNI JavaScript code
     */
    public function generateDNIScript(Campaign $campaign)
    {
        if (!$campaign->dni_enabled) {
            return response()->json(['error' => 'DNI not enabled for this campaign'], 400);
        }

        $script = $this->generateDynamicNumberScript($campaign);
        
        return response()->json([
            'script' => $script,
            'instructions' => 'Add this script to your website pages to enable dynamic number insertion.'
        ]);
    }

    /**
     * Get call routing configuration
     */
    public function getCallRouting()
    {
        $routingRules = [
            'business_hours' => [
                'enabled' => true,
                'start_time' => '09:00',
                'end_time' => '17:00',
                'timezone' => 'America/New_York',
                'days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday']
            ],
            'geographic_routing' => [
                'enabled' => false,
                'rules' => []
            ],
            'skill_based_routing' => [
                'enabled' => true,
                'rules' => [
                    ['skill' => 'insurance', 'agents' => ['agent1', 'agent2']],
                    ['skill' => 'legal', 'agents' => ['agent3', 'agent4']]
                ]
            ],
            'overflow_routing' => [
                'enabled' => true,
                'max_queue_time' => 300,
                'overflow_number' => '+1234567890'
            ]
        ];

        return view('admin.pay-per-call.routing', compact('routingRules'));
    }

    /**
     * Update call routing rules
     */
    public function updateCallRouting(Request $request)
    {
        $validated = $request->validate([
            'routing_rules' => 'required|array',
        ]);

        // Save routing rules to configuration
        $this->updateConfigFile('crm.call_routing', $validated['routing_rules']);
        
        return redirect()->back()->with('success', 'Call routing rules updated successfully!');
    }

    /**
     * Get Pay-per-Call settings
     */
    private function getPayPerCallSettings()
    {
        return config('crm.pay_per_call', [
            'default_payout_rate' => 25.00,
            'qualification_criteria' => [
                'minimum_call_duration' => 90,
                'caller_verification' => true,
                'duplicate_checking' => true
            ],
            'minimum_call_duration' => 90,
            'quality_score_threshold' => 75,
            'auto_lead_creation' => true,
            'webhook_notifications' => true,
            'fraud_detection' => true,
            'call_recording' => false,
        ]);
    }

    /**
     * Get integration status
     */
    private function getIntegrationStatus()
    {
        $providers = ['twilio', 'plivo', 'bandwidth'];
        $integrations = [];

        foreach ($providers as $provider) {
            $config = config("crm.telephony.providers.$provider");
            $integrations[$provider] = [
                'name' => ucfirst($provider),
                'enabled' => !empty($config['account_sid']) && !empty($config['auth_token']),
                'status' => 'unknown', // Will be updated by real-time check
                'last_test' => Cache::get("provider_test_$provider", null),
            ];
        }

        return $integrations;
    }

    /**
     * Get Pay-per-Call statistics
     */
    private function getPayPerCallStatistics()
    {
        $today = now()->startOfDay();
        $thisWeek = now()->startOfWeek();
        $thisMonth = now()->startOfMonth();

        return [
            'today' => [
                'qualified_calls' => CallLog::whereDate('created_at', $today)
                    ->where('duration', '>=', 90)
                    ->where('status', 'answered')
                    ->count(),
                'total_payout' => CallLog::whereDate('created_at', $today)
                    ->where('duration', '>=', 90)
                    ->where('status', 'answered')
                    ->sum('payout_amount'),
                'average_duration' => CallLog::whereDate('created_at', $today)
                    ->where('status', 'answered')
                    ->avg('duration'),
                'conversion_rate' => $this->calculateConversionRate($today, $today->copy()->endOfDay()),
            ],
            'week' => [
                'qualified_calls' => CallLog::where('created_at', '>=', $thisWeek)
                    ->where('duration', '>=', 90)
                    ->where('status', 'answered')
                    ->count(),
                'total_payout' => CallLog::where('created_at', '>=', $thisWeek)
                    ->where('duration', '>=', 90)
                    ->where('status', 'answered')
                    ->sum('payout_amount'),
            ],
            'month' => [
                'qualified_calls' => CallLog::where('created_at', '>=', $thisMonth)
                    ->where('duration', '>=', 90)
                    ->where('status', 'answered')
                    ->count(),
                'total_payout' => CallLog::where('created_at', '>=', $thisMonth)
                    ->where('duration', '>=', 90)
                    ->where('status', 'answered')
                    ->sum('payout_amount'),
            ],
        ];
    }

    /**
     * Calculate conversion rate
     */
    private function calculateConversionRate($startDate, $endDate)
    {
        $totalCalls = CallLog::whereBetween('created_at', [$startDate, $endDate])->count();
        $qualifiedCalls = CallLog::whereBetween('created_at', [$startDate, $endDate])
            ->where('duration', '>=', 90)
            ->where('status', 'answered')
            ->count();

        return $totalCalls > 0 ? round(($qualifiedCalls / $totalCalls) * 100, 2) : 0;
    }

    /**
     * Generate dynamic number insertion script
     */
    private function generateDynamicNumberScript(Campaign $campaign)
    {
        return "
(function() {
    // Dynamic Number Insertion Script for Campaign: {$campaign->campaign_name}
    var dniConfig = {
        campaignId: {$campaign->id},
        apiEndpoint: '" . url('/api/dni/get-tracking-number') . "',
        sessionDuration: {$campaign->dni_session_duration},
        fallbackNumber: '{$campaign->fallback_number}',
        selectors: ['.phone-number', '.contact-phone', '[data-dni-replace]']
    };
    
    function getTrackingNumber() {
        var sessionId = sessionStorage.getItem('dni_session_id');
        if (!sessionId) {
            sessionId = 'dni_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            sessionStorage.setItem('dni_session_id', sessionId);
        }
        
        fetch(dniConfig.apiEndpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                campaign_id: dniConfig.campaignId,
                session_id: sessionId,
                referrer: document.referrer,
                url: window.location.href,
                user_agent: navigator.userAgent
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.tracking_number) {
                replacePhoneNumbers(data.tracking_number);
                // Set session expiry
                setTimeout(function() {
                    sessionStorage.removeItem('dni_session_id');
                }, dniConfig.sessionDuration * 1000);
            } else {
                replacePhoneNumbers(dniConfig.fallbackNumber);
            }
        })
        .catch(error => {
            console.warn('DNI Error:', error);
            replacePhoneNumbers(dniConfig.fallbackNumber);
        });
    }
    
    function replacePhoneNumbers(phoneNumber) {
        dniConfig.selectors.forEach(function(selector) {
            var elements = document.querySelectorAll(selector);
            elements.forEach(function(element) {
                if (element.tagName === 'A' && element.href.startsWith('tel:')) {
                    element.href = 'tel:' + phoneNumber;
                }
                element.textContent = phoneNumber;
            });
        });
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', getTrackingNumber);
    } else {
        getTrackingNumber();
    }
})();
        ";
    }

    /**
     * Update configuration file
     */
    private function updateConfigFile($key, $value)
    {
        // In a real application, you would store this in database
        // For now, we'll just update the runtime config
        config([$key => $value]);
    }
}
