<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\CrmWebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API routes (for landing page real-time data)
Route::middleware(['cache.api:60'])->group(function () {
    Route::get('/metrics/real-time', [AnalyticsController::class, 'realTimeMetrics']);
    Route::get('/metrics/live-activity', [AnalyticsController::class, 'liveActivity']);
});

// Protected API routes
Route::middleware(['auth:sanctum', 'rate.limit:100,1'])->group(function () {
    // Analytics endpoints
    Route::get('/analytics/campaigns', [AnalyticsController::class, 'campaignAnalytics']);
    Route::get('/analytics/trends', [AnalyticsController::class, 'leadsTrend']);
    Route::get('/analytics/geographic', [AnalyticsController::class, 'geographicDistribution']);
    Route::get('/analytics/services', [AnalyticsController::class, 'servicePerformance']);
    
    // Lead management API
    Route::apiResource('leads', LeadController::class);
    Route::post('/leads/{lead}/status', [LeadController::class, 'updateStatus']);
    Route::post('/leads/bulk-action', [LeadController::class, 'bulkAction']);
    
    // Campaign management API
    Route::apiResource('campaigns', CampaignController::class);
    Route::post('/campaigns/{campaign}/toggle-status', [CampaignController::class, 'toggleStatus']);
    
    // Location verification for attendance
    Route::post('/verify-location', function(Request $request) {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);
        
        $result = \App\Models\AllowedLocation::isLocationAllowed(
            $request->latitude, 
            $request->longitude
        );
        
        return response()->json($result);
    });
});

// CRM Webhook endpoints (for external integrations)
Route::prefix('crm')->group(function () {
    Route::get('/health', [CrmWebhookController::class, 'healthCheck']);
    Route::post('/leads', [CrmWebhookController::class, 'receiveLead']);
    Route::put('/leads/status', [CrmWebhookController::class, 'updateLeadStatus']);
    Route::get('/leads/export', [CrmWebhookController::class, 'exportLeads']);
});

// Legacy webhook endpoints (for backward compatibility)
Route::post('/webhooks/lead-received', function (Request $request) {
    // Handle incoming leads from external sources
    Log::info('Lead webhook received', $request->all());
    return response()->json(['status' => 'received']);
});

Route::post('/webhooks/campaign-update', function (Request $request) {
    // Handle campaign updates from external platforms
    Log::info('Campaign webhook received', $request->all());
    return response()->json(['status' => 'processed']);
});

// Telephony Webhook Routes (no auth required)
Route::prefix('webhooks')->name('api.webhooks.')->group(function () {
    // Twilio webhooks
    Route::post('/twilio/voice/{did?}', [\App\Http\Controllers\Api\WebhookController::class, 'twilioVoice'])->name('twilio.voice');
    Route::post('/twilio/status', [\App\Http\Controllers\Api\WebhookController::class, 'twilioStatus'])->name('twilio.status');
    
    // Plivo webhooks
    Route::post('/plivo/voice/{did?}', [\App\Http\Controllers\Api\WebhookController::class, 'plivoVoice'])->name('plivo.voice');
    
    // Bandwidth webhooks
    Route::post('/bandwidth/voice/{did?}', [\App\Http\Controllers\Api\WebhookController::class, 'bandwidthVoice'])->name('bandwidth.voice');
});

// Admin Dashboard API Routes (authenticated)
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->name('api.admin.')->group(function () {
    Route::get('/dashboard/metrics', [\App\Http\Controllers\Api\AdminDashboardController::class, 'getRealTimeMetrics'])->name('dashboard.metrics');
    Route::get('/dashboard/hourly-leads', [\App\Http\Controllers\Api\AdminDashboardController::class, 'getHourlyLeadData'])->name('dashboard.hourly-leads');
    Route::get('/dashboard/weekly-leads', [\App\Http\Controllers\Api\AdminDashboardController::class, 'getWeeklyLeadData'])->name('dashboard.weekly-leads');
    Route::get('/dashboard/lead-sources', [\App\Http\Controllers\Api\AdminDashboardController::class, 'getLeadSourcesBreakdown'])->name('dashboard.lead-sources');
    Route::get('/dashboard/live-activity', [\App\Http\Controllers\Api\AdminDashboardController::class, 'getLiveActivity'])->name('dashboard.live-activity');
    Route::get('/dashboard/alerts', [\App\Http\Controllers\Api\AdminDashboardController::class, 'getSystemAlerts'])->name('dashboard.alerts');
    Route::get('/dashboard/summary', [\App\Http\Controllers\Api\AdminDashboardController::class, 'getDashboardSummary'])->name('dashboard.summary');
});

// Call Analytics API Routes
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('call-analytics')->name('api.call-analytics.')->group(function () {
    Route::get('/metrics', [\App\Http\Controllers\CallAnalyticsController::class, 'realTimeMetrics'])->name('metrics');
});

// Call management API routes
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('calls')->name('api.calls.')->group(function () {
    Route::post('/{call}/create-lead', function($callId) {
        $call = \App\Models\CallLog::findOrFail($callId);
        if ($call->lead_id) {
            return response()->json(['success' => false, 'message' => 'Lead already exists for this call']);
        }
        
        $lead = \App\Models\Lead::create([
            'name' => 'Lead from Call',
            'phone' => $call->caller_number,
            'email' => null,
            'status' => 'new',
            'source' => 'phone_call',
            'campaign_id' => $call->did->campaign_id ?? null,
            'agent' => auth('sanctum')->user()->name,
            'value' => 100, // Default lead value
            'notes' => 'Generated from call on ' . $call->created_at->format('Y-m-d H:i:s'),
        ]);
        
        $call->update(['lead_id' => $lead->id]);
        
        return response()->json(['success' => true, 'lead_id' => $lead->id]);
    })->name('create-lead');
});

// Agent Attendance API Routes
Route::middleware(['auth:sanctum'])->prefix('agent/attendance')->name('api.agent.attendance.')->group(function () {
    Route::get('/by-month', [\App\Http\Controllers\Api\AgentAttendanceController::class, 'getByMonth'])->name('by-month');
    Route::get('/break/status', [\App\Http\Controllers\Api\AgentAttendanceController::class, 'getBreakStatus'])->name('break.status');
    Route::get('/break/history', [\App\Http\Controllers\Api\AgentAttendanceController::class, 'getBreakHistory'])->name('break.history');
});

// Test route for security testing
Route::get('/test', function () {
    return response()->json(['status' => 'success']);
});

// Daily password API for DIDs access
Route::get('/daily-password', function () {
    $password = \App\Models\DailyPassword::getTodaysPassword();
    return response()->json(['password' => $password]);
});
