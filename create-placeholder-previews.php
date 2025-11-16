<?php
/**
 * Create Placeholder Preview Images
 * 
 * This script creates placeholder preview images for all palettes
 * until actual screenshots can be generated.
 * 
 * Usage: php create-placeholder-previews.php
 */

// Palette definitions with their color schemes
$palettes = [
    'professional-blue' => [
        'name' => 'Professional Blue',
        'colors' => ['#3b82f6', '#1e40af', '#06b6d4', '#f8fafc']
    ],
    'warm-sunset' => [
        'name' => 'Warm Sunset',
        'colors' => ['#f59e0b', '#ea580c', '#ec4899', '#fff7ed']
    ],
    'dark-mode-pro' => [
        'name' => 'Dark Mode Pro',
        'colors' => ['#8b5cf6', '#6366f1', '#06b6d4', '#0f172a']
    ],
    'nature-green' => [
        'name' => 'Nature Green',
        'colors' => ['#10b981', '#059669', '#84cc16', '#f0fdf4']
    ],
    'minimalist-gray' => [
        'name' => 'Minimalist Gray',
        'colors' => ['#6b7280', '#374151', '#3b82f6', '#ffffff']
    ],
    'vibrant-purple' => [
        'name' => 'Vibrant Purple',
        'colors' => ['#a855f7', '#ec4899', '#f59e0b', '#faf5ff']
    ],
    'ocean-blue' => [
        'name' => 'Ocean Blue',
        'colors' => ['#0ea5e9', '#0284c7', '#06b6d4', '#f0f9ff']
    ],
    'cherry-red' => [
        'name' => 'Cherry Red',
        'colors' => ['#ef4444', '#dc2626', '#f97316', '#fef2f2']
    ],
    'monochrome-elite' => [
        'name' => 'Monochrome Elite',
        'colors' => ['#000000', '#1f2937', '#fbbf24', '#ffffff']
    ],
    'cyberpunk-neon' => [
        'name' => 'Cyberpunk Neon',
        'colors' => ['#06b6d4', '#8b5cf6', '#ec4899', '#0a0e27']
    ],
];

$output_dir = __DIR__ . '/assets/images/previews/palettes/';

// Ensure directory exists
if (!is_dir($output_dir)) {
    mkdir($output_dir, 0755, true);
}

// Function to convert hex to RGB
function hexToRgb($hex) {
    $hex = ltrim($hex, '#');
    return [
        'r' => hexdec(substr($hex, 0, 2)),
        'g' => hexdec(substr($hex, 2, 2)),
        'b' => hexdec(substr($hex, 4, 2))
    ];
}

// Create placeholder for each palette
foreach ($palettes as $id => $palette) {
    $filename = $output_dir . $id . '.png';
    
    // Skip if file already exists
    if (file_exists($filename)) {
        echo "⏭️  Skipping {$id}.png (already exists)\n";
        continue;
    }
    
    // Create image
    $width = 1200;
    $height = 800;
    $image = imagecreatetruecolor($width, $height);
    
    // Get colors
    $colors = $palette['colors'];
    $rgb1 = hexToRgb($colors[0]);
    $rgb2 = hexToRgb($colors[1]);
    $rgb3 = hexToRgb($colors[2]);
    $rgb4 = hexToRgb($colors[3]);
    
    // Create gradient background
    for ($y = 0; $y < $height; $y++) {
        $ratio = $y / $height;
        
        // Interpolate between first and last color
        $r = (int)($rgb1['r'] * (1 - $ratio) + $rgb4['r'] * $ratio);
        $g = (int)($rgb1['g'] * (1 - $ratio) + $rgb4['g'] * $ratio);
        $b = (int)($rgb1['b'] * (1 - $ratio) + $rgb4['b'] * $ratio);
        
        $color = imagecolorallocate($image, $r, $g, $b);
        imagefilledrectangle($image, 0, $y, $width, $y + 1, $color);
    }
    
    // Add color swatches at top
    $swatchWidth = 200;
    $swatchHeight = 100;
    $swatchY = 50;
    $swatchX = 100;
    
    foreach ($colors as $index => $hexColor) {
        $rgb = hexToRgb($hexColor);
        $color = imagecolorallocate($image, $rgb['r'], $rgb['g'], $rgb['b']);
        
        $x = $swatchX + ($index * ($swatchWidth + 20));
        imagefilledrectangle($image, $x, $swatchY, $x + $swatchWidth, $swatchY + $swatchHeight, $color);
        
        // Add white border
        $white = imagecolorallocate($image, 255, 255, 255);
        imagerectangle($image, $x, $swatchY, $x + $swatchWidth, $swatchY + $swatchHeight, $white);
    }
    
    // Add text
    $white = imagecolorallocate($image, 255, 255, 255);
    $black = imagecolorallocate($image, 0, 0, 0);
    
    // Title
    $fontSize = 5;
    $text = strtoupper($palette['name']);
    $textWidth = imagefontwidth($fontSize) * strlen($text);
    $textX = ($width - $textWidth) / 2;
    $textY = $height / 2 - 50;
    
    // Add shadow
    imagestring($image, $fontSize, $textX + 2, $textY + 2, $text, $black);
    imagestring($image, $fontSize, $textX, $textY, $text, $white);
    
    // Subtitle
    $subtitle = "PLACEHOLDER - Replace with actual screenshot";
    $subtitleWidth = imagefontwidth(3) * strlen($subtitle);
    $subtitleX = ($width - $subtitleWidth) / 2;
    $subtitleY = $textY + 40;
    
    imagestring($image, 3, $subtitleX + 1, $subtitleY + 1, $subtitle, $black);
    imagestring($image, 3, $subtitleX, $subtitleY, $subtitle, $white);
    
    // Instructions
    $instructions = "1200x800px | " . $id . ".png";
    $instrWidth = imagefontwidth(2) * strlen($instructions);
    $instrX = ($width - $instrWidth) / 2;
    $instrY = $height - 100;
    
    imagestring($image, 2, $instrX + 1, $instrY + 1, $instructions, $black);
    imagestring($image, 2, $instrX, $instrY, $instructions, $white);
    
    // Save image
    imagepng($image, $filename);
    imagedestroy($image);
    
    echo "✅ Created placeholder: {$id}.png\n";
}

echo "\n✨ Done! Created placeholder preview images.\n";
echo "📝 Replace these with actual screenshots using the guide in PALETTE-PREVIEW-GENERATION-GUIDE.md\n";
