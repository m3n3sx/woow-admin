<?php
/**
 * Clear WOOW! Admin CSS Cache
 * 
 * Run this file to force regeneration of CSS
 */

// Load WordPress
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php';

// Check if user is admin
if (!current_user_can('manage_options')) {
    die('Access denied');
}

echo "Clearing WOOW! Admin CSS Cache...\n\n";

// Delete CSS cache transient
delete_transient('woow_generated_css');
echo "✓ Transient cleared\n";

// Try to clear object cache if available
if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
    echo "✓ Object cache flushed\n";
}

// Clear OPcache if available
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "✓ OPcache cleared\n";
}

echo "\n✅ CSS cache cleared successfully!\n";
echo "Refresh your browser to see the changes.\n";
