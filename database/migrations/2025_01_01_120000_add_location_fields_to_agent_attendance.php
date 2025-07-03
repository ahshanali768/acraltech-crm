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
        Schema::table('agent_attendance', function (Blueprint $table) {
            // Location tracking fields
            $table->decimal('clock_in_latitude', 10, 8)->nullable()->after('clock_in_time');
            $table->decimal('clock_in_longitude', 11, 8)->nullable()->after('clock_in_latitude');
            $table->string('clock_in_address', 500)->nullable()->after('clock_in_longitude');
            $table->string('clock_in_ip_address', 45)->nullable()->after('clock_in_address');
            
            $table->decimal('clock_out_latitude', 10, 8)->nullable()->after('clock_out_time');
            $table->decimal('clock_out_longitude', 11, 8)->nullable()->after('clock_out_latitude');
            $table->string('clock_out_address', 500)->nullable()->after('clock_out_longitude');
            $table->string('clock_out_ip_address', 45)->nullable()->after('clock_out_address');
            
            // Distance from office in meters
            $table->integer('clock_in_distance_meters')->nullable()->after('clock_in_ip_address');
            $table->integer('clock_out_distance_meters')->nullable()->after('clock_out_ip_address');
            
            // Validation status
            $table->boolean('location_verified')->default(false)->after('clock_out_distance_meters');
            $table->text('location_notes')->nullable()->after('location_verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agent_attendance', function (Blueprint $table) {
            $table->dropColumn([
                'clock_in_latitude',
                'clock_in_longitude',
                'clock_in_address',
                'clock_in_ip_address',
                'clock_out_latitude',
                'clock_out_longitude',
                'clock_out_address',
                'clock_out_ip_address',
                'clock_in_distance_meters',
                'clock_out_distance_meters',
                'location_verified',
                'location_notes'
            ]);
        });
    }
};
