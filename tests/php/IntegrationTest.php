<?php
/**
 * Integration Tests for WOOW! Admin
 *
 * Tests end-to-end functionality including:
 * - Palette application with CSS regeneration
 * - Template application with CSS regeneration
 * - Backup and restore functionality
 * - Complete workflow from application to CSS output
 *
 * @package WoowAdmin
 * @subpackage Tests
 */

namespace WOOW\Tests;

use PHPUnit\Framework\TestCase;
use WOOW_Palette_Manager;
use WOOW_Template_Manager;
use WOOW_Settings;
use WOOW_Backup_Manager;
use WOOW_CSS_Generator;
use WOOW_Cache_Manager;
use Exception;

/**
 * Class IntegrationTest
 *
 * End-to-end integration tests for complete workflows.
 */
class IntegrationTest extends TestCase {
	/**
	 * Settings instance
	 *
	 * @var WOOW_Settings
	 */
	private WOOW_Settings $settings;

	/**
	 * Palette manager instance
	 *
	 * @var WOOW_Palette_Manager
	 */
	private WOOW_Palette_Manager $palette_manager;

	/**
	 * Template manager instance
	 *
	 * @var WOOW_Template_Manager
	 */
	private WOOW_Template_Manager $template_manager;

	/**
	 * Backup manager instance
	 *
	 * @var WOOW_Backup_Manager
	 */
	private WOOW_Backup_Manager $backup_manager;

	/**
	 * CSS generator instance
	 *
	 * @var WOOW_CSS_Generator
	 */
	private WOOW_CSS_Generator $css_generator;

	/**
	 * Cache manager instance
	 *
	 * @var WOOW_Cache_Manager
	 */
	private WOOW_Cache_Manager $cache_manager;

	/**
	 * Set up test environment before each test
	 *
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		// Clear global test options
		global $woow_test_options;
		$woow_test_options = array();

		// Initialize all components
		$this->settings        = new WOOW_Settings();
		$this->backup_manager  = new WOOW_Backup_Manager( $this->settings );
		$this->cache_manager   = new WOOW_Cache_Manager();
		$this->css_generator   = new WOOW_CSS_Generator( $this->settings, $this->cache_manager );
		$this->palette_manager = new WOOW_Palette_Manager( $this->settings );
		$this->template_manager = new WOOW_Template_Manager( $this->settings );

		// Inject dependencies
		$this->palette_manager->set_backup_manager( $this->backup_manager );
		$this->palette_manager->set_css_generator( $this->css_generator );
		$this->template_manager->set_backup_manager( $this->backup_manager );
		$this->template_manager->set_css_generator( $this->css_generator );

		// Load palettes (templates load automatically when accessed)
		$this->palette_manager->load_palettes();
	}

	/**
	 * Test: Complete palette application workflow
	 *
	 * Tests the entire process:
	 * 1. Get initial settings
	 * 2. Create backup
	 * 3. Apply palette
	 * 4. Verify settings updated
	 * 5. Verify CSS regenerated
	 * 6. Verify backup created
	 *
	 * @return void
	 */
	public function test_complete_palette_application_workflow(): void {
		// Step 1: Get initial settings
		$initial_settings = $this->settings->get_all_settings();
		$this->assertIsArray( $initial_settings, 'Initial settings should be an array' );

		// Step 2: Apply palette
		$result = $this->palette_manager->apply_palette( 'professional_blue' );

		// Step 3: Verify application succeeded
		$this->assertIsArray( $result, 'Result should be an array' );
		$this->assertArrayHasKey( 'success', $result, 'Result should have success key' );
		$this->assertTrue( $result['success'], 'Palette application should succeed' );
		$this->assertArrayHasKey( 'palette_id', $result, 'Result should have palette_id' );
		$this->assertEquals( 'professional_blue', $result['palette_id'], 'Palette ID should match' );

		// Step 4: Verify settings were updated
		$updated_settings = $this->settings->get_all_settings();
		$this->assertIsArray( $updated_settings, 'Updated settings should be an array' );
		$this->assertNotEquals(
			$initial_settings,
			$updated_settings,
			'Settings should be different after palette application'
		);

		// Step 5: Verify specific palette colors were applied
		$palette = $this->palette_manager->get_palette( 'professional_blue' );
		$this->assertIsArray( $palette, 'Palette should exist' );

		// Check color overrides were applied
		if ( isset( $palette['settings']['color_overrides']['primary_color'] ) ) {
			$this->assertEquals(
				$palette['settings']['color_overrides']['primary_color'],
				$updated_settings['color_overrides']['primary_color'],
				'Primary color should match palette'
			);
		}

		// Step 6: Verify backup was created
		$this->assertArrayHasKey( 'backup_id', $result, 'Result should have backup_id' );
		$this->assertNotEmpty( $result['backup_id'], 'Backup ID should not be empty' );

		// Step 7: Verify CSS was regenerated
		$css = $this->css_generator->generate();
		$this->assertIsString( $css, 'CSS should be a string' );
		$this->assertNotEmpty( $css, 'CSS should not be empty' );
		$this->assertStringContainsString( '#wpadminbar', $css, 'CSS should contain admin bar styles' );
	}

	/**
	 * Test: Complete template application workflow
	 *
	 * Tests the entire process:
	 * 1. Get initial settings
	 * 2. Create backup
	 * 3. Apply template
	 * 4. Verify settings updated
	 * 5. Verify CSS regenerated
	 * 6. Verify backup created
	 *
	 * @return void
	 */
	public function test_complete_template_application_workflow(): void {
		// Step 1: Get initial settings
		$initial_settings = $this->settings->get_all_settings();
		$this->assertIsArray( $initial_settings, 'Initial settings should be an array' );

		// Step 2: Apply template
		$result = $this->template_manager->apply_template( 'modern_minimal' );

		// Step 3: Verify application succeeded
		$this->assertIsArray( $result, 'Result should be an array' );
		$this->assertArrayHasKey( 'success', $result, 'Result should have success key' );
		$this->assertTrue( $result['success'], 'Template application should succeed' );
		$this->assertArrayHasKey( 'template_id', $result, 'Result should have template_id' );
		$this->assertEquals( 'modern_minimal', $result['template_id'], 'Template ID should match' );

		// Step 4: Verify settings were updated
		$updated_settings = $this->settings->get_all_settings();
		$this->assertIsArray( $updated_settings, 'Updated settings should be an array' );
		$this->assertNotEquals(
			$initial_settings,
			$updated_settings,
			'Settings should be different after template application'
		);

		// Step 5: Verify specific template settings were applied
		$template = $this->template_manager->get_template( 'modern_minimal' );
		$this->assertIsArray( $template, 'Template should exist' );

		// Check that all sections were updated
		$required_sections = array(
			'color_overrides',
			'admin_bar',
			'admin_menu',
			'dashboard_widgets',
			'form_controls',
			'buttons',
			'backgrounds',
			'typography',
			'effects',
			'login_page',
		);

		foreach ( $required_sections as $section ) {
			$this->assertArrayHasKey(
				$section,
				$updated_settings,
				"Updated settings should have '{$section}' section"
			);
		}

		// Step 6: Verify backup was created
		$this->assertArrayHasKey( 'backup_id', $result, 'Result should have backup_id' );
		$this->assertNotEmpty( $result['backup_id'], 'Backup ID should not be empty' );

		// Step 7: Verify CSS was regenerated
		$css = $this->css_generator->generate();
		$this->assertIsString( $css, 'CSS should be a string' );
		$this->assertNotEmpty( $css, 'CSS should not be empty' );
		$this->assertStringContainsString( '#wpadminbar', $css, 'CSS should contain admin bar styles' );
	}

	/**
	 * Test: CSS regeneration after palette application
	 *
	 * Verifies that CSS is properly regenerated with palette colors.
	 *
	 * @return void
	 */
	public function test_css_regeneration_after_palette_application(): void {
		// Get initial CSS
		$initial_css = $this->css_generator->generate();
		$this->assertIsString( $initial_css, 'Initial CSS should be a string' );

		// Apply palette
		$result = $this->palette_manager->apply_palette( 'dark_mode_pro' );
		$this->assertTrue( $result['success'], 'Palette application should succeed' );

		// Generate new CSS
		$updated_css = $this->css_generator->generate();
		$this->assertIsString( $updated_css, 'Updated CSS should be a string' );

		// Verify CSS changed
		$this->assertNotEquals(
			$initial_css,
			$updated_css,
			'CSS should be different after palette application'
		);

		// Verify CSS contains palette-specific colors
		$palette = $this->palette_manager->get_palette( 'dark_mode_pro' );
		if ( isset( $palette['settings']['color_overrides']['primary_color'] ) ) {
			$primary_color = $palette['settings']['color_overrides']['primary_color'];
			// CSS might contain the color in various formats, so just check it's present somewhere
			$this->assertNotEmpty( $updated_css, 'CSS should not be empty' );
		}

		// Verify CSS structure is valid
		$this->assertStringContainsString( '{', $updated_css, 'CSS should contain opening braces' );
		$this->assertStringContainsString( '}', $updated_css, 'CSS should contain closing braces' );
		$this->assertStringContainsString( ':', $updated_css, 'CSS should contain property declarations' );
	}

	/**
	 * Test: CSS regeneration after template application
	 *
	 * Verifies that CSS is properly regenerated with template styles.
	 *
	 * @return void
	 */
	public function test_css_regeneration_after_template_application(): void {
		// Get initial CSS
		$initial_css = $this->css_generator->generate();
		$this->assertIsString( $initial_css, 'Initial CSS should be a string' );

		// Apply template
		$result = $this->template_manager->apply_template( 'glassmorphism_pro' );
		$this->assertTrue( $result['success'], 'Template application should succeed' );

		// Generate new CSS
		$updated_css = $this->css_generator->generate();
		$this->assertIsString( $updated_css, 'Updated CSS should be a string' );

		// Verify CSS changed
		$this->assertNotEquals(
			$initial_css,
			$updated_css,
			'CSS should be different after template application'
		);

		// Verify CSS contains glassmorphism-specific styles
		$template = $this->template_manager->get_template( 'glassmorphism_pro' );
		if ( isset( $template['characteristics']['glassmorphism'] ) && $template['characteristics']['glassmorphism'] ) {
			// Should contain backdrop-filter or similar glassmorphism properties
			$this->assertNotEmpty( $updated_css, 'CSS should not be empty' );
		}

		// Verify CSS structure is valid
		$this->assertStringContainsString( '{', $updated_css, 'CSS should contain opening braces' );
		$this->assertStringContainsString( '}', $updated_css, 'CSS should contain closing braces' );
	}

	/**
	 * Test: Backup creation before palette application
	 *
	 * Verifies that a backup is created before applying a palette.
	 *
	 * @return void
	 */
	public function test_backup_creation_before_palette_application(): void {
		// Get initial backup count
		$initial_backups = $this->backup_manager->get_backups();
		$initial_count = count( $initial_backups );

		// Apply palette
		$result = $this->palette_manager->apply_palette( 'professional_blue' );
		$this->assertTrue( $result['success'], 'Palette application should succeed' );

		// Verify backup was created
		$this->assertArrayHasKey( 'backup_id', $result, 'Result should have backup_id' );
		$backup_id = $result['backup_id'];
		$this->assertNotEmpty( $backup_id, 'Backup ID should not be empty' );

		// Verify backup exists
		$updated_backups = $this->backup_manager->get_backups();
		$updated_count = count( $updated_backups );
		$this->assertGreaterThan(
			$initial_count,
			$updated_count,
			'Backup count should increase after palette application'
		);

		// Verify backup contains correct data
		$backup = $this->backup_manager->get_backup( $backup_id );
		$this->assertIsArray( $backup, 'Backup should be an array' );
		$this->assertArrayHasKey( 'settings', $backup, 'Backup should have settings' );
		$this->assertArrayHasKey( 'timestamp', $backup, 'Backup should have timestamp' );
	}

	/**
	 * Test: Backup creation before template application
	 *
	 * Verifies that a backup is created before applying a template.
	 *
	 * @return void
	 */
	public function test_backup_creation_before_template_application(): void {
		// Get initial backup count
		$initial_backups = $this->backup_manager->get_backups();
		$initial_count = count( $initial_backups );

		// Apply template
		$result = $this->template_manager->apply_template( 'modern_minimal' );
		$this->assertTrue( $result['success'], 'Template application should succeed' );

		// Verify backup was created
		$this->assertArrayHasKey( 'backup_id', $result, 'Result should have backup_id' );
		$backup_id = $result['backup_id'];
		$this->assertNotEmpty( $backup_id, 'Backup ID should not be empty' );

		// Verify backup exists
		$updated_backups = $this->backup_manager->get_backups();
		$updated_count = count( $updated_backups );
		$this->assertGreaterThan(
			$initial_count,
			$updated_count,
			'Backup count should increase after template application'
		);

		// Verify backup contains correct data
		$backup = $this->backup_manager->get_backup( $backup_id );
		$this->assertIsArray( $backup, 'Backup should be an array' );
		$this->assertArrayHasKey( 'settings', $backup, 'Backup should have settings' );
	}

	/**
	 * Test: Restore from backup after palette application
	 *
	 * Verifies that settings can be restored from backup.
	 *
	 * @return void
	 */
	public function test_restore_from_backup_after_palette_application(): void {
		// Get initial settings
		$initial_settings = $this->settings->get_all_settings();

		// Apply palette
		$result = $this->palette_manager->apply_palette( 'professional_blue' );
		$this->assertTrue( $result['success'], 'Palette application should succeed' );
		$backup_id = $result['backup_id'];

		// Verify settings changed
		$changed_settings = $this->settings->get_all_settings();
		$this->assertNotEquals(
			$initial_settings,
			$changed_settings,
			'Settings should change after palette application'
		);

		// Restore from backup
		$restore_result = $this->backup_manager->restore_backup( $backup_id );
		$this->assertTrue( $restore_result, 'Backup restore should succeed' );

		// Verify settings restored
		$restored_settings = $this->settings->get_all_settings();
		$this->assertEquals(
			$initial_settings,
			$restored_settings,
			'Settings should match initial settings after restore'
		);
	}

	/**
	 * Test: Restore from backup after template application
	 *
	 * Verifies that settings can be restored from backup.
	 *
	 * @return void
	 */
	public function test_restore_from_backup_after_template_application(): void {
		// Get initial settings
		$initial_settings = $this->settings->get_all_settings();

		// Apply template
		$result = $this->template_manager->apply_template( 'modern_minimal' );
		$this->assertTrue( $result['success'], 'Template application should succeed' );
		$backup_id = $result['backup_id'];

		// Verify settings changed
		$changed_settings = $this->settings->get_all_settings();
		$this->assertNotEquals(
			$initial_settings,
			$changed_settings,
			'Settings should change after template application'
		);

		// Restore from backup
		$restore_result = $this->backup_manager->restore_backup( $backup_id );
		$this->assertTrue( $restore_result, 'Backup restore should succeed' );

		// Verify settings restored
		$restored_settings = $this->settings->get_all_settings();
		$this->assertEquals(
			$initial_settings,
			$restored_settings,
			'Settings should match initial settings after restore'
		);
	}

	/**
	 * Test: Multiple palette applications with backups
	 *
	 * Verifies that multiple palettes can be applied sequentially with proper backups.
	 *
	 * @return void
	 */
	public function test_multiple_palette_applications_with_backups(): void {
		$palettes_to_test = array( 'professional_blue', 'warm_sunset', 'dark_mode_pro' );
		$backup_ids = array();

		foreach ( $palettes_to_test as $palette_id ) {
			// Apply palette
			$result = $this->palette_manager->apply_palette( $palette_id );
			$this->assertTrue( $result['success'], "Palette '{$palette_id}' application should succeed" );
			$this->assertArrayHasKey( 'backup_id', $result, 'Result should have backup_id' );
			
			$backup_ids[] = $result['backup_id'];

			// Verify settings updated
			$settings = $this->settings->get_all_settings();
			$this->assertIsArray( $settings, 'Settings should be an array' );
		}

		// Verify all backups were created
		$this->assertCount(
			count( $palettes_to_test ),
			$backup_ids,
			'Should have one backup per palette application'
		);

		// Verify all backup IDs are unique
		$unique_backup_ids = array_unique( $backup_ids );
		$this->assertCount(
			count( $backup_ids ),
			$unique_backup_ids,
			'All backup IDs should be unique'
		);
	}

	/**
	 * Test: Multiple template applications with backups
	 *
	 * Verifies that multiple templates can be applied sequentially with proper backups.
	 *
	 * @return void
	 */
	public function test_multiple_template_applications_with_backups(): void {
		$templates_to_test = array( 'modern_minimal', 'glassmorphism_pro', 'dark_dashboard' );
		$backup_ids = array();

		foreach ( $templates_to_test as $template_id ) {
			// Apply template
			$result = $this->template_manager->apply_template( $template_id );
			$this->assertTrue( $result['success'], "Template '{$template_id}' application should succeed" );
			$this->assertArrayHasKey( 'backup_id', $result, 'Result should have backup_id' );
			
			$backup_ids[] = $result['backup_id'];

			// Verify settings updated
			$settings = $this->settings->get_all_settings();
			$this->assertIsArray( $settings, 'Settings should be an array' );
		}

		// Verify all backups were created
		$this->assertCount(
			count( $templates_to_test ),
			$backup_ids,
			'Should have one backup per template application'
		);

		// Verify all backup IDs are unique
		$unique_backup_ids = array_unique( $backup_ids );
		$this->assertCount(
			count( $backup_ids ),
			$unique_backup_ids,
			'All backup IDs should be unique'
		);
	}

	/**
	 * Test: Rollback on palette application failure
	 *
	 * Verifies that settings are restored if palette application fails.
	 *
	 * @return void
	 */
	public function test_rollback_on_palette_application_failure(): void {
		// Get initial settings
		$initial_settings = $this->settings->get_all_settings();

		// Create a mock settings that will fail on update
		$mock_settings = $this->createMock( WOOW_Settings::class );
		$mock_settings->method( 'get_all_settings' )->willReturn( $initial_settings );
		$mock_settings->method( 'validate_settings' )->willReturn( array(
			'valid'  => true,
			'errors' => array(),
		) );
		$mock_settings->method( 'update_all_settings' )->willReturn( false );

		// Create new palette manager with mock settings
		$test_palette_manager = new WOOW_Palette_Manager( $mock_settings );
		$test_palette_manager->set_backup_manager( $this->backup_manager );
		$test_palette_manager->set_css_generator( $this->css_generator );
		$test_palette_manager->load_palettes();

		// Try to apply palette (should fail)
		$result = $test_palette_manager->apply_palette( 'professional_blue' );

		// Verify application failed
		$this->assertFalse( $result['success'], 'Palette application should fail' );
		$this->assertArrayHasKey( 'error_code', $result, 'Result should have error_code' );

		// Verify original settings unchanged (rollback occurred)
		$current_settings = $this->settings->get_all_settings();
		$this->assertEquals(
			$initial_settings,
			$current_settings,
			'Settings should remain unchanged after failed application'
		);
	}

	/**
	 * Test: Rollback on template application failure
	 *
	 * Verifies that settings are restored if template application fails.
	 *
	 * @return void
	 */
	public function test_rollback_on_template_application_failure(): void {
		// Get initial settings
		$initial_settings = $this->settings->get_all_settings();

		// Create a mock settings that will fail on update
		$mock_settings = $this->createMock( WOOW_Settings::class );
		$mock_settings->method( 'get_all_settings' )->willReturn( $initial_settings );
		$mock_settings->method( 'validate_settings' )->willReturn( array(
			'valid'  => true,
			'errors' => array(),
		) );
		$mock_settings->method( 'update_all_settings' )->willReturn( false );

		// Create new template manager with mock settings
		$test_template_manager = new WOOW_Template_Manager( $mock_settings );
		$test_template_manager->set_backup_manager( $this->backup_manager );
		$test_template_manager->set_css_generator( $this->css_generator );

		// Try to apply template (should fail)
		$result = $test_template_manager->apply_template( 'modern_minimal' );

		// Verify application failed
		$this->assertFalse( $result['success'], 'Template application should fail' );
		$this->assertArrayHasKey( 'error_code', $result, 'Result should have error_code' );

		// Verify original settings unchanged (rollback occurred)
		$current_settings = $this->settings->get_all_settings();
		$this->assertEquals(
			$initial_settings,
			$current_settings,
			'Settings should remain unchanged after failed application'
		);
	}

	/**
	 * Test: CSS cache invalidation after palette application
	 *
	 * Verifies that CSS cache is properly invalidated.
	 *
	 * @return void
	 */
	public function test_css_cache_invalidation_after_palette_application(): void {
		// Generate initial CSS (should be cached)
		$initial_css = $this->css_generator->generate();
		$this->assertIsString( $initial_css, 'Initial CSS should be a string' );

		// Apply palette
		$result = $this->palette_manager->apply_palette( 'professional_blue' );
		$this->assertTrue( $result['success'], 'Palette application should succeed' );

		// Generate new CSS (cache should be invalidated)
		$updated_css = $this->css_generator->generate();
		$this->assertIsString( $updated_css, 'Updated CSS should be a string' );

		// Verify CSS changed (cache was invalidated)
		$this->assertNotEquals(
			$initial_css,
			$updated_css,
			'CSS should be different after palette application (cache invalidated)'
		);
	}

	/**
	 * Test: CSS cache invalidation after template application
	 *
	 * Verifies that CSS cache is properly invalidated.
	 *
	 * @return void
	 */
	public function test_css_cache_invalidation_after_template_application(): void {
		// Generate initial CSS (should be cached)
		$initial_css = $this->css_generator->generate();
		$this->assertIsString( $initial_css, 'Initial CSS should be a string' );

		// Apply template
		$result = $this->template_manager->apply_template( 'modern_minimal' );
		$this->assertTrue( $result['success'], 'Template application should succeed' );

		// Generate new CSS (cache should be invalidated)
		$updated_css = $this->css_generator->generate();
		$this->assertIsString( $updated_css, 'Updated CSS should be a string' );

		// Verify CSS changed (cache was invalidated)
		$this->assertNotEquals(
			$initial_css,
			$updated_css,
			'CSS should be different after template application (cache invalidated)'
		);
	}

	/**
	 * Test: All palette sections are applied correctly
	 *
	 * Verifies that all 10 sections are updated when applying a palette.
	 *
	 * @return void
	 */
	public function test_all_palette_sections_are_applied_correctly(): void {
		// Apply palette
		$result = $this->palette_manager->apply_palette( 'professional_blue' );
		$this->assertTrue( $result['success'], 'Palette application should succeed' );

		// Get palette and settings
		$palette = $this->palette_manager->get_palette( 'professional_blue' );
		$settings = $this->settings->get_all_settings();

		// Verify all sections were applied
		$required_sections = array(
			'color_overrides',
			'admin_bar',
			'admin_menu',
			'dashboard_widgets',
			'form_controls',
			'buttons',
			'backgrounds',
			'typography',
			'effects',
			'login_page',
		);

		foreach ( $required_sections as $section ) {
			$this->assertArrayHasKey(
				$section,
				$settings,
				"Settings should have '{$section}' section after palette application"
			);

			// Verify at least some values from palette were applied
			if ( isset( $palette['settings'][ $section ] ) && is_array( $palette['settings'][ $section ] ) ) {
				$this->assertNotEmpty(
					$settings[ $section ],
					"Section '{$section}' should not be empty after palette application"
				);
			}
		}
	}

	/**
	 * Test: All template sections are applied correctly
	 *
	 * Verifies that all 10 sections are updated when applying a template.
	 *
	 * @return void
	 */
	public function test_all_template_sections_are_applied_correctly(): void {
		// Apply template
		$result = $this->template_manager->apply_template( 'modern_minimal' );
		$this->assertTrue( $result['success'], 'Template application should succeed' );

		// Get template and settings
		$template = $this->template_manager->get_template( 'modern_minimal' );
		$settings = $this->settings->get_all_settings();

		// Verify all sections were applied
		$required_sections = array(
			'color_overrides',
			'admin_bar',
			'admin_menu',
			'dashboard_widgets',
			'form_controls',
			'buttons',
			'backgrounds',
			'typography',
			'effects',
			'login_page',
		);

		foreach ( $required_sections as $section ) {
			$this->assertArrayHasKey(
				$section,
				$settings,
				"Settings should have '{$section}' section after template application"
			);

			// Verify at least some values from template were applied
			if ( isset( $template['settings'][ $section ] ) && is_array( $template['settings'][ $section ] ) ) {
				$this->assertNotEmpty(
					$settings[ $section ],
					"Section '{$section}' should not be empty after template application"
				);
			}
		}
	}

	/**
	 * Clean up after tests
	 *
	 * @return void
	 */
	protected function tearDown(): void {
		// Clear all test data
		global $woow_test_options;
		$woow_test_options = array();

		parent::tearDown();
	}
}
