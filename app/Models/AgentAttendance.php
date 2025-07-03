<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentAttendance extends Model
{
    protected $table = 'agent_attendance';
    
    protected $fillable = [
        'user_id',
        'date',
        'clock_in_time',
        'clock_in_latitude',
        'clock_in_longitude',
        'clock_in_address',
        'clock_in_ip_address',
        'clock_in_distance_meters',
        'clock_out_time',
        'clock_out_latitude',
        'clock_out_longitude',
        'clock_out_address',
        'clock_out_ip_address',
        'clock_out_distance_meters',
        'total_break_minutes',
        'location_verified',
        'location_notes',
        'is_active',
        'notes',
        // Added for compatibility with the new UI
        'login_time',
        'logout_time',
        'latitude',
        'longitude',
        'address',
        'logout_latitude',
        'logout_longitude',
        'logout_address',
        'status'
    ];

    protected $casts = [
        'date' => 'date',
        'clock_in_latitude' => 'decimal:8',
        'clock_in_longitude' => 'decimal:8',
        'clock_out_latitude' => 'decimal:8',
        'clock_out_longitude' => 'decimal:8',
        'clock_in_distance_meters' => 'integer',
        'clock_out_distance_meters' => 'integer',
        'location_verified' => 'boolean',
        'is_active' => 'boolean',
        'total_break_minutes' => 'integer',
        // Added for compatibility with the new UI
        'login_time' => 'datetime',
        'logout_time' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'logout_latitude' => 'decimal:8',
        'logout_longitude' => 'decimal:8'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function breaks()
    {
        // If we have a login_time, use that date for finding breaks
        if ($this->login_time) {
            return $this->hasMany(AgentBreak::class, 'user_id', 'user_id')
                ->whereDate('date', $this->login_time->format('Y-m-d'));
        }
        
        // If we have the legacy date column, use that
        if ($this->date) {
            return $this->hasMany(AgentBreak::class, 'user_id', 'user_id')
                ->where('date', $this->date);
        }
        
        // If all else fails, just return breaks for this user
        return $this->hasMany(AgentBreak::class, 'user_id', 'user_id');
    }

    public function getTotalWorkingHoursAttribute()
    {
        // Try new column names first
        if ($this->login_time && $this->logout_time) {
            $totalMinutes = $this->logout_time->diffInMinutes($this->login_time);
            return max(0, ($totalMinutes - ($this->total_break_minutes ?? 0)) / 60);
        }
        
        // Fall back to old column names
        if ($this->clock_in && $this->clock_out) {
            $clockIn = $this->clock_in instanceof \Carbon\Carbon ? $this->clock_in : \Carbon\Carbon::parse($this->clock_in);
            $clockOut = $this->clock_out instanceof \Carbon\Carbon ? $this->clock_out : \Carbon\Carbon::parse($this->clock_out);
            $totalMinutes = $clockOut->diffInMinutes($clockIn);
            return max(0, ($totalMinutes - ($this->total_break_minutes ?? 0)) / 60);
        }
        
        // Legacy columns (if they exist)
        if ($this->clock_in_time && $this->clock_out_time) {
            $clockIn = strtotime($this->clock_in_time);
            $clockOut = strtotime($this->clock_out_time);
            $totalMinutes = ($clockOut - $clockIn) / 60;
            return max(0, ($totalMinutes - ($this->total_break_minutes ?? 0)) / 60);
        }
        
        return 0;
    }
}
