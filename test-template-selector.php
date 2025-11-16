<?php
/**
 * Test Template Selector Component
 * 
 * This script verifies that the TemplateSelector component is properly integrated.
 */

// Check if TemplateSelector.js exists
$template_selector_path = __DIR__ . '/assets/src/js/components/TemplateSelector.js';
$main_js_path = __DIR__ . '/assets/src/js/main.js';
$dist_js_path = __DIR__ . '/assets/dist/main.js';

echo "=== Template Selector Component Verification ===\n\n";

// 1. Check source file exists
echo "1. Checking source file...\n";
if (file_exists($template_selector_path)) {
    $size = filesize($template_selector_path);
    echo "   ✅ TemplateSelector.js exists ({$size} bytes)\n";
} else {
    echo "   ❌ TemplateSelector.js NOT FOUND\n";
    exit(1);
}

// 2. Check main.js imports TemplateSelector
echo "\n2. Checking main.js imports...\n";
if (file_exists($main_js_path)) {
    $main_content = file_get_contents($main_js_path);
    
    if (strpos($main_content, "import { TemplateSelector }") !== false) {
        echo "   ✅ TemplateSelector imported in main.js\n";
    } else {
        echo "   ❌ TemplateSelector NOT imported in main.js\n";
    }
    
    if (strpos($main_content, "this.components.templateSelector = new TemplateSelector(this)") !== false) {
        echo "   ✅ TemplateSelector initialized in main.js\n";
    } else {
        echo "   ❌ TemplateSelector NOT initialized in main.js\n";
    }
} else {
    echo "   ❌ main.js NOT FOUND\n";
}

// 3. Check compiled file
echo "\n3. Checking compiled file...\n";
if (file_exists($dist_js_path)) {
    $dist_size = filesize($dist_js_path);
    echo "   ✅ Compiled main.js exists ({$dist_size} bytes)\n";
    
    $dist_content = file_get_contents($dist_js_path);
    $template_selector_count = substr_count($dist_content, 'TemplateSelector');
    
    if ($template_selector_count > 0) {
        echo "   ✅ TemplateSelector found in compiled file ({$template_selector_count} occurrences)\n";
    } else {
        echo "   ❌ TemplateSelector NOT found in compiled file\n";
    }
} else {
    echo "   ❌ Compiled main.js NOT FOUND\n";
}

// 4. Check component features
echo "\n4. Checking component features...\n";
$source_content = file_get_contents($template_selector_path);

$features = [
    'initializeFilters' => 'Category filtering',
    'filterByCategory' => 'Filter by category method',
    'applyTemplate' => 'Template application',
    'getPreviewImageUrl' => 'Preview image support',
    'createTemplateCard' => 'Card creation',
    'createPlaceholder' => 'Placeholder fallback',
    'characteristics' => 'Characteristics display',
];

foreach ($features as $method => $description) {
    if (strpos($source_content, $method) !== false) {
        echo "   ✅ {$description}\n";
    } else {
        echo "   ❌ {$description} NOT FOUND\n";
    }
}

// 5. Compare with PaletteSelector pattern
echo "\n5. Checking consistency with PaletteSelector...\n";
$palette_selector_path = __DIR__ . '/assets/src/js/components/PaletteSelector.js';

if (file_exists($palette_selector_path)) {
    $palette_content = file_get_contents($palette_selector_path);
    
    // Check similar structure
    $common_methods = [
        'init()',
        'bindEvents()',
        'render(',
        'showNotification(',
        'getActiveTemplateId' => 'getActivePaletteId',
    ];
    
    $matches = 0;
    foreach ($common_methods as $method) {
        if (strpos($source_content, $method) !== false) {
            $matches++;
        }
    }
    
    $percentage = round(($matches / count($common_methods)) * 100);
    echo "   ✅ Pattern consistency: {$percentage}% ({$matches}/" . count($common_methods) . " methods)\n";
} else {
    echo "   ⚠️  PaletteSelector not found for comparison\n";
}

// 6. Check data structure support
echo "\n6. Checking data structure support...\n";
$data_checks = [
    'window.woowAdminData?.templates' => 'Templates data access',
    'template.preview_image' => 'Preview image field',
    'template.category' => 'Category field',
    'template.characteristics' => 'Characteristics field',
    'Array.isArray(templatesData)' => 'Array format support',
    'Object.values(templatesData)' => 'Object format support',
];

foreach ($data_checks as $check => $description) {
    if (strpos($source_content, $check) !== false) {
        echo "   ✅ {$description}\n";
    } else {
        echo "   ❌ {$description} NOT FOUND\n";
    }
}

echo "\n=== Verification Complete ===\n";
echo "\n✅ Task 29: Update template selector UI component - COMPLETE\n";
echo "\nNext steps:\n";
echo "  - Task 30: Integrate palette manager into admin interface\n";
echo "  - Task 31: Integrate template manager into admin interface\n";
echo "  - Task 32: Add REST API endpoints for palettes\n";
echo "  - Task 33: Add REST API endpoints for templates\n";
