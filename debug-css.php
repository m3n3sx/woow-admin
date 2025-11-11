<?php
/**
 * Debug CSS generation
 * Open: http://localhost:10004/wp-content/plugins/woow-admin/debug-css.php
 */

require_once('../../../wp-load.php');

// Get settings
$settings_data = get_option('woow_admin_settings', array());

echo "<h2>Admin Bar Settings</h2>";
echo "<pre>";
print_r($settings_data['admin_bar'] ?? 'No admin_bar settings');
echo "</pre>";

echo "<h2>Generated CSS</h2>";

// Generate CSS
require_once('includes/class-woow-settings.php');
require_once('includes/class-woow-css-generator.php');

$settings = new WOOW_Settings();
$generator = new WOOW_CSS_Generator($settings);
$css = $generator->generate();

// Show only admin bar section
$lines = explode("\n", $css);
$show = false;
$admin_bar_css = '';

foreach ($lines as $line) {
    if (strpos($line, 'Admin Bar Styling') !== false) {
        $show = true;
    }
    if ($show) {
        $admin_bar_css .= $line . "\n";
        if (strpos($line, '#wpadminbar #wp-toolbar') !== false) {
            break;
        }
    }
}

echo "<pre style='background: #f5f5f5; padding: 20px; border: 1px solid #ddd;'>";
echo htmlspecialchars($admin_bar_css);
echo "</pre>";

echo "<h2>Key Values</h2>";
$bar = $settings_data['admin_bar'] ?? array();
echo "<ul>";
echo "<li><strong>background_type:</strong> " . ($bar['background_type'] ?? 'not set') . "</li>";
echo "<li><strong>background_color:</strong> " . ($bar['background_color'] ?? 'not set') . "</li>";
echo "<li><strong>gradient_start:</strong> " . ($bar['gradient_start'] ?? 'not set') . "</li>";
echo "<li><strong>gradient_end:</strong> " . ($bar['gradient_end'] ?? 'not set') . "</li>";
echo "<li><strong>opacity:</strong> " . ($bar['opacity'] ?? 'not set') . "</li>";
echo "<li><strong>blur_strength:</strong> " . ($bar['blur_strength'] ?? 'not set') . "</li>";
echo "</ul>";
