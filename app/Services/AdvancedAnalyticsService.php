<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\Campaign;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdvancedAnalyticsService
{
    /**
     * Get comprehensive analytics data with caching.
     */
    public function getAdvancedAnalytics(array $filters = []): array
    {
        $cacheKey = 'advanced_analytics_' . md5(serialize($filters));
        
        return Cache::remember($cacheKey, 300, function () use ($filters) {
            return [
                'overview' => $this->getOverviewMetrics($filters),
                'trends' => $this->getTrendAnalysis($filters),
                'performance' => $this->getPerformanceMetrics($filters),
                'geographic' => $this->getGeographicDistribution($filters),
                'conversion_funnel' => $this->getConversionFunnel($filters),
                'revenue_analytics' => $this->getRevenueAnalytics($filters),
                'user_performance' => $this->getUserPerformanceMetrics($filters),
                'campaign_effectiveness' => $this->getCampaignEffectiveness($filters),
            ];
        });
    }

    /**
     * Get overview metrics.
     */
    private function getOverviewMetrics(array $filters): array
    {
        $query = Lead::query();
        $this->applyFilters($query, $filters);

        $totalLeads = $query->count();
        $approvedLeads = (clone $query)->where('status', 'approved')->count();
        $pendingLeads = (clone $query)->where('status', 'pending')->count();
        $rejectedLeads = (clone $query)->where('status', 'rejected')->count();

        $conversionRate = $totalLeads > 0 ? ($approvedLeads / $totalLeads) * 100 : 0;

        return [
            'total_leads' => $totalLeads,
            'approved_leads' => $approvedLeads,
            'pending_leads' => $pendingLeads,
            'rejected_leads' => $rejectedLeads,
            'conversion_rate' => round($conversionRate, 2),
            'total_campaigns' => Campaign::count(),
            'active_campaigns' => Campaign::where('status', 'active')->count(),
            'total_users' => User::where('status', 'active')->count(),
        ];
    }

    /**
     * Get trend analysis data.
     */
    private function getTrendAnalysis(array $filters): array
    {
        $query = Lead::query();
        $this->applyFilters($query, $filters);

        // Daily trends for the last 30 days
        $dailyTrends = $query->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total_leads'),
            DB::raw('SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved_leads'),
            DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_leads'),
            DB::raw('SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected_leads')
        )
        ->where('created_at', '>=', Carbon::now()->subDays(30))
        ->groupBy('date')
        ->orderBy('date')
        ->get()
        ->map(function ($item) {
            $item->conversion_rate = $item->total_leads > 0 
                ? round(($item->approved_leads / $item->total_leads) * 100, 2) 
                : 0;
            return $item;
        });

        // Weekly trends for the last 12 weeks
        $weeklyTrends = $query->select(
            DB::raw('YEARWEEK(created_at) as week'),
            DB::raw('COUNT(*) as total_leads'),
            DB::raw('SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved_leads')
        )
        ->where('created_at', '>=', Carbon::now()->subWeeks(12))
        ->groupBy('week')
        ->orderBy('week')
        ->get();

        return [
            'daily' => $dailyTrends,
            'weekly' => $weeklyTrends,
        ];
    }

    /**
     * Get performance metrics.
     */
    private function getPerformanceMetrics(array $filters): array
    {
        // Average response time from performance middleware data
        $performanceData = Cache::get('performance_metrics_*', []);
        
        $avgResponseTime = 0;
        $totalRequests = 0;
        
        foreach ($performanceData as $metrics) {
            if (is_array($metrics)) {
                foreach ($metrics as $metric) {
                    $avgResponseTime += $metric['execution_time_ms'] ?? 0;
                    $totalRequests++;
                }
            }
        }

        $avgResponseTime = $totalRequests > 0 ? $avgResponseTime / $totalRequests : 0;

        return [
            'avg_response_time_ms' => round($avgResponseTime, 2),
            'total_requests' => $totalRequests,
            'system_uptime' => $this->getSystemUptime(),
            'memory_usage' => memory_get_usage(true),
            'peak_memory' => memory_get_peak_usage(true),
        ];
    }

    /**
     * Get geographic distribution.
     */
    private function getGeographicDistribution(array $filters): array
    {
        $query = Lead::query();
        $this->applyFilters($query, $filters);

        $stateDistribution = $query->select('state', DB::raw('COUNT(*) as count'))
            ->whereNotNull('state')
            ->groupBy('state')
            ->orderByDesc('count')
            ->limit(15)
            ->get();

        $cityDistribution = $query->select('city', 'state', DB::raw('COUNT(*) as count'))
            ->whereNotNull('city')
            ->groupBy('city', 'state')
            ->orderByDesc('count')
            ->limit(20)
            ->get();

        return [
            'states' => $stateDistribution,
            'cities' => $cityDistribution,
        ];
    }

    /**
     * Get conversion funnel data.
     */
    private function getConversionFunnel(array $filters): array
    {
        $query = Lead::query();
        $this->applyFilters($query, $filters);

        $totalLeads = $query->count();
        $contactedLeads = (clone $query)->where('status', 'contacted')->count();
        $qualifiedLeads = (clone $query)->where('status', 'qualified')->count();
        $approvedLeads = (clone $query)->where('status', 'approved')->count();

        return [
            'total_leads' => $totalLeads,
            'contacted' => $contactedLeads,
            'qualified' => $qualifiedLeads,
            'approved' => $approvedLeads,
            'funnel_rates' => [
                'contact_rate' => $totalLeads > 0 ? ($contactedLeads / $totalLeads) * 100 : 0,
                'qualification_rate' => $contactedLeads > 0 ? ($qualifiedLeads / $contactedLeads) * 100 : 0,
                'approval_rate' => $qualifiedLeads > 0 ? ($approvedLeads / $qualifiedLeads) * 100 : 0,
            ],
        ];
    }

    /**
     * Get revenue analytics.
     */
    private function getRevenueAnalytics(array $filters): array
    {
        $paymentsQuery = Payment::query();
        
        if (isset($filters['start_date'])) {
            $paymentsQuery->where('created_at', '>=', $filters['start_date']);
        }
        if (isset($filters['end_date'])) {
            $paymentsQuery->where('created_at', '<=', $filters['end_date']);
        }

        $totalRevenue = $paymentsQuery->sum('amount_usd');
        $pendingPayments = (clone $paymentsQuery)->where('status', 'pending')->sum('amount_usd');
        $paidPayments = (clone $paymentsQuery)->where('status', 'paid')->sum('amount_usd');

        $monthlyRevenue = Payment::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(amount_usd) as revenue')
        )
        ->where('created_at', '>=', Carbon::now()->subMonths(12))
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get();

        return [
            'total_revenue' => $totalRevenue,
            'pending_payments' => $pendingPayments,
            'paid_payments' => $paidPayments,
            'monthly_revenue' => $monthlyRevenue,
        ];
    }

    /**
     * Get user performance metrics.
     */
    private function getUserPerformanceMetrics(array $filters): array
    {
        $topPerformers = User::with(['leads' => function($query) use ($filters) {
            $this->applyFilters($query, $filters);
        }])
        ->whereHas('leads', function($query) use ($filters) {
            $this->applyFilters($query, $filters);
        })
        ->get()
        ->map(function($user) {
            $totalLeads = $user->leads->count();
            $approvedLeads = $user->leads->where('status', 'approved')->count();
            $conversionRate = $totalLeads > 0 ? ($approvedLeads / $totalLeads) * 100 : 0;

            return [
                'user' => $user->only(['id', 'name', 'email', 'role']),
                'total_leads' => $totalLeads,
                'approved_leads' => $approvedLeads,
                'conversion_rate' => round($conversionRate, 2),
            ];
        })
        ->sortByDesc('approved_leads')
        ->take(10);

        return [
            'top_performers' => $topPerformers->values(),
        ];
    }

    /**
     * Get campaign effectiveness metrics.
     */
    private function getCampaignEffectiveness(array $filters): array
    {
        $campaigns = Campaign::with(['leads' => function($query) use ($filters) {
            $this->applyFilters($query, $filters);
        }])
        ->get()
        ->map(function($campaign) {
            $totalLeads = $campaign->leads->count();
            $approvedLeads = $campaign->leads->where('status', 'approved')->count();
            $conversionRate = $totalLeads > 0 ? ($approvedLeads / $totalLeads) * 100 : 0;
            $revenue = $approvedLeads * $campaign->payout_usd;

            return [
                'campaign' => $campaign->only(['id', 'campaign_name', 'status', 'payout_usd']),
                'total_leads' => $totalLeads,
                'approved_leads' => $approvedLeads,
                'conversion_rate' => round($conversionRate, 2),
                'revenue' => $revenue,
                'roi' => $totalLeads > 0 ? ($revenue / ($totalLeads * 10)) * 100 : 0, // Assuming $10 cost per lead
            ];
        })
        ->sortByDesc('revenue')
        ->take(15);

        return [
            'top_campaigns' => $campaigns->values(),
        ];
    }

    /**
     * Apply filters to query.
     */
    private function applyFilters($query, array $filters): void
    {
        if (isset($filters['start_date'])) {
            $query->where('created_at', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->where('created_at', '<=', $filters['end_date']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['campaign_id'])) {
            $query->where('campaign_id', $filters['campaign_id']);
        }

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }
    }

    /**
     * Get system uptime (simplified).
     */
    private function getSystemUptime(): string
    {
        // This is a simplified version - in production you'd track actual uptime
        return '99.9%';
    }
}
