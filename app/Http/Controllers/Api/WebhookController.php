<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TelephonyService;
use App\Models\Did;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    protected $telephonyService;

    public function __construct(TelephonyService $telephonyService)
    {
        $this->telephonyService = $telephonyService;
    }

    /**
     * Handle Twilio voice webhook
     */
    public function twilioVoice(Request $request, $did = null)
    {
        Log::info('Twilio Voice Webhook', $request->all());

        $didNumber = $did ?? $request->input('To');
        $callerId = $request->input('From');
        $callSid = $request->input('CallSid');

        // Log the incoming call
        $callLog = $this->telephonyService->logCall(
            $didNumber,
            $callerId,
            'answered',
            0,
            [
                'call_sid' => $callSid,
                'provider' => 'twilio',
                'direction' => 'inbound'
            ]
        );

        // Get DID configuration
        $didModel = Did::where('number', $didNumber)->first();
        
        if (!$didModel || !$didModel->call_forwarding_enabled) {
            // No forwarding, just record the call
            return response('<?xml version="1.0" encoding="UTF-8"?>
                <Response>
                    <Say>Thank you for calling. Please hold while we connect you.</Say>
                    <Hangup/>
                </Response>', 200)
                ->header('Content-Type', 'application/xml');
        }

        // Forward the call if routing destination is configured
        if ($didModel->routing_destination) {
            return response('<?xml version="1.0" encoding="UTF-8"?>
                <Response>
                    <Say>Please hold while we connect your call.</Say>
                    <Dial>' . $didModel->routing_destination . '</Dial>
                </Response>', 200)
                ->header('Content-Type', 'application/xml');
        }

        // Default response
        return response('<?xml version="1.0" encoding="UTF-8"?>
            <Response>
                <Say>Thank you for your interest. Someone will contact you shortly.</Say>
                <Hangup/>
            </Response>', 200)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Handle Twilio call status webhook
     */
    public function twilioStatus(Request $request)
    {
        Log::info('Twilio Status Webhook', $request->all());

        $callSid = $request->input('CallSid');
        $callStatus = $request->input('CallStatus');
        $duration = $request->input('CallDuration', 0);

        // Update call log with final status
        $callLog = \App\Models\CallLog::where('metadata->call_sid', $callSid)->first();
        
        if ($callLog) {
            $callLog->update([
                'status' => $this->mapTwilioStatus($callStatus),
                'duration' => $duration,
                'ended_at' => now(),
                'cost' => $this->telephonyService->calculateCallCost($duration, 'twilio')
            ]);

            // If call was answered and duration > 30 seconds, create a lead
            if ($duration > 30 && in_array($callStatus, ['completed', 'in-progress'])) {
                $this->createLeadFromCall($callLog);
            }
        }

        return response('OK', 200);
    }

    /**
     * Handle Plivo voice webhook
     */
    public function plivoVoice(Request $request, $did = null)
    {
        Log::info('Plivo Voice Webhook', $request->all());

        $didNumber = $did ?? $request->input('To');
        $callerId = $request->input('From');
        $callUuid = $request->input('CallUUID');

        // Log the incoming call
        $this->telephonyService->logCall(
            $didNumber,
            $callerId,
            'answered',
            0,
            [
                'call_uuid' => $callUuid,
                'provider' => 'plivo',
                'direction' => 'inbound'
            ]
        );

        // Get DID configuration
        $didModel = Did::where('number', $didNumber)->first();
        
        // Return Plivo XML response
        $response = '<?xml version="1.0" encoding="UTF-8"?>
            <Response>
                <Speak>Thank you for calling. Please hold while we connect you.</Speak>';
        
        if ($didModel && $didModel->call_forwarding_enabled && $didModel->routing_destination) {
            $response .= '<Dial>' . $didModel->routing_destination . '</Dial>';
        } else {
            $response .= '<Hangup/>';
        }
        
        $response .= '</Response>';

        return response($response, 200)->header('Content-Type', 'application/xml');
    }

    /**
     * Handle Bandwidth voice webhook
     */
    public function bandwidthVoice(Request $request, $did = null)
    {
        Log::info('Bandwidth Voice Webhook', $request->all());

        // Bandwidth webhook implementation
        return response('OK', 200);
    }

    /**
     * Map Twilio call status to our internal status
     */
    protected function mapTwilioStatus($twilioStatus)
    {
        $statusMap = [
            'completed' => 'completed',
            'busy' => 'busy',
            'no-answer' => 'missed',
            'failed' => 'failed',
            'canceled' => 'missed'
        ];

        return $statusMap[$twilioStatus] ?? 'answered';
    }

    /**
     * Create a lead from a successful call
     */
    protected function createLeadFromCall($callLog)
    {
        if ($callLog->lead_id || !$callLog->campaign_id) {
            return; // Lead already exists or no campaign assigned
        }

        try {
            $lead = Lead::create([
                'campaign_id' => $callLog->campaign_id,
                'first_name' => 'Unknown',
                'last_name' => 'Caller',
                'phone' => $callLog->caller_id,
                'email' => null,
                'address' => null,
                'city' => null,
                'state' => null,
                'zip' => null,
                'notes' => 'Lead generated from inbound call',
                'status' => 'pending',
                'agent' => 'System',
                'verifier' => 'System',
                'submitted_at' => $callLog->started_at,
                'did' => $callLog->did,
                'external_id' => 'call_' . $callLog->id,
                'metadata' => [
                    'source' => 'inbound_call',
                    'call_duration' => $callLog->duration,
                    'call_log_id' => $callLog->id
                ]
            ]);

            // Update call log with lead reference
            $callLog->update(['lead_id' => $lead->id]);

            Log::info("Lead {$lead->id} created from call {$callLog->id}");
        } catch (\Exception $e) {
            Log::error('Failed to create lead from call: ' . $e->getMessage());
        }
    }
}
