<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentActivity extends Model
{
    protected $table = 'agent_activity';
    
    protected $fillable = [
        'user_id',
        'date',
        'calls_made',
        'leads_transferred',
        'leads_approved',
        'leads_rejected',
        'commission_earned'
    ];

    protected $casts = [
        'date' => 'date',
        'calls_made' => 'integer',
        'leads_transferred' => 'integer', 
        'leads_approved' => 'integer',
        'leads_rejected' => 'integer',
        'commission_earned' => 'decimal:2'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getApprovalRateAttribute()
    {
        $total = $this->leads_approved + $this->leads_rejected;
        return $total > 0 ? round(($this->leads_approved / $total) * 100, 1) : 0;
    }

    public function getPendingLeadsAttribute()
    {
        return $this->leads_transferred - $this->leads_approved - $this->leads_rejected;
    }

    public static function todayForUser($userId)
    {
        return static::firstOrCreate([
            'user_id' => $userId,
            'date' => today()
        ]);
    }

    public function incrementCalls()
    {
        $this->increment('calls_made');
    }

    public function incrementTransfers()
    {
        $this->increment('leads_transferred');
    }

    public function incrementApproved($commission = 0)
    {
        $this->increment('leads_approved');
        if ($commission > 0) {
            $this->increment('commission_earned', $commission);
        }
    }

    public function incrementRejected()
    {
        $this->increment('leads_rejected');
    }
}
