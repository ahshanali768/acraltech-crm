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
        Schema::create('publisher_dids', function (Blueprint $table) {
            $table->id();
            $table->string('number', 20)->unique(); // The DID number
            $table->string('area_code', 5);
            $table->string('country_code', 2)->default('US');
            $table->string('provider', 50)->default('twilio');
            $table->enum('status', ['available', 'assigned', 'expired', 'suspended'])->default('available');
            $table->enum('type', ['local', 'toll_free', 'mobile'])->default('local');
            $table->decimal('monthly_cost', 8, 2)->default(0);
            $table->decimal('setup_cost', 8, 2)->default(0);
            $table->foreignId('publisher_id')->nullable()->constrained('publishers')->onDelete('set null');
            $table->string('destination_number', 20)->nullable(); // Where calls forward to
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('call_forwarding_enabled')->default(true);
            $table->boolean('recording_enabled')->default(false);
            $table->boolean('analytics_enabled')->default(true);
            $table->json('did_metadata')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'area_code']);
            $table->index(['publisher_id', 'status']);
            $table->index('assigned_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publisher_dids');
    }
};
