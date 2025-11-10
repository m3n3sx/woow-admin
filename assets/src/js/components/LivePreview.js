/**
 * LivePreview Component
 *
 * Manages the live preview using mock elements.
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

export class LivePreview {
    /**
     * Constructor
     *
     * @param {WoowAdmin} woowAdmin - Main controller instance
     */
    constructor(woowAdmin) {
        this.woow = woowAdmin;
        this.enabled = false;
        this.previewElements = {};
        this.init();
    }

    /**
     * Initialize live preview
     */
    init() {
        // Query mock preview elements
        this.previewElements = {
            adminBar: document.getElementById('woow-preview-adminbar'),
            adminMenu: document.getElementById('woow-preview-menu'),
            widget: document.getElementById('woow-preview-widget')
        };

        // Check if preview elements exist
        if (!this.previewElements.adminBar) {
            console.warn('[LivePreview] Preview elements not found - preview disabled');
            this.enabled = false;
            return;
        }

        this.enabled = true;
        console.log('[LivePreview] Initialized successfully');
        this.bindEvents();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Refresh preview button
        const refreshButton = document.querySelector('.woow-preview-refresh');
        if (refreshButton) {
            refreshButton.addEventListener('click', (e) => {
                e.preventDefault();
                this.refresh();
            });
        }
    }

    /**
     * Update preview with settings object
     *
     * @param {Object} settings - Settings object with all configuration
     */
    async updatePreview(settings) {
        if (!this.enabled) {
            console.log('[LivePreview] Preview disabled - skipping update');
            return;
        }

        try {
            // Admin Bar Preview
            if (settings.admin_bar && this.previewElements.adminBar) {
                this.updateAdminBarPreview(settings.admin_bar);
            }

            // Admin Menu Preview
            if (settings.admin_menu && this.previewElements.adminMenu) {
                this.updateAdminMenuPreview(settings.admin_menu);
            }

            // Widget Preview
            if (settings.dashboard_widgets && this.previewElements.widget) {
                this.updateWidgetPreview(settings.dashboard_widgets);
            }

            console.log('[LivePreview] Preview updated successfully');
        } catch (error) {
            console.error('[LivePreview] Update failed:', error);
            // Don't throw - allow save to continue
        }
    }

    /**
     * Update admin bar preview
     *
     * @param {Object} settings - Admin bar settings
     */
    updateAdminBarPreview(settings) {
        const el = this.previewElements.adminBar;
        if (!el) return;

        if (settings.background_color) {
            el.style.background = settings.background_color;
        }
        if (settings.text_color) {
            el.style.color = settings.text_color;
        }
        if (settings.height) {
            el.style.height = settings.height;
        }
        if (settings.border_radius) {
            el.style.borderRadius = settings.border_radius;
        }
        if (settings.glassmorphism && settings.blur_strength) {
            el.style.backdropFilter = `blur(${settings.blur_strength})`;
            el.style.opacity = settings.opacity || 0.9;
        }
    }

    /**
     * Update admin menu preview
     *
     * @param {Object} settings - Admin menu settings
     */
    updateAdminMenuPreview(settings) {
        const el = this.previewElements.adminMenu;
        if (!el) return;

        if (settings.background_color) {
            el.style.background = settings.background_color;
        }
        if (settings.text_color) {
            el.style.color = settings.text_color;
        }

        // Active item gradient
        const activeItem = el.querySelector('.woow-preview-menu-item.active');
        if (activeItem && settings.active_bg_start && settings.active_bg_end) {
            activeItem.style.background = `linear-gradient(to bottom right, ${settings.active_bg_start}, ${settings.active_bg_end})`;
        }
    }

    /**
     * Update widget preview
     *
     * @param {Object} settings - Dashboard widget settings
     */
    updateWidgetPreview(settings) {
        const el = this.previewElements.widget;
        if (!el) return;

        if (settings.background_color) {
            el.style.background = settings.background_color;
        }
        if (settings.border_radius) {
            el.style.borderRadius = settings.border_radius;
        }
        if (settings.glassmorphism && settings.blur_strength) {
            el.style.backdropFilter = `blur(${settings.blur_strength})`;
        }
    }

    /**
     * Legacy update method for CSS injection (kept for backward compatibility)
     *
     * @param {string} css - CSS string to inject
     */
    update(css) {
        // This method is kept for backward compatibility with main.js
        // The actual preview now uses updatePreview() with settings object
        console.log('[LivePreview] CSS injection received (legacy mode)');
    }

    /**
     * Refresh preview by re-applying current settings
     */
    refresh() {
        if (!this.enabled) return;

        console.log('[LivePreview] Refreshing preview');

        // Trigger a preview update from main controller
        if (this.woow && typeof this.woow.updateLivePreview === 'function') {
            this.woow.updateLivePreview();
        }

        // Show notification
        if (this.woow && typeof this.woow.showNotification === 'function') {
            this.woow.showNotification(
                this.woow.i18n?.previewRefreshed || 'Preview refreshed',
                'info'
            );
        }
    }

    /**
     * Check if preview is enabled
     *
     * @returns {boolean} True if enabled
     */
    isEnabled() {
        return this.enabled;
    }
}

export default LivePreview;
