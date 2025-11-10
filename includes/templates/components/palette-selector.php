<?php
/**
 * Palette Selector Component
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

<div class="woow-palette-selector">
    <div class="woow-palette-grid">
        <?php foreach ( $palettes as $palette_id => $palette ) : ?>
            <div class="woow-palette-card <?php echo $palette_id === $current_palette ? 'active' : ''; ?>" 
                 data-palette="<?php echo esc_attr( $palette_id ); ?>">
                
                <div class="woow-palette-colors">
                    <?php
                    $display_colors = [
                        $palette['colors']['primary'],
                        $palette['colors']['secondary'],
                        $palette['colors']['accent'],
                        $palette['colors']['background'],
                        $palette['colors']['card']
                    ];
                    
                    foreach ( $display_colors as $color ) :
                    ?>
                        <div class="woow-palette-color" 
                             style="background-color: <?php echo esc_attr( $color ); ?>;"
                             title="<?php echo esc_attr( $color ); ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="woow-palette-info">
                    <h4 class="woow-palette-name"><?php echo esc_html( $palette['name'] ); ?></h4>
                    <p class="woow-palette-description"><?php echo esc_html( $palette['description'] ); ?></p>
                </div>
                
                <?php if ( $palette_id === $current_palette ) : ?>
                    <div class="woow-palette-badge">
                        <span class="dashicons dashicons-yes-alt"></span>
                        <?php esc_html_e( 'Active', 'woow-admin' ); ?>
                    </div>
                <?php endif; ?>
                
                <button type="button" 
                        class="woow-palette-apply woow-button woow-button-primary"
                        data-palette="<?php echo esc_attr( $palette_id ); ?>">
                    <?php esc_html_e( 'Apply Palette', 'woow-admin' ); ?>
                </button>
            </div>
        <?php endforeach; ?>
    </div>
</div>
