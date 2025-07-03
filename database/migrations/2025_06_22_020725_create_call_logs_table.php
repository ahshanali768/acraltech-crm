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
        Schema::create('call_logs', function (Blueprint $table) {
            $table->id();
            $table->string('did', 20);
            $table->string('caller_id', 20);
            $table->integer('duration')->default(0); // in seconds
            $table->enum('status', ['answered', 'missed', 'busy', 'failed', 'completed'])->default('answered');
            $table->string('recording_url')->nullable();
            $table->decimal('cost', 8, 4)->default(0);
            $table->string('provider_call_id')->nullable();
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->foreignId('lead_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('campaign_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('agent_id')->nullable()->constrained('users')->onDelete('set null');
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['did', 'started_at']);
            $table->index(['campaign_id', 'status']);
            $table->index(['lead_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_logs');
    }
};
