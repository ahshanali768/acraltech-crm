<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Campaign;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{    /**
     * Display the analytics dashboard.
     */
    public function index()
    {
        return $this->dashboard();
    }

    /**
     * Display the analytics dashboard.
     */
    public function dashboard()
    {
        $totalLeads = Lead::count();
        $approvedLeads = Lead::where('status', 'approved')->count();
        $pendingLeads = Lead::where('status', 'pending')->count();
        $rejectedLeads = Lead::where('status', 'rejected')->count();
        
        $conversionRate = $totalLeads > 0 ? round(($approvedLeads / $totalLeads) * 100, 2) : 0;
        
        $totalCampaigns = Campaign::count();
        $activeCampaigns = Campaign::where('status', 'active')->count();
        
        $totalUsers = User::where('status', 'active')->count();
        $agentCount = User::where('role', 'agent')->where('status', 'active')->count();
          // Monthly performance data (SQLite compatible)
        $monthlyData = Lead::selectRaw("strftime('%m', created_at) as month, COUNT(*) as total_leads, SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_leads")
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->orderBy('month')
        ->get();
          // Top performing campaigns (simplified for SQLite)
        $topCampaigns = Campaign::all();
        
        // Recent activity
        $recentLeads = Lead::latest()
            ->limit(10)
            ->get();
          // Additional variables for the view
        $leadsGrowth = 12; // Mock data
        $approvalRate = $totalLeads > 0 ? round(($approvedLeads / $totalLeads) * 100, 2) : 0;
        $revenue = 25000; // Mock data
        
        return view('admin.analytics', compact(
            'totalLeads',
            'approvedLeads',
            'pendingLeads',
            'rejectedLeads',
            'conversionRate',
            'totalCampaigns',
            'activeCampaigns',
            'totalUsers',
            'agentCount',
            'monthlyData',
            'topCampaigns',
            'recentLeads',
            'leadsGrowth',
            'approvalRate',
            'revenue'
        ));
    }

    /**
     * Get real-time metrics data.
     */
    public function realTimeMetrics()
    {
        $todayLeads = Lead::whereDate('created_at', today())->count();
        $thisWeekLeads = Lead::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->count();
        
        $thisMonthLeads = Lead::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->count();
        
        $totalLeads = Lead::count();
        $approvedLeads = Lead::where('status', 'approved')->count();
        $conversionRate = $totalLeads > 0 ? round(($approvedLeads / $totalLeads) * 100, 2) : 0;
        
        return response()->json([
            'leads_count' => $totalLeads,
            'today_leads' => $todayLeads,
            'week_leads' => $thisWeekLeads,
            'month_leads' => $thisMonthLeads,
            'conversion_rate' => $conversionRate,
            'response_time' => rand(150, 300), // Simulated response time in ms
            'client_satisfaction' => rand(85, 98), // Simulated satisfaction score
            'last_updated' => now()->toISOString(),
        ]);
    }

    /**
     * Get campaign performance data.
     */
    public function campaignPerformance()
    {
        $campaigns = Campaign::with(['leads' => function($query) {
            $query->select('campaign_id', 'status');
        }])
        ->get()
        ->map(function($campaign) {
            $totalLeads = $campaign->leads->count();
            $approvedLeads = $campaign->leads->where('status', 'approved')->count();
            $conversionRate = $totalLeads > 0 ? round(($approvedLeads / $totalLeads) * 100, 2) : 0;
            
            return [
                'id' => $campaign->id,
                'name' => $campaign->campaign_name,
                'total_leads' => $totalLeads,
                'approved_leads' => $approvedLeads,
                'conversion_rate' => $conversionRate,
                'payout_usd' => $campaign->payout_usd,
                'total_earnings' => $approvedLeads * $campaign->payout_usd,
                'status' => $campaign->status,
            ];
        });
        
        return response()->json($campaigns);
    }

    /**
     * Get geographic distribution data.
     */
    public function geographicData()
    {
        $stateData = Lead::select('state', DB::raw('COUNT(*) as lead_count'))
            ->whereNotNull('state')
            ->groupBy('state')
            ->orderByDesc('lead_count')
            ->limit(20)
            ->get();
        
        $cityData = Lead::select('city', 'state', DB::raw('COUNT(*) as lead_count'))
            ->whereNotNull('city')
            ->groupBy('city', 'state')
            ->orderByDesc('lead_count')
            ->limit(15)
            ->get();
        
        return response()->json([
            'states' => $stateData,
            'cities' => $cityData,
        ]);
    }
}
