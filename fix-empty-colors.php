<?php
/**
 * Fix empty color values in settings
 */

// Load WordPress
$wp_load = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/wp-load.php';
if ( file_exists( $wp_load ) ) {
    require_once $wp_load;
} else {
    die( "Error: Cannot find wp-load.php\n" );
}

echo "🎨 Fixing empty color values...\n\n";

// Get current settings
$settings = get_option( 'woow_settings', array() );

// Default colors for each section
$default_colors = array(
    'admin_bar' => array(
        'background_color' => '#1d2327',
        'text_color' => '#ffffff',
        'hover_bg_color' => 'rgba(255,255,255,0.1)',
        'hover_text_color' => '#ffffff',
        'gradient_start' => '#6366f1',
        'gradient_end' => '#8b5cf6',
    ),
    'admin_menu' => array(
        'background_color' => '#1d2327',
        'hover_bg_color' => 'rgba(99,102,241,0.05)',
        'active_gradient_start' => '#6366f1',
        'active_gradient_end' => '#8b5cf6',
    ),
    'buttons' => array(
        'primary_bg' => '#6366f1',
        'primary_text' => '#ffffff',
        'secondary_bg' => '#ffffff',
        'secondary_text' => '#6366f1',
        'secondary_border' => '#e2e8f0',
        'destructive_bg' => '#ef4444',
        'destructive_text' => '#ffffff',
    ),
    'form_controls' => array(
        'background_color' => '#ffffff',
        'border_color' => '#e2e8f0',
        'text_color' => '#0f172a',
        'focus_ring_color' => '#6366f1',
    ),
    'dashboard_widgets' => array(
        'background_color' => '#ffffff',
        'header_text_color' => '#0f172a',
    ),
);

$fixed_count = 0;

foreach ( $default_colors as $section => $colors ) {
    if ( ! isset( $settings[$section] ) ) {
        $settings[$section] = array();
    }
    
    foreach ( $colors as $key => $default_value ) {
        if ( empty( $settings[$section][$key] ) ) {
            $settings[$section][$key] = $default_value;
            echo "✅ Fixed {$section}.{$key} = {$default_value}\n";
            $fixed_count++;
        }
    }
}

if ( $fixed_count > 0 ) {
    // Save settings
    update_option( 'woow_settings', $settings );
    echo "\n✅ Fixed {$fixed_count} empty color values!\n";
    
    // Clear cache
    global $wpdb;
    $wpdb->query(
        "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_woow_%' OR option_name LIKE '_transient_timeout_woow_%'"
    );
    
    if ( function_exists( 'wp_cache_flush' ) ) {
        wp_cache_flush();
    }
    
    echo "✅ Cache cleared!\n";
} else {
    echo "✅ No empty colors found!\n";
}

echo "\n✅ DONE! Refresh your browser.\n";
