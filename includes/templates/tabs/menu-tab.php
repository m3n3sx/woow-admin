<?php
/**
 * Admin Menu Styling Tab Template
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define default values for admin menu
$defaults = array(
    'enabled'               => true,
    
    // Background
    'background_type'       => 'solid',
    'background_color'      => '#ffffff',
    'gradient_start'        => '#ffffff',
    'gradient_end'          => '#f8fafc',
    
    // Text
    'text_color'            => '#0f172a',
    'hover_text_color'      => '#6366f1',
    
    // Hover
    'hover_bg_color'        => '#f8fafc',
    'hover_style'           => 'normal',
    
    // Active (Gradient)
    'active_bg_type'        => 'gradient',
    'active_bg_solid'       => '#6366f1',
    'active_bg_start'       => '#6366f1',
    'active_bg_end'         => '#8b5cf6',
    'active_text_color'     => '#ffffff',
    
    // Dimensions
    'width'                 => '256',
    'item_height'           => '48',
    
    // Border Radius
    'border_radius_mode'    => 'all',
    'border_radius_all'     => '26',
    'border_radius_top_left' => '26',
    'border_radius_top_right' => '26',
    'border_radius_bottom_right' => '26',
    'border_radius_bottom_left' => '26',
    
    // Item Border Radius
    'item_border_radius'    => '12',
    
    // Typography
    'font_size'             => '14',
    'font_weight'           => '400',
    
    // Glassmorphism
    'glassmorphism'         => true,
    'opacity'               => 0.9,
    'blur_strength'         => '12',
    
    // Shadow
    'box_shadow'            => '0 4px 12px rgba(0, 0, 0, 0.06)',
    'shadow_style'          => 'lg',
    
    // Spacing/Padding (internal - for menu items)
    'spacing_mode'          => 'all',
    'spacing_all'           => '10',
    'spacing_top'           => '10',
    'spacing_right'         => '12',
    'spacing_bottom'        => '10',
    'spacing_left'          => '12',
    
    // Margin (external - menu container)
    'margin_mode'           => 'individual',
    'margin_all'            => '16',
    'margin_top'            => '16',
    'margin_right'          => '0',
    'margin_bottom'         => '0',
    'margin_left'           => '16',
    
    // Icons
    'icon_size'             => '20',
    'icon_color'            => '#64748b',
    'icon_hover_color'      => '#6366f1',
    'icon_active_color'     => '#ffffff',
    
    // Submenu (Flyout - hover)
    'submenu_inherit_styles' => false,
    'submenu_bg_color'      => 'rgba(255, 255, 255, 0.98)',
    'submenu_text_color'    => '#0f172a',
    'submenu_hover_text_color' => '#6366f1',
    'submenu_hover_bg_color' => '#f1f5f9',
    'submenu_item_height'   => '36',
    'submenu_font_size'     => '13',
    'submenu_font_weight'   => '400',
    'submenu_item_border_radius' => '8',
    'submenu_border_radius' => '12',
    
    // Inline Submenu (Active/Current)
    'inline_submenu_visible' => true,
    'inline_submenu_inherit_styles' => true,
    'inline_submenu_bg_color' => '#f8fafc',
    'inline_submenu_text_color' => '#0f172a',
    'inline_submenu_font_size' => '13',
    'inline_submenu_font_weight' => '400',
    'inline_submenu_item_bg_color' => '#f1f5f9',
    
    'custom_css'            => '',
);

// Merge with saved settings
$admin_menu = array_merge( $defaults, $this->settings->get_section( 'admin_menu' ) ?? array() );
?>

<div class="woow-tab-pane" id="tab-menu">
    <!-- Section Header -->
    <div class="woow-section-header">
        <h2><?php esc_html_e( 'Admin Menu Styling', 'woow-admin' ); ?></h2>
        <p class="woow-section-description">
            <?php esc_html_e( 'Customize the sidebar menu with glassmorphism, gradients, and modern styling options.', 'woow-admin' ); ?>
        </p>
    </div>

    <!-- Enable/Disable Toggle -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Enable Menu Styling', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <label class="woow-toggle">
                <input 
                    type="checkbox" 
                    name="admin_menu[enabled]" 
                    value="1"
                    <?php checked( $admin_menu['enabled'], true ); ?>
                    class="woow-toggle-input"
                    data-section="admin_menu"
                />
                <span class="woow-toggle-slider"></span>
                <span class="woow-toggle-label">
                    <?php esc_html_e( 'Apply custom styles to admin menu', 'woow-admin' ); ?>
                </span>
            </label>
        </div>
    </div>

    <!-- Background Settings -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Background', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <!-- Background Type -->
            <div class="woow-form-group">
                <label class="woow-label">
                    <?php esc_html_e( 'Background Type', 'woow-admin' ); ?>
                </label>
                <select 
                    name="admin_menu[background_type]" 
                    class="woow-select woow-condition-trigger"
                    data-target="menu_background_type"
                >
                    <option value="solid" <?php selected( $admin_menu['background_type'], 'solid' ); ?>>
                        <?php esc_html_e( 'Solid Color', 'woow-admin' ); ?>
                    </option>
                    <option value="gradient" <?php selected( $admin_menu['background_type'], 'gradient' ); ?>>
                        <?php esc_html_e( 'Gradient', 'woow-admin' ); ?>
                    </option>
                    <option value="glass" <?php selected( $admin_menu['background_type'], 'glass' ); ?>>
                        <?php esc_html_e( 'Glassmorphism', 'woow-admin' ); ?>
                    </option>
                </select>
            </div>

            <!-- Solid Color Option -->
            <div class="woow-form-group woow-conditional-field" data-condition="menu_background_type" data-value="solid">
                <label class="woow-label">
                    <?php esc_html_e( 'Background Color', 'woow-admin' ); ?>
                </label>
                <div class="woow-color-picker-group">
                    <input 
                        type="color" 
                        id="menu-bg-color-solid"
                        name="admin_menu[background_color]" 
                        value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_menu['background_color'] ?? '', 'rgba(255,255,255,0.9)' ) ); ?>"
                        data-default="rgba(255,255,255,0.9)"
                        class="woow-color-input"
                    />
                    <input 
                        type="text" 
                        value="<?php echo esc_attr( $admin_menu['background_color'] ?? 'rgba(255,255,255,0.9)' ); ?>"
                        class="woow-color-text"
                        placeholder="rgba(255,255,255,0.9)"
                    />
                    <button type="button" class="woow-color-reset button" title="<?php esc_attr_e( 'Reset', 'woow-admin' ); ?>">
                        ↺
                    </button>
                </div>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Default: rgba(255,255,255,0.9) (White with transparency)', 'woow-admin' ); ?>
                </p>
            </div>

            <!-- Gradient Options -->
            <div class="woow-form-group woow-conditional-field" data-condition="menu_background_type" data-value="gradient">
                <label class="woow-label">
                    <?php esc_html_e( 'Gradient Start Color', 'woow-admin' ); ?>
                </label>
                <div class="woow-color-picker-group">
                    <input 
                        type="color" 
                        name="admin_menu[gradient_start]" 
                        value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_menu['gradient_start'] ?? '', '#ffffff' ) ); ?>"
                        data-default="#ffffff"
                        class="woow-color-input"
                    />
                    <input 
                        type="text" 
                        value="<?php echo esc_attr( $admin_menu['gradient_start'] ); ?>"
                        class="woow-color-text"
                    />
                    <button type="button" class="woow-color-reset button">↺</button>
                </div>
            </div>

            <div class="woow-form-group woow-conditional-field" data-condition="menu_background_type" data-value="gradient">
                <label class="woow-label">
                    <?php esc_html_e( 'Gradient End Color', 'woow-admin' ); ?>
                </label>
                <div class="woow-color-picker-group">
                    <input 
                        type="color" 
                        name="admin_menu[gradient_end]" 
                        value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_menu['gradient_end'] ?? '', '#f8fafc' ) ); ?>"
                        data-default="#f8fafc"
                        class="woow-color-input"
                    />
                    <input 
                        type="text" 
                        value="<?php echo esc_attr( $admin_menu['gradient_end'] ); ?>"
                        class="woow-color-text"
                    />
                    <button type="button" class="woow-color-reset button">↺</button>
                </div>
            </div>

            <!-- Glassmorphism Options -->
            <div class="woow-form-group woow-conditional-field" data-condition="menu_background_type" data-value="glass">
                <label class="woow-label">
                    <?php esc_html_e( 'Base Color', 'woow-admin' ); ?>
                </label>
                <div class="woow-color-picker-group">
                    <input 
                        type="color" 
                        id="menu-bg-color-glass"
                        name="admin_menu[glass_base_color]" 
                        value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_menu['glass_base_color'] ?? $admin_menu['background_color'] ?? '', '#ffffff' ) ); ?>"
                        data-default="#ffffff"
                        class="woow-color-input"
                    />
                    <input 
                        type="text" 
                        value="<?php echo esc_attr( $admin_menu['glass_base_color'] ?? $admin_menu['background_color'] ?? '#ffffff' ); ?>"
                        class="woow-color-text"
                    />
                    <button type="button" class="woow-color-reset button">↺</button>
                </div>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Base color for glassmorphism effect (will be made transparent)', 'woow-admin' ); ?>
                </p>
            </div>

            <div class="woow-form-row woow-conditional-field" data-condition="menu_background_type" data-value="glass">
                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Opacity', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-slider-group">
                        <input 
                            type="range" 
                            name="admin_menu[opacity]" 
                            value="<?php echo esc_attr( ( $admin_menu['opacity'] ?? 0.9 ) * 100 ); ?>"
                            min="0" 
                            max="100" 
                            step="5"
                            class="woow-slider"
                            data-type="opacity"
                        />
                        <span class="woow-slider-value"><?php echo esc_html( ( $admin_menu['opacity'] ?? 0.9 ) * 100 ); ?>%</span>
                    </div>
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Default: 90%. Background transparency', 'woow-admin' ); ?>
                    </p>
                </div>

                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Blur Strength', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-slider-group">
                        <input 
                            type="range" 
                            name="admin_menu[blur_strength]" 
                            value="<?php echo esc_attr( intval( $admin_menu['blur_strength'] ?? 12 ) ); ?>"
                            min="0" 
                            max="50" 
                            step="2"
                            class="woow-slider"
                            data-type="unitless"
                            data-unit="px"
                        />
                        <span class="woow-slider-value"><?php echo esc_html( $admin_menu['blur_strength'] ?? '12' ); ?>px</span>
                    </div>
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Default: 12px. Backdrop blur effect', 'woow-admin' ); ?>
                    </p>
                </div>
            </div>

            <!-- Text Color -->
            <div class="woow-form-group">
                <label class="woow-label">
                    <?php esc_html_e( 'Text Color', 'woow-admin' ); ?>
                </label>
                <div class="woow-color-picker-group">
                    <input 
                        type="color" 
                        name="admin_menu[text_color]" 
                        value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_menu['text_color'] ?? '', '#0f172a' ) ); ?>"
                        data-default="#0f172a"
                        class="woow-color-input"
                    />
                    <input 
                        type="text" 
                        value="<?php echo esc_attr( $admin_menu['text_color'] ); ?>"
                        class="woow-color-text"
                    />
                    <button type="button" class="woow-color-reset button">↺</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hover & Interaction Card -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Hover & Interaction', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <!-- Hover Style -->
            <div class="woow-form-group">
                <label class="woow-label">
                    <?php esc_html_e( 'Hover Style', 'woow-admin' ); ?>
                </label>
                <select name="admin_menu[hover_style]" class="woow-select">
                    <option value="normal" <?php selected( $admin_menu['hover_style'], 'normal' ); ?>>
                        <?php esc_html_e( 'Normal', 'woow-admin' ); ?>
                    </option>
                    <option value="lift" <?php selected( $admin_menu['hover_style'], 'lift' ); ?>>
                        <?php esc_html_e( 'Lift Effect', 'woow-admin' ); ?>
                    </option>
                    <option value="glow" <?php selected( $admin_menu['hover_style'], 'glow' ); ?>>
                        <?php esc_html_e( 'Glow Effect', 'woow-admin' ); ?>
                    </option>
                </select>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Visual effect when hovering over menu items', 'woow-admin' ); ?>
                </p>
            </div>

            <!-- Hover Colors -->
            <div class="woow-form-row">
                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Hover Background', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-color-picker-group">
                        <input 
                            type="color" 
                            name="admin_menu[hover_bg_color]" 
                            value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_menu['hover_bg_color'] ?? '', '#f8fafc' ) ); ?>"
                            data-default="#f8fafc"
                            class="woow-color-input"
                        />
                        <input 
                            type="text" 
                            value="<?php echo esc_attr( $admin_menu['hover_bg_color'] ); ?>"
                            class="woow-color-text"
                        />
                        <button type="button" class="woow-color-reset button">↺</button>
                    </div>
                </div>

                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Hover Text Color', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-color-picker-group">
                        <input 
                            type="color" 
                            name="admin_menu[hover_text_color]" 
                            value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_menu['hover_text_color'] ?? '', '#6366f1' ) ); ?>"
                            data-default="#6366f1"
                            class="woow-color-input"
                        />
                        <input 
                            type="text" 
                            value="<?php echo esc_attr( $admin_menu['hover_text_color'] ); ?>"
                            class="woow-color-text"
                        />
                        <button type="button" class="woow-color-reset button">↺</button>
                    </div>
                </div>
            </div>

            <!-- Active Item Background Type -->
            <div class="woow-form-group">
                <label class="woow-label">
                    <?php esc_html_e( 'Active Item Background Type', 'woow-admin' ); ?>
                </label>
                <select 
                    name="admin_menu[active_bg_type]" 
                    class="woow-select woow-condition-trigger"
                    data-target="menu_active_bg_type"
                >
                    <option value="gradient" <?php selected( $admin_menu['active_bg_type'] ?? 'gradient', 'gradient' ); ?>>
                        <?php esc_html_e( 'Gradient', 'woow-admin' ); ?>
                    </option>
                    <option value="solid" <?php selected( $admin_menu['active_bg_type'] ?? 'gradient', 'solid' ); ?>>
                        <?php esc_html_e( 'Solid Color', 'woow-admin' ); ?>
                    </option>
                </select>
            </div>

            <!-- Solid Color Option -->
            <div class="woow-form-group woow-conditional-field" data-condition="menu_active_bg_type" data-value="solid">
                <label class="woow-label">
                    <?php esc_html_e( 'Active Background Color', 'woow-admin' ); ?>
                </label>
                <div class="woow-color-picker-group">
                    <input 
                        type="color" 
                        name="admin_menu[active_bg_solid]" 
                        value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_menu['active_bg_solid'] ?? '', '#6366f1' ) ); ?>"
                        data-default="#6366f1"
                        class="woow-color-input"
                    />
                    <input 
                        type="text" 
                        value="<?php echo esc_attr( $admin_menu['active_bg_solid'] ?? '#6366f1' ); ?>"
                        class="woow-color-text"
                    />
                    <button type="button" class="woow-color-reset button">↺</button>
                </div>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Solid color for active menu item', 'woow-admin' ); ?>
                </p>
            </div>

            <!-- Gradient Options -->
            <div class="woow-form-row woow-conditional-field" data-condition="menu_active_bg_type" data-value="gradient">
                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Active Gradient Start', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-color-picker-group">
                        <input 
                            type="color" 
                            name="admin_menu[active_bg_start]" 
                            value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_menu['active_bg_start'] ?? '', '#6366f1' ) ); ?>"
                            data-default="#6366f1"
                            class="woow-color-input"
                        />
                        <input 
                            type="text" 
                            value="<?php echo esc_attr( $admin_menu['active_bg_start'] ); ?>"
                            class="woow-color-text"
                        />
                        <button type="button" class="woow-color-reset button">↺</button>
                    </div>
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Default: #6366f1 (Indigo 500)', 'woow-admin' ); ?>
                    </p>
                </div>

                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Active Gradient End', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-color-picker-group">
                        <input 
                            type="color" 
                            name="admin_menu[active_bg_end]" 
                            value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_menu['active_bg_end'] ?? '', '#8b5cf6' ) ); ?>"
                            data-default="#8b5cf6"
                            class="woow-color-input"
                        />
                        <input 
                            type="text" 
                            value="<?php echo esc_attr( $admin_menu['active_bg_end'] ); ?>"
                            class="woow-color-text"
                        />
                        <button type="button" class="woow-color-reset button">↺</button>
                    </div>
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Default: #8b5cf6 (Purple 500)', 'woow-admin' ); ?>
                    </p>
                </div>
            </div>

            <div class="woow-form-group">
                <label class="woow-label">
                    <?php esc_html_e( 'Active Text Color', 'woow-admin' ); ?>
                </label>
                <div class="woow-color-picker-group">
                    <input 
                        type="color" 
                        name="admin_menu[active_text_color]" 
                        value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_menu['active_text_color'] ?? '', '#ffffff' ) ); ?>"
                        data-default="#ffffff"
                        class="woow-color-input"
                    />
                    <input 
                        type="text" 
                        value="<?php echo esc_attr( $admin_menu['active_text_color'] ); ?>"
                        class="woow-color-text"
                    />
                    <button type="button" class="woow-color-reset button">↺</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Submenu Styling -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Submenu Styling', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <!-- Inherit Styles Toggle -->
            <div class="woow-form-group">
                <label class="woow-toggle-label">
                    <input 
                        type="checkbox" 
                        name="admin_menu[submenu_inherit_styles]" 
                        value="1"
                        <?php checked( $admin_menu['submenu_inherit_styles'] ?? false, true ); ?>
                        class="woow-toggle-input"
                    />
                    <span class="woow-toggle-slider"></span>
                    <span class="woow-toggle-text">
                        <?php esc_html_e( 'Inherit styles from main menu', 'woow-admin' ); ?>
                    </span>
                </label>
                <p class="woow-field-description">
                    <?php esc_html_e( 'When enabled, submenu inherits: background color, text color, hover colors, border radius, font size, and font weight from main menu', 'woow-admin' ); ?>
                </p>
            </div>

            <!-- Submenu Offset -->
            <div class="woow-form-group">
                <label class="woow-label">
                    <?php esc_html_e( 'Distance from Menu', 'woow-admin' ); ?>
                </label>
                <div class="woow-slider-group">
                    <input 
                        type="range" 
                        name="admin_menu[submenu_offset]"
                        value="<?php echo esc_attr( intval( $admin_menu['submenu_offset'] ?? 5 ) ); ?>"
                        min="0" 
                        max="20" 
                        step="1"
                        class="woow-slider"
                        data-type="unitless"
                        data-unit="px"
                    />
                    <span class="woow-slider-value">
                        <?php echo esc_html( $admin_menu['submenu_offset'] ?? 5 ); ?>px
                    </span>
                </div>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Gap between menu and submenu. Default: 5px', 'woow-admin' ); ?>
                </p>
            </div>

            <div class="woow-form-row">
                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Submenu Background', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-color-picker-group">
                        <input 
                            type="color" 
                            name="admin_menu[submenu_bg_color]" 
                            value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_menu['submenu_bg_color'] ?? '', '#f8fafc' ) ); ?>"
                            data-default="#f8fafc"
                            class="woow-color-input"
                        />
                        <input 
                            type="text" 
                            value="<?php echo esc_attr( $admin_menu['submenu_bg_color'] ); ?>"
                            class="woow-color-text"
                        />
                        <button type="button" class="woow-color-reset button">↺</button>
                    </div>
                </div>

                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Submenu Text Color', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-color-picker-group">
                        <input 
                            type="color" 
                            name="admin_menu[submenu_text_color]" 
                            value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_menu['submenu_text_color'] ?? '', '#0f172a' ) ); ?>"
                            data-default="#0f172a"
                            class="woow-color-input"
                        />
                        <input 
                            type="text" 
                            value="<?php echo esc_attr( $admin_menu['submenu_text_color'] ); ?>"
                            class="woow-color-text"
                        />
                        <button type="button" class="woow-color-reset button">↺</button>
                    </div>
                </div>
            </div>

            <div class="woow-form-row">
                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Submenu Hover Background', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-color-picker-group">
                        <input 
                            type="color" 
                            name="admin_menu[submenu_hover_bg_color]" 
                            value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_menu['submenu_hover_bg_color'] ?? '', '#f1f5f9' ) ); ?>"
                            data-default="#f1f5f9"
                            class="woow-color-input"
                        />
                        <input 
                            type="text" 
                            value="<?php echo esc_attr( $admin_menu['submenu_hover_bg_color'] ); ?>"
                            class="woow-color-text"
                        />
                        <button type="button" class="woow-color-reset button">↺</button>
                    </div>
                </div>

                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Submenu Hover Text Color', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-color-picker-group">
                        <input 
                            type="color" 
                            name="admin_menu[submenu_hover_text_color]" 
                            value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_menu['submenu_hover_text_color'] ?? '', '#6366f1' ) ); ?>"
                            data-default="#6366f1"
                            class="woow-color-input"
                        />
                        <input 
                            type="text" 
                            value="<?php echo esc_attr( $admin_menu['submenu_hover_text_color'] ?? '#6366f1' ); ?>"
                            class="woow-color-text"
                        />
                        <button type="button" class="woow-color-reset button">↺</button>
                    </div>
                </div>
            </div>

            <div class="woow-form-row">
                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Submenu Item Height', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-slider-group">
                        <input 
                            type="range" 
                            name="admin_menu[submenu_item_height]" 
                            value="<?php echo esc_attr( intval( $admin_menu['submenu_item_height'] ?? 36 ) ); ?>"
                            min="28" 
                            max="56" 
                            step="2"
                            class="woow-slider"
                            data-type="unitless"
                            data-unit="px"
                        />
                        <span class="woow-slider-value"><?php echo esc_html( $admin_menu['submenu_item_height'] ?? '36' ); ?>px</span>
                    </div>
                </div>

                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Submenu Font Size', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-slider-group">
                        <input 
                            type="range" 
                            name="admin_menu[submenu_font_size]" 
                            value="<?php echo esc_attr( intval( $admin_menu['submenu_font_size'] ?? 13 ) ); ?>"
                            min="11" 
                            max="16" 
                            step="1"
                            class="woow-slider"
                            data-type="unitless"
                            data-unit="px"
                        />
                        <span class="woow-slider-value"><?php echo esc_html( $admin_menu['submenu_font_size'] ?? '13' ); ?>px</span>
                    </div>
                </div>
            </div>

            <div class="woow-form-row">
                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Submenu Font Weight', 'woow-admin' ); ?>
                    </label>
                    <select name="admin_menu[submenu_font_weight]" class="woow-select">
                        <option value="300" <?php selected( $admin_menu['submenu_font_weight'] ?? '400', '300' ); ?>>300 (Light)</option>
                        <option value="400" <?php selected( $admin_menu['submenu_font_weight'] ?? '400', '400' ); ?>>400 (Normal)</option>
                        <option value="500" <?php selected( $admin_menu['submenu_font_weight'] ?? '400', '500' ); ?>>500 (Medium)</option>
                        <option value="600" <?php selected( $admin_menu['submenu_font_weight'] ?? '400', '600' ); ?>>600 (Semibold)</option>
                        <option value="700" <?php selected( $admin_menu['submenu_font_weight'] ?? '400', '700' ); ?>>700 (Bold)</option>
                    </select>
                </div>

                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Submenu Item Border Radius', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-slider-group">
                        <input 
                            type="range" 
                            name="admin_menu[submenu_item_border_radius]" 
                            value="<?php echo esc_attr( intval( $admin_menu['submenu_item_border_radius'] ?? 8 ) ); ?>"
                            min="0" 
                            max="16" 
                            step="2"
                            class="woow-slider"
                            data-type="unitless"
                            data-unit="px"
                        />
                        <span class="woow-slider-value"><?php echo esc_html( $admin_menu['submenu_item_border_radius'] ?? '8' ); ?>px</span>
                    </div>
                </div>
            </div>

            <div class="woow-form-row">
                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Submenu Border Radius', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-slider-group">
                        <input 
                            type="range" 
                            name="admin_menu[submenu_border_radius]" 
                            value="<?php echo esc_attr( intval( $admin_menu['submenu_border_radius'] ?? 8 ) ); ?>"
                            min="0" 
                            max="24" 
                            step="2"
                            class="woow-slider"
                            data-type="unitless"
                            data-unit="px"
                        />
                        <span class="woow-slider-value"><?php echo esc_html( $admin_menu['submenu_border_radius'] ?? '8' ); ?>px</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dimensions Card -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Dimensions', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-row">
                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Menu Width', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-slider-group">
                        <input 
                            type="range" 
                            name="admin_menu[width]" 
                            value="<?php echo esc_attr( intval( $admin_menu['width'] ?? 256 ) ); ?>"
                            min="160" 
                            max="320" 
                            step="8"
                            class="woow-slider"
                            data-type="unitless"
                            data-unit="px"
                        />
                        <span class="woow-slider-value"><?php echo esc_html( $admin_menu['width'] ?? '256' ); ?>px</span>
                    </div>
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Default: 256px. Range: 160-320px', 'woow-admin' ); ?>
                    </p>
                </div>

                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Menu Item Height', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-slider-group">
                        <input 
                            type="range" 
                            name="admin_menu[item_height]" 
                            value="<?php echo esc_attr( intval( $admin_menu['item_height'] ?? 48 ) ); ?>"
                            min="32" 
                            max="64" 
                            step="2"
                            class="woow-slider"
                            data-type="unitless"
                            data-unit="px"
                        />
                        <span class="woow-slider-value"><?php echo esc_html( $admin_menu['item_height'] ?? '48' ); ?>px</span>
                    </div>
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Default: 48px. Range: 32-64px', 'woow-admin' ); ?>
                    </p>
                </div>
            </div>

            <!-- Border Radius Mode -->
            <div class="woow-form-group">
                <label class="woow-label">
                    <?php esc_html_e( 'Border Radius Mode', 'woow-admin' ); ?>
                </label>
                <select 
                    name="admin_menu[border_radius_mode]" 
                    class="woow-select woow-condition-trigger"
                    data-target="menu_border_radius_mode"
                >
                    <option value="all" <?php selected( $admin_menu['border_radius_mode'], 'all' ); ?>>
                        <?php esc_html_e( 'All Corners (Uniform)', 'woow-admin' ); ?>
                    </option>
                    <option value="individual" <?php selected( $admin_menu['border_radius_mode'], 'individual' ); ?>>
                        <?php esc_html_e( 'Individual Corners', 'woow-admin' ); ?>
                    </option>
                </select>
            </div>

            <!-- All Corners (Uniform) -->
            <div class="woow-form-group woow-conditional-field" data-condition="menu_border_radius_mode" data-value="all">
                <label class="woow-label">
                    <?php esc_html_e( 'Border Radius (All Corners)', 'woow-admin' ); ?>
                </label>
                <div class="woow-slider-group">
                    <input 
                        type="range" 
                        name="admin_menu[border_radius_all]" 
                        value="<?php echo esc_attr( intval( $admin_menu['border_radius_all'] ?? 12 ) ); ?>"
                        min="0" 
                        max="50" 
                        step="2"
                        class="woow-slider"
                        data-type="unitless"
                        data-unit="px"
                    />
                    <span class="woow-slider-value"><?php echo esc_html( $admin_menu['border_radius_all'] ?? '12' ); ?>px</span>
                </div>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Default: 12px. Applies to all corners', 'woow-admin' ); ?>
                </p>
            </div>

            <!-- Individual Corners -->
            <div class="woow-conditional-field" data-condition="menu_border_radius_mode" data-value="individual">
                <div class="woow-form-row">
                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Top Left', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-slider-group">
                            <input 
                                type="range" 
                                name="admin_menu[border_radius_top_left]" 
                                value="<?php echo esc_attr( intval( $admin_menu['border_radius_top_left'] ?? 12 ) ); ?>"
                                min="0" 
                                max="50" 
                                step="2"
                                class="woow-slider"
                                data-type="unitless"
                                data-unit="px"
                            />
                            <span class="woow-slider-value"><?php echo esc_html( $admin_menu['border_radius_top_left'] ?? '12' ); ?>px</span>
                        </div>
                    </div>

                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Top Right', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-slider-group">
                            <input 
                                type="range" 
                                name="admin_menu[border_radius_top_right]" 
                                value="<?php echo esc_attr( intval( $admin_menu['border_radius_top_right'] ?? 12 ) ); ?>"
                                min="0" 
                                max="50" 
                                step="2"
                                class="woow-slider"
                                data-type="unitless"
                                data-unit="px"
                            />
                            <span class="woow-slider-value"><?php echo esc_html( $admin_menu['border_radius_top_right'] ?? '12' ); ?>px</span>
                        </div>
                    </div>
                </div>

                <div class="woow-form-row">
                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Bottom Right', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-slider-group">
                            <input 
                                type="range" 
                                name="admin_menu[border_radius_bottom_right]" 
                                value="<?php echo esc_attr( intval( $admin_menu['border_radius_bottom_right'] ?? 12 ) ); ?>"
                                min="0" 
                                max="50" 
                                step="2"
                                class="woow-slider"
                                data-type="unitless"
                                data-unit="px"
                            />
                            <span class="woow-slider-value"><?php echo esc_html( $admin_menu['border_radius_bottom_right'] ?? '12' ); ?>px</span>
                        </div>
                    </div>

                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Bottom Left', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-slider-group">
                            <input 
                                type="range" 
                                name="admin_menu[border_radius_bottom_left]" 
                                value="<?php echo esc_attr( intval( $admin_menu['border_radius_bottom_left'] ?? 12 ) ); ?>"
                                min="0" 
                                max="50" 
                                step="2"
                                class="woow-slider"
                                data-type="unitless"
                                data-unit="px"
                            />
                            <span class="woow-slider-value"><?php echo esc_html( $admin_menu['border_radius_bottom_left'] ?? '12' ); ?>px</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item Border Radius -->
            <div class="woow-form-group">
                <label class="woow-label">
                    <?php esc_html_e( 'Menu Item Border Radius', 'woow-admin' ); ?>
                </label>
                <div class="woow-slider-group">
                    <input 
                        type="range" 
                        name="admin_menu[item_border_radius]" 
                        value="<?php echo esc_attr( intval( $admin_menu['item_border_radius'] ?? 12 ) ); ?>"
                        min="0" 
                        max="24" 
                        step="2"
                        class="woow-slider"
                        data-type="unitless"
                        data-unit="px"
                    />
                    <span class="woow-slider-value"><?php echo esc_html( $admin_menu['item_border_radius'] ?? '12' ); ?>px</span>
                </div>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Border radius for individual menu items', 'woow-admin' ); ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Typography Card -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Typography', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-row">
                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Font Size', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-slider-group">
                        <input 
                            type="range" 
                            name="admin_menu[font_size]" 
                            value="<?php echo esc_attr( intval( $admin_menu['font_size'] ?? 14 ) ); ?>"
                            min="12" 
                            max="18" 
                            step="1"
                            class="woow-slider"
                            data-type="unitless"
                            data-unit="px"
                        />
                        <span class="woow-slider-value"><?php echo esc_html( $admin_menu['font_size'] ?? '14' ); ?>px</span>
                    </div>
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Default: 14px. Menu text size', 'woow-admin' ); ?>
                    </p>
                </div>

                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Font Weight', 'woow-admin' ); ?>
                    </label>
                    <select name="admin_menu[font_weight]" class="woow-select">
                        <option value="400" <?php selected( $admin_menu['font_weight'], '400' ); ?>>
                            <?php esc_html_e( 'Normal (400)', 'woow-admin' ); ?>
                        </option>
                        <option value="500" <?php selected( $admin_menu['font_weight'], '500' ); ?>>
                            <?php esc_html_e( 'Medium (500)', 'woow-admin' ); ?>
                        </option>
                        <option value="600" <?php selected( $admin_menu['font_weight'], '600' ); ?>>
                            <?php esc_html_e( 'Semibold (600)', 'woow-admin' ); ?>
                        </option>
                        <option value="700" <?php selected( $admin_menu['font_weight'], '700' ); ?>>
                            <?php esc_html_e( 'Bold (700)', 'woow-admin' ); ?>
                        </option>
                    </select>
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Default: Semibold (600)', 'woow-admin' ); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Spacing (Padding) Card -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Spacing (Padding)', 'woow-admin' ); ?></h3>
            <p class="woow-card-description">
                <?php esc_html_e( 'Internal padding for menu items', 'woow-admin' ); ?>
            </p>
        </div>
        <div class="woow-card-body">
            <!-- Spacing Mode -->
            <div class="woow-form-group">
                <label class="woow-label">
                    <?php esc_html_e( 'Spacing Mode', 'woow-admin' ); ?>
                </label>
                <select 
                    name="admin_menu[spacing_mode]" 
                    class="woow-select woow-condition-trigger"
                    data-target="menu_spacing_mode"
                >
                    <option value="all" <?php selected( $admin_menu['spacing_mode'], 'all' ); ?>>
                        <?php esc_html_e( 'All Sides (Uniform)', 'woow-admin' ); ?>
                    </option>
                    <option value="individual" <?php selected( $admin_menu['spacing_mode'], 'individual' ); ?>>
                        <?php esc_html_e( 'Individual Sides', 'woow-admin' ); ?>
                    </option>
                </select>
            </div>

            <!-- All Sides (Uniform) -->
            <div class="woow-form-group woow-conditional-field" data-condition="menu_spacing_mode" data-value="all">
                <label class="woow-label">
                    <?php esc_html_e( 'Padding (All Sides)', 'woow-admin' ); ?>
                </label>
                <div class="woow-slider-group">
                    <input 
                        type="range" 
                        name="admin_menu[spacing_all]" 
                        value="<?php echo esc_attr( intval( $admin_menu['spacing_all'] ?? 12 ) ); ?>"
                        min="0" 
                        max="48" 
                        step="2"
                        class="woow-slider"
                        data-type="unitless"
                        data-unit="px"
                    />
                    <span class="woow-slider-value"><?php echo esc_html( $admin_menu['spacing_all'] ?? '12' ); ?>px</span>
                </div>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Default: 12px. Applies to all sides', 'woow-admin' ); ?>
                </p>
            </div>

            <!-- Individual Sides -->
            <div class="woow-conditional-field" data-condition="menu_spacing_mode" data-value="individual">
                <div class="woow-form-row">
                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Top', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-slider-group">
                            <input 
                                type="range" 
                                name="admin_menu[spacing_top]" 
                                value="<?php echo esc_attr( intval( $admin_menu['spacing_top'] ?? 12 ) ); ?>"
                                min="0" 
                                max="48" 
                                step="2"
                                class="woow-slider"
                                data-type="unitless"
                                data-unit="px"
                            />
                            <span class="woow-slider-value"><?php echo esc_html( $admin_menu['spacing_top'] ?? '12' ); ?>px</span>
                        </div>
                    </div>

                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Right', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-slider-group">
                            <input 
                                type="range" 
                                name="admin_menu[spacing_right]" 
                                value="<?php echo esc_attr( intval( $admin_menu['spacing_right'] ?? 16 ) ); ?>"
                                min="0" 
                                max="48" 
                                step="2"
                                class="woow-slider"
                                data-type="unitless"
                                data-unit="px"
                            />
                            <span class="woow-slider-value"><?php echo esc_html( $admin_menu['spacing_right'] ?? '16' ); ?>px</span>
                        </div>
                    </div>
                </div>

                <div class="woow-form-row">
                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Bottom', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-slider-group">
                            <input 
                                type="range" 
                                name="admin_menu[spacing_bottom]" 
                                value="<?php echo esc_attr( intval( $admin_menu['spacing_bottom'] ?? 12 ) ); ?>"
                                min="0" 
                                max="48" 
                                step="2"
                                class="woow-slider"
                                data-type="unitless"
                                data-unit="px"
                            />
                            <span class="woow-slider-value"><?php echo esc_html( $admin_menu['spacing_bottom'] ?? '12' ); ?>px</span>
                        </div>
                    </div>

                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Left', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-slider-group">
                            <input 
                                type="range" 
                                name="admin_menu[spacing_left]" 
                                value="<?php echo esc_attr( intval( $admin_menu['spacing_left'] ?? 16 ) ); ?>"
                                min="0" 
                                max="48" 
                                step="2"
                                class="woow-slider"
                                data-type="unitless"
                                data-unit="px"
                            />
                            <span class="woow-slider-value"><?php echo esc_html( $admin_menu['spacing_left'] ?? '16' ); ?>px</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Margin Card -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Margin (Offset from Edges)', 'woow-admin' ); ?></h3>
            <p class="woow-card-description">
                <?php esc_html_e( 'External margin for menu container', 'woow-admin' ); ?>
            </p>
        </div>
        <div class="woow-card-body">
            <!-- Margin Mode -->
            <div class="woow-form-group">
                <label class="woow-label">
                    <?php esc_html_e( 'Margin Mode', 'woow-admin' ); ?>
                </label>
                <select 
                    name="admin_menu[margin_mode]" 
                    class="woow-select woow-condition-trigger"
                    data-target="menu_margin_mode"
                >
                    <option value="all" <?php selected( $admin_menu['margin_mode'], 'all' ); ?>>
                        <?php esc_html_e( 'All Sides (Uniform)', 'woow-admin' ); ?>
                    </option>
                    <option value="individual" <?php selected( $admin_menu['margin_mode'], 'individual' ); ?>>
                        <?php esc_html_e( 'Individual Sides', 'woow-admin' ); ?>
                    </option>
                </select>
            </div>

            <!-- All Sides (Uniform) -->
            <div class="woow-form-group woow-conditional-field" data-condition="menu_margin_mode" data-value="all">
                <label class="woow-label">
                    <?php esc_html_e( 'Margin (All Sides)', 'woow-admin' ); ?>
                </label>
                <div class="woow-slider-group">
                    <input 
                        type="range" 
                        name="admin_menu[margin_all]" 
                        value="<?php echo esc_attr( intval( $admin_menu['margin_all'] ?? 0 ) ); ?>"
                        min="0" 
                        max="48" 
                        step="2"
                        class="woow-slider"
                        data-type="unitless"
                        data-unit="px"
                    />
                    <span class="woow-slider-value"><?php echo esc_html( $admin_menu['margin_all'] ?? '0' ); ?>px</span>
                </div>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Default: 0px. Applies to all sides', 'woow-admin' ); ?>
                </p>
            </div>

            <!-- Individual Sides -->
            <div class="woow-conditional-field" data-condition="menu_margin_mode" data-value="individual">
                <div class="woow-form-row">
                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Top', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-slider-group">
                            <input 
                                type="range" 
                                name="admin_menu[margin_top]" 
                                value="<?php echo esc_attr( intval( $admin_menu['margin_top'] ?? 0 ) ); ?>"
                                min="0" 
                                max="48" 
                                step="2"
                                class="woow-slider"
                                data-type="unitless"
                                data-unit="px"
                            />
                            <span class="woow-slider-value"><?php echo esc_html( $admin_menu['margin_top'] ?? '0' ); ?>px</span>
                        </div>
                    </div>

                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Right', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-slider-group">
                            <input 
                                type="range" 
                                name="admin_menu[margin_right]" 
                                value="<?php echo esc_attr( intval( $admin_menu['margin_right'] ?? 0 ) ); ?>"
                                min="0" 
                                max="48" 
                                step="2"
                                class="woow-slider"
                                data-type="unitless"
                                data-unit="px"
                            />
                            <span class="woow-slider-value"><?php echo esc_html( $admin_menu['margin_right'] ?? '0' ); ?>px</span>
                        </div>
                    </div>
                </div>

                <div class="woow-form-row">
                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Bottom', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-slider-group">
                            <input 
                                type="range" 
                                name="admin_menu[margin_bottom]" 
                                value="<?php echo esc_attr( intval( $admin_menu['margin_bottom'] ?? 0 ) ); ?>"
                                min="0" 
                                max="48" 
                                step="2"
                                class="woow-slider"
                                data-type="unitless"
                                data-unit="px"
                            />
                            <span class="woow-slider-value"><?php echo esc_html( $admin_menu['margin_bottom'] ?? '0' ); ?>px</span>
                        </div>
                    </div>

                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Left', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-slider-group">
                            <input 
                                type="range" 
                                name="admin_menu[margin_left]" 
                                value="<?php echo esc_attr( intval( $admin_menu['margin_left'] ?? 0 ) ); ?>"
                                min="0" 
                                max="48" 
                                step="2"
                                class="woow-slider"
                                data-type="unitless"
                                data-unit="px"
                            />
                            <span class="woow-slider-value"><?php echo esc_html( $admin_menu['margin_left'] ?? '0' ); ?>px</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Visual Effects Card -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Visual Effects', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <!-- Shadow Style -->
            <div class="woow-form-group">
                <label class="woow-label">
                    <?php esc_html_e( 'Shadow Style', 'woow-admin' ); ?>
                </label>
                <select name="admin_menu[shadow_style]" class="woow-select">
                    <option value="none" <?php selected( $admin_menu['shadow_style'], 'none' ); ?>>
                        <?php esc_html_e( 'None', 'woow-admin' ); ?>
                    </option>
                    <option value="sm" <?php selected( $admin_menu['shadow_style'], 'sm' ); ?>>
                        <?php esc_html_e( 'Small', 'woow-admin' ); ?>
                    </option>
                    <option value="md" <?php selected( $admin_menu['shadow_style'], 'md' ); ?>>
                        <?php esc_html_e( 'Medium', 'woow-admin' ); ?>
                    </option>
                    <option value="lg" <?php selected( $admin_menu['shadow_style'], 'lg' ); ?>>
                        <?php esc_html_e( 'Large', 'woow-admin' ); ?>
                    </option>
                    <option value="xl" <?php selected( $admin_menu['shadow_style'], 'xl' ); ?>>
                        <?php esc_html_e( 'Extra Large', 'woow-admin' ); ?>
                    </option>
                </select>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Default: Small. Drop shadow effect', 'woow-admin' ); ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Icons Card -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Icon Styling', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-group">
                <label class="woow-label">
                    <?php esc_html_e( 'Icon Size', 'woow-admin' ); ?>
                </label>
                <div class="woow-slider-group">
                    <input 
                        type="range" 
                        name="admin_menu[icon_size]" 
                        value="<?php echo esc_attr( intval( $admin_menu['icon_size'] ?? 20 ) ); ?>"
                        min="16" 
                        max="32" 
                        step="2"
                        class="woow-slider"
                        data-type="unitless"
                        data-unit="px"
                    />
                    <span class="woow-slider-value"><?php echo esc_html( $admin_menu['icon_size'] ?? '20' ); ?>px</span>
                </div>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Default: 20px. Menu icon size', 'woow-admin' ); ?>
                </p>
            </div>

            <div class="woow-form-row">
                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Icon Color', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-color-picker-group">
                        <input 
                            type="color" 
                            name="admin_menu[icon_color]" 
                            value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_menu['icon_color'] ?? '', '#64748b' ) ); ?>"
                            data-default="#64748b"
                            class="woow-color-input"
                        />
                        <input 
                            type="text" 
                            value="<?php echo esc_attr( $admin_menu['icon_color'] ); ?>"
                            class="woow-color-text"
                        />
                        <button type="button" class="woow-color-reset button">↺</button>
                    </div>
                </div>

                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Icon Hover Color', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-color-picker-group">
                        <input 
                            type="color" 
                            name="admin_menu[icon_hover_color]" 
                            value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_menu['icon_hover_color'] ?? '', '#6366f1' ) ); ?>"
                            data-default="#6366f1"
                            class="woow-color-input"
                        />
                        <input 
                            type="text" 
                            value="<?php echo esc_attr( $admin_menu['icon_hover_color'] ); ?>"
                            class="woow-color-text"
                        />
                        <button type="button" class="woow-color-reset button">↺</button>
                    </div>
                </div>
            </div>

            <div class="woow-form-group">
                <label class="woow-label">
                    <?php esc_html_e( 'Icon Active Color', 'woow-admin' ); ?>
                </label>
                <div class="woow-color-picker-group">
                    <input 
                        type="color" 
                        name="admin_menu[icon_active_color]" 
                        value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_menu['icon_active_color'] ?? '', '#ffffff' ) ); ?>"
                        data-default="#ffffff"
                        class="woow-color-input"
                    />
                    <input 
                        type="text" 
                        value="<?php echo esc_attr( $admin_menu['icon_active_color'] ); ?>"
                        class="woow-color-text"
                    />
                    <button type="button" class="woow-color-reset button">↺</button>
                </div>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Icon color for active/current menu item', 'woow-admin' ); ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Inline Submenu Styling Card -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Inline Submenu Styling', 'woow-admin' ); ?></h3>
            <p class="woow-card-description">
                <?php esc_html_e( 'Style the submenu that appears inline below active menu items', 'woow-admin' ); ?>
            </p>
        </div>
        <div class="woow-card-body">
            <!-- Visibility Toggle -->
            <div class="woow-form-group">
                <label class="woow-toggle">
                    <input 
                        type="checkbox" 
                        name="admin_menu[inline_submenu_visible]" 
                        value="1"
                        <?php checked( $admin_menu['inline_submenu_visible'] ?? true, true ); ?>
                    />
                    <span class="woow-toggle-slider"></span>
                    <span class="woow-toggle-label"><?php esc_html_e( 'Show Inline Submenu', 'woow-admin' ); ?></span>
                </label>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Display submenu items inline below active parent item', 'woow-admin' ); ?>
                </p>
            </div>

            <!-- Inherit Styles Toggle -->
            <div class="woow-form-group">
                <label class="woow-toggle">
                    <input 
                        type="checkbox" 
                        name="admin_menu[inline_submenu_inherit_styles]" 
                        value="1"
                        <?php checked( $admin_menu['inline_submenu_inherit_styles'] ?? true, true ); ?>
                        class="woow-condition-trigger"
                        data-target="inline_submenu_inherit"
                    />
                    <span class="woow-toggle-slider"></span>
                    <span class="woow-toggle-label"><?php esc_html_e( 'Inherit Parent Styles', 'woow-admin' ); ?></span>
                </label>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Use parent item colors with adjusted opacity (50% for background, 19% for items)', 'woow-admin' ); ?>
                </p>
            </div>

            <!-- Custom Styles (shown when NOT inheriting) -->
            <div class="woow-conditional-field" data-condition="inline_submenu_inherit" data-value="false">
                <!-- Background Color -->
                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Background Color', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-color-picker-group">
                        <input 
                            type="color" 
                            name="admin_menu[inline_submenu_bg_color]" 
                            value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_menu['inline_submenu_bg_color'] ?? '', '#f8fafc' ) ); ?>"
                            data-default="#f8fafc"
                            class="woow-color-input"
                        />
                        <input 
                            type="text" 
                            value="<?php echo esc_attr( $admin_menu['inline_submenu_bg_color'] ); ?>"
                            class="woow-color-text"
                        />
                        <button type="button" class="woow-color-reset button">↺</button>
                    </div>
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Background color for inline submenu container', 'woow-admin' ); ?>
                    </p>
                </div>

                <!-- Text Color -->
                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Text Color', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-color-picker-group">
                        <input 
                            type="color" 
                            name="admin_menu[inline_submenu_text_color]" 
                            value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_menu['inline_submenu_text_color'] ?? '', '#0f172a' ) ); ?>"
                            data-default="#0f172a"
                            class="woow-color-input"
                        />
                        <input 
                            type="text" 
                            value="<?php echo esc_attr( $admin_menu['inline_submenu_text_color'] ); ?>"
                            class="woow-color-text"
                        />
                        <button type="button" class="woow-color-reset button">↺</button>
                    </div>
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Text color for inline submenu items', 'woow-admin' ); ?>
                    </p>
                </div>

                <!-- Font Size -->
                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Font Size', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-slider-group">
                        <input 
                            type="range" 
                            name="admin_menu[inline_submenu_font_size]"
                            value="<?php echo esc_attr( intval( $admin_menu['inline_submenu_font_size'] ?? 13 ) ); ?>"
                            min="10" 
                            max="18" 
                            step="1"
                            class="woow-slider"
                            data-type="unitless"
                            data-unit="px"
                        />
                        <span class="woow-slider-value">
                            <?php echo esc_html( $admin_menu['inline_submenu_font_size'] ?? 13 ); ?>px
                        </span>
                    </div>
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Font size for inline submenu text', 'woow-admin' ); ?>
                    </p>
                </div>

                <!-- Font Weight -->
                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Font Weight', 'woow-admin' ); ?>
                    </label>
                    <select name="admin_menu[inline_submenu_font_weight]" class="woow-select">
                        <option value="300" <?php selected( $admin_menu['inline_submenu_font_weight'] ?? '400', '300' ); ?>>
                            <?php esc_html_e( 'Light (300)', 'woow-admin' ); ?>
                        </option>
                        <option value="400" <?php selected( $admin_menu['inline_submenu_font_weight'] ?? '400', '400' ); ?>>
                            <?php esc_html_e( 'Normal (400)', 'woow-admin' ); ?>
                        </option>
                        <option value="500" <?php selected( $admin_menu['inline_submenu_font_weight'] ?? '400', '500' ); ?>>
                            <?php esc_html_e( 'Medium (500)', 'woow-admin' ); ?>
                        </option>
                        <option value="600" <?php selected( $admin_menu['inline_submenu_font_weight'] ?? '400', '600' ); ?>>
                            <?php esc_html_e( 'Semi-Bold (600)', 'woow-admin' ); ?>
                        </option>
                        <option value="700" <?php selected( $admin_menu['inline_submenu_font_weight'] ?? '400', '700' ); ?>>
                            <?php esc_html_e( 'Bold (700)', 'woow-admin' ); ?>
                        </option>
                    </select>
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Font weight for inline submenu text', 'woow-admin' ); ?>
                    </p>
                </div>

                <!-- Item Background Color -->
                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Item Background Color', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-color-picker-group">
                        <input 
                            type="color" 
                            name="admin_menu[inline_submenu_item_bg_color]" 
                            value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_menu['inline_submenu_item_bg_color'] ?? '', '#f1f5f9' ) ); ?>"
                            data-default="#f1f5f9"
                            class="woow-color-input"
                        />
                        <input 
                            type="text" 
                            value="<?php echo esc_attr( $admin_menu['inline_submenu_item_bg_color'] ); ?>"
                            class="woow-color-text"
                        />
                        <button type="button" class="woow-color-reset button">↺</button>
                    </div>
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Background color for inline submenu items on hover', 'woow-admin' ); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS Card -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Custom CSS', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <textarea 
                name="admin_menu[custom_css]" 
                class="woow-code-editor" 
                rows="8" 
                placeholder="/* Your custom CSS here */" 
                spellcheck="false"
            ><?php echo esc_textarea( $admin_menu['custom_css'] ); ?></textarea>
            <p class="woow-field-description">
                <?php esc_html_e( 'Add custom CSS rules for advanced menu styling', 'woow-admin' ); ?>
            </p>
        </div>
    </div>

    <!-- Card Footer -->
    <div class="woow-card-footer">
        <button type="button" class="button woow-button-secondary woow-reset-section" data-section="admin_menu">
            <?php esc_html_e( 'Reset to Defaults', 'woow-admin' ); ?>
        </button>
    </div>
</div>
