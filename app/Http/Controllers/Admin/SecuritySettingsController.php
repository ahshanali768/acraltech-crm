<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SecuritySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SecuritySettingsController extends Controller
{
    public function index()
    {
        $settings = SecuritySetting::first() ?? SecuritySetting::create([
            'max_login_attempts' => 5,
            'lockout_duration_minutes' => 30,
            'password_expiry_days' => 90,
            'password_min_length' => 8,
            'require_special_chars' => true,
            'require_uppercase' => true,
            'require_numbers' => true,
            'session_timeout_minutes' => 120,
            'two_factor_auth_enabled' => false,
            'ip_whitelist' => [],
            'allowed_countries' => ['US', 'CA', 'GB', 'AU', 'IN'],
            'enable_captcha' => true,
            'secure_headers' => true,
            'activity_log_retention' => 30
        ]);

        return view('admin.settings.security', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'max_login_attempts' => 'required|integer|min:1|max:10',
            'lockout_duration_minutes' => 'required|integer|min:1',
            'password_expiry_days' => 'required|integer|min:1',
            'password_min_length' => 'required|integer|min:6',
            'require_special_chars' => 'boolean',
            'require_uppercase' => 'boolean',
            'require_numbers' => 'boolean',
            'session_timeout_minutes' => 'required|integer|min:1',
            'two_factor_auth_enabled' => 'boolean',
            'ip_whitelist' => 'nullable|array',
            'allowed_countries' => 'nullable|array',
            'enable_captcha' => 'boolean',
            'secure_headers' => 'boolean',
            'activity_log_retention' => 'required|integer|min:1'
        ]);

        $settings = SecuritySetting::first();
        $settings->update($request->all() + ['last_updated_by' => Auth::id()]);

        return redirect()->route('admin.settings.security')->with('success', 'Security settings updated successfully.');
    }
}
