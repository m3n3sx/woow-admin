<?php
/**
 * WOOW_CSS_Generator Class
 *
 * Dynamically generates CSS from settings with <100ms performance target.
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * CSS Generator Class
 */
class WOOW_CSS_Generator {
    /**
     * Settings manager instance
     *
     * @var WOOW_Settings
     */
    private WOOW_Settings $settings;

    /**
     * Accumulated CSS string
     *
     * @var string
     */
    private string $css = '';

    /**
     * CSS generation time in milliseconds
     *
     * @var float
     */
    private float $generation_time = 0.0;

    /**
     * Constructor
     *
     * @param WOOW_Settings $settings Settings manager instance
     */
    public function __construct( WOOW_Settings $settings ) {
        $this->settings = $settings;
    }

    /**
     * Generate complete CSS from settings
     *
     * @return string Generated CSS
     */
    public function generate(): string {
        $start = microtime( true );
        $this->css = '';

        // Generate CSS sections
        $this->add_css_variables();
        
        if ( $this->settings->get_option( 'admin_bar.enabled', true ) ) {
            $this->add_admin_bar_styles();
        }
        
        if ( $this->settings->get_option( 'admin_menu.enabled', true ) ) {
            $this->add_admin_menu_styles();
        }
        
        if ( $this->settings->get_option( 'dashboard_widgets.enabled', true ) ) {
            $this->add_dashboard_widget_styles();
        }
        
        if ( $this->settings->get_option( 'form_controls.enabled', true ) ) {
            $this->add_form_control_styles();
        }
        
        if ( $this->settings->get_option( 'buttons.enabled', true ) ) {
            $this->add_button_styles();
        }
        
        if ( $this->settings->get_option( 'backgrounds.enabled', true ) ) {
            $this->add_background_styles();
        }
        
        if ( $this->settings->get_option( 'typography.enabled', true ) ) {
            $this->add_typography_styles();
        }
        
        if ( $this->settings->get_option( 'effects.enabled', true ) ) {
            $this->add_effect_styles();
        }
        
        // Add responsive styles
        $this->add_responsive_styles();

        // Minify in production
        if ( defined( 'WP_DEBUG' ) && ! WP_DEBUG ) {
            $this->css = $this->minify( $this->css );
        }

        $this->generation_time = ( microtime( true ) - $start ) * 1000;

        return $this->css;
    }

    /**
     * Get generation metrics
     *
     * @return array Metrics including generation time and CSS size
     */
    public function get_metrics(): array {
        return [
            'generation_time' => round( $this->generation_time, 2 ),
            'css_size' => strlen( $this->css ),
            'css_size_kb' => round( strlen( $this->css ) / 1024, 2 ),
        ];
    }

    /**
     * Add CSS variables to :root
     *
     * @return void
     */
    private function add_css_variables(): void {
        $admin_bar = $this->settings->get_section( 'admin_bar' );
        $admin_menu = $this->settings->get_section( 'admin_menu' );
        $buttons = $this->settings->get_section( 'buttons' );
        $form_controls = $this->settings->get_section( 'form_controls' );
        $effects = $this->settings->get_section( 'effects' );

        $this->css .= ":root {\n";
        
        // Color variables
        $this->css .= "    --woow-primary: {$buttons['primary_bg']};\n";
        $this->css .= "    --woow-primary-text: {$buttons['primary_text']};\n";
        $this->css .= "    --woow-secondary: {$buttons['secondary_bg']};\n";
        $this->css .= "    --woow-secondary-text: {$buttons['secondary_text']};\n";
        $this->css .= "    --woow-destructive: {$buttons['destructive_bg']};\n";
        $this->css .= "    --woow-destructive-text: {$buttons['destructive_text']};\n";
        $this->css .= "    --woow-border: {$form_controls['border_color']};\n";
        $this->css .= "    --woow-focus-ring: {$form_controls['focus_ring_color']};\n";
        
        // Spacing variables
        $this->css .= "    --woow-spacing-xs: 4px;\n";
        $this->css .= "    --woow-spacing-sm: 8px;\n";
        $this->css .= "    --woow-spacing-md: 16px;\n";
        $this->css .= "    --woow-spacing-lg: 24px;\n";
        $this->css .= "    --woow-spacing-xl: 32px;\n";
        $this->css .= "    --woow-spacing-2xl: 48px;\n";
        
        // Border radius variables
        $this->css .= "    --woow-radius-sm: 8px;\n";
        $this->css .= "    --woow-radius-md: 12px;\n";
        $this->css .= "    --woow-radius-lg: 16px;\n";
        $this->css .= "    --woow-radius-xl: 24px;\n";
        
        // Shadow variables
        $this->css .= "    --woow-shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1);\n";
        $this->css .= "    --woow-shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);\n";
        $this->css .= "    --woow-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);\n";
        $this->css .= "    --woow-shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);\n";
        
        // Animation variables
        $this->css .= "    --woow-transition-speed: {$effects['animation_duration']};\n";
        $this->css .= "    --woow-easing: {$effects['easing_function']};\n";
        
        $this->css .= "}\n\n";
    }

    /**
     * Add admin bar styles
     *
     * @return void
     */
    private function add_admin_bar_styles(): void {
        $bar = $this->settings->get_section( 'admin_bar' );
        
        // Get settings with defaults
        $height = $bar['height'] ?? '48px';
        $width = $bar['width'] ?? '100';
        $width_unit = $bar['width_unit'] ?? '%';
        $top_offset = $bar['top_offset'] ?? '16px';
        
        // Border Radius - handle mode (all or individual)
        $border_radius_mode = $bar['border_radius_mode'] ?? 'all';
        if ( $border_radius_mode === 'all' ) {
            $border_radius_all = $bar['border_radius_all'] ?? '24';
            $border_radius = $border_radius_all . 'px';
        } else {
            // Individual corners
            $border_radius_top_left = $bar['border_radius_top_left'] ?? '24';
            $border_radius_top_right = $bar['border_radius_top_right'] ?? '24';
            $border_radius_bottom_right = $bar['border_radius_bottom_right'] ?? '24';
            $border_radius_bottom_left = $bar['border_radius_bottom_left'] ?? '24';
            $border_radius = "{$border_radius_top_left}px {$border_radius_top_right}px {$border_radius_bottom_right}px {$border_radius_bottom_left}px";
        }
        
        // Get position and shadow settings
        $position = $bar['position'] ?? 'fixed';
        $shadow_style = $bar['shadow_style'] ?? 'md';
        
        // Shadow styles
        $shadows = array(
            'none' => 'none',
            'sm' => '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
            'md' => '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
            'lg' => '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
            'xl' => '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
            '2xl' => '0 25px 50px -12px rgba(0, 0, 0, 0.25)',
        );
        $box_shadow = $shadows[ $shadow_style ] ?? $shadows['md'];
        
        // Margin/Offset (from browser edges)
        $margin_mode = $bar['margin_mode'] ?? 'all';
        if ( $margin_mode === 'all' ) {
            $margin_all = $bar['margin_all'] ?? '16';
            $margin_top = $margin_all;
            $margin_right = $margin_all;
            $margin_bottom = $margin_all;
            $margin_left = $margin_all;
        } else {
            $margin_top = $bar['margin_top'] ?? '16';
            $margin_right = $bar['margin_right'] ?? '16';
            $margin_bottom = $bar['margin_bottom'] ?? '16';
            $margin_left = $bar['margin_left'] ?? '16';
        }
        
        // Calculate width with unit and margins
        $full_width = $width . $width_unit;
        
        // Calculate final positioning
        if ( $width_unit === '%' ) {
            // Percentage width: use margins directly
            $final_width = $full_width;
            $final_left = $margin_left . 'px';
            $final_right = $margin_right . 'px';
        } elseif ( $width_unit === 'px' ) {
            // Pixel width: center with margins
            $final_width = $full_width;
            $final_left = "calc((100% - {$full_width}) / 2 + {$margin_left}px)";
            $final_right = "calc((100% - {$full_width}) / 2 + {$margin_right}px)";
        } else {
            $final_width = '100%';
            $final_left = $margin_left . 'px';
            $final_right = $margin_right . 'px';
        }
        
        $this->css .= "/* Admin Bar Styling - Customizable */\n";
        $this->css .= "#wpadminbar {\n";
        $this->css .= "    /* Position and spacing */\n";
        $this->css .= "    position: {$position} !important;\n";
        $this->css .= "    top: {$margin_top}px !important;\n";
        $this->css .= "    left: {$final_left} !important;\n";
        $this->css .= "    right: {$final_right} !important;\n";
        $this->css .= "    bottom: auto !important;\n";
        $this->css .= "    width: {$final_width} !important;\n";
        $this->css .= "    max-width: calc(100vw - {$margin_left}px - {$margin_right}px) !important;\n";
        $this->css .= "    height: {$height} !important;\n";
        $this->css .= "    margin: 0 !important;\n";
        $this->css .= "    box-sizing: border-box !important;\n";
        $this->css .= "    z-index: 99999 !important;\n";
        $this->css .= "    \n";
        $this->css .= "    /* Flexbox for vertical centering */\n";
        $this->css .= "    display: flex !important;\n";
        $this->css .= "    align-items: center !important;\n";
        $this->css .= "    flex-wrap: nowrap !important;\n";
        $this->css .= "    \n";
        // Spacing/Padding
        $spacing_mode = $bar['spacing_mode'] ?? 'all';
        if ( $spacing_mode === 'all' ) {
            $spacing_all = $bar['spacing_all'] ?? '24';
            $padding = "0 {$spacing_all}px";
        } else {
            $spacing_top = $bar['spacing_top'] ?? '0';
            $spacing_right = $bar['spacing_right'] ?? '24';
            $spacing_bottom = $bar['spacing_bottom'] ?? '0';
            $spacing_left = $bar['spacing_left'] ?? '24';
            $padding = "{$spacing_top}px {$spacing_right}px {$spacing_bottom}px {$spacing_left}px";
        }
        
        $this->css .= "    /* Visual styling */\n";
        $this->css .= "    /* Spacing mode: {$spacing_mode}, Padding: {$padding} */\n";
        $this->css .= "    border-radius: {$border_radius} !important;\n";
        $this->css .= "    box-shadow: {$box_shadow} !important;\n";
        $this->css .= "    padding: {$padding} !important;\n";
        
        // Background based on type
        $background_type = $bar['background_type'] ?? 'solid';
        $opacity = $bar['opacity'] ?? 0.9;
        $blur_strength = $bar['blur_strength'] ?? '12px';
        $glassmorphism_enabled = $bar['glassmorphism'] ?? true;
        
        // Apply background based on selected type
        if ( $background_type === 'glass' ) {
            // Glassmorphism: transparent background + blur
            $bg_rgba = $this->hex_to_rgba( $bar['background_color'], $opacity );
            $this->css .= "    background: {$bg_rgba} !important;\n";
            $this->css .= "    backdrop-filter: blur({$blur_strength}) !important;\n";
            $this->css .= "    -webkit-backdrop-filter: blur({$blur_strength}) !important;\n";
        } elseif ( $background_type === 'gradient' ) {
            // Gradient background (solid, no transparency)
            $this->css .= "    background: linear-gradient(to right, {$bar['gradient_start']}, {$bar['gradient_end']}) !important;\n";
        } else {
            // Solid background (default)
            $this->css .= "    background: {$bar['background_color']} !important;\n";
        }
        
        $this->css .= "}\n\n";
        
        // Adjust body padding to account for floating admin bar
        $this->css .= "body.admin-bar {\n";
        $this->css .= "    padding-top: 80px !important;\n";
        $this->css .= "}\n\n";
        
        $this->css .= "#wpcontent,\n";
        $this->css .= "#wpbody {\n";
        $this->css .= "    padding-top: 0 !important;\n";
        $this->css .= "}\n\n";
        
        // Prevent horizontal overflow
        $this->css .= "html,\n";
        $this->css .= "body {\n";
        $this->css .= "    overflow-x: hidden !important;\n";
        $this->css .= "    max-width: 100vw !important;\n";
        $this->css .= "}\n\n";
        
        // Admin bar toolbar - main flex container
        $this->css .= "#wpadminbar #wp-toolbar {\n";
        $this->css .= "    display: flex !important;\n";
        $this->css .= "    align-items: center !important;\n";
        $this->css .= "    justify-content: space-between !important;\n";
        $this->css .= "    width: 100% !important;\n";
        $this->css .= "    height: 100% !important;\n";
        $this->css .= "}\n\n";
        
        // Admin bar top level containers - ensure vertical centering
        $this->css .= "#wpadminbar .ab-top-menu {\n";
        $this->css .= "    display: flex !important;\n";
        $this->css .= "    align-items: center !important;\n";
        $this->css .= "    height: 100% !important;\n";
        $this->css .= "}\n\n";
        
        $this->css .= "#wpadminbar .ab-top-secondary {\n";
        $this->css .= "    display: flex !important;\n";
        $this->css .= "    align-items: center !important;\n";
        $this->css .= "    height: 100% !important;\n";
        $this->css .= "}\n\n";
        
        // Admin bar list items
        $this->css .= "#wpadminbar > #wp-toolbar > ul > li {\n";
        $this->css .= "    display: flex !important;\n";
        $this->css .= "    align-items: center !important;\n";
        $this->css .= "    height: 100% !important;\n";
        $this->css .= "}\n\n";
        
        // Admin bar items
        $this->css .= "#wpadminbar .ab-item {\n";
        $this->css .= "    color: {$bar['text_color']} !important;\n";
        $this->css .= "    font-size: {$bar['font_size']} !important;\n";
        $this->css .= "    font-weight: {$bar['font_weight']} !important;\n";
        $this->css .= "    padding: 0 16px !important;\n";
        $this->css .= "    height: 100% !important;\n";
        $this->css .= "    display: flex !important;\n";
        $this->css .= "    align-items: center !important;\n";
        $this->css .= "    transition: background 200ms var(--woow-easing), color 200ms var(--woow-easing);\n";
        $this->css .= "}\n\n";
        
        // Admin bar label and icons - vertical center + color
        $this->css .= "#wpadminbar .ab-label,\n";
        $this->css .= "#wpadminbar .ab-icon {\n";
        $this->css .= "    display: flex !important;\n";
        $this->css .= "    align-items: center !important;\n";
        $this->css .= "    color: {$bar['text_color']} !important;\n";
        $this->css .= "}\n\n";
        
        // Admin bar icon before pseudo-elements (dashicons)
        $this->css .= "#wpadminbar .ab-icon:before,\n";
        $this->css .= "#wpadminbar .ab-item:before {\n";
        $this->css .= "    color: {$bar['text_color']} !important;\n";
        $this->css .= "}\n\n";
        
        // Admin bar empty items (like separators)
        $this->css .= "#wpadminbar .ab-empty-item {\n";
        $this->css .= "    display: flex !important;\n";
        $this->css .= "    align-items: center !important;\n";
        $this->css .= "    height: 100% !important;\n";
        $this->css .= "}\n\n";
        
        // Hover states
        $hover_style = $bar['hover_style'] ?? 'normal';
        $hover_bg = $bar['hover_bg_color'] ?? 'rgba(255, 255, 255, 0.1)';
        $hover_text = $bar['hover_text_color'] ?? '#ffffff';
        
        if ( $hover_style === 'compact' ) {
            // Compact hover: padding from edges
            $this->css .= "#wpadminbar .ab-item:hover {\n";
            $this->css .= "    background: {$hover_bg} !important;\n";
            $this->css .= "    color: {$hover_text} !important;\n";
            $this->css .= "    border-radius: 12px;\n";
            $this->css .= "    margin: 6px 0 !important;\n";
            $this->css .= "    height: calc(100% - 12px) !important;\n";
            $this->css .= "}\n\n";
        } else {
            // Normal hover: full height
            $this->css .= "#wpadminbar .ab-item:hover {\n";
            $this->css .= "    background: {$hover_bg} !important;\n";
            $this->css .= "    color: {$hover_text} !important;\n";
            $this->css .= "    border-radius: 12px;\n";
            $this->css .= "}\n\n";
        }
        
        // Hover state for icons and labels
        $this->css .= "#wpadminbar .ab-item:hover .ab-icon,\n";
        $this->css .= "#wpadminbar .ab-item:hover .ab-label,\n";
        $this->css .= "#wpadminbar .ab-item:hover .ab-icon:before,\n";
        $this->css .= "#wpadminbar .ab-item:hover:before {\n";
        $this->css .= "    color: {$hover_text} !important;\n";
        $this->css .= "}\n\n";
        
        // WordPress logo
        $this->css .= "#wpadminbar #wp-admin-bar-wp-logo > .ab-item .ab-icon {\n";
        $this->css .= "    width: 32px !important;\n";
        $this->css .= "    height: 32px !important;\n";
        $this->css .= "    background: linear-gradient(to bottom right, #6366f1, #8b5cf6) !important;\n";
        $this->css .= "    border-radius: 8px;\n";
        $this->css .= "    display: flex;\n";
        $this->css .= "    align-items: center;\n";
        $this->css .= "    justify-content: center;\n";
        $this->css .= "}\n\n";
        
        // Submenu styling
        $submenu_inherit = $bar['submenu_inherit_styles'] ?? false;
        
        // Get glassmorphism settings (needed for submenu)
        $background_type = $bar['background_type'] ?? 'solid';
        $glassmorphism_enabled = ( $background_type === 'glass' );
        $opacity = $bar['opacity'] ?? 0.9;
        $blur_strength = $bar['blur_strength'] ?? '12';
        
        if ( $submenu_inherit ) {
            // Inherit from admin bar
            $submenu_bg = $glassmorphism_enabled ? $this->hex_to_rgba( $bar['background_color'], $opacity ) : $bar['background_color'];
            $submenu_text = $bar['text_color'];
            $submenu_hover = $bar['hover_bg_color'];
            
            // Get border radius from admin bar (handle mode)
            $border_radius_mode = $bar['border_radius_mode'] ?? 'all';
            if ( $border_radius_mode === 'all' ) {
                $submenu_radius = $bar['border_radius_all'] ?? '24';
            } else {
                // Use top-left corner for submenu when individual mode
                $submenu_radius = $bar['border_radius_top_left'] ?? '24';
            }
            
            $submenu_font_size = $bar['font_size'] ?? '14px';
        } else {
            // Custom submenu styles
            $submenu_bg = $bar['submenu_bg_color'] ?? 'rgba(255, 255, 255, 0.98)';
            $submenu_text = $bar['submenu_text_color'] ?? '#0f172a';
            $submenu_hover = 'rgba(99, 102, 241, 0.08)';
            $submenu_radius = $bar['submenu_border_radius'] ?? '12';
            $submenu_font_size = ( $bar['submenu_font_size'] ?? '14' ) . 'px';
        }
        
        // Override WordPress default submenu wrapper background
        $this->css .= "#wpadminbar .menupop .ab-sub-wrapper {\n";
        $this->css .= "    position: absolute !important;\n";
        $this->css .= "    top: 100% !important;\n";
        $this->css .= "    margin-top: 3px !important;\n";
        $this->css .= "    background: {$submenu_bg} !important;\n";
        
        if ( $submenu_inherit && $glassmorphism_enabled ) {
            $this->css .= "    backdrop-filter: blur({$blur_strength}px) !important;\n";
            $this->css .= "    -webkit-backdrop-filter: blur({$blur_strength}px) !important;\n";
        } else {
            $this->css .= "    backdrop-filter: blur(12px) !important;\n";
            $this->css .= "    -webkit-backdrop-filter: blur(12px) !important;\n";
        }
        
        $this->css .= "    border-radius: {$submenu_radius}px !important;\n";
        $this->css .= "    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;\n";
        $this->css .= "    border: 1px solid rgba(0, 0, 0, 0.1) !important;\n";
        $this->css .= "    padding: 0 !important;\n";
        $this->css .= "}\n\n";
        
        // Inner submenu should be transparent with padding
        $this->css .= "#wpadminbar .ab-submenu {\n";
        $this->css .= "    background: transparent !important;\n";
        $this->css .= "    border: none !important;\n";
        $this->css .= "    box-shadow: none !important;\n";
        $this->css .= "    padding: 8px !important;\n";
        $this->css .= "    margin: 0 !important;\n";
        $this->css .= "}\n\n";
        
        $this->css .= "#wpadminbar .ab-submenu .ab-item {\n";
        $this->css .= "    color: {$submenu_text} !important;\n";
        $this->css .= "    border-radius: 8px !important;\n";
        $this->css .= "    padding: 8px 12px !important;\n";
        $this->css .= "    font-size: {$submenu_font_size} !important;\n";
        $this->css .= "    transition: all 200ms var(--woow-easing) !important;\n";
        $this->css .= "}\n\n";
        
        $this->css .= "#wpadminbar .ab-submenu .ab-item:hover,\n";
        $this->css .= "#wpadminbar .ab-submenu .ab-item:focus {\n";
        $this->css .= "    background: {$submenu_hover} !important;\n";
        $this->css .= "    color: {$submenu_text} !important;\n";
        $this->css .= "}\n\n";
        
        // Submenu icons color
        $this->css .= "#wpadminbar .ab-submenu .ab-icon:before,\n";
        $this->css .= "#wpadminbar .ab-submenu .ab-item:before {\n";
        $this->css .= "    color: {$submenu_text} !important;\n";
        $this->css .= "}\n\n";
        
        // Submenu separators
        $this->css .= "#wpadminbar .ab-submenu .ab-item.menupop {\n";
        $this->css .= "    border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important;\n";
        $this->css .= "}\n\n";
        
        // Override all submenu-related backgrounds
        $this->css .= "#wpadminbar .menupop > .ab-sub-wrapper,\n";
        $this->css .= "#wpadminbar .ab-top-menu > li.menupop > .ab-sub-wrapper,\n";
        $this->css .= "#wpadminbar .ab-top-secondary .menupop .ab-sub-wrapper {\n";
        $this->css .= "    background: {$submenu_bg} !important;\n";
        $this->css .= "}\n\n";
        
        // Submenu links color consistency
        $this->css .= "#wpadminbar .ab-submenu a,\n";
        $this->css .= "#wpadminbar .menupop .ab-sub-wrapper a {\n";
        $this->css .= "    color: {$submenu_text} !important;\n";
        $this->css .= "}\n\n";
        
        // Submenu hover consistency
        $this->css .= "#wpadminbar .ab-submenu a:hover,\n";
        $this->css .= "#wpadminbar .menupop .ab-sub-wrapper a:hover {\n";
        $this->css .= "    background: {$submenu_hover} !important;\n";
        $this->css .= "    color: {$submenu_text} !important;\n";
        $this->css .= "}\n\n";
        
        // Custom CSS
        if ( ! empty( $bar['custom_css'] ) ) {
            $this->css .= "/* Admin Bar Custom CSS */\n";
            $this->css .= $this->sanitize_css( $bar['custom_css'] ) . "\n\n";
        }
    }

    /**
     * Add admin menu styles
     *
     * @return void
     */
    private function add_admin_menu_styles(): void {
        $menu = $this->settings->get_section( 'admin_menu' );
        
        // Get settings with defaults
        $width = $menu['width'] ?? '256';
        $item_height = $menu['item_height'] ?? '48';
        $background_color = $menu['background_color'] ?? '#ffffff';
        $text_color = $menu['text_color'] ?? '#0f172a';
        $hover_text_color = $menu['hover_text_color'] ?? '#6366f1';
        $hover_bg_color = $menu['hover_bg_color'] ?? '#f8fafc';
        
        // Border Radius - handle mode (all or individual)
        $border_radius_mode = $menu['border_radius_mode'] ?? 'all';
        if ( $border_radius_mode === 'all' ) {
            $border_radius_all = $menu['border_radius_all'] ?? '12';
            $border_radius = $border_radius_all . 'px';
        } else {
            $border_radius_top_left = $menu['border_radius_top_left'] ?? '12';
            $border_radius_top_right = $menu['border_radius_top_right'] ?? '12';
            $border_radius_bottom_right = $menu['border_radius_bottom_right'] ?? '12';
            $border_radius_bottom_left = $menu['border_radius_bottom_left'] ?? '12';
            $border_radius = "{$border_radius_top_left}px {$border_radius_top_right}px {$border_radius_bottom_right}px {$border_radius_bottom_left}px";
        }
        
        $item_border_radius = $menu['item_border_radius'] ?? '12';
        
        // Typography
        $font_size = $menu['font_size'] ?? '14';
        $font_weight = $menu['font_weight'] ?? '600';
        
        // Glassmorphism
        $background_type = $menu['background_type'] ?? 'solid';
        $glassmorphism = $menu['glassmorphism'] ?? true;
        $blur_strength = $menu['blur_strength'] ?? '12';
        $opacity = $menu['opacity'] ?? 0.9;
        
        // Shadow
        $shadow_style = $menu['shadow_style'] ?? 'sm';
        $shadows = [
            'none' => 'none',
            'sm' => '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
            'md' => '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1)',
            'lg' => '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1)',
            'xl' => '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1)',
        ];
        $shadow = $shadows[$shadow_style] ?? $shadows['sm'];
        
        // Spacing/Padding (internal - for menu items)
        $spacing_mode = $menu['spacing_mode'] ?? 'all';
        if ( $spacing_mode === 'all' ) {
            $spacing_all = $menu['spacing_all'] ?? '12';
            $padding = "{$spacing_all}px {$spacing_all}px";
        } else {
            $spacing_top = $menu['spacing_top'] ?? '12';
            $spacing_right = $menu['spacing_right'] ?? '16';
            $spacing_bottom = $menu['spacing_bottom'] ?? '12';
            $spacing_left = $menu['spacing_left'] ?? '16';
            $padding = "{$spacing_top}px {$spacing_right}px {$spacing_bottom}px {$spacing_left}px";
        }
        
        // Margin (external - menu container)
        $margin_mode = $menu['margin_mode'] ?? 'all';
        if ( $margin_mode === 'all' ) {
            $margin_all = $menu['margin_all'] ?? '0';
            $margin_top = $margin_all;
            $margin_right = $margin_all;
            $margin_bottom = $margin_all;
            $margin_left = $margin_all;
        } else {
            $margin_top = $menu['margin_top'] ?? '0';
            $margin_right = $menu['margin_right'] ?? '0';
            $margin_bottom = $menu['margin_bottom'] ?? '0';
            $margin_left = $menu['margin_left'] ?? '0';
        }
        
        // Icons
        $icon_size = $menu['icon_size'] ?? '20';
        $icon_color = $menu['icon_color'] ?? '#64748b';
        $icon_hover_color = $menu['icon_hover_color'] ?? '#6366f1';
        $icon_active_color = $menu['icon_active_color'] ?? '#ffffff';
        
        // Active/Hover colors
        $active_bg_start = $menu['active_bg_start'] ?? '#6366f1';
        $active_bg_end = $menu['active_bg_end'] ?? '#8b5cf6';
        $active_text_color = $menu['active_text_color'] ?? '#ffffff';
        
        // Submenu
        $submenu_bg_color = $menu['submenu_bg_color'] ?? '#f8fafc';
        $submenu_text_color = $menu['submenu_text_color'] ?? '#0f172a';
        $submenu_hover_bg_color = $menu['submenu_hover_bg_color'] ?? '#f1f5f9';
        $submenu_border_radius = $menu['submenu_border_radius'] ?? '8';

        $this->css .= "/* Admin Menu Styling - Customizable */\n";
        
        // Hide adminmenuback to prevent double menu
        $this->css .= "#adminmenuback {\n";
        $this->css .= "    display: none !important;\n";
        $this->css .= "}\n\n";
        
        // Style adminmenuwrap with settings from plugin
        $this->css .= "#adminmenuwrap {\n";
        $this->css .= "    position: fixed !important;\n";
        $this->css .= "    left: {$margin_left}px !important;\n";
        $this->css .= "    top: {$margin_top}px !important;\n";
        $this->css .= "    width: {$width}px !important;\n";
        
        // Background based on type
        if ( $background_type === 'glass' ) {
            // Use glass_base_color if available, fallback to background_color
            $glass_color = $menu['glass_base_color'] ?? $background_color;
            $bg_rgba = $this->hex_to_rgba( $glass_color, $opacity );
            $this->css .= "    background: {$bg_rgba} !important;\n";
            $this->css .= "    backdrop-filter: blur({$blur_strength}px) !important;\n";
            $this->css .= "    -webkit-backdrop-filter: blur({$blur_strength}px) !important;\n";
        } elseif ( $background_type === 'gradient' ) {
            $gradient_start = $menu['gradient_start'] ?? '#ffffff';
            $gradient_end = $menu['gradient_end'] ?? '#f8fafc';
            $this->css .= "    background: linear-gradient(to bottom, {$gradient_start}, {$gradient_end}) !important;\n";
        } else {
            $this->css .= "    background: {$background_color} !important;\n";
        }
        
        $this->css .= "    border-radius: {$border_radius} !important;\n";
        $this->css .= "    box-shadow: {$shadow} !important;\n";
        $this->css .= "    overflow: visible !important;\n";
        $this->css .= "}\n\n";
        
        $this->css .= "#adminmenu {\n";
        $this->css .= "    width: {$width}px !important;\n";
        $this->css .= "    padding: 8px !important;\n";
        $this->css .= "    margin: 0 !important;\n";
        $this->css .= "    border-radius: {$border_radius} !important;\n";
        $this->css .= "    height: 85vh !important;\n";
        $this->css .= "    background: transparent !important;\n";
        $this->css .= "    overflow-x: visible !important;\n";
        $this->css .= "    overflow-y: auto !important;\n";
        $this->css .= "    box-sizing: border-box !important;\n";
        $this->css .= "    font-size: {$font_size}px !important;\n";
        $this->css .= "    font-weight: {$font_weight} !important;\n";
        $this->css .= "}\n\n";
        
        // Fix menu items to stay within bounds
        $this->css .= "#adminmenu li,\n";
        $this->css .= "#adminmenu li.menu-top {\n";
        $this->css .= "    width: 100% !important;\n";
        $this->css .= "    box-sizing: border-box !important;\n";
        $this->css .= "    min-height: {$item_height}px !important;\n";
        $this->css .= "}\n\n";
        
        $this->css .= "#adminmenu li a,\n";
        $this->css .= "#adminmenu li.menu-top a {\n";
        $this->css .= "    width: 100% !important;\n";
        $this->css .= "    box-sizing: border-box !important;\n";
        $this->css .= "    padding: {$padding} !important;\n";
        $this->css .= "    min-height: {$item_height}px !important;\n";
        $this->css .= "    display: flex !important;\n";
        $this->css .= "    align-items: center !important;\n";
        $this->css .= "    border-radius: {$item_border_radius}px !important;\n";
        $this->css .= "}\n\n";
        
        // Base text color and typography for all menu items
        $this->css .= "#adminmenu,\n";
        $this->css .= "#adminmenu a,\n";
        $this->css .= "#adminmenu div.wp-menu-name {\n";
        $this->css .= "    color: {$text_color} !important;\n";
        $this->css .= "    font-size: {$font_size}px !important;\n";
        $this->css .= "    font-weight: {$font_weight} !important;\n";
        $this->css .= "}\n\n";
        
        // Menu item icons - base color
        $this->css .= "#adminmenu .wp-menu-image:before,\n";
        $this->css .= "#adminmenu .wp-menu-image img,\n";
        $this->css .= "#adminmenu li .wp-menu-image:before {\n";
        $this->css .= "    color: {$icon_color} !important;\n";
        $this->css .= "    font-size: {$icon_size}px !important;\n";
        $this->css .= "    width: {$icon_size}px !important;\n";
        $this->css .= "    height: {$icon_size}px !important;\n";
        $this->css .= "}\n\n";
        
        // Active menu items - check if gradient or solid
        $active_bg_type = $menu['active_bg_type'] ?? 'gradient';
        $active_bg_solid = $menu['active_bg_solid'] ?? $active_bg_start;
        
        $this->css .= "#adminmenu .wp-has-current-submenu .wp-submenu .wp-submenu-head,\n";
        $this->css .= "#adminmenu li.current a.menu-top,\n";
        $this->css .= "#adminmenu li.wp-has-current-submenu a.wp-has-current-submenu {\n";
        $this->css .= "    border-radius: {$item_border_radius}px !important;\n";
        
        if ( $active_bg_type === 'solid' ) {
            $this->css .= "    background: {$active_bg_solid} !important;\n";
        } else {
            $this->css .= "    background: linear-gradient(to bottom right, {$active_bg_start}, {$active_bg_end}) !important;\n";
        }
        
        $this->css .= "    color: {$active_text_color} !important;\n";
        $this->css .= "    font-weight: {$font_weight} !important;\n";
        $this->css .= "}\n\n";
        
        // Active menu item text (more specific)
        $this->css .= "#adminmenu li.wp-has-current-submenu a.wp-has-current-submenu .wp-menu-name,\n";
        $this->css .= "#adminmenu li.current a.menu-top .wp-menu-name {\n";
        $this->css .= "    color: {$active_text_color} !important;\n";
        $this->css .= "}\n\n";
        
        // Active menu item icon - VERY SPECIFIC SELECTORS
        $this->css .= "#adminmenu li.wp-has-current-submenu .wp-menu-image:before,\n";
        $this->css .= "#adminmenu li.wp-has-current-submenu div.wp-menu-image:before,\n";
        $this->css .= "#adminmenu li.current .wp-menu-image:before,\n";
        $this->css .= "#adminmenu li.current div.wp-menu-image:before,\n";
        $this->css .= "#adminmenu li.wp-has-current-submenu .wp-menu-image img,\n";
        $this->css .= "#adminmenu li.current .wp-menu-image img {\n";
        $this->css .= "    color: {$icon_active_color} !important;\n";
        $this->css .= "    filter: brightness(0) saturate(100%) !important;\n";
        $this->css .= "}\n\n";
        
        // Hover state for menu items
        $this->css .= "#adminmenu li a:hover,\n";
        $this->css .= "#adminmenu li.menu-top:hover > a,\n";
        $this->css .= "#adminmenu li.opensub > a.menu-top,\n";
        $this->css .= "#adminmenu li > a.menu-top:focus {\n";
        $this->css .= "    background: {$hover_bg_color} !important;\n";
        $this->css .= "    border-radius: {$item_border_radius}px !important;\n";
        $this->css .= "    color: {$hover_text_color} !important;\n";
        $this->css .= "}\n\n";
        
        // Hover state for menu item text (more specific)
        $this->css .= "#adminmenu li a:hover .wp-menu-name,\n";
        $this->css .= "#adminmenu li.menu-top:hover > a .wp-menu-name {\n";
        $this->css .= "    color: {$hover_text_color} !important;\n";
        $this->css .= "}\n\n";
        
        // Hover state for menu item icons - ULTRA SPECIFIC SELECTORS
        $this->css .= "#adminmenu li:hover .wp-menu-image:before,\n";
        $this->css .= "#adminmenu li:hover div.wp-menu-image:before,\n";
        $this->css .= "#adminmenu li a:hover .wp-menu-image:before,\n";
        $this->css .= "#adminmenu li.menu-top:hover .wp-menu-image:before,\n";
        $this->css .= "#adminmenu li.menu-top:hover > a .wp-menu-image:before,\n";
        $this->css .= "#adminmenu li.opensub:hover .wp-menu-image:before,\n";
        $this->css .= "#adminmenu li:hover .wp-menu-image img,\n";
        $this->css .= "#adminmenu li a:hover .wp-menu-image img,\n";
        $this->css .= "#adminmenu li:hover .dashicons,\n";
        $this->css .= "#adminmenu li a:hover .dashicons {\n";
        $this->css .= "    color: {$icon_hover_color} !important;\n";
        $this->css .= "    filter: none !important;\n";
        $this->css .= "}\n\n";
        
        // Active state for menu item icons - ULTRA SPECIFIC SELECTORS
        $this->css .= "#adminmenu li.wp-has-current-submenu .wp-menu-image:before,\n";
        $this->css .= "#adminmenu li.current .wp-menu-image:before,\n";
        $this->css .= "#adminmenu li.wp-has-current-submenu div.wp-menu-image:before,\n";
        $this->css .= "#adminmenu li.current div.wp-menu-image:before,\n";
        $this->css .= "#adminmenu li.wp-has-current-submenu > a .wp-menu-image:before,\n";
        $this->css .= "#adminmenu li.current > a .wp-menu-image:before,\n";
        $this->css .= "#adminmenu li.wp-has-current-submenu .dashicons,\n";
        $this->css .= "#adminmenu li.current .dashicons {\n";
        $this->css .= "    color: {$icon_active_color} !important;\n";
        $this->css .= "    filter: none !important;\n";
        $this->css .= "}\n\n";
        
        // Override active icon color on hover (active should stay active color)
        $this->css .= "#adminmenu li.wp-has-current-submenu:hover .wp-menu-image:before,\n";
        $this->css .= "#adminmenu li.current:hover .wp-menu-image:before,\n";
        $this->css .= "#adminmenu li.wp-has-current-submenu:hover div.wp-menu-image:before,\n";
        $this->css .= "#adminmenu li.current:hover div.wp-menu-image:before,\n";
        $this->css .= "#adminmenu li.wp-has-current-submenu:hover .dashicons,\n";
        $this->css .= "#adminmenu li.current:hover .dashicons {\n";
        $this->css .= "    color: {$icon_active_color} !important;\n";
        $this->css .= "    filter: none !important;\n";
        $this->css .= "}\n\n";
        
        // Adjust content area to account for fixed menu
        $content_margin = (int)$width + (int)$margin_left + (int)$margin_right + 16;
        $this->css .= "#wpcontent {\n";
        $this->css .= "    margin-left: {$content_margin}px !important;\n";
        $this->css .= "}\n\n";
        
        $this->css .= "#wpbody-content {\n";
        $this->css .= "    width: 96% !important;\n";
        $this->css .= "    margin-right: 16px !important;\n";
        $this->css .= "    padding: 24px !important;\n";
        $this->css .= "    border-radius: 1.5rem !important;\n";
        $this->css .= "    background: #ffffff !important;\n";
        $this->css .= "    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;\n";
        $this->css .= "}\n\n";
        
        // Adjust wrap-content spacing
        $this->css .= ".wrap {\n";
        $this->css .= "    margin: 0 !important;\n";
        $this->css .= "}\n\n";
        
        // Hide all arrow indicators and borders in admin menu
        $this->css .= "ul#adminmenu a:after,\n";
        $this->css .= "ul#adminmenu a:before,\n";
        $this->css .= "ul#adminmenu li:after,\n";
        $this->css .= "ul#adminmenu li:before,\n";
        $this->css .= "ul#adminmenu a.wp-has-current-submenu:after,\n";
        $this->css .= "ul#adminmenu > li.current > a.current:after {\n";
        $this->css .= "    display: none !important;\n";
        $this->css .= "    border: none !important;\n";
        $this->css .= "    content: none !important;\n";
        $this->css .= "}\n\n";
        
        // Remove all borders and shadows from admin menu items
        $this->css .= "#adminmenu,\n";
        $this->css .= "#adminmenu li,\n";
        $this->css .= "#adminmenu a,\n";
        $this->css .= "#adminmenu li.menu-top,\n";
        $this->css .= "#adminmenu .wp-submenu,\n";
        $this->css .= "#adminmenu .wp-submenu li,\n";
        $this->css .= "#adminmenu .wp-submenu a,\n";
        $this->css .= "#adminmenu .wp-menu-arrow,\n";
        $this->css .= "#adminmenu .wp-menu-image {\n";
        $this->css .= "    border: none !important;\n";
        $this->css .= "    border-left: none !important;\n";
        $this->css .= "    border-right: none !important;\n";
        $this->css .= "    border-top: none !important;\n";
        $this->css .= "    border-bottom: none !important;\n";
        $this->css .= "    box-shadow: none !important;\n";
        $this->css .= "    -webkit-box-shadow: none !important;\n";
        $this->css .= "}\n\n";
        
        // Remove separator borders
        $this->css .= "#adminmenu li.wp-menu-separator {\n";
        $this->css .= "    height: 8px !important;\n";
        $this->css .= "    margin: 4px 0 !important;\n";
        $this->css .= "    background: transparent !important;\n";
        $this->css .= "    border: none !important;\n";
        $this->css .= "}\n\n";
        
        // Submenu base styles - hidden by default
        $this->css .= "#adminmenu .wp-submenu {\n";
        $this->css .= "    display: none !important;\n";
        $this->css .= "}\n\n";
        
        // Active submenu (current page) - inline below parent
        $this->css .= "#adminmenu li.wp-has-current-submenu > .wp-submenu,\n";
        $this->css .= "#adminmenu li.wp-menu-open > .wp-submenu {\n";
        $this->css .= "    display: block !important;\n";
        $this->css .= "    position: relative !important;\n";
        $this->css .= "    left: 0 !important;\n";
        $this->css .= "    top: 0 !important;\n";
        $this->css .= "    margin: 0 !important;\n";
        $this->css .= "    padding: 4px 8px 8px 8px !important;\n";
        $this->css .= "    background: {$submenu_bg_color} !important;\n";
        $this->css .= "    border-radius: {$submenu_border_radius}px !important;\n";
        $this->css .= "    box-shadow: none !important;\n";
        $this->css .= "    border: none !important;\n";
        $this->css .= "}\n\n";
        
        // Hover submenu (flyout) - positioned to the right
        $submenu_left = (int)$width + (int)$margin_left + 8;
        $this->css .= "#adminmenu li.wp-has-submenu:not(.wp-has-current-submenu):not(.wp-menu-open):hover > .wp-submenu {\n";
        $this->css .= "    display: block !important;\n";
        $this->css .= "    position: fixed !important;\n";
        $this->css .= "    left: {$submenu_left}px !important;\n";
        $this->css .= "    margin-left: 0 !important;\n";
        $this->css .= "    padding: 8px !important;\n";
        $this->css .= "    min-width: 200px !important;\n";
        $this->css .= "    background: {$submenu_bg_color} !important;\n";
        $this->css .= "    backdrop-filter: blur(12px) !important;\n";
        $this->css .= "    -webkit-backdrop-filter: blur(12px) !important;\n";
        $this->css .= "    border-radius: {$submenu_border_radius}px !important;\n";
        $this->css .= "    box-shadow: {$shadow} !important;\n";
        $this->css .= "    border: 1px solid rgba(0, 0, 0, 0.1) !important;\n";
        $this->css .= "    z-index: 99999 !important;\n";
        $this->css .= "}\n\n";
        
        // Position submenu relative to parent item
        $this->css .= "#adminmenu li.wp-has-submenu {\n";
        $this->css .= "    position: relative !important;\n";
        $this->css .= "}\n\n";
        
        // Submenu items styling
        $this->css .= "#adminmenu .wp-submenu li {\n";
        $this->css .= "    margin: 0 !important;\n";
        $this->css .= "    padding: 0 !important;\n";
        $this->css .= "}\n\n";
        
        $this->css .= "#adminmenu .wp-submenu a {\n";
        $this->css .= "    color: {$submenu_text_color} !important;\n";
        $this->css .= "    padding: 8px 12px 8px 20px !important;\n";
        $this->css .= "    margin: 2px 0 !important;\n";
        $this->css .= "    display: block !important;\n";
        $this->css .= "    border-radius: {$submenu_border_radius}px !important;\n";
        $this->css .= "    font-size: 13px !important;\n";
        $this->css .= "    transition: all 200ms ease !important;\n";
        $this->css .= "}\n\n";
        
        $this->css .= "#adminmenu .wp-submenu a:hover,\n";
        $this->css .= "#adminmenu .wp-submenu a:focus {\n";
        $this->css .= "    background: {$submenu_hover_bg_color} !important;\n";
        $this->css .= "    color: {$submenu_text_color} !important;\n";
        $this->css .= "    padding-left: 24px !important;\n";
        $this->css .= "}\n\n";
        
        $this->css .= "#adminmenu .wp-submenu li.current a,\n";
        $this->css .= "#adminmenu .wp-submenu li.current > a {\n";
        $this->css .= "    color: {$active_bg_start} !important;\n";
        $this->css .= "    font-weight: 600 !important;\n";
        $this->css .= "    background: {$submenu_hover_bg_color} !important;\n";
        $this->css .= "}\n\n";
        
        // Only add custom CSS if provided by user
        if ( ! empty( $menu['custom_css'] ) ) {
            $this->css .= "/* Admin Menu Custom CSS */\n";
            $this->css .= $this->sanitize_css( $menu['custom_css'] ) . "\n\n";
        }
    }

    /**
     * Add dashboard widget styles
     *
     * @return void
     */
    private function add_dashboard_widget_styles(): void {
        $widgets = $this->settings->get_section( 'dashboard_widgets' );

        $this->css .= "/* Dashboard Widgets Styling */\n";
        $this->css .= ".postbox,\n";
        $this->css .= "#dashboard-widgets .postbox,\n";
        $this->css .= ".wrap > div.card {\n";
        $this->css .= "    border-radius: {$widgets['border_radius']} !important;\n";
        $this->css .= "    padding: {$widgets['padding']} !important;\n";
        $this->css .= "    margin-bottom: {$widgets['margin_bottom']} !important;\n";
        $this->css .= "    box-shadow: {$this->get_shadow_value($widgets['shadow_style'])} !important;\n";
        $this->css .= "    border: none !important;\n";
        $this->css .= "    transition: all 200ms var(--woow-easing);\n";
        
        if ( $widgets['glassmorphism'] ) {
            $this->css .= $this->get_glassmorphism_css( $widgets['blur_strength'], $widgets['opacity'] );
            $this->css .= "    background: rgba(255, 255, 255, {$widgets['opacity']}) !important;\n";
        } else {
            $this->css .= "    background: {$widgets['background_color']} !important;\n";
        }
        
        $this->css .= "    border: 1px solid rgba(255, 255, 255, 0.4) !important;\n";
        $this->css .= "}\n\n";
        
        // Hover effects
        if ( $widgets['hover_transform'] ) {
            $this->css .= ".postbox:hover,\n";
            $this->css .= "#dashboard-widgets .postbox:hover {\n";
            $this->css .= "    transform: translateY(-2px);\n";
            $this->css .= "    box-shadow: {$this->get_shadow_value($widgets['hover_shadow'])} !important;\n";
            $this->css .= "}\n\n";
        }
        
        // Headers
        $this->css .= ".postbox-header,\n";
        $this->css .= ".postbox h2,\n";
        $this->css .= ".postbox h3,\n";
        $this->css .= "#dashboard-widgets .postbox h2 {\n";
        $this->css .= "    background: transparent !important;\n";
        $this->css .= "    border: none !important;\n";
        $this->css .= "    padding: 0 !important;\n";
        $this->css .= "    margin-bottom: 16px !important;\n";
        $this->css .= "    font-size: {$widgets['header_font_size']} !important;\n";
        $this->css .= "    font-weight: {$widgets['header_font_weight']} !important;\n";
        $this->css .= "    color: {$widgets['header_text_color']} !important;\n";
        $this->css .= "    line-height: 1.4 !important;\n";
        $this->css .= "    letter-spacing: -0.01em !important;\n";
        $this->css .= "}\n\n";
        
        // Welcome panel
        $this->css .= "#welcome-panel {\n";
        $this->css .= "    background: linear-gradient(to bottom right, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.1), rgba(236, 72, 153, 0.1)) !important;\n";
        $this->css .= "    backdrop-filter: blur(12px) !important;\n";
        $this->css .= "    border: 1px solid rgba(255, 255, 255, 0.4) !important;\n";
        $this->css .= "    border-radius: {$widgets['border_radius']} !important;\n";
        $this->css .= "    padding: 32px !important;\n";
        $this->css .= "    margin-bottom: {$widgets['margin_bottom']} !important;\n";
        $this->css .= "    box-shadow: {$this->get_shadow_value($widgets['shadow_style'])} !important;\n";
        $this->css .= "}\n\n";
        
        $this->css .= "#welcome-panel h2 {\n";
        $this->css .= "    font-size: 32px !important;\n";
        $this->css .= "    font-weight: 700 !important;\n";
        $this->css .= "    color: #0f172a !important;\n";
        $this->css .= "    margin-bottom: 8px !important;\n";
        $this->css .= "}\n\n";
        
        // At a Glance widget
        $this->css .= "#dashboard_right_now li {\n";
        $this->css .= "    background: rgba(255, 255, 255, 0.6) !important;\n";
        $this->css .= "    backdrop-filter: blur(8px) !important;\n";
        $this->css .= "    border: 1px solid rgba(255, 255, 255, 0.4) !important;\n";
        $this->css .= "    border-radius: 16px !important;\n";
        $this->css .= "    padding: 16px !important;\n";
        $this->css .= "    margin-bottom: 12px !important;\n";
        $this->css .= "    display: flex !important;\n";
        $this->css .= "    align-items: center !important;\n";
        $this->css .= "    justify-content: space-between !important;\n";
        $this->css .= "    transition: all 200ms;\n";
        $this->css .= "}\n\n";
        
        $this->css .= "#dashboard_right_now li:hover {\n";
        $this->css .= "    background: rgba(255, 255, 255, 0.8) !important;\n";
        $this->css .= "    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;\n";
        $this->css .= "}\n\n";
        
        $this->css .= "#dashboard_right_now li .dashicons {\n";
        $this->css .= "    width: 40px !important;\n";
        $this->css .= "    height: 40px !important;\n";
        $this->css .= "    font-size: 20px !important;\n";
        $this->css .= "    background: linear-gradient(to bottom right, #6366f1, #8b5cf6) !important;\n";
        $this->css .= "    color: #ffffff !important;\n";
        $this->css .= "    border-radius: 12px !important;\n";
        $this->css .= "    display: flex !important;\n";
        $this->css .= "    align-items: center !important;\n";
        $this->css .= "    justify-content: center !important;\n";
        $this->css .= "    margin-right: 12px !important;\n";
        $this->css .= "}\n\n";
        
        // Custom CSS
        if ( ! empty( $widgets['custom_css'] ) ) {
            $this->css .= "/* Dashboard Widgets Custom CSS */\n";
            $this->css .= $this->sanitize_css( $widgets['custom_css'] ) . "\n\n";
        }
    }

    /**
     * Add form control styles
     *
     * @return void
     */
    private function add_form_control_styles(): void {
        $forms = $this->settings->get_section( 'form_controls' );

        $this->css .= "/* Form Controls Styling */\n";
        $this->css .= "input[type=\"text\"],\n";
        $this->css .= "input[type=\"email\"],\n";
        $this->css .= "input[type=\"url\"],\n";
        $this->css .= "input[type=\"password\"],\n";
        $this->css .= "input[type=\"search\"],\n";
        $this->css .= "input[type=\"number\"],\n";
        $this->css .= "input[type=\"tel\"],\n";
        $this->css .= "input[type=\"date\"],\n";
        $this->css .= "input[type=\"time\"] {\n";
        $this->css .= "    height: {$forms['input_height']} !important;\n";
        $this->css .= "    padding: 10px 14px !important;\n";
        $this->css .= "    border: 1px solid {$forms['border_color']} !important;\n";
        $this->css .= "    border-radius: {$forms['border_radius']} !important;\n";
        $this->css .= "    color: {$forms['text_color']} !important;\n";
        $this->css .= "    font-size: 15px !important;\n";
        $this->css .= "    font-weight: 400 !important;\n";
        $this->css .= "    line-height: 1.5 !important;\n";
        $this->css .= "    transition: all 200ms var(--woow-easing) !important;\n";
        
        if ( $forms['glassmorphism'] ) {
            $this->css .= $this->get_glassmorphism_css( $forms['blur_strength'], 0.6 );
            $this->css .= "    background: {$forms['background_color']} !important;\n";
        } else {
            $this->css .= "    background: {$forms['background_color']} !important;\n";
        }
        
        $this->css .= "}\n\n";
        
        // Focus state
        $rgb = $this->hex_to_rgb( $forms['focus_ring_color'] );
        $this->css .= "input:focus,\n";
        $this->css .= "textarea:focus,\n";
        $this->css .= "select:focus {\n";
        $this->css .= "    outline: none !important;\n";
        $this->css .= "    border-color: {$forms['focus_ring_color']} !important;\n";
        $this->css .= "    background: rgba(255, 255, 255, 0.8) !important;\n";
        $this->css .= "    box-shadow: 0 0 0 4px rgba({$rgb}, {$forms['focus_ring_opacity']}) !important;\n";
        $this->css .= "}\n\n";
        
        // Textarea
        $this->css .= "textarea {\n";
        $this->css .= "    padding: 14px !important;\n";
        $this->css .= "    border: 1px solid {$forms['border_color']} !important;\n";
        $this->css .= "    border-radius: {$forms['border_radius']} !important;\n";
        $this->css .= "    background: {$forms['background_color']} !important;\n";
        $this->css .= "    font-size: 15px !important;\n";
        $this->css .= "    min-height: 120px !important;\n";
        $this->css .= "    transition: all 200ms var(--woow-easing) !important;\n";
        
        if ( $forms['glassmorphism'] ) {
            $this->css .= $this->get_glassmorphism_css( $forms['blur_strength'], 0.6 );
        }
        
        $this->css .= "}\n\n";
        
        // Select
        $this->css .= "select {\n";
        $this->css .= "    height: {$forms['input_height']} !important;\n";
        $this->css .= "    padding: 10px 14px !important;\n";
        $this->css .= "    border: 1px solid {$forms['border_color']} !important;\n";
        $this->css .= "    border-radius: {$forms['border_radius']} !important;\n";
        $this->css .= "    background: {$forms['background_color']} !important;\n";
        $this->css .= "    font-size: 15px !important;\n";
        $this->css .= "    cursor: pointer !important;\n";
        
        if ( $forms['glassmorphism'] ) {
            $this->css .= $this->get_glassmorphism_css( $forms['blur_strength'], 0.6 );
        }
        
        $this->css .= "}\n\n";
        
        // Checkbox
        $this->css .= "input[type=\"checkbox\"] {\n";
        $this->css .= "    width: {$forms['checkbox_size']} !important;\n";
        $this->css .= "    height: {$forms['checkbox_size']} !important;\n";
        $this->css .= "    border: 2px solid #e2e8f0 !important;\n";
        $this->css .= "    border-radius: 6px !important;\n";
        $this->css .= "    background: #ffffff !important;\n";
        $this->css .= "    cursor: pointer !important;\n";
        $this->css .= "    transition: all 200ms var(--woow-easing) !important;\n";
        $this->css .= "}\n\n";
        
        $this->css .= "input[type=\"checkbox\"]:checked {\n";
        $this->css .= "    background: {$forms['focus_ring_color']} !important;\n";
        $this->css .= "    border-color: {$forms['focus_ring_color']} !important;\n";
        $this->css .= "}\n\n";
        
        // Radio
        $this->css .= "input[type=\"radio\"] {\n";
        $this->css .= "    width: {$forms['checkbox_size']} !important;\n";
        $this->css .= "    height: {$forms['checkbox_size']} !important;\n";
        $this->css .= "    border: 2px solid #e2e8f0 !important;\n";
        $this->css .= "    background: #ffffff !important;\n";
        $this->css .= "    cursor: pointer !important;\n";
        $this->css .= "}\n\n";
        
        $this->css .= "input[type=\"radio\"]:checked {\n";
        $this->css .= "    border-color: {$forms['focus_ring_color']} !important;\n";
        $this->css .= "    background: {$forms['focus_ring_color']} !important;\n";
        $this->css .= "}\n\n";
        
        // Labels
        $this->css .= "label {\n";
        $this->css .= "    font-size: 14px !important;\n";
        $this->css .= "    font-weight: 600 !important;\n";
        $this->css .= "    color: #0f172a !important;\n";
        $this->css .= "    line-height: 1.5 !important;\n";
        $this->css .= "}\n\n";
        
        // Helper text
        $this->css .= ".description {\n";
        $this->css .= "    font-size: 13px !important;\n";
        $this->css .= "    color: #64748b !important;\n";
        $this->css .= "    line-height: 1.6 !important;\n";
        $this->css .= "    margin-top: 6px !important;\n";
        $this->css .= "}\n\n";
        
        // Custom CSS
        if ( ! empty( $forms['custom_css'] ) ) {
            $this->css .= "/* Form Controls Custom CSS */\n";
            $this->css .= $this->sanitize_css( $forms['custom_css'] ) . "\n\n";
        }
    }

    /**
     * Add button styles
     *
     * @return void
     */
    private function add_button_styles(): void {
        $buttons = $this->settings->get_section( 'buttons' );

        $this->css .= "/* Button Styling */\n";
        
        // Primary button
        $this->css .= ".button-primary {\n";
        $this->css .= "    height: {$buttons['height']} !important;\n";
        $this->css .= "    padding: 10px 16px !important;\n";
        $this->css .= "    border: none !important;\n";
        $this->css .= "    border-radius: {$buttons['border_radius']} !important;\n";
        $this->css .= "    background: {$buttons['primary_bg']} !important;\n";
        $this->css .= "    color: {$buttons['primary_text']} !important;\n";
        $this->css .= "    font-size: 14px !important;\n";
        $this->css .= "    font-weight: 600 !important;\n";
        $this->css .= "    line-height: 1.5 !important;\n";
        $this->css .= "    box-shadow: none !important;\n";
        $this->css .= "    transition: all {$buttons['transition_speed']} var(--woow-easing) !important;\n";
        $this->css .= "    cursor: pointer !important;\n";
        $this->css .= "}\n\n";
        
        $this->css .= ".button-primary:hover {\n";
        $this->css .= "    transform: scale({$buttons['hover_scale']});\n";
        $this->css .= "    box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.3) !important;\n";
        $this->css .= "}\n\n";
        
        $this->css .= ".button-primary:active {\n";
        $this->css .= "    transform: scale(1);\n";
        $this->css .= "}\n\n";
        
        $rgb = $this->hex_to_rgb( $buttons['primary_bg'] );
        $this->css .= ".button-primary:focus {\n";
        $this->css .= "    outline: none !important;\n";
        $this->css .= "    box-shadow: 0 0 0 4px rgba({$rgb}, 0.2) !important;\n";
        $this->css .= "}\n\n";
        
        // Secondary button
        $this->css .= ".button,\n";
        $this->css .= ".button-secondary {\n";
        $this->css .= "    height: {$buttons['height']} !important;\n";
        $this->css .= "    padding: 10px 16px !important;\n";
        $this->css .= "    border: 1px solid {$buttons['secondary_border']} !important;\n";
        $this->css .= "    border-radius: {$buttons['border_radius']} !important;\n";
        $this->css .= "    background: {$buttons['secondary_bg']} !important;\n";
        $this->css .= "    color: {$buttons['secondary_text']} !important;\n";
        $this->css .= "    font-size: 14px !important;\n";
        $this->css .= "    font-weight: 600 !important;\n";
        $this->css .= "    transition: all {$buttons['transition_speed']} var(--woow-easing) !important;\n";
        $this->css .= "    cursor: pointer !important;\n";
        $this->css .= "}\n\n";
        
        $this->css .= ".button:hover,\n";
        $this->css .= ".button-secondary:hover {\n";
        $this->css .= "    background: rgba(99, 102, 241, 0.1) !important;\n";
        $this->css .= "    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;\n";
        $this->css .= "}\n\n";
        
        // Destructive button
        $this->css .= ".button.delete,\n";
        $this->css .= ".submitdelete {\n";
        $this->css .= "    background: {$buttons['destructive_bg']} !important;\n";
        $this->css .= "    color: {$buttons['destructive_text']} !important;\n";
        $this->css .= "    border: none !important;\n";
        $this->css .= "}\n\n";
        
        $this->css .= ".button.delete:hover,\n";
        $this->css .= ".submitdelete:hover {\n";
        $this->css .= "    background: #dc2626 !important;\n";
        $this->css .= "    transform: scale({$buttons['hover_scale']});\n";
        $this->css .= "}\n\n";
        
        // Custom CSS
        if ( ! empty( $buttons['custom_css'] ) ) {
            $this->css .= "/* Buttons Custom CSS */\n";
            $this->css .= $this->sanitize_css( $buttons['custom_css'] ) . "\n\n";
        }
    }

    /**
     * Add background styles
     *
     * @return void
     */
    private function add_background_styles(): void {
        $bg = $this->settings->get_section( 'backgrounds' );

        $this->css .= "/* Background Styling */\n";
        $this->css .= "#wpbody-content {\n";
        $this->css .= "    padding: 16px !important;\n";
        $this->css .= "    margin-left: 0 !important;\n";
        
        if ( $bg['type'] === 'solid' ) {
            $this->css .= "    background: {$bg['solid_color']} !important;\n";
        } elseif ( $bg['type'] === 'gradient' ) {
            $colors = $bg['gradient_colors'];
            $angle = $bg['gradient_angle'];
            
            if ( $bg['gradient_type'] === 'linear' ) {
                $gradient = "linear-gradient({$angle}deg";
                foreach ( $colors as $color ) {
                    $gradient .= ", {$color}";
                }
                $gradient .= ")";
                $this->css .= "    background: {$gradient} !important;\n";
            } elseif ( $bg['gradient_type'] === 'radial' ) {
                $gradient = "radial-gradient(circle";
                foreach ( $colors as $color ) {
                    $gradient .= ", {$color}";
                }
                $gradient .= ")";
                $this->css .= "    background: {$gradient} !important;\n";
            } elseif ( $bg['gradient_type'] === 'conic' ) {
                $gradient = "conic-gradient(from {$angle}deg";
                foreach ( $colors as $color ) {
                    $gradient .= ", {$color}";
                }
                $gradient .= ")";
                $this->css .= "    background: {$gradient} !important;\n";
            }
        } elseif ( $bg['type'] === 'image' && ! empty( $bg['image_url'] ) ) {
            $this->css .= "    background-image: url('{$bg['image_url']}') !important;\n";
            $this->css .= "    background-position: {$bg['image_position']} !important;\n";
            $this->css .= "    background-size: {$bg['image_size']} !important;\n";
            $this->css .= "    background-repeat: no-repeat !important;\n";
        }
        
        $this->css .= "}\n\n";
        
        // Custom CSS
        if ( ! empty( $bg['custom_css'] ) ) {
            $this->css .= "/* Background Custom CSS */\n";
            $this->css .= $this->sanitize_css( $bg['custom_css'] ) . "\n\n";
        }
    }

    /**
     * Add typography styles
     *
     * @return void
     */
    private function add_typography_styles(): void {
        $typo = $this->settings->get_section( 'typography' );

        $this->css .= "/* Typography Styling */\n";
        
        // H1
        $this->css .= "h1 {\n";
        $this->css .= "    font-size: {$typo['h1_size']} !important;\n";
        $this->css .= "    font-weight: {$typo['h1_weight']} !important;\n";
        $this->css .= "    line-height: {$typo['h1_line_height']} !important;\n";
        $this->css .= "    letter-spacing: -0.02em !important;\n";
        $this->css .= "}\n\n";
        
        // H2
        $this->css .= "h2 {\n";
        $this->css .= "    font-size: {$typo['h2_size']} !important;\n";
        $this->css .= "    font-weight: {$typo['h2_weight']} !important;\n";
        $this->css .= "    line-height: {$typo['h2_line_height']} !important;\n";
        $this->css .= "    letter-spacing: -0.01em !important;\n";
        $this->css .= "}\n\n";
        
        // H3
        $this->css .= "h3 {\n";
        $this->css .= "    font-size: {$typo['h3_size']} !important;\n";
        $this->css .= "    font-weight: {$typo['h3_weight']} !important;\n";
        $this->css .= "    line-height: {$typo['h3_line_height']} !important;\n";
        $this->css .= "    letter-spacing: -0.01em !important;\n";
        $this->css .= "}\n\n";
        
        // Body
        $this->css .= "body {\n";
        $this->css .= "    font-size: {$typo['body_size']} !important;\n";
        $this->css .= "    font-weight: {$typo['body_weight']} !important;\n";
        $this->css .= "    line-height: {$typo['body_line_height']} !important;\n";
        $this->css .= "}\n\n";
        
        // Buttons and labels
        $this->css .= "button, label {\n";
        $this->css .= "    font-size: 14px !important;\n";
        $this->css .= "    font-weight: 600 !important;\n";
        $this->css .= "    line-height: 1.5 !important;\n";
        $this->css .= "}\n\n";
        
        // Small text
        $this->css .= "small {\n";
        $this->css .= "    font-size: 13px !important;\n";
        $this->css .= "    line-height: 1.5 !important;\n";
        $this->css .= "}\n\n";
        
        // Custom CSS
        if ( ! empty( $typo['custom_css'] ) ) {
            $this->css .= "/* Typography Custom CSS */\n";
            $this->css .= $this->sanitize_css( $typo['custom_css'] ) . "\n\n";
        }
    }

    /**
     * Add effect styles
     *
     * @return void
     */
    private function add_effect_styles(): void {
        $effects = $this->settings->get_section( 'effects' );

        $this->css .= "/* Effects and Animations */\n";
        
        // Global transitions
        $this->css .= "* {\n";
        $this->css .= "    transition-property: background, color, border, transform, box-shadow, opacity;\n";
        $this->css .= "    transition-duration: {$effects['animation_duration']};\n";
        $this->css .= "    transition-timing-function: {$effects['easing_function']};\n";
        $this->css .= "}\n\n";
        
        // Glassmorphism utilities
        $this->css .= ".woow-glass {\n";
        $this->css .= "    backdrop-filter: blur(8px);\n";
        $this->css .= "    -webkit-backdrop-filter: blur(8px);\n";
        $this->css .= "    background: rgba(255, 255, 255, 0.8);\n";
        $this->css .= "    border: 1px solid rgba(255, 255, 255, 0.4);\n";
        $this->css .= "    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);\n";
        $this->css .= "}\n\n";
        
        $this->css .= ".woow-glass-strong {\n";
        $this->css .= "    backdrop-filter: blur({$effects['glassmorphism_blur']});\n";
        $this->css .= "    -webkit-backdrop-filter: blur({$effects['glassmorphism_blur']});\n";
        $this->css .= "    background: rgba(255, 255, 255, 0.9);\n";
        $this->css .= "    border: 1px solid rgba(255, 255, 255, 0.4);\n";
        $this->css .= "    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);\n";
        $this->css .= "}\n\n";
        
        $this->css .= ".woow-glass-subtle {\n";
        $this->css .= "    backdrop-filter: blur(6px);\n";
        $this->css .= "    -webkit-backdrop-filter: blur(6px);\n";
        $this->css .= "    background: rgba(255, 255, 255, 0.6);\n";
        $this->css .= "    border: 1px solid rgba(255, 255, 255, 0.3);\n";
        $this->css .= "}\n\n";
        
        // Fade in animation
        $this->css .= "@keyframes woowFadeIn {\n";
        $this->css .= "    from {\n";
        $this->css .= "        opacity: 0;\n";
        $this->css .= "        transform: translateY(20px);\n";
        $this->css .= "    }\n";
        $this->css .= "    to {\n";
        $this->css .= "        opacity: 1;\n";
        $this->css .= "        transform: translateY(0);\n";
        $this->css .= "    }\n";
        $this->css .= "}\n\n";
        
        // Respect reduced motion
        if ( $effects['respect_reduced_motion'] ) {
            $this->css .= "@media (prefers-reduced-motion: reduce) {\n";
            $this->css .= "    * {\n";
            $this->css .= "        animation-duration: 0.01ms !important;\n";
            $this->css .= "        transition-duration: 0.01ms !important;\n";
            $this->css .= "    }\n";
            $this->css .= "}\n\n";
        }
        
        // Custom CSS
        if ( ! empty( $effects['custom_css'] ) ) {
            $this->css .= "/* Effects Custom CSS */\n";
            $this->css .= $this->sanitize_css( $effects['custom_css'] ) . "\n\n";
        }
    }

    /**
     * Add responsive styles
     *
     * @return void
     */
    private function add_responsive_styles(): void {
        $this->css .= "/* Responsive Styles */\n";
        
        // Mobile: < 768px
        $this->css .= "@media (max-width: 767px) {\n";
        $this->css .= "    #adminmenuwrap, #adminmenu {\n";
        $this->css .= "        position: relative !important;\n";
        $this->css .= "        width: 100% !important;\n";
        $this->css .= "        margin: 16px !important;\n";
        $this->css .= "        height: auto !important;\n";
        $this->css .= "    }\n\n";
        
        $this->css .= "    #wpadminbar {\n";
        $this->css .= "        height: 56px !important;\n";
        $this->css .= "        border-radius: 16px !important;\n";
        $this->css .= "    }\n\n";
        
        $this->css .= "    .postbox {\n";
        $this->css .= "        padding: 16px !important;\n";
        $this->css .= "        border-radius: 16px !important;\n";
        $this->css .= "    }\n\n";
        
        $this->css .= "    body {\n";
        $this->css .= "        font-size: 14px !important;\n";
        $this->css .= "    }\n\n";
        
        $this->css .= "    h1 { font-size: 24px !important; }\n";
        $this->css .= "    h2 { font-size: 20px !important; }\n";
        $this->css .= "    h3 { font-size: 18px !important; }\n\n";
        
        // Touch targets
        $this->css .= "    button, input, select, a {\n";
        $this->css .= "        min-height: 48px !important;\n";
        $this->css .= "        min-width: 48px !important;\n";
        $this->css .= "    }\n";
        $this->css .= "}\n\n";
        
        // Tablet: 768px - 1023px
        $this->css .= "@media (min-width: 768px) and (max-width: 1023px) {\n";
        $this->css .= "    #adminmenuwrap, #adminmenu {\n";
        $this->css .= "        width: 240px !important;\n";
        $this->css .= "    }\n";
        $this->css .= "}\n\n";
        
        // Desktop: > 1024px
        $this->css .= "@media (min-width: 1024px) {\n";
        $this->css .= "    /* Full layout enabled */\n";
        $this->css .= "}\n\n";
    }

    /**
     * Get shadow value from preset name
     *
     * @param string $style Shadow style preset
     * @return string CSS shadow value
     */
    private function get_shadow_value( string $style ): string {
        $shadows = [
            'none' => 'none',
            'sm'   => '0 1px 3px 0 rgba(0, 0, 0, 0.1)',
            'md'   => '0 4px 6px -1px rgba(0, 0, 0, 0.1)',
            'lg'   => '0 10px 15px -3px rgba(0, 0, 0, 0.1)',
            'xl'   => '0 20px 25px -5px rgba(0, 0, 0, 0.1)',
        ];

        return $shadows[ $style ] ?? $shadows['md'];
    }

    /**
     * Convert hex color to RGB string
     *
     * @param string $hex Hex color code
     * @return string RGB values as comma-separated string
     */
    private function hex_to_rgb( string $hex ): string {
        $hex = ltrim( $hex, '#' );
        
        if ( strlen( $hex ) === 3 ) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        $r = hexdec( substr( $hex, 0, 2 ) );
        $g = hexdec( substr( $hex, 2, 2 ) );
        $b = hexdec( substr( $hex, 4, 2 ) );

        return "$r,$g,$b";
    }

    /**
     * Generate glassmorphism CSS
     *
     * @param string $blur Blur strength
     * @param float  $opacity Background opacity
     * @return string Glassmorphism CSS
     */
    private function get_glassmorphism_css( string $blur, float $opacity ): string {
        $css = '';
        $css .= "    backdrop-filter: blur({$blur}) !important;\n";
        $css .= "    -webkit-backdrop-filter: blur({$blur}) !important;\n";
        return $css;
    }

    /**
     * Check if a color is light or dark
     *
     * @param string $color Hex or rgba color
     * @return bool True if light, false if dark
     */
    private function is_light_color( string $color ): bool {
        // Handle rgba colors
        if ( strpos( $color, 'rgba' ) !== false ) {
            preg_match( '/rgba?\((\d+),\s*(\d+),\s*(\d+)/', $color, $matches );
            if ( count( $matches ) >= 4 ) {
                $r = (int) $matches[1];
                $g = (int) $matches[2];
                $b = (int) $matches[3];
            } else {
                return false; // Default to dark if can't parse
            }
        } else {
            // Handle hex colors
            $hex = ltrim( $color, '#' );
            
            if ( strlen( $hex ) === 3 ) {
                $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
            }
            
            if ( strlen( $hex ) !== 6 ) {
                return false; // Default to dark if invalid
            }

            $r = hexdec( substr( $hex, 0, 2 ) );
            $g = hexdec( substr( $hex, 2, 2 ) );
            $b = hexdec( substr( $hex, 4, 2 ) );
        }

        // Calculate relative luminance using WCAG formula
        // https://www.w3.org/TR/WCAG20/#relativeluminancedef
        $luminance = ( 0.299 * $r + 0.587 * $g + 0.114 * $b ) / 255;

        // Return true if luminance > 0.5 (light color)
        return $luminance > 0.5;
    }

    /**
     * Sanitize custom CSS
     *
     * @param string $css Custom CSS code
     * @return string Sanitized CSS
     */
    private function sanitize_css( string $css ): string {
        // Remove dangerous patterns
        $dangerous_patterns = [
            '/<script[^>]*>.*?<\/script>/is',
            '/javascript:/i',
            '/on\w+\s*=/i',
            '/<iframe[^>]*>.*?<\/iframe>/is',
            '/<object[^>]*>.*?<\/object>/is',
            '/<embed[^>]*>/i',
            '/expression\s*\(/i',
            '/import\s+/i',
            '/@import/i',
        ];

        foreach ( $dangerous_patterns as $pattern ) {
            $css = preg_replace( $pattern, '', $css );
        }

        // Strip tags
        $css = wp_strip_all_tags( $css, true );

        return $css;
    }

    /**
     * Minify CSS by removing comments and whitespace
     *
     * @param string $css CSS to minify
     * @return string Minified CSS
     */
    private function minify( string $css ): string {
        // Remove comments
        $css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
        
        // Remove whitespace
        $css = str_replace( [ "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ], '', $css );
        
        // Remove spaces around selectors and properties
        $css = preg_replace( '/\s*([{}|:;,])\s*/', '$1', $css );
        
        // Remove trailing semicolons
        $css = str_replace( ';}', '}', $css );
        
        return trim( $css );
    }
    
    /**
     * Convert hex color to rgba with opacity
     *
     * @param string $hex Hex color (#rrggbb or #rgb)
     * @param float $opacity Opacity (0-1)
     * @return string RGBA color string
     */
    private function hex_to_rgba( string $hex, float $opacity = 1.0 ): string {
        // Remove # if present
        $hex = ltrim( $hex, '#' );
        
        // Handle short hex (#rgb)
        if ( strlen( $hex ) === 3 ) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        
        // Convert to RGB
        $r = hexdec( substr( $hex, 0, 2 ) );
        $g = hexdec( substr( $hex, 2, 2 ) );
        $b = hexdec( substr( $hex, 4, 2 ) );
        
        // Return rgba string
        return "rgba({$r}, {$g}, {$b}, {$opacity})";
    }
}
