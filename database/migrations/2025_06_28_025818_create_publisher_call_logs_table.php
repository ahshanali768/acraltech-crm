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
        Schema::create('publisher_call_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publisher_id')->constrained('publishers')->onDelete('cascade');
            $table->string('publisher_did', 20); // The tracking DID assigned to publisher
            $table->string('destination_did', 20); // Where the call forwards to (main buyer line)
            $table->string('caller_number', 20);
            $table->string('called_number', 20);
            $table->integer('duration')->default(0); // in seconds
            $table->enum('status', ['answered', 'missed', 'busy', 'failed', 'completed', 'no_answer'])->default('answered');
            $table->decimal('cost', 8, 4)->default(0); // Cost/payout for this call
            $table->string('provider_call_id')->nullable();
            $table->string('recording_url')->nullable();
            $table->timestamp('call_started_at');
            $table->timestamp('call_ended_at')->nullable();
            $table->string('call_source', 50)->default('web'); // web, mobile, direct, etc.
            $table->string('caller_city')->nullable();
            $table->string('caller_state')->nullable();
            $table->string('caller_country', 2)->default('US');
            $table->string('call_quality', 20)->nullable(); // excellent, good, fair, poor
            $table->boolean('is_billable')->default(true);
            $table->json('call_metadata')->nullable(); // Store additional call data
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['publisher_id', 'call_started_at']);
            $table->index(['publisher_did', 'status']);
            $table->index(['status', 'is_billable']);
            $table->index('call_started_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publisher_call_logs');
    }
};
