<?php
/**
 * Clear PHP OPcache
 * 
 * Run this file in browser or via WP-CLI to clear PHP cache
 */

// Security check
if (!defined('ABSPATH')) {
    // If not in WordPress, check if accessed directly
    if (php_sapi_name() === 'cli') {
        echo "Clearing cache via CLI...\n";
    } else {
        // Simple security token
        if (!isset($_GET['token']) || $_GET['token'] !== 'clear-woow-cache-2025') {
            die('Access denied. Use: ?token=clear-woow-cache-2025');
        }
    }
}

echo "WOOW! Admin Cache Clearer\n";
echo "=========================\n\n";

// Clear OPcache
if (function_exists('opcache_reset')) {
    if (opcache_reset()) {
        echo "✓ OPcache cleared successfully\n";
    } else {
        echo "✗ Failed to clear OPcache\n";
    }
} else {
    echo "- OPcache not enabled\n";
}

// Clear APCu cache
if (function_exists('apcu_clear_cache')) {
    if (apcu_clear_cache()) {
        echo "✓ APCu cache cleared successfully\n";
    } else {
        echo "✗ Failed to clear APCu cache\n";
    }
} else {
    echo "- APCu not enabled\n";
}

// Clear WordPress transients if in WordPress
if (defined('ABSPATH')) {
    global $wpdb;
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%'");
    echo "✓ WordPress transients cleared\n";
}

echo "\n";
echo "Cache cleared! Please refresh your browser.\n";
echo "\n";
echo "If you're still seeing errors:\n";
echo "1. Restart your web server (Apache/Nginx)\n";
echo "2. Restart PHP-FPM if using it\n";
echo "3. Clear browser cache (Ctrl+Shift+R)\n";
