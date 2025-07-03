<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Campaign;
use App\Models\Lead;
use App\Models\Message;
use Illuminate\Database\Seeder;

class DevelopmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::factory()->admin()->create([
            'name' => 'Ahshan Ali',
            'username' => 'Aigppc',
            'email' => 'admin@arcaltech.site',
            'password' => bcrypt('Aigppc@768'),
        ]);

        // Create sample users for different roles
        $agents = User::factory()->agent()->count(5)->create();

        // Create campaigns for different service categories
        $insuranceCampaigns = Campaign::factory()->forService('insurance')->count(7)->create();
        $financialCampaigns = Campaign::factory()->forService('financial')->count(4)->create();
        $homeCampaigns = Campaign::factory()->forService('home')->count(3)->create();

        $allCampaigns = $insuranceCampaigns->concat($financialCampaigns)->concat($homeCampaigns);

        // Create leads for campaigns
        foreach ($allCampaigns as $campaign) {
            // Create 15-50 leads per campaign
            Lead::factory()
                ->count(fake()->numberBetween(15, 50))
                ->create([
                    'campaign_id' => $campaign->id,
                    'user_id' => $agents->random()->id,
                ]);
        }

        // Create some approved leads for performance metrics
        Lead::factory()
            ->approved()
            ->count(100)
            ->create([
                'campaign_id' => $allCampaigns->random()->id,
                'user_id' => $agents->random()->id,
            ]);

        // Create pending leads
        Lead::factory()
            ->pending()
            ->count(75)
            ->create([
                'campaign_id' => $allCampaigns->random()->id,
                'user_id' => $agents->random()->id,
            ]);

        // Create business messages between users
        $allUsers = collect([$admin])
            ->concat($agents);

        // Create business communication
        for ($i = 0; $i < 50; $i++) {
            Message::factory()
                ->business()
                ->between(
                    $allUsers->random(),
                    $allUsers->random()
                )
                ->create();
        }

        // Create regular messages
        Message::factory()->count(100)->create([
            'sender_id' => $allUsers->random()->id,
            'receiver_id' => $allUsers->random()->id,
        ]);

        $this->command->info('Development data seeded successfully!');
        $this->command->info('Admin credentials: admin@arcaltech.site / Aigppc@768');
        $this->command->info('Total users: ' . User::count());
        $this->command->info('Total campaigns: ' . Campaign::count());
        $this->command->info('Total leads: ' . Lead::count());
        $this->command->info('Total messages: ' . Message::count());
    }
}
