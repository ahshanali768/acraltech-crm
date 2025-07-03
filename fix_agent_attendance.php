<?php
/**
 * Fix Agent Attendance Table Structure
 * This script adds missing columns to the agent_attendance table
 * 
 * Upload this file to the server and run: php fix_agent_attendance.php
 */

require_once 'bootstrap/app.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "🔧 Fixing agent_attendance table structure...\n";

try {
    // Check if table exists
    if (!Schema::hasTable('agent_attendance')) {
        echo "❌ agent_attendance table does not exist!\n";
        exit(1);
    }

    $columnsToAdd = [
        'login_time' => 'timestamp',
        'logout_time' => 'timestamp',
        'latitude' => 'decimal:10,8',
        'longitude' => 'decimal:11,8',
        'address' => 'text',
        'logout_latitude' => 'decimal:10,8',
        'logout_longitude' => 'decimal:11,8',
        'logout_address' => 'text',
        'status' => 'string:50'
    ];

    foreach ($columnsToAdd as $column => $type) {
        if (!Schema::hasColumn('agent_attendance', $column)) {
            echo "➕ Adding column: {$column}\n";
            
            Schema::table('agent_attendance', function (Blueprint $table) use ($column, $type) {
                switch ($type) {
                    case 'timestamp':
                        $table->timestamp($column)->nullable();
                        break;
                    case 'decimal:10,8':
                        $table->decimal($column, 10, 8)->nullable();
                        break;
                    case 'decimal:11,8':
                        $table->decimal($column, 11, 8)->nullable();
                        break;
                    case 'text':
                        $table->text($column)->nullable();
                        break;
                    case 'string:50':
                        $table->string($column, 50)->default('present');
                        break;
                }
            });
        } else {
            echo "✅ Column {$column} already exists\n";
        }
    }

    // Add some sample data if table is empty
    $recordCount = DB::table('agent_attendance')->count();
    
    if ($recordCount == 0) {
        echo "➕ Adding sample attendance data...\n";
        
        // Get first user ID from users table
        $firstUser = DB::table('users')->first();
        if ($firstUser) {
            $sampleData = [
                [
                    'user_id' => $firstUser->id,
                    'date' => now()->format('Y-m-d'),
                    'login_time' => now()->subHours(8),
                    'logout_time' => now()->subHour(),
                    'latitude' => 40.7128,
                    'longitude' => -74.0060,
                    'address' => 'New York, NY',
                    'logout_latitude' => 40.7580,
                    'logout_longitude' => -73.9855,
                    'logout_address' => 'Times Square, NY',
                    'status' => 'present',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'user_id' => $firstUser->id,
                    'date' => now()->subDay()->format('Y-m-d'),
                    'login_time' => now()->subHours(32),
                    'logout_time' => now()->subHours(25),
                    'latitude' => 40.7128,
                    'longitude' => -74.0060,
                    'address' => 'New York, NY',
                    'logout_latitude' => 40.7580,
                    'logout_longitude' => -73.9855,
                    'logout_address' => 'Times Square, NY',
                    'status' => 'present',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'user_id' => $firstUser->id,
                    'date' => now()->subDays(2)->format('Y-m-d'),
                    'login_time' => now()->subHours(56),
                    'logout_time' => now()->subHours(49),
                    'latitude' => 40.7128,
                    'longitude' => -74.0060,
                    'address' => 'New York, NY',
                    'logout_latitude' => 40.7580,
                    'logout_longitude' => -73.9855,
                    'logout_address' => 'Times Square, NY',
                    'status' => 'present',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ];

            DB::table('agent_attendance')->insert($sampleData);
            echo "✅ Sample attendance data added!\n";
        } else {
            echo "⚠️ No users found, skipping sample data\n";
        }
    } else {
        echo "✅ Table already has {$recordCount} records\n";
    }

    // Show current table structure
    echo "\n📋 Current agent_attendance table structure:\n";
    $columns = DB::select("SHOW COLUMNS FROM agent_attendance");
    foreach ($columns as $column) {
        echo "  - {$column->Field} ({$column->Type})\n";
    }

    // Clear Laravel cache
    echo "\n🧹 Clearing Laravel caches...\n";
    \Artisan::call('cache:clear');
    \Artisan::call('config:clear');
    \Artisan::call('view:clear');

    echo "\n✅ Agent attendance table fix completed successfully!\n";
    echo "🌐 Test the attendance page at: https://acraltech.site/admin/attendance\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📍 File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    exit(1);
}
