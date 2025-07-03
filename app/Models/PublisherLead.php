<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class PublisherLead extends Model
{
    protected $fillable = [
        'publisher_id',
        'call_log_id',
        'publisher_did',
        'caller_number',
        'first_name',
        'last_name',
        'email',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'status',
        'quality',
        'estimated_value',
        'notes',
        'lead_source',
        'ip_address',
        'user_agent',
        'referrer_url',
        'contacted_at',
        'qualified_at',
        'converted_at',
        'lead_metadata',
    ];

    protected $casts = [
        'estimated_value' => 'decimal:2',
        'contacted_at' => 'datetime',
        'qualified_at' => 'datetime',
        'converted_at' => 'datetime',
        'lead_metadata' => 'array',
    ];

    // Relationships
    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    public function callLog(): BelongsTo
    {
        return $this->belongsTo(PublisherCallLog::class, 'call_log_id');
    }

    public function publisherDid(): BelongsTo
    {
        return $this->belongsTo(PublisherDid::class, 'publisher_did', 'number');
    }

    // Scopes
    public function scopeForPublisher(Builder $query, int $publisherId): Builder
    {
        return $query->where('publisher_id', $publisherId);
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeByQuality(Builder $query, string $quality): Builder
    {
        return $query->where('quality', $quality);
    }

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek(Builder $query): Builder
    {
        return $query->whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth(Builder $query): Builder
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }

    public function scopeRecent(Builder $query, int $days = 30): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Helper methods
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function isNew(): bool
    {
        return $this->status === 'new';
    }

    public function isContacted(): bool
    {
        return $this->status === 'contacted';
    }

    public function isQualified(): bool
    {
        return $this->status === 'qualified';
    }

    public function isConverted(): bool
    {
        return $this->status === 'converted';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function markAsContacted(): void
    {
        $this->update([
            'status' => 'contacted',
            'contacted_at' => now()
        ]);
    }

    public function markAsQualified(): void
    {
        $this->update([
            'status' => 'qualified',
            'qualified_at' => now()
        ]);
    }

    public function markAsConverted(): void
    {
        $this->update([
            'status' => 'converted',
            'converted_at' => now()
        ]);
    }

    public function markAsRejected(): void
    {
        $this->update([
            'status' => 'rejected'
        ]);
    }

    public function getFormattedPhoneAttribute(): string
    {
        return $this->formatPhoneNumber($this->caller_number);
    }

    private function formatPhoneNumber(?string $phone): string
    {
        if (!$phone) return '';
        
        $cleaned = preg_replace('/[^0-9]/', '', $phone);
        
        if (strlen($cleaned) === 10) {
            return sprintf('(%s) %s-%s', 
                substr($cleaned, 0, 3),
                substr($cleaned, 3, 3),
                substr($cleaned, 6, 4)
            );
        }
        
        return $phone;
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'new' => 'blue',
            'contacted' => 'yellow',
            'qualified' => 'green',
            'converted' => 'emerald',
            'rejected' => 'red',
            default => 'gray'
        };
    }

    public function getQualityBadgeColorAttribute(): string
    {
        return match($this->quality) {
            'high' => 'green',
            'medium' => 'yellow',
            'low' => 'red',
            default => 'gray'
        };
    }
}
