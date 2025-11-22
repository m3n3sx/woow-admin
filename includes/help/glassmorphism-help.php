<?php
/**
 * Glassmorphism Contextual Help
 * 
 * This file provides inline help content for the glassmorphism settings.
 * Can be displayed as tooltips, help panels, or contextual documentation.
 *
 * @package WOOW_Admin
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Get glassmorphism help content
 *
 * @param string $section The help section to retrieve
 * @return string HTML help content
 */
function woow_get_glassmorphism_help( $section = 'overview' ) {
    $help_content = array(
        
        // Overview section
        'overview' => array(
            'title' => __( 'What is Glassmorphism?', 'woow-admin' ),
            'content' => __(
                'Glassmorphism is a modern design style that creates a frosted glass effect on interface elements. ' .
                'It combines backdrop blur, semi-transparent backgrounds, subtle borders, and soft shadows to create ' .
                'a translucent, layered appearance that adds depth and visual interest to your WordPress admin.',
                'woow-admin'
            ),
            'icon' => '✨',
        ),
        
        // Enable/Disable section
        'toggle' => array(
            'title' => __( 'Global Toggle', 'woow-admin' ),
            'content' => __(
                'The global toggle enables or disables glassmorphism effects across your entire admin interface. ' .
                'When enabled, the frosted glass effect is applied to the admin bar, admin menu, and dashboard widgets. ' .
                'When disabled, these elements return to solid backgrounds.',
                'woow-admin'
            ),
            'icon' => '🔘',
        ),
        
        // Strength levels section
        'strength' => array(
            'title' => __( 'Strength Levels', 'woow-admin' ),
            'content' => __(
                'Choose from 4 strength levels to control the intensity of the glass effect:',
                'woow-admin'
            ),
            'icon' => '🎚️',
            'list' => array(
                array(
                    'label' => __( 'Light (4px blur)', 'woow-admin' ),
                    'description' => __( 'Subtle effect, best for text-heavy areas and maximum readability', 'woow-admin' ),
                ),
                array(
                    'label' => __( 'Medium (8px blur)', 'woow-admin' ),
                    'description' => __( 'Balanced effect, recommended default for general use', 'woow-admin' ),
                ),
                array(
                    'label' => __( 'Strong (12px blur)', 'woow-admin' ),
                    'description' => __( 'Pronounced effect, ideal for decorative elements', 'woow-admin' ),
                ),
                array(
                    'label' => __( 'Extra Strong (16px blur)', 'woow-admin' ),
                    'description' => __( 'Maximum effect, use sparingly for dramatic impact', 'woow-admin' ),
                ),
            ),
        ),
        
        // Browser compatibility section
        'compatibility' => array(
            'title' => __( 'Browser Compatibility', 'woow-admin' ),
            'content' => __(
                'Glassmorphism requires modern browsers that support the CSS backdrop-filter property. ' .
                'Older browsers automatically fall back to solid, semi-transparent backgrounds.',
                'woow-admin'
            ),
            'icon' => '🌐',
            'supported' => array(
                'Chrome 76+' => true,
                'Safari 9+' => true,
                'Firefox 103+' => true,
                'Edge 79+' => true,
            ),
        ),
        
        // Dark mode section
        'dark_mode' => array(
            'title' => __( 'Dark Mode Support', 'woow-admin' ),
            'content' => __(
                'Glassmorphism automatically adapts to your system\'s color scheme. ' .
                'In dark mode, the effect uses darker backgrounds with adjusted opacity values ' .
                'to maintain readability and visual impact.',
                'woow-admin'
            ),
            'icon' => '🌙',
        ),
        
        // Performance section
        'performance' => array(
            'title' => __( 'Performance', 'woow-admin' ),
            'content' => __(
                'Glassmorphism uses hardware-accelerated CSS (backdrop-filter) processed by your GPU. ' .
                'The effect is optimized to apply only to major interface elements, ensuring minimal ' .
                'performance impact (typically less than 50ms on page load).',
                'woow-admin'
            ),
            'icon' => '⚡',
            'tips' => array(
                __( 'Use Light or Medium strength for daily work', 'woow-admin' ),
                __( 'Reduce strength if you experience lag', 'woow-admin' ),
                __( 'Keep your browser updated for best performance', 'woow-admin' ),
                __( 'Disable on older/slower hardware', 'woow-admin' ),
            ),
        ),
        
        // Troubleshooting section
        'troubleshooting' => array(
            'title' => __( 'Troubleshooting', 'woow-admin' ),
            'icon' => '🔧',
            'issues' => array(
                array(
                    'problem' => __( 'Glassmorphism not appearing', 'woow-admin' ),
                    'solutions' => array(
                        __( 'Check your browser version (see compatibility requirements)', 'woow-admin' ),
                        __( 'Verify the global toggle is enabled', 'woow-admin' ),
                        __( 'Clear browser cache (Ctrl+Shift+R or Cmd+Shift+R)', 'woow-admin' ),
                        __( 'Re-save settings by clicking "Apply Changes"', 'woow-admin' ),
                    ),
                ),
                array(
                    'problem' => __( 'Performance lag or slow rendering', 'woow-admin' ),
                    'solutions' => array(
                        __( 'Reduce strength level to Light or Medium', 'woow-admin' ),
                        __( 'Update your browser to the latest version', 'woow-admin' ),
                        __( 'Disable glassmorphism on slower machines', 'woow-admin' ),
                        __( 'Check for conflicting plugins or browser extensions', 'woow-admin' ),
                    ),
                ),
                array(
                    'problem' => __( 'Text hard to read', 'woow-admin' ),
                    'solutions' => array(
                        __( 'Reduce to Light or Medium strength level', 'woow-admin' ),
                        __( 'Use simpler backgrounds (avoid complex patterns)', 'woow-admin' ),
                        __( 'Adjust text colors for better contrast', 'woow-admin' ),
                        __( 'Test in both light and dark modes', 'woow-admin' ),
                    ),
                ),
            ),
        ),
        
        // Best practices section
        'best_practices' => array(
            'title' => __( 'Best Practices', 'woow-admin' ),
            'icon' => '💡',
            'dos' => array(
                __( 'Start with Medium strength and adjust from there', 'woow-admin' ),
                __( 'Test appearance in both light and dark modes', 'woow-admin' ),
                __( 'Use lighter strength for text-heavy areas', 'woow-admin' ),
                __( 'Keep your browser updated', 'woow-admin' ),
                __( 'Clear cache after changing settings', 'woow-admin' ),
            ),
            'donts' => array(
                __( 'Don\'t overuse Extra Strong - reserve for special areas', 'woow-admin' ),
                __( 'Don\'t mix with other blur effects from different plugins', 'woow-admin' ),
                __( 'Don\'t ignore performance - reduce strength if laggy', 'woow-admin' ),
                __( 'Don\'t use on very old hardware', 'woow-admin' ),
                __( 'Don\'t forget accessibility - ensure text remains readable', 'woow-admin' ),
            ),
        ),
    );
    
    return isset( $help_content[ $section ] ) ? $help_content[ $section ] : array();
}

/**
 * Render glassmorphism help panel
 *
 * @param string $section The help section to display
 * @return void
 */
function woow_render_glassmorphism_help( $section = 'overview' ) {
    $help = woow_get_glassmorphism_help( $section );
    
    if ( empty( $help ) ) {
        return;
    }
    
    ?>
    <div class="woow-help-panel">
        <?php if ( ! empty( $help['icon'] ) ) : ?>
            <span class="woow-help-icon"><?php echo esc_html( $help['icon'] ); ?></span>
        <?php endif; ?>
        
        <h4 class="woow-help-title"><?php echo esc_html( $help['title'] ); ?></h4>
        
        <?php if ( ! empty( $help['content'] ) ) : ?>
            <p class="woow-help-content"><?php echo esc_html( $help['content'] ); ?></p>
        <?php endif; ?>
        
        <?php if ( ! empty( $help['list'] ) ) : ?>
            <ul class="woow-help-list">
                <?php foreach ( $help['list'] as $item ) : ?>
                    <li>
                        <strong><?php echo esc_html( $item['label'] ); ?></strong>
                        <br>
                        <span class="woow-help-description"><?php echo esc_html( $item['description'] ); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        
        <?php if ( ! empty( $help['supported'] ) ) : ?>
            <ul class="woow-help-compatibility">
                <?php foreach ( $help['supported'] as $browser => $supported ) : ?>
                    <li>
                        <span class="woow-help-check">✅</span>
                        <?php echo esc_html( $browser ); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        
        <?php if ( ! empty( $help['tips'] ) ) : ?>
            <ul class="woow-help-tips">
                <?php foreach ( $help['tips'] as $tip ) : ?>
                    <li><?php echo esc_html( $tip ); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        
        <?php if ( ! empty( $help['issues'] ) ) : ?>
            <div class="woow-help-troubleshooting">
                <?php foreach ( $help['issues'] as $issue ) : ?>
                    <div class="woow-help-issue">
                        <h5><?php echo esc_html( $issue['problem'] ); ?></h5>
                        <ul>
                            <?php foreach ( $issue['solutions'] as $solution ) : ?>
                                <li><?php echo esc_html( $solution ); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if ( ! empty( $help['dos'] ) || ! empty( $help['donts'] ) ) : ?>
            <div class="woow-help-practices">
                <?php if ( ! empty( $help['dos'] ) ) : ?>
                    <div class="woow-help-dos">
                        <h5><?php esc_html_e( '✅ Do\'s', 'woow-admin' ); ?></h5>
                        <ul>
                            <?php foreach ( $help['dos'] as $do ) : ?>
                                <li><?php echo esc_html( $do ); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <?php if ( ! empty( $help['donts'] ) ) : ?>
                    <div class="woow-help-donts">
                        <h5><?php esc_html_e( '❌ Don\'ts', 'woow-admin' ); ?></h5>
                        <ul>
                            <?php foreach ( $help['donts'] as $dont ) : ?>
                                <li><?php echo esc_html( $dont ); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Get inline help tooltip content
 *
 * @param string $field The field to get help for
 * @return string Tooltip content
 */
function woow_get_glassmorphism_tooltip( $field ) {
    $tooltips = array(
        'enable' => __(
            'Enable or disable glassmorphism effects globally across admin bar, menu, and widgets.',
            'woow-admin'
        ),
        'strength' => __(
            'Control the intensity of the frosted glass effect. Light is subtle, Extra Strong is dramatic.',
            'woow-admin'
        ),
        'light' => __(
            '4px blur - Subtle effect, best for text-heavy areas with maximum readability.',
            'woow-admin'
        ),
        'medium' => __(
            '8px blur - Balanced effect, recommended default for general use.',
            'woow-admin'
        ),
        'strong' => __(
            '12px blur - Pronounced effect, ideal for decorative elements.',
            'woow-admin'
        ),
        'extra_strong' => __(
            '16px blur - Maximum effect, use sparingly for dramatic visual impact.',
            'woow-admin'
        ),
    );
    
    return isset( $tooltips[ $field ] ) ? $tooltips[ $field ] : '';
}

/**
 * Render help icon with tooltip
 *
 * @param string $field The field to show help for
 * @return void
 */
function woow_render_help_icon( $field ) {
    $tooltip = woow_get_glassmorphism_tooltip( $field );
    
    if ( empty( $tooltip ) ) {
        return;
    }
    
    ?>
    <span class="woow-help-icon-inline" 
          data-tooltip="<?php echo esc_attr( $tooltip ); ?>"
          aria-label="<?php echo esc_attr( $tooltip ); ?>">
        <span class="dashicons dashicons-editor-help"></span>
    </span>
    <?php
}
