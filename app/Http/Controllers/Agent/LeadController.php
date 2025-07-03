<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Campaign;
use App\Services\LeadTrackingService;
use App\Services\ActiveProspectService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class LeadController extends Controller
{
    protected $activeProspectService;

    public function __construct(ActiveProspectService $activeProspectService)
    {
        $this->activeProspectService = $activeProspectService;
    }

    /**
     * Store a new lead with generated tracking data and real-time verification
     */
    public function store(Request $request)
    {
        // Debug: Log the incoming request
        \Log::info('Lead form submission started', [
            'user_id' => Auth::id(),
            'request_data' => $request->all(),
            'method' => $request->method()
        ]);

        // Validate the form data
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'did_number' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:50',
            'zip' => 'nullable|string|max:10',
            'agent_name' => 'required|string|max:255',
            'verifier_name' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            \Log::info('Lead form validation failed', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->all()
            ]);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check for duplicate: same phone and DID combination
        $existingLead = Lead::where('phone', $request->phone)
                           ->where('did_number', $request->did_number)
                           ->first();

        if ($existingLead) {
            return redirect()->back()
                ->with('error', 'Duplicate lead: This phone number has already been submitted with the same DID number.')
                ->withInput();
        }

        // Validate agent and verifier usernames
        $agentUser = \App\Models\User::where('username', $request->agent_name)->whereIn('role', ['admin', 'agent'])->first();
        $verifierUser = \App\Models\User::where('username', $request->verifier_name)->whereIn('role', ['admin', 'agent'])->first();

        if (!$agentUser) {
            return redirect()->back()
                ->with('error', 'Agent username not found in CRM system or invalid role.')
                ->withInput();
        }

        if (!$verifierUser) {
            return redirect()->back()
                ->with('error', 'Verifier username not found in CRM system or invalid role.')
                ->withInput();
        }

        try {
            // Find campaign by DID number
            $campaign = Campaign::where('did', $request->did_number)->first();
            
            if (!$campaign) {
                Log::warning('Campaign not found for DID', [
                    'did_number' => $request->did_number,
                    'user_id' => Auth::id()
                ]);
                return redirect()->back()
                    ->with('error', 'Campaign not found for the specified DID number.')
                    ->withInput();
            }

            // Generate realistic tracking data based on lead location
            $trackingData = LeadTrackingService::generateTrackingData($request->all());
            // Only keep allowed tracking fields
            unset($trackingData['user_agent'], $trackingData['browser_language'], $trackingData['timezone'], $trackingData['cookies_data'], $trackingData['referrer_url'], $trackingData['landing_page_url'], $trackingData['session_token'], $trackingData['visitor_id'], $trackingData['session_id']);
            // Override with real certificates if provided by client
            if ($request->filled('trusted_form_cert_id') && $request->filled('trusted_form_url')) {
                Log::info('Using real TrustedForm certificate', [
                    'cert_id' => $request->trusted_form_cert_id,
                    'cert_url' => $request->trusted_form_url
                ]);
                $trackingData['trusted_form_cert_id'] = $request->trusted_form_cert_id;
                $trackingData['trusted_form_url'] = $request->trusted_form_url;
            }
            
            if ($request->filled('jornaya_lead_id')) {
                Log::info('Using real Jornaya Lead ID', [
                    'jornaya_lead_id' => $request->jornaya_lead_id
                ]);
                $trackingData['jornaya_lead_id'] = $request->jornaya_lead_id;
            }

            // Format names with proper capitalization
            $firstName = $this->formatName($request->first_name);
            $lastName = $this->formatName($request->last_name);
            
            // Convert state to abbreviated form
            $state = $this->convertStateToAbbreviation($request->state);

            // Prepare lead data
            $leadData = array_merge($request->all(), $trackingData, [
                'user_id' => Auth::id(),
                'campaign_id' => $campaign->id, // Set the campaign_id from found campaign
                'first_name' => $firstName,
                'last_name' => $lastName,
                'name' => trim($firstName . ' ' . $lastName),
                'state' => $state,
                'email' => $request->email ?: null, // Convert empty string to null
                'contact_info' => json_encode([
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'address' => $request->address,
                    'city' => $request->city,
                    'state' => $state,
                    'zip' => $request->zip
                ]),
                'status' => 'pending'
            ]);

            // Create the lead
            $lead = Lead::create($leadData);

            Log::info('Lead created successfully', [
                'lead_id' => $lead->id,
                'user_id' => Auth::id(),
                'phone' => $lead->phone,
                'did_number' => $lead->did_number
            ]);

            // Perform real-time lead verification with ActiveProspect
            $this->performLeadVerification($lead);

            return redirect()->route('agent.leads.view')
                ->with('success', 'Lead created successfully with tracking data generated and real-time verification completed!');

        } catch (\Exception $e) {
            Log::error('Failed to create lead', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'input' => $request->all()
            ]);
            return redirect()->back()
                ->with('error', 'Failed to create lead: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Perform comprehensive lead verification using ActiveProspect
     */
    private function performLeadVerification(Lead $lead)
    {
        Log::info('Starting ActiveProspect lead verification', ['lead_id' => $lead->id]);

        try {
            // Prepare lead data for ActiveProspect
            $leadData = [
                'id' => $lead->id,
                'email' => $lead->email,
                'first_name' => $lead->first_name,
                'last_name' => $lead->last_name,
                'phone' => $lead->phone,
                'address' => $lead->address,
                'city' => $lead->city,
                'state' => $lead->state,
                'zip' => $lead->zip,
                'ip_address' => $lead->ip_address,
                'trusted_form_url' => $lead->trusted_form_url,
                'jornaya_lead_id' => $lead->jornaya_lead_id,
                'campaign_name' => $lead->campaign->campaign_name ?? null
            ];

            // 1. Submit lead for comprehensive verification
            $verificationResult = $this->activeProspectService->submitLead($leadData);
            
            if ($verificationResult['success']) {
                Log::info('ActiveProspect lead verification successful', [
                    'lead_id' => $lead->id,
                    'verification_status' => $verificationResult['verification_status']
                ]);

                // Update lead with verification results
                $lead->update([
                    'verification_status' => $verificationResult['verification_status'],
                    'verification_data' => json_encode($verificationResult['data']),
                    'compliance_checks' => json_encode($verificationResult['compliance_checks'] ?? [])
                ]);
            }

            // 2. Verify TrustedForm certificate if available
            if ($lead->trusted_form_url) {
                $trustedFormResult = $this->activeProspectService->verifyTrustedFormCert($lead->trusted_form_url);
                
                if ($trustedFormResult['success']) {
                    Log::info('TrustedForm verification completed', [
                        'lead_id' => $lead->id,
                        'valid' => $trustedFormResult['valid']
                    ]);

                    $lead->update([
                        'trusted_form_verified' => $trustedFormResult['valid'],
                        'trusted_form_verification_data' => json_encode($trustedFormResult['cert_data'] ?? [])
                    ]);
                }
            }

            // 3. Validate Jornaya Lead ID if available
            if ($lead->jornaya_lead_id) {
                $jornayaResult = $this->activeProspectService->validateJornayaLeadId($lead->jornaya_lead_id);
                
                if ($jornayaResult['success']) {
                    Log::info('Jornaya validation completed', [
                        'lead_id' => $lead->id,
                        'valid' => $jornayaResult['valid']
                    ]);

                    $lead->update([
                        'jornaya_verified' => $jornayaResult['valid'],
                        'jornaya_verification_data' => json_encode($jornayaResult['lead_data'] ?? [])
                    ]);
                }
            }

            // 4. Get lead quality score
            $scoreResult = $this->activeProspectService->getLeadScore($leadData);
            
            if ($scoreResult['success']) {
                Log::info('Lead scoring completed', [
                    'lead_id' => $lead->id,
                    'score' => $scoreResult['score'],
                    'quality' => $scoreResult['quality']
                ]);

                $lead->update([
                    'quality_score' => $scoreResult['score'],
                    'quality_rating' => $scoreResult['quality'],
                    'risk_factors' => json_encode($scoreResult['risk_factors'] ?? []),
                    'quality_recommendations' => json_encode($scoreResult['recommendations'] ?? [])
                ]);
            }

            // 5. Check suppression lists
            $suppressionResult = $this->activeProspectService->checkSuppression($lead->phone, $lead->email);
            
            if ($suppressionResult['success']) {
                Log::info('Suppression check completed', [
                    'lead_id' => $lead->id,
                    'suppressed' => $suppressionResult['suppressed']
                ]);

                $lead->update([
                    'is_suppressed' => $suppressionResult['suppressed'],
                    'suppression_lists' => json_encode($suppressionResult['lists'] ?? []),
                    'suppression_reason' => $suppressionResult['reason']
                ]);

                // If suppressed, update status
                if ($suppressionResult['suppressed']) {
                    $lead->update(['status' => 'suppressed']);
                }
            }

            // 6. Enhance lead data
            $enhancementResult = $this->activeProspectService->enhanceLeadData($leadData);
            
            if ($enhancementResult['success']) {
                Log::info('Lead enhancement completed', [
                    'lead_id' => $lead->id,
                    'confidence' => $enhancementResult['confidence']
                ]);

                $lead->update([
                    'enhanced_data' => json_encode($enhancementResult['enhanced_lead'] ?? []),
                    'data_enhancements' => json_encode($enhancementResult['enhancements'] ?? []),
                    'enhancement_confidence' => $enhancementResult['confidence']
                ]);
            }

            // Set final status based on all verification results
            $finalStatus = $this->determineFinalLeadStatus($lead);
            $lead->update(['status' => $finalStatus]);

            Log::info('ActiveProspect verification process completed', [
                'lead_id' => $lead->id,
                'final_status' => $finalStatus
            ]);

        } catch (\Exception $e) {
            Log::error('ActiveProspect verification failed', [
                'lead_id' => $lead->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Continue processing even if verification fails
            $lead->update([
                'verification_status' => 'error',
                'verification_error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Determine final lead status based on verification results
     */
    private function determineFinalLeadStatus(Lead $lead)
    {
        // If suppressed, status is already set to 'suppressed'
        if ($lead->is_suppressed) {
            return 'suppressed';
        }

        // Check quality score
        $qualityScore = $lead->quality_score ?? 0;
        
        if ($qualityScore >= 80) {
            return 'verified-high-quality';
        } elseif ($qualityScore >= 60) {
            return 'verified-medium-quality';
        } elseif ($qualityScore >= 40) {
            return 'verified-low-quality';
        } elseif ($lead->verification_status === 'error') {
            return 'verification-failed';
        } else {
            return 'verified';
        }
    }

    /**
     * Format name with proper capitalization
     */
    private function formatName($name)
    {
        return ucwords(strtolower(trim($name)));
    }

    /**
     * Convert state name to abbreviation
     */
    private function convertStateToAbbreviation($state)
    {
        if (!$state) return null;
        
        $states = [
            'alabama' => 'AL', 'alaska' => 'AK', 'arizona' => 'AZ', 'arkansas' => 'AR',
            'california' => 'CA', 'colorado' => 'CO', 'connecticut' => 'CT', 'delaware' => 'DE',
            'florida' => 'FL', 'georgia' => 'GA', 'hawaii' => 'HI', 'idaho' => 'ID',
            'illinois' => 'IL', 'indiana' => 'IN', 'iowa' => 'IA', 'kansas' => 'KS',
            'kentucky' => 'KY', 'louisiana' => 'LA', 'maine' => 'ME', 'maryland' => 'MD',
            'massachusetts' => 'MA', 'michigan' => 'MI', 'minnesota' => 'MN', 'mississippi' => 'MS',
            'missouri' => 'MO', 'montana' => 'MT', 'nebraska' => 'NE', 'nevada' => 'NV',
            'new hampshire' => 'NH', 'new jersey' => 'NJ', 'new mexico' => 'NM', 'new york' => 'NY',
            'north carolina' => 'NC', 'north dakota' => 'ND', 'ohio' => 'OH', 'oklahoma' => 'OK',
            'oregon' => 'OR', 'pennsylvania' => 'PA', 'rhode island' => 'RI', 'south carolina' => 'SC',
            'south dakota' => 'SD', 'tennessee' => 'TN', 'texas' => 'TX', 'utah' => 'UT',
            'vermont' => 'VT', 'virginia' => 'VA', 'washington' => 'WA', 'west virginia' => 'WV',
            'wisconsin' => 'WI', 'wyoming' => 'WY'
        ];

        $stateLower = strtolower(trim($state));
        return $states[$stateLower] ?? strtoupper($state);
    }

    /**
     * Show lead details with tracking information
     */
    public function show(Lead $lead)
    {
        // Ensure agent can only view their own leads
        if ($lead->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this lead.');
        }

        return view('agent.lead_details', compact('lead'));
    }

    /**
     * Export lead tracking data for buyer verification
     */
    public function exportTrackingData(Lead $lead)
    {
        // Ensure agent can only export their own leads
        if ($lead->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this lead.');
        }

        $trackingData = [
            'lead_id' => $lead->id,
            'customer_info' => [
                'name' => $lead->name,
                'phone' => $lead->phone,
                'email' => $lead->email,
                'address' => [
                    'street' => $lead->address,
                    'city' => $lead->city,
                    'state' => $lead->state,
                    'zip' => $lead->zip
                ]
            ],
            'verification_data' => [
                'ip_address' => $lead->ip_address,
                'jornaya_lead_id' => $lead->jornaya_lead_id,
                'trusted_form_cert_id' => $lead->trusted_form_cert_id,
                'trusted_form_url' => $lead->trusted_form_url,
                'device_fingerprint' => $lead->device_fingerprint,
                'lead_submitted_at' => $lead->lead_submitted_at
            ],
            'generated_at' => now()->toISOString()
        ];

        $filename = "lead_{$lead->id}_tracking_data_" . date('Y-m-d_H-i-s') . ".json";
        
        return response()->json($trackingData, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => "attachment; filename=\"{$filename}\""
        ]);
    }

    /**
     * Show the form for editing the specified lead.
     */
    public function edit(Lead $lead)
    {
        // Ensure agent can only edit their own leads
        if ($lead->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this lead.');
        }

        // Check if lead can still be edited (within 30 minutes of creation)
        if (!$lead->canBeEdited()) {
            return redirect()->route('agent.leads.view')
                ->with('error', 'This lead cannot be edited as it was created more than 30 minutes ago.');
        }

        $activeCampaigns = Campaign::where('status', 'active')->get();
        return view('agent.edit_lead', compact('lead', 'activeCampaigns'));
    }

    /**
     * Update the specified lead in storage.
     */
    public function update(Request $request, Lead $lead)
    {
        // Ensure agent can only update their own leads
        if ($lead->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this lead.');
        }

        // Check if lead can still be edited (within 30 minutes of creation)
        if (!$lead->canBeEdited()) {
            return redirect()->route('agent.leads.view')
                ->with('error', 'This lead cannot be updated as it was created more than 30 minutes ago.');
        }

        // Validate the form data
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'did_number' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:50',
            'zip' => 'nullable|string|max:10',
            'campaign_id' => 'nullable|exists:campaigns,id',
            'agent_name' => 'required|string|max:255',
            'verifier_name' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            \Log::info('Lead form validation failed', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->all()
            ]);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check for duplicate: same phone and DID combination (excluding current lead)
        $existingLead = Lead::where('phone', $request->phone)
                           ->where('did_number', $request->did_number)
                           ->where('id', '!=', $lead->id)
                           ->first();

        if ($existingLead) {
            return redirect()->back()
                ->with('error', 'Duplicate lead: This phone number has already been submitted with the same DID number.')
                ->withInput();
        }

        // Validate agent and verifier usernames
        $agentUser = \App\Models\User::where('username', $request->agent_name)->whereIn('role', ['admin', 'agent'])->first();
        $verifierUser = \App\Models\User::where('username', $request->verifier_name)->whereIn('role', ['admin', 'agent'])->first();

        if (!$agentUser) {
            return redirect()->back()
                ->with('error', 'Agent username not found in CRM system or invalid role.')
                ->withInput();
        }

        if (!$verifierUser) {
            return redirect()->back()
                ->with('error', 'Verifier username not found in CRM system or invalid role.')
                ->withInput();
        }

        try {
            // Format names with proper capitalization
            $firstName = $this->formatName($request->first_name);
            $lastName = $this->formatName($request->last_name);
            
            // Convert state to abbreviated form
            $state = $this->convertStateToAbbreviation($request->state);

            // Update lead data
            $leadData = array_merge($request->all(), [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'name' => trim($firstName . ' ' . $lastName),
                'state' => $state,
                'email' => $request->email ?: null, // Convert empty string to null
                'contact_info' => json_encode([
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'address' => $request->address,
                    'city' => $request->city,
                    'state' => $state,
                    'zip' => $request->zip
                ])
            ]);

            $lead->update($leadData);

            return redirect()->route('agent.leads.view')
                ->with('success', 'Lead updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update lead: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified lead from storage.
     */
    public function destroy(Lead $lead)
    {
        // Ensure agent can only delete their own leads
        if ($lead->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this lead.');
        }

        try {
            $lead->delete();
            
            return redirect()->route('agent.leads.view')
                ->with('success', 'Lead deleted successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete lead: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new lead.
     */
    public function create()
    {
        $activeCampaigns = Campaign::where('status', 'active')->get();
        return view('agent.add_lead', compact('activeCampaigns'));
    }

    /**
     * Get campaign name by DID number
     */
    public function getCampaignByDid(Request $request)
    {
        $didNumber = $request->get('did');
        $campaign = \App\Models\Campaign::where('did', $didNumber)->where('status', 'active')->first();
        
        return response()->json([
            'campaign_name' => $campaign ? $campaign->campaign_name : null,
            'campaign_id' => $campaign ? $campaign->id : null
        ]);
    }

    /**
     * Get user suggestions for agent/verifier fields (usernames only, Admin/Agent roles)
     */
    public function getUserSuggestions(Request $request)
    {
        $query = $request->get('query');
        $users = \App\Models\User::where('username', 'LIKE', "%{$query}%")
                                 ->whereIn('role', ['admin', 'agent'])
                                 ->whereNotNull('username')
                                 ->limit(10)
                                 ->pluck('username');
        
        return response()->json($users);
    }
}
