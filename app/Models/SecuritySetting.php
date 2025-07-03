<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecuritySetting extends Model
{
    /**
     * Get the current security settings or create with defaults if not exists
     */
    public static function getSettings()
    {
        return self::first() ?? self::create([
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
    }
}
