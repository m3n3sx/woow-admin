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
$general = array_merge(
    array(
        'rounded_style' => true,
        'glass_style' => false,
        'floating_style' => false,
    ),
    $settings['general'] ?? array()
);
?>

<div class="woow-tab-pane" id="tab-general">
    <!-- Global Style Toggles -->
    <div class="woow-grid woow-grid-3" style="margin-bottom: 24px;">
        <!-- Rounded Style Toggle -->
        <div class="woow-card" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(139, 92, 246, 0.05) 100%); border: 1px solid rgba(99, 102, 241, 0.1);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="width: 48px; height: 48px; border-radius: 16px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <span class="dashicons dashicons-admin-appearance" style="font-size: 24px; width: 24px; height: 24px; color: white;"></span>
                    </div>
                    <div>
                        <h3 style="font-size: 16px; font-weight: 600; color: #0f172a; margin: 0 0 4px 0;">
                            <?php esc_html_e( 'Rounded Style', 'woow-admin' ); ?>
                        </h3>
                        <p style="font-size: 13px; color: #6b7280; margin: 0;">
                            <?php esc_html_e( 'Enable rounded corners everywhere', 'woow-admin' ); ?>
                        </p>
                    </div>
                </div>
                <label class="woow-switch">
                    <input type="hidden" name="general[rounded_style]" value="0" />
                    <input 
                        type="checkbox" 
                        name="general[rounded_style]" 
                        value="1"
                        <?php checked( $general['rounded_style'], true ); ?>
                    />
                    <span class="woow-switch-slider"></span>
                </label>
            </div>
        </div>

        <!-- Glass Style Toggle -->
        <div class="woow-card" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, rgba(147, 197, 253, 0.05) 100%); border: 1px solid rgba(59, 130, 246, 0.1);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="width: 48px; height: 48px; border-radius: 16px; background: linear-gradient(135deg, #3b82f6 0%, #93c5fd 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <span class="dashicons dashicons-visibility" style="font-size: 24px; width: 24px; height: 24px; color: white;"></span>
                    </div>
                    <div>
                        <h3 style="font-size: 16px; font-weight: 600; color: #0f172a; margin: 0 0 4px 0;">
                            <?php esc_html_e( 'Glass Style', 'woow-admin' ); ?>
                        </h3>
                        <p style="font-size: 13px; color: #6b7280; margin: 0;">
                            <?php esc_html_e( 'Enable glassmorphism effect', 'woow-admin' ); ?>
                        </p>
                    </div>
                </div>
                <label class="woow-switch">
                    <input type="hidden" name="general[glass_style]" value="0" />
                    <input 
                        type="checkbox" 
                        name="general[glass_style]" 
                        value="1"
                        <?php checked( $general['glass_style'], true ); ?>
                    />
                    <span class="woow-switch-slider"></span>
                </label>
            </div>
        </div>

        <!-- Floating Style Toggle -->
        <div class="woow-card" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(5, 150, 105, 0.05) 100%); border: 1px solid rgba(16, 185, 129, 0.1);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="width: 48px; height: 48px; border-radius: 16px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <span class="dashicons dashicons-editor-expand" style="font-size: 24px; width: 24px; height: 24px; color: white;"></span>
                    </div>
                    <div>
                        <h3 style="font-size: 16px; font-weight: 600; color: #0f172a; margin: 0 0 4px 0;">
                            <?php esc_html_e( 'Floating Style', 'woow-admin' ); ?>
                        </h3>
                        <p style="font-size: 13px; color: #6b7280; margin: 0;">
                            <?php esc_html_e( 'Remove margins - stick to edges', 'woow-admin' ); ?>
                        </p>
                    </div>
                </div>
                <label class="woow-switch">
                    <input type="hidden" name="general[floating_style]" value="0" />
                    <input 
                        type="checkbox" 
                        name="general[floating_style]" 
                        value="1"
                        <?php checked( $general['floating_style'], true ); ?>
                    />
                    <span class="woow-switch-slider"></span>
                </label>
            </div>
        </div>
    </div>

    <!-- Hej Admin Card (zgodnie z Figma) -->
    <div class="woow-card" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(139, 92, 246, 0.05) 100%); border: 1px solid rgba(99, 102, 241, 0.1);">
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="width: 64px; height: 64px; border-radius: 20px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <span class="dashicons dashicons-admin-users" style="font-size: 32px; width: 32px; height: 32px; color: white;"></span>
            </div>
            <div style="flex: 1;">
                <h2 style="font-size: 24px; font-weight: 700; color: #0f172a; margin: 0 0 8px 0; line-height: 1.3;">
                    <?php esc_html_e( 'Hej Admin!', 'woow-admin' ); ?>
                </h2>
                <p style="font-size: 15px; color: #6b7280; margin: 0; line-height: 1.6;">
                    <?php esc_html_e( 'Ready to make your WordPress dashboard look amazing? Start by choosing a template below.', 'woow-admin' ); ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="woow-card">
        <div style="margin-bottom: 24px;">
            <h3 style="font-size: 18px; font-weight: 600; color: #0f172a; margin: 0 0 8px 0;">
                <?php esc_html_e( 'Quick Actions', 'woow-admin' ); ?>
            </h3>
        </div>
        <div class="woow-grid woow-grid-3">
            <button type="button" class="woow-button woow-button-primary" style="width: 100%; height: 56px; font-size: 15px;" onclick="document.querySelector('[data-tab=\\'templates\\']').click()">
                <span class="dashicons dashicons-admin-appearance"></span>
                <?php esc_html_e( 'Apply Template', 'woow-admin' ); ?>
            </button>
            <button type="button" class="woow-button woow-button-secondary" style="width: 100%; height: 56px; font-size: 15px; background: white; color: #6b7280; border: 1px solid #e5e7eb;" onclick="document.querySelector('[data-tab=\\'settings\\']').click()">
                <span class="dashicons dashicons-upload"></span>
                <?php esc_html_e( 'Import Settings', 'woow-admin' ); ?>
            </button>
            <button type="button" class="woow-button woow-button-secondary" style="width: 100%; height: 56px; font-size: 15px; background: white; color: #6b7280; border: 1px solid #e5e7eb;" onclick="document.querySelector('[data-tab=\\'settings\\']').click()">
                <span class="dashicons dashicons-download"></span>
                <?php esc_html_e( 'Export Config', 'woow-admin' ); ?>
            </button>
        </div>
    </div>

    <!-- Choose Your Style Section -->
    <div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <div>
                <h3 style="font-size: 18px; font-weight: 600; color: #0f172a; margin: 0 0 4px 0;">
                    <?php esc_html_e( 'Choose Your Style', 'woow-admin' ); ?>
                </h3>
                <p style="font-size: 14px; color: #6b7280; margin: 0;">
                    <?php esc_html_e( 'Pick a template and customize it to match your brand', 'woow-admin' ); ?>
                </p>
            </div>
            <button type="button" class="woow-button woow-button-ghost" onclick="document.querySelector('[data-tab=\\'templates\\']').click()">
                <span class="dashicons dashicons-visibility"></span>
                <?php esc_html_e( 'View All', 'woow-admin' ); ?>
            </button>
        </div>

        <div class="woow-grid woow-grid-3">
            <!-- Modern Dark Template -->
            <div class="woow-card" style="padding: 0; overflow: hidden; cursor: pointer; transition: all 200ms;" onclick="document.querySelector('[data-tab=\\'templates\\']').click()">
                <div style="height: 140px; background: linear-gradient(135deg, #1e293b 0%, #334155 100%); position: relative; display: flex; align-items: center; justify-content: center; gap: 12px;">
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #3b82f6;"></div>
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #6366f1;"></div>
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #8b5cf6;"></div>
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #ec4899;"></div>
                    <span style="position: absolute; top: 12px; right: 12px; background: #fbbf24; color: #0f172a; font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 6px;">
                        <?php esc_html_e( 'Popular', 'woow-admin' ); ?>
                    </span>
                </div>
                <div style="padding: 20px;">
                    <h4 style="font-size: 16px; font-weight: 600; color: #0f172a; margin: 0 0 8px 0;">
                        <?php esc_html_e( 'Modern Dark', 'woow-admin' ); ?>
                    </h4>
                    <p style="font-size: 14px; color: #6b7280; margin: 0 0 16px 0; line-height: 1.5;">
                        <?php esc_html_e( 'Sleek dark theme with vibrant accents', 'woow-admin' ); ?>
                    </p>
                    <button type="button" class="woow-button woow-button-ghost" style="width: 100%; justify-content: center;">
                        <span class="dashicons dashicons-admin-appearance"></span>
                        <?php esc_html_e( 'Apply Template', 'woow-admin' ); ?>
                    </button>
                </div>
            </div>

            <!-- Professional Blue Template -->
            <div class="woow-card" style="padding: 0; overflow: hidden; cursor: pointer; transition: all 200ms;" onclick="document.querySelector('[data-tab=\\'templates\\']').click()">
                <div style="height: 140px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); position: relative; display: flex; align-items: center; justify-content: center; gap: 12px;">
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #60a5fa;"></div>
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #3b82f6;"></div>
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #2563eb;"></div>
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #1d4ed8;"></div>
                    <span style="position: absolute; top: 12px; right: 12px; background: #fbbf24; color: #0f172a; font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 6px;">
                        <?php esc_html_e( 'Popular', 'woow-admin' ); ?>
                    </span>
                </div>
                <div style="padding: 20px;">
                    <h4 style="font-size: 16px; font-weight: 600; color: #0f172a; margin: 0 0 8px 0;">
                        <?php esc_html_e( 'Professional Blue', 'woow-admin' ); ?>
                    </h4>
                    <p style="font-size: 14px; color: #6b7280; margin: 0 0 16px 0; line-height: 1.5;">
                        <?php esc_html_e( 'Classic WordPress blue with modern twist', 'woow-admin' ); ?>
                    </p>
                    <button type="button" class="woow-button woow-button-ghost" style="width: 100%; justify-content: center;">
                        <span class="dashicons dashicons-admin-appearance"></span>
                        <?php esc_html_e( 'Apply Template', 'woow-admin' ); ?>
                    </button>
                </div>
            </div>

            <!-- Minimal Light Template -->
            <div class="woow-card" style="padding: 0; overflow: hidden; cursor: pointer; transition: all 200ms;" onclick="document.querySelector('[data-tab=\\'templates\\']').click()">
                <div style="height: 140px; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); position: relative; display: flex; align-items: center; justify-content: center; gap: 12px;">
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #3b82f6;"></div>
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #6366f1;"></div>
                </div>
                <div style="padding: 20px;">
                    <h4 style="font-size: 16px; font-weight: 600; color: #0f172a; margin: 0 0 8px 0;">
                        <?php esc_html_e( 'Minimal Light', 'woow-admin' ); ?>
                    </h4>
                    <p style="font-size: 14px; color: #6b7280; margin: 0 0 16px 0; line-height: 1.5;">
                        <?php esc_html_e( 'Clean and modern light interface', 'woow-admin' ); ?>
                    </p>
                    <button type="button" class="woow-button woow-button-ghost" style="width: 100%; justify-content: center;">
                        <span class="dashicons dashicons-admin-appearance"></span>
                        <?php esc_html_e( 'Apply Template', 'woow-admin' ); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="woow-card">
        <div style="margin-bottom: 24px;">
            <h3 style="font-size: 18px; font-weight: 600; color: #0f172a; margin: 0;">
                <?php esc_html_e( 'Recent Activity', 'woow-admin' ); ?>
            </h3>
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
