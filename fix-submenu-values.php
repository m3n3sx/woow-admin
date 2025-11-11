<?php
/**
 * Fix submenu values - add units to old values
 * 
 * Run once: http://localhost:10004/wp-content/plugins/woow-admin/fix-submenu-values.php
 */

// Load WordPress
require_once('../../../wp-load.php');

// Get current settings
$settings = get_option('woow_admin_settings', array());

echo "<h2>Fixing submenu values...</h2>";

// Fix admin_bar submenu values
if (isset($settings['admin_bar'])) {
    $changed = false;
    
    // Fix submenu_border_radius
    if (isset($settings['admin_bar']['submenu_border_radius'])) {
        $old = $settings['admin_bar']['submenu_border_radius'];
        if (!preg_match('/px|rem|em|%$/', $old)) {
            $settings['admin_bar']['submenu_border_radius'] = $old . 'px';
            echo "✅ Fixed submenu_border_radius: '{$old}' → '{$old}px'<br>";
            $changed = true;
        } else {
            echo "✓ submenu_border_radius already has unit: {$old}<br>";
        }
    }
    
    // Fix submenu_font_size
    if (isset($settings['admin_bar']['submenu_font_size'])) {
        $old = $settings['admin_bar']['submenu_font_size'];
        if (!preg_match('/px|rem|em|%$/', $old)) {
            $settings['admin_bar']['submenu_font_size'] = $old . 'px';
            echo "✅ Fixed submenu_font_size: '{$old}' → '{$old}px'<br>";
            $changed = true;
        } else {
            echo "✓ submenu_font_size already has unit: {$old}<br>";
        }
    }
    
    if ($changed) {
        update_option('woow_admin_settings', $settings);
        echo "<br><strong>✅ Settings updated in database!</strong><br>";
    } else {
        echo "<br><strong>✓ No changes needed</strong><br>";
    }
} else {
    echo "❌ No admin_bar settings found<br>";
}

// Clear cache
delete_transient('woow_generated_css');
delete_option('woow_css_cache');
echo "<br>✅ Cache cleared<br>";

echo "<br><a href='/wp-admin/admin.php?page=woow-admin'>← Back to WOOW! Admin</a>";
