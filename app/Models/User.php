<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    // Admin, agent, and publisher roles are supported
    const ROLES = ['admin', 'agent', 'publisher'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'phone',
        'username',
        'email',
        'password',
        'plain_password',
        'role',
        'status', // Add status for active/revoke
        'account_status', // Consolidated status field
        'email_verified',
        'approval_status',
        'approved_at',
        'approved_by',
        'approval_notes',
        'profile_picture',
        'avatar_style',
        'avatar_seed',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'approved_at' => 'datetime',
            'email_verified' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user who approved this account
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get users approved by this user
     */
    public function approvedUsers()
    {
        return $this->hasMany(User::class, 'approved_by');
    }

    /**
     * Check if user account is approved
     */
    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    /**
     * Check if user account is pending approval
     */
    public function isPending(): bool
    {
        return $this->approval_status === 'pending';
    }

    /**
     * Check if user account is rejected
     */
    public function isRejected(): bool
    {
        return $this->approval_status === 'rejected';
    }

    /**
     * Check if the user has a specific role.
     */
    public function hasRole($role): bool
    {
        return in_array($role, self::ROLES) && $this->role === $role;
    }

    /**
     * Get the user's leads.
     */
    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    /**
     * Get messages sent by this user.
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get messages received by this user.
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Get chat rooms this user belongs to.
     */
    public function chatRooms()
    {
        return $this->belongsToMany(ChatRoom::class, 'chat_room_members')
                    ->withPivot('joined_at', 'role')
                    ->withTimestamps();
    }

    /**
     * Get created chat rooms by this user.
     */
    public function createdChatRooms()
    {
        return $this->hasMany(ChatRoom::class, 'created_by');
    }

    /**
     * Get unread message count for this user.
     */
    public function getUnreadMessageCount()
    {
        return $this->receivedMessages()->unread()->count();
    }

    /**
     * Get recent conversations for this user.
     */
    public function getRecentConversations($limit = 10)
    {
        // Get private conversations
        $privateChats = $this->sentMessages()
            ->private()
            ->with(['receiver'])
            ->select('receiver_id', \DB::raw('MAX(created_at) as last_message_at'))
            ->groupBy('receiver_id')
            ->orderBy('last_message_at', 'desc')
            ->limit($limit)
            ->get();

        // Get chat room conversations
        $chatRooms = $this->chatRooms()
            ->with(['latestMessage'])
            ->orderBy('updated_at', 'desc')
            ->limit($limit)
            ->get();

        return [
            'private_chats' => $privateChats,
            'chat_rooms' => $chatRooms
        ];
    }

    /**
     * Check if user is online (can be enhanced with presence system).
     */
    public function isOnline()
    {
        // Simple implementation - can be enhanced with Redis/WebSocket presence
        return $this->updated_at->gt(now()->subMinutes(5));
    }

    /**
     * Get user's performance metrics.
     */
    public function getPerformanceMetrics()
    {
        $totalLeads = $this->leads()->count();
        $approvedLeads = $this->leads()->where('status', 'approved')->count();
        $conversionRate = $totalLeads > 0 ? ($approvedLeads / $totalLeads) * 100 : 0;

        return [
            'total_leads' => $totalLeads,
            'approved_leads' => $approvedLeads,
            'conversion_rate' => round($conversionRate, 2),
            'total_earnings' => $approvedLeads * 50, // Assuming $50 per approved lead
        ];
    }

    /**
     * Scope for active users.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for users by role.
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Get the publisher profile for publisher users.
     */
    public function publisherProfile()
    {
        return $this->hasOne(Publisher::class, 'email', 'email');
    }

    /**
     * Check if user is a publisher.
     */
    public function isPublisher()
    {
        return $this->role === 'publisher';
    }

    protected static $superAdminEmail = 'aigpaypercall@gmail.com';

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\CustomResetPassword($token));
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($user) {
            if ($user->email === self::$superAdminEmail) {
                throw new \Exception('Cannot delete the super admin user.');
            }
        });
    }
}
