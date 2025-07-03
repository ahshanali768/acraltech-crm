<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CallLog;
use App\Models\Campaign;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayPerCallController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::with(['calls' => function($query) {
            $query->whereMonth('created_at', Carbon::now()->month);
        }, 'payments'])->get();

        $metrics = [
            'totalCalls' => CallLog::whereMonth('created_at', Carbon::now()->month)->count(),
            'totalPayout' => Payment::whereMonth('created_at', Carbon::now()->month)->sum('amount'),
            'avgCallDuration' => CallLog::whereMonth('created_at', Carbon::now()->month)->avg('duration'),
            'conversionRate' => $this->calculateConversionRate()
        ];

        $recentPayments = Payment::with(['campaign', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.pay-per-call.index', compact('campaigns', 'metrics', 'recentPayments'));
    }

    public function campaignReport($id)
    {
        $campaign = Campaign::with(['calls', 'payments'])->findOrFail($id);
        
        $metrics = [
            'totalCalls' => $campaign->calls()->count(),
            'totalPayout' => $campaign->payments()->sum('amount'),
            'avgCallDuration' => $campaign->calls()->avg('duration'),
            'conversionRate' => $this->calculateCampaignConversionRate($campaign)
        ];

        $monthlyStats = $campaign->calls()
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total_calls'),
                DB::raw('SUM(duration) as total_duration'),
                DB::raw('AVG(duration) as avg_duration')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('admin.pay-per-call.campaign-report', compact('campaign', 'metrics', 'monthlyStats'));
    }

    public function export(Request $request)
    {
        $query = Payment::with(['campaign', 'user']);

        if ($request->has('campaign_id')) {
            $query->where('campaign_id', $request->campaign_id);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date),
                Carbon::parse($request->end_date)
            ]);
        }

        $payments = $query->get();

        return response()->streamDownload(function() use ($payments) {
            $handle = fopen('php://output', 'w');
            
            // Headers
            fputcsv($handle, ['Date', 'Campaign', 'Agent', 'Amount', 'Status']);
            
            foreach ($payments as $payment) {
                fputcsv($handle, [
                    $payment->created_at->format('Y-m-d'),
                    $payment->campaign->name,
                    $payment->user->name,
                    $payment->amount,
                    $payment->status
                ]);
            }
            
            fclose($handle);
        }, 'pay-per-call-' . date('Y-m-d') . '.csv');
    }

    private function calculateConversionRate()
    {
        $totalCalls = CallLog::whereMonth('created_at', Carbon::now()->month)->count();
        $convertedCalls = CallLog::whereMonth('created_at', Carbon::now()->month)
            ->where('duration', '>=', 120) // Example: calls longer than 2 minutes are considered converted
            ->count();

        return $totalCalls > 0 ? round(($convertedCalls / $totalCalls) * 100, 2) : 0;
    }

    private function calculateCampaignConversionRate($campaign)
    {
        $totalCalls = $campaign->calls()->count();
        $convertedCalls = $campaign->calls()
            ->where('duration', '>=', 120)
            ->count();

        return $totalCalls > 0 ? round(($convertedCalls / $totalCalls) * 100, 2) : 0;
    }
}
