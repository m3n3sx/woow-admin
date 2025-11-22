<?php
/**
 * Settings Tab Template
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$advanced = $this->settings->get_section( 'advanced' );
$visual_effects = $this->settings->get_section( 'visual_effects' );
$backups = []; // Will be populated by backup manager
?>

<div class="woow-tab-pane" id="tab-settings">
    <div class="woow-section-header">
        <h2><?php esc_html_e( 'Advanced Settings', 'woow-admin' ); ?></h2>
        <p class="woow-section-description">
            <?php esc_html_e( 'Import/export settings, manage backups, view performance metrics, and configure advanced options.', 'woow-admin' ); ?>
        </p>
    </div>

    <!-- Import/Export -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Import / Export Settings', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-import-export-section">
                <div class="woow-export-section">
                    <h4><?php esc_html_e( 'Export Settings', 'woow-admin' ); ?></h4>
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Download your current settings as a JSON file for backup or transfer to another site.', 'woow-admin' ); ?>
                    </p>
                    <button type="button" class="button woow-button-primary" id="woow-export-settings">
                        <span class="dashicons dashicons-download"></span>
                        <?php esc_html_e( 'Export Settings', 'woow-admin' ); ?>
                    </button>
                </div>

                <div class="woow-import-section">
                    <h4><?php esc_html_e( 'Import Settings', 'woow-admin' ); ?></h4>
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Upload a previously exported JSON file to restore settings. A backup will be created automatically.', 'woow-admin' ); ?>
                    </p>
                    <input type="file" id="woow-import-file" accept=".json" style="display: none;" />
                    <button type="button" class="button woow-button-secondary" id="woow-import-settings">
                        <span class="dashicons dashicons-upload"></span>
                        <?php esc_html_e( 'Import Settings', 'woow-admin' ); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Backup/Restore -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Backup & Restore', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <p class="woow-field-description">
                <?php esc_html_e( 'Automatic backups are created when you save settings, apply templates, or import configurations. Up to 10 backups are stored.', 'woow-admin' ); ?>
            </p>

            <div class="woow-backup-list" id="woow-backup-list">
                <?php if ( empty( $backups ) ) : ?>
                    <p class="woow-no-backups"><?php esc_html_e( 'No backups available yet.', 'woow-admin' ); ?></p>
                <?php else : ?>
                    <?php foreach ( $backups as $backup ) : ?>
                        <div class="woow-backup-item">
                            <div class="woow-backup-info">
                                <span class="woow-backup-label"><?php echo esc_html( $backup['label'] ); ?></span>
                                <span class="woow-backup-date"><?php echo esc_html( $backup['date'] ); ?></span>
                            </div>
                            <div class="woow-backup-actions">
                                <button type="button" class="button woow-button-secondary woow-restore-backup" data-backup-id="<?php echo esc_attr( $backup['id'] ); ?>">
                                    <?php esc_html_e( 'Restore', 'woow-admin' ); ?>
                                </button>
                                <button type="button" class="button woow-button-secondary woow-delete-backup" data-backup-id="<?php echo esc_attr( $backup['id'] ); ?>">
                                    <?php esc_html_e( 'Delete', 'woow-admin' ); ?>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="woow-backup-actions-footer">
                <button type="button" class="button woow-button-primary" id="woow-create-backup">
                    <span class="dashicons dashicons-backup"></span>
                    <?php esc_html_e( 'Create Manual Backup', 'woow-admin' ); ?>
                </button>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Performance Metrics', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-metrics-grid">
                <div class="woow-metric-card">
                    <div class="woow-metric-icon">
                        <span class="dashicons dashicons-media-code"></span>
                    </div>
                    <div class="woow-metric-content">
                        <span class="woow-metric-label"><?php esc_html_e( 'CSS Size', 'woow-admin' ); ?></span>
                        <span class="woow-metric-value" id="settings-metric-css-size">--</span>
                        <span class="woow-metric-target"><?php esc_html_e( 'Target: < 50KB', 'woow-admin' ); ?></span>
                    </div>
                </div>

                <div class="woow-metric-card">
                    <div class="woow-metric-icon">
                        <span class="dashicons dashicons-clock"></span>
                    </div>
                    <div class="woow-metric-content">
                        <span class="woow-metric-label"><?php esc_html_e( 'Generation Time', 'woow-admin' ); ?></span>
                        <span class="woow-metric-value" id="settings-metric-gen-time">--</span>
                        <span class="woow-metric-target"><?php esc_html_e( 'Target: < 100ms', 'woow-admin' ); ?></span>
                    </div>
                </div>

                <div class="woow-metric-card">
                    <div class="woow-metric-icon">
                        <span class="dashicons dashicons-performance"></span>
                    </div>
                    <div class="woow-metric-content">
                        <span class="woow-metric-label"><?php esc_html_e( 'Cache Hit Rate', 'woow-admin' ); ?></span>
                        <span class="woow-metric-value" id="settings-metric-cache-rate">--</span>
                        <span class="woow-metric-target"><?php esc_html_e( 'Target: > 80%', 'woow-admin' ); ?></span>
                    </div>
                </div>

                <div class="woow-metric-card">
                    <div class="woow-metric-icon">
                        <span class="dashicons dashicons-networking"></span>
                    </div>
                    <div class="woow-metric-content">
                        <span class="woow-metric-label"><?php esc_html_e( 'AJAX Response', 'woow-admin' ); ?></span>
                        <span class="woow-metric-value" id="settings-metric-ajax-time">--</span>
                        <span class="woow-metric-target"><?php esc_html_e( 'Target: < 200ms', 'woow-admin' ); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Interface Layout -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Interface Layout', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-group">
                <label class="woow-toggle">
                    <input type="checkbox" name="advanced[sidebar_collapsed]" value="1" <?php checked( $advanced['sidebar_collapsed'] ?? false, true ); ?> class="woow-toggle-input" id="sidebar-collapse-toggle" />
                    <span class="woow-toggle-slider"></span>
                    <span class="woow-toggle-label"><?php esc_html_e( 'Collapse Sidebar by Default', 'woow-admin' ); ?></span>
                </label>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Start with the sidebar navigation collapsed to save screen space.', 'woow-admin' ); ?>
                </p>
            </div>
            
            <div class="woow-form-group">
                <label class="woow-toggle">
                    <input type="checkbox" name="advanced[preview_collapsed]" value="1" <?php checked( $advanced['preview_collapsed'] ?? false, true ); ?> class="woow-toggle-input" id="preview-collapse-toggle" />
                    <span class="woow-toggle-slider"></span>
                    <span class="woow-toggle-label"><?php esc_html_e( 'Collapse Live Preview by Default', 'woow-admin' ); ?></span>
                </label>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Start with the live preview panel collapsed to maximize content area.', 'woow-admin' ); ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Glassmorphism -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3>
                <span class="woow-icon">✨</span>
                <?php esc_html_e( 'Glassmorphism', 'woow-admin' ); ?>
            </h3>
            <p class="woow-section-description">
                <?php esc_html_e( 'Apply modern frosted glass effects to your admin interface', 'woow-admin' ); ?>
            </p>
        </div>
        <div class="woow-card-body">
            <!-- Global Toggle -->
            <div class="woow-form-group">
                <label class="woow-toggle">
                    <input 
                        type="checkbox" 
                        name="visual_effects[enable_glassmorphism]" 
                        value="1"
                        class="woow-toggle-input woow-condition-trigger"
                        data-target="glassmorphism_enabled"
                        <?php checked( $visual_effects['enable_glassmorphism'] ?? false, true ); ?>
                    />
                    <span class="woow-toggle-slider"></span>
                    <span class="woow-toggle-label"><?php esc_html_e( 'Enable Glassmorphism Globally', 'woow-admin' ); ?></span>
                </label>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Apply frosted glass effect to admin bar, menu, and widgets', 'woow-admin' ); ?>
                </p>
            </div>
            
            <!-- Strength Selector -->
            <div class="woow-form-group woow-conditional-field" data-condition="glassmorphism_enabled" data-value="1">
                <label class="woow-label">
                    <?php esc_html_e( 'Glassmorphism Strength', 'woow-admin' ); ?>
                </label>
                <select 
                    name="visual_effects[glass_strength]" 
                    class="woow-select"
                >
                    <option value="sm" <?php selected( $visual_effects['glass_strength'] ?? 'md', 'sm' ); ?>>
                        <?php esc_html_e( 'Light (4px blur)', 'woow-admin' ); ?>
                    </option>
                    <option value="md" <?php selected( $visual_effects['glass_strength'] ?? 'md', 'md' ); ?>>
                        <?php esc_html_e( 'Medium (8px blur)', 'woow-admin' ); ?>
                    </option>
                    <option value="lg" <?php selected( $visual_effects['glass_strength'] ?? 'md', 'lg' ); ?>>
                        <?php esc_html_e( 'Strong (12px blur)', 'woow-admin' ); ?>
                    </option>
                    <option value="xl" <?php selected( $visual_effects['glass_strength'] ?? 'md', 'xl' ); ?>>
                        <?php esc_html_e( 'Extra Strong (16px blur)', 'woow-admin' ); ?>
                    </option>
                </select>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Control the intensity of the frosted glass effect', 'woow-admin' ); ?>
                </p>
            </div>
            
            <!-- Browser Compatibility Notice -->
            <div class="woow-notice woow-notice-info">
                <span class="woow-notice-icon">ℹ️</span>
                <div class="woow-notice-content">
                    <strong><?php esc_html_e( 'Browser Compatibility:', 'woow-admin' ); ?></strong>
                    <?php esc_html_e( 'Glassmorphism requires modern browsers (Chrome 76+, Safari 9+, Firefox 103+, Edge 79+). Older browsers will show solid backgrounds.', 'woow-admin' ); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Debug Mode -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Debug Mode', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-group">
                <label class="woow-toggle">
                    <input type="checkbox" name="advanced[debug_mode]" value="1" <?php checked( $advanced['debug_mode'], true ); ?> class="woow-toggle-input" />
                    <span class="woow-toggle-slider"></span>
                    <span class="woow-toggle-label"><?php esc_html_e( 'Enable debug mode', 'woow-admin' ); ?></span>
                </label>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Logs detailed information to browser console and WordPress debug.log. Disable in production.', 'woow-admin' ); ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Cache Management -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Cache Management', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <p class="woow-field-description">
                <?php esc_html_e( 'Clear all cached CSS and settings. Use this if you experience styling issues.', 'woow-admin' ); ?>
            </p>
            <button type="button" class="button woow-button-secondary" id="woow-clear-cache">
                <span class="dashicons dashicons-trash"></span>
                <?php esc_html_e( 'Clear All Caches', 'woow-admin' ); ?>
            </button>
        </div>
    </div>

    <!-- System Information -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'System Information', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-system-info">
                <div class="woow-info-row">
                    <span class="woow-info-label"><?php esc_html_e( 'Plugin Version:', 'woow-admin' ); ?></span>
                    <span class="woow-info-value"><?php echo esc_html( WOOW_VERSION ); ?></span>
                </div>
                <div class="woow-info-row">
                    <span class="woow-info-label"><?php esc_html_e( 'WordPress Version:', 'woow-admin' ); ?></span>
                    <span class="woow-info-value"><?php echo esc_html( get_bloginfo( 'version' ) ); ?></span>
                </div>
                <div class="woow-info-row">
                    <span class="woow-info-label"><?php esc_html_e( 'PHP Version:', 'woow-admin' ); ?></span>
                    <span class="woow-info-value"><?php echo esc_html( PHP_VERSION ); ?></span>
                </div>
                <div class="woow-info-row">
                    <span class="woow-info-label"><?php esc_html_e( 'Active Theme:', 'woow-admin' ); ?></span>
                    <span class="woow-info-value"><?php echo esc_html( wp_get_theme()->get( 'Name' ) ); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
