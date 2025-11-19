<?php
/**
 * Content Styling Tab Template
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$settings = $this->settings->get_all();
$content = array_merge(
    array(
        'enabled' => true,
        'wpbody_content_border_radius' => '24',
        'wpbody_content_glassmorphism' => false,
        'wpbody_content_opacity' => 0.9,
        'wpbody_content_blur_strength' => '12',
        'wp_list_table_border_radius' => '12',
    ),
    $settings['content_styling'] ?? array()
);
?>

<div class="woow-tab-pane" id="tab-content">
    <!-- WPBody Content Card -->
    <div class="woow-card">
        <div class="woow-card-header">
            <div class="woow-card-icon">
                <span class="dashicons dashicons-admin-page"></span>
            </div>
            <div>
                <h3 class="woow-card-title"><?php esc_html_e( 'Content Area', 'woow-admin' ); ?></h3>
                <p class="woow-card-description"><?php esc_html_e( 'Customize the main content area (#wpbody-content)', 'woow-admin' ); ?></p>
            </div>
        </div>
        
        <div class="woow-card-body">
            <!-- Border Radius -->
            <div class="woow-form-group">
                <label class="woow-label">
                    <?php esc_html_e( 'Border Radius', 'woow-admin' ); ?>
                </label>
                <div class="woow-slider-group">
                    <input 
                        type="range" 
                        name="content_styling[wpbody_content_border_radius]"
                        value="<?php echo esc_attr( intval( $content['wpbody_content_border_radius'] ?? 24 ) ); ?>"
                        min="0" 
                        max="50" 
                        step="2"
                        class="woow-slider"
                        data-type="unitless"
                        data-unit="px"
                    />
                    <span class="woow-slider-value">
                        <?php echo esc_html( $content['wpbody_content_border_radius'] ?? 24 ); ?>px
                    </span>
                </div>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Border radius for content area. Default: 24px', 'woow-admin' ); ?>
                </p>
            </div>
            
            <!-- Glassmorphism -->
            <div class="woow-form-group">
                <label class="woow-switch-label">
                    <input type="hidden" name="content_styling[wpbody_content_glassmorphism]" value="0" />
                    <input 
                        type="checkbox" 
                        name="content_styling[wpbody_content_glassmorphism]" 
                        value="1"
                        class="woow-switch-input woow-condition-trigger"
                        data-target="wpbody_glassmorphism"
                        <?php checked( $content['wpbody_content_glassmorphism'], true ); ?>
                    />
                    <span class="woow-switch-slider"></span>
                    <span class="woow-switch-text"><?php esc_html_e( 'Enable Glassmorphism', 'woow-admin' ); ?></span>
                </label>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Apply glassmorphism effect to content area', 'woow-admin' ); ?>
                </p>
            </div>
            
            <!-- Glassmorphism Settings (Conditional) -->
            <div class="woow-conditional-field" data-condition="wpbody_glassmorphism" data-value="1">
                <!-- Opacity -->
                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Opacity', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-slider-group">
                        <input 
                            type="range" 
                            name="content_styling[wpbody_content_opacity]"
                            value="<?php echo esc_attr( $content['wpbody_content_opacity'] * 100 ); ?>"
                            min="0" 
                            max="100" 
                            step="5"
                            class="woow-slider"
                            data-type="opacity"
                        />
                        <span class="woow-slider-value">
                            <?php echo esc_html( $content['wpbody_content_opacity'] * 100 ); ?>%
                        </span>
                    </div>
                </div>
                
                <!-- Blur Strength -->
                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Blur Strength', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-slider-group">
                        <input 
                            type="range" 
                            name="content_styling[wpbody_content_blur_strength]"
                            value="<?php echo esc_attr( intval( $content['wpbody_content_blur_strength'] ?? 12 ) ); ?>"
                            min="0" 
                            max="30" 
                            step="2"
                            class="woow-slider"
                            data-type="unitless"
                            data-unit="px"
                        />
                        <span class="woow-slider-value">
                            <?php echo esc_html( $content['wpbody_content_blur_strength'] ?? 12 ); ?>px
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- WP List Table Card -->
    <div class="woow-card">
        <div class="woow-card-header">
            <div class="woow-card-icon">
                <span class="dashicons dashicons-list-view"></span>
            </div>
            <div>
                <h3 class="woow-card-title"><?php esc_html_e( 'Tables', 'woow-admin' ); ?></h3>
                <p class="woow-card-description"><?php esc_html_e( 'Customize tables (posts, pages, plugins)', 'woow-admin' ); ?></p>
            </div>
        </div>
        
        <div class="woow-card-body">
            <!-- Border Radius -->
            <div class="woow-form-group">
                <label class="woow-label">
                    <?php esc_html_e( 'Border Radius', 'woow-admin' ); ?>
                </label>
                <div class="woow-slider-group">
                    <input 
                        type="range" 
                        name="content_styling[wp_list_table_border_radius]"
                        value="<?php echo esc_attr( intval( $content['wp_list_table_border_radius'] ?? 12 ) ); ?>"
                        min="0" 
                        max="30" 
                        step="2"
                        class="woow-slider"
                        data-type="unitless"
                        data-unit="px"
                    />
                    <span class="woow-slider-value">
                        <?php echo esc_html( $content['wp_list_table_border_radius'] ?? 12 ); ?>px
                    </span>
                </div>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Border radius for tables. Default: 12px', 'woow-admin' ); ?>
                </p>
            </div>
        </div>
    </div>
</div>
