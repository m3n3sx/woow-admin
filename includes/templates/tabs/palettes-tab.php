<?php
/**
 * Color Palettes Tab Template
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$palettes = $this->settings->get_available_palettes();
$current_palette = $settings['general']['current_palette'] ?? 'professional_blue';
?>

<div class="woow-tab-pane" id="tab-palettes">
    <!-- Section Header -->
    <div class="woow-section-header">
        <h2><?php esc_html_e( 'Color Palettes', 'woow-admin' ); ?></h2>
        <p class="woow-section-description">
            <?php esc_html_e( 'Choose from 10 professionally designed color palettes or customize individual colors.', 'woow-admin' ); ?>
        </p>
    </div>

    <!-- Palette Selector -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Available Palettes', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <?php include WOOW_PLUGIN_DIR . 'includes/templates/components/palette-selector.php'; ?>
        </div>
    </div>

    <!-- Custom Color Overrides -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3>
                <?php esc_html_e( 'Custom Color Overrides', 'woow-admin' ); ?>
                <span class="woow-badge woow-badge-secondary">
                    <?php esc_html_e( 'Advanced', 'woow-admin' ); ?>
                </span>
            </h3>
        </div>
        <div class="woow-card-body">
            <p class="woow-field-description" style="margin-bottom: 20px;">
                <?php esc_html_e( 'Override specific colors from the active palette. Leave empty to use palette defaults.', 'woow-admin' ); ?>
            </p>

            <?php
            $color_overrides = $settings['color_overrides'] ?? [];
            $color_labels = [
                'primary' => __( 'Primary Color', 'woow-admin' ),
                'secondary' => __( 'Secondary Color', 'woow-admin' ),
                'background' => __( 'Background Color', 'woow-admin' ),
                'card' => __( 'Card Background', 'woow-admin' ),
                'foreground' => __( 'Text Color', 'woow-admin' ),
                'border' => __( 'Border Color', 'woow-admin' ),
                'muted_foreground' => __( 'Muted Text', 'woow-admin' ),
                'accent' => __( 'Accent Color', 'woow-admin' ),
                'destructive' => __( 'Destructive Color', 'woow-admin' ),
            ];
            ?>

            <div class="woow-color-overrides-grid">
                <?php foreach ( $color_labels as $key => $label ) : 
                    // Provide default colors for color picker (HTML5 requires valid hex)
                    $default_colors = [
                        'primary' => '#6366f1',
                        'secondary' => '#8b5cf6',
                        'background' => '#fafafa',
                        'card' => '#ffffff',
                        'foreground' => '#0f172a',
                        'border' => '#e2e8f0',
                        'muted_foreground' => '#64748b',
                        'accent' => '#a78bfa',
                        'destructive' => '#ef4444',
                    ];
                    $color_value = $color_overrides[ $key ] ?? '';
                    $display_value = ! empty( $color_value ) ? $color_value : ( $default_colors[ $key ] ?? '#6366f1' );
                ?>
                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php echo esc_html( $label ); ?>
                        </label>
                        <div class="woow-color-picker-group">
                            <input 
                                type="color" 
                                name="color_overrides[<?php echo esc_attr( $key ); ?>]" 
                                value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex( $display_value, $default_colors[ $key ] ?? '#6366f1' ) ); ?>"
                                class="woow-color-input"
                                data-default="<?php echo esc_attr( $default_colors[ $key ] ?? '#6366f1' ); ?>"
                            />
                            <input 
                                type="text" 
                                value="<?php echo esc_attr( $color_value ); ?>"
                                class="woow-color-text"
                                pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$"
                                placeholder="<?php esc_attr_e( 'Use palette default', 'woow-admin' ); ?>"
                            />
                            <button type="button" class="woow-color-reset button" title="<?php esc_attr_e( 'Clear Override', 'woow-admin' ); ?>">
                                ✕
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Palette Preview -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Active Palette Preview', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-palette-preview" id="active-palette-preview">
                <?php
                $active_palette = $palettes[ $current_palette ] ?? $palettes['professional_blue'];
                ?>
                <div class="woow-preview-colors">
                    <?php foreach ( $active_palette['colors'] as $color_key => $color_value ) : ?>
                        <div class="woow-preview-color-item">
                            <div class="woow-preview-color-swatch" 
                                 style="background-color: <?php echo esc_attr( $color_value ); ?>;">
                            </div>
                            <div class="woow-preview-color-info">
                                <span class="woow-preview-color-name">
                                    <?php echo esc_html( ucwords( str_replace( '_', ' ', $color_key ) ) ); ?>
                                </span>
                                <span class="woow-preview-color-value">
                                    <?php echo esc_html( $color_value ); ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
