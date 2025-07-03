<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateAgentUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:agent-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an agent user for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = \App\Models\User::updateOrCreate(
            ['email' => 'agent@test.com'],
            [
                'name' => 'Test Agent',
                'email' => 'agent@test.com',
                'password' => \Hash::make('password'),
                'role' => 'agent',
                'status' => 'active'
            ]
        );
        
        $this->info('Agent user created: agent@test.com (password: password)');
        return 0;
    }
}
