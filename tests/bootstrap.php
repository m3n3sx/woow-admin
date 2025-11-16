<?php
/**
 * PHPUnit Bootstrap File
 *
 * Sets up the testing environment for WOOW! Admin plugin tests.
 *
 * @package WoowAdmin
 * @subpackage Tests
 */

// Define test constants
define( 'WOOW_TESTS_DIR', __DIR__ );
define( 'WOOW_PLUGIN_DIR', dirname( __DIR__ ) . '/' );
define( 'WOOW_PLUGIN_URL', 'http://example.com/wp-content/plugins/woow-admin/' );
define( 'WOOW_VERSION', '1.0.0' );
define( 'ABSPATH', '/tmp/wordpress/' );

// Load Composer autoloader
require_once WOOW_PLUGIN_DIR . 'vendor/autoload.php';

// Mock WordPress functions that are used in the plugin
if ( ! function_exists( 'sanitize_key' ) ) {
	function sanitize_key( $key ) {
		$key = strtolower( $key );
		$key = preg_replace( '/[^a-z0-9_\-]/', '', $key );
		return $key;
	}
}

if ( ! function_exists( 'sanitize_text_field' ) ) {
	function sanitize_text_field( $str ) {
		return strip_tags( $str );
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

if ( ! function_exists( 'wp_json_encode' ) ) {
	function wp_json_encode( $data, $options = 0, $depth = 512 ) {
		return json_encode( $data, $options, $depth );
	}
}

if ( ! function_exists( 'error_log' ) ) {
	function error_log( $message ) {
		// Suppress error logs during tests
		return true;
	}
}

if ( ! function_exists( 'get_option' ) ) {
	function get_option( $option, $default = false ) {
		global $woow_test_options;
		return $woow_test_options[ $option ] ?? $default;
	}
}

if ( ! function_exists( 'update_option' ) ) {
	function update_option( $option, $value ) {
		global $woow_test_options;
		$woow_test_options[ $option ] = $value;
		return true;
	}
}

if ( ! function_exists( 'delete_option' ) ) {
	function delete_option( $option ) {
		global $woow_test_options;
		unset( $woow_test_options[ $option ] );
		return true;
	}
}

if ( ! function_exists( 'sanitize_title' ) ) {
	function sanitize_title( $title ) {
		$title = strtolower( $title );
		$title = preg_replace( '/[^a-z0-9_\-]/', '_', $title );
		$title = preg_replace( '/_+/', '_', $title );
		return trim( $title, '_' );
	}
}

if ( ! function_exists( 'get_current_user_id' ) ) {
	function get_current_user_id() {
		return 1;
	}
}

if ( ! function_exists( 'wp_get_current_user' ) ) {
	function wp_get_current_user() {
		return (object) array(
			'ID'           => 1,
			'user_login'   => 'admin',
			'display_name' => 'Admin User',
		);
	}
}

if ( ! function_exists( 'get_site_url' ) ) {
	function get_site_url() {
		return 'http://example.com';
	}
}

if ( ! function_exists( 'date_i18n' ) ) {
	function date_i18n( $format, $timestamp = null ) {
		if ( $timestamp === null ) {
			$timestamp = time();
		}
		return date( $format, $timestamp );
	}
}

if ( ! function_exists( 'get_transient' ) ) {
	function get_transient( $transient ) {
		global $woow_test_transients;
		return $woow_test_transients[ $transient ] ?? false;
	}
}

if ( ! function_exists( 'set_transient' ) ) {
	function set_transient( $transient, $value, $expiration = 0 ) {
		global $woow_test_transients;
		$woow_test_transients[ $transient ] = $value;
		return true;
	}
}

if ( ! function_exists( 'delete_transient' ) ) {
	function delete_transient( $transient ) {
		global $woow_test_transients;
		unset( $woow_test_transients[ $transient ] );
		return true;
	}
}

// Initialize global test options and transients arrays
global $woow_test_options, $woow_test_transients;
$woow_test_options = array();
$woow_test_transients = array();

// Load plugin files
require_once WOOW_PLUGIN_DIR . 'includes/defaults.php';
require_once WOOW_PLUGIN_DIR . 'includes/class-woow-settings.php';
require_once WOOW_PLUGIN_DIR . 'includes/class-woow-backup-manager.php';
require_once WOOW_PLUGIN_DIR . 'includes/class-woow-css-generator.php';
require_once WOOW_PLUGIN_DIR . 'includes/class-woow-cache-manager.php';
require_once WOOW_PLUGIN_DIR . 'includes/class-woow-palette-manager.php';
require_once WOOW_PLUGIN_DIR . 'includes/class-woow-template-manager.php';

echo "Bootstrap loaded successfully\n";
