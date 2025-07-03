# 🚀 CRM Deployment Quick Reference Card

## Your Hostinger Setup (Ready to Deploy!)

### 🔑 SSH Access
```bash
Host: in-mum-web1752.main-hosting.eu
User: u806021370
Domain: acraltech.site
```

### 🗄️ Database (Already Created ✅)
```bash
Database: u806021370_acraltech
User: u806021370_acraltech
Password: Health@768
Host: localhost
Created: 2025-06-13
```

### 📁 Server Paths
```bash
Web Root: /home/u806021370/domains/acraltech.site/public_html/
Home Dir: /home/u806021370/
SSH Keys: /home/u806021370/.ssh/
```

---

## 🚀 Deployment Commands

### 1. Test Connection
```bash
./test-connection.sh
```

### 2. Deploy CRM
```bash
./deploy-hostinger.sh
```

### 3. SSH Connect
```bash
ssh hostinger-crm
# or
ssh u806021370@in-mum-web1752.main-hosting.eu
```

### 4. VSCode Remote
```bash
# In VSCode Command Palette (Ctrl+Shift+P):
Remote-SSH: Connect to Host → hostinger-crm
```

---

## 📋 Post-Deployment Checklist

### On Server (via SSH):
```bash
cd domains/acraltech.site/public_html

# 1. Copy environment file
cp .env.hostinger .env

# 2. Generate app key
php artisan key:generate

# 3. Run migrations
php artisan migrate --force

# 4. Set permissions
chmod -R 755 storage bootstrap/cache

# 5. Cache configs
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Create storage link
php artisan storage:link
```

### Test Your Site:
- **Website**: https://acraltech.site
- **Admin Panel**: https://acraltech.site/admin
- **Database**: Already connected ✅

---

## 📧 Email Setup (Next)

### Create Email Account in cPanel:
1. Go to **Email Accounts**
2. Create: `noreply@acraltech.site`
3. Set strong password
4. Update `.env` file with password

### Email Settings:
```env
MAIL_USERNAME=noreply@acraltech.site
MAIL_PASSWORD=your-email-password
MAIL_FROM_ADDRESS=noreply@acraltech.site
```

---

## 🌐 Cloudflare Setup (Optional)

### DNS Records:
```
Type: A
Name: @
Value: [Your Hostinger IP]

Type: A  
Name: www
Value: [Your Hostinger IP]

Type: MX
Name: @
Value: mx1.hostinger.com (Priority: 10)
```

### SSL/TLS Settings:
- **SSL Mode**: Full (not Strict)
- **Always Use HTTPS**: On
- **Minimum TLS**: 1.2

---

## 🆘 Troubleshooting

### Database Issues:
```bash
# Test connection
mysql -h localhost -u u806021370_acraltech -p'Health@768' u806021370_acraltech
```

### Permission Issues:
```bash
chmod -R 755 storage bootstrap/cache
chmod 644 .env
```

### Cache Issues:
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Check Logs:
```bash
tail -f storage/logs/laravel.log
```

---

## 🎯 Files Ready for Deployment

- ✅ `.env.hostinger` - Production environment file
- ✅ `deploy-hostinger.sh` - Automated deployment script
- ✅ `test-connection.sh` - Database connection tester
- ✅ `VSCODE_REMOTE_SETUP.md` - VSCode remote guide
- ✅ `DEPLOYMENT_GUIDE.md` - Complete deployment guide

---

**🚀 You're ready to go live! Start with `./test-connection.sh` then `./deploy-hostinger.sh`**
