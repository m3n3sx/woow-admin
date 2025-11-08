<?php
/**
 * Template Gallery Component
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

<div class="woow-template-gallery">
    <div class="woow-template-grid">
        <?php foreach ( $templates as $template_id => $template ) : ?>
            <div class="woow-template-card <?php echo $template_id === $current_template ? 'active' : ''; ?>" 
                 data-template="<?php echo esc_attr( $template_id ); ?>">
                
                <div class="woow-template-thumbnail">
                    <?php if ( ! empty( $template['thumbnail'] ) && file_exists( WOOW_PLUGIN_DIR . $template['thumbnail'] ) ) : ?>
                        <img src="<?php echo esc_url( WOOW_PLUGIN_URL . $template['thumbnail'] ); ?>" 
                             alt="<?php echo esc_attr( $template['name'] ); ?>">
                    <?php else : ?>
                        <div class="woow-template-placeholder">
                            <span class="dashicons dashicons-layout"></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ( $template_id === $current_template ) : ?>
                        <div class="woow-template-badge">
                            <span class="dashicons dashicons-yes-alt"></span>
                            <?php esc_html_e( 'Active', 'woow-admin' ); ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="woow-template-info">
                    <h4 class="woow-template-name"><?php echo esc_html( $template['name'] ); ?></h4>
                    <p class="woow-template-description"><?php echo esc_html( $template['description'] ); ?></p>
                    
                    <?php if ( ! empty( $template['features'] ) ) : ?>
                        <ul class="woow-template-features">
                            <?php foreach ( array_slice( $template['features'], 0, 3 ) as $feature ) : ?>
                                <li>
                                    <span class="dashicons dashicons-yes"></span>
                                    <?php echo esc_html( $feature ); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
                
                <button type="button" 
                        class="woow-template-apply woow-button woow-button-primary"
                        data-template="<?php echo esc_attr( $template_id ); ?>">
                    <?php esc_html_e( 'Apply Template', 'woow-admin' ); ?>
                </button>
            </div>
        <?php endforeach; ?>
    </div>
</div>
