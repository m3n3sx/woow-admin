<?php
/**
 * Fix AdminMenu CSS Override Issue
 * 
 * Problem: CSS generator always generates full CSS for adminmenu,
 * even when all settings are defaults. This overrides WordPress defaults.
 * 
 * Solution: Only generate CSS for values that differ from defaults.
 * 
 * This script shows the exact changes needed in class-woow-css-generator.php
 */

echo "=== AdminMenu CSS Override Fix ===\n\n";

echo "PROBLEM IDENTIFIED:\n";
echo "-------------------\n";
echo "File: includes/class-woow-css-generator.php\n";
echo "Method: add_admin_menu_styles()\n";
echo "Line: ~555-800\n\n";

echo "Current behavior:\n";
echo "  ✗ ALWAYS generates CSS for ALL adminmenu properties\n";
echo "  ✗ Uses default values from defaults.php\n";
echo "  ✗ Overrides WordPress default styles\n";
echo "  ✗ Even on fresh install with no user changes\n\n";

echo "Expected behavior (commit 4de3336):\n";
echo "  ✓ Generate CSS ONLY for changed values\n";
echo "  ✓ If all values = defaults → NO CSS output\n";
echo "  ✓ WordPress default styles win\n";
echo "  ✓ Fresh install looks like vanilla WordPress\n\n";

echo "ROOT CAUSE:\n";
echo "-----------\n";
echo "The method uses ?? (null coalescing) operator with hardcoded defaults:\n\n";
echo "  \$width = \$menu['width'] ?? '256';  // ← ALWAYS has value!\n";
echo "  \$background_color = \$menu['background_color'] ?? '#ffffff';\n\n";
echo "Then it ALWAYS generates CSS:\n\n";
echo "  \$this->css .= \"width: {\$width}px !important;\\n\";  // ← ALWAYS runs!\n\n";

echo "SOLUTION:\n";
echo "---------\n";
echo "Add conditional generation - only output CSS when value differs from default.\n\n";

echo "IMPLEMENTATION OPTIONS:\n";
echo "=======================\n\n";

echo "OPTION 1: Minimal Override (Recommended)\n";
echo "-----------------------------------------\n";
echo "Only generate CSS for properties that are explicitly set and differ from defaults.\n\n";

echo "Changes needed in add_admin_menu_styles():\n\n";

echo "// BEFORE (Current - BAD):\n";
echo "private function add_admin_menu_styles(): void {\n";
echo "    \$menu = \$this->settings->get_section( 'admin_menu' );\n";
echo "    \n";
echo "    \$width = \$menu['width'] ?? '256';\n";
echo "    \$background_color = \$menu['background_color'] ?? '#ffffff';\n";
echo "    // ... more defaults ...\n";
echo "    \n";
echo "    // ALWAYS generates CSS:\n";
echo "    \$this->css .= \"#adminmenuwrap {\\n\";\n";
echo "    \$this->css .= \"    width: {\$width}px !important;\\n\";\n";
echo "    \$this->css .= \"    background: {\$background_color} !important;\\n\";\n";
echo "    // ...\n";
echo "}\n\n";

echo "// AFTER (Fixed - GOOD):\n";
echo "private function add_admin_menu_styles(): void {\n";
echo "    \$menu = \$this->settings->get_section( 'admin_menu' );\n";
echo "    \n";
echo "    // Get defaults for comparison\n";
echo "    \$defaults = woow_get_default_settings()['admin_menu'];\n";
echo "    \n";
echo "    // Check if user has changed ANY settings\n";
echo "    \$has_custom_settings = false;\n";
echo "    foreach (\$menu as \$key => \$value) {\n";
echo "        if (isset(\$defaults[\$key]) && \$value !== \$defaults[\$key]) {\n";
echo "            \$has_custom_settings = true;\n";
echo "            break;\n";
echo "        }\n";
echo "    }\n";
echo "    \n";
echo "    // If no custom settings, return early (WordPress defaults win)\n";
echo "    if (!has_custom_settings) {\n";
echo "        return;\n";
echo "    }\n";
echo "    \n";
echo "    // Only generate CSS for changed values\n";
echo "    \$this->css .= \"/* Admin Menu Styling - Customizable */\\n\";\n";
echo "    \n";
echo "    // Only add CSS if value differs from default\n";
echo "    if (isset(\$menu['width']) && \$menu['width'] !== \$defaults['width']) {\n";
echo "        \$this->css .= \"#adminmenuwrap { width: {\$menu['width']}px !important; }\\n\";\n";
echo "    }\n";
echo "    \n";
echo "    if (isset(\$menu['background_color']) && \$menu['background_color'] !== \$defaults['background_color']) {\n";
echo "        \$this->css .= \"#adminmenuwrap { background: {\$menu['background_color']} !important; }\\n\";\n";
echo "    }\n";
echo "    // ... etc for other properties ...\n";
echo "}\n\n";

echo "OPTION 2: Selective Override (Simpler)\n";
echo "---------------------------------------\n";
echo "Keep current structure but wrap CSS generation in conditionals.\n\n";

echo "Changes needed:\n\n";
echo "// Add helper method to check if value is custom\n";
echo "private function is_custom_value(\$section, \$key, \$value): bool {\n";
echo "    \$defaults = woow_get_default_settings();\n";
echo "    if (!isset(\$defaults[\$section][\$key])) {\n";
echo "        return true; // New field, consider custom\n";
echo "    }\n";
echo "    return \$value !== \$defaults[\$section][\$key];\n";
echo "}\n\n";

echo "// Then in add_admin_menu_styles():\n";
echo "private function add_admin_menu_styles(): void {\n";
echo "    \$menu = \$this->settings->get_section( 'admin_menu' );\n";
echo "    \$defaults = woow_get_default_settings()['admin_menu'];\n";
echo "    \n";
echo "    // Start CSS only if there are custom values\n";
echo "    \$css_parts = [];\n";
echo "    \n";
echo "    // Width\n";
echo "    if (\$this->is_custom_value('admin_menu', 'width', \$menu['width'] ?? \$defaults['width'])) {\n";
echo "        \$width = \$menu['width'] ?? \$defaults['width'];\n";
echo "        \$css_parts[] = \"width: {\$width}px !important;\";\n";
echo "    }\n";
echo "    \n";
echo "    // Background\n";
echo "    if (\$this->is_custom_value('admin_menu', 'background_color', \$menu['background_color'] ?? \$defaults['background_color'])) {\n";
echo "        \$bg = \$menu['background_color'] ?? \$defaults['background_color'];\n";
echo "        \$css_parts[] = \"background: {\$bg} !important;\";\n";
echo "    }\n";
echo "    \n";
echo "    // Only output CSS if there are custom properties\n";
echo "    if (!empty(\$css_parts)) {\n";
echo "        \$this->css .= \"#adminmenuwrap {\\n\";\n";
echo "        foreach (\$css_parts as \$css_line) {\n";
echo "            \$this->css .= \"    {\$css_line}\\n\";\n";
echo "        }\n";
echo "        \$this->css .= \"}\\n\\n\";\n";
echo "    }\n";
echo "}\n\n";

echo "OPTION 3: Quick Fix (Fastest)\n";
echo "------------------------------\n";
echo "Add early return if enabled = false or all defaults.\n\n";

echo "// Add at start of add_admin_menu_styles():\n";
echo "private function add_admin_menu_styles(): void {\n";
echo "    \$menu = \$this->settings->get_section( 'admin_menu' );\n";
echo "    \n";
echo "    // Quick check: if enabled is false, skip entirely\n";
echo "    if (isset(\$menu['enabled']) && !\$menu['enabled']) {\n";
echo "        return;\n";
echo "    }\n";
echo "    \n";
echo "    // Quick check: if section is empty or all defaults, skip\n";
echo "    \$defaults = woow_get_default_settings()['admin_menu'];\n";
echo "    if (empty(\$menu) || \$menu === \$defaults) {\n";
echo "        return; // No custom settings, WordPress defaults win\n";
echo "    }\n";
echo "    \n";
echo "    // ... rest of current code ...\n";
echo "}\n\n";

echo "RECOMMENDED APPROACH:\n";
echo "=====================\n";
echo "Use OPTION 3 (Quick Fix) first to test, then implement OPTION 1 for proper solution.\n\n";

echo "TESTING STEPS:\n";
echo "==============\n";
echo "1. Apply fix to includes/class-woow-css-generator.php\n";
echo "2. Run: npm run build\n";
echo "3. Clear cache: ./cc.sh\n";
echo "4. Test fresh install:\n";
echo "   - Deactivate plugin\n";
echo "   - Delete: DELETE FROM wp_options WHERE option_name = 'woow_admin_settings'\n";
echo "   - Activate plugin\n";
echo "   - Check adminmenu appearance\n";
echo "   - Expected: Looks like vanilla WordPress ✓\n";
echo "5. Test with changes:\n";
echo "   - Change one adminmenu option (e.g., background color)\n";
echo "   - Save\n";
echo "   - Check adminmenu appearance\n";
echo "   - Expected: Only changed property is styled ✓\n";
echo "6. Compare with commit 4de3336:\n";
echo "   - Checkout 4de3336\n";
echo "   - Fresh install\n";
echo "   - Take screenshot\n";
echo "   - Checkout current\n";
echo "   - Fresh install\n";
echo "   - Take screenshot\n";
echo "   - Expected: Identical ✓\n\n";

echo "FILES TO MODIFY:\n";
echo "================\n";
echo "1. includes/class-woow-css-generator.php (add_admin_menu_styles method)\n";
echo "2. includes/defaults.php (verify defaults match 4de3336 for critical properties)\n\n";

echo "CRITICAL DEFAULTS TO CHECK:\n";
echo "===========================\n";
echo "These should be EMPTY or match WordPress defaults:\n";
echo "  - background_color: Should NOT override WP default\n";
echo "  - text_color: Should NOT override WP default\n";
echo "  - width: Should NOT override WP default\n";
echo "  - border_radius: Should NOT override WP default\n\n";

echo "If defaults.php has custom values (e.g., '#ffffff' for background),\n";
echo "the generator will ALWAYS use them, even on fresh install!\n\n";

echo "NEXT STEPS:\n";
echo "===========\n";
echo "1. Review current defaults.php admin_menu section\n";
echo "2. Compare with commit 4de3336 defaults\n";
echo "3. Apply OPTION 3 (Quick Fix) to CSS generator\n";
echo "4. Test fresh install\n";
echo "5. If works, implement OPTION 1 (Minimal Override) for proper solution\n";
echo "6. Test all scenarios\n";
echo "7. Commit with message: 'Fix: AdminMenu CSS only generates for custom values'\n\n";

echo "Done! Run this script to see the fix plan.\n";
