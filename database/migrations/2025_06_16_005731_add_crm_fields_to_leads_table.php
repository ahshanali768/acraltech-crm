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
        Schema::table('leads', function (Blueprint $table) {
            if (!Schema::hasColumn('leads', 'external_id')) {
                $table->string('external_id')->nullable();
            }
            if (!Schema::hasColumn('leads', 'external_crm_id')) {
                $table->string('external_crm_id')->nullable();
            }
            if (!Schema::hasColumn('leads', 'crm_sync_status')) {
                $table->string('crm_sync_status')->default('pending');
            }
            if (!Schema::hasColumn('leads', 'crm_sync_error')) {
                $table->text('crm_sync_error')->nullable();
            }
            if (!Schema::hasColumn('leads', 'last_crm_sync')) {
                $table->timestamp('last_crm_sync')->nullable();
            }
            if (!Schema::hasColumn('leads', 'last_crm_sync_attempt')) {
                $table->timestamp('last_crm_sync_attempt')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn([
                'external_id',
                'external_crm_id',
                'crm_sync_status',
                'crm_sync_error',
                'last_crm_sync',
                'last_crm_sync_attempt'
            ]);
        });
    }
};
