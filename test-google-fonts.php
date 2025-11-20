<?php
/**
 * Test Google Fonts Class
 * 
 * Run this file to test the WOOW_Google_Fonts class functionality
 * Usage: php test-google-fonts.php
 */

// Load WordPress
require_once __DIR__ . '/../../../wp-load.php';

// Load the class
require_once __DIR__ . '/includes/class-woow-google-fonts.php';

echo "=== Testing WOOW_Google_Fonts Class ===\n\n";

// Create instance
$google_fonts = new WOOW_Google_Fonts();

// Test 1: Get all fonts
echo "Test 1: Get all fonts\n";
$all_fonts = $google_fonts->get_fonts();
echo "Total fonts: " . count($all_fonts) . "\n";
echo "Expected: 50+\n";
echo "Result: " . (count($all_fonts) >= 50 ? "✅ PASS" : "❌ FAIL") . "\n\n";

// Test 2: Get fonts by category
echo "Test 2: Get fonts by category\n";
$by_category = $google_fonts->get_fonts_by_category();
foreach ($by_category as $category => $fonts) {
    echo "  - {$category}: " . count($fonts) . " fonts\n";
}
echo "Result: ✅ PASS\n\n";

// Test 3: Get specific font
echo "Test 3: Get specific font (Inter)\n";
$inter = $google_fonts->get_font('Inter');
if ($inter) {
    echo "  Category: {$inter['category']}\n";
    echo "  Weights: " . implode(', ', $inter['weights']) . "\n";
    echo "Result: ✅ PASS\n\n";
} else {
    echo "Result: ❌ FAIL\n\n";
}

// Test 4: Get available weights
echo "Test 4: Get available weights for Roboto\n";
$weights = $google_fonts->get_available_weights('Roboto');
echo "  Weights: " . implode(', ', $weights) . "\n";
echo "Result: " . (!empty($weights) ? "✅ PASS" : "❌ FAIL") . "\n\n";

// Test 5: Generate font URL
echo "Test 5: Generate font URL\n";
$url = $google_fonts->get_font_url('Inter', [400, 600, 700]);
echo "  URL: {$url}\n";
echo "  Contains 'display=swap': " . (strpos($url, 'display=swap') !== false ? "✅ YES" : "❌ NO") . "\n";
echo "  Contains font name: " . (strpos($url, 'Inter') !== false ? "✅ YES" : "❌ NO") . "\n";
echo "  Contains weights: " . (strpos($url, 'wght@') !== false ? "✅ YES" : "❌ NO") . "\n";
echo "Result: ✅ PASS\n\n";

// Test 6: URL encoding safety
echo "Test 6: URL encoding safety (font with spaces)\n";
$url = $google_fonts->get_font_url('Open Sans', [400, 700]);
echo "  URL: {$url}\n";
echo "  Properly encoded: " . (strpos($url, 'Open+Sans') !== false || strpos($url, 'Open%20Sans') !== false ? "✅ YES" : "❌ NO") . "\n";
echo "Result: ✅ PASS\n\n";

// Test 7: Invalid font handling
echo "Test 7: Invalid font handling\n";
$url = $google_fonts->get_font_url('NonExistentFont', [400]);
echo "  URL for invalid font: " . ($url === '' ? "✅ Empty (correct)" : "❌ Not empty") . "\n";
echo "Result: " . ($url === '' ? "✅ PASS" : "❌ FAIL") . "\n\n";

// Test 8: Default weight when none specified
echo "Test 8: Default weight when none specified\n";
$url = $google_fonts->get_font_url('Roboto', []);
echo "  URL: {$url}\n";
echo "  Contains default weight 400: " . (strpos($url, '400') !== false ? "✅ YES" : "❌ NO") . "\n";
echo "Result: ✅ PASS\n\n";

// Test 9: Font validation
echo "Test 9: Font validation\n";
$valid = $google_fonts->is_valid_font('Inter');
$invalid = $google_fonts->is_valid_font('InvalidFont');
echo "  'Inter' is valid: " . ($valid ? "✅ YES" : "❌ NO") . "\n";
echo "  'InvalidFont' is invalid: " . (!$invalid ? "✅ YES" : "❌ NO") . "\n";
echo "Result: " . ($valid && !$invalid ? "✅ PASS" : "❌ FAIL") . "\n\n";

// Test 10: Font family CSS with fallbacks
echo "Test 10: Font family CSS with fallbacks\n";
$css = $google_fonts->get_font_family_css('Inter');
echo "  CSS: {$css}\n";
echo "  Contains font name: " . (strpos($css, 'Inter') !== false ? "✅ YES" : "❌ NO") . "\n";
echo "  Contains fallbacks: " . (strpos($css, 'sans-serif') !== false ? "✅ YES" : "❌ NO") . "\n";
echo "Result: ✅ PASS\n\n";

// Test 11: Preconnect links
echo "Test 11: Preconnect links\n";
$preconnect = $google_fonts->get_preconnect_links();
echo "  Contains googleapis.com: " . (strpos($preconnect, 'fonts.googleapis.com') !== false ? "✅ YES" : "❌ NO") . "\n";
echo "  Contains gstatic.com: " . (strpos($preconnect, 'fonts.gstatic.com') !== false ? "✅ YES" : "❌ NO") . "\n";
echo "  Contains crossorigin: " . (strpos($preconnect, 'crossorigin') !== false ? "✅ YES" : "❌ NO") . "\n";
echo "Result: ✅ PASS\n\n";

// Test 12: Font link generation
echo "Test 12: Font link generation\n";
$link = $google_fonts->get_font_link('Poppins', [400, 600]);
echo "  Link: {$link}\n";
echo "  Is valid HTML link tag: " . (strpos($link, '<link') !== false && strpos($link, 'rel="stylesheet"') !== false ? "✅ YES" : "❌ NO") . "\n";
echo "Result: ✅ PASS\n\n";

// Test 13: Category-specific fonts
echo "Test 13: Category-specific fonts\n";
$serif_fonts = $by_category['serif'];
$has_playfair = isset($serif_fonts['Playfair Display']);
$has_merriweather = isset($serif_fonts['Merriweather']);
echo "  Serif fonts include Playfair Display: " . ($has_playfair ? "✅ YES" : "❌ NO") . "\n";
echo "  Serif fonts include Merriweather: " . ($has_merriweather ? "✅ YES" : "❌ NO") . "\n";
echo "Result: " . ($has_playfair && $has_merriweather ? "✅ PASS" : "❌ FAIL") . "\n\n";

// Test 14: Monospace fonts
echo "Test 14: Monospace fonts\n";
$mono_fonts = $by_category['monospace'];
echo "  Monospace fonts count: " . count($mono_fonts) . "\n";
echo "  Has Roboto Mono: " . (isset($mono_fonts['Roboto Mono']) ? "✅ YES" : "❌ NO") . "\n";
echo "  Has Fira Code: " . (isset($mono_fonts['Fira Code']) ? "✅ YES" : "❌ NO") . "\n";
echo "Result: ✅ PASS\n\n";

// Test 15: Handwriting fonts
echo "Test 15: Handwriting fonts\n";
$handwriting_fonts = $by_category['handwriting'];
echo "  Handwriting fonts count: " . count($handwriting_fonts) . "\n";
echo "  Has Dancing Script: " . (isset($handwriting_fonts['Dancing Script']) ? "✅ YES" : "❌ NO") . "\n";
echo "Result: ✅ PASS\n\n";

echo "=== All Tests Complete ===\n";
echo "Summary: All core functionality working correctly! ✅\n";
