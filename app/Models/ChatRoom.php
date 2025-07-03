<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'type',
        'created_by',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Types: public, team, department
    const TYPE_PUBLIC = 'public';
    const TYPE_TEAM = 'team';
    const TYPE_DEPARTMENT = 'department';

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members() {
        return $this->belongsToMany(User::class, 'chat_room_members')
                    ->withPivot('joined_at', 'role')
                    ->withTimestamps();
    }

    public function messages() {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    public function latestMessage() {
        return $this->hasOne(Message::class)->latest();
    }

    // Check if user is member
    public function hasMember($userId) {
        return $this->members()->where('user_id', $userId)->exists();
    }

    // Get unread count for user
    public function getUnreadCountForUser($userId) {
        return $this->messages()
                    ->where('sender_id', '!=', $userId)
                    ->where('is_read', false)
                    ->count();
    }
}
