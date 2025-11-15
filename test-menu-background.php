<?php
/**
 * Test Admin Menu Background Color
 * 
 * Run this file to check if background_color is saved correctly
 */

// Load WordPress
require_once('../../../wp-load.php');

// Get current settings
$settings = get_option('woow_admin_settings', array());

echo "<h1>Admin Menu Background Color Test</h1>";
echo "<pre>";
echo "Full admin_menu settings:\n";
print_r($settings['admin_menu'] ?? 'NOT SET');
echo "\n\n";

echo "background_type: " . ($settings['admin_menu']['background_type'] ?? 'NOT SET') . "\n";
echo "background_color: " . ($settings['admin_menu']['background_color'] ?? 'NOT SET') . "\n";
echo "gradient_start: " . ($settings['admin_menu']['gradient_start'] ?? 'NOT SET') . "\n";
echo "gradient_end: " . ($settings['admin_menu']['gradient_end'] ?? 'NOT SET') . "\n";
echo "glass_base_color: " . ($settings['admin_menu']['glass_base_color'] ?? 'NOT SET') . "\n";
echo "</pre>";

// Test CSS generation
require_once('includes/class-woow-settings.php');
require_once('includes/class-woow-css-generator.php');

$settings_obj = new WOOW_Settings();
$css_generator = new WOOW_CSS_Generator($settings_obj);
$css = $css_generator->generate();

// Find admin menu CSS
$start = strpos($css, '/* Admin Menu Styling');
$end = strpos($css, '/* Dashboard', $start);
$menu_css = substr($css, $start, $end - $start);

echo "<h2>Generated CSS for Admin Menu:</h2>";
echo "<pre style='background: #f0f0f0; padding: 10px; overflow-x: auto;'>";
echo htmlspecialchars($menu_css);
echo "</pre>";
