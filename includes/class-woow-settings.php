<?php
/**
 * WOOW_Settings Class
 *
 * Manages all plugin settings, palettes, templates, validation, and sanitization.
 *
 * @package WoowAdmin
 * @since 1.0.0
 * 
 * Note: strict_types disabled to allow flexible type handling in validation
 */

// declare(strict_types=1); // Disabled - causes issues with mixed type validation

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Settings Manager Class
 */
class WOOW_Settings {
    /**
     * Settings storage
     *
     * @var array
     */
    private array $settings = [];

    /**
     * Option name in database
     *
     * @var string
     */
    private const OPTION_NAME = 'woow_admin_settings';

    /**
     * Constructor - Load settings from database
     */
    public function __construct() {
        $this->load_settings();
    }

    /**
     * Get default settings structure for all 13 sections
     *
     * @return array Complete default settings
     */
    public function get_default_settings(): array {
        return [
            'general' => [
                'enabled' => true,
                'auto_palette_switch' => false,
                'light_palette' => 'professional_blue',
                'dark_palette' => 'dark_mode_pro',
                'switch_time_light' => '06:00',
                'switch_time_dark' => '18:00',
            ],
            'admin_bar' => [
                'enabled' => true,
                'height' => '48px',
                'background_type' => 'gradient',
                'background_color' => '#1e293b',
                'gradient_start' => '#1e293b',
                'gradient_end' => '#0f172a',
                'text_color' => '#ffffff',
                'hover_bg_color' => 'rgba(255,255,255,0.1)',
                'hover_text_color' => '#ffffff',
                'border_radius' => '24px',
                'font_size' => '14px',
                'font_weight' => '600',
                'glassmorphism' => true,
                'blur_strength' => '12px',
                'opacity' => 0.9,
                'shadow_style' => 'lg',
                'position' => 'fixed',
                'top_offset' => '16px',
                'custom_css' => '',
            ],
            'admin_menu' => [
                'enabled' => true,
                'width_expanded' => '256px',
                'width_collapsed' => '80px',
                'background_color' => '#ffffff',
                'position' => 'fixed',
                'glassmorphism' => true,
                'blur_strength' => '12px',
                'opacity' => 0.9,
                'menu_item_height' => '40px',
                'active_gradient_start' => '#6366f1',
                'active_gradient_end' => '#8b5cf6',
                'hover_bg_color' => 'rgba(99,102,241,0.05)',
                'border_radius' => '24px',
                'shadow_style' => 'lg',
                'custom_css' => '',
            ],
            'dashboard_widgets' => [
                'enabled' => true,
                'border_radius' => '24px',
                'padding' => '24px',
                'margin_bottom' => '24px',
                'glassmorphism' => true,
                'blur_strength' => '12px',
                'opacity' => 0.9,
                'background_color' => '#ffffff',
                'shadow_style' => 'lg',
                'hover_transform' => true,
                'hover_shadow' => 'xl',
                'header_font_size' => '20px',
                'header_font_weight' => '600',
                'header_text_color' => '#0f172a',
                'custom_css' => '',
            ],
            'form_controls' => [
                'enabled' => true,
                'input_height' => '40px',
                'border_radius' => '12px',
                'background_color' => 'rgba(255,255,255,0.6)',
                'border_color' => 'rgba(255,255,255,0.4)',
                'text_color' => '#0f172a',
                'focus_ring_color' => '#6366f1',
                'focus_ring_opacity' => 0.2,
                'glassmorphism' => true,
                'blur_strength' => '8px',
                'checkbox_size' => '20px',
                'custom_css' => '',
            ],
            'buttons' => [
                'enabled' => true,
                'height' => '40px',
                'border_radius' => '12px',
                'primary_bg' => '#6366f1',
                'primary_text' => '#ffffff',
                'secondary_bg' => 'rgba(255,255,255,0.6)',
                'secondary_text' => '#6366f1',
                'secondary_border' => 'rgba(255,255,255,0.4)',
                'destructive_bg' => '#ef4444',
                'destructive_text' => '#ffffff',
                'hover_scale' => 1.02,
                'transition_speed' => '200ms',
                'custom_css' => '',
            ],
            'backgrounds' => [
                'enabled' => true,
                'type' => 'gradient',
                'solid_color' => '#fafafa',
                'gradient_type' => 'linear',
                'gradient_angle' => '135',
                'gradient_colors' => ['#f8fafc', '#eff6ff', '#eef2ff'],
                'pattern' => 'none',
                'image_url' => '',
                'image_position' => 'center',
                'image_size' => 'cover',
                'custom_css' => '',
            ],
            'typography' => [
                'enabled' => true,
                'h1_size' => '32px',
                'h1_weight' => '700',
                'h1_line_height' => '1.3',
                'h2_size' => '24px',
                'h2_weight' => '700',
                'h2_line_height' => '1.3',
                'h3_size' => '20px',
                'h3_weight' => '600',
                'h3_line_height' => '1.4',
                'body_size' => '15px',
                'body_weight' => '400',
                'body_line_height' => '1.6',
                'custom_css' => '',
            ],
            'effects' => [
                'enabled' => true,
                'shadow_preset' => 'lg',
                'animation_duration' => '200ms',
                'easing_function' => 'cubic-bezier(0.4, 0, 0.2, 1)',
                'glassmorphism_blur' => '12px',
                'respect_reduced_motion' => true,
                'custom_css' => '',
            ],
            'login_page' => [
                'enabled' => false,
                'logo_url' => '',
                'logo_width' => '320px',
                'logo_height' => '240px',
                'background_type' => 'gradient',
                'background_color' => '#fafafa',
                'gradient_start' => '#f8fafc',
                'gradient_end' => '#eef2ff',
                'form_glassmorphism' => true,
                'form_blur_strength' => '12px',
                'custom_css' => '',
            ],
            'advanced' => [
                'debug_mode' => false,
                'cache_enabled' => true,
                'cache_ttl' => 86400,
                'minify_css' => true,
                'load_conditionally' => true,
            ],
        ];
    }

    /**
     * Load settings from database
     *
     * @return void
     */
    private function load_settings(): void {
        $saved_settings = get_option( self::OPTION_NAME, [] );
        $default_settings = $this->get_default_settings();
        
        // Merge with defaults to ensure all keys exist
        $this->settings = array_replace_recursive( $default_settings, $saved_settings );
    }

    /**
     * Save current settings to database (private helper)
     *
     * @return bool Success status
     */
    private function persist_settings(): bool {
        return update_option( self::OPTION_NAME, $this->settings );
    }

    /**
     * Get single option with default fallback
     *
     * @param string $key Option key in dot notation (e.g., 'admin_bar.height')
     * @param mixed  $default Default value if not found
     * @return mixed Option value
     */
    public function get_option( string $key, $default = null ) {
        $keys = explode( '.', $key );
        $value = $this->settings;

        foreach ( $keys as $k ) {
            if ( ! isset( $value[ $k ] ) ) {
                return $default;
            }
            $value = $value[ $k ];
        }

        return $value;
    }

    /**
     * Get entire section settings
     *
     * @param string $section Section name
     * @return array Section settings
     */
    public function get_section( string $section ): array {
        return $this->settings[ $section ] ?? [];
    }

    /**
     * Update section with validation
     *
     * @param string $section Section name
     * @param array  $data Section data
     * @return bool Success status
     */
    public function update_section( string $section, array $data ): bool {
        // Validate section exists
        if ( ! isset( $this->settings[ $section ] ) ) {
            return false;
        }

        // Validate and sanitize data
        $validation = $this->validate_settings( [ $section => $data ] );
        if ( ! $validation['valid'] ) {
            return false;
        }

        // Update section
        $this->settings[ $section ] = array_merge( $this->settings[ $section ], $data );

        // Save to database
        return $this->persist_settings();
    }

    /**
     * Get all settings
     *
     * @return array All settings
     */
    public function get_all_settings(): array {
        return $this->settings;
    }

    /**
     * Get all settings (alias for get_all_settings)
     *
     * @return array All settings
     */
    public function get_all(): array {
        return $this->get_all_settings();
    }

    /**
     * Get available color palettes
     *
     * @return array Array of 10 color palettes
     */
    public function get_available_palettes(): array {
        return [
            'professional_blue' => [
                'id' => 'professional_blue',
                'name' => 'Professional Blue',
                'description' => 'Modern indigo palette perfect for professional environments',
                'colors' => [
                    'primary' => '#6366f1',
                    'secondary' => '#8b5cf6',
                    'background' => '#fafafa',
                    'card' => '#ffffff',
                    'foreground' => '#0f172a',
                    'border' => '#e2e8f0',
                    'muted_foreground' => '#64748b',
                    'accent' => '#a78bfa',
                    'destructive' => '#ef4444',
                ],
                'gradients' => [
                    'primary' => ['#6366f1', '#8b5cf6'],
                    'secondary' => ['#8b5cf6', '#a78bfa'],
                ],
            ],
            'energetic_green' => [
                'id' => 'energetic_green',
                'name' => 'Energetic Green',
                'description' => 'Fresh and vibrant green palette for dynamic brands',
                'colors' => [
                    'primary' => '#10b981',
                    'secondary' => '#34d399',
                    'background' => '#f0fdf4',
                    'card' => '#ffffff',
                    'foreground' => '#064e3b',
                    'border' => '#d1fae5',
                    'muted_foreground' => '#059669',
                    'accent' => '#6ee7b7',
                    'destructive' => '#ef4444',
                ],
                'gradients' => [
                    'primary' => ['#10b981', '#34d399'],
                    'secondary' => ['#34d399', '#6ee7b7'],
                ],
            ],
            'creative_purple' => [
                'id' => 'creative_purple',
                'name' => 'Creative Purple',
                'description' => 'Bold purple palette for creative and artistic projects',
                'colors' => [
                    'primary' => '#8b5cf6',
                    'secondary' => '#a78bfa',
                    'background' => '#faf5ff',
                    'card' => '#ffffff',
                    'foreground' => '#4c1d95',
                    'border' => '#e9d5ff',
                    'muted_foreground' => '#7c3aed',
                    'accent' => '#c4b5fd',
                    'destructive' => '#ef4444',
                ],
                'gradients' => [
                    'primary' => ['#8b5cf6', '#a78bfa'],
                    'secondary' => ['#a78bfa', '#c4b5fd'],
                ],
            ],
            'warm_sunset' => [
                'id' => 'warm_sunset',
                'name' => 'Warm Sunset',
                'description' => 'Warm orange and amber tones for inviting interfaces',
                'colors' => [
                    'primary' => '#f59e0b',
                    'secondary' => '#fbbf24',
                    'background' => '#fffbeb',
                    'card' => '#ffffff',
                    'foreground' => '#78350f',
                    'border' => '#fde68a',
                    'muted_foreground' => '#d97706',
                    'accent' => '#fcd34d',
                    'destructive' => '#ef4444',
                ],
                'gradients' => [
                    'primary' => ['#f59e0b', '#fbbf24'],
                    'secondary' => ['#fbbf24', '#fcd34d'],
                ],
            ],
            'deep_ocean' => [
                'id' => 'deep_ocean',
                'name' => 'Deep Ocean',
                'description' => 'Cool blue tones inspired by ocean depths',
                'colors' => [
                    'primary' => '#0ea5e9',
                    'secondary' => '#38bdf8',
                    'background' => '#f0f9ff',
                    'card' => '#ffffff',
                    'foreground' => '#0c4a6e',
                    'border' => '#bae6fd',
                    'muted_foreground' => '#0284c7',
                    'accent' => '#7dd3fc',
                    'destructive' => '#ef4444',
                ],
                'gradients' => [
                    'primary' => ['#0ea5e9', '#38bdf8'],
                    'secondary' => ['#38bdf8', '#7dd3fc'],
                ],
            ],
            'fresh_mint' => [
                'id' => 'fresh_mint',
                'name' => 'Fresh Mint',
                'description' => 'Refreshing cyan and teal combination',
                'colors' => [
                    'primary' => '#06b6d4',
                    'secondary' => '#22d3ee',
                    'background' => '#ecfeff',
                    'card' => '#ffffff',
                    'foreground' => '#164e63',
                    'border' => '#a5f3fc',
                    'muted_foreground' => '#0891b2',
                    'accent' => '#67e8f9',
                    'destructive' => '#ef4444',
                ],
                'gradients' => [
                    'primary' => ['#06b6d4', '#22d3ee'],
                    'secondary' => ['#22d3ee', '#67e8f9'],
                ],
            ],
            'elegant_rose' => [
                'id' => 'elegant_rose',
                'name' => 'Elegant Rose',
                'description' => 'Sophisticated pink palette for elegant designs',
                'colors' => [
                    'primary' => '#ec4899',
                    'secondary' => '#f472b6',
                    'background' => '#fdf2f8',
                    'card' => '#ffffff',
                    'foreground' => '#831843',
                    'border' => '#fbcfe8',
                    'muted_foreground' => '#db2777',
                    'accent' => '#f9a8d4',
                    'destructive' => '#ef4444',
                ],
                'gradients' => [
                    'primary' => ['#ec4899', '#f472b6'],
                    'secondary' => ['#f472b6', '#f9a8d4'],
                ],
            ],
            'bold_red' => [
                'id' => 'bold_red',
                'name' => 'Bold Red',
                'description' => 'Powerful red palette for impactful statements',
                'colors' => [
                    'primary' => '#ef4444',
                    'secondary' => '#f87171',
                    'background' => '#fef2f2',
                    'card' => '#ffffff',
                    'foreground' => '#7f1d1d',
                    'border' => '#fecaca',
                    'muted_foreground' => '#dc2626',
                    'accent' => '#fca5a5',
                    'destructive' => '#b91c1c',
                ],
                'gradients' => [
                    'primary' => ['#ef4444', '#f87171'],
                    'secondary' => ['#f87171', '#fca5a5'],
                ],
            ],
            'monochrome' => [
                'id' => 'monochrome',
                'name' => 'Monochrome',
                'description' => 'Classic grayscale palette for timeless design',
                'colors' => [
                    'primary' => '#64748b',
                    'secondary' => '#94a3b8',
                    'background' => '#f8fafc',
                    'card' => '#ffffff',
                    'foreground' => '#0f172a',
                    'border' => '#e2e8f0',
                    'muted_foreground' => '#475569',
                    'accent' => '#cbd5e1',
                    'destructive' => '#ef4444',
                ],
                'gradients' => [
                    'primary' => ['#64748b', '#94a3b8'],
                    'secondary' => ['#94a3b8', '#cbd5e1'],
                ],
            ],
            'dark_mode_pro' => [
                'id' => 'dark_mode_pro',
                'name' => 'Dark Mode Pro',
                'description' => 'Professional dark theme with light indigo accents',
                'colors' => [
                    'primary' => '#818cf8',
                    'secondary' => '#a5b4fc',
                    'background' => '#0f172a',
                    'card' => '#1e293b',
                    'foreground' => '#f1f5f9',
                    'border' => '#334155',
                    'muted_foreground' => '#94a3b8',
                    'accent' => '#c7d2fe',
                    'destructive' => '#f87171',
                ],
                'gradients' => [
                    'primary' => ['#818cf8', '#a5b4fc'],
                    'secondary' => ['#a5b4fc', '#c7d2fe'],
                ],
            ],
        ];
    }

    /**
     * Get available design templates
     *
     * @return array Array of 11 design templates
     */
    public function get_available_templates(): array {
        return [
            'default' => [
                'id' => 'default',
                'name' => 'Default',
                'description' => 'Figma base design with glassmorphism effects',
                'thumbnail' => WOOW_ASSETS_URL . 'images/templates/default.png',
                'settings' => [
                    'admin_bar' => [
                        'glassmorphism' => true,
                        'blur_strength' => '12px',
                        'border_radius' => '24px',
                    ],
                    'admin_menu' => [
                        'glassmorphism' => true,
                        'blur_strength' => '12px',
                    ],
                    'dashboard_widgets' => [
                        'glassmorphism' => true,
                        'border_radius' => '24px',
                    ],
                ],
            ],
            'modern_minimal' => [
                'id' => 'modern_minimal',
                'name' => 'Modern Minimal',
                'description' => 'Clean minimalist design with large spacing',
                'thumbnail' => WOOW_ASSETS_URL . 'images/templates/modern-minimal.png',
                'settings' => [
                    'admin_bar' => [
                        'height' => '56px',
                        'border_radius' => '16px',
                        'glassmorphism' => false,
                        'background_type' => 'solid',
                        'background_color' => '#ffffff',
                    ],
                    'admin_menu' => [
                        'width_expanded' => '280px',
                        'glassmorphism' => false,
                        'background_color' => '#ffffff',
                    ],
                    'dashboard_widgets' => [
                        'padding' => '32px',
                        'margin_bottom' => '32px',
                        'border_radius' => '16px',
                        'glassmorphism' => false,
                    ],
                    'typography' => [
                        'h1_size' => '36px',
                        'h2_size' => '28px',
                        'body_size' => '16px',
                    ],
                ],
            ],
            'corporate_professional' => [
                'id' => 'corporate_professional',
                'name' => 'Corporate Professional',
                'description' => 'Traditional corporate styling with subtle effects',
                'thumbnail' => WOOW_ASSETS_URL . 'images/templates/corporate.png',
                'settings' => [
                    'admin_bar' => [
                        'background_type' => 'solid',
                        'background_color' => '#1e293b',
                        'border_radius' => '0px',
                        'glassmorphism' => false,
                    ],
                    'admin_menu' => [
                        'border_radius' => '0px',
                        'glassmorphism' => false,
                        'background_color' => '#f8fafc',
                    ],
                    'dashboard_widgets' => [
                        'border_radius' => '8px',
                        'glassmorphism' => false,
                        'shadow_style' => 'sm',
                    ],
                    'buttons' => [
                        'border_radius' => '4px',
                    ],
                ],
            ],
            'creative_agency' => [
                'id' => 'creative_agency',
                'name' => 'Creative Agency',
                'description' => 'Colorful and dynamic with bold animations',
                'thumbnail' => WOOW_ASSETS_URL . 'images/templates/creative.png',
                'settings' => [
                    'admin_bar' => [
                        'background_type' => 'gradient',
                        'gradient_start' => '#ec4899',
                        'gradient_end' => '#8b5cf6',
                        'border_radius' => '32px',
                    ],
                    'admin_menu' => [
                        'active_gradient_start' => '#ec4899',
                        'active_gradient_end' => '#f472b6',
                    ],
                    'dashboard_widgets' => [
                        'border_radius' => '32px',
                        'hover_transform' => true,
                    ],
                    'effects' => [
                        'animation_duration' => '300ms',
                        'shadow_preset' => 'xl',
                    ],
                ],
            ],
            'dark_elegant' => [
                'id' => 'dark_elegant',
                'name' => 'Dark Elegant',
                'description' => 'Sophisticated dark mode with refined details',
                'thumbnail' => WOOW_ASSETS_URL . 'images/templates/dark-elegant.png',
                'settings' => [
                    'admin_bar' => [
                        'background_color' => '#0f172a',
                        'text_color' => '#f1f5f9',
                    ],
                    'admin_menu' => [
                        'background_color' => '#1e293b',
                    ],
                    'dashboard_widgets' => [
                        'background_color' => '#1e293b',
                        'header_text_color' => '#f1f5f9',
                    ],
                    'backgrounds' => [
                        'solid_color' => '#0f172a',
                        'type' => 'solid',
                    ],
                    'form_controls' => [
                        'background_color' => 'rgba(30,41,59,0.6)',
                        'text_color' => '#f1f5f9',
                    ],
                ],
            ],
            'pastel_soft' => [
                'id' => 'pastel_soft',
                'name' => 'Pastel Soft',
                'description' => 'Delicate pastel colors for gentle aesthetics',
                'thumbnail' => WOOW_ASSETS_URL . 'images/templates/pastel.png',
                'settings' => [
                    'admin_bar' => [
                        'background_type' => 'gradient',
                        'gradient_start' => '#fce7f3',
                        'gradient_end' => '#e0e7ff',
                    ],
                    'admin_menu' => [
                        'background_color' => '#fef3c7',
                        'active_gradient_start' => '#fbbf24',
                        'active_gradient_end' => '#f59e0b',
                    ],
                    'dashboard_widgets' => [
                        'background_color' => '#fef3c7',
                    ],
                    'backgrounds' => [
                        'type' => 'gradient',
                        'gradient_colors' => ['#fce7f3', '#e0e7ff', '#fef3c7'],
                    ],
                ],
            ],
            'high_contrast' => [
                'id' => 'high_contrast',
                'name' => 'High Contrast',
                'description' => 'WCAG AAA compliant with maximum accessibility',
                'thumbnail' => WOOW_ASSETS_URL . 'images/templates/high-contrast.png',
                'settings' => [
                    'admin_bar' => [
                        'background_color' => '#000000',
                        'text_color' => '#ffffff',
                        'glassmorphism' => false,
                    ],
                    'admin_menu' => [
                        'background_color' => '#ffffff',
                        'glassmorphism' => false,
                    ],
                    'dashboard_widgets' => [
                        'background_color' => '#ffffff',
                        'glassmorphism' => false,
                        'shadow_style' => 'xl',
                    ],
                    'buttons' => [
                        'primary_bg' => '#000000',
                        'primary_text' => '#ffffff',
                    ],
                    'form_controls' => [
                        'glassmorphism' => false,
                        'background_color' => '#ffffff',
                    ],
                ],
            ],
            'minimalist_white' => [
                'id' => 'minimalist_white',
                'name' => 'Minimalist White',
                'description' => 'Pure white canvas with subtle shadows',
                'thumbnail' => WOOW_ASSETS_URL . 'images/templates/minimalist.png',
                'settings' => [
                    'admin_bar' => [
                        'background_color' => '#ffffff',
                        'text_color' => '#0f172a',
                        'glassmorphism' => false,
                        'shadow_style' => 'sm',
                    ],
                    'admin_menu' => [
                        'background_color' => '#ffffff',
                        'glassmorphism' => false,
                    ],
                    'dashboard_widgets' => [
                        'background_color' => '#ffffff',
                        'glassmorphism' => false,
                        'shadow_style' => 'sm',
                    ],
                    'backgrounds' => [
                        'type' => 'solid',
                        'solid_color' => '#ffffff',
                    ],
                ],
            ],
            'bold_bright' => [
                'id' => 'bold_bright',
                'name' => 'Bold & Bright',
                'description' => 'High energy with vibrant color contrasts',
                'thumbnail' => WOOW_ASSETS_URL . 'images/templates/bold.png',
                'settings' => [
                    'admin_bar' => [
                        'background_type' => 'gradient',
                        'gradient_start' => '#ef4444',
                        'gradient_end' => '#f59e0b',
                        'border_radius' => '32px',
                    ],
                    'admin_menu' => [
                        'active_gradient_start' => '#ef4444',
                        'active_gradient_end' => '#f59e0b',
                    ],
                    'buttons' => [
                        'primary_bg' => '#ef4444',
                        'border_radius' => '16px',
                    ],
                    'effects' => [
                        'shadow_preset' => 'xl',
                    ],
                ],
            ],
            'material_design' => [
                'id' => 'material_design',
                'name' => 'Material Design',
                'description' => 'Google Material Design principles',
                'thumbnail' => WOOW_ASSETS_URL . 'images/templates/material.png',
                'settings' => [
                    'admin_bar' => [
                        'height' => '64px',
                        'border_radius' => '0px',
                        'shadow_style' => 'md',
                    ],
                    'admin_menu' => [
                        'border_radius' => '0px',
                    ],
                    'dashboard_widgets' => [
                        'border_radius' => '4px',
                        'shadow_style' => 'md',
                        'hover_transform' => true,
                    ],
                    'buttons' => [
                        'border_radius' => '4px',
                        'height' => '36px',
                    ],
                    'form_controls' => [
                        'border_radius' => '4px',
                    ],
                ],
            ],
            'glassmorphism_pro' => [
                'id' => 'glassmorphism_pro',
                'name' => 'Glassmorphism Pro',
                'description' => 'Maximum glass effect with strong blur',
                'thumbnail' => WOOW_ASSETS_URL . 'images/templates/glass-pro.png',
                'settings' => [
                    'admin_bar' => [
                        'glassmorphism' => true,
                        'blur_strength' => '24px',
                        'opacity' => 0.7,
                        'border_radius' => '32px',
                    ],
                    'admin_menu' => [
                        'glassmorphism' => true,
                        'blur_strength' => '24px',
                        'opacity' => 0.7,
                        'border_radius' => '32px',
                    ],
                    'dashboard_widgets' => [
                        'glassmorphism' => true,
                        'blur_strength' => '24px',
                        'opacity' => 0.7,
                        'border_radius' => '32px',
                    ],
                    'form_controls' => [
                        'glassmorphism' => true,
                        'blur_strength' => '16px',
                    ],
                    'effects' => [
                        'glassmorphism_blur' => '24px',
                    ],
                ],
            ],
        ];
    }

    /**
     * Apply color palette to settings
     *
     * @param string $palette_id Palette ID
     * @return bool Success status
     */
    public function apply_palette( string $palette_id ): bool {
        $palettes = $this->get_available_palettes();
        
        if ( ! isset( $palettes[ $palette_id ] ) ) {
            return false;
        }

        $palette = $palettes[ $palette_id ];
        $colors = $palette['colors'];
        $gradients = $palette['gradients'];

        // Update admin bar colors
        $this->settings['admin_bar']['background_color'] = $colors['card'];
        $this->settings['admin_bar']['gradient_start'] = $gradients['primary'][0];
        $this->settings['admin_bar']['gradient_end'] = $gradients['primary'][1];
        $this->settings['admin_bar']['text_color'] = $colors['foreground'];

        // Update admin menu colors
        $this->settings['admin_menu']['background_color'] = $colors['card'];
        $this->settings['admin_menu']['active_gradient_start'] = $gradients['primary'][0];
        $this->settings['admin_menu']['active_gradient_end'] = $gradients['primary'][1];
        $this->settings['admin_menu']['hover_bg_color'] = 'rgba(' . $this->hex_to_rgb( $colors['primary'] ) . ',0.05)';

        // Update dashboard widgets colors
        $this->settings['dashboard_widgets']['background_color'] = $colors['card'];
        $this->settings['dashboard_widgets']['header_text_color'] = $colors['foreground'];

        // Update form controls colors
        $this->settings['form_controls']['background_color'] = 'rgba(' . $this->hex_to_rgb( $colors['card'] ) . ',0.6)';
        $this->settings['form_controls']['border_color'] = $colors['border'];
        $this->settings['form_controls']['text_color'] = $colors['foreground'];
        $this->settings['form_controls']['focus_ring_color'] = $colors['primary'];

        // Update button colors
        $this->settings['buttons']['primary_bg'] = $colors['primary'];
        $this->settings['buttons']['primary_text'] = '#ffffff';
        $this->settings['buttons']['secondary_text'] = $colors['primary'];
        $this->settings['buttons']['destructive_bg'] = $colors['destructive'];

        // Update background colors
        $this->settings['backgrounds']['solid_color'] = $colors['background'];

        // Clear CSS cache after applying palette
        if ( function_exists( 'delete_transient' ) ) {
            delete_transient( 'woow_generated_css' );
        }

        return $this->persist_settings();
    }

    /**
     * Apply design template to settings
     *
     * @param string $template_id Template ID
     * @return bool Success status
     */
    public function apply_template( string $template_id ): bool {
        $templates = $this->get_available_templates();
        
        if ( ! isset( $templates[ $template_id ] ) ) {
            return false;
        }

        $template = $templates[ $template_id ];
        $template_settings = $template['settings'];

        // Apply template settings to each section
        foreach ( $template_settings as $section => $section_settings ) {
            if ( isset( $this->settings[ $section ] ) ) {
                $this->settings[ $section ] = array_merge(
                    $this->settings[ $section ],
                    $section_settings
                );
            }
        }

        // Clear CSS cache after applying template
        if ( function_exists( 'delete_transient' ) ) {
            delete_transient( 'woow_generated_css' );
        }

        return $this->persist_settings();
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
     * Validate settings against expected types and ranges
     *
     * @param array $settings Settings to validate
     * @return array Validation result with 'valid' boolean, 'errors' array, and 'validFields' array
     */
    public function validate_settings( array $settings ): array {
        $errors = [];
        $valid_fields = [];

        foreach ( $settings as $section => $section_data ) {
            if ( ! is_array( $section_data ) ) {
                $errors[] = [
                    'field'   => $section,
                    'message' => "Section '$section' must be an array",
                    'value'   => gettype( $section_data ),
                ];
                continue;
            }

            foreach ( $section_data as $key => $value ) {
                $full_key = "$section.$key";
                $is_valid = true;
                $error_message = '';

                // Validate based on key patterns
                // Check line_height first (unitless float) before checking height (unit value)
                if ( strpos( $key, 'line_height' ) !== false ) {
                    if ( ! is_numeric( $value ) || $value < 1.0 || $value > 3.0 ) {
                        $is_valid = false;
                        $error_message = "Line height must be between 1.0 and 3.0";
                    }
                }
                // Check image_size, image_position, image_repeat (keywords, not units)
                elseif ( $key === 'image_size' || $key === 'image_position' || $key === 'image_repeat' || $key === 'pattern' ) {
                    // These are keyword values, always valid if string
                    if ( ! is_string( $value ) ) {
                        $is_valid = false;
                        $error_message = "Value must be a string";
                    }
                    // Valid keywords for image_size: cover, contain, auto
                    // Valid keywords for image_position: center, top, bottom, left, right
                    // Valid keywords for image_repeat: no-repeat, repeat, repeat-x, repeat-y
                    // Valid keywords for pattern: none, dots, grid, diagonal
                }
                // Check for _type fields first (they are keywords, not colors)
                elseif ( strpos( $key, '_type' ) !== false ) {
                    // These are keyword values (background_type, active_bg_type, etc.)
                    if ( ! is_string( $value ) ) {
                        $is_valid = false;
                        $error_message = "Value must be a string";
                    }
                }
                elseif ( strpos( $key, 'color' ) !== false || strpos( $key, '_bg' ) !== false || strpos( $key, '_text' ) !== false ) {
                    if ( ! $this->sanitize_color( $value ) ) {
                        $is_valid = false;
                        $error_message = "Invalid color format (expected #hex or rgba())";
                    }
                } elseif ( $key === 'width_unit' ) {
                    // Unit selector - accepts "%" or "px" as keywords
                    if ( ! in_array( $value, array( '%', 'px' ), true ) ) {
                        $is_valid = false;
                        $error_message = "Invalid unit (expected '%' or 'px')";
                    }
                } elseif ( $key === 'width' ) {
                    // Width value (unitless number, unit is separate field)
                    if ( ! is_numeric( $value ) || $value < 0 ) {
                        $is_valid = false;
                        $error_message = "Width must be a positive number";
                    }
                }
                // Spacing, margin, and border radius mode selectors (keywords)
                elseif ( $key === 'spacing_mode' || $key === 'margin_mode' || $key === 'border_radius_mode' ) {
                    if ( ! in_array( $value, array( 'all', 'individual' ), true ) ) {
                        $is_valid = false;
                        $error_message = "Invalid mode (expected 'all' or 'individual')";
                    }
                }
                // Spacing, margin, and border_radius values (unitless numbers, unit added in CSS)
                elseif ( strpos( $key, 'spacing_' ) !== false || strpos( $key, 'margin_' ) !== false || strpos( $key, 'border_radius_' ) !== false ) {
                    // These are unitless numbers (unit added in CSS generation)
                    if ( ! is_numeric( $value ) || $value < 0 ) {
                        $is_valid = false;
                        $error_message = "Value must be a positive number";
                    }
                }
                // Admin Menu specific unitless fields (BEFORE general patterns!)
                elseif ( $section === 'admin_menu' && ( 
                    $key === 'width' || 
                    $key === 'item_height' || 
                    $key === 'item_border_radius' || 
                    $key === 'font_size' || 
                    $key === 'blur_strength' || 
                    $key === 'icon_size' || 
                    $key === 'submenu_border_radius' || 
                    $key === 'submenu_item_height' || 
                    $key === 'submenu_font_size' || 
                    $key === 'submenu_item_border_radius' || 
                    $key === 'inline_submenu_font_size' 
                ) ) {
                    // These are unitless numbers (unit added in CSS generation)
                    if ( ! is_numeric( $value ) || $value < 0 ) {
                        $is_valid = false;
                        $error_message = "Value must be a positive number";
                    }
                }
                // Admin Menu keyword fields
                elseif ( $section === 'admin_menu' && ( 
                    $key === 'font_weight' || 
                    $key === 'shadow_style' || 
                    $key === 'background_type' || 
                    $key === 'hover_style' || 
                    $key === 'submenu_font_weight' || 
                    $key === 'inline_submenu_font_weight' 
                ) ) {
                    // These are keyword values
                    if ( ! is_string( $value ) ) {
                        $is_valid = false;
                        $error_message = "Value must be a string";
                    }
                }
                elseif ( strpos( $key, 'height' ) !== false || strpos( $key, 'size' ) !== false || strpos( $key, 'radius' ) !== false || strpos( $key, 'padding' ) !== false || strpos( $key, 'margin' ) !== false || strpos( $key, 'offset' ) !== false || strpos( $key, 'blur' ) !== false ) {
                    // Convert to string if numeric (for validation)
                    $value_str = is_numeric( $value ) ? (string) $value : $value;
                    if ( ! $this->sanitize_unit( $value_str ) ) {
                        $is_valid = false;
                        $error_message = "Invalid unit format (expected number with px/rem/em/%)";
                    }
                } elseif ( strpos( $key, 'opacity' ) !== false ) {
                    if ( ! is_numeric( $value ) || $value < 0 || $value > 1 ) {
                        $is_valid = false;
                        $error_message = "Opacity must be between 0 and 1";
                    }
                } elseif ( strpos( $key, 'enabled' ) !== false || strpos( $key, 'glassmorphism' ) !== false || strpos( $key, 'hover_transform' ) !== false ) {
                    if ( ! is_bool( $value ) ) {
                        $is_valid = false;
                        $error_message = "Value must be boolean (true/false)";
                    }
                }

                if ( $is_valid ) {
                    $valid_fields[] = $full_key;
                } else {
                    $errors[] = [
                        'field'   => $full_key,
                        'message' => $error_message,
                        'value'   => $value,
                    ];
                }
            }
        }

        return [
            'valid'       => empty( $errors ),
            'errors'      => $errors,
            'validFields' => $valid_fields,
        ];
    }

    /**
     * Sanitize value based on type
     *
     * @param string $key Setting key
     * @param mixed  $value Value to sanitize
     * @return mixed Sanitized value
     */
    public function sanitize_value( string $key, $value ) {
        // Color sanitization
        if ( strpos( $key, 'color' ) !== false || strpos( $key, '_bg' ) !== false || strpos( $key, '_text' ) !== false ) {
            return $this->sanitize_color( $value );
        }

        // Unit sanitization
        if ( strpos( $key, 'height' ) !== false || strpos( $key, 'width' ) !== false || strpos( $key, 'size' ) !== false || strpos( $key, 'radius' ) !== false || strpos( $key, 'padding' ) !== false || strpos( $key, 'margin' ) !== false || strpos( $key, 'offset' ) !== false || strpos( $key, 'blur' ) !== false ) {
            return $this->sanitize_unit( $value );
        }

        // Boolean sanitization
        if ( strpos( $key, 'enabled' ) !== false || strpos( $key, 'glassmorphism' ) !== false || strpos( $key, 'hover_transform' ) !== false ) {
            return (bool) $value;
        }

        // URL sanitization
        if ( strpos( $key, 'url' ) !== false ) {
            return esc_url_raw( $value );
        }

        // CSS sanitization
        if ( strpos( $key, 'custom_css' ) !== false ) {
            return $this->sanitize_css( $value );
        }

        // Text sanitization (default)
        return sanitize_text_field( $value );
    }

    /**
     * Sanitize color value
     *
     * @param mixed $color Color value (string expected)
     * @return string|false Sanitized color or false if invalid
     */
    public function sanitize_color( $color ) {
        // Must be a string
        if ( ! is_string( $color ) ) {
            return false;
        }
        
        $color = trim( $color );

        // Hex color validation
        if ( preg_match( '/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $color ) ) {
            return $color;
        }

        // RGB/RGBA validation
        if ( preg_match( '/^rgba?\(\s*\d+\s*,\s*\d+\s*,\s*\d+\s*(,\s*[\d.]+\s*)?\)$/', $color ) ) {
            return $color;
        }

        return false;
    }

    /**
     * Sanitize unit value (px, rem, em, %)
     *
     * @param mixed $value Unit value (string or numeric)
     * @return string|false Sanitized unit or false if invalid
     */
    public function sanitize_unit( $value ) {
        // Convert to string if numeric
        if ( is_numeric( $value ) ) {
            $value = (string) $value;
        }
        
        if ( ! is_string( $value ) ) {
            return false;
        }
        
        $value = trim( $value );

        // Validate unit format
        if ( preg_match( '/^-?\d+(\.\d+)?(px|rem|em|%|vh|vw)$/', $value ) ) {
            return $value;
        }

        return false;
    }

    /**
     * Sanitize CSS code
     *
     * @param string $css CSS code
     * @return string Sanitized CSS
     */
    public function sanitize_css( string $css ): string {
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
     * Export settings as JSON
     *
     * @return string JSON string with metadata
     */
    public function export_settings(): string {
        $export_data = [
            'version' => WOOW_VERSION,
            'exported_at' => current_time( 'mysql' ),
            'site_url' => get_site_url(),
            'settings' => $this->settings,
        ];

        return wp_json_encode( $export_data, JSON_PRETTY_PRINT );
    }

    /**
     * Import settings from JSON
     *
     * @param string $json JSON string
     * @return array Result with 'success' boolean and 'message' or 'errors'
     */
    public function import_settings( string $json ): array {
        // Decode JSON
        $import_data = json_decode( $json, true );

        if ( json_last_error() !== JSON_ERROR_NONE ) {
            return [
                'success' => false,
                'errors' => [ 'Invalid JSON format: ' . json_last_error_msg() ],
            ];
        }

        // Validate structure
        if ( ! isset( $import_data['settings'] ) || ! is_array( $import_data['settings'] ) ) {
            return [
                'success' => false,
                'errors' => [ 'Invalid settings structure: missing settings key' ],
            ];
        }

        $settings = $import_data['settings'];

        // Validate settings
        $validation = $this->validate_settings( $settings );
        if ( ! $validation['valid'] ) {
            return [
                'success' => false,
                'errors' => $validation['errors'],
            ];
        }

        // Sanitize all values
        foreach ( $settings as $section => $section_data ) {
            foreach ( $section_data as $key => $value ) {
                $settings[ $section ][ $key ] = $this->sanitize_value( $key, $value );
            }
        }

        // Merge with current settings to preserve any new keys
        $this->settings = array_replace_recursive( $this->settings, $settings );

        // Save settings
        if ( $this->persist_settings() ) {
            // Clear CSS cache
            if ( function_exists( 'delete_transient' ) ) {
                delete_transient( 'woow_generated_css' );
            }

            return [
                'success' => true,
                'message' => 'Settings imported successfully',
            ];
        }

        return [
            'success' => false,
            'errors' => [ 'Failed to save imported settings' ],
        ];
    }

    /**
     * Auto switch palette based on time of day
     *
     * @return bool Success status
     */
    public function auto_switch_palette(): bool {
        $general = $this->get_section( 'general' );

        // Check if auto switching is enabled
        if ( ! $general['auto_palette_switch'] ) {
            return false;
        }

        // Get current time
        $current_time = current_time( 'H:i' );
        $light_time = $general['switch_time_light'];
        $dark_time = $general['switch_time_dark'];

        // Determine which palette to use
        $palette_id = null;

        if ( $current_time >= $light_time && $current_time < $dark_time ) {
            // Light hours
            $palette_id = $general['light_palette'];
        } else {
            // Dark hours
            $palette_id = $general['dark_palette'];
        }

        if ( $palette_id ) {
            return $this->apply_palette( $palette_id );
        }

        return false;
    }

    /**
     * Schedule auto palette switching cron job
     *
     * @return void
     */
    public function schedule_auto_palette_switch(): void {
        if ( ! wp_next_scheduled( 'woow_auto_palette_switch' ) ) {
            wp_schedule_event( time(), 'hourly', 'woow_auto_palette_switch' );
        }
    }

    /**
     * Unschedule auto palette switching cron job
     *
     * @return void
     */
    public function unschedule_auto_palette_switch(): void {
        $timestamp = wp_next_scheduled( 'woow_auto_palette_switch' );
        if ( $timestamp ) {
            wp_unschedule_event( $timestamp, 'woow_auto_palette_switch' );
        }
    }

    /**
     * Update all settings
     *
     * @param array $settings New settings array
     * @return bool Success status
     */
    public function update_all_settings( array $settings ): bool {
        $this->settings = $settings;
        return $this->persist_settings();
    }

    /**
     * Save settings (public method for AJAX handler)
     *
     * @param array $settings Settings to save
     * @return bool Success status
     */
    public function save_settings( array $settings ): bool {
        error_log( '[WOOW Admin] save_settings called with: ' . print_r( $settings['admin_bar'] ?? 'no admin_bar', true ) );
        error_log( '[WOOW Admin] Current background_color: ' . ( $this->settings['admin_bar']['background_color'] ?? 'not set' ) );
        
        // Merge with existing settings to preserve structure
        $this->settings = array_replace_recursive( $this->settings, $settings );
        
        error_log( '[WOOW Admin] After merge background_color: ' . ( $this->settings['admin_bar']['background_color'] ?? 'not set' ) );
        
        // Save to database
        // Note: update_option() returns false if value hasn't changed
        // This is normal WordPress behavior, not an error
        $result = update_option( self::OPTION_NAME, $this->settings );
        
        if ( ! $result ) {
            // Check if option exists and value is same (not an error)
            $existing = get_option( self::OPTION_NAME );
            if ( $existing === $this->settings ) {
                error_log( '[WOOW Admin] Settings unchanged - no database update needed' );
                return true; // Not an error - settings are already correct
            }
            error_log( '[WOOW Admin] Database update failed - settings differ from stored' );
        } else {
            error_log( '[WOOW Admin] Settings updated in database successfully' );
        }
        
        return $result;
    }

    /**
     * Reset all settings to defaults
     *
     * @return bool True on success, false on failure
     */
    public function reset_to_defaults(): bool {
        // Get default settings
        $defaults = $this->get_default_settings();
        
        // Replace current settings with defaults
        $this->settings = $defaults;
        
        // Delete the option first to force update
        delete_option( self::OPTION_NAME );
        
        // Save to database - this will now always return true since option doesn't exist
        $result = add_option( self::OPTION_NAME, $this->settings, '', 'no' );
        
        if ( $result ) {
            error_log( '[WOOW Admin] Settings reset to defaults successfully' );
        } else {
            // If add_option fails, try update_option as fallback
            $result = update_option( self::OPTION_NAME, $this->settings );
            if ( $result ) {
                error_log( '[WOOW Admin] Settings reset to defaults successfully (via update)' );
            } else {
                error_log( '[WOOW Admin] Failed to reset settings to defaults' );
            }
        }
        
        return $result;
    }

}
