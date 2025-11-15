<?php
/**
 * Test Save Color - Diagnostic
 */

// Load WordPress
require_once('../../../wp-load.php');

// Load classes
require_once('includes/class-woow-settings.php');

echo "<h1>Test Save Admin Menu Background Color</h1>";

$settings = new WOOW_Settings();

// Get current settings
$current = $settings->get_section('admin_menu');
echo "<h2>Current Settings:</h2>";
echo "<pre>";
echo "background_type: " . ($current['background_type'] ?? 'NOT SET') . "\n";
echo "background_color: " . ($current['background_color'] ?? 'NOT SET') . "\n";
echo "gradient_start: " . ($current['gradient_start'] ?? 'NOT SET') . "\n";
echo "gradient_end: " . ($current['gradient_end'] ?? 'NOT SET') . "\n";
echo "glass_base_color: " . ($current['glass_base_color'] ?? 'NOT SET') . "\n";
echo "</pre>";

// Test validation
echo "<h2>Test Color Validation:</h2>";
$test_colors = array(
    '#ff0000' => 'Red hex',
    'rgba(255, 0, 0, 0.9)' => 'Red rgba',
    '#ff0000ff' => 'Red hex with alpha',
    'transparent' => 'Transparent',
);

echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Color</th><th>Description</th><th>Valid?</th></tr>";

$reflection = new ReflectionClass($settings);
$method = $reflection->getMethod('sanitize_color');
$method->setAccessible(true);

foreach ($test_colors as $color => $desc) {
    $result = $method->invoke($settings, $color);
    $status = $result !== false ? '✅ YES' : '❌ NO';
    echo "<tr><td><code>" . htmlspecialchars($color) . "</code></td><td>$desc</td><td>$status</td></tr>";
}
echo "</table>";

// Simulate save
echo "<h2>Simulate Save:</h2>";
$test_data = array(
    'admin_menu' => array(
        'background_type' => 'solid',
        'background_color' => '#ff0000',
    )
);

echo "<p>Attempting to save: <code>background_color = #ff0000</code></p>";

// Validate
$validated = $settings->validate_settings($test_data);
echo "<h3>Validation Result:</h3>";
echo "<pre>";
print_r($validated);
echo "</pre>";

if ($validated['is_valid']) {
    echo "<p style='color: green; font-weight: bold;'>✅ Validation PASSED</p>";
} else {
    echo "<p style='color: red; font-weight: bold;'>❌ Validation FAILED</p>";
    echo "<h4>Errors:</h4>";
    echo "<pre>";
    print_r($validated['errors']);
    echo "</pre>";
}
