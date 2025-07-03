<?php

namespace App\Observers;

use App\Models\Lead;
use App\Models\User;
use App\Mail\LeadNotification;
use Illuminate\Support\Facades\Mail;

class LeadObserver
{    /**
     * Handle the Lead "created" event.
     */
    public function created(Lead $lead): void
    {        // Queue notification processing
        \App\Jobs\ProcessLeadNotification::dispatch($lead, 'new');
        
        // Load the campaign relationship if not already loaded
        if (!$lead->relationLoaded('campaign')) {
            $lead->load('campaign');
        }
        
        // Log the lead creation
        \Log::info('New lead created', [
            'lead_id' => $lead->id,
            'campaign' => $lead->campaign ? $lead->campaign->campaign_name : 'No Campaign',
            'full_name' => $lead->full_name,
            'email' => $lead->email,
            'phone' => $lead->phone
        ]);
    }    /**
     * Handle the Lead "updated" event.
     */
    public function updated(Lead $lead): void
    {
        // Check if status was changed
        if ($lead->wasChanged('status')) {
            $oldStatus = $lead->getOriginal('status');
            $newStatus = $lead->status;
            
            // Queue notification for status changes
            if ($newStatus === 'approved' && $oldStatus !== 'approved') {
                \App\Jobs\ProcessLeadNotification::dispatch($lead, 'approved');
            } elseif ($newStatus === 'rejected' && $oldStatus !== 'rejected') {
                \App\Jobs\ProcessLeadNotification::dispatch($lead, 'rejected');
            }
            
            // Log status change
            \Log::info('Lead status changed', [
                'lead_id' => $lead->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'full_name' => $lead->full_name
            ]);
        }
    }

    /**
     * Handle the Lead "deleted" event.
     */    public function deleted(Lead $lead): void
    {
        // Load the campaign relationship if not already loaded
        if (!$lead->relationLoaded('campaign')) {
            $lead->load('campaign');
        }
        
        \Log::info('Lead deleted', [
            'lead_id' => $lead->id,
            'full_name' => $lead->full_name,
            'campaign' => $lead->campaign ? $lead->campaign->campaign_name : 'No Campaign'
        ]);
    }
}
