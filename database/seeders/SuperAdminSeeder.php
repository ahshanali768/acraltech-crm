<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'aigpaypercall@gmail.com')->first();
        if (!$user) {
            User::create([
                'name' => 'Ahshan Ali',
                'username' => 'Aigppc',
                'email' => 'aigpaypercall@gmail.com',
                'password' => Hash::make('Aigppc@768'),
                'role' => 'admin',
                'status' => 'active',
            ]);
        }
    }
}
