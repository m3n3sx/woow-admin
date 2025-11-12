<?php
/**
 * Fix missing settings by merging with defaults
 */

require_once('../../../wp-load.php');

if (!current_user_can('manage_options')) {
    die('Access denied');
}

// Load defaults
require_once('includes/defaults.php');

// Get current settings from database
$current_settings = get_option('woow_admin_settings', array());

// Get all defaults
$defaults = woow_get_default_settings();

// Merge each section with defaults
$fixed_settings = array();
foreach ($defaults as $section => $section_defaults) {
    if (isset($current_settings[$section]) && is_array($current_settings[$section])) {
        // Merge existing settings with defaults (defaults fill in missing keys)
        $fixed_settings[$section] = array_merge($section_defaults, $current_settings[$section]);
    } else {
        // Section doesn't exist, use all defaults
        $fixed_settings[$section] = $section_defaults;
    }
}

// Save fixed settings
$result = update_option('woow_admin_settings', $fixed_settings);

// Clear all caches
delete_transient('woow_admin_css');
delete_transient('woow_admin_css_hash');
wp_cache_flush();

echo "<h1>🔧 Settings Fixed</h1>";

if ($result) {
    echo "<p style='color: green; font-weight: bold;'>✅ Settings updated successfully!</p>";
} else {
    echo "<p style='color: orange; font-weight: bold;'>⚠️ Settings were already correct or update failed</p>";
}

echo "<h2>Fixed Sections:</h2>";
echo "<ul>";
foreach ($defaults as $section => $section_defaults) {
    $before_count = isset($current_settings[$section]) ? count($current_settings[$section]) : 0;
    $after_count = count($fixed_settings[$section]);
    $added = $after_count - $before_count;
    
    if ($added > 0) {
        echo "<li><strong>{$section}:</strong> Added {$added} missing settings (now has {$after_count} settings)</li>";
    } else {
        echo "<li><strong>{$section}:</strong> Already complete ({$after_count} settings)</li>";
    }
}
echo "</ul>";

echo "<h2>Admin Menu Settings:</h2>";
echo "<pre>";
print_r($fixed_settings['admin_menu']);
echo "</pre>";

echo "<hr>";
echo "<h2>Next Steps:</h2>";
echo "<ol>";
echo "<li>Hard refresh browser (Ctrl+Shift+R)</li>";
echo "<li>Visit any admin page</li>";
echo "<li>Check if admin menu looks correct</li>";
echo "</ol>";

echo "<p><a href='diagnose.php' style='padding: 10px 20px; background: #6366f1; color: white; text-decoration: none; border-radius: 8px; display: inline-block; margin: 10px 10px 10px 0;'>Run Diagnostics Again</a></p>";
echo "<p><a href='/wp-admin/' style='padding: 10px 20px; background: #10b981; color: white; text-decoration: none; border-radius: 8px; display: inline-block;'>Go to Dashboard</a></p>";
