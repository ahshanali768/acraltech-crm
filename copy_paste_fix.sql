-- SIMPLE COPY-PASTE FIX FOR ATTENDANCE PAGE
-- Use this in phpMyAdmin SQL tab

USE u806021370_laravel_crm;

ALTER TABLE agent_attendance ADD COLUMN IF NOT EXISTS login_time TIMESTAMP NULL;
ALTER TABLE agent_attendance ADD COLUMN IF NOT EXISTS logout_time TIMESTAMP NULL;
ALTER TABLE agent_attendance ADD COLUMN IF NOT EXISTS latitude DECIMAL(10,8) NULL;
ALTER TABLE agent_attendance ADD COLUMN IF NOT EXISTS longitude DECIMAL(11,8) NULL;
ALTER TABLE agent_attendance ADD COLUMN IF NOT EXISTS address TEXT NULL;
ALTER TABLE agent_attendance ADD COLUMN IF NOT EXISTS logout_latitude DECIMAL(10,8) NULL;
ALTER TABLE agent_attendance ADD COLUMN IF NOT EXISTS logout_longitude DECIMAL(11,8) NULL;
ALTER TABLE agent_attendance ADD COLUMN IF NOT EXISTS logout_address TEXT NULL;
ALTER TABLE agent_attendance ADD COLUMN IF NOT EXISTS status VARCHAR(50) DEFAULT 'present';

INSERT IGNORE INTO agent_attendance (user_id, date, login_time, logout_time, latitude, longitude, address, logout_latitude, logout_longitude, logout_address, status, created_at, updated_at) 
VALUES 
(1, CURDATE(), NOW() - INTERVAL 8 HOUR, NOW() - INTERVAL 1 HOUR, 40.7128, -74.0060, 'New York, NY', 40.7580, -73.9855, 'Times Square, NY', 'present', NOW(), NOW()),
(1, CURDATE() - INTERVAL 1 DAY, NOW() - INTERVAL 32 HOUR, NOW() - INTERVAL 25 HOUR, 40.7128, -74.0060, 'New York, NY', 40.7580, -73.9855, 'Times Square, NY', 'present', NOW(), NOW());

DESCRIBE agent_attendance;
SELECT COUNT(*) as total_records FROM agent_attendance;
SELECT 'Attendance fix completed!' as message;
