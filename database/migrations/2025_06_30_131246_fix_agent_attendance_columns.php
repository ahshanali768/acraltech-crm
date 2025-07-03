<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Add the new columns if they don't exist yet
        if (!Schema::hasColumn('agent_attendance', 'login_time')) {
            Schema::table('agent_attendance', function (Blueprint $table) {
                $table->dateTime('login_time')->nullable()->after('user_id');
            });
        }

        if (!Schema::hasColumn('agent_attendance', 'logout_time')) {
            Schema::table('agent_attendance', function (Blueprint $table) {
                $table->dateTime('logout_time')->nullable()->after('login_time');
            });
        }

        if (!Schema::hasColumn('agent_attendance', 'latitude')) {
            Schema::table('agent_attendance', function (Blueprint $table) {
                $table->decimal('latitude', 10, 8)->nullable()->after('logout_time');
            });
        }

        if (!Schema::hasColumn('agent_attendance', 'longitude')) {
            Schema::table('agent_attendance', function (Blueprint $table) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            });
        }

        if (!Schema::hasColumn('agent_attendance', 'address')) {
            Schema::table('agent_attendance', function (Blueprint $table) {
                $table->string('address', 500)->nullable()->after('longitude');
            });
        }

        if (!Schema::hasColumn('agent_attendance', 'logout_latitude')) {
            Schema::table('agent_attendance', function (Blueprint $table) {
                $table->decimal('logout_latitude', 10, 8)->nullable()->after('address');
            });
        }

        if (!Schema::hasColumn('agent_attendance', 'logout_longitude')) {
            Schema::table('agent_attendance', function (Blueprint $table) {
                $table->decimal('logout_longitude', 11, 8)->nullable()->after('logout_latitude');
            });
        }

        if (!Schema::hasColumn('agent_attendance', 'logout_address')) {
            Schema::table('agent_attendance', function (Blueprint $table) {
                $table->string('logout_address', 500)->nullable()->after('logout_longitude');
            });
        }

        // Step 2: Copy data from old columns to new columns
        try {
            // Check if both old and new columns exist
            if (Schema::hasColumn('agent_attendance', 'clock_in') && Schema::hasColumn('agent_attendance', 'login_time')) {
                DB::statement('UPDATE agent_attendance SET login_time = clock_in WHERE clock_in IS NOT NULL AND login_time IS NULL');
            }
            
            if (Schema::hasColumn('agent_attendance', 'clock_out') && Schema::hasColumn('agent_attendance', 'logout_time')) {
                DB::statement('UPDATE agent_attendance SET logout_time = clock_out WHERE clock_out IS NOT NULL AND logout_time IS NULL');
            }
            
            if (Schema::hasColumn('agent_attendance', 'clock_in_latitude') && Schema::hasColumn('agent_attendance', 'latitude')) {
                DB::statement('UPDATE agent_attendance SET latitude = clock_in_latitude WHERE clock_in_latitude IS NOT NULL AND latitude IS NULL');
            }
            
            if (Schema::hasColumn('agent_attendance', 'clock_in_longitude') && Schema::hasColumn('agent_attendance', 'longitude')) {
                DB::statement('UPDATE agent_attendance SET longitude = clock_in_longitude WHERE clock_in_longitude IS NOT NULL AND longitude IS NULL');
            }
            
            if (Schema::hasColumn('agent_attendance', 'clock_in_address') && Schema::hasColumn('agent_attendance', 'address')) {
                DB::statement('UPDATE agent_attendance SET address = clock_in_address WHERE clock_in_address IS NOT NULL AND address IS NULL');
            }
            
            if (Schema::hasColumn('agent_attendance', 'clock_out_latitude') && Schema::hasColumn('agent_attendance', 'logout_latitude')) {
                DB::statement('UPDATE agent_attendance SET logout_latitude = clock_out_latitude WHERE clock_out_latitude IS NOT NULL AND logout_latitude IS NULL');
            }
            
            if (Schema::hasColumn('agent_attendance', 'clock_out_longitude') && Schema::hasColumn('agent_attendance', 'logout_longitude')) {
                DB::statement('UPDATE agent_attendance SET logout_longitude = clock_out_longitude WHERE clock_out_longitude IS NOT NULL AND logout_longitude IS NULL');
            }
            
            if (Schema::hasColumn('agent_attendance', 'clock_out_address') && Schema::hasColumn('agent_attendance', 'logout_address')) {
                DB::statement('UPDATE agent_attendance SET logout_address = clock_out_address WHERE clock_out_address IS NOT NULL AND logout_address IS NULL');
            }
        } catch (\Exception $e) {
            // Log error but don't fail migration
            \Log::error('Error copying attendance data: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't want to drop columns in the down method since this is a fix
        // and would undo our fixes
    }
};
