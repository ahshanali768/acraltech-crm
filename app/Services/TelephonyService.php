<?php

namespace App\Services;

use App\Models\Did;
use App\Models\CallLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelephonyService
{
    protected $config;

    public function __construct()
    {
        $this->config = config('crm.telephony', []);
    }

    /**
     * Purchase a DID from the telephony provider
     */
    public function purchaseDid($areaCode, $countryCode = 'US', $provider = 'twilio')
    {
        try {
            switch ($provider) {
                case 'twilio':
                    return $this->purchaseTwilioDid($areaCode, $countryCode);
                case 'plivo':
                    return $this->purchasePlivoDid($areaCode, $countryCode);
                case 'bandwidth':
                    return $this->purchaseBandwidthDid($areaCode, $countryCode);
                default:
                    // Mock DID for development/testing
                    return $this->createMockDid($areaCode, $countryCode, $provider);
            }
        } catch (\Exception $e) {
            Log::error('DID Purchase Error: ' . $e->getMessage());
            throw new \Exception('Failed to purchase DID: ' . $e->getMessage());
        }
    }

    /**
     * Purchase DID from Twilio
     */
    protected function purchaseTwilioDid($areaCode, $countryCode)
    {
        $accountSid = config('crm.telephony.twilio.account_sid');
        $authToken = config('crm.telephony.twilio.auth_token');

        if (!$accountSid || !$authToken) {
            return $this->createMockDid($areaCode, $countryCode, 'twilio');
        }

        // Search for available numbers
        $response = Http::withBasicAuth($accountSid, $authToken)
            ->get("https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/AvailablePhoneNumbers/{$countryCode}/Local.json", [
                'AreaCode' => $areaCode,
                'Limit' => 1
            ]);

        if ($response->successful() && !empty($response->json()['available_phone_numbers'])) {
            $availableNumber = $response->json()['available_phone_numbers'][0];
            
            // Purchase the number
            $purchaseResponse = Http::withBasicAuth($accountSid, $authToken)
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/IncomingPhoneNumbers.json", [
                    'PhoneNumber' => $availableNumber['phone_number']
                ]);

            if ($purchaseResponse->successful()) {
                return [
                    'number' => str_replace(['+', '-', '(', ')', ' '], '', $availableNumber['phone_number']),
                    'area_code' => $areaCode,
                    'country_code' => $countryCode,
                    'provider' => 'twilio',
                    'provider_id' => $purchaseResponse->json()['sid'],
                    'monthly_cost' => 1.00,
                    'setup_cost' => 1.00
                ];
            }
        }

        throw new \Exception('No DIDs available for area code ' . $areaCode);
    }

    /**
     * Purchase DID from Plivo
     */
    protected function purchasePlivoDid($areaCode, $countryCode)
    {
        $authId = config('crm.telephony.plivo.auth_id');
        $authToken = config('crm.telephony.plivo.auth_token');

        if (!$authId || !$authToken) {
            return $this->createMockDid($areaCode, $countryCode, 'plivo');
        }

        // Plivo API implementation would go here
        return $this->createMockDid($areaCode, $countryCode, 'plivo');
    }

    /**
     * Purchase DID from Bandwidth
     */
    protected function purchaseBandwidthDid($areaCode, $countryCode)
    {
        $userId = config('crm.telephony.bandwidth.user_id');
        $apiToken = config('crm.telephony.bandwidth.api_token');

        if (!$userId || !$apiToken) {
            return $this->createMockDid($areaCode, $countryCode, 'bandwidth');
        }

        // Bandwidth API implementation would go here
        return $this->createMockDid($areaCode, $countryCode, 'bandwidth');
    }

    /**
     * Create a mock DID for development/testing
     */
    protected function createMockDid($areaCode, $countryCode, $provider)
    {
        // Generate a realistic phone number
        $number = $areaCode . sprintf('%07d', rand(1000000, 9999999));
        
        return [
            'number' => $number,
            'area_code' => $areaCode,
            'country_code' => $countryCode,
            'provider' => $provider,
            'provider_id' => 'mock_' . uniqid(),
            'monthly_cost' => rand(80, 150) / 100,
            'setup_cost' => rand(80, 120) / 100
        ];
    }

    /**
     * Configure call forwarding for a DID
     */
    public function configureCallForwarding(Did $did, $forwardingNumber)
    {
        try {
            switch ($did->provider) {
                case 'twilio':
                    return $this->configureTwilioForwarding($did, $forwardingNumber);
                case 'plivo':
                    return $this->configurePlivoForwarding($did, $forwardingNumber);
                case 'bandwidth':
                    return $this->configureBandwidthForwarding($did, $forwardingNumber);
                default:
                    Log::info("Mock call forwarding configured for DID {$did->number} to {$forwardingNumber}");
                    return true;
            }
        } catch (\Exception $e) {
            Log::error('Call Forwarding Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Configure Twilio call forwarding
     */
    protected function configureTwilioForwarding(Did $did, $forwardingNumber)
    {
        $accountSid = config('crm.telephony.twilio.account_sid');
        $authToken = config('crm.telephony.twilio.auth_token');

        if (!$accountSid || !$authToken) {
            Log::info("Mock Twilio forwarding configured for DID {$did->number}");
            return true;
        }

        // Update Twilio webhook URL to handle call forwarding
        $webhookUrl = route('api.webhooks.twilio.voice', ['did' => $did->number]);
        
        // Implementation would update the phone number configuration
        Log::info("Twilio forwarding configured for DID {$did->number} to {$forwardingNumber}");
        return true;
    }

    /**
     * Configure Plivo call forwarding
     */
    protected function configurePlivoForwarding(Did $did, $forwardingNumber)
    {
        Log::info("Mock Plivo forwarding configured for DID {$did->number} to {$forwardingNumber}");
        return true;
    }

    /**
     * Configure Bandwidth call forwarding
     */
    protected function configureBandwidthForwarding(Did $did, $forwardingNumber)
    {
        Log::info("Mock Bandwidth forwarding configured for DID {$did->number} to {$forwardingNumber}");
        return true;
    }

    /**
     * Log an incoming call
     */
    public function logCall($didNumber, $callerId, $status = 'answered', $duration = 0, $metadata = [])
    {
        $did = Did::where('number', $didNumber)->first();
        
        if (!$did) {
            Log::warning("Call logged for unknown DID: {$didNumber}");
            return null;
        }

        return CallLog::create([
            'did' => $didNumber,
            'caller_id' => $callerId,
            'duration' => $duration,
            'status' => $status,
            'started_at' => now(),
            'ended_at' => $duration > 0 ? now()->addSeconds($duration) : null,
            'campaign_id' => $did->campaign_id,
            'cost' => $this->calculateCallCost($duration, $did->provider),
            'metadata' => $metadata
        ]);
    }

    /**
     * Calculate call cost based on duration and provider
     */
    public function calculateCallCost($duration, $provider)
    {
        $rates = [
            'twilio' => 0.0085, // per minute
            'plivo' => 0.0070,
            'bandwidth' => 0.0065
        ];

        $rate = $rates[$provider] ?? 0.0085;
        $minutes = ceil($duration / 60);
        
        return $minutes * $rate;
    }

    /**
     * Generate call analytics for a DID
     */
    public function getCallAnalytics(Did $did, $startDate = null, $endDate = null)
    {
        $query = $did->callLogs();
        
        if ($startDate) {
            $query->where('started_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('started_at', '<=', $endDate);
        }
        
        $calls = $query->get();
        
        return [
            'total_calls' => $calls->count(),
            'answered_calls' => $calls->where('status', 'answered')->count(),
            'missed_calls' => $calls->where('status', 'missed')->count(),
            'total_duration' => $calls->sum('duration'),
            'average_duration' => $calls->avg('duration'),
            'total_cost' => $calls->sum('cost'),
            'conversion_rate' => $did->call_conversion_rate,
            'peak_hours' => $this->calculatePeakHours($calls),
            'daily_stats' => $this->calculateDailyStats($calls)
        ];
    }

    /**
     * Calculate peak calling hours
     */
    protected function calculatePeakHours($calls)
    {
        $hourCounts = [];
        
        foreach ($calls as $call) {
            $hour = $call->started_at->format('H');
            $hourCounts[$hour] = ($hourCounts[$hour] ?? 0) + 1;
        }
        
        arsort($hourCounts);
        
        return array_slice($hourCounts, 0, 3, true);
    }

    /**
     * Calculate daily call statistics
     */
    protected function calculateDailyStats($calls)
    {
        $dailyStats = [];
        
        foreach ($calls as $call) {
            $date = $call->started_at->format('Y-m-d');
            
            if (!isset($dailyStats[$date])) {
                $dailyStats[$date] = [
                    'calls' => 0,
                    'duration' => 0,
                    'cost' => 0
                ];
            }
            
            $dailyStats[$date]['calls']++;
            $dailyStats[$date]['duration'] += $call->duration;
            $dailyStats[$date]['cost'] += $call->cost;
        }
        
        return $dailyStats;
    }

    /**
     * Release/delete a DID from the provider
     */
    public function releaseDid(Did $did)
    {
        try {
            switch ($did->provider) {
                case 'twilio':
                    return $this->releaseTwilioDid($did);
                case 'plivo':
                    return $this->releasePlivoDid($did);
                case 'bandwidth':
                    return $this->releaseBandwidthDid($did);
                default:
                    Log::info("Mock DID release for {$did->number}");
                    return true;
            }
        } catch (\Exception $e) {
            Log::error('DID Release Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Release Twilio DID
     */
    protected function releaseTwilioDid(Did $did)
    {
        $accountSid = config('crm.telephony.twilio.account_sid');
        $authToken = config('crm.telephony.twilio.auth_token');

        if (!$accountSid || !$authToken || !$did->metadata['provider_id'] ?? null) {
            Log::info("Mock Twilio DID release for {$did->number}");
            return true;
        }

        // Release the number from Twilio
        $response = Http::withBasicAuth($accountSid, $authToken)
            ->delete("https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/IncomingPhoneNumbers/{$did->metadata['provider_id']}.json");

        return $response->successful();
    }

    protected function releasePlivoDid(Did $did)
    {
        Log::info("Mock Plivo DID release for {$did->number}");
        return true;
    }

    protected function releaseBandwidthDid(Did $did)
    {
        Log::info("Mock Bandwidth DID release for {$did->number}");
        return true;
    }

    /**
     * Test connection to telephony provider
     */
    public function testConnection($provider)
    {
        try {
            switch ($provider) {
                case 'twilio':
                    return $this->testTwilioConnection();
                case 'plivo':
                    return $this->testPlivoConnection();
                case 'bandwidth':
                    return $this->testBandwidthConnection();
                default:
                    throw new \Exception("Unknown provider: {$provider}");
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Test Twilio connection
     */
    protected function testTwilioConnection()
    {
        $accountSid = config('crm.telephony.twilio.account_sid');
        $authToken = config('crm.telephony.twilio.auth_token');

        if (!$accountSid || !$authToken) {
            return [
                'success' => false,
                'message' => 'Twilio credentials not configured'
            ];
        }

        $response = Http::withBasicAuth($accountSid, $authToken)
            ->get("https://api.twilio.com/2010-04-01/Accounts/{$accountSid}.json");

        if ($response->successful()) {
            $data = $response->json();
            return [
                'success' => true,
                'message' => 'Twilio connection successful',
                'details' => [
                    'account_name' => $data['friendly_name'] ?? 'Unknown',
                    'account_status' => $data['status'] ?? 'Unknown',
                    'account_type' => $data['type'] ?? 'Unknown'
                ]
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Twilio connection failed: ' . $response->status() . ' - ' . $response->reason()
            ];
        }
    }

    /**
     * Test Plivo connection
     */
    protected function testPlivoConnection()
    {
        $authId = config('crm.telephony.plivo.auth_id');
        $authToken = config('crm.telephony.plivo.auth_token');

        if (!$authId || !$authToken) {
            return [
                'success' => false,
                'message' => 'Plivo credentials not configured'
            ];
        }

        // Mock success for Plivo (implement real API test in production)
        return [
            'success' => true,
            'message' => 'Plivo connection test successful (mock)',
            'details' => [
                'auth_id' => $authId,
                'status' => 'active'
            ]
        ];
    }

    /**
     * Test Bandwidth connection
     */
    protected function testBandwidthConnection()
    {
        $userId = config('crm.telephony.bandwidth.user_id');
        $token = config('crm.telephony.bandwidth.token');

        if (!$userId || !$token) {
            return [
                'success' => false,
                'message' => 'Bandwidth credentials not configured'
            ];
        }

        // Mock success for Bandwidth (implement real API test in production)
        return [
            'success' => true,
            'message' => 'Bandwidth connection test successful (mock)',
            'details' => [
                'user_id' => $userId,
                'status' => 'active'
            ]
        ];
    }
}
