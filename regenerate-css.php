<?php
/**
 * CSS Regeneration Script
 * 
 * Regenerates CSS to apply background color changes
 */

// Load WordPress
require_once dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/wp-load.php';

// Check if WOOW Admin is active
if ( ! class_exists( 'WOOW_Admin' ) ) {
	die( "WOOW! Admin plugin is not active.\n" );
}

echo "=================================================\n";
echo "WOOW! Admin - CSS Regeneration\n";
echo "=================================================\n\n";

// Get settings instance
$settings = new WOOW_Settings();

// Get CSS generator
$css_generator = new WOOW_CSS_Generator( $settings );

// Generate CSS
echo "Generating CSS...\n";
$css = $css_generator->generate();

// Get metrics
$metrics = $css_generator->get_metrics();

echo "✓ CSS generated successfully\n";
echo "  - Generation time: {$metrics['generation_time']}ms\n";
echo "  - CSS size: {$metrics['css_size_kb']} KB\n\n";

// Save CSS to file
$upload_dir = wp_upload_dir();
$css_file = $upload_dir['basedir'] . '/woow-admin-custom.css';

$result = file_put_contents( $css_file, $css );

if ( $result !== false ) {
	echo "✓ CSS saved to: {$css_file}\n";
	echo "  - File size: " . round( $result / 1024, 2 ) . " KB\n\n";
} else {
	echo "✗ Failed to save CSS file\n\n";
	exit( 1 );
}

// Show background settings
$bg = $settings->get_section( 'backgrounds' );
echo "Current Background Settings:\n";
echo "  - body_bg: " . ( $bg['body_bg'] ?? 'not set' ) . "\n";
echo "  - body_pattern: " . ( $bg['body_pattern'] ?? 'not set' ) . "\n";
echo "  - content_bg: " . ( $bg['content_bg'] ?? 'not set' ) . "\n";
echo "  - sidebar_bg: " . ( $bg['sidebar_bg'] ?? 'not set' ) . "\n";
echo "\n";

// Show relevant CSS snippet
echo "Generated CSS for body.wp-admin:\n";
echo "---\n";
$lines = explode( "\n", $css );
$in_body = false;
$body_css = '';
foreach ( $lines as $line ) {
	if ( strpos( $line, 'body.wp-admin' ) !== false ) {
		$in_body = true;
	}
	if ( $in_body ) {
		$body_css .= $line . "\n";
		if ( strpos( $line, '}' ) !== false && $in_body ) {
			break;
		}
	}
}
echo $body_css;
echo "---\n\n";

echo "✓ CSS regeneration complete!\n";
echo "  Please refresh your WordPress admin to see changes.\n\n";
