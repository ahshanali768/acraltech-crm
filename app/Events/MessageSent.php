<?php
namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message->load('sender');
    }

    public function broadcastOn()
    {
        if ($this->message->receiver_id) {
            // Private chat between two users
            return [new PrivateChannel('chat.private.' . min($this->message->sender_id, $this->message->receiver_id) . '.' . max($this->message->sender_id, $this->message->receiver_id))];
        } else {
            // Public chat
            return [new Channel('chat.public')];
        }
    }

    public function broadcastWith()
    {
        return $this->message->toArray();
    }
}
