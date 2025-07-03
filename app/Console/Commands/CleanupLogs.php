<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Setting;

class CleanupLogs extends Command
{
    protected $signature = 'logs:cleanup {--force : Force cleanup regardless of settings}';
    protected $description = 'Clean up old log files based on system settings';

    public function handle()
    {
        $cleanupDays = Setting::get('general.log_cleanup_days', 7);
        $force = $this->option('force');
        
        if ($cleanupDays == 0 && !$force) {
            $this->info('Log cleanup is disabled in settings. Use --force to cleanup anyway.');
            return;
        }
        
        $logFile = storage_path('logs/laravel.log');
        
        if (!file_exists($logFile)) {
            $this->info('No log file found.');
            return;
        }
        
        $fileSizeBefore = filesize($logFile);
        $fileAgeDays = (time() - filemtime($logFile)) / (60 * 60 * 24);
        
        if ($force || $fileAgeDays > $cleanupDays) {
            // Keep only last 100 lines
            $lines = file($logFile);
            $totalLines = count($lines);
            $keepLines = array_slice($lines, -100);
            
            file_put_contents($logFile, implode('', $keepLines));
            
            $fileSizeAfter = filesize($logFile);
            
            $this->info("Log cleanup completed:");
            $this->info("- File age: " . round($fileAgeDays, 1) . " days");
            $this->info("- Size before: " . $this->formatBytes($fileSizeBefore));
            $this->info("- Size after: " . $this->formatBytes($fileSizeAfter));
            $this->info("- Lines before: " . $totalLines);
            $this->info("- Lines after: " . count($keepLines));
            $this->info("- Space saved: " . $this->formatBytes($fileSizeBefore - $fileSizeAfter));
        } else {
            $this->info("Log file is only " . round($fileAgeDays, 1) . " days old. Cleanup threshold is {$cleanupDays} days.");
        }
    }
    
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
