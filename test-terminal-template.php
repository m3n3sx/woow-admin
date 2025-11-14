<?php
/**
 * Test Terminal Template
 * 
 * Run this file to check if Terminal template is available
 */

// Load WordPress
require_once('../../../wp-load.php');

// Check if WOOW Admin is active
if (!class_exists('WOOW_Admin')) {
    die('WOOW Admin plugin is not active!');
}

// Get template manager
$settings = new WOOW_Settings();
$template_manager = new WOOW_Template_Manager($settings);

// Get all templates
$templates = $template_manager->get_all_templates();

echo "<h1>WOOW Admin Templates</h1>";
echo "<p>Total templates: " . count($templates) . "</p>";

echo "<h2>Available Templates:</h2>";
echo "<ul>";
foreach ($templates as $template) {
    $is_terminal = ($template['id'] === 'terminal') ? ' <strong>(TERMINAL)</strong>' : '';
    echo "<li>";
    echo "<strong>{$template['name']}</strong> (ID: {$template['id']}){$is_terminal}<br>";
    echo "<em>{$template['description']}</em>";
    echo "</li>";
}
echo "</ul>";

// Try to get Terminal template specifically
echo "<h2>Terminal Template Details:</h2>";
$terminal = $template_manager->get_template('terminal');

if ($terminal) {
    echo "<pre>";
    echo "ID: " . $terminal['id'] . "\n";
    echo "Name: " . $terminal['name'] . "\n";
    echo "Description: " . $terminal['description'] . "\n";
    echo "Thumbnail: " . $terminal['thumbnail'] . "\n";
    echo "\nSettings sections: " . implode(', ', array_keys($terminal['settings'])) . "\n";
    echo "</pre>";
    
    echo "<p style='color: green; font-weight: bold;'>✓ Terminal template is available!</p>";
} else {
    echo "<p style='color: red; font-weight: bold;'>✗ Terminal template NOT found!</p>";
}
