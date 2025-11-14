<?php
/**
 * Test Backgrounds Settings
 * 
 * This file tests if backgrounds settings are properly saved and retrieved.
 * 
 * Usage: Place in plugin root and access via browser:
 * http://your-site.local/wp-content/plugins/woow-admin/test-backgrounds.php
 */

// Load WordPress
require_once '../../../wp-load.php';

// Check if user is admin
if (!current_user_can('manage_options')) {
    die('Access denied. You must be an administrator.');
}

echo '<h1>WOOW! Admin - Backgrounds Settings Test</h1>';
echo '<style>
    body { font-family: monospace; padding: 20px; background: #f5f5f5; }
    h1 { color: #333; }
    h2 { color: #666; margin-top: 30px; }
    pre { background: #fff; padding: 15px; border: 1px solid #ddd; border-radius: 5px; overflow-x: auto; }
    .success { color: green; font-weight: bold; }
    .error { color: red; font-weight: bold; }
    .info { color: blue; }
</style>';

// Get settings
$settings = get_option('woow_settings', []);
$backgrounds = $settings['backgrounds'] ?? [];

echo '<h2>Current Backgrounds Settings:</h2>';
echo '<pre>';
print_r($backgrounds);
echo '</pre>';

// Check required fields
echo '<h2>Field Validation:</h2>';
$required_fields = [
    'enabled',
    'background_color',
    'type',
    'gradient_type',
    'gradient_start',
    'gradient_end',
    'gradient_angle',
    'image_url',
    'image_position',
    'image_size',
    'image_repeat',
    'wpbody_content_color',
    'custom_css'
];

echo '<ul>';
foreach ($required_fields as $field) {
    $exists = isset($backgrounds[$field]);
    $class = $exists ? 'success' : 'error';
    $status = $exists ? '✓ EXISTS' : '✗ MISSING';
    $value = $exists ? var_export($backgrounds[$field], true) : 'N/A';
    echo "<li class='$class'>$field: $status (Value: $value)</li>";
}
echo '</ul>';

// Test CSS generation
echo '<h2>CSS Generation Test:</h2>';
if (class_exists('WOOW_CSS_Generator')) {
    try {
        $generator = new WOOW_CSS_Generator();
        $css = $generator->generate();
        
        // Check if background CSS is present
        if (strpos($css, '/* Background Styling */') !== false) {
            echo '<p class="success">✓ Background CSS is being generated</p>';
            
            // Extract background section
            $start = strpos($css, '/* Background Styling */');
            $end = strpos($css, '/*', $start + 1);
            $bg_css = substr($css, $start, $end - $start);
            
            echo '<h3>Generated Background CSS:</h3>';
            echo '<pre>' . htmlspecialchars($bg_css) . '</pre>';
        } else {
            echo '<p class="error">✗ Background CSS is NOT being generated</p>';
        }
    } catch (Exception $e) {
        echo '<p class="error">✗ Error generating CSS: ' . $e->getMessage() . '</p>';
    }
} else {
    echo '<p class="error">✗ WOOW_CSS_Generator class not found</p>';
}

// Test defaults
echo '<h2>Default Values Test:</h2>';
if (function_exists('woow_get_default_settings')) {
    $defaults = woow_get_default_settings();
    $bg_defaults = $defaults['backgrounds'] ?? [];
    
    echo '<pre>';
    print_r($bg_defaults);
    echo '</pre>';
} else {
    echo '<p class="error">✗ woow_get_default_settings() function not found</p>';
}

echo '<hr>';
echo '<p class="info">Test completed at ' . date('Y-m-d H:i:s') . '</p>';
