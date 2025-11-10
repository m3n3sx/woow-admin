<?php
/**
 * Tests for WOOW_Settings class
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

use PHPUnit\Framework\TestCase;

class Test_WOOW_Settings extends TestCase {
    
    private WOOW_Settings $settings;
    
    /**
     * Set up test environment
     */
    protected function setUp(): void {
        parent::setUp();
        
        // Reset global test storage
        global $_test_options, $_test_transients;
        $_test_options = [];
        $_test_transients = [];
        
        // Create fresh instance
        $this->settings = new WOOW_Settings();
    }
    
    /**
     * Test get_default_settings returns complete structure
     */
    public function test_get_default_settings_returns_complete_structure(): void {
        $defaults = $this->settings->get_default_settings();
        
        // Assert it's an array
        $this->assertIsArray( $defaults );
        
        // Assert all required sections exist
        $required_sections = [
            'general',
            'admin_bar',
            'admin_menu',
            'dashboard_widgets',
            'form_controls',
            'buttons',
            'backgrounds',
            'typography',
            'effects',
            'login_page'
        ];
        
        foreach ( $required_sections as $section ) {
            $this->assertArrayHasKey( $section, $defaults, "Missing section: {$section}" );
            $this->assertIsArray( $defaults[ $section ], "Section {$section} should be an array" );
        }
        
        // Assert general section has required keys
        $this->assertArrayHasKey( 'enabled', $defaults['general'] );
        $this->assertArrayHasKey( 'current_palette', $defaults['general'] );
        $this->assertArrayHasKey( 'current_template', $defaults['general'] );
    }
    
    /**
     * Test apply_palette updates all color settings
     */
    public function test_apply_palette_updates_color_settings(): void {
        // Apply a palette
        $result = $this->settings->apply_palette( 'professional_blue' );
        
        // Assert success
        $this->assertTrue( $result );
        
        // Get updated settings
        $settings = $this->settings->get_all_settings();
        
        // Assert palette was applied
        $this->assertEquals( 'professional_blue', $settings['general']['current_palette'] );
        
        // Assert colors were updated (check a few key colors)
        $this->assertNotEmpty( $settings['general']['primary_color'] );
        $this->assertMatchesRegularExpression( '/^#[0-9A-Fa-f]{6}$/', $settings['general']['primary_color'] );
    }
    
    /**
     * Test apply_template overrides all sections
     */
    public function test_apply_template_overrides_all_sections(): void {
        // Apply a template
        $result = $this->settings->apply_template( 'modern_minimal' );
        
        // Assert success
        $this->assertTrue( $result );
        
        // Get updated settings
        $settings = $this->settings->get_all_settings();
        
        // Assert template was applied
        $this->assertEquals( 'modern_minimal', $settings['general']['current_template'] );
        
        // Assert settings were updated
        $this->assertIsArray( $settings );
        $this->assertNotEmpty( $settings );
    }
    
    /**
     * Test validate_settings catches invalid values
     */
    public function test_validate_settings_catches_invalid_values(): void {
        $invalid_settings = [
            'general' => [
                'enabled' => 'not_a_boolean', // Should be boolean
            ],
            'admin_bar' => [
                'height' => 'invalid', // Should be numeric with unit
                'background_color' => 'not-a-color', // Should be hex color
            ]
        ];
        
        $result = $this->settings->validate_settings( $invalid_settings );
        
        // Assert validation failed
        $this->assertFalse( $result['valid'] );
        $this->assertNotEmpty( $result['errors'] );
        $this->assertIsArray( $result['errors'] );
    }
    
    /**
     * Test sanitize_color validates hex colors
     */
    public function test_sanitize_color_validates_hex(): void {
        // Valid hex colors
        $this->assertEquals( '#6366f1', $this->settings->sanitize_color( '#6366f1' ) );
        $this->assertEquals( '#fff', $this->settings->sanitize_color( '#fff' ) );
        $this->assertEquals( '#FFFFFF', $this->settings->sanitize_color( '#FFFFFF' ) );
        
        // Invalid colors should return default
        $this->assertEquals( '#6366f1', $this->settings->sanitize_color( 'invalid' ) );
        $this->assertEquals( '#6366f1', $this->settings->sanitize_color( 'blue' ) );
        $this->assertEquals( '#6366f1', $this->settings->sanitize_color( '#gg0000' ) );
    }
    
    /**
     * Test sanitize_color validates rgb colors
     */
    public function test_sanitize_color_validates_rgb(): void {
        // Valid RGB colors
        $this->assertEquals( 'rgb(255, 0, 0)', $this->settings->sanitize_color( 'rgb(255, 0, 0)' ) );
        $this->assertEquals( 'rgb(0,0,0)', $this->settings->sanitize_color( 'rgb(0,0,0)' ) );
        
        // Invalid RGB should return default
        $this->assertEquals( '#6366f1', $this->settings->sanitize_color( 'rgb(256, 0, 0)' ) );
        $this->assertEquals( '#6366f1', $this->settings->sanitize_color( 'rgb(a, b, c)' ) );
    }
    
    /**
     * Test sanitize_color validates rgba colors
     */
    public function test_sanitize_color_validates_rgba(): void {
        // Valid RGBA colors
        $this->assertEquals( 'rgba(255, 0, 0, 0.5)', $this->settings->sanitize_color( 'rgba(255, 0, 0, 0.5)' ) );
        $this->assertEquals( 'rgba(0,0,0,1)', $this->settings->sanitize_color( 'rgba(0,0,0,1)' ) );
        
        // Invalid RGBA should return default
        $this->assertEquals( '#6366f1', $this->settings->sanitize_color( 'rgba(256, 0, 0, 0.5)' ) );
        $this->assertEquals( '#6366f1', $this->settings->sanitize_color( 'rgba(0, 0, 0, 2)' ) );
    }
    
    /**
     * Test sanitize_unit validates px
     */
    public function test_sanitize_unit_validates_px(): void {
        $this->assertEquals( '16px', $this->settings->sanitize_unit( '16px' ) );
        $this->assertEquals( '0px', $this->settings->sanitize_unit( '0px' ) );
        $this->assertEquals( '100px', $this->settings->sanitize_unit( '100px' ) );
        
        // Invalid should return default
        $this->assertEquals( '16px', $this->settings->sanitize_unit( 'invalid' ) );
        $this->assertEquals( '16px', $this->settings->sanitize_unit( '-10px' ) );
    }
    
    /**
     * Test sanitize_unit validates rem
     */
    public function test_sanitize_unit_validates_rem(): void {
        $this->assertEquals( '1rem', $this->settings->sanitize_unit( '1rem' ) );
        $this->assertEquals( '2.5rem', $this->settings->sanitize_unit( '2.5rem' ) );
        $this->assertEquals( '0rem', $this->settings->sanitize_unit( '0rem' ) );
    }
    
    /**
     * Test sanitize_unit validates em
     */
    public function test_sanitize_unit_validates_em(): void {
        $this->assertEquals( '1em', $this->settings->sanitize_unit( '1em' ) );
        $this->assertEquals( '1.5em', $this->settings->sanitize_unit( '1.5em' ) );
    }
    
    /**
     * Test sanitize_unit validates percentage
     */
    public function test_sanitize_unit_validates_percentage(): void {
        $this->assertEquals( '50%', $this->settings->sanitize_unit( '50%' ) );
        $this->assertEquals( '100%', $this->settings->sanitize_unit( '100%' ) );
        $this->assertEquals( '0%', $this->settings->sanitize_unit( '0%' ) );
    }
    
    /**
     * Test export_settings generates valid JSON
     */
    public function test_export_settings_generates_valid_json(): void {
        $json = $this->settings->export_settings();
        
        // Assert it's a string
        $this->assertIsString( $json );
        
        // Assert it's valid JSON
        $decoded = json_decode( $json, true );
        $this->assertIsArray( $decoded );
        $this->assertNull( json_last_error_msg() === 'No error' ? null : json_last_error_msg() );
        
        // Assert it contains settings
        $this->assertArrayHasKey( 'settings', $decoded );
        $this->assertArrayHasKey( 'version', $decoded );
        $this->assertArrayHasKey( 'exported_at', $decoded );
    }
    
    /**
     * Test import_settings validates JSON structure
     */
    public function test_import_settings_validates_json_structure(): void {
        // Valid JSON
        $valid_json = json_encode( [
            'settings' => $this->settings->get_default_settings(),
            'version' => '1.0.0',
            'exported_at' => time()
        ] );
        
        $result = $this->settings->import_settings( $valid_json );
        $this->assertTrue( $result['success'] );
        
        // Invalid JSON
        $result = $this->settings->import_settings( 'invalid json' );
        $this->assertFalse( $result['success'] );
        $this->assertArrayHasKey( 'message', $result );
    }
    
    /**
     * Test import_settings applies valid JSON
     */
    public function test_import_settings_applies_valid_json(): void {
        // Export current settings
        $exported = $this->settings->export_settings();
        
        // Modify settings
        $this->settings->apply_palette( 'energetic_green' );
        
        // Import original settings
        $result = $this->settings->import_settings( $exported );
        
        // Assert success
        $this->assertTrue( $result['success'] );
        
        // Assert settings were restored
        $settings = $this->settings->get_all_settings();
        $this->assertEquals( 'professional_blue', $settings['general']['current_palette'] );
    }
    
    /**
     * Test get_available_palettes returns all palettes
     */
    public function test_get_available_palettes_returns_all(): void {
        $palettes = $this->settings->get_available_palettes();
        
        $this->assertIsArray( $palettes );
        $this->assertGreaterThanOrEqual( 10, count( $palettes ) );
        
        // Check structure of first palette
        $first_palette = reset( $palettes );
        $this->assertArrayHasKey( 'name', $first_palette );
        $this->assertArrayHasKey( 'colors', $first_palette );
        $this->assertIsArray( $first_palette['colors'] );
    }
    
    /**
     * Test get_available_templates returns all templates
     */
    public function test_get_available_templates_returns_all(): void {
        $templates = $this->settings->get_available_templates();
        
        $this->assertIsArray( $templates );
        $this->assertGreaterThanOrEqual( 11, count( $templates ) );
        
        // Check structure of first template
        $first_template = reset( $templates );
        $this->assertArrayHasKey( 'name', $first_template );
        $this->assertArrayHasKey( 'settings', $first_template );
        $this->assertIsArray( $first_template['settings'] );
    }
}
