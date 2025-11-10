<?php
/**
 * Quick WOOW! Cache Cleaner
 * 
 * Run: php clear-woow-cache.php
 */

// Load WordPress
require_once '../../../wp-load.php';

echo "🧹 Clearing WOOW! Admin cache...\n\n";

// 1. Delete generated CSS cache
$deleted = delete_transient('woow_cache_generated_css');
echo "1. Generated CSS cache: " . ($deleted ? "✓ DELETED" : "⚠ Not found") . "\n";

// 2. Delete all WOOW transients
global $wpdb;
$result = $wpdb->query(
    "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_woow_%' OR option_name LIKE '_transient_timeout_woow_%'"
);
echo "2. All WOOW transients: ✓ Deleted {$result} rows\n";

// 3. Flush object cache
if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
    echo "3. Object cache: ✓ FLUSHED\n";
}

// 4. Clear OPcache
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "4. OPcache: ✓ RESET\n";
}

echo "\n✅ CACHE CLEARED!\n";
echo "Now refresh your browser with Ctrl+Shift+R\n";
