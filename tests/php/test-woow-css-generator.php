<?php
/**
 * Tests for WOOW_CSS_Generator class
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

use PHPUnit\Framework\TestCase;

class Test_WOOW_CSS_Generator extends TestCase {
    
    private WOOW_Settings $settings;
    private WOOW_CSS_Generator $generator;
    
    /**
     * Set up test environment
     */
    protected function setUp(): void {
        parent::setUp();
        
        // Reset global test storage
        global $_test_options, $_test_transients;
        $_test_options = [];
        $_test_transients = [];
        
        // Create instances
        $this->settings = new WOOW_Settings();
        $this->generator = new WOOW_CSS_Generator( $this->settings );
    }
    
    /**
     * Test generate completes in reasonable time
     */
    public function test_generate_completes_quickly(): void {
        $start = microtime( true );
        $css = $this->generator->generate();
        $end = microtime( true );
        
        $duration_ms = ( $end - $start ) * 1000;
        
        // Assert it completes in under 200ms (allowing some margin)
        $this->assertLessThan( 200, $duration_ms, "CSS generation took {$duration_ms}ms, should be under 200ms" );
        
        // Assert CSS was generated
        $this->assertIsString( $css );
        $this->assertNotEmpty( $css );
    }
    
    /**
     * Test generate produces valid CSS
     */
    public function test_generate_produces_valid_css(): void {
        $css = $this->generator->generate();
        
        // Assert it's a string
        $this->assertIsString( $css );
        
        // Assert it contains CSS selectors
        $this->assertStringContainsString( '#wpadminbar', $css );
        $this->assertStringContainsString( '#adminmenu', $css );
        $this->assertStringContainsString( '.postbox', $css );
        
        // Assert it contains CSS properties
        $this->assertStringContainsString( 'background', $css );
        $this->assertStringContainsString( 'color', $css );
        $this->assertStringContainsString( 'border-radius', $css );
        
        // Assert it contains glassmorphism
        $this->assertStringContainsString( 'backdrop-filter', $css );
    }
    
    /**
     * Test minify removes comments and whitespace
     */
    public function test_minify_removes_comments_and_whitespace(): void {
        $css_with_comments = "
        /* This is a comment */
        .selector {
            color: red; /* inline comment */
            background: blue;
        }
        ";
        
        $minified = $this->generator->minify( $css_with_comments );
        
        // Assert comments are removed
        $this->assertStringNotContainsString( '/*', $minified );
        $this->assertStringNotContainsString( '*/', $minified );
        
        // Assert excessive whitespace is removed
        $this->assertStringNotContainsString( "\n\n", $minified );
        
        // Assert CSS is still valid
        $this->assertStringContainsString( '.selector', $minified );
        $this->assertStringContainsString( 'color:red', $minified );
    }
    
    /**
     * Test get_shadow_value returns correct CSS
     */
    public function test_get_shadow_value_returns_correct_css(): void {
        // Test different shadow styles
        $shadow_sm = $this->generator->get_shadow_value( 'sm' );
        $this->assertIsString( $shadow_sm );
        $this->assertStringContainsString( 'rgba', $shadow_sm );
        
        $shadow_md = $this->generator->get_shadow_value( 'md' );
        $this->assertIsString( $shadow_md );
        
        $shadow_lg = $this->generator->get_shadow_value( 'lg' );
        $this->assertIsString( $shadow_lg );
        
        $shadow_none = $this->generator->get_shadow_value( 'none' );
        $this->assertEquals( 'none', $shadow_none );
    }
    
    /**
     * Test hex_to_rgb converts colors correctly
     */
    public function test_hex_to_rgb_converts_correctly(): void {
        // Test 6-digit hex
        $rgb = $this->generator->hex_to_rgb( '#6366f1' );
        $this->assertIsArray( $rgb );
        $this->assertCount( 3, $rgb );
        $this->assertEquals( 99, $rgb[0] );
        $this->assertEquals( 102, $rgb[1] );
        $this->assertEquals( 241, $rgb[2] );
        
        // Test 3-digit hex
        $rgb = $this->generator->hex_to_rgb( '#fff' );
        $this->assertIsArray( $rgb );
        $this->assertEquals( 255, $rgb[0] );
        $this->assertEquals( 255, $rgb[1] );
        $this->assertEquals( 255, $rgb[2] );
        
        // Test black
        $rgb = $this->generator->hex_to_rgb( '#000' );
        $this->assertEquals( 0, $rgb[0] );
        $this->assertEquals( 0, $rgb[1] );
        $this->assertEquals( 0, $rgb[2] );
    }
    
    /**
     * Test get_glassmorphism_css generates backdrop-filter
     */
    public function test_get_glassmorphism_css_generates_backdrop_filter(): void {
        $glass_css = $this->generator->get_glassmorphism_css( '12px', 0.9 );
        
        // Assert it contains backdrop-filter
        $this->assertStringContainsString( 'backdrop-filter', $glass_css );
        $this->assertStringContainsString( 'blur(12px)', $glass_css );
        
        // Assert it contains webkit prefix
        $this->assertStringContainsString( '-webkit-backdrop-filter', $glass_css );
        
        // Assert it contains background with opacity
        $this->assertStringContainsString( 'background', $glass_css );
        $this->assertStringContainsString( '0.9', $glass_css );
    }
    
    /**
     * Test get_metrics returns generation time and size
     */
    public function test_get_metrics_returns_generation_time_and_size(): void {
        // Generate CSS first
        $css = $this->generator->generate();
        
        // Get metrics
        $metrics = $this->generator->get_metrics();
        
        // Assert metrics structure
        $this->assertIsArray( $metrics );
        $this->assertArrayHasKey( 'generation_time', $metrics );
        $this->assertArrayHasKey( 'css_size', $metrics );
        
        // Assert values are reasonable
        $this->assertIsNumeric( $metrics['generation_time'] );
        $this->assertGreaterThan( 0, $metrics['generation_time'] );
        $this->assertLessThan( 200, $metrics['generation_time'] ); // Under 200ms
        
        $this->assertIsInt( $metrics['css_size'] );
        $this->assertGreaterThan( 0, $metrics['css_size'] );
        $this->assertEquals( strlen( $css ), $metrics['css_size'] );
    }
}
