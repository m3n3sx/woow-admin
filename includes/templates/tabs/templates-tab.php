<?php
/**
 * Templates Tab Template
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Note: Templates are loaded via JavaScript from woowAdminData.templates
$current_template = $settings['general']['current_template'] ?? 'modern_minimal';
?>

<div class="woow-tab-pane" id="tab-templates">
    <!-- Section Header -->
    <div class="woow-section-header">
        <h2><?php esc_html_e( 'Design Templates', 'woow-admin' ); ?></h2>
        <p class="woow-section-description">
            <?php esc_html_e( 'Apply complete design templates with pre-configured settings for all sections.', 'woow-admin' ); ?>
        </p>
    </div>

    <!-- Template Gallery -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Available Templates', 'woow-admin' ); ?></h3>
            <p class="woow-card-description">
                <?php esc_html_e( 'Choose from 11 professionally designed templates. Each template includes complete styling for all admin sections.', 'woow-admin' ); ?>
            </p>
        </div>
        <div class="woow-card-body">
            <?php include WOOW_PLUGIN_DIR . 'includes/templates/components/template-gallery.php'; ?>
        </div>
    </div>

    <!-- Active Template Info (Dynamically Updated) -->
    <div class="woow-card" id="woow-active-template-info" style="display: none;">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Active Template Details', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-template-details">
                <div class="woow-template-detail-header">
                    <h4 id="woow-active-template-name"></h4>
                    <span class="woow-badge woow-badge-primary">
                        <?php esc_html_e( 'Active', 'woow-admin' ); ?>
                    </span>
                </div>
                
                <p class="woow-template-detail-description" id="woow-active-template-description"></p>

                <div class="woow-template-characteristics" id="woow-active-template-characteristics"></div>
            </div>
        </div>
    </div>

    <!-- Template Actions -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Template Actions', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-template-actions">
                <button type="button" class="woow-button woow-button-secondary" id="woow-save-as-template">
                    <span class="dashicons dashicons-saved"></span>
                    <?php esc_html_e( 'Save Current Settings as Template', 'woow-admin' ); ?>
                </button>
                
                <button type="button" class="woow-button woow-button-secondary" id="woow-reset-template">
                    <span class="dashicons dashicons-image-rotate"></span>
                    <?php esc_html_e( 'Reset to Template Defaults', 'woow-admin' ); ?>
                </button>
            </div>
            
            <p class="woow-field-description">
                <?php esc_html_e( 'Save your current configuration as a custom template or reset all settings to match the active template.', 'woow-admin' ); ?>
            </p>
        </div>
    </div>
</div>
