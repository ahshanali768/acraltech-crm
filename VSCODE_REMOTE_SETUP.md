# 🔧 VSCode Remote Development Setup for Hostinger

## Quick Setup Guide

### 1. Install VSCode Extensions

Install these extensions in VSCode:
```
ms-vscode-remote.remote-ssh
ms-vscode-remote.remote-ssh-edit
ms-vscode.remote-explorer
bmewburn.vscode-intelephense-client
```

### 2. SSH Configuration

Your SSH config should be in `~/.ssh/config`:

```bash
# Hostinger Production Server
Host hostinger-crm
    HostName in-mum-web1752.main-hosting.eu
    User u806021370
    Port 22
    IdentityFile ~/.ssh/id_rsa
    ForwardAgent yes
    ServerAliveInterval 60
    ServerAliveCountMax 3

# Quick access alias
Host crm-live
    HostName acraltech.site
    User u806021370
    Port 22
    IdentityFile ~/.ssh/id_rsa
    ForwardAgent yes
```

### 3. Connect to Your Server

**Method 1: Command Palette**
1. Press `Ctrl+Shift+P` (or `Cmd+Shift+P` on Mac)
2. Type "Remote-SSH: Connect to Host"
3. Select `hostinger-crm` from the list

**Method 2: Remote Explorer**
1. Click the Remote Explorer icon in the sidebar
2. Find `hostinger-crm` under SSH Targets
3. Click the connect button

**Method 3: Terminal**
```bash
# Quick connection test
ssh hostinger-crm

# Or connect with VS Code directly
code --remote ssh-remote+hostinger-crm /home/u806021370/domains/acraltech.site/public_html
```

### 4. Working with Your CRM Files

Once connected, you can:

**Open your CRM directory:**
```
/home/u806021370/domains/acraltech.site/public_html
```

**Common directories you'll work with:**
- `/app/` - Laravel application code
- `/resources/views/` - Blade templates
- `/resources/css/` - Stylesheets
- `/resources/js/` - JavaScript files
- `/config/` - Configuration files
- `/routes/` - Route definitions

### 5. Remote Terminal Commands

In VSCode's integrated terminal (connected to Hostinger):

```bash
# Navigate to your web directory
cd /home/u806021370/domains/acraltech.site/public_html

# Laravel commands
php artisan migrate
php artisan cache:clear
php artisan config:cache

# Check logs
tail -f storage/logs/laravel.log

# File permissions
chmod -R 755 storage bootstrap/cache
```

### 6. Live Editing Workflow

**For quick fixes:**
1. Connect to `hostinger-crm` via VSCode
2. Edit files directly on the server
3. Changes are live immediately
4. Test at https://acraltech.site

**For major changes:**
1. Edit locally in your project
2. Test locally
3. Run `./deploy-hostinger.sh` to deploy
4. Or push to Git and pull on server

### 7. Debugging on Production

**Check Laravel logs:**
```bash
# View recent errors
tail -50 storage/logs/laravel.log

# Watch logs in real-time
tail -f storage/logs/laravel.log
```

**Check web server logs:**
```bash
# Hostinger error logs (path may vary)
tail -f ~/logs/acraltech.site/error.log
```

**Clear Laravel caches:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### 8. File Backup Strategy

**Before making changes:**
```bash
# Create backup of important files
cp .env .env.backup
cp -r app/ app.backup/

# Or create a full backup
tar -czf backup-$(date +%Y%m%d).tar.gz app config resources routes database .env
```

### 9. Quick Commands

Create aliases for common tasks in `~/.bashrc` on the server:

```bash
# Add to ~/.bashrc on Hostinger
alias crmdir='cd /home/u806021370/domains/acraltech.site/public_html'
alias crmlog='tail -f /home/u806021370/domains/acraltech.site/public_html/storage/logs/laravel.log'
alias crmcache='cd /home/u806021370/domains/acraltech.site/public_html && php artisan cache:clear && php artisan config:cache'
```

### 10. Extension Recommendations for Remote

Install these PHP/Laravel extensions when working remotely:

```json
{
    "recommendations": [
        "bmewburn.vscode-intelephense-client",
        "onecentlin.laravel-blade",
        "ryannaddy.laravel-artisan",
        "codingyu.laravel-goto-view",
        "amiralizadeh9480.laravel-extra-intellisense",
        "ms-vscode.vscode-json"
    ]
}
```

### 11. Troubleshooting Remote Connection

**If connection fails:**

1. **Test SSH manually:**
   ```bash
   ssh -v u806021370@in-mum-web1752.main-hosting.eu
   ```

2. **Check SSH key:**
   ```bash
   ssh-add -l  # List loaded keys
   ssh-add ~/.ssh/id_rsa  # Add key if missing
   ```

3. **Reset VSCode Remote:**
   - Press `Ctrl+Shift+P`
   - Run "Remote-SSH: Kill VS Code Server on Host"
   - Try connecting again

**If files don't sync:**
- VSCode remote automatically syncs
- For manual sync, use the deployment script
- Or use `rsync` for specific files

### 12. Security Best Practices

**While working remotely:**
- Always edit `.env` carefully (backup first)
- Don't leave debug mode on in production
- Monitor file permissions
- Use Laravel's maintenance mode for major updates:
  ```bash
  php artisan down  # Enable maintenance mode
  # Make your changes
  php artisan up    # Disable maintenance mode
  ```

---

## 🚀 Ready to Go Live!

Your Hostinger setup is complete with:
- ✅ SSH access configured
- ✅ VSCode remote development ready
- ✅ Deployment script available
- ✅ Laravel environment prepared

**Next steps:**
1. Run `./deploy-hostinger.sh` to deploy your CRM
2. Configure `.env` with your database credentials
3. Set up Cloudflare DNS for your domain
4. Test everything at https://acraltech.site
