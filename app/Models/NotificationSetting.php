<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    protected $fillable = [
        'trigger_event',
        'email_enabled',
        'sms_enabled', 
        'push_enabled',
        'in_app_enabled',
        'recipients',
        'frequency',
        'is_active',
        'description'
    ];

    protected $casts = [
        'email_enabled' => 'boolean',
        'sms_enabled' => 'boolean',
        'push_enabled' => 'boolean',
        'in_app_enabled' => 'boolean',
        'is_active' => 'boolean',
        'recipients' => 'array'
    ];

    public function templates()
    {
        return $this->hasMany(NotificationTemplate::class, 'trigger_event', 'trigger_event');
    }

    public function logs()
    {
        return $this->hasMany(NotificationLog::class, 'trigger_event', 'trigger_event');
    }
}
