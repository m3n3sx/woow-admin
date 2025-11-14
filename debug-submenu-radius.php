<?php
/**
 * Debug Submenu Border Radius
 */

require_once '../../../wp-load.php';

if (!current_user_can('manage_options')) {
    die('Access denied');
}

echo '<h1>Submenu Border Radius Debug</h1>';
echo '<style>body { font-family: monospace; padding: 20px; } pre { background: #f5f5f5; padding: 10px; }</style>';

// Get current settings
$settings = get_option('woow_settings', []);
$admin_bar = $settings['admin_bar'] ?? [];

echo '<h2>Admin Bar Settings:</h2>';
echo '<pre>';
echo 'submenu_border_radius: ' . var_export($admin_bar['submenu_border_radius'] ?? 'NOT SET', true) . "\n";
echo 'submenu_inherit_styles: ' . var_export($admin_bar['submenu_inherit_styles'] ?? 'NOT SET', true) . "\n";
echo 'border_radius_all: ' . var_export($admin_bar['border_radius_all'] ?? 'NOT SET', true) . "\n";
echo 'border_radius_mode: ' . var_export($admin_bar['border_radius_mode'] ?? 'NOT SET', true) . "\n";
echo '</pre>';

// Test CSS generation
echo '<h2>Generated CSS (submenu section):</h2>';
if (class_exists('WOOW_CSS_Generator') && class_exists('WOOW_Settings')) {
    try {
        $woow_settings = new WOOW_Settings();
        $generator = new WOOW_CSS_Generator($woow_settings);
        $css = $generator->generate();
        
        // Extract submenu section
        $start = strpos($css, '#wpadminbar .menupop .ab-sub-wrapper');
        if ($start !== false) {
            $end = strpos($css, '}', $start) + 1;
            $submenu_css = substr($css, $start, $end - $start);
            echo '<pre>' . htmlspecialchars($submenu_css) . '</pre>';
        } else {
            echo '<p style="color: red;">Submenu CSS not found!</p>';
        }
    } catch (Exception $e) {
        echo '<p style="color: red;">Error: ' . $e->getMessage() . '</p>';
    }
}

echo '<hr>';
echo '<p><strong>Actions:</strong></p>';
echo '<p><a href="?reset_submenu=1" style="padding: 10px; background: #0073aa; color: white; text-decoration: none; border-radius: 5px;">Reset submenu_border_radius to 12</a></p>';
echo '<p><a href="?reset_all=1" style="padding: 10px; background: #dc3232; color: white; text-decoration: none; border-radius: 5px;">Reset ALL admin_bar settings to defaults</a></p>';

if (isset($_GET['reset_submenu'])) {
    if (empty($settings['admin_bar'])) {
        $settings['admin_bar'] = [];
    }
    $settings['admin_bar']['submenu_border_radius'] = '12';
    update_option('woow_settings', $settings);
    
    // Clear cache
    delete_transient('woow_generated_css');
    
    echo '<p style="color: green; font-weight: bold;">✓ Reset submenu_border_radius to 12! <a href="' . admin_url('admin.php?page=woow-admin') . '">Go to WOOW Admin</a></p>';
}

if (isset($_GET['reset_all'])) {
    // Load defaults
    require_once __DIR__ . '/includes/defaults.php';
    $defaults = woow_get_default_settings();
    $settings['admin_bar'] = $defaults['admin_bar'];
    update_option('woow_settings', $settings);
    
    // Clear cache
    delete_transient('woow_generated_css');
    
    echo '<p style="color: green; font-weight: bold;">✓ Reset ALL admin_bar settings! <a href="' . admin_url('admin.php?page=woow-admin') . '">Go to WOOW Admin</a></p>';
}
