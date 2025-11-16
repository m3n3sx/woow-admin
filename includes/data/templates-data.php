<?php
/**
 * Design Templates Data
 *
 * This file contains all predefined design template definitions for WOOW! Admin.
 * Each template configures ALL available plugin options (100+ settings across 10 sections).
 *
 * @package WOOW_Admin
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return all available design templates
 *
 * Each template includes:
 * - id: Unique identifier
 * - name: Display name
 * - description: Brief description
 * - category: minimal, modern, corporate, creative, dark
 * - preview_image: Filename of preview image
 * - author: Creator name
 * - version: Template version
 * - tags: Array of descriptive tags
 * - characteristics: Design characteristics (glassmorphism, gradients, animations, shadows, border_radius)
 * - settings: Complete configuration for all 10 sections
 *
 * @return array Array of template definitions
 */
return array(
	
	/**
	 * Modern Minimal Template
	 * 
	 * Clean, minimalist design focused on content with flat design and minimal colors.
	 * No gradients, no glassmorphism, sharp edges, subtle shadows only.
	 */
	'modern_minimal' => array(
		'id'            => 'modern_minimal',
		'name'          => 'Modern Minimal',
		'description'   => 'Clean, minimalist design focused on content',
		'category'      => 'minimal',
		'preview_image' => 'modern-minimal.png',
		'author'        => 'WOOW! Admin',
		'version'       => '1.0.0',
		'tags'          => array( 'minimal', 'flat', 'clean', 'professional' ),
		
		// Design characteristics
		'characteristics' => array(
			'glassmorphism' => false,
			'gradients'     => false,
			'animations'    => 'none',
			'shadows'       => 'minimal',
			'border_radius' => 'sharp',
		),
		
		// Complete settings configuration
		'settings' => array(
			
			// Section 1: Color Overrides (7 colors)
			'color_overrides' => array(
				'primary_color'   => '#3b82f6',
				'secondary_color' => '#6b7280',
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
				'background_color'       => '#ffffff',
				'gradient_start'         => '#ffffff',
				'gradient_end'           => '#ffffff',
				'base_color'             => '#ffffff',
				'opacity'                => 1.0,
				'blur_strength'          => '0',
				'text_color'             => '#1f2937',
				'hover_style'            => 'normal',
				'hover_bg_color'         => '#f3f4f6',
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
				'shadow_style'           => 'sm',
				'position'               => 'fixed',
				'top_offset'             => '0',
				'submenu_bg_color'       => '#ffffff',
				'submenu_text_color'     => '#1f2937',
				'submenu_border_radius'  => '0',
				'submenu_font_size'      => '13',
				'custom_css'             => '',
			),
			
			// Section 3: Admin Menu (15+ options)
			'admin_menu' => array(
				'enabled'                => true,
				'background_type'        => 'solid',
				'background_color'       => '#f9fafb',
				'gradient_start'         => '#f9fafb',
				'gradient_end'           => '#f9fafb',
				'base_color'             => '#f9fafb',
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
				'submenu_bg_color'       => '#ffffff',
				'submenu_text_color'     => '#374151',
				'submenu_indent'         => '12',
			),
			
			// Section 4: Dashboard Widgets (10 options)
			'dashboard_widgets' => array(
				'background_color' => '#ffffff',
				'border_color'     => '#e5e7eb',
				'border_radius'    => '0',
				'box_shadow'       => '0 1px 2px rgba(0, 0, 0, 0.05)',
				'title_color'      => '#111827',
				'title_size'       => '16',
				'title_weight'     => '600',
				'text_color'       => '#6b7280',
				'padding'          => '16',
				'margin'           => '16',
			),
			
			// Section 5: Form Controls (10 options)
			'form_controls' => array(
				'input_bg'                => '#ffffff',
				'input_border'            => '#d1d5db',
				'input_border_radius'     => '0',
				'input_text_color'        => '#111827',
				'input_placeholder_color' => '#9ca3af',
				'input_focus_border'      => '#3b82f6',
				'input_focus_shadow'      => '0 0 0 2px rgba(59, 130, 246, 0.1)',
				'label_color'             => '#374151',
				'label_size'              => '14',
				'label_weight'            => '500',
			),
			
			// Section 6: Buttons (10 options)
			'buttons' => array(
				'primary_bg'            => '#3b82f6',
				'primary_text'          => '#ffffff',
				'primary_hover_bg'      => '#2563eb',
				'primary_border_radius' => '0',
				'primary_shadow'        => 'none',
				'secondary_bg'          => '#6b7280',
				'secondary_text'        => '#ffffff',
				'secondary_hover_bg'    => '#4b5563',
				'danger_bg'             => '#ef4444',
				'danger_text'           => '#ffffff',
				'danger_hover_bg'       => '#dc2626',
			),
			
			// Section 7: Backgrounds (6 options)
			'backgrounds' => array(
				'body_bg'            => '#ffffff',
				'body_pattern'       => 'none',
				'body_pattern_color' => 'rgba(0, 0, 0, 0)',
				'content_bg'         => '#ffffff',
				'sidebar_bg'         => '#f9fafb',
				'header_bg'          => '#ffffff',
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
				'background_type'    => 'solid',
				'background_color'   => '#ffffff',
				'gradient_start'     => '#ffffff',
				'gradient_end'       => '#ffffff',
				'form_bg'            => '#ffffff',
				'form_border_radius' => '0',
				'form_shadow'        => '0 1px 3px rgba(0, 0, 0, 0.1)',
				'logo_url'           => '',
				'button_bg'          => '#3b82f6',
				'button_text'        => '#ffffff',
				'link_color'         => '#3b82f6',
			),
		),
	),
	
	/**
	 * Glassmorphism Pro Template
	 * 
	 * Full glassmorphism effect with premium look, heavy glass effects everywhere.
	 * Gradient backgrounds, floating elements, strong blur effects, premium shadows.
	 */
	'glassmorphism_pro' => array(
		'id'            => 'glassmorphism_pro',
		'name'          => 'Glassmorphism Pro',
		'description'   => 'Premium glassmorphism design with frosted glass effects',
		'category'      => 'modern',
		'preview_image' => 'glassmorphism-pro.png',
		'author'        => 'WOOW! Admin',
		'version'       => '1.0.0',
		'tags'          => array( 'glassmorphism', 'premium', 'modern', 'blur' ),
		
		// Design characteristics
		'characteristics' => array(
			'glassmorphism' => true,
			'gradients'     => true,
			'animations'    => 'smooth',
			'shadows'       => 'premium',
			'border_radius' => 'rounded',
		),
		
		// Complete settings configuration
		'settings' => array(
			
			// Section 1: Color Overrides (7 colors)
			'color_overrides' => array(
				'primary_color'   => '#6366f1',
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
				'background_color'       => '#6366f1',
				'gradient_start'         => '#6366f1',
				'gradient_end'           => '#8b5cf6',
				'base_color'             => '#6366f1',
				'opacity'                => 0.9,
				'blur_strength'          => '16',
				'text_color'             => '#ffffff',
				'hover_style'            => 'glow',
				'hover_bg_color'         => 'rgba(255, 255, 255, 0.2)',
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
				'shadow_style'           => 'xl',
				'position'               => 'fixed',
				'top_offset'             => '0',
				'submenu_bg_color'       => 'rgba(255, 255, 255, 0.95)',
				'submenu_text_color'     => '#1f2937',
				'submenu_border_radius'  => '12',
				'submenu_font_size'      => '13',
				'custom_css'             => '',
			),
			
			// Section 3: Admin Menu (15+ options)
			'admin_menu' => array(
				'enabled'                => true,
				'background_type'        => 'glassmorphism',
				'background_color'       => '#6366f1',
				'gradient_start'         => '#6366f1',
				'gradient_end'           => '#8b5cf6',
				'base_color'             => '#6366f1',
				'opacity'                => 0.85,
				'blur_strength'          => '16',
				'text_color'             => '#ffffff',
				'icon_color'             => '#ffffff',
				'hover_bg_color'         => 'rgba(255, 255, 255, 0.15)',
				'hover_text_color'       => '#ffffff',
				'active_bg_color'        => 'rgba(255, 255, 255, 0.25)',
				'active_text_color'      => '#ffffff',
				'border_radius'          => '12',
				'item_spacing'           => '6',
				'font_size'              => '14',
				'font_weight'            => '500',
				'submenu_bg_color'       => 'rgba(15, 23, 42, 0.9)',
				'submenu_text_color'     => '#ffffff',
				'submenu_indent'         => '16',
			),
			
			// Section 4: Dashboard Widgets (10 options)
			'dashboard_widgets' => array(
				'background_color' => 'rgba(255, 255, 255, 0.8)',
				'border_color'     => 'rgba(99, 102, 241, 0.2)',
				'border_radius'    => '20',
				'box_shadow'       => '0 8px 32px rgba(0, 0, 0, 0.1)',
				'title_color'      => '#1f2937',
				'title_size'       => '18',
				'title_weight'     => '700',
				'text_color'       => '#6b7280',
				'padding'          => '24',
				'margin'           => '20',
			),
			
			// Section 5: Form Controls (10 options)
			'form_controls' => array(
				'input_bg'                => 'rgba(255, 255, 255, 0.6)',
				'input_border'            => 'rgba(99, 102, 241, 0.3)',
				'input_border_radius'     => '12',
				'input_text_color'        => '#1f2937',
				'input_placeholder_color' => '#9ca3af',
				'input_focus_border'      => '#6366f1',
				'input_focus_shadow'      => '0 0 0 4px rgba(99, 102, 241, 0.1)',
				'label_color'             => '#374151',
				'label_size'              => '14',
				'label_weight'            => '600',
			),
			
			// Section 6: Buttons (10 options)
			'buttons' => array(
				'primary_bg'            => 'linear-gradient(135deg, #6366f1, #8b5cf6)',
				'primary_text'          => '#ffffff',
				'primary_hover_bg'      => 'linear-gradient(135deg, #4f46e5, #7c3aed)',
				'primary_border_radius' => '12',
				'primary_shadow'        => '0 4px 12px rgba(99, 102, 241, 0.3)',
				'secondary_bg'          => 'rgba(107, 114, 128, 0.8)',
				'secondary_text'        => '#ffffff',
				'secondary_hover_bg'    => 'rgba(75, 85, 99, 0.9)',
				'danger_bg'             => '#ef4444',
				'danger_text'           => '#ffffff',
				'danger_hover_bg'       => '#dc2626',
			),
			
			// Section 7: Backgrounds (6 options)
			'backgrounds' => array(
				'body_bg'            => 'linear-gradient(135deg, #f8fafc, #e0e7ff, #f3e8ff)',
				'body_pattern'       => 'none',
				'body_pattern_color' => 'rgba(99, 102, 241, 0.05)',
				'content_bg'         => 'rgba(255, 255, 255, 0.7)',
				'sidebar_bg'         => 'rgba(248, 250, 252, 0.8)',
				'header_bg'          => 'rgba(255, 255, 255, 0.9)',
			),
			
			// Section 8: Typography (10 options)
			'typography' => array(
				'body_font'        => 'Inter, sans-serif',
				'body_size'        => '14',
				'body_line_height' => 1.6,
				'body_color'       => '#374151',
				'heading_font'     => 'Inter, sans-serif',
				'heading_weight'   => '700',
				'heading_color'    => '#111827',
				'h1_size'          => '32',
				'h2_size'          => '26',
				'h3_size'          => '20',
			),
			
			// Section 9: Effects (8 options)
			'effects' => array(
				'glassmorphism_enabled' => true,
				'glassmorphism_blur'    => '16',
				'glassmorphism_opacity' => 0.85,
				'animations_enabled'    => true,
				'animation_speed'       => '0.3s',
				'hover_scale'           => 1.03,
				'hover_lift'            => '4',
				'shadow_color'          => 'rgba(99, 102, 241, 0.2)',
			),
			
			// Section 10: Login Page (10 options)
			'login_page' => array(
				'background_type'    => 'gradient',
				'background_color'   => '#6366f1',
				'gradient_start'     => '#6366f1',
				'gradient_end'       => '#8b5cf6',
				'form_bg'            => 'rgba(255, 255, 255, 0.9)',
				'form_border_radius' => '24',
				'form_shadow'        => '0 20px 60px rgba(99, 102, 241, 0.3)',
				'logo_url'           => '',
				'button_bg'          => 'linear-gradient(135deg, #6366f1, #8b5cf6)',
				'button_text'        => '#ffffff',
				'link_color'         => '#6366f1',
			),
		),
	),
	
	/**
	 * Dark Dashboard Template
	 * 
	 * Complete dark mode setup with dark backgrounds and light text.
	 * Neon accents, strong contrast, glow effects on interactive elements.
	 */
	'dark_dashboard' => array(
		'id'            => 'dark_dashboard',
		'name'          => 'Dark Dashboard',
		'description'   => 'Complete dark mode with neon accents',
		'category'      => 'dark',
		'preview_image' => 'dark-dashboard.png',
		'author'        => 'WOOW! Admin',
		'version'       => '1.0.0',
		'tags'          => array( 'dark', 'neon', 'modern', 'contrast' ),
		
		// Design characteristics
		'characteristics' => array(
			'glassmorphism' => true,
			'gradients'     => true,
			'animations'    => 'smooth',
			'shadows'       => 'glow',
			'border_radius' => 'rounded',
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
				'background_type'        => 'solid',
				'background_color'       => '#0f172a',
				'gradient_start'         => '#0f172a',
				'gradient_end'           => '#1e293b',
				'base_color'             => '#0f172a',
				'opacity'                => 0.95,
				'blur_strength'          => '12',
				'text_color'             => '#f1f5f9',
				'hover_style'            => 'glow',
				'hover_bg_color'         => '#8b5cf6',
				'hover_text_color'       => '#ffffff',
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
				'shadow_style'           => 'lg',
				'position'               => 'fixed',
				'top_offset'             => '0',
				'submenu_bg_color'       => '#1e293b',
				'submenu_text_color'     => '#f1f5f9',
				'submenu_border_radius'  => '8',
				'submenu_font_size'      => '13',
				'custom_css'             => '',
			),
			
			// Section 3: Admin Menu (15+ options)
			'admin_menu' => array(
				'enabled'                => true,
				'background_type'        => 'glassmorphism',
				'background_color'       => '#0f172a',
				'gradient_start'         => '#0f172a',
				'gradient_end'           => '#1e293b',
				'base_color'             => '#0f172a',
				'opacity'                => 0.9,
				'blur_strength'          => '16',
				'text_color'             => '#f1f5f9',
				'icon_color'             => '#94a3b8',
				'hover_bg_color'         => 'rgba(139, 92, 246, 0.2)',
				'hover_text_color'       => '#ffffff',
				'active_bg_color'        => '#8b5cf6',
				'active_text_color'      => '#ffffff',
				'border_radius'          => '12',
				'item_spacing'           => '4',
				'font_size'              => '14',
				'font_weight'            => '500',
				'submenu_bg_color'       => '#1e293b',
				'submenu_text_color'     => '#f1f5f9',
				'submenu_indent'         => '16',
			),
			
			// Section 4: Dashboard Widgets (10 options)
			'dashboard_widgets' => array(
				'background_color' => '#1e293b',
				'border_color'     => '#8b5cf6',
				'border_radius'    => '16',
				'box_shadow'       => '0 0 20px rgba(139, 92, 246, 0.3)',
				'title_color'      => '#f1f5f9',
				'title_size'       => '18',
				'title_weight'     => '700',
				'text_color'       => '#cbd5e1',
				'padding'          => '24',
				'margin'           => '16',
			),
			
			// Section 5: Form Controls (10 options)
			'form_controls' => array(
				'input_bg'                => '#1e293b',
				'input_border'            => '#334155',
				'input_border_radius'     => '8',
				'input_text_color'        => '#f1f5f9',
				'input_placeholder_color' => '#64748b',
				'input_focus_border'      => '#8b5cf6',
				'input_focus_shadow'      => '0 0 0 3px rgba(139, 92, 246, 0.3)',
				'label_color'             => '#cbd5e1',
				'label_size'              => '14',
				'label_weight'            => '600',
			),
			
			// Section 6: Buttons (10 options)
			'buttons' => array(
				'primary_bg'            => '#8b5cf6',
				'primary_text'          => '#ffffff',
				'primary_hover_bg'      => '#7c3aed',
				'primary_border_radius' => '8',
				'primary_shadow'        => '0 0 20px rgba(139, 92, 246, 0.5)',
				'secondary_bg'          => '#334155',
				'secondary_text'        => '#f1f5f9',
				'secondary_hover_bg'    => '#475569',
				'danger_bg'             => '#ef4444',
				'danger_text'           => '#ffffff',
				'danger_hover_bg'       => '#dc2626',
			),
			
			// Section 7: Backgrounds (6 options)
			'backgrounds' => array(
				'body_bg'            => '#0f172a',
				'body_pattern'       => 'dots',
				'body_pattern_color' => 'rgba(139, 92, 246, 0.1)',
				'content_bg'         => '#1e293b',
				'sidebar_bg'         => '#0f172a',
				'header_bg'          => '#0f172a',
			),
			
			// Section 8: Typography (10 options)
			'typography' => array(
				'body_font'        => 'Inter, sans-serif',
				'body_size'        => '14',
				'body_line_height' => 1.6,
				'body_color'       => '#cbd5e1',
				'heading_font'     => 'Inter, sans-serif',
				'heading_weight'   => '700',
				'heading_color'    => '#f1f5f9',
				'h1_size'          => '32',
				'h2_size'          => '26',
				'h3_size'          => '20',
			),
			
			// Section 9: Effects (8 options)
			'effects' => array(
				'glassmorphism_enabled' => true,
				'glassmorphism_blur'    => '16',
				'glassmorphism_opacity' => 0.9,
				'animations_enabled'    => true,
				'animation_speed'       => '0.3s',
				'hover_scale'           => 1.02,
				'hover_lift'            => '3',
				'shadow_color'          => 'rgba(139, 92, 246, 0.3)',
			),
			
			// Section 10: Login Page (10 options)
			'login_page' => array(
				'background_type'    => 'gradient',
				'background_color'   => '#0f172a',
				'gradient_start'     => '#0f172a',
				'gradient_end'       => '#1e293b',
				'form_bg'            => '#1e293b',
				'form_border_radius' => '16',
				'form_shadow'        => '0 0 40px rgba(139, 92, 246, 0.3)',
				'logo_url'           => '',
				'button_bg'          => '#8b5cf6',
				'button_text'        => '#ffffff',
				'link_color'         => '#8b5cf6',
			),
		),
	),
	
	/**
	 * Colorful Creative Template
	 * 
	 * Vibrant, creative, playful design with multiple vibrant colors.
	 * Gradients on multiple elements, rounded corners, playful animations, bold typography.
	 */
	'colorful_creative' => array(
		'id'            => 'colorful_creative',
		'name'          => 'Colorful Creative',
		'description'   => 'Vibrant and playful design with multiple colors',
		'category'      => 'creative',
		'preview_image' => 'colorful-creative.png',
		'author'        => 'WOOW! Admin',
		'version'       => '1.0.0',
		'tags'          => array( 'colorful', 'creative', 'vibrant', 'playful' ),
		
		// Design characteristics
		'characteristics' => array(
			'glassmorphism' => true,
			'gradients'     => true,
			'animations'    => 'playful',
			'shadows'       => 'colorful',
			'border_radius' => 'very-rounded',
		),
		
		// Complete settings configuration
		'settings' => array(
			
			// Section 1: Color Overrides (7 colors)
			'color_overrides' => array(
				'primary_color'   => '#ec4899',
				'secondary_color' => '#8b5cf6',
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
				'background_color'       => '#ec4899',
				'gradient_start'         => '#ec4899',
				'gradient_end'           => '#8b5cf6',
				'base_color'             => '#ec4899',
				'opacity'                => 0.95,
				'blur_strength'          => '12',
				'text_color'             => '#ffffff',
				'hover_style'            => 'highlight',
				'hover_bg_color'         => 'rgba(255, 255, 255, 0.2)',
				'hover_text_color'       => '#ffffff',
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
				'shadow_style'           => 'lg',
				'position'               => 'fixed',
				'top_offset'             => '0',
				'submenu_bg_color'       => '#ffffff',
				'submenu_text_color'     => '#1f2937',
				'submenu_border_radius'  => '16',
				'submenu_font_size'      => '13',
				'custom_css'             => '',
			),
			
			// Section 3: Admin Menu (15+ options)
			'admin_menu' => array(
				'enabled'                => true,
				'background_type'        => 'gradient',
				'background_color'       => '#8b5cf6',
				'gradient_start'         => '#8b5cf6',
				'gradient_end'           => '#ec4899',
				'base_color'             => '#8b5cf6',
				'opacity'                => 0.95,
				'blur_strength'          => '12',
				'text_color'             => '#ffffff',
				'icon_color'             => '#ffffff',
				'hover_bg_color'         => 'rgba(255, 255, 255, 0.2)',
				'hover_text_color'       => '#ffffff',
				'active_bg_color'        => '#f59e0b',
				'active_text_color'      => '#ffffff',
				'border_radius'          => '16',
				'item_spacing'           => '6',
				'font_size'              => '14',
				'font_weight'            => '600',
				'submenu_bg_color'       => '#7c3aed',
				'submenu_text_color'     => '#ffffff',
				'submenu_indent'         => '16',
			),
			
			// Section 4: Dashboard Widgets (10 options)
			'dashboard_widgets' => array(
				'background_color' => '#ffffff',
				'border_color'     => '#ec4899',
				'border_radius'    => '24',
				'box_shadow'       => '0 8px 24px rgba(236, 72, 153, 0.2)',
				'title_color'      => '#1f2937',
				'title_size'       => '20',
				'title_weight'     => '700',
				'text_color'       => '#6b7280',
				'padding'          => '28',
				'margin'           => '20',
			),
			
			// Section 5: Form Controls (10 options)
			'form_controls' => array(
				'input_bg'                => '#ffffff',
				'input_border'            => '#ec4899',
				'input_border_radius'     => '16',
				'input_text_color'        => '#1f2937',
				'input_placeholder_color' => '#9ca3af',
				'input_focus_border'      => '#8b5cf6',
				'input_focus_shadow'      => '0 0 0 4px rgba(139, 92, 246, 0.2)',
				'label_color'             => '#374151',
				'label_size'              => '14',
				'label_weight'            => '700',
			),
			
			// Section 6: Buttons (10 options)
			'buttons' => array(
				'primary_bg'            => 'linear-gradient(135deg, #ec4899, #8b5cf6)',
				'primary_text'          => '#ffffff',
				'primary_hover_bg'      => 'linear-gradient(135deg, #db2777, #7c3aed)',
				'primary_border_radius' => '16',
				'primary_shadow'        => '0 4px 16px rgba(236, 72, 153, 0.4)',
				'secondary_bg'          => '#f59e0b',
				'secondary_text'        => '#ffffff',
				'secondary_hover_bg'    => '#d97706',
				'danger_bg'             => '#ef4444',
				'danger_text'           => '#ffffff',
				'danger_hover_bg'       => '#dc2626',
			),
			
			// Section 7: Backgrounds (6 options)
			'backgrounds' => array(
				'body_bg'            => 'linear-gradient(135deg, #fef3c7, #fce7f3, #ddd6fe)',
				'body_pattern'       => 'dots',
				'body_pattern_color' => 'rgba(236, 72, 153, 0.1)',
				'content_bg'         => '#ffffff',
				'sidebar_bg'         => 'rgba(255, 255, 255, 0.8)',
				'header_bg'          => '#ffffff',
			),
			
			// Section 8: Typography (10 options)
			'typography' => array(
				'body_font'        => 'Poppins, sans-serif',
				'body_size'        => '14',
				'body_line_height' => 1.6,
				'body_color'       => '#374151',
				'heading_font'     => 'Poppins, sans-serif',
				'heading_weight'   => '700',
				'heading_color'    => '#111827',
				'h1_size'          => '36',
				'h2_size'          => '28',
				'h3_size'          => '22',
			),
			
			// Section 9: Effects (8 options)
			'effects' => array(
				'glassmorphism_enabled' => true,
				'glassmorphism_blur'    => '12',
				'glassmorphism_opacity' => 0.95,
				'animations_enabled'    => true,
				'animation_speed'       => '0.4s',
				'hover_scale'           => 1.05,
				'hover_lift'            => '6',
				'shadow_color'          => 'rgba(236, 72, 153, 0.3)',
			),
			
			// Section 10: Login Page (10 options)
			'login_page' => array(
				'background_type'    => 'gradient',
				'background_color'   => '#ec4899',
				'gradient_start'     => '#ec4899',
				'gradient_end'       => '#8b5cf6',
				'form_bg'            => '#ffffff',
				'form_border_radius' => '24',
				'form_shadow'        => '0 20px 60px rgba(236, 72, 153, 0.3)',
				'logo_url'           => '',
				'button_bg'          => 'linear-gradient(135deg, #ec4899, #8b5cf6)',
				'button_text'        => '#ffffff',
				'link_color'         => '#ec4899',
			),
		),
	),
	
	/**
	 * Corporate Blue Template
	 * 
	 * Professional corporate design with blue tones throughout.
	 * Clean lines, professional fonts, subtle effects, trust-building elements.
	 */
	'corporate_blue' => array(
		'id'            => 'corporate_blue',
		'name'          => 'Corporate Blue',
		'description'   => 'Professional corporate design with blue tones',
		'category'      => 'corporate',
		'preview_image' => 'corporate-blue.png',
		'author'        => 'WOOW! Admin',
		'version'       => '1.0.0',
		'tags'          => array( 'corporate', 'professional', 'blue', 'business' ),
		
		// Design characteristics
		'characteristics' => array(
			'glassmorphism' => false,
			'gradients'     => false,
			'animations'    => 'subtle',
			'shadows'       => 'subtle',
			'border_radius' => 'slight',
		),
		
		// Complete settings configuration
		'settings' => array(
			
			// Section 1: Color Overrides (7 colors)
			'color_overrides' => array(
				'primary_color'   => '#1e40af',
				'secondary_color' => '#3b82f6',
				'accent_color'    => '#0ea5e9',
				'success_color'   => '#10b981',
				'warning_color'   => '#f59e0b',
				'error_color'     => '#ef4444',
				'info_color'      => '#0ea5e9',
			),
			
			// Section 2: Admin Bar (25+ options)
			'admin_bar' => array(
				'enabled'                => true,
				'background_type'        => 'solid',
				'background_color'       => '#1e40af',
				'gradient_start'         => '#1e40af',
				'gradient_end'           => '#1e40af',
				'base_color'             => '#1e40af',
				'opacity'                => 1.0,
				'blur_strength'          => '0',
				'text_color'             => '#ffffff',
				'hover_style'            => 'highlight',
				'hover_bg_color'         => '#3b82f6',
				'hover_text_color'       => '#ffffff',
				'height'                 => '46',
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
				'glassmorphism'          => false,
				'shadow_style'           => 'sm',
				'position'               => 'fixed',
				'top_offset'             => '0',
				'submenu_bg_color'       => '#ffffff',
				'submenu_text_color'     => '#1f2937',
				'submenu_border_radius'  => '4',
				'submenu_font_size'      => '13',
				'custom_css'             => '',
			),
			
			// Section 3: Admin Menu (15+ options)
			'admin_menu' => array(
				'enabled'                => true,
				'background_type'        => 'solid',
				'background_color'       => '#f8fafc',
				'gradient_start'         => '#f8fafc',
				'gradient_end'           => '#f8fafc',
				'base_color'             => '#f8fafc',
				'opacity'                => 1.0,
				'blur_strength'          => '0',
				'text_color'             => '#1e40af',
				'icon_color'             => '#3b82f6',
				'hover_bg_color'         => '#e0e7ff',
				'hover_text_color'       => '#1e40af',
				'active_bg_color'        => '#1e40af',
				'active_text_color'      => '#ffffff',
				'border_radius'          => '4',
				'item_spacing'           => '4',
				'font_size'              => '14',
				'font_weight'            => '500',
				'submenu_bg_color'       => '#ffffff',
				'submenu_text_color'     => '#374151',
				'submenu_indent'         => '16',
			),
			
			// Section 4: Dashboard Widgets (10 options)
			'dashboard_widgets' => array(
				'background_color' => '#ffffff',
				'border_color'     => '#e5e7eb',
				'border_radius'    => '8',
				'box_shadow'       => '0 1px 3px rgba(0, 0, 0, 0.1)',
				'title_color'      => '#1e40af',
				'title_size'       => '18',
				'title_weight'     => '600',
				'text_color'       => '#6b7280',
				'padding'          => '20',
				'margin'           => '16',
			),
			
			// Section 5: Form Controls (10 options)
			'form_controls' => array(
				'input_bg'                => '#ffffff',
				'input_border'            => '#d1d5db',
				'input_border_radius'     => '4',
				'input_text_color'        => '#1f2937',
				'input_placeholder_color' => '#9ca3af',
				'input_focus_border'      => '#1e40af',
				'input_focus_shadow'      => '0 0 0 3px rgba(30, 64, 175, 0.1)',
				'label_color'             => '#374151',
				'label_size'              => '14',
				'label_weight'            => '600',
			),
			
			// Section 6: Buttons (10 options)
			'buttons' => array(
				'primary_bg'            => '#1e40af',
				'primary_text'          => '#ffffff',
				'primary_hover_bg'      => '#1e3a8a',
				'primary_border_radius' => '4',
				'primary_shadow'        => '0 1px 2px rgba(0, 0, 0, 0.05)',
				'secondary_bg'          => '#6b7280',
				'secondary_text'        => '#ffffff',
				'secondary_hover_bg'    => '#4b5563',
				'danger_bg'             => '#ef4444',
				'danger_text'           => '#ffffff',
				'danger_hover_bg'       => '#dc2626',
			),
			
			// Section 7: Backgrounds (6 options)
			'backgrounds' => array(
				'body_bg'            => '#f8fafc',
				'body_pattern'       => 'none',
				'body_pattern_color' => 'rgba(0, 0, 0, 0)',
				'content_bg'         => '#ffffff',
				'sidebar_bg'         => '#f8fafc',
				'header_bg'          => '#ffffff',
			),
			
			// Section 8: Typography (10 options)
			'typography' => array(
				'body_font'        => 'Inter, sans-serif',
				'body_size'        => '14',
				'body_line_height' => 1.5,
				'body_color'       => '#374151',
				'heading_font'     => 'Inter, sans-serif',
				'heading_weight'   => '600',
				'heading_color'    => '#1e40af',
				'h1_size'          => '30',
				'h2_size'          => '24',
				'h3_size'          => '20',
			),
			
			// Section 9: Effects (8 options)
			'effects' => array(
				'glassmorphism_enabled' => false,
				'glassmorphism_blur'    => '0',
				'glassmorphism_opacity' => 1.0,
				'animations_enabled'    => true,
				'animation_speed'       => '0.2s',
				'hover_scale'           => 1.0,
				'hover_lift'            => '1',
				'shadow_color'          => 'rgba(0, 0, 0, 0.1)',
			),
			
			// Section 10: Login Page (10 options)
			'login_page' => array(
				'background_type'    => 'solid',
				'background_color'   => '#f8fafc',
				'gradient_start'     => '#f8fafc',
				'gradient_end'       => '#f8fafc',
				'form_bg'            => '#ffffff',
				'form_border_radius' => '8',
				'form_shadow'        => '0 4px 12px rgba(0, 0, 0, 0.1)',
				'logo_url'           => '',
				'button_bg'          => '#1e40af',
				'button_text'        => '#ffffff',
				'link_color'         => '#1e40af',
			),
		),
	),
	
	/**
	 * Material Design Template
	 * 
	 * Google Material Design inspired with elevation-based shadows.
	 * Material shadow system, floating action buttons, ripple effects, card-based layout.
	 */
	'material_design' => array(
		'id'            => 'material_design',
		'name'          => 'Material Design',
		'description'   => 'Google Material Design inspired interface',
		'category'      => 'modern',
		'preview_image' => 'material-design.png',
		'author'        => 'WOOW! Admin',
		'version'       => '1.0.0',
		'tags'          => array( 'material', 'google', 'modern', 'elevation' ),
		
		// Design characteristics
		'characteristics' => array(
			'glassmorphism' => false,
			'gradients'     => false,
			'animations'    => 'material',
			'shadows'       => 'elevation',
			'border_radius' => 'slight',
		),
		
		// Complete settings configuration
		'settings' => array(
			
			// Section 1: Color Overrides (7 colors)
			'color_overrides' => array(
				'primary_color'   => '#6200ea',
				'secondary_color' => '#03dac6',
				'accent_color'    => '#ff6e40',
				'success_color'   => '#00c853',
				'warning_color'   => '#ffab00',
				'error_color'     => '#d50000',
				'info_color'      => '#2979ff',
			),
			
			// Section 2: Admin Bar (25+ options)
			'admin_bar' => array(
				'enabled'                => true,
				'background_type'        => 'solid',
				'background_color'       => '#6200ea',
				'gradient_start'         => '#6200ea',
				'gradient_end'           => '#6200ea',
				'base_color'             => '#6200ea',
				'opacity'                => 1.0,
				'blur_strength'          => '0',
				'text_color'             => '#ffffff',
				'hover_style'            => 'normal',
				'hover_bg_color'         => 'rgba(255, 255, 255, 0.1)',
				'hover_text_color'       => '#ffffff',
				'height'                 => '56',
				'width'                  => '100',
				'width_unit'             => '%',
				'border_radius_mode'     => 'all',
				'border_radius_all'      => '0',
				'font_size'              => '14',
				'font_weight'            => '500',
				'spacing_mode'           => 'all',
				'spacing_all'            => '16',
				'margin_mode'            => 'all',
				'margin_all'             => '0',
				'glassmorphism'          => false,
				'shadow_style'           => 'md',
				'position'               => 'fixed',
				'top_offset'             => '0',
				'submenu_bg_color'       => '#ffffff',
				'submenu_text_color'     => '#212121',
				'submenu_border_radius'  => '4',
				'submenu_font_size'      => '14',
				'custom_css'             => '',
			),
			
			// Section 3: Admin Menu (15+ options)
			'admin_menu' => array(
				'enabled'                => true,
				'background_type'        => 'solid',
				'background_color'       => '#ffffff',
				'gradient_start'         => '#ffffff',
				'gradient_end'           => '#ffffff',
				'base_color'             => '#ffffff',
				'opacity'                => 1.0,
				'blur_strength'          => '0',
				'text_color'             => '#212121',
				'icon_color'             => '#757575',
				'hover_bg_color'         => '#f5f5f5',
				'hover_text_color'       => '#212121',
				'active_bg_color'        => '#e8eaf6',
				'active_text_color'      => '#6200ea',
				'border_radius'          => '4',
				'item_spacing'           => '4',
				'font_size'              => '14',
				'font_weight'            => '500',
				'submenu_bg_color'       => '#fafafa',
				'submenu_text_color'     => '#424242',
				'submenu_indent'         => '16',
			),
			
			// Section 4: Dashboard Widgets (10 options)
			'dashboard_widgets' => array(
				'background_color' => '#ffffff',
				'border_color'     => 'transparent',
				'border_radius'    => '4',
				'box_shadow'       => '0 2px 4px rgba(0, 0, 0, 0.1), 0 4px 8px rgba(0, 0, 0, 0.1)',
				'title_color'      => '#212121',
				'title_size'       => '20',
				'title_weight'     => '500',
				'text_color'       => '#757575',
				'padding'          => '16',
				'margin'           => '16',
			),
			
			// Section 5: Form Controls (10 options)
			'form_controls' => array(
				'input_bg'                => '#ffffff',
				'input_border'            => '#e0e0e0',
				'input_border_radius'     => '4',
				'input_text_color'        => '#212121',
				'input_placeholder_color' => '#9e9e9e',
				'input_focus_border'      => '#6200ea',
				'input_focus_shadow'      => 'none',
				'label_color'             => '#757575',
				'label_size'              => '12',
				'label_weight'            => '500',
			),
			
			// Section 6: Buttons (10 options)
			'buttons' => array(
				'primary_bg'            => '#6200ea',
				'primary_text'          => '#ffffff',
				'primary_hover_bg'      => '#5600d6',
				'primary_border_radius' => '4',
				'primary_shadow'        => '0 2px 4px rgba(0, 0, 0, 0.2)',
				'secondary_bg'          => '#03dac6',
				'secondary_text'        => '#000000',
				'secondary_hover_bg'    => '#00c4b4',
				'danger_bg'             => '#d50000',
				'danger_text'           => '#ffffff',
				'danger_hover_bg'       => '#b00020',
			),
			
			// Section 7: Backgrounds (6 options)
			'backgrounds' => array(
				'body_bg'            => '#fafafa',
				'body_pattern'       => 'none',
				'body_pattern_color' => 'rgba(0, 0, 0, 0)',
				'content_bg'         => '#ffffff',
				'sidebar_bg'         => '#ffffff',
				'header_bg'          => '#ffffff',
			),
			
			// Section 8: Typography (10 options)
			'typography' => array(
				'body_font'        => 'Roboto, sans-serif',
				'body_size'        => '14',
				'body_line_height' => 1.5,
				'body_color'       => '#212121',
				'heading_font'     => 'Roboto, sans-serif',
				'heading_weight'   => '500',
				'heading_color'    => '#212121',
				'h1_size'          => '34',
				'h2_size'          => '24',
				'h3_size'          => '20',
			),
			
			// Section 9: Effects (8 options)
			'effects' => array(
				'glassmorphism_enabled' => false,
				'glassmorphism_blur'    => '0',
				'glassmorphism_opacity' => 1.0,
				'animations_enabled'    => true,
				'animation_speed'       => '0.2s',
				'hover_scale'           => 1.0,
				'hover_lift'            => '2',
				'shadow_color'          => 'rgba(0, 0, 0, 0.2)',
			),
			
			// Section 10: Login Page (10 options)
			'login_page' => array(
				'background_type'    => 'solid',
				'background_color'   => '#fafafa',
				'gradient_start'     => '#fafafa',
				'gradient_end'       => '#fafafa',
				'form_bg'            => '#ffffff',
				'form_border_radius' => '4',
				'form_shadow'        => '0 2px 4px rgba(0, 0, 0, 0.1), 0 4px 8px rgba(0, 0, 0, 0.1)',
				'logo_url'           => '',
				'button_bg'          => '#6200ea',
				'button_text'        => '#ffffff',
				'link_color'         => '#6200ea',
			),
		),
	),
	
	/**
	 * Flat 2.0 Template
	 * 
	 * Modern flat design with flat colors and no gradients.
	 * Bold typography, geometric shapes, bright colors, no shadows.
	 */
	'flat_2' => array(
		'id'            => 'flat_2',
		'name'          => 'Flat 2.0',
		'description'   => 'Modern flat design with bold colors',
		'category'      => 'modern',
		'preview_image' => 'flat-2.png',
		'author'        => 'WOOW! Admin',
		'version'       => '1.0.0',
		'tags'          => array( 'flat', 'modern', 'bold', 'geometric' ),
		
		// Design characteristics
		'characteristics' => array(
			'glassmorphism' => false,
			'gradients'     => false,
			'animations'    => 'none',
			'shadows'       => 'none',
			'border_radius' => 'slight',
		),
		
		// Complete settings configuration
		'settings' => array(
			
			// Section 1: Color Overrides (7 colors)
			'color_overrides' => array(
				'primary_color'   => '#3498db',
				'secondary_color' => '#2ecc71',
				'accent_color'    => '#e74c3c',
				'success_color'   => '#2ecc71',
				'warning_color'   => '#f39c12',
				'error_color'     => '#e74c3c',
				'info_color'      => '#3498db',
			),
			
			// Section 2: Admin Bar (25+ options)
			'admin_bar' => array(
				'enabled'                => true,
				'background_type'        => 'solid',
				'background_color'       => '#34495e',
				'gradient_start'         => '#34495e',
				'gradient_end'           => '#34495e',
				'base_color'             => '#34495e',
				'opacity'                => 1.0,
				'blur_strength'          => '0',
				'text_color'             => '#ecf0f1',
				'hover_style'            => 'normal',
				'hover_bg_color'         => '#2c3e50',
				'hover_text_color'       => '#ffffff',
				'height'                 => '50',
				'width'                  => '100',
				'width_unit'             => '%',
				'border_radius_mode'     => 'all',
				'border_radius_all'      => '0',
				'font_size'              => '15',
				'font_weight'            => '700',
				'spacing_mode'           => 'all',
				'spacing_all'            => '16',
				'margin_mode'            => 'all',
				'margin_all'             => '0',
				'glassmorphism'          => false,
				'shadow_style'           => 'none',
				'position'               => 'fixed',
				'top_offset'             => '0',
				'submenu_bg_color'       => '#ecf0f1',
				'submenu_text_color'     => '#2c3e50',
				'submenu_border_radius'  => '0',
				'submenu_font_size'      => '14',
				'custom_css'             => '',
			),
			
			// Section 3: Admin Menu (15+ options)
			'admin_menu' => array(
				'enabled'                => true,
				'background_type'        => 'solid',
				'background_color'       => '#2c3e50',
				'gradient_start'         => '#2c3e50',
				'gradient_end'           => '#2c3e50',
				'base_color'             => '#2c3e50',
				'opacity'                => 1.0,
				'blur_strength'          => '0',
				'text_color'             => '#ecf0f1',
				'icon_color'             => '#95a5a6',
				'hover_bg_color'         => '#34495e',
				'hover_text_color'       => '#ffffff',
				'active_bg_color'        => '#3498db',
				'active_text_color'      => '#ffffff',
				'border_radius'          => '0',
				'item_spacing'           => '0',
				'font_size'              => '15',
				'font_weight'            => '600',
				'submenu_bg_color'       => '#34495e',
				'submenu_text_color'     => '#ecf0f1',
				'submenu_indent'         => '16',
			),
			
			// Section 4: Dashboard Widgets (10 options)
			'dashboard_widgets' => array(
				'background_color' => '#ffffff',
				'border_color'     => '#bdc3c7',
				'border_radius'    => '4',
				'box_shadow'       => 'none',
				'title_color'      => '#2c3e50',
				'title_size'       => '20',
				'title_weight'     => '700',
				'text_color'       => '#7f8c8d',
				'padding'          => '20',
				'margin'           => '16',
			),
			
			// Section 5: Form Controls (10 options)
			'form_controls' => array(
				'input_bg'                => '#ffffff',
				'input_border'            => '#bdc3c7',
				'input_border_radius'     => '4',
				'input_text_color'        => '#2c3e50',
				'input_placeholder_color' => '#95a5a6',
				'input_focus_border'      => '#3498db',
				'input_focus_shadow'      => 'none',
				'label_color'             => '#34495e',
				'label_size'              => '14',
				'label_weight'            => '700',
			),
			
			// Section 6: Buttons (10 options)
			'buttons' => array(
				'primary_bg'            => '#3498db',
				'primary_text'          => '#ffffff',
				'primary_hover_bg'      => '#2980b9',
				'primary_border_radius' => '4',
				'primary_shadow'        => 'none',
				'secondary_bg'          => '#95a5a6',
				'secondary_text'        => '#ffffff',
				'secondary_hover_bg'    => '#7f8c8d',
				'danger_bg'             => '#e74c3c',
				'danger_text'           => '#ffffff',
				'danger_hover_bg'       => '#c0392b',
			),
			
			// Section 7: Backgrounds (6 options)
			'backgrounds' => array(
				'body_bg'            => '#ecf0f1',
				'body_pattern'       => 'none',
				'body_pattern_color' => 'rgba(0, 0, 0, 0)',
				'content_bg'         => '#ffffff',
				'sidebar_bg'         => '#ecf0f1',
				'header_bg'          => '#ffffff',
			),
			
			// Section 8: Typography (10 options)
			'typography' => array(
				'body_font'        => 'Open Sans, sans-serif',
				'body_size'        => '15',
				'body_line_height' => 1.6,
				'body_color'       => '#34495e',
				'heading_font'     => 'Open Sans, sans-serif',
				'heading_weight'   => '700',
				'heading_color'    => '#2c3e50',
				'h1_size'          => '36',
				'h2_size'          => '28',
				'h3_size'          => '22',
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
				'shadow_color'          => 'rgba(0, 0, 0, 0)',
			),
			
			// Section 10: Login Page (10 options)
			'login_page' => array(
				'background_type'    => 'solid',
				'background_color'   => '#34495e',
				'gradient_start'     => '#34495e',
				'gradient_end'       => '#34495e',
				'form_bg'            => '#ffffff',
				'form_border_radius' => '4',
				'form_shadow'        => 'none',
				'logo_url'           => '',
				'button_bg'          => '#3498db',
				'button_text'        => '#ffffff',
				'link_color'         => '#3498db',
			),
		),
	),
	
	/**
	 * Neumorphism Template
	 * 
	 * Soft UI neumorphic design with soft shadows creating embossed appearance.
	 * Monochrome base colors with subtle depth, raised/inset elements, tactile feel.
	 */
	'neumorphism' => array(
		'id'            => 'neumorphism',
		'name'          => 'Neumorphism',
		'description'   => 'Soft UI design with neumorphic shadows',
		'category'      => 'modern',
		'preview_image' => 'neumorphism.png',
		'author'        => 'WOOW! Admin',
		'version'       => '1.0.0',
		'tags'          => array( 'neumorphism', 'soft', 'tactile', 'modern' ),
		
		// Design characteristics
		'characteristics' => array(
			'glassmorphism' => false,
			'gradients'     => false,
			'animations'    => 'subtle',
			'shadows'       => 'neumorphic',
			'border_radius' => 'rounded',
		),
		
		// Complete settings configuration
		'settings' => array(
			
			// Section 1: Color Overrides (7 colors)
			'color_overrides' => array(
				'primary_color'   => '#6366f1',
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
				'background_type'        => 'solid',
				'background_color'       => '#e0e5ec',
				'gradient_start'         => '#e0e5ec',
				'gradient_end'           => '#e0e5ec',
				'base_color'             => '#e0e5ec',
				'opacity'                => 1.0,
				'blur_strength'          => '0',
				'text_color'             => '#4a5568',
				'hover_style'            => 'normal',
				'hover_bg_color'         => '#d1d9e6',
				'hover_text_color'       => '#2d3748',
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
				'glassmorphism'          => false,
				'shadow_style'           => 'none',
				'position'               => 'fixed',
				'top_offset'             => '0',
				'submenu_bg_color'       => '#e0e5ec',
				'submenu_text_color'     => '#4a5568',
				'submenu_border_radius'  => '12',
				'submenu_font_size'      => '13',
				'custom_css'             => 'box-shadow: 6px 6px 12px #a3b1c6, -6px -6px 12px #ffffff;',
			),
			
			// Section 3: Admin Menu (15+ options)
			'admin_menu' => array(
				'enabled'                => true,
				'background_type'        => 'solid',
				'background_color'       => '#e0e5ec',
				'gradient_start'         => '#e0e5ec',
				'gradient_end'           => '#e0e5ec',
				'base_color'             => '#e0e5ec',
				'opacity'                => 1.0,
				'blur_strength'          => '0',
				'text_color'             => '#4a5568',
				'icon_color'             => '#718096',
				'hover_bg_color'         => '#d1d9e6',
				'hover_text_color'       => '#2d3748',
				'active_bg_color'        => '#6366f1',
				'active_text_color'      => '#ffffff',
				'border_radius'          => '12',
				'item_spacing'           => '6',
				'font_size'              => '14',
				'font_weight'            => '500',
				'submenu_bg_color'       => '#e0e5ec',
				'submenu_text_color'     => '#4a5568',
				'submenu_indent'         => '16',
			),
			
			// Section 4: Dashboard Widgets (10 options)
			'dashboard_widgets' => array(
				'background_color' => '#e0e5ec',
				'border_color'     => 'transparent',
				'border_radius'    => '20',
				'box_shadow'       => '8px 8px 16px #a3b1c6, -8px -8px 16px #ffffff',
				'title_color'      => '#2d3748',
				'title_size'       => '18',
				'title_weight'     => '600',
				'text_color'       => '#718096',
				'padding'          => '24',
				'margin'           => '20',
			),
			
			// Section 5: Form Controls (10 options)
			'form_controls' => array(
				'input_bg'                => '#e0e5ec',
				'input_border'            => 'transparent',
				'input_border_radius'     => '12',
				'input_text_color'        => '#2d3748',
				'input_placeholder_color' => '#a0aec0',
				'input_focus_border'      => 'transparent',
				'input_focus_shadow'      => 'inset 4px 4px 8px #a3b1c6, inset -4px -4px 8px #ffffff',
				'label_color'             => '#4a5568',
				'label_size'              => '14',
				'label_weight'            => '600',
			),
			
			// Section 6: Buttons (10 options)
			'buttons' => array(
				'primary_bg'            => '#e0e5ec',
				'primary_text'          => '#6366f1',
				'primary_hover_bg'      => '#d1d9e6',
				'primary_border_radius' => '12',
				'primary_shadow'        => '6px 6px 12px #a3b1c6, -6px -6px 12px #ffffff',
				'secondary_bg'          => '#e0e5ec',
				'secondary_text'        => '#718096',
				'secondary_hover_bg'    => '#d1d9e6',
				'danger_bg'             => '#e0e5ec',
				'danger_text'           => '#ef4444',
				'danger_hover_bg'       => '#d1d9e6',
			),
			
			// Section 7: Backgrounds (6 options)
			'backgrounds' => array(
				'body_bg'            => '#e0e5ec',
				'body_pattern'       => 'none',
				'body_pattern_color' => 'rgba(0, 0, 0, 0)',
				'content_bg'         => '#e0e5ec',
				'sidebar_bg'         => '#e0e5ec',
				'header_bg'          => '#e0e5ec',
			),
			
			// Section 8: Typography (10 options)
			'typography' => array(
				'body_font'        => 'Inter, sans-serif',
				'body_size'        => '14',
				'body_line_height' => 1.6,
				'body_color'       => '#4a5568',
				'heading_font'     => 'Inter, sans-serif',
				'heading_weight'   => '600',
				'heading_color'    => '#2d3748',
				'h1_size'          => '32',
				'h2_size'          => '26',
				'h3_size'          => '20',
			),
			
			// Section 9: Effects (8 options)
			'effects' => array(
				'glassmorphism_enabled' => false,
				'glassmorphism_blur'    => '0',
				'glassmorphism_opacity' => 1.0,
				'animations_enabled'    => true,
				'animation_speed'       => '0.3s',
				'hover_scale'           => 1.0,
				'hover_lift'            => '0',
				'shadow_color'          => '#a3b1c6',
			),
			
			// Section 10: Login Page (10 options)
			'login_page' => array(
				'background_type'    => 'solid',
				'background_color'   => '#e0e5ec',
				'gradient_start'     => '#e0e5ec',
				'gradient_end'       => '#e0e5ec',
				'form_bg'            => '#e0e5ec',
				'form_border_radius' => '24',
				'form_shadow'        => '12px 12px 24px #a3b1c6, -12px -12px 24px #ffffff',
				'logo_url'           => '',
				'button_bg'          => '#e0e5ec',
				'button_text'        => '#6366f1',
				'link_color'         => '#6366f1',
			),
		),
	),
	
	/**
	 * Retro Wave Template
	 * 
	 * 80s synthwave inspired with neon colors typical of 1980s aesthetic.
	 * Gradient backgrounds, grid patterns, retro typography, glow effects.
	 */
	'retro_wave' => array(
		'id'            => 'retro_wave',
		'name'          => 'Retro Wave',
		'description'   => '80s synthwave inspired with neon colors',
		'category'      => 'creative',
		'preview_image' => 'retro-wave.png',
		'author'        => 'WOOW! Admin',
		'version'       => '1.0.0',
		'tags'          => array( 'retro', 'synthwave', '80s', 'neon' ),
		
		// Design characteristics
		'characteristics' => array(
			'glassmorphism' => false,
			'gradients'     => true,
			'animations'    => 'smooth',
			'shadows'       => 'glow',
			'border_radius' => 'slight',
		),
		
		// Complete settings configuration
		'settings' => array(
			
			// Section 1: Color Overrides (7 colors)
			'color_overrides' => array(
				'primary_color'   => '#ff006e',
				'secondary_color' => '#8338ec',
				'accent_color'    => '#00f5ff',
				'success_color'   => '#06ffa5',
				'warning_color'   => '#ffbe0b',
				'error_color'     => '#ff006e',
				'info_color'      => '#00f5ff',
			),
			
			// Section 2: Admin Bar (25+ options)
			'admin_bar' => array(
				'enabled'                => true,
				'background_type'        => 'gradient',
				'background_color'       => '#1a0033',
				'gradient_start'         => '#1a0033',
				'gradient_end'           => '#2d0052',
				'base_color'             => '#1a0033',
				'opacity'                => 1.0,
				'blur_strength'          => '0',
				'text_color'             => '#00f5ff',
				'hover_style'            => 'glow',
				'hover_bg_color'         => 'rgba(255, 0, 110, 0.3)',
				'hover_text_color'       => '#ff006e',
				'height'                 => '50',
				'width'                  => '100',
				'width_unit'             => '%',
				'border_radius_mode'     => 'all',
				'border_radius_all'      => '0',
				'font_size'              => '14',
				'font_weight'            => '700',
				'spacing_mode'           => 'all',
				'spacing_all'            => '16',
				'margin_mode'            => 'all',
				'margin_all'             => '0',
				'glassmorphism'          => false,
				'shadow_style'           => 'lg',
				'position'               => 'fixed',
				'top_offset'             => '0',
				'submenu_bg_color'       => '#2d0052',
				'submenu_text_color'     => '#00f5ff',
				'submenu_border_radius'  => '0',
				'submenu_font_size'      => '13',
				'custom_css'             => 'border-bottom: 2px solid #ff006e; box-shadow: 0 0 20px rgba(255, 0, 110, 0.5);',
			),
			
			// Section 3: Admin Menu (15+ options)
			'admin_menu' => array(
				'enabled'                => true,
				'background_type'        => 'gradient',
				'background_color'       => '#1a0033',
				'gradient_start'         => '#1a0033',
				'gradient_end'           => '#2d0052',
				'base_color'             => '#1a0033',
				'opacity'                => 1.0,
				'blur_strength'          => '0',
				'text_color'             => '#00f5ff',
				'icon_color'             => '#8338ec',
				'hover_bg_color'         => 'rgba(131, 56, 236, 0.3)',
				'hover_text_color'       => '#ff006e',
				'active_bg_color'        => 'rgba(255, 0, 110, 0.3)',
				'active_text_color'      => '#ff006e',
				'border_radius'          => '0',
				'item_spacing'           => '4',
				'font_size'              => '14',
				'font_weight'            => '700',
				'submenu_bg_color'       => '#2d0052',
				'submenu_text_color'     => '#00f5ff',
				'submenu_indent'         => '16',
			),
			
			// Section 4: Dashboard Widgets (10 options)
			'dashboard_widgets' => array(
				'background_color' => '#2d0052',
				'border_color'     => '#ff006e',
				'border_radius'    => '4',
				'box_shadow'       => '0 0 20px rgba(255, 0, 110, 0.5), 0 0 40px rgba(131, 56, 236, 0.3)',
				'title_color'      => '#00f5ff',
				'title_size'       => '20',
				'title_weight'     => '700',
				'text_color'       => '#b8a9ff',
				'padding'          => '24',
				'margin'           => '20',
			),
			
			// Section 5: Form Controls (10 options)
			'form_controls' => array(
				'input_bg'                => '#1a0033',
				'input_border'            => '#8338ec',
				'input_border_radius'     => '4',
				'input_text_color'        => '#00f5ff',
				'input_placeholder_color' => '#7b68a6',
				'input_focus_border'      => '#ff006e',
				'input_focus_shadow'      => '0 0 10px rgba(255, 0, 110, 0.5)',
				'label_color'             => '#00f5ff',
				'label_size'              => '14',
				'label_weight'            => '700',
			),
			
			// Section 6: Buttons (10 options)
			'buttons' => array(
				'primary_bg'            => '#ff006e',
				'primary_text'          => '#ffffff',
				'primary_hover_bg'      => '#d6005c',
				'primary_border_radius' => '4',
				'primary_shadow'        => '0 0 20px rgba(255, 0, 110, 0.7)',
				'secondary_bg'          => '#8338ec',
				'secondary_text'        => '#ffffff',
				'secondary_hover_bg'    => '#6d28d9',
				'danger_bg'             => '#ff006e',
				'danger_text'           => '#ffffff',
				'danger_hover_bg'       => '#d6005c',
			),
			
			// Section 7: Backgrounds (6 options)
			'backgrounds' => array(
				'body_bg'            => 'linear-gradient(180deg, #1a0033, #0d001a)',
				'body_pattern'       => 'grid',
				'body_pattern_color' => 'rgba(131, 56, 236, 0.2)',
				'content_bg'         => '#2d0052',
				'sidebar_bg'         => '#1a0033',
				'header_bg'          => '#2d0052',
			),
			
			// Section 8: Typography (10 options)
			'typography' => array(
				'body_font'        => '"Courier New", Courier, monospace',
				'body_size'        => '14',
				'body_line_height' => 1.6,
				'body_color'       => '#b8a9ff',
				'heading_font'     => '"Courier New", Courier, monospace',
				'heading_weight'   => '700',
				'heading_color'    => '#00f5ff',
				'h1_size'          => '36',
				'h2_size'          => '28',
				'h3_size'          => '22',
			),
			
			// Section 9: Effects (8 options)
			'effects' => array(
				'glassmorphism_enabled' => false,
				'glassmorphism_blur'    => '0',
				'glassmorphism_opacity' => 1.0,
				'animations_enabled'    => true,
				'animation_speed'       => '0.3s',
				'hover_scale'           => 1.02,
				'hover_lift'            => '3',
				'shadow_color'          => 'rgba(255, 0, 110, 0.5)',
			),
			
			// Section 10: Login Page (10 options)
			'login_page' => array(
				'background_type'    => 'gradient',
				'background_color'   => '#1a0033',
				'gradient_start'     => '#1a0033',
				'gradient_end'       => '#0d001a',
				'form_bg'            => '#2d0052',
				'form_border_radius' => '4',
				'form_shadow'        => '0 0 40px rgba(255, 0, 110, 0.5), 0 0 80px rgba(131, 56, 236, 0.3)',
				'logo_url'           => '',
				'button_bg'          => '#ff006e',
				'button_text'        => '#ffffff',
				'link_color'         => '#00f5ff',
			),
		),
	),
	
	/**
	 * Nature Inspired Template
	 * 
	 * Organic, natural design with green tones and earth colors.
	 * Organic shapes, soft shadows, natural textures, calm mood.
	 */
	'nature_inspired' => array(
		'id'            => 'nature_inspired',
		'name'          => 'Nature Inspired',
		'description'   => 'Organic design with natural colors and soft shadows',
		'category'      => 'creative',
		'preview_image' => 'nature-inspired.png',
		'author'        => 'WOOW! Admin',
		'version'       => '1.0.0',
		'tags'          => array( 'nature', 'organic', 'green', 'calm' ),
		
		// Design characteristics
		'characteristics' => array(
			'glassmorphism' => false,
			'gradients'     => true,
			'animations'    => 'subtle',
			'shadows'       => 'soft',
			'border_radius' => 'organic',
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
				'error_color'     => '#dc2626',
				'info_color'      => '#06b6d4',
			),
			
			// Section 2: Admin Bar (25+ options)
			'admin_bar' => array(
				'enabled'                => true,
				'background_type'        => 'gradient',
				'background_color'       => '#10b981',
				'gradient_start'         => '#10b981',
				'gradient_end'           => '#059669',
				'base_color'             => '#10b981',
				'opacity'                => 0.95,
				'blur_strength'          => '8',
				'text_color'             => '#ffffff',
				'hover_style'            => 'highlight',
				'hover_bg_color'         => 'rgba(255, 255, 255, 0.2)',
				'hover_text_color'       => '#ffffff',
				'height'                 => '48',
				'width'                  => '100',
				'width_unit'             => '%',
				'border_radius_mode'     => 'all',
				'border_radius_all'      => '0',
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
				'submenu_bg_color'       => '#ffffff',
				'submenu_text_color'     => '#064e3b',
				'submenu_border_radius'  => '12',
				'submenu_font_size'      => '13',
				'custom_css'             => '',
			),
			
			// Section 3: Admin Menu (15+ options)
			'admin_menu' => array(
				'enabled'                => true,
				'background_type'        => 'solid',
				'background_color'       => '#f0fdf4',
				'gradient_start'         => '#f0fdf4',
				'gradient_end'           => '#dcfce7',
				'base_color'             => '#f0fdf4',
				'opacity'                => 1.0,
				'blur_strength'          => '0',
				'text_color'             => '#064e3b',
				'icon_color'             => '#10b981',
				'hover_bg_color'         => '#dcfce7',
				'hover_text_color'       => '#064e3b',
				'active_bg_color'        => '#10b981',
				'active_text_color'      => '#ffffff',
				'border_radius'          => '16',
				'item_spacing'           => '6',
				'font_size'              => '14',
				'font_weight'            => '500',
				'submenu_bg_color'       => '#ffffff',
				'submenu_text_color'     => '#064e3b',
				'submenu_indent'         => '16',
			),
			
			// Section 4: Dashboard Widgets (10 options)
			'dashboard_widgets' => array(
				'background_color' => '#ffffff',
				'border_color'     => '#d1fae5',
				'border_radius'    => '20',
				'box_shadow'       => '0 4px 12px rgba(16, 185, 129, 0.1)',
				'title_color'      => '#064e3b',
				'title_size'       => '18',
				'title_weight'     => '600',
				'text_color'       => '#6b7280',
				'padding'          => '24',
				'margin'           => '20',
			),
			
			// Section 5: Form Controls (10 options)
			'form_controls' => array(
				'input_bg'                => '#ffffff',
				'input_border'            => '#d1fae5',
				'input_border_radius'     => '12',
				'input_text_color'        => '#064e3b',
				'input_placeholder_color' => '#9ca3af',
				'input_focus_border'      => '#10b981',
				'input_focus_shadow'      => '0 0 0 3px rgba(16, 185, 129, 0.1)',
				'label_color'             => '#065f46',
				'label_size'              => '14',
				'label_weight'            => '500',
			),
			
			// Section 6: Buttons (10 options)
			'buttons' => array(
				'primary_bg'            => '#10b981',
				'primary_text'          => '#ffffff',
				'primary_hover_bg'      => '#059669',
				'primary_border_radius' => '12',
				'primary_shadow'        => '0 2px 8px rgba(16, 185, 129, 0.2)',
				'secondary_bg'          => '#84cc16',
				'secondary_text'        => '#ffffff',
				'secondary_hover_bg'    => '#65a30d',
				'danger_bg'             => '#dc2626',
				'danger_text'           => '#ffffff',
				'danger_hover_bg'       => '#b91c1c',
			),
			
			// Section 7: Backgrounds (6 options)
			'backgrounds' => array(
				'body_bg'            => 'linear-gradient(135deg, #f0fdf4, #ecfdf5)',
				'body_pattern'       => 'none',
				'body_pattern_color' => 'rgba(16, 185, 129, 0.05)',
				'content_bg'         => '#ffffff',
				'sidebar_bg'         => '#f0fdf4',
				'header_bg'          => '#ffffff',
			),
			
			// Section 8: Typography (10 options)
			'typography' => array(
				'body_font'        => 'Nunito, sans-serif',
				'body_size'        => '14',
				'body_line_height' => 1.6,
				'body_color'       => '#374151',
				'heading_font'     => 'Nunito, sans-serif',
				'heading_weight'   => '600',
				'heading_color'    => '#064e3b',
				'h1_size'          => '32',
				'h2_size'          => '26',
				'h3_size'          => '20',
			),
			
			// Section 9: Effects (8 options)
			'effects' => array(
				'glassmorphism_enabled' => true,
				'glassmorphism_blur'    => '8',
				'glassmorphism_opacity' => 0.95,
				'animations_enabled'    => true,
				'animation_speed'       => '0.3s',
				'hover_scale'           => 1.01,
				'hover_lift'            => '2',
				'shadow_color'          => 'rgba(16, 185, 129, 0.15)',
			),
			
			// Section 10: Login Page (10 options)
			'login_page' => array(
				'background_type'    => 'gradient',
				'background_color'   => '#f0fdf4',
				'gradient_start'     => '#f0fdf4',
				'gradient_end'       => '#dcfce7',
				'form_bg'            => '#ffffff',
				'form_border_radius' => '20',
				'form_shadow'        => '0 10px 30px rgba(16, 185, 129, 0.15)',
				'logo_url'           => '',
				'button_bg'          => '#10b981',
				'button_text'        => '#ffffff',
				'link_color'         => '#10b981',
			),
		),
	),
	
	/**
	 * High Contrast Template
	 * 
	 * Accessibility focused with strong contrast (7:1+ ratio).
	 * Large fonts, clear borders, accessible colors, visible focus indicators.
	 */
	'high_contrast' => array(
		'id'            => 'high_contrast',
		'name'          => 'High Contrast',
		'description'   => 'Accessibility focused with strong contrast',
		'category'      => 'minimal',
		'preview_image' => 'high-contrast.png',
		'author'        => 'WOOW! Admin',
		'version'       => '1.0.0',
		'tags'          => array( 'accessibility', 'high-contrast', 'wcag', 'a11y' ),
		
		// Design characteristics
		'characteristics' => array(
			'glassmorphism' => false,
			'gradients'     => false,
			'animations'    => 'none',
			'shadows'       => 'none',
			'border_radius' => 'sharp',
		),
		
		// Complete settings configuration
		'settings' => array(
			
			// Section 1: Color Overrides (7 colors)
			'color_overrides' => array(
				'primary_color'   => '#0000ff',
				'secondary_color' => '#000000',
				'accent_color'    => '#0000ff',
				'success_color'   => '#008000',
				'warning_color'   => '#ff8c00',
				'error_color'     => '#ff0000',
				'info_color'      => '#0000ff',
			),
			
			// Section 2: Admin Bar (25+ options)
			'admin_bar' => array(
				'enabled'                => true,
				'background_type'        => 'solid',
				'background_color'       => '#000000',
				'gradient_start'         => '#000000',
				'gradient_end'           => '#000000',
				'base_color'             => '#000000',
				'opacity'                => 1.0,
				'blur_strength'          => '0',
				'text_color'             => '#ffffff',
				'hover_style'            => 'normal',
				'hover_bg_color'         => '#0000ff',
				'hover_text_color'       => '#ffffff',
				'height'                 => '56',
				'width'                  => '100',
				'width_unit'             => '%',
				'border_radius_mode'     => 'all',
				'border_radius_all'      => '0',
				'font_size'              => '16',
				'font_weight'            => '700',
				'spacing_mode'           => 'all',
				'spacing_all'            => '16',
				'margin_mode'            => 'all',
				'margin_all'             => '0',
				'glassmorphism'          => false,
				'shadow_style'           => 'none',
				'position'               => 'fixed',
				'top_offset'             => '0',
				'submenu_bg_color'       => '#ffffff',
				'submenu_text_color'     => '#000000',
				'submenu_border_radius'  => '0',
				'submenu_font_size'      => '16',
				'custom_css'             => 'border-bottom: 3px solid #ffffff;',
			),
			
			// Section 3: Admin Menu (15+ options)
			'admin_menu' => array(
				'enabled'                => true,
				'background_type'        => 'solid',
				'background_color'       => '#ffffff',
				'gradient_start'         => '#ffffff',
				'gradient_end'           => '#ffffff',
				'base_color'             => '#ffffff',
				'opacity'                => 1.0,
				'blur_strength'          => '0',
				'text_color'             => '#000000',
				'icon_color'             => '#000000',
				'hover_bg_color'         => '#ffff00',
				'hover_text_color'       => '#000000',
				'active_bg_color'        => '#0000ff',
				'active_text_color'      => '#ffffff',
				'border_radius'          => '0',
				'item_spacing'           => '4',
				'font_size'              => '16',
				'font_weight'            => '700',
				'submenu_bg_color'       => '#f0f0f0',
				'submenu_text_color'     => '#000000',
				'submenu_indent'         => '16',
			),
			
			// Section 4: Dashboard Widgets (10 options)
			'dashboard_widgets' => array(
				'background_color' => '#ffffff',
				'border_color'     => '#000000',
				'border_radius'    => '0',
				'box_shadow'       => 'none',
				'title_color'      => '#000000',
				'title_size'       => '20',
				'title_weight'     => '700',
				'text_color'       => '#000000',
				'padding'          => '24',
				'margin'           => '16',
			),
			
			// Section 5: Form Controls (10 options)
			'form_controls' => array(
				'input_bg'                => '#ffffff',
				'input_border'            => '#000000',
				'input_border_radius'     => '0',
				'input_text_color'        => '#000000',
				'input_placeholder_color' => '#666666',
				'input_focus_border'      => '#0000ff',
				'input_focus_shadow'      => '0 0 0 3px #ffff00',
				'label_color'             => '#000000',
				'label_size'              => '16',
				'label_weight'            => '700',
			),
			
			// Section 6: Buttons (10 options)
			'buttons' => array(
				'primary_bg'            => '#0000ff',
				'primary_text'          => '#ffffff',
				'primary_hover_bg'      => '#000080',
				'primary_border_radius' => '0',
				'primary_shadow'        => 'none',
				'secondary_bg'          => '#000000',
				'secondary_text'        => '#ffffff',
				'secondary_hover_bg'    => '#333333',
				'danger_bg'             => '#ff0000',
				'danger_text'           => '#ffffff',
				'danger_hover_bg'       => '#cc0000',
			),
			
			// Section 7: Backgrounds (6 options)
			'backgrounds' => array(
				'body_bg'            => '#ffffff',
				'body_pattern'       => 'none',
				'body_pattern_color' => 'rgba(0, 0, 0, 0)',
				'content_bg'         => '#ffffff',
				'sidebar_bg'         => '#ffffff',
				'header_bg'          => '#ffffff',
			),
			
			// Section 8: Typography (10 options)
			'typography' => array(
				'body_font'        => 'Arial, sans-serif',
				'body_size'        => '16',
				'body_line_height' => 1.8,
				'body_color'       => '#000000',
				'heading_font'     => 'Arial, sans-serif',
				'heading_weight'   => '700',
				'heading_color'    => '#000000',
				'h1_size'          => '36',
				'h2_size'          => '28',
				'h3_size'          => '22',
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
				'shadow_color'          => 'rgba(0, 0, 0, 0)',
			),
			
			// Section 10: Login Page (10 options)
			'login_page' => array(
				'background_type'    => 'solid',
				'background_color'   => '#ffffff',
				'gradient_start'     => '#ffffff',
				'gradient_end'       => '#ffffff',
				'form_bg'            => '#ffffff',
				'form_border_radius' => '0',
				'form_shadow'        => 'none',
				'logo_url'           => '',
				'button_bg'          => '#0000ff',
				'button_text'        => '#ffffff',
				'link_color'         => '#0000ff',
			),
		),
	),
	
);
