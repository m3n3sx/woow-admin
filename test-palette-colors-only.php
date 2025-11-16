<?php
/**
 * Test: Palette Colors-Only Application
 * 
 * Verifies that palettes change ONLY colors, not other settings.
 * 
 * Usage: php test-palette-colors-only.php
 */

// Load WordPress
require_once __DIR__ . '/../../../wp-load.php';

echo "=== Palette Colors-Only Test ===\n\n";

// Initialize managers
$settings = new WOOW_Settings();
$backup_manager = new WOOW_Backup_Manager($settings);
$css_generator = new WOOW_CSS_Generator($settings);
$palette_manager = new WOOW_Palette_Manager($settings);
$palette_manager->set_backup_manager($backup_manager);
$palette_manager->set_css_generator($css_generator);

// Test 1: Set custom non-color settings
echo "Test 1: Setting custom non-color settings...\n";

$custom_settings = array(
	'backgrounds' => array(
		'image_url' => 'https://example.com/custom-bg.jpg',
		'image_size' => 'cover',
		'body_background_color' => '#000000', // This SHOULD change
	),
	'typography' => array(
		'body_size' => '18px',
		'h1_size' => '48px',
		'body_color' => '#000000', // This SHOULD change
	),
	'effects' => array(
		'glassmorphism' => true,
		'glassmorphism_blur' => '20px',
	),
	'admin_bar' => array(
		'height' => '60px',
		'background_color' => '#000000', // This SHOULD change
	),
);

foreach ($custom_settings as $section => $values) {
	$current = $settings->get_section($section);
	$updated = array_merge($current, $values);
	$settings->update_section($section, $updated);
}

echo "✅ Custom settings applied\n\n";

// Get settings before palette application
$before = $settings->get_all_settings();

// Test 2: Apply palette
echo "Test 2: Applying palette 'ocean_breeze'...\n";

$result = $palette_manager->apply_palette('ocean_breeze');

if (!$result['success']) {
	echo "❌ Failed to apply palette: " . $result['message'] . "\n";
	exit(1);
}

echo "✅ Palette applied successfully\n\n";

// Get settings after palette application
$after = $settings->get_all_settings();

// Test 3: Verify non-color settings are preserved
echo "Test 3: Verifying non-color settings are preserved...\n\n";

$tests_passed = 0;
$tests_failed = 0;

// Check background image (should NOT change)
if ($after['backgrounds']['image_url'] === 'https://example.com/custom-bg.jpg') {
	echo "✅ Background image preserved: " . $after['backgrounds']['image_url'] . "\n";
	$tests_passed++;
} else {
	echo "❌ Background image changed: " . $before['backgrounds']['image_url'] . " → " . $after['backgrounds']['image_url'] . "\n";
	$tests_failed++;
}

// Check background image size (should NOT change)
if ($after['backgrounds']['image_size'] === 'cover') {
	echo "✅ Background image size preserved: " . $after['backgrounds']['image_size'] . "\n";
	$tests_passed++;
} else {
	echo "❌ Background image size changed: " . $before['backgrounds']['image_size'] . " → " . $after['backgrounds']['image_size'] . "\n";
	$tests_failed++;
}

// Check typography size (should NOT change)
if ($after['typography']['body_size'] === '18px') {
	echo "✅ Typography body size preserved: " . $after['typography']['body_size'] . "\n";
	$tests_passed++;
} else {
	echo "❌ Typography body size changed: " . $before['typography']['body_size'] . " → " . $after['typography']['body_size'] . "\n";
	$tests_failed++;
}

// Check typography h1 size (should NOT change)
if ($after['typography']['h1_size'] === '48px') {
	echo "✅ Typography h1 size preserved: " . $after['typography']['h1_size'] . "\n";
	$tests_passed++;
} else {
	echo "❌ Typography h1 size changed: " . $before['typography']['h1_size'] . " → " . $after['typography']['h1_size'] . "\n";
	$tests_failed++;
}

// Check glassmorphism effect (should NOT change)
if ($after['effects']['glassmorphism'] === true) {
	echo "✅ Glassmorphism effect preserved: enabled\n";
	$tests_passed++;
} else {
	echo "❌ Glassmorphism effect changed\n";
	$tests_failed++;
}

// Check glassmorphism blur (should NOT change)
if ($after['effects']['glassmorphism_blur'] === '20px') {
	echo "✅ Glassmorphism blur preserved: " . $after['effects']['glassmorphism_blur'] . "\n";
	$tests_passed++;
} else {
	echo "❌ Glassmorphism blur changed: " . $before['effects']['glassmorphism_blur'] . " → " . $after['effects']['glassmorphism_blur'] . "\n";
	$tests_failed++;
}

// Check admin bar height (should NOT change)
if ($after['admin_bar']['height'] === '60px') {
	echo "✅ Admin bar height preserved: " . $after['admin_bar']['height'] . "\n";
	$tests_passed++;
} else {
	echo "❌ Admin bar height changed: " . $before['admin_bar']['height'] . " → " . $after['admin_bar']['height'] . "\n";
	$tests_failed++;
}

echo "\n";

// Test 4: Verify color settings ARE changed
echo "Test 4: Verifying color settings ARE changed...\n\n";

// Check background color (SHOULD change)
if ($after['backgrounds']['body_background_color'] !== '#000000') {
	echo "✅ Background color changed: #000000 → " . $after['backgrounds']['body_background_color'] . "\n";
	$tests_passed++;
} else {
	echo "❌ Background color NOT changed (still #000000)\n";
	$tests_failed++;
}

// Check typography color (SHOULD change)
if ($after['typography']['body_color'] !== '#000000') {
	echo "✅ Typography color changed: #000000 → " . $after['typography']['body_color'] . "\n";
	$tests_passed++;
} else {
	echo "❌ Typography color NOT changed (still #000000)\n";
	$tests_failed++;
}

// Check admin bar background color (SHOULD change)
if ($after['admin_bar']['background_color'] !== '#000000') {
	echo "✅ Admin bar background color changed: #000000 → " . $after['admin_bar']['background_color'] . "\n";
	$tests_passed++;
} else {
	echo "❌ Admin bar background color NOT changed (still #000000)\n";
	$tests_failed++;
}

// Summary
echo "\n=== Test Summary ===\n";
echo "Tests passed: $tests_passed\n";
echo "Tests failed: $tests_failed\n";

if ($tests_failed === 0) {
	echo "\n✅ ALL TESTS PASSED - Palettes change only colors!\n";
	exit(0);
} else {
	echo "\n❌ SOME TESTS FAILED - Review implementation\n";
	exit(1);
}
