#!/bin/bash

# Deploy script for uploading build assets to Hostinger
# SSH Details: Port 65002, User: u806021370, IP: 46.202.161.86
echo "Deploying build assets to Hostinger..."

# Create build directories on server
ssh -p 65002 u806021370@46.202.161.86 "cd domains/acraltech.site/public_html && mkdir -p build/.vite build/assets"

# Upload manifest file
scp -P 65002 public/build/.vite/manifest.json u806021370@46.202.161.86:domains/acraltech.site/public_html/build/.vite/

# Upload CSS assets
scp -P 65002 public/build/assets/app-BgNOKJcH.css u806021370@46.202.161.86:domains/acraltech.site/public_html/build/assets/

# Upload JS assets
scp -P 65002 public/build/assets/app-BHFdub6u.js u806021370@46.202.161.86:domains/acraltech.site/public_html/build/assets/
scp -P 65002 public/build/assets/chat-K6rklCK8.js u806021370@46.202.161.86:domains/acraltech.site/public_html/build/assets/
scp -P 65002 public/build/assets/index-xsH4HHeE.js u806021370@46.202.161.86:domains/acraltech.site/public_html/build/assets/

echo "Assets deployed successfully!"
echo "Clearing Laravel cache..."
ssh -p 65002 u806021370@46.202.161.86 "cd domains/acraltech.site/public_html && php artisan cache:clear && php artisan config:clear && php artisan view:clear"

echo "Deployment complete!"
