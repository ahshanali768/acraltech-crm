# 🚀 Complete Deployment Guide for CRM System

## 📋 Table of Contents
1. [SSH Setup for the Project](#ssh-setup)
2. [Hosting Transfer & Go Live](#hosting-deployment)
3. [VSCode Remote Development](#vscode-remote)
4. [Production Configuration](#production-config)
5. [Cloudflare Integration](#cloudflare-integration)
6. [Maintenance & Updates](#maintenance)

---

## 🔐 SSH Setup for Hostinger (Already Configured ✅)

### 1. Your Hostinger SSH Configuration

**✅ You already have SSH access set up! Here's your configuration:**

```bash
# Your Hostinger SSH details:
Host: 46.202.161.86
Port: 65002
Username: u806021370
Password: Health@768
Domain: acraltech.site
SSH Key: Available for key-based auth
```

### 2. Create SSH Config File for Easy Access

Create `~/.ssh/config` on your local machine:

```bash
# Hostinger Production Server (CORRECT SSH DETAILS)
Host hostinger-crm
    HostName 46.202.161.86
    User u806021370
    Port 65002
    IdentityFile ~/.ssh/id_ed25519
    ForwardAgent yes
    ServerAliveInterval 60
    ServerAliveCountMax 3

# Alternative connection using domain (once DNS is pointed)
Host acraltech-live
    HostName acraltech.site
    User u806021370
    Port 65002
    IdentityFile ~/.ssh/id_ed25519
    ForwardAgent yes

# Quick connection shortcut
Host crm-prod
    HostName 46.202.161.86
    User u806021370
    Port 65002
    IdentityFile ~/.ssh/id_ed25519
```

### 3. Test SSH Connection

```bash
# Test connection to Hostinger
ssh hostinger-crm

# Or test with domain (once DNS is set up)
ssh acraltech-live

# Quick test command
ssh -p 65002 u806021370@46.202.161.86 "pwd && ls -la"

# Test database connection
./test-connection.sh
```

**Note**: SSH hardening is managed by Hostinger on shared hosting, so you don't need to configure SSH security settings manually.

---

## 🌐 Hosting Transfer & Go Live (Hostinger)

### 1. Your Hostinger Hosting ✅

**You're already set up with Hostinger! Here's what you have:**
- **Shared/VPS Hosting** - Perfect for Laravel CRM
- **cPanel Access** - Easy file management
- **PHP 8.x Support** - Laravel compatible
- **MySQL Database** - Ready to use
- **Free SSL Certificate** - Automatic HTTPS
- **Git Support** - For deployments

### 2. Hostinger Plan Compatibility

**Check your current plan includes:**
```
✅ PHP 8.1+ (required for Laravel)
✅ MySQL 8.0+ (database)
✅ SSH Access (for advanced deployment)
✅ Git Support (for code deployment)
✅ SSL Certificate (free with Hostinger)
✅ Node.js Support (for asset compilation)
```

**If you have Premium/Business plan, you get:**
- SSH access for direct deployment
- Git integration
- Advanced file manager
- Better performance resources

### 3. Hostinger Deployment Methods

**Choose your preferred deployment method:**

#### Option A: cPanel File Manager (Easiest)
- Upload via cPanel File Manager
- Extract ZIP file
- Configure database through cPanel

#### Option B: Git Deployment (Recommended)
- Use Hostinger's Git integration
- Push code directly from repository
- Automated deployment workflow

#### Option C: SSH Deployment (Advanced)
- Direct command-line access
- Full control over deployment
- Requires Premium/Business plan

### 4. Hostinger SSH Deployment (Recommended)

**Since you have SSH access, this is the best method:**

**Step 1: Connect to Your Server**
```bash
# Connect via SSH
ssh hostinger-crm

# Navigate to your domain folder
cd domains/acraltech.site/public_html
```

**Step 2: Git Deployment Setup**
```bash
# Clone your repository (replace with your actual repo)
git clone https://github.com/yourusername/your-crm-repo.git .

# Or if you prefer to keep Git separate from public folder:
cd ~/
git clone https://github.com/yourusername/your-crm-repo.git crm-source
```

**Step 3: Hostinger Directory Structure**
```
Your account structure:
/home/u806021370/
├── domains/
│   └── acraltech.site/
│       └── public_html/           # Web root (move Laravel public/* here)
├── crm-source/                    # Git repository (optional)
│   ├── app/
│   ├── config/
│   ├── database/
│   ├── resources/
│   └── public/                    # Move contents to public_html/
└── .ssh/                          # SSH keys (already set up)
```

**Step 4: Deployment Script for Hostinger**

Create `deploy-hostinger.sh`:

```bash
#!/bin/bash

# Hostinger Deployment Script
echo "🚀 Deploying CRM to Hostinger..."

# Variables
DOMAIN_PATH="/home/u806021370/domains/acraltech.site/public_html"
SOURCE_PATH="/home/u806021370/crm-source"

# Pull latest changes
echo "📥 Pulling latest code..."
cd $SOURCE_PATH
git pull origin main

# Install/update dependencies
echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Build assets (if Node.js is available)
if command -v npm &> /dev/null; then
    echo "🏗️ Building assets..."
    npm install
    npm run build
fi

# Sync files to public_html (excluding public folder)
echo "📋 Syncing application files..."
rsync -av --exclude='public' --exclude='.git' --exclude='node_modules' $SOURCE_PATH/ $DOMAIN_PATH/

# Move public folder contents to web root
echo "🌐 Setting up web root..."
rsync -av $SOURCE_PATH/public/ $DOMAIN_PATH/

# Set up .env if it doesn't exist
if [ ! -f "$DOMAIN_PATH/.env" ]; then
    echo "⚙️ Setting up environment..."
    cp $DOMAIN_PATH/.env.example $DOMAIN_PATH/.env
fi

# Set correct permissions
echo "🔒 Setting permissions..."
chmod -R 755 $DOMAIN_PATH/storage
chmod -R 755 $DOMAIN_PATH/bootstrap/cache
chmod 644 $DOMAIN_PATH/.env

# Run Laravel commands
echo "🎯 Running Laravel setup..."
cd $DOMAIN_PATH

# Generate app key if needed
if ! grep -q "APP_KEY=base64:" .env; then
    php artisan key:generate --no-interaction
fi

# Run migrations
php artisan migrate --force --no-interaction

# Clear and cache configs
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
php artisan storage:link

echo "✅ Deployment completed successfully!"
echo "🌐 Your CRM should be available at: https://acraltech.site"
```

### 5. Database Setup in Hostinger

**Step 1: Database Already Created ✅**
Your Hostinger database is already set up:
- **Database Name**: `u806021370_acraltech`
- **Database User**: `u806021370_acraltech`
- **Password**: `Health@768`
- **Host**: `localhost`
- **Created**: 2025-06-13

**Step 2: Database via SSH (Alternative)**
```bash
# Connect to your server
ssh hostinger-crm

# Access MySQL (you'll be prompted for password)
mysql -u u806021370 -p

# Create database and user (ALREADY DONE FOR YOU)
# Your existing database:
# Database: u806021370_acraltech
# User: u806021370_acraltech  
# Password: Health@768

# You can connect and check with:
mysql -u u806021370_acraltech -p'Health@768' u806021370_acraltech
```

### 6. Environment Configuration (.env) for Hostinger

Create/update `.env` file in your domain's public_html directory:

```env
# Application
APP_NAME="CRM System"
APP_ENV=production
APP_KEY=base64:your-generated-app-key
APP_DEBUG=false
APP_URL=https://acraltech.site

# Database (Hostinger - YOUR ACTUAL CREDENTIALS)
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u806021370_acraltech
DB_USERNAME=u806021370_acraltech
DB_PASSWORD=Health@768

# Cache & Sessions (File-based for shared hosting)
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

# Mail (Hostinger SMTP or your domain email)
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=noreply@acraltech.site
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@acraltech.site
MAIL_FROM_NAME="CRM System"

# Cloudflare
CLOUDFLARE_ENABLED=true
TRUSTED_PROXIES=*

# Logging
LOG_CHANNEL=daily
LOG_LEVEL=error
LOG_DEPRECATIONS_CHANNEL=null
LOG_STACK=single
```

### 7. Hostinger File Permissions

**Set correct permissions via cPanel File Manager:**

1. **Storage folder**: 755 (recursive)
2. **Bootstrap/cache**: 755 (recursive)
3. **.env file**: 644
4. **All PHP files**: 644

**Or use cPanel Terminal (if available):**
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod 644 .env
find . -type f -name "*.php" -exec chmod 644 {} \;
```

### 8. Laravel Artisan Commands

**Run these commands in cPanel Terminal or create a setup script:**

```bash
# Generate application key (if not set)
php artisan key:generate

# Run database migrations
php artisan migrate --force

# Clear and cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link for file uploads
php artisan storage:link
```

### 9. Hostinger .htaccess Configuration

**Create/update `.htaccess` in your `public_html` root:**

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

# Cache Control for Static Assets
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/ico "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 year"
</IfModule>
```

### 5. Nginx Configuration

Create `/etc/nginx/sites-available/crm`:

```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/crm/public;
    index index.php index.html index.htm;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/javascript application/xml+rss application/json;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

Enable the site:
```bash
sudo ln -s /etc/nginx/sites-available/crm /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## 💻 VSCode Remote Development with Hostinger

### 1. Install VSCode Extensions

Install these essential extensions:

```json
{
    "recommendations": [
        "ms-vscode-remote.remote-ssh",
        "ms-vscode-remote.remote-ssh-edit", 
        "bmewburn.vscode-intelephense-client",
        "bradlc.vscode-tailwindcss",
        "formulahendry.auto-rename-tag",
        "ms-vscode.vscode-json"
    ]
}
```

### 2. Configure Remote SSH for Hostinger

**Step 1: Set up SSH config (if not done already)**
```bash
# Add to ~/.ssh/config
Host hostinger-crm
    HostName in-mum-web1752.main-hosting.eu
    User u806021370
    Port 22
    IdentityFile ~/.ssh/id_rsa
    ForwardAgent yes
```

**Step 2: Connect via VSCode**
1. **Install Remote - SSH extension**
2. **Press Ctrl+Shift+P** → "Remote-SSH: Connect to Host"
3. **Select "hostinger-crm"** from the list
4. **Enter password if prompted**
5. **Wait for connection** (first time takes longer)

**Step 3: Open Your Project**
```bash
# Once connected, open terminal in VSCode (Ctrl+`)
cd domains/acraltech.site/public_html
code .
```

### 3. Remote Development Workspace

Create `.vscode/settings.json` in your project root:

```json
{
    "php.validate.executablePath": "/usr/bin/php",
    "intelephense.files.maxSize": 5000000,
    "files.associations": {
        "*.blade.php": "blade"
    },
    "emmet.includeLanguages": {
        "blade": "html"
    },
    "terminal.integrated.defaultProfile.linux": "bash",
    "git.enableSmartCommit": true,
    "git.confirmSync": false,
    "explorer.confirmDragAndDrop": false,
    "explorer.confirmDelete": false,
    "files.exclude": {
        "**/vendor": true,
        "**/node_modules": true,
        "**/.git": false
    }
}
```

### 4. Live Development Workflow on Hostinger

```bash
# Connect to server via SSH in VSCode
# Open terminal in VSCode (Ctrl+`)

# Navigate to your project
cd domains/acraltech.site/public_html

# For immediate changes (development mode)
php artisan config:clear
php artisan view:clear
php artisan route:clear

# For production optimizations
php artisan config:cache
php artisan route:cache  
php artisan view:cache

# Check Laravel status
php artisan about
```

### 5. Quick File Editing Tips

**Direct Live Editing:**
- Edit PHP files directly on server
- Changes appear immediately 
- No upload/download needed
- Full IntelliSense support

**Asset Compilation:**
```bash
# If you have Node.js access on Hostinger
npm run dev    # Development mode
npm run build  # Production build

# Otherwise, compile locally and upload
# Local: npm run build
# Then sync public/build/ folder to server
```

---

## ⚙️ Production Configuration

### 1. Environment Variables (.env)

```env
# Application
APP_NAME="CRM System"
APP_ENV=production
APP_KEY=your-generated-app-key
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=crm_production
DB_USERNAME=crm_user
DB_PASSWORD=secure-password

# Cache & Sessions
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=mail-password
MAIL_ENCRYPTION=tls

# Cloudflare
CLOUDFLARE_ENABLED=true
TRUSTED_PROXIES="173.245.48.0/20,103.21.244.0/22,103.22.200.0/22,103.31.4.0/22,141.101.64.0/18,108.162.192.0/18,190.93.240.0/20,188.114.96.0/20,197.234.240.0/22,198.41.128.0/17,162.158.0.0/15,104.16.0.0/13,104.24.0.0/14,172.64.0.0/13,131.0.72.0/22"

# Logging
LOG_CHANNEL=daily
LOG_LEVEL=error
```

### 2. Database Setup

```sql
-- Create database and user
CREATE DATABASE crm_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'crm_user'@'localhost' IDENTIFIED BY 'secure-password';
GRANT ALL PRIVILEGES ON crm_production.* TO 'crm_user'@'localhost';
FLUSH PRIVILEGES;
```

### 3. Install Redis (Recommended)

```bash
# Install Redis
sudo apt install redis-server

# Configure Redis
sudo nano /etc/redis/redis.conf

# Find and modify these lines:
# maxmemory 256mb
# maxmemory-policy allkeys-lru

# Restart Redis
sudo systemctl restart redis-server
sudo systemctl enable redis-server
```

### 4. SSL Certificate with Certbot

```bash
# Install SSL certificate
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Test auto-renewal
sudo certbot renew --dry-run

# Set up auto-renewal cron job
echo "0 12 * * * /usr/bin/certbot renew --quiet" | sudo crontab -
```

---

## ☁️ Cloudflare Integration

### 1. Update Nginx for Cloudflare

Add to your Nginx config:

```nginx
# Cloudflare IP ranges
set_real_ip_from 173.245.48.0/20;
set_real_ip_from 103.21.244.0/22;
set_real_ip_from 103.22.200.0/22;
set_real_ip_from 103.31.4.0/22;
set_real_ip_from 141.101.64.0/18;
set_real_ip_from 108.162.192.0/18;
set_real_ip_from 190.93.240.0/20;
set_real_ip_from 188.114.96.0/20;
set_real_ip_from 197.234.240.0/22;
set_real_ip_from 198.41.128.0/17;
set_real_ip_from 162.158.0.0/15;
set_real_ip_from 104.16.0.0/13;
set_real_ip_from 104.24.0.0/14;
set_real_ip_from 172.64.0.0/13;
set_real_ip_from 131.0.72.0/22;
real_ip_header CF-Connecting-IP;
```

### 2. Laravel Cloudflare Configuration

Create `config/cloudflare.php`:

```php
<?php

return [
    'enabled' => env('CLOUDFLARE_ENABLED', false),
    
    'trusted_proxies' => env('TRUSTED_PROXIES', [
        '173.245.48.0/20',
        '103.21.244.0/22',
        '103.22.200.0/22',
        '103.31.4.0/22',
        '141.101.64.0/18',
        '108.162.192.0/18',
        '190.93.240.0/20',
        '188.114.96.0/20',
        '197.234.240.0/22',
        '198.41.128.0/17',
        '162.158.0.0/15',
        '104.16.0.0/13',
        '104.24.0.0/14',
        '172.64.0.0/13',
        '131.0.72.0/22',
    ]),
];
```

---

## 🔧 Maintenance & Updates

### 1. Automated Backup Script

Create `scripts/backup.sh`:

```bash
#!/bin/bash

BACKUP_DIR="/var/backups/crm"
DB_NAME="crm_production"
DB_USER="crm_user"
DB_PASS="secure-password"
DATE=$(date +%Y%m%d_%H%M%S)

# Create backup directory
mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/db_backup_$DATE.sql

# Files backup
tar -czf $BACKUP_DIR/files_backup_$DATE.tar.gz /var/www/crm

# Keep only last 7 days of backups
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete

echo "Backup completed: $DATE"
```

### 2. Deployment Automation

Create GitHub Actions workflow `.github/workflows/deploy.yml`:

```yaml
name: Deploy to Production

on:
  push:
    branches: [ main ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v3
    
    - name: Deploy to server
      uses: appleboy/ssh-action@v0.1.5
      with:
        host: ${{ secrets.HOST }}
        username: ${{ secrets.USERNAME }}
        key: ${{ secrets.SSH_KEY }}
        script: |
          cd /var/www/crm
          git pull origin main
          composer install --no-dev --optimize-autoloader
          npm install
          npm run build
          php artisan migrate --force
          php artisan config:cache
          php artisan route:cache
          php artisan view:cache
          sudo systemctl reload nginx
```

### 3. Monitoring Script

Create `scripts/monitor.sh`:

```bash
#!/bin/bash

# Check if services are running
services=("nginx" "mysql" "php8.2-fpm" "redis-server")

for service in "${services[@]}"; do
    if systemctl is-active --quiet $service; then
        echo "✅ $service is running"
    else
        echo "❌ $service is down"
        sudo systemctl restart $service
    fi
done

# Check disk space
df -h | awk '$5 > 80 {print "⚠️ Disk space warning: " $0}'

# Check memory usage
free -m | awk 'NR==2{printf "Memory usage: %s/%sMB (%.2f%%)\n", $3,$2,$3*100/$2 }'
```

---

## 🚀 Quick Deployment Checklist

### Pre-deployment
- [ ] Server setup completed
- [ ] SSH keys configured
- [ ] Domain pointing to server
- [ ] SSL certificate installed

### Deployment
- [ ] Code deployed to server
- [ ] Dependencies installed
- [ ] Database migrated
- [ ] Environment configured
- [ ] Permissions set correctly

### Post-deployment
- [ ] Application accessible
- [ ] Admin panel working
- [ ] Cloudflare configured
- [ ] SSL working (HTTPS)
- [ ] Monitoring setup

### VSCode Remote Setup
- [ ] Remote SSH extension installed
- [ ] Connected to server
- [ ] Workspace configured
- [ ] Extensions installed

---

## 📞 Support & Troubleshooting

### Common Issues:

1. **Permission Errors**: Check file ownership and permissions
2. **Database Connection**: Verify database credentials
3. **SSL Issues**: Check certificate and Cloudflare settings
4. **Performance**: Monitor server resources and optimize

### Useful Commands:

```bash
# Check service status
sudo systemctl status nginx php8.2-fpm mysql

# Monitor logs
sudo tail -f /var/log/nginx/error.log
tail -f /var/www/crm/storage/logs/laravel.log

# Performance monitoring
htop
iotop
```

---

*Deployment Guide Updated: July 1, 2025*
*Complete production-ready deployment with modern DevOps practices*
