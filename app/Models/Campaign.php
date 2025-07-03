<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;
    protected $fillable = [
        'campaign_name',
        'vertical',
        'commission_inr',
        'did',
        'payout_usd',
        'status',
    ];

    protected $casts = [
        'commission_inr' => 'decimal:2',
        'payout_usd' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getConversionRateAttribute()
    {
        $totalLeads = $this->leads()->count();
        $approvedLeads = $this->leads()->where('status', 'approved')->count();
        
        return $totalLeads > 0 ? round(($approvedLeads / $totalLeads) * 100, 2) : 0;
    }

    public function getTotalEarningsAttribute()
    {
        $approvedLeads = $this->leads()->where('status', 'approved')->count();
        return $approvedLeads * $this->payout_usd;
    }

    public function getNameAttribute()
    {
        return $this->campaign_name;
    }
}
