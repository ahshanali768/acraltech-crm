-- Fix agent_attendance table to add missing columns expected by the model and controllers
-- This script will add missing columns safely (won't fail if they already exist)

USE u806021370_laravel_crm;

-- Add login_time column if it doesn't exist
SET @sql = IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE table_name = 'agent_attendance' 
     AND column_name = 'login_time' 
     AND table_schema = DATABASE()) = 0,
    'ALTER TABLE agent_attendance ADD COLUMN login_time TIMESTAMP NULL',
    'SELECT "login_time column already exists"'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add logout_time column if it doesn't exist
SET @sql = IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE table_name = 'agent_attendance' 
     AND column_name = 'logout_time' 
     AND table_schema = DATABASE()) = 0,
    'ALTER TABLE agent_attendance ADD COLUMN logout_time TIMESTAMP NULL',
    'SELECT "logout_time column already exists"'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add latitude column if it doesn't exist
SET @sql = IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE table_name = 'agent_attendance' 
     AND column_name = 'latitude' 
     AND table_schema = DATABASE()) = 0,
    'ALTER TABLE agent_attendance ADD COLUMN latitude DECIMAL(10,8) NULL',
    'SELECT "latitude column already exists"'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add longitude column if it doesn't exist
SET @sql = IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE table_name = 'agent_attendance' 
     AND column_name = 'longitude' 
     AND table_schema = DATABASE()) = 0,
    'ALTER TABLE agent_attendance ADD COLUMN longitude DECIMAL(11,8) NULL',
    'SELECT "longitude column already exists"'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add address column if it doesn't exist
SET @sql = IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE table_name = 'agent_attendance' 
     AND column_name = 'address' 
     AND table_schema = DATABASE()) = 0,
    'ALTER TABLE agent_attendance ADD COLUMN address TEXT NULL',
    'SELECT "address column already exists"'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add logout_latitude column if it doesn't exist
SET @sql = IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE table_name = 'agent_attendance' 
     AND column_name = 'logout_latitude' 
     AND table_schema = DATABASE()) = 0,
    'ALTER TABLE agent_attendance ADD COLUMN logout_latitude DECIMAL(10,8) NULL',
    'SELECT "logout_latitude column already exists"'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add logout_longitude column if it doesn't exist
SET @sql = IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE table_name = 'agent_attendance' 
     AND column_name = 'logout_longitude' 
     AND table_schema = DATABASE()) = 0,
    'ALTER TABLE agent_attendance ADD COLUMN logout_longitude DECIMAL(11,8) NULL',
    'SELECT "logout_longitude column already exists"'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add logout_address column if it doesn't exist
SET @sql = IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE table_name = 'agent_attendance' 
     AND column_name = 'logout_address' 
     AND table_schema = DATABASE()) = 0,
    'ALTER TABLE agent_attendance ADD COLUMN logout_address TEXT NULL',
    'SELECT "logout_address column already exists"'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add status column if it doesn't exist
SET @sql = IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE table_name = 'agent_attendance' 
     AND column_name = 'status' 
     AND table_schema = DATABASE()) = 0,
    'ALTER TABLE agent_attendance ADD COLUMN status VARCHAR(50) DEFAULT "present"',
    'SELECT "status column already exists"'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Show the updated table structure
DESCRIBE agent_attendance;

-- Add some sample data if the table is empty
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

SELECT 'Agent attendance table structure fixed and sample data added!' as message;
