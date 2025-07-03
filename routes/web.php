<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\PublisherController;
use App\Http\Controllers\Agent\LeadController as AgentLeadController;
use App\Http\Controllers\Publisher\DashboardController as PublisherDashboardController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Contact form route
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Admin dashboard route using LeadController
Route::get('/admin/dashboard', [\App\Http\Controllers\LeadController::class, 'adminDashboard'])->name('admin.dashboard')->middleware(['auth', 'role:admin']);

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::post('/admin/leads/{lead}/status', [\App\Http\Controllers\LeadController::class, 'updateStatus'])->name('admin.leads.updateStatus');
    
    // Replace simple closure with proper controller method
    Route::get('/admin/view_leads', [\App\Http\Controllers\LeadController::class, 'adminViewLeads'])->name('admin.view_leads');
    Route::get('/admin/payment', [LeadController::class, 'adminPayments'])->name('admin.payment');
    
    // Payment action routes
    Route::post('/admin/payment/{username}/pay', [LeadController::class, 'markPaymentPaid'])->name('admin.payment.pay');
    Route::get('/admin/payment/{username}/details', [LeadController::class, 'getPaymentDetails'])->name('admin.payment.details');
    
    Route::get('/admin/analytics', function(Request $request) {
        $totalLeads = \App\Models\Lead::count();
        $approvedLeads = \App\Models\Lead::where('status', 'approved')->count();
        $pendingLeads = \App\Models\Lead::where('status', 'pending')->count();
        $rejectedLeads = \App\Models\Lead::where('status', 'rejected')->count();
        $leadsGrowth = 12.5;
        $conversionRate = $totalLeads > 0 ? round(($approvedLeads / $totalLeads) * 100, 1) : 0;
        $revenue = 45250;
        $approvalRate = $totalLeads > 0 ? round(($approvedLeads / $totalLeads) * 100, 1) : 0;
        
        $chartLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $chartData = [12, 19, 15, 25, 22, 30, 28];
        
        $topCampaigns = \App\Models\Campaign::withCount([
            'leads',
            'leads as approved_leads_count' => function($query) {
                $query->where('status', 'approved');
            }
        ])->orderByDesc('approved_leads_count')->limit(5)->get();
        
        $topAgents = \App\Models\User::where('role', 'agent')
            ->withCount([
                'leads as approved_leads_count' => function($query) {
                    $query->where('status', 'approved');
                }
            ])
            ->orderByDesc('approved_leads_count')
            ->limit(5)
            ->get();
        
        return view('admin.analytics', compact(
            'totalLeads', 'approvedLeads', 'pendingLeads', 'rejectedLeads',
            'leadsGrowth', 'conversionRate', 'revenue', 'approvalRate',
            'chartLabels', 'chartData', 'topCampaigns', 'topAgents'
        ));
    })->name('admin.analytics');
    Route::get('/admin/settings', function () {
        return view('admin.settings');
    })->name('admin.settings');
    
    // Settings partial routes for AJAX loading
    Route::get('/admin/settings/partial/attendance', function() {
        $allowedLocations = \App\Models\AllowedLocation::orderBy('created_at', 'desc')->get();
        
        // Load attendance settings from database with defaults
        $attendanceSettings = [
            'login_time' => \App\Models\Setting::get('attendance.login_time', '09:00'),
            'logout_time' => \App\Models\Setting::get('attendance.logout_time', '18:00'),
            'email_notifications' => \App\Models\Setting::get('attendance.email_notifications', true),
            'bell_notifications' => \App\Models\Setting::get('attendance.bell_notifications', true),
            'grace_period' => \App\Models\Setting::get('attendance.grace_period', 15),
            'late_fee_amount' => \App\Models\Setting::get('attendance.late_fee_amount', 100),
            'auto_absent_threshold' => \App\Models\Setting::get('attendance.auto_absent_threshold', 4),
            'half_day_threshold' => \App\Models\Setting::get('attendance.half_day_threshold', 4),
            'half_day_deduction' => \App\Models\Setting::get('attendance.half_day_deduction', 500),
            'monthly_absence_limit' => \App\Models\Setting::get('attendance.monthly_absence_limit', 3),
            'absence_penalty' => \App\Models\Setting::get('attendance.absence_penalty', 250)
        ];
        
        return view('admin.partials.attendance-management', compact('allowedLocations', 'attendanceSettings'));
    })->name('admin.settings.partial.attendance');
    
    Route::get('/admin/settings/partial/campaign', function() {
        $campaigns = \App\Models\Campaign::orderBy('created_at', 'desc')->get();
        $currentDidPassword = \App\Models\DailyPassword::whereDate('password_date', today())->value('password_code') ?? 'N/A';
        
        return view('admin.partials.campaign-management', compact('campaigns', 'currentDidPassword'));
    })->name('admin.settings.partial.campaign');
    
    Route::get('/admin/settings/partial/user', function() {
        return view('admin.partials.user-management');
    })->name('admin.settings.partial.user');
    
    // Settings form handlers
    Route::post('/admin/settings/general', function(Request $request) {
        $request->validate([
            'lead_auto_assignment' => 'required|string|in:disabled,round_robin,skill_based,performance_based',
            'duplicate_detection' => 'required|string|in:disabled,phone_only,email_only,phone_email',
            'max_leads_per_agent' => 'required|integer|min:0|max:500',
            'followup_reminder' => 'required|string|in:disabled,30_minutes,1_hour,4_hours,24_hours',
            'default_country_code' => 'required|string|in:+91,+1,+44,+61,+971',
            'business_hours_start' => 'required|date_format:H:i',
            'business_hours_end' => 'required|date_format:H:i',
            'lead_aging_alert' => 'required|integer|min:0|max:30',
            'default_campaign_status' => 'required|string|in:draft,active,paused',
            'daily_call_target' => 'required|integer|min:0|max:500',
            'min_call_duration' => 'required|integer|min:0|max:3600',
            'quality_threshold' => 'required|string|in:6,7,8,9',
            'auto_approval_rate' => 'required|numeric|min:0|max:100',
        ]);
        
        // Save CRM business settings to database using Setting model
        \App\Models\Setting::set('general.lead_auto_assignment', $request->lead_auto_assignment, 'string');
        \App\Models\Setting::set('general.duplicate_detection', $request->duplicate_detection, 'string');
        \App\Models\Setting::set('general.max_leads_per_agent', $request->max_leads_per_agent, 'integer');
        \App\Models\Setting::set('general.followup_reminder', $request->followup_reminder, 'string');
        \App\Models\Setting::set('general.default_country_code', $request->default_country_code, 'string');
        \App\Models\Setting::set('general.business_hours_start', $request->business_hours_start, 'string');
        \App\Models\Setting::set('general.business_hours_end', $request->business_hours_end, 'string');
        \App\Models\Setting::set('general.lead_aging_alert', $request->lead_aging_alert, 'integer');
        \App\Models\Setting::set('general.default_campaign_status', $request->default_campaign_status, 'string');
        \App\Models\Setting::set('general.daily_call_target', $request->daily_call_target, 'integer');
        \App\Models\Setting::set('general.min_call_duration', $request->min_call_duration, 'integer');
        \App\Models\Setting::set('general.quality_threshold', $request->quality_threshold, 'string');
        \App\Models\Setting::set('general.auto_approval_rate', $request->auto_approval_rate, 'numeric');
        
        return redirect()->route('admin.settings')->with('success', 'CRM business settings updated successfully');
    })->name('admin.settings.general');
    
    // Security Settings Routes
    Route::get('/admin/settings/security', [\App\Http\Controllers\Admin\SecuritySettingsController::class, 'index'])
        ->name('admin.settings.security');
    Route::put('/admin/settings/security', [\App\Http\Controllers\Admin\SecuritySettingsController::class, 'update'])
        ->name('admin.settings.security.update');

    // Attendance Management Routes
    Route::prefix('admin/attendance')->name('admin.attendance.')->middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\Admin\AttendanceController::class, 'show'])->name('show');
        Route::get('/export', [\App\Http\Controllers\Admin\AttendanceController::class, 'export'])->name('export');
    });

    Route::post('/admin/settings/notifications', function(Request $request) {
        // Handle notification settings
        return redirect()->route('admin.settings')->with('success', 'Notification settings updated successfully');
    })->name('admin.settings.notifications');
    
    // Attendance & Agent Settings form handler
    Route::post('/admin/settings/attendance', function(Request $request) {
        $request->validate([
            'login_time' => 'required|date_format:H:i',
            'logout_time' => 'required|date_format:H:i',
            'email_notifications' => 'nullable|boolean',
            'bell_notifications' => 'nullable|boolean',
            'grace_period' => 'required|integer|min:0|max:60',
            'late_fee_amount' => 'required|numeric|min:0',
            'auto_absent_threshold' => 'required|integer|min:1|max:24',
            'half_day_threshold' => 'required|numeric|min:0|max:24',
            'half_day_deduction' => 'required|numeric|min:0',
            'monthly_absence_limit' => 'required|integer|min:0|max:31',
            'absence_penalty' => 'required|numeric|min:0',
        ]);
        
        // Save each setting to the database
        \App\Models\Setting::set('attendance.login_time', $request->login_time, 'string');
        \App\Models\Setting::set('attendance.logout_time', $request->logout_time, 'string');
        \App\Models\Setting::set('attendance.email_notifications', $request->email_notifications ?? false, 'boolean');
        \App\Models\Setting::set('attendance.bell_notifications', $request->bell_notifications ?? false, 'boolean');
        \App\Models\Setting::set('attendance.grace_period', $request->grace_period, 'integer');
        \App\Models\Setting::set('attendance.late_fee_amount', $request->late_fee_amount, 'numeric');
        \App\Models\Setting::set('attendance.auto_absent_threshold', $request->auto_absent_threshold, 'integer');
        \App\Models\Setting::set('attendance.half_day_threshold', $request->half_day_threshold, 'numeric');
        \App\Models\Setting::set('attendance.half_day_deduction', $request->half_day_deduction, 'numeric');
        \App\Models\Setting::set('attendance.monthly_absence_limit', $request->monthly_absence_limit, 'integer');
        \App\Models\Setting::set('attendance.absence_penalty', $request->absence_penalty, 'numeric');
        
        return redirect()->route('admin.settings')->with('success', 'Attendance settings updated successfully');
    })->name('admin.settings.attendance');
    
    // Integration Settings form handler
    Route::post('/admin/settings/integration', function(Request $request) {
        $request->validate([
            'crm_provider' => 'nullable|string',
            'crm_api_key' => 'nullable|string',
            'smtp_host' => 'nullable|string',
            'smtp_port' => 'nullable|integer',
            'smtp_username' => 'nullable|email',
            'smtp_password' => 'nullable|string',
        ]);
        // Save logic here (e.g., to settings table or config)
        // For now, just return success message
        return redirect()->route('admin.settings')->with('success', 'Integration settings updated successfully');
    })->name('admin.settings.integration');
    
    // Campaign Settings form handler
    Route::post('/admin/settings/campaign', function(Request $request) {
        $request->validate([
            'default_commission_inr' => 'nullable|numeric|min:0',
            'default_payout_usd' => 'nullable|numeric|min:0',
            'auto_approve_campaigns' => 'required|boolean',
            'default_campaign_duration' => 'nullable|integer|min:1',
            'min_conversion_rate' => 'nullable|numeric|min:0|max:100',
            'quality_score_threshold' => 'nullable|numeric|min:0|max:10',
            'min_call_duration' => 'nullable|integer|min:0',
            'enable_realtime_tracking' => 'required|boolean',
        ]);
        // Save logic here (e.g., to settings table or config)
        // For now, just return success message
        return redirect()->route('admin.settings')->with('success', 'Campaign settings updated successfully');
    })->name('admin.settings.campaign');
    
    // User Settings form handler
    Route::post('/admin/settings/user', function(Request $request) {
        $request->validate([
            'allow_registration' => 'required|boolean',
            'default_user_role' => 'required|string|in:agent,publisher,user',
            'email_verification' => 'required|boolean',
            'two_factor_auth' => 'required|string|in:optional,required,disabled',
            'password_min_length' => 'required|integer|min:6|max:32',
            'session_timeout' => 'required|integer|min:5|max:1440',
            'max_login_attempts' => 'required|integer|min:3|max:10',
            'lockout_duration' => 'required|integer|min:5|max:60',
            'send_welcome_email' => 'nullable|boolean',
            'account_activity_alerts' => 'nullable|boolean',
        ]);
        // Save logic here (e.g., to settings table or config)
        // For now, just return success message
        return redirect()->route('admin.settings')->with('success', 'User settings updated successfully');
    })->name('admin.settings.user');
    
    // Add Allowed Location
    Route::post('/admin/settings/add-location', [\App\Http\Controllers\Admin\AllowedLocationController::class, 'store'])->name('admin.settings.add_location');
    Route::post('/admin/settings/toggle-location/{id}', [\App\Http\Controllers\Admin\AllowedLocationController::class, 'toggle'])->name('admin.settings.toggle_location');
    Route::delete('/admin/settings/delete-location/{id}', [\App\Http\Controllers\Admin\AllowedLocationController::class, 'destroy'])->name('admin.settings.delete_location');
    
    // Campaign management
    Route::get('/admin/manage_campaigns', function(Request $request) {
        $campaigns = \App\Models\Campaign::orderBy('created_at', 'desc')->get();
        $currentDidPassword = \App\Models\DailyPassword::getTodaysPassword();
        return view('admin.manage_campaigns', compact('campaigns', 'currentDidPassword'));
    })->name('admin.manage_campaigns');
    
    // Store new campaign
    Route::post('/admin/manage_campaigns', function(Request $request) {
        $request->validate([
            'campaign_name' => 'required|string|max:255',
            'vertical' => 'required|string|in:ACA Health,Final Expense,Pest Control,Auto Insurance,Medicare,Home Warranty,SSDI,Debt Relief,Tax Debt Relief,Other',
            'did' => 'required|string|max:50',
            'commission_inr' => 'required|numeric|min:0',
            'payout_usd' => 'required|numeric|min:0',
            'status' => 'required|in:active,paused,draft'
        ]);
        
        \App\Models\Campaign::create([
            'campaign_name' => $request->campaign_name,
            'vertical' => $request->vertical,
            'did' => $request->did,
            'commission_inr' => $request->commission_inr,
            'payout_usd' => $request->payout_usd,
            'status' => $request->status,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Campaign created successfully!']);
        }
        
        return redirect()->route('admin.manage_campaigns')->with('success', 'Campaign created successfully!');
    })->name('admin.manage_campaigns.store');
    
    // Get campaign data for editing (JSON response)
    Route::get('/admin/campaigns/{campaign}/edit', function(\App\Models\Campaign $campaign) {
        return response()->json($campaign);
    })->name('admin.campaigns.edit');
    
    // Update campaign
    Route::put('/admin/manage_campaigns/{campaign}', function(Request $request, \App\Models\Campaign $campaign) {
        try {
            $request->validate([
                'campaign_name' => 'required|string|max:255',
                'vertical' => 'required|string|in:ACA Health,Final Expense,Pest Control,Auto Insurance,Medicare,Home Warranty,SSDI,Debt Relief,Tax Debt Relief,Other',
                'did' => 'required|string|max:50',
                'commission_inr' => 'required|numeric|min:0',
                'payout_usd' => 'required|numeric|min:0',
                'status' => 'required|in:active,paused,draft'
            ]);
            
            $campaign->update([
                'campaign_name' => $request->campaign_name,
                'vertical' => $request->vertical,
                'did' => $request->did,
                'commission_inr' => $request->commission_inr,
                'payout_usd' => $request->payout_usd,
                'status' => $request->status,
            ]);
            
            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Campaign updated successfully!']);
            }
            
            return redirect()->route('admin.manage_campaigns')->with('success', 'Campaign updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Campaign update failed: ' . $e->getMessage(), [
                'campaign_id' => $campaign->id,
                'request_data' => $request->all(),
                'exception' => $e
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Update failed: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->withErrors(['error' => 'Update failed: ' . $e->getMessage()]);
        }
    })->name('admin.manage_campaigns.update');
    
    // Delete campaign
    Route::delete('/admin/manage_campaigns/{campaign}', function(\App\Models\Campaign $campaign, Request $request) {
        $campaign->delete();
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Campaign deleted successfully!']);
        }
        
        return redirect()->route('admin.manage_campaigns')->with('success', 'Campaign deleted successfully!');
    })->name('admin.manage_campaigns.delete');
    
    // Admin Manage Users
    Route::get('/admin/manage_users', function(Request $request) {
        $users = \App\Models\User::with('approvedBy')->orderBy('created_at', 'desc')->get();
        
        // Handle AJAX requests
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'users' => $users,
                'success' => true
            ]);
        }
        
        // Separate users by approval status for better organization
        $pendingUsers = $users->where('approval_status', 'pending');
        $approvedUsers = $users->where('approval_status', 'approved');
        $rejectedUsers = $users->where('approval_status', 'rejected');
        $legacyUsers = $users->whereNull('approval_status');
        
        return view('admin.manage_users', compact('users', 'pendingUsers', 'approvedUsers', 'rejectedUsers', 'legacyUsers'));
    })->name('admin.manage_users');
    
    // Create user
    Route::post('/admin/manage_users', function(Request $request) {
        try {
            // Log the incoming request data
            \Log::info('User creation request received', [
                'data' => $request->all(),
                'is_ajax' => $request->ajax(),
                'wants_json' => $request->wantsJson(),
                'content_type' => $request->header('Content-Type'),
                'accept' => $request->header('Accept')
            ]);
            
            $rules = [
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username',
                'email' => 'required|string|email|max:255|unique:users,email',
                'phone' => 'nullable|string|max:20',
                'password' => 'required|string|min:6',
                'role' => 'required|in:admin,agent,publisher'
            ];
            
            $validator = \Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                \Log::error('Validation failed during user creation', [
                    'errors' => $validator->errors()->toArray(),
                    'input' => $request->all()
                ]);
                
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => $validator->errors()->toArray()
                    ], 422);
                }
                
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            \Log::info('Validation passed, creating user...');
            
            $user = \App\Models\User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => bcrypt($request->password),
                'plain_password' => $request->password, // Store plain password
                'role' => $request->role,
                'status' => 'active',
                'account_status' => 'active', // Set the new status
                'email_verified' => true,
                'approval_status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
            ]);
            
            \Log::info('User created successfully', ['user_id' => $user->id]);
            
            // Handle AJAX requests
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User created successfully!',
                    'user' => $user
                ]);
            }
            
            return redirect()->route('admin.manage_users')->with('success', 'User created successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Exception during user creation', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while creating the user: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'An error occurred while creating the user: ' . $e->getMessage());
        }
    })->name('admin.users.store');
    
    // Update user status
    Route::post('/admin/manage_users/{user}/status', function(Request $request, \App\Models\User $user) {
        try {
            $request->validate([
                'status' => 'required|in:active,revoked'
            ]);
            
            $user->update(['status' => $request->status]);
            
            // Handle AJAX requests
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User status updated successfully!'
                ]);
            }
            
            return redirect()->route('admin.manage_users')->with('success', 'User status updated successfully!');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while updating user status: ' . $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    })->name('admin.users.status');
    
    // Update user account status (new consolidated status)
    Route::post('/admin/manage_users/{user}/account-status', function(Request $request, \App\Models\User $user) {
        $request->validate([
            'account_status' => 'required|in:pending,active,revoked'
        ]);
        
        $newStatus = $request->account_status;
        
        // Update the account status
        $user->update(['account_status' => $newStatus]);
        
        // Also update the legacy fields for compatibility
        if ($newStatus === 'active') {
            $user->update([
                'approval_status' => 'approved',
                'status' => 'active',
                'approved_at' => now(),
                'approved_by' => auth()->id()
            ]);
            
            // Send approval notification email
            try {
                \Mail::to($user->email)->send(new \App\Mail\AccountStatusNotification($user, 'approved'));
            } catch (\Exception $e) {
                \Log::error('Failed to send approval email: ' . $e->getMessage());
            }
        } elseif ($newStatus === 'revoked') {
            $user->update([
                'approval_status' => 'rejected',
                'status' => 'revoked'
            ]);
            
            // Send rejection notification email
            try {
                \Mail::to($user->email)->send(new \App\Mail\AccountStatusNotification($user, 'rejected'));
            } catch (\Exception $e) {
                \Log::error('Failed to send rejection email: ' . $e->getMessage());
            }
        }
        
        $statusText = ucfirst($newStatus);
        return response()->json(['success' => true, 'message' => "User status updated to {$statusText} successfully!"]);
    })->name('admin.users.account-status');
    
    // Delete user
    Route::post('/admin/manage_users/{user}/delete', function(Request $request, \App\Models\User $user) {
        try {
            if ($user->id === \Illuminate\Support\Facades\Auth::id()) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You cannot delete yourself!'
                    ], 403);
                }
                return redirect()->route('admin.manage_users')->with('error', 'You cannot delete yourself!');
            }
            
            $user->delete();
            
            // Handle AJAX requests
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User deleted successfully!'
                ]);
            }
            
            return redirect()->route('admin.manage_users')->with('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while deleting user: ' . $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    })->name('admin.users.delete');

    // Reset user password
    Route::post('/admin/users/{user}/reset-password', function(\App\Models\User $user) {
        // Generate a new temporary password
        $newPassword = \Illuminate\Support\Str::random(12);
        
        // Update the user's password
        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($newPassword),
            'plain_password' => $newPassword
        ]);
        
        // Log the password reset activity
        \Log::info("Password reset for user {$user->username} (ID: {$user->id}) by admin " . \Illuminate\Support\Facades\Auth::user()->username);
        
        return response()->json([
            'success' => true, 
            'message' => 'Password reset successfully!',
            'new_password' => $newPassword
        ]);
    })->name('admin.users.reset-password');

    // User approval routes
    Route::get('/admin/users/{user}/approve', [\App\Http\Controllers\Admin\UserApprovalController::class, 'approve'])->name('admin.users.approve');
    Route::get('/admin/users/{user}/reject', [\App\Http\Controllers\Admin\UserApprovalController::class, 'reject'])->name('admin.users.reject');
    Route::post('/admin/users/{user}/approve', [\App\Http\Controllers\Admin\UserApprovalController::class, 'processApproval'])->name('admin.users.approve.process');
    Route::post('/admin/users/{user}/reject', [\App\Http\Controllers\Admin\UserApprovalController::class, 'processRejection'])->name('admin.users.reject.process');
    
    // Lead management routes
    Route::get('/admin/leads/{lead}/json', [\App\Http\Controllers\LeadController::class, 'getLeadJson'])->name('admin.leads.json');
    Route::put('/admin/leads/{lead}', [\App\Http\Controllers\LeadController::class, 'updateLead'])->name('admin.leads.update');
    Route::delete('/admin/leads/{lead}', [\App\Http\Controllers\LeadController::class, 'deleteLead'])->name('admin.leads.delete');
    
    // Additional lead management routes
    Route::get('/admin/leads/import', function() {
        return view('admin.leads_import');
    })->name('admin.leads.import');
    
    Route::post('/admin/leads/import', [\App\Http\Controllers\LeadController::class, 'importLeads'])->name('admin.leads.import.store');
    
    Route::get('/admin/leads/export', [\AppHttp\Controllers\LeadController::class, 'exportLeads'])->name('admin.leads.export');
    
    Route::get('/admin/import-maymonthdata', [\App\Http\Controllers\LeadController::class, 'importMayMonthData'])->name('admin.import.maymonthdata');

    // DID Management Routes
    Route::prefix('admin/dids')->name('admin.dids.')->group(function () {
        Route::get('/', function() {
            $totalDids = \App\Models\Did::count();
            $activeDids = \App\Models\Did::where('status', 'active')->count();
            $availableDids = \App\Models\Did::where('status', 'available')->count();
            $assignedDids = \App\Models\Did::where('status', 'assigned')->count();
            $monthlyCost = \App\Models\Did::sum('monthly_cost') ?? 0;
            $dids = \App\Models\Did::orderBy('created_at', 'desc')->get();
            
            return view('admin.dids_index', compact('totalDids', 'activeDids', 'availableDids', 'assignedDids', 'monthlyCost', 'dids'));
        })->name('index');
        
        Route::get('/create', function() {
            return view('admin.dids_create');
        })->name('create');
        
        Route::post('/', function(\Illuminate\Http\Request $request) {
            // Store new DID logic here
            return redirect()->route('admin.dids.index')->with('success', 'DID created successfully');
        })->name('store');
        
        Route::get('/{did}', function(\App\Models\Did $did) {
            return view('admin.dids_show', compact('did'));
        })->name('show');
        
        Route::get('/{did}/edit', function(\App\Models\Did $did) {
            return view('admin.dids_edit', compact('did'));
        })->name('edit');
        
        Route::put('/{did}', function(\Illuminate\Http\Request $request, \App\Models\Did $did) {
            // Update DID logic here
            return redirect()->route('admin.dids.index')->with('success', 'DID updated successfully');
        })->name('update');
        
        Route::delete('/{did}', function(\App\Models\Did $did) {
            $did->delete();
            return redirect()->route('admin.dids.index')->with('success', 'DID deleted successfully');
        })->name('destroy');
        
        Route::post('/{did}/assign', function(\Illuminate\Http\Request $request, \App\Models\Did $did) {
            // Assign DID to campaign logic here
            return redirect()->back()->with('success', 'DID assigned successfully');
        })->name('assign');
        
        Route::post('/{did}/release', function(\App\Models\Did $did) {
            // Release DID logic here
            return redirect()->back()->with('success', 'DID released successfully');
        })->name('release');
        
        Route::post('/bulk-assign', function(\Illuminate\Http\Request $request) {
            // Bulk assign DIDs logic here
            return redirect()->back()->with('success', 'DIDs assigned successfully');
        })->name('bulk-assign');
        
        Route::get('/analytics/dashboard', function() {
            return view('admin.dids_analytics');
        })->name('analytics');
    });
    
    // Call Analytics Routes
    Route::prefix('admin/call-analytics')->name('admin.call-analytics.')->group(function () {
        Route::get('/', function() {
            $totalCalls = \App\Models\CallLog::count();
            $todayCalls = \App\Models\CallLog::whereDate('created_at', today())->count();
            $callLogs = \App\Models\CallLog::latest()->paginate(25);
            
            return view('admin.call_analytics_index', compact('totalCalls', 'todayCalls', 'callLogs'));
        })->name('index');
        
        Route::get('/export', function() {
            // Export calls logic here
            return response()->download('calls_export.csv');
        })->name('export');
        
        Route::get('/did/{did}/history', function(\App\Models\Did $did) {
            $callHistory = \App\Models\CallLog::where('did_id', $did->id)->latest()->paginate(25);
            return view('admin.call_analytics_did_history', compact('did', 'callHistory'));
        })->name('did-history');
    });
    
    // Pay-per-Call Management Routes
    Route::prefix('admin/pay-per-call')->name('admin.pay-per-call.')->group(function () {
        Route::get('/dashboard', function() {
            // Pay Per Call main dashboard with stats
            $totalDids = \App\Models\Did::count();
            $activeDids = \App\Models\Did::where('status', 'active')->count();
            $totalCalls = \App\Models\CallLog::count();
            $todayCalls = \App\Models\CallLog::whereDate('created_at', today())->count();
            $monthlyCost = \App\Models\Did::sum('monthly_cost') ?? 0;
            
            return view('admin.pay_per_call_dashboard', compact('totalDids', 'activeDids', 'totalCalls', 'todayCalls', 'monthlyCost'));
        })->name('dashboard');
        
        Route::get('/', function() {
            return view('admin.pay_per_call_index');
        })->name('index');
        
        Route::post('/update-settings', function(\Illuminate\Http\Request $request) {
            // Update pay-per-call settings logic here
            return redirect()->back()->with('success', 'Settings updated successfully');
        })->name('update-settings');
        
        Route::post('/test-provider', function(\Illuminate\Http\Request $request) {
            // Test provider connection logic here
            return response()->json(['status' => 'success', 'message' => 'Provider connection successful']);
        })->name('test-provider');
        
        Route::post('/setup-dni', function(\Illuminate\Http\Request $request) {
            // Setup DNI logic here
            return redirect()->back()->with('success', 'DNI setup completed');
        })->name('setup-dni');
        
        Route::get('/dni-script/{campaign}', function(\App\Models\Campaign $campaign) {
            // Generate DNI script logic here
            return response()->json(['script' => 'DNI script content']);
        })->name('dni-script');
        
        Route::get('/routing', function() {
            return view('admin.pay_per_call_routing');
        })->name('routing');
        
        Route::post('/routing', function(\Illuminate\Http\Request $request) {
            // Update call routing logic here
            return redirect()->back()->with('success', 'Call routing updated successfully');
        })->name('update-routing');
    });
    
    // Publisher Management Routes for Call Tracking
    Route::prefix('admin/publishers')->name('admin.publishers.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PublisherController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\PublisherController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\PublisherController::class, 'store'])->name('store');
        Route::get('/{publisher}', [\App\Http\Controllers\Admin\PublisherController::class, 'show'])->name('show');
        Route::get('/{publisher}/edit', [\App\Http\Controllers\Admin\PublisherController::class, 'edit'])->name('edit');
        Route::put('/{publisher}', [\App\Http\Controllers\Admin\PublisherController::class, 'update'])->name('update');
        Route::delete('/{publisher}', [\App\Http\Controllers\Admin\PublisherController::class, 'destroy'])->name('destroy');
        
        // Special actions for call tracking
        Route::post('/{publisher}/assign-did', [\App\Http\Controllers\Admin\PublisherController::class, 'assignTrackingDid'])->name('assign-did');
        Route::patch('/{publisher}/toggle-status', [\App\Http\Controllers\Admin\PublisherController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/{publisher}/analytics', [\App\Http\Controllers\Admin\PublisherController::class, 'getAnalytics'])->name('analytics');
        Route::post('/bulk-assign-dids', [\App\Http\Controllers\Admin\PublisherController::class, 'bulkAssignDids'])->name('bulk-assign-dids');
    });
}); // End of admin middleware group

Route::middleware(['auth', 'role:agent'])->group(function () {
    // Agent dashboard
    Route::get('/agent/dashboard', function () {
        $userId = Auth::id();
        $totalLeads = \App\Models\Lead::where('user_id', $userId)->count();
        $approvedLeads = \App\Models\Lead::where('user_id', $userId)->where('status', 'approved')->count();
        $pendingLeads = \App\Models\Lead::where('user_id', $userId)->where('status', 'pending')->count();
        $rejectedLeads = \App\Models\Lead::where('user_id', $userId)->where('status', 'rejected')->count();
        $recentLeads = \App\Models\Lead::where('user_id', $userId)->latest()->take(5)->get();
        // Calculate revenue as sum of commission_inr for all approved leads for this agent
        $revenue = \App\Models\Lead::where('user_id', $userId)
            ->where('status', 'approved')
            ->with('campaign')
            ->get()
            ->sum(function($lead) {
                return $lead->campaign && $lead->campaign->commission_inr ? $lead->campaign->commission_inr : 0;
            });
        return view('agent.dashboard', compact('totalLeads', 'approvedLeads', 'pendingLeads', 'rejectedLeads', 'recentLeads', 'revenue'));
    })->name('agent.dashboard');
    
    // Agent leads view
    Route::get('/agent/leads', function () {
        $userId = Auth::id();
        $leads = \App\Models\Lead::where('user_id', $userId)->latest()->paginate(10);
        return view('agent.view_leads', compact('leads'));
    })->name('agent.leads.view');
    
    // Agent add lead form
    Route::get('/agent/leads/add', [AgentLeadController::class, 'create'])->name('agent.leads.add');
    
    // Agent store lead using the new Agent\LeadController
    Route::post('/agent/leads', [AgentLeadController::class, 'store'])->name('agent.leads.store');
    
    // API routes for real-time features
    Route::get('/api/campaign-by-did', [AgentLeadController::class, 'getCampaignByDid'])->name('api.campaign.by.did');
    Route::get('/api/user-suggestions', [AgentLeadController::class, 'getUserSuggestions'])->name('api.user.suggestions');

    // Agent lead management routes
    Route::get('/agent/leads/{lead}', [AgentLeadController::class, 'show'])->name('agent.leads.show');
    Route::get('/agent/leads/{lead}/edit', [AgentLeadController::class, 'edit'])->name('agent.leads.edit');
    Route::put('/agent/leads/{lead}', [AgentLeadController::class, 'update'])->name('agent.leads.update');
    Route::delete('/agent/leads/{lead}', [AgentLeadController::class, 'destroy'])->name('agent.leads.destroy');
    Route::get('/agent/leads/{lead}/tracking-data', [AgentLeadController::class, 'exportTrackingData'])->name('agent.leads.tracking-data');
    
    // Suggestions for agent/verifier fields
    Route::get('/agent/user-suggestions', function () {
        $users = \App\Models\User::where('status', 'active')->get(['name', 'username']);
        $names = $users->pluck('name')->toArray();
        $usernames = $users->pluck('username')->toArray();
        return response()->json(array_unique(array_merge($names, $usernames)));
    });
    
    // DID suggestions route
    Route::get('/agent/did-suggestions', function() {
        $dids = \App\Models\Campaign::select('did', 'campaign_name')->get()->map(function($c) {
            return [
                'did' => $c->did,
                'label' => $c->did . ($c->campaign_name ? ' - ' . $c->campaign_name : '')
            ];
        });
        return response()->json($dids);
    });
});

Route::middleware(['auth'])->group(function () {
    // Chat routes
    Route::get('/chat/public', [ChatController::class, 'publicMessages']);
    Route::get('/chat/private/{user}', [ChatController::class, 'privateMessages']);
    Route::post('/chat/send', [ChatController::class, 'send']);
    Route::get('/chat/users', [ChatController::class, 'users']);
    Route::get('/chat/rooms', [ChatController::class, 'getChatRooms']);
    Route::get('/chat/rooms/{chatRoom}/messages', [ChatController::class, 'getChatRoomMessages']);
    Route::post('/chat/rooms', [ChatController::class, 'createChatRoom']);
    Route::post('/chat/rooms/{chatRoom}/join', [ChatController::class, 'joinChatRoom']);
    Route::get('/chat/unread-count', [ChatController::class, 'getUnreadCount']);
    Route::post('/chat/mark-read', [ChatController::class, 'markAsRead']);
    Route::post('/chat/online-status', [ChatController::class, 'updateOnlineStatus']);
});

// Guest chat route
Route::post('/chat/guest-contact', [ChatController::class, 'guestContact']);

Route::get('/multiavatar/{seed}.svg', [\App\Http\Controllers\ProfileController::class, 'multiavatar'])
    ->where('seed', '.*')
    ->name('multiavatar');

// Email verification routes
Route::get('/email/verify', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'showEmailVerification'])
    ->name('email.verify');
Route::post('/email/verify', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'verifyEmail'])
    ->name('email.verify.submit');
Route::post('/email/resend', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'resendOTP'])
    ->name('email.resend');
Route::get('/auth/pending-approval', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'showPendingApproval'])
    ->name('auth.pending-approval');

require __DIR__.'/auth.php';

// Publisher Dashboard Routes
Route::middleware(['auth', 'role:publisher'])->prefix('publisher')->name('publisher.')->group(function () {
    Route::get('/dashboard', [PublisherDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/analytics', [PublisherDashboardController::class, 'analytics'])->name('analytics');
    Route::get('/call-history', [PublisherDashboardController::class, 'callHistory'])->name('call-history');
    // Remove publisher-specific profile routes
    // Route::get('/profile', [PublisherDashboardController::class, 'profile'])->name('profile');
    // Route::put('/profile', [PublisherDashboardController::class, 'updateProfile'])->name('profile.update');
});


