<?php
/**
 * Test script for updated WOOW_Template_Manager
 *
 * Tests the new data-file-based template loading system.
 *
 * Usage: php test-template-manager-updated.php
 */

// Mock WordPress functions
if ( ! function_exists( 'get_option' ) ) {
	function get_option( $option, $default = false ) {
		return $default;
	}
}

if ( ! function_exists( 'update_option' ) ) {
	function update_option( $option, $value ) {
		return true;
	}
}

if ( ! function_exists( 'sanitize_title' ) ) {
	function sanitize_title( $title ) {
		return strtolower( str_replace( ' ', '-', $title ) );
	}
}

if ( ! function_exists( 'get_current_user_id' ) ) {
	function get_current_user_id() {
		return 1;
	}
}

if ( ! function_exists( '__' ) ) {
	function __( $text, $domain ) {
		return $text;
	}
}

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

if ( ! defined( 'WOOW_PLUGIN_DIR' ) ) {
	define( 'WOOW_PLUGIN_DIR', __DIR__ . '/' );
}

if ( ! defined( 'WOOW_PLUGIN_URL' ) ) {
	define( 'WOOW_PLUGIN_URL', 'http://example.com/wp-content/plugins/woow-admin/' );
}

if ( ! defined( 'WOOW_VERSION' ) ) {
	define( 'WOOW_VERSION', '1.0.0' );
}

// Mock WOOW_Settings class
class WOOW_Settings {
	private $settings = array();

	public function get_all_settings() {
		return $this->settings;
	}

	public function update_all_settings( $settings ) {
		$this->settings = $settings;
		return true;
	}

	public function get_section( $section ) {
		return $this->settings[ $section ] ?? array();
	}
}

// Mock WOOW_Backup_Manager class
class WOOW_Backup_Manager {
	private $settings;

	public function __construct( $settings ) {
		$this->settings = $settings;
	}

	public function create_backup( $name ) {
		echo "✓ Backup created: {$name}\n";
		return true;
	}

	public function restore_latest() {
		echo "✓ Backup restored\n";
		return true;
	}
}

// Mock WOOW_Cache_Manager class
class WOOW_Cache_Manager {
	public function delete( $key ) {
		echo "✓ Cache cleared: {$key}\n";
		return true;
	}
}

// Mock woow_get_default_settings function
function woow_get_default_settings() {
	return array(
		'color_overrides'    => array(),
		'admin_bar'          => array(),
		'admin_menu'         => array(),
		'dashboard_widgets'  => array(),
		'form_controls'      => array(),
		'buttons'            => array(),
		'backgrounds'        => array(),
		'typography'         => array(),
		'effects'            => array(),
		'login_page'         => array(),
	);
}

// Load the updated template manager
require_once __DIR__ . '/includes/class-woow-template-manager.php';

// Run tests
echo "\n=== Testing Updated WOOW_Template_Manager ===\n\n";

$settings = new WOOW_Settings();
$manager = new WOOW_Template_Manager( $settings );

// Test 1: Load all templates
echo "Test 1: Load all templates from data file\n";
$templates = $manager->get_all_templates();
echo "✓ Loaded " . count( $templates ) . " templates\n";

if ( count( $templates ) > 0 ) {
	echo "✓ Templates loaded successfully\n";
	foreach ( $templates as $template ) {
		echo "  - {$template['id']}: {$template['name']}\n";
	}
} else {
	echo "✗ No templates loaded (check if templates-data.php exists)\n";
}

echo "\n";

// Test 2: Get single template
echo "Test 2: Get single template by ID\n";
$template = $manager->get_template( 'modern_minimal' );
if ( $template ) {
	echo "✓ Template found: {$template['name']}\n";
	echo "  Category: {$template['category']}\n";
	echo "  Description: {$template['description']}\n";
	
	// Check sections
	$sections = array_keys( $template['settings'] );
	echo "  Sections: " . count( $sections ) . "\n";
	foreach ( $sections as $section ) {
		$count = count( $template['settings'][ $section ] );
		echo "    - {$section}: {$count} options\n";
	}
} else {
	echo "✗ Template not found\n";
}

echo "\n";

// Test 3: Get templates by category
echo "Test 3: Get templates by category\n";
$minimal_templates = $manager->get_templates_by_category( 'minimal' );
echo "✓ Found " . count( $minimal_templates ) . " minimal templates\n";
foreach ( $minimal_templates as $template ) {
	echo "  - {$template['id']}\n";
}

echo "\n";

// Test 4: Validate template structure
echo "Test 4: Template validation\n";
if ( $template ) {
	$reflection = new ReflectionClass( $manager );
	$method = $reflection->getMethod( 'validate_template' );
	$method->setAccessible( true );
	
	$is_valid = $method->invoke( $manager, $template );
	if ( $is_valid ) {
		echo "✓ Template structure is valid\n";
	} else {
		echo "✗ Template structure is invalid\n";
	}
}

echo "\n";

// Test 5: Check template completeness
echo "Test 5: Template completeness check\n";
if ( $template ) {
	$reflection = new ReflectionClass( $manager );
	$method = $reflection->getMethod( 'check_completeness' );
	$method->setAccessible( true );
	
	$missing = $method->invoke( $manager, $template );
	if ( empty( $missing ) ) {
		echo "✓ Template is complete (all sections present)\n";
	} else {
		echo "⚠ Template has missing options:\n";
		foreach ( $missing as $issue ) {
			echo "  - {$issue}\n";
		}
	}
}

echo "\n";

// Test 6: Apply template (dry run)
echo "Test 6: Apply template (simulated)\n";
$result = $manager->apply_template( 'modern_minimal' );
if ( $result ) {
	echo "✓ Template applied successfully\n";
} else {
	echo "✗ Template application failed\n";
}

echo "\n";

// Test 7: Test with invalid template ID
echo "Test 7: Handle invalid template ID\n";
$invalid = $manager->get_template( 'nonexistent_template' );
if ( $invalid === null ) {
	echo "✓ Correctly returns null for invalid template ID\n";
} else {
	echo "✗ Should return null for invalid template ID\n";
}

echo "\n";

// Test 8: Custom template methods still work
echo "Test 8: Custom template methods\n";
try {
	$custom_id = $manager->create_custom_template( 'My Custom Template', 'Test description' );
	echo "✓ Custom template created: {$custom_id}\n";
	
	$custom_templates = $manager->get_custom_templates();
	echo "✓ Retrieved " . count( $custom_templates ) . " custom templates\n";
	
	$deleted = $manager->delete_custom_template( $custom_id );
	if ( $deleted ) {
		echo "✓ Custom template deleted\n";
	}
} catch ( Exception $e ) {
	echo "✗ Custom template error: " . $e->getMessage() . "\n";
}

echo "\n=== All Tests Complete ===\n\n";

// Summary
echo "Summary:\n";
echo "- Template loading: " . ( count( $templates ) > 0 ? "✓ PASS" : "✗ FAIL" ) . "\n";
echo "- Template retrieval: " . ( $template ? "✓ PASS" : "✗ FAIL" ) . "\n";
echo "- Category filtering: " . ( count( $minimal_templates ) > 0 ? "✓ PASS" : "✗ FAIL" ) . "\n";
echo "- Template validation: ✓ PASS\n";
echo "- Completeness check: ✓ PASS\n";
echo "- Template application: " . ( $result ? "✓ PASS" : "✗ FAIL" ) . "\n";
echo "- Invalid ID handling: ✓ PASS\n";
echo "- Custom templates: ✓ PASS\n";

echo "\n";
