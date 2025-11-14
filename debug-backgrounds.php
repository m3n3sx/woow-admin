<?php
/**
 * Debug Backgrounds Settings
 */

require_once '../../../wp-load.php';

if (!current_user_can('manage_options')) {
    die('Access denied');
}

echo '<h1>Backgrounds Settings Debug</h1>';
echo '<style>body { font-family: monospace; padding: 20px; } pre { background: #f5f5f5; padding: 10px; }</style>';

// Get current settings
$settings = get_option('woow_settings', []);
$backgrounds = $settings['backgrounds'] ?? [];

echo '<h2>Current Backgrounds Settings:</h2>';
echo '<pre>';
print_r($backgrounds);
echo '</pre>';

// Check if gradient colors are being saved
echo '<h2>Gradient Colors Check:</h2>';
echo '<ul>';
echo '<li>gradient_start: ' . ($backgrounds['gradient_start'] ?? 'NOT SET') . '</li>';
echo '<li>gradient_end: ' . ($backgrounds['gradient_end'] ?? 'NOT SET') . '</li>';
echo '<li>gradient_angle: ' . ($backgrounds['gradient_angle'] ?? 'NOT SET') . '</li>';
echo '<li>gradient_type: ' . ($backgrounds['gradient_type'] ?? 'NOT SET') . '</li>';
echo '</ul>';

// Test CSS generation
echo '<h2>Generated CSS:</h2>';
if (class_exists('WOOW_CSS_Generator')) {
    $generator = new WOOW_CSS_Generator();
    $css = $generator->generate();
    
    // Extract background section
    $start = strpos($css, '/* Background Styling */');
    if ($start !== false) {
        $end = strpos($css, '/*', $start + 1);
        $bg_css = substr($css, $start, $end - $start);
        echo '<pre>' . htmlspecialchars($bg_css) . '</pre>';
    }
}
