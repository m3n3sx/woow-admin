<?php
/**
 * Performance Testing Script for WOOW! Admin
 * 
 * Tests performance metrics for palettes and templates:
 * - Palette list loading time (target < 500ms)
 * - Template list loading time (target < 500ms)
 * - Palette application time (target < 2s)
 * - Template application time (target < 2s)
 * - CSS regeneration time (target < 1s)
 * 
 * Usage: php test-performance.php
 */

// Load WordPress
require_once dirname(__FILE__) . '/../../../wp-load.php';

// Ensure we're in admin context
if (!is_admin()) {
    define('WP_ADMIN', true);
}

// Load plugin files
require_once dirname(__FILE__) . '/woow-admin.php';

class WOOW_Performance_Tester {
    private $results = [];
    private $palette_manager;
    private $template_manager;
    private $css_generator;
    private $settings;
    
    public function __construct() {
        $this->settings = new WOOW_Settings();
        $this->palette_manager = new WOOW_Palette_Manager($this->settings);
        $this->template_manager = new WOOW_Template_Manager($this->settings);
        $this->css_generator = new WOOW_CSS_Generator($this->settings);
    }
    
    /**
     * Run all performance tests
     */
    public function run_all_tests() {
        echo "\n";
        echo "╔════════════════════════════════════════════════════════════════╗\n";
        echo "║         WOOW! Admin - Performance Testing Suite               ║\n";
        echo "╚════════════════════════════════════════════════════════════════╝\n";
        echo "\n";
        
        // Test 1: Palette list loading
        $this->test_palette_list_loading();
        
        // Test 2: Template list loading
        $this->test_template_list_loading();
        
        // Test 3: Palette application
        $this->test_palette_application();
        
        // Test 4: Template application
        $this->test_template_application();
        
        // Test 5: CSS regeneration
        $this->test_css_regeneration();
        
        // Display summary
        $this->display_summary();
    }
    
    /**
     * Test palette list loading time
     * Target: < 500ms
     */
    private function test_palette_list_loading() {
        echo "Test 1: Palette List Loading Time\n";
        echo str_repeat("-", 64) . "\n";
        
        $iterations = 10;
        $times = [];
        
        // Warm-up run
        $this->palette_manager->get_all_palettes();
        
        // Clear any caches
        wp_cache_flush();
        
        // Run multiple iterations
        for ($i = 0; $i < $iterations; $i++) {
            // Clear cache before each test
            wp_cache_delete('woow_palettes_v1');
            
            $start = microtime(true);
            $palettes = $this->palette_manager->get_all_palettes();
            $end = microtime(true);
            
            $time_ms = ($end - $start) * 1000;
            $times[] = $time_ms;
            
            echo sprintf("  Iteration %2d: %6.2f ms (%d palettes loaded)\n", 
                $i + 1, $time_ms, count($palettes));
        }
        
        $avg_time = array_sum($times) / count($times);
        $min_time = min($times);
        $max_time = max($times);
        
        $passed = $avg_time < 500;
        
        $this->results['palette_list_loading'] = [
            'avg_time' => $avg_time,
            'min_time' => $min_time,
            'max_time' => $max_time,
            'target' => 500,
            'passed' => $passed
        ];
        
        echo "\n";
        echo sprintf("  Average: %.2f ms\n", $avg_time);
        echo sprintf("  Min:     %.2f ms\n", $min_time);
        echo sprintf("  Max:     %.2f ms\n", $max_time);
        echo sprintf("  Target:  < 500 ms\n");
        echo sprintf("  Status:  %s\n", $passed ? "✅ PASSED" : "❌ FAILED");
        echo "\n\n";
    }
    
    /**
     * Test template list loading time
     * Target: < 500ms
     */
    private function test_template_list_loading() {
        echo "Test 2: Template List Loading Time\n";
        echo str_repeat("-", 64) . "\n";
        
        $iterations = 10;
        $times = [];
        
        // Warm-up run
        $this->template_manager->get_all_templates();
        
        // Clear any caches
        wp_cache_flush();
        
        // Run multiple iterations
        for ($i = 0; $i < $iterations; $i++) {
            // Clear cache before each test
            wp_cache_delete('woow_templates_v1');
            
            $start = microtime(true);
            $templates = $this->template_manager->get_all_templates();
            $end = microtime(true);
            
            $time_ms = ($end - $start) * 1000;
            $times[] = $time_ms;
            
            echo sprintf("  Iteration %2d: %6.2f ms (%d templates loaded)\n", 
                $i + 1, $time_ms, count($templates));
        }
        
        $avg_time = array_sum($times) / count($times);
        $min_time = min($times);
        $max_time = max($times);
        
        $passed = $avg_time < 500;
        
        $this->results['template_list_loading'] = [
            'avg_time' => $avg_time,
            'min_time' => $min_time,
            'max_time' => $max_time,
            'target' => 500,
            'passed' => $passed
        ];
        
        echo "\n";
        echo sprintf("  Average: %.2f ms\n", $avg_time);
        echo sprintf("  Min:     %.2f ms\n", $min_time);
        echo sprintf("  Max:     %.2f ms\n", $max_time);
        echo sprintf("  Target:  < 500 ms\n");
        echo sprintf("  Status:  %s\n", $passed ? "✅ PASSED" : "❌ FAILED");
        echo "\n\n";
    }
    
    /**
     * Test palette application time
     * Target: < 2s
     */
    private function test_palette_application() {
        echo "Test 3: Palette Application Time\n";
        echo str_repeat("-", 64) . "\n";
        
        $palettes = $this->palette_manager->get_all_palettes();
        $palette_ids = array_keys($palettes);
        
        // Test first 3 palettes
        $test_palettes = array_slice($palette_ids, 0, 3);
        $times = [];
        
        foreach ($test_palettes as $palette_id) {
            $palette = $palettes[$palette_id];
            
            $start = microtime(true);
            $result = $this->palette_manager->apply_palette($palette_id);
            $end = microtime(true);
            
            $time_ms = ($end - $start) * 1000;
            $times[] = $time_ms;
            
            echo sprintf("  %-20s: %7.2f ms [%s]\n", 
                $palette['name'], 
                $time_ms,
                $result ? "✓" : "✗"
            );
        }
        
        $avg_time = array_sum($times) / count($times);
        $min_time = min($times);
        $max_time = max($times);
        
        $passed = $avg_time < 2000;
        
        $this->results['palette_application'] = [
            'avg_time' => $avg_time,
            'min_time' => $min_time,
            'max_time' => $max_time,
            'target' => 2000,
            'passed' => $passed
        ];
        
        echo "\n";
        echo sprintf("  Average: %.2f ms\n", $avg_time);
        echo sprintf("  Min:     %.2f ms\n", $min_time);
        echo sprintf("  Max:     %.2f ms\n", $max_time);
        echo sprintf("  Target:  < 2000 ms\n");
        echo sprintf("  Status:  %s\n", $passed ? "✅ PASSED" : "❌ FAILED");
        echo "\n\n";
    }
    
    /**
     * Test template application time
     * Target: < 2s
     */
    private function test_template_application() {
        echo "Test 4: Template Application Time\n";
        echo str_repeat("-", 64) . "\n";
        
        $templates = $this->template_manager->get_all_templates();
        $template_ids = array_keys($templates);
        
        // Test first 3 templates
        $test_templates = array_slice($template_ids, 0, 3);
        $times = [];
        
        foreach ($test_templates as $template_id) {
            $template = $templates[$template_id];
            
            $start = microtime(true);
            $result = $this->template_manager->apply_template($template_id);
            $end = microtime(true);
            
            $time_ms = ($end - $start) * 1000;
            $times[] = $time_ms;
            
            echo sprintf("  %-20s: %7.2f ms [%s]\n", 
                $template['name'], 
                $time_ms,
                $result ? "✓" : "✗"
            );
        }
        
        $avg_time = array_sum($times) / count($times);
        $min_time = min($times);
        $max_time = max($times);
        
        $passed = $avg_time < 2000;
        
        $this->results['template_application'] = [
            'avg_time' => $avg_time,
            'min_time' => $min_time,
            'max_time' => $max_time,
            'target' => 2000,
            'passed' => $passed
        ];
        
        echo "\n";
        echo sprintf("  Average: %.2f ms\n", $avg_time);
        echo sprintf("  Min:     %.2f ms\n", $min_time);
        echo sprintf("  Max:     %.2f ms\n", $max_time);
        echo sprintf("  Target:  < 2000 ms\n");
        echo sprintf("  Status:  %s\n", $passed ? "✅ PASSED" : "❌ FAILED");
        echo "\n\n";
    }
    
    /**
     * Test CSS regeneration time
     * Target: < 1s
     */
    private function test_css_regeneration() {
        echo "Test 5: CSS Regeneration Time\n";
        echo str_repeat("-", 64) . "\n";
        
        $iterations = 10;
        $times = [];
        
        // Run multiple iterations
        for ($i = 0; $i < $iterations; $i++) {
            $start = microtime(true);
            $this->css_generator->generate();
            $end = microtime(true);
            
            $time_ms = ($end - $start) * 1000;
            $times[] = $time_ms;
            
            echo sprintf("  Iteration %2d: %6.2f ms\n", $i + 1, $time_ms);
        }
        
        $avg_time = array_sum($times) / count($times);
        $min_time = min($times);
        $max_time = max($times);
        
        $passed = $avg_time < 1000;
        
        $this->results['css_regeneration'] = [
            'avg_time' => $avg_time,
            'min_time' => $min_time,
            'max_time' => $max_time,
            'target' => 1000,
            'passed' => $passed
        ];
        
        echo "\n";
        echo sprintf("  Average: %.2f ms\n", $avg_time);
        echo sprintf("  Min:     %.2f ms\n", $min_time);
        echo sprintf("  Max:     %.2f ms\n", $max_time);
        echo sprintf("  Target:  < 1000 ms\n");
        echo sprintf("  Status:  %s\n", $passed ? "✅ PASSED" : "❌ FAILED");
        echo "\n\n";
    }
    
    /**
     * Display test summary
     */
    private function display_summary() {
        echo "╔════════════════════════════════════════════════════════════════╗\n";
        echo "║                      Test Summary                              ║\n";
        echo "╚════════════════════════════════════════════════════════════════╝\n";
        echo "\n";
        
        $all_passed = true;
        
        foreach ($this->results as $test_name => $result) {
            $status = $result['passed'] ? "✅ PASSED" : "❌ FAILED";
            $all_passed = $all_passed && $result['passed'];
            
            $test_label = ucwords(str_replace('_', ' ', $test_name));
            
            echo sprintf("%-30s: %s\n", $test_label, $status);
            echo sprintf("  Average: %6.2f ms (Target: < %.0f ms)\n", 
                $result['avg_time'], 
                $result['target']
            );
            
            if (!$result['passed']) {
                $diff = $result['avg_time'] - $result['target'];
                echo sprintf("  ⚠️  Exceeded target by %.2f ms (%.1f%%)\n", 
                    $diff,
                    ($diff / $result['target']) * 100
                );
            }
            
            echo "\n";
        }
        
        echo str_repeat("=", 64) . "\n";
        echo sprintf("Overall Status: %s\n", $all_passed ? "✅ ALL TESTS PASSED" : "❌ SOME TESTS FAILED");
        echo str_repeat("=", 64) . "\n";
        echo "\n";
        
        // Performance recommendations
        if (!$all_passed) {
            echo "Performance Recommendations:\n";
            echo str_repeat("-", 64) . "\n";
            
            if (!$this->results['palette_list_loading']['passed']) {
                echo "• Palette List Loading:\n";
                echo "  - Implement caching with wp_cache_set()\n";
                echo "  - Consider lazy loading palette data\n";
                echo "  - Optimize palette data structure\n\n";
            }
            
            if (!$this->results['template_list_loading']['passed']) {
                echo "• Template List Loading:\n";
                echo "  - Implement caching with wp_cache_set()\n";
                echo "  - Consider lazy loading template data\n";
                echo "  - Optimize template data structure\n\n";
            }
            
            if (!$this->results['palette_application']['passed']) {
                echo "• Palette Application:\n";
                echo "  - Batch database updates\n";
                echo "  - Defer CSS regeneration\n";
                echo "  - Optimize settings merge operation\n\n";
            }
            
            if (!$this->results['template_application']['passed']) {
                echo "• Template Application:\n";
                echo "  - Batch database updates\n";
                echo "  - Defer CSS regeneration\n";
                echo "  - Optimize settings merge operation\n\n";
            }
            
            if (!$this->results['css_regeneration']['passed']) {
                echo "• CSS Regeneration:\n";
                echo "  - Optimize CSS generation algorithm\n";
                echo "  - Cache generated CSS\n";
                echo "  - Minimize string concatenation\n\n";
            }
        }
    }
    
    /**
     * Export results to JSON
     */
    public function export_results($filename = 'performance-results.json') {
        $export_data = [
            'timestamp' => date('Y-m-d H:i:s'),
            'php_version' => PHP_VERSION,
            'wordpress_version' => get_bloginfo('version'),
            'results' => $this->results
        ];
        
        $json = json_encode($export_data, JSON_PRETTY_PRINT);
        file_put_contents(dirname(__FILE__) . '/' . $filename, $json);
        
        echo "Results exported to: {$filename}\n\n";
    }
}

// Run tests
try {
    $tester = new WOOW_Performance_Tester();
    $tester->run_all_tests();
    $tester->export_results();
    
    echo "Performance testing completed successfully!\n";
    exit(0);
    
} catch (Exception $e) {
    echo "\n❌ Error during performance testing:\n";
    echo $e->getMessage() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
