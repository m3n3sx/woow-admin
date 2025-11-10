<?php
/**
 * Force CSS Regeneration
 * Run this to force regenerate all CSS
 */

// Load WordPress
require_once dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/wp-load.php';

if ( ! current_user_can( 'manage_options' ) ) {
    die( 'Access denied. You must be an administrator.' );
}

echo "🔄 Forcing CSS regeneration...\n\n";

// Clear all WOOW caches
global $wpdb;
$deleted = $wpdb->query(
    "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_woow_%' OR option_name LIKE '_transient_timeout_woow_%'"
);
echo "1. Cleared {$deleted} transients\n";

// Flush object cache
if ( function_exists( 'wp_cache_flush' ) ) {
    wp_cache_flush();
    echo "2. Flushed object cache\n";
}

// Clear OPcache
if ( function_exists( 'opcache_reset' ) ) {
    opcache_reset();
    echo "3. Reset OPcache\n";
}

// Delete generated CSS option
delete_option( 'woow_generated_css' );
delete_transient( 'woow_css_cache' );
echo "4. Deleted CSS cache options\n";

echo "\n✅ CSS REGENERATION FORCED!\n";
echo "Now refresh your browser with Ctrl+Shift+R\n";
