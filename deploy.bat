@echo off
echo 🚀 Deploying Car Rental App to Vercel...

REM Check if vercel CLI is installed
vercel --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ Vercel CLI not found. Installing...
    npm install -g vercel
)

REM Backup original config if it exists
if not exist "includes\config-original.php" (
    if exist "includes\config.php" (
        echo 📋 Backing up original config...
        copy "includes\config.php" "includes\config-original.php"
    )
)

REM Use Vercel-optimized config
echo 🔧 Setting up Vercel configuration...
copy "includes\config-vercel.php" "includes\config.php"

REM Deploy
echo 🌐 Deploying to Vercel...
vercel --prod

echo.
echo ✅ Deployment complete!
echo.
echo 📝 Don't forget to:
echo    1. Set up your database (PlanetScale recommended)
echo    2. Import sqlfile/carrental.sql to your database
echo    3. Set environment variables in Vercel dashboard:
echo       - DB_HOST
echo       - DB_USER
echo       - DB_PASS
echo       - DB_NAME
echo.
echo 📖 See DEPLOYMENT.md for detailed instructions

pause