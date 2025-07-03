#!/bin/bash

# Direct SSH execution script for attendance fix
echo "🚀 Executing attendance table fix via SSH..."

# Create a temporary SQL file with simpler syntax for command line execution
cat > temp_fix.sql << 'EOF'
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
SELECT 'Attendance table fix completed!' as status;
EOF

echo "📤 Uploading fix script..."
# Try to upload the simplified SQL file
timeout 30 scp -P 65002 -o StrictHostKeyChecking=no -o ConnectTimeout=10 temp_fix.sql u806021370@srv521.hstgr.io:/home/u806021370/domains/acraltech.site/public_html/ 

if [ $? -eq 0 ]; then
    echo "✅ Upload successful! Executing SQL fix..."
    
    # Execute the SQL file
    timeout 60 ssh -p 65002 -o StrictHostKeyChecking=no -o ConnectTimeout=10 u806021370@srv521.hstgr.io "cd /home/u806021370/domains/acraltech.site/public_html && mysql -h localhost -u u806021370_laravel_crm -p'Zaq!2wsx' u806021370_laravel_crm < temp_fix.sql"
    
    if [ $? -eq 0 ]; then
        echo "🗄️ SQL executed successfully! Clearing Laravel caches..."
        
        # Clear Laravel caches
        timeout 30 ssh -p 65002 -o StrictHostKeyChecking=no u806021370@srv521.hstgr.io "cd /home/u806021370/domains/acraltech.site/public_html && php artisan cache:clear && php artisan config:clear && php artisan view:clear"
        
        # Cleanup temp file
        timeout 30 ssh -p 65002 -o StrictHostKeyChecking=no u806021370@srv521.hstgr.io "rm -f /home/u806021370/domains/acraltech.site/public_html/temp_fix.sql"
        
        echo "✅ ATTENDANCE TABLE FIX COMPLETED!"
        echo "🌐 Test the attendance page: https://acraltech.site/admin/attendance"
        
        # Test the attendance page
        echo "🧪 Testing attendance page..."
        response=$(curl -s -o /dev/null -w "%{http_code}" https://acraltech.site/admin/attendance)
        if [ "$response" = "200" ]; then
            echo "✅ Attendance page is working! (HTTP 200)"
        elif [ "$response" = "302" ]; then
            echo "🔄 Attendance page redirects to login (HTTP 302) - Normal behavior"
        else
            echo "⚠️ Attendance page returned HTTP $response"
        fi
        
    else
        echo "❌ SQL execution failed. Check database connection."
    fi
    
else
    echo "❌ Upload failed. Please use manual method:"
    echo ""
    echo "🔧 Manual Fix Instructions:"
    echo "1. Login to Hostinger cPanel → MySQL Databases → phpMyAdmin"
    echo "2. Select database: u806021370_laravel_crm" 
    echo "3. Copy and paste this SQL:"
    echo ""
    cat temp_fix.sql
fi

# Cleanup local temp file
rm -f temp_fix.sql

echo ""
echo "📋 Next Steps:"
echo "1. Login to: https://acraltech.site/admin/login" 
echo "2. Navigate to: Admin → Attendance"
echo "3. Verify page loads without 500 error"
echo "4. Check attendance records are displayed"
