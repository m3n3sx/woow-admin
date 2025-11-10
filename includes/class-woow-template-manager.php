<?php
/**
 * WOOW Template Manager
 *
 * Manages design templates and their application.
 * Provides 11 predefined templates with complete configuration overrides.
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WOOW_Template_Manager
 *
 * Manages design templates for the plugin.
 */
class WOOW_Template_Manager {
	/**
	 * Settings manager instance
	 *
	 * @var WOOW_Settings
	 */
	private WOOW_Settings $settings;

	/**
	 * Constructor
	 *
	 * @param WOOW_Settings $settings Settings manager instance.
	 */
	public function __construct( WOOW_Settings $settings ) {
		$this->settings = $settings;
	}

	/**
	 * Get single template by ID
	 *
	 * @param string $template_id Template ID.
	 * @return array|null Template data or null if not found.
	 */
	public function get_template( string $template_id ): ?array {
		$templates = $this->get_all_templates();

		foreach ( $templates as $template ) {
			if ( $template['id'] === $template_id ) {
				return $template;
			}
		}

		return null;
	}

	/**
	 * Get all available templates
	 *
	 * Returns all 11 predefined design templates.
	 *
	 * @return array Array of template data.
	 */
	public function get_all_templates(): array {
		return array(
			$this->get_default_template(),
			$this->get_modern_minimal_template(),
			$this->get_corporate_professional_template(),
			$this->get_creative_agency_template(),
			$this->get_dark_elegant_template(),
			$this->get_pastel_soft_template(),
			$this->get_high_contrast_template(),
			$this->get_minimalist_white_template(),
			$this->get_bold_bright_template(),
			$this->get_material_design_template(),
			$this->get_glassmorphism_pro_template(),
		);
	}

	/**
	 * Apply template
	 *
	 * Overrides all settings with template configuration.
	 * Creates backup before applying.
	 *
	 * @param string $template_id Template ID to apply.
	 * @return bool True on success, false on failure.
	 */
	public function apply_template( string $template_id ): bool {
		$template = $this->get_template( $template_id );

		if ( ! $template || ! isset( $template['settings'] ) ) {
			return false;
		}

		try {
			// Create backup before applying template
			$backup_manager = new WOOW_Backup_Manager( $this->settings );
			$backup_manager->create_backup( 'before_template_' . $template_id );

			// Get current settings
			$current_settings = $this->settings->get_all_settings();

			// Merge template settings with current settings
			$new_settings = array_replace_recursive( $current_settings, $template['settings'] );

			// Update settings
			$result = $this->settings->update_all_settings( $new_settings );

			if ( $result ) {
				// Clear CSS cache
				$cache = new WOOW_Cache_Manager();
				$cache->delete( 'woow_css' );

				return true;
			}

			return false;
		} catch ( Exception $e ) {
			error_log( '[WOOW Admin] Template apply failed: ' . $e->getMessage() );
			return false;
		}
	}

	/**
	 * Create custom template from current settings
	 *
	 * Saves current settings as a custom template.
	 *
	 * @param string $name Template name.
	 * @param string $description Template description.
	 * @return string Template ID on success.
	 * @throws Exception If template creation fails.
	 */
	public function create_custom_template( string $name, string $description = '' ): string {
		$template_id = 'custom_' . sanitize_title( $name ) . '_' . time();

		$template = array(
			'id'          => $template_id,
			'name'        => $name,
			'description' => $description,
			'thumbnail'   => '',
			'custom'      => true,
			'settings'    => $this->settings->get_all_settings(),
			'metadata'    => array(
				'created'   => time(),
				'author'    => get_current_user_id(),
				'version'   => WOOW_VERSION,
			),
		);

		// Store custom template
		$custom_templates = get_option( 'woow_custom_templates', array() );
		$custom_templates[ $template_id ] = $template;
		$result = update_option( 'woow_custom_templates', $custom_templates );

		if ( ! $result ) {
			throw new Exception( 'Failed to create custom template' );
		}

		return $template_id;
	}

	/**
	 * Get custom templates
	 *
	 * Returns user-created custom templates.
	 *
	 * @return array Array of custom templates.
	 */
	public function get_custom_templates(): array {
		return get_option( 'woow_custom_templates', array() );
	}

	/**
	 * Delete custom template
	 *
	 * @param string $template_id Template ID to delete.
	 * @return bool True on success, false on failure.
	 */
	public function delete_custom_template( string $template_id ): bool {
		$custom_templates = $this->get_custom_templates();

		if ( isset( $custom_templates[ $template_id ] ) ) {
			unset( $custom_templates[ $template_id ] );
			return update_option( 'woow_custom_templates', $custom_templates );
		}

		return false;
	}

	/**
	 * Default Template (Figma base with glassmorphism)
	 *
	 * @return array Template data.
	 */
	private function get_default_template(): array {
		return array(
			'id'          => 'default',
			'name'        => __( 'Default', 'woow-admin' ),
			'description' => __( 'Figma base design with glassmorphism effects', 'woow-admin' ),
			'thumbnail'   => WOOW_PLUGIN_URL . 'assets/dist/images/templates/default.png',
			'settings'    => array(
				'admin_bar' => array(
					'glassmorphism' => true,
					'blur_strength' => '12px',
					'border_radius' => '24px',
				),
				'admin_menu' => array(
					'glassmorphism' => true,
					'blur_strength' => '12px',
					'border_radius' => '24px',
				),
				'dashboard_widgets' => array(
					'glassmorphism' => true,
					'blur_strength' => '12px',
					'border_radius' => '24px',
				),
			),
		);
	}

	/**
	 * Modern Minimal Template
	 *
	 * @return array Template data.
	 */
	private function get_modern_minimal_template(): array {
		return array(
			'id'          => 'modern_minimal',
			'name'        => __( 'Modern Minimal', 'woow-admin' ),
			'description' => __( 'Clean design with large spacing and minimal elements', 'woow-admin' ),
			'thumbnail'   => WOOW_PLUGIN_URL . 'assets/dist/images/templates/modern-minimal.png',
			'settings'    => array(
				'admin_bar' => array(
					'height'        => '56px',
					'border_radius' => '16px',
					'glassmorphism' => false,
				),
				'admin_menu' => array(
					'width_expanded' => '280px',
					'border_radius'  => '16px',
					'glassmorphism'  => false,
				),
				'dashboard_widgets' => array(
					'padding'       => '32px',
					'margin_bottom' => '32px',
					'border_radius' => '16px',
					'glassmorphism' => false,
				),
			),
		);
	}

	/**
	 * Corporate Professional Template
	 *
	 * @return array Template data.
	 */
	private function get_corporate_professional_template(): array {
		return array(
			'id'          => 'corporate_professional',
			'name'        => __( 'Corporate Professional', 'woow-admin' ),
			'description' => __( 'Traditional corporate design with formal aesthetics', 'woow-admin' ),
			'thumbnail'   => WOOW_PLUGIN_URL . 'assets/dist/images/templates/corporate.png',
			'settings'    => array(
				'admin_bar' => array(
					'background_type' => 'solid',
					'background_color' => '#1e3a8a',
					'border_radius'    => '8px',
					'glassmorphism'    => false,
				),
				'admin_menu' => array(
					'background_color' => '#f8fafc',
					'border_radius'    => '8px',
					'glassmorphism'    => false,
				),
				'dashboard_widgets' => array(
					'border_radius' => '8px',
					'shadow_style'  => 'sm',
					'glassmorphism' => false,
				),
			),
		);
	}

	/**
	 * Creative Agency Template
	 *
	 * @return array Template data.
	 */
	private function get_creative_agency_template(): array {
		return array(
			'id'          => 'creative_agency',
			'name'        => __( 'Creative Agency', 'woow-admin' ),
			'description' => __( 'Colorful design with bold animations and gradients', 'woow-admin' ),
			'thumbnail'   => WOOW_PLUGIN_URL . 'assets/dist/images/templates/creative.png',
			'settings'    => array(
				'admin_bar' => array(
					'background_type'   => 'gradient',
					'gradient_start'    => '#ec4899',
					'gradient_end'      => '#8b5cf6',
					'border_radius'     => '32px',
					'glassmorphism'     => true,
				),
				'admin_menu' => array(
					'active_gradient_start' => '#ec4899',
					'active_gradient_end'   => '#f472b6',
					'border_radius'         => '32px',
				),
				'effects' => array(
					'animation_duration' => '300ms',
				),
			),
		);
	}

	/**
	 * Dark Elegant Template
	 *
	 * @return array Template data.
	 */
	private function get_dark_elegant_template(): array {
		return array(
			'id'          => 'dark_elegant',
			'name'        => __( 'Dark Elegant', 'woow-admin' ),
			'description' => __( 'Sophisticated dark mode design', 'woow-admin' ),
			'thumbnail'   => WOOW_PLUGIN_URL . 'assets/dist/images/templates/dark-elegant.png',
			'settings'    => array(
				'admin_bar' => array(
					'background_color' => '#0f172a',
					'text_color'       => '#f1f5f9',
					'glassmorphism'    => true,
					'opacity'          => 0.95,
				),
				'admin_menu' => array(
					'background_color' => '#1e293b',
					'glassmorphism'    => true,
					'opacity'          => 0.95,
				),
				'dashboard_widgets' => array(
					'background_color' => '#1e293b',
					'glassmorphism'    => true,
					'opacity'          => 0.95,
				),
			),
		);
	}

	/**
	 * Pastel Soft Template
	 *
	 * @return array Template data.
	 */
	private function get_pastel_soft_template(): array {
		return array(
			'id'          => 'pastel_soft',
			'name'        => __( 'Pastel Soft', 'woow-admin' ),
			'description' => __( 'Delicate pastel colors with soft aesthetics', 'woow-admin' ),
			'thumbnail'   => WOOW_PLUGIN_URL . 'assets/dist/images/templates/pastel.png',
			'settings'    => array(
				'admin_bar' => array(
					'background_type'  => 'gradient',
					'gradient_start'   => '#fae8ff',
					'gradient_end'     => '#ddd6fe',
					'text_color'       => '#581c87',
					'border_radius'    => '24px',
				),
				'admin_menu' => array(
					'background_color'      => '#faf5ff',
					'active_gradient_start' => '#c084fc',
					'active_gradient_end'   => '#e9d5ff',
				),
			),
		);
	}

	/**
	 * High Contrast Template
	 *
	 * @return array Template data.
	 */
	private function get_high_contrast_template(): array {
		return array(
			'id'          => 'high_contrast',
			'name'        => __( 'High Contrast', 'woow-admin' ),
			'description' => __( 'WCAG AAA compliant with maximum contrast', 'woow-admin' ),
			'thumbnail'   => WOOW_PLUGIN_URL . 'assets/dist/images/templates/high-contrast.png',
			'settings'    => array(
				'admin_bar' => array(
					'background_color' => '#000000',
					'text_color'       => '#ffffff',
					'glassmorphism'    => false,
				),
				'admin_menu' => array(
					'background_color' => '#ffffff',
					'glassmorphism'    => false,
				),
				'dashboard_widgets' => array(
					'background_color' => '#ffffff',
					'glassmorphism'    => false,
					'shadow_style'     => 'xl',
				),
			),
		);
	}

	/**
	 * Minimalist White Template
	 *
	 * @return array Template data.
	 */
	private function get_minimalist_white_template(): array {
		return array(
			'id'          => 'minimalist_white',
			'name'        => __( 'Minimalist White', 'woow-admin' ),
			'description' => __( 'Pure white design with minimal elements', 'woow-admin' ),
			'thumbnail'   => WOOW_PLUGIN_URL . 'assets/dist/images/templates/minimalist.png',
			'settings'    => array(
				'admin_bar' => array(
					'background_color' => '#ffffff',
					'text_color'       => '#0f172a',
					'border_radius'    => '0px',
					'glassmorphism'    => false,
					'shadow_style'     => 'sm',
				),
				'admin_menu' => array(
					'background_color' => '#ffffff',
					'border_radius'    => '0px',
					'glassmorphism'    => false,
				),
				'dashboard_widgets' => array(
					'background_color' => '#ffffff',
					'border_radius'    => '0px',
					'glassmorphism'    => false,
					'shadow_style'     => 'sm',
				),
			),
		);
	}

	/**
	 * Bold & Bright Template
	 *
	 * @return array Template data.
	 */
	private function get_bold_bright_template(): array {
		return array(
			'id'          => 'bold_bright',
			'name'        => __( 'Bold & Bright', 'woow-admin' ),
			'description' => __( 'High contrast colors with bold design', 'woow-admin' ),
			'thumbnail'   => WOOW_PLUGIN_URL . 'assets/dist/images/templates/bold.png',
			'settings'    => array(
				'admin_bar' => array(
					'background_type'  => 'gradient',
					'gradient_start'   => '#ef4444',
					'gradient_end'     => '#f59e0b',
					'border_radius'    => '24px',
				),
				'admin_menu' => array(
					'active_gradient_start' => '#ef4444',
					'active_gradient_end'   => '#f59e0b',
				),
				'effects' => array(
					'shadow_style' => 'xl',
				),
			),
		);
	}

	/**
	 * Material Design Template
	 *
	 * @return array Template data.
	 */
	private function get_material_design_template(): array {
		return array(
			'id'          => 'material_design',
			'name'        => __( 'Material Design', 'woow-admin' ),
			'description' => __( 'Google Material Design principles', 'woow-admin' ),
			'thumbnail'   => WOOW_PLUGIN_URL . 'assets/dist/images/templates/material.png',
			'settings'    => array(
				'admin_bar' => array(
					'height'        => '64px',
					'border_radius' => '0px',
					'shadow_style'  => 'md',
					'glassmorphism' => false,
				),
				'admin_menu' => array(
					'border_radius' => '0px',
					'glassmorphism' => false,
				),
				'dashboard_widgets' => array(
					'border_radius' => '4px',
					'shadow_style'  => 'md',
					'glassmorphism' => false,
				),
				'buttons' => array(
					'border_radius' => '4px',
				),
			),
		);
	}

	/**
	 * Glassmorphism Pro Template
	 *
	 * @return array Template data.
	 */
	private function get_glassmorphism_pro_template(): array {
		return array(
			'id'          => 'glassmorphism_pro',
			'name'        => __( 'Glassmorphism Pro', 'woow-admin' ),
			'description' => __( 'Maximum glass effect with strong blur', 'woow-admin' ),
			'thumbnail'   => WOOW_PLUGIN_URL . 'assets/dist/images/templates/glass-pro.png',
			'settings'    => array(
				'admin_bar' => array(
					'glassmorphism' => true,
					'blur_strength' => '24px',
					'opacity'       => 0.85,
					'border_radius' => '32px',
				),
				'admin_menu' => array(
					'glassmorphism' => true,
					'blur_strength' => '24px',
					'opacity'       => 0.85,
					'border_radius' => '32px',
				),
				'dashboard_widgets' => array(
					'glassmorphism' => true,
					'blur_strength' => '24px',
					'opacity'       => 0.85,
					'border_radius' => '32px',
				),
				'form_controls' => array(
					'glassmorphism' => true,
					'blur_strength' => '16px',
				),
				'buttons' => array(
					'glassmorphism' => true,
					'blur_strength' => '16px',
				),
			),
		);
	}
}
