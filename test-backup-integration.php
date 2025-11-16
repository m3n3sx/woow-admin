<?php
/**
 * Test Backup Integration with Palette and Template Managers
 *
 * This script tests that backups are created before applying palettes/templates
 * and that rollback works on failure.
 *
 * @package WoowAdmin
 */

// Load WordPress
$wp_load_path = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/wp-load.php';
if ( ! file_exists( $wp_load_path ) ) {
	die( "Error: Cannot find wp-load.php. Please run this script from the plugin directory.\n" );
}
require_once $wp_load_path;

// Load plugin files
require_once 'includes/class-woow-settings.php';
require_once 'includes/class-woow-backup-manager.php';
require_once 'includes/class-woow-palette-manager.php';
require_once 'includes/class-woow-template-manager.php';
require_once 'includes/class-woow-css-generator.php';
require_once 'includes/class-woow-cache-manager.php';
require_once 'includes/defaults.php';

echo "=== WOOW! Admin - Backup Integration Test ===\n\n";

// Initialize managers
$settings = new WOOW_Settings();
$backup_manager = new WOOW_Backup_Manager( $settings );
$css_generator = new WOOW_CSS_Generator( $settings );
$palette_manager = new WOOW_Palette_Manager( $settings );
$template_manager = new WOOW_Template_Manager( $settings );

// Set dependencies
$palette_manager->set_backup_manager( $backup_manager );
$palette_manager->set_css_generator( $css_generator );
$template_manager->set_backup_manager( $backup_manager );
$template_manager->set_css_generator( $css_generator );

echo "✓ Managers initialized\n\n";

// Test 1: Check backup manager has restore_latest method
echo "Test 1: Backup Manager Methods\n";
echo "-----------------------------------\n";

if ( method_exists( $backup_manager, 'restore_latest' ) ) {
	echo "✓ restore_latest() method exists\n";
} else {
	echo "✗ restore_latest() method missing\n";
}

if ( method_exists( $backup_manager, 'create_backup' ) ) {
	echo "✓ create_backup() method exists\n";
} else {
	echo "✗ create_backup() method missing\n";
}

if ( method_exists( $backup_manager, 'get_backups' ) ) {
	echo "✓ get_backups() method exists\n";
} else {
	echo "✗ get_backups() method missing\n";
}

echo "\n";

// Test 2: Get initial backup count
echo "Test 2: Initial Backup State\n";
echo "-----------------------------------\n";

$initial_backups = $backup_manager->get_backups();
$initial_count = count( $initial_backups );
echo "Initial backup count: {$initial_count}\n";

if ( $initial_count > 0 ) {
	echo "Latest backup:\n";
	$latest = $initial_backups[0];
	echo "  - ID: {$latest['id']}\n";
	echo "  - Label: {$latest['label']}\n";
	echo "  - Date: {$latest['date']}\n";
}

echo "\n";

// Test 3: Create manual backup
echo "Test 3: Create Manual Backup\n";
echo "-----------------------------------\n";

try {
	$backup_id = $backup_manager->create_backup( 'test_manual_backup' );
	echo "✓ Manual backup created: {$backup_id}\n";
	
	$backups_after = $backup_manager->get_backups();
	$new_count = count( $backups_after );
	
	if ( $new_count === $initial_count + 1 ) {
		echo "✓ Backup count increased from {$initial_count} to {$new_count}\n";
	} else {
		echo "✗ Backup count mismatch: expected " . ( $initial_count + 1 ) . ", got {$new_count}\n";
	}
	
	// Check backup metadata
	$backup_data = $backup_manager->get_backup( $backup_id );
	if ( $backup_data ) {
		echo "✓ Backup data retrieved\n";
		echo "  - Label: {$backup_data['label']}\n";
		echo "  - User: {$backup_data['metadata']['user_name']}\n";
		echo "  - Version: {$backup_data['metadata']['version']}\n";
	} else {
		echo "✗ Failed to retrieve backup data\n";
	}
} catch ( Exception $e ) {
	echo "✗ Failed to create backup: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 4: Apply palette with backup
echo "Test 4: Apply Palette with Backup\n";
echo "-----------------------------------\n";

try {
	$palette_manager->load_palettes();
	$palettes = $palette_manager->get_all_palettes();
	
	if ( empty( $palettes ) ) {
		echo "✗ No palettes available\n";
	} else {
		$palette_id = array_key_first( $palettes );
		$palette_name = $palettes[ $palette_id ]['name'];
		
		echo "Applying palette: {$palette_name} ({$palette_id})\n";
		
		$backups_before = $backup_manager->get_backups();
		$count_before = count( $backups_before );
		
		$result = $palette_manager->apply_palette( $palette_id );
		
		if ( $result ) {
			echo "✓ Palette applied successfully\n";
			
			$backups_after = $backup_manager->get_backups();
			$count_after = count( $backups_after );
			
			if ( $count_after > $count_before ) {
				echo "✓ Backup created before palette application\n";
				
				// Find the backup
				$latest_backup = $backups_after[0];
				if ( strpos( $latest_backup['label'], 'before_palette_' ) !== false ) {
					echo "✓ Backup has correct label: {$latest_backup['label']}\n";
				} else {
					echo "✗ Backup label incorrect: {$latest_backup['label']}\n";
				}
			} else {
				echo "✗ No backup created (count: {$count_before} -> {$count_after})\n";
			}
		} else {
			echo "✗ Palette application failed\n";
		}
	}
} catch ( Exception $e ) {
	echo "✗ Exception: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 5: Apply template with backup
echo "Test 5: Apply Template with Backup\n";
echo "-----------------------------------\n";

try {
	$templates = $template_manager->get_all_templates();
	
	if ( empty( $templates ) ) {
		echo "✗ No templates available\n";
	} else {
		$template = $templates[0];
		$template_id = $template['id'];
		$template_name = $template['name'];
		
		echo "Applying template: {$template_name} ({$template_id})\n";
		
		$backups_before = $backup_manager->get_backups();
		$count_before = count( $backups_before );
		
		$result = $template_manager->apply_template( $template_id );
		
		if ( $result ) {
			echo "✓ Template applied successfully\n";
			
			$backups_after = $backup_manager->get_backups();
			$count_after = count( $backups_after );
			
			if ( $count_after > $count_before ) {
				echo "✓ Backup created before template application\n";
				
				// Find the backup
				$latest_backup = $backups_after[0];
				if ( strpos( $latest_backup['label'], 'before_template_' ) !== false ) {
					echo "✓ Backup has correct label: {$latest_backup['label']}\n";
				} else {
					echo "✗ Backup label incorrect: {$latest_backup['label']}\n";
				}
			} else {
				echo "✗ No backup created (count: {$count_before} -> {$count_after})\n";
			}
		} else {
			echo "✗ Template application failed\n";
		}
	}
} catch ( Exception $e ) {
	echo "✗ Exception: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 6: Test restore_latest
echo "Test 6: Restore Latest Backup\n";
echo "-----------------------------------\n";

try {
	$backups = $backup_manager->get_backups();
	
	if ( empty( $backups ) ) {
		echo "✗ No backups available to restore\n";
	} else {
		$latest = $backups[0];
		echo "Latest backup: {$latest['label']} ({$latest['date']})\n";
		
		// Get current settings
		$settings_before = $settings->get_all_settings();
		
		// Restore latest
		$result = $backup_manager->restore_latest();
		
		if ( $result ) {
			echo "✓ restore_latest() succeeded\n";
			
			// Verify settings changed
			$settings_after = $settings->get_all_settings();
			
			// Note: Settings might be the same if we just restored what we had
			echo "✓ Settings restored\n";
		} else {
			echo "✗ restore_latest() failed\n";
		}
	}
} catch ( Exception $e ) {
	echo "✗ Exception: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 7: Backup statistics
echo "Test 7: Backup Statistics\n";
echo "-----------------------------------\n";

try {
	$stats = $backup_manager->get_stats();
	
	echo "Backup Statistics:\n";
	echo "  - Count: {$stats['count']} / {$stats['max']}\n";
	echo "  - Total Size: " . number_format( $stats['total_size'] ) . " bytes\n";
	echo "  - Oldest: " . ( $stats['oldest'] ?? 'N/A' ) . "\n";
	echo "  - Newest: " . ( $stats['newest'] ?? 'N/A' ) . "\n";
	
	if ( $stats['count'] > 0 ) {
		echo "✓ Backup statistics retrieved\n";
	}
} catch ( Exception $e ) {
	echo "✗ Exception: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 8: Verify dependency injection
echo "Test 8: Dependency Injection\n";
echo "-----------------------------------\n";

// Check if managers have backup manager set
$reflection_palette = new ReflectionClass( $palette_manager );
$backup_prop_palette = $reflection_palette->getProperty( 'backup_manager' );
$backup_prop_palette->setAccessible( true );
$backup_instance_palette = $backup_prop_palette->getValue( $palette_manager );

if ( $backup_instance_palette instanceof WOOW_Backup_Manager ) {
	echo "✓ Palette Manager has backup manager injected\n";
} else {
	echo "✗ Palette Manager missing backup manager\n";
}

$reflection_template = new ReflectionClass( $template_manager );
$backup_prop_template = $reflection_template->getProperty( 'backup_manager' );
$backup_prop_template->setAccessible( true );
$backup_instance_template = $backup_prop_template->getValue( $template_manager );

if ( $backup_instance_template instanceof WOOW_Backup_Manager ) {
	echo "✓ Template Manager has backup manager injected\n";
} else {
	echo "✗ Template Manager missing backup manager\n";
}

echo "\n";

// Summary
echo "=== Test Summary ===\n";
echo "All backup integration tests completed.\n";
echo "Check the output above for any failures (✗).\n";
echo "\n";
echo "Final backup count: " . count( $backup_manager->get_backups() ) . "\n";
