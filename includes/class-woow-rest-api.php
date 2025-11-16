<?php
/**
 * WOOW REST API
 *
 * Provides REST API endpoints for external integrations.
 * All endpoints require manage_options capability.
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WOOW_REST_API
 *
 * Handles REST API endpoints for the plugin.
 */
class WOOW_REST_API {
	/**
	 * Settings manager instance
	 *
	 * @var WOOW_Settings
	 */
	private WOOW_Settings $settings;

	/**
	 * Palette manager instance
	 *
	 * @var WOOW_Palette_Manager|null
	 */
	private ?WOOW_Palette_Manager $palette_manager = null;

	/**
	 * Template manager instance
	 *
	 * @var WOOW_Template_Manager|null
	 */
	private ?WOOW_Template_Manager $template_manager = null;

	/**
	 * API namespace
	 *
	 * @var string
	 */
	private const NAMESPACE = 'woow/v1';

	/**
	 * Constructor
	 *
	 * @param WOOW_Settings $settings Settings manager instance.
	 */
	public function __construct( WOOW_Settings $settings ) {
		$this->settings = $settings;
	}

	/**
	 * Set palette manager instance
	 *
	 * @param WOOW_Palette_Manager $palette_manager Palette manager instance.
	 * @return void
	 */
	public function set_palette_manager( WOOW_Palette_Manager $palette_manager ): void {
		$this->palette_manager = $palette_manager;
	}

	/**
	 * Set template manager instance
	 *
	 * @param WOOW_Template_Manager $template_manager Template manager instance.
	 * @return void
	 */
	public function set_template_manager( WOOW_Template_Manager $template_manager ): void {
		$this->template_manager = $template_manager;
	}

	/**
	 * Register REST API routes
	 *
	 * Hooked to rest_api_init.
	 *
	 * @return void
	 */
	public function register_routes(): void {
		// Settings endpoints
		register_rest_route(
			self::NAMESPACE,
			'/settings',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_settings' ),
					'permission_callback' => array( $this, 'check_permissions' ),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'update_settings' ),
					'permission_callback' => array( $this, 'check_permissions' ),
					'args'                => array(
						'settings' => array(
							'required'          => true,
							'type'              => 'object',
							'validate_callback' => array( $this, 'validate_settings' ),
						),
					),
				),
			)
		);

		// Palettes endpoints
		register_rest_route(
			self::NAMESPACE,
			'/palettes',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_palettes' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);

		register_rest_route(
			self::NAMESPACE,
			'/palettes/(?P<id>[a-zA-Z0-9_-]+)',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_palette' ),
				'permission_callback' => array( $this, 'check_permissions' ),
				'args'                => array(
					'id' => array(
						'required'          => true,
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_key',
					),
				),
			)
		);

		register_rest_route(
			self::NAMESPACE,
			'/palettes/(?P<id>[a-zA-Z0-9_-]+)/apply',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'apply_palette' ),
				'permission_callback' => array( $this, 'check_permissions' ),
				'args'                => array(
					'id' => array(
						'required'          => true,
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_key',
					),
				),
			)
		);

		// Templates endpoints
		register_rest_route(
			self::NAMESPACE,
			'/templates',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_templates' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);

		register_rest_route(
			self::NAMESPACE,
			'/templates/(?P<id>[a-zA-Z0-9_-]+)',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_template' ),
				'permission_callback' => array( $this, 'check_permissions' ),
				'args'                => array(
					'id' => array(
						'required'          => true,
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_key',
					),
				),
			)
		);

		register_rest_route(
			self::NAMESPACE,
			'/templates/(?P<id>[a-zA-Z0-9_-]+)/apply',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'apply_template' ),
				'permission_callback' => array( $this, 'check_permissions' ),
				'args'                => array(
					'id' => array(
						'required'          => true,
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_key',
					),
				),
			)
		);

		// Backup endpoints
		register_rest_route(
			self::NAMESPACE,
			'/backups',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_backups' ),
					'permission_callback' => array( $this, 'check_permissions' ),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_backup' ),
					'permission_callback' => array( $this, 'check_permissions' ),
					'args'                => array(
						'label' => array(
							'type'              => 'string',
							'sanitize_callback' => 'sanitize_text_field',
							'default'           => 'api',
						),
					),
				),
			)
		);

		register_rest_route(
			self::NAMESPACE,
			'/backups/(?P<id>[a-zA-Z0-9_-]+)',
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'restore_backup' ),
					'permission_callback' => array( $this, 'check_permissions' ),
					'args'                => array(
						'id' => array(
							'required'          => true,
							'type'              => 'string',
							'sanitize_callback' => 'sanitize_text_field',
						),
					),
				),
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_backup' ),
					'permission_callback' => array( $this, 'check_permissions' ),
					'args'                => array(
						'id' => array(
							'required'          => true,
							'type'              => 'string',
							'sanitize_callback' => 'sanitize_text_field',
						),
					),
				),
			)
		);

		// CSS generation endpoint
		register_rest_route(
			self::NAMESPACE,
			'/css',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_css' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			)
		);
	}

	/**
	 * Check permissions
	 *
	 * Verifies user has manage_options capability.
	 *
	 * @return bool True if user has permission.
	 */
	public function check_permissions(): bool {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Get settings
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Response object.
	 */
	public function get_settings( WP_REST_Request $request ): WP_REST_Response {
		$settings = $this->settings->get_all_settings();

		return new WP_REST_Response(
			array(
				'success'  => true,
				'settings' => $settings,
			),
			200
		);
	}

	/**
	 * Update settings
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Response object.
	 */
	public function update_settings( WP_REST_Request $request ): WP_REST_Response {
		$new_settings = $request->get_param( 'settings' );

		// Validate settings
		$validation = $this->settings->validate_settings( $new_settings );

		if ( ! $validation['valid'] ) {
			return new WP_REST_Response(
				array(
					'success' => false,
					'message' => 'Invalid settings',
					'errors'  => $validation['errors'],
				),
				400
			);
		}

		// Update settings
		$result = $this->settings->update_all_settings( $new_settings );

		if ( $result ) {
			// Clear CSS cache
			$cache = new WOOW_Cache_Manager();
			$cache->delete( 'woow_css' );

			return new WP_REST_Response(
				array(
					'success'  => true,
					'message'  => 'Settings updated successfully',
					'settings' => $this->settings->get_all_settings(),
				),
				200
			);
		}

		return new WP_REST_Response(
			array(
				'success' => false,
				'message' => 'Failed to update settings',
			),
			500
		);
	}

	/**
	 * Get palettes
	 *
	 * Returns all available color palettes with metadata.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Response object.
	 */
	public function get_palettes( WP_REST_Request $request ): WP_REST_Response {
		// Verify nonce if provided
		$nonce = $request->get_header( 'X-WP-Nonce' );
		if ( $nonce && ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
			return new WP_REST_Response(
				array(
					'success' => false,
					'message' => 'Invalid nonce',
				),
				403
			);
		}

		// Get palette manager instance
		if ( $this->palette_manager === null ) {
			$this->palette_manager = new WOOW_Palette_Manager( $this->settings );
		}

		try {
			$palettes = $this->palette_manager->get_all_palettes();

			// Add preview URLs to each palette
			$palettes_with_previews = array();
			foreach ( $palettes as $palette_id => $palette ) {
				$palette['preview_url'] = $this->palette_manager->get_preview_image_url( $palette_id );
				$palettes_with_previews[ $palette_id ] = $palette;
			}

			return new WP_REST_Response(
				array(
					'success'  => true,
					'palettes' => $palettes_with_previews,
					'count'    => count( $palettes_with_previews ),
				),
				200
			);
		} catch ( Exception $e ) {
			return new WP_REST_Response(
				array(
					'success' => false,
					'message' => 'Failed to load palettes: ' . $e->getMessage(),
				),
				500
			);
		}
	}

	/**
	 * Get single palette
	 *
	 * Returns a specific palette by ID with full details.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Response object.
	 */
	public function get_palette( WP_REST_Request $request ): WP_REST_Response {
		// Verify nonce if provided
		$nonce = $request->get_header( 'X-WP-Nonce' );
		if ( $nonce && ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
			return new WP_REST_Response(
				array(
					'success' => false,
					'message' => 'Invalid nonce',
				),
				403
			);
		}

		$palette_id = $request->get_param( 'id' );

		// Get palette manager instance
		if ( $this->palette_manager === null ) {
			$this->palette_manager = new WOOW_Palette_Manager( $this->settings );
		}

		try {
			$palette = $this->palette_manager->get_palette( $palette_id );

			if ( $palette === null ) {
				return new WP_REST_Response(
					array(
						'success' => false,
						'message' => 'Palette not found',
					),
					404
				);
			}

			// Add preview URL
			$palette['preview_url'] = $this->palette_manager->get_preview_image_url( $palette_id );

			// Add completeness check
			$completeness = $this->palette_manager->check_completeness( $palette );

			return new WP_REST_Response(
				array(
					'success'      => true,
					'palette'      => $palette,
					'completeness' => $completeness,
				),
				200
			);
		} catch ( Exception $e ) {
			return new WP_REST_Response(
				array(
					'success' => false,
					'message' => 'Failed to load palette: ' . $e->getMessage(),
				),
				500
			);
		}
	}

	/**
	 * Apply palette
	 *
	 * Applies a color palette to the current settings.
	 * Creates a backup before applying and regenerates CSS.
	 * Implements automatic rollback on failure.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Response object.
	 */
	public function apply_palette( WP_REST_Request $request ): WP_REST_Response {
		// Verify nonce
		$nonce = $request->get_header( 'X-WP-Nonce' );
		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
			return new WP_REST_Response(
				array(
					'success'    => false,
					'error_code' => 'INVALID_NONCE',
					'message'    => 'Invalid or missing security token. Please refresh the page and try again.',
				),
				403
			);
		}

		$palette_id = $request->get_param( 'id' );

		// Get palette manager instance
		if ( $this->palette_manager === null ) {
			$this->palette_manager = new WOOW_Palette_Manager( $this->settings );
			
			// Set dependencies
			$backup_manager = new WOOW_Backup_Manager( $this->settings );
			$css_generator  = new WOOW_CSS_Generator( $this->settings );
			
			$this->palette_manager->set_backup_manager( $backup_manager );
			$this->palette_manager->set_css_generator( $css_generator );
		}

		try {
			// Apply palette (returns array with success, message, error_code, etc.)
			$result = $this->palette_manager->apply_palette( $palette_id );

			if ( $result['success'] ) {
				// Clear CSS cache on success
				try {
					$cache = new WOOW_Cache_Manager();
					$cache->delete( 'woow_css' );
				} catch ( Exception $e ) {
					error_log( '[WOOW REST API] Warning: Failed to clear CSS cache: ' . $e->getMessage() );
				}

				return new WP_REST_Response(
					array(
						'success'    => true,
						'message'    => $result['message'],
						'palette_id' => $result['palette_id'],
						'backup_id'  => $result['backup_id'] ?? null,
						'settings'   => $this->settings->get_all_settings(),
					),
					200
				);
			}

			// Determine HTTP status code based on error code
			$status_code = $this->get_http_status_for_error( $result['error_code'] ?? 'APPLICATION_FAILED' );

			return new WP_REST_Response(
				array(
					'success'    => false,
					'error_code' => $result['error_code'] ?? 'APPLICATION_FAILED',
					'message'    => $result['message'],
					'context'    => $result['context'] ?? array(),
				),
				$status_code
			);

		} catch ( Exception $e ) {
			error_log( sprintf(
				'[WOOW REST API] Unexpected exception in apply_palette: %s (File: %s, Line: %d)',
				$e->getMessage(),
				$e->getFile(),
				$e->getLine()
			) );
			
			return new WP_REST_Response(
				array(
					'success'    => false,
					'error_code' => 'UNEXPECTED_ERROR',
					'message'    => 'An unexpected error occurred. Please try again or contact support.',
					'context'    => array(
						'error' => $e->getMessage(),
					),
				),
				500
			);
		}
	}

	/**
	 * Get templates
	 *
	 * Returns all available design templates with metadata.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Response object.
	 */
	public function get_templates( WP_REST_Request $request ): WP_REST_Response {
		// Verify nonce if provided
		$nonce = $request->get_header( 'X-WP-Nonce' );
		if ( $nonce && ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
			return new WP_REST_Response(
				array(
					'success' => false,
					'message' => 'Invalid nonce',
				),
				403
			);
		}

		// Check if template manager is available
		if ( $this->template_manager === null ) {
			return new WP_REST_Response(
				array(
					'success' => false,
					'message' => 'Template manager not initialized',
				),
				500
			);
		}

		try {
			$templates = $this->template_manager->get_all_templates();

			// Add preview URLs to each template
			$templates_with_previews = array();
			foreach ( $templates as $template ) {
				if ( isset( $template['id'] ) ) {
					$template['preview_url'] = $this->get_template_preview_url( $template['id'] );
					$templates_with_previews[] = $template;
				}
			}

			return new WP_REST_Response(
				array(
					'success'   => true,
					'templates' => $templates_with_previews,
					'count'     => count( $templates_with_previews ),
				),
				200
			);
		} catch ( Exception $e ) {
			return new WP_REST_Response(
				array(
					'success' => false,
					'message' => 'Failed to load templates: ' . $e->getMessage(),
				),
				500
			);
		}
	}

	/**
	 * Get single template
	 *
	 * Returns a specific template by ID with full details.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Response object.
	 */
	public function get_template( WP_REST_Request $request ): WP_REST_Response {
		// Verify nonce if provided
		$nonce = $request->get_header( 'X-WP-Nonce' );
		if ( $nonce && ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
			return new WP_REST_Response(
				array(
					'success' => false,
					'message' => 'Invalid nonce',
				),
				403
			);
		}

		$template_id = $request->get_param( 'id' );

		// Check if template manager is available
		if ( $this->template_manager === null ) {
			return new WP_REST_Response(
				array(
					'success' => false,
					'message' => 'Template manager not initialized',
				),
				500
			);
		}

		try {
			$template = $this->template_manager->get_template( $template_id );

			if ( $template === null ) {
				return new WP_REST_Response(
					array(
						'success' => false,
						'message' => 'Template not found',
					),
					404
				);
			}

			// Add preview URL
			$template['preview_url'] = $this->get_template_preview_url( $template_id );

			return new WP_REST_Response(
				array(
					'success'  => true,
					'template' => $template,
				),
				200
			);
		} catch ( Exception $e ) {
			return new WP_REST_Response(
				array(
					'success' => false,
					'message' => 'Failed to load template: ' . $e->getMessage(),
				),
				500
			);
		}
	}

	/**
	 * Apply template
	 *
	 * Applies a design template to the current settings.
	 * Creates a backup before applying and regenerates CSS.
	 * Implements automatic rollback on failure.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Response object.
	 */
	public function apply_template( WP_REST_Request $request ): WP_REST_Response {
		// Verify nonce
		$nonce = $request->get_header( 'X-WP-Nonce' );
		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
			return new WP_REST_Response(
				array(
					'success'    => false,
					'error_code' => 'INVALID_NONCE',
					'message'    => 'Invalid or missing security token. Please refresh the page and try again.',
				),
				403
			);
		}

		$template_id = $request->get_param( 'id' );

		// Check if template manager is available
		if ( $this->template_manager === null ) {
			return new WP_REST_Response(
				array(
					'success'    => false,
					'error_code' => 'MANAGER_NOT_INITIALIZED',
					'message'    => 'Template manager not initialized. Please contact support.',
				),
				500
			);
		}

		try {
			// Apply template (returns array with success, message, error_code, etc.)
			$result = $this->template_manager->apply_template( $template_id );

			if ( $result['success'] ) {
				// Clear CSS cache on success
				try {
					$cache = new WOOW_Cache_Manager();
					$cache->delete( 'woow_css' );
				} catch ( Exception $e ) {
					error_log( '[WOOW REST API] Warning: Failed to clear CSS cache: ' . $e->getMessage() );
				}

				return new WP_REST_Response(
					array(
						'success'     => true,
						'message'     => $result['message'],
						'template_id' => $result['template_id'],
						'backup_id'   => $result['backup_id'] ?? null,
						'settings'    => $this->settings->get_all_settings(),
					),
					200
				);
			}

			// Determine HTTP status code based on error code
			$status_code = $this->get_http_status_for_error( $result['error_code'] ?? 'APPLICATION_FAILED' );

			return new WP_REST_Response(
				array(
					'success'    => false,
					'error_code' => $result['error_code'] ?? 'APPLICATION_FAILED',
					'message'    => $result['message'],
					'context'    => $result['context'] ?? array(),
				),
				$status_code
			);

		} catch ( Exception $e ) {
			error_log( sprintf(
				'[WOOW REST API] Unexpected exception in apply_template: %s (File: %s, Line: %d)',
				$e->getMessage(),
				$e->getFile(),
				$e->getLine()
			) );
			
			return new WP_REST_Response(
				array(
					'success'    => false,
					'error_code' => 'UNEXPECTED_ERROR',
					'message'    => 'An unexpected error occurred. Please try again or contact support.',
					'context'    => array(
						'error' => $e->getMessage(),
					),
				),
				500
			);
		}
	}
	
	/**
	 * Get HTTP status code for error code
	 *
	 * Maps error codes to appropriate HTTP status codes.
	 *
	 * @param string $error_code Error code.
	 * @return int HTTP status code.
	 */
	private function get_http_status_for_error( string $error_code ): int {
		$status_map = array(
			'INVALID_PALETTE_ID'   => 400,
			'INVALID_TEMPLATE_ID'  => 400,
			'PALETTE_NOT_FOUND'    => 404,
			'TEMPLATE_NOT_FOUND'   => 404,
			'PALETTE_INCOMPLETE'   => 400,
			'TEMPLATE_INVALID'     => 400,
			'BACKUP_FAILED'        => 500,
			'APPLICATION_FAILED'   => 500,
			'INVALID_NONCE'        => 403,
			'UNEXPECTED_ERROR'     => 500,
		);
		
		return $status_map[ $error_code ] ?? 500;
	}

	/**
	 * Get template preview image URL
	 *
	 * @param string $template_id Template ID.
	 * @return string Preview image URL.
	 */
	private function get_template_preview_url( string $template_id ): string {
		$preview_file = str_replace( '_', '-', $template_id ) . '.png';
		$preview_path = WOOW_PLUGIN_DIR . 'assets/images/previews/templates/' . $preview_file;
		
		if ( file_exists( $preview_path ) ) {
			return WOOW_PLUGIN_URL . 'assets/images/previews/templates/' . $preview_file;
		}
		
		// Return placeholder if preview doesn't exist
		return WOOW_PLUGIN_URL . 'assets/images/previews/templates/placeholder.png';
	}

	/**
	 * Get backups
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Response object.
	 */
	public function get_backups( WP_REST_Request $request ): WP_REST_Response {
		$backup_manager = new WOOW_Backup_Manager( $this->settings );
		$backups        = $backup_manager->get_backups();

		return new WP_REST_Response(
			array(
				'success' => true,
				'backups' => $backups,
			),
			200
		);
	}

	/**
	 * Create backup
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Response object.
	 */
	public function create_backup( WP_REST_Request $request ): WP_REST_Response {
		$label          = $request->get_param( 'label' );
		$backup_manager = new WOOW_Backup_Manager( $this->settings );

		try {
			$backup_id = $backup_manager->create_backup( $label );

			return new WP_REST_Response(
				array(
					'success'   => true,
					'message'   => 'Backup created successfully',
					'backup_id' => $backup_id,
				),
				201
			);
		} catch ( Exception $e ) {
			return new WP_REST_Response(
				array(
					'success' => false,
					'message' => 'Failed to create backup: ' . $e->getMessage(),
				),
				500
			);
		}
	}

	/**
	 * Restore backup
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Response object.
	 */
	public function restore_backup( WP_REST_Request $request ): WP_REST_Response {
		$backup_id      = $request->get_param( 'id' );
		$backup_manager = new WOOW_Backup_Manager( $this->settings );

		$result = $backup_manager->restore_backup( $backup_id );

		if ( $result ) {
			return new WP_REST_Response(
				array(
					'success'  => true,
					'message'  => 'Backup restored successfully',
					'settings' => $this->settings->get_all_settings(),
				),
				200
			);
		}

		return new WP_REST_Response(
			array(
				'success' => false,
				'message' => 'Failed to restore backup',
			),
			400
		);
	}

	/**
	 * Delete backup
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Response object.
	 */
	public function delete_backup( WP_REST_Request $request ): WP_REST_Response {
		$backup_id      = $request->get_param( 'id' );
		$backup_manager = new WOOW_Backup_Manager( $this->settings );

		$result = $backup_manager->delete_backup( $backup_id );

		if ( $result ) {
			return new WP_REST_Response(
				array(
					'success' => true,
					'message' => 'Backup deleted successfully',
				),
				200
			);
		}

		return new WP_REST_Response(
			array(
				'success' => false,
				'message' => 'Failed to delete backup',
			),
			400
		);
	}

	/**
	 * Get generated CSS
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Response object.
	 */
	public function get_css( WP_REST_Request $request ): WP_REST_Response {
		$css_generator = new WOOW_CSS_Generator( $this->settings );
		$css           = $css_generator->generate();
		$metrics       = $css_generator->get_metrics();

		return new WP_REST_Response(
			array(
				'success' => true,
				'css'     => $css,
				'metrics' => $metrics,
			),
			200
		);
	}

	/**
	 * Validate settings
	 *
	 * @param mixed           $value   Value to validate.
	 * @param WP_REST_Request $request Request object.
	 * @param string          $param   Parameter name.
	 * @return bool True if valid.
	 */
	public function validate_settings( $value, WP_REST_Request $request, string $param ): bool {
		if ( ! is_array( $value ) ) {
			return false;
		}

		$validation = $this->settings->validate_settings( $value );

		return $validation['valid'];
	}
}
