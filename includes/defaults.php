<?php
/**
 * Default values for WOOW! Admin
 * 
 * @package WOOW_Admin
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get default settings for all sections
 * 
 * @return array Default settings
 */
function woow_get_default_settings() {
    return array(
        'admin_bar' => array(
            // Background & Gradient
            'background_color' => '#1e293b',
            'gradient_start' => '#1e293b',
            'gradient_end' => '#0f172a',
            'use_gradient' => true,
            
            // Text
            'text_color' => '#ffffff',
            'hover_text_color' => '#ffffff',
            
            // Hover
            'hover_bg_color' => '#2d3748',
            
            // Dimensions
            'height' => '48',
            'border_radius' => '24',
            
            // Glassmorphism
            'glassmorphism' => true,
            'opacity' => 0.9,
            'blur_strength' => '12',
            
            // Shadow
            'box_shadow' => '0 8px 24px rgba(0, 0, 0, 0.12)',
        ),
        
        'admin_menu' => array(
            // Background
            'background_color' => '#ffffff',
            'border_color' => '#e2e8f0',
            
            // Text
            'text_color' => '#0f172a',
            'hover_text_color' => '#6366f1',
            
            // Hover
            'hover_bg_color' => '#f8fafc',
            
            // Active (Gradient)
            'active_bg_start' => '#6366f1',
            'active_bg_end' => '#8b5cf6',
            'active_text_color' => '#ffffff',
            
            // Dimensions
            'width' => '256',
            'item_height' => '48',
            'item_padding' => '10px 12px',
            'border_radius' => '12',
            
            // Glassmorphism
            'glassmorphism' => true,
            'opacity' => 0.9,
            'blur_strength' => '12',
            
            // Icons
            'icon_size' => '20',
            'icon_color' => '#64748b',
        ),
        
        'dashboard_widgets' => array(
            // Background
            'background_color' => '#ffffff',
            'border_color' => '#e2e8f0',
            
            // Text
            'text_color' => '#0f172a',
            'heading_color' => '#0f172a',
            
            // Dimensions
            'border_radius' => '24',
            'padding' => '24',
            'max_height' => '600',
            
            // Glassmorphism
            'glassmorphism' => true,
            'opacity' => 0.9,
            'blur_strength' => '12',
            
            // Shadow
            'box_shadow' => '0 4px 12px rgba(0, 0, 0, 0.06)',
        ),
        
        'form_controls' => array(
            // Input Background
            'input_bg_color' => '#ffffff',
            'input_border_color' => '#e2e8f0',
            'input_text_color' => '#0f172a',
            
            // Focus
            'input_focus_color' => '#6366f1',
            'input_focus_shadow' => '0 0 0 4px rgba(99, 102, 241, 0.2)',
            
            // Dimensions
            'input_height' => '40',
            'input_padding' => '10px 14px',
            'input_border_radius' => '12',
            
            // Glassmorphism
            'glassmorphism' => true,
            'blur_strength' => '8',
        ),
        
        'buttons' => array(
            // Primary Button
            'primary_bg_color' => '#6366f1',
            'primary_text_color' => '#ffffff',
            'primary_hover_bg' => '#5558e3',
            'primary_hover_shadow' => '0 4px 6px rgba(99, 102, 241, 0.3)',
            
            // Secondary Button
            'secondary_bg_color' => '#ffffff',
            'secondary_border_color' => '#e2e8f0',
            'secondary_text_color' => '#6366f1',
            'secondary_hover_bg' => '#f8fafc',
            
            // Dimensions
            'button_height' => '40',
            'button_padding' => '10px 16px',
            'button_border_radius' => '12',
            
            // Typography
            'button_font_size' => '14',
            'button_font_weight' => '600',
        ),
        
        'backgrounds' => array(
            // Main Background
            'main_bg_color_start' => '#f8fafc',
            'main_bg_color_middle' => '#eff6ff',
            'main_bg_color_end' => '#eef2ff',
            'use_gradient' => true,
            
            // Image
            'image_url' => '',
            'image_size' => 'cover',
            'image_repeat' => 'no-repeat',
            'image_position' => 'center',
            'image_attachment' => 'fixed',
        ),
        
        'typography' => array(
            // Headings
            'h1_color' => '#0f172a',
            'h1_font_size' => '28',
            'h1_font_weight' => '700',
            'h1_line_height' => '1.3',
            
            'h2_color' => '#0f172a',
            'h2_font_size' => '24',
            'h2_font_weight' => '700',
            'h2_line_height' => '1.3',
            
            'h3_color' => '#0f172a',
            'h3_font_size' => '18',
            'h3_font_weight' => '600',
            'h3_line_height' => '1.4',
            
            // Body
            'body_color' => '#0f172a',
            'body_font_size' => '15',
            'body_font_weight' => '400',
            'body_line_height' => '1.6',
            
            // Links
            'link_color' => '#6366f1',
            'link_hover_color' => '#5558e3',
        ),
        
        'visual_effects' => array(
            // Glassmorphism Global
            'global_glassmorphism' => true,
            'global_blur_strength' => '12',
            'global_opacity' => 0.9,
            
            // Animations
            'enable_animations' => true,
            'animation_duration' => '200',
            'animation_easing' => 'cubic-bezier(0.4, 0, 0.2, 1)',
            
            // Shadows
            'enable_shadows' => true,
            'shadow_color' => 'rgba(0, 0, 0, 0.08)',
        ),
        
        'login_page' => array(
            // Background
            'login_bg_color_start' => '#6366f1',
            'login_bg_color_end' => '#8b5cf6',
            'use_gradient' => true,
            
            // Form
            'form_bg_color' => '#ffffff',
            'form_border_color' => '#e2e8f0',
            'form_text_color' => '#0f172a',
            
            // Glassmorphism
            'glassmorphism' => true,
            'opacity' => 0.95,
            'blur_strength' => '16',
            
            // Logo
            'custom_logo' => '',
            'logo_width' => '84',
            'logo_height' => '84',
        ),
    );
}

/**
 * Get default settings for specific section
 * 
 * @param string $section Section name
 * @return array Section defaults
 */
function woow_get_section_defaults($section) {
    $all_defaults = woow_get_default_settings();
    return $all_defaults[$section] ?? array();
}

/**
 * Merge user settings with defaults
 * 
 * @param array $user_settings User settings
 * @return array Merged settings
 */
function woow_merge_with_defaults($user_settings) {
    $defaults = woow_get_default_settings();
    
    $merged = array();
    foreach ($defaults as $section => $section_defaults) {
        $merged[$section] = array_merge(
            $section_defaults,
            $user_settings[$section] ?? array()
        );
    }
    
    return $merged;
}
