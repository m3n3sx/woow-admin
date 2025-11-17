<?php
/**
 * Script to add missing color fields to all palettes
 * Run once to update palettes.php
 */

// Palettes that still need updating
$palettes_to_update = [
    'minimal_gray',
    'vibrant_purple',
    'ocean_blue',
    'bold_red',
    'midnight_gold',
    'neon_cyber',
];

// Missing fields template for each palette
$missing_fields = [
    'admin_bar' => [
        'submenu_hover_bg_color',
        'submenu_hover_text_color',
    ],
    'admin_menu' => [
        'active_bg_type',
        'active_bg_start',
        'active_bg_end',
        'submenu_hover_bg_color',
        'submenu_hover_text_color',
    ],
    'backgrounds' => [
        'body_background_color',
        'content_background_color',
        'wpbody_content_color',
    ],
    'typography' => [
        'link_color',
    ],
];

echo "Palettes to update:\n";
foreach ($palettes_to_update as $palette) {
    echo "- $palette\n";
}

echo "\nMissing fields per section:\n";
foreach ($missing_fields as $section => $fields) {
    echo "$section:\n";
    foreach ($fields as $field) {
        echo "  - $field\n";
    }
}

echo "\nManual update required for each palette:\n";
echo "1. Find palette in palettes.php\n";
echo "2. Add missing color fields with appropriate colors\n";
echo "3. Ensure colors match the palette theme\n";
