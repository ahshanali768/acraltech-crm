<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('security_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('max_login_attempts')->default(5);
            $table->integer('lockout_duration')->default(30); // in minutes
            $table->integer('password_expiry_days')->default(90);
            $table->integer('min_password_length')->default(8);
            $table->boolean('require_special_char')->default(true);
            $table->boolean('require_uppercase')->default(true);
            $table->boolean('require_number')->default(true);
            $table->integer('session_timeout')->default(120); // in minutes
            $table->boolean('enable_2fa')->default(false);
            $table->json('ip_whitelist')->nullable();
            $table->json('allowed_countries')->nullable();
            $table->boolean('enable_captcha')->default(true);
            $table->boolean('secure_headers')->default(true);
            $table->integer('activity_log_retention')->default(30); // in days
            $table->foreignId('last_updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('security_settings')->insert([
            'max_login_attempts' => 5,
            'lockout_duration' => 30,
            'password_expiry_days' => 90,
            'min_password_length' => 8,
            'require_special_char' => true,
            'require_uppercase' => true,
            'require_number' => true,
            'session_timeout' => 120,
            'enable_2fa' => false,
            'ip_whitelist' => json_encode([]),
            'allowed_countries' => json_encode(['US', 'CA', 'GB', 'AU', 'IN']),
            'enable_captcha' => true,
            'secure_headers' => true,
            'activity_log_retention' => 30,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_settings');
    }
};
