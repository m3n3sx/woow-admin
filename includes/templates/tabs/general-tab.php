<?php
/**
 * General Tab Template
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$general = $this->settings->get_section( 'general' );
$metrics = $this->cache->get_stats();
?>

<div class="woow-tab-pane active" id="tab-general">
    <!-- Section Header -->
    <div class="woow-section-header">
        <h2><?php esc_html_e( 'General Settings', 'woow-admin' ); ?></h2>
        <p class="woow-section-description">
            <?php esc_html_e( 'Configure global plugin settings, auto palette switching, and view performance metrics.', 'woow-admin' ); ?>
        </p>
    </div>

    <!-- Enable/Disable Plugin -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Plugin Status', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <label class="woow-toggle">
                <input 
                    type="checkbox" 
                    name="general[enabled]" 
                    value="1"
                    <?php checked( $general['enabled'], true ); ?>
                    class="woow-toggle-input"
                />
                <span class="woow-toggle-slider"></span>
                <span class="woow-toggle-label">
                    <?php esc_html_e( 'Enable WOOW! Admin styling', 'woow-admin' ); ?>
                </span>
            </label>
            <p class="woow-field-description">
                <?php esc_html_e( 'Turn off to temporarily disable all custom styling without losing your settings.', 'woow-admin' ); ?>
            </p>
        </div>
    </div>

    <!-- Current Configuration -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Current Configuration', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-config-display">
                <div class="woow-config-item">
                    <span class="woow-config-label"><?php esc_html_e( 'Active Palette:', 'woow-admin' ); ?></span>
                    <span class="woow-config-value">
                        <?php echo esc_html( ucwords( str_replace( '_', ' ', $general['current_palette'] ?? 'professional_blue' ) ) ); ?>
                    </span>
                </div>
                
                <div class="woow-config-item">
                    <span class="woow-config-label"><?php esc_html_e( 'Active Template:', 'woow-admin' ); ?></span>
                    <span class="woow-config-value">
                        <?php echo esc_html( ucwords( str_replace( '_', ' ', $general['current_template'] ?? 'default' ) ) ); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Auto Palette Switching -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3>
                <?php esc_html_e( 'Auto Palette Switching', 'woow-admin' ); ?>
                <span class="woow-badge woow-badge-primary">
                    <?php esc_html_e( 'Smart', 'woow-admin' ); ?>
                </span>
            </h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-form-group">
                <label class="woow-toggle">
                    <input 
                        type="checkbox" 
                        name="general[auto_palette_switch]" 
                        value="1"
                        <?php checked( $general['auto_palette_switch'], true ); ?>
                        class="woow-toggle-input"
                        id="auto-palette-toggle"
                    />
                    <span class="woow-toggle-slider"></span>
                    <span class="woow-toggle-label">
                        <?php esc_html_e( 'Enable automatic palette switching based on time of day', 'woow-admin' ); ?>
                    </span>
                </label>
                <p class="woow-field-description">
                    <?php esc_html_e( 'Automatically switch between light and dark palettes based on configured times.', 'woow-admin' ); ?>
                </p>
            </div>

            <div class="woow-conditional" data-show-when="#auto-palette-toggle:checked">
                <div class="woow-form-row">
                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Light Palette', 'woow-admin' ); ?>
                        </label>
                        <select name="general[light_palette]" class="woow-select">
                            <?php
                            $palettes = $this->settings->get_available_palettes();
                            foreach ( $palettes as $palette_id => $palette ) :
                            ?>
                                <option value="<?php echo esc_attr( $palette_id ); ?>" 
                                        <?php selected( $general['light_palette'], $palette_id ); ?>>
                                    <?php echo esc_html( $palette['name'] ); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Dark Palette', 'woow-admin' ); ?>
                        </label>
                        <select name="general[dark_palette]" class="woow-select">
                            <?php foreach ( $palettes as $palette_id => $palette ) : ?>
                                <option value="<?php echo esc_attr( $palette_id ); ?>" 
                                        <?php selected( $general['dark_palette'], $palette_id ); ?>>
                                    <?php echo esc_html( $palette['name'] ); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="woow-form-row">
                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Switch to Light at', 'woow-admin' ); ?>
                        </label>
                        <input 
                            type="time" 
                            name="general[switch_time_light]" 
                            value="<?php echo esc_attr( $general['switch_time_light'] ?? '06:00' ); ?>"
                            class="woow-input"
                        />
                        <p class="woow-field-description">
                            <?php esc_html_e( 'Default: 06:00 (6:00 AM)', 'woow-admin' ); ?>
                        </p>
                    </div>

                    <div class="woow-form-group">
                        <label class="woow-label">
                            <?php esc_html_e( 'Switch to Dark at', 'woow-admin' ); ?>
                        </label>
                        <input 
                            type="time" 
                            name="general[switch_time_dark]" 
                            value="<?php echo esc_attr( $general['switch_time_dark'] ?? '18:00' ); ?>"
                            class="woow-input"
                        />
                        <p class="woow-field-description">
                            <?php esc_html_e( 'Default: 18:00 (6:00 PM)', 'woow-admin' ); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="woow-card">
        <div class="woow-card-header">
            <h3><?php esc_html_e( 'Performance Metrics', 'woow-admin' ); ?></h3>
        </div>
        <div class="woow-card-body">
            <div class="woow-metrics-grid">
                <div class="woow-metric-card">
                    <div class="woow-metric-icon">
                        <span class="dashicons dashicons-media-code"></span>
                    </div>
                    <div class="woow-metric-content">
                        <span class="woow-metric-label"><?php esc_html_e( 'CSS Size', 'woow-admin' ); ?></span>
                        <span class="woow-metric-value" id="metric-css-size">
                            <?php echo esc_html( $metrics['css_size'] ?? '--' ); ?>
                        </span>
                    </div>
                </div>

                <div class="woow-metric-card">
                    <div class="woow-metric-icon">
                        <span class="dashicons dashicons-clock"></span>
                    </div>
                    <div class="woow-metric-content">
                        <span class="woow-metric-label"><?php esc_html_e( 'Generation Time', 'woow-admin' ); ?></span>
                        <span class="woow-metric-value" id="metric-gen-time">
                            <?php echo esc_html( $metrics['generation_time'] ?? '--' ); ?>
                        </span>
                    </div>
                </div>

                <div class="woow-metric-card">
                    <div class="woow-metric-icon">
                        <span class="dashicons dashicons-performance"></span>
                    </div>
                    <div class="woow-metric-content">
                        <span class="woow-metric-label"><?php esc_html_e( 'Cache Hit Rate', 'woow-admin' ); ?></span>
                        <span class="woow-metric-value" id="metric-cache-rate">
                            <?php echo esc_html( $metrics['cache_hit_rate'] ?? '--' ); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
