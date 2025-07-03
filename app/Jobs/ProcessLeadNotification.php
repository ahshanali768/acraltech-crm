<?php

namespace App\Jobs;

use App\Models\Lead;
use App\Models\User;
use App\Mail\LeadNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ProcessLeadNotification implements ShouldQueue
{
    use Queueable;

    /**
     * The lead instance.
     */
    public Lead $lead;

    /**
     * The notification type.
     */
    public string $type;

    /**
     * Create a new job instance.
     */
    public function __construct(Lead $lead, string $type = 'new')
    {
        $this->lead = $lead;
        $this->type = $type;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Load the lead with relationships
            $this->lead->load(['campaign', 'agent']);

            // Determine recipients based on notification type
            $recipients = $this->getRecipients();

            foreach ($recipients as $recipient) {
                Mail::to($recipient->email)
                    ->send(new LeadNotification($this->lead, $this->type));
                
                Log::info('Lead notification sent', [
                    'lead_id' => $this->lead->id,
                    'type' => $this->type,
                    'recipient' => $recipient->email
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Failed to process lead notification', [
                'lead_id' => $this->lead->id,
                'type' => $this->type,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Get the recipients for the notification.
     */
    private function getRecipients(): \Illuminate\Database\Eloquent\Collection
    {
        switch ($this->type) {
            case 'new':
                return User::where('role', 'admin')->get();
            case 'approved':
            case 'rejected':
                // Try to find user by agent_name, otherwise send to admins
                if ($this->lead->agent_name) {
                    $agent = User::where('name', $this->lead->agent_name)->first();
                    if ($agent) {
                        return User::where('id', $agent->id)->get();
                    }
                }
                return User::where('role', 'admin')->get();
            default:
                return User::whereRaw('1 = 0')->get(); // Empty Eloquent collection
        }
    }
}
