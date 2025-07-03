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
            // Add the new columns that the application is expecting
            $table->dateTime('login_time')->nullable()->after('user_id');
            $table->dateTime('logout_time')->nullable()->after('login_time');
            $table->decimal('latitude', 10, 8)->nullable()->after('logout_time');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('address', 500)->nullable()->after('longitude');
            $table->decimal('logout_latitude', 10, 8)->nullable()->after('address');
            $table->decimal('logout_longitude', 11, 8)->nullable()->after('logout_latitude');
            $table->string('logout_address', 500)->nullable()->after('logout_longitude');
        });
        
        // Copy data in a separate step after columns are created
        if (Schema::hasColumn('agent_attendance', 'clock_in') && Schema::hasColumn('agent_attendance', 'login_time')) {
            \DB::statement('UPDATE agent_attendance SET login_time = clock_in WHERE clock_in IS NOT NULL');
        }
        
        if (Schema::hasColumn('agent_attendance', 'clock_out') && Schema::hasColumn('agent_attendance', 'logout_time')) {
            \DB::statement('UPDATE agent_attendance SET logout_time = clock_out WHERE clock_out IS NOT NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agent_attendance', function (Blueprint $table) {
            $table->dropColumn([
                'login_time',
                'logout_time',
                'latitude',
                'longitude',
                'address',
                'logout_latitude',
                'logout_longitude',
                'logout_address'
            ]);
        });
    }
};
