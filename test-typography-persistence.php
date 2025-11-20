<?php
/**
 * Test Typography Settings Persistence
 * 
 * This test verifies that font settings save and load correctly through
 * the existing save mechanism.
 * 
 * Requirements tested:
 * - 1.4: Font selection persistence
 * - 2.5: Heading font persistence
 * - 4.4: Weight selection persistence
 * - 6.3: Reset functionality
 * - 6.5: Settings load correctly
 * 
 * @package WoowAdmin
 */

// Load WordPress
require_once dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/wp-load.php';

// Load plugin files
require_once __DIR__ . '/includes/class-woow-settings.php';
require_once __DIR__ . '/includes/class-woow-google-fonts.php';

/**
 * Test class for typography persistence
 */
class WOOW_Typography_Persistence_Test {
    private $settings;
    private $test_results = array();
    private $original_settings;
    
    public function __construct() {
        $this->settings = new WOOW_Settings();
        // Backup original settings
        $this->original_settings = $this->settings->get_all();
    }
    
    /**
     * Run all tests
     */
    public function run_all_tests() {
        echo "<h1>Typography Settings Persistence Tests</h1>\n";
        echo "<p>Testing Requirements: 1.4, 2.5, 4.4, 6.3, 6.5</p>\n";
        echo "<hr>\n";
        
        $this->test_body_font_persistence();
        $this->test_heading_font_persistence();
        $this->test_weight_persistence();
        $this->test_system_default_persistence();
        $this->test_reset_to_system_default();
        $this->test_load_on_tab_access();
        $this->test_combined_font_settings();
        
        $this->print_summary();
        
        // Restore original settings
        $this->restore_original_settings();
    }
    
    /**
     * Test 1: Body font persistence (Requirement 1.4)
     */
    private function test_body_font_persistence() {
        echo "<h2>Test 1: Body Font Persistence (Req 1.4)</h2>\n";
        
        $test_font = 'Inter';
        $test_weights = array( 400, 600, 700 );
        
        // Save body font settings
        $settings = array(
            'typography' => array(
                'body_font' => $test_font,
                'body_weights' => $test_weights,
            ),
        );
        
        $save_result = $this->settings->save_settings( $settings );
        
        if ( ! $save_result ) {
            $this->record_failure( 'Body Font Persistence', 'Failed to save body font settings' );
            return;
        }
        
        // Reload settings from database
        $this->settings = new WOOW_Settings();
        $loaded_settings = $this->settings->get_section( 'typography' );
        
        // Verify body font
        if ( $loaded_settings['body_font'] !== $test_font ) {
            $this->record_failure( 
                'Body Font Persistence', 
                "Expected body_font '$test_font', got '{$loaded_settings['body_font']}'" 
            );
            return;
        }
        
        // Verify body weights
        if ( $loaded_settings['body_weights'] !== $test_weights ) {
            $this->record_failure( 
                'Body Font Persistence', 
                'Body weights do not match. Expected: ' . print_r( $test_weights, true ) . 
                ', Got: ' . print_r( $loaded_settings['body_weights'], true )
            );
            return;
        }
        
        $this->record_success( 'Body Font Persistence', 'Body font and weights persisted correctly' );
    }
    
    /**
     * Test 2: Heading font persistence (Requirement 2.5)
     */
    private function test_heading_font_persistence() {
        echo "<h2>Test 2: Heading Font Persistence (Req 2.5)</h2>\n";
        
        $test_font = 'Playfair Display';
        $test_weights = array( 600, 700, 800 );
        
        // Save heading font settings
        $settings = array(
            'typography' => array(
                'heading_font' => $test_font,
                'heading_weights' => $test_weights,
            ),
        );
        
        $save_result = $this->settings->save_settings( $settings );
        
        if ( ! $save_result ) {
            $this->record_failure( 'Heading Font Persistence', 'Failed to save heading font settings' );
            return;
        }
        
        // Reload settings from database
        $this->settings = new WOOW_Settings();
        $loaded_settings = $this->settings->get_section( 'typography' );
        
        // Verify heading font
        if ( $loaded_settings['heading_font'] !== $test_font ) {
            $this->record_failure( 
                'Heading Font Persistence', 
                "Expected heading_font '$test_font', got '{$loaded_settings['heading_font']}'" 
            );
            return;
        }
        
        // Verify heading weights
        if ( $loaded_settings['heading_weights'] !== $test_weights ) {
            $this->record_failure( 
                'Heading Font Persistence', 
                'Heading weights do not match. Expected: ' . print_r( $test_weights, true ) . 
                ', Got: ' . print_r( $loaded_settings['heading_weights'], true )
            );
            return;
        }
        
        $this->record_success( 'Heading Font Persistence', 'Heading font and weights persisted correctly' );
    }
    
    /**
     * Test 3: Weight array persistence (Requirement 4.4)
     */
    private function test_weight_persistence() {
        echo "<h2>Test 3: Weight Array Persistence (Req 4.4)</h2>\n";
        
        $test_weights = array( 300, 400, 500, 600, 700, 800, 900 );
        
        // Save all available weights
        $settings = array(
            'typography' => array(
                'body_font' => 'Roboto',
                'body_weights' => $test_weights,
            ),
        );
        
        $save_result = $this->settings->save_settings( $settings );
        
        if ( ! $save_result ) {
            $this->record_failure( 'Weight Persistence', 'Failed to save weight settings' );
            return;
        }
        
        // Reload settings
        $this->settings = new WOOW_Settings();
        $loaded_settings = $this->settings->get_section( 'typography' );
        
        // Verify all weights persisted
        if ( count( $loaded_settings['body_weights'] ) !== count( $test_weights ) ) {
            $this->record_failure( 
                'Weight Persistence', 
                'Weight count mismatch. Expected: ' . count( $test_weights ) . 
                ', Got: ' . count( $loaded_settings['body_weights'] )
            );
            return;
        }
        
        // Verify each weight
        foreach ( $test_weights as $weight ) {
            if ( ! in_array( $weight, $loaded_settings['body_weights'], true ) ) {
                $this->record_failure( 
                    'Weight Persistence', 
                    "Weight $weight not found in persisted weights" 
                );
                return;
            }
        }
        
        $this->record_success( 'Weight Persistence', 'All font weights persisted correctly' );
    }
    
    /**
     * Test 4: System default persistence
     */
    private function test_system_default_persistence() {
        echo "<h2>Test 4: System Default Persistence</h2>\n";
        
        // Save system default
        $settings = array(
            'typography' => array(
                'body_font' => 'system',
                'heading_font' => 'system',
            ),
        );
        
        $save_result = $this->settings->save_settings( $settings );
        
        if ( ! $save_result ) {
            $this->record_failure( 'System Default Persistence', 'Failed to save system default' );
            return;
        }
        
        // Reload settings
        $this->settings = new WOOW_Settings();
        $loaded_settings = $this->settings->get_section( 'typography' );
        
        // Verify system default
        if ( $loaded_settings['body_font'] !== 'system' || $loaded_settings['heading_font'] !== 'system' ) {
            $this->record_failure( 
                'System Default Persistence', 
                'System default not persisted correctly' 
            );
            return;
        }
        
        $this->record_success( 'System Default Persistence', 'System default persisted correctly' );
    }
    
    /**
     * Test 5: Reset functionality returns to system default (Requirement 6.3)
     */
    private function test_reset_to_system_default() {
        echo "<h2>Test 5: Reset to System Default (Req 6.3)</h2>\n";
        
        // First, set a custom font
        $settings = array(
            'typography' => array(
                'body_font' => 'Open Sans',
                'heading_font' => 'Montserrat',
                'body_weights' => array( 400, 700 ),
                'heading_weights' => array( 600, 800 ),
            ),
        );
        
        $this->settings->save_settings( $settings );
        
        // Now reset to defaults
        $reset_result = $this->settings->reset_to_defaults();
        
        if ( ! $reset_result ) {
            $this->record_failure( 'Reset to System Default', 'Failed to reset settings' );
            return;
        }
        
        // Reload settings
        $this->settings = new WOOW_Settings();
        $loaded_settings = $this->settings->get_section( 'typography' );
        
        // Verify fonts are back to system default
        if ( $loaded_settings['body_font'] !== 'system' ) {
            $this->record_failure( 
                'Reset to System Default', 
                "Expected body_font 'system', got '{$loaded_settings['body_font']}'" 
            );
            return;
        }
        
        if ( $loaded_settings['heading_font'] !== 'system' ) {
            $this->record_failure( 
                'Reset to System Default', 
                "Expected heading_font 'system', got '{$loaded_settings['heading_font']}'" 
            );
            return;
        }
        
        // Verify default weights are restored
        $default_weights = array( 400, 600, 700 );
        if ( $loaded_settings['body_weights'] !== $default_weights ) {
            $this->record_failure( 
                'Reset to System Default', 
                'Default weights not restored correctly' 
            );
            return;
        }
        
        $this->record_success( 'Reset to System Default', 'Settings reset to system default correctly' );
    }
    
    /**
     * Test 6: Settings load correctly on Typography Tab access (Requirement 6.5)
     */
    private function test_load_on_tab_access() {
        echo "<h2>Test 6: Settings Load on Tab Access (Req 6.5)</h2>\n";
        
        // Save specific settings
        $test_settings = array(
            'typography' => array(
                'body_font' => 'Lato',
                'heading_font' => 'Merriweather',
                'body_weights' => array( 300, 400, 700 ),
                'heading_weights' => array( 400, 700, 900 ),
                'h1_size' => '36px',
                'body_size' => '16px',
            ),
        );
        
        $this->settings->save_settings( $test_settings );
        
        // Simulate tab access by creating new settings instance
        $fresh_settings = new WOOW_Settings();
        $loaded_settings = $fresh_settings->get_section( 'typography' );
        
        // Verify all settings loaded correctly
        $checks = array(
            'body_font' => 'Lato',
            'heading_font' => 'Merriweather',
            'h1_size' => '36px',
            'body_size' => '16px',
        );
        
        foreach ( $checks as $key => $expected ) {
            if ( $loaded_settings[$key] !== $expected ) {
                $this->record_failure( 
                    'Settings Load on Tab Access', 
                    "Expected $key '$expected', got '{$loaded_settings[$key]}'" 
                );
                return;
            }
        }
        
        // Verify weight arrays
        if ( $loaded_settings['body_weights'] !== array( 300, 400, 700 ) ) {
            $this->record_failure( 
                'Settings Load on Tab Access', 
                'Body weights not loaded correctly' 
            );
            return;
        }
        
        if ( $loaded_settings['heading_weights'] !== array( 400, 700, 900 ) ) {
            $this->record_failure( 
                'Settings Load on Tab Access', 
                'Heading weights not loaded correctly' 
            );
            return;
        }
        
        $this->record_success( 'Settings Load on Tab Access', 'All settings loaded correctly on tab access' );
    }
    
    /**
     * Test 7: Combined font settings (body + heading)
     */
    private function test_combined_font_settings() {
        echo "<h2>Test 7: Combined Font Settings</h2>\n";
        
        // Save both body and heading fonts with different weights
        $settings = array(
            'typography' => array(
                'body_font' => 'Source Sans Pro',
                'body_weights' => array( 400, 600 ),
                'heading_font' => 'Raleway',
                'heading_weights' => array( 700, 800 ),
            ),
        );
        
        $save_result = $this->settings->save_settings( $settings );
        
        if ( ! $save_result ) {
            $this->record_failure( 'Combined Font Settings', 'Failed to save combined settings' );
            return;
        }
        
        // Reload settings
        $this->settings = new WOOW_Settings();
        $loaded_settings = $this->settings->get_section( 'typography' );
        
        // Verify both fonts persisted independently
        if ( $loaded_settings['body_font'] !== 'Source Sans Pro' ) {
            $this->record_failure( 
                'Combined Font Settings', 
                'Body font not persisted correctly in combined save' 
            );
            return;
        }
        
        if ( $loaded_settings['heading_font'] !== 'Raleway' ) {
            $this->record_failure( 
                'Combined Font Settings', 
                'Heading font not persisted correctly in combined save' 
            );
            return;
        }
        
        // Verify weights are independent
        if ( $loaded_settings['body_weights'] !== array( 400, 600 ) ) {
            $this->record_failure( 
                'Combined Font Settings', 
                'Body weights not persisted correctly in combined save' 
            );
            return;
        }
        
        if ( $loaded_settings['heading_weights'] !== array( 700, 800 ) ) {
            $this->record_failure( 
                'Combined Font Settings', 
                'Heading weights not persisted correctly in combined save' 
            );
            return;
        }
        
        $this->record_success( 'Combined Font Settings', 'Combined font settings persisted independently' );
    }
    
    /**
     * Record test success
     */
    private function record_success( $test_name, $message ) {
        $this->test_results[] = array(
            'test' => $test_name,
            'status' => 'PASS',
            'message' => $message,
        );
        
        echo "<p style='color: green;'>✓ PASS: $message</p>\n";
    }
    
    /**
     * Record test failure
     */
    private function record_failure( $test_name, $message ) {
        $this->test_results[] = array(
            'test' => $test_name,
            'status' => 'FAIL',
            'message' => $message,
        );
        
        echo "<p style='color: red;'>✗ FAIL: $message</p>\n";
    }
    
    /**
     * Print test summary
     */
    private function print_summary() {
        echo "<hr>\n";
        echo "<h2>Test Summary</h2>\n";
        
        $passed = 0;
        $failed = 0;
        
        foreach ( $this->test_results as $result ) {
            if ( $result['status'] === 'PASS' ) {
                $passed++;
            } else {
                $failed++;
            }
        }
        
        $total = $passed + $failed;
        $percentage = $total > 0 ? round( ( $passed / $total ) * 100, 2 ) : 0;
        
        echo "<p><strong>Total Tests:</strong> $total</p>\n";
        echo "<p style='color: green;'><strong>Passed:</strong> $passed</p>\n";
        echo "<p style='color: red;'><strong>Failed:</strong> $failed</p>\n";
        echo "<p><strong>Success Rate:</strong> $percentage%</p>\n";
        
        if ( $failed === 0 ) {
            echo "<h3 style='color: green;'>✓ All tests passed!</h3>\n";
        } else {
            echo "<h3 style='color: red;'>✗ Some tests failed</h3>\n";
        }
    }
    
    /**
     * Restore original settings
     */
    private function restore_original_settings() {
        echo "<hr>\n";
        echo "<h3>Restoring Original Settings...</h3>\n";
        
        $this->settings->save_settings( $this->original_settings );
        
        echo "<p>Original settings restored.</p>\n";
    }
}

// Run tests
$test = new WOOW_Typography_Persistence_Test();
$test->run_all_tests();
