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

// Define default values for typography
$defaults = array(
    'enabled'          => true,
    'h1_size'          => '32px',
    'h1_weight'        => '700',
    'h1_line_height'   => 1.3,
    'h1_color'         => '#0f172a',
    'h2_size'          => '24px',
    'h2_weight'        => '700',
    'h2_line_height'   => 1.3,
    'h2_color'         => '#0f172a',
    'h3_size'          => '20px',
    'h3_weight'        => '600',
    'h3_line_height'   => 1.4,
    'h3_color'         => '#0f172a',
    'body_size'        => '14px',
    'body_line_height' => 1.6,
    'body_color'       => '#475569',
    'link_color'       => '#6366f1',
    'custom_css'       => '',
);

// Merge with saved settings
$typography = array_merge( $defaults, $this->settings->get_section( 'typography' ) ?? array() );
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
            <div class="woow-form-row">
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Line Height', 'woow-admin' ); ?></label>
                    <input 
                        type="number" 
                        name="typography[h1_line_height]" 
                        value="<?php echo esc_attr( floatval( $typography['h1_line_height'] ) ); ?>"
                        min="1.0" 
                        max="3.0" 
                        step="0.1"
                        class="woow-input"
                        data-type="unitless"
                    />
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Default: 1.3 (unitless)', 'woow-admin' ); ?>
                    </p>
                </div>
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Text Color', 'woow-admin' ); ?></label>
                    <div class="woow-color-picker-group">
                        <input 
                            type="color" 
                            name="typography[h1_color]" 
                            value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $typography['h1_color'] ?? '', '#0f172a' ) ); ?>"
                            data-default="#0f172a"
                            class="woow-color-input"
                        />
                        <input 
                            type="text" 
                            value="<?php echo esc_attr( $typography['h1_color'] ); ?>"
                            class="woow-color-text"
                        />
                        <button type="button" class="woow-color-reset button" title="<?php esc_attr_e( 'Reset', 'woow-admin' ); ?>">
                            ↺
                        </button>
                    </div>
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Default: #0f172a (Slate 900)', 'woow-admin' ); ?>
                    </p>
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
            <div class="woow-form-row">
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Line Height', 'woow-admin' ); ?></label>
                    <input 
                        type="number" 
                        name="typography[h2_line_height]" 
                        value="<?php echo esc_attr( floatval( $typography['h2_line_height'] ) ); ?>"
                        min="1.0" 
                        max="3.0" 
                        step="0.1"
                        class="woow-input"
                        data-type="unitless"
                    />
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Default: 1.3 (unitless)', 'woow-admin' ); ?>
                    </p>
                </div>
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Text Color', 'woow-admin' ); ?></label>
                    <div class="woow-color-picker-group">
                        <input 
                            type="color" 
                            name="typography[h2_color]" 
                            value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $typography['h2_color'] ?? '', '#0f172a' ) ); ?>"
                            data-default="#0f172a"
                            class="woow-color-input"
                        />
                        <input 
                            type="text" 
                            value="<?php echo esc_attr( $typography['h2_color'] ); ?>"
                            class="woow-color-text"
                        />
                        <button type="button" class="woow-color-reset button" title="<?php esc_attr_e( 'Reset', 'woow-admin' ); ?>">
                            ↺
                        </button>
                    </div>
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Default: #0f172a (Slate 900)', 'woow-admin' ); ?>
                    </p>
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
            <div class="woow-form-row">
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Line Height', 'woow-admin' ); ?></label>
                    <input 
                        type="number" 
                        name="typography[h3_line_height]" 
                        value="<?php echo esc_attr( floatval( $typography['h3_line_height'] ) ); ?>"
                        min="1.0" 
                        max="3.0" 
                        step="0.1"
                        class="woow-input"
                        data-type="unitless"
                    />
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Default: 1.4 (unitless)', 'woow-admin' ); ?>
                    </p>
                </div>
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Text Color', 'woow-admin' ); ?></label>
                    <div class="woow-color-picker-group">
                        <input 
                            type="color" 
                            name="typography[h3_color]" 
                            value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $typography['h3_color'] ?? '', '#0f172a' ) ); ?>"
                            data-default="#0f172a"
                            class="woow-color-input"
                        />
                        <input 
                            type="text" 
                            value="<?php echo esc_attr( $typography['h3_color'] ); ?>"
                            class="woow-color-text"
                        />
                        <button type="button" class="woow-color-reset button" title="<?php esc_attr_e( 'Reset', 'woow-admin' ); ?>">
                            ↺
                        </button>
                    </div>
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Default: #0f172a (Slate 900)', 'woow-admin' ); ?>
                    </p>
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
                    <input 
                        type="number" 
                        name="typography[body_line_height]" 
                        value="<?php echo esc_attr( floatval( $typography['body_line_height'] ) ); ?>"
                        min="1.0" 
                        max="3.0" 
                        step="0.1"
                        class="woow-input"
                        data-type="unitless"
                    />
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Default: 1.6 (unitless)', 'woow-admin' ); ?>
                    </p>
                </div>
            </div>
            <div class="woow-form-row">
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Body Text Color', 'woow-admin' ); ?></label>
                    <div class="woow-color-picker-group">
                        <input 
                            type="color" 
                            name="typography[body_color]" 
                            value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $typography['body_color'] ?? '', '#475569' ) ); ?>"
                            data-default="#475569"
                            class="woow-color-input"
                        />
                        <input 
                            type="text" 
                            value="<?php echo esc_attr( $typography['body_color'] ); ?>"
                            class="woow-color-text"
                        />
                        <button type="button" class="woow-color-reset button" title="<?php esc_attr_e( 'Reset', 'woow-admin' ); ?>">
                            ↺
                        </button>
                    </div>
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Default: #475569 (Slate 600)', 'woow-admin' ); ?>
                    </p>
                </div>
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Link Color', 'woow-admin' ); ?></label>
                    <div class="woow-color-picker-group">
                        <input 
                            type="color" 
                            name="typography[link_color]" 
                            value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $typography['link_color'] ?? '', '#6366f1' ) ); ?>"
                            data-default="#6366f1"
                            class="woow-color-input"
                        />
                        <input 
                            type="text" 
                            value="<?php echo esc_attr( $typography['link_color'] ); ?>"
                            class="woow-color-text"
                        />
                        <button type="button" class="woow-color-reset button" title="<?php esc_attr_e( 'Reset', 'woow-admin' ); ?>">
                            ↺
                        </button>
                    </div>
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Default: #6366f1 (Indigo 500)', 'woow-admin' ); ?>
                    </p>
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
