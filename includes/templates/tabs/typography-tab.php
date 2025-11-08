<?php
/**
 * Typography Tab Template
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$typography = $this->settings->get_section( 'typography' );
?>

<div class="woow-tab-pane" id="tab-typography">
    <div class="woow-section-header">
        <h2><?php esc_html_e( 'Typography', 'woow-admin' ); ?></h2>
        <p class="woow-section-description">
            <?php esc_html_e( 'Customize font sizes, weights, line heights, and letter spacing for all text elements.', 'woow-admin' ); ?>
        </p>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Enable Typography Styling', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <label class="woow-toggle">
                <input type="checkbox" name="typography[enabled]" value="1" <?php checked( $typography['enabled'], true ); ?> class="woow-toggle-input" />
                <span class="woow-toggle-slider"></span>
                <span class="woow-toggle-label"><?php esc_html_e( 'Apply custom typography', 'woow-admin' ); ?></span>
            </label>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Heading 1 (H1)', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-row">
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Font Size', 'woow-admin' ); ?></label>
                    <div class="woow-slider-group">
                        <input type="range" name="typography[h1_size]" value="<?php echo esc_attr( intval( $typography['h1_size'] ) ); ?>" min="24" max="48" step="2" class="woow-slider" data-unit="px" />
                        <span class="woow-slider-value"><?php echo esc_html( $typography['h1_size'] ); ?></span>
                    </div>
                </div>
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Font Weight', 'woow-admin' ); ?></label>
                    <select name="typography[h1_weight]" class="woow-select">
                        <option value="400" <?php selected( $typography['h1_weight'], '400' ); ?>><?php esc_html_e( 'Normal (400)', 'woow-admin' ); ?></option>
                        <option value="500" <?php selected( $typography['h1_weight'], '500' ); ?>><?php esc_html_e( 'Medium (500)', 'woow-admin' ); ?></option>
                        <option value="600" <?php selected( $typography['h1_weight'], '600' ); ?>><?php esc_html_e( 'Semibold (600)', 'woow-admin' ); ?></option>
                        <option value="700" <?php selected( $typography['h1_weight'], '700' ); ?>><?php esc_html_e( 'Bold (700)', 'woow-admin' ); ?></option>
                    </select>
                </div>
            </div>
            <div class="woow-form-group">
                <label class="woow-label"><?php esc_html_e( 'Line Height', 'woow-admin' ); ?></label>
                <div class="woow-slider-group">
                    <input type="range" name="typography[h1_line_height]" value="<?php echo esc_attr( floatval( $typography['h1_line_height'] ) * 10 ); ?>" min="12" max="20" step="1" class="woow-slider" data-unit="" />
                    <span class="woow-slider-value"><?php echo esc_html( $typography['h1_line_height'] ); ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Heading 2 (H2)', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-row">
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Font Size', 'woow-admin' ); ?></label>
                    <div class="woow-slider-group">
                        <input type="range" name="typography[h2_size]" value="<?php echo esc_attr( intval( $typography['h2_size'] ) ); ?>" min="20" max="36" step="2" class="woow-slider" data-unit="px" />
                        <span class="woow-slider-value"><?php echo esc_html( $typography['h2_size'] ); ?></span>
                    </div>
                </div>
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Font Weight', 'woow-admin' ); ?></label>
                    <select name="typography[h2_weight]" class="woow-select">
                        <option value="400" <?php selected( $typography['h2_weight'], '400' ); ?>><?php esc_html_e( 'Normal (400)', 'woow-admin' ); ?></option>
                        <option value="500" <?php selected( $typography['h2_weight'], '500' ); ?>><?php esc_html_e( 'Medium (500)', 'woow-admin' ); ?></option>
                        <option value="600" <?php selected( $typography['h2_weight'], '600' ); ?>><?php esc_html_e( 'Semibold (600)', 'woow-admin' ); ?></option>
                        <option value="700" <?php selected( $typography['h2_weight'], '700' ); ?>><?php esc_html_e( 'Bold (700)', 'woow-admin' ); ?></option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Heading 3 (H3)', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-row">
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Font Size', 'woow-admin' ); ?></label>
                    <div class="woow-slider-group">
                        <input type="range" name="typography[h3_size]" value="<?php echo esc_attr( intval( $typography['h3_size'] ) ); ?>" min="16" max="28" step="2" class="woow-slider" data-unit="px" />
                        <span class="woow-slider-value"><?php echo esc_html( $typography['h3_size'] ); ?></span>
                    </div>
                </div>
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Font Weight', 'woow-admin' ); ?></label>
                    <select name="typography[h3_weight]" class="woow-select">
                        <option value="400" <?php selected( $typography['h3_weight'], '400' ); ?>><?php esc_html_e( 'Normal (400)', 'woow-admin' ); ?></option>
                        <option value="500" <?php selected( $typography['h3_weight'], '500' ); ?>><?php esc_html_e( 'Medium (500)', 'woow-admin' ); ?></option>
                        <option value="600" <?php selected( $typography['h3_weight'], '600' ); ?>><?php esc_html_e( 'Semibold (600)', 'woow-admin' ); ?></option>
                        <option value="700" <?php selected( $typography['h3_weight'], '700' ); ?>><?php esc_html_e( 'Bold (700)', 'woow-admin' ); ?></option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Body Text', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-row">
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Font Size', 'woow-admin' ); ?></label>
                    <div class="woow-slider-group">
                        <input type="range" name="typography[body_size]" value="<?php echo esc_attr( intval( $typography['body_size'] ) ); ?>" min="12" max="18" step="1" class="woow-slider" data-unit="px" />
                        <span class="woow-slider-value"><?php echo esc_html( $typography['body_size'] ); ?></span>
                    </div>
                </div>
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Line Height', 'woow-admin' ); ?></label>
                    <div class="woow-slider-group">
                        <input type="range" name="typography[body_line_height]" value="<?php echo esc_attr( floatval( $typography['body_line_height'] ) * 10 ); ?>" min="12" max="20" step="1" class="woow-slider" data-unit="" />
                        <span class="woow-slider-value"><?php echo esc_html( $typography['body_line_height'] ); ?></span>
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
            <textarea name="typography[custom_css]" class="woow-code-editor" rows="8" placeholder="/* Your custom CSS here */" spellcheck="false"><?php echo esc_textarea( $typography['custom_css'] ); ?></textarea>
        </div>
    </div>

    <div class="woow-card-footer">
        <button type="button" class="button woow-button-secondary woow-reset-section" data-section="typography"><?php esc_html_e( 'Reset to Defaults', 'woow-admin' ); ?></button>
    </div>
</div>
