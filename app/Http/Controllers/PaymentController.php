<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['user', 'campaign']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('period_start', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('period_end', '<=', $request->end_date);
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Show payment details.
     */
    public function show(Payment $payment)
    {
        $payment->load(['user', 'campaign']);
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Process monthly commissions.
     */
    public function processMonthly(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:' . (date('Y') + 1),
        ]);

        try {
            DB::beginTransaction();

            $processedPayments = Payment::processMonthlyCommissions(
                $request->month,
                $request->year
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Monthly commissions processed successfully',
                'payments_count' => count($processedPayments),
                'total_amount' => array_sum(array_column($processedPayments, 'amount_usd')),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to process commissions: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update payment status.
     */
    public function updateStatus(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:pending,processed,paid,failed',
            'transaction_id' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $payment->update([
            'status' => $request->status,
            'transaction_id' => $request->transaction_id,
            'payment_method' => $request->payment_method,
            'notes' => $request->notes,
            'processed_at' => $request->status === 'processed' ? now() : $payment->processed_at,
            'paid_at' => $request->status === 'paid' ? now() : $payment->paid_at,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment status updated successfully',
        ]);
    }

    /**
     * Get payment statistics.
     */
    public function statistics()
    {
        $totalPending = Payment::pending()->sum('amount_usd');
        $totalProcessed = Payment::processed()->sum('amount_usd');
        $totalPaid = Payment::paid()->sum('amount_usd');

        $thisMonthPayments = Payment::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->sum('amount_usd');

        $topEarners = User::with(['payments' => function($query) {
            $query->where('status', 'paid');
        }])
        ->whereHas('payments', function($query) {
            $query->where('status', 'paid');
        })
        ->get()
        ->map(function($user) {
            return [
                'user' => $user,
                'total_earnings' => $user->payments->sum('amount_usd'),
            ];
        })
        ->sortByDesc('total_earnings')
        ->take(10);

        return response()->json([
            'total_pending' => $totalPending,
            'total_processed' => $totalProcessed,
            'total_paid' => $totalPaid,
            'this_month_payments' => $thisMonthPayments,
            'top_earners' => $topEarners->values(),
        ]);
    }

    /**
     * Calculate commission preview for a user.
     */
    public function calculatePreview(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $user = User::findOrFail($request->user_id);
        $calculation = Payment::calculateCommission(
            $user,
            $request->start_date,
            $request->end_date
        );

        return response()->json([
            'user' => $user->only(['id', 'name', 'email']),
            'calculation' => $calculation,
        ]);
    }

    /**
     * Export payments to CSV.
     */
    public function export(Request $request)
    {
        $query = Payment::with(['user', 'campaign']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->where('period_start', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('period_end', '<=', $request->end_date);
        }

        $payments = $query->get();

        $filename = 'payments_export_' . date('Y_m_d_H_i_s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'ID', 'User', 'Campaign', 'Type', 'Amount USD', 'Leads Count',
                'Period Start', 'Period End', 'Status', 'Transaction ID', 'Created At'
            ]);

            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->id,
                    $payment->user->name ?? 'N/A',
                    $payment->campaign->campaign_name ?? 'N/A',
                    $payment->payment_type,
                    $payment->amount_usd,
                    $payment->leads_count,
                    $payment->period_start,
                    $payment->period_end,
                    $payment->status,
                    $payment->transaction_id ?? 'N/A',
                    $payment->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
