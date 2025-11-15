<?php
/**
 * Test Color Validation
 */

// Load WordPress
require_once('../../../wp-load.php');

// Load settings class
require_once('includes/class-woow-settings.php');

$settings = new WOOW_Settings();

echo "<h1>Color Validation Test</h1>";

// Test colors
$test_colors = array(
    '#ffffff' => 'Valid hex (6 chars)',
    '#fff' => 'Valid hex (3 chars)',
    '#ffffffff' => 'Valid hex with alpha (8 chars)',
    'rgba(255, 255, 255, 0.9)' => 'Valid rgba',
    'rgb(255, 255, 255)' => 'Valid rgb',
    'transparent' => 'Valid named color',
    'invalid' => 'Invalid color',
    '123456' => 'Invalid (no #)',
);

echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Color</th><th>Description</th><th>Result</th></tr>";

foreach ($test_colors as $color => $description) {
    // Use reflection to call private method
    $reflection = new ReflectionClass($settings);
    $method = $reflection->getMethod('sanitize_color');
    $method->setAccessible(true);
    
    $result = $method->invoke($settings, $color);
    $status = $result !== false ? '✅ VALID' : '❌ INVALID';
    
    echo "<tr>";
    echo "<td><code>" . htmlspecialchars($color) . "</code></td>";
    echo "<td>" . htmlspecialchars($description) . "</td>";
    echo "<td>" . $status . "</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h2>Current admin_menu settings:</h2>";
echo "<pre>";
print_r($settings->get_section('admin_menu'));
echo "</pre>";
