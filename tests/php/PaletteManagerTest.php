<?php
/**
 * Unit Tests for WOOW_Palette_Manager
 *
 * Tests palette loading, retrieval, validation, and application functionality.
 *
 * @package WoowAdmin
 * @subpackage Tests
 */

namespace WOOW\Tests;

use PHPUnit\Framework\TestCase;
use WOOW_Palette_Manager;
use WOOW_Settings;
use WOOW_Backup_Manager;
use WOOW_CSS_Generator;
use Exception;

/**
 * Class PaletteManagerTest
 *
 * Comprehensive unit tests for WOOW_Palette_Manager class.
 */
class PaletteManagerTest extends TestCase {
	/**
	 * Palette manager instance
	 *
	 * @var WOOW_Palette_Manager
	 */
	private WOOW_Palette_Manager $palette_manager;

	/**
	 * Mock settings instance
	 *
	 * @var WOOW_Settings
	 */
	private $mock_settings;

	/**
	 * Test palettes data directory
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

		// Create palette manager instance
		$this->palette_manager = new WOOW_Palette_Manager( $this->mock_settings );

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
			WOOW_Palette_Manager::class,
			$this->palette_manager,
			'Palette manager should be instantiated correctly'
		);
	}

	/**
	 * Test: Load palettes successfully
	 *
	 * @return void
	 */
	public function test_load_palettes_successfully(): void {
		// Check if palettes file exists
		$palettes_file = $this->test_data_dir . 'palettes.php';
		
		if ( ! file_exists( $palettes_file ) ) {
			$this->markTestSkipped( 'Palettes data file not found: ' . $palettes_file );
		}

		// Load palettes
		$this->palette_manager->load_palettes();

		// Get all palettes
		$palettes = $this->palette_manager->get_all_palettes();

		// Assert palettes loaded
		$this->assertIsArray( $palettes, 'Palettes should be an array' );
		$this->assertNotEmpty( $palettes, 'Palettes array should not be empty' );
		$this->assertGreaterThanOrEqual( 10, count( $palettes ), 'Should have at least 10 palettes' );
	}

	/**
	 * Test: Load palettes throws exception when file missing
	 *
	 * @return void
	 */
	public function test_load_palettes_throws_exception_when_file_missing(): void {
		// Temporarily rename palettes file
		$palettes_file = $this->test_data_dir . 'palettes.php';
		$backup_file = $this->test_data_dir . 'palettes.php.backup';

		if ( file_exists( $palettes_file ) ) {
			rename( $palettes_file, $backup_file );
		}

		try {
			$this->expectException( Exception::class );
			$this->expectExceptionMessage( 'Palettes data file not found' );
			
			$this->palette_manager->load_palettes();
		} finally {
			// Restore file
			if ( file_exists( $backup_file ) ) {
				rename( $backup_file, $palettes_file );
			}
		}
	}

	/**
	 * Test: Get all palettes returns array
	 *
	 * @return void
	 */
	public function test_get_all_palettes_returns_array(): void {
		$palettes = $this->palette_manager->get_all_palettes();

		$this->assertIsArray( $palettes, 'get_all_palettes should return an array' );
	}

	/**
	 * Test: Get all palettes returns expected count
	 *
	 * @return void
	 */
	public function test_get_all_palettes_returns_expected_count(): void {
		$palettes = $this->palette_manager->get_all_palettes();

		// Should have exactly 10 palettes as per requirements
		$this->assertCount( 10, $palettes, 'Should have exactly 10 palettes' );
	}

	/**
	 * Test: Get all palettes contains required palette IDs
	 *
	 * @return void
	 */
	public function test_get_all_palettes_contains_required_ids(): void {
		$palettes = $this->palette_manager->get_all_palettes();

		$required_ids = array(
			'professional_blue',
			'warm_sunset',
			'dark_mode_pro',
			'nature_green',
			'minimalist_gray',
			'vibrant_purple',
			'ocean_blue',
			'cherry_red',
			'monochrome_elite',
			'cyberpunk_neon',
		);

		foreach ( $required_ids as $palette_id ) {
			$this->assertArrayHasKey(
				$palette_id,
				$palettes,
				"Palettes should contain '{$palette_id}'"
			);
		}
	}

	/**
	 * Test: Get palette by ID returns correct palette
	 *
	 * @return void
	 */
	public function test_get_palette_returns_correct_palette(): void {
		$palette = $this->palette_manager->get_palette( 'professional_blue' );

		$this->assertIsArray( $palette, 'get_palette should return an array' );
		$this->assertArrayHasKey( 'id', $palette, 'Palette should have id key' );
		$this->assertEquals( 'professional_blue', $palette['id'], 'Palette ID should match' );
	}

	/**
	 * Test: Get palette returns null for non-existent ID
	 *
	 * @return void
	 */
	public function test_get_palette_returns_null_for_nonexistent_id(): void {
		$palette = $this->palette_manager->get_palette( 'nonexistent_palette' );

		$this->assertNull( $palette, 'get_palette should return null for non-existent palette' );
	}

	/**
	 * Test: Get palette sanitizes ID
	 *
	 * @return void
	 */
	public function test_get_palette_sanitizes_id(): void {
		// Try with uppercase - sanitize_key converts to lowercase and removes special chars
		// Our mock sanitize_key removes special chars, so 'PROFESSIONAL_BLUE!' becomes 'professional_blue'
		$palette = $this->palette_manager->get_palette( 'professional-blue' );

		// Should still find the palette after sanitization (dashes become underscores)
		// Actually, our mock removes dashes, so this will be 'professionalblue' which won't match
		// Let's test that invalid characters are handled gracefully
		$this->assertNull( $palette, 'get_palette should return null for ID that becomes invalid after sanitization' );
		
		// Test with valid uppercase ID
		$palette2 = $this->palette_manager->get_palette( 'PROFESSIONAL_BLUE' );
		$this->assertIsArray( $palette2, 'get_palette should sanitize uppercase to lowercase' );
		$this->assertEquals( 'professional_blue', $palette2['id'], 'Should find palette with lowercase ID' );
	}

	/**
	 * Test: Palette exists returns true for valid ID
	 *
	 * @return void
	 */
	public function test_palette_exists_returns_true_for_valid_id(): void {
		$exists = $this->palette_manager->palette_exists( 'professional_blue' );

		$this->assertTrue( $exists, 'palette_exists should return true for valid palette' );
	}

	/**
	 * Test: Palette exists returns false for invalid ID
	 *
	 * @return void
	 */
	public function test_palette_exists_returns_false_for_invalid_id(): void {
		$exists = $this->palette_manager->palette_exists( 'nonexistent_palette' );

		$this->assertFalse( $exists, 'palette_exists should return false for invalid palette' );
	}

	/**
	 * Test: Get palettes by category returns correct palettes
	 *
	 * @return void
	 */
	public function test_get_palettes_by_category_returns_correct_palettes(): void {
		$professional_palettes = $this->palette_manager->get_palettes_by_category( 'professional' );

		$this->assertIsArray( $professional_palettes, 'Should return an array' );
		$this->assertNotEmpty( $professional_palettes, 'Should have at least one professional palette' );

		// Check that all returned palettes have the correct category
		foreach ( $professional_palettes as $palette ) {
			$this->assertEquals(
				'professional',
				$palette['category'],
				'All palettes should have professional category'
			);
		}
	}

	/**
	 * Test: Get palettes by category returns empty for invalid category
	 *
	 * @return void
	 */
	public function test_get_palettes_by_category_returns_empty_for_invalid_category(): void {
		$palettes = $this->palette_manager->get_palettes_by_category( 'nonexistent_category' );

		$this->assertIsArray( $palettes, 'Should return an array' );
		$this->assertEmpty( $palettes, 'Should return empty array for invalid category' );
	}

	/**
	 * Test: Get categories returns all unique categories
	 *
	 * @return void
	 */
	public function test_get_categories_returns_all_unique_categories(): void {
		$categories = $this->palette_manager->get_categories();

		$this->assertIsArray( $categories, 'Should return an array' );
		$this->assertNotEmpty( $categories, 'Should have at least one category' );
		
		// Check for duplicates
		$unique_categories = array_unique( $categories );
		$this->assertCount(
			count( $categories ),
			$unique_categories,
			'Categories should be unique'
		);
	}

	/**
	 * Test: Palette has all required keys
	 *
	 * @return void
	 */
	public function test_palette_has_all_required_keys(): void {
		$palette = $this->palette_manager->get_palette( 'professional_blue' );

		$required_keys = array( 'id', 'name', 'description', 'category', 'settings' );

		foreach ( $required_keys as $key ) {
			$this->assertArrayHasKey(
				$key,
				$palette,
				"Palette should have '{$key}' key"
			);
		}
	}

	/**
	 * Test: Palette settings has all required sections
	 *
	 * @return void
	 */
	public function test_palette_settings_has_all_required_sections(): void {
		$palette = $this->palette_manager->get_palette( 'professional_blue' );

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

		$this->assertArrayHasKey( 'settings', $palette, 'Palette should have settings key' );

		foreach ( $required_sections as $section ) {
			$this->assertArrayHasKey(
				$section,
				$palette['settings'],
				"Palette settings should have '{$section}' section"
			);
		}
	}

	/**
	 * Test: Check completeness returns complete for valid palette
	 *
	 * @return void
	 */
	public function test_check_completeness_returns_complete_for_valid_palette(): void {
		$palette = $this->palette_manager->get_palette( 'professional_blue' );
		$completeness = $this->palette_manager->check_completeness( $palette );

		$this->assertIsArray( $completeness, 'Should return an array' );
		$this->assertArrayHasKey( 'complete', $completeness, 'Should have complete key' );
		$this->assertArrayHasKey( 'missing', $completeness, 'Should have missing key' );
		$this->assertArrayHasKey( 'sections', $completeness, 'Should have sections key' );
		
		$this->assertTrue( $completeness['complete'], 'Valid palette should be complete' );
		$this->assertEmpty( $completeness['missing'], 'Valid palette should have no missing items' );
		$this->assertEquals( 10, $completeness['sections'], 'Should have 10 sections' );
	}

	/**
	 * Test: Check completeness detects incomplete palette
	 *
	 * @return void
	 */
	public function test_check_completeness_detects_incomplete_palette(): void {
		$incomplete_palette = array(
			'id'       => 'test_palette',
			'name'     => 'Test Palette',
			'settings' => array(
				'color_overrides' => array(),
				// Missing other sections
			),
		);

		$completeness = $this->palette_manager->check_completeness( $incomplete_palette );

		$this->assertFalse( $completeness['complete'], 'Incomplete palette should not be complete' );
		$this->assertNotEmpty( $completeness['missing'], 'Incomplete palette should have missing items' );
	}

	/**
	 * Test: Get palette count returns correct number
	 *
	 * @return void
	 */
	public function test_get_palette_count_returns_correct_number(): void {
		$count = $this->palette_manager->get_palette_count();

		$this->assertIsInt( $count, 'Should return an integer' );
		$this->assertEquals( 10, $count, 'Should have exactly 10 palettes' );
	}

	/**
	 * Test: Get preview image URL returns valid URL
	 *
	 * @return void
	 */
	public function test_get_preview_image_url_returns_valid_url(): void {
		$url = $this->palette_manager->get_preview_image_url( 'professional_blue' );

		$this->assertIsString( $url, 'Should return a string' );
		$this->assertStringContainsString( 'assets/images/previews/palettes/', $url, 'URL should contain correct path' );
		$this->assertStringContainsString( '.png', $url, 'URL should point to PNG file' );
	}

	/**
	 * Test: Get preview image URL returns null for invalid palette
	 *
	 * @return void
	 */
	public function test_get_preview_image_url_returns_null_for_invalid_palette(): void {
		$url = $this->palette_manager->get_preview_image_url( 'nonexistent_palette' );

		$this->assertNull( $url, 'Should return null for invalid palette' );
	}

	/**
	 * Test: Apply palette with invalid ID returns error
	 *
	 * @return void
	 */
	public function test_apply_palette_with_invalid_id_returns_error(): void {
		$result = $this->palette_manager->apply_palette( '' );

		$this->assertIsArray( $result, 'Should return an array' );
		$this->assertArrayHasKey( 'success', $result, 'Should have success key' );
		$this->assertFalse( $result['success'], 'Should return false for invalid ID' );
		$this->assertArrayHasKey( 'error_code', $result, 'Should have error_code key' );
		$this->assertEquals( 'INVALID_PALETTE_ID', $result['error_code'], 'Should have correct error code' );
	}

	/**
	 * Test: Apply palette with nonexistent ID returns error
	 *
	 * @return void
	 */
	public function test_apply_palette_with_nonexistent_id_returns_error(): void {
		$result = $this->palette_manager->apply_palette( 'nonexistent_palette' );

		$this->assertIsArray( $result, 'Should return an array' );
		$this->assertFalse( $result['success'], 'Should return false for nonexistent palette' );
		$this->assertEquals( 'PALETTE_NOT_FOUND', $result['error_code'], 'Should have correct error code' );
	}

	/**
	 * Test: Apply palette successfully with mocked dependencies
	 *
	 * @return void
	 */
	public function test_apply_palette_successfully_with_mocked_dependencies(): void {
		// Mock backup manager
		$mock_backup = $this->createMock( WOOW_Backup_Manager::class );
		$mock_backup->method( 'create_backup' )->willReturn( 'backup_123' );
		$this->palette_manager->set_backup_manager( $mock_backup );

		// Mock CSS generator
		$mock_css = $this->createMock( WOOW_CSS_Generator::class );
		$mock_css->method( 'generate' )->willReturn( 'generated-css-content' );
		$this->palette_manager->set_css_generator( $mock_css );

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

		// Apply palette
		$result = $this->palette_manager->apply_palette( 'professional_blue' );

		$this->assertIsArray( $result, 'Should return an array' );
		$this->assertArrayHasKey( 'success', $result, 'Should have success key' );
		$this->assertTrue( $result['success'], 'Should return true for successful application' );
		$this->assertArrayHasKey( 'palette_id', $result, 'Should have palette_id key' );
		$this->assertArrayHasKey( 'backup_id', $result, 'Should have backup_id key' );
	}

	/**
	 * Test: Apply palette handles backup failure
	 *
	 * @return void
	 */
	public function test_apply_palette_handles_backup_failure(): void {
		// Mock backup manager that throws exception
		$mock_backup = $this->createMock( WOOW_Backup_Manager::class );
		$mock_backup->method( 'create_backup' )->willThrowException( new Exception( 'Backup failed' ) );
		$this->palette_manager->set_backup_manager( $mock_backup );

		// Apply palette
		$result = $this->palette_manager->apply_palette( 'professional_blue' );

		$this->assertFalse( $result['success'], 'Should return false when backup fails' );
		$this->assertEquals( 'BACKUP_FAILED', $result['error_code'], 'Should have correct error code' );
	}

	/**
	 * Test: Apply palette handles settings update failure
	 *
	 * @return void
	 */
	public function test_apply_palette_handles_settings_update_failure(): void {
		// Mock backup manager
		$mock_backup = $this->createMock( WOOW_Backup_Manager::class );
		$mock_backup->method( 'create_backup' )->willReturn( 'backup_123' );
		$mock_backup->method( 'restore_backup' )->willReturn( true );
		$this->palette_manager->set_backup_manager( $mock_backup );

		// Mock settings methods - update fails
		$this->mock_settings->method( 'get_all_settings' )->willReturn( array(
			'color_overrides' => array(),
		) );
		$this->mock_settings->method( 'validate_settings' )->willReturn( array(
			'valid'  => true,
			'errors' => array(),
		) );
		$this->mock_settings->method( 'update_all_settings' )->willReturn( false );

		// Apply palette
		$result = $this->palette_manager->apply_palette( 'professional_blue' );

		$this->assertFalse( $result['success'], 'Should return false when settings update fails' );
		$this->assertEquals( 'APPLICATION_FAILED', $result['error_code'], 'Should have correct error code' );
	}

	/**
	 * Test: All palettes have minimum required options
	 *
	 * @return void
	 */
	public function test_all_palettes_have_minimum_required_options(): void {
		$palettes = $this->palette_manager->get_all_palettes();

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

		foreach ( $palettes as $palette_id => $palette ) {
			foreach ( $min_option_counts as $section => $min_count ) {
				$this->assertArrayHasKey(
					$section,
					$palette['settings'],
					"Palette '{$palette_id}' should have '{$section}' section"
				);

				$actual_count = count( $palette['settings'][ $section ] );
				$this->assertGreaterThanOrEqual(
					$min_count,
					$actual_count,
					"Palette '{$palette_id}' section '{$section}' should have at least {$min_count} options (has {$actual_count})"
				);
			}
		}
	}

	/**
	 * Test: All palettes have valid category
	 *
	 * @return void
	 */
	public function test_all_palettes_have_valid_category(): void {
		$palettes = $this->palette_manager->get_all_palettes();
		$valid_categories = array( 'professional', 'creative', 'minimal', 'dark', 'vibrant' );

		foreach ( $palettes as $palette_id => $palette ) {
			$this->assertArrayHasKey(
				'category',
				$palette,
				"Palette '{$palette_id}' should have category"
			);
			$this->assertContains(
				$palette['category'],
				$valid_categories,
				"Palette '{$palette_id}' should have valid category"
			);
		}
	}

	/**
	 * Test: All palettes have valid ID format
	 *
	 * @return void
	 */
	public function test_all_palettes_have_valid_id_format(): void {
		$palettes = $this->palette_manager->get_all_palettes();

		foreach ( $palettes as $palette_id => $palette ) {
			$this->assertMatchesRegularExpression(
				'/^[a-z0-9_]+$/',
				$palette['id'],
				"Palette '{$palette_id}' should have valid ID format (lowercase, numbers, underscores only)"
			);
		}
	}

	/**
	 * Clean up after tests
	 *
	 * @return void
	 */
	protected function tearDown(): void {
		parent::tearDown();
	}
}
