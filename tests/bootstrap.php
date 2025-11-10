<?php
/**
 * PHPUnit Bootstrap File
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

// Define test constants
define( 'WOOW_PLUGIN_DIR', dirname( __DIR__ ) . '/' );
define( 'WOOW_PLUGIN_URL', 'http://example.com/wp-content/plugins/woow-admin/' );
define( 'WOOW_ASSETS_URL', WOOW_PLUGIN_URL . 'assets/dist/' );
define( 'WOOW_VERSION', '1.0.0' );
define( 'WOOW_MIN_WP_VERSION', '6.0' );
define( 'WOOW_MIN_PHP_VERSION', '8.0' );

// Define WordPress constants for testing
if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', '/tmp/wordpress/' );
}

// Load Composer autoloader
require_once WOOW_PLUGIN_DIR . 'vendor/autoload.php';

// Mock WordPress functions for unit tests
if ( ! function_exists( 'get_option' ) ) {
    function get_option( $option, $default = false ) {
        global $_test_options;
        return $_test_options[ $option ] ?? $default;
    }
}

if ( ! function_exists( 'update_option' ) ) {
    function update_option( $option, $value ) {
        global $_test_options;
        $_test_options[ $option ] = $value;
        return true;
    }
}

if ( ! function_exists( 'delete_option' ) ) {
    function delete_option( $option ) {
        global $_test_options;
        unset( $_test_options[ $option ] );
        return true;
    }
}

if ( ! function_exists( 'set_transient' ) ) {
    function set_transient( $transient, $value, $expiration = 0 ) {
        global $_test_transients;
        $_test_transients[ $transient ] = [
            'value' => $value,
            'expiration' => $expiration > 0 ? time() + $expiration : 0
        ];
        return true;
    }
}

if ( ! function_exists( 'get_transient' ) ) {
    function get_transient( $transient ) {
        global $_test_transients;
        if ( ! isset( $_test_transients[ $transient ] ) ) {
            return false;
        }
        
        $data = $_test_transients[ $transient ];
        if ( $data['expiration'] > 0 && $data['expiration'] < time() ) {
            unset( $_test_transients[ $transient ] );
            return false;
        }
        
        return $data['value'];
    }
}

if ( ! function_exists( 'delete_transient' ) ) {
    function delete_transient( $transient ) {
        global $_test_transients;
        unset( $_test_transients[ $transient ] );
        return true;
    }
}

if ( ! function_exists( 'esc_html' ) ) {
    function esc_html( $text ) {
        return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' );
    }
}

if ( ! function_exists( 'esc_attr' ) ) {
    function esc_attr( $text ) {
        return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' );
    }
}

if ( ! function_exists( 'esc_url' ) ) {
    function esc_url( $url ) {
        return filter_var( $url, FILTER_SANITIZE_URL );
    }
}

if ( ! function_exists( '__' ) ) {
    function __( $text, $domain = 'default' ) {
        return $text;
    }
}

if ( ! function_exists( 'esc_html__' ) ) {
    function esc_html__( $text, $domain = 'default' ) {
        return esc_html( __( $text, $domain ) );
    }
}

// Initialize global test storage
global $_test_options, $_test_transients;
$_test_options = [];
$_test_transients = [];

echo "WOOW Admin Test Bootstrap Loaded\n";
