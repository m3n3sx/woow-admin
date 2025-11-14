<?php
/**
 * Backgrounds Tab Template
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define default values for backgrounds
$defaults = array(
    'enabled'                 => true,
    'background_color'        => '#dbeafe',
    'background_opacity'      => '1',
    'type'                    => 'gradient',
    'gradient_type'           => 'linear',
    'gradient_start'          => '#dbeafe',
    'gradient_end'            => '#e0e7ff',
    'gradient_angle'          => '135',
    'image_url'               => '',
    'image_position'          => 'center',
    'image_size'              => 'cover',
    'image_repeat'            => 'no-repeat',
    'wpbody_content_color'    => 'transparent',
    'wpbody_content_opacity'  => '1',
    'custom_css'              => '',
);

// Merge with saved settings
$backgrounds = array_merge( $defaults, $this->settings->get_section( 'backgrounds' ) ?? array() );
?>

<div class="woow-tab-pane" id="tab-backgrounds">
    <div class="woow-section-header">
        <h2><?php esc_html_e( 'Background Customization', 'woow-admin' ); ?></h2>
        <p class="woow-section-description">
            <?php esc_html_e( 'Customize the admin panel background with colors, gradients, patterns, or images.', 'woow-admin' ); ?>
        </p>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Enable Background Styling', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <label class="woow-toggle">
                <input type="checkbox" name="backgrounds[enabled]" value="1" <?php checked( $backgrounds['enabled'], true ); ?> class="woow-toggle-input" />
                <span class="woow-toggle-slider"></span>
                <span class="woow-toggle-label"><?php esc_html_e( 'Apply custom background', 'woow-admin' ); ?></span>
            </label>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Body Background Color', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-group">
                <label class="woow-label"><?php esc_html_e( 'Background Color', 'woow-admin' ); ?></label>
                <div class="woow-color-picker-group">
                    <input 
                        type="color" 
                        name="backgrounds[background_color]" 
                        value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $backgrounds['background_color'] ?? '', '#dbeafe' ) ); ?>"
                        data-default="#dbeafe"
                        class="woow-color-input"
                    />
                    <input 
                        type="text" 
                        value="<?php echo esc_attr( $backgrounds['background_color'] ); ?>"
                        class="woow-color-text"
                    />
                    <button type="button" class="woow-color-reset button" title="<?php esc_attr_e( 'Reset', 'woow-admin' ); ?>">
                        ↺
                    </button>
                </div>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Base background color for body.wp-admin. Default: #dbeafe (Blue 100)', 'woow-admin' ); ?>
                </p>
            </div>
            
            <div class="woow-form-group">
                <label class="woow-label"><?php esc_html_e( 'Color Opacity', 'woow-admin' ); ?></label>
                <div class="woow-slider-group">
                    <input 
                        type="range" 
                        name="backgrounds[background_opacity]" 
                        value="<?php echo esc_attr( floatval( $backgrounds['background_opacity'] ?? 1 ) * 100 ); ?>" 
                        min="0" 
                        max="100" 
                        step="5" 
                        class="woow-slider" 
                        data-type="opacity"
                    />
                    <span class="woow-slider-value"><?php echo esc_html( round( floatval( $backgrounds['background_opacity'] ?? 1 ) * 100 ) ); ?>%</span>
                </div>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Adjust the transparency of the background color. 100% = fully opaque, 0% = fully transparent.', 'woow-admin' ); ?>
                </p>
            </div>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Additional Background Effects', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-group">
                <label class="woow-label"><?php esc_html_e( 'Effect Type', 'woow-admin' ); ?></label>
                <select name="backgrounds[type]" class="woow-select" id="bg-type-select">
                    <option value="none" <?php selected( $backgrounds['type'], 'none' ); ?>><?php esc_html_e( 'None (Solid Color Only)', 'woow-admin' ); ?></option>
                    <option value="gradient" <?php selected( $backgrounds['type'], 'gradient' ); ?>><?php esc_html_e( 'Gradient Overlay', 'woow-admin' ); ?></option>
                    <option value="image" <?php selected( $backgrounds['type'], 'image' ); ?>><?php esc_html_e( 'Background Image', 'woow-admin' ); ?></option>
                </select>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Add gradient or image effects on top of the base background color.', 'woow-admin' ); ?>
                </p>
            </div>

            <!-- Gradient -->
            <div class="woow-conditional" data-show-when="#bg-type-select=gradient">
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Gradient Type', 'woow-admin' ); ?></label>
                    <select name="backgrounds[gradient_type]" class="woow-select">
                        <option value="linear" <?php selected( $backgrounds['gradient_type'], 'linear' ); ?>><?php esc_html_e( 'Linear', 'woow-admin' ); ?></option>
                        <option value="radial" <?php selected( $backgrounds['gradient_type'], 'radial' ); ?>><?php esc_html_e( 'Radial', 'woow-admin' ); ?></option>
                        <option value="conic" <?php selected( $backgrounds['gradient_type'], 'conic' ); ?>><?php esc_html_e( 'Conic', 'woow-admin' ); ?></option>
                    </select>
                </div>
                <div class="woow-form-row">
                    <div class="woow-form-group">
                        <label class="woow-label"><?php esc_html_e( 'Start Color', 'woow-admin' ); ?></label>
                        <div class="woow-color-picker-group">
                            <input 
                                type="color" 
                                name="backgrounds[gradient_start]" 
                                value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $backgrounds['gradient_start'] ?? '', '#dbeafe' ) ); ?>"
                                data-default="#dbeafe"
                                class="woow-color-input"
                            />
                            <input 
                                type="text" 
                                value="<?php echo esc_attr( $backgrounds['gradient_start'] ); ?>"
                                class="woow-color-text"
                            />
                            <button type="button" class="woow-color-reset button" title="<?php esc_attr_e( 'Reset', 'woow-admin' ); ?>">
                                ↺
                            </button>
                        </div>
                        <p class="woow-field-description">
                            <?php esc_html_e( 'Default: #dbeafe (Blue 100)', 'woow-admin' ); ?>
                        </p>
                    </div>
                    <div class="woow-form-group">
                        <label class="woow-label"><?php esc_html_e( 'End Color', 'woow-admin' ); ?></label>
                        <div class="woow-color-picker-group">
                            <input 
                                type="color" 
                                name="backgrounds[gradient_end]" 
                                value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $backgrounds['gradient_end'] ?? '', '#e0e7ff' ) ); ?>"
                                data-default="#e0e7ff"
                                class="woow-color-input"
                            />
                            <input 
                                type="text" 
                                value="<?php echo esc_attr( $backgrounds['gradient_end'] ); ?>"
                                class="woow-color-text"
                            />
                            <button type="button" class="woow-color-reset button" title="<?php esc_attr_e( 'Reset', 'woow-admin' ); ?>">
                                ↺
                            </button>
                        </div>
                        <p class="woow-field-description">
                            <?php esc_html_e( 'Default: #e0e7ff (Indigo 100)', 'woow-admin' ); ?>
                        </p>
                    </div>
                </div>
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Angle', 'woow-admin' ); ?></label>
                    <div class="woow-slider-group">
                        <input type="range" name="backgrounds[gradient_angle]" value="<?php echo esc_attr( intval( $backgrounds['gradient_angle'] ) ); ?>" min="0" max="360" step="15" class="woow-slider" data-type="unitless" data-unit="°" />
                        <span class="woow-slider-value"><?php echo esc_html( $backgrounds['gradient_angle'] ); ?>°</span>
                    </div>
                </div>
            </div>

            <!-- Image -->
            <div class="woow-conditional" data-show-when="#bg-type-select=image">
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Background Image', 'woow-admin' ); ?></label>
                    <div class="woow-image-upload-container">
                        <input 
                            type="hidden" 
                            name="backgrounds[image_url]" 
                            value="<?php echo esc_attr( $backgrounds['image_url'] ); ?>" 
                            id="bg-image-url"
                        />
                        <div class="woow-upload-controls" style="display: flex; gap: 10px; align-items: center; margin-bottom: 10px;">
                            <input 
                                type="file" 
                                id="bg-image-file" 
                                accept="image/*"
                                style="display: none;"
                            />
                            <button type="button" class="button" id="bg-upload-btn">
                                <?php esc_html_e( 'Upload Image', 'woow-admin' ); ?>
                            </button>
                            <span id="bg-upload-status" style="color: #666; font-size: 13px;"></span>
                        </div>
                        <input 
                            type="text" 
                            id="bg-image-url-display"
                            value="<?php echo esc_attr( $backgrounds['image_url'] ); ?>"
                            class="woow-input"
                            placeholder="<?php esc_attr_e( 'Or paste image URL here', 'woow-admin' ); ?>"
                            style="width: 100%; margin-bottom: 10px;"
                        />
                        <?php if ( ! empty( $backgrounds['image_url'] ) ) : ?>
                            <img src="<?php echo esc_url( $backgrounds['image_url'] ); ?>" id="bg-image-preview" class="woow-image-preview" style="max-width: 200px; display: block; margin-top: 10px; border-radius: 8px; border: 1px solid #ddd;" />
                        <?php else : ?>
                            <img id="bg-image-preview" class="woow-image-preview" style="max-width: 200px; display: none; margin-top: 10px; border-radius: 8px; border: 1px solid #ddd;" />
                        <?php endif; ?>
                    </div>
                    <p class="woow-field-description">
                        <?php esc_html_e( 'Upload an image or paste a URL. Recommended size: 1920x1080px or larger.', 'woow-admin' ); ?>
                    </p>
                </div>
                <div class="woow-form-row">
                    <div class="woow-form-group">
                        <label class="woow-label"><?php esc_html_e( 'Position', 'woow-admin' ); ?></label>
                        <select name="backgrounds[image_position]" class="woow-select">
                            <option value="center" <?php selected( $backgrounds['image_position'], 'center' ); ?>><?php esc_html_e( 'Center', 'woow-admin' ); ?></option>
                            <option value="top" <?php selected( $backgrounds['image_position'], 'top' ); ?>><?php esc_html_e( 'Top', 'woow-admin' ); ?></option>
                            <option value="bottom" <?php selected( $backgrounds['image_position'], 'bottom' ); ?>><?php esc_html_e( 'Bottom', 'woow-admin' ); ?></option>
                            <option value="left" <?php selected( $backgrounds['image_position'], 'left' ); ?>><?php esc_html_e( 'Left', 'woow-admin' ); ?></option>
                            <option value="right" <?php selected( $backgrounds['image_position'], 'right' ); ?>><?php esc_html_e( 'Right', 'woow-admin' ); ?></option>
                        </select>
                    </div>
                    <div class="woow-form-group">
                        <label class="woow-label"><?php esc_html_e( 'Size', 'woow-admin' ); ?></label>
                        <select name="backgrounds[image_size]" class="woow-select">
                            <option value="cover" <?php selected( $backgrounds['image_size'], 'cover' ); ?>><?php esc_html_e( 'Cover', 'woow-admin' ); ?></option>
                            <option value="contain" <?php selected( $backgrounds['image_size'], 'contain' ); ?>><?php esc_html_e( 'Contain', 'woow-admin' ); ?></option>
                            <option value="auto" <?php selected( $backgrounds['image_size'], 'auto' ); ?>><?php esc_html_e( 'Auto', 'woow-admin' ); ?></option>
                        </select>
                    </div>
                </div>
                <div class="woow-form-group">
                    <label class="woow-label"><?php esc_html_e( 'Repeat', 'woow-admin' ); ?></label>
                    <select name="backgrounds[image_repeat]" class="woow-select">
                        <option value="no-repeat" <?php selected( $backgrounds['image_repeat'], 'no-repeat' ); ?>><?php esc_html_e( 'No Repeat', 'woow-admin' ); ?></option>
                        <option value="repeat" <?php selected( $backgrounds['image_repeat'], 'repeat' ); ?>><?php esc_html_e( 'Repeat', 'woow-admin' ); ?></option>
                        <option value="repeat-x" <?php selected( $backgrounds['image_repeat'], 'repeat-x' ); ?>><?php esc_html_e( 'Repeat X', 'woow-admin' ); ?></option>
                        <option value="repeat-y" <?php selected( $backgrounds['image_repeat'], 'repeat-y' ); ?>><?php esc_html_e( 'Repeat Y', 'woow-admin' ); ?></option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Content Area Background', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-group">
                <label class="woow-label"><?php esc_html_e( 'Content Background Color', 'woow-admin' ); ?></label>
                <div class="woow-color-picker-group">
                    <input 
                        type="color" 
                        name="backgrounds[wpbody_content_color]" 
                        value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $backgrounds['wpbody_content_color'] ?? '', 'transparent' ) ); ?>"
                        data-default="transparent"
                        class="woow-color-input"
                    />
                    <input 
                        type="text" 
                        value="<?php echo esc_attr( $backgrounds['wpbody_content_color'] ?? 'transparent' ); ?>"
                        class="woow-color-text"
                        placeholder="transparent"
                    />
                    <button type="button" class="woow-color-reset button" title="<?php esc_attr_e( 'Reset', 'woow-admin' ); ?>">
                        ↺
                    </button>
                </div>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Background color for the main content area (#wpbody-content). Use "transparent" to show the body background.', 'woow-admin' ); ?>
                </p>
            </div>
            
            <div class="woow-form-group">
                <label class="woow-label"><?php esc_html_e( 'Content Area Opacity', 'woow-admin' ); ?></label>
                <div class="woow-slider-group">
                    <input 
                        type="range" 
                        name="backgrounds[wpbody_content_opacity]" 
                        value="<?php echo esc_attr( floatval( $backgrounds['wpbody_content_opacity'] ?? 1 ) * 100 ); ?>" 
                        min="0" 
                        max="100" 
                        step="5" 
                        class="woow-slider" 
                        data-type="opacity"
                    />
                    <span class="woow-slider-value"><?php echo esc_html( round( floatval( $backgrounds['wpbody_content_opacity'] ?? 1 ) * 100 ) ); ?>%</span>
                </div>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Adjust the transparency of the content area background. 100% = fully opaque, 0% = fully transparent.', 'woow-admin' ); ?>
                </p>
            </div>
        </div>
    </div>

    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Custom CSS', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <textarea name="backgrounds[custom_css]" class="woow-code-editor" rows="8" placeholder="/* Your custom CSS here */" spellcheck="false"><?php echo esc_textarea( $backgrounds['custom_css'] ); ?></textarea>
        </div>
    </div>

    <div class="woow-card-footer">
        <button type="button" class="button woow-button-secondary woow-reset-section" data-section="backgrounds"><?php esc_html_e( 'Reset to Defaults', 'woow-admin' ); ?></button>
    </div>
</div>
