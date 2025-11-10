<?php
/**
 * Test CSS Generator
 * 
 * Quick test to verify WOOW_CSS_Generator works correctly
 */

// Load WordPress
require_once __DIR__ . '/../../../wp-load.php';

// Load autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Create instances
$settings = new WOOW_Settings();
$generator = new WOOW_CSS_Generator( $settings );

// Generate CSS
$start = microtime( true );
$css = $generator->generate();
$duration = ( microtime( true ) - $start ) * 1000;

// Get metrics
$metrics = $generator->get_metrics();

// Display results
echo "=== WOOW! Admin CSS Generator Test ===\n\n";
echo "Generation Time: " . round( $duration, 2 ) . "ms\n";
echo "CSS Size: " . $metrics['css_size'] . " bytes (" . $metrics['css_size_kb'] . " KB)\n";
echo "Performance Target: <100ms\n";
echo "Status: " . ( $duration < 100 ? "✓ PASSED" : "✗ FAILED" ) . "\n\n";

echo "=== First 500 characters of generated CSS ===\n";
echo substr( $css, 0, 500 ) . "...\n\n";

echo "=== CSS includes these sections ===\n";
$sections = [
    'CSS variables' => strpos( $css, ':root' ) !== false,
    'Admin Bar' => strpos( $css, '#wpadminbar' ) !== false,
    'Admin Menu' => strpos( $css, '#adminmenu' ) !== false,
    'Dashboard Widgets' => strpos( $css, '.postbox' ) !== false,
    'Form Controls' => strpos( $css, 'input[type="text"]' ) !== false,
    'Buttons' => strpos( $css, '.button-primary' ) !== false,
    'Background' => strpos( $css, '#wpbody-content' ) !== false,
    'Typography' => strpos( $css, 'h1 {' ) !== false,
    'Effects' => strpos( $css, '.woow-glass' ) !== false,
    'Responsive' => strpos( $css, '@media' ) !== false,
];

foreach ( $sections as $section => $found ) {
    echo "  " . ( $found ? "✓" : "✗" ) . " {$section}\n";
}

echo "\n=== Test Complete ===\n";
