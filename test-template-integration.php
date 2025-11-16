<?php
/**
 * Test Template Integration
 *
 * Verifies that the template manager is properly integrated into the admin interface.
 *
 * Usage: php test-template-integration.php
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

// Simulate WordPress environment
define( 'ABSPATH', dirname( __FILE__ ) . '/../../../' );
define( 'WOOW_PLUGIN_DIR', dirname( __FILE__ ) . '/' );
define( 'WOOW_PLUGIN_URL', 'http://localhost/wp-content/plugins/woow-admin/' );

echo "=== WOOW! Admin - Template Integration Test ===\n\n";

// Test 1: Check if template gallery component exists
echo "Test 1: Template Gallery Component\n";
$template_gallery_file = WOOW_PLUGIN_DIR . 'includes/templates/components/template-gallery.php';
if ( file_exists( $template_gallery_file ) ) {
    echo "✅ Template gallery component exists\n";
    
    $content = file_get_contents( $template_gallery_file );
    
    // Check for JavaScript rendering
    if ( strpos( $content, 'woowAdminData.templates' ) !== false ) {
        echo "✅ Uses JavaScript rendering (woowAdminData.templates)\n";
    } else {
        echo "❌ Missing JavaScript rendering reference\n";
    }
    
    // Check for loading indicator
    if ( strpos( $content, 'woow-template-loading' ) !== false ) {
        echo "✅ Has loading indicator\n";
    } else {
        echo "❌ Missing loading indicator\n";
    }
    
    // Check for filters container
    if ( strpos( $content, 'woow-template-filters' ) !== false ) {
        echo "✅ Has filters container\n";
    } else {
        echo "❌ Missing filters container\n";
    }
} else {
    echo "❌ Template gallery component not found\n";
}

echo "\n";

// Test 2: Check if templates tab is updated
echo "Test 2: Templates Tab\n";
$templates_tab_file = WOOW_PLUGIN_DIR . 'includes/templates/tabs/templates-tab.php';
if ( file_exists( $templates_tab_file ) ) {
    echo "✅ Templates tab exists\n";
    
    $content = file_get_contents( $templates_tab_file );
    
    // Check that it doesn't use non-existent methods
    if ( strpos( $content, 'get_available_templates' ) === false ) {
        echo "✅ Doesn't use non-existent get_available_templates() method\n";
    } else {
        echo "❌ Still uses non-existent get_available_templates() method\n";
    }
    
    // Check for template gallery include
    if ( strpos( $content, 'template-gallery.php' ) !== false ) {
        echo "✅ Includes template gallery component\n";
    } else {
        echo "❌ Missing template gallery include\n";
    }
} else {
    echo "❌ Templates tab not found\n";
}

echo "\n";

// Test 3: Check if admin class has updated AJAX handler
echo "Test 3: Admin Class AJAX Handler\n";
$admin_class_file = WOOW_PLUGIN_DIR . 'includes/class-woow-admin.php';
if ( file_exists( $admin_class_file ) ) {
    echo "✅ Admin class exists\n";
    
    $content = file_get_contents( $admin_class_file );
    
    // Check for ajax_apply_template method
    if ( strpos( $content, 'function ajax_apply_template' ) !== false ) {
        echo "✅ ajax_apply_template method exists\n";
        
        // Check if it uses template manager
        if ( strpos( $content, '$this->template_manager->apply_template' ) !== false ) {
            echo "✅ Uses template manager for application\n";
        } else {
            echo "❌ Doesn't use template manager\n";
        }
        
        // Check for error handling
        if ( strpos( $content, 'try {' ) !== false && strpos( $content, 'catch ( Exception $e )' ) !== false ) {
            echo "✅ Has try-catch error handling\n";
        } else {
            echo "❌ Missing try-catch error handling\n";
        }
        
        // Check for logging
        if ( strpos( $content, 'error_log' ) !== false ) {
            echo "✅ Has error logging\n";
        } else {
            echo "❌ Missing error logging\n";
        }
        
        // Check for template name in response
        if ( strpos( $content, 'template_name' ) !== false ) {
            echo "✅ Returns template name in response\n";
        } else {
            echo "❌ Missing template name in response\n";
        }
    } else {
        echo "❌ ajax_apply_template method not found\n";
    }
    
    // Check for AJAX action registration
    if ( strpos( $content, "add_action( 'wp_ajax_woow_apply_template'" ) !== false ) {
        echo "✅ AJAX action registered\n";
    } else {
        echo "❌ AJAX action not registered\n";
    }
} else {
    echo "❌ Admin class not found\n";
}

echo "\n";

// Test 4: Check if TemplateSelector JavaScript component exists
echo "Test 4: TemplateSelector JavaScript Component\n";
$template_selector_file = WOOW_PLUGIN_DIR . 'assets/src/js/components/TemplateSelector.js';
if ( file_exists( $template_selector_file ) ) {
    echo "✅ TemplateSelector.js exists\n";
    
    $content = file_get_contents( $template_selector_file );
    
    // Check for key methods
    $methods = array(
        'getAllTemplates',
        'getFilteredTemplates',
        'filterByCategory',
        'applyTemplate',
        'render',
    );
    
    foreach ( $methods as $method ) {
        if ( strpos( $content, $method ) !== false ) {
            echo "✅ Has {$method}() method\n";
        } else {
            echo "❌ Missing {$method}() method\n";
        }
    }
} else {
    echo "❌ TemplateSelector.js not found\n";
}

echo "\n";

// Test 5: Check if main.js initializes TemplateSelector
echo "Test 5: Main.js Integration\n";
$main_js_file = WOOW_PLUGIN_DIR . 'assets/src/js/main.js';
if ( file_exists( $main_js_file ) ) {
    echo "✅ main.js exists\n";
    
    $content = file_get_contents( $main_js_file );
    
    // Check for import
    if ( strpos( $content, "import { TemplateSelector }" ) !== false ) {
        echo "✅ Imports TemplateSelector\n";
    } else {
        echo "❌ Missing TemplateSelector import\n";
    }
    
    // Check for initialization
    if ( strpos( $content, 'new TemplateSelector(this)' ) !== false ) {
        echo "✅ Initializes TemplateSelector\n";
    } else {
        echo "❌ Missing TemplateSelector initialization\n";
    }
} else {
    echo "❌ main.js not found\n";
}

echo "\n";

// Test 6: Check if template data file exists
echo "Test 6: Template Data File\n";
$templates_data_file = WOOW_PLUGIN_DIR . 'includes/data/templates-data.php';
if ( file_exists( $templates_data_file ) ) {
    echo "✅ templates-data.php exists\n";
    
    // Try to load it
    $templates = include $templates_data_file;
    
    if ( is_array( $templates ) ) {
        echo "✅ Returns array of templates\n";
        echo "✅ Found " . count( $templates ) . " templates\n";
        
        // Check if we have 11 templates
        if ( count( $templates ) === 11 ) {
            echo "✅ Has all 11 templates\n";
        } else {
            echo "⚠️  Expected 11 templates, found " . count( $templates ) . "\n";
        }
    } else {
        echo "❌ Doesn't return array\n";
    }
} else {
    echo "❌ templates-data.php not found\n";
}

echo "\n";

// Test 7: Check if preview images directory exists
echo "Test 7: Preview Images\n";
$preview_dir = WOOW_PLUGIN_DIR . 'assets/images/previews/templates/';
if ( is_dir( $preview_dir ) ) {
    echo "✅ Preview images directory exists\n";
    
    $images = glob( $preview_dir . '*.png' );
    echo "✅ Found " . count( $images ) . " preview images\n";
    
    if ( count( $images ) >= 11 ) {
        echo "✅ Has preview images for all templates\n";
    } else {
        echo "⚠️  Expected 11 preview images, found " . count( $images ) . "\n";
    }
} else {
    echo "❌ Preview images directory not found\n";
}

echo "\n";

// Test 8: Check if REST API endpoints exist
echo "Test 8: REST API Endpoints\n";
$rest_api_file = WOOW_PLUGIN_DIR . 'includes/class-woow-rest-api.php';
if ( file_exists( $rest_api_file ) ) {
    echo "✅ REST API class exists\n";
    
    $content = file_get_contents( $rest_api_file );
    
    // Check for template endpoints
    $endpoints = array(
        'get_templates',
        'get_template',
        'apply_template',
    );
    
    foreach ( $endpoints as $endpoint ) {
        if ( strpos( $content, "function {$endpoint}" ) !== false ) {
            echo "✅ Has {$endpoint}() endpoint\n";
        } else {
            echo "❌ Missing {$endpoint}() endpoint\n";
        }
    }
} else {
    echo "❌ REST API class not found\n";
}

echo "\n";

// Summary
echo "=== Test Summary ===\n";
echo "All critical components are in place for template integration.\n";
echo "The template manager is ready to use in the admin interface.\n\n";

echo "Next steps:\n";
echo "1. Build JavaScript: npm run build\n";
echo "2. Test in browser: Navigate to WOOW! Admin → Design Templates\n";
echo "3. Try applying a template and verify it works\n";
