<?php
/**
 * Completeness Verification Script
 * 
 * This script verifies that all palettes and templates are complete
 * and meet the requirements.
 * 
 * @package WOOW_Admin
 * @since 1.0.0
 */

// Color codes for output
define('GREEN', "\033[32m");
define('RED', "\033[31m");
define('YELLOW', "\033[33m");
define('BLUE', "\033[34m");
define('RESET', "\033[0m");

echo BLUE . "=================================================\n" . RESET;
echo BLUE . "WOOW! Admin - Completeness Verification\n" . RESET;
echo BLUE . "=================================================\n\n" . RESET;

$errors = array();
$warnings = array();
$passed = 0;
$total = 0;

/**
 * Check if file exists
 */
function check_file($file, $description) {
	global $errors, $passed, $total;
	$total++;
	
	if (file_exists($file)) {
		echo GREEN . "✓" . RESET . " $description\n";
		$passed++;
		return true;
	} else {
		echo RED . "✗" . RESET . " $description\n";
		$errors[] = "Missing file: $file";
		return false;
	}
}

/**
 * Check palette completeness
 */
function check_palette($palette_id, $palette_data) {
	global $errors, $warnings, $passed, $total;
	
	$required_sections = array(
		'color_overrides',
		'admin_bar',
		'admin_menu',
		'dashboard_widgets',
		'form_controls',
		'buttons',
		'backgrounds',
		'typography',
		'effects',
		'login_page'
	);
	
	$missing_sections = array();
	foreach ($required_sections as $section) {
		if (!isset($palette_data['settings'][$section])) {
			$missing_sections[] = $section;
		}
	}
	
	$total++;
	if (empty($missing_sections)) {
		echo GREEN . "✓" . RESET . " Palette '$palette_id' has all 10 sections\n";
		$passed++;
	} else {
		echo RED . "✗" . RESET . " Palette '$palette_id' missing sections: " . implode(', ', $missing_sections) . "\n";
		$errors[] = "Palette '$palette_id' incomplete";
	}
	
	// Check option count
	$total_options = 0;
	foreach ($required_sections as $section) {
		if (isset($palette_data['settings'][$section])) {
			$total_options += count($palette_data['settings'][$section]);
		}
	}
	
	$total++;
	if ($total_options >= 100) {
		echo GREEN . "✓" . RESET . " Palette '$palette_id' has $total_options options (target: 100+)\n";
		$passed++;
	} else {
		echo YELLOW . "⚠" . RESET . " Palette '$palette_id' has only $total_options options (target: 100+)\n";
		$warnings[] = "Palette '$palette_id' has fewer than 100 options";
	}
}

/**
 * Check template completeness
 */
function check_template($template_id, $template_data) {
	global $errors, $warnings, $passed, $total;
	
	$required_sections = array(
		'color_overrides',
		'admin_bar',
		'admin_menu',
		'dashboard_widgets',
		'form_controls',
		'buttons',
		'backgrounds',
		'typography',
		'effects',
		'login_page'
	);
	
	$missing_sections = array();
	foreach ($required_sections as $section) {
		if (!isset($template_data['settings'][$section])) {
			$missing_sections[] = $section;
		}
	}
	
	$total++;
	if (empty($missing_sections)) {
		echo GREEN . "✓" . RESET . " Template '$template_id' has all 10 sections\n";
		$passed++;
	} else {
		echo RED . "✗" . RESET . " Template '$template_id' missing sections: " . implode(', ', $missing_sections) . "\n";
		$errors[] = "Template '$template_id' incomplete";
	}
	
	// Check option count
	$total_options = 0;
	foreach ($required_sections as $section) {
		if (isset($template_data['settings'][$section])) {
			$total_options += count($template_data['settings'][$section]);
		}
	}
	
	$total++;
	if ($total_options >= 100) {
		echo GREEN . "✓" . RESET . " Template '$template_id' has $total_options options (target: 100+)\n";
		$passed++;
	} else {
		echo YELLOW . "⚠" . RESET . " Template '$template_id' has only $total_options options (target: 100+)\n";
		$warnings[] = "Template '$template_id' has fewer than 100 options";
	}
}

// Check core files
echo BLUE . "\n1. Checking Core Files...\n" . RESET;
check_file('includes/data/palettes.php', 'Palettes data file');
check_file('includes/data/templates-data.php', 'Templates data file');
check_file('includes/palettes/class-woow-palette-manager.php', 'Palette Manager class');
check_file('includes/class-woow-template-manager.php', 'Template Manager class');
check_file('includes/class-woow-rest-api.php', 'REST API class');

// Load and check palettes
echo BLUE . "\n2. Checking Palettes...\n" . RESET;
if (file_exists('includes/data/palettes.php')) {
	$palettes = include 'includes/data/palettes.php';
	
	$total++;
	$palette_count = count($palettes);
	if ($palette_count === 10) {
		echo GREEN . "✓" . RESET . " Found $palette_count palettes (target: 10)\n";
		$passed++;
	} else {
		echo RED . "✗" . RESET . " Found $palette_count palettes (target: 10)\n";
		$errors[] = "Incorrect palette count: $palette_count";
	}
	
	foreach ($palettes as $palette_id => $palette_data) {
		check_palette($palette_id, $palette_data);
	}
}

// Load and check templates
echo BLUE . "\n3. Checking Templates...\n" . RESET;
if (file_exists('includes/data/templates-data.php')) {
	$templates = include 'includes/data/templates-data.php';
	
	$total++;
	$template_count = count($templates);
	if ($template_count === 11) {
		echo GREEN . "✓" . RESET . " Found $template_count templates (target: 11)\n";
		$passed++;
	} else {
		echo RED . "✗" . RESET . " Found $template_count templates (target: 11)\n";
		$errors[] = "Incorrect template count: $template_count";
	}
	
	foreach ($templates as $template_id => $template_data) {
		check_template($template_id, $template_data);
	}
}

// Check preview images
echo BLUE . "\n4. Checking Preview Images...\n" . RESET;

// Palette previews
$palette_preview_dir = 'assets/images/previews/palettes/';
if (is_dir($palette_preview_dir)) {
	$palette_previews = glob($palette_preview_dir . '*.png');
	$total++;
	if (count($palette_previews) === 10) {
		echo GREEN . "✓" . RESET . " Found " . count($palette_previews) . " palette preview images (target: 10)\n";
		$passed++;
	} else {
		echo RED . "✗" . RESET . " Found " . count($palette_previews) . " palette preview images (target: 10)\n";
		$errors[] = "Incorrect palette preview count";
	}
}

// Template previews
$template_preview_dir = 'assets/images/previews/templates/';
if (is_dir($template_preview_dir)) {
	$template_previews = glob($template_preview_dir . '*.png');
	$total++;
	if (count($template_previews) === 11) {
		echo GREEN . "✓" . RESET . " Found " . count($template_previews) . " template preview images (target: 11)\n";
		$passed++;
	} else {
		echo RED . "✗" . RESET . " Found " . count($template_previews) . " template preview images (target: 11)\n";
		$errors[] = "Incorrect template preview count";
	}
}

// Check test files
echo BLUE . "\n5. Checking Test Files...\n" . RESET;
check_file('tests/php/PaletteManagerTest.php', 'Palette Manager tests');
check_file('tests/php/TemplateManagerTest.php', 'Template Manager tests');
check_file('tests/php/IntegrationTest.php', 'Integration tests');
check_file('tests/php/PerformanceTest.php', 'Performance tests');

// Check documentation
echo BLUE . "\n6. Checking Documentation...\n" . RESET;
check_file('docs/README.md', 'Documentation README');
check_file('docs/USER-GUIDE.md', 'User Guide');
check_file('docs/DEVELOPER-GUIDE.md', 'Developer Guide');
check_file('docs/QUICK-START.md', 'Quick Start Guide');
check_file('docs/FAQ.md', 'FAQ');
check_file('docs/VISUAL-GUIDE.md', 'Visual Guide');

// Summary
echo BLUE . "\n=================================================\n" . RESET;
echo BLUE . "Verification Summary\n" . RESET;
echo BLUE . "=================================================\n\n" . RESET;

$pass_rate = ($total > 0) ? round(($passed / $total) * 100, 1) : 0;

echo "Total Checks: $total\n";
echo GREEN . "Passed: $passed\n" . RESET;
if (!empty($warnings)) {
	echo YELLOW . "Warnings: " . count($warnings) . "\n" . RESET;
}
if (!empty($errors)) {
	echo RED . "Failed: " . count($errors) . "\n" . RESET;
}
echo "Pass Rate: $pass_rate%\n\n";

if (!empty($warnings)) {
	echo YELLOW . "Warnings:\n" . RESET;
	foreach ($warnings as $warning) {
		echo YELLOW . "  ⚠ $warning\n" . RESET;
	}
	echo "\n";
}

if (!empty($errors)) {
	echo RED . "Errors:\n" . RESET;
	foreach ($errors as $error) {
		echo RED . "  ✗ $error\n" . RESET;
	}
	echo "\n";
}

if (empty($errors)) {
	echo GREEN . "✓ All checks passed! Project is complete and ready.\n\n" . RESET;
	exit(0);
} else {
	echo RED . "✗ Some checks failed. Please review the errors above.\n\n" . RESET;
	exit(1);
}
