#!/bin/bash

echo "🚀 Deploying Car Rental App to Vercel..."

# Check if vercel CLI is installed
if ! command -v vercel &> /dev/null; then
    echo "❌ Vercel CLI not found. Installing..."
    npm install -g vercel
fi

# Backup original config if it exists
if [ ! -f "includes/config-original.php" ] && [ -f "includes/config.php" ]; then
    echo "📋 Backing up original config..."
    cp includes/config.php includes/config-original.php
fi

# Use Vercel-optimized config
echo "🔧 Setting up Vercel configuration..."
cp includes/config-vercel.php includes/config.php

# Deploy
echo "🌐 Deploying to Vercel..."
vercel --prod

echo ""
echo "✅ Deployment complete!"
echo ""
echo "📝 Don't forget to:"
echo "   1. Set up your database (PlanetScale recommended)"
echo "   2. Import sqlfile/carrental.sql to your database"
echo "   3. Set environment variables in Vercel dashboard:"
echo "      - DB_HOST"
echo "      - DB_USER" 
echo "      - DB_PASS"
echo "      - DB_NAME"
echo ""
echo "📖 See DEPLOYMENT.md for detailed instructions"