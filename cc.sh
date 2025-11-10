#!/bin/bash
# Quick cache clear - short version

WP_ROOT="$(cd "$(dirname "$0")/../../.." && pwd)"

echo "🧹 Clearing cache..."

# Clear transients
if command -v wp &> /dev/null; then
    cd "$WP_ROOT" && wp transient delete --all --allow-root 2>/dev/null
    cd "$WP_ROOT" && wp cache flush --allow-root 2>/dev/null
    cd "$WP_ROOT" && wp rewrite flush --allow-root 2>/dev/null
fi

# Clear cache files
rm -rf "$WP_ROOT/wp-content/cache/*" 2>/dev/null

# Clear PHP opcache
php -r "if (function_exists('opcache_reset')) opcache_reset();" 2>/dev/null

echo "✓ Done! Remember to refresh browser (Ctrl+Shift+R)"
