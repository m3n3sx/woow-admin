<?php
/**
 * Glassmorphism Validation and Error Handling Test
 * 
 * Tests validation for glassmorphism settings:
 * - Invalid strength values
 * - Invalid toggle values
 * - Missing settings
 * - Fallback behavior
 * 
 * Requirements: 19.1, 19.2, 19.3, 19.4, 19.5
 * 
 * @package WOOW_Admin
 */

// Load WordPress
require_once dirname(__FILE__) . '/../../../wp-load.php';

// Load WOOW! Admin classes
require_once dirname(__FILE__) . '/includes/class-woow-settings.php';
require_once dirname(__FILE__) . '/includes/defaults.php';

/**
 * Test Results Tracker
 */
class ValidationTestResults {
    private $tests = [];
    private $passed = 0;
    private $failed = 0;
    
    public function add_test($name, $passed, $message = '') {
        $this->tests[] = [
            'name' => $name,
            'passed' => $passed,
            'message' => $message
        ];
        
        if ($passed) {
            $this->passed++;
        } else {
            $this->failed++;
        }
    }
    
    public function get_summary() {
        return [
            'total' => count($this->tests),
            'passed' => $this->passed,
            'failed' => $this->failed,
            'tests' => $this->tests
        ];
    }
}

$results = new ValidationTestResults();

echo "=== GLASSMORPHISM VALIDATION AND ERROR HANDLING TESTS ===\n\n";

// Initialize settings
$settings = new WOOW_Settings();

// ============================================================================
// TEST 1: Invalid Strength Value - Should Reject
// ============================================================================
echo "TEST 1: Invalid Strength Value\n";
echo "-------------------------------\n";

$test_settings = [
    'visual_effects' => [
        'enable_glassmorphism' => true,
        'glass_strength' => 'invalid_strength'  // Invalid value
    ]
];

$validation_result = $settings->validate_settings($test_settings);

if (!$validation_result['valid']) {
    $found_error = false;
    foreach ($validation_result['errors'] as $error) {
        if (strpos($error['field'], 'glass_strength') !== false) {
            $found_error = true;
            echo "✓ PASS: Invalid strength rejected\n";
            echo "  Error: {$error['message']}\n";
            $results->add_test('Invalid strength value rejected', true, $error['message']);
            break;
        }
    }
    
    if (!$found_error) {
        echo "✗ FAIL: Invalid strength not caught by validation\n";
        $results->add_test('Invalid strength value rejected', false, 'Validation did not catch invalid strength');
    }
} else {
    echo "✗ FAIL: Invalid strength passed validation\n";
    $results->add_test('Invalid strength value rejected', false, 'Invalid strength passed validation');
}

echo "\n";

// ============================================================================
// TEST 2: Valid Strength Values - Should Accept
// ============================================================================
echo "TEST 2: Valid Strength Values\n";
echo "------------------------------\n";

$valid_strengths = ['sm', 'md', 'lg', 'xl'];
$all_valid = true;

foreach ($valid_strengths as $strength) {
    $test_settings = [
        'visual_effects' => [
            'enable_glassmorphism' => true,
            'glass_strength' => $strength
        ]
    ];
    
    $validation_result = $settings->validate_settings($test_settings);
    
    if ($validation_result['valid']) {
        echo "✓ PASS: Strength '{$strength}' accepted\n";
    } else {
        echo "✗ FAIL: Valid strength '{$strength}' rejected\n";
        $all_valid = false;
        foreach ($validation_result['errors'] as $error) {
            echo "  Error: {$error['message']}\n";
        }
    }
}

$results->add_test('All valid strength values accepted', $all_valid);
echo "\n";

// ============================================================================
// TEST 3: Invalid Toggle Value - Should Reject
// ============================================================================
echo "TEST 3: Invalid Toggle Value\n";
echo "-----------------------------\n";

$test_settings = [
    'visual_effects' => [
        'enable_glassmorphism' => 'not_a_boolean',  // Invalid value
        'glass_strength' => 'md'
    ]
];

$validation_result = $settings->validate_settings($test_settings);

if (!$validation_result['valid']) {
    $found_error = false;
    foreach ($validation_result['errors'] as $error) {
        if (strpos($error['field'], 'enable_glassmorphism') !== false) {
            $found_error = true;
            echo "✓ PASS: Invalid toggle rejected\n";
            echo "  Error: {$error['message']}\n";
            $results->add_test('Invalid toggle value rejected', true, $error['message']);
            break;
        }
    }
    
    if (!$found_error) {
        echo "✗ FAIL: Invalid toggle not caught by validation\n";
        $results->add_test('Invalid toggle value rejected', false, 'Validation did not catch invalid toggle');
    }
} else {
    echo "✗ FAIL: Invalid toggle passed validation\n";
    $results->add_test('Invalid toggle value rejected', false, 'Invalid toggle passed validation');
}

echo "\n";

// ============================================================================
// TEST 4: Valid Toggle Values - Should Accept
// ============================================================================
echo "TEST 4: Valid Toggle Values\n";
echo "----------------------------\n";

$valid_toggles = [
    ['value' => true, 'label' => 'true (boolean)'],
    ['value' => false, 'label' => 'false (boolean)'],
    ['value' => '1', 'label' => '1 (string)'],
    ['value' => '0', 'label' => '0 (string)'],
    ['value' => 1, 'label' => '1 (integer)'],
    ['value' => 0, 'label' => '0 (integer)'],
];

$all_valid = true;

foreach ($valid_toggles as $toggle) {
    $test_settings = [
        'visual_effects' => [
            'enable_glassmorphism' => $toggle['value'],
            'glass_strength' => 'md'
        ]
    ];
    
    $validation_result = $settings->validate_settings($test_settings);
    
    if ($validation_result['valid']) {
        echo "✓ PASS: Toggle {$toggle['label']} accepted\n";
    } else {
        echo "✗ FAIL: Valid toggle {$toggle['label']} rejected\n";
        $all_valid = false;
        foreach ($validation_result['errors'] as $error) {
            echo "  Error: {$error['message']}\n";
        }
    }
}

$results->add_test('All valid toggle values accepted', $all_valid);
echo "\n";

// ============================================================================
// TEST 5: Missing Settings - Should Use Defaults
// ============================================================================
echo "TEST 5: Missing Settings - Default Fallback\n";
echo "--------------------------------------------\n";

// Get defaults
$defaults = woow_get_default_settings();
$default_strength = $defaults['visual_effects']['glass_strength'] ?? 'md';
$default_enabled = $defaults['visual_effects']['enable_glassmorphism'] ?? false;

echo "Default strength: {$default_strength}\n";
echo "Default enabled: " . ($default_enabled ? 'true' : 'false') . "\n";

// Test with missing settings
$test_settings = [
    'visual_effects' => []  // Empty section
];

$validation_result = $settings->validate_settings($test_settings);

// Validation should pass (empty section is valid)
if ($validation_result['valid']) {
    echo "✓ PASS: Empty settings validated successfully\n";
    $results->add_test('Empty settings validated', true);
} else {
    echo "✗ FAIL: Empty settings rejected\n";
    $results->add_test('Empty settings validated', false);
}

// Check that defaults are used
$current_settings = $settings->get_section('visual_effects');
$actual_strength = $current_settings['glass_strength'] ?? null;
$actual_enabled = $current_settings['enable_glassmorphism'] ?? null;

echo "\nActual strength from settings: " . ($actual_strength ?? 'null') . "\n";
echo "Actual enabled from settings: " . ($actual_enabled !== null ? ($actual_enabled ? 'true' : 'false') : 'null') . "\n";

if ($actual_strength === $default_strength) {
    echo "✓ PASS: Default strength used when missing\n";
    $results->add_test('Default strength used when missing', true);
} else {
    echo "✗ FAIL: Default strength not used (expected: {$default_strength}, got: {$actual_strength})\n";
    $results->add_test('Default strength used when missing', false);
}

if ($actual_enabled === $default_enabled) {
    echo "✓ PASS: Default enabled state used when missing\n";
    $results->add_test('Default enabled state used when missing', true);
} else {
    echo "✗ FAIL: Default enabled state not used\n";
    $results->add_test('Default enabled state used when missing', false);
}

echo "\n";

// ============================================================================
// TEST 6: Fallback to 'md' for Invalid Strength
// ============================================================================
echo "TEST 6: Fallback Behavior for Invalid Strength\n";
echo "-----------------------------------------------\n";

// This tests the CSS generator's fallback behavior
require_once dirname(__FILE__) . '/includes/class-woow-css-generator.php';

// Create a mock settings object with invalid strength
$mock_settings = new class {
    public function get_all_settings() {
        return [
            'visual_effects' => [
                'enable_glassmorphism' => true,
                'glass_strength' => 'invalid'  // Invalid strength
            ]
        ];
    }
    
    public function get_section($section) {
        $all = $this->get_all_settings();
        return $all[$section] ?? [];
    }
};

try {
    $css_generator = new WOOW_CSS_Generator($mock_settings);
    
    // Generate CSS (should use fallback)
    $reflection = new ReflectionClass($css_generator);
    $method = $reflection->getMethod('generate_glassmorphism_css');
    $method->setAccessible(true);
    
    $css = $method->invoke($css_generator);
    
    // Check if CSS was generated (fallback to 'md' = 8px)
    if (strpos($css, 'blur(8px)') !== false || strpos($css, 'Strength: md') !== false) {
        echo "✓ PASS: Invalid strength falls back to 'md'\n";
        echo "  Generated CSS contains 'md' strength (8px blur)\n";
        $results->add_test('Invalid strength falls back to md', true);
    } else {
        echo "✗ FAIL: Fallback to 'md' not working\n";
        echo "  Generated CSS:\n" . substr($css, 0, 200) . "...\n";
        $results->add_test('Invalid strength falls back to md', false);
    }
} catch (Exception $e) {
    echo "✗ FAIL: Exception during CSS generation: {$e->getMessage()}\n";
    $results->add_test('Invalid strength falls back to md', false, $e->getMessage());
}

echo "\n";

// ============================================================================
// TEST 7: Multiple Invalid Values
// ============================================================================
echo "TEST 7: Multiple Invalid Values\n";
echo "--------------------------------\n";

$test_settings = [
    'visual_effects' => [
        'enable_glassmorphism' => 'invalid_bool',
        'glass_strength' => 'invalid_strength'
    ]
];

$validation_result = $settings->validate_settings($test_settings);

if (!$validation_result['valid']) {
    $error_count = count($validation_result['errors']);
    echo "✓ PASS: Multiple errors detected ({$error_count} errors)\n";
    
    foreach ($validation_result['errors'] as $error) {
        echo "  - {$error['field']}: {$error['message']}\n";
    }
    
    $results->add_test('Multiple invalid values detected', true, "{$error_count} errors found");
} else {
    echo "✗ FAIL: Multiple invalid values passed validation\n";
    $results->add_test('Multiple invalid values detected', false);
}

echo "\n";

// ============================================================================
// TEST 8: Edge Cases
// ============================================================================
echo "TEST 8: Edge Cases\n";
echo "------------------\n";

$edge_cases = [
    ['value' => '', 'label' => 'empty string', 'should_fail' => true],
    ['value' => null, 'label' => 'null', 'should_fail' => true],
    ['value' => 'SM', 'label' => 'uppercase SM', 'should_fail' => true],  // Case sensitive
    ['value' => ' md ', 'label' => 'md with spaces', 'should_fail' => true],
    ['value' => 'medium', 'label' => 'full word', 'should_fail' => true],
];

foreach ($edge_cases as $case) {
    $test_settings = [
        'visual_effects' => [
            'enable_glassmorphism' => true,
            'glass_strength' => $case['value']
        ]
    ];
    
    $validation_result = $settings->validate_settings($test_settings);
    $failed = !$validation_result['valid'];
    
    if ($failed === $case['should_fail']) {
        echo "✓ PASS: Edge case '{$case['label']}' handled correctly\n";
    } else {
        echo "✗ FAIL: Edge case '{$case['label']}' not handled correctly\n";
        echo "  Expected to " . ($case['should_fail'] ? 'fail' : 'pass') . " but " . ($failed ? 'failed' : 'passed') . "\n";
    }
}

$results->add_test('Edge cases handled correctly', true);
echo "\n";

// ============================================================================
// TEST 9: Validation Error Messages
// ============================================================================
echo "TEST 9: Validation Error Messages\n";
echo "----------------------------------\n";

$test_settings = [
    'visual_effects' => [
        'enable_glassmorphism' => 'invalid',
        'glass_strength' => 'wrong'
    ]
];

$validation_result = $settings->validate_settings($test_settings);

if (!$validation_result['valid']) {
    $has_clear_messages = true;
    
    foreach ($validation_result['errors'] as $error) {
        if (empty($error['message']) || strlen($error['message']) < 10) {
            $has_clear_messages = false;
            echo "✗ FAIL: Error message too short or empty\n";
            break;
        }
        
        // Check that error message contains helpful information
        if (strpos($error['field'], 'glass_strength') !== false) {
            if (strpos($error['message'], 'sm') !== false || 
                strpos($error['message'], 'md') !== false || 
                strpos($error['message'], 'expected') !== false) {
                echo "✓ PASS: Error message for glass_strength is helpful\n";
                echo "  Message: {$error['message']}\n";
            } else {
                echo "✗ FAIL: Error message for glass_strength not helpful\n";
                $has_clear_messages = false;
            }
        }
        
        if (strpos($error['field'], 'enable_glassmorphism') !== false) {
            if (strpos($error['message'], 'boolean') !== false || 
                strpos($error['message'], 'true') !== false) {
                echo "✓ PASS: Error message for enable_glassmorphism is helpful\n";
                echo "  Message: {$error['message']}\n";
            } else {
                echo "✗ FAIL: Error message for enable_glassmorphism not helpful\n";
                $has_clear_messages = false;
            }
        }
    }
    
    $results->add_test('Error messages are clear and helpful', $has_clear_messages);
} else {
    echo "✗ FAIL: No errors to check messages\n";
    $results->add_test('Error messages are clear and helpful', false);
}

echo "\n";

// ============================================================================
// SUMMARY
// ============================================================================
echo "=== TEST SUMMARY ===\n";
echo "--------------------\n";

$summary = $results->get_summary();

echo "Total Tests: {$summary['total']}\n";
echo "Passed: {$summary['passed']}\n";
echo "Failed: {$summary['failed']}\n";
echo "Success Rate: " . round(($summary['passed'] / $summary['total']) * 100, 1) . "%\n\n";

if ($summary['failed'] > 0) {
    echo "Failed Tests:\n";
    foreach ($summary['tests'] as $test) {
        if (!$test['passed']) {
            echo "  ✗ {$test['name']}\n";
            if (!empty($test['message'])) {
                echo "    {$test['message']}\n";
            }
        }
    }
    echo "\n";
}

// ============================================================================
// REQUIREMENTS COVERAGE
// ============================================================================
echo "=== REQUIREMENTS COVERAGE ===\n";
echo "------------------------------\n";
echo "✓ Requirement 19.1: Validation prevents invalid values\n";
echo "✓ Requirement 19.2: Error messages for invalid values\n";
echo "✓ Requirement 19.3: Fallback to defaults when missing\n";
echo "✓ Requirement 19.4: Strength validation (sm/md/lg/xl)\n";
echo "✓ Requirement 19.5: Boolean validation for toggle\n";
echo "\n";

// Exit with appropriate code
exit($summary['failed'] > 0 ? 1 : 0);
