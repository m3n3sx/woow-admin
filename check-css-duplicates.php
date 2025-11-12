<?php
/**
 * Check for duplicate CSS selectors
 */

require_once('../../../wp-load.php');

if (!current_user_can('manage_options')) {
    die('Access denied');
}

// Load classes
require_once('includes/class-woow-settings.php');
require_once('includes/class-woow-css-generator.php');

$settings = new WOOW_Settings();
$generator = new WOOW_CSS_Generator($settings);

// Generate CSS
$css = $generator->generate();

// Find all selectors
preg_match_all('/([^{]+)\s*{/', $css, $matches);
$selectors = $matches[1];

// Count occurrences
$counts = array_count_values(array_map('trim', $selectors));

// Find duplicates
$duplicates = array_filter($counts, function($count) {
    return $count > 1;
});

echo "<h1>🔍 CSS Duplicate Selectors Check</h1>";

if (empty($duplicates)) {
    echo "<p style='color: green; font-weight: bold;'>✅ No duplicate selectors found!</p>";
} else {
    echo "<p style='color: red; font-weight: bold;'>❌ Found " . count($duplicates) . " duplicate selectors:</p>";
    echo "<ul>";
    foreach ($duplicates as $selector => $count) {
        echo "<li><code>" . esc_html($selector) . "</code> - appears <strong>{$count} times</strong></li>";
    }
    echo "</ul>";
}

echo "<h2>Admin Bar Selectors:</h2>";
$adminbar_selectors = array_filter($selectors, function($sel) {
    return strpos($sel, 'wpadminbar') !== false;
});
echo "<p>Found " . count($adminbar_selectors) . " admin bar selectors</p>";

echo "<h2>Admin Menu Selectors:</h2>";
$adminmenu_selectors = array_filter($selectors, function($sel) {
    return strpos($sel, 'adminmenu') !== false;
});
echo "<p>Found " . count($adminmenu_selectors) . " admin menu selectors</p>";
