<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'event_type',
        'ip_address',
        'user_agent',
        'details',
        'risk_level',
        'suspicious',
        'session_id',
        'location',
        'metadata'
    ];

    protected $casts = [
        'details' => 'array',
        'metadata' => 'array',
        'suspicious' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function log($eventType, $details = [], $riskLevel = 'low', $suspicious = false)
    {
        return static::create([
            'user_id' => auth()->id(),
            'event_type' => $eventType,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'details' => $details,
            'risk_level' => $riskLevel,
            'suspicious' => $suspicious,
            'session_id' => session()->getId(),
            'metadata' => [
                'url' => request()->fullUrl(),
                'method' => request()->method()
            ]
        ]);
    }
}
