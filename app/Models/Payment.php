<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'campaign_id',
        'payment_type',
        'amount_usd',
        'amount_inr',
        'exchange_rate',
        'leads_count',
        'period_start',
        'period_end',
        'status',
        'payment_method',
        'transaction_id',
        'notes',
        'processed_at',
        'paid_at',
    ];

    protected $casts = [
        'amount_usd' => 'decimal:2',
        'amount_inr' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'processed_at' => 'datetime',
        'paid_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the payment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the campaign associated with the payment.
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Scope for pending payments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for processed payments.
     */
    public function scopeProcessed($query)
    {
        return $query->where('status', 'processed');
    }

    /**
     * Scope for paid payments.
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Calculate payment based on approved leads.
     */
    public static function calculateCommission(User $user, $startDate, $endDate)
    {
        $approvedLeads = $user->leads()
            ->where('status', 'approved')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with('campaign')
            ->get();

        $totalAmount = 0;
        $leadsCount = 0;
        $campaignBreakdown = [];

        foreach ($approvedLeads as $lead) {
            if ($lead->campaign) {
                $payout = $lead->campaign->payout_usd;
                $totalAmount += $payout;
                $leadsCount++;

                $campaignId = $lead->campaign->id;
                if (!isset($campaignBreakdown[$campaignId])) {
                    $campaignBreakdown[$campaignId] = [
                        'campaign_name' => $lead->campaign->campaign_name,
                        'leads_count' => 0,
                        'total_amount' => 0,
                    ];
                }
                $campaignBreakdown[$campaignId]['leads_count']++;
                $campaignBreakdown[$campaignId]['total_amount'] += $payout;
            }
        }

        return [
            'total_amount_usd' => $totalAmount,
            'leads_count' => $leadsCount,
            'campaign_breakdown' => $campaignBreakdown,
        ];
    }

    /**
     * Process payment calculation and create payment record.
     */
    public static function processMonthlyCommissions($month = null, $year = null)
    {
        $month = $month ?? date('m');
        $year = $year ?? date('Y');
        
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $users = User::where('role', 'agent')
            ->where('status', 'active')
            ->get();

        $processedPayments = [];

        foreach ($users as $user) {
            $calculation = self::calculateCommission($user, $startDate, $endDate);
            
            if ($calculation['total_amount_usd'] > 0) {
                $payment = self::create([
                    'user_id' => $user->id,
                    'payment_type' => 'commission',
                    'amount_usd' => $calculation['total_amount_usd'],
                    'leads_count' => $calculation['leads_count'],
                    'period_start' => $startDate->toDateString(),
                    'period_end' => $endDate->toDateString(),
                    'status' => 'pending',
                    'notes' => 'Monthly commission for ' . $startDate->format('F Y'),
                ]);

                $processedPayments[] = $payment;
            }
        }

        return $processedPayments;
    }
}
