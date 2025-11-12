<?php
/**
 * Complete diagnostic check
 */

require_once('../../../wp-load.php');

if (!current_user_can('manage_options')) {
    die('Access denied');
}

echo "<h1>🔧 WOOW! Admin Complete Diagnostic</h1>";

// 1. Check settings in database
$settings = get_option('woow_admin_settings', array());

echo "<h2>1. Database Settings</h2>";
if (empty($settings)) {
    echo "<p style='color: red;'>❌ No settings found in database!</p>";
    echo "<p><strong>Action:</strong> Run enable-globally.php or reset-menu-defaults.php</p>";
} else {
    echo "<p style='color: green;'>✅ Settings exist</p>";
    
    // Check general.enabled
    if (isset($settings['general']['enabled']) && $settings['general']['enabled']) {
        echo "<p style='color: green;'>✅ Plugin is enabled globally</p>";
    } else {
        echo "<p style='color: red;'>❌ Plugin is NOT enabled (general.enabled = false or missing)</p>";
        echo "<p><strong>Action:</strong> Run enable-globally.php</p>";
    }
    
    // Check admin_menu.enabled
    if (isset($settings['admin_menu']['enabled']) && $settings['admin_menu']['enabled']) {
        echo "<p style='color: green;'>✅ Admin menu is enabled</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ Admin menu is disabled or missing enabled flag</p>";
    }
    
    // Check if admin_menu has actual settings
    if (isset($settings['admin_menu'])) {
        $menu_count = count($settings['admin_menu']);
        if ($menu_count < 10) {
            echo "<p style='color: red;'>❌ Admin menu section is incomplete (only {$menu_count} settings)</p>";
            echo "<p><strong>Action:</strong> Run fix-missing-settings.php</p>";
        } else {
            echo "<p style='color: green;'>✅ Admin menu has {$menu_count} settings</p>";
        }
    }
}

// 2. Check CSS cache
echo "<h2>2. CSS Cache</h2>";
$css_cache = get_transient('woow_admin_css');
if ($css_cache) {
    echo "<p style='color: green;'>✅ CSS is cached (" . strlen($css_cache) . " chars)</p>";
    
    // Check if admin menu styles are in CSS
    if (strpos($css_cache, '#adminmenu') !== false) {
        echo "<p style='color: green;'>✅ Admin menu styles found in CSS</p>";
    } else {
        echo "<p style='color: red;'>❌ Admin menu styles NOT found in CSS</p>";
    }
    
    if (strpos($css_cache, '#wpadminbar') !== false) {
        echo "<p style='color: green;'>✅ Admin bar styles found in CSS</p>";
    } else {
        echo "<p style='color: red;'>❌ Admin bar styles NOT found in CSS</p>";
    }
} else {
    echo "<p style='color: orange;'>⚠️ No cached CSS (will generate on next page load)</p>";
}

// 3. Test CSS generation
echo "<h2>3. CSS Generation Test</h2>";
try {
    require_once('includes/class-woow-settings.php');
    require_once('includes/class-woow-css-generator.php');
    
    $settings_obj = new WOOW_Settings();
    $generator = new WOOW_CSS_Generator($settings_obj);
    $test_css = $generator->generate();
    
    echo "<p style='color: green;'>✅ CSS generation works (" . strlen($test_css) . " chars)</p>";
    
    if (strpos($test_css, '#adminmenu') !== false) {
        echo "<p style='color: green;'>✅ Generated CSS contains admin menu styles</p>";
    } else {
        echo "<p style='color: red;'>❌ Generated CSS missing admin menu styles</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ CSS generation failed: " . $e->getMessage() . "</p>";
}

// 4. Actions
echo "<hr><h2>Quick Actions</h2>";
echo "<p><a href='fix-missing-settings.php' style='padding: 10px 20px; background: #ef4444; color: white; text-decoration: none; border-radius: 8px; display: inline-block; margin: 5px;'>🔧 Fix Missing Settings</a></p>";
echo "<p><a href='enable-globally.php' style='padding: 10px 20px; background: #6366f1; color: white; text-decoration: none; border-radius: 8px; display: inline-block; margin: 5px;'>Enable Globally</a></p>";
echo "<p><a href='check-settings.php' style='padding: 10px 20px; background: #8b5cf6; color: white; text-decoration: none; border-radius: 8px; display: inline-block; margin: 5px;'>Check Settings</a></p>";
echo "<p><a href='check-css-duplicates.php' style='padding: 10px 20px; background: #10b981; color: white; text-decoration: none; border-radius: 8px; display: inline-block; margin: 5px;'>Check CSS Duplicates</a></p>";
