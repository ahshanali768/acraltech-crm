<?php
namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\ChatRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    // Get chat rooms for the authenticated user
    public function getChatRooms()
    {
        $user = Auth::user();
        $chatRooms = $user->chatRooms()->with(['messages' => function($query) {
            $query->latest()->take(1);
        }, 'members.user'])->get();

        return response()->json($chatRooms);
    }

    // Get messages for a specific chat room
    public function getChatRoomMessages($chatRoomId)
    {
        $chatRoom = ChatRoom::findOrFail($chatRoomId);
        
        // Check if user is a member
        if (!$chatRoom->members()->where('user_id', Auth::id())->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = $chatRoom->messages()
            ->with(['sender', 'replyTo.sender'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read
        $chatRoom->messages()
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }

    // Fetch public chat messages
    public function publicMessages()
    {
        return Message::whereNull('receiver_id')
            ->whereNull('chat_room_id')
            ->with(['sender', 'replyTo.sender'])
            ->latest()
            ->take(50)
            ->get()
            ->reverse()
            ->values();
    }

    // Fetch private chat messages between two users
    public function privateMessages($userId)
    {
        $authId = Auth::id();
        $messages = Message::where(function($q) use ($authId, $userId) {
            $q->where('sender_id', $authId)->where('receiver_id', $userId);
        })->orWhere(function($q) use ($authId, $userId) {
            $q->where('sender_id', $userId)->where('receiver_id', $authId);
        })->with(['sender', 'replyTo.sender'])
        ->orderBy('created_at')
        ->get();

        // Mark messages as read
        Message::where('sender_id', $userId)
            ->where('receiver_id', $authId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return $messages;
    }

    // Send a message (public, private, or chat room)
    public function send(Request $request)
    {
        $data = $request->validate([
            'message' => 'required|string',
            'receiver_id' => 'nullable|exists:users,id',
            'chat_room_id' => 'nullable|exists:chat_rooms,id',
            'reply_to_id' => 'nullable|exists:messages,id',
            'message_type' => 'nullable|in:text,image,file,system',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);

        // Handle file upload
        $attachmentUrl = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('chat-attachments', 'public');
            $attachmentUrl = Storage::url($path);
        }

        $msg = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $data['receiver_id'] ?? null,
            'chat_room_id' => $data['chat_room_id'] ?? null,
            'message' => $data['message'],
            'message_type' => $data['message_type'] ?? 'text',
            'attachment_url' => $attachmentUrl,
            'reply_to_id' => $data['reply_to_id'] ?? null,
        ]);

        broadcast(new MessageSent($msg))->toOthers();
        return $msg->load(['sender', 'replyTo.sender']);
    }

    // Create a new chat room
    public function createChatRoom(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:public,private,group',
            'member_ids' => 'nullable|array',
            'member_ids.*' => 'exists:users,id',
        ]);

        $chatRoom = ChatRoom::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'type' => $data['type'],
            'created_by' => Auth::id(),
        ]);

        // Add creator as admin
        $chatRoom->members()->create([
            'user_id' => Auth::id(),
            'role' => 'admin',
        ]);

        // Add other members
        if (!empty($data['member_ids'])) {
            foreach ($data['member_ids'] as $userId) {
                $chatRoom->members()->create([
                    'user_id' => $userId,
                    'role' => 'member',
                ]);
            }
        }

        return response()->json($chatRoom->load('members.user'));
    }

    // Join a chat room
    public function joinChatRoom($chatRoomId)
    {
        $chatRoom = ChatRoom::findOrFail($chatRoomId);
        
        if ($chatRoom->type === 'private') {
            return response()->json(['error' => 'Cannot join private chat room'], 403);
        }

        $chatRoom->members()->firstOrCreate([
            'user_id' => Auth::id(),
        ], [
            'role' => 'member',
        ]);

        return response()->json(['success' => true]);
    }

    // List users for private chat
    public function users()
    {
        return User::where('id', '!=', Auth::id())
            ->select('id', 'name', 'avatar_seed', 'is_online', 'last_seen_at')
            ->get();
    }

    // Get user's unread message count
    public function getUnreadCount()
    {
        $user = Auth::user();
        return response()->json([
            'unread_count' => $user->getUnreadMessageCount()
        ]);
    }

    // Mark messages as read
    public function markAsRead(Request $request)
    {
        $data = $request->validate([
            'message_ids' => 'required|array',
            'message_ids.*' => 'exists:messages,id',
        ]);

        Message::whereIn('id', $data['message_ids'])
            ->where('sender_id', '!=', Auth::id())
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    // Update user's online status
    public function updateOnlineStatus(Request $request)
    {
        $data = $request->validate([
            'is_online' => 'required|boolean',
        ]);

        Auth::user()->update([
            'is_online' => $data['is_online'],
            'last_seen_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    // Handle guest pre-chat contact form
    public function guestContact(Request $request)
    {
        \Log::info('Guest contact form submission received', $request->all());
        
        try {
            $data = $request->validate([
                'name' => 'required|string|max:100',
                'phone' => 'required|string|max:30',
                'email' => 'required|email|max:100',
                'consent' => 'accepted',
            ]);
            
            // Store guest info in session (or DB if you want to persist)
            session(['guest_chat_contact' => $data]);
            
            \Log::info('Guest contact form processed successfully', $data);
            
            // Optionally: notify admin (e.g., via email, event, or dashboard notification)
            // Optionally: assign a chat room/session
            return response()->json(['success' => true, 'message' => 'Contact information saved successfully']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed for guest contact form', ['errors' => $e->errors()]);
            return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            \Log::error('Error processing guest contact form', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'An error occurred while processing your request'], 500);
        }
    }
}
