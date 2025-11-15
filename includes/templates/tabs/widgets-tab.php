<?php
/**
 * Dashboard Widgets Tab Template
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define default values for dashboard widgets
$defaults = array(
    'enabled'              => true,
    'border_radius'        => '24px',
    'padding'              => '24px',
    'background_color'     => '#ffffff',
    'border_color'         => '#e2e8f0',
    'text_color'           => '#0f172a',
    'heading_color'        => '#0f172a',
    'shadow_style'         => 'md',
    'hover_effects'        => true,
    'glassmorphism'        => true,
    'blur_strength'        => '8px',
    'header_font_size'     => '20px',
    'header_font_weight'   => '600',
    'custom_css'           => '',
);

// Merge with saved settings
$widgets = array_merge( $defaults, $this->settings->get_section( 'dashboard_widgets' ) ?? array() );
?>

<div class="woow-tab-pane" id="tab-widgets">
    <div class="woow-section-header">
        <h2><?php esc_html_e( 'Dashboard Widgets', 'woow-admin' ); ?></h2>
        <p class="woow-section-description">
            <?php esc_html_e( 'Customize dashboard widget cards with glassmorphism, shadows, and hover effects.', 'woow-admin' ); ?>
        </p>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Enable Widget Styling', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <label class="woow-toggle">
                <input type="checkbox" name="dashboard_widgets[enabled]" value="1" <?php checked( $widgets['enabled'], true ); ?> class="woow-toggle-input" />
                <span class="woow-toggle-slider"></span>
                <span class="woow-toggle-label"><?php esc_html_e( 'Apply custom styles to dashboard widgets', 'woow-admin' ); ?></span>
            </label>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Widget Dimensions', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-row">
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Border Radius', 'woow-admin' ); ?></label>
                    <div class="woow-slider-group">
                        <input type="range" name="dashboard_widgets[border_radius]" value="<?php echo esc_attr( intval( $widgets['border_radius'] ) ); ?>" min="8" max="32" step="2" class="woow-slider" data-unit="px" />
                        <span class="woow-slider-value"><?php echo esc_html( $widgets['border_radius'] ); ?></span>
                    </div>
                    <p class="woow-field-description"><?php esc_html_e( 'Default: 24px. Range: 8-32px', 'woow-admin' ); ?></p>
                </div>
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Padding', 'woow-admin' ); ?></label>
                    <div class="woow-slider-group">
                        <input type="range" name="dashboard_widgets[padding]" value="<?php echo esc_attr( intval( $widgets['padding'] ) ); ?>" min="12" max="48" step="4" class="woow-slider" data-unit="px" />
                        <span class="woow-slider-value"><?php echo esc_html( $widgets['padding'] ); ?></span>
                    </div>
                    <p class="woow-field-description"><?php esc_html_e( 'Default: 24px. Range: 12-48px', 'woow-admin' ); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Colors', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-group">
                <label class="woow-label"><?php esc_html_e( 'Background Color', 'woow-admin' ); ?></label>
                <div class="woow-color-picker-group">
                    <input 
                        type="color" 
                        name="dashboard_widgets[background_color]" 
                        value="<?php echo esc_attr( $widgets['background_color'] ?? '#ffffff' ); ?>"
                        class="woow-color-input"
                    />
                    <input 
                        type="text" 
                        name="dashboard_widgets[background_color]"
                        value="<?php echo esc_attr( $widgets['background_color'] ?? '#ffffff' ); ?>"
                        class="woow-color-text"
                        placeholder="#ffffff"
                    />
                    <button type="button" class="woow-color-reset button" data-default="#ffffff" title="<?php esc_attr_e( 'Reset', 'woow-admin' ); ?>">
                        ↺
                    </button>
                </div>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Default: #ffffff (White)', 'woow-admin' ); ?>
                </p>
            </div>
            
            <div class="woow-form-group">
                <label class="woow-label"><?php esc_html_e( 'Border Color', 'woow-admin' ); ?></label>
                <div class="woow-color-picker-group">
                    <input 
                        type="color" 
                        name="dashboard_widgets[border_color]" 
                        value="<?php echo esc_attr( $widgets['border_color'] ?? '#e2e8f0' ); ?>"
                        class="woow-color-input"
                    />
                    <input 
                        type="text" 
                        name="dashboard_widgets[border_color]"
                        value="<?php echo esc_attr( $widgets['border_color'] ?? '#e2e8f0' ); ?>"
                        class="woow-color-text"
                        placeholder="#e2e8f0"
                    />
                    <button type="button" class="woow-color-reset button" data-default="#e2e8f0" title="<?php esc_attr_e( 'Reset', 'woow-admin' ); ?>">
                        ↺
                    </button>
                </div>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Default: #e2e8f0 (Light gray)', 'woow-admin' ); ?>
                </p>
            </div>
            
            <div class="woow-form-group">
                <label class="woow-label"><?php esc_html_e( 'Text Color', 'woow-admin' ); ?></label>
                <div class="woow-color-picker-group">
                    <input 
                        type="color" 
                        name="dashboard_widgets[text_color]" 
                        value="<?php echo esc_attr( $widgets['text_color'] ?? '#0f172a' ); ?>"
                        class="woow-color-input"
                    />
                    <input 
                        type="text" 
                        name="dashboard_widgets[text_color]"
                        value="<?php echo esc_attr( $widgets['text_color'] ?? '#0f172a' ); ?>"
                        class="woow-color-text"
                        placeholder="#0f172a"
                    />
                    <button type="button" class="woow-color-reset button" data-default="#0f172a" title="<?php esc_attr_e( 'Reset', 'woow-admin' ); ?>">
                        ↺
                    </button>
                </div>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Default: #0f172a (Dark gray)', 'woow-admin' ); ?>
                </p>
            </div>
            
            <div class="woow-form-group">
                <label class="woow-label"><?php esc_html_e( 'Heading Color', 'woow-admin' ); ?></label>
                <div class="woow-color-picker-group">
                    <input 
                        type="color" 
                        name="dashboard_widgets[heading_color]" 
                        value="<?php echo esc_attr( $widgets['heading_color'] ?? '#0f172a' ); ?>"
                        class="woow-color-input"
                    />
                    <input 
                        type="text" 
                        name="dashboard_widgets[heading_color]"
                        value="<?php echo esc_attr( $widgets['heading_color'] ?? '#0f172a' ); ?>"
                        class="woow-color-text"
                        placeholder="#0f172a"
                    />
                    <button type="button" class="woow-color-reset button" data-default="#0f172a" title="<?php esc_attr_e( 'Reset', 'woow-admin' ); ?>">
                        ↺
                    </button>
                </div>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Default: #0f172a (Dark gray)', 'woow-admin' ); ?>
                </p>
            </div>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Shadow & Effects', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-group">
                <label class="woow-label"><?php esc_html_e( 'Shadow Style', 'woow-admin' ); ?></label>
                <div class="woow-shadow-picker">
                    <?php
                    $shadows = array( 'none' => __( 'None', 'woow-admin' ), 'sm' => __( 'Small', 'woow-admin' ), 'md' => __( 'Medium', 'woow-admin' ), 'lg' => __( 'Large', 'woow-admin' ), 'xl' => __( 'Extra Large', 'woow-admin' ) );
                    foreach ( $shadows as $value => $label ) :
                    ?>
                        <label class="woow-radio-card <?php echo $widgets['shadow_style'] === $value ? 'checked' : ''; ?>">
                            <input type="radio" name="dashboard_widgets[shadow_style]" value="<?php echo esc_attr( $value ); ?>" <?php checked( $widgets['shadow_style'], $value ); ?> />
                            <span class="woow-radio-card-box woow-shadow-<?php echo esc_attr( $value ); ?>"></span>
                            <span class="woow-radio-card-label"><?php echo esc_html( $label ); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="woow-form-group">
                <label class="woow-toggle">
                    <input type="checkbox" name="dashboard_widgets[hover_effects]" value="1" <?php checked( $widgets['hover_effects'], true ); ?> class="woow-toggle-input" />
                    <span class="woow-toggle-slider"></span>
                    <span class="woow-toggle-label"><?php esc_html_e( 'Enable hover effects (lift & shadow)', 'woow-admin' ); ?></span>
                </label>
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
                    <input type="checkbox" name="dashboard_widgets[glassmorphism]" value="1" <?php checked( $widgets['glassmorphism'], true ); ?> class="woow-toggle-input" id="widgets-glass-toggle" />
                    <span class="woow-toggle-slider"></span>
                    <span class="woow-toggle-label"><?php esc_html_e( 'Enable glass effect', 'woow-admin' ); ?></span>
                </label>
            </div>
            <div class="woow-form-group woow-conditional" data-show-when="#widgets-glass-toggle:checked">
                <label class="woow-label"><?php esc_html_e( 'Blur Strength', 'woow-admin' ); ?></label>
                <div class="woow-slider-group">
                    <input type="range" name="dashboard_widgets[blur_strength]" value="<?php echo esc_attr( intval( $widgets['blur_strength'] ) ); ?>" min="0" max="24" step="2" class="woow-slider" data-unit="px" />
                    <span class="woow-slider-value"><?php echo esc_html( $widgets['blur_strength'] ); ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Header Typography', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-row">
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Font Size', 'woow-admin' ); ?></label>
                    <div class="woow-slider-group">
                        <input type="range" name="dashboard_widgets[header_font_size]" value="<?php echo esc_attr( intval( $widgets['header_font_size'] ) ); ?>" min="16" max="28" step="2" class="woow-slider" data-unit="px" />
                        <span class="woow-slider-value"><?php echo esc_html( $widgets['header_font_size'] ); ?></span>
                    </div>
                </div>
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Font Weight', 'woow-admin' ); ?></label>
                    <select name="dashboard_widgets[header_font_weight]" class="woow-select">
                        <option value="400" <?php selected( $widgets['header_font_weight'], '400' ); ?>><?php esc_html_e( 'Normal (400)', 'woow-admin' ); ?></option>
                        <option value="500" <?php selected( $widgets['header_font_weight'], '500' ); ?>><?php esc_html_e( 'Medium (500)', 'woow-admin' ); ?></option>
                        <option value="600" <?php selected( $widgets['header_font_weight'], '600' ); ?>><?php esc_html_e( 'Semibold (600)', 'woow-admin' ); ?></option>
                        <option value="700" <?php selected( $widgets['header_font_weight'], '700' ); ?>><?php esc_html_e( 'Bold (700)', 'woow-admin' ); ?></option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Custom CSS', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <textarea name="dashboard_widgets[custom_css]" class="woow-code-editor" rows="8" placeholder="/* Your custom CSS here */" spellcheck="false"><?php echo esc_textarea( $widgets['custom_css'] ); ?></textarea>
        </div>
    </div>

    <div class="woow-card-footer">
        <button type="button" class="button woow-button-secondary woow-reset-section" data-section="dashboard_widgets"><?php esc_html_e( 'Reset to Defaults', 'woow-admin' ); ?></button>
    </div>
</div>
