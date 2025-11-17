<?php
/**
 * Quick test for #wpcontent background
 */

require_once __DIR__ . '/../../../wp-load.php';

if ( ! class_exists( 'WOOW_Admin' ) ) {
    die( "WOOW Admin not active!\n" );
}

echo "=== Testing #wpcontent Background ===\n\n";

// Clear cache
$cache = new WOOW_Cache_Manager();
$cache->clear_all();
echo "✓ Cache cleared\n\n";

// Set test background
$settings = new WOOW_Settings();
$test_settings = [
    'backgrounds' => [
        'enabled' => true,
        'wpbody_content_color' => '#3b82f6',  // Blue
        'wpbody_content_opacity' => '0.2',     // 20% opacity
    ]
];

$settings->update_settings( $test_settings );
echo "✓ Settings updated: Blue (#3b82f6) with 20% opacity\n\n";

// Generate CSS
$css_generator = new WOOW_CSS_Generator( $settings );
$css = $css_generator->generate();

// Extract #wpcontent styles
echo "Generated CSS for #wpcontent:\n";
echo "---\n";
if ( preg_match( '/#wpcontent\s*\{([^}]+)\}/s', $css, $matches ) ) {
    echo trim( $matches[0] ) . "\n";
} else {
    echo "NOT FOUND!\n";
}
echo "---\n\n";

// Extract #wpbody-content styles
echo "Generated CSS for #wpbody-content:\n";
echo "---\n";
if ( preg_match( '/#wpbody-content\s*\{([^}]+)\}/s', $css, $matches ) ) {
    echo trim( $matches[0] ) . "\n";
} else {
    echo "NOT FOUND!\n";
}
echo "---\n\n";

echo "✓ Test complete!\n";
echo "\nNow refresh your WordPress admin page to see the blue background.\n";
echo "If you don't see it, hard refresh with Ctrl+Shift+R\n";
