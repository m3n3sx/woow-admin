<?php
/**
 * Visual Quality Testing for All Palettes
 * 
 * This script performs comprehensive visual testing for all 10 color palettes:
 * - Applies each palette
 * - Verifies all sections are styled correctly
 * - Checks color consistency
 * - Verifies WCAG contrast requirements
 * - Rates visual quality
 * 
 * Requirements: 28.2, 28.3, 28.4, 28.5
 * 
 * @package WOOW_Admin
 */

// Load WordPress
require_once dirname( __FILE__ ) . '/../../../wp-load.php';

// Ensure we're in admin context
if ( ! is_admin() ) {
	define( 'WP_ADMIN', true );
}

// Load plugin files
require_once dirname( __FILE__ ) . '/woow-admin.php';

/**
 * WCAG Contrast Checker
 */
class WCAG_Contrast_Checker {
	
	/**
	 * Calculate relative luminance
	 */
	private static function get_luminance( $color ) {
		$color = str_replace( '#', '', $color );
		
		$r = hexdec( substr( $color, 0, 2 ) ) / 255;
		$g = hexdec( substr( $color, 2, 2 ) ) / 255;
		$b = hexdec( substr( $color, 4, 2 ) ) / 255;
		
		$r = ( $r <= 0.03928 ) ? $r / 12.92 : pow( ( $r + 0.055 ) / 1.055, 2.4 );
		$g = ( $g <= 0.03928 ) ? $g / 12.92 : pow( ( $g + 0.055 ) / 1.055, 2.4 );
		$b = ( $b <= 0.03928 ) ? $b / 12.92 : pow( ( $b + 0.055 ) / 1.055, 2.4 );
		
		return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
	}
	
	/**
	 * Calculate contrast ratio
	 */
	public static function get_contrast_ratio( $color1, $color2 ) {
		$l1 = self::get_luminance( $color1 );
		$l2 = self::get_luminance( $color2 );
		
		$lighter = max( $l1, $l2 );
		$darker = min( $l1, $l2 );
		
		return ( $lighter + 0.05 ) / ( $darker + 0.05 );
	}
	
	/**
	 * Check if contrast meets WCAG AA
	 */
	public static function meets_wcag_aa( $color1, $color2, $is_large_text = false ) {
		$ratio = self::get_contrast_ratio( $color1, $color2 );
		$required = $is_large_text ? 3.0 : 4.5;
		return $ratio >= $required;
	}
	
	/**
	 * Check if contrast meets WCAG AAA
	 */
	public static function meets_wcag_aaa( $color1, $color2, $is_large_text = false ) {
		$ratio = self::get_contrast_ratio( $color1, $color2 );
		$required = $is_large_text ? 4.5 : 7.0;
		return $ratio >= $required;
	}
}

/**
 * Visual Quality Tester
 */
class Palette_Visual_Tester {
	
	private $palette_manager;
	private $settings;
	private $results = array();
	
	public function __construct() {
		$this->settings = new WOOW_Settings();
		$this->palette_manager = new WOOW_Palette_Manager( $this->settings );
	}
	
	/**
	 * Test all palettes
	 */
	public function test_all_palettes() {
		echo "<h1>WOOW! Admin - Palette Visual Quality Testing</h1>\n";
		echo "<p>Testing all 10 color palettes for visual quality, color consistency, and WCAG compliance.</p>\n";
		echo "<hr>\n\n";
		
		$palettes = $this->palette_manager->get_all_palettes();
		
		foreach ( $palettes as $palette_id => $palette ) {
			$this->test_palette( $palette_id, $palette );
		}
		
		$this->print_summary();
	}
	
	/**
	 * Test individual palette
	 */
	private function test_palette( $palette_id, $palette ) {
		echo "<h2>Testing: {$palette['name']}</h2>\n";
		echo "<p><strong>ID:</strong> {$palette_id}</p>\n";
		echo "<p><strong>Category:</strong> {$palette['category']}</p>\n";
		echo "<p><strong>Description:</strong> {$palette['description']}</p>\n";
		
		$result = array(
			'id' => $palette_id,
			'name' => $palette['name'],
			'category' => $palette['category'],
			'tests' => array(),
			'score' => 0,
			'max_score' => 0,
		);
		
		// Test 1: Completeness
		$completeness = $this->test_completeness( $palette );
		$result['tests']['completeness'] = $completeness;
		$result['score'] += $completeness['passed'] ? 2 : 0;
		$result['max_score'] += 2;
		
		// Test 2: Color Consistency
		$consistency = $this->test_color_consistency( $palette );
		$result['tests']['consistency'] = $consistency;
		$result['score'] += $consistency['score'];
		$result['max_score'] += 2;
		
		// Test 3: WCAG Contrast
		$contrast = $this->test_wcag_contrast( $palette );
		$result['tests']['contrast'] = $contrast;
		$result['score'] += $contrast['passed'] ? 2 : 0;
		$result['max_score'] += 2;
		
		// Test 4: Visual Harmony
		$harmony = $this->test_visual_harmony( $palette );
		$result['tests']['harmony'] = $harmony;
		$result['score'] += $harmony['score'];
		$result['max_score'] += 2;
		
		// Test 5: Section Coverage
		$coverage = $this->test_section_coverage( $palette );
		$result['tests']['coverage'] = $coverage;
		$result['score'] += $coverage['passed'] ? 2 : 0;
		$result['max_score'] += 2;
		
		// Calculate quality rating (out of 10)
		$result['quality_rating'] = round( ( $result['score'] / $result['max_score'] ) * 10, 1 );
		
		$this->results[ $palette_id ] = $result;
		
		$this->print_palette_results( $result );
		
		echo "<hr>\n\n";
	}
	
	/**
	 * Test completeness - all sections present
	 */
	private function test_completeness( $palette ) {
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
		
		$missing = array();
		foreach ( $required_sections as $section ) {
			if ( ! isset( $palette['settings'][ $section ] ) ) {
				$missing[] = $section;
			}
		}
		
		$passed = empty( $missing );
		
		echo "<h3>✓ Test 1: Completeness</h3>\n";
		if ( $passed ) {
			echo "<p style='color: green;'><strong>PASSED:</strong> All 10 sections present</p>\n";
		} else {
			echo "<p style='color: red;'><strong>FAILED:</strong> Missing sections: " . implode( ', ', $missing ) . "</p>\n";
		}
		
		return array(
			'passed' => $passed,
			'missing' => $missing,
		);
	}
	
	/**
	 * Test color consistency
	 */
	private function test_color_consistency( $palette ) {
		echo "<h3>✓ Test 2: Color Consistency</h3>\n";
		
		$settings = $palette['settings'];
		$issues = array();
		$score = 2;
		
		// Check if primary colors are used consistently
		$primary = $settings['color_overrides']['primary_color'] ?? '';
		$secondary = $settings['color_overrides']['secondary_color'] ?? '';
		
		// Check admin bar uses palette colors
		if ( isset( $settings['admin_bar']['background_type'] ) && $settings['admin_bar']['background_type'] === 'gradient' ) {
			$gradient_start = $settings['admin_bar']['gradient_start'] ?? '';
			$gradient_end = $settings['admin_bar']['gradient_end'] ?? '';
			
			if ( $gradient_start !== $primary && $gradient_start !== $secondary ) {
				$issues[] = "Admin bar gradient doesn't use primary/secondary colors";
				$score -= 0.5;
			}
		}
		
		// Check button colors match palette
		$button_bg = $settings['buttons']['primary_bg'] ?? '';
		if ( $button_bg !== $primary && $button_bg !== $secondary ) {
			$issues[] = "Primary button doesn't use primary/secondary color";
			$score -= 0.5;
		}
		
		// Check active menu color
		$active_bg = $settings['admin_menu']['active_bg_color'] ?? '';
		if ( $active_bg !== $primary && $active_bg !== $secondary ) {
			$issues[] = "Active menu item doesn't use primary/secondary color";
			$score -= 0.5;
		}
		
		$score = max( 0, $score );
		
		if ( empty( $issues ) ) {
			echo "<p style='color: green;'><strong>PASSED:</strong> Colors are used consistently (Score: {$score}/2)</p>\n";
		} else {
			echo "<p style='color: orange;'><strong>PARTIAL:</strong> Some inconsistencies found (Score: {$score}/2)</p>\n";
			echo "<ul>\n";
			foreach ( $issues as $issue ) {
				echo "<li>{$issue}</li>\n";
			}
			echo "</ul>\n";
		}
		
		return array(
			'score' => $score,
			'issues' => $issues,
		);
	}
	
	/**
	 * Test WCAG contrast requirements
	 */
	private function test_wcag_contrast( $palette ) {
		echo "<h3>✓ Test 3: WCAG Contrast Compliance</h3>\n";
		
		$settings = $palette['settings'];
		$failures = array();
		
		// Test admin bar text on background
		$admin_bar_text = $settings['admin_bar']['text_color'] ?? '#ffffff';
		$admin_bar_bg = $settings['admin_bar']['background_color'] ?? '#000000';
		
		$ratio = WCAG_Contrast_Checker::get_contrast_ratio( $admin_bar_text, $admin_bar_bg );
		$meets_aa = WCAG_Contrast_Checker::meets_wcag_aa( $admin_bar_text, $admin_bar_bg );
		
		echo "<p><strong>Admin Bar:</strong> Text {$admin_bar_text} on {$admin_bar_bg} = " . number_format( $ratio, 2 ) . ":1 ";
		if ( $meets_aa ) {
			echo "<span style='color: green;'>✓ WCAG AA</span></p>\n";
		} else {
			echo "<span style='color: red;'>✗ FAILS WCAG AA</span></p>\n";
			$failures[] = "Admin bar text contrast: " . number_format( $ratio, 2 ) . ":1 (needs 4.5:1)";
		}
		
		// Test admin menu text on background
		$menu_text = $settings['admin_menu']['text_color'] ?? '#ffffff';
		$menu_bg = $settings['admin_menu']['background_color'] ?? '#000000';
		
		$ratio = WCAG_Contrast_Checker::get_contrast_ratio( $menu_text, $menu_bg );
		$meets_aa = WCAG_Contrast_Checker::meets_wcag_aa( $menu_text, $menu_bg );
		
		echo "<p><strong>Admin Menu:</strong> Text {$menu_text} on {$menu_bg} = " . number_format( $ratio, 2 ) . ":1 ";
		if ( $meets_aa ) {
			echo "<span style='color: green;'>✓ WCAG AA</span></p>\n";
		} else {
			echo "<span style='color: red;'>✗ FAILS WCAG AA</span></p>\n";
			$failures[] = "Admin menu text contrast: " . number_format( $ratio, 2 ) . ":1 (needs 4.5:1)";
		}
		
		// Test body text on background
		$body_text = $settings['typography']['body_color'] ?? '#000000';
		$body_bg = $settings['backgrounds']['body_bg'] ?? '#ffffff';
		
		$ratio = WCAG_Contrast_Checker::get_contrast_ratio( $body_text, $body_bg );
		$meets_aa = WCAG_Contrast_Checker::meets_wcag_aa( $body_text, $body_bg );
		
		echo "<p><strong>Body Text:</strong> Text {$body_text} on {$body_bg} = " . number_format( $ratio, 2 ) . ":1 ";
		if ( $meets_aa ) {
			echo "<span style='color: green;'>✓ WCAG AA</span></p>\n";
		} else {
			echo "<span style='color: red;'>✗ FAILS WCAG AA</span></p>\n";
			$failures[] = "Body text contrast: " . number_format( $ratio, 2 ) . ":1 (needs 4.5:1)";
		}
		
		// Test button text on background
		$button_text = $settings['buttons']['primary_text'] ?? '#ffffff';
		$button_bg = $settings['buttons']['primary_bg'] ?? '#000000';
		
		$ratio = WCAG_Contrast_Checker::get_contrast_ratio( $button_text, $button_bg );
		$meets_aa = WCAG_Contrast_Checker::meets_wcag_aa( $button_text, $button_bg );
		
		echo "<p><strong>Primary Button:</strong> Text {$button_text} on {$button_bg} = " . number_format( $ratio, 2 ) . ":1 ";
		if ( $meets_aa ) {
			echo "<span style='color: green;'>✓ WCAG AA</span></p>\n";
		} else {
			echo "<span style='color: red;'>✗ FAILS WCAG AA</span></p>\n";
			$failures[] = "Button text contrast: " . number_format( $ratio, 2 ) . ":1 (needs 4.5:1)";
		}
		
		$passed = empty( $failures );
		
		if ( $passed ) {
			echo "<p style='color: green;'><strong>PASSED:</strong> All contrast ratios meet WCAG AA</p>\n";
		} else {
			echo "<p style='color: red;'><strong>FAILED:</strong> Some contrast ratios fail WCAG AA</p>\n";
		}
		
		return array(
			'passed' => $passed,
			'failures' => $failures,
		);
	}
	
	/**
	 * Test visual harmony
	 */
	private function test_visual_harmony( $palette ) {
		echo "<h3>✓ Test 4: Visual Harmony</h3>\n";
		
		$settings = $palette['settings'];
		$score = 2;
		$issues = array();
		
		// Check if glassmorphism is used consistently
		$admin_bar_glass = $settings['admin_bar']['glassmorphism'] ?? false;
		$effects_glass = $settings['effects']['glassmorphism_enabled'] ?? false;
		
		if ( $admin_bar_glass !== $effects_glass ) {
			$issues[] = "Glassmorphism settings inconsistent between admin bar and effects";
			$score -= 0.5;
		}
		
		// Check if border radius is consistent
		$admin_bar_radius = (int) ( $settings['admin_bar']['border_radius_all'] ?? 0 );
		$menu_radius = (int) ( $settings['admin_menu']['border_radius'] ?? 0 );
		$widget_radius = (int) ( $settings['dashboard_widgets']['border_radius'] ?? 0 );
		
		$radius_variance = max( $admin_bar_radius, $menu_radius, $widget_radius ) - min( $admin_bar_radius, $menu_radius, $widget_radius );
		
		if ( $radius_variance > 16 ) {
			$issues[] = "Border radius varies significantly across sections (variance: {$radius_variance}px)";
			$score -= 0.5;
		}
		
		// Check if font weights are appropriate
		$body_weight = (int) ( $settings['typography']['heading_weight'] ?? 400 );
		if ( $body_weight < 600 ) {
			$issues[] = "Heading font weight might be too light for good hierarchy";
			$score -= 0.25;
		}
		
		$score = max( 0, $score );
		
		if ( empty( $issues ) ) {
			echo "<p style='color: green;'><strong>PASSED:</strong> Visual elements are harmonious (Score: {$score}/2)</p>\n";
		} else {
			echo "<p style='color: orange;'><strong>PARTIAL:</strong> Some harmony issues found (Score: {$score}/2)</p>\n";
			echo "<ul>\n";
			foreach ( $issues as $issue ) {
				echo "<li>{$issue}</li>\n";
			}
			echo "</ul>\n";
		}
		
		return array(
			'score' => $score,
			'issues' => $issues,
		);
	}
	
	/**
	 * Test section coverage
	 */
	private function test_section_coverage( $palette ) {
		echo "<h3>✓ Test 5: Section Coverage</h3>\n";
		
		$settings = $palette['settings'];
		$expected_counts = array(
			'color_overrides' => 7,
			'admin_bar' => 25,
			'admin_menu' => 15,
			'dashboard_widgets' => 10,
			'form_controls' => 10,
			'buttons' => 10,
			'backgrounds' => 6,
			'typography' => 10,
			'effects' => 8,
			'login_page' => 10,
		);
		
		$issues = array();
		$total_expected = 0;
		$total_actual = 0;
		
		foreach ( $expected_counts as $section => $expected ) {
			$actual = isset( $settings[ $section ] ) ? count( $settings[ $section ] ) : 0;
			$total_expected += $expected;
			$total_actual += $actual;
			
			if ( $actual < $expected ) {
				$issues[] = "{$section}: {$actual}/{$expected} options";
			}
		}
		
		$coverage_percent = ( $total_actual / $total_expected ) * 100;
		$passed = $coverage_percent >= 95;
		
		echo "<p><strong>Total Coverage:</strong> {$total_actual}/{$total_expected} options (" . number_format( $coverage_percent, 1 ) . "%)</p>\n";
		
		if ( $passed ) {
			echo "<p style='color: green;'><strong>PASSED:</strong> Excellent section coverage</p>\n";
		} else {
			echo "<p style='color: orange;'><strong>PARTIAL:</strong> Some sections incomplete</p>\n";
			if ( ! empty( $issues ) ) {
				echo "<ul>\n";
				foreach ( $issues as $issue ) {
					echo "<li>{$issue}</li>\n";
				}
				echo "</ul>\n";
			}
		}
		
		return array(
			'passed' => $passed,
			'coverage_percent' => $coverage_percent,
			'issues' => $issues,
		);
	}
	
	/**
	 * Print palette results
	 */
	private function print_palette_results( $result ) {
		$rating = $result['quality_rating'];
		$color = $rating >= 8 ? 'green' : ( $rating >= 6 ? 'orange' : 'red' );
		
		echo "<h3 style='color: {$color};'>Overall Quality Rating: {$rating}/10</h3>\n";
		
		if ( $rating >= 8 ) {
			echo "<p style='color: green;'><strong>✓ EXCELLENT:</strong> Meets target quality (8/10+)</p>\n";
		} elseif ( $rating >= 6 ) {
			echo "<p style='color: orange;'><strong>⚠ GOOD:</strong> Close to target, minor improvements needed</p>\n";
		} else {
			echo "<p style='color: red;'><strong>✗ NEEDS WORK:</strong> Below target quality</p>\n";
		}
	}
	
	/**
	 * Print summary
	 */
	private function print_summary() {
		echo "<h2>Summary Report</h2>\n";
		echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>\n";
		echo "<tr style='background: #f0f0f0;'>\n";
		echo "<th>Palette</th>\n";
		echo "<th>Category</th>\n";
		echo "<th>Quality Rating</th>\n";
		echo "<th>Completeness</th>\n";
		echo "<th>WCAG Contrast</th>\n";
		echo "<th>Status</th>\n";
		echo "</tr>\n";
		
		$total_rating = 0;
		$count = 0;
		$passed_count = 0;
		
		foreach ( $this->results as $result ) {
			$rating = $result['quality_rating'];
			$color = $rating >= 8 ? 'green' : ( $rating >= 6 ? 'orange' : 'red' );
			$status = $rating >= 8 ? '✓ PASS' : ( $rating >= 6 ? '⚠ PARTIAL' : '✗ FAIL' );
			
			$completeness = $result['tests']['completeness']['passed'] ? '✓' : '✗';
			$contrast = $result['tests']['contrast']['passed'] ? '✓' : '✗';
			
			echo "<tr>\n";
			echo "<td><strong>{$result['name']}</strong></td>\n";
			echo "<td>{$result['category']}</td>\n";
			echo "<td style='color: {$color};'><strong>{$rating}/10</strong></td>\n";
			echo "<td>{$completeness}</td>\n";
			echo "<td>{$contrast}</td>\n";
			echo "<td style='color: {$color};'><strong>{$status}</strong></td>\n";
			echo "</tr>\n";
			
			$total_rating += $rating;
			$count++;
			if ( $rating >= 8 ) {
				$passed_count++;
			}
		}
		
		echo "</table>\n\n";
		
		$average_rating = $count > 0 ? $total_rating / $count : 0;
		$pass_rate = $count > 0 ? ( $passed_count / $count ) * 100 : 0;
		
		echo "<h3>Overall Statistics</h3>\n";
		echo "<p><strong>Total Palettes Tested:</strong> {$count}</p>\n";
		echo "<p><strong>Average Quality Rating:</strong> " . number_format( $average_rating, 1 ) . "/10</p>\n";
		echo "<p><strong>Palettes Meeting Target (8/10+):</strong> {$passed_count}/{$count} (" . number_format( $pass_rate, 1 ) . "%)</p>\n";
		
		if ( $passed_count === $count ) {
			echo "<p style='color: green; font-size: 18px;'><strong>✓ ALL PALETTES PASS QUALITY REQUIREMENTS!</strong></p>\n";
		} elseif ( $pass_rate >= 80 ) {
			echo "<p style='color: orange; font-size: 18px;'><strong>⚠ MOST PALETTES PASS - MINOR IMPROVEMENTS NEEDED</strong></p>\n";
		} else {
			echo "<p style='color: red; font-size: 18px;'><strong>✗ SIGNIFICANT IMPROVEMENTS NEEDED</strong></p>\n";
		}
	}
}

// Run tests
$tester = new Palette_Visual_Tester();
$tester->test_all_palettes();
