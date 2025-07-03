<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'did',
        'caller_id',
        'duration',
        'status',
        'recording_url',
        'cost',
        'provider_call_id',
        'started_at',
        'ended_at',
        'lead_id',
        'campaign_id',
        'agent_id',
        'metadata'
    ];

    protected $casts = [
        'duration' => 'integer', // in seconds
        'cost' => 'decimal:4',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'metadata' => 'json'
    ];

    // Relationships
    public function did()
    {
        return $this->belongsTo(Did::class, 'did', 'number');
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeMissed($query)
    {
        return $query->where('status', 'missed');
    }

    public function scopeAnswered($query)
    {
        return $query->where('status', 'answered');
    }

    // Accessors
    public function getFormattedDurationAttribute()
    {
        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    public function getWasAnsweredAttribute()
    {
        return in_array($this->status, ['answered', 'completed']);
    }
}
