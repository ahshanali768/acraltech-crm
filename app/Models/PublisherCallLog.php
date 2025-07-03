<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PublisherCallLog extends Model
{
    use HasFactory;

    protected $table = 'publisher_call_logs';

    protected $fillable = [
        'publisher_id',
        'publisher_did',
        'destination_did',
        'caller_number',
        'called_number',
        'duration',
        'status',
        'cost',
        'provider_call_id',
        'recording_url',
        'call_started_at',
        'call_ended_at',
        'call_source',
        'caller_city',
        'caller_state',
        'caller_country',
        'call_quality',
        'is_billable',
        'call_metadata'
    ];

    protected $casts = [
        'cost' => 'decimal:4',
        'call_started_at' => 'datetime',
        'call_ended_at' => 'datetime',
        'is_billable' => 'boolean',
        'call_metadata' => 'json'
    ];

    // Relationships
    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function publisherDid()
    {
        return $this->belongsTo(PublisherDid::class, 'publisher_did', 'number');
    }

    public function lead()
    {
        return $this->hasOne(PublisherLead::class, 'call_log_id');
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeBillable($query)
    {
        return $query->where('is_billable', true);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }

    public function scopeByPublisher($query, $publisherId)
    {
        return $query->where('publisher_id', $publisherId);
    }

    // Accessors
    public function getFormattedDurationAttribute()
    {
        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;
        return sprintf('%d:%02d', $minutes, $seconds);
    }

    public function getFormattedCostAttribute()
    {
        return '$' . number_format($this->cost, 2);
    }

    public function getCallDateAttribute()
    {
        return $this->call_started_at->format('M d, Y g:i A');
    }

    // Helper methods
    public static function getTotalCallsForPublisher($publisherId, $startDate = null, $endDate = null)
    {
        $query = self::where('publisher_id', $publisherId);
        
        if ($startDate) {
            $query->whereDate('call_started_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->whereDate('call_started_at', '<=', $endDate);
        }
        
        return $query->count();
    }

    public static function getTotalEarningsForPublisher($publisherId, $startDate = null, $endDate = null)
    {
        $query = self::where('publisher_id', $publisherId)
                    ->where('is_billable', true);
        
        if ($startDate) {
            $query->whereDate('call_started_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->whereDate('call_started_at', '<=', $endDate);
        }
        
        return $query->sum('cost');
    }

    public static function getCallTrendsForPublisher($publisherId, $days = 7)
    {
        $trends = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = self::where('publisher_id', $publisherId)
                        ->whereDate('call_started_at', $date)
                        ->count();
            
            $trends[] = [
                'date' => $date->format('M d'),
                'calls' => $count
            ];
        }
        return $trends;
    }
}
