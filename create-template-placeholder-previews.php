<?php
/**
 * Create Placeholder Preview Images for Templates
 *
 * This script generates placeholder preview images for all 11 templates.
 * These are temporary placeholders until real screenshots are taken.
 *
 * Run: php create-template-placeholder-previews.php
 *
 * @package WOOW_Admin
 * @since 1.0.0
 */

// Template definitions with their visual characteristics
$templates = array(
	'modern-minimal' => array(
		'name' => 'Modern Minimal',
		'colors' => array('#ffffff', '#f3f4f6', '#3b82f6', '#1f2937'),
		'description' => 'Clean, minimalist design',
	),
	'glassmorphism-pro' => array(
		'name' => 'Glassmorphism Pro',
		'colors' => array('#667eea', '#764ba2', '#ffffff', '#f0f0f0'),
		'description' => 'Premium glass effects',
	),
	'dark-dashboard' => array(
		'name' => 'Dark Dashboard',
		'colors' => array('#0f172a', '#1e293b', '#8b5cf6', '#06b6d4'),
		'description' => 'Complete dark mode',
	),
	'colorful-creative' => array(
		'name' => 'Colorful Creative',
		'colors' => array('#ec4899', '#8b5cf6', '#f59e0b', '#10b981'),
		'description' => 'Vibrant and playful',
	),
	'corporate-blue' => array(
		'name' => 'Corporate Blue',
		'colors' => array('#3b82f6', '#1e40af', '#ffffff', '#f8fafc'),
		'description' => 'Professional corporate',
	),
	'material-design' => array(
		'name' => 'Material Design',
		'colors' => array('#2196f3', '#1976d2', '#ffffff', '#f5f5f5'),
		'description' => 'Google Material Design',
	),
	'flat-2' => array(
		'name' => 'Flat 2.0',
		'colors' => array('#e74c3c', '#3498db', '#2ecc71', '#f39c12'),
		'description' => 'Modern flat design',
	),
	'neumorphism' => array(
		'name' => 'Neumorphism',
		'colors' => array('#e0e5ec', '#ffffff', '#a3b1c6', '#d1d9e6'),
		'description' => 'Soft UI design',
	),
	'retro-wave' => array(
		'name' => 'Retro Wave',
		'colors' => array('#ff006e', '#8338ec', '#06ffa5', '#1a1a2e'),
		'description' => '80s synthwave',
	),
	'nature-inspired' => array(
		'name' => 'Nature Inspired',
		'colors' => array('#10b981', '#059669', '#84cc16', '#f0fdf4'),
		'description' => 'Organic natural design',
	),
	'high-contrast' => array(
		'name' => 'High Contrast',
		'colors' => array('#000000', '#ffffff', '#ffff00', '#0000ff'),
		'description' => 'Accessibility focused',
	),
);

// Output directory
$output_dir = __DIR__ . '/assets/images/previews/templates/';

// Ensure directory exists
if ( ! is_dir( $output_dir ) ) {
	mkdir( $output_dir, 0755, true );
}

// Image dimensions
$width = 1200;
$height = 800;

echo "Creating placeholder preview images for templates...\n\n";

foreach ( $templates as $template_id => $template ) {
	$filename = $output_dir . $template_id . '.png';
	
	// Skip if file already exists
	if ( file_exists( $filename ) ) {
		echo "⏭️  Skipping {$template_id}.png (already exists)\n";
		continue;
	}
	
	// Create image
	$image = imagecreatetruecolor( $width, $height );
	
	// Parse colors
	$colors_rgb = array();
	foreach ( $template['colors'] as $hex ) {
		$hex = ltrim( $hex, '#' );
		$colors_rgb[] = array(
			'r' => hexdec( substr( $hex, 0, 2 ) ),
			'g' => hexdec( substr( $hex, 2, 2 ) ),
			'b' => hexdec( substr( $hex, 4, 2 ) ),
		);
	}
	
	// Create gradient background
	for ( $y = 0; $y < $height; $y++ ) {
		$ratio = $y / $height;
		
		// Interpolate between first and second color
		$r = (int) ( $colors_rgb[0]['r'] * ( 1 - $ratio ) + $colors_rgb[1]['r'] * $ratio );
		$g = (int) ( $colors_rgb[0]['g'] * ( 1 - $ratio ) + $colors_rgb[1]['g'] * $ratio );
		$b = (int) ( $colors_rgb[0]['b'] * ( 1 - $ratio ) + $colors_rgb[1]['b'] * $ratio );
		
		$color = imagecolorallocate( $image, $r, $g, $b );
		imagefilledrectangle( $image, 0, $y, $width, $y + 1, $color );
	}
	
	// Add color swatches
	$swatch_size = 80;
	$swatch_spacing = 20;
	$swatch_y = $height - $swatch_size - 40;
	$swatch_x = 40;
	
	foreach ( $colors_rgb as $color_rgb ) {
		$color = imagecolorallocate( $image, $color_rgb['r'], $color_rgb['g'], $color_rgb['b'] );
		imagefilledrectangle( $image, $swatch_x, $swatch_y, $swatch_x + $swatch_size, $swatch_y + $swatch_size, $color );
		
		// Add border
		$border_color = imagecolorallocate( $image, 255, 255, 255 );
		imagerectangle( $image, $swatch_x, $swatch_y, $swatch_x + $swatch_size, $swatch_y + $swatch_size, $border_color );
		
		$swatch_x += $swatch_size + $swatch_spacing;
	}
	
	// Add text
	$text_color = imagecolorallocate( $image, 255, 255, 255 );
	$shadow_color = imagecolorallocate( $image, 0, 0, 0 );
	
	// Template name (large)
	$font_size = 48;
	$text_x = 40;
	$text_y = 100;
	
	// Shadow
	imagestring( $image, 5, $text_x + 2, $text_y + 2, $template['name'], $shadow_color );
	// Text
	imagestring( $image, 5, $text_x, $text_y, $template['name'], $text_color );
	
	// Description (smaller)
	$desc_y = $text_y + 40;
	imagestring( $image, 3, $text_x + 2, $desc_y + 2, $template['description'], $shadow_color );
	imagestring( $image, 3, $text_x, $desc_y, $template['description'], $text_color );
	
	// Placeholder notice
	$notice = 'PLACEHOLDER - Replace with actual screenshot';
	$notice_y = $height / 2;
	$notice_x = ( $width - ( strlen( $notice ) * 8 ) ) / 2;
	
	$notice_bg = imagecolorallocate( $image, 0, 0, 0 );
	imagefilledrectangle( $image, $notice_x - 20, $notice_y - 10, $notice_x + ( strlen( $notice ) * 8 ) + 20, $notice_y + 30, $notice_bg );
	
	imagestring( $image, 4, $notice_x + 2, $notice_y + 2, $notice, $shadow_color );
	imagestring( $image, 4, $notice_x, $notice_y, $notice, $text_color );
	
	// Save image
	imagepng( $image, $filename );
	imagedestroy( $image );
	
	echo "✅ Created {$template_id}.png\n";
}

echo "\n✨ Done! Created placeholder preview images for all templates.\n";
echo "📁 Location: {$output_dir}\n";
echo "\n⚠️  Remember: These are PLACEHOLDERS. Replace with actual screenshots!\n";
echo "   Use generate-template-previews.php to apply templates and take screenshots.\n";
