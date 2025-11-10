#!/bin/bash

# WOOW! Admin - Hard Cache Clear Script
# Clears all WordPress and plugin caches

echo "🧹 WOOW! Admin - Hard Cache Clear"
echo "=================================="
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Get the WordPress root directory (3 levels up from plugin)
WP_ROOT="$(cd "$(dirname "$0")/../../.." && pwd)"
echo "📁 WordPress root: $WP_ROOT"
echo ""

# Function to check if wp-cli is available
check_wp_cli() {
    if command -v wp &> /dev/null; then
        echo -e "${GREEN}✓${NC} WP-CLI found"
        return 0
    else
        echo -e "${YELLOW}⚠${NC} WP-CLI not found (some operations will be skipped)"
        return 1
    fi
}

# Check WP-CLI
HAS_WP_CLI=false
if check_wp_cli; then
    HAS_WP_CLI=true
fi
echo ""

# 1. Clear WordPress transients
echo "1️⃣  Clearing WordPress transients..."
if [ "$HAS_WP_CLI" = true ]; then
    cd "$WP_ROOT" && wp transient delete --all --allow-root 2>/dev/null
    if [ $? -eq 0 ]; then
        echo -e "   ${GREEN}✓${NC} Transients cleared"
    else
        echo -e "   ${YELLOW}⚠${NC} Could not clear transients via WP-CLI"
    fi
else
    echo -e "   ${YELLOW}⚠${NC} Skipped (WP-CLI not available)"
fi

# 2. Clear WOOW! Admin specific cache
echo ""
echo "2️⃣  Clearing WOOW! Admin cache..."
if [ "$HAS_WP_CLI" = true ]; then
    cd "$WP_ROOT" && wp transient delete woow_generated_css --allow-root 2>/dev/null
    cd "$WP_ROOT" && wp transient delete woow_settings_cache --allow-root 2>/dev/null
    echo -e "   ${GREEN}✓${NC} WOOW! transients cleared"
else
    echo -e "   ${YELLOW}⚠${NC} Skipped (WP-CLI not available)"
fi

# 3. Clear WordPress object cache
echo ""
echo "3️⃣  Clearing WordPress object cache..."
if [ "$HAS_WP_CLI" = true ]; then
    cd "$WP_ROOT" && wp cache flush --allow-root 2>/dev/null
    if [ $? -eq 0 ]; then
        echo -e "   ${GREEN}✓${NC} Object cache flushed"
    else
        echo -e "   ${YELLOW}⚠${NC} No object cache to flush"
    fi
else
    echo -e "   ${YELLOW}⚠${NC} Skipped (WP-CLI not available)"
fi

# 4. Clear rewrite rules
echo ""
echo "4️⃣  Flushing rewrite rules..."
if [ "$HAS_WP_CLI" = true ]; then
    cd "$WP_ROOT" && wp rewrite flush --allow-root 2>/dev/null
    echo -e "   ${GREEN}✓${NC} Rewrite rules flushed"
else
    echo -e "   ${YELLOW}⚠${NC} Skipped (WP-CLI not available)"
fi

# 5. Clear opcache (if available)
echo ""
echo "5️⃣  Clearing PHP opcache..."
if [ -f "$WP_ROOT/wp-admin/admin-ajax.php" ]; then
    # Try to clear via PHP
    php -r "if (function_exists('opcache_reset')) { opcache_reset(); echo 'OPcache cleared'; } else { echo 'OPcache not available'; }" 2>/dev/null
    echo ""
else
    echo -e "   ${YELLOW}⚠${NC} Could not clear opcache"
fi

# 6. Delete cache files
echo ""
echo "6️⃣  Deleting cache files..."

# WordPress cache directory
if [ -d "$WP_ROOT/wp-content/cache" ]; then
    rm -rf "$WP_ROOT/wp-content/cache/*" 2>/dev/null
    echo -e "   ${GREEN}✓${NC} wp-content/cache cleared"
else
    echo -e "   ${YELLOW}⚠${NC} No wp-content/cache directory"
fi

# Advanced cache
if [ -f "$WP_ROOT/wp-content/advanced-cache.php" ]; then
    echo -e "   ${YELLOW}⚠${NC} Advanced cache detected (not removed)"
fi

# Object cache
if [ -f "$WP_ROOT/wp-content/object-cache.php" ]; then
    echo -e "   ${YELLOW}⚠${NC} Object cache detected (not removed)"
fi

# 7. Clear browser cache instructions
echo ""
echo "7️⃣  Browser cache:"
echo -e "   ${YELLOW}⚠${NC} Remember to clear browser cache:"
echo "   • Chrome/Edge: Ctrl+Shift+R (Cmd+Shift+R on Mac)"
echo "   • Firefox: Ctrl+Shift+Delete"
echo "   • Or use Incognito/Private mode"

# 8. Summary
echo ""
echo "=================================="
echo -e "${GREEN}✓${NC} Cache clearing complete!"
echo ""
echo "📝 What was cleared:"
echo "   • WordPress transients"
echo "   • WOOW! Admin cache"
echo "   • Object cache (if available)"
echo "   • Rewrite rules"
echo "   • PHP opcache (if available)"
echo "   • Cache files"
echo ""
echo "🔄 Next steps:"
echo "   1. Refresh your browser (Ctrl+Shift+R)"
echo "   2. Check if changes are visible"
echo "   3. If still not working, try Incognito mode"
echo ""
