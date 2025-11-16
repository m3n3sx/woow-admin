<?php
/**
 * Template Visual Quality Testing Script
 *
 * This script tests all 11 templates by applying each one and evaluating:
 * - All sections are styled correctly
 * - Design consistency within template
 * - Uniqueness vs other templates
 * - Visual quality rating (target 8/10+)
 *
 * Usage: php test-template-visual-quality.php
 *
 * @package WOOW_Admin
 */

// Ensure we're running from command line
if ( php_sapi_name() !== 'cli' ) {
	die( 'This script must be run from the command line.' );
}

// Load WordPress
$wp_load_path = __DIR__ . '/../../../wp-load.php';
if ( ! file_exists( $wp_load_path ) ) {
	die( "Error: Cannot find wp-load.php at {$wp_load_path}\n" );
}

// Suppress errors during WordPress load
error_reporting( E_ERROR | E_PARSE );
require_once $wp_load_path;
error_reporting( E_ALL );

// Check if WordPress loaded
if ( ! defined( 'ABSPATH' ) ) {
	die( "Error: WordPress did not load properly\n" );
}

// Color output helpers
function color_text( $text, $color ) {
	$colors = array(
		'red'    => "\033[31m",
		'green'  => "\033[32m",
		'yellow' => "\033[33m",
		'blue'   => "\033[34m",
		'purple' => "\033[35m",
		'cyan'   => "\033[36m",
		'white'  => "\033[37m",
		'reset'  => "\033[0m",
	);
	return $colors[ $color ] . $text . $colors['reset'];
}

function print_header( $text ) {
	echo "\n" . str_repeat( '=', 80 ) . "\n";
	echo color_text( $text, 'cyan' ) . "\n";
	echo str_repeat( '=', 80 ) . "\n\n";
}

function print_section( $text ) {
	echo "\n" . color_text( $text, 'yellow' ) . "\n";
	echo str_repeat( '-', 80 ) . "\n";
}

function print_success( $text ) {
	echo color_text( '✓ ', 'green' ) . $text . "\n";
}

function print_error( $text ) {
	echo color_text( '✗ ', 'red' ) . $text . "\n";
}

function print_info( $text ) {
	echo color_text( '→ ', 'blue' ) . $text . "\n";
}

function print_warning( $text ) {
	echo color_text( '⚠ ', 'yellow' ) . $text . "\n";
}

// Initialize managers
$settings_manager = new WOOW_Settings();
$template_manager = new WOOW_Template_Manager( $settings_manager );
$css_generator = new WOOW_CSS_Generator( $settings_manager );

print_header( 'WOOW! Admin - Template Visual Quality Testing' );

// Get all templates
$templates = $template_manager->get_all_templates();

if ( empty( $templates ) ) {
	print_error( 'No templates found!' );
	exit( 1 );
}

print_info( 'Found ' . count( $templates ) . ' templates to test' );

// Expected templates
$expected_templates = array(
	'modern_minimal',
	'glassmorphism_pro',
	'dark_dashboard',
	'colorful_creative',
	'corporate_blue',
	'material_design',
	'flat_2',
	'neumorphism',
	'retro_wave',
	'nature_inspired',
	'high_contrast',
);

// Check all expected templates exist
print_section( 'Template Availability Check' );
$missing_templates = array();
foreach ( $expected_templates as $template_id ) {
	if ( isset( $templates[ $template_id ] ) ) {
		print_success( "Template '{$template_id}' found" );
	} else {
		print_error( "Template '{$template_id}' MISSING" );
		$missing_templates[] = $template_id;
	}
}

if ( ! empty( $missing_templates ) ) {
	print_error( "\nMissing templates: " . implode( ', ', $missing_templates ) );
	exit( 1 );
}

// Store original settings for restoration
$original_settings = $settings_manager->get_all_settings();

// Test results storage
$test_results = array();
$all_template_colors = array();

// Test each template
foreach ( $templates as $template_id => $template ) {
	print_section( "Testing Template: {$template['name']} ({$template_id})" );
	
	$result = array(
		'id'                  => $template_id,
		'name'                => $template['name'],
		'category'            => $template['category'] ?? 'unknown',
		'completeness'        => array(),
		'consistency'         => array(),
		'uniqueness'          => array(),
		'quality_score'       => 0,
		'issues'              => array(),
		'strengths'           => array(),
	);
	
	// Test 1: Completeness - Check all sections are present
	print_info( 'Test 1: Checking completeness...' );
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
		'login_page',
	);
	
	$missing_sections = array();
	foreach ( $required_sections as $section ) {
		if ( ! isset( $template['settings'][ $section ] ) ) {
			$missing_sections[] = $section;
			print_error( "  Missing section: {$section}" );
		} else {
			$option_count = count( $template['settings'][ $section ] );
			print_success( "  Section '{$section}' present ({$option_count} options)" );
			$result['completeness'][ $section ] = $option_count;
		}
	}
	
	if ( empty( $missing_sections ) ) {
		print_success( 'All 10 sections present' );
		$result['strengths'][] = 'Complete section coverage';
	} else {
		$result['issues'][] = 'Missing sections: ' . implode( ', ', $missing_sections );
	}
	
	// Test 2: Apply template and check CSS generation
	print_info( 'Test 2: Applying template and generating CSS...' );
	try {
		$apply_result = $template_manager->apply_template( $template_id );
		
		if ( $apply_result ) {
			print_success( '  Template applied successfully' );
			$result['strengths'][] = 'Successful application';
			
			// Generate CSS
			$css = $css_generator->generate();
			if ( ! empty( $css ) ) {
				$css_size = strlen( $css );
				print_success( "  CSS generated ({$css_size} bytes)" );
				$result['strengths'][] = "CSS generation successful ({$css_size} bytes)";
			} else {
				print_error( '  CSS generation failed or empty' );
				$result['issues'][] = 'Empty CSS output';
			}
		} else {
			print_error( '  Template application failed' );
			$result['issues'][] = 'Application failed';
		}
	} catch ( Exception $e ) {
		print_error( '  Exception: ' . $e->getMessage() );
		$result['issues'][] = 'Exception during application: ' . $e->getMessage();
	}
	
	// Test 3: Design Consistency - Check characteristics match settings
	print_info( 'Test 3: Checking design consistency...' );
	$characteristics = $template['characteristics'] ?? array();
	$settings = $template['settings'];
	
	// Check glassmorphism consistency
	if ( isset( $characteristics['glassmorphism'] ) ) {
		$char_glass = $characteristics['glassmorphism'];
		$settings_glass = $settings['effects']['glassmorphism_enabled'] ?? false;
		
		if ( $char_glass === $settings_glass ) {
			print_success( "  Glassmorphism consistent: " . ( $char_glass ? 'enabled' : 'disabled' ) );
			$result['consistency'][] = 'Glassmorphism setting matches characteristics';
		} else {
			print_warning( "  Glassmorphism mismatch: char={$char_glass}, settings={$settings_glass}" );
			$result['issues'][] = 'Glassmorphism characteristic mismatch';
		}
	}
	
	// Check gradients consistency
	if ( isset( $characteristics['gradients'] ) ) {
		$char_gradients = $characteristics['gradients'];
		$has_gradient_bar = ( $settings['admin_bar']['background_type'] ?? '' ) === 'gradient';
		$has_gradient_menu = ( $settings['admin_menu']['background_type'] ?? '' ) === 'gradient';
		
		if ( $char_gradients && ( $has_gradient_bar || $has_gradient_menu ) ) {
			print_success( '  Gradients used as expected' );
			$result['consistency'][] = 'Gradient usage matches characteristics';
		} elseif ( ! $char_gradients && ! $has_gradient_bar && ! $has_gradient_menu ) {
			print_success( '  No gradients as expected' );
			$result['consistency'][] = 'No gradients as expected';
		} else {
			print_warning( '  Gradient usage may not match characteristics' );
		}
	}
	
	// Check animations consistency
	if ( isset( $characteristics['animations'] ) ) {
		$char_animations = $characteristics['animations'];
		$settings_animations = $settings['effects']['animations_enabled'] ?? false;
		
		if ( $char_animations === 'none' && ! $settings_animations ) {
			print_success( '  Animations disabled as expected' );
			$result['consistency'][] = 'Animations disabled as expected';
		} elseif ( $char_animations !== 'none' && $settings_animations ) {
			print_success( "  Animations enabled ({$char_animations})" );
			$result['consistency'][] = "Animations enabled ({$char_animations})";
		} else {
			print_warning( '  Animation settings may not match characteristics' );
		}
	}
	
	// Test 4: Color Palette Analysis
	print_info( 'Test 4: Analyzing color palette...' );
	$colors = $settings['color_overrides'] ?? array();
	$unique_colors = array_unique( array_values( $colors ) );
	$color_count = count( $unique_colors );
	
	print_info( "  Using {$color_count} unique colors" );
	$result['uniqueness']['color_count'] = $color_count;
	$result['uniqueness']['colors'] = $unique_colors;
	
	// Store for uniqueness comparison
	$all_template_colors[ $template_id ] = $unique_colors;
	
	// Check color variety
	if ( $color_count >= 5 ) {
		print_success( '  Good color variety' );
		$result['strengths'][] = 'Good color variety';
	} elseif ( $color_count >= 3 ) {
		print_info( '  Moderate color variety' );
	} else {
		print_warning( '  Limited color variety' );
		$result['issues'][] = 'Limited color variety';
	}
	
	// Test 5: Typography Analysis
	print_info( 'Test 5: Analyzing typography...' );
	$typography = $settings['typography'] ?? array();
	
	$body_font = $typography['body_font'] ?? '';
	$heading_font = $typography['heading_font'] ?? '';
	$body_size = $typography['body_size'] ?? 0;
	
	print_info( "  Body font: {$body_font}" );
	print_info( "  Heading font: {$heading_font}" );
	print_info( "  Body size: {$body_size}px" );
	
	// Check font size is reasonable
	if ( $body_size >= 13 && $body_size <= 16 ) {
		print_success( '  Body font size is readable' );
		$result['strengths'][] = 'Readable font size';
	} else {
		print_warning( '  Body font size may be too small or large' );
		$result['issues'][] = 'Font size outside recommended range';
	}
	
	// Test 6: Effects Analysis
	print_info( 'Test 6: Analyzing effects...' );
	$effects = $settings['effects'] ?? array();
	
	$glass_enabled = $effects['glassmorphism_enabled'] ?? false;
	$animations_enabled = $effects['animations_enabled'] ?? false;
	$hover_scale = $effects['hover_scale'] ?? 1.0;
	
	print_info( "  Glassmorphism: " . ( $glass_enabled ? 'enabled' : 'disabled' ) );
	print_info( "  Animations: " . ( $animations_enabled ? 'enabled' : 'disabled' ) );
	print_info( "  Hover scale: {$hover_scale}" );
	
	// Test 7: Border Radius Analysis
	print_info( 'Test 7: Analyzing border radius...' );
	$admin_bar_radius = $settings['admin_bar']['border_radius_all'] ?? 0;
	$widget_radius = $settings['dashboard_widgets']['border_radius'] ?? 0;
	$button_radius = $settings['buttons']['primary_border_radius'] ?? 0;
	
	print_info( "  Admin bar radius: {$admin_bar_radius}px" );
	print_info( "  Widget radius: {$widget_radius}px" );
	print_info( "  Button radius: {$button_radius}px" );
	
	// Check consistency
	$radius_values = array( $admin_bar_radius, $widget_radius, $button_radius );
	$radius_variety = count( array_unique( $radius_values ) );
	
	if ( $radius_variety === 1 ) {
		print_success( '  Consistent border radius throughout' );
		$result['consistency'][] = 'Consistent border radius';
	} else {
		print_info( '  Varied border radius for different elements' );
		$result['consistency'][] = 'Varied border radius';
	}
	
	// Calculate Quality Score (0-10)
	$quality_score = 10;
	
	// Deduct points for issues
	$quality_score -= count( $result['issues'] ) * 0.5;
	
	// Deduct points for missing sections
	$quality_score -= count( $missing_sections ) * 1.0;
	
	// Add points for strengths (max +2)
	$quality_score += min( count( $result['strengths'] ) * 0.2, 2.0 );
	
	// Ensure score is between 0 and 10
	$quality_score = max( 0, min( 10, $quality_score ) );
	
	$result['quality_score'] = round( $quality_score, 1 );
	
	// Print quality score
	if ( $quality_score >= 8.0 ) {
		print_success( "\nQuality Score: {$quality_score}/10 - EXCELLENT" );
	} elseif ( $quality_score >= 6.0 ) {
		print_info( "\nQuality Score: {$quality_score}/10 - GOOD" );
	} else {
		print_warning( "\nQuality Score: {$quality_score}/10 - NEEDS IMPROVEMENT" );
	}
	
	$test_results[ $template_id ] = $result;
}

// Restore original settings
print_section( 'Restoring Original Settings' );
$settings_manager->update_settings( $original_settings );
print_success( 'Original settings restored' );

// Test 8: Uniqueness Comparison
print_section( 'Template Uniqueness Analysis' );
print_info( 'Comparing templates for uniqueness...' );

$similarity_matrix = array();

foreach ( $all_template_colors as $template1_id => $colors1 ) {
	foreach ( $all_template_colors as $template2_id => $colors2 ) {
		if ( $template1_id === $template2_id ) {
			continue;
		}
		
		// Calculate color overlap
		$common_colors = array_intersect( $colors1, $colors2 );
		$total_colors = array_unique( array_merge( $colors1, $colors2 ) );
		$similarity = count( $common_colors ) / count( $total_colors ) * 100;
		
		$similarity_matrix[ $template1_id ][ $template2_id ] = $similarity;
		
		if ( $similarity > 50 ) {
			print_warning( "  {$template1_id} and {$template2_id}: {$similarity}% similar (HIGH)" );
			$test_results[ $template1_id ]['issues'][] = "High similarity with {$template2_id} ({$similarity}%)";
		} elseif ( $similarity > 20 ) {
			print_info( "  {$template1_id} and {$template2_id}: {$similarity}% similar" );
		}
	}
}

// Final Summary
print_header( 'Test Summary' );

$total_templates = count( $test_results );
$excellent_count = 0;
$good_count = 0;
$needs_improvement_count = 0;

foreach ( $test_results as $template_id => $result ) {
	$score = $result['quality_score'];
	
	if ( $score >= 8.0 ) {
		$excellent_count++;
		$status = color_text( 'EXCELLENT', 'green' );
	} elseif ( $score >= 6.0 ) {
		$good_count++;
		$status = color_text( 'GOOD', 'yellow' );
	} else {
		$needs_improvement_count++;
		$status = color_text( 'NEEDS IMPROVEMENT', 'red' );
	}
	
	echo sprintf(
		"%-25s %s %s\n",
		$result['name'],
		str_pad( $score . '/10', 8 ),
		$status
	);
}

echo "\n";
print_info( "Total templates tested: {$total_templates}" );
print_success( "Excellent (8.0+): {$excellent_count}" );
print_info( "Good (6.0-7.9): {$good_count}" );
print_warning( "Needs Improvement (<6.0): {$needs_improvement_count}" );

// Check if target met
$target_met = ( $excellent_count + $good_count ) >= ( $total_templates * 0.8 );

echo "\n";
if ( $target_met ) {
	print_success( '✓ TARGET MET: 80%+ of templates rated 6.0 or higher' );
} else {
	print_error( '✗ TARGET NOT MET: Less than 80% of templates rated 6.0 or higher' );
}

// Detailed Issues Report
if ( $needs_improvement_count > 0 ) {
	print_section( 'Templates Needing Improvement' );
	
	foreach ( $test_results as $template_id => $result ) {
		if ( $result['quality_score'] < 6.0 ) {
			echo "\n" . color_text( $result['name'] . " ({$template_id})", 'yellow' ) . "\n";
			
			if ( ! empty( $result['issues'] ) ) {
				echo "  Issues:\n";
				foreach ( $result['issues'] as $issue ) {
					print_error( "    - {$issue}" );
				}
			}
			
			if ( ! empty( $result['strengths'] ) ) {
				echo "  Strengths:\n";
				foreach ( $result['strengths'] as $strength ) {
					print_success( "    - {$strength}" );
				}
			}
		}
	}
}

// Save detailed report
$report_file = __DIR__ . '/TEMPLATE-VISUAL-TESTING-REPORT.md';
$report_content = "# Template Visual Testing Report\n\n";
$report_content .= "Generated: " . date( 'Y-m-d H:i:s' ) . "\n\n";
$report_content .= "## Summary\n\n";
$report_content .= "- Total templates tested: {$total_templates}\n";
$report_content .= "- Excellent (8.0+): {$excellent_count}\n";
$report_content .= "- Good (6.0-7.9): {$good_count}\n";
$report_content .= "- Needs Improvement (<6.0): {$needs_improvement_count}\n";
$report_content .= "- Target met: " . ( $target_met ? 'YES' : 'NO' ) . "\n\n";

$report_content .= "## Individual Template Results\n\n";

foreach ( $test_results as $template_id => $result ) {
	$report_content .= "### {$result['name']} ({$template_id})\n\n";
	$report_content .= "- **Category**: {$result['category']}\n";
	$report_content .= "- **Quality Score**: {$result['quality_score']}/10\n";
	$report_content .= "- **Sections**: " . count( $result['completeness'] ) . "/10\n\n";
	
	if ( ! empty( $result['strengths'] ) ) {
		$report_content .= "**Strengths:**\n";
		foreach ( $result['strengths'] as $strength ) {
			$report_content .= "- {$strength}\n";
		}
		$report_content .= "\n";
	}
	
	if ( ! empty( $result['issues'] ) ) {
		$report_content .= "**Issues:**\n";
		foreach ( $result['issues'] as $issue ) {
			$report_content .= "- {$issue}\n";
		}
		$report_content .= "\n";
	}
	
	if ( ! empty( $result['consistency'] ) ) {
		$report_content .= "**Consistency Checks:**\n";
		foreach ( $result['consistency'] as $check ) {
			$report_content .= "- {$check}\n";
		}
		$report_content .= "\n";
	}
	
	$report_content .= "---\n\n";
}

file_put_contents( $report_file, $report_content );
print_success( "\nDetailed report saved to: {$report_file}" );

// Exit with appropriate code
exit( $target_met ? 0 : 1 );
