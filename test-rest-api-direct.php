<?php
/**
 * Direct REST API Test
 * 
 * Tests REST API endpoints directly to see actual errors
 */

// Load WordPress
require_once dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/wp-load.php';

// Enable error display
error_reporting( E_ALL );
ini_set( 'display_errors', '1' );

echo "=================================================\n";
echo "WOOW! Admin - REST API Direct Test\n";
echo "=================================================\n\n";

// Check if classes exist
if ( ! class_exists( 'WOOW_Settings' ) ) {
	die( "ERROR: WOOW_Settings class not found. Is plugin active?\n" );
}

if ( ! class_exists( 'WOOW_Palette_Manager' ) ) {
	die( "ERROR: WOOW_Palette_Manager class not found.\n" );
}

if ( ! class_exists( 'WOOW_Template_Manager' ) ) {
	die( "ERROR: WOOW_Template_Manager class not found.\n" );
}

echo "✓ All required classes loaded\n\n";

// Test 1: Get all palettes
echo "Test 1: Get All Palettes\n";
echo "---\n";

try {
	$settings = new WOOW_Settings();
	$palette_manager = new WOOW_Palette_Manager( $settings );
	
	$palettes = $palette_manager->get_all_palettes();
	
	echo "✓ Found " . count( $palettes ) . " palettes\n";
	
	// Show first 3 palette IDs
	$count = 0;
	foreach ( $palettes as $id => $palette ) {
		echo "  - {$id}: {$palette['name']}\n";
		$count++;
		if ( $count >= 3 ) {
			echo "  ... and " . ( count( $palettes ) - 3 ) . " more\n";
			break;
		}
	}
	
} catch ( Exception $e ) {
	echo "✗ ERROR: " . $e->getMessage() . "\n";
	echo "  File: " . $e->getFile() . "\n";
	echo "  Line: " . $e->getLine() . "\n";
}

echo "\n";

// Test 2: Apply palette
echo "Test 2: Apply Palette (professional_blue)\n";
echo "---\n";

try {
	$settings = new WOOW_Settings();
	$palette_manager = new WOOW_Palette_Manager( $settings );
	
	// Set dependencies
	$backup_manager = new WOOW_Backup_Manager( $settings );
	$css_generator = new WOOW_CSS_Generator( $settings );
	
	$palette_manager->set_backup_manager( $backup_manager );
	$palette_manager->set_css_generator( $css_generator );
	
	echo "Applying palette...\n";
	$result = $palette_manager->apply_palette( 'professional_blue' );
	
	if ( $result['success'] ) {
		echo "✓ SUCCESS: " . $result['message'] . "\n";
		echo "  Palette ID: " . $result['palette_id'] . "\n";
		if ( isset( $result['backup_id'] ) ) {
			echo "  Backup ID: " . $result['backup_id'] . "\n";
		}
	} else {
		echo "✗ FAILED: " . $result['message'] . "\n";
		echo "  Error Code: " . ( $result['error_code'] ?? 'UNKNOWN' ) . "\n";
		if ( isset( $result['context'] ) ) {
			echo "  Context: " . print_r( $result['context'], true ) . "\n";
		}
	}
	
} catch ( Exception $e ) {
	echo "✗ EXCEPTION: " . $e->getMessage() . "\n";
	echo "  File: " . $e->getFile() . "\n";
	echo "  Line: " . $e->getLine() . "\n";
	echo "  Trace:\n";
	echo $e->getTraceAsString() . "\n";
}

echo "\n";

// Test 3: Get all templates
echo "Test 3: Get All Templates\n";
echo "---\n";

try {
	$settings = new WOOW_Settings();
	$template_manager = new WOOW_Template_Manager( $settings );
	
	$templates = $template_manager->get_all_templates();
	
	echo "✓ Found " . count( $templates ) . " templates\n";
	
	// Show first 3 template IDs
	$count = 0;
	foreach ( $templates as $id => $template ) {
		echo "  - {$id}: {$template['name']}\n";
		$count++;
		if ( $count >= 3 ) {
			echo "  ... and " . ( count( $templates ) - 3 ) . " more\n";
			break;
		}
	}
	
} catch ( Exception $e ) {
	echo "✗ ERROR: " . $e->getMessage() . "\n";
	echo "  File: " . $e->getFile() . "\n";
	echo "  Line: " . $e->getLine() . "\n";
}

echo "\n";

// Test 4: Apply template
echo "Test 4: Apply Template (modern_minimal)\n";
echo "---\n";

try {
	$settings = new WOOW_Settings();
	$template_manager = new WOOW_Template_Manager( $settings );
	
	// Set dependencies
	$backup_manager = new WOOW_Backup_Manager( $settings );
	$css_generator = new WOOW_CSS_Generator( $settings );
	
	$template_manager->set_backup_manager( $backup_manager );
	$template_manager->set_css_generator( $css_generator );
	
	echo "Applying template...\n";
	$result = $template_manager->apply_template( 'modern_minimal' );
	
	if ( $result['success'] ) {
		echo "✓ SUCCESS: " . $result['message'] . "\n";
		echo "  Template ID: " . $result['template_id'] . "\n";
		if ( isset( $result['backup_id'] ) ) {
			echo "  Backup ID: " . $result['backup_id'] . "\n";
		}
	} else {
		echo "✗ FAILED: " . $result['message'] . "\n";
		echo "  Error Code: " . ( $result['error_code'] ?? 'UNKNOWN' ) . "\n";
		if ( isset( $result['context'] ) ) {
			echo "  Context: " . print_r( $result['context'], true ) . "\n";
		}
	}
	
} catch ( Exception $e ) {
	echo "✗ EXCEPTION: " . $e->getMessage() . "\n";
	echo "  File: " . $e->getFile() . "\n";
	echo "  Line: " . $e->getLine() . "\n";
	echo "  Trace:\n";
	echo $e->getTraceAsString() . "\n";
}

echo "\n";

// Test 5: Check current settings
echo "Test 5: Check Current Settings\n";
echo "---\n";

try {
	$settings = new WOOW_Settings();
	$all_settings = $settings->get_all_settings();
	
	echo "✓ Settings loaded\n";
	echo "  Sections: " . count( $all_settings ) . "\n";
	
	// Check backgrounds section
	if ( isset( $all_settings['backgrounds'] ) ) {
		$bg = $all_settings['backgrounds'];
		echo "  Backgrounds:\n";
		echo "    - body_bg: " . ( $bg['body_bg'] ?? 'not set' ) . "\n";
		echo "    - content_bg: " . ( $bg['content_bg'] ?? 'not set' ) . "\n";
		echo "    - sidebar_bg: " . ( $bg['sidebar_bg'] ?? 'not set' ) . "\n";
	}
	
} catch ( Exception $e ) {
	echo "✗ ERROR: " . $e->getMessage() . "\n";
}

echo "\n";

echo "=================================================\n";
echo "Test Complete\n";
echo "=================================================\n";
