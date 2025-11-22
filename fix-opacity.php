<?php
/**
 * Fix Opacity for Content Styling
 * 
 * This script increases wpbody_content_opacity from 0.2 to 0.9
 * so the glassmorphism effect is more visible.
 */

// Load WordPress
require_once '../../../wp-load.php';

if ( ! current_user_can( 'manage_options' ) ) {
    die( 'Access denied.' );
}

echo '<h1>Fix Opacity</h1>';
echo '<style>body { font-family: monospace; padding: 20px; }</style>';

// Get settings
$settings = get_option( 'woow_admin_settings', array() );

echo '<h2>Before:</h2>';
echo '<pre>';
echo 'wpbody_content_opacity: ' . ( $settings['content_styling']['wpbody_content_opacity'] ?? 'NOT SET' ) . "\n";
echo 'dashboard_widgets opacity: ' . ( $settings['dashboard_widgets']['opacity'] ?? 'NOT SET' ) . "\n";
echo '</pre>';

// Update opacity
$settings['content_styling']['wpbody_content_opacity'] = 0.9;

// Save
$result = update_option( 'woow_admin_settings', $settings );

echo '<h2>After:</h2>';
echo '<pre>';
echo 'wpbody_content_opacity: ' . $settings['content_styling']['wpbody_content_opacity'] . "\n";
echo 'Update result: ' . ( $result ? 'SUCCESS' : 'FAILED (or no change)' ) . "\n";
echo '</pre>';

// Clear cache
if ( function_exists( 'wp_cache_flush' ) ) {
    wp_cache_flush();
    echo '<p>✓ Cache cleared</p>';
}

echo '<p><strong>Now refresh dashboard (Ctrl+Shift+R) and check if glassmorphism is visible!</strong></p>';
echo '<p><a href="' . admin_url() . '">← Go to Dashboard</a></p>';
