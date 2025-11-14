<?php
/**
 * Test Submenu Fix
 * 
 * Quick verification that submenu CSS is generated correctly
 * 
 * Usage: php test-submenu-fix.php
 */

// Load WordPress
require_once __DIR__ . '/../../../wp-load.php';

// Load plugin classes
require_once __DIR__ . '/includes/class-woow-settings.php';
require_once __DIR__ . '/includes/class-woow-css-generator.php';

echo "=== WOOW! Admin - Submenu Fix Verification ===\n\n";

// Initialize settings
$settings = new WOOW_Settings();

// Get admin menu settings
$menu = $settings->get_section('admin_menu');

echo "Admin Menu Settings:\n";
echo "- Width: " . ($menu['width'] ?? '256') . "px\n";
echo "- Margin Left: " . ($menu['margin_left'] ?? '16') . "px\n";
echo "- Item Height: " . ($menu['item_height'] ?? '48') . "px\n";
echo "- Submenu Border Radius: " . ($menu['submenu_border_radius'] ?? '8') . "px\n";
echo "\n";

// Generate CSS
$generator = new WOOW_CSS_Generator($settings);
$css = $generator->generate();

// Extract submenu-related CSS
echo "Checking Submenu CSS Rules:\n\n";

// Check 1: Flyout submenu positioning
if (preg_match('/#adminmenu li\.wp-has-submenu:not\(\.wp-has-current-submenu\):not\(\.wp-menu-open\):hover > \.wp-submenu \{([^}]+)\}/s', $css, $matches)) {
    echo "✅ Flyout Submenu CSS Found:\n";
    $rules = $matches[1];
    
    // Check for correct positioning
    if (strpos($rules, 'top: 0 !important') !== false) {
        echo "  ✅ top: 0 !important (CORRECT)\n";
    } else {
        echo "  ❌ top: 0 !important (MISSING)\n";
    }
    
    if (strpos($rules, 'margin-top: 0 !important') !== false) {
        echo "  ✅ margin-top: 0 !important (CORRECT)\n";
    } else {
        echo "  ❌ margin-top: 0 !important (MISSING)\n";
    }
    
    if (strpos($rules, 'position: fixed !important') !== false) {
        echo "  ✅ position: fixed !important (CORRECT)\n";
    } else {
        echo "  ❌ position: fixed !important (MISSING)\n";
    }
    
    if (strpos($rules, 'transition:') !== false) {
        echo "  ✅ Transitions added (CORRECT)\n";
    } else {
        echo "  ❌ Transitions missing\n";
    }
} else {
    echo "❌ Flyout Submenu CSS NOT FOUND\n";
}

echo "\n";

// Check 2: Hover bridge
if (preg_match('/#adminmenu li\.wp-has-submenu > a::after \{([^}]+)\}/s', $css, $matches)) {
    echo "✅ Hover Bridge CSS Found:\n";
    $rules = $matches[1];
    
    if (strpos($rules, 'pointer-events: all !important') !== false) {
        echo "  ✅ pointer-events: all (CRITICAL)\n";
    } else {
        echo "  ❌ pointer-events: all (MISSING - CRITICAL!)\n";
    }
    
    if (strpos($rules, 'width: 15px !important') !== false) {
        echo "  ✅ width: 15px (CORRECT)\n";
    } else {
        echo "  ❌ width: 15px (MISSING)\n";
    }
} else {
    echo "❌ Hover Bridge CSS NOT FOUND (CRITICAL!)\n";
}

echo "\n";

// Check 3: Collapsed state submenu
if (preg_match('/\.folded #adminmenu \.wp-submenu[^{]*\{([^}]+)\}/s', $css, $matches)) {
    echo "✅ Collapsed Submenu CSS Found:\n";
    $rules = $matches[1];
    
    if (strpos($rules, 'top: 0 !important') !== false) {
        echo "  ✅ top: 0 !important (CORRECT)\n";
    } else {
        echo "  ❌ top: 0 !important (MISSING)\n";
    }
    
    if (strpos($rules, 'margin-top: 0 !important') !== false) {
        echo "  ✅ margin-top: 0 !important (CORRECT)\n";
    } else {
        echo "  ❌ margin-top: 0 !important (MISSING)\n";
    }
} else {
    echo "❌ Collapsed Submenu CSS NOT FOUND\n";
}

echo "\n";

// Check 4: Collapsed state hover bridge
if (preg_match('/\.folded #adminmenu li\.wp-has-submenu > a::after \{([^}]+)\}/s', $css, $matches)) {
    echo "✅ Collapsed Hover Bridge CSS Found:\n";
    $rules = $matches[1];
    
    if (strpos($rules, 'pointer-events: all !important') !== false) {
        echo "  ✅ pointer-events: all (CRITICAL)\n";
    } else {
        echo "  ❌ pointer-events: all (MISSING - CRITICAL!)\n";
    }
} else {
    echo "❌ Collapsed Hover Bridge CSS NOT FOUND (CRITICAL!)\n";
}

echo "\n";

// Check 5: JavaScript handler
$main_js_path = __DIR__ . '/assets/dist/main.js';
if (file_exists($main_js_path)) {
    $main_js = file_get_contents($main_js_path);
    
    if (strpos($main_js, 'setupSubmenuHoverHandler') !== false) {
        echo "✅ JavaScript Submenu Handler Found\n";
        
        if (strpos($main_js, 'HIDE_DELAY') !== false) {
            echo "  ✅ HIDE_DELAY constant defined\n";
        }
        
        if (strpos($main_js, 'mouseenter') !== false && strpos($main_js, 'mouseleave') !== false) {
            echo "  ✅ Mouse event listeners added\n";
        }
        
        if (strpos($main_js, 'setTimeout') !== false) {
            echo "  ✅ Delay timeout implemented\n";
        }
    } else {
        echo "❌ JavaScript Submenu Handler NOT FOUND\n";
    }
} else {
    echo "❌ Built JavaScript file not found (run npm run build)\n";
}

echo "\n";

// Summary
echo "=== SUMMARY ===\n";
echo "If all checks show ✅, the submenu fix is correctly implemented.\n";
echo "If any checks show ❌, review the corresponding section in the code.\n\n";

echo "Next Steps:\n";
echo "1. Clear cache: ./cc.sh\n";
echo "2. Hard refresh browser: Ctrl + Shift + R\n";
echo "3. Test submenu hover behavior in WordPress admin\n";
echo "4. Test both expanded and collapsed menu states\n";
