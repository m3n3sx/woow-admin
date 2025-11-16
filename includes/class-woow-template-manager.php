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
	 * Loaded templates cache
	 *
	 * @var array|null
	 */
	private ?array $templates = null;

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
	 * Load templates from data file
	 *
	 * Loads template definitions from includes/data/templates-data.php
	 * and caches them for subsequent calls.
	 *
	 * @return void
	 */
	private function load_templates(): void {
		if ( $this->templates !== null ) {
			return; // Already loaded
		}

		$templates_file = WOOW_PLUGIN_DIR . 'includes/data/templates-data.php';

		if ( ! file_exists( $templates_file ) ) {
			error_log( '[WOOW Admin] Templates data file not found: ' . $templates_file );
			$this->templates = array();
			return;
		}

		try {
			$loaded_templates = require $templates_file;

			if ( ! is_array( $loaded_templates ) ) {
				error_log( '[WOOW Admin] Templates data file did not return an array' );
				$this->templates = array();
				return;
			}

			$this->templates = $loaded_templates;
		} catch ( Exception $e ) {
			error_log( '[WOOW Admin] Error loading templates: ' . $e->getMessage() );
			$this->templates = array();
		}
	}

	/**
	 * Get single template by ID
	 *
	 * @param string $template_id Template ID.
	 * @return array|null Template data or null if not found.
	 */
	public function get_template( string $template_id ): ?array {
		$this->load_templates();

		if ( isset( $this->templates[ $template_id ] ) ) {
			return $this->templates[ $template_id ];
		}

		return null;
	}

	/**
	 * Get all available templates
	 *
	 * Returns all predefined design templates from data file.
	 *
	 * @return array Array of template data.
	 */
	public function get_all_templates(): array {
		$this->load_templates();
		return array_values( $this->templates );
	}

	/**
	 * Get templates by category
	 *
	 * @param string $category Category to filter by (minimal, modern, corporate, creative, dark).
	 * @return array Array of templates in the specified category.
	 */
	public function get_templates_by_category( string $category ): array {
		$this->load_templates();

		$filtered = array();
		foreach ( $this->templates as $template ) {
			if ( isset( $template['category'] ) && $template['category'] === $category ) {
				$filtered[] = $template;
			}
		}

		return $filtered;
	}

	/**
	 * Validate template data structure
	 *
	 * Checks if template has all required keys and valid structure.
	 *
	 * @param array $template Template data to validate.
	 * @return bool True if valid, false otherwise.
	 */
	private function validate_template( array $template ): bool {
		// Check required keys
		$required_keys = array( 'id', 'name', 'description', 'settings' );
		foreach ( $required_keys as $key ) {
			if ( ! isset( $template[ $key ] ) ) {
				error_log( "[WOOW Admin] Template validation failed: Missing required key '{$key}'" );
				return false;
			}
		}

		// Check settings is an array
		if ( ! is_array( $template['settings'] ) ) {
			error_log( '[WOOW Admin] Template validation failed: settings must be an array' );
			return false;
		}

		// Check required sections exist
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
			if ( ! isset( $template['settings'][ $section ] ) ) {
				error_log( "[WOOW Admin] Template validation failed: Missing settings section '{$section}'" );
				return false;
			}
		}

		return true;
	}

	/**
	 * Check template completeness
	 *
	 * Verifies all 10 sections are present and counts options in each section.
	 * Returns array of missing options or empty array if complete.
	 *
	 * @param array $template Template data to check.
	 * @return array Array of missing options (empty if complete).
	 */
	private function check_completeness( array $template ): array {
		$missing = array();

		// Expected minimum option counts per section
		$expected_counts = array(
			'color_overrides'    => 7,
			'admin_bar'          => 25,
			'admin_menu'         => 15,
			'dashboard_widgets'  => 10,
			'form_controls'      => 10,
			'buttons'            => 10,
			'backgrounds'        => 6,
			'typography'         => 10,
			'effects'            => 8,
			'login_page'         => 10,
		);

		if ( ! isset( $template['settings'] ) || ! is_array( $template['settings'] ) ) {
			$missing[] = 'settings section is missing or invalid';
			return $missing;
		}

		foreach ( $expected_counts as $section => $min_count ) {
			if ( ! isset( $template['settings'][ $section ] ) ) {
				$missing[] = "Section '{$section}' is missing";
				continue;
			}

			$actual_count = count( $template['settings'][ $section ] );
			if ( $actual_count < $min_count ) {
				$missing[] = "Section '{$section}' has {$actual_count} options, expected at least {$min_count}";
			}
		}

		return $missing;
	}

	/**
	 * Apply template
	 *
	 * Overrides all settings with template configuration.
	 * Validates completeness, creates backup, and implements automatic rollback on failure.
	 *
	 * @param string $template_id Template ID to apply.
	 * @return array Result array with 'success' boolean, 'message' string, and optional 'error_code'.
	 */
	public function apply_template( string $template_id ): array {
		$backup_id = null;
		$template_name = $template_id;
		
		try {
			// Sanitize template ID
			$template_id = sanitize_key( $template_id );
			
			if ( empty( $template_id ) ) {
				return $this->error_response(
					'INVALID_TEMPLATE_ID',
					'Invalid template identifier provided',
					array( 'template_id' => $template_id )
				);
			}

			// Get template
			$template = $this->get_template( $template_id );
			if ( ! $template ) {
				return $this->error_response(
					'TEMPLATE_NOT_FOUND',
					sprintf( 'Template "%s" not found', $template_id ),
					array( 'template_id' => $template_id )
				);
			}
			
			$template_name = $template['name'] ?? $template_id;

			// Validate template structure
			if ( ! $this->validate_template( $template ) ) {
				return $this->error_response(
					'TEMPLATE_INVALID',
					sprintf( 'Template "%s" has invalid structure', $template_name ),
					array( 'template_id' => $template_id )
				);
			}

			// Check completeness (warning only, not blocking)
			$missing = $this->check_completeness( $template );
			if ( ! empty( $missing ) ) {
				error_log( sprintf(
					'[WOOW Template Manager] Warning: Template "%s" may be incomplete: %s',
					$template_name,
					implode( ', ', $missing )
				) );
			}

			// Create backup before applying template
			if ( $this->backup_manager !== null ) {
				try {
					$backup_label = 'before_template_' . $template_id;
					$backup_id = $this->backup_manager->create_backup( $backup_label );
					error_log( sprintf(
						'[WOOW Template Manager] Created backup "%s" before applying template "%s"',
						$backup_id,
						$template_name
					) );
				} catch ( Exception $e ) {
					// Backup failure is critical - don't proceed without backup
					return $this->error_response(
						'BACKUP_FAILED',
						sprintf( 'Failed to create backup before applying template "%s"', $template_name ),
						array(
							'template_id' => $template_id,
							'error'       => $e->getMessage(),
						)
					);
				}
			} else {
				error_log( '[WOOW Template Manager] Warning: Backup manager not available, proceeding without backup' );
			}

			// Get defaults first (ensures all sections exist)
			$defaults = woow_get_default_settings();
			if ( empty( $defaults ) ) {
				throw new Exception( 'Failed to load default settings' );
			}
			
			// Get current settings
			$current_settings = $this->settings->get_all_settings();
			if ( empty( $current_settings ) ) {
				throw new Exception( 'Failed to retrieve current settings' );
			}
			
			// Merge: defaults -> current -> template
			// This ensures all sections exist even if template only has partial settings
			$new_settings = array_replace_recursive( $defaults, $current_settings, $template['settings'] );
			
			// Note: We skip strict validation for template settings because:
			// 1. Templates are pre-defined and trusted
			// 2. Template values are stored without units (e.g., '52' not '52px')
			// 3. Units are added during CSS generation
			// 4. Strict validation would reject valid template values

			// Update all settings directly (validation happens during CSS generation)
			$result = $this->settings->update_all_settings( $new_settings );
			if ( ! $result ) {
				throw new Exception( 'Failed to update settings in database' );
			}

			// Regenerate CSS
			if ( $this->css_generator !== null ) {
				try {
					$this->css_generator->generate();
					error_log( sprintf(
						'[WOOW Template Manager] CSS regenerated successfully for template "%s"',
						$template_name
					) );
				} catch ( Exception $e ) {
					// CSS generation failure is not critical but should be logged
					error_log( sprintf(
						'[WOOW Template Manager] Warning: CSS regeneration failed for template "%s": %s',
						$template_name,
						$e->getMessage()
					) );
				}
			} else {
				// Fallback: Clear CSS cache if generator not available
				try {
					$cache = new WOOW_Cache_Manager();
					$cache->delete( 'woow_css' );
					error_log( '[WOOW Template Manager] CSS cache cleared (generator not available)' );
				} catch ( Exception $e ) {
					error_log( sprintf(
						'[WOOW Template Manager] Warning: Failed to clear CSS cache: %s',
						$e->getMessage()
					) );
				}
			}

			// Log success
			error_log( sprintf(
				'[WOOW Template Manager] Successfully applied template "%s" (ID: %s)',
				$template_name,
				$template_id
			) );
			
			return array(
				'success'     => true,
				'message'     => sprintf( 'Template "%s" applied successfully', $template_name ),
				'template_id' => $template_id,
				'backup_id'   => $backup_id,
			);

		} catch ( Exception $e ) {
			// Log detailed error
			error_log( sprintf(
				'[WOOW Template Manager] Exception during template application: %s (Template: %s, File: %s, Line: %d)',
				$e->getMessage(),
				$template_name,
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
							'[WOOW Template Manager] Successfully restored from backup "%s" after failure',
							$backup_id
						) );
					} else {
						error_log( sprintf(
							'[WOOW Template Manager] Failed to restore from backup "%s"',
							$backup_id
						) );
					}
				} catch ( Exception $restore_error ) {
					error_log( sprintf(
						'[WOOW Template Manager] Exception during backup restore: %s',
						$restore_error->getMessage()
					) );
				}
			}

			return $this->error_response(
				'APPLICATION_FAILED',
				sprintf( 'Failed to apply template "%s": %s', $template_name, $e->getMessage() ),
				array(
					'template_id'      => $template_id,
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
			'[WOOW Template Manager] Error %s: %s | Context: %s',
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
}
