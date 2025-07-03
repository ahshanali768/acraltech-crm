<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\NotificationSetting;
use App\Models\NotificationTemplate;
use App\Models\NotificationLog;
use App\Models\SecuritySetting;
use App\Models\AuditLog;
use App\Models\ApiKey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::getAll();
        $notificationSettings = NotificationSetting::with('templates')->get();
        $notificationTemplates = NotificationTemplate::all()->groupBy('trigger_event');
        $users = User::select('id', 'name', 'email', 'role')->get();
        $securitySettings = SecuritySetting::getSettings();
        $apiKeys = ApiKey::with('user')->orderBy('created_at', 'desc')->get();
        
        return view('admin.settings', compact('settings', 'notificationSettings', 'notificationTemplates', 'users', 'securitySettings', 'apiKeys'));
    }

    public function updateGeneral(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'site_url' => 'nullable|url',
            'admin_email' => 'required|email|max:255',
            'timezone' => 'required|string',
            'language' => 'required|string',
            'currency' => 'required|string',
            'date_format' => 'required|string',
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|integer|min:1|max:65535',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string',
            'smtp_encryption' => 'nullable|in:tls,ssl,',
            'mail_from_name' => 'nullable|string|max:255',
            'maintenance_mode' => 'nullable|boolean',
            'maintenance_message' => 'nullable|string',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico,svg|max:1024',
        ]);

        // Handle file uploads
        if ($request->hasFile('site_logo')) {
            $logoPath = $request->file('site_logo')->store('logos', 'public');
            Setting::set('site_logo', $logoPath);
        }

        if ($request->hasFile('favicon')) {
            $faviconPath = $request->file('favicon')->store('favicons', 'public');
            Setting::set('favicon', $faviconPath);
        }

        // Save all other settings
        $settingsToSave = [
            'site_name' => 'string',
            'site_description' => 'string',
            'site_url' => 'string',
            'admin_email' => 'string',
            'timezone' => 'string',
            'language' => 'string',
            'currency' => 'string',
            'date_format' => 'string',
            'smtp_host' => 'string',
            'smtp_port' => 'integer',
            'smtp_username' => 'string',
            'smtp_password' => 'string',
            'smtp_encryption' => 'string',
            'mail_from_name' => 'string',
            'maintenance_mode' => 'boolean',
            'maintenance_message' => 'string',
        ];

        foreach ($settingsToSave as $key => $type) {
            $value = $request->input($key);
            if ($value !== null || $type === 'boolean') {
                // For boolean fields, convert checkbox to boolean
                if ($type === 'boolean') {
                    $value = $request->has($key) ? 1 : 0;
                }
                Setting::set($key, $value, $type);
            }
        }

        return redirect()->route('admin.settings')->with('success', 'General settings updated successfully');
    }

    public function updateNotifications(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*.trigger_event' => 'required|string',
            'settings.*.email_enabled' => 'boolean',
            'settings.*.sms_enabled' => 'boolean',
            'settings.*.push_enabled' => 'boolean',
            'settings.*.in_app_enabled' => 'boolean',
            'settings.*.recipients' => 'nullable|array',
            'settings.*.frequency' => 'required|in:immediate,hourly,daily,weekly',
            'settings.*.is_active' => 'boolean',
        ]);

        foreach ($request->settings as $settingData) {
            NotificationSetting::updateOrCreate(
                ['trigger_event' => $settingData['trigger_event']],
                [
                    'email_enabled' => $settingData['email_enabled'] ?? false,
                    'sms_enabled' => $settingData['sms_enabled'] ?? false,
                    'push_enabled' => $settingData['push_enabled'] ?? false,
                    'in_app_enabled' => $settingData['in_app_enabled'] ?? false,
                    'recipients' => $settingData['recipients'] ?? [],
                    'frequency' => $settingData['frequency'],
                    'is_active' => $settingData['is_active'] ?? false,
                ]
            );
        }

        return redirect()->route('admin.settings')->with('success', 'Notification settings updated successfully');
    }

    public function updateSecurity(Request $request)
    {
        $request->validate([
            'email_verification_required' => 'boolean',
            'security_alerts_enabled' => 'boolean',
            'ssl_enforcement' => 'boolean',
            'two_factor_enabled' => 'boolean',
            'session_timeout_minutes' => 'required|integer|min:5|max:1440',
            'max_login_attempts' => 'required|integer|min:3|max:10',
            'lockout_duration_minutes' => 'required|integer|min:1|max:60',
            'password_min_length' => 'required|integer|min:6|max:50',
            'password_require_symbols' => 'boolean',
            'password_require_numbers' => 'boolean',
            'password_require_uppercase' => 'boolean',
            'password_expiry_days' => 'required|integer|min:30|max:365',
            'audit_logs_enabled' => 'boolean',
            'api_rate_limiting' => 'boolean',
            'api_rate_limit_per_minute' => 'required|integer|min:10|max:1000',
            'data_encryption_enabled' => 'boolean',
        ]);

        $securitySettings = SecuritySetting::getSettings();
        $securitySettings->update([
            'email_verification_required' => $request->has('email_verification_required'),
            'security_alerts_enabled' => $request->has('security_alerts_enabled'),
            'ssl_enforcement' => $request->has('ssl_enforcement'),
            'two_factor_enabled' => $request->has('two_factor_enabled'),
            'session_timeout_minutes' => $request->session_timeout_minutes,
            'max_login_attempts' => $request->max_login_attempts,
            'lockout_duration_minutes' => $request->lockout_duration_minutes,
            'password_min_length' => $request->password_min_length,
            'password_require_symbols' => $request->has('password_require_symbols'),
            'password_require_numbers' => $request->has('password_require_numbers'),
            'password_require_uppercase' => $request->has('password_require_uppercase'),
            'password_expiry_days' => $request->password_expiry_days,
            'audit_logs_enabled' => $request->has('audit_logs_enabled'),
            'api_rate_limiting' => $request->has('api_rate_limiting'),
            'api_rate_limit_per_minute' => $request->api_rate_limit_per_minute,
            'data_encryption_enabled' => $request->has('data_encryption_enabled'),
        ]);

        // Log security settings change
        AuditLog::log('security_settings_updated', [
            'changed_by' => auth()->user()->name,
            'changes' => $request->only([
                'email_verification_required', 'security_alerts_enabled', 'ssl_enforcement',
                'two_factor_enabled', 'session_timeout_minutes', 'audit_logs_enabled'
            ])
        ], 'medium');

        return redirect()->route('admin.settings')->with('success', 'Security settings updated successfully');
    }

    public function testEmail(Request $request)
    {
        $request->validate([
            'smtp_host' => 'required|string',
            'smtp_port' => 'required|integer',
            'smtp_username' => 'required|string',
            'smtp_password' => 'required|string',
            'smtp_encryption' => 'nullable|string',
        ]);

        try {
            // Create a test mail configuration
            $config = [
                'transport' => 'smtp',
                'host' => $request->smtp_host,
                'port' => $request->smtp_port,
                'username' => $request->smtp_username,
                'password' => $request->smtp_password,
                'encryption' => $request->smtp_encryption ?: null,
            ];

            // Configure the mail settings temporarily
            config(['mail.mailers.smtp' => $config]);

            // Send a test email
            $testEmail = $request->smtp_username;
            
            \Mail::raw('This is a test email from your CRM system. If you receive this, your SMTP configuration is working correctly.', function ($message) use ($testEmail) {
                $message->to($testEmail)
                        ->subject('CRM Test Email - SMTP Configuration Test')
                        ->from($testEmail, 'CRM System Test');
            });

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'SMTP connection failed: ' . $e->getMessage()
            ], 422);
        }
    }

    public function updateTemplate(Request $request)
    {
        $request->validate([
            'trigger_event' => 'required|string',
            'channel' => 'required|in:email,sms,push,in_app',
            'subject' => 'nullable|string|max:255',
            'content' => 'required|string',
            'is_active' => 'boolean'
        ]);

        NotificationTemplate::updateOrCreate(
            [
                'trigger_event' => $request->trigger_event,
                'channel' => $request->channel
            ],
            [
                'subject' => $request->subject,
                'content' => $request->content,
                'is_active' => $request->is_active ?? true
            ]
        );

        return response()->json(['success' => true, 'message' => 'Template updated successfully']);
    }

    public function testNotification(Request $request)
    {
        $request->validate([
            'trigger_event' => 'required|string',
            'channel' => 'required|in:email,sms,push,in_app',
            'recipient' => 'required|email'
        ]);

        try {
            $template = NotificationTemplate::where('trigger_event', $request->trigger_event)
                ->where('channel', $request->channel)
                ->first();

            if (!$template) {
                return response()->json([
                    'success' => false,
                    'message' => 'Template not found for this event and channel'
                ], 404);
            }

            // Send test notification based on channel
            switch ($request->channel) {
                case 'email':
                    \Mail::raw($template->content, function ($message) use ($request, $template) {
                        $message->to($request->recipient)
                                ->subject($template->subject ?: 'Test Notification')
                                ->from(config('mail.from.address'), config('mail.from.name'));
                    });
                    break;

                case 'sms':
                    // SMS implementation would go here
                    // For now, just log it
                    \Log::info('Test SMS sent', ['recipient' => $request->recipient, 'content' => $template->content]);
                    break;

                case 'push':
                    // Push notification implementation would go here
                    \Log::info('Test Push notification sent', ['recipient' => $request->recipient, 'content' => $template->content]);
                    break;

                case 'in_app':
                    // In-app notification implementation would go here
                    \Log::info('Test In-app notification sent', ['recipient' => $request->recipient, 'content' => $template->content]);
                    break;
            }

            // Log the test notification
            NotificationLog::create([
                'trigger_event' => $request->trigger_event,
                'channel' => $request->channel,
                'recipient_email' => $request->recipient,
                'subject' => $template->subject,
                'content' => $template->content,
                'status' => 'sent',
                'sent_at' => now(),
                'metadata' => ['test' => true]
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Test notification sent successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test notification: ' . $e->getMessage()
            ], 422);
        }
    }

    public function getNotificationLogs(Request $request)
    {
        $logs = NotificationLog::with('user')
            ->when($request->trigger_event, function ($query, $event) {
                return $query->where('trigger_event', $event);
            })
            ->when($request->channel, function ($query, $channel) {
                return $query->where('channel', $channel);
            })
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return response()->json($logs);
    }

    public function createApiKey(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'array',
            'expires_at' => 'nullable|date|after:now',
            'rate_limit' => 'required|integer|min:100|max:10000'
        ]);

        $apiKey = ApiKey::generate(
            $request->name,
            auth()->id(),
            $request->permissions ?? [],
            $request->expires_at ? $request->expires_at : null
        );

        $apiKey->update(['rate_limit' => $request->rate_limit]);

        // Log API key creation
        AuditLog::log('api_key_created', [
            'key_name' => $request->name,
            'key_id' => $apiKey->id,
            'permissions' => $request->permissions ?? []
        ], 'medium');

        return response()->json([
            'success' => true,
            'message' => 'API key created successfully',
            'key' => $apiKey->key,
            'secret' => $apiKey->secret
        ]);
    }

    public function deleteApiKey(Request $request, $id)
    {
        $apiKey = ApiKey::findOrFail($id);
        
        // Log API key deletion
        AuditLog::log('api_key_deleted', [
            'key_name' => $apiKey->name,
            'key_id' => $apiKey->id
        ], 'medium');

        $apiKey->delete();

        return response()->json(['success' => true, 'message' => 'API key deleted successfully']);
    }

    public function toggleApiKey(Request $request, $id)
    {
        $apiKey = ApiKey::findOrFail($id);
        $apiKey->update(['is_active' => !$apiKey->is_active]);

        // Log API key toggle
        AuditLog::log('api_key_toggled', [
            'key_name' => $apiKey->name,
            'key_id' => $apiKey->id,
            'new_status' => $apiKey->is_active ? 'active' : 'inactive'
        ], 'low');

        return response()->json([
            'success' => true, 
            'message' => 'API key ' . ($apiKey->is_active ? 'activated' : 'deactivated') . ' successfully'
        ]);
    }

    public function getAuditLogs(Request $request)
    {
        $logs = AuditLog::with('user')
            ->when($request->event_type, function ($query, $eventType) {
                return $query->where('event_type', $eventType);
            })
            ->when($request->risk_level, function ($query, $riskLevel) {
                return $query->where('risk_level', $riskLevel);
            })
            ->when($request->suspicious, function ($query) {
                return $query->where('suspicious', true);
            })
            ->when($request->user_id, function ($query, $userId) {
                return $query->where('user_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return response()->json($logs);
    }

    public function exportAuditLogs(Request $request)
    {
        $logs = AuditLog::with('user')
            ->when($request->start_date, function ($query, $startDate) {
                return $query->where('created_at', '>=', $startDate);
            })
            ->when($request->end_date, function ($query, $endDate) {
                return $query->where('created_at', '<=', $endDate);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'audit_logs_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'User', 'Event Type', 'IP Address', 'Risk Level', 'Details']);
            
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user ? $log->user->name : 'System',
                    $log->event_type,
                    $log->ip_address,
                    $log->risk_level,
                    json_encode($log->details)
                ]);
            }
            fclose($file);
        };

        // Log audit export
        AuditLog::log('audit_logs_exported', [
            'exported_by' => auth()->user()->name,
            'record_count' => $logs->count()
        ], 'medium');

        return response()->stream($callback, 200, $headers);
    }
}
