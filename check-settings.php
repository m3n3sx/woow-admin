<?php
/**
 * Check current WOOW! Admin settings
 * 
 * URL: /wp-content/plugins/woow-admin/check-settings.php
 */

// Load WordPress
require_once('../../../wp-load.php');

if (!current_user_can('manage_options')) {
    die('Access denied');
}

// Get current settings
$settings = get_option('woow_admin_settings', array());

echo "<h1>🔍 WOOW! Admin Settings Check</h1>";

echo "<h2>General Section:</h2>";
if (isset($settings['general'])) {
    echo "<pre>";
    print_r($settings['general']);
    echo "</pre>";
    
    if (isset($settings['general']['enabled'])) {
        if ($settings['general']['enabled']) {
            echo "<p style='color: green; font-weight: bold;'>✅ Plugin is ENABLED globally</p>";
        } else {
            echo "<p style='color: red; font-weight: bold;'>❌ Plugin is DISABLED</p>";
        }
    } else {
        echo "<p style='color: orange; font-weight: bold;'>⚠️ 'enabled' key not found in general section</p>";
    }
} else {
    echo "<p style='color: red; font-weight: bold;'>❌ General section NOT FOUND</p>";
    echo "<p>This is why CSS is not being injected!</p>";
}

echo "<h2>Admin Menu Section:</h2>";
if (isset($settings['admin_menu'])) {
    echo "<p style='color: green;'>✅ Admin menu section exists</p>";
    echo "<p>Width: " . ($settings['admin_menu']['width'] ?? 'not set') . "</p>";
    echo "<p>Text color: " . ($settings['admin_menu']['text_color'] ?? 'not set') . "</p>";
    echo "<p>Border radius: " . ($settings['admin_menu']['border_radius_all'] ?? 'not set') . "</p>";
} else {
    echo "<p style='color: red;'>❌ Admin menu section NOT FOUND</p>";
}

echo "<h2>CSS Generation Test:</h2>";
$css = get_transient('woow_admin_css');
if ($css) {
    echo "<p style='color: green;'>✅ CSS is cached</p>";
    echo "<p>CSS length: " . strlen($css) . " characters</p>";
} else {
    echo "<p style='color: orange;'>⚠️ No cached CSS (will be generated on next page load)</p>";
}

echo "<hr>";
echo "<h2>Actions:</h2>";
echo "<p><a href='enable-globally.php' style='padding: 10px 20px; background: #6366f1; color: white; text-decoration: none; border-radius: 8px;'>Enable Plugin Globally</a></p>";
echo "<p><a href='reset-menu-defaults.php' style='padding: 10px 20px; background: #8b5cf6; color: white; text-decoration: none; border-radius: 8px;'>Reset Menu to New Defaults</a></p>";
echo "<p><a href='/wp-admin/' style='padding: 10px 20px; background: #10b981; color: white; text-decoration: none; border-radius: 8px;'>Go to Dashboard</a></p>";
