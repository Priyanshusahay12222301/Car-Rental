# 🌐 InfinityFree Hosting - Car Rental App

## ✅ Why InfinityFree?
- **100% Free forever** - no time limits
- **cPanel hosting** - familiar interface  
- **MySQL database** included
- **No forced ads** on websites
- **FTP/File Manager** upload

## 🚀 Deploy to InfinityFree (15 minutes)

### Step 1: Create Account
1. **Go to**: [infinityfree.net](https://infinityfree.net)
2. **Click**: "Sign Up"
3. **Choose**: Free hosting plan
4. **Verify** email and activate account

### Step 2: Create Website
1. **Control Panel**: "Create Account"
2. **Domain**: Choose subdomain (e.g., `yourname.epizy.com`)
3. **Wait**: Account creation (5-10 minutes)
4. **Access**: cPanel when ready

### Step 3: Upload Files
**Method A: File Manager**
1. **cPanel**: Open "Online File Manager"
2. **Navigate**: to `htdocs` folder
3. **Upload**: ZIP your Car-Rental files
4. **Extract**: ZIP in htdocs

**Method B: FTP**
```bash
# FTP Details (from cPanel):
Host: files.infinityfree.net
Username: [your cPanel username]
Password: [your cPanel password]
Port: 21

# Upload all files to /htdocs/ folder
```

### Step 4: Create MySQL Database
1. **cPanel**: "MySQL Databases"
2. **Create**: New database `carrental`
3. **Create**: MySQL user with password
4. **Assign**: User to database (All privileges)
5. **Note**: Database details for config

### Step 5: Configure Database
**Edit `includes/config.php`:**
```php
<?php 
// InfinityFree database configuration
define('DB_HOST','sql200.infinityfree.com'); // Your DB host
define('DB_USER','your_db_username');        // Your DB user
define('DB_PASS','your_db_password');        // Your DB password  
define('DB_NAME','your_db_name');            // Your DB name

try {
    $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
} catch (PDOException $e) {
    exit("Error: " . $e->getMessage());
}
?>
```

### Step 6: Import Database
1. **cPanel**: Open "phpMyAdmin"
2. **Select**: Your database
3. **Import**: Upload `sqlfile/carrental.sql`
4. **Execute**: SQL import

### Step 7: Test Website
- **Visit**: `http://yoursubdomain.epizy.com`
- **Test**: Homepage, login, admin panel
- **Debug**: Check error logs if needed

## 🔧 InfinityFree Specifications
- **Disk Space**: 5GB
- **Bandwidth**: Unlimited
- **MySQL**: 400 databases
- **PHP**: Version 8.0+
- **Subdomains**: Unlimited
- **Email**: 10 accounts

## 📁 File Structure for Upload
```
htdocs/
├── admin/
├── assets/
├── includes/
├── sqlfile/
├── index.php
├── car-listing.php
├── ... (all your PHP files)
```

## ⚡ Quick Upload Script
Save this as `upload-infinityfree.bat`:
```batch
@echo off
echo 📁 Preparing files for InfinityFree upload...
7z a car-rental-infinityfree.zip * -x!.git -x!node_modules -x!*.bat
echo ✅ Created car-rental-infinityfree.zip
echo 📤 Upload this ZIP to htdocs folder in File Manager
pause
```

## 🎉 Expected Result
- **Live URL**: `http://yourname.epizy.com`  
- **cPanel Access**: Full hosting control
- **MySQL**: phpMyAdmin access
- **FTP**: File management
- **Email**: Professional email accounts

## 🆘 Common Issues

**File Upload Problems:**
- Use ZIP upload for faster transfer
- Check file permissions (755 for folders, 644 for files)
- Avoid special characters in filenames

**Database Connection:**
- Verify database host from cPanel
- Check username/password exactly
- Ensure user has database privileges

**PHP Errors:**
- Check Error Logs in cPanel
- Verify PHP version compatibility
- Some functions may be disabled for security

## 💡 Pro Tips
1. **Always backup** your files and database
2. **Use cPanel File Manager** for quick edits
3. **Check Error Logs** for debugging
4. **Enable Cloudflare** for better performance
5. **Custom domain** available (free setup)

**Start here**: [infinityfree.net](https://infinityfree.net) 🌐