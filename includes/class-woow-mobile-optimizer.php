<?php
/**
 * WOOW Mobile Optimizer
 *
 * Handles responsive design and mobile optimization.
 * Generates mobile-specific CSS and ensures touch-friendly interfaces.
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WOOW_Mobile_Optimizer
 *
 * Optimizes the admin interface for mobile devices.
 */
class WOOW_Mobile_Optimizer {
	/**
	 * Minimum touch target size (px)
	 *
	 * @var int
	 */
	private const MIN_TOUCH_TARGET = 48;

	/**
	 * Mobile breakpoint (px)
	 *
	 * @var int
	 */
	private const MOBILE_BREAKPOINT = 768;

	/**
	 * Tablet breakpoint (px)
	 *
	 * @var int
	 */
	private const TABLET_BREAKPOINT = 1024;

	/**
	 * Wide breakpoint (px)
	 *
	 * @var int
	 */
	private const WIDE_BREAKPOINT = 1600;

	/**
	 * Get responsive CSS
	 *
	 * Generates media queries for mobile, tablet, and desktop layouts.
	 *
	 * @return string Responsive CSS.
	 */
	public function get_responsive_css(): string {
		$css = '';

		// Mobile styles (< 768px)
		$css .= $this->get_mobile_css();

		// Tablet styles (768px - 1024px)
		$css .= $this->get_tablet_css();

		// Desktop styles (> 1024px)
		$css .= $this->get_desktop_css();

		// Wide screen styles (> 1600px)
		$css .= $this->get_wide_css();

		return $css;
	}

	/**
	 * Optimize touch targets
	 *
	 * Ensures all interactive elements meet minimum touch target size.
	 *
	 * @return string Touch target optimization CSS.
	 */
	public function optimize_touch_targets(): string {
		$min_size = self::MIN_TOUCH_TARGET . 'px';

		return "
		/* Touch Target Optimization */
		@media (max-width: " . self::MOBILE_BREAKPOINT . "px) {
			/* Buttons */
			.button,
			.woow-button,
			button {
				min-height: {$min_size} !important;
				min-width: {$min_size} !important;
				padding: 12px 16px !important;
			}

			/* Links */
			a {
				min-height: {$min_size};
				display: inline-flex;
				align-items: center;
			}

			/* Form controls */
			input[type='text'],
			input[type='email'],
			input[type='url'],
			input[type='password'],
			input[type='search'],
			input[type='number'],
			select,
			textarea {
				min-height: {$min_size} !important;
				font-size: 16px !important; /* Prevent zoom on iOS */
			}

			/* Checkboxes and radios */
			input[type='checkbox'],
			input[type='radio'] {
				min-width: 24px !important;
				min-height: 24px !important;
			}

			/* Admin bar items */
			#wpadminbar .ab-item {
				min-height: {$min_size} !important;
				padding: 0 16px !important;
			}

			/* Menu items */
			#adminmenu li.menu-top > a {
				min-height: {$min_size} !important;
				padding: 12px 16px !important;
			}

			/* Tab buttons */
			.woow-tabs button {
				min-height: {$min_size} !important;
				padding: 12px 16px !important;
			}

			/* Palette and template cards */
			.woow-palette-card,
			.woow-template-card {
				min-height: {$min_size} !important;
			}
		}
		";
	}

	/**
	 * Get mobile CSS (< 768px)
	 *
	 * @return string Mobile CSS.
	 */
	private function get_mobile_css(): string {
		return "
		/* Mobile Styles (< 768px) */
		@media (max-width: " . ( self::MOBILE_BREAKPOINT - 1 ) . "px) {
			/* Admin page layout */
			.woow-admin-wrap {
				padding: 8px !important;
			}

			.woow-header {
				flex-direction: column !important;
				gap: 12px !important;
				padding: 16px !important;
			}

			.woow-header-actions {
				width: 100% !important;
				justify-content: stretch !important;
			}

			.woow-header-actions .button {
				flex: 1 !important;
			}

			/* Content layout - stack vertically */
			.woow-content {
				flex-direction: column !important;
			}

			.woow-sidebar {
				width: 100% !important;
				position: relative !important;
				top: 0 !important;
				height: auto !important;
				margin-bottom: 16px !important;
			}

			.woow-tab-content {
				width: 100% !important;
			}

			/* Tabs - horizontal scroll */
			.woow-tabs {
				overflow-x: auto !important;
				-webkit-overflow-scrolling: touch !important;
				white-space: nowrap !important;
			}

			.woow-tabs button {
				font-size: 13px !important;
				padding: 12px 16px !important;
			}

			/* Cards */
			.woow-card {
				border-radius: 16px !important;
				padding: 16px !important;
				margin-bottom: 16px !important;
			}

			.woow-card-header {
				padding: 12px 16px !important;
			}

			.woow-card-body {
				padding: 16px !important;
			}

			/* Form rows - single column */
			.woow-form-row {
				grid-template-columns: 1fr !important;
			}

			/* Palette grid - single column */
			.woow-palette-grid {
				grid-template-columns: 1fr !important;
				gap: 12px !important;
			}

			/* Template grid - single column */
			.woow-template-grid {
				grid-template-columns: 1fr !important;
				gap: 12px !important;
			}

			/* Preview panel - hide on mobile */
			.woow-preview-panel {
				display: none !important;
			}

			/* WordPress admin bar */
			#wpadminbar {
				height: 56px !important;
				border-radius: 16px !important;
				margin: 8px !important;
				width: calc(100% - 16px) !important;
			}

			/* WordPress admin menu */
			#adminmenuwrap,
			#adminmenu {
				width: 100% !important;
				position: relative !important;
				margin: 8px !important;
				border-radius: 16px !important;
			}

			/* Dashboard widgets - single column */
			#dashboard-widgets {
				grid-template-columns: 1fr !important;
			}

			.postbox {
				padding: 16px !important;
				border-radius: 16px !important;
				margin-bottom: 16px !important;
			}

			/* Typography - smaller on mobile */
			h1 { font-size: 24px !important; }
			h2 { font-size: 20px !important; }
			h3 { font-size: 18px !important; }
			body { font-size: 14px !important; }

			/* Color picker group - wrap */
			.woow-color-picker-group {
				flex-wrap: wrap !important;
			}

			/* Shadow picker - 3 columns */
			.woow-shadow-picker {
				grid-template-columns: repeat(3, 1fr) !important;
			}
		}
		";
	}

	/**
	 * Get tablet CSS (768px - 1024px)
	 *
	 * @return string Tablet CSS.
	 */
	private function get_tablet_css(): string {
		return "
		/* Tablet Styles (768px - 1024px) */
		@media (min-width: " . self::MOBILE_BREAKPOINT . "px) and (max-width: " . ( self::TABLET_BREAKPOINT - 1 ) . "px) {
			/* Admin page layout */
			.woow-admin-wrap {
				padding: 16px !important;
			}

			/* Sidebar - narrower */
			.woow-sidebar {
				width: 240px !important;
			}

			/* Palette grid - 2 columns */
			.woow-palette-grid {
				grid-template-columns: repeat(2, 1fr) !important;
			}

			/* Template grid - 2 columns */
			.woow-template-grid {
				grid-template-columns: repeat(2, 1fr) !important;
			}

			/* Dashboard widgets - 2 columns */
			#dashboard-widgets {
				grid-template-columns: repeat(2, 1fr) !important;
			}

			/* WordPress admin menu - collapsed by default */
			#adminmenuwrap,
			#adminmenu {
				width: 80px !important;
			}

			#adminmenu .wp-menu-name {
				display: none !important;
			}
		}
		";
	}

	/**
	 * Get desktop CSS (> 1024px)
	 *
	 * @return string Desktop CSS.
	 */
	private function get_desktop_css(): string {
		return "
		/* Desktop Styles (> 1024px) */
		@media (min-width: " . self::TABLET_BREAKPOINT . "px) {
			/* Full layout */
			.woow-content {
				display: flex !important;
				gap: 24px !important;
			}

			.woow-sidebar {
				width: 320px !important;
				position: fixed !important;
				top: 96px !important;
				height: calc(100vh - 128px) !important;
			}

			.woow-tab-content {
				flex: 1 !important;
			}

			/* Palette grid - 2 columns */
			.woow-palette-grid {
				grid-template-columns: repeat(2, 1fr) !important;
			}

			/* Template grid - 2 columns */
			.woow-template-grid {
				grid-template-columns: repeat(2, 1fr) !important;
			}

			/* Dashboard widgets - 2 columns */
			#dashboard-widgets {
				grid-template-columns: repeat(2, 1fr) !important;
			}

			/* Preview panel - show */
			.woow-preview-panel {
				display: block !important;
			}
		}
		";
	}

	/**
	 * Get wide screen CSS (> 1600px)
	 *
	 * @return string Wide screen CSS.
	 */
	private function get_wide_css(): string {
		return "
		/* Wide Screen Styles (> 1600px) */
		@media (min-width: " . self::WIDE_BREAKPOINT . "px) {
			/* Palette grid - 3 columns */
			.woow-palette-grid {
				grid-template-columns: repeat(3, 1fr) !important;
			}

			/* Template grid - 3 columns */
			.woow-template-grid {
				grid-template-columns: repeat(3, 1fr) !important;
			}

			/* Dashboard widgets - 3 columns */
			#dashboard-widgets {
				grid-template-columns: repeat(3, 1fr) !important;
			}

			/* Wider sidebar */
			.woow-sidebar {
				width: 360px !important;
			}

			/* Larger cards */
			.woow-card {
				padding: 32px !important;
			}
		}
		";
	}

	/**
	 * Get orientation-specific CSS
	 *
	 * Handles landscape and portrait orientations.
	 *
	 * @return string Orientation CSS.
	 */
	public function get_orientation_css(): string {
		return "
		/* Landscape Orientation */
		@media (max-width: " . self::MOBILE_BREAKPOINT . "px) and (orientation: landscape) {
			/* Reduce vertical spacing in landscape */
			.woow-card {
				margin-bottom: 12px !important;
			}

			.woow-header {
				padding: 12px !important;
			}

			/* Smaller admin bar */
			#wpadminbar {
				height: 48px !important;
			}
		}

		/* Portrait Orientation */
		@media (max-width: " . self::MOBILE_BREAKPOINT . "px) and (orientation: portrait) {
			/* More vertical spacing in portrait */
			.woow-card {
				margin-bottom: 20px !important;
			}
		}
		";
	}

	/**
	 * Detect if current device is mobile
	 *
	 * @return bool True if mobile device.
	 */
	public function is_mobile(): bool {
		return wp_is_mobile();
	}

	/**
	 * Get device type
	 *
	 * @return string Device type: 'mobile', 'tablet', or 'desktop'.
	 */
	public function get_device_type(): string {
		if ( wp_is_mobile() ) {
			// Check if tablet (basic detection)
			$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

			if ( preg_match( '/(tablet|ipad|playbook|silk)|(android(?!.*mobile))/i', $user_agent ) ) {
				return 'tablet';
			}

			return 'mobile';
		}

		return 'desktop';
	}

	/**
	 * Get all mobile optimization CSS
	 *
	 * Returns complete mobile optimization CSS including responsive and touch targets.
	 *
	 * @return string Complete mobile CSS.
	 */
	public function get_all_mobile_css(): string {
		$css = '';

		$css .= $this->get_responsive_css();
		$css .= $this->optimize_touch_targets();
		$css .= $this->get_orientation_css();

		return $css;
	}
}
