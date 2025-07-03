<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'status',
        'payout_rate', // $ per call or % of call value
        'payout_type', // 'per_call' or 'percentage'
        'tracking_did', // The DID assigned to this publisher
        'destination_did', // The main buyer DID where calls forward to
        'notes',
        'metadata'
    ];

    protected $casts = [
        'payout_rate' => 'decimal:2',
        'metadata' => 'json'
    ];

    // Relationships
    public function dids()
    {
        return $this->hasMany(PublisherDid::class);
    }

    public function callLogs()
    {
        return $this->hasMany(PublisherCallLog::class);
    }

    public function leads()
    {
        return $this->hasMany(PublisherLead::class);
    }

    // Legacy relationship for backward compatibility - will be removed
    public function did()
    {
        return $this->belongsTo(Did::class, 'tracking_did', 'number');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    // Accessors & Calculated Fields
    public function getTotalCallsAttribute()
    {
        return $this->callLogs()->count();
    }

    public function getTotalCallsToday()
    {
        return $this->callLogs()->today()->count();
    }

    public function getTotalCallsThisMonth()
    {
        return $this->callLogs()->thisMonth()->count();
    }

    public function getConversionRateAttribute()
    {
        $totalCalls = $this->getTotalCallsAttribute();
        $convertedLeads = $this->leads()->where('status', 'converted')->count();
        
        return $totalCalls > 0 ? round(($convertedLeads / $totalCalls) * 100, 2) : 0;
    }

    public function getTotalEarningsAttribute()
    {
        if ($this->payout_type === 'per_call') {
            $convertedLeads = $this->leads()->where('status', 'converted')->count();
            return $convertedLeads * $this->payout_rate;
        } else {
            // Percentage based - using estimated_value from leads
            return $this->leads()->where('status', 'converted')->sum('estimated_value') * ($this->payout_rate / 100);
        }
    }

    public function getEarningsThisMonth()
    {
        if ($this->payout_type === 'per_call') {
            $convertedLeads = $this->leads()
                ->where('status', 'converted')
                ->thisMonth()
                ->count();
            return $convertedLeads * $this->payout_rate;
        } else {
            return $this->leads()
                ->where('status', 'converted')
                ->thisMonth()
                ->sum('estimated_value') * ($this->payout_rate / 100);
        }
    }

    public function getFormattedTrackingDidAttribute()
    {
        return $this->formatPhoneNumber($this->tracking_did);
    }

    public function getFormattedDestinationDidAttribute()
    {
        return $this->formatPhoneNumber($this->destination_did);
    }

    // Methods
    public function assignTrackingDid($didNumber, $destinationDid)
    {
        // Create a new PublisherDid entry
        $publisherDid = PublisherDid::create([
            'publisher_id' => $this->id,
            'number' => $didNumber,
            'area_code' => substr($didNumber, 0, 3),
            'destination_number' => $destinationDid,
            'status' => 'assigned',
            'monthly_cost' => 2.00, // Default cost
            'setup_cost' => 1.00,
            'assigned_at' => now(),
            'expires_at' => now()->addYear(),
            'did_metadata' => [
                'assigned_at' => now(),
                'assigned_by' => auth()->id() ?? null
            ]
        ]);

        // Update the publisher's tracking_did field for backward compatibility
        $this->update([
            'tracking_did' => $didNumber,
            'destination_did' => $destinationDid
        ]);

        return $publisherDid;
    }

    public function activate()
    {
        $this->update(['status' => 'active']);
    }

    public function deactivate()
    {
        $this->update(['status' => 'inactive']);
    }

    private function formatPhoneNumber($number)
    {
        if (empty($number)) return '';
        return preg_replace('/(\d{3})(\d{3})(\d{4})/', '+1 ($1) $2-$3', $number);
    }

    // Static methods
    public static function createWithTrackingDid($publisherData, $didNumber, $destinationDid)
    {
        $publisher = self::create($publisherData);
        $publisher->assignTrackingDid($didNumber, $destinationDid);
        return $publisher;
    }

    // Helper method to get primary DID
    public function getPrimaryDid()
    {
        return $this->dids()->where('status', 'assigned')->first();
    }

    // Helper method to get all active DIDs
    public function getActiveDids()
    {
        return $this->dids()->where('status', 'assigned')->get();
    }
}
