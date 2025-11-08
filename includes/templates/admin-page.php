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
    <!-- Header -->
    <div class="woow-header woow-glass-strong">
        <div class="woow-header-left">
            <h1 class="woow-title">
                <span class="woow-logo-icon">✨</span>
                <?php esc_html_e( 'WOOW! Admin', 'woow-admin' ); ?>
            </h1>
            <span class="woow-version">v<?php echo esc_html( WOOW_VERSION ); ?></span>
        </div>
        
        <div class="woow-header-right">
            <button type="button" class="woow-button woow-button-secondary woow-preview-toggle" id="woow-preview-toggle">
                <span class="dashicons dashicons-visibility"></span>
                <?php esc_html_e( 'Toggle Preview', 'woow-admin' ); ?>
            </button>
            
            <button type="button" class="woow-button woow-button-secondary woow-export-btn" id="woow-export-btn">
                <span class="dashicons dashicons-download"></span>
                <?php esc_html_e( 'Export', 'woow-admin' ); ?>
            </button>
            
            <button type="button" class="woow-button woow-button-primary woow-save-btn" id="woow-save-btn">
                <span class="dashicons dashicons-yes"></span>
                <?php esc_html_e( 'Save Changes', 'woow-admin' ); ?>
            </button>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="woow-tabs woow-glass-strong">
        <button type="button" class="woow-tab-button active" data-tab="general">
            <span class="dashicons dashicons-admin-home"></span>
            <?php esc_html_e( 'General', 'woow-admin' ); ?>
        </button>
        
        <button type="button" class="woow-tab-button" data-tab="palettes">
            <span class="dashicons dashicons-art"></span>
            <?php esc_html_e( 'Palettes', 'woow-admin' ); ?>
        </button>
        
        <button type="button" class="woow-tab-button" data-tab="templates">
            <span class="dashicons dashicons-layout"></span>
            <?php esc_html_e( 'Templates', 'woow-admin' ); ?>
        </button>
        
        <button type="button" class="woow-tab-button" data-tab="admin-bar">
            <span class="dashicons dashicons-menu-alt"></span>
            <?php esc_html_e( 'Admin Bar', 'woow-admin' ); ?>
        </button>
        
        <button type="button" class="woow-tab-button" data-tab="menu">
            <span class="dashicons dashicons-menu"></span>
            <?php esc_html_e( 'Menu', 'woow-admin' ); ?>
        </button>
        
        <button type="button" class="woow-tab-button" data-tab="widgets">
            <span class="dashicons dashicons-dashboard"></span>
            <?php esc_html_e( 'Widgets', 'woow-admin' ); ?>
        </button>
        
        <button type="button" class="woow-tab-button" data-tab="forms">
            <span class="dashicons dashicons-edit"></span>
            <?php esc_html_e( 'Forms', 'woow-admin' ); ?>
        </button>
        
        <button type="button" class="woow-tab-button" data-tab="buttons">
            <span class="dashicons dashicons-button"></span>
            <?php esc_html_e( 'Buttons', 'woow-admin' ); ?>
        </button>
        
        <button type="button" class="woow-tab-button" data-tab="backgrounds">
            <span class="dashicons dashicons-format-image"></span>
            <?php esc_html_e( 'Backgrounds', 'woow-admin' ); ?>
        </button>
        
        <button type="button" class="woow-tab-button" data-tab="typography">
            <span class="dashicons dashicons-editor-textcolor"></span>
            <?php esc_html_e( 'Typography', 'woow-admin' ); ?>
        </button>
        
        <button type="button" class="woow-tab-button" data-tab="effects">
            <span class="dashicons dashicons-admin-appearance"></span>
            <?php esc_html_e( 'Effects', 'woow-admin' ); ?>
        </button>
        
        <button type="button" class="woow-tab-button" data-tab="login">
            <span class="dashicons dashicons-lock"></span>
            <?php esc_html_e( 'Login Page', 'woow-admin' ); ?>
        </button>
        
        <button type="button" class="woow-tab-button" data-tab="settings">
            <span class="dashicons dashicons-admin-settings"></span>
            <?php esc_html_e( 'Settings', 'woow-admin' ); ?>
        </button>
    </div>

    <!-- Main Content Area -->
    <div class="woow-content">
        <!-- Sidebar with Tab Content -->
        <div class="woow-sidebar">
            <form id="woow-settings-form" method="post">
                <?php wp_nonce_field( 'woow_save_settings', 'woow_nonce' ); ?>
                
                <!-- Tab Content Containers -->
                <div class="woow-tab-content">
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
                </div>
            </form>
        </div>

        <!-- Live Preview Panel -->
        <div class="woow-preview-panel woow-glass-strong" id="woow-preview-panel">
            <div class="woow-preview-header">
                <h3><?php esc_html_e( 'Live Preview', 'woow-admin' ); ?></h3>
                
                <div class="woow-preview-controls">
                    <button type="button" class="woow-preview-mode active" data-mode="desktop" title="<?php esc_attr_e( 'Desktop', 'woow-admin' ); ?>">
                        <span class="dashicons dashicons-desktop"></span>
                    </button>
                    <button type="button" class="woow-preview-mode" data-mode="tablet" title="<?php esc_attr_e( 'Tablet', 'woow-admin' ); ?>">
                        <span class="dashicons dashicons-tablet"></span>
                    </button>
                    <button type="button" class="woow-preview-mode" data-mode="mobile" title="<?php esc_attr_e( 'Mobile', 'woow-admin' ); ?>">
                        <span class="dashicons dashicons-smartphone"></span>
                    </button>
                </div>
            </div>
            
            <div class="woow-preview-body">
                <iframe 
                    id="woow-preview-iframe" 
                    src="<?php echo esc_url( admin_url() ); ?>" 
                    frameborder="0"
                    title="<?php esc_attr_e( 'Live Preview', 'woow-admin' ); ?>"
                ></iframe>
            </div>
        </div>
    </div>

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
