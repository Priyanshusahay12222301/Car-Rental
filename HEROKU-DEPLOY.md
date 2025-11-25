# 🚀 Heroku Deployment - Car Rental App

## ✅ Prerequisites
- Heroku account (free): [heroku.com](https://heroku.com)
- Your GitHub repository: `Priyanshusahay12222301/Car-Rental`

## 🎯 Deploy to Heroku (10 minutes)

### Step 1: Create Heroku App
1. **Go to**: [dashboard.heroku.com](https://dashboard.heroku.com)
2. **Click**: "New" → "Create new app"
3. **App name**: `car-rental-app-[yourname]`
4. **Region**: Choose closest to you
5. **Click**: "Create app"

### Step 2: Connect GitHub Repository
1. **In Deploy tab**: Select "GitHub" deployment method
2. **Connect**: Search for `Car-Rental` repository
3. **Click**: "Connect"
4. **Enable**: "Automatic deploys" (optional)

### Step 3: Add Database (Free)
```bash
# Option A: PostgreSQL (Recommended - Free)
# In Heroku dashboard → Resources tab
# Add-ons: Search "Heroku Postgres" → Select "Hobby Dev - Free"

# Option B: MySQL (JawsDB - Free tier)
# Resources → Add-ons → Search "JawsDB MySQL" → "Kitefin Shared - Free"
```

### Step 4: Set Environment Variables
**Settings tab → Config Vars → Add:**
```env
# If using PostgreSQL (recommended):
DATABASE_URL = [auto-populated by Heroku Postgres]

# If using MySQL:
MYSQL_URL = [from JawsDB addon]
DB_HOST = [from JawsDB addon]  
DB_USER = [from JawsDB addon]
DB_PASS = [from JawsDB addon]
DB_NAME = [from JawsDB addon]
```

### Step 5: Deploy
1. **Deploy tab**: Click "Deploy Branch" (master/main)
2. **Wait**: 2-3 minutes for build
3. **Click**: "View" to see your live app

### Step 6: Import Database
```sql
# For PostgreSQL:
heroku pg:psql --app your-app-name < sqlfile/carrental.sql

# For MySQL (JawsDB):
# Use phpMyAdmin or MySQL client with JawsDB credentials
# Import sqlfile/carrental.sql
```

## 🔧 Files Already Created
✅ **Procfile** - Heroku server configuration
✅ **composer.json** - PHP dependencies  
✅ **Database config** - Multi-database support

## 🎉 Expected Result
- **Live URL**: `https://your-app-name.herokuapp.com`
- **SSL**: Automatic HTTPS
- **Database**: Free PostgreSQL or MySQL
- **File uploads**: Supported (temporary storage)

## 🆘 Troubleshooting

**Build Fails:**
- Check buildpack is set to PHP
- Verify composer.json is valid
- Check Heroku build logs

**Database Connection:**
- Verify DATABASE_URL is set
- Check database addon is provisioned
- Import SQL data correctly

**App crashes:**
- Check Heroku logs: `heroku logs --tail --app your-app-name`
- Verify PHP syntax errors
- Check file permissions

## ⚡ One-Click Deploy
[![Deploy to Heroku](https://www.herokucdn.com/deploy/button.svg)](https://heroku.com/deploy?template=https://github.com/Priyanshusahay12222301/Car-Rental)

Click the button above for instant deployment! 🚀