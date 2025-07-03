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
        Schema::table('call_logs', function (Blueprint $table) {
            // $table->decimal('payout_amount', 8, 2)->nullable(); // Already exists, skip to avoid duplicate error
            // $table->integer('quality_score')->nullable(); // Already exists, skip to avoid duplicate error
            // $table->boolean('qualified')->default(false); // Already exists, skip to avoid duplicate error
            // $table->json('fraud_check_results')->nullable(); // Already exists, skip to avoid duplicate error
            // $table->string('recording_url')->nullable(); // Already exists, skip to avoid duplicate error
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('call_logs', function (Blueprint $table) {
            $table->dropColumn([
                'payout_amount',
                'quality_score',
                'qualified',
                'fraud_check_results',
                'recording_url'
            ]);
        });
    }
};
