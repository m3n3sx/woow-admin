<?php
/**
 * Effects Tab Template
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define default values for effects
$defaults = array(
    'enabled'             => true,
    'shadow_preset'       => 'md',
    'animation_duration'  => '200ms',
    'easing_function'     => 'cubic-bezier(0.4, 0, 0.2, 1)',
    'glassmorphism_blur'  => '12px',
    'custom_css'          => '',
);

// Merge with saved settings
$effects = array_merge( $defaults, $this->settings->get_section( 'effects' ) ?? array() );
?>

<div class="woow-tab-pane" id="tab-effects">
    <div class="woow-section-header">
        <h2><?php esc_html_e( 'Visual Effects', 'woow-admin' ); ?></h2>
        <p class="woow-section-description">
            <?php esc_html_e( 'Configure shadows, animations, glassmorphism strength, and transition effects.', 'woow-admin' ); ?>
        </p>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Enable Effects', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <label class="woow-toggle">
                <input type="checkbox" name="effects[enabled]" value="1" <?php checked( $effects['enabled'], true ); ?> class="woow-toggle-input" />
                <span class="woow-toggle-slider"></span>
                <span class="woow-toggle-label"><?php esc_html_e( 'Apply visual effects', 'woow-admin' ); ?></span>
            </label>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Shadow Presets', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-group">
                <label class="woow-label"><?php esc_html_e( 'Default Shadow Style', 'woow-admin' ); ?></label>
                <div class="woow-shadow-picker">
                    <?php
                    $shadows = array( 'none' => __( 'None', 'woow-admin' ), 'sm' => __( 'Small', 'woow-admin' ), 'md' => __( 'Medium', 'woow-admin' ), 'lg' => __( 'Large', 'woow-admin' ), 'xl' => __( 'Extra Large', 'woow-admin' ) );
                    foreach ( $shadows as $value => $label ) :
                    ?>
                        <label class="woow-radio-card <?php echo $effects['shadow_preset'] === $value ? 'checked' : ''; ?>">
                            <input type="radio" name="effects[shadow_preset]" value="<?php echo esc_attr( $value ); ?>" <?php checked( $effects['shadow_preset'], $value ); ?> />
                            <span class="woow-radio-card-box woow-shadow-<?php echo esc_attr( $value ); ?>"></span>
                            <span class="woow-radio-card-label"><?php echo esc_html( $label ); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Animations', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-group">
                <label class="woow-label"><?php esc_html_e( 'Animation Duration', 'woow-admin' ); ?></label>
                <div class="woow-slider-group">
                    <input type="range" name="effects[animation_duration]" value="<?php echo esc_attr( intval( $effects['animation_duration'] ) ); ?>" min="100" max="500" step="50" class="woow-slider" data-unit="ms" />
                    <span class="woow-slider-value"><?php echo esc_html( $effects['animation_duration'] ); ?></span>
                </div>
                <p class="woow-field-description"><?php esc_html_e( 'Default: 200ms. Controls transition speed', 'woow-admin' ); ?></p>
            </div>
            <div class="woow-form-group">
                <label class="woow-label"><?php esc_html_e( 'Easing Function', 'woow-admin' ); ?></label>
                <select name="effects[easing_function]" class="woow-select">
                    <option value="ease" <?php selected( $effects['easing_function'], 'ease' ); ?>><?php esc_html_e( 'Ease', 'woow-admin' ); ?></option>
                    <option value="ease-in" <?php selected( $effects['easing_function'], 'ease-in' ); ?>><?php esc_html_e( 'Ease In', 'woow-admin' ); ?></option>
                    <option value="ease-out" <?php selected( $effects['easing_function'], 'ease-out' ); ?>><?php esc_html_e( 'Ease Out', 'woow-admin' ); ?></option>
                    <option value="ease-in-out" <?php selected( $effects['easing_function'], 'ease-in-out' ); ?>><?php esc_html_e( 'Ease In-Out', 'woow-admin' ); ?></option>
                    <option value="cubic-bezier(0.4, 0, 0.2, 1)" <?php selected( $effects['easing_function'], 'cubic-bezier(0.4, 0, 0.2, 1)' ); ?>><?php esc_html_e( 'Cubic Bezier (Smooth)', 'woow-admin' ); ?></option>
                </select>
            </div>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Glassmorphism', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-group">
                <label class="woow-label"><?php esc_html_e( 'Global Blur Strength', 'woow-admin' ); ?></label>
                <div class="woow-slider-group">
                    <input type="range" name="effects[glassmorphism_blur]" value="<?php echo esc_attr( intval( $effects['glassmorphism_blur'] ) ); ?>" min="0" max="24" step="2" class="woow-slider" data-unit="px" />
                    <span class="woow-slider-value"><?php echo esc_html( $effects['glassmorphism_blur'] ); ?></span>
                </div>
                <p class="woow-field-description"><?php esc_html_e( 'Default: 12px. Affects all glass elements', 'woow-admin' ); ?></p>
            </div>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Custom CSS', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <textarea name="effects[custom_css]" class="woow-code-editor" rows="8" placeholder="/* Your custom CSS here */" spellcheck="false"><?php echo esc_textarea( $effects['custom_css'] ); ?></textarea>
        </div>
    </div>

    <div class="woow-card-footer">
        <button type="button" class="button woow-button-secondary woow-reset-section" data-section="effects"><?php esc_html_e( 'Reset to Defaults', 'woow-admin' ); ?></button>
    </div>
</div>
