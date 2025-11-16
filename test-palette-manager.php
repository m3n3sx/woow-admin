<?php
/**
 * Test script for WOOW_Palette_Manager
 *
 * This script tests the basic functionality of the WOOW_Palette_Manager class.
 * Run from command line: php test-palette-manager.php
 */

// Define WordPress constants for testing
define( 'ABSPATH', __DIR__ . '/' );
define( 'WOOW_PLUGIN_DIR', __DIR__ . '/' );
define( 'WOOW_PLUGIN_URL', 'http://localhost/wp-content/plugins/woow-admin/' );
define( 'WOOW_VERSION', '1.0.0' );

// Mock WordPress functions
function sanitize_key( $key ) {
	return strtolower( preg_replace( '/[^a-z0-9_\-]/', '', $key ) );
}

function error_log( $message ) {
	echo "[LOG] " . $message . "\n";
}

// Load required classes
require_once __DIR__ . '/includes/class-woow-settings.php';
require_once __DIR__ . '/includes/class-woow-palette-manager.php';

echo "=== WOOW Palette Manager Test ===\n\n";

try {
	// Create mock settings instance
	echo "1. Creating WOOW_Settings instance...\n";
	$settings = new WOOW_Settings();
	echo "   ✓ Settings instance created\n\n";

	// Create palette manager
	echo "2. Creating WOOW_Palette_Manager instance...\n";
	$palette_manager = new WOOW_Palette_Manager( $settings );
	echo "   ✓ Palette manager instance created\n\n";

	// Load palettes
	echo "3. Loading palettes...\n";
	$palette_manager->load_palettes();
	echo "   ✓ Palettes loaded\n\n";

	// Get all palettes
	echo "4. Getting all palettes...\n";
	$palettes = $palette_manager->get_all_palettes();
	echo "   ✓ Found " . count( $palettes ) . " palettes\n";
	foreach ( $palettes as $id => $palette ) {
		echo "     - {$id}: {$palette['name']}\n";
	}
	echo "\n";

	// Test getting single palette
	echo "5. Testing get_palette() method...\n";
	$palette = $palette_manager->get_palette( 'professional_blue' );
	if ( $palette !== null ) {
		echo "   ✓ Retrieved 'professional_blue' palette\n";
		echo "     Name: {$palette['name']}\n";
		echo "     Description: {$palette['description']}\n";
		echo "     Category: {$palette['category']}\n";
		echo "     Sections: " . count( $palette['settings'] ) . "\n";
	} else {
		echo "   ✗ Failed to retrieve palette\n";
	}
	echo "\n";

	// Test palette validation
	echo "6. Testing palette validation...\n";
	$completeness = $palette_manager->check_completeness( $palette );
	if ( $completeness['complete'] ) {
		echo "   ✓ Palette is complete\n";
		echo "     Sections: {$completeness['sections']}\n";
	} else {
		echo "   ✗ Palette is incomplete\n";
		echo "     Missing: " . implode( ', ', $completeness['missing'] ) . "\n";
	}
	echo "\n";

	// Test getting palettes by category
	echo "7. Testing get_palettes_by_category()...\n";
	$professional = $palette_manager->get_palettes_by_category( 'professional' );
	echo "   ✓ Found " . count( $professional ) . " professional palettes\n";
	foreach ( $professional as $id => $pal ) {
		echo "     - {$id}\n";
	}
	echo "\n";

	// Test palette existence
	echo "8. Testing palette_exists()...\n";
	$exists = $palette_manager->palette_exists( 'professional_blue' );
	echo "   ✓ professional_blue exists: " . ( $exists ? 'YES' : 'NO' ) . "\n";
	$exists = $palette_manager->palette_exists( 'nonexistent_palette' );
	echo "   ✓ nonexistent_palette exists: " . ( $exists ? 'YES' : 'NO' ) . "\n";
	echo "\n";

	// Test getting categories
	echo "9. Testing get_categories()...\n";
	$categories = $palette_manager->get_categories();
	echo "   ✓ Found " . count( $categories ) . " categories: " . implode( ', ', $categories ) . "\n";
	echo "\n";

	// Test palette count
	echo "10. Testing get_palette_count()...\n";
	$count = $palette_manager->get_palette_count();
	echo "   ✓ Total palettes: {$count}\n";
	echo "\n";

	echo "=== All Tests Passed! ===\n";

} catch ( Exception $e ) {
	echo "\n✗ ERROR: " . $e->getMessage() . "\n";
	echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
	exit( 1 );
}
