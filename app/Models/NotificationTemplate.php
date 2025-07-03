<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    protected $fillable = [
        'trigger_event',
        'channel',
        'subject',
        'content',
        'variables',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'variables' => 'array'
    ];

    public function setting()
    {
        return $this->belongsTo(NotificationSetting::class, 'trigger_event', 'trigger_event');
    }
}
