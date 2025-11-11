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
    'height'            => '48px',
    'width'             => '100',
    'width_unit'        => '%',
    'border_radius'     => '24px',
    'font_size'         => '14px',
    'font_weight'       => '600',
    'glassmorphism'     => true,
    'blur_strength'     => '12px',
    'opacity'           => 0.9,
    'shadow_style'      => 'md',
    'position'          => 'fixed',
    'top_offset'        => '16px',
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
                            value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $admin_bar['hover_bg_color'] ?? '', '#ffffff' ) ); ?>"
                            data-default="#ffffff"
                            class="woow-color-input"
                        />
                        <input 
                            type="text" 
                            value="<?php echo esc_attr( $admin_bar['hover_bg_color'] ); ?>"
                            class="woow-color-text"
                        />
                    </div>
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

                <!-- Border Radius -->
                <div class="woow-form-group">
                    <label class="woow-label">
                        <?php esc_html_e( 'Border Radius', 'woow-admin' ); ?>
                    </label>
                    <div class="woow-slider-group">
                        <input 
                            type="range" 
                            name="admin_bar[border_radius]" 
                            value="<?php echo esc_attr( intval( $admin_bar['border_radius'] ) ); ?>"
                            min="0" 
                            max="32" 
                            step="2"
                            class="woow-slider"
                            data-unit="px"
                        />
                        <span class="woow-slider-value"><?php echo esc_html( $admin_bar['border_radius'] ); ?></span>
                    </div>
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Default: 24px (rounded-3xl)', 'woow-admin' ); ?>
                    </p>
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
