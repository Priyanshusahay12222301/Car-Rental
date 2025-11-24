# Deploying Car Rental App to Vercel

## Prerequisites

1. **Database Setup**: Since Vercel doesn't provide MySQL hosting, you'll need a cloud MySQL database:
   - **PlanetScale** (Recommended - MySQL compatible, free tier)
   - **Railway MySQL** 
   - **AWS RDS** 
   - **Google Cloud SQL**
   - **ClearDB** (Heroku addon)

2. **Vercel Account**: Sign up at [vercel.com](https://vercel.com)

## Database Setup Instructions

### Option A: PlanetScale (Recommended)
1. Go to [planetscale.com](https://planetscale.com) and create account
2. Create new database called `carrental`
3. Import your SQL file (`sqlfile/carrental.sql`)
4. Get connection details from dashboard

### Option B: Railway
1. Go to [railway.app](https://railway.app) 
2. Create MySQL database
3. Import your SQL file
4. Get connection details

## Deployment Steps

### 1. Prepare Your Project
```bash
# Clone/download this repository
# Navigate to project directory
cd Car-Rental-main
```

### 2. Install Vercel CLI
```bash
npm i -g vercel
```

### 3. Update Configuration
Replace `includes/config.php` with `includes/config-vercel.php`:
```bash
# Backup original config
cp includes/config.php includes/config-original.php

# Use Vercel config
cp includes/config-vercel.php includes/config.php
```

### 4. Deploy to Vercel
```bash
# Login to Vercel
vercel login

# Deploy
vercel

# Follow prompts:
# - Link to existing project? No
# - Project name: car-rental-app (or your choice)
# - Directory: ./
# - Override settings? No
```

### 5. Set Environment Variables
In Vercel Dashboard → Your Project → Settings → Environment Variables:

```
DB_HOST=your_database_host
DB_USER=your_database_username  
DB_PASS=your_database_password
DB_NAME=carrental
```

### 6. Redeploy
After setting environment variables:
```bash
vercel --prod
```

## Important Notes

### Database Migration
Import your `sqlfile/carrental.sql` to your cloud database:
```sql
-- Use your database management tool or command line:
mysql -h your_host -u your_user -p your_database < sqlfile/carrental.sql
```

### File Uploads
Vercel has limitations on file uploads. For vehicle images:
1. Consider using cloud storage (AWS S3, Cloudinary)
2. Or use Vercel's built-in file handling

### Sessions
Vercel functions are stateless. You might need to:
1. Use database sessions instead of file sessions
2. Consider JWT tokens for authentication

## Alternative Deployment Platforms

If Vercel doesn't work well, consider:

1. **Railway** - Better PHP support
2. **Heroku** - Traditional hosting
3. **DigitalOcean App Platform**
4. **Shared hosting** (cPanel-based)

## Troubleshooting

### Common Issues:
1. **Database Connection**: Ensure environment variables are correct
2. **PHP Version**: Vercel uses PHP 8.x, ensure compatibility
3. **File Permissions**: May need to adjust file upload paths

### Testing Locally:
```bash
# Test with PHP built-in server
php -S localhost:8000
```

## Need Help?
- Check Vercel logs: `vercel logs`
- Vercel PHP documentation: [vercel.com/docs/runtimes/php](https://vercel.com/docs/runtimes/php)