#!/bin/bash

# Fixed Allowed Locations Table Script for CRM
# This script creates the missing allowed_locations table that's causing 500 errors on the settings page

echo "🔧 Creating allowed_locations table to fix settings page..."

# Database credentials from .env
DB_HOST="localhost"
DB_NAME="u806021370_acraltech"
DB_USER="u806021370_acraltech"
DB_PASS="AhshanAli768@"

# Upload SQL file via SSH
echo "📤 Uploading SQL fix file..."
scp -P 65002 fix_allowed_locations.sql u806021370@185.201.11.52:/home/u806021370/domains/acraltech.site/public_html/

# Execute SQL via SSH
echo "🗄️ Creating allowed_locations table..."
ssh -p 65002 u806021370@185.201.11.52 "cd /home/u806021370/domains/acraltech.site/public_html && mysql -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME < fix_allowed_locations.sql"

# Clear Laravel cache
echo "🧹 Clearing Laravel cache..."
ssh -p 65002 u806021370@185.201.11.52 "cd /home/u806021370/domains/acraltech.site/public_html && php artisan cache:clear && php artisan config:clear && php artisan view:clear"

echo "✅ Allowed locations table created! Settings page should now work."
echo "🌐 Test the settings page at: https://acraltech.site/admin/settings"
