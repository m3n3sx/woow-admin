<?php
/**
 * Test Dashboard Glassmorphism Fix
 * 
 * This test verifies that dashboard.css no longer has !important on backgrounds
 * when glassmorphism is enabled, allowing glassmorphism-system.css to override.
 * 
 * Usage: Place in woow-admin root and run: php test-dashboard-glassmorphism.php
 */

echo "=== Dashboard Glassmorphism Fix Test ===\n\n";

$dashboard_css = file_get_contents(__DIR__ . '/assets/src/css/wordpress-overrides/dashboard.css');

// Test 1: Check that default backgrounds don't have !important (excluding body:not rules)
echo "Test 1: Default backgrounds should NOT have !important\n";
$test1_pass = true;

// Check .postbox default background (not in body:not selector)
if (preg_match('/^\.postbox[^{]*\{[^}]*background:\s*#ffffff\s*!important/m', $dashboard_css)) {
    echo "  ❌ FAIL: .postbox still has background: #ffffff !important\n";
    $test1_pass = false;
} else {
    echo "  ✅ PASS: .postbox background doesn't have !important in default rule\n";
}

// Check #welcome-panel default background (not in body:not selector)
if (preg_match('/^#welcome-panel\s*\{[^}]*background:[^}]*!important/m', $dashboard_css)) {
    echo "  ❌ FAIL: #welcome-panel still has background !important in default rule\n";
    $test1_pass = false;
} else {
    echo "  ✅ PASS: #welcome-panel background doesn't have !important in default rule\n";
}

echo "\n";

// Test 2: Check that body:not(.woow-glass-enabled) rules exist
echo "Test 2: Solid backgrounds should exist for body:not(.woow-glass-enabled)\n";
$test2_pass = true;

if (strpos($dashboard_css, 'body:not(.woow-glass-enabled) .postbox') === false) {
    echo "  ❌ FAIL: Missing body:not(.woow-glass-enabled) .postbox rule\n";
    $test2_pass = false;
} else {
    echo "  ✅ PASS: Found body:not(.woow-glass-enabled) .postbox rule\n";
}

if (strpos($dashboard_css, 'body:not(.woow-glass-enabled) #welcome-panel') === false) {
    echo "  ❌ FAIL: Missing body:not(.woow-glass-enabled) #welcome-panel rule\n";
    $test2_pass = false;
} else {
    echo "  ✅ PASS: Found body:not(.woow-glass-enabled) #welcome-panel rule\n";
}

if (strpos($dashboard_css, 'body:not(.woow-glass-enabled) #dashboard_right_now li') === false) {
    echo "  ❌ FAIL: Missing body:not(.woow-glass-enabled) #dashboard_right_now li rule\n";
    $test2_pass = false;
} else {
    echo "  ✅ PASS: Found body:not(.woow-glass-enabled) #dashboard_right_now li rule\n";
}

echo "\n";

// Test 3: Check dark mode rules
echo "Test 3: Dark mode should also use body:not(.woow-glass-enabled)\n";
$test3_pass = true;

// Extract dark mode section
if (preg_match('/@media\s*\(prefers-color-scheme:\s*dark\)\s*\{(.+?)\n\}/s', $dashboard_css, $matches)) {
    $dark_mode_section = $matches[1];
    if (strpos($dark_mode_section, 'body:not(.woow-glass-enabled)') !== false) {
        echo "  ✅ PASS: Dark mode has body:not(.woow-glass-enabled) rules\n";
    } else {
        echo "  ❌ FAIL: Dark mode missing body:not(.woow-glass-enabled) rules\n";
        $test3_pass = false;
    }
} else {
    echo "  ❌ FAIL: Could not find dark mode section\n";
    $test3_pass = false;
}

echo "\n";

// Test 4: Verify glassmorphism-system.css has proper selectors
echo "Test 4: Glassmorphism system should target .woow-glass-enabled elements\n";
$glass_css = file_get_contents(__DIR__ . '/assets/src/css/glassmorphism-system.css');
$test4_pass = true;

if (strpos($glass_css, '.woow-glass-enabled .postbox') === false) {
    echo "  ❌ FAIL: Missing .woow-glass-enabled .postbox in glassmorphism-system.css\n";
    $test4_pass = false;
} else {
    echo "  ✅ PASS: Found .woow-glass-enabled .postbox in glassmorphism-system.css\n";
}

if (strpos($glass_css, '.woow-glass-enabled #welcome-panel') === false) {
    echo "  ❌ FAIL: Missing .woow-glass-enabled #welcome-panel in glassmorphism-system.css\n";
    $test4_pass = false;
} else {
    echo "  ✅ PASS: Found .woow-glass-enabled #welcome-panel in glassmorphism-system.css\n";
}

// Check for backdrop-filter with !important
if (preg_match('/\.woow-glass-enabled[^}]*backdrop-filter:[^}]*!important/s', $glass_css)) {
    echo "  ✅ PASS: Glassmorphism uses backdrop-filter with !important\n";
} else {
    echo "  ⚠️  WARNING: Glassmorphism backdrop-filter might not have !important\n";
}

echo "\n";

// Final Summary
echo "=== SUMMARY ===\n";
$all_pass = $test1_pass && $test2_pass && $test3_pass && $test4_pass;

if ($all_pass) {
    echo "✅ ALL TESTS PASSED!\n\n";
    echo "The fix is correct:\n";
    echo "1. Default backgrounds don't have !important\n";
    echo "2. Solid backgrounds only apply when glassmorphism is disabled\n";
    echo "3. Glassmorphism can now override with its !important rules\n\n";
    echo "Next steps:\n";
    echo "1. Build CSS: npm run build (in terminal)\n";
    echo "2. Clear cache: ./cc.sh\n";
    echo "3. Enable glassmorphism in WOOW! Admin → Effects\n";
    echo "4. Refresh browser (Ctrl+Shift+R)\n";
} else {
    echo "❌ SOME TESTS FAILED\n\n";
    echo "Please review the failed tests above.\n";
}

echo "\n";
