<?php

namespace App\Http\Controllers\Publisher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Publisher;
use App\Models\PublisherCallLog;
use App\Models\PublisherDid;
use App\Models\PublisherLead;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the publisher dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $publisher = $user->publisherProfile;
        
        if (!$publisher) {
            // If no publisher profile exists, create one or redirect
            $publisher = Publisher::firstOrCreate([
                'email' => $user->email
            ], [
                'name' => $user->name,
                'company' => 'Not Set',
                'phone' => '',
                'status' => 'active',
                'payout_rate' => 0.00,
                'payout_type' => 'per_call',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Get publisher's assigned DIDs
        $assignedDids = $publisher->dids()->active()->get();

        // Calculate stats using the new publisher-specific tables
        $stats = [
            'total_calls' => $publisher->callLogs()->count(),
            'calls_today' => $publisher->callLogs()->today()->count(),
            'calls_this_month' => $publisher->callLogs()->thisMonth()->count(),
            'total_leads' => $publisher->leads()->count(),
            'leads_today' => $publisher->leads()->today()->count(),
            'leads_this_month' => $publisher->leads()->thisMonth()->count(),
            'converted_leads' => $publisher->leads()->where('status', 'converted')->count(),
            'total_earnings' => $publisher->total_earnings,
            'earnings_this_month' => $publisher->getEarningsThisMonth(),
            'conversion_rate' => $publisher->conversion_rate,
            'avg_call_duration' => $this->getAverageCallDuration($publisher),
            'tracking_did' => $publisher->tracking_did ?? null,
            'destination_did' => $publisher->destination_did ?? null,
            'payout_rate' => $publisher->payout_rate ?? 0,
            'payout_type' => $publisher->payout_type ?? 'per_call',
            'status' => $publisher->status ?? 'active',
        ];

        // Get recent calls for dashboard
        $recentCalls = $publisher->callLogs()
            ->with(['publisherDid', 'lead'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Get call trends for the chart (last 7 days)
        $dailyStats = $this->getCallTrends($publisher);

        return view('publisher.dashboard', compact(
            'publisher', 
            'stats', 
            'recentCalls', 
            'dailyStats', 
            'assignedDids'
        ));
    }

    /**
     * Show analytics page.
     */
    public function analytics()
    {
        $user = Auth::user();
        $publisher = $user->publisherProfile;
        
        if (!$publisher) {
            return redirect()->route('publisher.dashboard')->with('error', 'Publisher profile not found.');
        }

        $assignedDids = $publisher->dids()->active()->get();

        // Monthly performance data
        $monthlyData = $this->getMonthlyPerformance($publisher);
        
        // Call source breakdown
        $callSources = $this->getCallSources($publisher);
        
        // Lead status breakdown
        $leadStatuses = $this->getLeadStatusBreakdown($publisher);
        
        // Peak hours analysis
        $peakHours = $this->getPeakHours($publisher);
        
        // DID performance
        $didPerformance = $this->getDidPerformance($publisher);

        return view('publisher.analytics', compact(
            'publisher',
            'monthlyData',
            'callSources',
            'leadStatuses',
            'peakHours',
            'didPerformance',
            'assignedDids'
        ));
    }

    /**
     * Show call history.
     */
    public function callHistory(Request $request)
    {
        $user = Auth::user();
        $publisher = $user->publisherProfile;
        
        if (!$publisher) {
            return redirect()->route('publisher.dashboard')->with('error', 'Publisher profile not found.');
        }

        $assignedDids = $publisher->dids()->active()->get();

        $query = $publisher->callLogs()
            ->with(['publisherDid', 'lead']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('publisher_did')) {
            $query->where('publisher_did', $request->publisher_did);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $calls = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('publisher.call_history', compact('calls', 'assignedDids', 'publisher'));
    }

    /**
     * Show profile page.
     */
    public function profile()
    {
        $user = Auth::user();
        $publisher = $user->publisherProfile;
        
        if (!$publisher) {
            return redirect()->route('publisher.dashboard')->with('error', 'Publisher profile not found.');
        }

        // Get assigned DIDs
        $assignedDids = $publisher->dids()->active()->get();

        return view('publisher.profile', compact('publisher', 'assignedDids'));
    }

    /**
     * Update profile.
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
        ]);

        $user = Auth::user();
        $publisher = $user->publisherProfile;
        
        if (!$publisher) {
            return redirect()->route('publisher.dashboard')->with('error', 'Publisher profile not found.');
        }

        $publisher->update([
            'name' => $request->name,
            'company' => $request->company,
            'phone' => $request->phone,
            'website' => $request->website,
        ]);

        // Also update user name if changed
        if ($user->name !== $request->name) {
            $user->update(['name' => $request->name]);
        }

        return redirect()->route('publisher.profile')->with('success', 'Profile updated successfully.');
    }

    /**
     * Get average call duration.
     */
    private function getAverageCallDuration(Publisher $publisher)
    {
        $avgDuration = $publisher->callLogs()
            ->where('status', 'completed')
            ->avg('duration');

        return $avgDuration ? round($avgDuration, 2) : 0;
    }

    /**
     * Get call trends for the last 7 days.
     */
    private function getCallTrends(Publisher $publisher)
    {
        $trends = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = $publisher->callLogs()
                ->whereDate('created_at', $date)
                ->count();
            
            $trends[] = [
                'date' => $date->format('M d'),
                'calls' => $count
            ];
        }
        return $trends;
    }

    /**
     * Get monthly performance data.
     */
    private function getMonthlyPerformance(Publisher $publisher)
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $calls = $publisher->callLogs()
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            
            $leads = $publisher->leads()
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            
            $convertedLeads = $publisher->leads()
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->where('status', 'converted')
                ->count();
            
            // Calculate earnings based on payout type
            if ($publisher->payout_type === 'per_call') {
                $earnings = $convertedLeads * $publisher->payout_rate;
            } else {
                $earnings = $publisher->leads()
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->where('status', 'converted')
                    ->sum('estimated_value') * ($publisher->payout_rate / 100);
            }
            
            $months[] = [
                'month' => $date->format('M Y'),
                'calls' => $calls,
                'leads' => $leads,
                'converted_leads' => $convertedLeads,
                'earnings' => $earnings
            ];
        }
        return $months;
    }

    /**
     * Get call sources breakdown.
     */
    private function getCallSources(Publisher $publisher)
    {
        return $publisher->callLogs()
            ->selectRaw('source, COUNT(*) as count')
            ->groupBy('source')
            ->orderBy('count', 'desc')
            ->get();
    }

    /**
     * Get lead status breakdown.
     */
    private function getLeadStatusBreakdown(Publisher $publisher)
    {
        return $publisher->leads()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->orderBy('count', 'desc')
            ->get();
    }

    /**
     * Get DID performance.
     */
    private function getDidPerformance(Publisher $publisher)
    {
        return $publisher->dids()
            ->withCount(['callLogs', 'leads'])
            ->with(['callLogs' => function($query) {
                $query->selectRaw('publisher_did, AVG(duration) as avg_duration, SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed_calls')
                    ->groupBy('publisher_did');
            }])
            ->get();
    }

    /**
     * Get peak hours analysis.
     */
    private function getPeakHours(Publisher $publisher)
    {
        $hours = [];
        for ($i = 0; $i < 24; $i++) {
            $count = $publisher->callLogs()
                ->whereRaw('HOUR(created_at) = ?', [$i])
                ->count();
            
            $hours[] = [
                'hour' => $i,
                'calls' => $count,
                'label' => Carbon::createFromTime($i)->format('g A')
            ];
        }
        return $hours;
    }
}
