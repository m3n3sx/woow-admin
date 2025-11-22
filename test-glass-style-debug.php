<?php
/**
 * Debug Glass Style Settings
 */

// Load WordPress
require_once '../../../wp-load.php';

if ( ! current_user_can( 'manage_options' ) ) {
    die( 'Access denied.' );
}

echo '<h1>Glass Style Debug</h1>';
echo '<style>
body { font-family: monospace; padding: 20px; background: #f0f0f0; }
.section { background: white; padding: 15px; margin: 10px 0; border-radius: 8px; }
.success { color: green; font-weight: bold; }
.error { color: red; font-weight: bold; }
pre { background: #f8f8f8; padding: 10px; border: 1px solid #ddd; overflow-x: auto; }
</style>';

// Get settings
$settings = get_option( 'woow_admin_settings', array() );

echo '<div class="section">';
echo '<h2>1. Raw Settings from Database</h2>';
echo '<pre>';
print_r( $settings );
echo '</pre>';
echo '</div>';

echo '<div class="section">';
echo '<h2>2. General Section</h2>';
echo '<pre>';
print_r( $settings['general'] ?? 'NOT SET' );
echo '</pre>';
echo '</div>';

echo '<div class="section">';
echo '<h2>3. Glass Style Value</h2>';
$glass_style = $settings['general']['glass_style'] ?? null;
echo '<p>Value: <strong>' . var_export( $glass_style, true ) . '</strong></p>';
echo '<p>Type: <strong>' . gettype( $glass_style ) . '</strong></p>';
echo '<p>Is TRUE: ' . ( $glass_style === true ? '<span class="success">YES</span>' : '<span class="error">NO</span>' ) . '</p>';
echo '<p>Is truthy: ' . ( $glass_style ? '<span class="success">YES</span>' : '<span class="error">NO</span>' ) . '</p>';
echo '</div>';

echo '<div class="section">';
echo '<h2>4. Body Class Test</h2>';

// Simulate what add_glassmorphism_body_class does
$classes = '';
if ( isset( $settings['general']['glass_style'] ) && $settings['general']['glass_style'] ) {
    $classes .= ' woow-glass-enabled';
    echo '<p class="success">✓ Class WOULD be added: "woow-glass-enabled"</p>';
} else {
    echo '<p class="error">✗ Class would NOT be added</p>';
    echo '<p>Reason: ';
    if ( ! isset( $settings['general']['glass_style'] ) ) {
        echo 'glass_style is NOT SET';
    } elseif ( ! $settings['general']['glass_style'] ) {
        echo 'glass_style is FALSE or falsy (value: ' . var_export( $settings['general']['glass_style'], true ) . ')';
    }
    echo '</p>';
}
echo '<p>Classes: "<strong>' . trim( $classes ) . '</strong>"</p>';
echo '</div>';

echo '<div class="section">';
echo '<h2>5. Check Actual Body Class</h2>';
echo '<p>Open browser console and run:</p>';
echo '<pre>document.body.className</pre>';
echo '<p>Should contain: <strong>woow-glass-enabled</strong></p>';
echo '</div>';

echo '<div class="section">';
echo '<h2>6. Dashboard Widgets Settings</h2>';
echo '<pre>';
print_r( $settings['dashboard_widgets'] ?? 'NOT SET' );
echo '</pre>';
echo '</div>';

echo '<div class="section">';
echo '<h2>7. Content Styling Settings</h2>';
echo '<pre>';
print_r( $settings['content_styling'] ?? 'NOT SET' );
echo '</pre>';
echo '</div>';

echo '<p><a href="' . admin_url( 'admin.php?page=woow-admin' ) . '">← Back to WOOW Admin</a></p>';
