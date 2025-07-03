<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    protected $fillable = [
        'name',
        'key',
        'secret',
        'user_id',
        'permissions',
        'allowed_ips',
        'rate_limit',
        'requests_used',
        'last_used_at',
        'expires_at',
        'is_active'
    ];

    protected $casts = [
        'permissions' => 'array',
        'allowed_ips' => 'array',
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    protected $hidden = ['secret'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generate($name, $userId, $permissions = [], $expiresAt = null)
    {
        return static::create([
            'name' => $name,
            'key' => 'crm_' . Str::random(32),
            'secret' => Str::random(32),
            'user_id' => $userId,
            'permissions' => $permissions,
            'expires_at' => $expiresAt,
            'is_active' => true
        ]);
    }

    public function incrementUsage()
    {
        $this->increment('requests_used');
        $this->update(['last_used_at' => now()]);
    }

    public function isValid()
    {
        return $this->is_active && 
               (!$this->expires_at || $this->expires_at->isFuture()) &&
               $this->requests_used < $this->rate_limit;
    }
}
