<?php
/**
 * Enable Menu Styling
 */

// Load WordPress
$wp_load = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/wp-load.php';
if ( file_exists( $wp_load ) ) {
    require_once $wp_load;
} else {
    die( "Error: Cannot find wp-load.php\n" );
}

echo "🔧 Enabling menu styling...\n\n";

// Get current settings
$settings = get_option( 'woow_settings', array() );

// Enable admin menu
if ( ! isset( $settings['admin_menu'] ) ) {
    $settings['admin_menu'] = array();
}
$settings['admin_menu']['enabled'] = true;

// Clear custom CSS
$settings['admin_menu']['custom_css'] = '';

// Save settings
update_option( 'woow_settings', $settings );

echo "✅ Menu styling enabled!\n";
echo "✅ Custom CSS cleared!\n\n";

// Clear all caches
global $wpdb;
$deleted = $wpdb->query(
    "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_woow_%' OR option_name LIKE '_transient_timeout_woow_%'"
);
echo "Cleared {$deleted} transients\n";

if ( function_exists( 'wp_cache_flush' ) ) {
    wp_cache_flush();
    echo "Flushed object cache\n";
}

if ( function_exists( 'opcache_reset' ) ) {
    opcache_reset();
    echo "Reset OPcache\n";
}

echo "\n✅ DONE! Refresh your browser with Ctrl+Shift+R\n";
