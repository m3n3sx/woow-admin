#!/bin/bash

echo "🗑️  Clearing all caches..."

# Clear WordPress object cache
wp cache flush 2>/dev/null && echo "✅ WordPress cache cleared" || echo "⚠️  wp-cli not available"

# Clear transients
wp transient delete --all 2>/dev/null && echo "✅ Transients cleared" || echo "⚠️  wp-cli not available"

# Clear WOOW specific transients
wp transient delete woow_generated_css 2>/dev/null && echo "✅ WOOW CSS cache cleared" || echo "⚠️  wp-cli not available"

# Touch files to force browser reload
touch assets/dist/main.js
touch assets/dist/style.css

echo ""
echo "✅ Done! Please hard refresh your browser (Ctrl+Shift+R or Cmd+Shift+R)"
