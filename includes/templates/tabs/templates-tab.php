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

$templates = $this->settings->get_available_templates();
$current_template = $settings['general']['current_template'] ?? 'default';
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
        </div>
        <div class="woow-card-body">
            <?php include WOOW_PLUGIN_DIR . 'includes/templates/components/template-gallery.php'; ?>
        </div>
    </div>

    <!-- Active Template Details -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Active Template Details', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <?php
            $active_template = $templates[ $current_template ] ?? $templates['default'];
            ?>
            <div class="woow-template-details">
                <div class="woow-template-detail-header">
                    <h4><?php echo esc_html( $active_template['name'] ); ?></h4>
                    <span class="woow-badge woow-badge-primary">
                        <?php esc_html_e( 'Active', 'woow-admin' ); ?>
                    </span>
                </div>
                
                <p class="woow-template-detail-description">
                    <?php echo esc_html( $active_template['description'] ); ?>
                </p>

                <?php if ( ! empty( $active_template['features'] ) ) : ?>
                    <div class="woow-template-features-list">
                        <h5><?php esc_html_e( 'Features:', 'woow-admin' ); ?></h5>
                        <ul>
                            <?php foreach ( $active_template['features'] as $feature ) : ?>
                                <li>
                                    <span class="dashicons dashicons-yes"></span>
                                    <?php echo esc_html( $feature ); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ( ! empty( $active_template['metadata'] ) ) : ?>
                    <div class="woow-template-metadata">
                        <?php if ( ! empty( $active_template['metadata']['author'] ) ) : ?>
                            <div class="woow-metadata-item">
                                <span class="woow-metadata-label"><?php esc_html_e( 'Author:', 'woow-admin' ); ?></span>
                                <span class="woow-metadata-value"><?php echo esc_html( $active_template['metadata']['author'] ); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $active_template['metadata']['version'] ) ) : ?>
                            <div class="woow-metadata-item">
                                <span class="woow-metadata-label"><?php esc_html_e( 'Version:', 'woow-admin' ); ?></span>
                                <span class="woow-metadata-value"><?php echo esc_html( $active_template['metadata']['version'] ); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
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
