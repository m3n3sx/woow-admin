<?php
/**
 * Test Dashboard Widgets Glass Style CSS Generation
 * 
 * This script tests if Glass Style correctly generates transparent background for dashboard widgets.
 * 
 * Usage:
 * 1. Place in plugin root: wp-content/plugins/woow-admin/
 * 2. Access: http://yoursite.com/wp-content/plugins/woow-admin/test-dashboard-glass-css.php
 */

// Load WordPress
require_once '../../../wp-load.php';

// Check if user is admin
if ( ! current_user_can( 'manage_options' ) ) {
    die( 'Access denied. You must be an administrator.' );
}

echo '<h1>Dashboard Widgets Glass Style CSS Test</h1>';
echo '<style>
body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; padding: 20px; }
.test-section { background: #f0f0f0; padding: 15px; margin: 20px 0; border-radius: 8px; }
.success { color: #0a0; font-weight: bold; }
.error { color: #d00; font-weight: bold; }
.code { background: #fff; padding: 10px; border: 1px solid #ddd; border-radius: 4px; overflow-x: auto; }
pre { margin: 0; white-space: pre-wrap; }
</style>';

// Get settings
$settings = get_option( 'woow_admin_settings', array() );
$general = $settings['general'] ?? array();
$widgets = $settings['dashboard_widgets'] ?? array();

echo '<div class="test-section">';
echo '<h2>1. Current Settings</h2>';
echo '<div class="code"><pre>';
echo 'Glass Style (global): ' . ( $general['glass_style'] ?? 'not set' ) . "\n";
echo 'Dashboard Widgets glassmorphism (local): ' . ( $widgets['glassmorphism'] ?? 'not set' ) . "\n";
echo 'Dashboard Widgets background_color: ' . ( $widgets['background_color'] ?? 'not set' ) . "\n";
echo 'Dashboard Widgets opacity: ' . ( $widgets['opacity'] ?? 'not set' ) . "\n";
echo 'Dashboard Widgets blur_strength: ' . ( $widgets['blur_strength'] ?? 'not set' ) . "\n";
echo '</pre></div>';
echo '</div>';

// Test CSS generation
require_once __DIR__ . '/includes/class-woow-settings.php';
require_once __DIR__ . '/includes/class-woow-css-generator.php';

$settings_obj = new WOOW_Settings();
$css_generator = new WOOW_CSS_Generator( $settings_obj );

// Generate CSS
$css = $css_generator->generate();

// Extract dashboard widgets CSS
preg_match( '/\/\* Dashboard Widgets Styling \*\/(.*?)(?=\/\*|$)/s', $css, $matches );
$dashboard_css = $matches[1] ?? 'NOT FOUND';

echo '<div class="test-section">';
echo '<h2>2. Generated CSS for Dashboard Widgets</h2>';
echo '<div class="code"><pre>';
if ( $dashboard_css === 'NOT FOUND' ) {
    echo '<span class="error">NOT FOUND - Showing first 2000 chars of full CSS:</span>' . "\n\n";
    echo htmlspecialchars( substr( $css, 0, 2000 ) );
} else {
    echo htmlspecialchars( trim( $dashboard_css ) );
}
echo '</pre></div>';
echo '</div>';

// Check if glassmorphism is applied
$glass_style = $general['glass_style'] ?? false;
$local_glassmorphism = $widgets['glassmorphism'] ?? false;
$apply_glassmorphism = $glass_style || $local_glassmorphism;

echo '<div class="test-section">';
echo '<h2>3. Glassmorphism Logic Check</h2>';
echo '<div class="code"><pre>';
echo 'glass_style (global): ' . ( $glass_style ? 'TRUE' : 'FALSE' ) . "\n";
echo 'glassmorphism (local): ' . ( $local_glassmorphism ? 'TRUE' : 'FALSE' ) . "\n";
echo 'apply_glassmorphism: ' . ( $apply_glassmorphism ? 'TRUE' : 'FALSE' ) . "\n";
echo '</pre></div>';
echo '</div>';

// Check if background is transparent
$has_rgba = strpos( $dashboard_css, 'rgba(' ) !== false;
$has_backdrop_filter = strpos( $dashboard_css, 'backdrop-filter' ) !== false;

echo '<div class="test-section">';
echo '<h2>4. CSS Analysis</h2>';
echo '<div class="code"><pre>';
echo 'Contains rgba() (transparent): ' . ( $has_rgba ? '<span class="success">YES ✓</span>' : '<span class="error">NO ✗</span>' ) . "\n";
echo 'Contains backdrop-filter (blur): ' . ( $has_backdrop_filter ? '<span class="success">YES ✓</span>' : '<span class="error">NO ✗</span>' ) . "\n";
echo '</pre></div>';
echo '</div>';

// Expected vs Actual
echo '<div class="test-section">';
echo '<h2>5. Expected Behavior</h2>';
echo '<div class="code"><pre>';
if ( $apply_glassmorphism ) {
    echo '<span class="success">Glassmorphism SHOULD be applied</span>' . "\n";
    echo 'Expected: background with rgba() and backdrop-filter' . "\n";
    echo 'Actual: ' . ( $has_rgba && $has_backdrop_filter ? '<span class="success">CORRECT ✓</span>' : '<span class="error">INCORRECT ✗</span>' ) . "\n";
} else {
    echo '<span class="error">Glassmorphism should NOT be applied</span>' . "\n";
    echo 'Expected: solid background without backdrop-filter' . "\n";
    echo 'Actual: ' . ( ! $has_backdrop_filter ? '<span class="success">CORRECT ✓</span>' : '<span class="error">INCORRECT ✗</span>' ) . "\n";
}
echo '</pre></div>';
echo '</div>';

// Recommendations
echo '<div class="test-section">';
echo '<h2>6. Recommendations</h2>';
echo '<div class="code"><pre>';
if ( $apply_glassmorphism && ! ( $has_rgba && $has_backdrop_filter ) ) {
    echo '<span class="error">⚠️ PROBLEM DETECTED</span>' . "\n\n";
    echo 'Glass Style is enabled but CSS is not generated correctly.' . "\n";
    echo 'Possible causes:' . "\n";
    echo '1. CSS cache not cleared' . "\n";
    echo '2. Settings not saved properly' . "\n";
    echo '3. CSS generation logic error' . "\n\n";
    echo 'Try:' . "\n";
    echo '1. Clear cache: ./cc.sh' . "\n";
    echo '2. Re-save settings in WOOW Admin' . "\n";
    echo '3. Hard refresh browser (Ctrl+Shift+R)' . "\n";
} elseif ( ! $apply_glassmorphism && $has_backdrop_filter ) {
    echo '<span class="error">⚠️ PROBLEM DETECTED</span>' . "\n\n";
    echo 'Glass Style is disabled but glassmorphism CSS is still present.' . "\n";
    echo 'Try clearing cache: ./cc.sh' . "\n";
} else {
    echo '<span class="success">✓ Everything looks correct!</span>' . "\n\n";
    if ( $apply_glassmorphism ) {
        echo 'Glassmorphism is enabled and CSS is generated correctly.' . "\n";
        echo 'If widgets still look solid, check:' . "\n";
        echo '1. Browser cache (Ctrl+Shift+R)' . "\n";
        echo '2. CSS specificity conflicts' . "\n";
        echo '3. Other plugins overriding styles' . "\n";
    } else {
        echo 'Glassmorphism is disabled and CSS is correct.' . "\n";
    }
}
echo '</pre></div>';
echo '</div>';

echo '<p><a href="' . admin_url( 'admin.php?page=woow-admin' ) . '">← Back to WOOW Admin</a></p>';
