<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use App\Models\Did;
use App\Models\CallLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PublisherController extends Controller
{
    public function index()
    {
        $publishers = Publisher::with(['did', 'callLogs' => function($query) {
            $query->whereDate('created_at', today());
        }])->get();

        $stats = [
            'total_publishers' => Publisher::count(),
            'active_publishers' => Publisher::where('status', 'active')->count(),
            'total_calls_today' => CallLog::whereDate('created_at', today())->count(),
            'total_earnings_month' => Publisher::sum('payout_rate') // Simplified calculation
        ];

        return view('admin.publisher_management', compact('publishers', 'stats'));
    }

    public function create()
    {
        $availableDids = Did::where('status', 'available')->get();
        return view('admin.publishers.create', compact('availableDids'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:publishers,email',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'payout_rate' => 'required|numeric|min:0',
            'payout_type' => 'required|in:per_call,percentage',
            'tracking_did' => 'nullable|string|unique:publishers,tracking_did',
            'destination_did' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        $publisher = Publisher::create($validated);

        if ($request->tracking_did) {
            $publisher->assignTrackingDid($request->tracking_did, $request->destination_did);
        }

        return response()->json([
            'success' => true,
            'message' => 'Publisher created successfully',
            'publisher' => $publisher
        ]);
    }

    public function show(Publisher $publisher)
    {
        $publisher->load(['callLogs', 'leads']);
        
        $analytics = [
            'total_calls' => $publisher->getTotalCallsAttribute(),
            'calls_today' => $publisher->getTotalCallsToday(),
            'calls_this_month' => $publisher->getTotalCallsThisMonth(),
            'conversion_rate' => $publisher->getConversionRateAttribute(),
            'earnings_total' => $publisher->getTotalEarningsAttribute(),
            'earnings_this_month' => $publisher->getEarningsThisMonth(),
        ];

        return response()->json([
            'publisher' => $publisher,
            'analytics' => $analytics
        ]);
    }

    public function edit(Publisher $publisher)
    {
        $availableDids = Did::where('status', 'available')
            ->orWhere('number', $publisher->tracking_did)
            ->get();
            
        return view('admin.publishers.edit', compact('publisher', 'availableDids'));
    }

    public function update(Request $request, Publisher $publisher)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('publishers')->ignore($publisher)],
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,suspended',
            'payout_rate' => 'required|numeric|min:0',
            'payout_type' => 'required|in:per_call,percentage',
            'tracking_did' => ['nullable', 'string', Rule::unique('publishers')->ignore($publisher)],
            'destination_did' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        $oldTrackingDid = $publisher->tracking_did;
        $publisher->update($validated);

        // If tracking DID changed, update the DID routing
        if ($request->tracking_did !== $oldTrackingDid) {
            // Release old DID if it exists
            if ($oldTrackingDid) {
                $oldDid = Did::where('number', $oldTrackingDid)->first();
                if ($oldDid) {
                    $oldDid->update(['status' => 'available', 'routing_destination' => null]);
                }
            }
            
            // Assign new DID
            if ($request->tracking_did) {
                $publisher->assignTrackingDid($request->tracking_did, $request->destination_did);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Publisher updated successfully',
            'publisher' => $publisher
        ]);
    }

    public function destroy(Publisher $publisher)
    {
        // Release the tracking DID
        if ($publisher->tracking_did) {
            $did = Did::where('number', $publisher->tracking_did)->first();
            if ($did) {
                $did->update(['status' => 'available', 'routing_destination' => null]);
            }
        }

        $publisher->delete();

        return response()->json([
            'success' => true,
            'message' => 'Publisher deleted successfully'
        ]);
    }

    public function assignTrackingDid(Request $request, Publisher $publisher)
    {
        $validated = $request->validate([
            'tracking_did' => 'required|string|unique:publishers,tracking_did',
            'destination_did' => 'required|string'
        ]);

        $publisher->assignTrackingDid($validated['tracking_did'], $validated['destination_did']);

        return response()->json([
            'success' => true,
            'message' => 'Tracking DID assigned successfully',
            'publisher' => $publisher
        ]);
    }

    public function toggleStatus(Publisher $publisher)
    {
        $newStatus = $publisher->status === 'active' ? 'inactive' : 'active';
        $publisher->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => "Publisher {$newStatus}",
            'status' => $newStatus
        ]);
    }

    public function getAnalytics(Publisher $publisher)
    {
        $callLogs = $publisher->callLogs()
            ->with('lead')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $analytics = [
            'total_calls' => $publisher->getTotalCallsAttribute(),
            'calls_today' => $publisher->getTotalCallsToday(),
            'calls_this_month' => $publisher->getTotalCallsThisMonth(),
            'conversion_rate' => $publisher->getConversionRateAttribute(),
            'earnings_total' => $publisher->getTotalEarningsAttribute(),
            'earnings_this_month' => $publisher->getEarningsThisMonth(),
            'call_logs' => $callLogs
        ];

        return response()->json($analytics);
    }

    public function bulkAssignDids(Request $request)
    {
        $validated = $request->validate([
            'publisher_ids' => 'required|array',
            'publisher_ids.*' => 'exists:publishers,id',
            'destination_did' => 'required|string'
        ]);

        $publishers = Publisher::whereIn('id', $validated['publisher_ids'])
            ->whereNull('tracking_did')
            ->get();

        $availableDids = Did::where('status', 'available')
            ->limit($publishers->count())
            ->get();

        if ($availableDids->count() < $publishers->count()) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough available DIDs for all selected publishers'
            ], 400);
        }

        $assigned = [];
        foreach ($publishers as $index => $publisher) {
            if (isset($availableDids[$index])) {
                $did = $availableDids[$index];
                $publisher->assignTrackingDid($did->number, $validated['destination_did']);
                $assigned[] = [
                    'publisher' => $publisher->name,
                    'tracking_did' => $did->number
                ];
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'DIDs assigned successfully',
            'assigned' => $assigned
        ]);
    }
}
