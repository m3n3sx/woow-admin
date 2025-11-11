<?php
/**
 * Force reload - clear all caches and reload page
 * 
 * Open this file in browser: http://localhost:10004/wp-content/plugins/woow-admin/force-reload.php
 */

// Clear WordPress cache
if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
}

// Clear transients
global $wpdb;
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%'");

// Clear WOOW cache
delete_transient('woow_generated_css');
delete_option('woow_css_cache');

echo "✅ All caches cleared!<br>";
echo "✅ Now refresh your browser with Ctrl+Shift+R<br>";
echo "<br>";
echo "Current time: " . time() . "<br>";
echo "Expected version: 2.0.0-beta." . time() . "<br>";
