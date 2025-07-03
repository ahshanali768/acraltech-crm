# Complete Nameserver Switch to Hostinger - Action Plan

## 🎯 Why This is the BEST Approach

✅ **Advantages:**
- Immediate full SSH access
- No DNS propagation delays
- Complete control over all records
- Can deploy everything without issues
- Can reconnect to Cloudflare later when ready

❌ **Temporary disadvantages:**
- Lose Cloudflare CDN/protection temporarily
- Slower global DNS resolution
- No DDoS protection during deployment

## 🔧 Steps to Switch Nameservers

### 1. Get Hostinger Nameservers
**Default Hostinger nameservers:**
```
ns1.hostinger.com
ns2.hostinger.com
```

### 2. Update at Domain Registrar
- Login to your domain registrar (where you bought acraltech.site)
- Find "DNS Management" or "Nameservers" section
- Change from:
  ```
  elisa.ns.cloudflare.com
  jonah.ns.cloudflare.com
  ```
- Change to:
  ```
  ns1.hostinger.com
  ns2.hostinger.com
  ```

### 3. Wait for Propagation
- **Time needed:** 2-24 hours (usually 2-6 hours)
- **Check status:** `ping acraltech.site` should show `46.202.161.86`

## 🚀 Deployment Plan After Nameserver Switch

### Phase 1: Verify SSH Access
```bash
# Test SSH connection
ssh u806021370@acraltech.site

# Should connect directly to Hostinger server
```

### Phase 2: Deploy Dashboard Changes
```bash
# Upload updated dashboard (remove DID Numbers, Total Calls cards)
scp resources/views/admin/dashboard.blade.php u806021370@acraltech.site:domains/acraltech.site/public_html/resources/views/admin/

# Clear Laravel cache
ssh u806021370@acraltech.site "cd domains/acraltech.site/public_html && php artisan cache:clear && php artisan view:clear"
```

### Phase 3: Fix JavaScript Assets
```bash
# Run the deployment script for missing assets
./deploy-assets.sh
```

### Phase 4: Verify Everything Works
```bash
# Test the site
curl -I https://acraltech.site/admin/dashboard
curl -I https://acraltech.site/build/assets/app-BgNOKJcH.css  # Should return 200
```

## 🔄 Reconnecting to Cloudflare Later

### When Ready to Re-enable Cloudflare:
1. **Switch nameservers back to Cloudflare**
2. **Import DNS records** (or recreate them)
3. **Enable proxy** for records you want protected
4. **Keep SSH record as "DNS only"** for future access

### Recommended Final Cloudflare Setup:
```
acraltech.site     A     46.202.161.86  ⚪ DNS only    (for SSH)
www.acraltech.site CNAME acraltech.site 🟠 Proxied    (for web traffic)
```

## 📋 Current Status Checklist

- [ ] Switch nameservers to Hostinger
- [ ] Wait for DNS propagation (2-6 hours)
- [ ] Test SSH access
- [ ] Deploy dashboard changes
- [ ] Upload missing CSS/JS assets
- [ ] Verify all functionality works
- [ ] (Optional) Reconnect to Cloudflare later

## ⏱️ Timeline Estimate

- **Nameserver change:** 5 minutes
- **DNS propagation:** 2-6 hours
- **Deployment & testing:** 30 minutes
- **Total project completion:** Today/tomorrow

**This approach ensures you get everything working properly without any DNS/proxy complications!**
