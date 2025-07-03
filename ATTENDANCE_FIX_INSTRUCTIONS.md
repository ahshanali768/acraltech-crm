# Agent Attendance Table Fix - Manual Instructions

## Problem
The attendance page (/admin/attendance) is returning a 500 error because the `agent_attendance` table is missing required columns that the model and controller expect.

## Required Columns Missing
Based on the AgentAttendance model and Admin AttendanceController, these columns are missing:
- `login_time` (timestamp)
- `logout_time` (timestamp) 
- `latitude` (decimal)
- `longitude` (decimal)
- `address` (text)
- `logout_latitude` (decimal)
- `logout_longitude` (decimal)
- `logout_address` (text)
- `status` (varchar)

## Quick Fix Options

### Option 1: Upload and Run PHP Script (Recommended)
1. Upload `fix_agent_attendance.php` to your server root
2. Run via SSH: `php fix_agent_attendance.php`
3. Delete the script: `rm fix_agent_attendance.php`

### Option 2: Direct MySQL Commands
Log into your Hostinger cPanel > MySQL Databases > phpMyAdmin and run:

```sql
USE u806021370_laravel_crm;

ALTER TABLE agent_attendance 
ADD COLUMN IF NOT EXISTS login_time TIMESTAMP NULL,
ADD COLUMN IF NOT EXISTS logout_time TIMESTAMP NULL,
ADD COLUMN IF NOT EXISTS latitude DECIMAL(10,8) NULL,
ADD COLUMN IF NOT EXISTS longitude DECIMAL(11,8) NULL,
ADD COLUMN IF NOT EXISTS address TEXT NULL,
ADD COLUMN IF NOT EXISTS logout_latitude DECIMAL(10,8) NULL,
ADD COLUMN IF NOT EXISTS logout_longitude DECIMAL(11,8) NULL,
ADD COLUMN IF NOT EXISTS logout_address TEXT NULL,
ADD COLUMN IF NOT EXISTS status VARCHAR(50) DEFAULT 'present';

-- Add sample data
INSERT IGNORE INTO agent_attendance (user_id, date, login_time, logout_time, latitude, longitude, address, status, created_at, updated_at) 
VALUES (1, CURDATE(), NOW() - INTERVAL 8 HOUR, NOW() - INTERVAL 1 HOUR, 40.7128, -74.0060, 'New York, NY', 'present', NOW(), NOW());
```

### Option 3: Laravel Artisan Migration (Most Proper)
Create this migration and run it:

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToAgentAttendanceTable extends Migration
{
    public function up()
    {
        Schema::table('agent_attendance', function (Blueprint $table) {
            if (!Schema::hasColumn('agent_attendance', 'login_time')) {
                $table->timestamp('login_time')->nullable();
            }
            if (!Schema::hasColumn('agent_attendance', 'logout_time')) {
                $table->timestamp('logout_time')->nullable();
            }
            if (!Schema::hasColumn('agent_attendance', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable();
            }
            if (!Schema::hasColumn('agent_attendance', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable();
            }
            if (!Schema::hasColumn('agent_attendance', 'address')) {
                $table->text('address')->nullable();
            }
            if (!Schema::hasColumn('agent_attendance', 'logout_latitude')) {
                $table->decimal('logout_latitude', 10, 8)->nullable();
            }
            if (!Schema::hasColumn('agent_attendance', 'logout_longitude')) {
                $table->decimal('logout_longitude', 11, 8)->nullable();
            }
            if (!Schema::hasColumn('agent_attendance', 'logout_address')) {
                $table->text('logout_address')->nullable();
            }
            if (!Schema::hasColumn('agent_attendance', 'status')) {
                $table->string('status', 50)->default('present');
            }
        });
    }
}
```

## After Fix
1. Clear Laravel caches: `php artisan cache:clear && php artisan config:clear`
2. Test attendance page: https://acraltech.site/admin/attendance
3. Should load without 500 error and show attendance records

## Files Created
- `/home/ahshanali768/project-export/fix_agent_attendance.php` - PHP script to fix table
- `/home/ahshanali768/project-export/fix_agent_attendance_table.sql` - SQL script
- `/home/ahshanali768/project-export/fix-agent-attendance.sh` - Deployment script (requires SSH access)
