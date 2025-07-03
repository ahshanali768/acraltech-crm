-- Simple Attendance Fix for phpMyAdmin
-- Copy and paste this entire script into phpMyAdmin SQL tab

-- Select the correct database
USE u806021370_laravel_crm;

-- Add missing columns to agent_attendance table
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

-- Add sample attendance data
INSERT IGNORE INTO agent_attendance (
    user_id, 
    date, 
    login_time, 
    logout_time, 
    latitude, 
    longitude, 
    address, 
    logout_latitude, 
    logout_longitude, 
    logout_address,
    status,
    created_at, 
    updated_at
) VALUES 
(1, CURDATE(), NOW() - INTERVAL 8 HOUR, NOW() - INTERVAL 1 HOUR, 40.7128, -74.0060, 'New York, NY', 40.7580, -73.9855, 'Times Square, NY', 'present', NOW(), NOW()),
(1, CURDATE() - INTERVAL 1 DAY, NOW() - INTERVAL 32 HOUR, NOW() - INTERVAL 25 HOUR, 40.7128, -74.0060, 'New York, NY', 40.7580, -73.9855, 'Times Square, NY', 'present', NOW(), NOW()),
(1, CURDATE() - INTERVAL 2 DAY, NOW() - INTERVAL 56 HOUR, NOW() - INTERVAL 49 HOUR, 40.7128, -74.0060, 'New York, NY', 40.7580, -73.9855, 'Times Square, NY', 'present', NOW(), NOW());

-- Show updated table structure
DESCRIBE agent_attendance;

-- Show sample data
SELECT 'Fix completed! Check the attendance page now.' AS message;
SELECT COUNT(*) AS total_attendance_records FROM agent_attendance;
