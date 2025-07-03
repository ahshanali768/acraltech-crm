<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LeadController extends Controller
{
    use AuthorizesRequests;
    // Agent: Show form and submit new lead
    public function create()
    {
        return view('leads.create');
    }
    // Show the agent add lead form
    public function addLeadForm()
    {
        // Fetch all active DIDs from campaigns
        $activeDids = \App\Models\Campaign::where('status', 'active')->pluck('did');
        $activeCampaigns = \App\Models\Campaign::where('status', 'active')->get(['did', 'campaign_name']);
        return view('agent.add_lead', [
            'activeDids' => $activeDids,
            'activeCampaigns' => $activeCampaigns
        ]);
    }
    public function store(Request $request)
    {
        // Clean phone number - remove all non-digit characters
        if ($request->filled('phone')) {
            $cleanPhone = preg_replace('/\D/', '', $request->phone);
            $request->merge(['phone' => $cleanPhone]);
        }
        
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => ['required', 'regex:/^\d{10}$/'],
            'did_number' => ['required', 'regex:/^\d{10}$/', 'exists:campaigns,did'],
            'address' => 'nullable',
            'city' => 'nullable',
            'state' => 'nullable',
            'zip' => ['required', 'regex:/^\d{5}$/'],
            'email' => 'nullable|email',
            'agent_name' => ['required'],
            'verifier_name' => ['nullable'],
            'campaign' => 'nullable',
            'notes' => 'nullable',
        ];
        
        // Add server-side agent_name validation: must match a user name or username (case-insensitive, status=active)
        $rules['agent_name'][] = function($attribute, $value, $fail) {
            $exists = \App\Models\User::where('status', 'active')
                ->where(function($q) use ($value) {
                    $q->whereRaw('LOWER(name) = ?', [strtolower($value)])
                      ->orWhereRaw('LOWER(username) = ?', [strtolower($value)]);
                })->exists();
            if (!$exists) {
                $fail('Agent Name must match a valid user.');
            }
        };
        
        // Add server-side verifier_name validation: must match a user name or username (case-insensitive, status=active) if present
        $rules['verifier_name'][] = function($attribute, $value, $fail) {
            if ($value) {
                $exists = \App\Models\User::where('status', 'active')
                    ->where(function($q) use ($value) {
                        $q->whereRaw('LOWER(name) = ?', [strtolower($value)])
                          ->orWhereRaw('LOWER(username) = ?', [strtolower($value)]);
                    })->exists();
                if (!$exists) {
                    $fail('Verifier Name must match a valid user.');
                }
            }
        };
        
        $request->validate($rules, [
            'phone.regex' => 'Phone must be exactly 10 digits.',
            'did_number.required' => 'DID Number is required for lead transfer.',
            'did_number.regex' => 'DID Number must be exactly 10 digits.',
            'did_number.exists' => 'DID Number must match a valid campaign DID.',
            'zip.required' => 'ZIP is required.',
            'zip.regex' => 'ZIP must be exactly 5 digits.',
        ]);
        // Auto-fill campaign by DID Number if present
        $campaignId = null;
        $campaignName = null;
        if ($request->filled('did_number')) {
            $campaign = \App\Models\Campaign::where('did', $request->did_number)->first();
            if ($campaign) {
                $campaignId = $campaign->id;
                $campaignName = $campaign->campaign_name;
            }
        }
        
        Lead::create([
            'user_id' => Auth::id(),
            'campaign_id' => $campaignId,
            'name' => $request->first_name . ' ' . $request->last_name,
            'contact_info' => $request->phone ?? $request->email ?? '',
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'did_number' => $request->did_number,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'email' => $request->email,
            'agent_name' => $request->agent_name,
            'verifier_name' => $request->verifier_name,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);
        
        // Track lead transfer activity
        $activity = \App\Models\AgentActivity::todayForUser(Auth::id());
        $activity->incrementTransfers();
        
        return redirect()->route('agent.leads.add')->with('success', 'Lead submitted!');
    }
    // Agent: List own leads
    public function index()
    {
        $leads = Lead::where('user_id', Auth::id())->get();
        return view('leads.index', compact('leads'));
    }
    // Admin: List all leads
    public function adminIndex()
    {
        $leads = Lead::all();
        return view('leads.admin', compact('leads'));
    }
    // Admin: Update lead status
    public function updateStatus(Request $request, Lead $lead)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);
        
        $oldStatus = $lead->status;
        $newStatus = $request->status;
        
        $lead->status = $newStatus;
        $lead->save();
        
        // Update agent activity statistics when status changes from pending
        if ($oldStatus === 'pending' && $lead->user_id) {
            $activity = \App\Models\AgentActivity::where('user_id', $lead->user_id)
                ->where('date', $lead->created_at->format('Y-m-d'))
                ->first();
                
            if ($activity) {
                if ($newStatus === 'approved') {
                    $activity->incrementApproved();
                    // Could add commission calculation here based on campaign
                } elseif ($newStatus === 'rejected') {
                    $activity->incrementRejected();
                }
            }
        }
        
        return back()->with('success', 'Lead status updated!');
    }
    // Agent: View and filter own leads
    public function viewLeads(Request $request)
    {
        $perPage = $request->input('per_page', 25);
        $query = Lead::with('campaign')->where('user_id', Auth::id());
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('campaign')) {
            $query->whereHas('campaign', function($q) use ($request) {
                $q->where('campaign_name', 'like', '%' . $request->campaign . '%');
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $leads = $query->orderByDesc('created_at')->paginate($perPage)->appends($request->except('page'));
        return view('agent.view_leads', compact('leads', 'perPage'));
    }
    // Agent: Edit a lead
    public function edit(Lead $lead)
    {
        $this->authorize('update', $lead); // Optional: add policy for security
        return view('agent.add_lead', compact('lead'));
    }
    // Agent: Update a lead
    public function update(Request $request, Lead $lead)
    {
        $this->authorize('update', $lead); // Optional: add policy for security
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => ['required', 'regex:/^\d{10}$/'],
            'did_number' => ['nullable', 'regex:/^\d{10}$/'],
            'address' => 'nullable',
            'city' => 'nullable',
            'state' => 'nullable',
            'zip' => ['required', 'regex:/^\d{5}$/'],
            'email' => 'nullable|email',
            'agent_name' => 'required',
            'verifier_name' => 'nullable',
            'campaign' => 'nullable',
            'notes' => 'nullable',
        ], [
            'phone.regex' => 'Phone must be exactly 10 digits.',
            'did_number.regex' => 'DID Number must be exactly 10 digits.',
            'zip.required' => 'ZIP is required.',
            'zip.regex' => 'ZIP must be exactly 5 digits.',
        ]);
        $lead->update($request->only([
            'first_name', 'last_name', 'phone', 'did_number', 'address', 'city', 'state', 'zip', 'email', 'agent_name', 'verifier_name', 'campaign', 'notes'
        ]));
        return redirect()->route('agent.leads.view')->with('success', 'Lead updated!');
    }
    // Agent: Delete a lead
    public function destroy(Lead $lead)
    {
        $this->authorize('delete', $lead); // Optional: add policy for security
        $lead->delete();
        return back()->with('success', 'Lead deleted!');
    }
    // Agent: Dashboard with stats, recent leads, and chart data
    public function agentDashboard()
    {
        $userId = Auth::id();
        $totalLeads = Lead::where('user_id', $userId)->count();
        $approvedLeads = Lead::where('user_id', $userId)->where('status', 'approved')->count();
        $pendingLeads = Lead::where('user_id', $userId)->where('status', 'pending')->count();
        $rejectedLeads = Lead::where('user_id', $userId)->where('status', 'rejected')->count();
        $recentLeads = Lead::where('user_id', $userId)->orderByDesc('created_at')->take(5)->get();
        // Chart data: leads by month (last 6 months) - SQLite compatible
        $leadsByMonth = Lead::where('user_id', $userId)
            ->selectRaw("strftime('%Y-%m', created_at) as month, COUNT(*) as count")
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');
        // Chart data: leads by status
        $statusCounts = [
            'approved' => $approvedLeads,
            'pending' => $pendingLeads,
            'rejected' => $rejectedLeads,
        ];
        return view('agent.dashboard', compact(
            'totalLeads', 'approvedLeads', 'pendingLeads', 'rejectedLeads',
            'recentLeads', 'leadsByMonth', 'statusCounts'
        ));
    }
    // Admin: Dashboard with stats, recent leads, and chart data
    public function adminDashboard()
    {
        // Basic lead statistics
        $totalLeads = Lead::count();
        $approvedLeads = Lead::where('status', 'approved')->count();
        $pendingLeads = Lead::where('status', 'pending')->count();
        $rejectedLeads = Lead::where('status', 'rejected')->count();
        
        // Recent leads with more details
        $recentLeads = Lead::with('campaign')
            ->orderByDesc('created_at')
            ->take(20)
            ->get();
        
        // Chart data: leads by month (last 12 months, MySQL compatible)
        $leadsByMonth = Lead::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');
        
        // Fill missing months with 0
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i)->format('Y-m');
            $months[$month] = $leadsByMonth->get($month, 0);
        }
        $leadsByMonth = collect($months);
        
        // Chart data: leads by status
        $statusCounts = [
            'approved' => $approvedLeads,
            'pending' => $pendingLeads,
            'rejected' => $rejectedLeads,
        ];
        
        // Additional analytics
        $todayLeads = Lead::whereDate('created_at', today())->count();
        $weekLeads = Lead::where('created_at', '>=', now()->startOfWeek())->count();
        $monthLeads = Lead::where('created_at', '>=', now()->startOfMonth())->count();
        
        // Campaign performance
        $topCampaigns = \App\Models\Campaign::withCount([
                'leads',
                'leads as approved_leads_count' => function($query) {
                    $query->where('status', 'approved');
                }
            ])
            ->orderByDesc('approved_leads_count')
            ->limit(5)
            ->get();
        
        // Agent performance
        $topAgents = \App\Models\User::where('role', 'agent')
            ->withCount([
                'leads as approved_leads_count' => function($query) {
                    $query->where('status', 'approved');
                }
            ])
            ->orderByDesc('approved_leads_count')
            ->limit(5)
            ->get();
        
        // DID statistics
        $totalDids = \App\Models\Did::count();
        $activeDids = \App\Models\Did::where('status', 'active')->count();
        $availableDids = \App\Models\Did::where('status', 'available')->count();
        $assignedDids = \App\Models\Did::where('status', 'assigned')->count();
        
        // Call statistics
        $totalCalls = \App\Models\CallLog::count();
        $todayCalls = \App\Models\CallLog::whereDate('started_at', today())->count();
        
        // Revenue calculations
        $totalRevenue = $topCampaigns->sum(function($campaign) {
            return $campaign->approved_leads_count * $campaign->payout_usd;
        });
        
        // Growth calculations
        $lastMonthLeads = Lead::where('created_at', '>=', now()->subMonths(2)->startOfMonth())
            ->where('created_at', '<', now()->subMonth()->startOfMonth())
            ->count();
        
        $leadsGrowth = $lastMonthLeads > 0 ? 
            round((($monthLeads - $lastMonthLeads) / $lastMonthLeads) * 100, 1) : 0;
        
        return view('admin.dashboard', compact(
            'totalLeads', 'approvedLeads', 'pendingLeads', 'rejectedLeads',
            'recentLeads', 'leadsByMonth', 'statusCounts', 'todayLeads', 
            'weekLeads', 'monthLeads', 'topCampaigns', 'topAgents',
            'totalDids', 'activeDids', 'availableDids', 'assignedDids', 'totalCalls', 
            'todayCalls', 'totalRevenue', 'leadsGrowth'
        ));
    }
    // Admin: View and filter all leads (with pagination, filters, status update)
    public function adminViewLeads(Request $request)
    {
        $perPage = $request->input('per_page', 25);
        $query = Lead::with('campaign'); // Eager load campaign relationship
        
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
        if ($request->filled('campaign')) {
            $query->where('campaign', 'like', '%' . $request->campaign . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('username')) {
            $query->where('agent_name', $request->username);
        }
        if ($request->filled('did_number')) {
            $query->where('did_number', $request->did_number);
        }
        if ($request->filled('search')) {
            $search = preg_replace('/\D/', '', $request->search);
            if (strlen($search) >= 5) {
                $query->where('phone', 'like', "%$search%");
            }
        }
        
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        if ($startDate && $endDate) {
            // Validate date format (YYYY-MM-DD)
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $startDate) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $endDate)) {
                $query->whereDate('created_at', '>=', $startDate)
                      ->whereDate('created_at', '<=', $endDate);
            }
        }
        $sortField = $request->input('sort_field', 'created_at');
        $sortDir = $request->input('sort', 'az') === 'za' ? 'desc' : 'asc';
        $allowedSortFields = [
            'created_at', 'name', 'phone', 'did_number', 'campaign', 'agent_name', 'verifier_name', 'notes', 'status'
        ];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDir);
        } else {
            $query->orderByDesc('created_at');
        }
        $leads = $query->paginate($perPage)->appends($request->except('page'));
        $agentUsers = \App\Models\User::where('role', 'agent')->orderBy('username')->get(['username', 'name']);
        $didCampaigns = \App\Models\Campaign::where('status', 'active')->orderBy('campaign_name')->get(['did', 'campaign_name']);
        return view('admin.view_leads', compact('leads', 'perPage', 'agentUsers', 'didCampaigns'));
    }
    public function importMayMonthData()
    {
        $file = database_path('maymonthdata.csv');
        if (!file_exists($file)) {
            return back()->with('error', 'CSV file not found.');
        }
        $handle = fopen($file, 'r');
        $header = fgetcsv($handle);
        $imported = 0;
        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);
            // Find agent by name or username (case-insensitive)
            $agent = \App\Models\User::whereRaw('LOWER(name) = ?', [strtolower($data['Agent Name'])])
                ->orWhereRaw('LOWER(username) = ?', [strtolower($data['Agent Name'])])
                ->first();
            // Find verifier by name or username (case-insensitive)
            $verifier = \App\Models\User::whereRaw('LOWER(name) = ?', [strtolower($data['Verifier Name'])])
                ->orWhereRaw('LOWER(username) = ?', [strtolower($data['Verifier Name'])])
                ->first();
            if (!$agent) {
                continue; // Skip if agent not found
            }
            // Parse date (format: m/d/Y or m/d/Y H:i)
            $createdAt = null;
            if (!empty($data['Date'])) {
                $createdAt = \Carbon\Carbon::createFromFormat('m/d/Y', $data['Date']);
                if (!$createdAt) {
                    $createdAt = \Carbon\Carbon::parse($data['Date']);
                }
            }
            \App\Models\Lead::create([
                'user_id' => $agent->id,
                'first_name' => $data['First Name'] ?? '',
                'last_name' => $data['Last Name'] ?? '',
                'phone' => $data['Phone'] ?? '',
                'did_number' => $data['DID Number'] ?? '',
                'address' => $data['Address'] ?? '',
                'city' => $data['City'] ?? '',
                'state' => $data['State'] ?? '',
                'zip' => $data['ZIP'] ?? '',
                'email' => $data['Email'] ?? '',
                'agent_name' => $agent->name,
                'username' => $agent->username,
                'verifier_name' => $verifier ? $verifier->name : ($data['Verifier Name'] ?? ''),
                'campaign' => '',
                'notes' => '',
                'status' => 'pending',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
            $imported++;
        }
        fclose($handle);
        return back()->with('success', "Imported $imported leads from maymonthdata.csv");
    }
    // Admin: Import leads from CSV/Excel with column mapping
    public function importLeads(Request $request)
    {
        $request->validate([
            'numbers' => 'required|string',
            'status' => 'required|in:pending,approved,rejected',
        ]);
        // Extract numbers from textarea (allow comma, space, newline separated, and ignore non-digit chars)
        $numbers = preg_split('/[\s,]+/', $request->numbers);
        $numbers = array_map(function($n) {
            return preg_replace('/\D/', '', $n); // Remove non-digits
        }, $numbers);
        $numbers = array_filter($numbers, function($n) {
            return preg_match('/^\d{10}$/', $n);
        });
        $numbers = array_unique($numbers);
        $updated = 0;
        $notFound = 0;
        $notFoundNumbers = [];
        foreach ($numbers as $number) {
            $lead = \App\Models\Lead::where('phone', $number)->first();
            if ($lead) {
                $lead->status = $request->status;
                $lead->save();
                $updated++;
            } else {
                $notFound++;
                $notFoundNumbers[] = $number;
            }
        }
        $msg = "Bulk status update complete. Updated: $updated, Not found: $notFound";
        if ($notFound > 0) {
            $msg .= ". Not found numbers: " . implode(', ', $notFoundNumbers);
        }
        return back()->with('success', $msg);
    }
    // Admin: Export leads as CSV
    public function exportLeads(Request $request)
    {
        $query = \App\Models\Lead::query();
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('campaign')) {
            $query->where('campaign', 'like', '%' . $request->campaign . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('username')) {
            $query->where('username', $request->username);
        }
        $leads = $query->orderByDesc('created_at')->get();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="leads_export_' . now()->format('Ymd_His') . '.csv"',
        ];
        $columns = [
            'Date', 'First Name', 'Last Name', 'Phone', 'DID', 'Address', 'City', 'State', 'ZIP', 'Email', 'Agent Name', 'Verifier Name', 'Campaign', 'Notes', 'Status'
        ];
        $callback = function() use ($leads, $columns) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $columns);
            foreach ($leads as $lead) {
                fputcsv($out, [
                    $lead->created_at ? $lead->created_at->format('Y-m-d H:i:s') : '',
                    $lead->first_name,
                    $lead->last_name,
                    $lead->phone,
                    $lead->did_number,
                    $lead->address,
                    $lead->city,
                    $lead->state,
                    $lead->zip,
                    $lead->email,
                    $lead->agent_name,
                    $lead->verifier_name,
                    $lead->campaign,
                    $lead->notes,
                    $lead->status,
                ]);
            }
            fclose($out);
        };
        return response()->stream($callback, 200, $headers);
    }
    // Admin: Get lead as JSON for edit modal
    public function getLeadJson($id)
    {
        $lead = \App\Models\Lead::findOrFail($id);
        return response()->json($lead);
    }
    // Admin: Update lead from modal
    public function updateLead(Request $request, $id)
    {
        $lead = \App\Models\Lead::findOrFail($id);
        $lead->update($request->only([
            'first_name', 'last_name', 'phone', 'did_number', 'email', 'notes',
            'address', 'city', 'state', 'zip', 'agent_name', 'verifier_name', 'campaign', 'status'
        ]));
        return back()->with('success', 'Lead updated successfully!');
    }
    // Admin: Delete lead from modal
    public function deleteLead($id)
    {
        $lead = \App\Models\Lead::findOrFail($id);
        $lead->delete();
        return response()->json(['success' => true]);
    }
    // Admin: Payment aggregation for agent commissions + verification commission
    public function adminPayments()
    {
        // Get all unique usernames from both agent_name and verifier_name fields
        $allUsers = collect();
        
        // Get agent usernames
        $agentUsernames = DB::table('leads')
            ->select('agent_name')
            ->whereNotNull('agent_name')
            ->where('agent_name', '!=', '')
            ->distinct()
            ->pluck('agent_name');
            
        // Get verifier usernames
        $verifierUsernames = DB::table('leads')
            ->select('verifier_name')
            ->whereNotNull('verifier_name')
            ->where('verifier_name', '!=', '')
            ->distinct()
            ->pluck('verifier_name');
            
        // Combine all unique usernames
        $allUsernames = $agentUsernames->merge($verifierUsernames)->unique();
        
        $paymentData = [];
        
        foreach ($allUsernames as $username) {
            // Get user's full name - check both name and username fields
            $user = \App\Models\User::where('username', $username)
                ->orWhere('name', $username)
                ->first();
            $fullName = $user ? $user->name : $username;
            
            // Initialize data
            $data = [
                'username' => $username,
                'full_name' => $fullName,
                'approved_leads_as_agent' => 0,
                'lead_commission' => 0,
                'verified_leads_as_verifier' => 0,
                'verification_commission' => 0,
                'rejected_leads_as_agent' => 0,
                'roles' => []
            ];
            
            // Calculate agent commission (approved leads by this agent)
            $agentLeads = DB::table('leads')
                ->join('campaigns', 'leads.campaign_id', '=', 'campaigns.id')
                ->where('leads.agent_name', $username)
                ->where('leads.status', 'approved')
                ->select('campaigns.commission_inr')
                ->get();
                
            $data['approved_leads_as_agent'] = $agentLeads->count();
            $data['lead_commission'] = $agentLeads->sum('commission_inr');
            
            if ($data['approved_leads_as_agent'] > 0) {
                $data['roles'][] = 'Agent';
            }
            
            // Calculate verification commission (approved leads verified by this user)
            $verifiedLeads = DB::table('leads')
                ->where('verifier_name', $username)
                ->where('status', 'approved')
                ->count();
                
            $data['verified_leads_as_verifier'] = $verifiedLeads;
            $data['verification_commission'] = $verifiedLeads * 200; // Fixed ₹200 per verification
            
            if ($data['verified_leads_as_verifier'] > 0) {
                $data['roles'][] = 'Verifier';
            }
            
            // Calculate rejected leads (as agent)
            $data['rejected_leads_as_agent'] = DB::table('leads')
                ->where('agent_name', $username)
                ->where('status', 'rejected')
                ->count();
            
            // Only include users who have at least some activity
            if ($data['approved_leads_as_agent'] > 0 || $data['verified_leads_as_verifier'] > 0 || $data['rejected_leads_as_agent'] > 0) {
                $paymentData[] = $data;
            }
        }
        
        // Sort by total earnings (descending)
        usort($paymentData, function($a, $b) {
            $totalA = $a['lead_commission'] + $a['verification_commission'];
            $totalB = $b['lead_commission'] + $b['verification_commission'];
            return $totalB - $totalA;
        });
        
        return view('admin.payment', compact('paymentData'));
    }
    // Admin: Mark payment as paid
    public function markPaymentPaid(Request $request, $username)
    {
        $user = \App\Models\User::where('username', $username)->first();
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        // Calculate total earnings for this user
        $paymentData = $this->calculateUserPayment($username);
        $totalEarnings = $paymentData['lead_commission'] + $paymentData['verification_commission'];
        
        // Update user payment status
        $user->update([
            'total_earnings' => $user->total_earnings + $totalEarnings,
            'paid_amount' => $user->paid_amount + $totalEarnings,
            'pending_amount' => max(0, $user->pending_amount - $totalEarnings),
            'payment_status' => 'paid',
            'last_payment_date' => now(),
            'payment_notes' => $request->input('notes', 'Payment processed via admin panel')
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Payment marked as paid successfully',
            'total_paid' => $totalEarnings
        ]);
    }
    // Admin: Get payment details for view modal
    public function getPaymentDetails($username)
    {
        \Log::info("Getting payment details for username: " . $username);
        
        $user = \App\Models\User::where('username', $username)->first();
        
        if (!$user) {
            \Log::error("User not found with username: " . $username);
            return response()->json(['error' => 'User not found'], 404);
        }
        
        \Log::info("Found user: " . $user->name);
        
        try {
            $paymentData = $this->calculateUserPayment($username);
            
            // Get lead details for this user
            $agentLeads = \App\Models\Lead::with('campaign')
                ->where('agent_name', $username)
                ->where('status', 'approved')
                ->get();
                
            $verifiedLeads = \App\Models\Lead::with('campaign')
                ->where('verifier_name', $username)
                ->where('status', 'approved')
                ->get();
                
            $rejectedLeads = \App\Models\Lead::with('campaign')
                ->where('agent_name', $username)
                ->where('status', 'rejected')
                ->get();
            
            \Log::info("Payment data calculated successfully", [
                'agent_leads_count' => $agentLeads->count(),
                'verified_leads_count' => $verifiedLeads->count(),
                'rejected_leads_count' => $rejectedLeads->count()
            ]);
            
            return response()->json([
                'user' => $user,
                'payment_summary' => $paymentData,
                'agent_leads' => $agentLeads,
                'verified_leads' => $verifiedLeads,
                'rejected_leads' => $rejectedLeads
            ]);
            
        } catch (\Exception $e) {
            \Log::error("Error calculating payment data: " . $e->getMessage());
            return response()->json(['error' => 'Error calculating payment data: ' . $e->getMessage()], 500);
        }
    }
    // Helper method to calculate payment data for a specific user
    private function calculateUserPayment($username)
    {
        $user = \App\Models\User::where('username', $username)->first();
        $fullName = $user ? $user->name : $username;
        
        $data = [
            'username' => $username,
            'full_name' => $fullName,
            'approved_leads_as_agent' => 0,
            'lead_commission' => 0,
            'verified_leads_as_verifier' => 0,
            'verification_commission' => 0,
            'rejected_leads_as_agent' => 0,
            'roles' => []
        ];
        
        // Calculate agent commission
        $agentLeads = DB::table('leads')
            ->join('campaigns', 'leads.campaign_id', '=', 'campaigns.id')
            ->where('leads.agent_name', $username)
            ->where('leads.status', 'approved')
            ->select('campaigns.commission_inr')
            ->get();
            
        $data['approved_leads_as_agent'] = $agentLeads->count();
        $data['lead_commission'] = $agentLeads->sum('commission_inr');
        
        if ($data['approved_leads_as_agent'] > 0) {
            $data['roles'][] = 'Agent';
        }
        
        // Calculate verification commission
        $verifiedLeads = DB::table('leads')
            ->where('verifier_name', $username)
            ->where('status', 'approved')
            ->count();
            
        $data['verified_leads_as_verifier'] = $verifiedLeads;
        $data['verification_commission'] = $verifiedLeads * 200;
        
        if ($data['verified_leads_as_verifier'] > 0) {
            $data['roles'][] = 'Verifier';
        }
        
        // Calculate rejected leads
        $data['rejected_leads_as_agent'] = DB::table('leads')
            ->where('agent_name', $username)
            ->where('status', 'rejected')
            ->count();
        
        return $data;
    }
}
