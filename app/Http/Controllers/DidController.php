<?php

namespace App\Http\Controllers;

use App\Models\Did;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DidController extends Controller
{
    public function index(Request $request)
    {
        $query = Did::with('campaign');
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('area_code')) {
            $query->where('area_code', $request->area_code);
        }
        
        if ($request->filled('campaign_id')) {
            $query->where('campaign_id', $request->campaign_id);
        }
        
        if ($request->filled('search')) {
            $query->where('number', 'like', '%' . $request->search . '%');
        }
        
        $dids = $query->paginate(20);
        $campaigns = Campaign::all();
        
        return view('admin.dids.index', compact('dids', 'campaigns'));
    }

    public function create()
    {
        $campaigns = Campaign::all();
        return view('admin.dids.create', compact('campaigns'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'generation_type' => 'required|in:single,bulk',
            'area_code' => 'required|string|size:3',
            'country_code' => 'required|string|size:2',
            'provider' => 'required|string',
            'count' => 'required_if:generation_type,bulk|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            if ($request->generation_type === 'single') {
                $did = Did::generateDid(
                    $request->area_code,
                    $request->country_code,
                    $request->provider
                );
                
                return redirect()->route('admin.dids.index')
                    ->with('success', 'DID generated successfully: ' . $did->formatted_number);
            } else {
                $dids = Did::bulkGenerateDids(
                    $request->area_code,
                    $request->count,
                    $request->country_code
                );
                
                return redirect()->route('admin.dids.index')
                    ->with('success', count($dids) . ' DIDs generated successfully');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to generate DIDs: ' . $e->getMessage());
        }
    }

    public function show(Did $did)
    {
        $did->load(['campaign', 'leads', 'callLogs']);
        
        // Get call analytics
        $callStats = [
            'total_calls' => $did->callLogs()->count(),
            'answered_calls' => $did->callLogs()->answered()->count(),
            'missed_calls' => $did->callLogs()->missed()->count(),
            'total_duration' => $did->callLogs()->sum('duration'),
            'average_duration' => $did->callLogs()->avg('duration'),
            'total_cost' => $did->callLogs()->sum('cost'),
            'conversion_rate' => $did->call_conversion_rate
        ];
        
        return view('admin.dids.show', compact('did', 'callStats'));
    }

    public function edit(Did $did)
    {
        $campaigns = Campaign::all();
        return view('admin.dids.edit', compact('did', 'campaigns'));
    }

    public function update(Request $request, Did $did)
    {
        $validator = Validator::make($request->all(), [
            'campaign_id' => 'nullable|exists:campaigns,id',
            'status' => 'required|in:available,active,expired,suspended',
            'routing_destination' => 'nullable|string',
            'call_forwarding_enabled' => 'boolean',
            'recording_enabled' => 'boolean',
            'analytics_enabled' => 'boolean'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $did->update($request->all());

        return redirect()->route('admin.dids.show', $did)
            ->with('success', 'DID updated successfully');
    }

    public function destroy(Did $did)
    {
        if ($did->leads()->count() > 0) {
            return back()->with('error', 'Cannot delete DID with associated leads');
        }

        $did->delete();

        return redirect()->route('admin.dids.index')
            ->with('success', 'DID deleted successfully');
    }

    public function assignToCampaign(Request $request, Did $did)
    {
        $validator = Validator::make($request->all(), [
            'campaign_id' => 'required|exists:campaigns,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid campaign'], 400);
        }

        $did->assignToCampaign($request->campaign_id);

        return response()->json(['success' => 'DID assigned to campaign successfully']);
    }

    public function release(Did $did)
    {
        $did->release();

        return response()->json(['success' => 'DID released successfully']);
    }

    public function bulkAssign(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'did_ids' => 'required|array',
            'did_ids.*' => 'exists:dids,id',
            'campaign_id' => 'required|exists:campaigns,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid data'], 400);
        }

        $dids = Did::whereIn('id', $request->did_ids)->get();
        
        foreach ($dids as $did) {
            $did->assignToCampaign($request->campaign_id);
        }

        return response()->json(['success' => count($dids) . ' DIDs assigned successfully']);
    }

    public function analytics(Request $request)
    {
        $query = Did::with(['campaign', 'callLogs']);
        
        // Date range filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereHas('callLogs', function($q) use ($request) {
                $q->whereBetween('started_at', [$request->start_date, $request->end_date]);
            });
        }
        
        $dids = $query->get();
        
        $analytics = [
            'total_dids' => $dids->count(),
            'active_dids' => $dids->where('status', 'active')->count(),
            'available_dids' => $dids->where('status', 'available')->count(),
            'total_calls' => $dids->sum('total_calls'),
            'total_revenue' => $dids->sum(function($did) {
                return $did->leads()->where('status', 'approved')->count() * 
                       ($did->campaign->payout_usd ?? 0);
            }),
            'average_conversion_rate' => $dids->avg('call_conversion_rate'),
            'top_performing_dids' => $dids->sortByDesc('call_conversion_rate')->take(10),
            'expiring_dids' => $dids->filter(function($did) {
                return $did->is_expiring;
            })
        ];
        
        return view('admin.dids.analytics', compact('analytics', 'dids'));
    }
}
