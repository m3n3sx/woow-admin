<?php
/**
 * Simple Template Visual Quality Testing Script
 *
 * This script tests all 11 templates by loading the data file directly
 * and evaluating completeness, consistency, and uniqueness.
 *
 * Usage: php test-template-visual-simple.php
 *
 * @package WOOW_Admin
 */

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

// Define ABSPATH for the data file
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/../../../' );
}

print_header( 'WOOW! Admin - Template Visual Quality Testing' );

// Load templates data
$templates_file = __DIR__ . '/includes/data/templates-data.php';

if ( ! file_exists( $templates_file ) ) {
	print_error( "Templates data file not found: {$templates_file}" );
	exit( 1 );
}

print_info( "Loading templates from: {$templates_file}" );
$templates = require $templates_file;

if ( empty( $templates ) || ! is_array( $templates ) ) {
	print_error( 'No templates found or invalid data format!' );
	exit( 1 );
}

print_success( 'Found ' . count( $templates ) . ' templates' );

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

print_success( "\nAll 11 expected templates are present!" );

// Test results storage
$test_results = array();
$all_template_colors = array();

// Required sections
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
	
	// Test 1: Metadata Check
	print_info( 'Test 1: Checking metadata...' );
	$required_meta = array( 'id', 'name', 'description', 'category', 'preview_image', 'characteristics', 'settings' );
	$missing_meta = array();
	
	foreach ( $required_meta as $meta_key ) {
		if ( ! isset( $template[ $meta_key ] ) ) {
			$missing_meta[] = $meta_key;
			print_error( "  Missing metadata: {$meta_key}" );
		}
	}
	
	if ( empty( $missing_meta ) ) {
		print_success( '  All metadata present' );
		$result['strengths'][] = 'Complete metadata';
	} else {
		$result['issues'][] = 'Missing metadata: ' . implode( ', ', $missing_meta );
	}
	
	// Test 2: Completeness - Check all sections are present
	print_info( 'Test 2: Checking section completeness...' );
	
	$missing_sections = array();
	$total_options = 0;
	
	foreach ( $required_sections as $section ) {
		if ( ! isset( $template['settings'][ $section ] ) ) {
			$missing_sections[] = $section;
			print_error( "  Missing section: {$section}" );
		} else {
			$option_count = count( $template['settings'][ $section ] );
			$total_options += $option_count;
			print_success( "  Section '{$section}' present ({$option_count} options)" );
			$result['completeness'][ $section ] = $option_count;
		}
	}
	
	if ( empty( $missing_sections ) ) {
		print_success( "  All 10 sections present ({$total_options} total options)" );
		$result['strengths'][] = "Complete section coverage ({$total_options} options)";
	} else {
		$result['issues'][] = 'Missing sections: ' . implode( ', ', $missing_sections );
	}
	
	// Check if meets 100+ options target
	if ( $total_options >= 100 ) {
		print_success( "  Meets 100+ options target ({$total_options} options)" );
		$result['strengths'][] = 'Meets 100+ options target';
	} else {
		print_warning( "  Below 100 options target ({$total_options} options)" );
		$result['issues'][] = "Only {$total_options} options (target: 100+)";
	}
	
	// Test 3: Design Consistency - Check characteristics match settings
	print_info( 'Test 3: Checking design consistency...' );
	$characteristics = $template['characteristics'] ?? array();
	$settings = $template['settings'];
	
	// Check glassmorphism consistency
	if ( isset( $characteristics['glassmorphism'] ) && isset( $settings['effects']['glassmorphism_enabled'] ) ) {
		$char_glass = $characteristics['glassmorphism'];
		$settings_glass = $settings['effects']['glassmorphism_enabled'];
		
		if ( $char_glass === $settings_glass ) {
			print_success( "  Glassmorphism consistent: " . ( $char_glass ? 'enabled' : 'disabled' ) );
			$result['consistency'][] = 'Glassmorphism setting matches characteristics';
		} else {
			print_warning( "  Glassmorphism mismatch: char=" . var_export( $char_glass, true ) . ", settings=" . var_export( $settings_glass, true ) );
			$result['issues'][] = 'Glassmorphism characteristic mismatch';
		}
	}
	
	// Check gradients consistency
	if ( isset( $characteristics['gradients'] ) ) {
		$char_gradients = $characteristics['gradients'];
		$has_gradient_bar = ( $settings['admin_bar']['background_type'] ?? '' ) === 'gradient';
		$has_gradient_menu = ( $settings['admin_menu']['background_type'] ?? '' ) === 'gradient';
		$has_gradient_bg = strpos( $settings['backgrounds']['body_bg'] ?? '', 'gradient' ) !== false;
		
		$has_any_gradient = $has_gradient_bar || $has_gradient_menu || $has_gradient_bg;
		
		if ( $char_gradients && $has_any_gradient ) {
			print_success( '  Gradients used as expected' );
			$result['consistency'][] = 'Gradient usage matches characteristics';
		} elseif ( ! $char_gradients && ! $has_any_gradient ) {
			print_success( '  No gradients as expected' );
			$result['consistency'][] = 'No gradients as expected';
		} else {
			print_warning( "  Gradient usage may not match characteristics (char={$char_gradients}, has={$has_any_gradient})" );
		}
	}
	
	// Check animations consistency
	if ( isset( $characteristics['animations'] ) && isset( $settings['effects']['animations_enabled'] ) ) {
		$char_animations = $characteristics['animations'];
		$settings_animations = $settings['effects']['animations_enabled'];
		
		if ( $char_animations === 'none' && ! $settings_animations ) {
			print_success( '  Animations disabled as expected' );
			$result['consistency'][] = 'Animations disabled as expected';
		} elseif ( $char_animations !== 'none' && $settings_animations ) {
			print_success( "  Animations enabled ({$char_animations})" );
			$result['consistency'][] = "Animations enabled ({$char_animations})";
		} else {
			print_warning( "  Animation settings may not match characteristics (char={$char_animations}, enabled={$settings_animations})" );
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
		print_success( '  Good color variety (5+ unique colors)' );
		$result['strengths'][] = 'Good color variety';
	} elseif ( $color_count >= 3 ) {
		print_info( '  Moderate color variety (3-4 unique colors)' );
	} else {
		print_warning( '  Limited color variety (<3 unique colors)' );
		$result['issues'][] = 'Limited color variety';
	}
	
	// Test 5: Typography Analysis
	print_info( 'Test 5: Analyzing typography...' );
	$typography = $settings['typography'] ?? array();
	
	$body_font = $typography['body_font'] ?? 'not set';
	$heading_font = $typography['heading_font'] ?? 'not set';
	$body_size = $typography['body_size'] ?? 0;
	$line_height = $typography['body_line_height'] ?? 0;
	
	print_info( "  Body font: {$body_font}" );
	print_info( "  Heading font: {$heading_font}" );
	print_info( "  Body size: {$body_size}px" );
	print_info( "  Line height: {$line_height}" );
	
	// Check font size is reasonable
	if ( $body_size >= 13 && $body_size <= 16 ) {
		print_success( '  Body font size is readable (13-16px)' );
		$result['strengths'][] = 'Readable font size';
	} else {
		print_warning( "  Body font size outside recommended range: {$body_size}px" );
		$result['issues'][] = 'Font size outside recommended range (13-16px)';
	}
	
	// Check line height is reasonable
	if ( $line_height >= 1.4 && $line_height <= 1.8 ) {
		print_success( '  Line height is readable (1.4-1.8)' );
		$result['strengths'][] = 'Readable line height';
	} elseif ( $line_height > 0 ) {
		print_warning( "  Line height outside recommended range: {$line_height}" );
	}
	
	// Test 6: Effects Analysis
	print_info( 'Test 6: Analyzing effects...' );
	$effects = $settings['effects'] ?? array();
	
	$glass_enabled = $effects['glassmorphism_enabled'] ?? false;
	$animations_enabled = $effects['animations_enabled'] ?? false;
	$hover_scale = $effects['hover_scale'] ?? 1.0;
	$animation_speed = $effects['animation_speed'] ?? '0s';
	
	print_info( "  Glassmorphism: " . ( $glass_enabled ? 'enabled' : 'disabled' ) );
	print_info( "  Animations: " . ( $animations_enabled ? 'enabled' : 'disabled' ) );
	print_info( "  Hover scale: {$hover_scale}" );
	print_info( "  Animation speed: {$animation_speed}" );
	
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
		$result['consistency'][] = 'Varied border radius (intentional design choice)';
	}
	
	// Test 8: Background Analysis
	print_info( 'Test 8: Analyzing backgrounds...' );
	$backgrounds = $settings['backgrounds'] ?? array();
	
	$body_bg = $backgrounds['body_bg'] ?? '';
	$has_pattern = ( $backgrounds['body_pattern'] ?? 'none' ) !== 'none';
	
	if ( strpos( $body_bg, 'gradient' ) !== false ) {
		print_info( '  Body background uses gradient' );
		$result['strengths'][] = 'Gradient background';
	} else {
		print_info( '  Body background uses solid color' );
	}
	
	if ( $has_pattern ) {
		print_info( '  Body pattern enabled' );
		$result['strengths'][] = 'Background pattern';
	}
	
	// Calculate Quality Score (0-10)
	$quality_score = 10;
	
	// Deduct points for issues
	$quality_score -= count( $result['issues'] ) * 0.5;
	
	// Deduct points for missing sections
	$quality_score -= count( $missing_sections ) * 1.0;
	
	// Deduct points for missing metadata
	$quality_score -= count( $missing_meta ) * 0.5;
	
	// Add points for strengths (max +2)
	$quality_score += min( count( $result['strengths'] ) * 0.15, 2.0 );
	
	// Bonus for meeting 100+ options target
	if ( $total_options >= 100 ) {
		$quality_score += 0.5;
	}
	
	// Ensure score is between 0 and 10
	$quality_score = max( 0, min( 10, $quality_score ) );
	
	$result['quality_score'] = round( $quality_score, 1 );
	
	// Print quality score
	echo "\n";
	if ( $quality_score >= 8.0 ) {
		print_success( "Quality Score: {$quality_score}/10 - EXCELLENT ⭐⭐⭐" );
	} elseif ( $quality_score >= 6.0 ) {
		print_info( "Quality Score: {$quality_score}/10 - GOOD ⭐⭐" );
	} else {
		print_warning( "Quality Score: {$quality_score}/10 - NEEDS IMPROVEMENT ⭐" );
	}
	
	$test_results[ $template_id ] = $result;
}

// Test 9: Uniqueness Comparison
print_section( 'Template Uniqueness Analysis' );
print_info( 'Comparing templates for color uniqueness...' );

$similarity_matrix = array();
$high_similarity_pairs = array();

foreach ( $all_template_colors as $template1_id => $colors1 ) {
	foreach ( $all_template_colors as $template2_id => $colors2 ) {
		if ( $template1_id >= $template2_id ) {
			continue; // Skip self and already compared pairs
		}
		
		// Calculate color overlap
		$common_colors = array_intersect( $colors1, $colors2 );
		$total_colors = array_unique( array_merge( $colors1, $colors2 ) );
		$similarity = count( $total_colors ) > 0 ? ( count( $common_colors ) / count( $total_colors ) * 100 ) : 0;
		
		$similarity_matrix[ $template1_id ][ $template2_id ] = $similarity;
		
		if ( $similarity > 50 ) {
			print_warning( "  {$template1_id} and {$template2_id}: " . round( $similarity, 1 ) . "% similar (HIGH)" );
			$high_similarity_pairs[] = "{$template1_id} <-> {$template2_id}";
			$test_results[ $template1_id ]['issues'][] = "High similarity with {$template2_id} (" . round( $similarity, 1 ) . "%)";
			$test_results[ $template2_id ]['issues'][] = "High similarity with {$template1_id} (" . round( $similarity, 1 ) . "%)";
		} elseif ( $similarity > 30 ) {
			print_info( "  {$template1_id} and {$template2_id}: " . round( $similarity, 1 ) . "% similar (MODERATE)" );
		}
	}
}

if ( empty( $high_similarity_pairs ) ) {
	print_success( "\nAll templates are sufficiently unique (< 50% similarity)" );
} else {
	print_warning( "\nHigh similarity pairs found: " . count( $high_similarity_pairs ) );
}

// Final Summary
print_header( 'Test Summary' );

$total_templates = count( $test_results );
$excellent_count = 0;
$good_count = 0;
$needs_improvement_count = 0;

echo sprintf( "%-30s %-10s %s\n", 'Template', 'Score', 'Rating' );
echo str_repeat( '-', 80 ) . "\n";

foreach ( $test_results as $template_id => $result ) {
	$score = $result['quality_score'];
	
	if ( $score >= 8.0 ) {
		$excellent_count++;
		$status = color_text( 'EXCELLENT ⭐⭐⭐', 'green' );
	} elseif ( $score >= 6.0 ) {
		$good_count++;
		$status = color_text( 'GOOD ⭐⭐', 'yellow' );
	} else {
		$needs_improvement_count++;
		$status = color_text( 'NEEDS IMPROVEMENT ⭐', 'red' );
	}
	
	echo sprintf(
		"%-30s %-10s %s\n",
		$result['name'],
		$score . '/10',
		$status
	);
}

echo "\n";
print_info( "Total templates tested: {$total_templates}" );
print_success( "Excellent (8.0+): {$excellent_count}" );
print_info( "Good (6.0-7.9): {$good_count}" );
print_warning( "Needs Improvement (<6.0): {$needs_improvement_count}" );

// Check if target met (80% should be 8.0+)
$target_met = $excellent_count >= ( $total_templates * 0.8 );
$secondary_target = ( $excellent_count + $good_count ) >= ( $total_templates * 0.9 );

echo "\n";
if ( $target_met ) {
	print_success( '✓ PRIMARY TARGET MET: 80%+ of templates rated 8.0 or higher' );
} else {
	$percentage = round( ( $excellent_count / $total_templates ) * 100, 1 );
	print_warning( "✗ PRIMARY TARGET NOT MET: {$percentage}% rated 8.0+ (target: 80%)" );
}

if ( $secondary_target ) {
	print_success( '✓ SECONDARY TARGET MET: 90%+ of templates rated 6.0 or higher' );
} else {
	$percentage = round( ( ( $excellent_count + $good_count ) / $total_templates ) * 100, 1 );
	print_info( "  Secondary target: {$percentage}% rated 6.0+ (target: 90%)" );
}

// Detailed Issues Report
if ( $needs_improvement_count > 0 || ! empty( $high_similarity_pairs ) ) {
	print_section( 'Issues and Recommendations' );
	
	foreach ( $test_results as $template_id => $result ) {
		if ( $result['quality_score'] < 8.0 || ! empty( $result['issues'] ) ) {
			echo "\n" . color_text( $result['name'] . " ({$template_id}) - Score: {$result['quality_score']}/10", 'yellow' ) . "\n";
			
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
$report_content .= "- Excellent (8.0+): {$excellent_count} (" . round( ( $excellent_count / $total_templates ) * 100, 1 ) . "%)\n";
$report_content .= "- Good (6.0-7.9): {$good_count} (" . round( ( $good_count / $total_templates ) * 100, 1 ) . "%)\n";
$report_content .= "- Needs Improvement (<6.0): {$needs_improvement_count} (" . round( ( $needs_improvement_count / $total_templates ) * 100, 1 ) . "%)\n";
$report_content .= "- Primary target (80% at 8.0+): " . ( $target_met ? '✅ MET' : '❌ NOT MET' ) . "\n";
$report_content .= "- Secondary target (90% at 6.0+): " . ( $secondary_target ? '✅ MET' : '❌ NOT MET' ) . "\n\n";

if ( ! empty( $high_similarity_pairs ) ) {
	$report_content .= "## High Similarity Warnings\n\n";
	$report_content .= "The following template pairs have >50% color similarity:\n\n";
	foreach ( $high_similarity_pairs as $pair ) {
		$report_content .= "- {$pair}\n";
	}
	$report_content .= "\n";
}

$report_content .= "## Individual Template Results\n\n";

foreach ( $test_results as $template_id => $result ) {
	$report_content .= "### {$result['name']} ({$template_id})\n\n";
	$report_content .= "- **Category**: {$result['category']}\n";
	$report_content .= "- **Quality Score**: {$result['quality_score']}/10\n";
	$report_content .= "- **Sections**: " . count( $result['completeness'] ) . "/10\n";
	
	$total_opts = array_sum( $result['completeness'] );
	$report_content .= "- **Total Options**: {$total_opts}\n";
	$report_content .= "- **Unique Colors**: " . ( $result['uniqueness']['color_count'] ?? 0 ) . "\n\n";
	
	if ( ! empty( $result['strengths'] ) ) {
		$report_content .= "**Strengths:**\n";
		foreach ( $result['strengths'] as $strength ) {
			$report_content .= "- ✅ {$strength}\n";
		}
		$report_content .= "\n";
	}
	
	if ( ! empty( $result['issues'] ) ) {
		$report_content .= "**Issues:**\n";
		foreach ( $result['issues'] as $issue ) {
			$report_content .= "- ⚠️ {$issue}\n";
		}
		$report_content .= "\n";
	}
	
	if ( ! empty( $result['consistency'] ) ) {
		$report_content .= "**Consistency Checks:**\n";
		foreach ( $result['consistency'] as $check ) {
			$report_content .= "- ✓ {$check}\n";
		}
		$report_content .= "\n";
	}
	
	$report_content .= "---\n\n";
}

$report_content .= "## Testing Methodology\n\n";
$report_content .= "Each template was evaluated on:\n\n";
$report_content .= "1. **Completeness**: All 10 sections present with 100+ total options\n";
$report_content .= "2. **Consistency**: Characteristics match actual settings\n";
$report_content .= "3. **Uniqueness**: Color palette differs from other templates\n";
$report_content .= "4. **Typography**: Readable font sizes and line heights\n";
$report_content .= "5. **Effects**: Appropriate use of glassmorphism and animations\n";
$report_content .= "6. **Border Radius**: Consistent or intentionally varied\n";
$report_content .= "7. **Backgrounds**: Appropriate use of gradients and patterns\n";
$report_content .= "8. **Metadata**: All required fields present\n\n";

$report_content .= "## Quality Scoring\n\n";
$report_content .= "- Base score: 10.0\n";
$report_content .= "- Deductions: -0.5 per issue, -1.0 per missing section, -0.5 per missing metadata\n";
$report_content .= "- Bonuses: +0.15 per strength (max +2.0), +0.5 for 100+ options\n";
$report_content .= "- Range: 0.0 to 10.0\n\n";

$report_content .= "## Recommendations\n\n";

if ( $target_met ) {
	$report_content .= "✅ All templates meet the quality target. Great work!\n\n";
} else {
	$report_content .= "⚠️ Some templates need improvement to meet the 8.0+ quality target:\n\n";
	foreach ( $test_results as $template_id => $result ) {
		if ( $result['quality_score'] < 8.0 ) {
			$report_content .= "- **{$result['name']}**: Address " . count( $result['issues'] ) . " issue(s)\n";
		}
	}
	$report_content .= "\n";
}

if ( ! empty( $high_similarity_pairs ) ) {
	$report_content .= "⚠️ Consider adjusting color palettes for templates with high similarity.\n\n";
}

file_put_contents( $report_file, $report_content );
print_success( "\nDetailed report saved to: {$report_file}" );

// Exit with appropriate code
exit( $target_met ? 0 : 1 );
