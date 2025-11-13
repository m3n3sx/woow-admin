<?php
/**
 * Debug Template Save Issue
 * 
 * Problem: Gdy ustawiasz szablon, zapisują się tylko backgrounds i login_page,
 * ale NIE admin_menu. Dopiero gdy zmienisz coś w admin_menu i zapiszesz,
 * wtedy zapisują się wszystkie defaulty.
 */

require_once('../../../wp-load.php');

echo "<h1>Template Save Debug</h1>\n\n";

// Get current settings
$current_settings = get_option('woow_admin_settings', []);

echo "<h2>1. Current Saved Settings</h2>\n";
echo "<pre>";
print_r($current_settings);
echo "</pre>\n\n";

echo "<h2>2. Which Sections Are Saved?</h2>\n";
echo "<ul>\n";
$sections = ['admin_bar', 'admin_menu', 'backgrounds', 'login_page', 'dashboard_widgets', 'form_controls', 'buttons', 'typography'];
foreach ($sections as $section) {
    $exists = isset($current_settings[$section]) && !empty($current_settings[$section]);
    $color = $exists ? 'green' : 'red';
    $status = $exists ? '✅ EXISTS' : '❌ MISSING';
    echo "<li style='color: {$color};'><strong>{$section}:</strong> {$status}</li>\n";
}
echo "</ul>\n\n";

echo "<h2>3. Problem Analysis</h2>\n";

if (!isset($current_settings['admin_menu']) || empty($current_settings['admin_menu'])) {
    echo "<div style='background: #fee; padding: 20px; border-left: 4px solid red;'>\n";
    echo "<h3>❌ PROBLEM FOUND!</h3>\n";
    echo "<p><strong>admin_menu</strong> section is MISSING from saved settings!</p>\n";
    echo "<p>This means:</p>\n";
    echo "<ul>\n";
    echo "<li>When you set a template, it only saves backgrounds and login_page</li>\n";
    echo "<li>admin_menu settings are NOT saved</li>\n";
    echo "<li>CSS generator uses defaults from defaults.php</li>\n";
    echo "<li>When you change left margin and save, THEN all admin_menu defaults get saved</li>\n";
    echo "</ul>\n";
    echo "</div>\n\n";
    
    echo "<h3>Why This Happens</h3>\n";
    echo "<p>Template application probably:</p>\n";
    echo "<ol>\n";
    echo "<li>Loads template data (only backgrounds + login_page)</li>\n";
    echo "<li>Saves ONLY those sections</li>\n";
    echo "<li>Doesn't save admin_menu section</li>\n";
    echo "<li>CSS generator then uses defaults.php for admin_menu</li>\n";
    echo "</ol>\n\n";
    
    echo "<h3>Solution</h3>\n";
    echo "<p>When applying template, we need to:</p>\n";
    echo "<ol>\n";
    echo "<li>Load template data</li>\n";
    echo "<li>Merge with FULL defaults (all sections)</li>\n";
    echo "<li>Save ALL sections, not just template sections</li>\n";
    echo "</ol>\n";
} else {
    echo "<div style='background: #efe; padding: 20px; border-left: 4px solid green;'>\n";
    echo "<h3>✅ admin_menu section EXISTS</h3>\n";
    echo "<p>Settings are saved correctly.</p>\n";
    echo "</div>\n\n";
    
    // Check if it's all defaults
    require_once('includes/defaults.php');
    $defaults = woow_get_default_settings();
    
    $is_all_defaults = true;
    foreach ($defaults['admin_menu'] as $key => $default_value) {
        $current_value = $current_settings['admin_menu'][$key] ?? null;
        if ($current_value !== $default_value && $current_value !== null) {
            $is_all_defaults = false;
            break;
        }
    }
    
    if ($is_all_defaults) {
        echo "<p style='color: orange;'>⚠️ All admin_menu values are defaults (no custom changes)</p>\n";
    } else {
        echo "<p style='color: green;'>✅ Some admin_menu values are customized</p>\n";
    }
}

echo "<h2>4. Template Application Flow</h2>\n";
echo "<p>Check where templates are applied:</p>\n";
echo "<ul>\n";
echo "<li>File: <code>includes/class-woow-settings.php</code></li>\n";
echo "<li>Method: <code>apply_template()</code> or similar</li>\n";
echo "<li>Look for: Template data loading and saving</li>\n";
echo "</ul>\n\n";

echo "<h2>5. Expected Behavior</h2>\n";
echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>\n";
echo "<tr><th>Action</th><th>Current Behavior</th><th>Expected Behavior</th></tr>\n";
echo "<tr>\n";
echo "<td>Apply Template</td>\n";
echo "<td style='color: red;'>❌ Saves only backgrounds + login_page</td>\n";
echo "<td style='color: green;'>✅ Saves ALL sections (merged with defaults)</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td>admin_menu CSS</td>\n";
echo "<td style='color: red;'>❌ Uses defaults.php (not saved)</td>\n";
echo "<td style='color: green;'>✅ Uses saved settings</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td>Change admin_menu option</td>\n";
echo "<td style='color: orange;'>⚠️ Saves ALL defaults + change</td>\n";
echo "<td style='color: green;'>✅ Saves only changed value</td>\n";
echo "</tr>\n";
echo "</table>\n\n";

echo "<h2>6. Fix Required</h2>\n";
echo "<pre>";
echo "// In template application code:\n";
echo "\n";
echo "// BEFORE (Current - BAD):\n";
echo "function apply_template(\$template_name) {\n";
echo "    \$template_data = load_template(\$template_name);\n";
echo "    // Only saves template sections (backgrounds, login_page)\n";
echo "    update_option('woow_admin_settings', \$template_data);\n";
echo "}\n";
echo "\n";
echo "// AFTER (Fixed - GOOD):\n";
echo "function apply_template(\$template_name) {\n";
echo "    \$template_data = load_template(\$template_name);\n";
echo "    \$defaults = woow_get_default_settings();\n";
echo "    \n";
echo "    // Merge template with defaults (all sections)\n";
echo "    \$full_settings = array_replace_recursive(\$defaults, \$template_data);\n";
echo "    \n";
echo "    // Save ALL sections\n";
echo "    update_option('woow_admin_settings', \$full_settings);\n";
echo "}\n";
echo "</pre>\n\n";

echo "<h2>7. Quick Test</h2>\n";
echo "<p>To verify the fix:</p>\n";
echo "<ol>\n";
echo "<li>Delete settings: <code>wp option delete woow_admin_settings</code></li>\n";
echo "<li>Apply template</li>\n";
echo "<li>Check this page again</li>\n";
echo "<li>Expected: ALL sections should exist (including admin_menu)</li>\n";
echo "</ol>\n";
