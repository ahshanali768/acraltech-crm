<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Generate daily password every day at 10:00 AM
        $schedule->command('password:generate-daily')
                 ->dailyAt('10:00')
                 ->timezone('America/New_York');
        
        // Auto-cleanup logs based on system settings
        $schedule->call(function () {
            $cleanupDays = \App\Models\Setting::get('general.log_cleanup_days', 7);
            
            if ($cleanupDays > 0) {
                $logFile = storage_path('logs/laravel.log');
                
                if (file_exists($logFile)) {
                    $fileAge = (time() - filemtime($logFile)) / (60 * 60 * 24); // Age in days
                    
                    if ($fileAge > $cleanupDays) {
                        // Keep only last 50 lines when cleaning up
                        $lines = file($logFile);
                        $keepLines = array_slice($lines, -50);
                        file_put_contents($logFile, implode('', $keepLines));
                        
                        \Log::info("Log file cleaned up - kept last 50 lines, file was {$fileAge} days old");
                    }
                }
            }
        })->dailyAt('02:00'); // Run at 2 AM daily
        
        // Auto-clear cache if enabled (weekly on Sunday at 3 AM)
        $schedule->call(function () {
            $autoCacheClear = \App\Models\Setting::get('general.auto_cache_clear', true);
            
            if ($autoCacheClear) {
                \Artisan::call('cache:clear');
                \Artisan::call('config:clear');
                \Artisan::call('view:clear');
                \Log::info('Automatic weekly cache cleanup completed');
            }
        })->weeklyOn(0, '03:00'); // Sunday at 3 AM
        
        // For testing - uncomment to run every minute
        // $schedule->command('password:generate-daily')->everyMinute();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        $this->commands([
            \App\Console\Commands\ImportLeadsWithFuzzyAgent::class,
            \App\Console\Commands\GenerateDailyPassword::class,
            \App\Console\Commands\CleanupLogs::class,
        ]);
    }
}