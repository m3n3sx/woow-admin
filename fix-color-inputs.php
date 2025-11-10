<?php
/**
 * Fix Color Inputs - Add default parameter to rgba_to_hex() calls
 * 
 * This script updates all color input value attributes to pass the data-default
 * value as a second parameter to rgba_to_hex(), ensuring empty values display
 * the default color instead of empty strings.
 *
 * Usage: php fix-color-inputs.php
 */

$tab_files = [
    'backgrounds-tab.php',
    'buttons-tab.php',
    'forms-tab.php',
    'login-tab.php',
    'menu-tab.php',
    'typography-tab.php',
    'widgets-tab.php',
];

$tabs_dir = __DIR__ . '/includes/templates/tabs/';
$fixed_count = 0;
$total_count = 0;

foreach ($tab_files as $file) {
    $filepath = $tabs_dir . $file;
    
    if (!file_exists($filepath)) {
        echo "⚠️  File not found: $file\n";
        continue;
    }
    
    $content = file_get_contents($filepath);
    $original = $content;
    
    // Pattern to match color inputs with rgba_to_hex() calls
    // Captures: variable name, data-default value
    $pattern = '/value="<\?php echo esc_attr\( WOOW_Admin::rgba_to_hex\( (\$\w+\[\'[^\']+\'\])(?: \?\? \'\')? \) \); \?>"[\s\S]*?data-default="(#[0-9A-Fa-f]{6}|rgba?\([^)]+\))"/';
    
    $content = preg_replace_callback($pattern, function($matches) use (&$total_count, &$fixed_count) {
        $total_count++;
        $var = $matches[1];
        $default = $matches[2];
        
        // Check if already has ?? '' and second parameter
        if (strpos($matches[0], '?? \'\'') !== false && strpos($matches[0], ', \'') !== false) {
            return $matches[0]; // Already fixed
        }
        
        $fixed_count++;
        
        // Build the replacement
        $replacement = str_replace(
            "WOOW_Admin::rgba_to_hex( $var )",
            "WOOW_Admin::rgba_to_hex( $var ?? '', '$default' )",
            $matches[0]
        );
        
        return $replacement;
    }, $content);
    
    // Save if changed
    if ($content !== $original) {
        file_put_contents($filepath, $content);
        echo "✅ Fixed $file\n";
    } else {
        echo "ℹ️  No changes needed in $file\n";
    }
}

echo "\n";
echo "═══════════════════════════════════════\n";
echo "Summary:\n";
echo "  Total color inputs found: $total_count\n";
echo "  Fixed: $fixed_count\n";
echo "  Already correct: " . ($total_count - $fixed_count) . "\n";
echo "═══════════════════════════════════════\n";
echo "\n✨ Done! All color inputs now have default fallbacks.\n";
