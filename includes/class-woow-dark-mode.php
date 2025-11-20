<?php
/**
 * WOOW_Dark_Mode Class
 *
 * Manages dark mode detection, time-based switching, and theme application.
 * Provides automatic detection based on system preferences, time-based switching,
 * and manual override controls.
 *
 * @package WoowAdmin
 * @since 2.0.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Dark Mode Manager Class
 */
class WOOW_Dark_Mode {
    /**
     * Option name in database
     *
     * @var string
     */
    private const OPTION_NAME = 'woow_dark_mode';

    /**
     * Night time start hour (24-hour format)
     *
     * @var int
     */
    private const NIGHT_START_HOUR = 20; // 8 PM

    /**
     * Night time end hour (24-hour format)
     *
     * @var int
     */
    private const NIGHT_END_HOUR = 6; // 6 AM

    /**
     * Valid mode values
     *
     * @var array
     */
    private const VALID_MODES = array( 'auto', 'enabled', 'disabled' );

    /**
     * Constructor
     */
    public function __construct() {
        // Initialize hooks will be called by init() method
    }

    /**
     * Initialize dark mode system
     * Registers hooks for CSS/JS enqueuing
     * 
     * Hooks into wp_head, admin_head, and login_head actions (Requirements 4.1-4.5)
     *
     * @return void
     */
    public function init(): void {
        // Enqueue dark mode styles and scripts for admin (Requirements 4.1, 4.2, 4.3)
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_dark_mode_style' ), 10 );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_dark_mode_script' ), 10 );
        
        // Enqueue dark mode styles and scripts for frontend (Requirement 4.5)
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_dark_mode_style' ), 10 );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_dark_mode_script' ), 10 );
        
        // Enqueue dark mode styles and scripts for login screen (Requirement 4.4)
        add_action( 'login_enqueue_scripts', array( $this, 'enqueue_login_dark_mode' ), 10 );

        // Add early inline script to prevent flash (before wp_head)
        add_action( 'admin_head', array( $this, 'add_early_dark_mode_script' ), 1 );
        add_action( 'wp_head', array( $this, 'add_early_dark_mode_script' ), 1 );
        add_action( 'login_head', array( $this, 'add_early_dark_mode_script' ), 1 );

        // Add body class for dark mode
        add_filter( 'admin_body_class', array( $this, 'add_dark_mode_body_class' ) );
        add_filter( 'body_class', array( $this, 'add_dark_mode_body_class_frontend' ) );
        add_filter( 'login_body_class', array( $this, 'add_dark_mode_body_class_frontend' ) );
    }

    /**
     * Enqueue dark mode stylesheet for admin and frontend
     * Hooked to: wp_enqueue_scripts, admin_enqueue_scripts
     * 
     * Implements conditional loading based on active mode (Requirement 9.1, 9.4)
     * Adds proper asset versioning and cache busting (Requirement 9.1)
     *
     * @return void
     */
    public function enqueue_dark_mode_style(): void {
        // Only enqueue if dark mode should be active (Requirement 9.4)
        if ( ! $this->should_use_dark_mode() ) {
            return;
        }

        // Get file path for cache busting
        $css_file = WOOW_PLUGIN_DIR . 'assets/dist/dark-mode.css';
        $version = file_exists( $css_file ) ? filemtime( $css_file ) : WOOW_VERSION;

        // Enqueue dark mode stylesheet (Requirements 4.1, 4.2, 4.3, 4.5)
        wp_enqueue_style(
            'woow-dark-mode',
            WOOW_ASSETS_URL . 'dark-mode.css',
            array(),
            $version,
            'all'
        );

        // Add inline critical CSS to prevent flash of unstyled content
        $this->add_inline_critical_css();
    }

    /**
     * Enqueue dark mode JavaScript for admin and frontend
     * Hooked to: wp_enqueue_scripts, admin_enqueue_scripts
     * 
     * Adds proper asset versioning and cache busting
     * Note: JavaScript file will be created in task 4
     *
     * @return void
     */
    public function enqueue_dark_mode_script(): void {
        // Get file path for cache busting
        $js_file = WOOW_PLUGIN_DIR . 'assets/dist/dark-mode.js';
        
        // Only enqueue if file exists (will be created in task 4)
        if ( ! file_exists( $js_file ) ) {
            return;
        }
        
        $version = filemtime( $js_file );

        wp_enqueue_script(
            'woow-dark-mode',
            WOOW_ASSETS_URL . 'dark-mode.js',
            array(),
            $version,
            true
        );

        // Localize script with configuration
        $this->localize_script();
    }

    /**
     * Enqueue login page dark mode styles
     * Hooked to: login_enqueue_scripts
     * 
     * Implements conditional loading for login screen (Requirement 4.4)
     * Adds proper asset versioning and cache busting
     *
     * @return void
     */
    public function enqueue_login_dark_mode(): void {
        // Only enqueue if dark mode should be active (Requirement 9.4)
        if ( ! $this->should_use_dark_mode() ) {
            return;
        }

        // Get file path for cache busting
        $css_file = WOOW_PLUGIN_DIR . 'assets/dist/dark-mode.css';
        $version = file_exists( $css_file ) ? filemtime( $css_file ) : WOOW_VERSION;

        // Enqueue dark mode stylesheet for login screen (Requirement 4.4)
        wp_enqueue_style(
            'woow-dark-mode-login',
            WOOW_ASSETS_URL . 'dark-mode.css',
            array(),
            $version,
            'all'
        );

        // Enqueue JavaScript for login screen (if file exists - created in task 4)
        $js_file = WOOW_PLUGIN_DIR . 'assets/dist/dark-mode.js';
        
        if ( file_exists( $js_file ) ) {
            $js_version = filemtime( $js_file );

            wp_enqueue_script(
                'woow-dark-mode-login',
                WOOW_ASSETS_URL . 'dark-mode.js',
                array(),
                $js_version,
                true
            );

            // Localize script for login screen
            $this->localize_script();
        }

        // Add inline critical CSS to prevent flash
        $this->add_inline_critical_css();
    }

    /**
     * Determine if dark mode should be active
     * Implements auto/enabled/disabled logic
     *
     * @return bool True if dark mode should be enabled
     */
    public function should_use_dark_mode(): bool {
        $mode = $this->get_dark_mode();

        switch ( $mode ) {
            case 'enabled':
                // Always dark mode
                return true;

            case 'disabled':
                // Always light mode
                return false;

            case 'auto':
            default:
                // Auto mode: use time-based detection
                // System preference detection is handled by JavaScript
                // PHP fallback uses time-based detection
                return $this->is_night_time();
        }
    }

    /**
     * Get current dark mode setting
     *
     * @return string 'auto'|'enabled'|'disabled'
     */
    public function get_dark_mode(): string {
        $mode = get_option( self::OPTION_NAME, 'auto' );

        // Validate mode value
        if ( ! in_array( $mode, self::VALID_MODES, true ) ) {
            return 'auto';
        }

        return $mode;
    }

    /**
     * Set dark mode setting
     *
     * @param string $mode Mode value ('auto'|'enabled'|'disabled')
     * @return bool True on success, false on failure
     */
    public function set_dark_mode( string $mode ): bool {
        // Validate mode value
        if ( ! in_array( $mode, self::VALID_MODES, true ) ) {
            return false;
        }

        return update_option( self::OPTION_NAME, $mode );
    }

    /**
     * Check if current time is night (8 PM - 6 AM)
     * Uses WordPress timezone setting
     *
     * @return bool True if night time
     */
    private function is_night_time(): bool {
        // Get current time in WordPress timezone
        $timezone_string = get_option( 'timezone_string' );
        
        if ( empty( $timezone_string ) ) {
            // Fallback to GMT offset if timezone_string is not set
            $gmt_offset = get_option( 'gmt_offset', 0 );
            $timezone_string = $this->get_timezone_from_gmt_offset( $gmt_offset );
        }

        try {
            $timezone = new DateTimeZone( $timezone_string );
            $datetime = new DateTime( 'now', $timezone );
            $current_hour = (int) $datetime->format( 'G' ); // 24-hour format without leading zeros

            // Night time is from 8 PM (20:00) to 6 AM (06:00)
            // This means hours 20, 21, 22, 23, 0, 1, 2, 3, 4, 5
            if ( $current_hour >= self::NIGHT_START_HOUR || $current_hour < self::NIGHT_END_HOUR ) {
                return true;
            }

            return false;
        } catch ( Exception $e ) {
            // If timezone is invalid, fallback to UTC
            if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                error_log( '[WOOW Dark Mode] Invalid timezone: ' . $timezone_string . ' - ' . $e->getMessage() );
            }

            // Fallback to UTC
            $datetime = new DateTime( 'now', new DateTimeZone( 'UTC' ) );
            $current_hour = (int) $datetime->format( 'G' );

            if ( $current_hour >= self::NIGHT_START_HOUR || $current_hour < self::NIGHT_END_HOUR ) {
                return true;
            }

            return false;
        }
    }

    /**
     * Convert GMT offset to timezone string
     *
     * @param float $gmt_offset GMT offset in hours
     * @return string Timezone string
     */
    private function get_timezone_from_gmt_offset( float $gmt_offset ): string {
        // Convert offset to seconds
        $offset_seconds = (int) ( $gmt_offset * 3600 );

        // Get timezone name from offset
        $timezone_name = timezone_name_from_abbr( '', $offset_seconds, 0 );

        if ( false === $timezone_name ) {
            // Fallback to UTC if conversion fails
            return 'UTC';
        }

        return $timezone_name;
    }

    /**
     * Add early dark mode detection script
     * Runs before page render to prevent flash of unstyled content
     * 
     * Implements requirement 6.3: Apply dark styles before initial render
     * Implements requirement 10.2: Read localStorage before server requests
     * Implements requirement 6.1, 6.2: Prevent transitions on initial load
     * Implements requirement 9.2: Initialization completes within 100ms
     * Implements requirement 9.5: Prevent layout shifts during theme switch
     *
     * @return void
     */
    public function add_early_dark_mode_script(): void {
        // Only add if dark mode could be active
        $mode = $this->get_dark_mode();
        
        // Skip if explicitly disabled
        if ( 'disabled' === $mode ) {
            return;
        }

        ?>
        <script id="woow-dark-mode-early">
        (function() {
            'use strict';
            
            // Performance: Track initialization time (Requirement 9.2)
            var startTime = performance.now();
            
            // Prevent layout shifts by setting theme before first paint (Requirement 9.5)
            // Add preload class to prevent transitions during initial load (Requirements 6.1, 6.2, 6.3)
            document.documentElement.classList.add('woow-preload');
            
            // Read localStorage immediately (Requirement 10.2)
            // This is synchronous and fast (<1ms typically)
            var storedMode = null;
            try {
                storedMode = localStorage.getItem('woow-dark-mode');
            } catch (e) {
                // localStorage unavailable (private browsing, etc.)
                // Continue with server-side detection
            }
            
            var shouldApplyDark = false;
            
            // Determine if dark mode should be applied
            if (storedMode === 'enabled') {
                shouldApplyDark = true;
            } else if (storedMode === 'disabled') {
                shouldApplyDark = false;
            } else {
                // Auto mode: check system preference or time
                // System preference check is synchronous and fast (<1ms)
                var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                
                if (prefersDark) {
                    shouldApplyDark = true;
                } else {
                    // Fallback to time-based detection (also fast, <1ms)
                    var hour = new Date().getHours();
                    shouldApplyDark = (hour >= 20 || hour < 6);
                }
            }
            
            // Apply dark mode class immediately (Requirement 6.3)
            // This prevents layout shift by setting theme before first paint (Requirement 9.5)
            if (shouldApplyDark) {
                document.documentElement.classList.add('woow-dark-mode');
                if (document.body) {
                    document.body.classList.add('woow-dark-mode');
                    document.body.classList.add('woow-preload');
                } else {
                    // Body not ready yet, add on DOMContentLoaded
                    // Use passive listener for better performance (Requirement 9.5)
                    document.addEventListener('DOMContentLoaded', function() {
                        document.body.classList.add('woow-dark-mode');
                        document.body.classList.add('woow-preload');
                    }, { passive: true, once: true });
                }
            } else {
                // Ensure preload class is on body for light mode too
                if (document.body) {
                    document.body.classList.add('woow-preload');
                } else {
                    document.addEventListener('DOMContentLoaded', function() {
                        document.body.classList.add('woow-preload');
                    }, { passive: true, once: true });
                }
            }
            
            // Performance: Log initialization time (Requirement 9.2)
            var endTime = performance.now();
            var initTime = endTime - startTime;
            
            // Verify initialization completed within 100ms (Requirement 9.2)
            if (window.woowAdminData && window.woowAdminData.debug) {
                console.log('[DarkMode Early] Initialized in ' + initTime.toFixed(2) + 'ms');
                if (initTime > 100) {
                    console.warn('[DarkMode Early] Initialization took longer than 100ms target');
                }
            }
        })();
        </script>
        <?php
    }

    /**
     * Add inline critical CSS to prevent flash of unstyled content
     * This ensures dark mode is applied before first paint
     * 
     * Implements requirements 6.3, 6.1, 6.2, 6.4, 6.5:
     * - Apply dark styles before initial render (6.3)
     * - Smooth transitions with 300ms duration (6.1, 6.2)
     * - All color properties transition simultaneously (6.5)
     * - Ease timing function (6.4)
     *
     * @return void
     */
    private function add_inline_critical_css(): void {
        // Critical CSS for instant dark mode application (Requirement 6.3)
        $critical_css = "
        /* Critical Dark Mode CSS - Prevents Flash (Requirement 6.3) */
        body.woow-dark-mode {
            background-color: #111827 !important;
            color: #e5e7eb !important;
        }
        body.woow-dark-mode #wpadminbar {
            background-color: #1f2937 !important;
            color: #f9fafb !important;
        }
        body.woow-dark-mode #adminmenuback,
        body.woow-dark-mode #adminmenuwrap,
        body.woow-dark-mode #adminmenu {
            background-color: #1f2937 !important;
        }
        body.woow-dark-mode #wpcontent,
        body.woow-dark-mode #wpbody-content {
            background-color: #111827 !important;
            color: #e5e7eb !important;
        }
        body.woow-dark-mode .postbox,
        body.woow-dark-mode .card {
            background-color: #1f2937 !important;
            border-color: #374151 !important;
        }
        
        /* Smooth Transitions (Requirements 6.1, 6.2, 6.4, 6.5) */
        body:not(.woow-preload),
        body:not(.woow-preload) *,
        body:not(.woow-preload) #wpadminbar,
        body:not(.woow-preload) #wpadminbar *,
        body:not(.woow-preload) #adminmenu,
        body:not(.woow-preload) #adminmenu *,
        body:not(.woow-preload) #wpcontent,
        body:not(.woow-preload) #wpcontent * {
            transition-property: background-color, color, border-color, box-shadow, opacity, background;
            transition-duration: 300ms;
            transition-timing-function: ease;
        }
        
        /* Prevent transitions on initial load (Requirement 6.3) */
        body.woow-preload,
        body.woow-preload * {
            transition: none !important;
        }
        
        /* Accessibility: Respect reduced motion preference */
        @media (prefers-reduced-motion: reduce) {
            body,
            body *,
            #wpadminbar,
            #wpadminbar *,
            #adminmenu,
            #adminmenu *,
            #wpcontent,
            #wpcontent * {
                transition-duration: 0ms !important;
            }
        }
        ";

        // Add inline CSS to the dark mode stylesheet
        wp_add_inline_style( 'woow-dark-mode', $critical_css );
    }

    /**
     * Localize script with configuration
     * Passes PHP data to JavaScript
     *
     * @return void
     */
    private function localize_script(): void {
        $config = array(
            'mode'           => $this->get_dark_mode(),
            'isNightTime'    => $this->is_night_time(),
            'nightStartHour' => self::NIGHT_START_HOUR,
            'nightEndHour'   => self::NIGHT_END_HOUR,
            'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
            'nonce'          => wp_create_nonce( 'woow_dark_mode_nonce' ),
        );

        wp_localize_script( 'woow-dark-mode', 'woowDarkModeConfig', $config );
    }

    /**
     * Add dark mode body class for admin
     *
     * @param string $classes Existing body classes
     * @return string Modified body classes
     */
    public function add_dark_mode_body_class( string $classes ): string {
        if ( $this->should_use_dark_mode() ) {
            $classes .= ' woow-dark-mode';
        }

        return $classes;
    }

    /**
     * Add dark mode body class for frontend and login
     *
     * @param array $classes Existing body classes
     * @return array Modified body classes
     */
    public function add_dark_mode_body_class_frontend( array $classes ): array {
        if ( $this->should_use_dark_mode() ) {
            $classes[] = 'woow-dark-mode';
        }

        return $classes;
    }

    /**
     * AJAX handler for saving dark mode preference
     *
     * @return void
     */
    public function ajax_save_dark_mode(): void {
        // Verify nonce
        check_ajax_referer( 'woow_dark_mode_nonce', 'nonce' );

        // Check user capabilities
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => 'Insufficient permissions' ), 403 );
        }

        // Get and validate mode
        $mode = isset( $_POST['mode'] ) ? sanitize_text_field( wp_unslash( $_POST['mode'] ) ) : '';

        if ( ! in_array( $mode, self::VALID_MODES, true ) ) {
            wp_send_json_error( array( 'message' => 'Invalid mode value' ), 400 );
        }

        // Save mode
        $success = $this->set_dark_mode( $mode );

        if ( $success ) {
            wp_send_json_success( array(
                'message' => 'Dark mode preference saved',
                'mode'    => $mode,
            ) );
        } else {
            wp_send_json_error( array( 'message' => 'Failed to save preference' ), 500 );
        }
    }

    /**
     * Register AJAX handlers
     *
     * @return void
     */
    public function register_ajax_handlers(): void {
        add_action( 'wp_ajax_woow_save_dark_mode', array( $this, 'ajax_save_dark_mode' ) );
    }
}
