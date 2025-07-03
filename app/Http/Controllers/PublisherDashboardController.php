<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Publisher;
use App\Models\CallLog;
use App\Models\Lead;

class PublisherDashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get the publisher profile associated with this user
        $publisher = Publisher::where('email', $user->email)->first();
        
        if (!$publisher) {
            return redirect()->back()->with('error', 'No publisher profile found. Please contact admin.');
        }

        // Get publisher statistics
        $stats = [
            'total_calls' => $publisher->getTotalCallsAttribute(),
            'calls_today' => $publisher->getTotalCallsToday(),
            'calls_this_month' => $publisher->getTotalCallsThisMonth(),
            'conversion_rate' => $publisher->getConversionRateAttribute(),
            'earnings_total' => $publisher->getTotalEarningsAttribute(),
            'earnings_this_month' => $publisher->getEarningsThisMonth(),
            'tracking_did' => $publisher->tracking_did,
            'destination_did' => $publisher->destination_did,
            'payout_rate' => $publisher->payout_rate,
            'payout_type' => $publisher->payout_type,
            'status' => $publisher->status
        ];

        // Recent call logs
        $recentCalls = $publisher->callLogs()
            ->with('lead')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Daily stats for chart (last 7 days)
        $dailyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $callsCount = $publisher->callLogs()
                ->whereDate('created_at', $date)
                ->count();
            
            $dailyStats[] = [
                'date' => $date->format('M d'),
                'calls' => $callsCount
            ];
        }

        return view('publisher.dashboard', compact('publisher', 'stats', 'recentCalls', 'dailyStats'));
    }

    public function analytics()
    {
        $user = Auth::user();
        $publisher = Publisher::where('email', $user->email)->first();
        
        if (!$publisher) {
            return redirect()->back()->with('error', 'No publisher profile found.');
        }

        // Monthly analytics
        $monthlyStats = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $callsCount = $publisher->callLogs()
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $leadsCount = $publisher->leads()
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->where('status', 'approved')
                ->count();
            
            $earnings = $publisher->payout_type === 'per_call' 
                ? $leadsCount * $publisher->payout_rate
                : $publisher->leads()
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->where('status', 'approved')
                    ->sum('value') * ($publisher->payout_rate / 100);
                    
            $monthlyStats[] = [
                'month' => $date->format('M Y'),
                'calls' => $callsCount,
                'leads' => $leadsCount,
                'earnings' => $earnings
            ];
        }

        // All-time call logs with pagination
        $callLogs = $publisher->callLogs()
            ->with('lead')
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return view('publisher.analytics', compact('publisher', 'monthlyStats', 'callLogs'));
    }

    public function callHistory()
    {
        $user = Auth::user();
        $publisher = Publisher::where('email', $user->email)->first();
        
        if (!$publisher) {
            return redirect()->back()->with('error', 'No publisher profile found.');
        }

        $callLogs = $publisher->callLogs()
            ->with('lead')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('publisher.call_history', compact('publisher', 'callLogs'));
    }
}
