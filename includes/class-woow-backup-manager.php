<?php
/**
 * WOOW Backup Manager
 *
 * Handles automatic backup and restore of plugin settings.
 * Maintains up to 10 backups with automatic cleanup.
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WOOW_Backup_Manager
 *
 * Manages backup and restore operations for plugin settings.
 */
class WOOW_Backup_Manager {
	/**
	 * Settings manager instance
	 *
	 * @var WOOW_Settings
	 */
	private WOOW_Settings $settings;

	/**
	 * Maximum number of backups to keep
	 *
	 * @var int
	 */
	private const MAX_BACKUPS = 10;

	/**
	 * Option name prefix for backups
	 *
	 * @var string
	 */
	private const BACKUP_PREFIX = 'woow_backup_';

	/**
	 * Option name for backup index
	 *
	 * @var string
	 */
	private const BACKUP_INDEX = 'woow_backup_index';

	/**
	 * Constructor
	 *
	 * @param WOOW_Settings $settings Settings manager instance.
	 */
	public function __construct( WOOW_Settings $settings ) {
		$this->settings = $settings;
	}

	/**
	 * Create a new backup
	 *
	 * Stores current settings with timestamp and optional label.
	 * Automatically deletes oldest backup if limit is reached.
	 *
	 * @param string $label Optional label for the backup (e.g., 'activation', 'manual', 'auto').
	 * @return string Backup ID on success.
	 * @throws Exception If backup creation fails.
	 */
	public function create_backup( string $label = '' ): string {
		$timestamp = time();
		$backup_id = self::BACKUP_PREFIX . $timestamp;

		// Get current settings
		$current_settings = $this->settings->get_all_settings();

		// Create backup data
		$backup_data = array(
			'id'        => $backup_id,
			'timestamp' => $timestamp,
			'label'     => $label ?: 'manual',
			'settings'  => $current_settings,
			'metadata'  => array(
				'version'   => WOOW_VERSION,
				'user_id'   => get_current_user_id(),
				'user_name' => wp_get_current_user()->display_name,
				'site_url'  => get_site_url(),
			),
		);

		// Store backup
		$result = update_option( $backup_id, $backup_data, false );

		if ( ! $result ) {
			throw new Exception( 'Failed to create backup' );
		}

		// Update backup index
		$this->add_to_index( $backup_id );

		// Enforce backup limit
		$this->enforce_backup_limit();

		return $backup_id;
	}

	/**
	 * Get all backups
	 *
	 * Returns list of backups sorted by timestamp (newest first).
	 *
	 * @return array Array of backup data.
	 */
	public function get_backups(): array {
		$index   = $this->get_backup_index();
		$backups = array();

		foreach ( $index as $backup_id ) {
			$backup = get_option( $backup_id );

			if ( $backup && is_array( $backup ) ) {
				// Return simplified backup info (without full settings)
				$backups[] = array(
					'id'        => $backup['id'],
					'timestamp' => $backup['timestamp'],
					'label'     => $backup['label'],
					'date'      => date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $backup['timestamp'] ),
					'metadata'  => $backup['metadata'] ?? array(),
				);
			}
		}

		// Sort by timestamp (newest first)
		usort(
			$backups,
			function ( $a, $b ) {
				return $b['timestamp'] - $a['timestamp'];
			}
		);

		return $backups;
	}

	/**
	 * Restore a backup
	 *
	 * Applies backup settings and creates a new backup before restoring.
	 *
	 * @param string $backup_id Backup ID to restore.
	 * @return bool True on success, false on failure.
	 */
	public function restore_backup( string $backup_id ): bool {
		// Get backup data
		$backup = get_option( $backup_id );

		if ( ! $backup || ! is_array( $backup ) || ! isset( $backup['settings'] ) ) {
			return false;
		}

		try {
			// Create backup of current state before restoring
			$this->create_backup( 'before_restore' );

			// Restore settings
			$result = $this->settings->update_all_settings( $backup['settings'] );

			if ( $result ) {
				// Clear CSS cache after restore
				$cache = new WOOW_Cache_Manager();
				$cache->delete( 'woow_css' );

				return true;
			}

			return false;
		} catch ( Exception $e ) {
			error_log( '[WOOW Admin] Backup restore failed: ' . $e->getMessage() );
			return false;
		}
	}

	/**
	 * Restore the most recent backup
	 *
	 * Convenience method to restore the latest backup without needing to know the ID.
	 * Useful for automatic rollback scenarios.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function restore_latest(): bool {
		$backups = $this->get_backups();

		if ( empty( $backups ) ) {
			error_log( '[WOOW Admin] No backups available to restore' );
			return false;
		}

		// Get the most recent backup (backups are already sorted newest first)
		$latest_backup = $backups[0];

		return $this->restore_backup( $latest_backup['id'] );
	}

	/**
	 * Delete a backup
	 *
	 * Removes backup from options and updates index.
	 *
	 * @param string $backup_id Backup ID to delete.
	 * @return bool True on success, false on failure.
	 */
	public function delete_backup( string $backup_id ): bool {
		// Delete backup option
		$result = delete_option( $backup_id );

		if ( $result ) {
			// Remove from index
			$this->remove_from_index( $backup_id );
			return true;
		}

		return false;
	}

	/**
	 * Get backup by ID
	 *
	 * Returns full backup data including settings.
	 *
	 * @param string $backup_id Backup ID.
	 * @return array|null Backup data or null if not found.
	 */
	public function get_backup( string $backup_id ): ?array {
		$backup = get_option( $backup_id );

		if ( $backup && is_array( $backup ) ) {
			return $backup;
		}

		return null;
	}

	/**
	 * Get backup index
	 *
	 * Returns array of backup IDs.
	 *
	 * @return array Array of backup IDs.
	 */
	private function get_backup_index(): array {
		$index = get_option( self::BACKUP_INDEX, array() );

		if ( ! is_array( $index ) ) {
			$index = array();
		}

		return $index;
	}

	/**
	 * Add backup to index
	 *
	 * @param string $backup_id Backup ID to add.
	 * @return void
	 */
	private function add_to_index( string $backup_id ): void {
		$index = $this->get_backup_index();

		if ( ! in_array( $backup_id, $index, true ) ) {
			$index[] = $backup_id;
			update_option( self::BACKUP_INDEX, $index, false );
		}
	}

	/**
	 * Remove backup from index
	 *
	 * @param string $backup_id Backup ID to remove.
	 * @return void
	 */
	private function remove_from_index( string $backup_id ): void {
		$index = $this->get_backup_index();
		$index = array_diff( $index, array( $backup_id ) );
		update_option( self::BACKUP_INDEX, array_values( $index ), false );
	}

	/**
	 * Enforce backup limit
	 *
	 * Deletes oldest backups if limit is exceeded.
	 *
	 * @return void
	 */
	private function enforce_backup_limit(): void {
		$backups = $this->get_backups();

		if ( count( $backups ) > self::MAX_BACKUPS ) {
			// Sort by timestamp (oldest first)
			usort(
				$backups,
				function ( $a, $b ) {
					return $a['timestamp'] - $b['timestamp'];
				}
			);

			// Delete oldest backups
			$to_delete = count( $backups ) - self::MAX_BACKUPS;

			for ( $i = 0; $i < $to_delete; $i++ ) {
				$this->delete_backup( $backups[ $i ]['id'] );
			}
		}
	}

	/**
	 * Clean up all backups
	 *
	 * Removes all backups and index. Use with caution.
	 *
	 * @return bool True on success.
	 */
	public function cleanup_all_backups(): bool {
		$index = $this->get_backup_index();

		foreach ( $index as $backup_id ) {
			delete_option( $backup_id );
		}

		delete_option( self::BACKUP_INDEX );

		return true;
	}

	/**
	 * Get backup statistics
	 *
	 * Returns information about backup usage.
	 *
	 * @return array Backup statistics.
	 */
	public function get_stats(): array {
		$backups      = $this->get_backups();
		$total_size   = 0;
		$oldest       = null;
		$newest       = null;

		foreach ( $backups as $backup ) {
			$backup_data = $this->get_backup( $backup['id'] );

			if ( $backup_data ) {
				$total_size += strlen( maybe_serialize( $backup_data ) );
			}

			if ( null === $oldest || $backup['timestamp'] < $oldest ) {
				$oldest = $backup['timestamp'];
			}

			if ( null === $newest || $backup['timestamp'] > $newest ) {
				$newest = $backup['timestamp'];
			}
		}

		return array(
			'count'      => count( $backups ),
			'max'        => self::MAX_BACKUPS,
			'total_size' => $total_size,
			'oldest'     => $oldest ? date_i18n( get_option( 'date_format' ), $oldest ) : null,
			'newest'     => $newest ? date_i18n( get_option( 'date_format' ), $newest ) : null,
		);
	}
}
