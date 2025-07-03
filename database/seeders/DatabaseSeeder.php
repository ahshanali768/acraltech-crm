<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Only create the superadmin user with requested credentials
        User::factory()->create([
            'name' => 'Ahshan Ali',
            'username' => 'Aigppc',
            'email' => 'admin@arcaltech.site',
            'password' => bcrypt('Aigppc@768'),
            'role' => 'admin',
            'status' => 'active',
        ]);
    }
}
