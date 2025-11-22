# GD Extension Fix

## Issue
Error: "The PHP GD extension is required, but is not installed" when downloading reports.

## Solution Applied
✅ Enabled the GD extension in `C:\xampp\php\php.ini` by uncommenting the line:
```
extension=gd
```

## Next Steps Required

### 1. Restart Apache in XAMPP
- Open **XAMPP Control Panel**
- Click **Stop** on Apache (if running)
- Click **Start** on Apache to restart it

### 2. Verify the Fix
After restarting Apache, you can verify the GD extension is loaded by:
- Creating a PHP info file: `<?php phpinfo(); ?>` and checking for GD section
- Or running: `php -m | findstr /i gd` (should show "gd")

## What is GD Extension?
The GD (Graphics Draw) extension is a PHP library for creating and manipulating image files. It's required by DomPDF (the PDF generation library) to process images in PDF reports.

## Status
- ✅ GD extension enabled in php.ini
- ✅ GD DLL file exists at `C:\xampp\php\ext\php_gd.dll`
- ⚠️ **Apache restart required** for changes to take effect

