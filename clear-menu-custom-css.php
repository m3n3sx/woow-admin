<?php
/**
 * Clear Menu Custom CSS
 * This script clears the custom CSS field for admin menu
 */

// Load WordPress
$wp_load = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/wp-load.php';
if ( file_exists( $wp_load ) ) {
    require_once $wp_load;
} else {
    die( "Error: Cannot find wp-load.php\n" );
}

echo "🧹 Clearing Menu Custom CSS...\n\n";

// Get current settings
$settings = get_option( 'woow_settings', array() );

if ( isset( $settings['admin_menu']['custom_css'] ) ) {
    echo "Found custom CSS: " . strlen( $settings['admin_menu']['custom_css'] ) . " characters\n";
    
    // Clear the custom CSS
    $settings['admin_menu']['custom_css'] = '';
    
    // Save settings
    update_option( 'woow_settings', $settings );
    
    echo "✅ Custom CSS cleared!\n\n";
} else {
    echo "⚠️  No custom CSS found in settings\n\n";
}

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
