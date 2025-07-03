<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class AgentBreak extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'start_time',
        'end_time',
        'duration_minutes',
        'break_type',
        'reason'
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'duration_minutes' => 'integer'
    ];
    
    // Ensure we always have a date value from start_time if date is null
    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->date && $model->start_time) {
                $model->date = $model->start_time->format('Y-m-d');
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function calculateDuration()
    {
        if ($this->start_time && $this->end_time) {
            $this->duration_minutes = $this->end_time->diffInMinutes($this->start_time);
            $this->save();
            return $this->duration_minutes;
        }
        return 0;
    }

    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeActive($query)
    {
        return $query->whereNull('end_time');
    }

    public function scopeCompleted($query)
    {
        return $query->whereNotNull('end_time');
    }

    public function getFormattedDurationAttribute()
    {
        if ($this->duration_minutes === null && $this->start_time && $this->end_time) {
            $this->calculateDuration();
        }
        
        if ($this->duration_minutes === null) {
            return '--';
        }
        
        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;
        
        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }
        
        return "{$minutes}m";
    }

    public function getIsActiveAttribute()
    {
        return $this->end_time === null;
    }
}
