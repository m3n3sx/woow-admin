/**
 * LivePreview Component
 *
 * Manages the live preview iframe and CSS injection.
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
        this.iframe = null;
        this.previewPanel = null;
        this.mode = 'desktop'; // desktop, tablet, mobile
        this.init();
    }

    /**
     * Initialize live preview
     */
    init() {
        this.previewPanel = document.querySelector('.woow-preview-panel');
        this.iframe = document.querySelector('#woow-preview-frame');

        if (!this.previewPanel || !this.iframe) {
            console.warn('[LivePreview] Preview elements not found');
            return;
        }

        this.bindEvents();
        this.initializeIframe();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Preview mode buttons
        const modeButtons = document.querySelectorAll('.woow-preview-mode');
        modeButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const mode = button.dataset.mode;
                if (mode) {
                    this.setMode(mode);
                }
            });
        });

        // Toggle preview button
        const toggleButton = document.querySelector('.woow-preview-toggle');
        if (toggleButton) {
            toggleButton.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggle();
            });
        }

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
     * Initialize iframe content
     */
    initializeIframe() {
        if (!this.iframe) return;

        // Wait for iframe to load
        this.iframe.addEventListener('load', () => {
            console.log('[LivePreview] Iframe loaded');
            this.injectBaseStyles();
        });

        // Set iframe source to WordPress admin dashboard
        if (!this.iframe.src) {
            this.iframe.src = window.woowAdminData?.previewUrl || '/wp-admin/';
        }
    }

    /**
     * Inject base styles into iframe
     */
    injectBaseStyles() {
        try {
            const iframeDoc = this.iframe.contentDocument || this.iframe.contentWindow.document;

            if (!iframeDoc) {
                console.warn('[LivePreview] Cannot access iframe document');
                return;
            }

            // Check if style element already exists
            let styleEl = iframeDoc.getElementById('woow-preview-styles');

            if (!styleEl) {
                styleEl = iframeDoc.createElement('style');
                styleEl.id = 'woow-preview-styles';
                iframeDoc.head.appendChild(styleEl);
            }

            console.log('[LivePreview] Base styles injected');
        } catch (error) {
            console.error('[LivePreview] Error injecting base styles:', error);
        }
    }

    /**
     * Update preview with new CSS
     *
     * @param {string} css - CSS string to inject
     */
    update(css) {
        if (!this.iframe) return;

        try {
            const iframeDoc = this.iframe.contentDocument || this.iframe.contentWindow.document;

            if (!iframeDoc) {
                console.warn('[LivePreview] Cannot access iframe document');
                return;
            }

            // Get or create style element
            let styleEl = iframeDoc.getElementById('woow-preview-styles');

            if (!styleEl) {
                styleEl = iframeDoc.createElement('style');
                styleEl.id = 'woow-preview-styles';
                iframeDoc.head.appendChild(styleEl);
            }

            // Update CSS content
            styleEl.textContent = css;

            console.log('[LivePreview] CSS updated');
        } catch (error) {
            console.error('[LivePreview] Error updating CSS:', error);
        }
    }

    /**
     * Set preview mode (desktop, tablet, mobile)
     *
     * @param {string} mode - Preview mode
     */
    setMode(mode) {
        if (!this.iframe || !this.previewPanel) return;

        this.mode = mode;

        // Update active button
        const modeButtons = document.querySelectorAll('.woow-preview-mode');
        modeButtons.forEach(button => {
            if (button.dataset.mode === mode) {
                button.classList.add('active');
            } else {
                button.classList.remove('active');
            }
        });

        // Update iframe dimensions
        this.iframe.classList.remove('woow-preview-desktop', 'woow-preview-tablet', 'woow-preview-mobile');
        this.iframe.classList.add(`woow-preview-${mode}`);

        // Apply mode-specific styles
        switch (mode) {
            case 'mobile':
                this.iframe.style.width = '375px';
                this.iframe.style.height = '667px';
                break;
            case 'tablet':
                this.iframe.style.width = '768px';
                this.iframe.style.height = '1024px';
                break;
            case 'desktop':
            default:
                this.iframe.style.width = '100%';
                this.iframe.style.height = '100%';
                break;
        }

        console.log('[LivePreview] Mode set to:', mode);
    }

    /**
     * Toggle preview panel visibility
     */
    toggle() {
        if (!this.previewPanel) return;

        const isVisible = !this.previewPanel.classList.contains('woow-preview-hidden');

        if (isVisible) {
            this.previewPanel.classList.add('woow-preview-hidden');
            console.log('[LivePreview] Preview hidden');
        } else {
            this.previewPanel.classList.remove('woow-preview-hidden');
            console.log('[LivePreview] Preview shown');
        }

        // Update toggle button text
        const toggleButton = document.querySelector('.woow-preview-toggle');
        if (toggleButton) {
            toggleButton.textContent = isVisible
                ? (this.woow.i18n.showPreview || 'Show Preview')
                : (this.woow.i18n.hidePreview || 'Hide Preview');
        }
    }

    /**
     * Refresh preview iframe
     */
    refresh() {
        if (!this.iframe) return;

        console.log('[LivePreview] Refreshing preview');

        // Reload iframe
        this.iframe.src = this.iframe.src;

        // Show notification
        this.woow.showNotification(
            this.woow.i18n.previewRefreshed || 'Preview refreshed',
            'info'
        );
    }

    /**
     * Check if preview is visible
     *
     * @returns {boolean} True if visible
     */
    isVisible() {
        if (!this.previewPanel) return false;
        return !this.previewPanel.classList.contains('woow-preview-hidden');
    }

    /**
     * Get current preview mode
     *
     * @returns {string} Current mode
     */
    getMode() {
        return this.mode;
    }
}

export default LivePreview;
