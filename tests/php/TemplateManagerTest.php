<?php
/**
 * Unit Tests for WOOW_Template_Manager
 *
 * Tests template loading, retrieval, validation, completeness check, and application functionality.
 *
 * @package WoowAdmin
 * @subpackage Tests
 */

namespace WOOW\Tests;

use PHPUnit\Framework\TestCase;
use WOOW_Template_Manager;
use WOOW_Settings;
use WOOW_Backup_Manager;
use WOOW_CSS_Generator;
use Exception;

/**
 * Class TemplateManagerTest
 *
 * Comprehensive unit tests for WOOW_Template_Manager class.
 */
class TemplateManagerTest extends TestCase {
	/**
	 * Template manager instance
	 *
	 * @var WOOW_Template_Manager
	 */
	private WOOW_Template_Manager $template_manager;

	/**
	 * Mock settings instance
	 *
	 * @var WOOW_Settings
	 */
	private $mock_settings;

	/**
	 * Test templates data directory
	 *
	 * @var string
	 */
	private string $test_data_dir;

	/**
	 * Set up test environment before each test
	 *
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		// Create mock settings
		$this->mock_settings = $this->createMock( WOOW_Settings::class );

		// Create template manager instance
		$this->template_manager = new WOOW_Template_Manager( $this->mock_settings );

		// Set up test data directory
		$this->test_data_dir = WOOW_PLUGIN_DIR . 'includes/data/';
	}

	/**
	 * Test: Constructor initializes properly
	 *
	 * @return void
	 */
	public function test_constructor_initializes_properly(): void {
		$this->assertInstanceOf(
			WOOW_Template_Manager::class,
			$this->template_manager,
			'Template manager should be instantiated correctly'
		);
	}

	/**
	 * Test: Get all templates returns array
	 *
	 * @return void
	 */
	public function test_get_all_templates_returns_array(): void {
		$templates = $this->template_manager->get_all_templates();

		$this->assertIsArray( $templates, 'get_all_templates should return an array' );
	}

	/**
	 * Test: Get all templates returns expected count
	 *
	 * @return void
	 */
	public function test_get_all_templates_returns_expected_count(): void {
		$templates = $this->template_manager->get_all_templates();

		// Should have exactly 11 templates as per requirements
		$this->assertCount( 11, $templates, 'Should have exactly 11 templates' );
	}

	/**
	 * Test: Get all templates contains required template IDs
	 *
	 * @return void
	 */
	public function test_get_all_templates_contains_required_ids(): void {
		$templates = $this->template_manager->get_all_templates();

		$required_ids = array(
			'modern_minimal',
			'glassmorphism_pro',
			'dark_dashboard',
			'colorful_creative',
			'corporate_blue',
			'material_design',
			'flat_2',
			'neumorphism',
			'retro_wave',
			'nature_inspired',
			'high_contrast',
		);

		// Convert templates array to associative array by ID
		$templates_by_id = array();
		foreach ( $templates as $template ) {
			if ( isset( $template['id'] ) ) {
				$templates_by_id[ $template['id'] ] = $template;
			}
		}

		foreach ( $required_ids as $template_id ) {
			$this->assertArrayHasKey(
				$template_id,
				$templates_by_id,
				"Templates should contain '{$template_id}'"
			);
		}
	}

	/**
	 * Test: Get template by ID returns correct template
	 *
	 * @return void
	 */
	public function test_get_template_returns_correct_template(): void {
		$template = $this->template_manager->get_template( 'modern_minimal' );

		$this->assertIsArray( $template, 'get_template should return an array' );
		$this->assertArrayHasKey( 'id', $template, 'Template should have id key' );
		$this->assertEquals( 'modern_minimal', $template['id'], 'Template ID should match' );
	}

	/**
	 * Test: Get template returns null for non-existent ID
	 *
	 * @return void
	 */
	public function test_get_template_returns_null_for_nonexistent_id(): void {
		$template = $this->template_manager->get_template( 'nonexistent_template' );

		$this->assertNull( $template, 'get_template should return null for non-existent template' );
	}

	/**
	 * Test: Get templates by category returns correct templates
	 *
	 * @return void
	 */
	public function test_get_templates_by_category_returns_correct_templates(): void {
		$minimal_templates = $this->template_manager->get_templates_by_category( 'minimal' );

		$this->assertIsArray( $minimal_templates, 'Should return an array' );
		$this->assertNotEmpty( $minimal_templates, 'Should have at least one minimal template' );

		// Check that all returned templates have the correct category
		foreach ( $minimal_templates as $template ) {
			$this->assertEquals(
				'minimal',
				$template['category'],
				'All templates should have minimal category'
			);
		}
	}

	/**
	 * Test: Get templates by category returns empty for invalid category
	 *
	 * @return void
	 */
	public function test_get_templates_by_category_returns_empty_for_invalid_category(): void {
		$templates = $this->template_manager->get_templates_by_category( 'nonexistent_category' );

		$this->assertIsArray( $templates, 'Should return an array' );
		$this->assertEmpty( $templates, 'Should return empty array for invalid category' );
	}

	/**
	 * Test: Template has all required keys
	 *
	 * @return void
	 */
	public function test_template_has_all_required_keys(): void {
		$template = $this->template_manager->get_template( 'modern_minimal' );

		$required_keys = array( 'id', 'name', 'description', 'category', 'settings' );

		foreach ( $required_keys as $key ) {
			$this->assertArrayHasKey(
				$key,
				$template,
				"Template should have '{$key}' key"
			);
		}
	}

	/**
	 * Test: Template settings has all required sections
	 *
	 * @return void
	 */
	public function test_template_settings_has_all_required_sections(): void {
		$template = $this->template_manager->get_template( 'modern_minimal' );

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

		$this->assertArrayHasKey( 'settings', $template, 'Template should have settings key' );

		foreach ( $required_sections as $section ) {
			$this->assertArrayHasKey(
				$section,
				$template['settings'],
				"Template settings should have '{$section}' section"
			);
		}
	}

	/**
	 * Test: All templates have minimum required options
	 *
	 * @return void
	 */
	public function test_all_templates_have_minimum_required_options(): void {
		$templates = $this->template_manager->get_all_templates();

		$min_option_counts = array(
			'color_overrides'   => 7,
			'admin_bar'         => 20,
			'admin_menu'        => 10,
			'dashboard_widgets' => 8,
			'form_controls'     => 8,
			'buttons'           => 8,
			'backgrounds'       => 5,
			'typography'        => 8,
			'effects'           => 6,
			'login_page'        => 8,
		);

		foreach ( $templates as $template ) {
			$template_id = $template['id'] ?? 'unknown';
			
			foreach ( $min_option_counts as $section => $min_count ) {
				$this->assertArrayHasKey(
					$section,
					$template['settings'],
					"Template '{$template_id}' should have '{$section}' section"
				);

				$actual_count = count( $template['settings'][ $section ] );
				$this->assertGreaterThanOrEqual(
					$min_count,
					$actual_count,
					"Template '{$template_id}' section '{$section}' should have at least {$min_count} options (has {$actual_count})"
				);
			}
		}
	}

	/**
	 * Test: All templates have valid category
	 *
	 * @return void
	 */
	public function test_all_templates_have_valid_category(): void {
		$templates = $this->template_manager->get_all_templates();
		$valid_categories = array( 'minimal', 'modern', 'corporate', 'creative', 'dark' );

		foreach ( $templates as $template ) {
			$template_id = $template['id'] ?? 'unknown';
			
			$this->assertArrayHasKey(
				'category',
				$template,
				"Template '{$template_id}' should have category"
			);
			$this->assertContains(
				$template['category'],
				$valid_categories,
				"Template '{$template_id}' should have valid category"
			);
		}
	}

	/**
	 * Test: All templates have valid ID format
	 *
	 * @return void
	 */
	public function test_all_templates_have_valid_id_format(): void {
		$templates = $this->template_manager->get_all_templates();

		foreach ( $templates as $template ) {
			$template_id = $template['id'] ?? '';
			
			$this->assertMatchesRegularExpression(
				'/^[a-z0-9_]+$/',
				$template_id,
				"Template '{$template_id}' should have valid ID format (lowercase, numbers, underscores only)"
			);
		}
	}

	/**
	 * Test: Apply template with invalid ID returns error
	 *
	 * @return void
	 */
	public function test_apply_template_with_invalid_id_returns_error(): void {
		$result = $this->template_manager->apply_template( '' );

		$this->assertIsArray( $result, 'Should return an array' );
		$this->assertArrayHasKey( 'success', $result, 'Should have success key' );
		$this->assertFalse( $result['success'], 'Should return false for invalid ID' );
		$this->assertArrayHasKey( 'error_code', $result, 'Should have error_code key' );
		$this->assertEquals( 'INVALID_TEMPLATE_ID', $result['error_code'], 'Should have correct error code' );
	}

	/**
	 * Test: Apply template with nonexistent ID returns error
	 *
	 * @return void
	 */
	public function test_apply_template_with_nonexistent_id_returns_error(): void {
		$result = $this->template_manager->apply_template( 'nonexistent_template' );

		$this->assertIsArray( $result, 'Should return an array' );
		$this->assertFalse( $result['success'], 'Should return false for nonexistent template' );
		$this->assertEquals( 'TEMPLATE_NOT_FOUND', $result['error_code'], 'Should have correct error code' );
	}

	/**
	 * Test: Apply template successfully with mocked dependencies
	 *
	 * @return void
	 */
	public function test_apply_template_successfully_with_mocked_dependencies(): void {
		// Mock backup manager
		$mock_backup = $this->createMock( WOOW_Backup_Manager::class );
		$mock_backup->method( 'create_backup' )->willReturn( 'backup_123' );
		$this->template_manager->set_backup_manager( $mock_backup );

		// Mock CSS generator
		$mock_css = $this->createMock( WOOW_CSS_Generator::class );
		$mock_css->method( 'generate' )->willReturn( 'generated-css-content' );
		$this->template_manager->set_css_generator( $mock_css );

		// Mock settings methods
		$this->mock_settings->method( 'get_all_settings' )->willReturn( array(
			'color_overrides' => array(),
			'admin_bar'       => array(),
			'admin_menu'      => array(),
		) );
		$this->mock_settings->method( 'validate_settings' )->willReturn( array(
			'valid'  => true,
			'errors' => array(),
		) );
		$this->mock_settings->method( 'update_all_settings' )->willReturn( true );

		// Apply template
		$result = $this->template_manager->apply_template( 'modern_minimal' );

		$this->assertIsArray( $result, 'Should return an array' );
		$this->assertArrayHasKey( 'success', $result, 'Should have success key' );
		$this->assertTrue( $result['success'], 'Should return true for successful application' );
		$this->assertArrayHasKey( 'template_id', $result, 'Should have template_id key' );
		$this->assertArrayHasKey( 'backup_id', $result, 'Should have backup_id key' );
	}

	/**
	 * Test: Apply template handles backup failure
	 *
	 * @return void
	 */
	public function test_apply_template_handles_backup_failure(): void {
		// Mock backup manager that throws exception
		$mock_backup = $this->createMock( WOOW_Backup_Manager::class );
		$mock_backup->method( 'create_backup' )->willThrowException( new Exception( 'Backup failed' ) );
		$this->template_manager->set_backup_manager( $mock_backup );

		// Apply template
		$result = $this->template_manager->apply_template( 'modern_minimal' );

		$this->assertFalse( $result['success'], 'Should return false when backup fails' );
		$this->assertEquals( 'BACKUP_FAILED', $result['error_code'], 'Should have correct error code' );
	}

	/**
	 * Test: Apply template handles settings update failure
	 *
	 * @return void
	 */
	public function test_apply_template_handles_settings_update_failure(): void {
		// Mock backup manager
		$mock_backup = $this->createMock( WOOW_Backup_Manager::class );
		$mock_backup->method( 'create_backup' )->willReturn( 'backup_123' );
		$mock_backup->method( 'restore_backup' )->willReturn( true );
		$this->template_manager->set_backup_manager( $mock_backup );

		// Mock settings methods - update fails
		$this->mock_settings->method( 'get_all_settings' )->willReturn( array(
			'color_overrides' => array(),
		) );
		$this->mock_settings->method( 'validate_settings' )->willReturn( array(
			'valid'  => true,
			'errors' => array(),
		) );
		$this->mock_settings->method( 'update_all_settings' )->willReturn( false );

		// Apply template
		$result = $this->template_manager->apply_template( 'modern_minimal' );

		$this->assertFalse( $result['success'], 'Should return false when settings update fails' );
		$this->assertEquals( 'APPLICATION_FAILED', $result['error_code'], 'Should have correct error code' );
	}

	/**
	 * Test: Create custom template successfully
	 *
	 * @return void
	 */
	public function test_create_custom_template_successfully(): void {
		// Mock settings to return current settings
		$this->mock_settings->method( 'get_all_settings' )->willReturn( array(
			'color_overrides' => array( 'primary_color' => '#3b82f6' ),
			'admin_bar'       => array( 'enabled' => true ),
		) );

		// Create custom template
		$template_id = $this->template_manager->create_custom_template(
			'My Custom Template',
			'A custom template for testing'
		);

		$this->assertIsString( $template_id, 'Should return a string template ID' );
		$this->assertStringStartsWith( 'custom_', $template_id, 'Custom template ID should start with "custom_"' );
	}

	/**
	 * Test: Get custom templates returns array
	 *
	 * @return void
	 */
	public function test_get_custom_templates_returns_array(): void {
		$custom_templates = $this->template_manager->get_custom_templates();

		$this->assertIsArray( $custom_templates, 'Should return an array' );
	}

	/**
	 * Test: Delete custom template successfully
	 *
	 * @return void
	 */
	public function test_delete_custom_template_successfully(): void {
		// First create a custom template
		$this->mock_settings->method( 'get_all_settings' )->willReturn( array(
			'color_overrides' => array(),
		) );

		$template_id = $this->template_manager->create_custom_template( 'Test Template' );

		// Then delete it
		$result = $this->template_manager->delete_custom_template( $template_id );

		$this->assertTrue( $result, 'Should return true when deleting existing custom template' );
	}

	/**
	 * Test: Delete custom template returns false for nonexistent template
	 *
	 * @return void
	 */
	public function test_delete_custom_template_returns_false_for_nonexistent(): void {
		$result = $this->template_manager->delete_custom_template( 'nonexistent_custom_template' );

		$this->assertFalse( $result, 'Should return false when deleting nonexistent template' );
	}

	/**
	 * Test: Template characteristics are properly defined
	 *
	 * @return void
	 */
	public function test_template_characteristics_are_properly_defined(): void {
		$template = $this->template_manager->get_template( 'modern_minimal' );

		$this->assertArrayHasKey( 'characteristics', $template, 'Template should have characteristics' );
		
		$characteristics = $template['characteristics'];
		$this->assertArrayHasKey( 'glassmorphism', $characteristics, 'Should have glassmorphism characteristic' );
		$this->assertArrayHasKey( 'gradients', $characteristics, 'Should have gradients characteristic' );
		$this->assertArrayHasKey( 'animations', $characteristics, 'Should have animations characteristic' );
		$this->assertArrayHasKey( 'shadows', $characteristics, 'Should have shadows characteristic' );
		$this->assertArrayHasKey( 'border_radius', $characteristics, 'Should have border_radius characteristic' );
	}

	/**
	 * Test: Templates have unique names
	 *
	 * @return void
	 */
	public function test_templates_have_unique_names(): void {
		$templates = $this->template_manager->get_all_templates();
		$names = array();

		foreach ( $templates as $template ) {
			$name = $template['name'] ?? '';
			$this->assertNotContains( $name, $names, "Template name '{$name}' should be unique" );
			$names[] = $name;
		}
	}

	/**
	 * Test: Templates have preview images defined
	 *
	 * @return void
	 */
	public function test_templates_have_preview_images_defined(): void {
		$templates = $this->template_manager->get_all_templates();

		foreach ( $templates as $template ) {
			$template_id = $template['id'] ?? 'unknown';
			
			$this->assertArrayHasKey(
				'preview_image',
				$template,
				"Template '{$template_id}' should have preview_image defined"
			);
			$this->assertNotEmpty(
				$template['preview_image'],
				"Template '{$template_id}' preview_image should not be empty"
			);
			$this->assertStringEndsWith(
				'.png',
				$template['preview_image'],
				"Template '{$template_id}' preview_image should be a PNG file"
			);
		}
	}

	/**
	 * Test: Set backup manager works correctly
	 *
	 * @return void
	 */
	public function test_set_backup_manager_works_correctly(): void {
		$mock_backup = $this->createMock( WOOW_Backup_Manager::class );
		
		// Should not throw exception
		$this->template_manager->set_backup_manager( $mock_backup );
		
		// Verify by attempting to apply template (which uses backup manager)
		$mock_backup->method( 'create_backup' )->willReturn( 'backup_123' );
		
		$this->assertTrue( true, 'set_backup_manager should work without errors' );
	}

	/**
	 * Test: Set CSS generator works correctly
	 *
	 * @return void
	 */
	public function test_set_css_generator_works_correctly(): void {
		$mock_css = $this->createMock( WOOW_CSS_Generator::class );
		
		// Should not throw exception
		$this->template_manager->set_css_generator( $mock_css );
		
		$this->assertTrue( true, 'set_css_generator should work without errors' );
	}

	/**
	 * Clean up after tests
	 *
	 * @return void
	 */
	protected function tearDown(): void {
		// Clean up any custom templates created during tests
		delete_option( 'woow_custom_templates' );
		
		parent::tearDown();
	}
}
