<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    protected $fillable = [
        'trigger_event',
        'channel',
        'recipient_user_id',
        'recipient_email',
        'recipient_phone',
        'subject',
        'content',
        'status',
        'error_message',
        'sent_at',
        'delivered_at',
        'read_at',
        'metadata'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
        'metadata' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'recipient_user_id');
    }

    public function setting()
    {
        return $this->belongsTo(NotificationSetting::class, 'trigger_event', 'trigger_event');
    }
}
