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
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'apply_palette' ),
				'permission_callback' => array( $this, 'check_permissions' ),
				'args'                => array(
					'id' => array(
						'required'          => true,
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_text_field',
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
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'apply_template' ),
				'permission_callback' => array( $this, 'check_permissions' ),
				'args'                => array(
					'id' => array(
						'required'          => true,
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_text_field',
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
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Response object.
	 */
	public function get_palettes( WP_REST_Request $request ): WP_REST_Response {
		$palettes = $this->settings->get_available_palettes();

		return new WP_REST_Response(
			array(
				'success'  => true,
				'palettes' => $palettes,
			),
			200
		);
	}

	/**
	 * Apply palette
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Response object.
	 */
	public function apply_palette( WP_REST_Request $request ): WP_REST_Response {
		$palette_id = $request->get_param( 'id' );

		$result = $this->settings->apply_palette( $palette_id );

		if ( $result ) {
			// Clear CSS cache
			$cache = new WOOW_Cache_Manager();
			$cache->delete( 'woow_css' );

			return new WP_REST_Response(
				array(
					'success'  => true,
					'message'  => 'Palette applied successfully',
					'settings' => $this->settings->get_all_settings(),
				),
				200
			);
		}

		return new WP_REST_Response(
			array(
				'success' => false,
				'message' => 'Failed to apply palette',
			),
			400
		);
	}

	/**
	 * Get templates
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Response object.
	 */
	public function get_templates( WP_REST_Request $request ): WP_REST_Response {
		$template_manager = new WOOW_Template_Manager( $this->settings );
		$templates        = $template_manager->get_all_templates();

		return new WP_REST_Response(
			array(
				'success'   => true,
				'templates' => $templates,
			),
			200
		);
	}

	/**
	 * Apply template
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Response object.
	 */
	public function apply_template( WP_REST_Request $request ): WP_REST_Response {
		$template_id      = $request->get_param( 'id' );
		$template_manager = new WOOW_Template_Manager( $this->settings );

		$result = $template_manager->apply_template( $template_id );

		if ( $result ) {
			return new WP_REST_Response(
				array(
					'success'  => true,
					'message'  => 'Template applied successfully',
					'settings' => $this->settings->get_all_settings(),
				),
				200
			);
		}

		return new WP_REST_Response(
			array(
				'success' => false,
				'message' => 'Failed to apply template',
			),
			400
		);
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
