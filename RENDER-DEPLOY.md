# 🎨 Render Deployment - Car Rental App

## ✅ Why Render?
- **Modern platform** with excellent PHP support
- **Free tier**: 750 hours/month (enough for testing)
- **Auto-scaling** and instant deploys
- **Free SSL** and custom domains

## 🚀 Deploy to Render (8 minutes)

### Step 1: Create Render Account
1. **Go to**: [render.com](https://render.com)
2. **Sign up** with GitHub account
3. **Authorize** Render to access your repositories

### Step 2: Create Web Service
1. **Dashboard**: Click "New +"
2. **Select**: "Web Service"
3. **Connect**: `Priyanshusahay12222301/Car-Rental`
4. **Click**: "Connect"

### Step 3: Configure Service
```yaml
Name: car-rental-app
Environment: Web Service
Language: PHP
Branch: master (or main)
Build Command: composer install
Start Command: vendor/bin/heroku-php-apache2
```

### Step 4: Add Database
1. **Dashboard**: Click "New +" → "PostgreSQL"
2. **Name**: `car-rental-db`
3. **Plan**: Free
4. **Click**: "Create Database"

### Step 5: Environment Variables
**In your Web Service → Environment tab:**
```env
DATABASE_URL = [copy from PostgreSQL service]
DB_HOST = [from database internal URL]
DB_USER = [from database credentials]
DB_PASS = [from database credentials]  
DB_NAME = [database name]
```

### Step 6: Deploy & Import Data
1. **Deploy**: Automatic after configuration
2. **Import SQL**: Use database web console or psql
3. **Test**: Visit your Render URL

## 🔧 Render Configuration

### render.yaml (Optional - for advanced config)
```yaml
services:
  - type: web
    name: car-rental-app
    env: php
    buildCommand: composer install
    startCommand: vendor/bin/heroku-php-apache2
    envVars:
      - key: DATABASE_URL
        fromDatabase:
          name: car-rental-db
          property: connectionString

databases:
  - name: car-rental-db
    databaseName: carrental
    user: caruser
```

## 🎯 Render Features
- **Auto-deploys** from GitHub
- **Preview deployments** for pull requests  
- **Custom domains** (free SSL)
- **Environment branches** (staging/production)
- **Real-time logs**

## 📊 Free Tier Limits
- **750 hours/month** runtime
- **100GB bandwidth/month**  
- **PostgreSQL**: 1GB storage
- **Sleep after 15min** inactivity (wakes on request)

## 🎉 Expected Result
- **Live URL**: `https://car-rental-app.onrender.com`
- **Auto SSL**: HTTPS enabled
- **Database**: PostgreSQL included
- **Monitoring**: Built-in metrics

## 💡 Pro Tips
1. **Use PostgreSQL** instead of MySQL (better Render support)
2. **Enable auto-deploy** for continuous deployment
3. **Monitor usage** in dashboard to stay within limits
4. **Custom domains** available in service settings

**Deploy now**: [render.com/new](https://render.com/new) 🎨