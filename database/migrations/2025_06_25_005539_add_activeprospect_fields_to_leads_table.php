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
            // ActiveProspect verification fields
            $table->string('verification_status')->nullable()->after('status');
            $table->json('verification_data')->nullable()->after('verification_status');
            $table->json('compliance_checks')->nullable()->after('verification_data');
            $table->string('verification_error')->nullable()->after('compliance_checks');
            
            // TrustedForm verification
            $table->boolean('trusted_form_verified')->nullable()->after('verification_error');
            $table->json('trusted_form_verification_data')->nullable()->after('trusted_form_verified');
            
            // Jornaya verification
            $table->boolean('jornaya_verified')->nullable()->after('trusted_form_verification_data');
            $table->json('jornaya_verification_data')->nullable()->after('jornaya_verified');
            
            // Lead quality and scoring
            $table->integer('quality_score')->nullable()->after('jornaya_verification_data');
            $table->string('quality_rating')->nullable()->after('quality_score');
            $table->json('risk_factors')->nullable()->after('quality_rating');
            $table->json('quality_recommendations')->nullable()->after('risk_factors');
            
            // Suppression checking
            $table->boolean('is_suppressed')->default(false)->after('quality_recommendations');
            $table->json('suppression_lists')->nullable()->after('is_suppressed');
            $table->string('suppression_reason')->nullable()->after('suppression_lists');
            
            // Data enhancement
            $table->json('enhanced_data')->nullable()->after('suppression_reason');
            $table->json('data_enhancements')->nullable()->after('enhanced_data');
            $table->integer('enhancement_confidence')->nullable()->after('data_enhancements');
            
            // Tracking fields that may be missing
            $table->string('ip_address')->nullable()->after('enhancement_confidence');
            $table->text('user_agent')->nullable()->after('ip_address');
            $table->string('trusted_form_cert_id')->nullable()->after('user_agent');
            $table->string('trusted_form_url')->nullable()->after('trusted_form_cert_id');
            $table->string('jornaya_lead_id')->nullable()->after('trusted_form_url');
            $table->string('device_fingerprint')->nullable()->after('jornaya_lead_id');
            $table->string('browser_language')->nullable()->after('device_fingerprint');
            $table->string('timezone')->nullable()->after('browser_language');
            $table->json('cookies_data')->nullable()->after('timezone');
            $table->string('referrer_url')->nullable()->after('cookies_data');
            $table->string('landing_page_url')->nullable()->after('referrer_url');
            $table->timestamp('lead_submitted_at')->nullable()->after('landing_page_url');
            $table->string('session_id')->nullable()->after('lead_submitted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Drop ActiveProspect verification fields
            $table->dropColumn([
                'verification_status',
                'verification_data',
                'compliance_checks',
                'verification_error',
                'trusted_form_verified',
                'trusted_form_verification_data',
                'jornaya_verified',
                'jornaya_verification_data',
                'quality_score',
                'quality_rating',
                'risk_factors',
                'quality_recommendations',
                'is_suppressed',
                'suppression_lists',
                'suppression_reason',
                'enhanced_data',
                'data_enhancements',
                'enhancement_confidence',
                'ip_address',
                'user_agent',
                'trusted_form_cert_id',
                'trusted_form_url',
                'jornaya_lead_id',
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
