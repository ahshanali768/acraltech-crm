<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DailyPassword;
use Carbon\Carbon;

class GenerateDailyPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'password:generate-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new 4-digit daily password for DIDs access';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $password = DailyPassword::generateTodaysPassword();
        
        $this->info("Daily password generated successfully!");
        $this->info("Date: " . $password->password_date->format('Y-m-d'));
        $this->info("Password: " . $password->password_code);
        $this->info("Generated at: " . $password->generated_at->format('Y-m-d H:i:s'));
        
        return 0;
    }
}
