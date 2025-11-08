<?php
/**
 * WOOW_Admin Class
 *
 * Handles WordPress admin integration, page rendering, and AJAX endpoints.
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin Integration Class
 */
class WOOW_Admin {
	/**
	 * Settings manager instance
	 *
	 * @var WOOW_Settings
	 */
	private WOOW_Settings $settings;

	/**
	 * CSS generator instance
	 *
	 * @var WOOW_CSS_Generator
	 */
	private WOOW_CSS_Generator $css_generator;

	/**
	 * Cache manager instance
	 *
	 * @var WOOW_Cache_Manager
	 */
	private WOOW_Cache_Manager $cache;

	/**
	 * Rate limit: requests per minute
	 *
	 * @var int
	 */
	private const RATE_LIMIT = 60;

	/**
	 * Constructor - Inject dependencies
	 *
	 * @param WOOW_Settings      $settings      Settings manager.
	 * @param WOOW_CSS_Generator $css_generator CSS generator.
	 * @param WOOW_Cache_Manager $cache         Cache manager.
	 */
	public function __construct(
		WOOW_Settings $settings,
		WOOW_CSS_Generator $css_generator,
		WOOW_Cache_Manager $cache
	) {
		$this->settings      = $settings;
		$this->css_generator = $css_generator;
		$this->cache         = $cache;
	}

	/**
	 * Check rate limit for current user
	 *
	 * @return bool True if within limit, false if exceeded.
	 */
	private function check_rate_limit(): bool {
		$user_id = get_current_user_id();
		$key     = 'woow_rate_limit_' . $user_id;
		$count   = (int) get_transient( $key );

		if ( $count >= self::RATE_LIMIT ) {
			return false;
		}

		set_transient( $key, $count + 1, 60 );
		return true;
	}

	/**
	 * Register all WordPress hooks
	 *
	 * @return void
	 */
	public function add_hooks(): void {
		// Register admin menu
		add_action( 'admin_menu', array( $this, 'register_admin_page' ) );

		// Enqueue assets
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );

		// Inject generated CSS
		add_action( 'admin_head', array( $this, 'inject_generated_css' ) );

		// Register AJAX handlers
		add_action( 'wp_ajax_woow_save_settings', array( $this, 'ajax_save_settings' ) );
		add_action( 'wp_ajax_woow_apply_palette', array( $this, 'ajax_apply_palette' ) );
		add_action( 'wp_ajax_woow_apply_template', array( $this, 'ajax_apply_template' ) );
		add_action( 'wp_ajax_woow_preview_css', array( $this, 'ajax_preview_css' ) );
		add_action( 'wp_ajax_woow_export_settings', array( $this, 'ajax_export_settings' ) );
		add_action( 'wp_ajax_woow_import_settings', array( $this, 'ajax_import_settings' ) );
	}

	/**
	 * Register admin page and menu item
	 *
	 * @return void
	 */
	public function register_admin_page(): void {
		add_menu_page(
			__( 'WOOW! Admin', 'woow-admin' ),           // Page title
			__( 'WOOW! Admin', 'woow-admin' ),           // Menu title
			'manage_options',                             // Capability
			'woow-admin',                                 // Menu slug
			array( $this, 'render_admin_page' ),         // Callback
			'dashicons-admin-customizer',                 // Icon
			2                                             // Position (after Dashboard)
		);
	}

	/**
	 * Render admin page
	 *
	 * @return void
	 */
	public function render_admin_page(): void {
		// Check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'woow-admin' ) );
		}

		// Get current settings
		$settings = $this->settings->get_all();
		$palettes = $this->settings->get_available_palettes();
		$templates = $this->settings->get_available_templates();

		// Render template
		?>
		<div class="wrap woow-admin-wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			
			<div class="woow-admin-notice">
				<p>
					<strong><?php esc_html_e( 'Welcome to WOOW! Admin!', 'woow-admin' ); ?></strong>
				</p>
				<p>
					<?php esc_html_e( 'Transform your WordPress admin panel with modern glassmorphism design.', 'woow-admin' ); ?>
				</p>
			</div>

			<div class="woow-admin-content">
				<div class="woow-section">
					<h2><?php esc_html_e( 'Quick Start', 'woow-admin' ); ?></h2>
					<p><?php esc_html_e( 'Choose a color palette to get started:', 'woow-admin' ); ?></p>
					
					<div class="woow-palette-grid">
						<?php foreach ( $palettes as $palette ) : ?>
							<div class="woow-palette-card" data-palette-id="<?php echo esc_attr( $palette['id'] ); ?>">
								<h3><?php echo esc_html( $palette['name'] ); ?></h3>
								<p><?php echo esc_html( $palette['description'] ); ?></p>
								<div class="woow-palette-colors">
									<?php foreach ( array_slice( $palette['colors'], 0, 5 ) as $color ) : ?>
										<span class="woow-color-swatch" style="background-color: <?php echo esc_attr( $color ); ?>"></span>
									<?php endforeach; ?>
								</div>
								<button type="button" class="button button-primary woow-apply-palette" data-palette-id="<?php echo esc_attr( $palette['id'] ); ?>">
									<?php esc_html_e( 'Apply Palette', 'woow-admin' ); ?>
								</button>
							</div>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="woow-section">
					<h2><?php esc_html_e( 'Design Templates', 'woow-admin' ); ?></h2>
					<p><?php esc_html_e( 'Or choose a complete design template:', 'woow-admin' ); ?></p>
					
					<div class="woow-template-grid">
						<?php foreach ( $templates as $template ) : ?>
							<div class="woow-template-card" data-template-id="<?php echo esc_attr( $template['id'] ); ?>">
								<h3><?php echo esc_html( $template['name'] ); ?></h3>
								<p><?php echo esc_html( $template['description'] ); ?></p>
								<button type="button" class="button button-primary woow-apply-template" data-template-id="<?php echo esc_attr( $template['id'] ); ?>">
									<?php esc_html_e( 'Apply Template', 'woow-admin' ); ?>
								</button>
							</div>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="woow-section">
					<h2><?php esc_html_e( 'Cache Statistics', 'woow-admin' ); ?></h2>
					<?php
					$cache_stats = $this->cache->get_stats();
					?>
					<table class="widefat">
						<tbody>
							<tr>
								<th><?php esc_html_e( 'Cache Hit Rate', 'woow-admin' ); ?></th>
								<td><strong><?php echo esc_html( $cache_stats['hit_rate'] ); ?>%</strong></td>
							</tr>
							<tr>
								<th><?php esc_html_e( 'Cache Hits', 'woow-admin' ); ?></th>
								<td><?php echo esc_html( number_format( $cache_stats['hits'] ) ); ?></td>
							</tr>
							<tr>
								<th><?php esc_html_e( 'Cache Misses', 'woow-admin' ); ?></th>
								<td><?php echo esc_html( number_format( $cache_stats['misses'] ) ); ?></td>
							</tr>
							<tr>
								<th><?php esc_html_e( 'Cache Size', 'woow-admin' ); ?></th>
								<td><?php echo esc_html( $cache_stats['size_kb'] ); ?> KB</td>
							</tr>
						</tbody>
					</table>
					<p>
						<button type="button" class="button woow-clear-cache">
							<?php esc_html_e( 'Clear Cache', 'woow-admin' ); ?>
						</button>
					</p>
				</div>
			</div>
		</div>

		<style>
			.woow-admin-wrap {
				max-width: 1200px;
				margin: 20px 0;
			}
			.woow-admin-notice {
				background: linear-gradient(to right, #6366f1, #8b5cf6);
				color: white;
				padding: 20px;
				border-radius: 8px;
				margin: 20px 0;
			}
			.woow-section {
				background: white;
				padding: 20px;
				margin: 20px 0;
				border-radius: 8px;
				box-shadow: 0 1px 3px rgba(0,0,0,0.1);
			}
			.woow-palette-grid,
			.woow-template-grid {
				display: grid;
				grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
				gap: 20px;
				margin-top: 20px;
			}
			.woow-palette-card,
			.woow-template-card {
				border: 1px solid #ddd;
				padding: 20px;
				border-radius: 8px;
				transition: all 0.2s;
			}
			.woow-palette-card:hover,
			.woow-template-card:hover {
				box-shadow: 0 4px 12px rgba(0,0,0,0.1);
				transform: translateY(-2px);
			}
			.woow-palette-colors {
				display: flex;
				gap: 8px;
				margin: 15px 0;
			}
			.woow-color-swatch {
				width: 40px;
				height: 40px;
				border-radius: 50%;
				border: 2px solid white;
				box-shadow: 0 2px 4px rgba(0,0,0,0.1);
			}
		</style>
		<?php
	}

	/**
	 * Enqueue admin assets
	 *
	 * @param string $hook Current admin page hook.
	 * @return void
	 */
	public function enqueue_admin_assets( string $hook ): void {
		// Only load on WOOW! Admin page
		if ( 'toplevel_page_woow-admin' !== $hook ) {
			return;
		}

		// Enqueue styles (if built)
		$css_file = WOOW_PLUGIN_DIR . 'assets/dist/css/style.css';
		if ( file_exists( $css_file ) ) {
			wp_enqueue_style(
				'woow-admin',
				WOOW_ASSETS_URL . 'css/style.css',
				array(),
				WOOW_VERSION
			);
		}

		// Enqueue scripts (if built)
		$js_file = WOOW_PLUGIN_DIR . 'assets/dist/js/main.js';
		if ( file_exists( $js_file ) ) {
			wp_enqueue_script(
				'woow-admin',
				WOOW_ASSETS_URL . 'js/main.js',
				array(),
				WOOW_VERSION,
				true
			);

			// Localize script
			wp_localize_script(
				'woow-admin',
				'woowAdmin',
				array(
					'ajaxUrl' => admin_url( 'admin-ajax.php' ),
					'nonce'   => wp_create_nonce( 'woow_nonce' ),
					'i18n'    => array(
						'confirmReset' => __( 'Are you sure you want to reset to defaults?', 'woow-admin' ),
						'saved'        => __( 'Settings saved successfully!', 'woow-admin' ),
						'error'        => __( 'An error occurred. Please try again.', 'woow-admin' ),
					),
				)
			);
		}
	}

	/**
	 * Inject generated CSS into admin head
	 *
	 * @return void
	 */
	public function inject_generated_css(): void {
		// Check if plugin is enabled
		if ( ! $this->settings->get_option( 'general.enabled', true ) ) {
			return;
		}

		// Try to get from cache first
		$cache_key = 'css_' . md5( serialize( $this->settings->get_all() ) );
		$css       = $this->cache->get( $cache_key );

		if ( false === $css ) {
			// Generate CSS
			$css = $this->css_generator->generate();

			// Cache it
			$this->cache->set( $cache_key, $css, 86400 ); // 24 hours
		}

		// Output CSS
		if ( ! empty( $css ) ) {
			echo '<style id="woow-generated-css">' . $css . '</style>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * AJAX handler: Save settings
	 *
	 * @return void
	 */
	public function ajax_save_settings(): void {
		// Verify nonce
		check_ajax_referer( 'woow_nonce', 'nonce' );

		// Check capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Insufficient permissions', 'woow-admin' ) ) );
		}

		// Check rate limit
		if ( ! $this->check_rate_limit() ) {
			wp_send_json_error( array(
				'message' => __( 'Rate limit exceeded. Please try again later.', 'woow-admin' ),
				'code'    => 'rate_limit_exceeded',
			) );
		}

		// Get settings from request
		$settings = isset( $_POST['settings'] ) ? json_decode( stripslashes( $_POST['settings'] ), true ) : array();

		if ( empty( $settings ) ) {
			wp_send_json_error( array(
				'message' => __( 'No settings provided', 'woow-admin' ),
				'code'    => 'no_settings',
			) );
		}

		// Validate settings
		$validation = $this->settings->validate_settings( $settings );

		if ( ! $validation['valid'] ) {
			wp_send_json_error( array(
				'message' => __( 'Invalid settings provided', 'woow-admin' ),
				'code'    => 'invalid_settings',
				'errors'  => $validation['errors'],
			) );
		}

		// Save settings
		$result = $this->settings->save_settings( $settings );

		if ( ! $result ) {
			wp_send_json_error( array(
				'message' => __( 'Failed to save settings', 'woow-admin' ),
				'code'    => 'save_failed',
			) );
		}

		// Clear CSS cache
		$cache_key = 'css_' . md5( serialize( $this->settings->get_all() ) );
		$this->cache->delete( $cache_key );

		// Get updated settings
		$updated_settings = $this->settings->get_all();

		wp_send_json_success( array(
			'message'  => __( 'Settings saved successfully', 'woow-admin' ),
			'settings' => $updated_settings,
		) );
	}

	/**
	 * AJAX handler: Apply palette
	 *
	 * @return void
	 */
	public function ajax_apply_palette(): void {
		// Verify nonce
		check_ajax_referer( 'woow_nonce', 'nonce' );

		// Check capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array(
				'message' => __( 'Insufficient permissions', 'woow-admin' ),
				'code'    => 'insufficient_permissions',
			) );
		}

		// Check rate limit
		if ( ! $this->check_rate_limit() ) {
			wp_send_json_error( array(
				'message' => __( 'Rate limit exceeded. Please try again later.', 'woow-admin' ),
				'code'    => 'rate_limit_exceeded',
			) );
		}

		$palette_id = isset( $_POST['palette_id'] ) ? sanitize_text_field( $_POST['palette_id'] ) : '';

		if ( empty( $palette_id ) ) {
			wp_send_json_error( array(
				'message' => __( 'No palette ID provided', 'woow-admin' ),
				'code'    => 'no_palette_id',
			) );
		}

		// Apply palette
		$result = $this->settings->apply_palette( $palette_id );

		if ( ! $result ) {
			wp_send_json_error( array(
				'message' => __( 'Failed to apply palette', 'woow-admin' ),
				'code'    => 'apply_failed',
			) );
		}

		// Clear CSS cache
		$this->cache->flush();

		// Generate new CSS
		$css     = $this->css_generator->generate();
		$metrics = $this->css_generator->get_metrics();

		// Get updated settings
		$updated_settings = $this->settings->get_all();

		wp_send_json_success( array(
			'message'  => __( 'Palette applied successfully', 'woow-admin' ),
			'palette'  => $palette_id,
			'css'      => $css,
			'settings' => $updated_settings,
			'metrics'  => $metrics,
		) );
	}

	/**
	 * AJAX handler: Apply template
	 *
	 * @return void
	 */
	public function ajax_apply_template(): void {
		// Verify nonce
		check_ajax_referer( 'woow_nonce', 'nonce' );

		// Check capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array(
				'message' => __( 'Insufficient permissions', 'woow-admin' ),
				'code'    => 'insufficient_permissions',
			) );
		}

		// Check rate limit
		if ( ! $this->check_rate_limit() ) {
			wp_send_json_error( array(
				'message' => __( 'Rate limit exceeded. Please try again later.', 'woow-admin' ),
				'code'    => 'rate_limit_exceeded',
			) );
		}

		$template_id = isset( $_POST['template_id'] ) ? sanitize_text_field( $_POST['template_id'] ) : '';

		if ( empty( $template_id ) ) {
			wp_send_json_error( array(
				'message' => __( 'No template ID provided', 'woow-admin' ),
				'code'    => 'no_template_id',
			) );
		}

		// Apply template
		$result = $this->settings->apply_template( $template_id );

		if ( ! $result ) {
			wp_send_json_error( array(
				'message' => __( 'Failed to apply template', 'woow-admin' ),
				'code'    => 'apply_failed',
			) );
		}

		// Clear CSS cache
		$this->cache->flush();

		// Generate new CSS
		$css     = $this->css_generator->generate();
		$metrics = $this->css_generator->get_metrics();

		// Get updated settings
		$updated_settings = $this->settings->get_all();

		wp_send_json_success( array(
			'message'  => __( 'Template applied successfully', 'woow-admin' ),
			'template' => $template_id,
			'css'      => $css,
			'settings' => $updated_settings,
			'metrics'  => $metrics,
		) );
	}

	/**
	 * AJAX handler: Preview CSS
	 *
	 * @return void
	 */
	public function ajax_preview_css(): void {
		// Verify nonce
		check_ajax_referer( 'woow_nonce', 'nonce' );

		// Check capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array(
				'message' => __( 'Insufficient permissions', 'woow-admin' ),
				'code'    => 'insufficient_permissions',
			) );
		}

		// Check rate limit
		if ( ! $this->check_rate_limit() ) {
			wp_send_json_error( array(
				'message' => __( 'Rate limit exceeded. Please try again later.', 'woow-admin' ),
				'code'    => 'rate_limit_exceeded',
			) );
		}

		// Get settings from request (for preview without saving)
		$preview_settings = isset( $_POST['settings'] ) ? json_decode( stripslashes( $_POST['settings'] ), true ) : null;

		// If preview settings provided, temporarily use them
		if ( ! empty( $preview_settings ) ) {
			// Create temporary settings instance for preview
			$temp_settings = clone $this->settings;
			
			// Validate preview settings
			$validation = $temp_settings->validate_settings( $preview_settings );
			
			if ( ! $validation['valid'] ) {
				wp_send_json_error( array(
					'message' => __( 'Invalid preview settings', 'woow-admin' ),
					'code'    => 'invalid_settings',
					'errors'  => $validation['errors'],
				) );
			}
			
			// Apply preview settings temporarily (without saving)
			foreach ( $preview_settings as $section => $values ) {
				$temp_settings->update_section( $section, $values );
			}
			
			// Generate CSS with preview settings
			$temp_generator = new WOOW_CSS_Generator( $temp_settings );
			$css            = $temp_generator->generate();
			$metrics        = $temp_generator->get_metrics();
		} else {
			// Generate CSS with current settings
			$css     = $this->css_generator->generate();
			$metrics = $this->css_generator->get_metrics();
		}

		wp_send_json_success( array(
			'css'     => $css,
			'metrics' => $metrics,
		) );
	}

	/**
	 * AJAX handler: Export settings
	 *
	 * @return void
	 */
	public function ajax_export_settings(): void {
		// Verify nonce
		check_ajax_referer( 'woow_nonce', 'nonce' );

		// Check capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array(
				'message' => __( 'Insufficient permissions', 'woow-admin' ),
				'code'    => 'insufficient_permissions',
			) );
		}

		// Check rate limit
		if ( ! $this->check_rate_limit() ) {
			wp_send_json_error( array(
				'message' => __( 'Rate limit exceeded. Please try again later.', 'woow-admin' ),
				'code'    => 'rate_limit_exceeded',
			) );
		}

		try {
			$json = $this->settings->export_settings();

			if ( empty( $json ) ) {
				wp_send_json_error( array(
					'message' => __( 'Failed to export settings', 'woow-admin' ),
					'code'    => 'export_failed',
				) );
			}

			wp_send_json_success( array(
				'json'     => $json,
				'filename' => 'woow-admin-settings-' . gmdate( 'Y-m-d-His' ) . '.json',
			) );
		} catch ( Exception $e ) {
			wp_send_json_error( array(
				'message' => __( 'Export error: ', 'woow-admin' ) . $e->getMessage(),
				'code'    => 'export_exception',
			) );
		}
	}

	/**
	 * AJAX handler: Import settings
	 *
	 * @return void
	 */
	public function ajax_import_settings(): void {
		// Verify nonce
		check_ajax_referer( 'woow_nonce', 'nonce' );

		// Check capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array(
				'message' => __( 'Insufficient permissions', 'woow-admin' ),
				'code'    => 'insufficient_permissions',
			) );
		}

		// Check rate limit
		if ( ! $this->check_rate_limit() ) {
			wp_send_json_error( array(
				'message' => __( 'Rate limit exceeded. Please try again later.', 'woow-admin' ),
				'code'    => 'rate_limit_exceeded',
			) );
		}

		$json = isset( $_POST['json'] ) ? stripslashes( $_POST['json'] ) : '';

		if ( empty( $json ) ) {
			wp_send_json_error( array(
				'message' => __( 'No JSON provided', 'woow-admin' ),
				'code'    => 'no_json',
			) );
		}

		// Validate JSON structure
		$decoded = json_decode( $json, true );

		if ( json_last_error() !== JSON_ERROR_NONE ) {
			wp_send_json_error( array(
				'message' => __( 'Invalid JSON format', 'woow-admin' ),
				'code'    => 'invalid_json',
				'error'   => json_last_error_msg(),
			) );
		}

		// Create backup before import
		$backup_created = $this->create_backup_before_import();

		if ( ! $backup_created ) {
			wp_send_json_error( array(
				'message' => __( 'Failed to create backup before import', 'woow-admin' ),
				'code'    => 'backup_failed',
			) );
		}

		// Import settings
		try {
			$result = $this->settings->import_settings( $json );

			if ( ! $result ) {
				wp_send_json_error( array(
					'message' => __( 'Failed to import settings', 'woow-admin' ),
					'code'    => 'import_failed',
				) );
			}

			// Clear CSS cache
			$this->cache->flush();

			// Get updated settings
			$updated_settings = $this->settings->get_all();

			wp_send_json_success( array(
				'message'  => __( 'Settings imported successfully', 'woow-admin' ),
				'settings' => $updated_settings,
			) );
		} catch ( Exception $e ) {
			wp_send_json_error( array(
				'message' => __( 'Import error: ', 'woow-admin' ) . $e->getMessage(),
				'code'    => 'import_exception',
			) );
		}
	}

	/**
	 * Create backup before import
	 *
	 * @return bool True on success, false on failure.
	 */
	private function create_backup_before_import(): bool {
		$current_settings = $this->settings->get_all();
		$backup_key       = 'woow_backup_before_import_' . time();
		
		return update_option( $backup_key, $current_settings, false );
	}
}
