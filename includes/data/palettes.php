<?php
/**
 * Color Palettes Data
 *
 * This file contains all predefined color palette definitions for WOOW! Admin.
 * Each palette configures ALL available plugin options (100+ settings across 10 sections).
 *
 * @package WOOW_Admin
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return all available color palettes
 *
 * Each palette includes:
 * - id: Unique identifier
 * - name: Display name
 * - description: Brief description
 * - category: professional, creative, minimal, dark, vibrant
 * - preview_image: Filename of preview image
 * - author: Creator name
 * - version: Palette version
 * - colors: Quick reference color scheme
 * - settings: Complete configuration for all 10 sections
 *
 * @return array Array of palette definitions
 */
return array(
	
	/**
	 * Professional Blue Palette
	 * 
	 * Clean corporate design with blue tones that conveys trust and professionalism.
	 * Perfect for business and corporate WordPress admin panels.
	 */
	'professional_blue' => array(
		'id'            => 'professional_blue',
		'name'          => 'Professional Blue',
		'description'   => 'Clean corporate design with blue tones',
		'category'      => 'professional',
		'preview_image' => 'professional-blue.png',
		'author'        => 'WOOW! Admin',
		'version'       => '1.0.0',
		
		// Quick reference color scheme
		'colors' => array(
			'primary'    => '#3b82f6',
			'secondary'  => '#1e40af',
			'accent'     => '#06b6d4',
			'background' => '#f8fafc',
			'text'       => '#1e293b',
		),
		
		// Complete settings configuration
		'settings' => array(
			
			// Section 1: Color Overrides (7 colors)
			'color_overrides' => array(
				'primary_color'   => '#3b82f6',
				'secondary_color' => '#1e40af',
				'accent_color'    => '#06b6d4',
				'success_color'   => '#10b981',
				'warning_color'   => '#f59e0b',
				'error_color'     => '#ef4444',
				'info_color'      => '#06b6d4',
			),
			
			// Section 2: Admin Bar (25+ options)
			'admin_bar' => array(
				'enabled'                => true,
				'background_type'        => 'gradient',
				'background_color'       => '#3b82f6',
				'gradient_start'         => '#3b82f6',
				'gradient_end'           => '#1e40af',
				'base_color'             => '#1e40af',
				'opacity'                => 0.95,
				'blur_strength'          => '12',
				'text_color'             => '#ffffff',
				'hover_style'            => 'highlight',
				'hover_bg_color'         => '#ffffff',
				'hover_text_color'       => '#1e40af',
				'height'                 => '48',
				'width'                  => '100',
				'width_unit'             => '%',
				'border_radius_mode'     => 'all',
				'border_radius_all'      => '0',
				'font_size'              => '14',
				'font_weight'            => '600',
				'spacing_mode'           => 'all',
				'spacing_all'            => '16',
				'margin_mode'            => 'all',
				'margin_all'             => '0',
				'glassmorphism'          => true,
				'shadow_style'           => 'md',
				'position'               => 'fixed',
				'top_offset'             => '0',
				'submenu_bg_color'       => '#ffffff',
				'submenu_text_color'     => '#1e293b',
				'submenu_border_radius'  => '8',
				'submenu_font_size'      => '13',
				'custom_css'             => '',
			),
			
			// Section 3: Admin Menu (15+ options)
			'admin_menu' => array(
				'enabled'                => true,
				'background_type'        => 'solid',
				'background_color'       => '#1e40af',
				'gradient_start'         => '#3b82f6',
				'gradient_end'           => '#1e40af',
				'base_color'             => '#1e40af',
				'opacity'                => 0.95,
				'blur_strength'          => '12',
				'text_color'             => '#ffffff',
				'icon_color'             => '#ffffff',
				'hover_bg_color'         => 'rgba(255, 255, 255, 0.1)',
				'hover_text_color'       => '#ffffff',
				'active_bg_color'        => '#3b82f6',
				'active_text_color'      => '#ffffff',
				'border_radius'          => '8',
				'item_spacing'           => '4',
				'font_size'              => '14',
				'font_weight'            => '500',
				'submenu_bg_color'       => '#0f172a',
				'submenu_text_color'     => '#ffffff',
				'submenu_indent'         => '16',
			),
			
			// Section 4: Dashboard Widgets (10 options)
			'dashboard_widgets' => array(
				'background_color' => '#ffffff',
				'border_color'     => '#e5e7eb',
				'border_radius'    => '12',
				'box_shadow'       => '0 1px 3px rgba(0, 0, 0, 0.1)',
				'title_color'      => '#1f2937',
				'title_size'       => '18',
				'title_weight'     => '600',
				'text_color'       => '#6b7280',
				'padding'          => '20',
				'margin'           => '16',
			),
			
			// Section 5: Form Controls (10 options)
			'form_controls' => array(
				'input_bg'               => '#ffffff',
				'input_border'           => '#d1d5db',
				'input_border_radius'    => '6',
				'input_text_color'       => '#1f2937',
				'input_placeholder_color' => '#9ca3af',
				'input_focus_border'     => '#3b82f6',
				'input_focus_shadow'     => '0 0 0 3px rgba(59, 130, 246, 0.1)',
				'label_color'            => '#374151',
				'label_size'             => '14',
				'label_weight'           => '500',
			),
			
			// Section 6: Buttons (10 options)
			'buttons' => array(
				'primary_bg'           => '#3b82f6',
				'primary_text'         => '#ffffff',
				'primary_hover_bg'     => '#2563eb',
				'primary_border_radius' => '6',
				'primary_shadow'       => '0 1px 2px rgba(0, 0, 0, 0.05)',
				'secondary_bg'         => '#6b7280',
				'secondary_text'       => '#ffffff',
				'secondary_hover_bg'   => '#4b5563',
				'danger_bg'            => '#ef4444',
				'danger_text'          => '#ffffff',
				'danger_hover_bg'      => '#dc2626',
			),
			
			// Section 7: Backgrounds (10+ options)
			'backgrounds' => array(
				'enabled'                 => true,
				'background_color'        => '#dbeafe',
				'background_opacity'      => '1',
				'type'                    => 'gradient',
				'gradient_type'           => 'linear',
				'gradient_start'          => '#dbeafe',
				'gradient_end'            => '#e0e7ff',
				'gradient_angle'          => '135',
				'wpbody_content_color'    => 'transparent',
				'wpbody_content_opacity'  => '1',
			),
			
			// Section 8: Typography (10 options)
			'typography' => array(
				'body_font'        => 'Inter',
				'body_size'        => '14',
				'body_line_height' => 1.5,
				'body_color'       => '#1e293b',
				'heading_font'     => 'Inter',
				'heading_weight'   => '700',
				'heading_color'    => '#0f172a',
				'h1_size'          => '32',
				'h2_size'          => '24',
				'h3_size'          => '20',
			),
			
			// Section 9: Effects (8 options)
			'effects' => array(
				'glassmorphism_enabled' => true,
				'glassmorphism_blur'    => '12',
				'glassmorphism_opacity' => 0.95,
				'animations_enabled'    => true,
				'animation_speed'       => '0.3s',
				'hover_scale'           => 1.02,
				'hover_lift'            => '2',
				'shadow_color'          => 'rgba(0, 0, 0, 0.1)',
			),
			
			// Section 10: Login Page (10 options)
			'login_page' => array(
				'background_type'   => 'gradient',
				'background_color'  => '#1e40af',
				'gradient_start'    => '#3b82f6',
				'gradient_end'      => '#1e40af',
				'form_bg'           => '#ffffff',
				'form_border_radius' => '12',
				'form_shadow'       => '0 10px 25px rgba(0, 0, 0, 0.1)',
				'logo_url'          => '',
				'button_bg'         => '#3b82f6',
				'button_text'       => '#ffffff',
				'link_color'        => '#3b82f6',
			),
		),
	),
	
	/**
	 * Warm Sunset Palette
	 * 
	 * Energetic, creative design with warm colors that conveys creativity and warmth.
	 * Perfect for creative agencies and vibrant WordPress admin panels.
	 */
	'warm_sunset' => array(
		'id'            => 'warm_sunset',
		'name'          => 'Warm Sunset',
		'description'   => 'Energetic design with warm amber and orange tones',
		'category'      => 'creative',
		'preview_image' => 'warm-sunset.png',
		'author'        => 'WOOW! Admin',
		'version'       => '1.0.0',
		
		// Quick reference color scheme
		'colors' => array(
			'primary'    => '#f59e0b',
			'secondary'  => '#ea580c',
			'accent'     => '#ec4899',
			'background' => '#fff7ed',
			'text'       => '#78350f',
		),
		
		// Complete settings configuration
		'settings' => array(
			
			// Section 1: Color Overrides (7 colors)
			'color_overrides' => array(
				'primary_color'   => '#f59e0b',
				'secondary_color' => '#ea580c',
				'accent_color'    => '#ec4899',
				'success_color'   => '#10b981',
				'warning_color'   => '#f59e0b',
				'error_color'     => '#ef4444',
				'info_color'      => '#06b6d4',
			),
			
			// Section 2: Admin Bar (25+ options)
			'admin_bar' => array(
				'enabled'                => true,
				'background_type'        => 'gradient',
				'background_color'       => '#f59e0b',
				'gradient_start'         => '#f59e0b',
				'gradient_end'           => '#ea580c',
				'base_color'             => '#ea580c',
				'opacity'                => 0.9,
				'blur_strength'          => '16',
				'text_color'             => '#ffffff',
				'hover_style'            => 'glow',
				'hover_bg_color'         => '#ffffff',
				'hover_text_color'       => '#ea580c',
				'height'                 => '52',
				'width'                  => '100',
				'width_unit'             => '%',
				'border_radius_mode'     => 'all',
				'border_radius_all'      => '0',
				'font_size'              => '14',
				'font_weight'            => '600',
				'spacing_mode'           => 'all',
				'spacing_all'            => '20',
				'margin_mode'            => 'all',
				'margin_all'             => '0',
				'glassmorphism'          => true,
				'shadow_style'           => 'lg',
				'position'               => 'fixed',
				'top_offset'             => '0',
				'submenu_bg_color'       => '#fff7ed',
				'submenu_text_color'     => '#78350f',
				'submenu_border_radius'  => '12',
				'submenu_font_size'      => '13',
				'custom_css'             => '',
			),
			
			// Section 3: Admin Menu (15+ options)
			'admin_menu' => array(
				'enabled'                => true,
				'background_type'        => 'gradient',
				'background_color'       => '#fff7ed',
				'gradient_start'         => '#fff7ed',
				'gradient_end'           => '#fed7aa',
				'base_color'             => '#fff7ed',
				'opacity'                => 0.95,
				'blur_strength'          => '12',
				'text_color'             => '#78350f',
				'icon_color'             => '#f59e0b',
				'hover_bg_color'         => 'rgba(245, 158, 11, 0.1)',
				'hover_text_color'       => '#ea580c',
				'active_bg_color'        => '#f59e0b',
				'active_text_color'      => '#ffffff',
				'border_radius'          => '12',
				'item_spacing'           => '4',
				'font_size'              => '14',
				'font_weight'            => '500',
				'submenu_bg_color'       => '#fffbeb',
				'submenu_text_color'     => '#78350f',
				'submenu_indent'         => '16',
			),
			
			// Section 4: Dashboard Widgets (10 options)
			'dashboard_widgets' => array(
				'background_color' => '#fff7ed',
				'border_color'     => '#fed7aa',
				'border_radius'    => '16',
				'box_shadow'       => '0 4px 12px rgba(245, 158, 11, 0.15)',
				'title_color'      => '#78350f',
				'title_size'       => '18',
				'title_weight'     => '700',
				'text_color'       => '#92400e',
				'padding'          => '24',
				'margin'           => '16',
			),
			
			// Section 5: Form Controls (10 options)
			'form_controls' => array(
				'input_bg'               => '#ffffff',
				'input_border'           => '#fed7aa',
				'input_border_radius'    => '8',
				'input_text_color'       => '#78350f',
				'input_placeholder_color' => '#d97706',
				'input_focus_border'     => '#f59e0b',
				'input_focus_shadow'     => '0 0 0 3px rgba(245, 158, 11, 0.2)',
				'label_color'            => '#78350f',
				'label_size'             => '14',
				'label_weight'           => '600',
			),
			
			// Section 6: Buttons (10 options)
			'buttons' => array(
				'primary_bg'           => '#f59e0b',
				'primary_text'         => '#ffffff',
				'primary_hover_bg'     => '#ea580c',
				'primary_border_radius' => '8',
				'primary_shadow'       => '0 4px 12px rgba(245, 158, 11, 0.3)',
				'secondary_bg'         => '#ec4899',
				'secondary_text'       => '#ffffff',
				'secondary_hover_bg'   => '#db2777',
				'danger_bg'            => '#ef4444',
				'danger_text'          => '#ffffff',
				'danger_hover_bg'      => '#dc2626',
			),
			
			// Section 7: Backgrounds (10+ options)
			'backgrounds' => array(
				'enabled'                 => true,
				'background_color'        => '#fff7ed',
				'background_opacity'      => '1',
				'type'                    => 'gradient',
				'gradient_type'           => 'linear',
				'gradient_start'          => '#fff7ed',
				'gradient_end'            => '#fed7aa',
				'gradient_angle'          => '135',
				'wpbody_content_color'    => 'transparent',
				'wpbody_content_opacity'  => '1',
			),
			
			// Section 8: Typography (10 options)
			'typography' => array(
				'body_font'        => 'Inter',
				'body_size'        => '14',
				'body_line_height' => 1.6,
				'body_color'       => '#78350f',
				'heading_font'     => 'Inter',
				'heading_weight'   => '700',
				'heading_color'    => '#78350f',
				'h1_size'          => '32',
				'h2_size'          => '24',
				'h3_size'          => '20',
			),
			
			// Section 9: Effects (8 options)
			'effects' => array(
				'glassmorphism_enabled' => true,
				'glassmorphism_blur'    => '16',
				'glassmorphism_opacity' => 0.9,
				'animations_enabled'    => true,
				'animation_speed'       => '0.3s',
				'hover_scale'           => 1.03,
				'hover_lift'            => '4',
				'shadow_color'          => 'rgba(245, 158, 11, 0.2)',
			),
			
			// Section 10: Login Page (10 options)
			'login_page' => array(
				'background_type'   => 'gradient',
				'background_color'  => '#f59e0b',
				'gradient_start'    => '#f59e0b',
				'gradient_end'      => '#ea580c',
				'form_bg'           => '#ffffff',
				'form_border_radius' => '16',
				'form_shadow'       => '0 20px 40px rgba(245, 158, 11, 0.2)',
				'logo_url'          => '',
				'button_bg'         => '#f59e0b',
				'button_text'       => '#ffffff',
				'link_color'        => '#ea580c',
			),
		),
	),
	
	/**
	 * Dark Mode Pro Palette
	 * 
	 * Modern, sleek, premium dark mode design with neon accents.
	 * Perfect for tech-forward, modern WordPress admin panels that are easy on the eyes.
	 */
	'dark_mode_pro' => array(
		'id'            => 'dark_mode_pro',
		'name'          => 'Dark Mode Pro',
		'description'   => 'Modern dark mode with purple and cyan neon accents',
		'category'      => 'dark',
		'preview_image' => 'dark-mode-pro.png',
		'author'        => 'WOOW! Admin',
		'version'       => '1.0.0',
		
		// Quick reference color scheme
		'colors' => array(
			'primary'    => '#8b5cf6',
			'secondary'  => '#6366f1',
			'accent'     => '#06b6d4',
			'background' => '#0f172a',
			'text'       => '#f1f5f9',
		),
		
		// Complete settings configuration
		'settings' => array(
			
			// Section 1: Color Overrides (7 colors)
			'color_overrides' => array(
				'primary_color'   => '#8b5cf6',
				'secondary_color' => '#6366f1',
				'accent_color'    => '#06b6d4',
				'success_color'   => '#10b981',
				'warning_color'   => '#f59e0b',
				'error_color'     => '#ef4444',
				'info_color'      => '#06b6d4',
			),
			
			// Section 2: Admin Bar (25+ options)
			'admin_bar' => array(
				'enabled'                => true,
				'background_type'        => 'glassmorphism',
				'background_color'       => '#0f172a',
				'gradient_start'         => '#8b5cf6',
				'gradient_end'           => '#6366f1',
				'base_color'             => '#0f172a',
				'opacity'                => 0.9,
				'blur_strength'          => '16',
				'text_color'             => '#f1f5f9',
				'hover_style'            => 'glow',
				'hover_bg_color'         => '#8b5cf6',
				'hover_text_color'       => '#ffffff',
				'height'                 => '52',
				'width'                  => '100',
				'width_unit'             => '%',
				'border_radius_mode'     => 'all',
				'border_radius_all'      => '0',
				'font_size'              => '14',
				'font_weight'            => '600',
				'spacing_mode'           => 'all',
				'spacing_all'            => '20',
				'margin_mode'            => 'all',
				'margin_all'             => '0',
				'glassmorphism'          => true,
				'shadow_style'           => 'lg',
				'position'               => 'fixed',
				'top_offset'             => '0',
				'submenu_bg_color'       => '#1e293b',
				'submenu_text_color'     => '#f1f5f9',
				'submenu_border_radius'  => '12',
				'submenu_font_size'      => '13',
				'custom_css'             => '',
			),
			
			// Section 3: Admin Menu (15+ options)
			'admin_menu' => array(
				'enabled'                => true,
				'background_type'        => 'glassmorphism',
				'background_color'       => '#0f172a',
				'gradient_start'         => '#1e293b',
				'gradient_end'           => '#0f172a',
				'base_color'             => '#0f172a',
				'opacity'                => 0.85,
				'blur_strength'          => '16',
				'text_color'             => '#f1f5f9',
				'icon_color'             => '#8b5cf6',
				'hover_bg_color'         => 'rgba(139, 92, 246, 0.2)',
				'hover_text_color'       => '#ffffff',
				'active_bg_color'        => '#8b5cf6',
				'active_text_color'      => '#ffffff',
				'border_radius'          => '12',
				'item_spacing'           => '4',
				'font_size'              => '14',
				'font_weight'            => '600',
				'submenu_bg_color'       => '#1e293b',
				'submenu_text_color'     => '#cbd5e1',
				'submenu_indent'         => '16',
			),
			
			// Section 4: Dashboard Widgets (10 options)
			'dashboard_widgets' => array(
				'background_color' => '#1e293b',
				'border_color'     => '#8b5cf6',
				'border_radius'    => '16',
				'box_shadow'       => '0 8px 24px rgba(139, 92, 246, 0.2)',
				'title_color'      => '#f1f5f9',
				'title_size'       => '18',
				'title_weight'     => '700',
				'text_color'       => '#cbd5e1',
				'padding'          => '24',
				'margin'           => '16',
			),
			
			// Section 5: Form Controls (10 options)
			'form_controls' => array(
				'input_bg'               => '#1e293b',
				'input_border'           => '#475569',
				'input_border_radius'    => '8',
				'input_text_color'       => '#f1f5f9',
				'input_placeholder_color' => '#64748b',
				'input_focus_border'     => '#8b5cf6',
				'input_focus_shadow'     => '0 0 0 3px rgba(139, 92, 246, 0.3)',
				'label_color'            => '#cbd5e1',
				'label_size'             => '14',
				'label_weight'           => '600',
			),
			
			// Section 6: Buttons (10 options)
			'buttons' => array(
				'primary_bg'           => '#8b5cf6',
				'primary_text'         => '#ffffff',
				'primary_hover_bg'     => '#7c3aed',
				'primary_border_radius' => '8',
				'primary_shadow'       => '0 0 20px rgba(139, 92, 246, 0.5)',
				'secondary_bg'         => '#6366f1',
				'secondary_text'       => '#ffffff',
				'secondary_hover_bg'   => '#4f46e5',
				'danger_bg'            => '#ef4444',
				'danger_text'          => '#ffffff',
				'danger_hover_bg'      => '#dc2626',
			),
			
			// Section 7: Backgrounds (10+ options)
			'backgrounds' => array(
				'enabled'                 => true,
				'background_color'        => '#0f172a',
				'background_opacity'      => '1',
				'type'                    => 'gradient',
				'gradient_type'           => 'linear',
				'gradient_start'          => '#0f172a',
				'gradient_end'            => '#1e293b',
				'gradient_angle'          => '135',
				'wpbody_content_color'    => 'transparent',
				'wpbody_content_opacity'  => '1',
			),
			
			// Section 8: Typography (10 options)
			'typography' => array(
				'body_font'        => 'Inter',
				'body_size'        => '14',
				'body_line_height' => 1.6,
				'body_color'       => '#f1f5f9',
				'heading_font'     => 'Inter',
				'heading_weight'   => '700',
				'heading_color'    => '#ffffff',
				'h1_size'          => '32',
				'h2_size'          => '24',
				'h3_size'          => '20',
			),
			
			// Section 9: Effects (8 options)
			'effects' => array(
				'glassmorphism_enabled' => true,
				'glassmorphism_blur'    => '16',
				'glassmorphism_opacity' => 0.85,
				'animations_enabled'    => true,
				'animation_speed'       => '0.3s',
				'hover_scale'           => 1.02,
				'hover_lift'            => '4',
				'shadow_color'          => 'rgba(139, 92, 246, 0.3)',
			),
			
			// Section 10: Login Page (10 options)
			'login_page' => array(
				'background_type'   => 'gradient',
				'background_color'  => '#0f172a',
				'gradient_start'    => '#0f172a',
				'gradient_end'      => '#1e293b',
				'form_bg'           => '#1e293b',
				'form_border_radius' => '16',
				'form_shadow'       => '0 20px 40px rgba(139, 92, 246, 0.3)',
				'logo_url'          => '',
				'button_bg'         => '#8b5cf6',
				'button_text'       => '#ffffff',
				'link_color'        => '#06b6d4',
			),
		),
	),
	
	/**
	 * Nature Green Palette
	 * 
	 * Fresh, organic design with natural green colors that conveys freshness and calm.
	 * Perfect for eco-friendly, health, and nature-focused WordPress admin panels.
	 */
	'nature_green' => array(
		'id'            => 'nature_green',
		'name'          => 'Nature Green',
		'description'   => 'Fresh organic design with emerald and green tones',
		'category'      => 'creative',
		'preview_image' => 'nature-green.png',
		'author'        => 'WOOW! Admin',
		'version'       => '1.0.0',
		
		// Quick reference color scheme
		'colors' => array(
			'primary'    => '#10b981',
			'secondary'  => '#059669',
			'accent'     => '#84cc16',
			'background' => '#f0fdf4',
			'text'       => '#064e3b',
		),
		
		// Complete settings configuration
		'settings' => array(
			
			// Section 1: Color Overrides (7 colors)
			'color_overrides' => array(
				'primary_color'   => '#10b981',
				'secondary_color' => '#059669',
				'accent_color'    => '#84cc16',
				'success_color'   => '#10b981',
				'warning_color'   => '#f59e0b',
				'error_color'     => '#ef4444',
				'info_color'      => '#06b6d4',
			),
			
			// Section 2: Admin Bar (25+ options)
			'admin_bar' => array(
				'enabled'                => true,
				'background_type'        => 'gradient',
				'background_color'       => '#10b981',
				'gradient_start'         => '#10b981',
				'gradient_end'           => '#059669',
				'base_color'             => '#059669',
				'opacity'                => 0.85,
				'blur_strength'          => '12',
				'text_color'             => '#ffffff',
				'hover_style'            => 'highlight',
				'hover_bg_color'         => '#ffffff',
				'hover_text_color'       => '#059669',
				'height'                 => '48',
				'width'                  => '100',
				'width_unit'             => '%',
				'border_radius_mode'     => 'all',
				'border_radius_all'      => '24',
				'font_size'              => '14',
				'font_weight'            => '500',
				'spacing_mode'           => 'all',
				'spacing_all'            => '16',
				'margin_mode'            => 'all',
				'margin_all'             => '0',
				'glassmorphism'          => true,
				'shadow_style'           => 'md',
				'position'               => 'fixed',
				'top_offset'             => '0',
				'submenu_bg_color'       => '#f0fdf4',
				'submenu_text_color'     => '#064e3b',
				'submenu_border_radius'  => '12',
				'submenu_font_size'      => '13',
				'custom_css'             => '',
			),
			
			// Section 3: Admin Menu (15+ options)
			'admin_menu' => array(
				'enabled'                => true,
				'background_type'        => 'glassmorphism',
				'background_color'       => '#f0fdf4',
				'gradient_start'         => '#f0fdf4',
				'gradient_end'           => '#d1fae5',
				'base_color'             => '#f0fdf4',
				'opacity'                => 0.85,
				'blur_strength'          => '12',
				'text_color'             => '#064e3b',
				'icon_color'             => '#10b981',
				'hover_bg_color'         => 'rgba(16, 185, 129, 0.1)',
				'hover_text_color'       => '#059669',
				'active_bg_color'        => '#10b981',
				'active_text_color'      => '#ffffff',
				'border_radius'          => '16',
				'item_spacing'           => '4',
				'font_size'              => '14',
				'font_weight'            => '500',
				'submenu_bg_color'       => '#ecfdf5',
				'submenu_text_color'     => '#064e3b',
				'submenu_indent'         => '16',
			),
			
			// Section 4: Dashboard Widgets (10 options)
			'dashboard_widgets' => array(
				'background_color' => '#ffffff',
				'border_color'     => '#d1fae5',
				'border_radius'    => '16',
				'box_shadow'       => '0 4px 12px rgba(16, 185, 129, 0.15)',
				'title_color'      => '#064e3b',
				'title_size'       => '18',
				'title_weight'     => '600',
				'text_color'       => '#047857',
				'padding'          => '24',
				'margin'           => '16',
			),
			
			// Section 5: Form Controls (10 options)
			'form_controls' => array(
				'input_bg'               => '#ffffff',
				'input_border'           => '#d1fae5',
				'input_border_radius'    => '8',
				'input_text_color'       => '#064e3b',
				'input_placeholder_color' => '#6ee7b7',
				'input_focus_border'     => '#10b981',
				'input_focus_shadow'     => '0 0 0 3px rgba(16, 185, 129, 0.2)',
				'label_color'            => '#064e3b',
				'label_size'             => '14',
				'label_weight'           => '500',
			),
			
			// Section 6: Buttons (10 options)
			'buttons' => array(
				'primary_bg'           => '#10b981',
				'primary_text'         => '#ffffff',
				'primary_hover_bg'     => '#059669',
				'primary_border_radius' => '8',
				'primary_shadow'       => '0 2px 8px rgba(16, 185, 129, 0.2)',
				'secondary_bg'         => '#84cc16',
				'secondary_text'       => '#ffffff',
				'secondary_hover_bg'   => '#65a30d',
				'danger_bg'            => '#ef4444',
				'danger_text'          => '#ffffff',
				'danger_hover_bg'      => '#dc2626',
			),
			
			// Section 7: Backgrounds (10+ options)
			'backgrounds' => array(
				'enabled'                 => true,
				'background_color'        => '#f0fdf4',
				'background_opacity'      => '1',
				'type'                    => 'gradient',
				'gradient_type'           => 'linear',
				'gradient_start'          => '#f0fdf4',
				'gradient_end'            => '#d1fae5',
				'gradient_angle'          => '135',
				'wpbody_content_color'    => 'transparent',
				'wpbody_content_opacity'  => '1',
			),
			
			// Section 8: Typography (10 options)
			'typography' => array(
				'body_font'        => 'Inter',
				'body_size'        => '14',
				'body_line_height' => 1.6,
				'body_color'       => '#064e3b',
				'heading_font'     => 'Inter',
				'heading_weight'   => '600',
				'heading_color'    => '#064e3b',
				'h1_size'          => '32',
				'h2_size'          => '24',
				'h3_size'          => '20',
			),
			
			// Section 9: Effects (8 options)
			'effects' => array(
				'glassmorphism_enabled' => true,
				'glassmorphism_blur'    => '12',
				'glassmorphism_opacity' => 0.85,
				'animations_enabled'    => true,
				'animation_speed'       => '0.3s',
				'hover_scale'           => 1.02,
				'hover_lift'            => '2',
				'shadow_color'          => 'rgba(16, 185, 129, 0.15)',
			),
			
			// Section 10: Login Page (10 options)
			'login_page' => array(
				'background_type'   => 'gradient',
				'background_color'  => '#10b981',
				'gradient_start'    => '#10b981',
				'gradient_end'      => '#059669',
				'form_bg'           => '#ffffff',
				'form_border_radius' => '16',
				'form_shadow'       => '0 20px 40px rgba(16, 185, 129, 0.2)',
				'logo_url'          => '',
				'button_bg'         => '#10b981',
				'button_text'       => '#ffffff',
				'link_color'        => '#059669',
			),
		),
	),
	
	/**
	 * Minimalist Gray Palette
	 * 
	 * Clean, simple, focused design with neutral colors that conveys simplicity and clarity.
	 * Perfect for distraction-free, content-focused WordPress admin panels.
	 */
	'minimalist_gray' => array(
		'id'            => 'minimalist_gray',
		'name'          => 'Minimalist Gray',
		'description'   => 'Clean minimalist design with neutral gray tones',
		'category'      => 'minimal',
		'preview_image' => 'minimalist-gray.png',
		'author'        => 'WOOW! Admin',
		'version'       => '1.0.0',
		
		// Quick reference color scheme
		'colors' => array(
			'primary'    => '#6b7280',
			'secondary'  => '#374151',
			'accent'     => '#3b82f6',
			'background' => '#ffffff',
			'text'       => '#111827',
		),
		
		// Complete settings configuration
		'settings' => array(
			
			// Section 1: Color Overrides (7 colors)
			'color_overrides' => array(
				'primary_color'   => '#6b7280',
				'secondary_color' => '#374151',
				'accent_color'    => '#3b82f6',
				'success_color'   => '#10b981',
				'warning_color'   => '#f59e0b',
				'error_color'     => '#ef4444',
				'info_color'      => '#3b82f6',
			),
			
			// Section 2: Admin Bar (25+ options)
			'admin_bar' => array(
				'enabled'                => true,
				'background_type'        => 'solid',
				'background_color'       => '#f3f4f6',
				'gradient_start'         => '#f3f4f6',
				'gradient_end'           => '#e5e7eb',
				'base_color'             => '#f3f4f6',
				'opacity'                => 1.0,
				'blur_strength'          => '0',
				'text_color'             => '#111827',
				'hover_style'            => 'normal',
				'hover_bg_color'         => '#e5e7eb',
				'hover_text_color'       => '#111827',
				'height'                 => '40',
				'width'                  => '100',
				'width_unit'             => '%',
				'border_radius_mode'     => 'all',
				'border_radius_all'      => '0',
				'font_size'              => '14',
				'font_weight'            => '400',
				'spacing_mode'           => 'all',
				'spacing_all'            => '12',
				'margin_mode'            => 'all',
				'margin_all'             => '0',
				'glassmorphism'          => false,
				'shadow_style'           => 'none',
				'position'               => 'fixed',
				'top_offset'             => '0',
				'submenu_bg_color'       => '#ffffff',
				'submenu_text_color'     => '#111827',
				'submenu_border_radius'  => '0',
				'submenu_font_size'      => '13',
				'custom_css'             => '',
			),
			
			// Section 3: Admin Menu (15+ options)
			'admin_menu' => array(
				'enabled'                => true,
				'background_type'        => 'solid',
				'background_color'       => '#ffffff',
				'gradient_start'         => '#ffffff',
				'gradient_end'           => '#f9fafb',
				'base_color'             => '#ffffff',
				'opacity'                => 1.0,
				'blur_strength'          => '0',
				'text_color'             => '#374151',
				'icon_color'             => '#6b7280',
				'hover_bg_color'         => '#f3f4f6',
				'hover_text_color'       => '#111827',
				'active_bg_color'        => '#3b82f6',
				'active_text_color'      => '#ffffff',
				'border_radius'          => '0',
				'item_spacing'           => '2',
				'font_size'              => '14',
				'font_weight'            => '400',
				'submenu_bg_color'       => '#f9fafb',
				'submenu_text_color'     => '#374151',
				'submenu_indent'         => '16',
			),
			
			// Section 4: Dashboard Widgets (10 options)
			'dashboard_widgets' => array(
				'background_color' => '#ffffff',
				'border_color'     => '#e5e7eb',
				'border_radius'    => '0',
				'box_shadow'       => 'none',
				'title_color'      => '#111827',
				'title_size'       => '16',
				'title_weight'     => '600',
				'text_color'       => '#374151',
				'padding'          => '16',
				'margin'           => '12',
			),
			
			// Section 5: Form Controls (10 options)
			'form_controls' => array(
				'input_bg'               => '#ffffff',
				'input_border'           => '#d1d5db',
				'input_border_radius'    => '2',
				'input_text_color'       => '#111827',
				'input_placeholder_color' => '#9ca3af',
				'input_focus_border'     => '#3b82f6',
				'input_focus_shadow'     => 'none',
				'label_color'            => '#374151',
				'label_size'             => '14',
				'label_weight'           => '500',
			),
			
			// Section 6: Buttons (10 options)
			'buttons' => array(
				'primary_bg'           => '#3b82f6',
				'primary_text'         => '#ffffff',
				'primary_hover_bg'     => '#2563eb',
				'primary_border_radius' => '2',
				'primary_shadow'       => 'none',
				'secondary_bg'         => '#6b7280',
				'secondary_text'       => '#ffffff',
				'secondary_hover_bg'   => '#4b5563',
				'danger_bg'            => '#ef4444',
				'danger_text'          => '#ffffff',
				'danger_hover_bg'      => '#dc2626',
			),
			
			// Section 7: Backgrounds (10+ options)
			'backgrounds' => array(
				'enabled'                 => true,
				'background_color'        => '#ffffff',
				'background_opacity'      => '1',
				'type'                    => 'gradient',
				'gradient_type'           => 'linear',
				'gradient_start'          => '#ffffff',
				'gradient_end'            => '#f9fafb',
				'gradient_angle'          => '135',
				'wpbody_content_color'    => 'transparent',
				'wpbody_content_opacity'  => '1',
			),
			
			// Section 8: Typography (10 options)
			'typography' => array(
				'body_font'        => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
				'body_size'        => '14',
				'body_line_height' => 1.5,
				'body_color'       => '#374151',
				'heading_font'     => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
				'heading_weight'   => '600',
				'heading_color'    => '#111827',
				'h1_size'          => '28',
				'h2_size'          => '22',
				'h3_size'          => '18',
			),
			
			// Section 9: Effects (8 options)
			'effects' => array(
				'glassmorphism_enabled' => false,
				'glassmorphism_blur'    => '0',
				'glassmorphism_opacity' => 1.0,
				'animations_enabled'    => false,
				'animation_speed'       => '0s',
				'hover_scale'           => 1.0,
				'hover_lift'            => '0',
				'shadow_color'          => 'rgba(0, 0, 0, 0.05)',
			),
			
			// Section 10: Login Page (10 options)
			'login_page' => array(
				'background_type'   => 'solid',
				'background_color'  => '#f9fafb',
				'gradient_start'    => '#f9fafb',
				'gradient_end'      => '#ffffff',
				'form_bg'           => '#ffffff',
				'form_border_radius' => '0',
				'form_shadow'       => '0 1px 3px rgba(0, 0, 0, 0.1)',
				'logo_url'          => '',
				'button_bg'         => '#3b82f6',
				'button_text'       => '#ffffff',
				'link_color'        => '#3b82f6',
			),
		),
	),
	
	/**
	 * Vibrant Purple Palette
	 * 
	 * Bold, creative, artistic design with vibrant purple and pink colors.
	 * Perfect for creative agencies, artistic portfolios, and bold WordPress admin panels.
	 */
	'vibrant_purple' => array(
		'id'            => 'vibrant_purple',
		'name'          => 'Vibrant Purple',
		'description'   => 'Bold creative design with vibrant purple and pink tones',
		'category'      => 'vibrant',
		'preview_image' => 'vibrant-purple.png',
		'author'        => 'WOOW! Admin',
		'version'       => '1.0.0',
		
		// Quick reference color scheme
		'colors' => array(
			'primary'    => '#a855f7',
			'secondary'  => '#ec4899',
			'accent'     => '#f59e0b',
			'background' => '#faf5ff',
			'text'       => '#581c87',
		),
		
		// Complete settings configuration
		'settings' => array(
			
			// Section 1: Color Overrides (7 colors)
			'color_overrides' => array(
				'primary_color'   => '#a855f7',
				'secondary_color' => '#ec4899',
				'accent_color'    => '#f59e0b',
				'success_color'   => '#10b981',
				'warning_color'   => '#f59e0b',
				'error_color'     => '#ef4444',
				'info_color'      => '#06b6d4',
			),
			
			// Section 2: Admin Bar (25+ options)
			'admin_bar' => array(
				'enabled'                => true,
				'background_type'        => 'gradient',
				'background_color'       => '#a855f7',
				'gradient_start'         => '#a855f7',
				'gradient_end'           => '#ec4899',
				'base_color'             => '#a855f7',
				'opacity'                => 0.9,
				'blur_strength'          => '16',
				'text_color'             => '#ffffff',
				'hover_style'            => 'glow',
				'hover_bg_color'         => '#ffffff',
				'hover_text_color'       => '#a855f7',
				'height'                 => '52',
				'width'                  => '100',
				'width_unit'             => '%',
				'border_radius_mode'     => 'all',
				'border_radius_all'      => '0',
				'font_size'              => '14',
				'font_weight'            => '700',
				'spacing_mode'           => 'all',
				'spacing_all'            => '20',
				'margin_mode'            => 'all',
				'margin_all'             => '0',
				'glassmorphism'          => true,
				'shadow_style'           => 'xl',
				'position'               => 'fixed',
				'top_offset'             => '0',
				'submenu_bg_color'       => '#faf5ff',
				'submenu_text_color'     => '#581c87',
				'submenu_border_radius'  => '12',
				'submenu_font_size'      => '13',
				'custom_css'             => '',
			),
			
			// Section 3: Admin Menu (15+ options)
			'admin_menu' => array(
				'enabled'                => true,
				'background_type'        => 'glassmorphism',
				'background_color'       => '#faf5ff',
				'gradient_start'         => '#faf5ff',
				'gradient_end'           => '#f3e8ff',
				'base_color'             => '#faf5ff',
				'opacity'                => 0.85,
				'blur_strength'          => '16',
				'text_color'             => '#581c87',
				'icon_color'             => '#a855f7',
				'hover_bg_color'         => 'rgba(168, 85, 247, 0.15)',
				'hover_text_color'       => '#7c3aed',
				'active_bg_color'        => '#a855f7',
				'active_text_color'      => '#ffffff',
				'border_radius'          => '16',
				'item_spacing'           => '4',
				'font_size'              => '14',
				'font_weight'            => '600',
				'submenu_bg_color'       => '#f5f3ff',
				'submenu_text_color'     => '#581c87',
				'submenu_indent'         => '16',
			),
			
			// Section 4: Dashboard Widgets (10 options)
			'dashboard_widgets' => array(
				'background_color' => '#ffffff',
				'border_color'     => '#e9d5ff',
				'border_radius'    => '20',
				'box_shadow'       => '0 8px 24px rgba(168, 85, 247, 0.2)',
				'title_color'      => '#581c87',
				'title_size'       => '20',
				'title_weight'     => '700',
				'text_color'       => '#7c3aed',
				'padding'          => '28',
				'margin'           => '20',
			),
			
			// Section 5: Form Controls (10 options)
			'form_controls' => array(
				'input_bg'               => '#ffffff',
				'input_border'           => '#e9d5ff',
				'input_border_radius'    => '10',
				'input_text_color'       => '#581c87',
				'input_placeholder_color' => '#c084fc',
				'input_focus_border'     => '#a855f7',
				'input_focus_shadow'     => '0 0 0 4px rgba(168, 85, 247, 0.25)',
				'label_color'            => '#581c87',
				'label_size'             => '14',
				'label_weight'           => '700',
			),
			
			// Section 6: Buttons (10 options)
			'buttons' => array(
				'primary_bg'           => '#a855f7',
				'primary_text'         => '#ffffff',
				'primary_hover_bg'     => '#9333ea',
				'primary_border_radius' => '10',
				'primary_shadow'       => '0 8px 20px rgba(168, 85, 247, 0.4)',
				'secondary_bg'         => '#ec4899',
				'secondary_text'       => '#ffffff',
				'secondary_hover_bg'   => '#db2777',
				'danger_bg'            => '#ef4444',
				'danger_text'          => '#ffffff',
				'danger_hover_bg'      => '#dc2626',
			),
			
			// Section 7: Backgrounds (10+ options)
			'backgrounds' => array(
				'enabled'                 => true,
				'background_color'        => '#faf5ff',
				'background_opacity'      => '1',
				'type'                    => 'gradient',
				'gradient_type'           => 'linear',
				'gradient_start'          => '#faf5ff',
				'gradient_end'            => '#f5f3ff',
				'gradient_angle'          => '135',
				'wpbody_content_color'    => 'transparent',
				'wpbody_content_opacity'  => '1',
			),
			
			// Section 8: Typography (10 options)
			'typography' => array(
				'body_font'        => 'Inter',
				'body_size'        => '14',
				'body_line_height' => 1.6,
				'body_color'       => '#581c87',
				'heading_font'     => 'Inter',
				'heading_weight'   => '700',
				'heading_color'    => '#581c87',
				'h1_size'          => '36',
				'h2_size'          => '28',
				'h3_size'          => '22',
			),
			
			// Section 9: Effects (8 options)
			'effects' => array(
				'glassmorphism_enabled' => true,
				'glassmorphism_blur'    => '16',
				'glassmorphism_opacity' => 0.85,
				'animations_enabled'    => true,
				'animation_speed'       => '0.25s',
				'hover_scale'           => 1.05,
				'hover_lift'            => '6',
				'shadow_color'          => 'rgba(168, 85, 247, 0.25)',
			),
			
			// Section 10: Login Page (10 options)
			'login_page' => array(
				'background_type'   => 'gradient',
				'background_color'  => '#a855f7',
				'gradient_start'    => '#a855f7',
				'gradient_end'      => '#ec4899',
				'form_bg'           => '#ffffff',
				'form_border_radius' => '20',
				'form_shadow'       => '0 20px 50px rgba(168, 85, 247, 0.3)',
				'logo_url'          => '',
				'button_bg'         => '#a855f7',
				'button_text'       => '#ffffff',
				'link_color'        => '#ec4899',
			),
		),
	),
	
	/**
	 * Ocean Blue Palette
	 * 
	 * Calm, professional, fluid design with ocean-inspired blue colors.
	 * Perfect for professional, soothing WordPress admin panels with water-like effects.
	 */
	'ocean_blue' => array(
		'id'            => 'ocean_blue',
		'name'          => 'Ocean Blue',
		'description'   => 'Calm professional design with ocean-inspired blue tones',
		'category'      => 'professional',
		'preview_image' => 'ocean-blue.png',
		'author'        => 'WOOW! Admin',
		'version'       => '1.0.0',
		
		// Quick reference color scheme
		'colors' => array(
			'primary'    => '#0ea5e9',
			'secondary'  => '#0284c7',
			'accent'     => '#06b6d4',
			'background' => '#f0f9ff',
			'text'       => '#0c4a6e',
		),
		
		// Complete settings configuration
		'settings' => array(
			
			// Section 1: Color Overrides (7 colors)
			'color_overrides' => array(
				'primary_color'   => '#0ea5e9',
				'secondary_color' => '#0284c7',
				'accent_color'    => '#06b6d4',
				'success_color'   => '#10b981',
				'warning_color'   => '#f59e0b',
				'error_color'     => '#ef4444',
				'info_color'      => '#06b6d4',
			),
			
			// Section 2: Admin Bar (25+ options)
			'admin_bar' => array(
				'enabled'                => true,
				'background_type'        => 'gradient',
				'background_color'       => '#0ea5e9',
				'gradient_start'         => '#0ea5e9',
				'gradient_end'           => '#0284c7',
				'base_color'             => '#0284c7',
				'opacity'                => 0.92,
				'blur_strength'          => '14',
				'text_color'             => '#ffffff',
				'hover_style'            => 'highlight',
				'hover_bg_color'         => '#ffffff',
				'hover_text_color'       => '#0284c7',
				'height'                 => '48',
				'width'                  => '100',
				'width_unit'             => '%',
				'border_radius_mode'     => 'all',
				'border_radius_all'      => '0',
				'font_size'              => '14',
				'font_weight'            => '500',
				'spacing_mode'           => 'all',
				'spacing_all'            => '18',
				'margin_mode'            => 'all',
				'margin_all'             => '0',
				'glassmorphism'          => true,
				'shadow_style'           => 'md',
				'position'               => 'fixed',
				'top_offset'             => '0',
				'submenu_bg_color'       => '#f0f9ff',
				'submenu_text_color'     => '#0c4a6e',
				'submenu_border_radius'  => '10',
				'submenu_font_size'      => '13',
				'custom_css'             => '',
			),
			
			// Section 3: Admin Menu (15+ options)
			'admin_menu' => array(
				'enabled'                => true,
				'background_type'        => 'glassmorphism',
				'background_color'       => '#f0f9ff',
				'gradient_start'         => '#f0f9ff',
				'gradient_end'           => '#e0f2fe',
				'base_color'             => '#f0f9ff',
				'opacity'                => 0.88,
				'blur_strength'          => '14',
				'text_color'             => '#0c4a6e',
				'icon_color'             => '#0ea5e9',
				'hover_bg_color'         => 'rgba(14, 165, 233, 0.12)',
				'hover_text_color'       => '#0284c7',
				'active_bg_color'        => '#0ea5e9',
				'active_text_color'      => '#ffffff',
				'border_radius'          => '12',
				'item_spacing'           => '4',
				'font_size'              => '14',
				'font_weight'            => '500',
				'submenu_bg_color'       => '#e0f2fe',
				'submenu_text_color'     => '#0c4a6e',
				'submenu_indent'         => '16',
			),
			
			// Section 4: Dashboard Widgets (10 options)
			'dashboard_widgets' => array(
				'background_color' => '#ffffff',
				'border_color'     => '#bae6fd',
				'border_radius'    => '14',
				'box_shadow'       => '0 4px 14px rgba(14, 165, 233, 0.15)',
				'title_color'      => '#0c4a6e',
				'title_size'       => '18',
				'title_weight'     => '600',
				'text_color'       => '#075985',
				'padding'          => '22',
				'margin'           => '16',
			),
			
			// Section 5: Form Controls (10 options)
			'form_controls' => array(
				'input_bg'               => '#ffffff',
				'input_border'           => '#bae6fd',
				'input_border_radius'    => '8',
				'input_text_color'       => '#0c4a6e',
				'input_placeholder_color' => '#7dd3fc',
				'input_focus_border'     => '#0ea5e9',
				'input_focus_shadow'     => '0 0 0 3px rgba(14, 165, 233, 0.18)',
				'label_color'            => '#0c4a6e',
				'label_size'             => '14',
				'label_weight'           => '500',
			),
			
			// Section 6: Buttons (10 options)
			'buttons' => array(
				'primary_bg'           => '#0ea5e9',
				'primary_text'         => '#ffffff',
				'primary_hover_bg'     => '#0284c7',
				'primary_border_radius' => '8',
				'primary_shadow'       => '0 3px 10px rgba(14, 165, 233, 0.25)',
				'secondary_bg'         => '#06b6d4',
				'secondary_text'       => '#ffffff',
				'secondary_hover_bg'   => '#0891b2',
				'danger_bg'            => '#ef4444',
				'danger_text'          => '#ffffff',
				'danger_hover_bg'      => '#dc2626',
			),
			
			// Section 7: Backgrounds (10+ options)
			'backgrounds' => array(
				'enabled'                 => true,
				'background_color'        => '#f0f9ff',
				'background_opacity'      => '1',
				'type'                    => 'gradient',
				'gradient_type'           => 'linear',
				'gradient_start'          => '#f0f9ff',
				'gradient_end'            => '#e0f2fe',
				'gradient_angle'          => '135',
				'wpbody_content_color'    => 'transparent',
				'wpbody_content_opacity'  => '1',
			),
			
			// Section 8: Typography (10 options)
			'typography' => array(
				'body_font'        => 'Inter',
				'body_size'        => '14',
				'body_line_height' => 1.6,
				'body_color'       => '#0c4a6e',
				'heading_font'     => 'Inter',
				'heading_weight'   => '600',
				'heading_color'    => '#0c4a6e',
				'h1_size'          => '32',
				'h2_size'          => '24',
				'h3_size'          => '20',
			),
			
			// Section 9: Effects (8 options)
			'effects' => array(
				'glassmorphism_enabled' => true,
				'glassmorphism_blur'    => '14',
				'glassmorphism_opacity' => 0.88,
				'animations_enabled'    => true,
				'animation_speed'       => '0.35s',
				'hover_scale'           => 1.02,
				'hover_lift'            => '3',
				'shadow_color'          => 'rgba(14, 165, 233, 0.18)',
			),
			
			// Section 10: Login Page (10 options)
			'login_page' => array(
				'background_type'   => 'gradient',
				'background_color'  => '#0ea5e9',
				'gradient_start'    => '#0ea5e9',
				'gradient_end'      => '#0284c7',
				'form_bg'           => '#ffffff',
				'form_border_radius' => '14',
				'form_shadow'       => '0 20px 40px rgba(14, 165, 233, 0.25)',
				'logo_url'          => '',
				'button_bg'         => '#0ea5e9',
				'button_text'       => '#ffffff',
				'link_color'        => '#0284c7',
			),
		),
	),
	
	/**
	 * Cherry Red Palette
	 * 
	 * Bold, energetic, passionate design with vibrant red colors.
	 * Perfect for bold, energetic WordPress admin panels that convey passion and energy.
	 */
	'cherry_red' => array(
		'id'            => 'cherry_red',
		'name'          => 'Cherry Red',
		'description'   => 'Bold energetic design with vibrant red and orange tones',
		'category'      => 'vibrant',
		'preview_image' => 'cherry-red.png',
		'author'        => 'WOOW! Admin',
		'version'       => '1.0.0',
		
		// Quick reference color scheme
		'colors' => array(
			'primary'    => '#ef4444',
			'secondary'  => '#dc2626',
			'accent'     => '#f97316',
			'background' => '#fef2f2',
			'text'       => '#7f1d1d',
		),
		
		// Complete settings configuration
		'settings' => array(
			
			// Section 1: Color Overrides (7 colors)
			'color_overrides' => array(
				'primary_color'   => '#ef4444',
				'secondary_color' => '#dc2626',
				'accent_color'    => '#f97316',
				'success_color'   => '#10b981',
				'warning_color'   => '#f59e0b',
				'error_color'     => '#ef4444',
				'info_color'      => '#06b6d4',
			),
			
			// Section 2: Admin Bar (25+ options)
			'admin_bar' => array(
				'enabled'                => true,
				'background_type'        => 'gradient',
				'background_color'       => '#ef4444',
				'gradient_start'         => '#ef4444',
				'gradient_end'           => '#dc2626',
				'base_color'             => '#dc2626',
				'opacity'                => 0.95,
				'blur_strength'          => '14',
				'text_color'             => '#ffffff',
				'hover_style'            => 'glow',
				'hover_bg_color'         => '#ffffff',
				'hover_text_color'       => '#dc2626',
				'height'                 => '52',
				'width'                  => '100',
				'width_unit'             => '%',
				'border_radius_mode'     => 'all',
				'border_radius_all'      => '0',
				'font_size'              => '15',
				'font_weight'            => '700',
				'spacing_mode'           => 'all',
				'spacing_all'            => '20',
				'margin_mode'            => 'all',
				'margin_all'             => '0',
				'glassmorphism'          => true,
				'shadow_style'           => 'lg',
				'position'               => 'fixed',
				'top_offset'             => '0',
				'submenu_bg_color'       => '#fef2f2',
				'submenu_text_color'     => '#7f1d1d',
				'submenu_border_radius'  => '10',
				'submenu_font_size'      => '14',
				'custom_css'             => '',
			),
			
			// Section 3: Admin Menu (15+ options)
			'admin_menu' => array(
				'enabled'                => true,
				'background_type'        => 'solid',
				'background_color'       => '#7f1d1d',
				'gradient_start'         => '#7f1d1d',
				'gradient_end'           => '#991b1b',
				'base_color'             => '#7f1d1d',
				'opacity'                => 0.95,
				'blur_strength'          => '12',
				'text_color'             => '#fecaca',
				'icon_color'             => '#fca5a5',
				'hover_bg_color'         => 'rgba(239, 68, 68, 0.2)',
				'hover_text_color'       => '#ffffff',
				'active_bg_color'        => '#ef4444',
				'active_text_color'      => '#ffffff',
				'border_radius'          => '10',
				'item_spacing'           => '4',
				'font_size'              => '14',
				'font_weight'            => '700',
				'submenu_bg_color'       => '#991b1b',
				'submenu_text_color'     => '#fecaca',
				'submenu_indent'         => '16',
			),
			
			// Section 4: Dashboard Widgets (10 options)
			'dashboard_widgets' => array(
				'background_color' => '#ffffff',
				'border_color'     => '#fecaca',
				'border_radius'    => '16',
				'box_shadow'       => '0 6px 18px rgba(239, 68, 68, 0.2)',
				'title_color'      => '#7f1d1d',
				'title_size'       => '20',
				'title_weight'     => '700',
				'text_color'       => '#991b1b',
				'padding'          => '24',
				'margin'           => '18',
			),
			
			// Section 5: Form Controls (10 options)
			'form_controls' => array(
				'input_bg'               => '#ffffff',
				'input_border'           => '#fecaca',
				'input_border_radius'    => '8',
				'input_text_color'       => '#7f1d1d',
				'input_placeholder_color' => '#fca5a5',
				'input_focus_border'     => '#ef4444',
				'input_focus_shadow'     => '0 0 0 3px rgba(239, 68, 68, 0.2)',
				'label_color'            => '#7f1d1d',
				'label_size'             => '14',
				'label_weight'           => '700',
			),
			
			// Section 6: Buttons (10 options)
			'buttons' => array(
				'primary_bg'           => '#ef4444',
				'primary_text'         => '#ffffff',
				'primary_hover_bg'     => '#dc2626',
				'primary_border_radius' => '8',
				'primary_shadow'       => '0 6px 16px rgba(239, 68, 68, 0.35)',
				'secondary_bg'         => '#f97316',
				'secondary_text'       => '#ffffff',
				'secondary_hover_bg'   => '#ea580c',
				'danger_bg'            => '#991b1b',
				'danger_text'          => '#ffffff',
				'danger_hover_bg'      => '#7f1d1d',
			),
			
			// Section 7: Backgrounds (10+ options)
			'backgrounds' => array(
				'enabled'                 => true,
				'background_color'        => '#fef2f2',
				'background_opacity'      => '1',
				'type'                    => 'gradient',
				'gradient_type'           => 'linear',
				'gradient_start'          => '#fef2f2',
				'gradient_end'            => '#fee2e2',
				'gradient_angle'          => '135',
				'wpbody_content_color'    => 'transparent',
				'wpbody_content_opacity'  => '1',
			),
			
			// Section 8: Typography (10 options)
			'typography' => array(
				'body_font'        => 'Inter',
				'body_size'        => '14',
				'body_line_height' => 1.6,
				'body_color'       => '#7f1d1d',
				'heading_font'     => 'Inter',
				'heading_weight'   => '700',
				'heading_color'    => '#7f1d1d',
				'h1_size'          => '36',
				'h2_size'          => '28',
				'h3_size'          => '22',
			),
			
			// Section 9: Effects (8 options)
			'effects' => array(
				'glassmorphism_enabled' => true,
				'glassmorphism_blur'    => '14',
				'glassmorphism_opacity' => 0.95,
				'animations_enabled'    => true,
				'animation_speed'       => '0.2s',
				'hover_scale'           => 1.03,
				'hover_lift'            => '4',
				'shadow_color'          => 'rgba(239, 68, 68, 0.25)',
			),
			
			// Section 10: Login Page (10 options)
			'login_page' => array(
				'background_type'   => 'gradient',
				'background_color'  => '#ef4444',
				'gradient_start'    => '#ef4444',
				'gradient_end'      => '#dc2626',
				'form_bg'           => '#ffffff',
				'form_border_radius' => '16',
				'form_shadow'       => '0 20px 50px rgba(239, 68, 68, 0.3)',
				'logo_url'          => '',
				'button_bg'         => '#ef4444',
				'button_text'       => '#ffffff',
				'link_color'        => '#dc2626',
			),
		),
	),
	
	/**
	 * Monochrome Elite Palette
	 * 
	 * Premium, luxury, elite design with black and gold colors.
	 * Perfect for luxury brands, premium services, and elite WordPress admin panels.
	 */
	'monochrome_elite' => array(
		'id'            => 'monochrome_elite',
		'name'          => 'Monochrome Elite',
		'description'   => 'Premium luxury design with black and gold accents',
		'category'      => 'professional',
		'preview_image' => 'monochrome-elite.png',
		'author'        => 'WOOW! Admin',
		'version'       => '1.0.0',
		
		// Quick reference color scheme
		'colors' => array(
			'primary'    => '#000000',
			'secondary'  => '#1f2937',
			'accent'     => '#fbbf24',
			'background' => '#ffffff',
			'text'       => '#111827',
		),
		
		// Complete settings configuration
		'settings' => array(
			
			// Section 1: Color Overrides (7 colors)
			'color_overrides' => array(
				'primary_color'   => '#000000',
				'secondary_color' => '#1f2937',
				'accent_color'    => '#fbbf24',
				'success_color'   => '#10b981',
				'warning_color'   => '#f59e0b',
				'error_color'     => '#ef4444',
				'info_color'      => '#06b6d4',
			),
			
			// Section 2: Admin Bar (25+ options)
			'admin_bar' => array(
				'enabled'                => true,
				'background_type'        => 'solid',
				'background_color'       => '#000000',
				'gradient_start'         => '#000000',
				'gradient_end'           => '#1f2937',
				'base_color'             => '#000000',
				'opacity'                => 0.98,
				'blur_strength'          => '8',
				'text_color'             => '#fbbf24',
				'hover_style'            => 'highlight',
				'hover_bg_color'         => '#fbbf24',
				'hover_text_color'       => '#000000',
				'height'                 => '50',
				'width'                  => '100',
				'width_unit'             => '%',
				'border_radius_mode'     => 'all',
				'border_radius_all'      => '0',
				'font_size'              => '14',
				'font_weight'            => '600',
				'spacing_mode'           => 'all',
				'spacing_all'            => '20',
				'margin_mode'            => 'all',
				'margin_all'             => '0',
				'glassmorphism'          => true,
				'shadow_style'           => 'lg',
				'position'               => 'fixed',
				'top_offset'             => '0',
				'submenu_bg_color'       => '#ffffff',
				'submenu_text_color'     => '#111827',
				'submenu_border_radius'  => '8',
				'submenu_font_size'      => '13',
				'custom_css'             => '',
			),
			
			// Section 3: Admin Menu (15+ options)
			'admin_menu' => array(
				'enabled'                => true,
				'background_type'        => 'solid',
				'background_color'       => '#1f2937',
				'gradient_start'         => '#1f2937',
				'gradient_end'           => '#111827',
				'base_color'             => '#1f2937',
				'opacity'                => 1.0,
				'blur_strength'          => '0',
				'text_color'             => '#d1d5db',
				'icon_color'             => '#fbbf24',
				'hover_bg_color'         => 'rgba(251, 191, 36, 0.1)',
				'hover_text_color'       => '#fbbf24',
				'active_bg_color'        => '#fbbf24',
				'active_text_color'      => '#000000',
				'border_radius'          => '8',
				'item_spacing'           => '4',
				'font_size'              => '14',
				'font_weight'            => '600',
				'submenu_bg_color'       => '#111827',
				'submenu_text_color'     => '#d1d5db',
				'submenu_indent'         => '16',
			),
			
			// Section 4: Dashboard Widgets (10 options)
			'dashboard_widgets' => array(
				'background_color' => '#ffffff',
				'border_color'     => '#000000',
				'border_radius'    => '12',
				'box_shadow'       => '0 4px 12px rgba(0, 0, 0, 0.15)',
				'title_color'      => '#000000',
				'title_size'       => '20',
				'title_weight'     => '700',
				'text_color'       => '#374151',
				'padding'          => '28',
				'margin'           => '20',
			),
			
			// Section 5: Form Controls (10 options)
			'form_controls' => array(
				'input_bg'               => '#ffffff',
				'input_border'           => '#d1d5db',
				'input_border_radius'    => '6',
				'input_text_color'       => '#111827',
				'input_placeholder_color' => '#9ca3af',
				'input_focus_border'     => '#fbbf24',
				'input_focus_shadow'     => '0 0 0 3px rgba(251, 191, 36, 0.2)',
				'label_color'            => '#111827',
				'label_size'             => '14',
				'label_weight'           => '600',
			),
			
			// Section 6: Buttons (10 options)
			'buttons' => array(
				'primary_bg'           => '#000000',
				'primary_text'         => '#fbbf24',
				'primary_hover_bg'     => '#1f2937',
				'primary_border_radius' => '6',
				'primary_shadow'       => '0 4px 12px rgba(0, 0, 0, 0.25)',
				'secondary_bg'         => '#fbbf24',
				'secondary_text'       => '#000000',
				'secondary_hover_bg'   => '#f59e0b',
				'danger_bg'            => '#ef4444',
				'danger_text'          => '#ffffff',
				'danger_hover_bg'      => '#dc2626',
			),
			
			// Section 7: Backgrounds (10+ options)
			'backgrounds' => array(
				'enabled'                 => true,
				'background_color'        => '#ffffff',
				'background_opacity'      => '1',
				'type'                    => 'gradient',
				'gradient_type'           => 'linear',
				'gradient_start'          => '#ffffff',
				'gradient_end'            => '#f9fafb',
				'gradient_angle'          => '135',
				'wpbody_content_color'    => 'transparent',
				'wpbody_content_opacity'  => '1',
			),
			
			// Section 8: Typography (10 options)
			'typography' => array(
				'body_font'        => 'Georgia, serif',
				'body_size'        => '15',
				'body_line_height' => 1.7,
				'body_color'       => '#374151',
				'heading_font'     => 'Georgia, serif',
				'heading_weight'   => '700',
				'heading_color'    => '#000000',
				'h1_size'          => '36',
				'h2_size'          => '28',
				'h3_size'          => '22',
			),
			
			// Section 9: Effects (8 options)
			'effects' => array(
				'glassmorphism_enabled' => true,
				'glassmorphism_blur'    => '8',
				'glassmorphism_opacity' => 0.98,
				'animations_enabled'    => true,
				'animation_speed'       => '0.35s',
				'hover_scale'           => 1.01,
				'hover_lift'            => '2',
				'shadow_color'          => 'rgba(0, 0, 0, 0.15)',
			),
			
			// Section 10: Login Page (10 options)
			'login_page' => array(
				'background_type'   => 'solid',
				'background_color'  => '#ffffff',
				'gradient_start'    => '#ffffff',
				'gradient_end'      => '#f9fafb',
				'form_bg'           => '#ffffff',
				'form_border_radius' => '12',
				'form_shadow'       => '0 20px 40px rgba(0, 0, 0, 0.15)',
				'logo_url'          => '',
				'button_bg'         => '#000000',
				'button_text'       => '#fbbf24',
				'link_color'        => '#fbbf24',
			),
		),
	),
	
	/**
	 * Cyberpunk Neon Palette
	 * 
	 * Futuristic, tech-forward, bold design with neon colors on dark backgrounds.
	 * Perfect for tech-focused, futuristic WordPress admin panels with cyberpunk aesthetics.
	 */
	'cyberpunk_neon' => array(
		'id'            => 'cyberpunk_neon',
		'name'          => 'Cyberpunk Neon',
		'description'   => 'Futuristic design with neon cyan, purple, and pink on dark navy',
		'category'      => 'dark',
		'preview_image' => 'cyberpunk-neon.png',
		'author'        => 'WOOW! Admin',
		'version'       => '1.0.0',
		
		// Quick reference color scheme
		'colors' => array(
			'primary'    => '#06b6d4',
			'secondary'  => '#8b5cf6',
			'accent'     => '#ec4899',
			'background' => '#0a0e27',
			'text'       => '#00ffff',
		),
		
		// Complete settings configuration
		'settings' => array(
			
			// Section 1: Color Overrides (7 colors)
			'color_overrides' => array(
				'primary_color'   => '#06b6d4',
				'secondary_color' => '#8b5cf6',
				'accent_color'    => '#ec4899',
				'success_color'   => '#10b981',
				'warning_color'   => '#f59e0b',
				'error_color'     => '#ef4444',
				'info_color'      => '#06b6d4',
			),
			
			// Section 2: Admin Bar (25+ options)
			'admin_bar' => array(
				'enabled'                => true,
				'background_type'        => 'gradient',
				'background_color'       => '#0a0e27',
				'gradient_start'         => '#06b6d4',
				'gradient_end'           => '#8b5cf6',
				'base_color'             => '#0a0e27',
				'opacity'                => 0.92,
				'blur_strength'          => '18',
				'text_color'             => '#00ffff',
				'hover_style'            => 'glow',
				'hover_bg_color'         => '#06b6d4',
				'hover_text_color'       => '#0a0e27',
				'height'                 => '54',
				'width'                  => '100',
				'width_unit'             => '%',
				'border_radius_mode'     => 'all',
				'border_radius_all'      => '0',
				'font_size'              => '14',
				'font_weight'            => '700',
				'spacing_mode'           => 'all',
				'spacing_all'            => '24',
				'margin_mode'            => 'all',
				'margin_all'             => '0',
				'glassmorphism'          => true,
				'shadow_style'           => 'xl',
				'position'               => 'fixed',
				'top_offset'             => '0',
				'submenu_bg_color'       => '#0a0e27',
				'submenu_text_color'     => '#00ffff',
				'submenu_border_radius'  => '8',
				'submenu_font_size'      => '13',
				'custom_css'             => '',
			),
			
			// Section 3: Admin Menu (15+ options)
			'admin_menu' => array(
				'enabled'                => true,
				'background_type'        => 'solid',
				'background_color'       => '#0a0e27',
				'gradient_start'         => '#0a0e27',
				'gradient_end'           => '#1a1f3a',
				'base_color'             => '#0a0e27',
				'opacity'                => 0.95,
				'blur_strength'          => '16',
				'text_color'             => '#00ffff',
				'icon_color'             => '#06b6d4',
				'hover_bg_color'         => 'rgba(6, 182, 212, 0.15)',
				'hover_text_color'       => '#ec4899',
				'active_bg_color'        => '#06b6d4',
				'active_text_color'      => '#0a0e27',
				'border_radius'          => '8',
				'item_spacing'           => '4',
				'font_size'              => '14',
				'font_weight'            => '700',
				'submenu_bg_color'       => '#1a1f3a',
				'submenu_text_color'     => '#00ffff',
				'submenu_indent'         => '16',
			),
			
			// Section 4: Dashboard Widgets (10 options)
			'dashboard_widgets' => array(
				'background_color' => '#0a0e27',
				'border_color'     => '#06b6d4',
				'border_radius'    => '12',
				'box_shadow'       => '0 0 30px rgba(6, 182, 212, 0.4)',
				'title_color'      => '#00ffff',
				'title_size'       => '20',
				'title_weight'     => '700',
				'text_color'       => '#67e8f9',
				'padding'          => '24',
				'margin'           => '20',
			),
			
			// Section 5: Form Controls (10 options)
			'form_controls' => array(
				'input_bg'               => '#1a1f3a',
				'input_border'           => '#06b6d4',
				'input_border_radius'    => '6',
				'input_text_color'       => '#00ffff',
				'input_placeholder_color' => '#22d3ee',
				'input_focus_border'     => '#ec4899',
				'input_focus_shadow'     => '0 0 0 3px rgba(236, 72, 153, 0.4)',
				'label_color'            => '#00ffff',
				'label_size'             => '13',
				'label_weight'           => '700',
			),
			
			// Section 6: Buttons (10 options)
			'buttons' => array(
				'primary_bg'           => '#06b6d4',
				'primary_text'         => '#0a0e27',
				'primary_hover_bg'     => '#0891b2',
				'primary_border_radius' => '6',
				'primary_shadow'       => '0 0 25px rgba(6, 182, 212, 0.6)',
				'secondary_bg'         => '#8b5cf6',
				'secondary_text'       => '#ffffff',
				'secondary_hover_bg'   => '#7c3aed',
				'danger_bg'            => '#ef4444',
				'danger_text'          => '#ffffff',
				'danger_hover_bg'      => '#dc2626',
			),
			
			// Section 7: Backgrounds (10+ options)
			'backgrounds' => array(
				'enabled'                 => true,
				'background_color'        => '#0a0e27',
				'background_opacity'      => '1',
				'type'                    => 'gradient',
				'gradient_type'           => 'linear',
				'gradient_start'          => '#0a0e27',
				'gradient_end'            => '#0f1629',
				'gradient_angle'          => '135',
				'wpbody_content_color'    => 'transparent',
				'wpbody_content_opacity'  => '1',
			),
			
			// Section 8: Typography (10 options)
			'typography' => array(
				'body_font'        => 'Courier New, monospace',
				'body_size'        => '14',
				'body_line_height' => 1.6,
				'body_color'       => '#67e8f9',
				'heading_font'     => 'Courier New, monospace',
				'heading_weight'   => '700',
				'heading_color'    => '#00ffff',
				'h1_size'          => '36',
				'h2_size'          => '28',
				'h3_size'          => '22',
			),
			
			// Section 9: Effects (8 options)
			'effects' => array(
				'glassmorphism_enabled' => true,
				'glassmorphism_blur'    => '18',
				'glassmorphism_opacity' => 0.92,
				'animations_enabled'    => true,
				'animation_speed'       => '0.25s',
				'hover_scale'           => 1.03,
				'hover_lift'            => '6',
				'shadow_color'          => 'rgba(6, 182, 212, 0.4)',
			),
			
			// Section 10: Login Page (10 options)
			'login_page' => array(
				'background_type'   => 'gradient',
				'background_color'  => '#0a0e27',
				'gradient_start'    => '#0a0e27',
				'gradient_end'      => '#1a1f3a',
				'form_bg'           => '#0f1629',
				'form_border_radius' => '12',
				'form_shadow'       => '0 0 40px rgba(6, 182, 212, 0.5)',
				'logo_url'          => '',
				'button_bg'         => '#06b6d4',
				'button_text'       => '#0a0e27',
				'link_color'        => '#ec4899',
			),
		),
	),
	
);
