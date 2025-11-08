<?php
/**
 * Backgrounds Tab Template
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$backgrounds = $this->settings->get_section( 'backgrounds' );
?>

<div class="woow-tab-pane" id="tab-backgrounds">
    <div class="woow-section-header">
        <h2><?php esc_html_e( 'Background Customization', 'woow-admin' ); ?></h2>
        <p class="woow-section-description">
            <?php esc_html_e( 'Customize the admin panel background with colors, gradients, patterns, or images.', 'woow-admin' ); ?>
        </p>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Enable Background Styling', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <label class="woow-toggle">
                <input type="checkbox" name="backgrounds[enabled]" value="1" <?php checked( $backgrounds['enabled'], true ); ?> class="woow-toggle-input" />
                <span class="woow-toggle-slider"></span>
                <span class="woow-toggle-label"><?php esc_html_e( 'Apply custom background', 'woow-admin' ); ?></span>
            </label>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Background Type', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-group">
                <label class="woow-label"><?php esc_html_e( 'Type', 'woow-admin' ); ?></label>
                <select name="backgrounds[type]" class="woow-select" id="bg-type-select">
                    <option value="solid" <?php selected( $backgrounds['type'], 'solid' ); ?>><?php esc_html_e( 'Solid Color', 'woow-admin' ); ?></option>
                    <option value="gradient" <?php selected( $backgrounds['type'], 'gradient' ); ?>><?php esc_html_e( 'Gradient', 'woow-admin' ); ?></option>
                    <option value="pattern" <?php selected( $backgrounds['type'], 'pattern' ); ?>><?php esc_html_e( 'Pattern', 'woow-admin' ); ?></option>
                    <option value="image" <?php selected( $backgrounds['type'], 'image' ); ?>><?php esc_html_e( 'Image', 'woow-admin' ); ?></option>
                </select>
            </div>

            <!-- Solid Color -->
            <div class="woow-form-group woow-conditional" data-show-when="#bg-type-select=solid">
                <label class="woow-label"><?php esc_html_e( 'Background Color', 'woow-admin' ); ?></label>
                <div class="woow-color-picker-group">
                    <input type="color" name="backgrounds[solid_color]" value="<?php echo esc_attr( $backgrounds['solid_color'] ); ?>" class="woow-color-input" />
                    <input type="text" value="<?php echo esc_attr( $backgrounds['solid_color'] ); ?>" class="woow-color-text" />
                </div>
            </div>

            <!-- Gradient -->
            <div class="woow-conditional" data-show-when="#bg-type-select=gradient">
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Gradient Type', 'woow-admin' ); ?></label>
                    <select name="backgrounds[gradient_type]" class="woow-select">
                        <option value="linear" <?php selected( $backgrounds['gradient_type'], 'linear' ); ?>><?php esc_html_e( 'Linear', 'woow-admin' ); ?></option>
                        <option value="radial" <?php selected( $backgrounds['gradient_type'], 'radial' ); ?>><?php esc_html_e( 'Radial', 'woow-admin' ); ?></option>
                        <option value="conic" <?php selected( $backgrounds['gradient_type'], 'conic' ); ?>><?php esc_html_e( 'Conic', 'woow-admin' ); ?></option>
                    </select>
                </div>
                <div class="woow-form-row">
                    <div class="woow-form-group">
                        <label class="woow-label"><?php esc_html_e( 'Start Color', 'woow-admin' ); ?></label>
                        <div class="woow-color-picker-group">
                            <input type="color" name="backgrounds[gradient_start]" value="<?php echo esc_attr( $backgrounds['gradient_start'] ); ?>" class="woow-color-input" />
                            <input type="text" value="<?php echo esc_attr( $backgrounds['gradient_start'] ); ?>" class="woow-color-text" />
                        </div>
                    </div>
                    <div class="woow-form-group">
                        <label class="woow-label"><?php esc_html_e( 'End Color', 'woow-admin' ); ?></label>
                        <div class="woow-color-picker-group">
                            <input type="color" name="backgrounds[gradient_end]" value="<?php echo esc_attr( $backgrounds['gradient_end'] ); ?>" class="woow-color-input" />
                            <input type="text" value="<?php echo esc_attr( $backgrounds['gradient_end'] ); ?>" class="woow-color-text" />
                        </div>
                    </div>
                </div>
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Angle', 'woow-admin' ); ?></label>
                    <div class="woow-slider-group">
                        <input type="range" name="backgrounds[gradient_angle]" value="<?php echo esc_attr( intval( $backgrounds['gradient_angle'] ) ); ?>" min="0" max="360" step="15" class="woow-slider" data-unit="°" />
                        <span class="woow-slider-value"><?php echo esc_html( $backgrounds['gradient_angle'] ); ?>°</span>
                    </div>
                </div>
            </div>

            <!-- Image -->
            <div class="woow-conditional" data-show-when="#bg-type-select=image">
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Background Image', 'woow-admin' ); ?></label>
                    <div class="woow-image-upload">
                        <input type="hidden" name="backgrounds[image_url]" value="<?php echo esc_attr( $backgrounds['image_url'] ); ?>" id="bg-image-url" />
                        <button type="button" class="button woow-upload-image" data-target="#bg-image-url">
                            <?php esc_html_e( 'Upload Image', 'woow-admin' ); ?>
                        </button>
                        <?php if ( ! empty( $backgrounds['image_url'] ) ) : ?>
                            <img src="<?php echo esc_url( $backgrounds['image_url'] ); ?>" class="woow-image-preview" />
                        <?php endif; ?>
                    </div>
                </div>
                <div class="woow-form-row">
                    <div class="woow-form-group">
                        <label class="woow-label"><?php esc_html_e( 'Position', 'woow-admin' ); ?></label>
                        <select name="backgrounds[image_position]" class="woow-select">
                            <option value="center" <?php selected( $backgrounds['image_position'], 'center' ); ?>><?php esc_html_e( 'Center', 'woow-admin' ); ?></option>
                            <option value="top" <?php selected( $backgrounds['image_position'], 'top' ); ?>><?php esc_html_e( 'Top', 'woow-admin' ); ?></option>
                            <option value="bottom" <?php selected( $backgrounds['image_position'], 'bottom' ); ?>><?php esc_html_e( 'Bottom', 'woow-admin' ); ?></option>
                        </select>
                    </div>
                    <div class="woow-form-group">
                        <label class="woow-label"><?php esc_html_e( 'Size', 'woow-admin' ); ?></label>
                        <select name="backgrounds[image_size]" class="woow-select">
                            <option value="cover" <?php selected( $backgrounds['image_size'], 'cover' ); ?>><?php esc_html_e( 'Cover', 'woow-admin' ); ?></option>
                            <option value="contain" <?php selected( $backgrounds['image_size'], 'contain' ); ?>><?php esc_html_e( 'Contain', 'woow-admin' ); ?></option>
                            <option value="auto" <?php selected( $backgrounds['image_size'], 'auto' ); ?>><?php esc_html_e( 'Auto', 'woow-admin' ); ?></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Custom CSS', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <textarea name="backgrounds[custom_css]" class="woow-code-editor" rows="8" placeholder="/* Your custom CSS here */" spellcheck="false"><?php echo esc_textarea( $backgrounds['custom_css'] ); ?></textarea>
        </div>
    </div>

    <div class="woow-card-footer">
        <button type="button" class="button woow-button-secondary woow-reset-section" data-section="backgrounds"><?php esc_html_e( 'Reset to Defaults', 'woow-admin' ); ?></button>
    </div>
</div>
