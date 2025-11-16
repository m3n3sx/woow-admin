<?php
/**
 * Palette Preview Generator Helper
 * 
 * This script helps generate preview images for all palettes by:
 * 1. Applying each palette sequentially
 * 2. Providing instructions for screenshot capture
 * 3. Tracking which previews have been generated
 * 
 * Usage:
 * 1. Access this file via browser: /wp-content/plugins/woow-admin/generate-palette-previews.php
 * 2. Follow the on-screen instructions
 * 3. Take screenshots at 1200x800px resolution
 * 4. Save to assets/images/previews/palettes/
 */

// Load WordPress
require_once('../../../wp-load.php');

// Security check
if (!current_user_can('manage_options')) {
    wp_die('Unauthorized access');
}

// Load WOOW! Admin classes
require_once(__DIR__ . '/includes/class-woow-settings.php');
require_once(__DIR__ . '/includes/class-woow-palette-manager.php');

// Initialize
$settings = new WOOW_Settings();
$palette_manager = new WOOW_Palette_Manager($settings);

// Get all palettes
$palettes = $palette_manager->get_all_palettes();

// Handle palette application
$current_palette = isset($_GET['palette']) ? sanitize_key($_GET['palette']) : null;
$action = isset($_GET['action']) ? sanitize_key($_GET['action']) : 'list';

if ($action === 'apply' && $current_palette) {
    $result = $palette_manager->apply_palette($current_palette);
    if ($result) {
        // Redirect to admin dashboard for screenshot
        wp_redirect(admin_url('index.php?woow_preview=1&palette=' . $current_palette));
        exit;
    }
}

// Check which previews already exist
$preview_dir = __DIR__ . '/assets/images/previews/palettes/';
$existing_previews = [];
if (is_dir($preview_dir)) {
    $files = scandir($preview_dir);
    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'png') {
            $existing_previews[] = pathinfo($file, PATHINFO_FILENAME);
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1200, initial-scale=1.0">
    <title>WOOW! Admin - Palette Preview Generator</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        h1 {
            font-size: 36px;
            color: #1a202c;
            margin-bottom: 10px;
        }
        
        .subtitle {
            color: #718096;
            font-size: 18px;
            margin-bottom: 40px;
        }
        
        .instructions {
            background: #f7fafc;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin-bottom: 40px;
            border-radius: 8px;
        }
        
        .instructions h2 {
            color: #2d3748;
            font-size: 20px;
            margin-bottom: 15px;
        }
        
        .instructions ol {
            margin-left: 20px;
            color: #4a5568;
            line-height: 1.8;
        }
        
        .instructions code {
            background: #edf2f7;
            padding: 2px 8px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            color: #667eea;
        }
        
        .palette-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .palette-card {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            transition: all 0.3s ease;
        }
        
        .palette-card:hover {
            border-color: #667eea;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
        }
        
        .palette-card.completed {
            background: #f0fff4;
            border-color: #48bb78;
        }
        
        .palette-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 15px;
        }
        
        .palette-name {
            font-size: 18px;
            font-weight: 600;
            color: #2d3748;
        }
        
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-badge.pending {
            background: #fed7d7;
            color: #c53030;
        }
        
        .status-badge.completed {
            background: #c6f6d5;
            color: #22543d;
        }
        
        .palette-description {
            color: #718096;
            font-size: 14px;
            margin-bottom: 15px;
            line-height: 1.6;
        }
        
        .color-swatches {
            display: flex;
            gap: 8px;
            margin-bottom: 15px;
        }
        
        .color-swatch {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .apply-button {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .apply-button:hover {
            background: #5a67d8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        .apply-button:disabled {
            background: #cbd5e0;
            cursor: not-allowed;
            transform: none;
        }
        
        .progress-bar {
            background: #edf2f7;
            height: 8px;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        .progress-fill {
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            height: 100%;
            transition: width 0.3s ease;
        }
        
        .progress-text {
            text-align: center;
            color: #4a5568;
            font-size: 14px;
            margin-bottom: 30px;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-info {
            background: #bee3f8;
            color: #2c5282;
            border-left: 4px solid #3182ce;
        }
        
        .alert-success {
            background: #c6f6d5;
            color: #22543d;
            border-left: 4px solid #38a169;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎨 Palette Preview Generator</h1>
        <p class="subtitle">Generate preview images for all WOOW! Admin color palettes</p>
        
        <div class="instructions">
            <h2>📋 Instructions</h2>
            <ol>
                <li>Click <strong>"Apply & Screenshot"</strong> on a palette below</li>
                <li>You'll be redirected to the WordPress admin dashboard with the palette applied</li>
                <li>Take a screenshot at <code>1200x800px</code> resolution (use browser dev tools to set viewport)</li>
                <li>Save the screenshot as <code>{palette-id}.png</code> in <code>assets/images/previews/palettes/</code></li>
                <li>Return to this page and proceed to the next palette</li>
                <li>Repeat until all palettes have preview images</li>
            </ol>
        </div>
        
        <?php
        $total = count($palettes);
        $completed = count($existing_previews);
        $progress = $total > 0 ? ($completed / $total) * 100 : 0;
        ?>
        
        <div class="progress-bar">
            <div class="progress-fill" style="width: <?php echo $progress; ?>%"></div>
        </div>
        <p class="progress-text">
            <strong><?php echo $completed; ?> of <?php echo $total; ?></strong> preview images generated (<?php echo round($progress); ?>%)
        </p>
        
        <?php if ($completed === $total): ?>
            <div class="alert alert-success">
                ✅ <strong>All preview images generated!</strong> You can now proceed to the next task.
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                ℹ️ <strong>Tip:</strong> Use browser dev tools (F12) to set viewport to 1200x800px before taking screenshots.
            </div>
        <?php endif; ?>
        
        <div class="palette-grid">
            <?php foreach ($palettes as $palette_id => $palette): ?>
                <?php
                $is_completed = in_array($palette_id, $existing_previews);
                $colors = $palette['colors'] ?? [];
                ?>
                <div class="palette-card <?php echo $is_completed ? 'completed' : ''; ?>">
                    <div class="palette-header">
                        <div class="palette-name"><?php echo esc_html($palette['name']); ?></div>
                        <span class="status-badge <?php echo $is_completed ? 'completed' : 'pending'; ?>">
                            <?php echo $is_completed ? '✓ Done' : 'Pending'; ?>
                        </span>
                    </div>
                    
                    <p class="palette-description">
                        <?php echo esc_html($palette['description']); ?>
                    </p>
                    
                    <div class="color-swatches">
                        <?php foreach ($colors as $color): ?>
                            <div class="color-swatch" style="background-color: <?php echo esc_attr($color); ?>"></div>
                        <?php endforeach; ?>
                    </div>
                    
                    <form method="get" action="">
                        <input type="hidden" name="action" value="apply">
                        <input type="hidden" name="palette" value="<?php echo esc_attr($palette_id); ?>">
                        <button type="submit" class="apply-button" <?php echo $is_completed ? 'disabled' : ''; ?>>
                            <?php echo $is_completed ? '✓ Preview Generated' : '📸 Apply & Screenshot'; ?>
                        </button>
                    </form>
                    
                    <?php if ($is_completed): ?>
                        <p style="margin-top: 10px; text-align: center; color: #48bb78; font-size: 12px;">
                            Saved as: <code><?php echo esc_html($palette_id); ?>.png</code>
                        </p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div style="text-align: center; padding-top: 20px; border-top: 2px solid #e2e8f0;">
            <p style="color: #718096; font-size: 14px;">
                Preview images should be saved to:<br>
                <code style="background: #edf2f7; padding: 4px 12px; border-radius: 4px; color: #667eea;">
                    <?php echo esc_html($preview_dir); ?>
                </code>
            </p>
        </div>
    </div>
</body>
</html>
