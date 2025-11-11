<?php
/**
 * Plugin Name: WOOW! Admin
 * Plugin URI: https://github.com/m3n3sx/woow-admin
 * Description: Transform your WordPress admin panel with modern glassmorphism design, 10 color palettes, 11 templates, and real-time customization.
 * Version: 2.0.0-beta
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * Author: WOOW! Team
 * Author URI: https://github.com/m3n3sx
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: woow-admin
 * Domain Path: /languages
 *
 * @package WoowAdmin
 * @since 2.0.0-beta
 */

declare(strict_types=1);

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
define( 'WOOW_VERSION', '2.0.0-beta.' . time() ); // Force cache bust
define( 'WOOW_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WOOW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WOOW_ASSETS_URL', WOOW_PLUGIN_URL . 'assets/dist/' );
define( 'WOOW_MIN_WP_VERSION', '6.0' );
define( 'WOOW_MIN_PHP_VERSION', '8.0' );

/**
 * Check system requirements on activation
 *
 * @since 1.0.0
 * @return void
 */
function woow_activation_check(): void {
    global $wp_version;

    // Check WordPress version
    if ( version_compare( $wp_version, WOOW_MIN_WP_VERSION, '<' ) ) {
        deactivate_plugins( plugin_basename( __FILE__ ) );
        wp_die(
            sprintf(
                /* translators: 1: Required WordPress version, 2: Current WordPress version */
                esc_html__( 'WOOW! Admin requires WordPress %1$s or higher. You are running version %2$s.', 'woow-admin' ),
                esc_html( WOOW_MIN_WP_VERSION ),
                esc_html( $wp_version )
            ),
            esc_html__( 'Plugin Activation Error', 'woow-admin' ),
            array( 'back_link' => true )
        );
    }

    // Check PHP version
    if ( version_compare( PHP_VERSION, WOOW_MIN_PHP_VERSION, '<' ) ) {
        deactivate_plugins( plugin_basename( __FILE__ ) );
        wp_die(
            sprintf(
                /* translators: 1: Required PHP version, 2: Current PHP version */
                esc_html__( 'WOOW! Admin requires PHP %1$s or higher. You are running version %2$s.', 'woow-admin' ),
                esc_html( WOOW_MIN_PHP_VERSION ),
                esc_html( PHP_VERSION )
            ),
            esc_html__( 'Plugin Activation Error', 'woow-admin' ),
            array( 'back_link' => true )
        );
    }

    // Initialize default settings
    woow_initialize_default_settings();

    // Create activation backup
    woow_create_activation_backup();

    // Schedule auto palette switching cron job
    if ( ! wp_next_scheduled( 'woow_auto_palette_switch' ) ) {
        wp_schedule_event( time(), 'hourly', 'woow_auto_palette_switch' );
    }
}

/**
 * Initialize default settings on activation
 *
 * @since 1.0.0
 * @return void
 */
function woow_initialize_default_settings(): void {
    $existing_settings = get_option( 'woow_settings' );
    
    if ( false === $existing_settings ) {
        // Settings will be initialized by WOOW_Settings class
        // This just ensures the option exists
        add_option( 'woow_settings', array() );
        
        // Log activation
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( '[WOOW Admin] Plugin activated - default settings initialized' );
        }
    }
}

/**
 * Create activation backup
 *
 * @since 1.0.0
 * @return void
 */
function woow_create_activation_backup(): void {
    $backup_data = array(
        'timestamp' => time(),
        'label'     => 'activation',
        'settings'  => get_option( 'woow_settings', array() ),
        'version'   => WOOW_VERSION,
    );
    
    add_option( 'woow_backup_activation_' . time(), $backup_data );
}

/**
 * Clean up on deactivation
 *
 * @since 1.0.0
 * @return void
 */
function woow_deactivation_cleanup(): void {
    // Clear all transient caches
    global $wpdb;
    
    $wpdb->query(
        $wpdb->prepare(
            "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
            $wpdb->esc_like( '_transient_woow_' ) . '%'
        )
    );
    
    $wpdb->query(
        $wpdb->prepare(
            "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
            $wpdb->esc_like( '_transient_timeout_woow_' ) . '%'
        )
    );
    
    // Unschedule cron jobs
    $timestamp = wp_next_scheduled( 'woow_auto_palette_switch' );
    if ( $timestamp ) {
        wp_unschedule_event( $timestamp, 'woow_auto_palette_switch' );
    }
    
    // Log deactivation
    if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
        error_log( '[WOOW Admin] Plugin deactivated - caches cleared, cron jobs unscheduled' );
    }
}

// Register activation and deactivation hooks
register_activation_hook( __FILE__, 'woow_activation_check' );
register_deactivation_hook( __FILE__, 'woow_deactivation_cleanup' );

/**
 * Load Composer autoloader
 *
 * @since 1.0.0
 * @return void
 */
function woow_load_autoloader(): void {
    $autoloader = WOOW_PLUGIN_DIR . 'vendor/autoload.php';
    
    if ( file_exists( $autoloader ) ) {
        require_once $autoloader;
    } else {
        add_action( 'admin_notices', 'woow_missing_autoloader_notice' );
        return;
    }
}

/**
 * Display notice if autoloader is missing
 *
 * @since 1.0.0
 * @return void
 */
function woow_missing_autoloader_notice(): void {
    ?>
    <div class="notice notice-error">
        <p>
            <?php
            echo wp_kses_post(
                sprintf(
                    /* translators: %s: composer install command */
                    __( '<strong>WOOW! Admin:</strong> Composer autoloader not found. Please run %s in the plugin directory.', 'woow-admin' ),
                    '<code>composer install</code>'
                )
            );
            ?>
        </p>
    </div>
    <?php
}

/**
 * Initialize the plugin
 *
 * @since 1.0.0
 * @return void
 */
function woow_init(): void {
    // Load defaults
    require_once WOOW_PLUGIN_DIR . 'includes/defaults.php';
    
    // Load autoloader
    woow_load_autoloader();
    
    // Load text domain for translations
    load_plugin_textdomain(
        'woow-admin',
        false,
        dirname( plugin_basename( __FILE__ ) ) . '/languages'
    );
    
    // Clear cache if version changed
    $saved_version = get_option( 'woow_version', '0.0.0' );
    if ( version_compare( $saved_version, WOOW_VERSION, '<' ) ) {
        // Clear all WOOW caches
        global $wpdb;
        $wpdb->query(
            "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_woow_%' OR option_name LIKE '_transient_timeout_woow_%'"
        );
        if ( function_exists( 'wp_cache_flush' ) ) {
            wp_cache_flush();
        }
        update_option( 'woow_version', WOOW_VERSION );
        
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( '[WOOW Admin] Cache cleared after version update: ' . $saved_version . ' -> ' . WOOW_VERSION );
        }
    }
    
    // Run migrations
    if ( class_exists( 'WOOW_Migration' ) ) {
        $migration = new WOOW_Migration();
        $migration->run_migrations();
    }
    
    // Initialize plugin classes
    if ( class_exists( 'WOOW_Settings' ) && 
         class_exists( 'WOOW_CSS_Generator' ) && 
         class_exists( 'WOOW_Cache_Manager' ) &&
         class_exists( 'WOOW_Backup_Manager' ) &&
         class_exists( 'WOOW_Template_Manager' ) &&
         class_exists( 'WOOW_Mobile_Optimizer' ) &&
         class_exists( 'WOOW_Admin' ) ) {
        
        // Create instances with dependency injection
        $settings          = new WOOW_Settings();
        $cache             = new WOOW_Cache_Manager();
        $css_generator     = new WOOW_CSS_Generator( $settings );
        $backup_manager    = new WOOW_Backup_Manager( $settings );
        $template_manager  = new WOOW_Template_Manager( $settings );
        $mobile_optimizer  = new WOOW_Mobile_Optimizer();
        $admin             = new WOOW_Admin( $settings, $css_generator, $cache, $backup_manager, $template_manager );
        
        // Register hooks
        $admin->add_hooks();
        
        // Initialize REST API
        if ( class_exists( 'WOOW_REST_API' ) ) {
            $rest_api = new WOOW_REST_API( $settings );
            add_action( 'rest_api_init', array( $rest_api, 'register_routes' ) );
        }
        
        // Log initialization in debug mode
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( '[WOOW Admin] Plugin initialized successfully' );
        }
    }
}

// Initialize plugin on plugins_loaded hook
add_action( 'plugins_loaded', 'woow_init' );

/**
 * Display admin notice on successful activation
 *
 * @since 1.0.0
 * @return void
 */
function woow_activation_notice(): void {
    $screen = get_current_screen();
    
    if ( 'plugins' === $screen->id && get_transient( 'woow_activation_notice' ) ) {
        ?>
        <div class="notice notice-success is-dismissible">
            <p>
                <?php
                echo wp_kses_post(
                    sprintf(
                        /* translators: %s: Link to settings page */
                        __( '<strong>WOOW! Admin</strong> has been activated successfully! %s to start customizing your admin panel.', 'woow-admin' ),
                        '<a href="' . esc_url( admin_url( 'admin.php?page=woow-admin' ) ) . '">' . esc_html__( 'Go to Settings', 'woow-admin' ) . '</a>'
                    )
                );
                ?>
            </p>
        </div>
        <?php
        delete_transient( 'woow_activation_notice' );
    }
}
add_action( 'admin_notices', 'woow_activation_notice' );

// Set activation notice transient
register_activation_hook( __FILE__, function() {
    set_transient( 'woow_activation_notice', true, 60 );
} );
