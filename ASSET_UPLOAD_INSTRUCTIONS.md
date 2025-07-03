# Asset Upload Instructions

## Files to Upload

The following files need to be uploaded to the server at `domains/acraltech.site/public_html/build/`:

### Manifest File
- Upload `/home/ahshanali768/project-export/public/build/.vite/manifest.json` 
- To: `build/.vite/manifest.json`

### CSS Assets  
- Upload `/home/ahshanali768/project-export/public/build/assets/app-BgNOKJcH.css`
- To: `build/assets/app-BgNOKJcH.css`

### JavaScript Assets
- Upload `/home/ahshanali768/project-export/public/build/assets/app-BHFdub6u.js`
- To: `build/assets/app-BHFdub6u.js`

- Upload `/home/ahshanali768/project-export/public/build/assets/chat-K6rklCK8.js`
- To: `build/assets/chat-K6rklCK8.js`

- Upload `/home/ahshanali768/project-export/public/build/assets/index-xsH4HHeE.js`
- To: `build/assets/index-xsH4HHeE.js`

## Manual Upload Commands (if SSH works)

```bash
# Create directories
ssh u806021370@acraltech.site "cd domains/acraltech.site/public_html && mkdir -p build/.vite build/assets"

# Upload manifest
scp public/build/.vite/manifest.json u806021370@acraltech.site:domains/acraltech.site/public_html/build/.vite/

# Upload assets
scp public/build/assets/* u806021370@acraltech.site:domains/acraltech.site/public_html/build/assets/

# Clear cache
ssh u806021370@acraltech.site "cd domains/acraltech.site/public_html && php artisan cache:clear"
```

## Alternative: FTP Upload
If using FTP client, upload the entire `public/build/` directory structure to maintain the correct paths.

## After Upload
Clear Laravel caches:
```bash
php artisan cache:clear
php artisan config:clear  
php artisan view:clear
```
