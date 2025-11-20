<?php
require_once __DIR__ . '/includes/class-woow-google-fonts.php';

$gf = new WOOW_Google_Fonts();

echo "Testing URL encoding:\n\n";

// Test 1: Font with spaces
$url1 = $gf->get_font_url('Open Sans', [400, 700]);
echo "Open Sans URL:\n{$url1}\n\n";
echo "Expected format: Open+Sans\n";
echo "Result: " . (strpos($url1, 'Open+Sans') !== false ? "✅ CORRECT" : "❌ INCORRECT") . "\n\n";

// Test 2: Font without spaces
$url2 = $gf->get_font_url('Inter', [400, 600]);
echo "Inter URL:\n{$url2}\n\n";

// Test 3: Another font with spaces
$url3 = $gf->get_font_url('Playfair Display', [400, 700]);
echo "Playfair Display URL:\n{$url3}\n\n";
echo "Expected format: Playfair+Display\n";
echo "Result: " . (strpos($url3, 'Playfair+Display') !== false ? "✅ CORRECT" : "❌ INCORRECT") . "\n";
