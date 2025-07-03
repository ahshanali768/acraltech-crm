<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'sender_id', 
        'receiver_id', 
        'chat_room_id',
        'message',
        'message_type',
        'attachment_url',
        'is_read',
        'reply_to_id'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function sender() {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver() {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function chatRoom() {
        return $this->belongsTo(ChatRoom::class, 'chat_room_id');
    }

    public function replyTo() {
        return $this->belongsTo(Message::class, 'reply_to_id');
    }

    public function replies() {
        return $this->hasMany(Message::class, 'reply_to_id');
    }

    // Scope for unread messages
    public function scopeUnread($query) {
        return $query->where('is_read', false);
    }

    // Scope for private messages
    public function scopePrivate($query) {
        return $query->whereNotNull('receiver_id')->whereNull('chat_room_id');
    }

    // Scope for public messages (chat rooms)
    public function scopePublic($query) {
        return $query->whereNotNull('chat_room_id');
    }
}
