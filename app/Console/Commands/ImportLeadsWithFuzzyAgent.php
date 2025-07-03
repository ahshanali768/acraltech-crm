<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lead;
use App\Models\User;
use App\Models\Campaign;
use Illuminate\Support\Str;

class ImportLeadsWithFuzzyAgent extends Command
{
    protected $signature = 'leads:import-fuzzy {csv=database/maymonthdata.csv}';
    protected $description = 'Import leads from CSV, fuzzy match agent/verifier names, add DIDs to campaigns and users as agents if missing.';

    public function handle()
    {
        $csv = $this->argument('csv');
        if (!file_exists($csv)) {
            $this->error("CSV file not found: $csv");
            return 1;
        }
        $handle = fopen($csv, 'r');
        $header = fgetcsv($handle);
        $imported = 0;
        $skipped = 0;
        $agentCreated = 0;
        $verifierCreated = 0;
        $didCreated = 0;
        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);
            // Fuzzy match agent name
            $agentName = trim($data['Agent Name'] ?? '');
            $agent = $this->findOrCreateUser($agentName, 'agent', $agentCreated);
            // Fuzzy match verifier name
            $verifierName = trim($data['Verifier Name'] ?? '');
            $verifier = $verifierName ? $this->findOrCreateUser($verifierName, 'agent', $verifierCreated) : null;
            // Add DID to campaigns if not exists
            $did = trim($data['DID Number'] ?? $data['DiD'] ?? $data['DID'] ?? '');
            if ($did && !Campaign::where('did', $did)->exists()) {
                Campaign::create([
                    'campaign_name' => '', // To be set manually later
                    'commission_inr' => 0,
                    'did' => $did,
                    'payout_usd' => 0,
                    'status' => 'active',
                ]);
                $didCreated++;
            }
            // Check for duplicate lead (by phone and did)
            if (Lead::where('phone', $data['Phone'] ?? $data['Number'] ?? '')->where('did_number', $did)->exists()) {
                $skipped++;
                continue;
            }
            // Compose full name for 'name' field
            $firstName = $data['First Name'] ?? '';
            $lastName = $data['Last Name'] ?? '';
            $fullName = trim($firstName . ' ' . $lastName);
            Lead::create([
                'user_id' => $agent ? $agent->id : null,
                'name' => $fullName,
                'contact_info' => ($data['Phone'] ?? $data['Number'] ?? $data['Email'] ?? ''),
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone' => $data['Phone'] ?? $data['Number'] ?? '',
                'did_number' => $did,
                'address' => $data['Address'] ?? $data['address'] ?? '',
                'city' => $data['City'] ?? $data['city'] ?? '',
                'state' => $data['State'] ?? $data['state'] ?? '',
                'zip' => $data['ZIP'] ?? $data['zip'] ?? '',
                'email' => $data['Email'] ?? '',
                'agent_name' => $agent ? $agent->name : $agentName,
                'verifier_name' => $verifier ? $verifier->name : $verifierName,
                'campaign' => '', // To be set manually later
                'notes' => $data['Notes'] ?? '',
                'status' => 'pending',
            ]);
            $imported++;
        }
        fclose($handle);
        $this->info("Imported: $imported, Skipped (duplicates): $skipped, Agents created: $agentCreated, Verifiers created: $verifierCreated, DIDs created: $didCreated");
        return 0;
    }

    private function findOrCreateUser($name, $role, &$createdCount)
    {
        if (!$name) return null;
        $user = User::whereRaw('LOWER(name) = ?', [strtolower($name)])
            ->orWhereRaw('LOWER(username) = ?', [strtolower($name)])
            ->first();
        if ($user) return $user;
        // Fuzzy match
        $all = User::where('role', $role)->get();
        $best = null; $bestScore = 0;
        foreach ($all as $u) {
            $score = similar_text(strtolower($u->name), strtolower($name));
            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $u;
            }
        }
        if ($best && $bestScore > 5) return $best; // Accept close match
        // Create new agent if not found
        $createdCount++;
        return User::create([
            'name' => ucwords(strtolower($name)),
            'username' => Str::slug($name) . rand(100,999),
            'email' => Str::slug($name) . rand(1000,9999) . '@imported.local',
            'password' => bcrypt('password'),
            'role' => $role,
            'status' => 'active',
        ]);
    }
}
