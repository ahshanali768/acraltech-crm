<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class CrmSettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Set dynamic timezone from database settings
        if ($this->app->runningInConsole() === false) {
            try {
                $timezone = \App\Models\Setting::get('general.timezone', 'Asia/Kolkata');
                Config::set('app.timezone', $timezone);
                date_default_timezone_set($timezone);
            } catch (\Exception $e) {
                // Fallback to default timezone if database is not available
                Config::set('app.timezone', 'Asia/Kolkata');
                date_default_timezone_set('Asia/Kolkata');
            }
        }
        
        // Set dynamic session timeout
        try {
            $sessionTimeout = \App\Models\Setting::get('general.session_timeout', 60);
            Config::set('session.lifetime', $sessionTimeout);
        } catch (\Exception $e) {
            // Fallback to default
            Config::set('session.lifetime', 60);
        }
        
        // Set dynamic log level based on environment mode
        try {
            $envMode = \App\Models\Setting::get('general.environment_mode', 'production');
            
            $logLevel = match($envMode) {
                'production' => 'warning',
                'development' => 'info', 
                'troubleshooting' => 'debug',
                default => 'warning'
            };
            
            Config::set('logging.channels.single.level', $logLevel);
            Config::set('logging.channels.daily.level', $logLevel);
        } catch (\Exception $e) {
            // Fallback to warning level
            Config::set('logging.channels.single.level', 'warning');
            Config::set('logging.channels.daily.level', 'warning');
        }
    }
}
