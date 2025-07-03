<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class PublisherDid extends Model
{
    protected $fillable = [
        'number',
        'area_code',
        'country_code',
        'provider',
        'status',
        'type',
        'monthly_cost',
        'setup_cost',
        'publisher_id',
        'destination_number',
        'assigned_at',
        'expires_at',
        'call_forwarding_enabled',
        'recording_enabled',
        'analytics_enabled',
        'did_metadata',
    ];

    protected $casts = [
        'monthly_cost' => 'decimal:2',
        'setup_cost' => 'decimal:2',
        'assigned_at' => 'datetime',
        'expires_at' => 'datetime',
        'call_forwarding_enabled' => 'boolean',
        'recording_enabled' => 'boolean',
        'analytics_enabled' => 'boolean',
        'did_metadata' => 'array',
    ];

    // Relationships
    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    public function callLogs(): HasMany
    {
        return $this->hasMany(PublisherCallLog::class, 'publisher_did', 'number');
    }

    public function leads(): HasMany
    {
        return $this->hasMany(PublisherLead::class, 'publisher_did', 'number');
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'assigned');
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', 'available');
    }

    public function scopeForPublisher(Builder $query, int $publisherId): Builder
    {
        return $query->where('publisher_id', $publisherId);
    }

    public function scopeExpiringSoon(Builder $query, int $days = 30): Builder
    {
        return $query->where('expires_at', '<=', now()->addDays($days));
    }

    // Helper methods
    public function isActive(): bool
    {
        return $this->status === 'assigned';
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getFormattedNumberAttribute(): string
    {
        return $this->formatPhoneNumber($this->number);
    }

    public function getFormattedDestinationAttribute(): string
    {
        return $this->formatPhoneNumber($this->destination_number);
    }

    public function getTotalCallsAttribute(): int
    {
        return $this->callLogs()->count();
    }

    public function getTotalCallsToday(): int
    {
        return $this->callLogs()->whereDate('created_at', today())->count();
    }

    public function getTotalCallsThisMonth(): int
    {
        return $this->callLogs()->whereMonth('created_at', now()->month)->count();
    }

    public function getAverageCallDuration(): float
    {
        return $this->callLogs()->where('status', 'completed')->avg('duration') ?? 0;
    }

    public function assignToPublisher(Publisher $publisher, string $destinationNumber): void
    {
        $this->update([
            'publisher_id' => $publisher->id,
            'destination_number' => $destinationNumber,
            'status' => 'assigned',
            'assigned_at' => now(),
            'did_metadata' => array_merge($this->did_metadata ?? [], [
                'assigned_to_publisher' => $publisher->name,
                'assignment_date' => now()->toDateString()
            ])
        ]);
    }

    public function unassign(): void
    {
        $this->update([
            'publisher_id' => null,
            'destination_number' => null,
            'status' => 'available',
            'assigned_at' => null,
            'did_metadata' => array_merge($this->did_metadata ?? [], [
                'unassigned_date' => now()->toDateString()
            ])
        ]);
    }

    private function formatPhoneNumber(?string $number): string
    {
        if (!$number) return '';
        
        $cleaned = preg_replace('/[^0-9]/', '', $number);
        
        if (strlen($cleaned) === 10) {
            return sprintf('(%s) %s-%s', 
                substr($cleaned, 0, 3),
                substr($cleaned, 3, 3),
                substr($cleaned, 6, 4)
            );
        } elseif (strlen($cleaned) === 11 && substr($cleaned, 0, 1) === '1') {
            return sprintf('+1 (%s) %s-%s', 
                substr($cleaned, 1, 3),
                substr($cleaned, 4, 3),
                substr($cleaned, 7, 4)
            );
        }
        
        return $number;
    }
}
