<?php
/**
 * Fix validation errors in settings
 */

// Load WordPress
$wp_load = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/wp-load.php';
if ( file_exists( $wp_load ) ) {
    require_once $wp_load;
} else {
    die( "Error: Cannot find wp-load.php\n" );
}

echo "🔧 Fixing validation errors...\n\n";

// Get current settings
$settings = get_option( 'woow_settings', array() );

// Fix opacity values (must be 0-1)
if ( isset( $settings['admin_bar']['opacity'] ) && $settings['admin_bar']['opacity'] > 1 ) {
    $settings['admin_bar']['opacity'] = $settings['admin_bar']['opacity'] / 100;
    echo "✅ Fixed admin_bar.opacity\n";
}

// Fix item_height - add px unit
if ( isset( $settings['admin_menu']['item_height'] ) && is_numeric( $settings['admin_menu']['item_height'] ) ) {
    $settings['admin_menu']['item_height'] = $settings['admin_menu']['item_height'] . 'px';
    echo "✅ Fixed admin_menu.item_height\n";
}

// Fix image_size - this should be a string value, not validated as unit
if ( ! isset( $settings['backgrounds']['image_size'] ) ) {
    $settings['backgrounds']['image_size'] = 'cover';
    echo "✅ Set backgrounds.image_size\n";
}

// Fix line-height - can be unitless
if ( isset( $settings['typography']['h1_line_height'] ) && is_numeric( $settings['typography']['h1_line_height'] ) ) {
    // Line height is fine as unitless number
    echo "✅ typography.h1_line_height is OK (unitless)\n";
}

if ( isset( $settings['typography']['body_line_height'] ) && is_numeric( $settings['typography']['body_line_height'] ) ) {
    // Line height is fine as unitless number
    echo "✅ typography.body_line_height is OK (unitless)\n";
}

// Fix blur_strength - add px unit
if ( isset( $settings['login_page']['blur_strength'] ) && is_numeric( $settings['login_page']['blur_strength'] ) ) {
    $settings['login_page']['blur_strength'] = $settings['login_page']['blur_strength'] . 'px';
    echo "✅ Fixed login_page.blur_strength\n";
}

// Save settings
update_option( 'woow_settings', $settings );

// Clear cache
global $wpdb;
$wpdb->query(
    "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_woow_%' OR option_name LIKE '_transient_timeout_woow_%'"
);

if ( function_exists( 'wp_cache_flush' ) ) {
    wp_cache_flush();
}

echo "\n✅ DONE! Settings fixed and cache cleared.\n";
echo "Now try saving again.\n";
