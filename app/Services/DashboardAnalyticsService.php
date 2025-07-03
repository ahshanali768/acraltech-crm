<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\User;
use App\Models\Campaign;
use App\Models\Did;
use App\Models\CallLog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardAnalyticsService
{
    /**
     * Get real-time dashboard metrics
     */
    public function getRealTimeMetrics()
    {
        return Cache::remember('dashboard_metrics', 60, function() {
            return [
                'leads' => $this->getLeadMetrics(),
                'campaigns' => $this->getCampaignMetrics(),
                'agents' => $this->getAgentMetrics(),
                'dids' => $this->getDidMetrics(),
                'calls' => $this->getCallMetrics(),
                'revenue' => $this->getRevenueMetrics(),
                'system' => $this->getSystemMetrics(),
            ];
        });
    }

    /**
     * Get lead metrics
     */
    private function getLeadMetrics()
    {
        $total = Lead::count();
        $today = Lead::whereDate('created_at', today())->count();
        $thisWeek = Lead::where('created_at', '>=', now()->startOfWeek())->count();
        $thisMonth = Lead::where('created_at', '>=', now()->startOfMonth())->count();
        
        $approved = Lead::where('status', 'approved')->count();
        $pending = Lead::where('status', 'pending')->count();
        $rejected = Lead::where('status', 'rejected')->count();
        
        // Calculate conversion rate
        $conversionRate = $total > 0 ? round(($approved / $total) * 100, 2) : 0;
        
        // Calculate growth
        $lastMonth = Lead::where('created_at', '>=', now()->subMonths(2)->startOfMonth())
            ->where('created_at', '<', now()->subMonth()->startOfMonth())
            ->count();
        
        $growth = $lastMonth > 0 ? round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1) : 0;
        
        return [
            'total' => $total,
            'today' => $today,
            'this_week' => $thisWeek,
            'this_month' => $thisMonth,
            'approved' => $approved,
            'pending' => $pending,
            'rejected' => $rejected,
            'conversion_rate' => $conversionRate,
            'growth' => $growth,
        ];
    }

    /**
     * Get campaign metrics
     */
    private function getCampaignMetrics()
    {
        $total = Campaign::count();
        $active = Campaign::where('status', 'active')->count();
        
        $topPerforming = Campaign::withCount([
                'leads',
                'leads as approved_leads_count' => function($query) {
                    $query->where('status', 'approved');
                }
            ])
            ->orderByDesc('approved_leads_count')
            ->limit(3)
            ->get();
        
        return [
            'total' => $total,
            'active' => $active,
            'top_performing' => $topPerforming,
        ];
    }

    /**
     * Get agent metrics
     */
    private function getAgentMetrics()
    {
        $totalAgents = User::where('role', 'agent')->count();
        $activeAgents = User::where('role', 'agent')
            ->where('status', 'active')
            ->count();
        
        $topAgents = User::where('role', 'agent')
            ->withCount([
                'leads as approved_leads_count' => function($query) {
                    $query->where('status', 'approved')
                          ->where('created_at', '>=', now()->startOfMonth());
                }
            ])
            ->orderByDesc('approved_leads_count')
            ->limit(5)
            ->get();
        
        return [
            'total' => $totalAgents,
            'active' => $activeAgents,
            'top_performers' => $topAgents,
        ];
    }

    /**
     * Get DID metrics
     */
    private function getDidMetrics()
    {
        $total = Did::count();
        $active = Did::where('status', 'active')->count();
        $available = Did::where('status', 'available')->count();
        $expiring = Did::where('expires_at', '<=', now()->addDays(30))->count();
        
        $topPerforming = Did::withCount([
                'callLogs',
                'leads as converted_leads_count' => function($query) {
                    $query->where('status', 'approved');
                }
            ])
            ->orderByDesc('call_logs_count')
            ->limit(5)
            ->get();
        
        return [
            'total' => $total,
            'active' => $active,
            'available' => $available,
            'expiring' => $expiring,
            'top_performing' => $topPerforming,
        ];
    }

    /**
     * Get call metrics
     */
    private function getCallMetrics()
    {
        $total = CallLog::count();
        $today = CallLog::whereDate('started_at', today())->count();
        $answered = CallLog::where('status', 'answered')->count();
        $missed = CallLog::where('status', 'missed')->count();
        
        $avgDuration = CallLog::where('status', 'answered')->avg('duration') ?? 0;
        $totalDuration = CallLog::sum('duration');
        
        // Answer rate
        $answerRate = $total > 0 ? round(($answered / $total) * 100, 2) : 0;
        
        return [
            'total' => $total,
            'today' => $today,
            'answered' => $answered,
            'missed' => $missed,
            'answer_rate' => $answerRate,
            'avg_duration' => round($avgDuration, 2),
            'total_duration' => $totalDuration,
        ];
    }

    /**
     * Get revenue metrics
     */
    private function getRevenueMetrics()
    {
        $campaigns = Campaign::withCount([
            'leads as approved_leads_count' => function($query) {
                $query->where('status', 'approved');
            }
        ])->get();
        
        $totalRevenue = $campaigns->sum(function($campaign) {
            return $campaign->approved_leads_count * $campaign->payout_usd;
        });
        
        $thisMonth = $campaigns->sum(function($campaign) {
            $monthlyLeads = $campaign->leads()
                ->where('status', 'approved')
                ->where('created_at', '>=', now()->startOfMonth())
                ->count();
            return $monthlyLeads * $campaign->payout_usd;
        });
        
        $lastMonth = $campaigns->sum(function($campaign) {
            $lastMonthLeads = $campaign->leads()
                ->where('status', 'approved')
                ->where('created_at', '>=', now()->subMonth()->startOfMonth())
                ->where('created_at', '<', now()->startOfMonth())
                ->count();
            return $lastMonthLeads * $campaign->payout_usd;
        });
        
        $growth = $lastMonth > 0 ? round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1) : 0;
        
        return [
            'total' => $totalRevenue,
            'this_month' => $thisMonth,
            'last_month' => $lastMonth,
            'growth' => $growth,
        ];
    }

    /**
     * Get system metrics
     */
    private function getSystemMetrics()
    {
        // Database size (approximate)
        $dbSize = $this->getDatabaseSize();
        
        // Active sessions
        $activeSessions = User::where('last_activity', '>=', now()->subMinutes(5))->count();
        
        // Queue jobs (if using database queue)
        $queueJobs = 0;
        try {
            $queueJobs = DB::table('jobs')->count();
        } catch (\Exception $e) {
            // Table doesn't exist
        }
        
        return [
            'database_size' => $dbSize,
            'active_sessions' => $activeSessions,
            'queue_jobs' => $queueJobs,
            'uptime' => $this->getUptime(),
            'memory_usage' => $this->getMemoryUsage(),
        ];
    }

    /**
     * Get database size
     */
    private function getDatabaseSize()
    {
        try {
            $path = database_path('database.sqlite');
            if (file_exists($path)) {
                return round(filesize($path) / 1024 / 1024, 2) . ' MB';
            }
        } catch (\Exception $e) {
            // Error getting file size
        }
        
        return 'N/A';
    }

    /**
     * Get system uptime
     */
    private function getUptime()
    {
        // This is a simplified uptime calculation
        // In production, you'd want to track this properly
        return '99.9%';
    }

    /**
     * Get memory usage
     */
    private function getMemoryUsage()
    {
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = ini_get('memory_limit');
        
        // Convert to MB
        $usageMB = round($memoryUsage / 1024 / 1024, 2);
        
        return $usageMB . ' MB';
    }

    /**
     * Get hourly lead data for charts
     */
    public function getHourlyLeadData($date = null)
    {
        $date = $date ?? today();
        
        $hourlyData = Lead::whereDate('created_at', $date)
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('count', 'hour');
        
        // Fill missing hours with 0
        $result = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $result[$hour] = $hourlyData->get($hour, 0);
        }
        
        return $result;
    }

    /**
     * Get weekly lead data for charts
     */
    public function getWeeklyLeadData($weeks = 12)
    {
        $weeklyData = Lead::where('created_at', '>=', now()->subWeeks($weeks))
            ->selectRaw('YEAR(created_at) as year, WEEK(created_at) as week, COUNT(*) as count')
            ->groupBy('year', 'week')
            ->orderBy('year')
            ->orderBy('week')
            ->get();
        
        return $weeklyData->mapWithKeys(function($item) {
            return ["{$item->year}-W{$item->week}" => $item->count];
        });
    }

    /**
     * Get lead sources breakdown
     */
    public function getLeadSourcesBreakdown()
    {
        return Lead::selectRaw('
                CASE 
                    WHEN did IS NOT NULL THEN "Phone Call"
                    WHEN external_id LIKE "web%" THEN "Website"
                    WHEN external_id LIKE "social%" THEN "Social Media"
                    ELSE "Direct Entry"
                END as source,
                COUNT(*) as count
            ')
            ->groupBy('source')
            ->pluck('count', 'source');
    }
}
