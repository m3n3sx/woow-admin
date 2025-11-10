<?php
/**
 * Login Page Tab Template
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define default values for login page
$defaults = array(
    'enabled'                => false,
    'logo_url'               => '',
    'background_type'        => 'gradient',
    'background_color'       => '#f8fafc',
    'gradient_start'         => '#6366f1',
    'gradient_end'           => '#8b5cf6',
    'background_image'       => '',
    'form_glassmorphism'     => true,
    'blur_strength'          => '12px',
    'inherit_button_styles'  => true,
    'inherit_input_styles'   => true,
    'custom_css'             => '',
);

// Merge with saved settings
$login = array_merge( $defaults, $this->settings->get_section( 'login_page' ) ?? array() );
?>

<div class="woow-tab-pane" id="tab-login">
    <div class="woow-section-header">
        <h2><?php esc_html_e( 'Login Page Customization', 'woow-admin' ); ?></h2>
        <p class="woow-section-description">
            <?php esc_html_e( 'Customize the WordPress login page with your branding and styling.', 'woow-admin' ); ?>
        </p>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Enable Login Page Styling', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <label class="woow-toggle">
                <input type="checkbox" name="login_page[enabled]" value="1" <?php checked( $login['enabled'], true ); ?> class="woow-toggle-input" />
                <span class="woow-toggle-slider"></span>
                <span class="woow-toggle-label"><?php esc_html_e( 'Apply custom styles to login page', 'woow-admin' ); ?></span>
            </label>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Logo', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-group">
                <label class="woow-label"><?php esc_html_e( 'Custom Logo', 'woow-admin' ); ?></label>
                <div class="woow-image-upload">
                    <input type="hidden" name="login_page[logo_url]" value="<?php echo esc_attr( $login['logo_url'] ); ?>" id="login-logo-url" />
                    <button type="button" class="button woow-upload-image" data-target="#login-logo-url">
                        <?php esc_html_e( 'Upload Logo', 'woow-admin' ); ?>
                    </button>
                    <?php if ( ! empty( $login['logo_url'] ) ) : ?>
                        <img src="<?php echo esc_url( $login['logo_url'] ); ?>" class="woow-image-preview" style="max-width: 320px; max-height: 240px;" />
                    <?php endif; ?>
                </div>
                <p class="woow-field-description"><?php esc_html_e( 'Maximum dimensions: 320x240px', 'woow-admin' ); ?></p>
            </div>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Background', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-group">
                <label class="woow-label"><?php esc_html_e( 'Background Type', 'woow-admin' ); ?></label>
                <select name="login_page[background_type]" class="woow-select" id="login-bg-type">
                    <option value="color" <?php selected( $login['background_type'], 'color' ); ?>><?php esc_html_e( 'Solid Color', 'woow-admin' ); ?></option>
                    <option value="gradient" <?php selected( $login['background_type'], 'gradient' ); ?>><?php esc_html_e( 'Gradient', 'woow-admin' ); ?></option>
                    <option value="image" <?php selected( $login['background_type'], 'image' ); ?>><?php esc_html_e( 'Image', 'woow-admin' ); ?></option>
                </select>
            </div>

            <div class="woow-form-group woow-conditional" data-show-when="#login-bg-type=color">
                <label class="woow-label"><?php esc_html_e( 'Background Color', 'woow-admin' ); ?></label>
                <div class="woow-color-picker-group">
                    <input 
                        type="color" 
                        name="login_page[background_color]" 
                        value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $login['background_color'] ?? '', '#f8fafc' ) ); ?>"
                        data-default="#f8fafc"
                        class="woow-color-input"
                    />
                    <input 
                        type="text" 
                        value="<?php echo esc_attr( $login['background_color'] ); ?>"
                        class="woow-color-text"
                    />
                    <button type="button" class="woow-color-reset button" title="<?php esc_attr_e( 'Reset', 'woow-admin' ); ?>">
                        ↺
                    </button>
                </div>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Default: #f8fafc (Slate 50)', 'woow-admin' ); ?>
                </p>
            </div>

            <div class="woow-conditional" data-show-when="#login-bg-type=gradient">
                <div class="woow-form-row">
                    <div class="woow-form-group">
                        <label class="woow-label"><?php esc_html_e( 'Start Color', 'woow-admin' ); ?></label>
                        <div class="woow-color-picker-group">
                            <input 
                                type="color" 
                                name="login_page[gradient_start]" 
                                value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $login['gradient_start'] ?? '', '#6366f1' ) ); ?>"
                                data-default="#6366f1"
                                class="woow-color-input"
                            />
                            <input 
                                type="text" 
                                value="<?php echo esc_attr( $login['gradient_start'] ); ?>"
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
                        <label class="woow-label"><?php esc_html_e( 'End Color', 'woow-admin' ); ?></label>
                        <div class="woow-color-picker-group">
                            <input 
                                type="color" 
                                name="login_page[gradient_end]" 
                                value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $login['gradient_end'] ?? '', '#8b5cf6' ) ); ?>"
                                data-default="#8b5cf6"
                                class="woow-color-input"
                            />
                            <input 
                                type="text" 
                                value="<?php echo esc_attr( $login['gradient_end'] ); ?>"
                                class="woow-color-text"
                            />
                            <button type="button" class="woow-color-reset button" title="<?php esc_attr_e( 'Reset', 'woow-admin' ); ?>">
                                ↺
                            </button>
                        </div>
                        <p class="woow-field-description">
                            <?php esc_html_e( 'Default: #8b5cf6 (Purple 500)', 'woow-admin' ); ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="woow-conditional" data-show-when="#login-bg-type=image">
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Background Image', 'woow-admin' ); ?></label>
                    <div class="woow-image-upload">
                        <input type="hidden" name="login_page[background_image]" value="<?php echo esc_attr( $login['background_image'] ); ?>" id="login-bg-image" />
                        <button type="button" class="button woow-upload-image" data-target="#login-bg-image">
                            <?php esc_html_e( 'Upload Image', 'woow-admin' ); ?>
                        </button>
                        <?php if ( ! empty( $login['background_image'] ) ) : ?>
                            <img src="<?php echo esc_url( $login['background_image'] ); ?>" class="woow-image-preview" />
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Form Styling', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-group">
                <label class="woow-toggle">
                    <input type="checkbox" name="login_page[form_glassmorphism]" value="1" <?php checked( $login['form_glassmorphism'], true ); ?> class="woow-toggle-input" id="login-glass-toggle" />
                    <span class="woow-toggle-slider"></span>
                    <span class="woow-toggle-label"><?php esc_html_e( 'Enable glassmorphism for login form', 'woow-admin' ); ?></span>
                </label>
            </div>
            <div class="woow-form-group woow-conditional" data-show-when="#login-glass-toggle:checked">
                <label class="woow-label"><?php esc_html_e( 'Blur Strength', 'woow-admin' ); ?></label>
                <div class="woow-slider-group">
                    <input type="range" name="login_page[blur_strength]" value="<?php echo esc_attr( intval( $login['blur_strength'] ) ); ?>" min="0" max="24" step="2" class="woow-slider" data-unit="px" />
                    <span class="woow-slider-value"><?php echo esc_html( $login['blur_strength'] ); ?></span>
                </div>
            </div>
            <div class="woow-form-group">
                <label class="woow-toggle">
                    <input type="checkbox" name="login_page[inherit_button_styles]" value="1" <?php checked( $login['inherit_button_styles'], true ); ?> class="woow-toggle-input" />
                    <span class="woow-toggle-slider"></span>
                    <span class="woow-toggle-label"><?php esc_html_e( 'Inherit button styles from Universal Buttons tab', 'woow-admin' ); ?></span>
                </label>
            </div>
            <div class="woow-form-group">
                <label class="woow-toggle">
                    <input type="checkbox" name="login_page[inherit_input_styles]" value="1" <?php checked( $login['inherit_input_styles'], true ); ?> class="woow-toggle-input" />
                    <span class="woow-toggle-slider"></span>
                    <span class="woow-toggle-label"><?php esc_html_e( 'Inherit input styles from Form Controls tab', 'woow-admin' ); ?></span>
                </label>
            </div>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Custom CSS', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <textarea name="login_page[custom_css]" class="woow-code-editor" rows="8" placeholder="/* Your custom CSS here */" spellcheck="false"><?php echo esc_textarea( $login['custom_css'] ); ?></textarea>
        </div>
    </div>

    <div class="woow-card-footer">
        <button type="button" class="button woow-button-secondary woow-reset-section" data-section="login_page"><?php esc_html_e( 'Reset to Defaults', 'woow-admin' ); ?></button>
    </div>
</div>
