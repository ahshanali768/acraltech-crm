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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('event_type'); // login, logout, password_change, permission_change, data_export, etc.
            $table->string('ip_address', 45);
            $table->string('user_agent')->nullable();
            $table->json('details')->nullable(); // Additional event details
            $table->enum('risk_level', ['low', 'medium', 'high', 'critical'])->default('low');
            $table->boolean('suspicious')->default(false);
            $table->string('session_id')->nullable();
            $table->string('location')->nullable(); // Geographic location
            $table->json('metadata')->nullable(); // Extra data like device info, browser, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
