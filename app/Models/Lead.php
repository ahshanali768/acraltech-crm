<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'campaign_id',
        'name',
        'contact_info',
        'first_name',
        'last_name',
        'phone',
        'did_number',
        'address',
        'city',
        'state',
        'zip',
        'email',
        'agent_name',
        'verifier_name',
        'notes',
        'status',
        'external_id',
        'external_crm_id',
        'crm_sync_status',
        'crm_sync_error',
        'last_crm_sync',
        'last_crm_sync_attempt',
        // Tracking fields
        'ip_address',
        'jornaya_lead_id',
        'trusted_form_cert_id',
        'trusted_form_url',
        'device_fingerprint',
        'lead_submitted_at',
        // ActiveProspect verification fields
        'verification_status',
        'verification_data',
        'compliance_checks',
        'verification_error',
        'trusted_form_verified',
        'trusted_form_verification_data',
        'jornaya_verified',
        'jornaya_verification_data',
        'quality_score',
        'quality_rating',
        'risk_factors',
        'quality_recommendations',
        'is_suppressed',
        'suppression_lists',
        'suppression_reason',
        'enhanced_data',
        'data_enhancements',
        'enhancement_confidence'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'last_crm_sync' => 'datetime',
        'last_crm_sync_attempt' => 'datetime',
        'lead_submitted_at' => 'datetime',
        // ActiveProspect JSON fields
        'verification_data' => 'array',
        'compliance_checks' => 'array',
        'trusted_form_verification_data' => 'array',
        'jornaya_verification_data' => 'array',
        'risk_factors' => 'array',
        'quality_recommendations' => 'array',
        'suppression_lists' => 'array',
        'enhanced_data' => 'array',
        'data_enhancements' => 'array',
        // Boolean fields
        'trusted_form_verified' => 'boolean',
        'jornaya_verified' => 'boolean',
        'is_suppressed' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    // Temporary agent relationship to resolve test issues
    public function agent()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Get masked phone number if lead is older than 30 minutes
     */
    public function getMaskedPhoneAttribute()
    {
        if (!$this->phone) {
            return null;
        }

        // Check if lead is older than 30 minutes
        $canViewFullPhone = $this->created_at->diffInMinutes(now()) <= 30;
        
        if ($canViewFullPhone) {
            return $this->phone;
        }

        // Remove all non-numeric characters for masking
        $numericPhone = preg_replace('/[^0-9]/', '', $this->phone);
        
        if (strlen($numericPhone) >= 6) {
            // Mask phone number: show first 3 and last 3 digits
            return substr($numericPhone, 0, 3) . str_repeat('X', strlen($numericPhone) - 6) . substr($numericPhone, -3);
        }
        
        return $this->phone; // Return original if too short to mask
    }

    /**
     * Check if phone number can be viewed in full (within 30 minutes)
     */
    public function canViewFullPhone()
    {
        return $this->created_at->diffInMinutes(now()) <= 30;
    }

    /**
     * Check if lead can be edited (within 30 minutes)
     */
    public function canBeEdited()
    {
        return $this->created_at->diffInMinutes(now()) <= 30;
    }
}
