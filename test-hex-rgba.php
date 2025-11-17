<?php
/**
 * Test hex_to_rgba conversion
 */

require_once __DIR__ . '/../../../wp-load.php';

echo "=== Testing hex_to_rgba ===\n\n";

// Test 1: Basic conversion
$hex = '#3b82f6';
$opacity = 0.5;
$result = WOOW_Admin::hex_to_rgba( $hex, $opacity );
echo "Test 1: hex_to_rgba('$hex', $opacity)\n";
echo "Result: $result\n";
echo "Expected: rgba(59, 130, 246, 0.50)\n\n";

// Test 2: Full opacity
$hex = '#ffffff';
$opacity = 1.0;
$result = WOOW_Admin::hex_to_rgba( $hex, $opacity );
echo "Test 2: hex_to_rgba('$hex', $opacity)\n";
echo "Result: $result\n";
echo "Expected: rgba(255, 255, 255, 1.00)\n\n";

// Test 3: Transparent
$hex = 'transparent';
$opacity = 1.0;
$result = WOOW_Admin::hex_to_rgba( $hex, $opacity );
echo "Test 3: hex_to_rgba('$hex', $opacity)\n";
echo "Result: $result\n";
echo "Expected: transparent\n\n";

echo "✓ All tests complete!\n";
