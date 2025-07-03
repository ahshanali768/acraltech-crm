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
        Schema::create('publisher_leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publisher_id')->constrained('publishers')->onDelete('cascade');
            $table->foreignId('call_log_id')->nullable()->constrained('publisher_call_logs')->onDelete('set null');
            $table->string('publisher_did', 20); // The DID that generated this lead
            $table->string('caller_number', 20);
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('country', 2)->default('US');
            $table->enum('status', ['new', 'contacted', 'qualified', 'converted', 'rejected'])->default('new');
            $table->enum('quality', ['high', 'medium', 'low'])->default('medium');
            $table->decimal('estimated_value', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->string('lead_source', 50)->default('call'); // call, form, chat, etc.
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('referrer_url')->nullable();
            $table->timestamp('contacted_at')->nullable();
            $table->timestamp('qualified_at')->nullable();
            $table->timestamp('converted_at')->nullable();
            $table->json('lead_metadata')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['publisher_id', 'status']);
            $table->index(['publisher_did', 'created_at']);
            $table->index(['status', 'quality']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publisher_leads');
    }
};
