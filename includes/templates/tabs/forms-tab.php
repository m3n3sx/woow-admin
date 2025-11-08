<?php
/**
 * Form Controls Tab Template
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$forms = $this->settings->get_section( 'form_controls' );
?>

<div class="woow-tab-pane" id="tab-forms">
    <div class="woow-section-header">
        <h2><?php esc_html_e( 'Form Controls', 'woow-admin' ); ?></h2>
        <p class="woow-section-description">
            <?php esc_html_e( 'Customize inputs, selects, textareas, checkboxes, and radio buttons.', 'woow-admin' ); ?>
        </p>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Enable Form Styling', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <label class="woow-toggle">
                <input type="checkbox" name="form_controls[enabled]" value="1" <?php checked( $forms['enabled'], true ); ?> class="woow-toggle-input" />
                <span class="woow-toggle-slider"></span>
                <span class="woow-toggle-label"><?php esc_html_e( 'Apply custom styles to form controls', 'woow-admin' ); ?></span>
            </label>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Input Dimensions', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-row">
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Input Height', 'woow-admin' ); ?></label>
                    <div class="woow-slider-group">
                        <input type="range" name="form_controls[input_height]" value="<?php echo esc_attr( intval( $forms['input_height'] ) ); ?>" min="32" max="48" step="2" class="woow-slider" data-unit="px" />
                        <span class="woow-slider-value"><?php echo esc_html( $forms['input_height'] ); ?></span>
                    </div>
                </div>
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Border Radius', 'woow-admin' ); ?></label>
                    <div class="woow-slider-group">
                        <input type="range" name="form_controls[border_radius]" value="<?php echo esc_attr( intval( $forms['border_radius'] ) ); ?>" min="4" max="16" step="2" class="woow-slider" data-unit="px" />
                        <span class="woow-slider-value"><?php echo esc_html( $forms['border_radius'] ); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Colors', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-row">
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Background', 'woow-admin' ); ?></label>
                    <div class="woow-color-picker-group">
                        <input type="color" name="form_controls[background_color]" value="<?php echo esc_attr( $forms['background_color'] ); ?>" class="woow-color-input" />
                        <input type="text" value="<?php echo esc_attr( $forms['background_color'] ); ?>" class="woow-color-text" />
                    </div>
                </div>
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Border', 'woow-admin' ); ?></label>
                    <div class="woow-color-picker-group">
                        <input type="color" name="form_controls[border_color]" value="<?php echo esc_attr( $forms['border_color'] ); ?>" class="woow-color-input" />
                        <input type="text" value="<?php echo esc_attr( $forms['border_color'] ); ?>" class="woow-color-text" />
                    </div>
                </div>
            </div>
            <div class="woow-form-row">
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Text', 'woow-admin' ); ?></label>
                    <div class="woow-color-picker-group">
                        <input type="color" name="form_controls[text_color]" value="<?php echo esc_attr( $forms['text_color'] ); ?>" class="woow-color-input" />
                        <input type="text" value="<?php echo esc_attr( $forms['text_color'] ); ?>" class="woow-color-text" />
                    </div>
                </div>
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Focus Ring', 'woow-admin' ); ?></label>
                    <div class="woow-color-picker-group">
                        <input type="color" name="form_controls[focus_ring_color]" value="<?php echo esc_attr( $forms['focus_ring_color'] ); ?>" class="woow-color-input" />
                        <input type="text" value="<?php echo esc_attr( $forms['focus_ring_color'] ); ?>" class="woow-color-text" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Glassmorphism', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-group">
                <label class="woow-toggle">
                    <input type="checkbox" name="form_controls[glassmorphism]" value="1" <?php checked( $forms['glassmorphism'], true ); ?> class="woow-toggle-input" id="forms-glass-toggle" />
                    <span class="woow-toggle-slider"></span>
                    <span class="woow-toggle-label"><?php esc_html_e( 'Enable glass effect for inputs', 'woow-admin' ); ?></span>
                </label>
            </div>
            <div class="woow-form-group woow-conditional" data-show-when="#forms-glass-toggle:checked">
                <label class="woow-label"><?php esc_html_e( 'Blur Strength', 'woow-admin' ); ?></label>
                <div class="woow-slider-group">
                    <input type="range" name="form_controls[blur_strength]" value="<?php echo esc_attr( intval( $forms['blur_strength'] ) ); ?>" min="0" max="24" step="2" class="woow-slider" data-unit="px" />
                    <span class="woow-slider-value"><?php echo esc_html( $forms['blur_strength'] ); ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Checkbox & Radio', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-group">
                <label class="woow-label"><?php esc_html_e( 'Size', 'woow-admin' ); ?></label>
                <div class="woow-slider-group">
                    <input type="range" name="form_controls[checkbox_size]" value="<?php echo esc_attr( intval( $forms['checkbox_size'] ) ); ?>" min="16" max="24" step="2" class="woow-slider" data-unit="px" />
                    <span class="woow-slider-value"><?php echo esc_html( $forms['checkbox_size'] ); ?></span>
                </div>
                <p class="woow-field-description"><?php esc_html_e( 'Default: 20px. Range: 16-24px', 'woow-admin' ); ?></p>
            </div>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Custom CSS', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <textarea name="form_controls[custom_css]" class="woow-code-editor" rows="8" placeholder="/* Your custom CSS here */" spellcheck="false"><?php echo esc_textarea( $forms['custom_css'] ); ?></textarea>
        </div>
    </div>

    <div class="woow-card-footer">
        <button type="button" class="button woow-button-secondary woow-reset-section" data-section="form_controls"><?php esc_html_e( 'Reset to Defaults', 'woow-admin' ); ?></button>
    </div>
</div>
