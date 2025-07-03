# EMERGENCY FIX: Allowed Locations Table Missing

## Problem
The settings page is showing a 500 error because the `allowed_locations` table doesn't exist in the database. This table is required for the location management section on the settings page.

## Manual Fix Instructions

Since SSH connectivity is currently having issues, here's how to fix this manually:

### Step 1: Upload the Fix Script
1. **Download the fix file**: Download `/home/ahshanali768/project-export/fix_allowed_locations.php` from this workspace
2. **Access Hostinger File Manager**: 
   - Go to your Hostinger hPanel
   - Navigate to **File Manager**
   - Go to `/domains/acraltech.site/public_html/`
3. **Upload the fix file**: Upload `fix_allowed_locations.php` to the root directory

### Step 2: Run the Fix
1. **Open in browser**: Navigate to `https://acraltech.site/fix_allowed_locations.php?fix_allowed_locations=yes`
2. **Execute the fix**: The script will:
   - Create the `allowed_locations` table
   - Add sample location data
   - Clear Laravel cache
3. **Verify success**: You should see green checkmarks and success messages

### Step 3: Security Cleanup
1. **Delete the fix file**: Go back to File Manager and delete `fix_allowed_locations.php` immediately after running it
2. **Verify deletion**: Ensure the file is completely removed for security

### Step 4: Test the Settings Page
1. **Test settings**: Go to `https://acraltech.site/admin/settings`
2. **Check location section**: Scroll down to the "Attendance Settings" section
3. **Verify locations**: You should see the "Allowed Locations" section with sample data

## What This Fix Does

### Creates the `allowed_locations` table with:
- `id` - Primary key
- `name` - Location name (e.g., "Main Office")
- `address` - Full address
- `latitude`/`longitude` - GPS coordinates
- `radius_meters` - Allowed radius for attendance
- `is_active` - Enable/disable location
- `location_type` - Type (office, branch, remote)
- `notes` - Additional notes
- `created_by` - User who created it
- `created_at`/`updated_at` - Timestamps

### Adds sample data:
1. **Main Office** - Primary office location
2. **Remote Hub A** - Remote work hub
3. **Branch Office** - Secondary branch office

## Alternative: SSH Fix (if connectivity is restored)

If SSH connectivity is restored, you can run the automated script:

```bash
cd /home/ahshanali768/project-export
./fix-allowed-locations.sh
```

## Expected Result

After this fix:
- ✅ Settings page loads without 500 errors
- ✅ Location management section is visible
- ✅ Sample locations are displayed
- ✅ You can add/edit/delete locations
- ✅ Attendance system can use location-based rules

## Next Steps

1. **Test all settings tabs**: Make sure all sections of the settings page work
2. **Customize locations**: Add your actual office/work locations
3. **Test attendance**: Verify location-based attendance works
4. **Run any remaining migrations**: Check if other tables need to be created

---

**Status**: This fix resolves the `allowed_locations` table missing error on the settings page.
**Impact**: Settings page will be fully functional after this fix.
**Security**: Remember to delete the fix script after running it!
