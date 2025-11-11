#!/bin/bash

echo "🔄 Full rebuild process..."

# Step 1: Sync validator files (case-sensitive issue)
echo "📋 Syncing validator files..."
cp assets/src/js/utils/validator.js assets/src/js/utils/Validator.js

# Step 2: Build assets
echo "📦 Building assets..."
npm run build

# Step 3: Touch files to bust cache
echo "🔨 Busting cache..."
touch assets/dist/main.js
touch assets/dist/style.css
date +%s > .cache-bust

# Step 4: Clear WordPress caches
echo "🗑️  Clearing WordPress caches..."
wp cache flush 2>/dev/null && echo "✅ Cache cleared" || echo "⚠️  wp-cli not available"
wp transient delete woow_generated_css 2>/dev/null && echo "✅ CSS cache cleared" || true

echo ""
echo "✅ Rebuild complete!"
echo "📌 Hard refresh your browser: Ctrl+Shift+R (or Cmd+Shift+R on Mac)"
echo "📌 Or clear browser cache completely"
