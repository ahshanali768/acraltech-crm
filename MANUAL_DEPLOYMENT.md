# 🚀 Manual Deployment Steps for Hostinger cPanel

## Since SSH is having connectivity issues, let's use cPanel deployment:

### **Method 1: cPanel File Manager (Recommended)**

#### Step 1: Prepare Files Locally
Your project is already built and ready! Files are in `/home/ahshanali768/project-export/`

#### Step 2: Access Hostinger cPanel
1. **Login to Hostinger Control Panel**
2. **Go to cPanel** 
3. **Open File Manager**
4. **Navigate to** `public_html` folder for `acraltech.site`

#### Step 3: Upload Project Files
**Option A: Upload via cPanel File Manager**
1. **Select all files** in your local project folder EXCEPT:
   - `node_modules/` folder
   - `.git/` folder  
   - `tests/` folder
   - `.env*` files
   - `storage/logs/*` contents

2. **Upload to** `public_html` folder
3. **Extract if using ZIP**

**Option B: Upload Individual Folders**
Upload these folders/files to `public_html`:
```
app/                    ✅ Upload
bootstrap/             ✅ Upload  
config/                ✅ Upload
database/              ✅ Upload
public/                ✅ Upload (contents go to root)
resources/             ✅ Upload
routes/                ✅ Upload
storage/               ✅ Upload (but clear cache folders)
vendor/                ✅ Upload
artisan                ✅ Upload
composer.json          ✅ Upload
composer.lock          ✅ Upload
package.json           ✅ Upload
```

#### Step 4: Important File Structure
**Move `public/` folder contents to `public_html` root:**
```
public_html/
├── index.php          ← from public/index.php
├── .htaccess          ← from public/.htaccess (create if missing)
├── build/             ← from public/build/
├── app/               ← Laravel app folder
├── bootstrap/         ← Laravel bootstrap folder
├── config/            ← Laravel config folder
├── vendor/            ← Composer dependencies
└── ... (other Laravel folders)
```

#### Step 5: Create .env File
1. **In File Manager**, create `.env` file in `public_html` root
2. **Copy contents** from `.env.hostinger` file:

```env
APP_NAME="CRM System"
APP_ENV=production
APP_KEY=base64:WILL_GENERATE_THIS
APP_DEBUG=false
APP_URL=https://acraltech.site

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u806021370_acraltech
DB_USERNAME=u806021370_acraltech
DB_PASSWORD=Health@768

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=noreply@acraltech.site
MAIL_PASSWORD=YOUR_EMAIL_PASSWORD_HERE
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@acraltech.site
MAIL_FROM_NAME="CRM System"

CLOUDFLARE_ENABLED=true
TRUSTED_PROXIES=*

LOG_CHANNEL=daily
LOG_LEVEL=error
```

#### Step 6: Create .htaccess File
Create `.htaccess` in `public_html` root:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

<IfModule mod_headers.c>
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-XSS-Protection "1; mode=block"
</IfModule>
```

#### Step 7: Set File Permissions
**In cPanel File Manager:**
1. **Right-click `storage` folder** → Permissions → **755** (recursive)
2. **Right-click `bootstrap/cache` folder** → Permissions → **755** (recursive)
3. **Right-click `.env` file** → Permissions → **644**

#### Step 8: Run Laravel Commands
**In cPanel Terminal (if available) or create PHP script:**

Create `setup.php` in `public_html`:
```php
<?php
// Run this once via browser: https://acraltech.site/setup.php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "🔑 Generating app key...\n";
$kernel->call('key:generate');

echo "🗄️ Running migrations...\n";
$kernel->call('migrate', ['--force' => true]);

echo "🗃️ Caching config...\n";
$kernel->call('config:cache');

echo "🛣️ Caching routes...\n";
$kernel->call('route:cache');

echo "👁️ Caching views...\n";
$kernel->call('view:cache');

echo "🔗 Creating storage link...\n";
$kernel->call('storage:link');

echo "✅ Setup completed! Delete this file for security.";
?>
```

Then visit: `https://acraltech.site/setup.php`

---

### **Method 2: Alternative - Git Deployment**

If you can set up Git repository:

1. **Create GitHub repository** for your project
2. **Push your code** to GitHub
3. **In Hostinger cPanel** → **Git Repository** 
4. **Clone your repository**
5. **Set up deployment script**

---

### **Method 3: FTP/SFTP Upload**

Use FTP client like FileZilla:
```
Host: acraltech.site
Username: u806021370
Password: (your cPanel password)
Port: 21 (FTP) or 22 (SFTP)
```

---

## 📋 Post-Deployment Checklist

### ✅ **Immediate Tests:**
1. **Visit**: https://acraltech.site
2. **Should see**: Laravel welcome or your CRM login
3. **Check database**: Connection working
4. **Check admin**: https://acraltech.site/admin

### ✅ **If Issues:**
1. **Check error logs**: cPanel → Error Logs
2. **Check Laravel logs**: `storage/logs/laravel.log`
3. **Verify permissions**: 755 for folders, 644 for files
4. **Check .env**: Database credentials correct

### ✅ **Email Setup:**
1. **cPanel** → **Email Accounts**
2. **Create**: `noreply@acraltech.site`
3. **Update .env**: Add email password

---

## 🚀 Ready to Deploy!

**Recommended order:**
1. **Upload files via cPanel File Manager**
2. **Create .env file with your credentials**
3. **Set permissions**
4. **Run setup.php**
5. **Test your site**
6. **Delete setup.php**

**Your database is ready**: ✅
**Your domain is ready**: ✅  
**Your files are built**: ✅

**Let's go live!** 🎯
