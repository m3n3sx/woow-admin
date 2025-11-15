<?php
/**
 * Force Refresh - Clear WordPress cache and force browser refresh
 */

// Load WordPress
require_once('../../../wp-load.php');

// Check if user is admin
if (!current_user_can('manage_options')) {
    die('Access denied');
}

echo "<h1>🔄 Force Refresh WOOW! Admin</h1>";

// Clear WordPress object cache
wp_cache_flush();
echo "<p>✅ WordPress object cache cleared</p>";

// Clear transients
global $wpdb;
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%'");
echo "<p>✅ Transients cleared</p>";

// Get plugin version
$plugin_data = get_file_data(
    __DIR__ . '/woow-admin.php',
    array('Version' => 'Version')
);
$version = $plugin_data['Version'] ?? '1.0.0';

echo "<p>📦 Current plugin version: <strong>{$version}</strong></p>";

// Check file timestamps
$files = [
    'includes/class-woow-admin.php',
    'includes/templates/tabs/login-tab.php',
    'assets/dist/main.js',
    'assets/dist/style.css',
];

echo "<h2>📁 File Timestamps:</h2>";
echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
echo "<tr><th>File</th><th>Last Modified</th><th>Size</th></tr>";

foreach ($files as $file) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        $mtime = filemtime($path);
        $size = filesize($path);
        $date = date('Y-m-d H:i:s', $mtime);
        $size_kb = round($size / 1024, 2);
        echo "<tr>";
        echo "<td><code>{$file}</code></td>";
        echo "<td>{$date}</td>";
        echo "<td>{$size_kb} KB</td>";
        echo "</tr>";
    } else {
        echo "<tr>";
        echo "<td><code>{$file}</code></td>";
        echo "<td colspan='2' style='color: red;'>❌ File not found</td>";
        echo "</tr>";
    }
}
echo "</table>";

// Check if wp_enqueue_media is in code
$admin_class = file_get_contents(__DIR__ . '/includes/class-woow-admin.php');
$has_enqueue_media = strpos($admin_class, 'wp_enqueue_media()') !== false;

echo "<h2>🔍 Code Checks:</h2>";
echo "<ul>";
echo "<li>wp_enqueue_media() present: " . ($has_enqueue_media ? "✅ YES" : "❌ NO") . "</li>";

// Check MediaUploader in JS
$main_js = file_get_contents(__DIR__ . '/assets/dist/main.js');
$has_media_uploader = strpos($main_js, 'MediaUploader') !== false;
echo "<li>MediaUploader in main.js: " . ($has_media_uploader ? "✅ YES" : "❌ NO") . "</li>";

// Check login tab
$login_tab = file_get_contents(__DIR__ . '/includes/templates/tabs/login-tab.php');
$has_correct_name = strpos($login_tab, 'name="login_page[background_color]"') !== false;
echo "<li>Correct name attribute in login tab: " . ($has_correct_name ? "✅ YES" : "❌ NO") . "</li>";

$has_correct_conditional = strpos($login_tab, 'data-show-when="login_page[background_type]=color"') !== false;
echo "<li>Correct conditional format: " . ($has_correct_conditional ? "✅ YES" : "❌ NO") . "</li>";
echo "</ul>";

echo "<h2>🎯 Next Steps:</h2>";
echo "<ol>";
echo "<li><strong>Clear browser cache:</strong> Ctrl+Shift+Delete</li>";
echo "<li><strong>Hard refresh:</strong> Ctrl+Shift+R</li>";
echo "<li><strong>Go to WOOW! Admin:</strong> <a href='" . admin_url('admin.php?page=woow-admin') . "' target='_blank'>Open WOOW! Admin</a></li>";
echo "<li><strong>Open Developer Tools:</strong> F12</li>";
echo "<li><strong>Check Console:</strong> Look for '[MediaUploader] Initialized'</li>";
echo "<li><strong>Test Upload button:</strong> Should open WordPress Media Library</li>";
echo "</ol>";

echo "<h2>🐛 Debug Info:</h2>";
echo "<pre>";
echo "WordPress Version: " . get_bloginfo('version') . "\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Plugin Directory: " . __DIR__ . "\n";
echo "Assets URL: " . plugins_url('assets/dist/', __FILE__) . "\n";
echo "Current User: " . wp_get_current_user()->user_login . "\n";
echo "Is Admin: " . (current_user_can('manage_options') ? 'YES' : 'NO') . "\n";
echo "</pre>";

echo "<hr>";
echo "<p><a href='" . admin_url('admin.php?page=woow-admin') . "' class='button button-primary'>Go to WOOW! Admin</a></p>";
