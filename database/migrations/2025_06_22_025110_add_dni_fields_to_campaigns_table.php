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
        Schema::table('campaigns', function (Blueprint $table) {
            $table->boolean('dni_enabled')->default(false);
            $table->integer('dni_pool_size')->nullable();
            $table->integer('dni_session_duration')->default(1800); // 30 minutes
            $table->boolean('dni_script_enabled')->default(false);
            $table->string('preferred_provider')->nullable();
            $table->string('fallback_number')->nullable();
            $table->json('routing_rules')->nullable();
            $table->decimal('payout_rate', 8, 2)->nullable();
            $table->boolean('call_recording_enabled')->default(false);
            $table->json('qualification_criteria')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn([
                'dni_enabled',
                'dni_pool_size',
                'dni_session_duration',
                'dni_script_enabled',
                'preferred_provider',
                'fallback_number',
                'routing_rules',
                'payout_rate',
                'call_recording_enabled',
                'qualification_criteria'
            ]);
        });
    }
};
