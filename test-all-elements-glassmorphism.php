<?php
/**
 * Test: Glassmorphism - All WordPress Elements
 * 
 * Quick visual test to verify glassmorphism is applied to all WordPress admin elements
 * 
 * Usage:
 * 1. Enable "Global Glassmorphism" in WOOW! Admin → Settings
 * 2. Visit different WordPress admin pages
 * 3. Check if glassmorphism is visible on all elements
 * 
 * @package WOOW_Admin
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Glassmorphism - All Elements Test</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            max-width: 1200px;
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
        
        .subtitle {
            color: #64748b;
            margin-bottom: 30px;
        }
        
        .checklist {
            list-style: none;
            padding: 0;
        }
        
        .checklist li {
            padding: 15px 20px;
            margin-bottom: 10px;
            background: rgba(99, 102, 241, 0.05);
            border-left: 4px solid #6366f1;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .checklist li:hover {
            background: rgba(99, 102, 241, 0.1);
            transform: translateX(5px);
            transition: all 0.2s;
        }
        
        .checkbox {
            width: 24px;
            height: 24px;
            border: 2px solid #6366f1;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .page-link {
            color: #6366f1;
            text-decoration: none;
            font-weight: 600;
            flex: 1;
        }
        
        .page-link:hover {
            text-decoration: underline;
        }
        
        .category {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin-top: 30px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-enabled {
            background: #10b981;
            color: white;
        }
        
        .status-disabled {
            background: #ef4444;
            color: white;
        }
        
        .instructions {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
        }
        
        .instructions h3 {
            margin-top: 0;
        }
        
        .instructions ol {
            margin: 10px 0;
            padding-left: 20px;
        }
        
        .instructions li {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="test-container">
        <h1>🎨 Glassmorphism - Test Wszystkich Elementów</h1>
        <p class="subtitle">Sprawdź czy glassmorphism jest aplikowany do wszystkich elementów WordPress admin</p>
        
        <?php
        // Check if glassmorphism is enabled
        $settings = get_option( 'woow_admin_settings', array() );
        $glassmorphism_enabled = isset( $settings['glassmorphism']['enabled'] ) && $settings['glassmorphism']['enabled'];
        ?>
        
        <div class="instructions">
            <h3>📋 Instrukcje:</h3>
            <ol>
                <li>Status glassmorphism: 
                    <?php if ( $glassmorphism_enabled ): ?>
                        <span class="status status-enabled">✓ WŁĄCZONY</span>
                    <?php else: ?>
                        <span class="status status-disabled">✗ WYŁĄCZONY</span>
                    <?php endif; ?>
                </li>
                <?php if ( ! $glassmorphism_enabled ): ?>
                <li><strong>Włącz glassmorphism:</strong> WOOW! Admin → Settings → Enable Global Glassmorphism</li>
                <?php endif; ?>
                <li>Kliknij na linki poniżej aby odwiedzić różne strony WordPress admin</li>
                <li>Sprawdź czy elementy mają efekt glassmorphism (przezroczyste tło z blur)</li>
                <li>Zaznacz checkbox obok każdej strony po sprawdzeniu</li>
            </ol>
        </div>
        
        <div class="category">🏠 Dashboard & Główne</div>
        <ul class="checklist">
            <li>
                <div class="checkbox">☐</div>
                <a href="<?php echo admin_url(); ?>" class="page-link" target="_blank">
                    Dashboard - Widgety, Welcome Panel
                </a>
            </li>
            <li>
                <div class="checkbox">☐</div>
                <a href="<?php echo admin_url( 'index.php' ); ?>" class="page-link" target="_blank">
                    Dashboard - Activity, Quick Press
                </a>
            </li>
        </ul>
        
        <div class="category">📄 Strony & Wpisy</div>
        <ul class="checklist">
            <li>
                <div class="checkbox">☐</div>
                <a href="<?php echo admin_url( 'edit.php?post_type=page' ); ?>" class="page-link" target="_blank">
                    Wszystkie Strony - Lista, Tabela
                </a>
            </li>
            <li>
                <div class="checkbox">☐</div>
                <a href="<?php echo admin_url( 'post-new.php?post_type=page' ); ?>" class="page-link" target="_blank">
                    Dodaj Nową Stronę - Meta Boxes
                </a>
            </li>
            <li>
                <div class="checkbox">☐</div>
                <a href="<?php echo admin_url( 'edit.php' ); ?>" class="page-link" target="_blank">
                    Wszystkie Wpisy - Lista, Tabela
                </a>
            </li>
            <li>
                <div class="checkbox">☐</div>
                <a href="<?php echo admin_url( 'post-new.php' ); ?>" class="page-link" target="_blank">
                    Dodaj Nowy Wpis - Meta Boxes
                </a>
            </li>
        </ul>
        
        <div class="category">🔌 Wtyczki & Motywy</div>
        <ul class="checklist">
            <li>
                <div class="checkbox">☐</div>
                <a href="<?php echo admin_url( 'plugins.php' ); ?>" class="page-link" target="_blank">
                    Wtyczki - Lista, Tabela, Cards
                </a>
            </li>
            <li>
                <div class="checkbox">☐</div>
                <a href="<?php echo admin_url( 'plugin-install.php' ); ?>" class="page-link" target="_blank">
                    Dodaj Wtyczkę - Plugin Cards
                </a>
            </li>
            <li>
                <div class="checkbox">☐</div>
                <a href="<?php echo admin_url( 'themes.php' ); ?>" class="page-link" target="_blank">
                    Motywy - Theme Browser
                </a>
            </li>
            <li>
                <div class="checkbox">☐</div>
                <a href="<?php echo admin_url( 'theme-install.php' ); ?>" class="page-link" target="_blank">
                    Dodaj Motyw - Theme Cards
                </a>
            </li>
        </ul>
        
        <div class="category">🖼️ Media</div>
        <ul class="checklist">
            <li>
                <div class="checkbox">☐</div>
                <a href="<?php echo admin_url( 'upload.php' ); ?>" class="page-link" target="_blank">
                    Biblioteka Mediów - Lista, Grid
                </a>
            </li>
            <li>
                <div class="checkbox">☐</div>
                <a href="<?php echo admin_url( 'media-new.php' ); ?>" class="page-link" target="_blank">
                    Dodaj Nowy - Upload Form
                </a>
            </li>
        </ul>
        
        <div class="category">⚙️ Ustawienia</div>
        <ul class="checklist">
            <li>
                <div class="checkbox">☐</div>
                <a href="<?php echo admin_url( 'options-general.php' ); ?>" class="page-link" target="_blank">
                    Ustawienia Ogólne - Form Tables
                </a>
            </li>
            <li>
                <div class="checkbox">☐</div>
                <a href="<?php echo admin_url( 'options-writing.php' ); ?>" class="page-link" target="_blank">
                    Ustawienia Pisania - Form Tables
                </a>
            </li>
            <li>
                <div class="checkbox">☐</div>
                <a href="<?php echo admin_url( 'options-reading.php' ); ?>" class="page-link" target="_blank">
                    Ustawienia Czytania - Form Tables
                </a>
            </li>
        </ul>
        
        <div class="category">🎨 Wygląd</div>
        <ul class="checklist">
            <li>
                <div class="checkbox">☐</div>
                <a href="<?php echo admin_url( 'widgets.php' ); ?>" class="page-link" target="_blank">
                    Widgety - Widget Containers
                </a>
            </li>
            <li>
                <div class="checkbox">☐</div>
                <a href="<?php echo admin_url( 'nav-menus.php' ); ?>" class="page-link" target="_blank">
                    Menu - Menu Editor
                </a>
            </li>
            <li>
                <div class="checkbox">☐</div>
                <a href="<?php echo admin_url( 'customize.php' ); ?>" class="page-link" target="_blank">
                    Dostosuj - Customizer
                </a>
            </li>
        </ul>
        
        <div class="category">💬 Komentarze</div>
        <ul class="checklist">
            <li>
                <div class="checkbox">☐</div>
                <a href="<?php echo admin_url( 'edit-comments.php' ); ?>" class="page-link" target="_blank">
                    Komentarze - Lista, Comment Items
                </a>
            </li>
        </ul>
        
        <div class="category">👥 Użytkownicy</div>
        <ul class="checklist">
            <li>
                <div class="checkbox">☐</div>
                <a href="<?php echo admin_url( 'users.php' ); ?>" class="page-link" target="_blank">
                    Wszyscy Użytkownicy - Lista, Tabela
                </a>
            </li>
            <li>
                <div class="checkbox">☐</div>
                <a href="<?php echo admin_url( 'user-new.php' ); ?>" class="page-link" target="_blank">
                    Dodaj Użytkownika - Form
                </a>
            </li>
            <li>
                <div class="checkbox">☐</div>
                <a href="<?php echo admin_url( 'profile.php' ); ?>" class="page-link" target="_blank">
                    Profil - Form Tables
                </a>
            </li>
        </ul>
        
        <div class="category">🔧 Narzędzia</div>
        <ul class="checklist">
            <li>
                <div class="checkbox">☐</div>
                <a href="<?php echo admin_url( 'tools.php' ); ?>" class="page-link" target="_blank">
                    Dostępne Narzędzia
                </a>
            </li>
            <li>
                <div class="checkbox">☐</div>
                <a href="<?php echo admin_url( 'import.php' ); ?>" class="page-link" target="_blank">
                    Import
                </a>
            </li>
            <li>
                <div class="checkbox">☐</div>
                <a href="<?php echo admin_url( 'export.php' ); ?>" class="page-link" target="_blank">
                    Eksport
                </a>
            </li>
        </ul>
        
        <div style="margin-top: 40px; padding: 20px; background: #f1f5f9; border-radius: 12px;">
            <h3 style="margin-top: 0;">✅ Co sprawdzać:</h3>
            <ul style="margin-bottom: 0;">
                <li>Czy elementy mają przezroczyste tło z efektem blur?</li>
                <li>Czy tło jest widoczne przez elementy?</li>
                <li>Czy hover na wierszach tabel działa poprawnie?</li>
                <li>Czy wszystkie karty (cards) mają glassmorphism?</li>
                <li>Czy meta boxes mają glassmorphism?</li>
                <li>Czy formularze mają glassmorphism?</li>
            </ul>
        </div>
    </div>
    
    <script>
        // Add click handler to checkboxes
        document.querySelectorAll('.checkbox').forEach(checkbox => {
            checkbox.addEventListener('click', function() {
                if (this.textContent === '☐') {
                    this.textContent = '✓';
                    this.style.background = '#10b981';
                    this.style.color = 'white';
                    this.style.borderColor = '#10b981';
                } else {
                    this.textContent = '☐';
                    this.style.background = '';
                    this.style.color = '';
                    this.style.borderColor = '#6366f1';
                }
            });
        });
    </script>
</body>
</html>
