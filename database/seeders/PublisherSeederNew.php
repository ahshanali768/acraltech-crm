<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Publisher;
use App\Models\Did;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create some sample DIDs first (for tracking purposes)
        $trackingDids = [
            '8882323882',
            '8882323883', 
            '8882323884',
            '8882323885',
            '8882323886'
        ];

        foreach ($trackingDids as $didNumber) {
            Did::create([
                'number' => $didNumber,
                'area_code' => '888',
                'country_code' => 'US',
                'provider' => 'twilio',
                'status' => 'available',
                'type' => 'local',
                'monthly_cost' => 2.00,
                'setup_cost' => 1.00,
                'purchased_at' => now(),
                'expires_at' => now()->addYear(),
                'call_forwarding_enabled' => true,
                'recording_enabled' => true,
                'analytics_enabled' => true,
                'metadata' => [
                    'purpose' => 'publisher_tracking'
                ]
            ]);
        }

        // Create sample publishers with your use case in mind
        $publishers = [
            [
                'name' => 'Premium Marketing Solutions',
                'email' => 'contact@premiummarketing.com',
                'phone' => '5551234567',
                'company' => 'Premium Marketing Solutions Inc.',
                'status' => 'active',
                'payout_rate' => 15.00,
                'payout_type' => 'per_call',
                'tracking_did' => '8882323882',
                'destination_did' => '8884444888', // Your main buyer DID
                'notes' => 'High-volume publisher, proven track record'
            ],
            [
                'name' => 'Digital Lead Pro',
                'email' => 'admin@digitalleadpro.com',
                'phone' => '5559876543',
                'company' => 'Digital Lead Pro LLC',
                'status' => 'active',
                'payout_rate' => 12.50,
                'payout_type' => 'per_call',
                'tracking_did' => '8882323883',
                'destination_did' => '8884444888',
                'notes' => 'Consistent quality leads'
            ],
            [
                'name' => 'Call Connect Media',
                'email' => 'leads@callconnectmedia.com',
                'phone' => '5556543210',
                'company' => 'Call Connect Media Group',
                'status' => 'active',
                'payout_rate' => 18.00,
                'payout_type' => 'per_call',
                'tracking_did' => '8882323884',
                'destination_did' => '8884444888',
                'notes' => 'Premium rates for exclusive leads'
            ],
            [
                'name' => 'Lead Generation Plus',
                'email' => 'info@leadgenplus.com',
                'phone' => '5551357924',
                'company' => 'Lead Generation Plus',
                'status' => 'active',
                'payout_rate' => 10.00,
                'payout_type' => 'per_call',
                'tracking_did' => '8882323885',
                'destination_did' => '8884444888',
                'notes' => 'Good volume, competitive pricing'
            ],
            [
                'name' => 'Quality Call Source',
                'email' => 'publisher@qualitycallsource.com',
                'phone' => '5552468135',
                'company' => 'Quality Call Source Ltd.',
                'status' => 'inactive',
                'payout_rate' => 8.00,
                'payout_type' => 'per_call',
                'tracking_did' => null, // Not assigned yet
                'destination_did' => '8884444888',
                'notes' => 'Currently on hold - pending quality review'
            ]
        ];

        foreach ($publishers as $publisherData) {
            $publisher = Publisher::create($publisherData);
            
            // If tracking DID is assigned, update the DID record
            if ($publisher->tracking_did) {
                $publisher->assignTrackingDid($publisher->tracking_did, $publisher->destination_did);
            }
        }

        $this->command->info('Created ' . count($publishers) . ' sample publishers with tracking DIDs');
        $this->command->info('Main buyer DID: 8884444888');
        $this->command->info('Publisher tracking DIDs: ' . implode(', ', $trackingDids));
    }
}
