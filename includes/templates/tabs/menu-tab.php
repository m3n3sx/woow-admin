<?php
/**
 * Menu Styling Tab Template
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$menu = $this->settings->get_section( 'admin_menu' );
?>

<div class="woow-tab-pane" id="tab-menu">
    <div class="woow-section-header">
        <h2><?php esc_html_e( 'Admin Menu Styling', 'woow-admin' ); ?></h2>
        <p class="woow-section-description">
            <?php esc_html_e( 'Customize the sidebar menu with glassmorphism, gradients, and responsive behavior.', 'woow-admin' ); ?>
        </p>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Enable Menu Styling', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <label class="woow-toggle">
                <input type="checkbox" name="admin_menu[enabled]" value="1" <?php checked( $menu['enabled'], true ); ?> class="woow-toggle-input" />
                <span class="woow-toggle-slider"></span>
                <span class="woow-toggle-label"><?php esc_html_e( 'Apply custom styles to admin menu', 'woow-admin' ); ?></span>
            </label>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Menu Dimensions', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-row">
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Expanded Width', 'woow-admin' ); ?></label>
                    <div class="woow-slider-group">
                        <input type="range" name="admin_menu[width_expanded]" value="<?php echo esc_attr( intval( $menu['width_expanded'] ) ); ?>" min="160" max="320" step="8" class="woow-slider" data-unit="px" />
                        <span class="woow-slider-value"><?php echo esc_html( $menu['width_expanded'] ); ?></span>
                    </div>
                    <p class="woow-field-description"><?php esc_html_e( 'Default: 256px. Range: 160-320px', 'woow-admin' ); ?></p>
                </div>
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Collapsed Width', 'woow-admin' ); ?></label>
                    <div class="woow-slider-group">
                        <input type="range" name="admin_menu[width_collapsed]" value="<?php echo esc_attr( intval( $menu['width_collapsed'] ) ); ?>" min="60" max="100" step="4" class="woow-slider" data-unit="px" />
                        <span class="woow-slider-value"><?php echo esc_html( $menu['width_collapsed'] ); ?></span>
                    </div>
                    <p class="woow-field-description"><?php esc_html_e( 'Default: 80px. Range: 60-100px', 'woow-admin' ); ?></p>
                </div>
            </div>
            <div class="woow-form-group">
                <label class="woow-label"><?php esc_html_e( 'Menu Item Height', 'woow-admin' ); ?></label>
                <div class="woow-slider-group">
                    <input type="range" name="admin_menu[item_height]" value="<?php echo esc_attr( intval( $menu['item_height'] ) ); ?>" min="32" max="48" step="2" class="woow-slider" data-unit="px" />
                    <span class="woow-slider-value"><?php echo esc_html( $menu['item_height'] ); ?></span>
                </div>
                <p class="woow-field-description"><?php esc_html_e( 'Default: 40px. Range: 32-48px', 'woow-admin' ); ?></p>
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
                    <input type="color" name="admin_menu[background_color]" value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $menu['background_color'] ?? '#ffffff' ) ); ?>" class="woow-color-input" />
                    <input type="text" value="<?php echo esc_attr( $menu['background_color'] ?? '#ffffff' ); ?>" class="woow-color-text" />
                    <button type="button" class="woow-color-reset button">↺</button>
                </div>
            </div>
            <div class="woow-form-group">
                <label class="woow-label"><?php esc_html_e( 'Hover Background', 'woow-admin' ); ?></label>
                <div class="woow-color-picker-group">
                    <input type="color" name="admin_menu[hover_bg_color]" value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $menu['hover_bg_color'] ?? '#6366f1' ) ); ?>" class="woow-color-input" />
                    <input type="text" value="<?php echo esc_attr( $menu['hover_bg_color'] ?? 'rgba(99,102,241,0.05)' ); ?>" class="woow-color-text" />
                </div>
            </div>
            <div class="woow-form-row">
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Active Gradient Start', 'woow-admin' ); ?></label>
                    <div class="woow-color-picker-group">
                        <input type="color" name="admin_menu[active_gradient_start]" value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $menu['active_gradient_start'] ?? '#6366f1' ) ); ?>" class="woow-color-input" />
                        <input type="text" value="<?php echo esc_attr( $menu['active_gradient_start'] ?? '#6366f1' ); ?>" class="woow-color-text" />
                    </div>
                </div>
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Active Gradient End', 'woow-admin' ); ?></label>
                    <div class="woow-color-picker-group">
                        <input type="color" name="admin_menu[active_gradient_end]" value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $menu['active_gradient_end'] ?? '#8b5cf6' ) ); ?>" class="woow-color-input" />
                        <input type="text" value="<?php echo esc_attr( $menu['active_gradient_end'] ?? '#8b5cf6' ); ?>" class="woow-color-text" />
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
                    <input type="checkbox" name="admin_menu[glassmorphism]" value="1" <?php checked( $menu['glassmorphism'], true ); ?> class="woow-toggle-input" id="menu-glass-toggle" />
                    <span class="woow-toggle-slider"></span>
                    <span class="woow-toggle-label"><?php esc_html_e( 'Enable glass effect', 'woow-admin' ); ?></span>
                </label>
            </div>
            <div class="woow-form-group woow-conditional" data-show-when="#menu-glass-toggle:checked">
                <label class="woow-label"><?php esc_html_e( 'Blur Strength', 'woow-admin' ); ?></label>
                <div class="woow-slider-group">
                    <input type="range" name="admin_menu[blur_strength]" value="<?php echo esc_attr( intval( $menu['blur_strength'] ) ); ?>" min="0" max="24" step="2" class="woow-slider" data-unit="px" />
                    <span class="woow-slider-value"><?php echo esc_html( $menu['blur_strength'] ); ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Custom CSS', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <textarea name="admin_menu[custom_css]" class="woow-code-editor" rows="8" placeholder="/* Your custom CSS here */" spellcheck="false"><?php echo esc_textarea( $menu['custom_css'] ); ?></textarea>
        </div>
    </div>

    <div class="woow-card-footer">
        <button type="button" class="button woow-button-secondary woow-reset-section" data-section="admin_menu"><?php esc_html_e( 'Reset to Defaults', 'woow-admin' ); ?></button>
    </div>
</div>
