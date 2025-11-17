<?php
/**
 * WOOW Palette Manager
 *
 * Manages color palette loading, validation, and application.
 * Handles 10 complete color palettes.
 * 
 * IMPORTANT: Palettes change ONLY colors (including background colors).
 * They do NOT change:
 * - Background images
 * - Typography (fonts, sizes)
 * - Effects (glassmorphism, shadows, animations)
 * - Other visual settings
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WOOW_Palette_Manager
 *
 * Manages palette operations including loading, validation, and application.
 */
class WOOW_Palette_Manager {
	/**
	 * Settings manager instance
	 *
	 * @var WOOW_Settings
	 */
	private WOOW_Settings $settings;

	/**
	 * Loaded palettes
	 *
	 * @var array|null
	 */
	private ?array $palettes = null;

	/**
	 * Backup manager instance
	 *
	 * @var WOOW_Backup_Manager|null
	 */
	private ?WOOW_Backup_Manager $backup_manager = null;

	/**
	 * CSS generator instance
	 *
	 * @var WOOW_CSS_Generator|null
	 */
	private ?WOOW_CSS_Generator $css_generator = null;

	/**
	 * Constructor
	 *
	 * @param WOOW_Settings $settings Settings manager instance.
	 */
	public function __construct( WOOW_Settings $settings ) {
		$this->settings = $settings;
	}

	/**
	 * Set backup manager instance
	 *
	 * @param WOOW_Backup_Manager $backup_manager Backup manager instance.
	 * @return void
	 */
	public function set_backup_manager( WOOW_Backup_Manager $backup_manager ): void {
		$this->backup_manager = $backup_manager;
	}

	/**
	 * Set CSS generator instance
	 *
	 * @param WOOW_CSS_Generator $css_generator CSS generator instance.
	 * @return void
	 */
	public function set_css_generator( WOOW_CSS_Generator $css_generator ): void {
		$this->css_generator = $css_generator;
	}

	/**
	 * Load all palettes from data file
	 *
	 * Loads palette definitions from includes/data/palettes.php.
	 * Uses lazy loading - only loads when first accessed.
	 * Implements error handling for missing or invalid files.
	 *
	 * @return void
	 * @throws Exception If palette file is missing or invalid.
	 */
	public function load_palettes(): void {
		// Check if already loaded
		if ( $this->palettes !== null ) {
			return;
		}

		// Build path to palettes data file
		$palettes_file = WOOW_PLUGIN_DIR . 'includes/data/palettes.php';

		// Check if file exists
		if ( ! file_exists( $palettes_file ) ) {
			error_log( '[WOOW Palette Manager] Palettes data file not found: ' . $palettes_file );
			throw new Exception( 'Palettes data file not found' );
		}

		// Check if file is readable
		if ( ! is_readable( $palettes_file ) ) {
			error_log( '[WOOW Palette Manager] Palettes data file not readable: ' . $palettes_file );
			throw new Exception( 'Palettes data file not readable' );
		}

		// Load palettes data
		$palettes = require $palettes_file;

		// Validate loaded data
		if ( ! is_array( $palettes ) ) {
			error_log( '[WOOW Palette Manager] Invalid palettes data format - expected array' );
			throw new Exception( 'Invalid palettes data format' );
		}

		// Validate each palette
		foreach ( $palettes as $palette_id => $palette ) {
			$validation_errors = $this->validate_palette( $palette );
			if ( ! empty( $validation_errors ) ) {
				error_log( '[WOOW Palette Manager] Palette validation failed for "' . $palette_id . '": ' . implode( ', ', $validation_errors ) );
				// Continue loading other palettes, but log the error
			}
		}

		// Store loaded palettes
		$this->palettes = $palettes;

		// Log success
		error_log( '[WOOW Palette Manager] Successfully loaded ' . count( $palettes ) . ' palettes' );
	}


	/**
	 * Get all available palettes
	 *
	 * Returns all loaded palettes. Automatically loads palettes if not yet loaded.
	 *
	 * @return array Array of all palette definitions.
	 */
	public function get_all_palettes(): array {
		// Ensure palettes are loaded
		if ( $this->palettes === null ) {
			try {
				$this->load_palettes();
			} catch ( Exception $e ) {
				error_log( '[WOOW Palette Manager] Failed to load palettes: ' . $e->getMessage() );
				return array();
			}
		}

		return $this->palettes ?? array();
	}

	/**
	 * Get single palette by ID
	 *
	 * Returns a specific palette by its unique identifier.
	 *
	 * @param string $palette_id Unique palette identifier.
	 * @return array|null Palette definition or null if not found.
	 */
	public function get_palette( string $palette_id ): ?array {
		// Ensure palettes are loaded
		$palettes = $this->get_all_palettes();

		// Sanitize palette ID
		$palette_id = sanitize_key( $palette_id );

		// Return palette if exists
		if ( isset( $palettes[ $palette_id ] ) ) {
			return $palettes[ $palette_id ];
		}

		// Log warning if palette not found
		error_log( '[WOOW Palette Manager] Palette not found: ' . $palette_id );
		return null;
	}

	/**
	 * Get palettes by category
	 *
	 * Returns all palettes that belong to a specific category.
	 * Categories: professional, creative, minimal, dark, vibrant
	 *
	 * @param string $category Category name.
	 * @return array Array of palettes in the specified category.
	 */
	public function get_palettes_by_category( string $category ): array {
		// Ensure palettes are loaded
		$palettes = $this->get_all_palettes();

		// Sanitize category
		$category = sanitize_key( $category );

		// Filter palettes by category
		$filtered = array();
		foreach ( $palettes as $palette_id => $palette ) {
			if ( isset( $palette['category'] ) && $palette['category'] === $category ) {
				$filtered[ $palette_id ] = $palette;
			}
		}

		return $filtered;
	}

	/**
	 * Check if palette exists
	 *
	 * @param string $palette_id Palette identifier.
	 * @return bool True if palette exists, false otherwise.
	 */
	public function palette_exists( string $palette_id ): bool {
		return $this->get_palette( $palette_id ) !== null;
	}

	/**
	 * Get palette categories
	 *
	 * Returns list of all available categories.
	 *
	 * @return array Array of category names.
	 */
	public function get_categories(): array {
		$palettes = $this->get_all_palettes();
		$categories = array();

		foreach ( $palettes as $palette ) {
			if ( isset( $palette['category'] ) && ! in_array( $palette['category'], $categories, true ) ) {
				$categories[] = $palette['category'];
			}
		}

		return $categories;
	}


	/**
	 * Validate palette data structure
	 *
	 * Checks that palette has all required keys and settings sections.
	 * Returns array of validation errors (empty if valid).
	 *
	 * @param array $palette Palette data to validate.
	 * @return array Array of validation error messages (empty if valid).
	 */
	private function validate_palette( array $palette ): array {
		$errors = array();

		// Required top-level keys
		$required_keys = array( 'id', 'name', 'description', 'category', 'settings' );
		foreach ( $required_keys as $key ) {
			if ( ! isset( $palette[ $key ] ) ) {
				$errors[] = "Missing required key: {$key}";
			}
		}

		// Validate settings section exists
		if ( ! isset( $palette['settings'] ) || ! is_array( $palette['settings'] ) ) {
			$errors[] = 'Missing or invalid settings section';
			return $errors; // Can't continue validation without settings
		}

		// Required settings sections (all 10 sections)
		$required_sections = array(
			'color_overrides',
			'admin_bar',
			'admin_menu',
			'dashboard_widgets',
			'form_controls',
			'buttons',
			'backgrounds',
			'typography',
			'effects',
			'login_page',
		);

		foreach ( $required_sections as $section ) {
			if ( ! isset( $palette['settings'][ $section ] ) ) {
				$errors[] = "Missing settings section: {$section}";
			} elseif ( ! is_array( $palette['settings'][ $section ] ) ) {
				$errors[] = "Invalid settings section (not array): {$section}";
			}
		}

		// Validate category is valid
		$valid_categories = array( 'professional', 'creative', 'minimal', 'dark', 'vibrant' );
		if ( isset( $palette['category'] ) && ! in_array( $palette['category'], $valid_categories, true ) ) {
			$errors[] = "Invalid category: {$palette['category']}";
		}

		// Validate ID format
		if ( isset( $palette['id'] ) && ! preg_match( '/^[a-z0-9_]+$/', $palette['id'] ) ) {
			$errors[] = "Invalid ID format (use lowercase letters, numbers, underscores only): {$palette['id']}";
		}

		// Check minimum option counts per section
		$min_option_counts = array(
			'color_overrides'    => 7,
			'admin_bar'          => 20,
			'admin_menu'         => 10,
			'dashboard_widgets'  => 8,
			'form_controls'      => 8,
			'buttons'            => 8,
			'backgrounds'        => 5,
			'typography'         => 8,
			'effects'            => 6,
			'login_page'         => 8,
		);

		foreach ( $min_option_counts as $section => $min_count ) {
			if ( isset( $palette['settings'][ $section ] ) ) {
				$actual_count = count( $palette['settings'][ $section ] );
				if ( $actual_count < $min_count ) {
					$errors[] = "Section '{$section}' has only {$actual_count} options (minimum {$min_count} required)";
				}
			}
		}

		return $errors;
	}

	/**
	 * Check palette completeness
	 *
	 * Returns detailed information about palette completeness.
	 *
	 * @param array $palette Palette data to check.
	 * @return array Completeness information with 'complete' boolean and 'missing' array.
	 */
	public function check_completeness( array $palette ): array {
		$errors = $this->validate_palette( $palette );

		return array(
			'complete' => empty( $errors ),
			'missing'  => $errors,
			'sections' => isset( $palette['settings'] ) ? count( $palette['settings'] ) : 0,
		);
	}


	/**
	 * Apply palette to current settings
	 *
	 * Validates palette, creates backup, merges settings, and regenerates CSS.
	 * Implements automatic rollback on failure with comprehensive error handling.
	 *
	 * @param string $palette_id Unique palette identifier.
	 * @return array Result array with 'success' boolean, 'message' string, and optional 'error_code'.
	 */
	public function apply_palette( string $palette_id ): array {
		$backup_id = null;
		$palette_name = $palette_id;
		
		try {
			// Sanitize palette ID
			$palette_id = sanitize_key( $palette_id );
			
			if ( empty( $palette_id ) ) {
				return $this->error_response(
					'INVALID_PALETTE_ID',
					'Invalid palette identifier provided',
					array( 'palette_id' => $palette_id )
				);
			}

			// Get palette
			$palette = $this->get_palette( $palette_id );
			if ( $palette === null ) {
				return $this->error_response(
					'PALETTE_NOT_FOUND',
					sprintf( 'Palette "%s" not found', $palette_id ),
					array( 'palette_id' => $palette_id )
				);
			}
			
			$palette_name = $palette['name'] ?? $palette_id;

			// Validate palette completeness
			$validation = $this->check_completeness( $palette );
			if ( ! $validation['complete'] ) {
				return $this->error_response(
					'PALETTE_INCOMPLETE',
					sprintf( 'Palette "%s" is incomplete or invalid', $palette_name ),
					array(
						'palette_id' => $palette_id,
						'missing'    => $validation['missing'],
					)
				);
			}

			// Create backup before applying
			if ( $this->backup_manager !== null ) {
				try {
					$backup_id = $this->backup_manager->create_backup( 'before_palette_' . $palette_id );
					error_log( sprintf(
						'[WOOW Palette Manager] Created backup "%s" before applying palette "%s"',
						$backup_id,
						$palette_name
					) );
				} catch ( Exception $e ) {
					// Backup failure is critical - don't proceed without backup
					return $this->error_response(
						'BACKUP_FAILED',
						sprintf( 'Failed to create backup before applying palette "%s"', $palette_name ),
						array(
							'palette_id' => $palette_id,
							'error'      => $e->getMessage(),
						)
					);
				}
			} else {
				error_log( '[WOOW Palette Manager] Warning: Backup manager not available, proceeding without backup' );
			}

			// Apply palette using settings manager
			// This will handle merging and validation
			$apply_success = $this->settings->apply_palette( $palette_id );
			if ( ! $apply_success ) {
				throw new Exception( 'Failed to apply palette settings' );
			}

			// Regenerate CSS
			if ( $this->css_generator !== null ) {
				try {
					$this->css_generator->generate();
					error_log( sprintf(
						'[WOOW Palette Manager] CSS regenerated successfully for palette "%s"',
						$palette_name
					) );
				} catch ( Exception $e ) {
					// CSS generation failure is not critical but should be logged
					error_log( sprintf(
						'[WOOW Palette Manager] Warning: CSS regeneration failed for palette "%s": %s',
						$palette_name,
						$e->getMessage()
					) );
				}
			}

			// Log success
			error_log( sprintf(
				'[WOOW Palette Manager] Successfully applied palette "%s" (ID: %s)',
				$palette_name,
				$palette_id
			) );
			
			return array(
				'success'    => true,
				'message'    => sprintf( 'Palette "%s" applied successfully', $palette_name ),
				'palette_id' => $palette_id,
				'backup_id'  => $backup_id,
			);

		} catch ( Exception $e ) {
			// Log detailed error
			error_log( sprintf(
				'[WOOW Palette Manager] Exception during palette application: %s (Palette: %s, File: %s, Line: %d)',
				$e->getMessage(),
				$palette_name,
				$e->getFile(),
				$e->getLine()
			) );
			
			// Attempt automatic rollback
			$rollback_success = false;
			if ( $this->backup_manager !== null && $backup_id !== null ) {
				try {
					$rollback_success = $this->backup_manager->restore_backup( $backup_id );
					if ( $rollback_success ) {
						error_log( sprintf(
							'[WOOW Palette Manager] Successfully restored from backup "%s" after failure',
							$backup_id
						) );
					} else {
						error_log( sprintf(
							'[WOOW Palette Manager] Failed to restore from backup "%s"',
							$backup_id
						) );
					}
				} catch ( Exception $restore_error ) {
					error_log( sprintf(
						'[WOOW Palette Manager] Exception during backup restore: %s',
						$restore_error->getMessage()
					) );
				}
			}

			return $this->error_response(
				'APPLICATION_FAILED',
				sprintf( 'Failed to apply palette "%s": %s', $palette_name, $e->getMessage() ),
				array(
					'palette_id'       => $palette_id,
					'error'            => $e->getMessage(),
					'backup_id'        => $backup_id,
					'rollback_success' => $rollback_success,
				)
			);
		}
	}
	
	/**
	 * Create standardized error response
	 *
	 * @param string $error_code Error code for programmatic handling.
	 * @param string $message User-friendly error message.
	 * @param array  $context Additional context data.
	 * @return array Error response array.
	 */
	private function error_response( string $error_code, string $message, array $context = array() ): array {
		// Log error with full context
		error_log( sprintf(
			'[WOOW Palette Manager] Error %s: %s | Context: %s',
			$error_code,
			$message,
			wp_json_encode( $context )
		) );
		
		return array(
			'success'    => false,
			'error_code' => $error_code,
			'message'    => $message,
			'context'    => $context,
		);
	}

	/**
	 * Normalize palette settings by adding units where needed
	 *
	 * Palettes store unitless numbers for flexibility, but validation expects units.
	 * This method adds 'px' units to numeric fields that require them.
	 *
	 * @param array $palette_settings Palette settings to normalize.
	 * @return array Normalized settings with units added.
	 */
	private function normalize_palette_settings( array $palette_settings ): array {
		// Fields that need 'px' unit added if they're unitless numbers
		$px_fields = array(
			'admin_bar' => array( 'height', 'font_size', 'blur_strength', 'top_offset', 'submenu_border_radius', 'submenu_font_size' ),
			'admin_menu' => array( 'border_radius', 'font_size', 'submenu_font_size', 'submenu_item_height', 'submenu_border_radius', 'submenu_item_border_radius' ),
			'dashboard_widgets' => array( 'border_radius', 'padding', 'margin', 'title_size', 'margin_bottom' ),
			'form_controls' => array( 'input_border_radius', 'label_size' ),
			'buttons' => array( 'primary_border_radius' ),
			'typography' => array( 'h1_size', 'h2_size', 'h3_size', 'body_size' ),
			'effects' => array( 'glassmorphism_blur', 'hover_lift' ),
			'login_page' => array( 'form_border_radius' ),
		);

		$normalized = $palette_settings;

		foreach ( $px_fields as $section => $fields ) {
			if ( ! isset( $normalized[ $section ] ) ) {
				continue;
			}

			foreach ( $fields as $field ) {
				if ( ! isset( $normalized[ $section ][ $field ] ) ) {
					continue;
				}

				$value = $normalized[ $section ][ $field ];

				// If it's a numeric string without a unit, add 'px'
				if ( is_string( $value ) && is_numeric( $value ) ) {
					$normalized[ $section ][ $field ] = $value . 'px';
				}
			}
		}

		return $normalized;
	}

	/**
	 * Merge palette settings with current settings
	 *
	 * Applies ONLY color-related settings from palette.
	 * Preserves all non-color settings (backgrounds, typography, effects, etc.)
	 *
	 * @param array $current_settings Current plugin settings.
	 * @param array $palette_settings Palette settings to merge.
	 * @return array Merged settings with only colors changed.
	 */
	private function merge_palette_settings( array $current_settings, array $palette_settings ): array {
		// Start with current settings
		$merged = $current_settings;

		// Define which fields are color-related in each section
		$color_fields = array(
			'color_overrides' => array(
				'primary_color',
				'secondary_color',
				'accent_color',
				'background_color',
				'text_color',
				'link_color',
				'success_color',
				'warning_color',
				'error_color',
			),
			'admin_bar' => array(
				'background_color',
				'text_color',
				'hover_color',
				'icon_color',
				'submenu_bg_color',
				'submenu_text_color',
				'submenu_hover_color',
				'gradient_start',
				'gradient_end',
			),
			'admin_menu' => array(
				'background_color',
				'text_color',
				'hover_bg_color',
				'hover_text_color',
				'active_bg_color',
				'active_text_color',
				'icon_color',
				'separator_color',
				'submenu_bg_color',
				'submenu_text_color',
				'submenu_hover_bg_color',
				'submenu_hover_text_color',
			),
			'dashboard_widgets' => array(
				'background_color',
				'border_color',
				'title_color',
				'text_color',
				'link_color',
			),
			'form_controls' => array(
				'input_bg_color',
				'input_border_color',
				'input_text_color',
				'input_focus_border_color',
				'label_color',
			),
			'buttons' => array(
				'primary_bg_color',
				'primary_text_color',
				'primary_hover_bg_color',
				'secondary_bg_color',
				'secondary_text_color',
				'secondary_hover_bg_color',
			),
			'backgrounds' => array(
				'body_background_color',
				'content_background_color',
				// Note: NOT including image_url, image_size, image_repeat, image_position
			),
			'typography' => array(
				'heading_color',
				'body_color',
				'link_color',
				// Note: NOT including font_family, font_size, line_height, etc.
			),
			'effects' => array(
				// Note: NOT including glassmorphism, shadows, animations
				// Effects section has no color fields
			),
			'login_page' => array(
				'background_color',
				'form_bg_color',
				'form_text_color',
				'button_bg_color',
				'button_text_color',
				'link_color',
			),
		);

		// Apply only color fields from palette
		foreach ( $color_fields as $section => $fields ) {
			// Skip if section doesn't exist in palette
			if ( ! isset( $palette_settings[ $section ] ) ) {
				continue;
			}

			// Ensure section exists in merged settings
			if ( ! isset( $merged[ $section ] ) ) {
				$merged[ $section ] = array();
			}

			// Copy only color fields
			foreach ( $fields as $field ) {
				if ( isset( $palette_settings[ $section ][ $field ] ) ) {
					$merged[ $section ][ $field ] = $palette_settings[ $section ][ $field ];
				}
			}
		}

		return $merged;
	}

	/**
	 * Get palette preview image URL
	 *
	 * Returns the URL to the palette's preview image.
	 *
	 * @param string $palette_id Palette identifier.
	 * @return string|null Preview image URL or null if not found.
	 */
	public function get_preview_image_url( string $palette_id ): ?string {
		$palette = $this->get_palette( $palette_id );
		
		if ( $palette === null || ! isset( $palette['preview_image'] ) ) {
			return null;
		}

		// Build URL to preview image
		$preview_url = WOOW_PLUGIN_URL . 'assets/images/previews/palettes/' . $palette['preview_image'];
		
		return $preview_url;
	}

	/**
	 * Get palette count
	 *
	 * Returns the total number of available palettes.
	 *
	 * @return int Number of palettes.
	 */
	public function get_palette_count(): int {
		$palettes = $this->get_all_palettes();
		return count( $palettes );
	}
}
