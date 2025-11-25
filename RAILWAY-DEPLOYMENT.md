# 🚂 Railway Deployment Guide - Car Rental App

Railway is perfect for PHP applications! It provides both PHP hosting and MySQL databases in one platform.

## 🎯 Quick Deploy Steps

### Step 1: Create Railway Account
1. Go to [railway.app](https://railway.app)
2. Sign up with your GitHub account
3. Verify your account

### Step 2: Deploy from GitHub
1. **Click "New Project"**
2. **Select "Deploy from GitHub repo"**
3. **Choose your repository**: `Priyanshusahay12222301/Car-Rental`
4. **Railway will auto-detect it as a PHP project**

### Step 3: Add MySQL Database
1. **In your Railway project dashboard**
2. **Click "New" → "Database" → "Add MySQL"**
3. **Railway will provision a MySQL database**
4. **Copy the connection details from the "Connect" tab**

### Step 4: Set Environment Variables
1. **Go to your PHP service (not database)**
2. **Click "Variables" tab**
3. **Add these environment variables:**

```env
DB_HOST=mysql.railway.internal
DB_USER=[from MySQL service variables]
DB_PASS=[from MySQL service variables]
DB_NAME=railway
MYSQL_URL=[copy from MySQL service]
```

### Step 5: Import Database
1. **Connect to your Railway MySQL database**
2. **Use Railway's built-in database client or phpMyAdmin**
3. **Import your SQL file**: `sqlfile/carrental.sql`

## 🔧 Alternative: One-Click Deploy

### Option A: Deploy Button (Fastest)
Click this button to deploy instantly:

[![Deploy on Railway](https://railway.app/button.svg)](https://railway.app/template/php-mysql)

### Option B: Railway CLI
```bash
# Install Railway CLI
npm install -g @railway/cli

# Login
railway login

# Deploy from current directory
cd "c:\Users\priya\Downloads\Car-Rental-main (1)\Car-Rental-main"
railway deploy
```

## 📋 Railway Configuration Files Created

✅ **Procfile** - Tells Railway how to run your PHP app  
✅ **composer.json** - PHP dependency management  
✅ **Railway-optimized config** - Environment variable support  

## 🗄️ Database Setup Details

Railway will provide you with:
- **Host**: Usually `mysql.railway.internal` (internal) or external host
- **Port**: 3306 (default)
- **Database**: `railway` (default name)
- **Username & Password**: Generated automatically

## ⚡ Advantages of Railway vs Vercel

| Feature | Railway | Vercel |
|---------|---------|--------|
| **PHP Support** | ✅ Native | ❌ Limited |
| **MySQL Database** | ✅ Included | ❌ External only |
| **File Uploads** | ✅ Persistent | ❌ Ephemeral |
| **Sessions** | ✅ File-based | ❌ Stateless only |
| **Pricing** | ✅ Free tier | ✅ Free tier |

## 🚀 Expected Deployment Time

- **Account Setup**: 2 minutes
- **Project Deploy**: 3-5 minutes  
- **Database Setup**: 2 minutes
- **Total**: ~10 minutes to live app

## 📱 Post-Deployment Checklist

After deployment:
- [ ] Homepage loads successfully
- [ ] Database connection works
- [ ] User registration/login functional
- [ ] Admin panel accessible (`/admin`)
- [ ] Car listings display correctly
- [ ] Image uploads work
- [ ] Contact forms submit properly

## 🔍 Railway Dashboard Features

- **Deployments**: See build logs and deployment history
- **Metrics**: Monitor CPU, memory, and request metrics  
- **Logs**: Real-time application and database logs
- **Variables**: Manage environment variables
- **Domains**: Custom domain setup (optional)

## 🆘 Troubleshooting

### Common Issues:

**Build Fails:**
- Check if `composer.json` is present
- Verify PHP version compatibility  

**Database Connection Error:**
- Verify environment variables are set correctly
- Check if MySQL service is running
- Ensure database is imported

**Images Not Loading:**
- Railway supports persistent storage
- Check file upload paths in your code
- Verify image directory permissions

### Getting Help:
- **Railway Docs**: [docs.railway.app](https://docs.railway.app)
- **Community**: Railway Discord server
- **Logs**: Check deployment logs in dashboard

## 🎉 Success! 

Once deployed, your Car Rental app will be available at:
`https://your-project-name.railway.app`

Railway is much better suited for PHP applications than Vercel! 🚂✨