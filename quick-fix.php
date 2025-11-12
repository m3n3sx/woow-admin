<?php
/**
 * Quick fix - Reset to defaults and enable everything
 */

require_once('../../../wp-load.php');

if (!current_user_can('manage_options')) {
    die('Access denied');
}

// Load defaults
require_once('includes/defaults.php');

// Get fresh defaults
$defaults = woow_get_default_settings();

// Save defaults directly (overwrite everything)
$result = update_option('woow_admin_settings', $defaults);

// Clear all caches
delete_transient('woow_admin_css');
delete_transient('woow_admin_css_hash');
wp_cache_flush();

echo "<h1>⚡ Quick Fix Applied</h1>";

if ($result) {
    echo "<p style='color: green; font-weight: bold; font-size: 18px;'>✅ ALL settings reset to defaults!</p>";
} else {
    echo "<p style='color: orange; font-weight: bold;'>⚠️ Settings may have been already correct</p>";
}

echo "<h2>What was done:</h2>";
echo "<ul>";
echo "<li>✅ Reset ALL sections to default values</li>";
echo "<li>✅ Enabled plugin globally (general.enabled = true)</li>";
echo "<li>✅ Enabled all sections (admin_bar, admin_menu, etc.)</li>";
echo "<li>✅ Applied new improved admin menu defaults</li>";
echo "<li>✅ Cleared all caches</li>";
echo "</ul>";

echo "<h2>Admin Menu Defaults Applied:</h2>";
echo "<ul>";
echo "<li>Width: 256px</li>";
echo "<li>Text color: #64748b (gray)</li>";
echo "<li>Hover text: #0f172a (dark)</li>";
echo "<li>Border radius: 24px</li>";
echo "<li>Font weight: 600 (semibold)</li>";
echo "<li>Item height: 48px</li>";
echo "<li>Glassmorphism: enabled</li>";
echo "</ul>";

echo "<hr>";
echo "<h2 style='color: #ef4444;'>⚠️ IMPORTANT - Next Steps:</h2>";
echo "<ol style='font-size: 16px;'>";
echo "<li><strong>Hard refresh browser:</strong> Ctrl+Shift+R (or Cmd+Shift+R on Mac)</li>";
echo "<li><strong>Visit any admin page</strong> to see the new styling</li>";
echo "<li><strong>Delete this file:</strong> quick-fix.php</li>";
echo "</ol>";

echo "<p style='margin-top: 30px;'>";
echo "<a href='/wp-admin/' style='padding: 15px 30px; background: #10b981; color: white; text-decoration: none; border-radius: 8px; display: inline-block; font-size: 16px; font-weight: bold;'>Go to Dashboard</a> ";
echo "<a href='/wp-admin/admin.php?page=woow-admin' style='padding: 15px 30px; background: #6366f1; color: white; text-decoration: none; border-radius: 8px; display: inline-block; font-size: 16px; font-weight: bold;'>Go to WOOW! Settings</a>";
echo "</p>";
