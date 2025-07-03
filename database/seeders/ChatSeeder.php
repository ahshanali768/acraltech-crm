<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChatRoom;
use App\Models\User;
use App\Models\Message;

class ChatSeeder extends Seeder
{
    public function run()
    {
        // Create a general chat room for all agents
        $generalRoom = ChatRoom::create([
            'name' => 'General Chat',
            'description' => 'Main chat room for all team members',
            'type' => 'public',
            'created_by' => User::where('role', 'admin')->first()->id ?? 1,
        ]);

        // Add all users to the general chat room
        $users = User::all();
        foreach ($users as $user) {
            $generalRoom->members()->create([
                'user_id' => $user->id,
                'role' => $user->role === 'admin' ? 'admin' : 'member',
            ]);
        }

        // Create some sample messages
        $messages = [
            'Welcome to the team chat! 👋',
            'Great to have everyone here!',
            'Don\'t forget our team meeting at 3 PM',
            'New lead assignments are ready for review',
            'Keep up the excellent work everyone! 🚀'
        ];

        foreach ($messages as $index => $messageText) {
            Message::create([
                'sender_id' => $users->random()->id,
                'chat_room_id' => $generalRoom->id,
                'message' => $messageText,
                'message_type' => 'text',
                'created_at' => now()->subMinutes(rand(1, 60)),
            ]);
        }

        // Create a private admin room
        $adminUsers = User::where('role', 'admin')->get();
        if ($adminUsers->count() > 1) {
            $adminRoom = ChatRoom::create([
                'name' => 'Admin Only',
                'description' => 'Private chat for administrators',
                'type' => 'private',
                'created_by' => $adminUsers->first()->id,
            ]);

            foreach ($adminUsers as $admin) {
                $adminRoom->members()->create([
                    'user_id' => $admin->id,
                    'role' => 'admin',
                ]);
            }
        }
    }
}
