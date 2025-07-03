<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Did;
use App\Models\Campaign;

class DidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $campaigns = Campaign::all();
        
        $areaCodes = [
            '212' => 'New York',
            '310' => 'Los Angeles',
            '415' => 'San Francisco',
            '702' => 'Las Vegas',
            '305' => 'Miami',
            '713' => 'Houston',
            '312' => 'Chicago',
            '617' => 'Boston'
        ];
        
        $providers = ['twilio', 'plivo', 'bandwidth'];
        $statuses = ['available', 'active'];
        
        foreach ($areaCodes as $areaCode => $city) {
            // Generate 5-8 DIDs per area code
            $count = rand(5, 8);
            
            for ($i = 0; $i < $count; $i++) {
                $number = $areaCode . sprintf('%07d', rand(1000000, 9999999));
                $status = $statuses[array_rand($statuses)];
                $campaign = null;
                
                // If status is active, assign to a random campaign
                if ($status === 'active' && $campaigns->count() > 0) {
                    $campaign = $campaigns->random();
                }
                
                Did::create([
                    'number' => $number,
                    'area_code' => $areaCode,
                    'country_code' => 'US',
                    'provider' => $providers[array_rand($providers)],
                    'status' => $status,
                    'type' => 'local',
                    'monthly_cost' => rand(80, 150) / 100, // $0.80 - $1.50
                    'setup_cost' => rand(80, 120) / 100, // $0.80 - $1.20
                    'campaign_id' => $campaign?->id,
                    'purchased_at' => now()->subDays(rand(1, 365)),
                    'expires_at' => now()->addYear(),
                    'call_forwarding_enabled' => rand(0, 1),
                    'recording_enabled' => rand(0, 1),
                    'analytics_enabled' => true,
                    'metadata' => [
                        'city' => $city,
                        'timezone' => 'America/New_York'
                    ]
                ]);
            }
        }
        
        // Create some expiring DIDs
        $expiringCount = 3;
        for ($i = 0; $i < $expiringCount; $i++) {
            $areaCode = array_rand($areaCodes);
            $number = $areaCode . sprintf('%07d', rand(1000000, 9999999));
            
            Did::create([
                'number' => $number,
                'area_code' => $areaCode,
                'country_code' => 'US',
                'provider' => $providers[array_rand($providers)],
                'status' => 'active',
                'type' => 'local',
                'monthly_cost' => 1.00,
                'setup_cost' => 1.00,
                'campaign_id' => $campaigns->count() > 0 ? $campaigns->random()->id : null,
                'purchased_at' => now()->subDays(330),
                'expires_at' => now()->addDays(rand(5, 25)), // Expiring soon
                'call_forwarding_enabled' => true,
                'recording_enabled' => true,
                'analytics_enabled' => true,
                'metadata' => [
                    'city' => $areaCodes[$areaCode],
                    'timezone' => 'America/New_York',
                    'note' => 'Expiring soon - renewal required'
                ]
            ]);
        }
        
        $this->command->info('Created ' . Did::count() . ' DIDs with various statuses and configurations');
    }
}
