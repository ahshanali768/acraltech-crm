<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class LeadController extends Controller
{
    /**
     * Display a listing of leads with pagination and filtering
     */
    public function index(Request $request): JsonResponse
    {
        $query = Lead::with(['campaign']);
        
        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('campaign_id')) {
            $query->where('campaign_id', $request->campaign_id);
        }
        
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $leads = $query->paginate($request->get('per_page', 25));
        
        return response()->json($leads);
    }

    /**
     * Store a newly created lead
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:50',
            'zip_code' => 'nullable|string|max:10',
            'age' => 'nullable|integer|min:18|max:120',
            'annual_income' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'lead_source' => 'nullable|string|max:100',
        ]);
          $validated['status'] = 'pending';
        $validated['agent_name'] = auth()->user()->name;
        
        $lead = Lead::create($validated);
        $lead->load('campaign');
        
        return response()->json([
            'message' => 'Lead created successfully',
            'lead' => $lead
        ], 201);
    }

    /**
     * Display the specified lead
     */
    public function show(Lead $lead): JsonResponse
    {
        $lead->load(['campaign', 'agent']);
        return response()->json($lead);
    }

    /**
     * Update the specified lead
     */
    public function update(Request $request, Lead $lead): JsonResponse
    {
        $validated = $request->validate([
            'campaign_id' => 'sometimes|exists:campaigns,id',
            'full_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'phone' => 'sometimes|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:50',
            'zip_code' => 'nullable|string|max:10',
            'age' => 'nullable|integer|min:18|max:120',
            'annual_income' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'lead_source' => 'nullable|string|max:100',
        ]);
        
        $lead->update($validated);
        $lead->load('campaign');
        
        return response()->json([
            'message' => 'Lead updated successfully',
            'lead' => $lead
        ]);
    }

    /**
     * Remove the specified lead
     */
    public function destroy(Lead $lead): JsonResponse
    {
        $lead->delete();
        
        return response()->json([
            'message' => 'Lead deleted successfully'
        ]);
    }

    /**
     * Update lead status
     */
    public function updateStatus(Request $request, Lead $lead): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'approved', 'rejected'])],
            'notes' => 'nullable|string|max:1000'
        ]);
        
        $lead->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $lead->notes
        ]);
        
        return response()->json([
            'message' => 'Lead status updated successfully',
            'lead' => $lead
        ]);
    }

    /**
     * Perform bulk actions on multiple leads
     */
    public function bulkAction(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'action' => ['required', Rule::in(['approve', 'reject', 'delete'])],
            'lead_ids' => 'required|array|min:1',
            'lead_ids.*' => 'exists:leads,id'
        ]);
        
        $leads = Lead::whereIn('id', $validated['lead_ids']);
        $count = $leads->count();
        
        switch ($validated['action']) {
            case 'approve':
                $leads->update(['status' => 'approved']);
                break;
            case 'reject':
                $leads->update(['status' => 'rejected']);
                break;
            case 'delete':
                $leads->delete();
                break;
        }
        
        return response()->json([
            'message' => "Bulk action '{$validated['action']}' applied to {$count} leads successfully"
        ]);
    }
}
