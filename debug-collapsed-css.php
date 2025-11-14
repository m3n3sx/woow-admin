<?php
/**
 * Debug collapsed submenu CSS
 */

require_once __DIR__ . '/../../wp-load.php';

if (!is_user_logged_in() || !current_user_can('manage_options')) {
    die('Access denied');
}

// Get settings
$settings = get_option('woow_admin_settings', array());

// Create CSS generator
require_once __DIR__ . '/includes/class-woow-settings.php';
require_once __DIR__ . '/includes/class-woow-css-generator.php';

$settings_obj = new WOOW_Settings();
$css_generator = new WOOW_CSS_Generator($settings_obj);

// Generate CSS
$css = $css_generator->generate();

// Extract only collapsed submenu CSS
preg_match_all('/\.folded.*?\.wp-submenu.*?\{[^}]+\}/s', $css, $matches);

echo "<h1>Collapsed Submenu CSS</h1>";
echo "<pre style='background: #f5f5f5; padding: 20px; overflow: auto;'>";
foreach ($matches[0] as $rule) {
    echo htmlspecialchars($rule) . "\n\n";
}
echo "</pre>";

echo "<hr>";
echo "<h1>Flyout Submenu CSS (for comparison)</h1>";
preg_match_all('/#adminmenu li\.wp-has-submenu:not.*?\.wp-submenu.*?\{[^}]+\}/s', $css, $matches2);
echo "<pre style='background: #f5f5f5; padding: 20px; overflow: auto;'>";
foreach ($matches2[0] as $rule) {
    echo htmlspecialchars($rule) . "\n\n";
}
echo "</pre>";
