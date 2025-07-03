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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('did_number')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('email');
            $table->string('agent_name')->nullable();
            $table->string('verifier_name')->nullable();
            $table->string('campaign')->nullable();
            $table->text('contact_info');
            $table->text('notes')->nullable();
            $table->string('status')->default('pending');
            $table->string('external_crm_id')->nullable();
            $table->string('crm_sync_status')->default('pending');
            $table->text('crm_sync_error')->nullable();
            $table->timestamp('last_crm_sync')->nullable();
            $table->timestamp('last_crm_sync_attempt')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
