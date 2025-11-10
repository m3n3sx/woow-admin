<?php
/**
 * Hardcore Cache Cleaner for WOOW! Admin
 * 
 * Run this file directly in browser: /wp-content/plugins/woow-admin/clear-cache.php
 * 
 * @package WoowAdmin
 */

// Load WordPress
require_once '../../../wp-load.php';

// Security check
if (!current_user_can('manage_options')) {
    die('Access denied. You must be an administrator.');
}

echo '<h1>🧹 WOOW! Admin - Hardcore Cache Cleaner</h1>';
echo '<pre>';

$cleared = [];

// 1. Clear WordPress transients
echo "1. Clearing WordPress transients...\n";
global $wpdb;
$result = $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%'");
$cleared[] = "Transients: {$result} rows deleted";
echo "   ✓ Deleted {$result} transient rows\n\n";

// 2. Clear WOOW specific options
echo "2. Clearing WOOW! Admin cache...\n";
$woow_options = $wpdb->get_results(
    "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE 'woow_%'"
);
foreach ($woow_options as $option) {
    if (strpos($option->option_name, 'woow_cache_') === 0) {
        delete_option($option->option_name);
        echo "   ✓ Deleted: {$option->option_name}\n";
    }
}
echo "\n";

// 3. Clear object cache
echo "3. Clearing object cache...\n";
if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
    $cleared[] = "Object cache flushed";
    echo "   ✓ Object cache flushed\n\n";
}

// 4. Clear opcache (PHP)
echo "4. Clearing PHP OPcache...\n";
if (function_exists('opcache_reset')) {
    opcache_reset();
    $cleared[] = "OPcache reset";
    echo "   ✓ OPcache reset\n\n";
} else {
    echo "   ⚠ OPcache not available\n\n";
}

// 5. Clear rewrite rules
echo "5. Flushing rewrite rules...\n";
flush_rewrite_rules();
$cleared[] = "Rewrite rules flushed";
echo "   ✓ Rewrite rules flushed\n\n";

// 6. Clear theme/plugin caches
echo "6. Clearing theme and plugin caches...\n";
if (function_exists('wp_clean_themes_cache')) {
    wp_clean_themes_cache();
    echo "   ✓ Theme cache cleared\n";
}
if (function_exists('wp_clean_plugins_cache')) {
    wp_clean_plugins_cache();
    echo "   ✓ Plugin cache cleared\n";
}
$cleared[] = "Theme/Plugin cache cleared";
echo "\n";

// 7. Clear update cache
echo "7. Clearing update cache...\n";
delete_site_transient('update_core');
delete_site_transient('update_plugins');
delete_site_transient('update_themes');
$cleared[] = "Update cache cleared";
echo "   ✓ Update cache cleared\n\n";

// 8. Clear browser cache headers
echo "8. Setting no-cache headers...\n";
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
$cleared[] = "No-cache headers set";
echo "   ✓ No-cache headers set\n\n";

// 9. Clear WordPress core caches
echo "9. Clearing WordPress core caches...\n";
wp_cache_delete('alloptions', 'options');
wp_cache_delete('notoptions', 'options');
$cleared[] = "Core option cache cleared";
echo "   ✓ Core option cache cleared\n\n";

// 10. Force asset version bump
echo "10. Bumping asset versions...\n";
$version = time();
update_option('woow_asset_version', $version);
$cleared[] = "Asset version: {$version}";
echo "   ✓ Asset version set to: {$version}\n\n";

// Summary
echo str_repeat('=', 60) . "\n";
echo "✅ CACHE CLEARED SUCCESSFULLY!\n";
echo str_repeat('=', 60) . "\n\n";

echo "Summary:\n";
foreach ($cleared as $item) {
    echo "  • {$item}\n";
}

echo "\n";
echo str_repeat('=', 60) . "\n";
echo "🔄 Next steps:\n";
echo "  1. Close this tab\n";
echo "  2. Press Ctrl+Shift+R (or Cmd+Shift+R on Mac) to hard refresh\n";
echo "  3. Go back to WOOW! Admin settings page\n";
echo str_repeat('=', 60) . "\n";

echo '</pre>';

// Add auto-redirect after 3 seconds
?>
<script>
console.log('Cache cleared successfully!');
setTimeout(function() {
    if (confirm('Cache cleared! Redirect to WOOW! Admin page?')) {
        window.location.href = '<?php echo admin_url('admin.php?page=woow-admin'); ?>';
    }
}, 2000);
</script>
<style>
body {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    padding: 40px;
}
h1 {
    text-align: center;
    font-size: 2.5em;
    margin-bottom: 30px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}
pre {
    background: rgba(0,0,0,0.3);
    padding: 30px;
    border-radius: 12px;
    font-size: 14px;
    line-height: 1.6;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    backdrop-filter: blur(10px);
}
</style>
