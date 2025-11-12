<?php
/**
 * Reset Admin Menu settings to new defaults
 * 
 * Run this file once to apply new menu defaults
 * URL: /wp-content/plugins/woow-admin/reset-menu-defaults.php
 */

// Load WordPress
require_once('../../../wp-load.php');

if (!current_user_can('manage_options')) {
    die('Access denied');
}

// Load defaults
require_once('includes/defaults.php');

// Get current settings
$settings = get_option('woow_admin_settings', array());

// Get new defaults
$general_defaults = woow_get_section_defaults('general');
$menu_defaults = woow_get_section_defaults('admin_menu');

// Update sections with new defaults
$settings['general'] = $general_defaults;
$settings['admin_menu'] = $menu_defaults;

// Save updated settings
update_option('woow_admin_settings', $settings);

// Clear all caches
delete_transient('woow_admin_css');
delete_transient('woow_admin_css_hash');
wp_cache_flush();

echo "<h1>✅ Admin Menu Settings Reset</h1>";
echo "<p>New defaults applied:</p>";
echo "<ul>";
echo "<li><strong>General:</strong> Plugin enabled globally</li>";
echo "<li><strong>Text color:</strong> #64748b (gray)</li>";
echo "<li><strong>Hover text:</strong> #0f172a (dark)</li>";
echo "<li><strong>Item height:</strong> 48px</li>";
echo "<li><strong>Border radius:</strong> 24px</li>";
echo "<li><strong>Font weight:</strong> 600 (semibold)</li>";
echo "<li><strong>Opacity:</strong> 0.8</li>";
echo "<li><strong>Spacing:</strong> Better padding and margins</li>";
echo "</ul>";
echo "<p><strong>Next steps:</strong></p>";
echo "<ol>";
echo "<li>Hard refresh browser (Ctrl+Shift+R)</li>";
echo "<li>Check admin menu appearance</li>";
echo "<li>Delete this file: reset-menu-defaults.php</li>";
echo "</ol>";
echo "<p><a href='/wp-admin/admin.php?page=woow-admin'>Go to WOOW! Admin Settings</a></p>";
