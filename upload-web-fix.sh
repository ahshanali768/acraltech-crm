#!/bin/bash

# Simple Upload Script for Web Fix
echo "📤 Uploading web-based attendance fix..."

# Try to copy the file using different methods
echo "Method 1: Trying SCP..."
scp -P 65002 -o StrictHostKeyChecking=no web_fix_attendance.php u806021370@srv521.hstgr.io:/home/u806021370/domains/acraltech.site/public_html/ 2>/dev/null

if [ $? -eq 0 ]; then
    echo "✅ Upload successful via SCP!"
    echo "🌐 Access the fix at: https://acraltech.site/web_fix_attendance.php"
else
    echo "❌ SCP failed. Please use one of these alternatives:"
    echo ""
    echo "🔧 Alternative 1: FTP/File Manager"
    echo "   1. Login to Hostinger cPanel"
    echo "   2. Go to File Manager"
    echo "   3. Navigate to public_html/"
    echo "   4. Upload web_fix_attendance.php"
    echo "   5. Visit: https://acraltech.site/web_fix_attendance.php"
    echo ""
    echo "🔧 Alternative 2: Direct SQL (phpMyAdmin)"
    echo "   1. Login to Hostinger cPanel > MySQL Databases"
    echo "   2. Open phpMyAdmin"
    echo "   3. Select database: u806021370_laravel_crm"
    echo "   4. Run this SQL:"
    echo ""
    echo "   ALTER TABLE agent_attendance"
    echo "   ADD COLUMN IF NOT EXISTS login_time TIMESTAMP NULL,"
    echo "   ADD COLUMN IF NOT EXISTS logout_time TIMESTAMP NULL,"
    echo "   ADD COLUMN IF NOT EXISTS latitude DECIMAL(10,8) NULL,"
    echo "   ADD COLUMN IF NOT EXISTS longitude DECIMAL(11,8) NULL,"
    echo "   ADD COLUMN IF NOT EXISTS address TEXT NULL,"
    echo "   ADD COLUMN IF NOT EXISTS logout_latitude DECIMAL(10,8) NULL,"
    echo "   ADD COLUMN IF NOT EXISTS logout_longitude DECIMAL(11,8) NULL,"
    echo "   ADD COLUMN IF NOT EXISTS logout_address TEXT NULL,"
    echo "   ADD COLUMN IF NOT EXISTS status VARCHAR(50) DEFAULT 'present';"
    echo ""
    echo "   Then add sample data:"
    echo "   INSERT INTO agent_attendance (user_id, date, login_time, logout_time, latitude, longitude, address, status, created_at, updated_at)"
    echo "   VALUES (1, CURDATE(), NOW() - INTERVAL 8 HOUR, NOW() - INTERVAL 1 HOUR, 40.7128, -74.0060, 'New York, NY', 'present', NOW(), NOW());"
fi

echo ""
echo "📋 Files created for attendance fix:"
echo "  - web_fix_attendance.php (Web-based fixer)"
echo "  - fix_agent_attendance.php (Command-line fixer)"
echo "  - fix_agent_attendance_table.sql (Raw SQL script)"
echo "  - ATTENDANCE_FIX_INSTRUCTIONS.md (Manual instructions)"
