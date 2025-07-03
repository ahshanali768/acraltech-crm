<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Get real-time performance metrics
     */
    public function realTimeMetrics(): JsonResponse
    {        $currentMonth = Carbon::now()->startOfMonth();
        
        // Calculate metrics
        $totalLeads = Lead::where('created_at', '>=', $currentMonth)->count();
        $approvedLeads = Lead::where('created_at', '>=', $currentMonth)
                           ->where('status', 'approved')
                           ->count();
        
        $pendingLeads = Lead::where('created_at', '>=', $currentMonth)
                          ->where('status', 'pending')
                          ->count();
        
        $conversionRate = $totalLeads > 0 ? round(($approvedLeads / $totalLeads) * 100, 1) : 0;
        
        // Average response time (simulate)
        $avgResponseTime = rand(15, 45);
        
        // Client satisfaction (simulate based on approved leads ratio)
        $clientSatisfaction = max(85, min(98, 85 + ($conversionRate * 0.5)));
        
        // Total campaigns count
        $totalCampaigns = \App\Models\Campaign::count();
        
        return response()->json([
            'total_leads' => $totalLeads,
            'approved_leads' => $approvedLeads,
            'pending_leads' => $pendingLeads,
            'total_campaigns' => $totalCampaigns,
            'leads_count' => $totalLeads, // For backward compatibility
            'conversion_rate' => $conversionRate,
            'response_time' => $avgResponseTime,
            'client_satisfaction' => round($clientSatisfaction, 1),
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Get live activity feed
     */
    public function liveActivity(): JsonResponse
    {
        $activities = [];
        
        // Get recent leads for activity feed
        $recentLeads = Lead::with('campaign')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        foreach ($recentLeads as $lead) {
            $timeAgo = $lead->created_at->diffForHumans();
            $service = $lead->campaign->name ?? 'General';
            $state = $lead->state ?? 'Unknown';
            
            $activities[] = [
                'message' => "{$service} lead verified - {$lead->city}, {$state}",
                'time' => $timeAgo,
                'type' => $lead->status,
                'timestamp' => $lead->created_at->toISOString()
            ];
        }
        
        return response()->json($activities);
    }

    /**
     * Get campaign performance analytics
     */
    public function campaignAnalytics(Request $request): JsonResponse
    {
        $timeframe = $request->get('timeframe', '30'); // days
        $startDate = Carbon::now()->subDays($timeframe);
        
        $campaigns = Campaign::withCount([
            'leads as total_leads' => function ($query) use ($startDate) {
                $query->where('created_at', '>=', $startDate);
            },
            'leads as approved_leads' => function ($query) use ($startDate) {
                $query->where('created_at', '>=', $startDate)
                      ->where('status', 'approved');
            }
        ])->get();
        
        $analytics = $campaigns->map(function ($campaign) {
            $conversionRate = $campaign->total_leads > 0 
                ? round(($campaign->approved_leads / $campaign->total_leads) * 100, 2)
                : 0;
                
            return [
                'id' => $campaign->id,
                'name' => $campaign->name,
                'total_leads' => $campaign->total_leads,
                'approved_leads' => $campaign->approved_leads,
                'conversion_rate' => $conversionRate,
                'status' => $campaign->status
            ];
        });
        
        return response()->json($analytics);
    }

    /**
     * Get leads trend data for charts
     */
    public function leadsTrend(Request $request): JsonResponse
    {
        $days = $request->get('days', 30);
        $startDate = Carbon::now()->subDays($days);
        
        $trends = Lead::selectRaw('DATE(created_at) as date, COUNT(*) as count, 
                                  SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        $trendData = [];
        for ($i = $days; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dayData = $trends->firstWhere('date', $date);
            
            $trendData[] = [
                'date' => $date,
                'total_leads' => $dayData->count ?? 0,
                'approved_leads' => $dayData->approved ?? 0,
                'conversion_rate' => $dayData && $dayData->count > 0 
                    ? round(($dayData->approved / $dayData->count) * 100, 2) 
                    : 0
            ];
        }
        
        return response()->json($trendData);
    }

    /**
     * Get geographic distribution of leads
     */
    public function geographicDistribution(): JsonResponse
    {
        $distribution = Lead::selectRaw('state, COUNT(*) as count')
            ->whereNotNull('state')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('state')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
        
        return response()->json($distribution);
    }

    /**
     * Get service performance breakdown
     */
    public function servicePerformance(): JsonResponse
    {
        $services = Campaign::withCount([
            'leads as total_leads' => function ($query) {
                $query->where('created_at', '>=', Carbon::now()->subDays(30));
            },
            'leads as approved_leads' => function ($query) {
                $query->where('created_at', '>=', Carbon::now()->subDays(30))
                      ->where('status', 'approved');
            }
        ])->get();
        
        $performance = $services->map(function ($service) {
            return [
                'name' => $service->name,
                'total_leads' => $service->total_leads,
                'approved_leads' => $service->approved_leads,
                'conversion_rate' => $service->total_leads > 0 
                    ? round(($service->approved_leads / $service->total_leads) * 100, 2)
                    : 0
            ];
        });
        
        return response()->json($performance);
    }
}
