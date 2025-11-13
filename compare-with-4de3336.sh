#!/bin/bash

# Compare AdminMenu CSS between commit 4de3336 and current

echo "=== AdminMenu CSS Comparison Tool ==="
echo ""
echo "This script helps you compare the CSS output between"
echo "commit 4de3336 (working) and current (fixed)."
echo ""

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if we're in the right directory
if [ ! -f "includes/class-woow-css-generator.php" ]; then
    echo -e "${RED}Error: Must run from woow-admin directory${NC}"
    exit 1
fi

echo "Step 1: Saving current state..."
CURRENT_BRANCH=$(git branch --show-current)
echo "Current branch: $CURRENT_BRANCH"

echo ""
echo "Step 2: Extracting CSS generator from commit 4de3336..."
git show 4de3336:includes/class-woow-css-generator.php > /tmp/css-gen-4de3336.php
git show 4de3336:includes/defaults.php > /tmp/defaults-4de3336.php

echo ""
echo "Step 3: Comparing CSS generators..."
echo ""
echo -e "${YELLOW}=== Differences in CSS Generator ===${NC}"
diff -u /tmp/css-gen-4de3336.php includes/class-woow-css-generator.php | grep -A 5 -B 5 "add_admin_menu_styles" | head -50

echo ""
echo -e "${YELLOW}=== Differences in Defaults ===${NC}"
diff -u /tmp/defaults-4de3336.php includes/defaults.php | grep -A 20 "admin_menu"

echo ""
echo "Step 4: Testing CSS output..."
echo ""
echo "To fully test, you need to:"
echo ""
echo "1. Delete current settings:"
echo "   ${YELLOW}DELETE FROM wp_options WHERE option_name = 'woow_admin_settings';${NC}"
echo ""
echo "2. Refresh admin panel and check adminmenu appearance"
echo ""
echo "3. Expected result:"
echo "   ${GREEN}✓ AdminMenu looks like vanilla WordPress${NC}"
echo "   ${GREEN}✓ No custom styling applied${NC}"
echo "   ${GREEN}✓ Matches commit 4de3336 appearance${NC}"
echo ""
echo "4. Test custom changes:"
echo "   - Change one adminmenu option"
echo "   - Save"
echo "   - Expected: Only that option is styled"
echo ""

echo "Step 5: Visual comparison checklist..."
echo ""
echo "Compare these elements between 4de3336 and current:"
echo "  [ ] Menu background color"
echo "  [ ] Menu text color"
echo "  [ ] Menu width"
echo "  [ ] Menu border radius"
echo "  [ ] Menu shadow"
echo "  [ ] Menu item padding"
echo "  [ ] Menu item hover effect"
echo "  [ ] Active menu item style"
echo "  [ ] Submenu appearance"
echo ""

echo "Step 6: Run diagnostic tools..."
echo ""
echo "Open in browser:"
echo "  ${YELLOW}http://your-site.local/wp-content/plugins/woow-admin/diagnose-adminmenu.php${NC}"
echo "  ${YELLOW}http://your-site.local/wp-content/plugins/woow-admin/test-adminmenu-fix.php${NC}"
echo ""

echo "Done! Review the output above."
echo ""
echo "If you want to temporarily checkout 4de3336 for comparison:"
echo "  ${YELLOW}git checkout 4de3336${NC}"
echo "  ${YELLOW}npm run build${NC}"
echo "  (test in browser)"
echo "  ${YELLOW}git checkout $CURRENT_BRANCH${NC}"
echo "  ${YELLOW}npm run build${NC}"
