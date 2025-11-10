<?php
/**
 * WOOW! Admin Main Page Template
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$settings = $this->settings->get_all();
$current_palette = $settings['general']['current_palette'] ?? 'professional_blue';
$current_template = $settings['general']['current_template'] ?? 'default';
?>

<div class="woow-admin-wrap">
    <!-- Header (Figma-compliant: 2 rows) -->
    <header class="woow-header">
        <!-- Row 1: Title Bar (h-16 = 64px) -->
        <div class="woow-header-row-1">
            <!-- Left: Page Title -->
            <h1>
                <?php esc_html_e( 'WOOW! Admin', 'woow-admin' ); ?>
            </h1>
            
            <!-- Right: Theme Toggle + User Info -->
            <div class="woow-header-user-section">
                <!-- Theme Toggle Button -->
                <button type="button" id="woow-theme-toggle" aria-label="<?php esc_attr_e( 'Toggle theme', 'woow-admin' ); ?>">
                    <span class="dashicons dashicons-admin-appearance"></span>
                </button>
                
                <!-- User Info -->
                <div class="woow-user-info">
                    <div class="woow-user-details">
                        <p class="woow-user-name">
                            <?php echo esc_html( wp_get_current_user()->display_name ); ?>
                        </p>
                        <p class="woow-user-role">
                            <?php esc_html_e( 'WordPress Admin', 'woow-admin' ); ?>
                        </p>
                    </div>
                    <!-- User Avatar -->
                    <div class="woow-user-avatar">
                        <?php echo esc_html( strtoupper( substr( wp_get_current_user()->display_name, 0, 1 ) ) ); ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Row 2: Control Bar (h-14 = 56px) -->
        <div class="woow-header-row-2">
            <!-- Left: Status Indicators -->
            <div class="woow-status-indicators">
                <!-- Connection Status -->
                <div class="woow-connection-status">
                    <div class="woow-status-dot"></div>
                    <span><?php esc_html_e( 'Connected', 'woow-admin' ); ?></span>
                </div>
                
                <!-- Save Status Badge -->
                <div id="woow-save-status" class="woow-badge woow-badge-success">
                    <span class="dashicons dashicons-yes-alt"></span>
                    <?php esc_html_e( 'Saved', 'woow-admin' ); ?>
                </div>
                
                <!-- Pending Changes -->
                <div id="woow-pending-changes" class="woow-badge woow-badge-outline">
                    <span id="woow-changes-count">0</span> <?php esc_html_e( 'changes pending', 'woow-admin' ); ?>
                </div>
                
                <!-- Live Preview Status -->
                <div class="woow-preview-status">
                    <span><?php esc_html_e( 'Live Preview:', 'woow-admin' ); ?></span>
                    <span id="woow-preview-status"><?php esc_html_e( 'Active', 'woow-admin' ); ?></span>
                </div>
            </div>
            
            <!-- Right: Action Buttons -->
            <div class="woow-action-buttons">
                <!-- Undo/Redo -->
                <div class="woow-undo-redo-group">
                    <button type="button" id="woow-undo-btn" aria-label="<?php esc_attr_e( 'Undo', 'woow-admin' ); ?>">
                        <span class="dashicons dashicons-undo"></span>
                    </button>
                    <button type="button" id="woow-redo-btn" aria-label="<?php esc_attr_e( 'Redo', 'woow-admin' ); ?>">
                        <span class="dashicons dashicons-redo"></span>
                    </button>
                </div>
                
                <!-- Real-time Toggle -->
                <div class="woow-realtime-toggle-group">
                    <label for="woow-realtime-toggle">
                        <input type="checkbox" id="woow-realtime-toggle" checked>
                        <span><?php esc_html_e( 'Real-time', 'woow-admin' ); ?></span>
                    </label>
                </div>
                
                <!-- Reset Button -->
                <button type="button" id="woow-reset-btn" class="woow-btn woow-btn-secondary">
                    <span class="dashicons dashicons-image-rotate"></span>
                    <?php esc_html_e( 'Reset', 'woow-admin' ); ?>
                </button>
                
                <!-- Apply Changes Button -->
                <button type="button" id="woow-save-btn" class="woow-btn woow-btn-primary">
                    <span class="dashicons dashicons-yes-alt"></span>
                    <?php esc_html_e( 'Apply Changes', 'woow-admin' ); ?>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Layout Container (KLUCZOWE!) -->
    <div class="woow-layout-container">
        <!-- Sidebar Navigation (Figma-compliant: w-64 = 256px) -->
        <aside class="woow-sidebar">
            <!-- Logo Section (h-16 = 64px) -->
            <div class="woow-sidebar-logo">
                <div class="woow-logo-icon">
                    <span class="dashicons dashicons-star-filled"></span>
                </div>
                <div class="woow-logo-text">
                    <h2><?php esc_html_e( 'WOOW!', 'woow-admin' ); ?></h2>
                    <p><?php esc_html_e( 'Admin Styler', 'woow-admin' ); ?></p>
                </div>
            </div>
            
            <!-- Navigation Section -->
            <nav class="woow-sidebar-nav">
                <?php
                // Define navigation items with icons and badges
                $nav_items = [
                    [
                        'id' => 'general',
                        'label' => __( 'Dashboard', 'woow-admin' ),
                        'icon' => 'dashicons-admin-home',
                        'badge' => null
                    ],
                    [
                        'id' => 'palettes',
                        'label' => __( 'Color Palettes', 'woow-admin' ),
                        'icon' => 'dashicons-art',
                        'badge' => '10'
                    ],
                    [
                        'id' => 'templates',
                        'label' => __( 'Templates', 'woow-admin' ),
                        'icon' => 'dashicons-layout',
                        'badge' => '11'
                    ],
                    [
                        'id' => 'admin-bar',
                        'label' => __( 'Admin Bar', 'woow-admin' ),
                        'icon' => 'dashicons-menu-alt',
                        'badge' => null
                    ],
                    [
                        'id' => 'menu',
                        'label' => __( 'Menu Styling', 'woow-admin' ),
                        'icon' => 'dashicons-menu',
                        'badge' => null
                    ],
                    [
                        'id' => 'widgets',
                        'label' => __( 'Dashboard Widgets', 'woow-admin' ),
                        'icon' => 'dashicons-dashboard',
                        'badge' => null
                    ],
                    [
                        'id' => 'forms',
                        'label' => __( 'Form Controls', 'woow-admin' ),
                        'icon' => 'dashicons-edit',
                        'badge' => null
                    ],
                    [
                        'id' => 'buttons',
                        'label' => __( 'Buttons', 'woow-admin' ),
                        'icon' => 'dashicons-button',
                        'badge' => null
                    ],
                    [
                        'id' => 'backgrounds',
                        'label' => __( 'Backgrounds', 'woow-admin' ),
                        'icon' => 'dashicons-format-image',
                        'badge' => null
                    ],
                    [
                        'id' => 'typography',
                        'label' => __( 'Typography', 'woow-admin' ),
                        'icon' => 'dashicons-editor-textcolor',
                        'badge' => null
                    ],
                    [
                        'id' => 'effects',
                        'label' => __( 'Effects', 'woow-admin' ),
                        'icon' => 'dashicons-admin-appearance',
                        'badge' => null
                    ],
                    [
                        'id' => 'login',
                        'label' => __( 'Login Page', 'woow-admin' ),
                        'icon' => 'dashicons-lock',
                        'badge' => null
                    ],
                    [
                        'id' => 'settings',
                        'label' => __( 'Settings', 'woow-admin' ),
                        'icon' => 'dashicons-admin-settings',
                        'badge' => null
                    ]
                ];
                
                foreach ( $nav_items as $index => $item ) :
                    $is_active = $index === 0; // First item active by default
                    $active_class = $is_active ? ' active' : '';
                ?>
                    <button 
                        type="button" 
                        class="woow-nav-item<?php echo esc_attr( $active_class ); ?>" 
                        data-tab="<?php echo esc_attr( $item['id'] ); ?>"
                    >
                        <span class="dashicons <?php echo esc_attr( $item['icon'] ); ?>"></span>
                        <span class="woow-nav-label"><?php echo esc_html( $item['label'] ); ?></span>
                        <?php if ( $item['badge'] ) : ?>
                            <span class="woow-nav-badge"><?php echo esc_html( $item['badge'] ); ?></span>
                        <?php endif; ?>
                    </button>
                <?php endforeach; ?>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="woow-main-content">
            <div class="woow-content-inner">
                <form id="woow-settings-form" method="post">
                    <?php wp_nonce_field( 'woow_save_settings', 'woow_nonce' ); ?>
                    
                    <!-- Tab Content Containers -->
                    <?php
                    // Include all tab templates
                    $tabs = [
                        'general',
                        'palettes',
                        'templates',
                        'admin-bar',
                        'menu',
                        'widgets',
                        'forms',
                        'buttons',
                        'backgrounds',
                        'typography',
                        'effects',
                        'login',
                        'settings'
                    ];
                    
                    foreach ( $tabs as $tab ) {
                        $tab_file = WOOW_PLUGIN_DIR . "includes/templates/tabs/{$tab}-tab.php";
                        if ( file_exists( $tab_file ) ) {
                            include $tab_file;
                        }
                    }
                    ?>
                </form>
            </div>
        </main>

        <!-- Live Preview Container -->
        <div class="woow-preview-container">
            <!-- Preview Header -->
            <div class="woow-preview-header">
                <h3><?php esc_html_e( 'Live Preview', 'woow-admin' ); ?></h3>
                <button type="button" class="woow-preview-refresh" title="<?php esc_attr_e( 'Refresh Preview', 'woow-admin' ); ?>">
                    <span class="dashicons dashicons-update"></span>
                </button>
            </div>
            
            <!-- Preview Body -->
            <div class="woow-preview-body">
                <!-- Mock Admin Bar -->
                <div id="woow-preview-adminbar" class="woow-preview-element woow-preview-adminbar">
                    <div class="woow-preview-logo">
                        <span class="dashicons dashicons-wordpress"></span>
                    </div>
                    <span class="woow-preview-text"><?php esc_html_e( 'WordPress', 'woow-admin' ); ?></span>
                    <div class="woow-preview-menu">
                        <span class="dashicons dashicons-admin-generic"></span>
                    </div>
                </div>
                
                <!-- Mock Admin Menu -->
                <div id="woow-preview-menu" class="woow-preview-element woow-preview-menu">
                    <div class="woow-preview-menu-item active">
                        <span class="dashicons dashicons-dashboard"></span>
                        <span><?php esc_html_e( 'Dashboard', 'woow-admin' ); ?></span>
                    </div>
                    <div class="woow-preview-menu-item">
                        <span class="dashicons dashicons-admin-post"></span>
                        <span><?php esc_html_e( 'Posts', 'woow-admin' ); ?></span>
                    </div>
                    <div class="woow-preview-menu-item">
                        <span class="dashicons dashicons-admin-page"></span>
                        <span><?php esc_html_e( 'Pages', 'woow-admin' ); ?></span>
                    </div>
                </div>
                
                <!-- Mock Dashboard Widget -->
                <div id="woow-preview-widget" class="woow-preview-element woow-preview-widget">
                    <h3><?php esc_html_e( 'Dashboard Widget', 'woow-admin' ); ?></h3>
                    <p><?php esc_html_e( 'This is a preview of dashboard widget styling.', 'woow-admin' ); ?></p>
                    <button type="button" class="button button-primary"><?php esc_html_e( 'Primary Button', 'woow-admin' ); ?></button>
                    <button type="button" class="button"><?php esc_html_e( 'Secondary Button', 'woow-admin' ); ?></button>
                </div>
            </div>
        </div>
    </div> <!-- .woow-layout-container -->

    <!-- Footer with Performance Stats -->
    <div class="woow-footer woow-glass-strong">
        <div class="woow-stats">
            <div class="woow-stat">
                <span class="woow-stat-label"><?php esc_html_e( 'CSS Size:', 'woow-admin' ); ?></span>
                <span class="woow-stat-value" id="woow-stat-css-size">--</span>
            </div>
            
            <div class="woow-stat">
                <span class="woow-stat-label"><?php esc_html_e( 'Generation Time:', 'woow-admin' ); ?></span>
                <span class="woow-stat-value" id="woow-stat-gen-time">--</span>
            </div>
            
            <div class="woow-stat">
                <span class="woow-stat-label"><?php esc_html_e( 'Cache Hit Rate:', 'woow-admin' ); ?></span>
                <span class="woow-stat-value" id="woow-stat-cache-rate">--</span>
            </div>
            
            <div class="woow-stat">
                <span class="woow-stat-label"><?php esc_html_e( 'Current Palette:', 'woow-admin' ); ?></span>
                <span class="woow-stat-value" id="woow-stat-palette"><?php echo esc_html( ucwords( str_replace( '_', ' ', $current_palette ) ) ); ?></span>
            </div>
            
            <div class="woow-stat">
                <span class="woow-stat-label"><?php esc_html_e( 'Current Template:', 'woow-admin' ); ?></span>
                <span class="woow-stat-value" id="woow-stat-template"><?php echo esc_html( ucwords( str_replace( '_', ' ', $current_template ) ) ); ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification Container -->
<div id="woow-toast-container" class="woow-toast-container"></div>
