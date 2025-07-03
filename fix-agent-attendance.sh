#!/bin/bash

# Fix Agent Attendance Table - Deploy Script
# This script uploads and executes the SQL fix for the agent_attendance table

echo "🔧 Fixing agent_attendance table structure..."

# Upload the SQL file
echo "📤 Uploading SQL fix to server..."
scp -P 65002 -o StrictHostKeyChecking=no fix_agent_attendance_table.sql u806021370@srv521.hstgr.io:/home/u806021370/domains/acraltech.site/public_html/

# Execute the SQL file on the server
echo "🗄️ Executing SQL fix on database..."
ssh -p 65002 -o StrictHostKeyChecking=no u806021370@srv521.hstgr.io "cd /home/u806021370/domains/acraltech.site/public_html && mysql -h localhost -u u806021370_laravel_crm -p'Zaq!2wsx' u806021370_laravel_crm < fix_agent_attendance_table.sql"

# Clear Laravel cache
echo "🧹 Clearing Laravel cache..."
ssh -p 65002 -o StrictHostKeyChecking=no u806021370@srv521.hstgr.io "cd /home/u806021370/domains/acraltech.site/public_html && php artisan cache:clear && php artisan config:clear && php artisan view:clear"

# Cleanup the SQL file
echo "🧽 Cleaning up temporary files..."
ssh -p 65002 -o StrictHostKeyChecking=no u806021370@srv521.hstgr.io "rm -f /home/u806021370/domains/acraltech.site/public_html/fix_agent_attendance_table.sql"

echo "✅ Agent attendance table fix completed!"
echo "🌐 Test the attendance page at: https://acraltech.site/admin/attendance"

# Test the attendance page
echo "🧪 Testing attendance page..."
response=$(curl -s -o /dev/null -w "%{http_code}" -H "User-Agent: Mozilla/5.0" https://acraltech.site/admin/attendance)
if [ "$response" = "200" ]; then
    echo "✅ Attendance page is responding with HTTP 200!"
elif [ "$response" = "302" ]; then
    echo "🔄 Attendance page is redirecting (likely needs login) - HTTP 302"
else
    echo "❌ Attendance page returned HTTP $response"
fi

echo ""
echo "📋 Next steps:"
echo "1. Login to admin panel: https://acraltech.site/admin/login"
echo "2. Navigate to: Admin > Attendance"
echo "3. Verify the attendance page loads without 500 errors"
echo "4. Check that attendance records are displayed properly"
