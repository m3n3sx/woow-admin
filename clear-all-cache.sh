#!/bin/bash

echo "🧹 Clearing all caches..."

# Clear WordPress transients
wp transient delete --all 2>/dev/null

# Clear WOOW specific transients
wp db query "DELETE FROM wp_options WHERE option_name LIKE '_transient_woow_%' OR option_name LIKE '_transient_timeout_woow_%'" 2>/dev/null

# Flush object cache
wp cache flush 2>/dev/null

# Clear generated CSS
wp option delete woow_generated_css 2>/dev/null
wp transient delete woow_css_cache 2>/dev/null

echo "✅ Cache cleared!"
echo "Now refresh browser with Ctrl+Shift+R"
