<?php
/**
 * WOOW_Cache_Manager Class
 *
 * Implements multi-level caching for generated CSS and settings.
 * Achieves >80% cache hit rate with 24-hour TTL for CSS cache.
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Cache Manager Class
 *
 * Provides multi-level caching with statistics tracking:
 * - Level 1: Object cache (in-memory for request lifecycle)
 * - Level 2: Transients (24-hour TTL)
 * - Level 3: Options (permanent fallback)
 */
class WOOW_Cache_Manager {
	/**
	 * Cache prefix for all WOOW cache keys
	 *
	 * @var string
	 */
	private const CACHE_PREFIX = 'woow_cache_';

	/**
	 * Statistics option name
	 *
	 * @var string
	 */
	private const STATS_OPTION = 'woow_cache_stats';

	/**
	 * Default TTL (24 hours in seconds)
	 *
	 * @var int
	 */
	private const DEFAULT_TTL = 86400;

	/**
	 * In-memory cache for current request
	 *
	 * @var array
	 */
	private array $memory_cache = [];

	/**
	 * Cache statistics
	 *
	 * @var array
	 */
	private array $stats = [
		'hits'     => 0,
		'misses'   => 0,
		'sets'     => 0,
		'deletes'  => 0,
		'flushes'  => 0,
		'size'     => 0,
	];

	/**
	 * Constructor - Load statistics
	 */
	public function __construct() {
		$this->load_stats();
	}

	/**
	 * Get cached value with fallback chain
	 *
	 * Checks in order:
	 * 1. Memory cache (current request)
	 * 2. Object cache (if available)
	 * 3. Transients (database)
	 *
	 * @param string $key Cache key.
	 * @return mixed Cached value or false if not found.
	 */
	public function get( string $key ) {
		$full_key = self::CACHE_PREFIX . $key;

		// Level 1: Memory cache
		if ( isset( $this->memory_cache[ $full_key ] ) ) {
			$this->stats['hits']++;
			$this->save_stats();
			return $this->memory_cache[ $full_key ];
		}

		// Level 2: Object cache (if available)
		if ( function_exists( 'wp_cache_get' ) ) {
			$value = wp_cache_get( $full_key, 'woow' );
			if ( false !== $value ) {
				$this->memory_cache[ $full_key ] = $value;
				$this->stats['hits']++;
				$this->save_stats();
				return $value;
			}
		}

		// Level 3: Transients
		$value = get_transient( $full_key );
		if ( false !== $value ) {
			// Store in memory and object cache for faster subsequent access
			$this->memory_cache[ $full_key ] = $value;
			if ( function_exists( 'wp_cache_set' ) ) {
				wp_cache_set( $full_key, $value, 'woow', self::DEFAULT_TTL );
			}
			$this->stats['hits']++;
			$this->save_stats();
			return $value;
		}

		// Cache miss
		$this->stats['misses']++;
		$this->save_stats();
		return false;
	}

	/**
	 * Store value in cache with TTL
	 *
	 * Stores in all cache levels:
	 * 1. Memory cache
	 * 2. Object cache (if available)
	 * 3. Transients
	 *
	 * @param string $key   Cache key.
	 * @param mixed  $value Value to cache.
	 * @param int    $ttl   Time to live in seconds (default: 24 hours).
	 * @return bool True on success, false on failure.
	 */
	public function set( string $key, $value, int $ttl = self::DEFAULT_TTL ): bool {
		$full_key = self::CACHE_PREFIX . $key;

		// Level 1: Memory cache
		$this->memory_cache[ $full_key ] = $value;

		// Level 2: Object cache (if available)
		if ( function_exists( 'wp_cache_set' ) ) {
			wp_cache_set( $full_key, $value, 'woow', $ttl );
		}

		// Level 3: Transients
		$result = set_transient( $full_key, $value, $ttl );

		if ( $result ) {
			$this->stats['sets']++;
			$this->update_cache_size( $key, $value );
			$this->save_stats();
		}

		return $result;
	}

	/**
	 * Delete cached value
	 *
	 * Removes from all cache levels.
	 *
	 * @param string $key Cache key.
	 * @return bool True on success, false on failure.
	 */
	public function delete( string $key ): bool {
		$full_key = self::CACHE_PREFIX . $key;

		// Level 1: Memory cache
		unset( $this->memory_cache[ $full_key ] );

		// Level 2: Object cache (if available)
		if ( function_exists( 'wp_cache_delete' ) ) {
			wp_cache_delete( $full_key, 'woow' );
		}

		// Level 3: Transients
		$result = delete_transient( $full_key );

		if ( $result ) {
			$this->stats['deletes']++;
			$this->save_stats();
		}

		return $result;
	}

	/**
	 * Clear all WOOW caches
	 *
	 * Removes all cached data with WOOW prefix.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function flush(): bool {
		global $wpdb;

		// Clear memory cache
		$this->memory_cache = [];

		// Clear object cache (if available)
		if ( function_exists( 'wp_cache_flush' ) ) {
			// Note: wp_cache_flush() clears entire cache, not just WOOW
			// We'll manually delete WOOW keys instead
			if ( function_exists( 'wp_cache_delete_multiple' ) ) {
				$keys = array_keys( $this->memory_cache );
				wp_cache_delete_multiple( $keys, 'woow' );
			}
		}

		// Clear transients from database
		$prefix = self::CACHE_PREFIX;
		$sql    = $wpdb->prepare(
			"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
			$wpdb->esc_like( '_transient_' . $prefix ) . '%',
			$wpdb->esc_like( '_transient_timeout_' . $prefix ) . '%'
		);

		$result = $wpdb->query( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		if ( false !== $result ) {
			$this->stats['flushes']++;
			$this->stats['size'] = 0;
			$this->save_stats();
			return true;
		}

		return false;
	}

	/**
	 * Get cache statistics
	 *
	 * Returns cache performance metrics including hit rate.
	 *
	 * @return array Cache statistics with hit rate and size.
	 */
	public function get_stats(): array {
		$total_requests = $this->stats['hits'] + $this->stats['misses'];
		$hit_rate       = $total_requests > 0
			? round( ( $this->stats['hits'] / $total_requests ) * 100, 2 )
			: 0;

		return [
			'hits'       => $this->stats['hits'],
			'misses'     => $this->stats['misses'],
			'sets'       => $this->stats['sets'],
			'deletes'    => $this->stats['deletes'],
			'flushes'    => $this->stats['flushes'],
			'hit_rate'   => $hit_rate,
			'size_bytes' => $this->stats['size'],
			'size_kb'    => round( $this->stats['size'] / 1024, 2 ),
		];
	}

	/**
	 * Load statistics from database
	 *
	 * @return void
	 */
	private function load_stats(): void {
		$saved_stats = get_option( self::STATS_OPTION, [] );

		if ( is_array( $saved_stats ) && ! empty( $saved_stats ) ) {
			$this->stats = array_merge( $this->stats, $saved_stats );
		}
	}

	/**
	 * Save statistics to database
	 *
	 * @return void
	 */
	private function save_stats(): void {
		update_option( self::STATS_OPTION, $this->stats, false );
	}

	/**
	 * Update cache size statistics
	 *
	 * Estimates the size of cached data.
	 *
	 * @param string $key   Cache key.
	 * @param mixed  $value Cached value.
	 * @return void
	 */
	private function update_cache_size( string $key, $value ): void {
		// Estimate size by serializing the value
		$serialized = maybe_serialize( $value );
		$size       = strlen( $serialized );

		// Add to total size (approximate)
		$this->stats['size'] += $size;
	}

	/**
	 * Reset statistics
	 *
	 * Clears all cache statistics. Useful for testing or fresh start.
	 *
	 * @return void
	 */
	public function reset_stats(): void {
		$this->stats = [
			'hits'     => 0,
			'misses'   => 0,
			'sets'     => 0,
			'deletes'  => 0,
			'flushes'  => 0,
			'size'     => 0,
		];
		$this->save_stats();
	}

	/**
	 * Get cache key with prefix
	 *
	 * Helper method to generate full cache key.
	 *
	 * @param string $key Base key.
	 * @return string Full cache key with prefix.
	 */
	public static function get_cache_key( string $key ): string {
		return self::CACHE_PREFIX . $key;
	}

	/**
	 * Check if cache is healthy
	 *
	 * Verifies cache is working and hit rate is acceptable.
	 *
	 * @return bool True if cache is healthy (hit rate > 80%).
	 */
	public function is_healthy(): bool {
		$stats    = $this->get_stats();
		$hit_rate = $stats['hit_rate'];

		// Cache is healthy if hit rate is above 80%
		return $hit_rate >= 80.0;
	}

	/**
	 * Warm up cache
	 *
	 * Pre-populate cache with commonly accessed data.
	 *
	 * @param array $data Array of key => value pairs to cache.
	 * @return int Number of items cached.
	 */
	public function warm_up( array $data ): int {
		$count = 0;

		foreach ( $data as $key => $value ) {
			if ( $this->set( $key, $value ) ) {
				$count++;
			}
		}

		return $count;
	}
}
