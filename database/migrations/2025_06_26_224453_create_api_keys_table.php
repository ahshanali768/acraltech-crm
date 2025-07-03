<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Key name/description
            $table->string('key', 64)->unique(); // API key
            $table->string('secret', 64)->nullable(); // Optional secret for HMAC
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('permissions')->nullable(); // Specific API permissions
            $table->json('allowed_ips')->nullable(); // IP whitelist
            $table->integer('rate_limit')->default(1000); // Requests per hour
            $table->integer('requests_used')->default(0);
            $table->datetime('last_used_at')->nullable();
            $table->datetime('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_keys');
    }
};
