<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

class AttendanceReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $type;
    protected $scheduledTime;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $type, Carbon $scheduledTime)
    {
        $this->type = $type;
        $this->scheduledTime = $scheduledTime;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        $channels = [];
        
        if (config('attendance.notifications.email_notifications', true)) {
            $channels[] = 'mail';
        }
        
        if (config('attendance.notifications.bell_notifications', true)) {
            $channels[] = 'database'; // For bell notifications
        }
        
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $subject = $this->getEmailSubject();
        $message = $this->getEmailMessage($notifiable);
        
        return (new MailMessage)
                    ->subject($subject)
                    ->greeting("Hello {$notifiable->name}!")
                    ->line($message)
                    ->line('Please make sure you are ready for your shift.')
                    ->action('Access Attendance System', url('/admin/attendance'))
                    ->line('Thank you for your attention to punctuality!');
    }

    /**
     * Get the array representation of the notification (for database/bell notifications).
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => $this->type,
            'message' => $this->getBellMessage(),
            'scheduled_time' => $this->scheduledTime->toDateTimeString(),
            'icon' => $this->getIcon(),
            'priority' => 'high'
        ];
    }

    /**
     * Get email subject based on notification type
     */
    private function getEmailSubject(): string
    {
        switch ($this->type) {
            case 'login':
                return 'Attendance Reminder - Shift Starting Soon';
            case 'logout':
                return 'Attendance Reminder - Shift Ending Soon';
            case 'missed_login':
                return 'Attendance Alert - Missed Login';
            default:
                return 'Attendance Reminder';
        }
    }

    /**
     * Get email message based on notification type
     */
    private function getEmailMessage($notifiable): string
    {
        $loginTime = config('attendance.shift.login_time');
        $logoutTime = config('attendance.shift.logout_time');
        
        switch ($this->type) {
            case 'login':
                return "Your shift is scheduled to start at {$loginTime}. This is a reminder to log in within the next 10 minutes.";
            case 'logout':
                return "Your shift is scheduled to end at {$logoutTime}. This is a reminder to log out within the next 10 minutes.";
            case 'missed_login':
                return "You have missed your scheduled login time of {$loginTime}. Please log in immediately or contact your supervisor.";
            default:
                return "This is an attendance reminder for your scheduled shift.";
        }
    }

    /**
     * Get bell notification message
     */
    private function getBellMessage(): string
    {
        switch ($this->type) {
            case 'login':
                return 'Your shift starts in 10 minutes. Please prepare to log in.';
            case 'logout':
                return 'Your shift ends in 10 minutes. Please prepare to log out.';
            case 'missed_login':
                return 'You have missed your scheduled login time. Please log in immediately.';
            default:
                return 'Attendance reminder for your shift.';
        }
    }

    /**
     * Get icon for bell notification
     */
    private function getIcon(): string
    {
        switch ($this->type) {
            case 'login':
                return 'fas fa-sign-in-alt';
            case 'logout':
                return 'fas fa-sign-out-alt';
            case 'missed_login':
                return 'fas fa-exclamation-triangle';
            default:
                return 'fas fa-clock';
        }
    }
}
