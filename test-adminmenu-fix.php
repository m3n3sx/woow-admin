<?php
/**
 * Test AdminMenu CSS Fix
 * 
 * This script tests if the fix works correctly:
 * 1. Fresh install = no CSS override
 * 2. Custom changes = CSS generated
 * 3. Reset to defaults = no CSS override
 */

// Load WordPress
require_once('../../../wp-load.php');

echo "<h1>AdminMenu CSS Fix Test</h1>\n\n";

// Load required files
require_once('includes/defaults.php');
require_once('includes/class-woow-settings.php');
require_once('includes/class-woow-css-generator.php');

$settings_obj = new WOOW_Settings();
$defaults = woow_get_default_settings();

echo "<h2>Test 1: Fresh Install (All Defaults)</h2>\n";
echo "<p>Simulating fresh install with default settings...</p>\n";

// Simulate fresh install - all defaults
$test_settings_fresh = $defaults;
$settings_obj_test = new WOOW_Settings();

// Temporarily override get_section to return defaults
class Test_Settings extends WOOW_Settings {
    private $test_data;
    
    public function set_test_data($data) {
        $this->test_data = $data;
    }
    
    public function get_section($section) {
        return $this->test_data[$section] ?? [];
    }
}

$test_settings = new Test_Settings();
$test_settings->set_test_data($defaults);

$css_gen_fresh = new WOOW_CSS_Generator($test_settings);
$css_fresh = $css_gen_fresh->generate();

// Extract adminmenu CSS
preg_match('/\/\* Admin Menu Styling.*?(?=\/\*[^*]|$)/s', $css_fresh, $matches_fresh);
$adminmenu_css_fresh = $matches_fresh[0] ?? '';

echo "<h3>Generated CSS (Fresh Install):</h3>\n";
if (empty($adminmenu_css_fresh) || strlen($adminmenu_css_fresh) < 50) {
    echo "<pre style='color: green; font-weight: bold;'>✅ PASS: No AdminMenu CSS generated (WordPress defaults win!)</pre>\n";
} else {
    echo "<pre style='color: red; font-weight: bold;'>❌ FAIL: AdminMenu CSS was generated even with defaults!</pre>\n";
    echo "<pre>" . htmlspecialchars($adminmenu_css_fresh) . "</pre>\n";
}

echo "<h2>Test 2: Custom Settings (Changed Values)</h2>\n";
echo "<p>Simulating user changing background color...</p>\n";

// Simulate custom settings - change background color
$test_settings_custom = $defaults;
$test_settings_custom['admin_menu']['background_color'] = '#ff0000'; // Changed!
$test_settings_custom['admin_menu']['width'] = '300'; // Changed!

$test_settings->set_test_data($test_settings_custom);
$css_gen_custom = new WOOW_CSS_Generator($test_settings);
$css_custom = $css_gen_custom->generate();

// Extract adminmenu CSS
preg_match('/\/\* Admin Menu Styling.*?(?=\/\*[^*]|$)/s', $css_custom, $matches_custom);
$adminmenu_css_custom = $matches_custom[0] ?? '';

echo "<h3>Generated CSS (Custom Settings):</h3>\n";
if (!empty($adminmenu_css_custom) && strlen($adminmenu_css_custom) > 50) {
    echo "<pre style='color: green; font-weight: bold;'>✅ PASS: AdminMenu CSS generated for custom values!</pre>\n";
    echo "<pre>" . htmlspecialchars(substr($adminmenu_css_custom, 0, 500)) . "...</pre>\n";
} else {
    echo "<pre style='color: red; font-weight: bold;'>❌ FAIL: No CSS generated even with custom values!</pre>\n";
}

echo "<h2>Test 3: Current Saved Settings</h2>\n";
$current_settings = get_option('woow_admin_settings', []);

if (empty($current_settings['admin_menu'])) {
    echo "<p style='color: orange;'>⚠️ No saved settings found (fresh install)</p>\n";
} else {
    echo "<p>Checking current saved settings...</p>\n";
    
    $diff_count = 0;
    $changed_fields = [];
    
    foreach ($defaults['admin_menu'] as $key => $default_value) {
        $current_value = $current_settings['admin_menu'][$key] ?? null;
        if ($current_value !== null && $current_value !== $default_value) {
            $diff_count++;
            $changed_fields[] = $key;
        }
    }
    
    echo "<h3>Changed Fields: {$diff_count}</h3>\n";
    if ($diff_count > 0) {
        echo "<ul>\n";
        foreach ($changed_fields as $field) {
            $current_val = $current_settings['admin_menu'][$field];
            $default_val = $defaults['admin_menu'][$field];
            echo "<li><code>{$field}</code>: <code>" . htmlspecialchars(print_r($current_val, true)) . "</code> (default: <code>" . htmlspecialchars(print_r($default_val, true)) . "</code>)</li>\n";
        }
        echo "</ul>\n";
        echo "<p style='color: green;'>✅ CSS should be generated for these fields</p>\n";
    } else {
        echo "<p style='color: green;'>✅ All fields match defaults, no CSS should be generated</p>\n";
    }
    
    // Generate CSS with current settings
    $css_gen_current = new WOOW_CSS_Generator($settings_obj);
    $css_current = $css_gen_current->generate();
    
    preg_match('/\/\* Admin Menu Styling.*?(?=\/\*[^*]|$)/s', $css_current, $matches_current);
    $adminmenu_css_current = $matches_current[0] ?? '';
    
    echo "<h3>Current Generated CSS:</h3>\n";
    if ($diff_count > 0) {
        if (!empty($adminmenu_css_current)) {
            echo "<pre style='color: green;'>✅ PASS: CSS generated (as expected with custom values)</pre>\n";
        } else {
            echo "<pre style='color: red;'>❌ FAIL: No CSS generated despite custom values!</pre>\n";
        }
    } else {
        if (empty($adminmenu_css_current) || strlen($adminmenu_css_current) < 50) {
            echo "<pre style='color: green;'>✅ PASS: No CSS generated (as expected with defaults)</pre>\n";
        } else {
            echo "<pre style='color: red;'>❌ FAIL: CSS generated despite all defaults!</pre>\n";
        }
    }
    
    if (!empty($adminmenu_css_current)) {
        echo "<details>\n";
        echo "<summary>Show Generated CSS</summary>\n";
        echo "<pre>" . htmlspecialchars($adminmenu_css_current) . "</pre>\n";
        echo "</details>\n";
    }
}

echo "<h2>Test 4: Comparison with Commit 4de3336</h2>\n";
echo "<p>To fully test, you need to:</p>\n";
echo "<ol>\n";
echo "<li>Checkout commit 4de3336: <code>git checkout 4de3336</code></li>\n";
echo "<li>Fresh install (delete settings)</li>\n";
echo "<li>Take screenshot of adminmenu</li>\n";
echo "<li>Checkout current: <code>git checkout main</code></li>\n";
echo "<li>Fresh install (delete settings)</li>\n";
echo "<li>Take screenshot of adminmenu</li>\n";
echo "<li>Compare screenshots - should be IDENTICAL</li>\n";
echo "</ol>\n";

echo "<h2>Summary</h2>\n";
echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>\n";
echo "<tr><th>Test</th><th>Expected</th><th>Status</th></tr>\n";

// Test 1
$test1_pass = empty($adminmenu_css_fresh) || strlen($adminmenu_css_fresh) < 50;
echo "<tr><td>Fresh Install</td><td>No CSS</td><td style='color: " . ($test1_pass ? 'green' : 'red') . ";'>" . ($test1_pass ? '✅ PASS' : '❌ FAIL') . "</td></tr>\n";

// Test 2
$test2_pass = !empty($adminmenu_css_custom) && strlen($adminmenu_css_custom) > 50;
echo "<tr><td>Custom Settings</td><td>CSS Generated</td><td style='color: " . ($test2_pass ? 'green' : 'red') . ";'>" . ($test2_pass ? '✅ PASS' : '❌ FAIL') . "</td></tr>\n";

echo "</table>\n";

if ($test1_pass && $test2_pass) {
    echo "<h2 style='color: green;'>🎉 ALL TESTS PASSED!</h2>\n";
    echo "<p>The fix is working correctly. AdminMenu CSS is only generated when user makes custom changes.</p>\n";
} else {
    echo "<h2 style='color: red;'>❌ SOME TESTS FAILED</h2>\n";
    echo "<p>Review the output above to see what went wrong.</p>\n";
}

echo "<h2>Next Steps</h2>\n";
echo "<ol>\n";
echo "<li>If tests pass: Test in browser with fresh install</li>\n";
echo "<li>Delete settings: <code>DELETE FROM wp_options WHERE option_name = 'woow_admin_settings'</code></li>\n";
echo "<li>Refresh admin panel</li>\n";
echo "<li>Check if adminmenu looks like vanilla WordPress</li>\n";
echo "<li>Change one option and save</li>\n";
echo "<li>Check if only that option is styled</li>\n";
echo "</ol>\n";
