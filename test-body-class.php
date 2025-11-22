<?php
/**
 * Test: Body Class - Glassmorphism
 * 
 * Quick test to verify that woow-glass-enabled class is added to body
 * 
 * Usage:
 * 1. Enable "Global Glassmorphism" in WOOW! Admin → Settings
 * 2. Open this file in browser: /wp-content/plugins/woow-admin/test-body-class.php
 * 3. Check if body has woow-glass-enabled class
 * 
 * @package WOOW_Admin
 */

// Load WordPress
require_once '../../../wp-load.php';

// Check if user is admin
if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( 'You must be an administrator to view this page.' );
}

// Get settings
$settings = get_option( 'woow_admin_settings', array() );
$glassmorphism_enabled = isset( $settings['effects']['glassmorphism_enabled'] ) && $settings['effects']['glassmorphism_enabled'];

// Simulate admin_body_class filter
$body_classes = apply_filters( 'admin_body_class', '' );

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Body Class Test - Glassmorphism</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .test-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        h1 {
            color: #1e293b;
            margin-bottom: 10px;
        }
        
        .status {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            margin: 10px 0;
        }
        
        .status-success {
            background: #10b981;
            color: white;
        }
        
        .status-error {
            background: #ef4444;
            color: white;
        }
        
        .status-warning {
            background: #f59e0b;
            color: white;
        }
        
        .code-block {
            background: #1e293b;
            color: #e2e8f0;
            padding: 20px;
            border-radius: 12px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            margin: 20px 0;
            overflow-x: auto;
        }
        
        .info-box {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 15px 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .success-box {
            background: #f0fdf4;
            border-left: 4px solid #10b981;
            padding: 15px 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .error-box {
            background: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 15px 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        
        th {
            background: #f8fafc;
            font-weight: 600;
            color: #1e293b;
        }
        
        .check {
            color: #10b981;
            font-weight: bold;
        }
        
        .cross {
            color: #ef4444;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="test-container">
        <h1>🧪 Body Class Test - Glassmorphism</h1>
        
        <h2>Status Glassmorphism:</h2>
        <?php if ( $glassmorphism_enabled ): ?>
            <span class="status status-success">✓ WŁĄCZONY</span>
        <?php else: ?>
            <span class="status status-error">✗ WYŁĄCZONY</span>
        <?php endif; ?>
        
        <h2>Wynik Testu:</h2>
        
        <?php
        $has_class = strpos( $body_classes, 'woow-glass-enabled' ) !== false;
        
        if ( $glassmorphism_enabled && $has_class ) {
            echo '<div class="success-box">';
            echo '<strong>✓ TEST PASSED!</strong><br>';
            echo 'Klasa <code>woow-glass-enabled</code> jest poprawnie dodawana do body.';
            echo '</div>';
        } elseif ( $glassmorphism_enabled && ! $has_class ) {
            echo '<div class="error-box">';
            echo '<strong>✗ TEST FAILED!</strong><br>';
            echo 'Glassmorphism jest włączony, ale klasa <code>woow-glass-enabled</code> NIE jest dodawana do body.';
            echo '</div>';
        } elseif ( ! $glassmorphism_enabled && ! $has_class ) {
            echo '<div class="info-box">';
            echo '<strong>ℹ INFO:</strong><br>';
            echo 'Glassmorphism jest wyłączony. Klasa nie powinna być dodawana.';
            echo '</div>';
        } else {
            echo '<div class="error-box">';
            echo '<strong>⚠ WARNING:</strong><br>';
            echo 'Glassmorphism jest wyłączony, ale klasa jest dodawana do body!';
            echo '</div>';
        }
        ?>
        
        <h2>Szczegóły:</h2>
        <table>
            <tr>
                <th>Test</th>
                <th>Wynik</th>
            </tr>
            <tr>
                <td>Glassmorphism włączony w ustawieniach</td>
                <td><?php echo $glassmorphism_enabled ? '<span class="check">✓ TAK</span>' : '<span class="cross">✗ NIE</span>'; ?></td>
            </tr>
            <tr>
                <td>Klasa <code>woow-glass-enabled</code> w body</td>
                <td><?php echo $has_class ? '<span class="check">✓ TAK</span>' : '<span class="cross">✗ NIE</span>'; ?></td>
            </tr>
            <tr>
                <td>Hook <code>admin_body_class</code> działa</td>
                <td><?php echo has_filter( 'admin_body_class' ) ? '<span class="check">✓ TAK</span>' : '<span class="cross">✗ NIE</span>'; ?></td>
            </tr>
        </table>
        
        <h2>Body Classes:</h2>
        <div class="code-block">
            <?php 
            if ( empty( trim( $body_classes ) ) ) {
                echo '(brak klas)';
            } else {
                echo esc_html( $body_classes );
            }
            ?>
        </div>
        
        <h2>Raw Settings:</h2>
        <div class="code-block">
            <?php 
            echo 'effects.glassmorphism_enabled = ';
            var_dump( $settings['effects']['glassmorphism_enabled'] ?? 'NOT SET' );
            ?>
        </div>
        
        <?php if ( ! $glassmorphism_enabled ): ?>
        <div class="info-box">
            <strong>💡 Jak włączyć glassmorphism:</strong><br>
            1. Przejdź do <a href="<?php echo admin_url( 'admin.php?page=woow-admin' ); ?>">WOOW! Admin → Effects</a><br>
            2. Włącz "Enable Global Glassmorphism"<br>
            3. Kliknij "Apply Changes"<br>
            4. Odśwież tę stronę
        </div>
        <?php endif; ?>
        
        <?php if ( $glassmorphism_enabled && $has_class ): ?>
        <div class="success-box">
            <strong>🎉 Wszystko działa poprawnie!</strong><br>
            Teraz możesz sprawdzić czy glassmorphism jest widoczny na różnych stronach WordPress admin:
            <ul style="margin: 10px 0 0 20px;">
                <li><a href="<?php echo admin_url(); ?>">Dashboard</a></li>
                <li><a href="<?php echo admin_url( 'edit.php?post_type=page' ); ?>">Strony</a></li>
                <li><a href="<?php echo admin_url( 'plugins.php' ); ?>">Wtyczki</a></li>
                <li><a href="<?php echo admin_url( 'options-general.php' ); ?>">Ustawienia</a></li>
            </ul>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
