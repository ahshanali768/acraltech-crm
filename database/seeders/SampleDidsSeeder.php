<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SampleDidsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('dids')->insert([
            [
                'number' => '+1-800-555-0100',
                'provider' => 'Twilio',
                'status' => 'active',


                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'number' => '+1-800-555-0200',
                'provider' => 'Twilio',
                'status' => 'active',


                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
