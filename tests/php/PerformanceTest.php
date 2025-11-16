<?php
/**
 * Performance Tests for WOOW! Admin
 * 
 * Tests performance metrics for palettes and templates
 * 
 * @package WOOW_Admin
 * @subpackage Tests
 */

use PHPUnit\Framework\TestCase;

class PerformanceTest extends TestCase {
    private $palette_manager;
    private $template_manager;
    private $css_generator;
    private $settings;
    
    protected function setUp(): void {
        parent::setUp();
        
        // Initialize components
        $this->settings = new WOOW_Settings();
        $this->palette_manager = new WOOW_Palette_Manager($this->settings);
        $this->template_manager = new WOOW_Template_Manager($this->settings);
        $this->css_generator = new WOOW_CSS_Generator($this->settings);
    }
    
    /**
     * Test palette list loading performance
     * Target: < 500ms
     */
    public function test_palette_list_loading_performance() {
        $iterations = 5;
        $times = [];
        
        // Warm-up
        $this->palette_manager->get_all_palettes();
        
        // Run test iterations
        for ($i = 0; $i < $iterations; $i++) {
            // Clear cache
            if (function_exists('wp_cache_delete')) {
                wp_cache_delete('woow_palettes_v1');
            }
            
            $start = microtime(true);
            $palettes = $this->palette_manager->get_all_palettes();
            $end = microtime(true);
            
            $time_ms = ($end - $start) * 1000;
            $times[] = $time_ms;
            
            // Verify palettes loaded
            $this->assertIsArray($palettes);
            $this->assertGreaterThanOrEqual(10, count($palettes), 'Should load at least 10 palettes');
        }
        
        $avg_time = array_sum($times) / count($times);
        
        // Assert performance target
        $this->assertLessThan(
            500,
            $avg_time,
            sprintf('Palette list loading took %.2f ms (target: < 500 ms)', $avg_time)
        );
        
        // Log performance data
        fwrite(STDERR, sprintf("\nPalette List Loading: %.2f ms (avg of %d runs)\n", $avg_time, $iterations));
    }
    
    /**
     * Test template list loading performance
     * Target: < 500ms
     */
    public function test_template_list_loading_performance() {
        $iterations = 5;
        $times = [];
        
        // Warm-up
        $this->template_manager->get_all_templates();
        
        // Run test iterations
        for ($i = 0; $i < $iterations; $i++) {
            // Clear cache
            if (function_exists('wp_cache_delete')) {
                wp_cache_delete('woow_templates_v1');
            }
            
            $start = microtime(true);
            $templates = $this->template_manager->get_all_templates();
            $end = microtime(true);
            
            $time_ms = ($end - $start) * 1000;
            $times[] = $time_ms;
            
            // Verify templates loaded
            $this->assertIsArray($templates);
            $this->assertGreaterThanOrEqual(11, count($templates), 'Should load at least 11 templates');
        }
        
        $avg_time = array_sum($times) / count($times);
        
        // Assert performance target
        $this->assertLessThan(
            500,
            $avg_time,
            sprintf('Template list loading took %.2f ms (target: < 500 ms)', $avg_time)
        );
        
        // Log performance data
        fwrite(STDERR, sprintf("\nTemplate List Loading: %.2f ms (avg of %d runs)\n", $avg_time, $iterations));
    }
    
    /**
     * Test palette application performance
     * Target: < 2000ms
     */
    public function test_palette_application_performance() {
        $palettes = $this->palette_manager->get_all_palettes();
        $this->assertNotEmpty($palettes, 'Palettes should be available');
        
        // Test first palette
        $palette_id = array_key_first($palettes);
        $times = [];
        $iterations = 3;
        
        for ($i = 0; $i < $iterations; $i++) {
            $start = microtime(true);
            $result = $this->palette_manager->apply_palette($palette_id);
            $end = microtime(true);
            
            $time_ms = ($end - $start) * 1000;
            $times[] = $time_ms;
            
            // Verify application succeeded
            $this->assertTrue($result, 'Palette application should succeed');
        }
        
        $avg_time = array_sum($times) / count($times);
        
        // Assert performance target
        $this->assertLessThan(
            2000,
            $avg_time,
            sprintf('Palette application took %.2f ms (target: < 2000 ms)', $avg_time)
        );
        
        // Log performance data
        fwrite(STDERR, sprintf("\nPalette Application: %.2f ms (avg of %d runs)\n", $avg_time, $iterations));
    }
    
    /**
     * Test template application performance
     * Target: < 2000ms
     */
    public function test_template_application_performance() {
        $templates = $this->template_manager->get_all_templates();
        $this->assertNotEmpty($templates, 'Templates should be available');
        
        // Test first template
        $template_id = array_key_first($templates);
        $times = [];
        $iterations = 3;
        
        for ($i = 0; $i < $iterations; $i++) {
            $start = microtime(true);
            $result = $this->template_manager->apply_template($template_id);
            $end = microtime(true);
            
            $time_ms = ($end - $start) * 1000;
            $times[] = $time_ms;
            
            // Verify application succeeded
            $this->assertTrue($result, 'Template application should succeed');
        }
        
        $avg_time = array_sum($times) / count($times);
        
        // Assert performance target
        $this->assertLessThan(
            2000,
            $avg_time,
            sprintf('Template application took %.2f ms (target: < 2000 ms)', $avg_time)
        );
        
        // Log performance data
        fwrite(STDERR, sprintf("\nTemplate Application: %.2f ms (avg of %d runs)\n", $avg_time, $iterations));
    }
    
    /**
     * Test CSS regeneration performance
     * Target: < 1000ms
     */
    public function test_css_regeneration_performance() {
        $iterations = 5;
        $times = [];
        
        for ($i = 0; $i < $iterations; $i++) {
            $start = microtime(true);
            $css = $this->css_generator->generate();
            $end = microtime(true);
            
            $time_ms = ($end - $start) * 1000;
            $times[] = $time_ms;
            
            // Verify CSS generated
            $this->assertIsString($css);
            $this->assertNotEmpty($css, 'CSS should be generated');
        }
        
        $avg_time = array_sum($times) / count($times);
        
        // Assert performance target
        $this->assertLessThan(
            1000,
            $avg_time,
            sprintf('CSS regeneration took %.2f ms (target: < 1000 ms)', $avg_time)
        );
        
        // Log performance data
        fwrite(STDERR, sprintf("\nCSS Regeneration: %.2f ms (avg of %d runs)\n", $avg_time, $iterations));
    }
    
    /**
     * Test memory usage during palette loading
     */
    public function test_palette_loading_memory_usage() {
        $memory_before = memory_get_usage();
        
        $palettes = $this->palette_manager->get_all_palettes();
        
        $memory_after = memory_get_usage();
        $memory_used = $memory_after - $memory_before;
        $memory_used_mb = $memory_used / 1024 / 1024;
        
        // Should use less than 5MB
        $this->assertLessThan(
            5 * 1024 * 1024,
            $memory_used,
            sprintf('Palette loading used %.2f MB (should be < 5 MB)', $memory_used_mb)
        );
        
        fwrite(STDERR, sprintf("\nPalette Loading Memory: %.2f MB\n", $memory_used_mb));
    }
    
    /**
     * Test memory usage during template loading
     */
    public function test_template_loading_memory_usage() {
        $memory_before = memory_get_usage();
        
        $templates = $this->template_manager->get_all_templates();
        
        $memory_after = memory_get_usage();
        $memory_used = $memory_after - $memory_before;
        $memory_used_mb = $memory_used / 1024 / 1024;
        
        // Should use less than 5MB
        $this->assertLessThan(
            5 * 1024 * 1024,
            $memory_used,
            sprintf('Template loading used %.2f MB (should be < 5 MB)', $memory_used_mb)
        );
        
        fwrite(STDERR, sprintf("\nTemplate Loading Memory: %.2f MB\n", $memory_used_mb));
    }
    
    /**
     * Test concurrent palette operations
     */
    public function test_concurrent_palette_operations() {
        $start = microtime(true);
        
        // Simulate concurrent operations
        $palettes = $this->palette_manager->get_all_palettes();
        $palette_id = array_key_first($palettes);
        $palette = $this->palette_manager->get_palette($palette_id);
        $by_category = $this->palette_manager->get_palettes_by_category('professional');
        
        $end = microtime(true);
        $time_ms = ($end - $start) * 1000;
        
        // Should complete quickly
        $this->assertLessThan(
            1000,
            $time_ms,
            sprintf('Concurrent operations took %.2f ms (should be < 1000 ms)', $time_ms)
        );
        
        fwrite(STDERR, sprintf("\nConcurrent Palette Operations: %.2f ms\n", $time_ms));
    }
    
    /**
     * Test database query count during application
     */
    public function test_database_query_efficiency() {
        global $wpdb;
        
        // Reset query counter
        $wpdb->num_queries = 0;
        
        $palettes = $this->palette_manager->get_all_palettes();
        $palette_id = array_key_first($palettes);
        
        $queries_before = $wpdb->num_queries;
        
        $this->palette_manager->apply_palette($palette_id);
        
        $queries_after = $wpdb->num_queries;
        $queries_used = $queries_after - $queries_before;
        
        // Should use minimal queries (ideally 1-2)
        $this->assertLessThan(
            5,
            $queries_used,
            sprintf('Palette application used %d queries (should be < 5)', $queries_used)
        );
        
        fwrite(STDERR, sprintf("\nDatabase Queries for Application: %d\n", $queries_used));
    }
}
