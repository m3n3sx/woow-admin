<?php
/**
 * Universal Buttons Tab Template
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define default values for buttons
$defaults = array(
    'enabled'              => true,
    'height'               => '40px',
    'border_radius'        => '12px',
    'primary_bg'           => '#6366f1',
    'primary_text'         => '#ffffff',
    'secondary_border'     => '#e2e8f0',
    'secondary_text'       => '#6366f1',
    'destructive_bg'       => '#ef4444',
    'destructive_text'     => '#ffffff',
    'hover_scale'          => 1.02,
    'transition_duration'  => '200ms',
    'custom_css'           => '',
);

// Merge with saved settings
$buttons = array_merge( $defaults, $this->settings->get_section( 'buttons' ) ?? array() );
?>

<div class="woow-tab-pane" id="tab-buttons">
    <div class="woow-section-header">
        <h2><?php esc_html_e( 'Universal Buttons', 'woow-admin' ); ?></h2>
        <p class="woow-section-description">
            <?php esc_html_e( 'Customize all button styles including primary, secondary, and destructive variants.', 'woow-admin' ); ?>
        </p>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Enable Button Styling', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <label class="woow-toggle">
                <input type="checkbox" name="buttons[enabled]" value="1" <?php checked( $buttons['enabled'], true ); ?> class="woow-toggle-input" />
                <span class="woow-toggle-slider"></span>
                <span class="woow-toggle-label"><?php esc_html_e( 'Apply custom styles to buttons', 'woow-admin' ); ?></span>
            </label>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Button Dimensions', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-row">
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Height', 'woow-admin' ); ?></label>
                    <div class="woow-slider-group">
                        <input type="range" name="buttons[height]" value="<?php echo esc_attr( intval( $buttons['height'] ) ); ?>" min="32" max="48" step="2" class="woow-slider" data-unit="px" />
                        <span class="woow-slider-value"><?php echo esc_html( $buttons['height'] ); ?></span>
                    </div>
                </div>
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Border Radius', 'woow-admin' ); ?></label>
                    <div class="woow-slider-group">
                        <input type="range" name="buttons[border_radius]" value="<?php echo esc_attr( intval( $buttons['border_radius'] ) ); ?>" min="4" max="16" step="2" class="woow-slider" data-unit="px" />
                        <span class="woow-slider-value"><?php echo esc_html( $buttons['border_radius'] ); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Primary Button', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-row">
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Background', 'woow-admin' ); ?></label>
                    <div class="woow-color-picker-group">
                        <input 
                            type="color" 
                            name="buttons[primary_bg]" 
                            value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $buttons['primary_bg'] ?? '', '#6366f1' ) ); ?>"
                            data-default="#6366f1"
                            class="woow-color-input"
                        />
                        <input 
                            type="text" 
                            value="<?php echo esc_attr( $buttons['primary_bg'] ); ?>"
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
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Text', 'woow-admin' ); ?></label>
                    <div class="woow-color-picker-group">
                        <input 
                            type="color" 
                            name="buttons[primary_text]" 
                            value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $buttons['primary_text'] ?? '', '#ffffff' ) ); ?>"
                            data-default="#ffffff"
                            class="woow-color-input"
                        />
                        <input 
                            type="text" 
                            value="<?php echo esc_attr( $buttons['primary_text'] ); ?>"
                            class="woow-color-text"
                        />
                        <button type="button" class="woow-color-reset button" title="<?php esc_attr_e( 'Reset', 'woow-admin' ); ?>">
                            ↺
                        </button>
                    </div>
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Default: #ffffff (White)', 'woow-admin' ); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Secondary Button', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-row">
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Border', 'woow-admin' ); ?></label>
                    <div class="woow-color-picker-group">
                        <input 
                            type="color" 
                            name="buttons[secondary_border]" 
                            value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $buttons['secondary_border'] ?? '', '#e2e8f0' ) ); ?>"
                            data-default="#e2e8f0"
                            class="woow-color-input"
                        />
                        <input 
                            type="text" 
                            value="<?php echo esc_attr( $buttons['secondary_border'] ); ?>"
                            class="woow-color-text"
                        />
                        <button type="button" class="woow-color-reset button" title="<?php esc_attr_e( 'Reset', 'woow-admin' ); ?>">
                            ↺
                        </button>
                    </div>
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Default: #e2e8f0 (Gray 200)', 'woow-admin' ); ?>
                    </p>
                </div>
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Text', 'woow-admin' ); ?></label>
                    <div class="woow-color-picker-group">
                        <input 
                            type="color" 
                            name="buttons[secondary_text]" 
                            value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $buttons['secondary_text'] ?? '', '#6366f1' ) ); ?>"
                            data-default="#6366f1"
                            class="woow-color-input"
                        />
                        <input 
                            type="text" 
                            value="<?php echo esc_attr( $buttons['secondary_text'] ); ?>"
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
            <h3><?php esc_html_e( 'Destructive Button', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-row">
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Background', 'woow-admin' ); ?></label>
                    <div class="woow-color-picker-group">
                        <input 
                            type="color" 
                            name="buttons[destructive_bg]" 
                            value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $buttons['destructive_bg'] ?? '', '#ef4444' ) ); ?>"
                            data-default="#ef4444"
                            class="woow-color-input"
                        />
                        <input 
                            type="text" 
                            value="<?php echo esc_attr( $buttons['destructive_bg'] ); ?>"
                            class="woow-color-text"
                        />
                        <button type="button" class="woow-color-reset button" title="<?php esc_attr_e( 'Reset', 'woow-admin' ); ?>">
                            ↺
                        </button>
                    </div>
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Default: #ef4444 (Red 500)', 'woow-admin' ); ?>
                    </p>
                </div>
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Text', 'woow-admin' ); ?></label>
                    <div class="woow-color-picker-group">
                        <input 
                            type="color" 
                            name="buttons[destructive_text]" 
                            value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $buttons['destructive_text'] ?? '', '#ffffff' ) ); ?>"
                            data-default="#ffffff"
                            class="woow-color-input"
                        />
                        <input 
                            type="text" 
                            value="<?php echo esc_attr( $buttons['destructive_text'] ); ?>"
                            class="woow-color-text"
                        />
                        <button type="button" class="woow-color-reset button" title="<?php esc_attr_e( 'Reset', 'woow-admin' ); ?>">
                            ↺
                        </button>
                    </div>
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Default: #ffffff (White)', 'woow-admin' ); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Hover Effects', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-row">
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Scale', 'woow-admin' ); ?></label>
                    <div class="woow-slider-group">
                        <input type="range" name="buttons[hover_scale]" value="<?php echo esc_attr( floatval( $buttons['hover_scale'] ) * 100 ); ?>" min="100" max="110" step="1" class="woow-slider" data-unit="%" />
                        <span class="woow-slider-value"><?php echo esc_html( floatval( $buttons['hover_scale'] ) * 100 ); ?>%</span>
                    </div>
                    <p class="woow-field-description"><?php esc_html_e( 'Default: 102%. Button grows on hover', 'woow-admin' ); ?></p>
                </div>
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Transition Duration', 'woow-admin' ); ?></label>
                    <div class="woow-slider-group">
                        <input type="range" name="buttons[transition_duration]" value="<?php echo esc_attr( intval( $buttons['transition_duration'] ) ); ?>" min="100" max="500" step="50" class="woow-slider" data-unit="ms" />
                        <span class="woow-slider-value"><?php echo esc_html( $buttons['transition_duration'] ); ?></span>
                    </div>
                    <p class="woow-field-description"><?php esc_html_e( 'Default: 200ms', 'woow-admin' ); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Custom CSS', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <textarea name="buttons[custom_css]" class="woow-code-editor" rows="8" placeholder="/* Your custom CSS here */" spellcheck="false"><?php echo esc_textarea( $buttons['custom_css'] ); ?></textarea>
        </div>
    </div>

    <div class="woow-card-footer">
        <button type="button" class="button woow-button-secondary woow-reset-section" data-section="buttons"><?php esc_html_e( 'Reset to Defaults', 'woow-admin' ); ?></button>
    </div>
</div>
