<?php
/**
 * AdminMenu CSS Diagnostic Tool
 * 
 * Compares default settings between commit 4de3336 and current
 * Shows what CSS is generated with defaults
 */

// Load WordPress
require_once('../../../wp-load.php');

// Get current settings
$current_settings = get_option('woow_admin_settings', []);

echo "<h1>AdminMenu CSS Diagnostic</h1>\n\n";

echo "<h2>1. Current Saved Settings (admin_menu section)</h2>\n";
echo "<pre>";
print_r($current_settings['admin_menu'] ?? 'NOT SET');
echo "</pre>\n\n";

echo "<h2>2. Default Settings from defaults.php</h2>\n";
require_once('includes/defaults.php');
$defaults = woow_get_default_settings();
echo "<pre>";
print_r($defaults['admin_menu']);
echo "</pre>\n\n";

echo "<h2>3. Generated CSS with Current Settings</h2>\n";
require_once('includes/class-woow-settings.php');
require_once('includes/class-woow-css-generator.php');

$settings_obj = new WOOW_Settings();
$css_generator = new WOOW_CSS_Generator($settings_obj);
$generated_css = $css_generator->generate();

// Extract only adminmenu CSS
preg_match('/\/\* Admin Menu Styling.*?(?=\/\*|$)/s', $generated_css, $matches);
echo "<pre>";
echo htmlspecialchars($matches[0] ?? 'NO ADMINMENU CSS FOUND');
echo "</pre>\n\n";

echo "<h2>4. Problem Analysis</h2>\n";
echo "<ul>\n";

// Check if settings are empty (fresh install)
if (empty($current_settings['admin_menu'])) {
    echo "<li><strong>✅ GOOD:</strong> No saved settings (fresh install)</li>\n";
    echo "<li><strong>⚠️ ISSUE:</strong> But CSS is still being generated from defaults!</li>\n";
} else {
    echo "<li><strong>⚠️ ISSUE:</strong> Settings are saved, checking if they match defaults...</li>\n";
    
    $diff_count = 0;
    foreach ($defaults['admin_menu'] as $key => $default_value) {
        $current_value = $current_settings['admin_menu'][$key] ?? null;
        if ($current_value !== $default_value && $current_value !== null) {
            $diff_count++;
            echo "<li>Changed: <code>{$key}</code> = <code>" . htmlspecialchars(print_r($current_value, true)) . "</code> (default: <code>" . htmlspecialchars(print_r($default_value, true)) . "</code>)</li>\n";
        }
    }
    
    if ($diff_count === 0) {
        echo "<li><strong>⚠️ ISSUE:</strong> All settings match defaults, but CSS is still generated!</li>\n";
    }
}

echo "</ul>\n\n";

echo "<h2>5. Expected Behavior (Commit 4de3336)</h2>\n";
echo "<pre>";
echo "When settings = defaults:
  → CSS generator should return MINIMAL or NO CSS for adminmenu
  → WordPress default styles should win
  → Only generate CSS for CHANGED values

Current behavior:
  → CSS generator ALWAYS generates full CSS
  → Even when all values are defaults
  → This overrides WordPress defaults!
";
echo "</pre>\n\n";

echo "<h2>6. Fix Required</h2>\n";
echo "<pre>";
echo "File: includes/class-woow-css-generator.php
Method: add_admin_menu_styles()

BEFORE (Current - BAD):
  - Always generates CSS for all properties
  - Uses default values from defaults.php
  - Overrides WordPress defaults

AFTER (Fixed - GOOD):
  - Check if value !== default
  - Only generate CSS for changed values
  - If all defaults → return empty CSS
  - WordPress defaults win!
";
echo "</pre>\n\n";

echo "<h2>7. Quick Test</h2>\n";
echo "<p>To test if this is the issue:</p>\n";
echo "<ol>\n";
echo "<li>Deactivate plugin</li>\n";
echo "<li>Delete option: <code>DELETE FROM wp_options WHERE option_name = 'woow_admin_settings'</code></li>\n";
echo "<li>Activate plugin</li>\n";
echo "<li>Check admin menu appearance (should look like WordPress default)</li>\n";
echo "<li>If it looks different → CSS generator is the problem!</li>\n";
echo "</ol>\n";
