<?php
/**
 * Admin Bar Configuration Tab
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define default values for admin bar
$defaults = array(
    'enabled'           => true,
    'background_type'   => 'solid',
    'background_color'  => '#1e293b',
    'gradient_start'    => '#1e293b',
    'gradient_end'      => '#0f172a',
    'text_color'        => '#ffffff',
    'hover_bg_color'    => 'rgba(255,255,255,0.1)',
    'hover_text_color'  => '#ffffff',
    'hover_style'       => 'normal',
    'height'            => '48px',
    'width'             => '100',
    'width_unit'        => '%',
    'border_radius_mode' => 'all',
    'border_radius_all' => '24',
    'border_radius_top_left' => '24',
    'border_radius_top_right' => '24',
    'border_radius_bottom_right' => '24',
    'border_radius_bottom_left' => '24',
    'font_size'         => '14px',
    'font_weight'       => '600',
    'glassmorphism'     => true,
    'blur_strength'     => '12px',
    'opacity'           => 0.9,
    'shadow_style'      => 'md',
    'position'          => 'fixed',
    'top_offset'        => '16px',
    'spacing_mode'      => 'all',
    'spacing_all'       => '24',
    'spacing_top'       => '0',
    'spacing_right'     => '24',
    'spacing_bottom'    => '0',
    'spacing_left'      => '24',
    'margin_mode'       => 'all',
    'margin_all'        => '16',
    'margin_top'        => '16',
    'margin_right'      => '16',
    'margin_bottom'     => '16',
    'margin_left'       => '16',
    'submenu_inherit_styles' => false,
    'submenu_bg_color'  => 'rgba(255, 255, 255, 0.98)',
    'submenu_text_color' => '#0f172a',
    'submenu_hover_bg_color' => '#f1f5f9',
    'submenu_hover_text_color' => '#6366f1',
    'submenu_border_radius' => '12',
    'submenu_font_size' => '14',
    'submenu_font_weight' => '400',
    'submenu_item_height' => '36',
    'submenu_item_border_radius' => '8',
    'submenu_distance_from_menu' => '5',
    'custom_css'        => '',
);

// Merge with saved settings
$admin_bar = array_merge( $defaults, $this->settings->get_section( 'admin_bar' ) ?? array() );
?>

<div class="woow-tab-pane" id="tab-admin-bar">
    <!-- Section Header -->
    <div class="woow-section-header">
        <h2><?php esc_html_e( 'Admin Bar Styling', 'woow-admin' ); ?></h2>
        <p class="woow-section-description">
            <?php esc_html_e( 'Customize the top WordPress admin bar with glassmorphism effects, gradients, and typography options.', 'woow-admin' ); ?>
        </p>
    </div>

    <!-- Enable/Disable Toggle -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Enable Admin Bar Styling', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <label class="woow-toggle">
                <input 
                    type="checkbox" 
                    name="admin_bar[enabled]" 
                    value="1"
                    <?php checked( $admin_bar['enabled'], true ); ?>
                    class="woow-toggle-input"
                    data-section="admin_bar"
                />
                <span class="woow-toggle-slider"></span>
                <span class="woow-toggle-label">
                    <?php esc_html_e( 'Apply custom styles to admin bar', 'woow-admin' ); ?>
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
                    name="admin_bar[background_type]" 
                    class="woow-select"
                    data-preview-target="admin-bar-background"
                >
                    <option value="solid" <?php selected( $admin_bar['background_type'], 'solid' ); ?>>
                        <?php esc_html_e( 'Solid Color', 'woow-admin' ); ?>
                    </option>
                    <option value="gradient" <?php selected( $admin_bar['background_type'], 'gradient' ); ?>>
                        <?php esc_html_e( 'Gradient', 'woow-admin' ); ?>
                    </option>
                    <option value="glass" <?php selected( $admin_bar['background_type'], 'glass' ); ?>>
                        <?php esc_html_e( 'Glassmorphism', 'woow-admin' ); ?>
                    </option>
                </select>
            </div>

            <!-- Solid Color Option -->
            <div class="woow-form-group woow-conditional" data-show-when="background_type=solid">
                <label class="woow-label">
                    <?php esc_html_e( 'Background Color', 'woow-admin' ); ?>
                </label>
                <div class="woow-color-picker-group">
                    <input 
                        type="color" 
                        name="admin_bar[background_color]" 
                        value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_bar['background_color'] ?? '', '#1e293b' ) ); ?>"
                        data-default="#1e293b"
                        class="woow-color-input"
                        data-preview-target="admin-bar-background"
                    />
                    <input 
                        type="text" 
                        value="<?php echo esc_attr( $admin_bar['background_color'] ); ?>"
                        class="woow-color-text"
                        pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$"
                        placeholder="#1e293b"
                    />
                    <button type="button" class="woow-color-reset button" title="<?php esc_attr_e( 'Reset', 'woow-admin' ); ?>">
                        ↺
                    </button>
                </div>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Default: #1e293b (Slate 800)', 'woow-admin' ); ?>
                </p>
            </div>

            <!-- Gradient Options -->
            <div class="woow-form-group woow-conditional" data-show-when="background_type=gradient">
                <label class="woow-label">
                    <?php esc_html_e( 'Gradient Start Color', 'woow-admin' ); ?>
                </label>
                <div class="woow-color-picker-group">
                    <input 
                        type="color" 
                        name="admin_bar[gradient_start]" 
                        value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_bar['gradient_start'] ?? '', '#1e293b' ) ); ?>"
                        data-default="#1e293b"
                        class="woow-color-input"
                    />
                    <input 
                        type="text" 
                        value="<?php echo esc_attr( $admin_bar['gradient_start'] ); ?>"
                        class="woow-color-text"
                    />
                </div>
            </div>

            <div class="woow-form-group woow-conditional" data-show-when="background_type=gradient">
                <label class="woow-label">
                    <?php esc_html_e( 'Gradient End Color', 'woow-admin' ); ?>
                </label>
                <div class="woow-color-picker-group">
                    <input 
                        type="color" 
                        name="admin_bar[gradient_end]" 
                        value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_bar['gradient_end'] ?? '', '#0f172a' ) ); ?>"
                        data-default="#0f172a"
                        class="woow-color-input"
                    />
                    <input 
                        type="text" 
                        value="<?php echo esc_attr( $admin_bar['gradient_end'] ); ?>"
                        class="woow-color-text"
                    />
                </div>
            </div>

            <!-- Glassmorphism Options (visible when glass selected) -->
            <div class="woow-form-group woow-conditional" data-show-when="background_type=glass">
                <label class="woow-label">
                    <?php esc_html_e( 'Base Color', 'woow-admin' ); ?>
                </label>
                <div class="woow-color-picker-group">
                    <input 
                        type="color" 
                        name="admin_bar[background_color]" 
                        value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_bar['background_color'] ?? '', '#1e293b' ) ); ?>"
                        data-default="#1e293b"
                        class="woow-color-input"
                    />
                    <input 
                        type="text" 
                        value="<?php echo esc_attr( $admin_bar['background_color'] ); ?>"
                        class="woow-color-text"
                    />
                </div>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Base color for glassmorphism effect (will be made transparent)', 'woow-admin' ); ?>
                </p>
            </div>

            <div class="woow-form-row woow-conditional" data-show-when="background_type=glass">
                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Opacity', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-slider-group">
                        <input 
                            type="range" 
                            name="admin_bar[opacity]" 
                            value="<?php echo esc_attr( ( $admin_bar['opacity'] ?? 0.9 ) * 100 ); ?>"
                            min="0" 
                            max="100" 
                            step="5"
                            class="woow-slider"
                            data-type="opacity"
                        />
                        <span class="woow-slider-value"><?php echo esc_html( ( $admin_bar['opacity'] ?? 0.9 ) * 100 ); ?>%</span>
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
                            name="admin_bar[blur_strength]" 
                            value="<?php echo esc_attr( intval( $admin_bar['blur_strength'] ?? 12 ) ); ?>"
                            min="0" 
                            max="50" 
                            step="2"
                            class="woow-slider"
                            data-unit="px"
                        />
                        <span class="woow-slider-value"><?php echo esc_html( $admin_bar['blur_strength'] ?? '12px' ); ?></span>
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
                        name="admin_bar[text_color]" 
                        value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_bar['text_color'] ?? '', '#ffffff' ) ); ?>"
                        data-default="#ffffff"
                        class="woow-color-input"
                    />
                    <input 
                        type="text" 
                        value="<?php echo esc_attr( $admin_bar['text_color'] ); ?>"
                        class="woow-color-text"
                    />
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
                <select name="admin_bar[hover_style]" class="woow-select">
                    <option value="normal" <?php selected( $admin_bar['hover_style'] ?? 'normal', 'normal' ); ?>>
                        <?php esc_html_e( 'Normal (Full Height)', 'woow-admin' ); ?>
                    </option>
                    <option value="compact" <?php selected( $admin_bar['hover_style'] ?? 'normal', 'compact' ); ?>>
                        <?php esc_html_e( 'Compact (Padded)', 'woow-admin' ); ?>
                    </option>
                </select>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Normal: hover fills full height. Compact: hover has padding from edges.', 'woow-admin' ); ?>
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
                            name="admin_bar[hover_bg_color]" 
                            value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_bar['hover_bg_color'] ?? '', 'rgba(255,255,255,0.1)' ) ); ?>"
                            data-default="rgba(255,255,255,0.1)"
                            class="woow-color-input"
                        />
                        <input 
                            type="text" 
                            value="<?php echo esc_attr( $admin_bar['hover_bg_color'] ?? 'rgba(255,255,255,0.1)' ); ?>"
                            class="woow-color-text"
                            placeholder="rgba(255,255,255,0.1) or transparent"
                        />
                    </div>
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Use "transparent" for no background on hover', 'woow-admin' ); ?>
                    </p>
                </div>

                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Hover Text Color', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-color-picker-group">
                        <input 
                            type="color" 
                            name="admin_bar[hover_text_color]" 
                            value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_bar['hover_text_color'] ?? '', '#ffffff' ) ); ?>"
                            data-default="#ffffff"
                            class="woow-color-input"
                        />
                        <input 
                            type="text" 
                            value="<?php echo esc_attr( $admin_bar['hover_text_color'] ); ?>"
                            class="woow-color-text"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submenu Card -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Submenu Styling', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <!-- Inherit Styles Toggle -->
            <div class="woow-form-group">
                <label class="woow-toggle">
                    <input 
                        type="checkbox" 
                        name="admin_bar[submenu_inherit_styles]" 
                        value="1"
                        <?php checked( $admin_bar['submenu_inherit_styles'] ?? false, true ); ?>
                        class="woow-toggle-input"
                    />
                    <span class="woow-toggle-slider"></span>
                    <span class="woow-toggle-label">
                        <?php esc_html_e( 'Inherit Admin Bar Styles', 'woow-admin' ); ?>
                    </span>
                </label>
                <p class="woow-field-description">
                    <?php esc_html_e( 'When enabled, submenu will use same colors and styles as admin bar', 'woow-admin' ); ?>
                </p>
            </div>

            <!-- Custom Submenu Styles (visible when NOT inheriting) -->
            <div class="woow-conditional" data-show-when="submenu_inherit_styles=0">
                <div class="woow-form-row">
                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Submenu Background', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-color-picker-group">
                            <input 
                                type="color" 
                                name="admin_bar[submenu_bg_color]" 
                                value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_bar['submenu_bg_color'] ?? '', '#ffffff' ) ); ?>"
                                data-default="#ffffff"
                                class="woow-color-input"
                            />
                            <input 
                                type="text" 
                                value="<?php echo esc_attr( $admin_bar['submenu_bg_color'] ?? 'rgba(255,255,255,0.98)' ); ?>"
                                class="woow-color-text"
                            />
                        </div>
                    </div>

                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Submenu Text Color', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-color-picker-group">
                            <input 
                                type="color" 
                                name="admin_bar[submenu_text_color]" 
                                value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_bar['submenu_text_color'] ?? '', '#0f172a' ) ); ?>"
                                data-default="#0f172a"
                                class="woow-color-input"
                            />
                            <input 
                                type="text" 
                                value="<?php echo esc_attr( $admin_bar['submenu_text_color'] ); ?>"
                                class="woow-color-text"
                            />
                        </div>
                    </div>
                </div>

                <!-- Hover Colors -->
                <div class="woow-form-row">
                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Submenu Hover Background', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-color-picker-group">
                            <input 
                                type="color" 
                                name="admin_bar[submenu_hover_bg_color]" 
                                value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_bar['submenu_hover_bg_color'] ?? '', '#f1f5f9' ) ); ?>"
                                data-default="#f1f5f9"
                                class="woow-color-input"
                            />
                            <input 
                                type="text" 
                                value="<?php echo esc_attr( $admin_bar['submenu_hover_bg_color'] ?? '#f1f5f9' ); ?>"
                                class="woow-color-text"
                            />
                        </div>
                    </div>

                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Submenu Hover Text Color', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-color-picker-group">
                            <input 
                                type="color" 
                                name="admin_bar[submenu_hover_text_color]" 
                                value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_bar['submenu_hover_text_color'] ?? '', '#6366f1' ) ); ?>"
                                data-default="#6366f1"
                                class="woow-color-input"
                            />
                            <input 
                                type="text" 
                                value="<?php echo esc_attr( $admin_bar['submenu_hover_text_color'] ); ?>"
                                class="woow-color-text"
                            />
                        </div>
                    </div>
                </div>

                <!-- Typography -->
                <div class="woow-form-row">
                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Submenu Font Size', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-slider-group">
                            <input 
                                type="range" 
                                name="admin_bar[submenu_font_size]" 
                                value="<?php echo esc_attr( intval( $admin_bar['submenu_font_size'] ?? 14 ) ); ?>"
                                min="12" 
                                max="18" 
                                step="1"
                                class="woow-slider"
                                data-type="unitless"
                                data-unit="px"
                            />
                            <span class="woow-slider-value"><?php echo esc_html( $admin_bar['submenu_font_size'] ?? '14' ); ?>px</span>
                        </div>
                    </div>

                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Submenu Font Weight', 'woow-admin' ); ?>
                        </label>
                        <select name="admin_bar[submenu_font_weight]" class="woow-select">
                            <option value="300" <?php selected( $admin_bar['submenu_font_weight'] ?? '400', '300' ); ?>>
                                <?php esc_html_e( 'Light (300)', 'woow-admin' ); ?>
                            </option>
                            <option value="400" <?php selected( $admin_bar['submenu_font_weight'] ?? '400', '400' ); ?>>
                                <?php esc_html_e( 'Normal (400)', 'woow-admin' ); ?>
                            </option>
                            <option value="500" <?php selected( $admin_bar['submenu_font_weight'] ?? '400', '500' ); ?>>
                                <?php esc_html_e( 'Medium (500)', 'woow-admin' ); ?>
                            </option>
                            <option value="600" <?php selected( $admin_bar['submenu_font_weight'] ?? '400', '600' ); ?>>
                                <?php esc_html_e( 'Semibold (600)', 'woow-admin' ); ?>
                            </option>
                            <option value="700" <?php selected( $admin_bar['submenu_font_weight'] ?? '400', '700' ); ?>>
                                <?php esc_html_e( 'Bold (700)', 'woow-admin' ); ?>
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Dimensions -->
                <div class="woow-form-row">
                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Submenu Item Height', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-slider-group">
                            <input 
                                type="range" 
                                name="admin_bar[submenu_item_height]" 
                                value="<?php echo esc_attr( intval( $admin_bar['submenu_item_height'] ?? 36 ) ); ?>"
                                min="28" 
                                max="56" 
                                step="2"
                                class="woow-slider"
                                data-type="unitless"
                                data-unit="px"
                            />
                            <span class="woow-slider-value"><?php echo esc_html( $admin_bar['submenu_item_height'] ?? '36' ); ?>px</span>
                        </div>
                        <p class="woow-field-description">
                            <?php esc_html_e( 'Default: 36px. Height of each submenu item', 'woow-admin' ); ?>
                        </p>
                    </div>

                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Distance from Menu', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-slider-group">
                            <input 
                                type="range" 
                                name="admin_bar[submenu_distance_from_menu]" 
                                value="<?php echo esc_attr( intval( $admin_bar['submenu_distance_from_menu'] ?? 5 ) ); ?>"
                                min="0" 
                                max="20" 
                                step="1"
                                class="woow-slider"
                                data-type="unitless"
                                data-unit="px"
                            />
                            <span class="woow-slider-value"><?php echo esc_html( $admin_bar['submenu_distance_from_menu'] ?? '5' ); ?>px</span>
                        </div>
                        <p class="woow-field-description">
                            <?php esc_html_e( 'Default: 5px. Gap between admin bar and submenu dropdown', 'woow-admin' ); ?>
                        </p>
                    </div>
                </div>

                <!-- Border Radius -->
                <div class="woow-form-row">
                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Submenu Border Radius', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-slider-group">
                            <input 
                                type="range" 
                                name="admin_bar[submenu_border_radius]" 
                                value="<?php echo esc_attr( intval( $admin_bar['submenu_border_radius'] ?? 12 ) ); ?>"
                                min="0" 
                                max="24" 
                                step="2"
                                class="woow-slider"
                                data-type="unitless"
                                data-unit="px"
                            />
                            <span class="woow-slider-value"><?php echo esc_html( $admin_bar['submenu_border_radius'] ?? '12' ); ?>px</span>
                        </div>
                        <p class="woow-field-description">
                            <?php esc_html_e( 'Default: 12px. Rounded corners of submenu container', 'woow-admin' ); ?>
                        </p>
                    </div>

                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Submenu Item Border Radius', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-slider-group">
                            <input 
                                type="range" 
                                name="admin_bar[submenu_item_border_radius]" 
                                value="<?php echo esc_attr( intval( $admin_bar['submenu_item_border_radius'] ?? 8 ) ); ?>"
                                min="0" 
                                max="16" 
                                step="2"
                                class="woow-slider"
                                data-type="unitless"
                                data-unit="px"
                            />
                            <span class="woow-slider-value"><?php echo esc_html( $admin_bar['submenu_item_border_radius'] ?? '8' ); ?>px</span>
                        </div>
                        <p class="woow-field-description">
                            <?php esc_html_e( 'Default: 8px. Rounded corners of individual submenu items', 'woow-admin' ); ?>
                        </p>
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
                <!-- Height -->
                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Height', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-slider-group">
                        <input 
                            type="range" 
                            name="admin_bar[height]" 
                            value="<?php echo esc_attr( intval( $admin_bar['height'] ) ); ?>"
                            min="32" 
                            max="72" 
                            step="4"
                            class="woow-slider"
                            data-unit="px"
                        />
                        <span class="woow-slider-value"><?php echo esc_html( $admin_bar['height'] ); ?></span>
                    </div>
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Default: 48px. Range: 32-72px', 'woow-admin' ); ?>
                    </p>
                </div>

                <!-- Width -->
                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Width', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-slider-group">
                        <input 
                            type="range" 
                            name="admin_bar[width]" 
                            value="<?php echo esc_attr( intval( $admin_bar['width'] ?? 100 ) ); ?>"
                            min="50" 
                            max="100" 
                            step="5"
                            class="woow-slider"
                            data-type="unitless"
                            data-unit="<?php echo esc_attr( $admin_bar['width_unit'] ?? '%' ); ?>"
                        />
                        <span class="woow-slider-value"><?php echo esc_html( $admin_bar['width'] ?? 100 ); ?><?php echo esc_html( $admin_bar['width_unit'] ?? '%' ); ?></span>
                    </div>
                    <div class="woow-unit-selector" style="margin-top: 8px;">
                        <label style="display: inline-flex; align-items: center; gap: 8px; margin-right: 16px;">
                            <input 
                                type="radio" 
                                name="admin_bar[width_unit]" 
                                value="%" 
                                <?php checked( $admin_bar['width_unit'] ?? '%', '%' ); ?>
                                class="woow-radio"
                            />
                            <span class="woow-radio-label"><?php esc_html_e( 'Percent (%)', 'woow-admin' ); ?></span>
                        </label>
                        <label style="display: inline-flex; align-items: center; gap: 8px;">
                            <input 
                                type="radio" 
                                name="admin_bar[width_unit]" 
                                value="px" 
                                <?php checked( $admin_bar['width_unit'] ?? '%', 'px' ); ?>
                                class="woow-radio"
                            />
                            <span class="woow-radio-label"><?php esc_html_e( 'Pixels (px)', 'woow-admin' ); ?></span>
                        </label>
                    </div>
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Default: 100%. Adjust admin bar width. Use % for responsive or px for fixed width.', 'woow-admin' ); ?>
                    </p>
                </div>

                <!-- Border Radius Mode -->
                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Border Radius Mode', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-radio-group">
                        <label class="woow-radio-label">
                            <input 
                                type="radio" 
                                name="admin_bar[border_radius_mode]" 
                                value="all" 
                                <?php checked( $admin_bar['border_radius_mode'] ?? 'all', 'all' ); ?>
                                class="woow-radio woow-condition-trigger"
                                data-target="border_radius_mode"
                            />
                            <span><?php esc_html_e( 'All Corners', 'woow-admin' ); ?></span>
                        </label>
                        <label class="woow-radio-label">
                            <input 
                                type="radio" 
                                name="admin_bar[border_radius_mode]" 
                                value="individual" 
                                <?php checked( $admin_bar['border_radius_mode'] ?? 'all', 'individual' ); ?>
                                class="woow-radio woow-condition-trigger"
                                data-target="border_radius_mode"
                            />
                            <span><?php esc_html_e( 'Individual Corners', 'woow-admin' ); ?></span>
                        </label>
                    </div>
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Choose whether to apply the same radius to all corners or customize each corner individually.', 'woow-admin' ); ?>
                    </p>
                </div>

                <!-- Border Radius All (shown when mode = all) -->
                <div class="woow-form-group woow-conditional-field" data-condition="border_radius_mode" data-value="all">
                    <label class="woow-label">
                        <?php esc_html_e( 'Border Radius (All Corners)', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-slider-group">
                        <input 
                            type="range" 
                            name="admin_bar[border_radius_all]" 
                            value="<?php echo esc_attr( intval( $admin_bar['border_radius_all'] ?? 24 ) ); ?>"
                            min="0" 
                            max="50" 
                            step="2"
                            class="woow-slider"
                            data-type="unitless"
                            data-unit="px"
                        />
                        <span class="woow-slider-value"><?php echo esc_html( $admin_bar['border_radius_all'] ?? '24' ); ?>px</span>
                    </div>
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Default: 24px. Applies to all four corners.', 'woow-admin' ); ?>
                    </p>
                </div>

                <!-- Individual Border Radius (shown when mode = individual) -->
                <div class="woow-conditional-field" data-condition="border_radius_mode" data-value="individual">
                    <!-- Top Left -->
                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Top Left Corner', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-slider-group">
                            <input 
                                type="range" 
                                name="admin_bar[border_radius_top_left]" 
                                value="<?php echo esc_attr( intval( $admin_bar['border_radius_top_left'] ?? 24 ) ); ?>"
                                min="0" 
                                max="50" 
                                step="2"
                                class="woow-slider"
                                data-type="unitless"
                                data-unit="px"
                            />
                            <span class="woow-slider-value"><?php echo esc_html( $admin_bar['border_radius_top_left'] ?? '24' ); ?>px</span>
                        </div>
                    </div>

                    <!-- Top Right -->
                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Top Right Corner', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-slider-group">
                            <input 
                                type="range" 
                                name="admin_bar[border_radius_top_right]" 
                                value="<?php echo esc_attr( intval( $admin_bar['border_radius_top_right'] ?? 24 ) ); ?>"
                                min="0" 
                                max="50" 
                                step="2"
                                class="woow-slider"
                                data-type="unitless"
                                data-unit="px"
                            />
                            <span class="woow-slider-value"><?php echo esc_html( $admin_bar['border_radius_top_right'] ?? '24' ); ?>px</span>
                        </div>
                    </div>

                    <!-- Bottom Right -->
                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Bottom Right Corner', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-slider-group">
                            <input 
                                type="range" 
                                name="admin_bar[border_radius_bottom_right]" 
                                value="<?php echo esc_attr( intval( $admin_bar['border_radius_bottom_right'] ?? 24 ) ); ?>"
                                min="0" 
                                max="50" 
                                step="2"
                                class="woow-slider"
                                data-type="unitless"
                                data-unit="px"
                            />
                            <span class="woow-slider-value"><?php echo esc_html( $admin_bar['border_radius_bottom_right'] ?? '24' ); ?>px</span>
                        </div>
                    </div>

                    <!-- Bottom Left -->
                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Bottom Left Corner', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-slider-group">
                            <input 
                                type="range" 
                                name="admin_bar[border_radius_bottom_left]" 
                                value="<?php echo esc_attr( intval( $admin_bar['border_radius_bottom_left'] ?? 24 ) ); ?>"
                                min="0" 
                                max="50" 
                                step="2"
                                class="woow-slider"
                                data-type="unitless"
                                data-unit="px"
                            />
                            <span class="woow-slider-value"><?php echo esc_html( $admin_bar['border_radius_bottom_left'] ?? '24' ); ?>px</span>
                        </div>
                    </div>
                </div>
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
                <!-- Font Size -->
                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Font Size', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-slider-group">
                        <input 
                            type="range" 
                            name="admin_bar[font_size]" 
                            value="<?php echo esc_attr( intval( $admin_bar['font_size'] ) ); ?>"
                            min="12" 
                            max="18" 
                            step="1"
                            class="woow-slider"
                            data-unit="px"
                        />
                        <span class="woow-slider-value"><?php echo esc_html( $admin_bar['font_size'] ); ?></span>
                    </div>
                </div>

                <!-- Font Weight -->
                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Font Weight', 'woow-admin' ); ?>
                    </label>
                    <select name="admin_bar[font_weight]" class="woow-select">
                        <option value="400" <?php selected( $admin_bar['font_weight'], '400' ); ?>>
                            <?php esc_html_e( 'Normal (400)', 'woow-admin' ); ?>
                        </option>
                        <option value="500" <?php selected( $admin_bar['font_weight'], '500' ); ?>>
                            <?php esc_html_e( 'Medium (500)', 'woow-admin' ); ?>
                        </option>
                        <option value="600" <?php selected( $admin_bar['font_weight'], '600' ); ?>>
                            <?php esc_html_e( 'Semibold (600)', 'woow-admin' ); ?>
                        </option>
                        <option value="700" <?php selected( $admin_bar['font_weight'], '700' ); ?>>
                            <?php esc_html_e( 'Bold (700)', 'woow-admin' ); ?>
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Spacing/Padding Card -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Spacing (Padding)', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <!-- Spacing Mode -->
            <div class="woow-form-group">
                <label class="woow-label">
                    <?php esc_html_e( 'Spacing Mode', 'woow-admin' ); ?>
                </label>
                <div class="woow-radio-group">
                    <label class="woow-radio-label">
                        <input 
                            type="radio" 
                            name="admin_bar[spacing_mode]" 
                            value="all" 
                            <?php checked( $admin_bar['spacing_mode'] ?? 'all', 'all' ); ?>
                            class="woow-radio woow-condition-trigger"
                            data-target="spacing_mode"
                        />
                        <span><?php esc_html_e( 'All Sides (Uniform)', 'woow-admin' ); ?></span>
                    </label>
                    <label class="woow-radio-label">
                        <input 
                            type="radio" 
                            name="admin_bar[spacing_mode]" 
                            value="individual" 
                            <?php checked( $admin_bar['spacing_mode'] ?? 'all', 'individual' ); ?>
                            class="woow-radio woow-condition-trigger"
                            data-target="spacing_mode"
                        />
                        <span><?php esc_html_e( 'Individual Sides', 'woow-admin' ); ?></span>
                    </label>
                </div>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Choose uniform spacing for all sides or set each side individually', 'woow-admin' ); ?>
                </p>
            </div>

            <!-- All Sides Spacing (visible when mode is 'all') -->
            <div class="woow-form-group woow-conditional-field" data-condition="spacing_mode" data-value="all">
                <label class="woow-label">
                    <?php esc_html_e( 'Padding (All Sides)', 'woow-admin' ); ?>
                </label>
                <div class="woow-slider-group">
                    <input 
                        type="range" 
                        name="admin_bar[spacing_all]" 
                        value="<?php echo esc_attr( intval( $admin_bar['spacing_all'] ?? 24 ) ); ?>"
                        min="0" 
                        max="64" 
                        step="4"
                        class="woow-slider"
                        data-type="unitless"
                        data-unit="px"
                    />
                    <span class="woow-slider-value"><?php echo esc_html( $admin_bar['spacing_all'] ?? '24' ); ?>px</span>
                </div>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Default: 24px. Applies to left and right padding', 'woow-admin' ); ?>
                </p>
            </div>

            <!-- Individual Sides (visible when mode is 'individual') -->
            <div class="woow-conditional-field" data-condition="spacing_mode" data-value="individual">
                <div class="woow-form-row">
                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Top Padding', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-slider-group">
                            <input 
                                type="range" 
                                name="admin_bar[spacing_top]" 
                                value="<?php echo esc_attr( intval( $admin_bar['spacing_top'] ?? 0 ) ); ?>"
                                min="0" 
                                max="32" 
                                step="2"
                                class="woow-slider"
                                data-type="unitless"
                                data-unit="px"
                            />
                            <span class="woow-slider-value"><?php echo esc_html( $admin_bar['spacing_top'] ?? '0' ); ?>px</span>
                        </div>
                    </div>

                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Bottom Padding', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-slider-group">
                            <input 
                                type="range" 
                                name="admin_bar[spacing_bottom]" 
                                value="<?php echo esc_attr( intval( $admin_bar['spacing_bottom'] ?? 0 ) ); ?>"
                                min="0" 
                                max="32" 
                                step="2"
                                class="woow-slider"
                                data-type="unitless"
                                data-unit="px"
                            />
                            <span class="woow-slider-value"><?php echo esc_html( $admin_bar['spacing_bottom'] ?? '0' ); ?>px</span>
                        </div>
                    </div>
                </div>

                <div class="woow-form-row">
                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Left Padding', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-slider-group">
                            <input 
                                type="range" 
                                name="admin_bar[spacing_left]" 
                                value="<?php echo esc_attr( intval( $admin_bar['spacing_left'] ?? 24 ) ); ?>"
                                min="0" 
                                max="64" 
                                step="4"
                                class="woow-slider"
                                data-type="unitless"
                                data-unit="px"
                            />
                            <span class="woow-slider-value"><?php echo esc_html( $admin_bar['spacing_left'] ?? '24' ); ?>px</span>
                        </div>
                    </div>

                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Right Padding', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-slider-group">
                            <input 
                                type="range" 
                                name="admin_bar[spacing_right]" 
                                value="<?php echo esc_attr( intval( $admin_bar['spacing_right'] ?? 24 ) ); ?>"
                                min="0" 
                                max="64" 
                                step="4"
                                class="woow-slider"
                                data-type="unitless"
                                data-unit="px"
                            />
                            <span class="woow-slider-value"><?php echo esc_html( $admin_bar['spacing_right'] ?? '24' ); ?>px</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Margin/Offset Card -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Margin (Offset from Edges)', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <!-- Margin Mode -->
            <div class="woow-form-group">
                <label class="woow-label">
                    <?php esc_html_e( 'Margin Mode', 'woow-admin' ); ?>
                </label>
                <div class="woow-radio-group">
                    <label class="woow-radio-label">
                        <input 
                            type="radio" 
                            name="admin_bar[margin_mode]" 
                            value="all" 
                            <?php checked( $admin_bar['margin_mode'] ?? 'all', 'all' ); ?>
                            class="woow-radio woow-condition-trigger"
                            data-target="margin_mode"
                        />
                        <span><?php esc_html_e( 'All Sides (Uniform)', 'woow-admin' ); ?></span>
                    </label>
                    <label class="woow-radio-label">
                        <input 
                            type="radio" 
                            name="admin_bar[margin_mode]" 
                            value="individual" 
                            <?php checked( $admin_bar['margin_mode'] ?? 'all', 'individual' ); ?>
                            class="woow-radio woow-condition-trigger"
                            data-target="margin_mode"
                        />
                        <span><?php esc_html_e( 'Individual Sides', 'woow-admin' ); ?></span>
                    </label>
                </div>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Distance from browser edges. Uniform applies to all sides equally.', 'woow-admin' ); ?>
                </p>
            </div>

            <!-- All Sides Margin (visible when mode is 'all') -->
            <div class="woow-form-group woow-conditional-field" data-condition="margin_mode" data-value="all">
                <label class="woow-label">
                    <?php esc_html_e( 'Margin (All Sides)', 'woow-admin' ); ?>
                </label>
                <div class="woow-slider-group">
                    <input 
                        type="range" 
                        name="admin_bar[margin_all]" 
                        value="<?php echo esc_attr( intval( $admin_bar['margin_all'] ?? 16 ) ); ?>"
                        min="0" 
                        max="64" 
                        step="4"
                        class="woow-slider"
                        data-type="unitless"
                        data-unit="px"
                    />
                    <span class="woow-slider-value"><?php echo esc_html( $admin_bar['margin_all'] ?? '16' ); ?>px</span>
                </div>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Default: 16px. Distance from all browser edges', 'woow-admin' ); ?>
                </p>
            </div>

            <!-- Individual Sides (visible when mode is 'individual') -->
            <div class="woow-conditional-field" data-condition="margin_mode" data-value="individual">
                <div class="woow-form-row">
                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Top Margin', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-slider-group">
                            <input 
                                type="range" 
                                name="admin_bar[margin_top]" 
                                value="<?php echo esc_attr( intval( $admin_bar['margin_top'] ?? 16 ) ); ?>"
                                min="0" 
                                max="64" 
                                step="4"
                                class="woow-slider"
                                data-type="unitless"
                                data-unit="px"
                            />
                            <span class="woow-slider-value"><?php echo esc_html( $admin_bar['margin_top'] ?? '16' ); ?>px</span>
                        </div>
                    </div>

                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Bottom Margin', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-slider-group">
                            <input 
                                type="range" 
                                name="admin_bar[margin_bottom]" 
                                value="<?php echo esc_attr( intval( $admin_bar['margin_bottom'] ?? 16 ) ); ?>"
                                min="0" 
                                max="64" 
                                step="4"
                                class="woow-slider"
                                data-type="unitless"
                                data-unit="px"
                            />
                            <span class="woow-slider-value"><?php echo esc_html( $admin_bar['margin_bottom'] ?? '16' ); ?>px</span>
                        </div>
                    </div>
                </div>

                <div class="woow-form-row">
                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Left Margin', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-slider-group">
                            <input 
                                type="range" 
                                name="admin_bar[margin_left]" 
                                value="<?php echo esc_attr( intval( $admin_bar['margin_left'] ?? 16 ) ); ?>"
                                min="0" 
                                max="64" 
                                step="4"
                                class="woow-slider"
                                data-type="unitless"
                                data-unit="px"
                            />
                            <span class="woow-slider-value"><?php echo esc_html( $admin_bar['margin_left'] ?? '16' ); ?>px</span>
                        </div>
                    </div>

                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Right Margin', 'woow-admin' ); ?>
                        </label>
                        <div class="woow-slider-group">
                            <input 
                                type="range" 
                                name="admin_bar[margin_right]" 
                                value="<?php echo esc_attr( intval( $admin_bar['margin_right'] ?? 16 ) ); ?>"
                                min="0" 
                                max="64" 
                                step="4"
                                class="woow-slider"
                                data-type="unitless"
                                data-unit="px"
                            />
                            <span class="woow-slider-value"><?php echo esc_html( $admin_bar['margin_right'] ?? '16' ); ?>px</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Glassmorphism Card -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3>
                <?php esc_html_e( 'Glassmorphism Effect', 'woow-admin' ); ?>
                <span class="woow-badge woow-badge-primary">
                    <?php esc_html_e( 'Recommended', 'woow-admin' ); ?>
                </span>
            </h3>
        </div>
        <div class="woow-card-body">
            <!-- Enable Glassmorphism -->
            <div class="woow-form-group">
                <label class="woow-toggle">
                    <input 
                        type="checkbox" 
                        name="admin_bar[glassmorphism]" 
                        value="1"
                        <?php checked( $admin_bar['glassmorphism'], true ); ?>
                        class="woow-toggle-input"
                        id="admin-bar-glass-toggle"
                    />
                    <span class="woow-toggle-slider"></span>
                    <span class="woow-toggle-label">
                        <?php esc_html_e( 'Enable glass effect', 'woow-admin' ); ?>
                    </span>
                </label>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Adds backdrop blur and semi-transparent background', 'woow-admin' ); ?>
                </p>
            </div>

            <!-- Blur Strength (conditional) -->
            <div class="woow-form-group woow-conditional" data-show-when="#admin-bar-glass-toggle:checked">
                <label class="woow-label">
                    <?php esc_html_e( 'Blur Strength', 'woow-admin' ); ?>
                </label>
                <div class="woow-slider-group">
                    <input 
                        type="range" 
                        name="admin_bar[blur_strength]" 
                        value="<?php echo esc_attr( intval( $admin_bar['blur_strength'] ) ); ?>"
                        min="0" 
                        max="24" 
                        step="2"
                        class="woow-slider"
                        data-unit="px"
                    />
                    <span class="woow-slider-value"><?php echo esc_html( $admin_bar['blur_strength'] ); ?></span>
                </div>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Default: 12px. Higher = more blur', 'woow-admin' ); ?>
                </p>
            </div>

            <!-- Opacity (conditional) -->
            <div class="woow-form-group woow-conditional" data-show-when="#admin-bar-glass-toggle:checked">
                <label class="woow-label">
                    <?php esc_html_e( 'Background Opacity', 'woow-admin' ); ?>
                </label>
                <div class="woow-slider-group">
                    <input 
                        type="range" 
                        name="admin_bar[opacity]" 
                        value="<?php echo esc_attr( floatval( $admin_bar['opacity'] ) * 100 ); ?>"
                        min="50" 
                        max="100" 
                        step="5"
                        class="woow-slider"
                        data-type="opacity"
                        data-unit="%"
                    />
                    <span class="woow-slider-value"><?php echo esc_html( floatval( $admin_bar['opacity'] ) * 100 ); ?>%</span>
                </div>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Default: 90%. Lower = more transparent', 'woow-admin' ); ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Effects Card -->
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
                <div class="woow-shadow-picker">
                    <?php
                    $shadows = array(
                        'none' => __( 'None', 'woow-admin' ),
                        'sm'   => __( 'Small', 'woow-admin' ),
                        'md'   => __( 'Medium', 'woow-admin' ),
                        'lg'   => __( 'Large', 'woow-admin' ),
                        'xl'   => __( 'Extra Large', 'woow-admin' ),
                    );
                    foreach ( $shadows as $value => $label ) :
                        $checked = ( $admin_bar['shadow_style'] === $value ) ? 'checked' : '';
                        ?>
                        <label class="woow-radio-card <?php echo esc_attr( $checked ); ?>">
                            <input 
                                type="radio" 
                                name="admin_bar[shadow_style]" 
                                value="<?php echo esc_attr( $value ); ?>"
                                <?php checked( $admin_bar['shadow_style'], $value ); ?>
                            />
                            <span class="woow-radio-card-box woow-shadow-<?php echo esc_attr( $value ); ?>"></span>
                            <span class="woow-radio-card-label"><?php echo esc_html( $label ); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Position -->
            <div class="woow-form-group">
                <label class="woow-label">
                    <?php esc_html_e( 'Position', 'woow-admin' ); ?>
                </label>
                <select name="admin_bar[position]" class="woow-select">
                    <option value="fixed" <?php selected( $admin_bar['position'], 'fixed' ); ?>>
                        <?php esc_html_e( 'Fixed (stays at top on scroll)', 'woow-admin' ); ?>
                    </option>
                    <option value="sticky" <?php selected( $admin_bar['position'], 'sticky' ); ?>>
                        <?php esc_html_e( 'Sticky (floats with gap)', 'woow-admin' ); ?>
                    </option>
                </select>
            </div>

            <!-- Top Offset (conditional) -->
            <div class="woow-form-group woow-conditional" data-show-when="admin_bar[position]=sticky">
                <label class="woow-label">
                    <?php esc_html_e( 'Top Offset', 'woow-admin' ); ?>
                </label>
                <div class="woow-slider-group">
                    <input 
                        type="range" 
                        name="admin_bar[top_offset]" 
                        value="<?php echo esc_attr( intval( $admin_bar['top_offset'] ) ); ?>"
                        min="0" 
                        max="32" 
                        step="4"
                        class="woow-slider"
                        data-unit="px"
                    />
                    <span class="woow-slider-value"><?php echo esc_html( $admin_bar['top_offset'] ); ?></span>
                </div>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Default: 16px. Gap from top of screen', 'woow-admin' ); ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Custom CSS Card -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3>
                <?php esc_html_e( 'Custom CSS', 'woow-admin' ); ?>
                <span class="woow-badge woow-badge-secondary">
                    <?php esc_html_e( 'Advanced', 'woow-admin' ); ?>
                </span>
            </h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-group">
                <label class="woow-label">
                    <?php esc_html_e( 'Additional CSS for Admin Bar', 'woow-admin' ); ?>
                </label>
                <textarea 
                    name="admin_bar[custom_css]" 
                    class="woow-code-editor"
                    rows="8"
                    placeholder="/* Your custom CSS here */"
                    spellcheck="false"
                    style="width: 100%; min-width: 100%;"
                ><?php echo esc_textarea( $admin_bar['custom_css'] ); ?></textarea>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Advanced users: Add custom CSS rules for #wpadminbar', 'woow-admin' ); ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Reset Button -->
    <div class="woow-card-footer">
        <button 
            type="button" 
            class="button woow-button-secondary woow-reset-section" 
            data-section="admin_bar"
        >
            <?php esc_html_e( 'Reset to Defaults', 'woow-admin' ); ?>
        </button>
    </div>
</div>
