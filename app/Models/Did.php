<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Did extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'area_code',
        'country_code',
        'provider',
        'status',
        'type',
        'monthly_cost',
        'setup_cost',
        'campaign_id',
        'purchased_at',
        'expires_at',
        'routing_destination',
        'call_forwarding_enabled',
        'recording_enabled',
        'analytics_enabled',
        'metadata'
    ];

    protected $casts = [
        'monthly_cost' => 'decimal:2',
        'setup_cost' => 'decimal:2',
        'purchased_at' => 'datetime',
        'expires_at' => 'datetime',
        'call_forwarding_enabled' => 'boolean',
        'recording_enabled' => 'boolean',
        'analytics_enabled' => 'boolean',
        'metadata' => 'json'
    ];

    // Relationships
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class, 'did', 'number');
    }

    public function callLogs()
    {
        return $this->hasMany(CallLog::class, 'did', 'number');
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByAreaCode($query, $areaCode)
    {
        return $query->where('area_code', $areaCode);
    }

    public function scopeByCountry($query, $countryCode)
    {
        return $query->where('country_code', $countryCode);
    }

    // Accessors
    public function getFormattedNumberAttribute()
    {
        return $this->formatPhoneNumber($this->number);
    }

    public function getIsExpiredAttribute()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getIsExpiringAttribute()
    {
        return $this->expires_at && $this->expires_at->diffInDays(now()) <= 30;
    }

    public function getTotalCallsAttribute()
    {
        return $this->callLogs()->count();
    }

    public function getCallConversionRateAttribute()
    {
        $totalCalls = $this->getTotalCallsAttribute();
        $convertedCalls = $this->leads()->where('status', 'approved')->count();
        
        return $totalCalls > 0 ? round(($convertedCalls / $totalCalls) * 100, 2) : 0;
    }

    // Methods
    public function assignToCampaign($campaignId)
    {
        $this->update([
            'campaign_id' => $campaignId,
            'status' => 'active'
        ]);
    }

    public function release()
    {
        $this->update([
            'campaign_id' => null,
            'status' => 'available'
        ]);
    }

    public function markAsExpired()
    {
        $this->update(['status' => 'expired']);
    }

    private function formatPhoneNumber($number)
    {
        // Format phone number based on country code
        if ($this->country_code === 'US' || $this->country_code === 'CA') {
            return preg_replace('/(\d{3})(\d{3})(\d{4})/', '+1 ($1) $2-$3', $number);
        }
        
        return $number;
    }

    // Static methods for DID generation
    public static function generateDid($areaCode, $countryCode = 'US', $provider = 'twilio')
    {
        $telephonyService = app(\App\Services\TelephonyService::class);
        $didData = $telephonyService->purchaseDid($areaCode, $countryCode, $provider);
        
        return self::create([
            'number' => $didData['number'],
            'area_code' => $didData['area_code'],
            'country_code' => $didData['country_code'],
            'provider' => $didData['provider'],
            'status' => 'available',
            'type' => 'local',
            'monthly_cost' => $didData['monthly_cost'],
            'setup_cost' => $didData['setup_cost'],
            'purchased_at' => now(),
            'expires_at' => now()->addYear(),
            'metadata' => [
                'provider_id' => $didData['provider_id'] ?? null
            ]
        ]);
    }

    public static function bulkGenerateDids($areaCode, $count = 10, $countryCode = 'US')
    {
        $dids = [];
        for ($i = 0; $i < $count; $i++) {
            $dids[] = self::generateDid($areaCode, $countryCode);
        }
        return $dids;
    }
}
