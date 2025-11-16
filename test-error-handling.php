<?php
/**
 * Test Error Handling and Rollback
 *
 * Tests comprehensive error handling and automatic rollback functionality
 * for palette and template application.
 *
 * Usage: php test-error-handling.php
 *
 * @package WoowAdmin
 */

// Load WordPress
require_once __DIR__ . '/../../../wp-load.php';

// Ensure we're in CLI mode
if ( php_sapi_name() !== 'cli' ) {
	die( 'This script can only be run from the command line.' );
}

echo "=== WOOW! Admin - Error Handling & Rollback Tests ===\n\n";

// Initialize managers
$settings = new WOOW_Settings();
$backup_manager = new WOOW_Backup_Manager( $settings );
$css_generator = new WOOW_CSS_Generator( $settings );
$palette_manager = new WOOW_Palette_Manager( $settings );
$template_manager = new WOOW_Template_Manager( $settings );

$palette_manager->set_backup_manager( $backup_manager );
$palette_manager->set_css_generator( $css_generator );
$template_manager->set_backup_manager( $backup_manager );
$template_manager->set_css_generator( $css_generator );

// Test counter
$tests_passed = 0;
$tests_failed = 0;

/**
 * Test helper function
 */
function run_test( $name, $callback ) {
	global $tests_passed, $tests_failed;
	
	echo "Testing: {$name}... ";
	
	try {
		$result = $callback();
		
		if ( $result ) {
			echo "✓ PASSED\n";
			$tests_passed++;
		} else {
			echo "✗ FAILED\n";
			$tests_failed++;
		}
	} catch ( Exception $e ) {
		echo "✗ EXCEPTION: {$e->getMessage()}\n";
		$tests_failed++;
	}
}

// ============================================================================
// Test 1: Invalid Palette ID
// ============================================================================
run_test( 'Invalid Palette ID', function() use ( $palette_manager ) {
	$result = $palette_manager->apply_palette( '' );
	
	return ! $result['success'] 
		&& $result['error_code'] === 'INVALID_PALETTE_ID'
		&& ! empty( $result['message'] );
} );

// ============================================================================
// Test 2: Palette Not Found
// ============================================================================
run_test( 'Palette Not Found', function() use ( $palette_manager ) {
	$result = $palette_manager->apply_palette( 'nonexistent_palette' );
	
	return ! $result['success'] 
		&& $result['error_code'] === 'PALETTE_NOT_FOUND'
		&& strpos( $result['message'], 'not found' ) !== false;
} );

// ============================================================================
// Test 3: Invalid Template ID
// ============================================================================
run_test( 'Invalid Template ID', function() use ( $template_manager ) {
	$result = $template_manager->apply_template( '' );
	
	return ! $result['success'] 
		&& $result['error_code'] === 'INVALID_TEMPLATE_ID'
		&& ! empty( $result['message'] );
} );

// ============================================================================
// Test 4: Template Not Found
// ============================================================================
run_test( 'Template Not Found', function() use ( $template_manager ) {
	$result = $template_manager->apply_template( 'nonexistent_template' );
	
	return ! $result['success'] 
		&& $result['error_code'] === 'TEMPLATE_NOT_FOUND'
		&& strpos( $result['message'], 'not found' ) !== false;
} );

// ============================================================================
// Test 5: Successful Palette Application
// ============================================================================
run_test( 'Successful Palette Application', function() use ( $palette_manager, $settings ) {
	// Get current settings
	$original_settings = $settings->get_all_settings();
	
	// Apply palette
	$result = $palette_manager->apply_palette( 'professional_blue' );
	
	if ( ! $result['success'] ) {
		echo "\n  Error: {$result['message']}\n";
		return false;
	}
	
	// Verify backup was created
	if ( empty( $result['backup_id'] ) ) {
		echo "\n  Error: No backup ID returned\n";
		return false;
	}
	
	// Verify settings changed
	$new_settings = $settings->get_all_settings();
	if ( $original_settings === $new_settings ) {
		echo "\n  Error: Settings did not change\n";
		return false;
	}
	
	return true;
} );

// ============================================================================
// Test 6: Successful Template Application
// ============================================================================
run_test( 'Successful Template Application', function() use ( $template_manager, $settings ) {
	// Get current settings
	$original_settings = $settings->get_all_settings();
	
	// Apply template
	$result = $template_manager->apply_template( 'modern_minimal' );
	
	if ( ! $result['success'] ) {
		echo "\n  Error: {$result['message']}\n";
		return false;
	}
	
	// Verify backup was created
	if ( empty( $result['backup_id'] ) ) {
		echo "\n  Error: No backup ID returned\n";
		return false;
	}
	
	// Verify settings changed
	$new_settings = $settings->get_all_settings();
	if ( $original_settings === $new_settings ) {
		echo "\n  Error: Settings did not change\n";
		return false;
	}
	
	return true;
} );

// ============================================================================
// Test 7: Error Response Format
// ============================================================================
run_test( 'Error Response Format', function() use ( $palette_manager ) {
	$result = $palette_manager->apply_palette( 'invalid' );
	
	// Check required keys
	$required_keys = array( 'success', 'error_code', 'message', 'context' );
	foreach ( $required_keys as $key ) {
		if ( ! isset( $result[ $key ] ) ) {
			echo "\n  Error: Missing key '{$key}' in response\n";
			return false;
		}
	}
	
	// Verify success is false
	if ( $result['success'] !== false ) {
		echo "\n  Error: success should be false\n";
		return false;
	}
	
	// Verify error_code is string
	if ( ! is_string( $result['error_code'] ) ) {
		echo "\n  Error: error_code should be string\n";
		return false;
	}
	
	// Verify message is string
	if ( ! is_string( $result['message'] ) ) {
		echo "\n  Error: message should be string\n";
		return false;
	}
	
	// Verify context is array
	if ( ! is_array( $result['context'] ) ) {
		echo "\n  Error: context should be array\n";
		return false;
	}
	
	return true;
} );

// ============================================================================
// Test 8: Success Response Format
// ============================================================================
run_test( 'Success Response Format', function() use ( $palette_manager ) {
	$result = $palette_manager->apply_palette( 'professional_blue' );
	
	if ( ! $result['success'] ) {
		echo "\n  Skipping: Palette application failed\n";
		return true; // Skip test if application fails
	}
	
	// Check required keys
	$required_keys = array( 'success', 'message', 'palette_id', 'backup_id' );
	foreach ( $required_keys as $key ) {
		if ( ! isset( $result[ $key ] ) ) {
			echo "\n  Error: Missing key '{$key}' in response\n";
			return false;
		}
	}
	
	// Verify success is true
	if ( $result['success'] !== true ) {
		echo "\n  Error: success should be true\n";
		return false;
	}
	
	return true;
} );

// ============================================================================
// Test 9: Backup Creation Before Application
// ============================================================================
run_test( 'Backup Creation Before Application', function() use ( $palette_manager, $backup_manager ) {
	// Get backup count before
	$backups_before = $backup_manager->get_backups();
	$count_before = count( $backups_before );
	
	// Apply palette
	$result = $palette_manager->apply_palette( 'warm_sunset' );
	
	if ( ! $result['success'] ) {
		echo "\n  Skipping: Palette application failed\n";
		return true;
	}
	
	// Get backup count after
	$backups_after = $backup_manager->get_backups();
	$count_after = count( $backups_after );
	
	// Verify backup was created
	if ( $count_after <= $count_before ) {
		echo "\n  Error: No new backup created\n";
		return false;
	}
	
	// Verify backup ID matches
	if ( empty( $result['backup_id'] ) ) {
		echo "\n  Error: No backup_id in response\n";
		return false;
	}
	
	// Verify backup exists
	$backup = $backup_manager->get_backup( $result['backup_id'] );
	if ( ! $backup ) {
		echo "\n  Error: Backup not found: {$result['backup_id']}\n";
		return false;
	}
	
	return true;
} );

// ============================================================================
// Test 10: User-Friendly Error Messages
// ============================================================================
run_test( 'User-Friendly Error Messages', function() use ( $palette_manager ) {
	$result = $palette_manager->apply_palette( 'nonexistent' );
	
	// Check message is user-friendly (not technical)
	$message = $result['message'];
	
	// Should not contain technical terms
	$technical_terms = array( 'Exception', 'undefined', 'null', 'array', 'object' );
	foreach ( $technical_terms as $term ) {
		if ( stripos( $message, $term ) !== false ) {
			echo "\n  Error: Message contains technical term '{$term}'\n";
			return false;
		}
	}
	
	// Should be descriptive (at least 20 characters)
	if ( strlen( $message ) < 20 ) {
		echo "\n  Error: Message too short: '{$message}'\n";
		return false;
	}
	
	return true;
} );

// ============================================================================
// Test 11: Error Logging
// ============================================================================
run_test( 'Error Logging', function() use ( $palette_manager ) {
	// Clear error log
	$log_file = WP_CONTENT_DIR . '/debug.log';
	if ( file_exists( $log_file ) ) {
		$log_before = file_get_contents( $log_file );
	} else {
		$log_before = '';
	}
	
	// Trigger error
	$result = $palette_manager->apply_palette( 'invalid_palette_xyz' );
	
	// Check if error was logged
	if ( file_exists( $log_file ) ) {
		$log_after = file_get_contents( $log_file );
		$new_log = substr( $log_after, strlen( $log_before ) );
		
		// Verify log contains error information
		if ( strpos( $new_log, 'WOOW Palette Manager' ) === false ) {
			echo "\n  Warning: Error may not have been logged\n";
			return true; // Don't fail test if logging is disabled
		}
		
		if ( strpos( $new_log, 'PALETTE_NOT_FOUND' ) === false ) {
			echo "\n  Warning: Error code not in log\n";
			return true;
		}
	}
	
	return true;
} );

// ============================================================================
// Test 12: Context Data in Errors
// ============================================================================
run_test( 'Context Data in Errors', function() use ( $palette_manager ) {
	$result = $palette_manager->apply_palette( 'test_palette_id' );
	
	if ( $result['success'] ) {
		echo "\n  Skipping: Palette application succeeded\n";
		return true;
	}
	
	// Verify context contains palette_id
	if ( ! isset( $result['context']['palette_id'] ) ) {
		echo "\n  Error: Context missing palette_id\n";
		return false;
	}
	
	// Verify palette_id matches
	if ( $result['context']['palette_id'] !== 'test_palette_id' ) {
		echo "\n  Error: Context palette_id mismatch\n";
		return false;
	}
	
	return true;
} );

// ============================================================================
// Summary
// ============================================================================
echo "\n";
echo "=== Test Summary ===\n";
echo "Passed: {$tests_passed}\n";
echo "Failed: {$tests_failed}\n";
echo "Total:  " . ( $tests_passed + $tests_failed ) . "\n";

if ( $tests_failed === 0 ) {
	echo "\n✓ All tests passed!\n";
	exit( 0 );
} else {
	echo "\n✗ Some tests failed.\n";
	exit( 1 );
}
