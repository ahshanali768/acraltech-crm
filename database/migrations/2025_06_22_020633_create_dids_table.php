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
        Schema::create('dids', function (Blueprint $table) {
            $table->id();
            $table->string('number', 20)->unique();
            $table->string('area_code', 5);
            $table->string('country_code', 2)->default('US');
            $table->string('provider', 50)->default('twilio');
            $table->enum('status', ['available', 'active', 'expired', 'suspended'])->default('available');
            $table->enum('type', ['local', 'toll_free', 'mobile'])->default('local');
            $table->decimal('monthly_cost', 8, 2)->default(0);
            $table->decimal('setup_cost', 8, 2)->default(0);
            $table->foreignId('campaign_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamp('purchased_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->string('routing_destination')->nullable();
            $table->boolean('call_forwarding_enabled')->default(false);
            $table->boolean('recording_enabled')->default(false);
            $table->boolean('analytics_enabled')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'area_code']);
            $table->index(['campaign_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dids');
    }
};
