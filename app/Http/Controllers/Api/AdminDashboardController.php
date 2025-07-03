<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DashboardAnalyticsService;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    protected $analyticsService;

    public function __construct(DashboardAnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Get real-time dashboard metrics
     */
    public function getRealTimeMetrics()
    {
        return response()->json($this->analyticsService->getRealTimeMetrics());
    }

    /**
     * Get hourly lead data for today
     */
    public function getHourlyLeadData(Request $request)
    {
        $date = $request->get('date', today());
        return response()->json($this->analyticsService->getHourlyLeadData($date));
    }

    /**
     * Get weekly lead data
     */
    public function getWeeklyLeadData(Request $request)
    {
        $weeks = $request->get('weeks', 12);
        return response()->json($this->analyticsService->getWeeklyLeadData($weeks));
    }

    /**
     * Get lead sources breakdown
     */
    public function getLeadSourcesBreakdown()
    {
        return response()->json($this->analyticsService->getLeadSourcesBreakdown());
    }

    /**
     * Get live activity feed
     */
    public function getLiveActivity()
    {
        $recentLeads = \App\Models\Lead::with(['campaign'])
            ->orderByDesc('created_at')
            ->take(10)
            ->get()
            ->map(function($lead) {
                return [
                    'id' => $lead->id,
                    'type' => 'lead',
                    'title' => "New lead: {$lead->first_name} {$lead->last_name}",
                    'description' => "{$lead->campaign} • {$lead->phone}",
                    'status' => $lead->status,
                    'timestamp' => $lead->created_at->toISOString(),
                    'time_ago' => $lead->created_at->diffForHumans(),
                ];
            });

        $recentCalls = \App\Models\CallLog::with(['did'])
            ->orderByDesc('started_at')
            ->take(5)
            ->get()
            ->map(function($call) {
                return [
                    'id' => $call->id,
                    'type' => 'call',
                    'title' => "Incoming call: {$call->caller_id}",
                    'description' => "DID: {$call->did} • Duration: " . gmdate('i:s', $call->duration),
                    'status' => $call->status,
                    'timestamp' => $call->started_at->toISOString(),
                    'time_ago' => $call->started_at->diffForHumans(),
                ];
            });

        $activities = $recentLeads->concat($recentCalls)
            ->sortByDesc('timestamp')
            ->take(15)
            ->values();

        return response()->json($activities);
    }

    /**
     * Get system alerts
     */
    public function getSystemAlerts()
    {
        $alerts = [];

        // Check for high pending leads
        $pendingCount = \App\Models\Lead::where('status', 'pending')->count();
        if ($pendingCount > 50) {
            $alerts[] = [
                'type' => 'warning',
                'title' => 'High Pending Leads',
                'message' => "{$pendingCount} leads awaiting review",
                'action' => route('admin.view_leads', ['status' => 'pending']),
            ];
        }

        // Check for expiring DIDs
        $expiringDids = \App\Models\Did::where('expires_at', '<=', now()->addDays(30))->count();
        if ($expiringDids > 0) {
            $alerts[] = [
                'type' => 'error',
                'title' => 'DIDs Expiring Soon',
                'message' => "{$expiringDids} DIDs will expire within 30 days",
                'action' => route('admin.dids.index', ['status' => 'expiring']),
            ];
        }

        // Check for failed calls
        $failedCalls = \App\Models\CallLog::where('status', 'failed')
            ->whereDate('started_at', today())
            ->count();
        if ($failedCalls > 10) {
            $alerts[] = [
                'type' => 'warning',
                'title' => 'High Failed Call Rate',
                'message' => "{$failedCalls} failed calls today",
                'action' => route('admin.dids.analytics'),
            ];
        }

        // Check for inactive agents
        $inactiveAgents = \App\Models\User::where('role', 'agent')
            ->where('last_activity', '<', now()->subDays(7))
            ->count();
        if ($inactiveAgents > 0) {
            $alerts[] = [
                'type' => 'info',
                'title' => 'Inactive Agents',
                'message' => "{$inactiveAgents} agents inactive for 7+ days",
                'action' => route('admin.manage_users'),
            ];
        }

        return response()->json($alerts);
    }

    /**
     * Get dashboard summary stats
     */
    public function getDashboardSummary()
    {
        $metrics = $this->analyticsService->getRealTimeMetrics();
        
        return response()->json([
            'total_leads' => $metrics['leads']['total'],
            'leads_today' => $metrics['leads']['today'],
            'conversion_rate' => $metrics['leads']['conversion_rate'],
            'revenue_this_month' => $metrics['revenue']['this_month'],
            'active_dids' => $metrics['dids']['active'],
            'calls_today' => $metrics['calls']['today'],
            'answer_rate' => $metrics['calls']['answer_rate'],
            'active_agents' => $metrics['agents']['active'],
            'last_updated' => now()->toISOString(),
        ]);
    }
}
