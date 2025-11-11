#!/bin/bash

echo "🔧 Fixing admin bar width control..."

# Build assets
echo "📦 Building JavaScript assets..."
npm run build

# Clear WordPress cache
echo "🗑️  Clearing WordPress cache..."
wp cache flush 2>/dev/null || echo "   (wp-cli not available, skip cache flush)"

# Clear transients
echo "🗑️  Clearing CSS transients..."
wp transient delete woow_generated_css 2>/dev/null || echo "   (wp-cli not available, skip transient delete)"

echo "✅ Done! Please refresh your browser and test the width control."
