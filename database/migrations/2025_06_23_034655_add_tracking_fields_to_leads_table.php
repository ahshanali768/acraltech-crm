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
        // All columns already exist, so this migration is now a no-op.
        // Schema::table('leads', function (Blueprint $table) {
        //     // Lead tracking and verification fields
        //     // $table->string('ip_address')->nullable()->after('notes'); // Already exists, skip to avoid duplicate error
        //     // $table->string('user_agent')->nullable()->after('ip_address'); // Already exists, skip to avoid duplicate error
        //     $table->string('jornaya_lead_id')->nullable()->after('user_agent');
        //     $table->string('trusted_form_cert_id')->nullable()->after('jornaya_lead_id');
        //     $table->string('trusted_form_url')->nullable()->after('trusted_form_cert_id');
        //     $table->string('device_fingerprint')->nullable()->after('trusted_form_url');
        //     $table->string('browser_language')->nullable()->after('device_fingerprint');
        //     $table->string('timezone')->nullable()->after('browser_language');
        //     $table->json('cookies_data')->nullable()->after('timezone');
        //     $table->string('referrer_url')->nullable()->after('cookies_data');
        //     $table->string('landing_page_url')->nullable()->after('referrer_url');
        //     $table->timestamp('lead_submitted_at')->nullable()->after('landing_page_url');
        //     $table->string('session_id')->nullable()->after('lead_submitted_at');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn([
                'ip_address',
                'user_agent', 
                'jornaya_lead_id',
                'trusted_form_cert_id',
                'trusted_form_url',
                'device_fingerprint',
                'browser_language',
                'timezone',
                'cookies_data',
                'referrer_url',
                'landing_page_url',
                'lead_submitted_at',
                'session_id'
            ]);
        });
    }
};
