<?php
/**
 * Enable WOOW! Admin globally
 * 
 * Run this file once to enable the plugin on all admin pages
 * URL: /wp-content/plugins/woow-admin/enable-globally.php
 */

// Load WordPress
require_once('../../../wp-load.php');

if (!current_user_can('manage_options')) {
    die('Access denied');
}

// Get current settings
$settings = get_option('woow_admin_settings', array());

// Load defaults
require_once('includes/defaults.php');
$defaults = woow_get_default_settings();

// Ensure all sections exist with enabled = true
$sections = ['general', 'admin_bar', 'admin_menu', 'dashboard_widgets', 'form_controls', 'buttons', 'backgrounds', 'typography', 'visual_effects', 'login_page'];

foreach ($sections as $section) {
    if (!isset($settings[$section])) {
        $settings[$section] = $defaults[$section] ?? array();
    }
    $settings[$section]['enabled'] = true;
}

// Save updated settings
update_option('woow_admin_settings', $settings);

// Clear all caches
delete_transient('woow_admin_css');
delete_transient('woow_admin_css_hash');
wp_cache_flush();

echo "<h1>✅ WOOW! Admin Enabled Globally</h1>";
echo "<p>The plugin is now active on <strong>all WordPress admin pages</strong>.</p>";
echo "<h2>Enabled Sections:</h2>";
echo "<ul>";
foreach ($sections as $section) {
    echo "<li>✅ " . ucwords(str_replace('_', ' ', $section)) . "</li>";
}
echo "</ul>";
echo "<p><strong>Next steps:</strong></p>";
echo "<ol>";
echo "<li>Hard refresh browser (Ctrl+Shift+R)</li>";
echo "<li>Visit any admin page to see the styling</li>";
echo "<li>Delete this file: enable-globally.php</li>";
echo "</ol>";
echo "<p><a href='/wp-admin/' style='padding: 10px 20px; background: #10b981; color: white; text-decoration: none; border-radius: 8px; display: inline-block; margin: 10px 10px 10px 0;'>Go to WordPress Dashboard</a></p>";
echo "<p><a href='/wp-admin/admin.php?page=woow-admin' style='padding: 10px 20px; background: #6366f1; color: white; text-decoration: none; border-radius: 8px; display: inline-block;'>Go to WOOW! Admin Settings</a></p>";
