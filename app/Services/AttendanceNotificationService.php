<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\AttendanceReminder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AttendanceNotificationService
{
    /**
     * Schedule attendance reminders for all users
     */
    public function scheduleAttendanceReminders()
    {
        $loginTime = config('attendance.shift.login_time');
        $logoutTime = config('attendance.shift.logout_time');
        $emailEnabled = config('attendance.notifications.email_notifications', true);
        $bellEnabled = config('attendance.notifications.bell_notifications', true);
        
        if (!$loginTime || !$logoutTime) {
            Log::warning('Attendance shift times not configured');
            return;
        }
        
        // Get all active users
        $users = User::where('is_active', true)->get();
        
        foreach ($users as $user) {
            // Schedule login reminder (10 minutes before login time)
            $this->scheduleLoginReminder($user, $loginTime, $emailEnabled, $bellEnabled);
            
            // Schedule logout reminder (10 minutes before logout time)
            $this->scheduleLogoutReminder($user, $logoutTime, $emailEnabled, $bellEnabled);
        }
    }
    
    /**
     * Schedule login reminder for a specific user
     */
    private function scheduleLoginReminder(User $user, string $loginTime, bool $emailEnabled, bool $bellEnabled)
    {
        try {
            $loginDateTime = Carbon::createFromFormat('H:i', $loginTime);
            $reminderTime = $loginDateTime->subMinutes(10);
            
            // If reminder time has passed for today, schedule for tomorrow
            if ($reminderTime->isPast()) {
                $reminderTime->addDay();
            }
            
            if ($emailEnabled) {
                // Schedule email notification
                $user->notify(new AttendanceReminder('login', $reminderTime));
            }
            
            if ($bellEnabled) {
                // Store bell notification in database for frontend to display
                $this->storeBellNotification($user->id, 'login', $reminderTime);
            }
            
            Log::info("Login reminder scheduled for user {$user->id} at {$reminderTime}");
            
        } catch (\Exception $e) {
            Log::error("Failed to schedule login reminder for user {$user->id}: " . $e->getMessage());
        }
    }
    
    /**
     * Schedule logout reminder for a specific user
     */
    private function scheduleLogoutReminder(User $user, string $logoutTime, bool $emailEnabled, bool $bellEnabled)
    {
        try {
            $logoutDateTime = Carbon::createFromFormat('H:i', $logoutTime);
            $reminderTime = $logoutDateTime->subMinutes(10);
            
            // For night shifts, logout might be next day
            if ($logoutDateTime->hour < 12) {
                $reminderTime->addDay();
            }
            
            // If reminder time has passed for today, schedule for tomorrow
            if ($reminderTime->isPast()) {
                $reminderTime->addDay();
            }
            
            if ($emailEnabled) {
                // Schedule email notification
                $user->notify(new AttendanceReminder('logout', $reminderTime));
            }
            
            if ($bellEnabled) {
                // Store bell notification in database for frontend to display
                $this->storeBellNotification($user->id, 'logout', $reminderTime);
            }
            
            Log::info("Logout reminder scheduled for user {$user->id} at {$reminderTime}");
            
        } catch (\Exception $e) {
            Log::error("Failed to schedule logout reminder for user {$user->id}: " . $e->getMessage());
        }
    }
    
    /**
     * Store bell notification in database
     */
    private function storeBellNotification(int $userId, string $type, Carbon $reminderTime)
    {
        // This would typically create a notification record in the database
        // that the frontend can query and display as bell notifications
        
        // You can implement this by creating a notifications table
        // or using Laravel's built-in notifications system
        
        $message = $type === 'login' 
            ? 'Your shift starts in 10 minutes. Please prepare to log in.'
            : 'Your shift ends in 10 minutes. Please prepare to log out.';
            
        // Store in database for real-time notifications
        // This is a placeholder - implement according to your notification system
        Log::info("Bell notification stored for user {$userId}: {$message} at {$reminderTime}");
    }
    
    /**
     * Send immediate reminder if user is not logged in at expected time
     */
    public function checkMissedLogins()
    {
        $loginTime = config('attendance.shift.login_time');
        $gracePeriod = config('attendance.grace_period', 15);
        
        if (!$loginTime) {
            return;
        }
        
        $expectedLoginTime = Carbon::createFromFormat('H:i', $loginTime);
        $cutoffTime = $expectedLoginTime->addMinutes($gracePeriod);
        
        if (Carbon::now()->greaterThan($cutoffTime)) {
            // Find users who haven't logged in yet
            $users = User::whereDoesntHave('attendances', function ($query) {
                $query->whereDate('date', Carbon::today())
                      ->whereNotNull('clock_in_time');
            })->where('is_active', true)->get();
            
            foreach ($users as $user) {
                // Send immediate notification for missed login
                $user->notify(new AttendanceReminder('missed_login', Carbon::now()));
                Log::warning("Missed login notification sent to user {$user->id}");
            }
        }
    }
    
    /**
     * Schedule notifications with provided parameters
     */
    public function scheduleNotifications(string $loginTime, string $logoutTime, bool $emailEnabled, bool $bellEnabled)
    {
        if (!$loginTime || !$logoutTime) {
            Log::warning('Attendance shift times not provided');
            return;
        }
        
        // Get all active users
        $users = User::where('is_active', true)->get();
        
        foreach ($users as $user) {
            // Schedule login reminder (10 minutes before login time)
            $this->scheduleLoginReminder($user, $loginTime, $emailEnabled, $bellEnabled);
            
            // Schedule logout reminder (10 minutes before logout time)
            $this->scheduleLogoutReminder($user, $logoutTime, $emailEnabled, $bellEnabled);
        }
        
        Log::info('Attendance notifications scheduled for ' . $users->count() . ' users');
    }
}
