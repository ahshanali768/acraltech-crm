<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Publisher;
use App\Models\PublisherDid;
use App\Models\PublisherCallLog;
use App\Models\PublisherLead;
use App\Models\User;
use Carbon\Carbon;

class PublisherDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample publishers
        $publishers = [
            [
                'name' => 'Premium Marketing Solutions',
                'email' => 'contact@premiummarketing.com',
                'phone' => '+1-555-0101',
                'company' => 'Premium Marketing Solutions LLC',
                'status' => 'active',
                'payout_rate' => 15.00,
                'payout_type' => 'per_call',
                'tracking_did' => '8882323882',
                'destination_did' => '8882323999',
                'notes' => 'High-volume publisher specializing in home insurance leads.',
                'metadata' => [
                    'signup_date' => '2025-01-15',
                    'lead_sources' => ['Google Ads', 'Facebook', 'Direct Mail'],
                    'quality_score' => 8.5
                ]
            ],
            [
                'name' => 'Digital Lead Generation Co',
                'email' => 'admin@digitalleadgen.com',
                'phone' => '+1-555-0102',
                'company' => 'Digital Lead Generation Co',
                'status' => 'active',
                'payout_rate' => 12.50,
                'payout_type' => 'per_call',
                'tracking_did' => '8882323883',
                'destination_did' => '8882323999',
                'notes' => 'Focuses on auto insurance and life insurance leads.',
                'metadata' => [
                    'signup_date' => '2025-02-01',
                    'lead_sources' => ['SEO', 'PPC', 'Social Media'],
                    'quality_score' => 7.8
                ]
            ]
        ];

        foreach ($publishers as $publisherData) {
            $publisher = Publisher::create($publisherData);
            
            // If tracking DID is assigned, create PublisherDid record
            if ($publisher->tracking_did) {
                $publisher->assignTrackingDid($publisher->tracking_did, $publisher->destination_did);
                
                // Create some sample call logs for active publishers
                $this->createSampleCallLogs($publisher);
                
                // Create some sample leads
                $this->createSampleLeads($publisher);
            }
        }

        // Create a test publisher user account
        $publisherUser = User::create([
            'name' => 'Test Publisher',
            'email' => 'publisher@test.com',
            'username' => 'testpublisher',
            'password' => bcrypt('password'),
            'role' => 'publisher',
            'status' => 'active',
        ]);

        // Link to the first publisher
        $firstPublisher = Publisher::first();
        if ($firstPublisher) {
            $firstPublisher->update(['email' => $publisherUser->email]);
        }

        $this->command->info('Created ' . count($publishers) . ' sample publishers with tracking DIDs and sample data');
        $this->command->info('Test publisher login: publisher@test.com / password');
    }

    private function createSampleCallLogs(Publisher $publisher)
    {
        $didNumbers = $publisher->dids()->pluck('number');
        
        foreach ($didNumbers as $didNumber) {
            for ($i = 0; $i < rand(10, 30); $i++) {
                $callStarted = Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23));
                $duration = rand(30, 300);
                
                PublisherCallLog::create([
                    'publisher_id' => $publisher->id,
                    'publisher_did' => $didNumber,
                    'destination_did' => $publisher->destination_did,
                    'caller_number' => '+1555' . rand(1000000, 9999999),
                    'called_number' => $didNumber,
                    'duration' => $duration,
                    'status' => collect(['answered', 'completed', 'missed', 'busy', 'failed', 'no_answer'])->random(),
                    'cost' => rand(1, 5) / 100, // $0.01 to $0.05
                    'call_started_at' => $callStarted,
                    'call_ended_at' => $callStarted->copy()->addSeconds($duration),
                    'call_source' => collect(['web', 'mobile', 'direct', 'referral'])->random(),
                    'caller_city' => collect(['New York', 'Los Angeles', 'Chicago', 'Houston'])->random(),
                    'caller_state' => collect(['NY', 'CA', 'IL', 'TX'])->random(),
                    'call_quality' => collect(['excellent', 'good', 'fair', 'poor'])->random(),
                    'is_billable' => true,
                    'call_metadata' => [
                        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                        'ip_address' => '192.168.1.' . rand(1, 254),
                        'referrer' => collect(['google.com', 'facebook.com', 'direct', 'bing.com'])->random()
                    ],
                    'created_at' => $callStarted,
                ]);
            }
        }
    }

    private function createSampleLeads(Publisher $publisher)
    {
        $callLogs = $publisher->callLogs()->where('status', 'completed')->take(15)->get();
        
        foreach ($callLogs as $callLog) {
            if (rand(1, 100) <= 70) { // 70% chance to create a lead from completed call
                PublisherLead::create([
                    'publisher_id' => $publisher->id,
                    'call_log_id' => $callLog->id,
                    'publisher_did' => $callLog->publisher_did,
                    'caller_number' => $callLog->caller_number,
                    'first_name' => collect(['John', 'Jane', 'Mike', 'Sarah', 'David', 'Lisa', 'Mark', 'Anna'])->random(),
                    'last_name' => collect(['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis'])->random(),
                    'email' => 'lead' . rand(1000, 9999) . '@example.com',
                    'address' => rand(100, 9999) . ' Main St',
                    'city' => $callLog->caller_city ?? collect(['New York', 'Los Angeles', 'Chicago'])->random(),
                    'state' => $callLog->caller_state ?? collect(['NY', 'CA', 'IL'])->random(),
                    'zip' => rand(10000, 99999),
                    'status' => collect(['new', 'contacted', 'qualified', 'converted', 'rejected'])->random(),
                    'quality' => collect(['high', 'medium', 'low'])->random(),
                    'estimated_value' => rand(100, 1000),
                    'notes' => 'Sample lead generated from call',
                    'lead_source' => 'call',
                    'ip_address' => $callLog->call_metadata['ip_address'] ?? '192.168.1.1',
                    'user_agent' => $callLog->call_metadata['user_agent'] ?? 'Mozilla/5.0',
                    'created_at' => $callLog->created_at,
                ]);
            }
        }
    }
}
