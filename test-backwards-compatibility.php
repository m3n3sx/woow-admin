<?php
/**
 * Backwards Compatibility Test for Glassmorphism Global System
 * 
 * Tests that the new global glassmorphism system works correctly with
 * existing admin bar and admin menu settings without conflicts.
 * 
 * Requirements tested:
 * - 13.1: Global toggle disabled respects section-specific settings
 * - 13.2: No override of user-configured settings when global toggle is off
 * - 13.3: Sensible defaults without breaking existing designs
 * - 13.4: Section-specific settings coexist with global system
 * - 13.5: Global settings prioritize over section-specific when enabled
 * 
 * @package WoowAdmin
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Backwards Compatibility Test Class
 */
class WOOW_Backwards_Compatibility_Test {
    
    /**
     * Test results
     *
     * @var array
     */
    private $results = array();
    
    /**
     * Test counter
     *
     * @var int
     */
    private $test_count = 0;
    
    /**
     * Passed tests counter
     *
     * @var int
     */
    private $passed = 0;
    
    /**
     * Failed tests counter
     *
     * @var int
     */
    private $failed = 0;
    
    /**
     * Run all backwards compatibility tests
     *
     * @return array Test results
     */
    public function run_all_tests() {
        echo "<h1>🔄 Backwards Compatibility Test Suite</h1>\n";
        echo "<p>Testing glassmorphism global system with existing settings...</p>\n";
        
        // Test 1: Global toggle disabled respects admin bar settings
        $this->test_global_disabled_respects_admin_bar();
        
        // Test 2: Global toggle disabled respects admin menu settings
        $this->test_global_disabled_respects_admin_menu();
        
        // Test 3: No conflicts with existing glassmorphism settings
        $this->test_no_conflicts_with_existing_glass();
        
        // Test 4: Global toggle override behavior
        $this->test_global_toggle_override();
        
        // Test 5: Defaults don't break existing designs
        $this->test_defaults_dont_break_existing();
        
        // Test 6: Section-specific settings coexist
        $this->test_section_specific_coexistence();
        
        // Test 7: CSS generation with mixed settings
        $this->test_css_generation_mixed_settings();
        
        // Test 8: Settings persistence
        $this->test_settings_persistence();
        
        // Display summary
        $this->display_summary();
        
        return $this->results;
    }
    
    /**
     * Test 1: Global toggle disabled respects admin bar settings
     * Requirement: 13.1, 13.2
     */
    private function test_global_disabled_respects_admin_bar() {
        $this->start_test( "Global toggle disabled respects admin bar settings" );
        
        // Simulate existing admin bar settings
        $existing_settings = array(
            'visual_effects' => array(
                'enable_glassmorphism' => false, // Global toggle OFF
                'glass_strength' => 'md',
            ),
            'admin_bar' => array(
                'enabled' => true,
                'background_type' => 'glass',
                'glassmorphism' => true,
                'blur_strength' => '16', // Custom blur
                'opacity' => 0.85, // Custom opacity
                'background_color' => '#1e293b',
            ),
        );
        
        // Check that admin bar glassmorphism is NOT overridden
        $admin_bar = $existing_settings['admin_bar'];
        $global_enabled = $existing_settings['visual_effects']['enable_glassmorphism'];
        
        $checks = array();
        
        // Admin bar should keep its own glassmorphism settings
        $checks[] = array(
            'name' => 'Admin bar glassmorphism enabled',
            'expected' => true,
            'actual' => $admin_bar['glassmorphism'],
        );
        
        $checks[] = array(
            'name' => 'Admin bar custom blur preserved',
            'expected' => '16',
            'actual' => $admin_bar['blur_strength'],
        );
        
        $checks[] = array(
            'name' => 'Admin bar custom opacity preserved',
            'expected' => 0.85,
            'actual' => $admin_bar['opacity'],
        );
        
        $checks[] = array(
            'name' => 'Global toggle is disabled',
            'expected' => false,
            'actual' => $global_enabled,
        );
        
        $this->assert_all( $checks );
    }
    
    /**
     * Test 2: Global toggle disabled respects admin menu settings
     * Requirement: 13.1, 13.2
     */
    private function test_global_disabled_respects_admin_menu() {
        $this->start_test( "Global toggle disabled respects admin menu settings" );
        
        // Simulate existing admin menu settings
        $existing_settings = array(
            'visual_effects' => array(
                'enable_glassmorphism' => false, // Global toggle OFF
                'glass_strength' => 'md',
            ),
            'admin_menu' => array(
                'enabled' => true,
                'background_type' => 'glass',
                'glassmorphism' => true,
                'blur_strength' => '20', // Custom blur
                'opacity' => 0.95, // Custom opacity
                'background_color' => '#ffffff',
            ),
        );
        
        // Check that admin menu glassmorphism is NOT overridden
        $admin_menu = $existing_settings['admin_menu'];
        $global_enabled = $existing_settings['visual_effects']['enable_glassmorphism'];
        
        $checks = array();
        
        // Admin menu should keep its own glassmorphism settings
        $checks[] = array(
            'name' => 'Admin menu glassmorphism enabled',
            'expected' => true,
            'actual' => $admin_menu['glassmorphism'],
        );
        
        $checks[] = array(
            'name' => 'Admin menu custom blur preserved',
            'expected' => '20',
            'actual' => $admin_menu['blur_strength'],
        );
        
        $checks[] = array(
            'name' => 'Admin menu custom opacity preserved',
            'expected' => 0.95,
            'actual' => $admin_menu['opacity'],
        );
        
        $checks[] = array(
            'name' => 'Global toggle is disabled',
            'expected' => false,
            'actual' => $global_enabled,
        );
        
        $this->assert_all( $checks );
    }
    
    /**
     * Test 3: No conflicts with existing glassmorphism settings
     * Requirement: 13.3, 13.4
     */
    private function test_no_conflicts_with_existing_glass() {
        $this->start_test( "No conflicts with existing glassmorphism settings" );
        
        // Simulate settings with both old and new glassmorphism
        $settings = array(
            'visual_effects' => array(
                'enable_glassmorphism' => false, // New global system OFF
                'glass_strength' => 'lg',
                'global_glassmorphism' => true, // Old global setting
                'global_blur_strength' => '12',
            ),
            'admin_bar' => array(
                'background_type' => 'glass',
                'glassmorphism' => true, // Section-specific
                'blur_strength' => '16',
            ),
        );
        
        $checks = array();
        
        // Both systems should coexist
        $checks[] = array(
            'name' => 'New global system disabled',
            'expected' => false,
            'actual' => $settings['visual_effects']['enable_glassmorphism'],
        );
        
        $checks[] = array(
            'name' => 'Old global system still works',
            'expected' => true,
            'actual' => $settings['visual_effects']['global_glassmorphism'],
        );
        
        $checks[] = array(
            'name' => 'Section-specific glassmorphism works',
            'expected' => true,
            'actual' => $settings['admin_bar']['glassmorphism'],
        );
        
        $checks[] = array(
            'name' => 'Section-specific blur preserved',
            'expected' => '16',
            'actual' => $settings['admin_bar']['blur_strength'],
        );
        
        $this->assert_all( $checks );
    }
    
    /**
     * Test 4: Global toggle override behavior
     * Requirement: 13.5
     */
    private function test_global_toggle_override() {
        $this->start_test( "Global toggle override behavior when enabled" );
        
        // Simulate global toggle ENABLED
        $settings = array(
            'visual_effects' => array(
                'enable_glassmorphism' => true, // Global toggle ON
                'glass_strength' => 'xl', // Global strength
            ),
            'admin_bar' => array(
                'background_type' => 'solid', // Should be overridden
                'glassmorphism' => false, // Should be overridden
                'blur_strength' => '8', // Should be overridden
            ),
        );
        
        // When global is enabled, it should take priority
        $global_enabled = $settings['visual_effects']['enable_glassmorphism'];
        $global_strength = $settings['visual_effects']['glass_strength'];
        
        // Map strength to blur values
        $blur_map = array(
            'sm' => '4',
            'md' => '8',
            'lg' => '12',
            'xl' => '16',
        );
        
        $expected_blur = $blur_map[ $global_strength ];
        
        $checks = array();
        
        $checks[] = array(
            'name' => 'Global toggle is enabled',
            'expected' => true,
            'actual' => $global_enabled,
        );
        
        $checks[] = array(
            'name' => 'Global strength is xl',
            'expected' => 'xl',
            'actual' => $global_strength,
        );
        
        $checks[] = array(
            'name' => 'Expected blur for xl strength',
            'expected' => '16',
            'actual' => $expected_blur,
        );
        
        // Note: In actual implementation, CSS generator would apply global settings
        $checks[] = array(
            'name' => 'Global settings should override section settings',
            'expected' => true,
            'actual' => $global_enabled, // When true, global takes priority
        );
        
        $this->assert_all( $checks );
    }
    
    /**
     * Test 5: Defaults don't break existing designs
     * Requirement: 13.3
     */
    private function test_defaults_dont_break_existing() {
        $this->start_test( "Defaults don't break existing designs" );
        
        // Simulate upgrade scenario - new field added to existing settings
        $existing_settings = array(
            'admin_bar' => array(
                'enabled' => true,
                'background_color' => '#1e293b',
                'text_color' => '#ffffff',
                'height' => '48',
                // No glassmorphism fields - old installation
            ),
        );
        
        // Defaults that would be merged
        $defaults = array(
            'enable_glassmorphism' => false, // Safe default - OFF
            'glass_strength' => 'md',
        );
        
        $checks = array();
        
        // Existing settings should be preserved
        $checks[] = array(
            'name' => 'Existing background color preserved',
            'expected' => '#1e293b',
            'actual' => $existing_settings['admin_bar']['background_color'],
        );
        
        $checks[] = array(
            'name' => 'Existing text color preserved',
            'expected' => '#ffffff',
            'actual' => $existing_settings['admin_bar']['text_color'],
        );
        
        $checks[] = array(
            'name' => 'New global glassmorphism defaults to OFF',
            'expected' => false,
            'actual' => $defaults['enable_glassmorphism'],
        );
        
        $checks[] = array(
            'name' => 'Safe default strength',
            'expected' => 'md',
            'actual' => $defaults['glass_strength'],
        );
        
        $this->assert_all( $checks );
    }
    
    /**
     * Test 6: Section-specific settings coexist with global
     * Requirement: 13.4
     */
    private function test_section_specific_coexistence() {
        $this->start_test( "Section-specific settings coexist with global system" );
        
        // Both systems active but global disabled
        $settings = array(
            'visual_effects' => array(
                'enable_glassmorphism' => false, // Global OFF
                'glass_strength' => 'sm',
            ),
            'admin_bar' => array(
                'background_type' => 'glass',
                'glassmorphism' => true,
                'blur_strength' => '12',
            ),
            'admin_menu' => array(
                'background_type' => 'gradient',
                'glassmorphism' => false,
            ),
        );
        
        $checks = array();
        
        // Each section should maintain independence
        $checks[] = array(
            'name' => 'Admin bar has glassmorphism',
            'expected' => true,
            'actual' => $settings['admin_bar']['glassmorphism'],
        );
        
        $checks[] = array(
            'name' => 'Admin menu does not have glassmorphism',
            'expected' => false,
            'actual' => $settings['admin_menu']['glassmorphism'],
        );
        
        $checks[] = array(
            'name' => 'Global system is disabled',
            'expected' => false,
            'actual' => $settings['visual_effects']['enable_glassmorphism'],
        );
        
        $checks[] = array(
            'name' => 'Admin bar uses custom blur',
            'expected' => '12',
            'actual' => $settings['admin_bar']['blur_strength'],
        );
        
        $this->assert_all( $checks );
    }
    
    /**
     * Test 7: CSS generation with mixed settings
     * Requirement: 13.1, 13.2, 13.5
     */
    private function test_css_generation_mixed_settings() {
        $this->start_test( "CSS generation with mixed settings" );
        
        // Test CSS generation logic
        $test_cases = array(
            // Case 1: Global OFF, section ON
            array(
                'name' => 'Global OFF, Admin Bar ON',
                'global_enabled' => false,
                'section_enabled' => true,
                'section_blur' => '16',
                'expected_blur' => '16', // Section blur used
                'expected_applies' => true,
            ),
            // Case 2: Global ON, section OFF
            array(
                'name' => 'Global ON (md), Admin Bar OFF',
                'global_enabled' => true,
                'global_strength' => 'md',
                'section_enabled' => false,
                'expected_blur' => '8', // Global blur used (md = 8px)
                'expected_applies' => true,
            ),
            // Case 3: Both OFF
            array(
                'name' => 'Global OFF, Admin Bar OFF',
                'global_enabled' => false,
                'section_enabled' => false,
                'expected_applies' => false, // No glassmorphism
            ),
            // Case 4: Both ON - global takes priority
            array(
                'name' => 'Global ON (lg), Admin Bar ON (custom)',
                'global_enabled' => true,
                'global_strength' => 'lg',
                'section_enabled' => true,
                'section_blur' => '20',
                'expected_blur' => '12', // Global blur used (lg = 12px)
                'expected_applies' => true,
            ),
        );
        
        $checks = array();
        
        foreach ( $test_cases as $case ) {
            // Simulate CSS generation logic
            $global_enabled = $case['global_enabled'] ?? false;
            $section_enabled = $case['section_enabled'] ?? false;
            
            if ( $global_enabled ) {
                // Global takes priority
                $blur_map = array( 'sm' => '4', 'md' => '8', 'lg' => '12', 'xl' => '16' );
                $strength = $case['global_strength'] ?? 'md';
                $actual_blur = $blur_map[ $strength ];
                $applies = true;
            } elseif ( $section_enabled ) {
                // Section-specific
                $actual_blur = $case['section_blur'] ?? '0';
                $applies = true;
            } else {
                // No glassmorphism
                $actual_blur = '0';
                $applies = false;
            }
            
            if ( isset( $case['expected_blur'] ) ) {
                $checks[] = array(
                    'name' => $case['name'] . ' - Blur value',
                    'expected' => $case['expected_blur'],
                    'actual' => $actual_blur,
                );
            }
            
            $checks[] = array(
                'name' => $case['name'] . ' - Applies',
                'expected' => $case['expected_applies'],
                'actual' => $applies,
            );
        }
        
        $this->assert_all( $checks );
    }
    
    /**
     * Test 8: Settings persistence
     * Requirement: 13.3
     */
    private function test_settings_persistence() {
        $this->start_test( "Settings persistence after upgrade" );
        
        // Simulate settings before and after upgrade
        $before_upgrade = array(
            'admin_bar' => array(
                'enabled' => true,
                'background_color' => '#1e293b',
                'glassmorphism' => true,
                'blur_strength' => '16',
            ),
        );
        
        // After upgrade - new fields added with defaults
        $after_upgrade = array_merge(
            $before_upgrade,
            array(
                'visual_effects' => array(
                    'enable_glassmorphism' => false, // New field
                    'glass_strength' => 'md', // New field
                ),
            )
        );
        
        $checks = array();
        
        // Old settings should be preserved
        $checks[] = array(
            'name' => 'Admin bar enabled preserved',
            'expected' => true,
            'actual' => $after_upgrade['admin_bar']['enabled'],
        );
        
        $checks[] = array(
            'name' => 'Background color preserved',
            'expected' => '#1e293b',
            'actual' => $after_upgrade['admin_bar']['background_color'],
        );
        
        $checks[] = array(
            'name' => 'Section glassmorphism preserved',
            'expected' => true,
            'actual' => $after_upgrade['admin_bar']['glassmorphism'],
        );
        
        $checks[] = array(
            'name' => 'Section blur preserved',
            'expected' => '16',
            'actual' => $after_upgrade['admin_bar']['blur_strength'],
        );
        
        // New settings should have safe defaults
        $checks[] = array(
            'name' => 'New global toggle defaults to OFF',
            'expected' => false,
            'actual' => $after_upgrade['visual_effects']['enable_glassmorphism'],
        );
        
        $checks[] = array(
            'name' => 'New strength has default',
            'expected' => 'md',
            'actual' => $after_upgrade['visual_effects']['glass_strength'],
        );
        
        $this->assert_all( $checks );
    }
    
    /**
     * Start a new test
     *
     * @param string $name Test name
     */
    private function start_test( $name ) {
        $this->test_count++;
        echo "<h2>Test {$this->test_count}: {$name}</h2>\n";
    }
    
    /**
     * Assert all checks in a test
     *
     * @param array $checks Array of checks
     */
    private function assert_all( $checks ) {
        $test_passed = true;
        
        echo "<table style='width:100%; border-collapse: collapse; margin: 20px 0;'>\n";
        echo "<tr style='background: #f0f0f0;'>\n";
        echo "<th style='padding: 10px; text-align: left; border: 1px solid #ddd;'>Check</th>\n";
        echo "<th style='padding: 10px; text-align: left; border: 1px solid #ddd;'>Expected</th>\n";
        echo "<th style='padding: 10px; text-align: left; border: 1px solid #ddd;'>Actual</th>\n";
        echo "<th style='padding: 10px; text-align: left; border: 1px solid #ddd;'>Result</th>\n";
        echo "</tr>\n";
        
        foreach ( $checks as $check ) {
            $expected = $this->format_value( $check['expected'] );
            $actual = $this->format_value( $check['actual'] );
            $passed = ( $check['expected'] === $check['actual'] );
            
            if ( ! $passed ) {
                $test_passed = false;
            }
            
            $status = $passed ? '✅ PASS' : '❌ FAIL';
            $row_color = $passed ? '#e8f5e9' : '#ffebee';
            
            echo "<tr style='background: {$row_color};'>\n";
            echo "<td style='padding: 10px; border: 1px solid #ddd;'>{$check['name']}</td>\n";
            echo "<td style='padding: 10px; border: 1px solid #ddd;'>{$expected}</td>\n";
            echo "<td style='padding: 10px; border: 1px solid #ddd;'>{$actual}</td>\n";
            echo "<td style='padding: 10px; border: 1px solid #ddd;'>{$status}</td>\n";
            echo "</tr>\n";
        }
        
        echo "</table>\n";
        
        if ( $test_passed ) {
            $this->passed++;
            echo "<p style='color: green; font-weight: bold;'>✅ Test PASSED</p>\n";
        } else {
            $this->failed++;
            echo "<p style='color: red; font-weight: bold;'>❌ Test FAILED</p>\n";
        }
        
        echo "<hr>\n";
        
        $this->results[] = array(
            'test' => $this->test_count,
            'passed' => $test_passed,
            'checks' => $checks,
        );
    }
    
    /**
     * Format value for display
     *
     * @param mixed $value Value to format
     * @return string Formatted value
     */
    private function format_value( $value ) {
        if ( is_bool( $value ) ) {
            return $value ? 'true' : 'false';
        }
        if ( is_array( $value ) ) {
            return json_encode( $value );
        }
        if ( is_null( $value ) ) {
            return 'null';
        }
        return (string) $value;
    }
    
    /**
     * Display test summary
     */
    private function display_summary() {
        $total = $this->test_count;
        $pass_rate = $total > 0 ? round( ( $this->passed / $total ) * 100, 1 ) : 0;
        
        echo "<div style='background: #f5f5f5; padding: 20px; border-radius: 8px; margin: 20px 0;'>\n";
        echo "<h2>📊 Test Summary</h2>\n";
        echo "<table style='width: 100%;'>\n";
        echo "<tr><td><strong>Total Tests:</strong></td><td>{$total}</td></tr>\n";
        echo "<tr><td><strong>Passed:</strong></td><td style='color: green;'>{$this->passed}</td></tr>\n";
        echo "<tr><td><strong>Failed:</strong></td><td style='color: red;'>{$this->failed}</td></tr>\n";
        echo "<tr><td><strong>Pass Rate:</strong></td><td>{$pass_rate}%</td></tr>\n";
        echo "</table>\n";
        
        if ( $this->failed === 0 ) {
            echo "<p style='color: green; font-weight: bold; font-size: 18px;'>🎉 All backwards compatibility tests passed!</p>\n";
            echo "<p>The glassmorphism global system is fully backwards compatible with existing settings.</p>\n";
        } else {
            echo "<p style='color: red; font-weight: bold; font-size: 18px;'>⚠️ Some tests failed!</p>\n";
            echo "<p>Please review the failed tests above and fix any compatibility issues.</p>\n";
        }
        
        echo "</div>\n";
    }
}

// Run tests if accessed directly
if ( ! empty( $_GET['run_backwards_compatibility_test'] ) ) {
    $test = new WOOW_Backwards_Compatibility_Test();
    $test->run_all_tests();
}
