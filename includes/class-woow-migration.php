<?php
/**
 * WOOW Migration
 *
 * Handles version migrations and data structure updates.
 * Ensures smooth upgrades between plugin versions.
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WOOW_Migration
 *
 * Manages plugin version migrations.
 */
class WOOW_Migration {
	/**
	 * Option name for stored version
	 *
	 * @var string
	 */
	private const VERSION_OPTION = 'woow_admin_version';

	/**
	 * Option name for migration log
	 *
	 * @var string
	 */
	private const MIGRATION_LOG = 'woow_migration_log';

	/**
	 * Run pending migrations
	 *
	 * Checks current version and runs necessary migrations.
	 *
	 * @return void
	 */
	public function run_migrations(): void {
		$current_version = $this->get_current_version();
		$plugin_version  = WOOW_VERSION;

		// No migration needed if versions match
		if ( version_compare( $current_version, $plugin_version, '=' ) ) {
			return;
		}

		// Log migration start
		$this->log_migration( "Starting migration from {$current_version} to {$plugin_version}" );

		try {
			// Run migrations in order
			if ( version_compare( $current_version, '1.0.0', '<' ) ) {
				$this->migrate_to_1_0_0();
			}

			if ( version_compare( $current_version, '1.1.0', '<' ) && version_compare( $plugin_version, '1.1.0', '>=' ) ) {
				$this->migrate_to_1_1_0();
			}

			if ( version_compare( $current_version, '1.2.0', '<' ) && version_compare( $plugin_version, '1.2.0', '>=' ) ) {
				$this->migrate_to_1_2_0();
			}

			// Update version after successful migration
			$this->update_version( $plugin_version );

			// Log migration success
			$this->log_migration( "Migration completed successfully to {$plugin_version}" );

		} catch ( Exception $e ) {
			// Log migration error
			$this->log_migration( "Migration failed: " . $e->getMessage(), 'error' );
			error_log( '[WOOW Admin] Migration failed: ' . $e->getMessage() );
		}
	}

	/**
	 * Get current stored version
	 *
	 * @return string Current version or '0.0.0' if not set.
	 */
	public function get_current_version(): string {
		return get_option( self::VERSION_OPTION, '0.0.0' );
	}

	/**
	 * Update stored version
	 *
	 * @param string $version Version to store.
	 * @return bool True on success.
	 */
	private function update_version( string $version ): bool {
		return update_option( self::VERSION_OPTION, $version );
	}

	/**
	 * Migration to version 1.0.0
	 *
	 * Initial version - set up default settings.
	 *
	 * @return void
	 */
	private function migrate_to_1_0_0(): void {
		$this->log_migration( 'Running migration to 1.0.0' );

		// Initialize default settings if not exists
		$settings = get_option( 'woow_admin_settings' );

		if ( false === $settings ) {
			$settings_manager = new WOOW_Settings();
			$default_settings = $settings_manager->get_default_settings();
			update_option( 'woow_admin_settings', $default_settings );

			$this->log_migration( 'Default settings initialized' );
		}

		// Create initial backup
		try {
			$backup_manager = new WOOW_Backup_Manager( new WOOW_Settings() );
			$backup_manager->create_backup( 'migration_1_0_0' );
			$this->log_migration( 'Initial backup created' );
		} catch ( Exception $e ) {
			$this->log_migration( 'Failed to create initial backup: ' . $e->getMessage(), 'warning' );
		}
	}

	/**
	 * Migration to version 1.1.0
	 *
	 * Example: Add new settings fields, update data structures.
	 *
	 * @return void
	 */
	private function migrate_to_1_1_0(): void {
		$this->log_migration( 'Running migration to 1.1.0' );

		// Get current settings
		$settings = get_option( 'woow_admin_settings', array() );

		// Example: Add new fields to existing sections
		if ( isset( $settings['admin_bar'] ) && ! isset( $settings['admin_bar']['transition_speed'] ) ) {
			$settings['admin_bar']['transition_speed'] = '200ms';
			$this->log_migration( 'Added transition_speed to admin_bar' );
		}

		// Example: Add new section
		if ( ! isset( $settings['accessibility'] ) ) {
			$settings['accessibility'] = array(
				'enabled'              => true,
				'high_contrast_mode'   => false,
				'focus_indicators'     => true,
				'keyboard_navigation'  => true,
				'screen_reader_support' => true,
			);
			$this->log_migration( 'Added accessibility section' );
		}

		// Save updated settings
		update_option( 'woow_admin_settings', $settings );

		// Create backup after migration
		try {
			$backup_manager = new WOOW_Backup_Manager( new WOOW_Settings() );
			$backup_manager->create_backup( 'migration_1_1_0' );
			$this->log_migration( 'Migration backup created' );
		} catch ( Exception $e ) {
			$this->log_migration( 'Failed to create migration backup: ' . $e->getMessage(), 'warning' );
		}
	}

	/**
	 * Migration to version 1.2.0
	 *
	 * Example: Update color format, restructure data.
	 *
	 * @return void
	 */
	private function migrate_to_1_2_0(): void {
		$this->log_migration( 'Running migration to 1.2.0' );

		// Get current settings
		$settings = get_option( 'woow_admin_settings', array() );

		// Example: Convert old color format to new format
		foreach ( $settings as $section => $section_data ) {
			if ( is_array( $section_data ) ) {
				foreach ( $section_data as $key => $value ) {
					// Example: Convert rgb() to rgba()
					if ( is_string( $value ) && strpos( $value, 'rgb(' ) === 0 ) {
						$value = str_replace( 'rgb(', 'rgba(', $value );
						$value = str_replace( ')', ',1)', $value );
						$settings[ $section ][ $key ] = $value;
						$this->log_migration( "Converted color format in {$section}.{$key}" );
					}
				}
			}
		}

		// Save updated settings
		update_option( 'woow_admin_settings', $settings );

		// Clear CSS cache to regenerate with new format
		$cache = new WOOW_Cache_Manager();
		$cache->delete( 'woow_css' );
		$this->log_migration( 'CSS cache cleared' );

		// Create backup after migration
		try {
			$backup_manager = new WOOW_Backup_Manager( new WOOW_Settings() );
			$backup_manager->create_backup( 'migration_1_2_0' );
			$this->log_migration( 'Migration backup created' );
		} catch ( Exception $e ) {
			$this->log_migration( 'Failed to create migration backup: ' . $e->getMessage(), 'warning' );
		}
	}

	/**
	 * Log migration event
	 *
	 * @param string $message Log message.
	 * @param string $level   Log level: 'info', 'warning', 'error'.
	 * @return void
	 */
	private function log_migration( string $message, string $level = 'info' ): void {
		$log = get_option( self::MIGRATION_LOG, array() );

		$log[] = array(
			'timestamp' => time(),
			'level'     => $level,
			'message'   => $message,
		);

		// Keep only last 100 log entries
		if ( count( $log ) > 100 ) {
			$log = array_slice( $log, -100 );
		}

		update_option( self::MIGRATION_LOG, $log );

		// Also log to WordPress debug log
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( "[WOOW Admin Migration] [{$level}] {$message}" );
		}
	}

	/**
	 * Get migration log
	 *
	 * @param int $limit Number of entries to return (default: 50).
	 * @return array Migration log entries.
	 */
	public function get_migration_log( int $limit = 50 ): array {
		$log = get_option( self::MIGRATION_LOG, array() );

		// Return most recent entries
		return array_slice( array_reverse( $log ), 0, $limit );
	}

	/**
	 * Clear migration log
	 *
	 * @return bool True on success.
	 */
	public function clear_migration_log(): bool {
		return delete_option( self::MIGRATION_LOG );
	}

	/**
	 * Check if migration is needed
	 *
	 * @return bool True if migration is needed.
	 */
	public function needs_migration(): bool {
		$current_version = $this->get_current_version();
		$plugin_version  = WOOW_VERSION;

		return version_compare( $current_version, $plugin_version, '<' );
	}

	/**
	 * Get migration status
	 *
	 * @return array Migration status information.
	 */
	public function get_migration_status(): array {
		$current_version = $this->get_current_version();
		$plugin_version  = WOOW_VERSION;
		$needs_migration = $this->needs_migration();

		return array(
			'current_version' => $current_version,
			'plugin_version'  => $plugin_version,
			'needs_migration' => $needs_migration,
			'last_migration'  => $this->get_last_migration_time(),
		);
	}

	/**
	 * Get last migration time
	 *
	 * @return int|null Timestamp of last migration or null.
	 */
	private function get_last_migration_time(): ?int {
		$log = get_option( self::MIGRATION_LOG, array() );

		if ( empty( $log ) ) {
			return null;
		}

		$last_entry = end( $log );

		return $last_entry['timestamp'] ?? null;
	}

	/**
	 * Rollback to previous version
	 *
	 * Attempts to restore from the most recent migration backup.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function rollback(): bool {
		try {
			$backup_manager = new WOOW_Backup_Manager( new WOOW_Settings() );
			$backups        = $backup_manager->get_backups();

			// Find most recent migration backup
			foreach ( $backups as $backup ) {
				if ( strpos( $backup['label'], 'migration_' ) === 0 ) {
					$result = $backup_manager->restore_backup( $backup['id'] );

					if ( $result ) {
						$this->log_migration( 'Rollback successful from backup: ' . $backup['id'] );
						return true;
					}
				}
			}

			$this->log_migration( 'No migration backup found for rollback', 'error' );
			return false;

		} catch ( Exception $e ) {
			$this->log_migration( 'Rollback failed: ' . $e->getMessage(), 'error' );
			return false;
		}
	}

	/**
	 * Force version update
	 *
	 * Manually sets the stored version without running migrations.
	 * Use with caution!
	 *
	 * @param string $version Version to set.
	 * @return bool True on success.
	 */
	public function force_version_update( string $version ): bool {
		$this->log_migration( "Force version update to {$version}", 'warning' );
		return $this->update_version( $version );
	}
}
