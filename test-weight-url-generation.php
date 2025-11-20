<?php
/**
 * Test Weight URL Generation
 * 
 * Tests for Task 11: Weight URL Generation - Complete Implementation
 * Validates Requirements 4.2, 4.3, 4.5
 * 
 * Usage: php test-weight-url-generation.php
 */

// Load WordPress
require_once __DIR__ . '/../../../wp-load.php';

// Load the class
require_once __DIR__ . '/includes/class-woow-google-fonts.php';

echo "=== Testing Weight URL Generation (Task 11) ===\n\n";

$google_fonts = new WOOW_Google_Fonts();
$test_results = [];

// Test 1: Weight arrays correctly included in font URLs (Requirement 4.2)
echo "Test 1: Weight arrays correctly included in font URLs\n";
echo "Requirement 4.2: WHEN the administrator selects multiple Font_Weight values, THE WOOW_Admin_System SHALL include all selected weights in the Font_URL request\n\n";

$test_cases = [
    ['font' => 'Inter', 'weights' => [400, 600, 700], 'expected_pattern' => 'wght@400;600;700'],
    ['font' => 'Roboto', 'weights' => [300, 400, 700], 'expected_pattern' => 'wght@300;400;700'],
    ['font' => 'Poppins', 'weights' => [100, 400, 900], 'expected_pattern' => 'wght@100;400;900'],
    ['font' => 'Open Sans', 'weights' => [300, 600, 800], 'expected_pattern' => 'wght@300;600;800'],
];

$test1_passed = true;
foreach ($test_cases as $case) {
    $url = $google_fonts->get_font_url($case['font'], $case['weights']);
    $contains_pattern = strpos($url, $case['expected_pattern']) !== false;
    
    echo "  Font: {$case['font']}, Weights: [" . implode(', ', $case['weights']) . "]\n";
    echo "  URL: {$url}\n";
    echo "  Expected pattern: {$case['expected_pattern']}\n";
    echo "  Result: " . ($contains_pattern ? "✅ PASS" : "❌ FAIL") . "\n\n";
    
    if (!$contains_pattern) {
        $test1_passed = false;
    }
}

$test_results['test1'] = $test1_passed;
echo "Test 1 Overall: " . ($test1_passed ? "✅ PASS" : "❌ FAIL") . "\n\n";

// Test 2: Only selected weights are requested (Requirement 4.5)
echo "Test 2: Only selected weights are requested\n";
echo "Requirement 4.5: WHEN fonts are loaded, THE WOOW_Admin_System SHALL only request the selected Font_Weight values to minimize bandwidth usage\n\n";

$test2_passed = true;

// Test case: Request specific weights and verify ONLY those are in URL
$font = 'Inter';
$selected_weights = [400, 700];
$url = $google_fonts->get_font_url($font, $selected_weights);

echo "  Font: {$font}\n";
echo "  Selected weights: [" . implode(', ', $selected_weights) . "]\n";
echo "  URL: {$url}\n";

// Check that selected weights are present
$has_400 = strpos($url, '400') !== false;
$has_700 = strpos($url, '700') !== false;

// Check that unselected weights are NOT present
$has_300 = strpos($url, '300') !== false;
$has_600 = strpos($url, '600') !== false;
$has_800 = strpos($url, '800') !== false;

echo "  Contains 400 (selected): " . ($has_400 ? "✅ YES" : "❌ NO") . "\n";
echo "  Contains 700 (selected): " . ($has_700 ? "✅ YES" : "❌ NO") . "\n";
echo "  Contains 300 (not selected): " . (!$has_300 ? "✅ NO (correct)" : "❌ YES (incorrect)") . "\n";
echo "  Contains 600 (not selected): " . (!$has_600 ? "✅ NO (correct)" : "❌ YES (incorrect)") . "\n";
echo "  Contains 800 (not selected): " . (!$has_800 ? "✅ NO (correct)" : "❌ YES (incorrect)") . "\n";

$test2_passed = $has_400 && $has_700 && !$has_300 && !$has_600 && !$has_800;
$test_results['test2'] = $test2_passed;
echo "Test 2 Result: " . ($test2_passed ? "✅ PASS" : "❌ FAIL") . "\n\n";

// Test 3: Default weight (400) when no weights selected (Requirement 4.3)
echo "Test 3: Default weight (400) when no weights selected\n";
echo "Requirement 4.3: WHEN no Font_Weight is selected, THE WOOW_Admin_System SHALL default to loading regular weight (400)\n\n";

$test3_cases = [
    ['font' => 'Inter', 'weights' => []],
    ['font' => 'Roboto', 'weights' => []],
    ['font' => 'Poppins', 'weights' => null],
];

$test3_passed = true;
foreach ($test3_cases as $case) {
    $weights = $case['weights'] ?? [];
    $url = $google_fonts->get_font_url($case['font'], $weights);
    $has_400 = strpos($url, '400') !== false;
    $weight_count = substr_count($url, ';') + 1; // Count semicolons + 1 to get number of weights
    
    echo "  Font: {$case['font']}, Weights: " . (empty($weights) ? "[]" : "[" . implode(', ', $weights) . "]") . "\n";
    echo "  URL: {$url}\n";
    echo "  Contains 400: " . ($has_400 ? "✅ YES" : "❌ NO") . "\n";
    echo "  Only one weight: " . ($weight_count === 1 ? "✅ YES" : "❌ NO (found {$weight_count})") . "\n";
    
    if (!$has_400 || $weight_count !== 1) {
        $test3_passed = false;
    }
    echo "\n";
}

$test_results['test3'] = $test3_passed;
echo "Test 3 Overall: " . ($test3_passed ? "✅ PASS" : "❌ FAIL") . "\n\n";

// Test 4: Weights are sorted in URL
echo "Test 4: Weights are sorted in ascending order in URL\n";
echo "This ensures consistent URLs for caching\n\n";

$test4_cases = [
    ['font' => 'Inter', 'weights' => [700, 400, 600], 'expected_order' => '400;600;700'],
    ['font' => 'Roboto', 'weights' => [900, 300, 700, 400], 'expected_order' => '300;400;700;900'],
];

$test4_passed = true;
foreach ($test4_cases as $case) {
    $url = $google_fonts->get_font_url($case['font'], $case['weights']);
    $contains_order = strpos($url, $case['expected_order']) !== false;
    
    echo "  Font: {$case['font']}, Weights (unsorted): [" . implode(', ', $case['weights']) . "]\n";
    echo "  URL: {$url}\n";
    echo "  Expected order: {$case['expected_order']}\n";
    echo "  Result: " . ($contains_order ? "✅ PASS" : "❌ FAIL") . "\n\n";
    
    if (!$contains_order) {
        $test4_passed = false;
    }
}

$test_results['test4'] = $test4_passed;
echo "Test 4 Overall: " . ($test4_passed ? "✅ PASS" : "❌ FAIL") . "\n\n";

// Test 5: Invalid weights are filtered out
echo "Test 5: Invalid weights are filtered out\n";
echo "Only weights available for the font should be included\n\n";

// Roboto has weights: [100, 300, 400, 500, 700, 900]
// Request includes 600 which is not available
$font = 'Roboto';
$requested_weights = [400, 600, 700]; // 600 is not available for Roboto
$url = $google_fonts->get_font_url($font, $requested_weights);

echo "  Font: {$font}\n";
echo "  Available weights: [100, 300, 400, 500, 700, 900]\n";
echo "  Requested weights: [" . implode(', ', $requested_weights) . "]\n";
echo "  URL: {$url}\n";

$has_400 = strpos($url, '400') !== false;
$has_600 = strpos($url, '600') !== false;
$has_700 = strpos($url, '700') !== false;

echo "  Contains 400 (available): " . ($has_400 ? "✅ YES" : "❌ NO") . "\n";
echo "  Contains 600 (not available): " . (!$has_600 ? "✅ NO (correct)" : "❌ YES (incorrect)") . "\n";
echo "  Contains 700 (available): " . ($has_700 ? "✅ YES" : "❌ NO") . "\n";

$test5_passed = $has_400 && !$has_600 && $has_700;
$test_results['test5'] = $test5_passed;
echo "Test 5 Result: " . ($test5_passed ? "✅ PASS" : "❌ FAIL") . "\n\n";

// Test 6: Display swap parameter is always included
echo "Test 6: Display swap parameter is always included\n";
echo "Requirement 5.2: WHEN generating the Font_URL, THE WOOW_Admin_System SHALL include the display=swap parameter\n\n";

$test6_cases = [
    ['font' => 'Inter', 'weights' => [400]],
    ['font' => 'Roboto', 'weights' => [400, 700]],
    ['font' => 'Open Sans', 'weights' => []],
];

$test6_passed = true;
foreach ($test6_cases as $case) {
    $url = $google_fonts->get_font_url($case['font'], $case['weights']);
    $has_display_swap = strpos($url, 'display=swap') !== false;
    
    echo "  Font: {$case['font']}\n";
    echo "  URL: {$url}\n";
    echo "  Contains display=swap: " . ($has_display_swap ? "✅ YES" : "❌ NO") . "\n\n";
    
    if (!$has_display_swap) {
        $test6_passed = false;
    }
}

$test_results['test6'] = $test6_passed;
echo "Test 6 Overall: " . ($test6_passed ? "✅ PASS" : "❌ FAIL") . "\n\n";

// Test 7: URL format is correct
echo "Test 7: URL format is correct\n";
echo "URL should follow Google Fonts API v2 format\n\n";

$url = $google_fonts->get_font_url('Inter', [400, 600, 700]);
$expected_format = 'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap';

echo "  Generated URL: {$url}\n";
echo "  Expected URL:  {$expected_format}\n";
echo "  Match: " . ($url === $expected_format ? "✅ YES" : "❌ NO") . "\n";

$test7_passed = ($url === $expected_format);
$test_results['test7'] = $test7_passed;
echo "Test 7 Result: " . ($test7_passed ? "✅ PASS" : "❌ FAIL") . "\n\n";

// Test 8: Font name with spaces is properly encoded
echo "Test 8: Font name with spaces is properly encoded\n";
echo "Spaces should be replaced with + for Google Fonts API\n\n";

$url = $google_fonts->get_font_url('Open Sans', [400, 700]);
$has_plus = strpos($url, 'Open+Sans') !== false;
$no_spaces = strpos($url, 'Open Sans') === false;

echo "  Font: Open Sans\n";
echo "  URL: {$url}\n";
echo "  Contains 'Open+Sans': " . ($has_plus ? "✅ YES" : "❌ NO") . "\n";
echo "  No unencoded spaces: " . ($no_spaces ? "✅ YES" : "❌ NO") . "\n";

$test8_passed = $has_plus && $no_spaces;
$test_results['test8'] = $test8_passed;
echo "Test 8 Result: " . ($test8_passed ? "✅ PASS" : "❌ FAIL") . "\n\n";

// Summary
echo "=== Test Summary ===\n\n";
$total_tests = count($test_results);
$passed_tests = count(array_filter($test_results));

foreach ($test_results as $test => $passed) {
    $test_num = str_replace('test', '', $test);
    echo "Test {$test_num}: " . ($passed ? "✅ PASS" : "❌ FAIL") . "\n";
}

echo "\n";
echo "Total: {$passed_tests}/{$total_tests} tests passed\n";

if ($passed_tests === $total_tests) {
    echo "\n🎉 All tests passed! Weight URL generation is working correctly.\n";
    echo "✅ Requirements 4.2, 4.3, 4.5 validated\n";
} else {
    echo "\n⚠️  Some tests failed. Please review the implementation.\n";
}

echo "\n=== Test Complete ===\n";
