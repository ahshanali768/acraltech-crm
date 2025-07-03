<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class CampaignController extends Controller
{
    /**
     * Display a listing of campaigns
     */
    public function index(Request $request): JsonResponse
    {
        $query = Campaign::withCount(['leads']);
        
        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $campaigns = $query->paginate($request->get('per_page', 25));
        
        return response()->json($campaigns);
    }

    /**
     * Store a newly created campaign
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:campaigns',
            'description' => 'nullable|string|max:1000',
            'status' => ['required', Rule::in(['active', 'inactive', 'paused'])],
            'target_criteria' => 'nullable|json',
            'budget_daily' => 'nullable|numeric|min:0',
            'budget_monthly' => 'nullable|numeric|min:0',
            'lead_cap_daily' => 'nullable|integer|min:1',
            'lead_cap_monthly' => 'nullable|integer|min:1',
            'payout_amount' => 'nullable|numeric|min:0',
            'category' => 'nullable|string|max:100',
        ]);
        
        $campaign = Campaign::create($validated);
        
        return response()->json([
            'message' => 'Campaign created successfully',
            'campaign' => $campaign
        ], 201);
    }

    /**
     * Display the specified campaign
     */
    public function show(Campaign $campaign): JsonResponse
    {
        $campaign->loadCount(['leads', 'leads as approved_leads' => function ($query) {
            $query->where('status', 'approved');
        }]);
        
        return response()->json($campaign);
    }

    /**
     * Update the specified campaign
     */
    public function update(Request $request, Campaign $campaign): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255|unique:campaigns,name,' . $campaign->id,
            'description' => 'nullable|string|max:1000',
            'status' => ['sometimes', Rule::in(['active', 'inactive', 'paused'])],
            'target_criteria' => 'nullable|json',
            'budget_daily' => 'nullable|numeric|min:0',
            'budget_monthly' => 'nullable|numeric|min:0',
            'lead_cap_daily' => 'nullable|integer|min:1',
            'lead_cap_monthly' => 'nullable|integer|min:1',
            'payout_amount' => 'nullable|numeric|min:0',
            'category' => 'nullable|string|max:100',
        ]);
        
        $campaign->update($validated);
        
        return response()->json([
            'message' => 'Campaign updated successfully',
            'campaign' => $campaign
        ]);
    }

    /**
     * Remove the specified campaign
     */
    public function destroy(Campaign $campaign): JsonResponse
    {
        if ($campaign->leads()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete campaign with existing leads'
            ], 422);
        }
        
        $campaign->delete();
        
        return response()->json([
            'message' => 'Campaign deleted successfully'
        ]);
    }

    /**
     * Toggle campaign status
     */
    public function toggleStatus(Campaign $campaign): JsonResponse
    {
        $newStatus = $campaign->status === 'active' ? 'inactive' : 'active';
        $campaign->update(['status' => $newStatus]);
        
        return response()->json([
            'message' => "Campaign status changed to {$newStatus}",
            'campaign' => $campaign
        ]);
    }
}
