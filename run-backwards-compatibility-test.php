#!/usr/bin/env php
<?php
/**
 * Run Backwards Compatibility Tests
 * 
 * @package WoowAdmin
 */

// Simulate WordPress environment
define('ABSPATH', true);

// Load the test file
require_once __DIR__ . '/test-backwards-compatibility.php';

// Run tests
$_GET['run_backwards_compatibility_test'] = '1';
$test = new WOOW_Backwards_Compatibility_Test();
$results = $test->run_all_tests();

// Output results summary
echo "\n\n=== FINAL RESULTS ===\n";
$total = count($results);
$passed = array_filter($results, function($r) { return $r['passed']; });
$pass_count = count($passed);
echo "Total Tests: $total\n";
echo "Passed: $pass_count\n";
echo "Failed: " . ($total - $pass_count) . "\n";
echo "Pass Rate: " . round(($pass_count / $total) * 100, 1) . "%\n";

if ($pass_count === $total) {
    echo "\n✅ ALL BACKWARDS COMPATIBILITY TESTS PASSED!\n";
    exit(0);
} else {
    echo "\n❌ SOME TESTS FAILED\n";
    exit(1);
}
