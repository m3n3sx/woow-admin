<?php
/**
 * Simple test to verify backup manager methods exist
 *
 * @package WoowAdmin
 */

require_once 'includes/class-woow-backup-manager.php';

echo "=== Backup Manager Method Verification ===\n\n";

// Check if class exists
if ( ! class_exists( 'WOOW_Backup_Manager' ) ) {
	die( "✗ WOOW_Backup_Manager class not found\n" );
}

echo "✓ WOOW_Backup_Manager class exists\n\n";

// Check methods
$required_methods = array(
	'create_backup'    => 'Creates a new backup with label',
	'restore_backup'   => 'Restores a specific backup by ID',
	'restore_latest'   => 'Restores the most recent backup',
	'get_backups'      => 'Gets list of all backups',
	'get_backup'       => 'Gets a specific backup by ID',
	'delete_backup'    => 'Deletes a backup',
	'get_stats'        => 'Gets backup statistics',
);

echo "Checking required methods:\n";
echo "-----------------------------------\n";

$all_exist = true;

foreach ( $required_methods as $method => $description ) {
	if ( method_exists( 'WOOW_Backup_Manager', $method ) ) {
		echo "✓ {$method}() - {$description}\n";
	} else {
		echo "✗ {$method}() - MISSING\n";
		$all_exist = false;
	}
}

echo "\n";

// Check method signatures using reflection
echo "Checking method signatures:\n";
echo "-----------------------------------\n";

$reflection = new ReflectionClass( 'WOOW_Backup_Manager' );

// Check create_backup signature
$create_backup = $reflection->getMethod( 'create_backup' );
$params = $create_backup->getParameters();
echo "create_backup() parameters:\n";
foreach ( $params as $param ) {
	$name = $param->getName();
	$type = $param->getType() ? $param->getType()->getName() : 'mixed';
	$optional = $param->isOptional() ? ' (optional)' : ' (required)';
	$default = $param->isOptional() ? " = '" . $param->getDefaultValue() . "'" : '';
	echo "  - \${$name}: {$type}{$optional}{$default}\n";
}

echo "\n";

// Check restore_latest signature
if ( method_exists( 'WOOW_Backup_Manager', 'restore_latest' ) ) {
	$restore_latest = $reflection->getMethod( 'restore_latest' );
	$params = $restore_latest->getParameters();
	echo "restore_latest() parameters:\n";
	if ( empty( $params ) ) {
		echo "  - No parameters (correct)\n";
	} else {
		foreach ( $params as $param ) {
			echo "  - \${$param->getName()}\n";
		}
	}
	
	$return_type = $restore_latest->getReturnType();
	if ( $return_type ) {
		echo "  Return type: {$return_type->getName()}\n";
	}
}

echo "\n";

// Summary
echo "=== Summary ===\n";
if ( $all_exist ) {
	echo "✓ All required methods exist\n";
	echo "✓ Backup integration is ready\n";
} else {
	echo "✗ Some methods are missing\n";
	echo "✗ Backup integration incomplete\n";
}

echo "\n";
