@echo off
echo 🚂 Deploying Car Rental App to Railway...

REM Update configuration for Railway
echo 🔧 Setting up Railway configuration...
copy "includes\config-railway.php" "includes\config.php"

REM Commit changes
echo 📋 Committing Railway configuration...
git add .
git commit -m "Configure for Railway deployment"
git push

echo.
echo ✅ Repository updated for Railway!
echo.
echo 🚂 Next steps:
echo    1. Go to https://railway.app
echo    2. Sign up with GitHub
echo    3. Click "New Project" - "Deploy from GitHub repo"
echo    4. Select: Priyanshusahay12222301/Car-Rental
echo    5. Add MySQL database service
echo    6. Import sqlfile/carrental.sql
echo.
echo 📖 See RAILWAY-DEPLOYMENT.md for detailed instructions

pause