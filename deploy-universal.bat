@echo off
echo 🌐 Setting up Car Rental App for Multiple Hosting Platforms...
echo.

REM Update to universal configuration
echo 🔧 Activating universal database configuration...
copy "includes\config-universal.php" "includes\config.php"

REM Commit changes
echo 📋 Committing universal configuration...
git add .
git commit -m "Add universal hosting platform support (Heroku/Render/InfinityFree/000webhost)"
git push

echo.
echo ✅ Repository updated for multiple hosting platforms!
echo.
echo 🚀 CHOOSE YOUR HOSTING PLATFORM:
echo.
echo 1. 🟢 HEROKU - Deploy to https://heroku.com/deploy?template=https://github.com/Priyanshusahay12222301/Car-Rental
echo 2. 🎨 RENDER - Go to https://render.com and import your GitHub repo
echo 3. 🌐 INFINITYFREE - Sign up at https://infinityfree.net (traditional hosting)
echo 4. 🔵 000WEBHOST - Sign up at https://000webhost.com (cPanel hosting)
echo 5. 🟡 AWARDSPACE - Sign up at https://awardspace.com (professional hosting)
echo.
echo 📖 Check the platform-specific guides:
echo    - HEROKU-DEPLOY.md
echo    - RENDER-DEPLOY.md  
echo    - INFINITYFREE-DEPLOY.md
echo.

pause