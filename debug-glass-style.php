<?php
/**
 * Debug Glass Style Settings
 * 
 * Uruchom ten plik w przeglądarce: /wp-content/plugins/woow-admin/debug-glass-style.php
 */

// Load WordPress
require_once('../../../wp-load.php');

// Check if user is admin
if (!current_user_can('manage_options')) {
    die('Access denied');
}

// Get settings
$settings = get_option('woow_admin_settings', array());

echo "<h1>WOOW Admin - Glass Style Debug</h1>";
echo "<pre>";

echo "\n=== GENERAL SETTINGS ===\n";
print_r($settings['general'] ?? 'NOT SET');

echo "\n\n=== ADMIN BAR SETTINGS ===\n";
echo "background_type: " . ($settings['admin_bar']['background_type'] ?? 'NOT SET') . "\n";
echo "background_color: " . ($settings['admin_bar']['background_color'] ?? 'NOT SET') . "\n";
echo "opacity: " . ($settings['admin_bar']['opacity'] ?? 'NOT SET') . "\n";
echo "blur_strength: " . ($settings['admin_bar']['blur_strength'] ?? 'NOT SET') . "\n";
echo "glassmorphism: " . ($settings['admin_bar']['glassmorphism'] ?? 'NOT SET') . "\n";

echo "\n\n=== ADMIN MENU SETTINGS ===\n";
echo "background_type: " . ($settings['admin_menu']['background_type'] ?? 'NOT SET') . "\n";
echo "glass_base_color: " . ($settings['admin_menu']['glass_base_color'] ?? 'NOT SET') . "\n";
echo "opacity: " . ($settings['admin_menu']['opacity'] ?? 'NOT SET') . "\n";
echo "blur_strength: " . ($settings['admin_menu']['blur_strength'] ?? 'NOT SET') . "\n";
echo "glassmorphism: " . ($settings['admin_menu']['glassmorphism'] ?? 'NOT SET') . "\n";

echo "\n\n=== CONTENT STYLING SETTINGS ===\n";
echo "wpbody_content_glassmorphism: " . ($settings['content_styling']['wpbody_content_glassmorphism'] ?? 'NOT SET') . "\n";
echo "wpbody_content_opacity: " . ($settings['content_styling']['wpbody_content_opacity'] ?? 'NOT SET') . "\n";
echo "wpbody_content_blur_strength: " . ($settings['content_styling']['wpbody_content_blur_strength'] ?? 'NOT SET') . "\n";

echo "\n\n=== FULL SETTINGS DUMP ===\n";
print_r($settings);

echo "</pre>";

echo "<hr>";
echo "<h2>Actions</h2>";
echo "<a href='?reset=1' style='padding: 10px 20px; background: #dc3545; color: white; text-decoration: none; border-radius: 5px;'>Reset All Settings</a>";

if (isset($_GET['reset'])) {
    delete_option('woow_admin_settings');
    echo "<p style='color: green; font-weight: bold;'>Settings reset! Refresh page.</p>";
}
