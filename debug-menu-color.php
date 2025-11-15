<?php
/**
 * Debug Menu Background Color
 */

// Load WordPress
require_once('../../../wp-load.php');

// Load classes
require_once('includes/class-woow-settings.php');
require_once('includes/class-woow-css-generator.php');

echo "<style>body { font-family: monospace; } table { border-collapse: collapse; } td, th { border: 1px solid #ccc; padding: 8px; }</style>";
echo "<h1>🔍 Debug: Admin Menu Background Color</h1>";

$settings = new WOOW_Settings();

// 1. Check current database value
echo "<h2>1️⃣ Database Value</h2>";
$db_settings = get_option('woow_admin_settings', array());
echo "<table>";
echo "<tr><th>Key</th><th>Value</th></tr>";
echo "<tr><td>background_type</td><td><strong>" . ($db_settings['admin_menu']['background_type'] ?? 'NOT SET') . "</strong></td></tr>";
echo "<tr><td>background_color</td><td><strong style='color: " . ($db_settings['admin_menu']['background_color'] ?? '#000') . "'>" . ($db_settings['admin_menu']['background_color'] ?? 'NOT SET') . "</strong></td></tr>";
echo "<tr><td>gradient_start</td><td><strong style='color: " . ($db_settings['admin_menu']['gradient_start'] ?? '#000') . "'>" . ($db_settings['admin_menu']['gradient_start'] ?? 'NOT SET') . "</strong></td></tr>";
echo "<tr><td>gradient_end</td><td><strong style='color: " . ($db_settings['admin_menu']['gradient_end'] ?? '#000') . "'>" . ($db_settings['admin_menu']['gradient_end'] ?? 'NOT SET') . "</strong></td></tr>";
echo "<tr><td>glass_base_color</td><td><strong style='color: " . ($db_settings['admin_menu']['glass_base_color'] ?? '#000') . "'>" . ($db_settings['admin_menu']['glass_base_color'] ?? 'NOT SET') . "</strong></td></tr>";
echo "</table>";

// 2. Check what Settings class returns
echo "<h2>2️⃣ Settings Class Returns</h2>";
$menu_settings = $settings->get_section('admin_menu');
echo "<table>";
echo "<tr><th>Key</th><th>Value</th></tr>";
echo "<tr><td>background_type</td><td><strong>" . ($menu_settings['background_type'] ?? 'NOT SET') . "</strong></td></tr>";
echo "<tr><td>background_color</td><td><strong style='color: " . ($menu_settings['background_color'] ?? '#000') . "'>" . ($menu_settings['background_color'] ?? 'NOT SET') . "</strong></td></tr>";
echo "<tr><td>gradient_start</td><td><strong style='color: " . ($menu_settings['gradient_start'] ?? '#000') . "'>" . ($menu_settings['gradient_start'] ?? 'NOT SET') . "</strong></td></tr>";
echo "<tr><td>gradient_end</td><td><strong style='color: " . ($menu_settings['gradient_end'] ?? '#000') . "'>" . ($menu_settings['gradient_end'] ?? 'NOT SET') . "</strong></td></tr>";
echo "<tr><td>glass_base_color</td><td><strong style='color: " . ($menu_settings['glass_base_color'] ?? '#000') . "'>" . ($menu_settings['glass_base_color'] ?? 'NOT SET') . "</strong></td></tr>";
echo "</table>";

// 3. Check generated CSS
echo "<h2>3️⃣ Generated CSS</h2>";
$css_generator = new WOOW_CSS_Generator($settings);
$css = $css_generator->generate();

// Extract admin menu CSS
$start = strpos($css, '/* Admin Menu Styling');
$end = strpos($css, '/* Dashboard', $start);
if ($start === false) {
    $end = strpos($css, '/* Form', $start);
}
if ($start !== false && $end !== false) {
    $menu_css = substr($css, $start, $end - $start);
} else {
    $menu_css = "CSS section not found!";
}

// Find background line
preg_match('/background:\s*([^!]+)!important/m', $menu_css, $bg_match);
$background_line = $bg_match[0] ?? 'NOT FOUND';

echo "<div style='background: #f0f0f0; padding: 10px; margin: 10px 0;'>";
echo "<strong>Background CSS:</strong><br>";
echo "<code style='color: red; font-size: 14px;'>" . htmlspecialchars($background_line) . "</code>";
echo "</div>";

echo "<details>";
echo "<summary><strong>Full Admin Menu CSS (click to expand)</strong></summary>";
echo "<pre style='background: #f0f0f0; padding: 10px; overflow-x: auto; font-size: 11px;'>";
echo htmlspecialchars($menu_css);
echo "</pre>";
echo "</details>";

// 4. Test color validation
echo "<h2>4️⃣ Color Validation Test</h2>";
$test_colors = array(
    '#ff0000' => 'Red',
    '#00ff00' => 'Green',
    '#0000ff' => 'Blue',
    'rgba(255, 0, 0, 0.9)' => 'Red RGBA',
    '#ffffff' => 'White',
);

echo "<table>";
echo "<tr><th>Color</th><th>Preview</th><th>Valid?</th></tr>";

$reflection = new ReflectionClass($settings);
$method = $reflection->getMethod('sanitize_color');
$method->setAccessible(true);

foreach ($test_colors as $color => $name) {
    $result = $method->invoke($settings, $color);
    $status = $result !== false ? '✅ VALID' : '❌ INVALID';
    echo "<tr>";
    echo "<td><code>" . htmlspecialchars($color) . "</code></td>";
    echo "<td><div style='width: 50px; height: 20px; background: " . htmlspecialchars($color) . "; border: 1px solid #000;'></div></td>";
    echo "<td>$status</td>";
    echo "</tr>";
}
echo "</table>";

// 5. Check conditional fields visibility
echo "<h2>5️⃣ Conditional Fields Check</h2>";
echo "<p>Current background_type: <strong>" . ($menu_settings['background_type'] ?? 'NOT SET') . "</strong></p>";
echo "<p>This means the following field should be visible:</p>";
echo "<ul>";
if (($menu_settings['background_type'] ?? 'solid') === 'solid') {
    echo "<li>✅ <strong>background_color</strong> field (Solid Color)</li>";
} elseif (($menu_settings['background_type'] ?? 'solid') === 'gradient') {
    echo "<li>✅ <strong>gradient_start</strong> and <strong>gradient_end</strong> fields (Gradient)</li>";
} elseif (($menu_settings['background_type'] ?? 'solid') === 'glass') {
    echo "<li>✅ <strong>glass_base_color</strong> field (Glassmorphism)</li>";
}
echo "</ul>";

echo "<h2>✅ Next Steps</h2>";
echo "<ol>";
echo "<li>Check if the correct field is visible in the admin panel</li>";
echo "<li>Try changing the color and click 'Apply Changes'</li>";
echo "<li>Refresh this page to see if the database value changed</li>";
echo "<li>Check browser console for JavaScript errors</li>";
echo "</ol>";
