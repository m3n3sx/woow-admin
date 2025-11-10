<?php
/**
 * General/Dashboard Tab Template
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$settings = $this->settings->get_all();
?>

<div class="woow-tab-pane" id="tab-general">
    <!-- Welcome Card -->
    <div class="woow-welcome-card">
        <h2 class="woow-welcome-title">
            <?php esc_html_e( 'Welcome to WOOW! Admin', 'woow-admin' ); ?>
        </h2>
        <p class="woow-welcome-description">
            <?php esc_html_e( 'Transform your WordPress admin with modern glassmorphism design', 'woow-admin' ); ?>
        </p>
        <div class="woow-welcome-actions">
            <button type="button" class="woow-button woow-button-white" onclick="document.querySelector('[data-tab=\\'palettes\\']').click()">
                <span class="dashicons dashicons-art"></span>
                <?php esc_html_e( 'Choose Palette', 'woow-admin' ); ?>
            </button>
            <button type="button" class="woow-button woow-button-outline-white" onclick="document.querySelector('[data-tab=\\'templates\\']').click()">
                <span class="dashicons dashicons-layout"></span>
                <?php esc_html_e( 'Browse Templates', 'woow-admin' ); ?>
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="woow-grid woow-grid-4">
        <!-- Stat 1: Saved Presets -->
        <div class="woow-stat-card">
            <div class="woow-stat-header">
                <div class="woow-stat-icon" style="background: linear-gradient(to bottom right, #6366f1, #8b5cf6);">
                    <span class="dashicons dashicons-saved"></span>
                </div>
                <span class="woow-card-badge success">+3 new</span>
            </div>
            <p class="woow-stat-label"><?php esc_html_e( 'Saved Presets', 'woow-admin' ); ?></p>
            <p class="woow-stat-value">12</p>
        </div>

        <!-- Stat 2: Last Modified -->
        <div class="woow-stat-card">
            <div class="woow-stat-header">
                <div class="woow-stat-icon" style="background: linear-gradient(to bottom right, #8b5cf6, #a78bfa);">
                    <span class="dashicons dashicons-clock"></span>
                </div>
                <span class="woow-card-badge secondary">Today</span>
            </div>
            <p class="woow-stat-label"><?php esc_html_e( 'Last Modified', 'woow-admin' ); ?></p>
            <p class="woow-stat-value">2h ago</p>
        </div>

        <!-- Stat 3: Active Elements -->
        <div class="woow-stat-card">
            <div class="woow-stat-header">
                <div class="woow-stat-icon" style="background: linear-gradient(to bottom right, #10b981, #34d399);">
                    <span class="dashicons dashicons-yes-alt"></span>
                </div>
                <span class="woow-card-badge primary">Styled</span>
            </div>
            <p class="woow-stat-label"><?php esc_html_e( 'Active Elements', 'woow-admin' ); ?></p>
            <p class="woow-stat-value">47</p>
        </div>

        <!-- Stat 4: Performance -->
        <div class="woow-stat-card">
            <div class="woow-stat-header">
                <div class="woow-stat-icon" style="background: linear-gradient(to bottom right, #f59e0b, #fbbf24);">
                    <span class="dashicons dashicons-performance"></span>
                </div>
                <span class="woow-card-badge success">-9%</span>
            </div>
            <p class="woow-stat-label"><?php esc_html_e( 'Performance', 'woow-admin' ); ?></p>
            <p class="woow-stat-value">98%</p>
        </div>
    </div>

    <!-- Quick Actions & Recent Activity -->
    <div class="woow-grid woow-grid-2">
        <!-- Quick Actions Card -->
        <div class="woow-card">
            <div class="woow-card-header">
                <div class="woow-card-header-left">
                    <div class="woow-card-icon">
                        <span class="dashicons dashicons-admin-generic"></span>
                    </div>
                    <div class="woow-card-header-text">
                        <h3 class="woow-card-title"><?php esc_html_e( 'Quick Actions', 'woow-admin' ); ?></h3>
                        <p class="woow-card-description"><?php esc_html_e( 'Common tasks and shortcuts', 'woow-admin' ); ?></p>
                    </div>
                </div>
            </div>
            <div class="woow-card-body">
                <button type="button" class="woow-button woow-button-secondary woow-button-full" onclick="document.querySelector('[data-tab=\\'palettes\\']').click()">
                    <span class="dashicons dashicons-art"></span>
                    <?php esc_html_e( 'Apply New Palette', 'woow-admin' ); ?>
                </button>
                <button type="button" class="woow-button woow-button-secondary woow-button-full" onclick="document.querySelector('[data-tab=\\'templates\\']').click()">
                    <span class="dashicons dashicons-layout"></span>
                    <?php esc_html_e( 'Change Template', 'woow-admin' ); ?>
                </button>
                <button type="button" class="woow-button woow-button-secondary woow-button-full" onclick="document.querySelector('[data-tab=\\'settings\\']').click()">
                    <span class="dashicons dashicons-download"></span>
                    <?php esc_html_e( 'Export Settings', 'woow-admin' ); ?>
                </button>
                <button type="button" class="woow-button woow-button-secondary woow-button-full" onclick="document.querySelector('[data-tab=\\'settings\\']').click()">
                    <span class="dashicons dashicons-backup"></span>
                    <?php esc_html_e( 'Create Backup', 'woow-admin' ); ?>
                </button>
            </div>
        </div>

        <!-- Recent Activity Card -->
        <div class="woow-card">
            <div class="woow-card-header">
                <div class="woow-card-header-left">
                    <div class="woow-card-icon purple">
                        <span class="dashicons dashicons-backup"></span>
                    </div>
                    <div class="woow-card-header-text">
                        <h3 class="woow-card-title"><?php esc_html_e( 'Recent Activity', 'woow-admin' ); ?></h3>
                        <p class="woow-card-description"><?php esc_html_e( 'Latest changes and updates', 'woow-admin' ); ?></p>
                    </div>
                </div>
            </div>
            <div class="woow-card-body">
                <div class="woow-activity-item">
                    <div style="width: 8px; height: 8px; border-radius: 50%; background: #6366f1; margin-top: 8px; flex-shrink: 0;"></div>
                    <div style="flex: 1;">
                        <p style="font-size: 14px; color: #0f172a; margin: 0 0 4px 0; font-weight: 500;">
                            <?php esc_html_e( 'Applied Professional Blue palette', 'woow-admin' ); ?>
                        </p>
                        <p style="font-size: 12px; color: #6b7280; margin: 0;">
                            <?php esc_html_e( '2 hours ago', 'woow-admin' ); ?>
                        </p>
                    </div>
                </div>
                <div class="woow-activity-item">
                    <div style="width: 8px; height: 8px; border-radius: 50%; background: #8b5cf6; margin-top: 8px; flex-shrink: 0;"></div>
                    <div style="flex: 1;">
                        <p style="font-size: 14px; color: #0f172a; margin: 0 0 4px 0; font-weight: 500;">
                            <?php esc_html_e( 'Updated admin bar settings', 'woow-admin' ); ?>
                        </p>
                        <p style="font-size: 12px; color: #6b7280; margin: 0;">
                            <?php esc_html_e( '5 hours ago', 'woow-admin' ); ?>
                        </p>
                    </div>
                </div>
                <div class="woow-activity-item">
                    <div style="width: 8px; height: 8px; border-radius: 50%; background: #10b981; margin-top: 8px; flex-shrink: 0;"></div>
                    <div style="flex: 1;">
                        <p style="font-size: 14px; color: #0f172a; margin: 0 0 4px 0; font-weight: 500;">
                            <?php esc_html_e( 'Created backup: manual-backup', 'woow-admin' ); ?>
                        </p>
                        <p style="font-size: 12px; color: #6b7280; margin: 0;">
                            <?php esc_html_e( 'Yesterday', 'woow-admin' ); ?>
                        </p>
                    </div>
                </div>
                <div class="woow-activity-item">
                    <div style="width: 8px; height: 8px; border-radius: 50%; background: #f59e0b; margin-top: 8px; flex-shrink: 0;"></div>
                    <div style="flex: 1;">
                        <p style="font-size: 14px; color: #0f172a; margin: 0 0 4px 0; font-weight: 500;">
                            <?php esc_html_e( 'Exported settings to JSON', 'woow-admin' ); ?>
                        </p>
                        <p style="font-size: 12px; color: #6b7280; margin: 0;">
                            <?php esc_html_e( '2 days ago', 'woow-admin' ); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Configuration Card -->
    <div class="woow-card">
        <div class="woow-card-header">
            <div class="woow-card-header-left">
                <div class="woow-card-icon orange">
                    <span class="dashicons dashicons-admin-settings"></span>
                </div>
                <div class="woow-card-header-text">
                    <h3 class="woow-card-title"><?php esc_html_e( 'Current Configuration', 'woow-admin' ); ?></h3>
                    <p class="woow-card-description"><?php esc_html_e( 'Active palette and template settings', 'woow-admin' ); ?></p>
                </div>
            </div>
        </div>
        <div class="woow-card-body">
            <div class="woow-grid woow-grid-3">
                <div style="padding: 16px; background: rgba(99, 102, 241, 0.05); border-radius: 12px; border: 1px solid rgba(99, 102, 241, 0.1);">
                    <p style="font-size: 13px; color: #6b7280; margin: 0 0 8px 0; font-weight: 500;">
                        <?php esc_html_e( 'Active Palette', 'woow-admin' ); ?>
                    </p>
                    <p style="font-size: 16px; color: #0f172a; margin: 0; font-weight: 600;">
                        <?php echo esc_html( ucwords( str_replace( '_', ' ', $settings['general']['current_palette'] ?? 'Professional Blue' ) ) ); ?>
                    </p>
                </div>
                <div style="padding: 16px; background: rgba(139, 92, 246, 0.05); border-radius: 12px; border: 1px solid rgba(139, 92, 246, 0.1);">
                    <p style="font-size: 13px; color: #6b7280; margin: 0 0 8px 0; font-weight: 500;">
                        <?php esc_html_e( 'Active Template', 'woow-admin' ); ?>
                    </p>
                    <p style="font-size: 16px; color: #0f172a; margin: 0; font-weight: 600;">
                        <?php echo esc_html( ucwords( str_replace( '_', ' ', $settings['general']['current_template'] ?? 'Default' ) ) ); ?>
                    </p>
                </div>
                <div style="padding: 16px; background: rgba(16, 185, 129, 0.05); border-radius: 12px; border: 1px solid rgba(16, 185, 129, 0.1);">
                    <p style="font-size: 13px; color: #6b7280; margin: 0 0 8px 0; font-weight: 500;">
                        <?php esc_html_e( 'CSS Size', 'woow-admin' ); ?>
                    </p>
                    <p style="font-size: 16px; color: #0f172a; margin: 0; font-weight: 600;">
                        45 KB
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.woow-activity-item {
    display: flex;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid rgba(229, 231, 235, 0.5);
}

.woow-activity-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.woow-button-full {
    width: 100%;
    justify-content: flex-start;
}
</style>
