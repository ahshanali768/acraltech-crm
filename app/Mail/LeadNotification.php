<?php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeadNotification extends Mailable
{
    use SerializesModels;

    public function __construct(
        public Lead $lead,
        public string $type = 'new'
    ) {}

    public function envelope(): Envelope
    {
        $subject = match($this->type) {
            'new' => 'New Lead Received - ' . $this->lead->campaign->name,
            'approved' => 'Lead Approved - ' . $this->lead->full_name,
            'rejected' => 'Lead Rejected - ' . $this->lead->full_name,
            default => 'Lead Update - ' . $this->lead->full_name
        };

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.lead-notification',
            with: [
                'lead' => $this->lead,
                'type' => $this->type
            ]
        );
    }
}
