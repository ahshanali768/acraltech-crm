<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Publisher;

class PublisherUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a publisher user account
        $user = User::firstOrCreate([
            'email' => 'publisher@test.com'
        ], [
            'name' => 'Test Publisher',
            'username' => 'testpub',
            'password' => bcrypt('password'),
            'role' => 'publisher',
            'status' => 'active'
        ]);

        // Make sure there's a corresponding publisher profile
        $publisher = Publisher::firstOrCreate([
            'email' => $user->email
        ], [
            'name' => $user->name,
            'company' => 'Test Publishing Co.',
            'phone' => '+1 (555) 123-4567',
            'website' => 'https://testpublisher.com',
            'status' => 'active',
            'payout_rate' => 15.00,
            'payout_type' => 'per_call',
            'notes' => 'Test publisher account for development'
        ]);

        echo "Created publisher user: {$user->email} with publisher profile ID: {$publisher->id}\n";
    }
}
