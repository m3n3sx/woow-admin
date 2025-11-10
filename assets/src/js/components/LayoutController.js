/**
 * Layout Controller Component
 *
 * Manages sidebar and preview panel collapse/expand functionality
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

export class LayoutController {
    /**
     * Constructor
     *
     * @param {WoowAdmin} app - Main application instance
     */
    constructor(app) {
        this.app = app;
        this.sidebar = document.querySelector('.woow-sidebar');
        this.sidebarToggle = document.querySelector('.woow-sidebar-toggle');
        this.preview = document.querySelector('.woow-preview-container');
        this.previewToggle = document.querySelector('.woow-preview-toggle');
        
        // Load saved states from localStorage
        this.sidebarCollapsed = localStorage.getItem('woow_sidebar_collapsed') === 'true';
        this.previewCollapsed = localStorage.getItem('woow_preview_collapsed') === 'true';
        
        this.init();
    }

    /**
     * Initialize the layout controller
     */
    init() {
        if (!this.sidebar || !this.preview) {
            console.warn('[LayoutController] Required elements not found');
            return;
        }

        // Apply saved states
        if (this.sidebarCollapsed) {
            this.sidebar.classList.add('collapsed');
        }
        
        if (this.previewCollapsed) {
            this.preview.classList.add('collapsed');
        }

        // Bind events
        this.bindEvents();

        console.log('[LayoutController] Initialized');
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Sidebar toggle
        if (this.sidebarToggle) {
            this.sidebarToggle.addEventListener('click', () => this.toggleSidebar());
        }

        // Preview toggle
        if (this.previewToggle) {
            this.previewToggle.addEventListener('click', () => this.togglePreview());
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            // Ctrl/Cmd + B = Toggle sidebar
            if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
                e.preventDefault();
                this.toggleSidebar();
            }
            
            // Ctrl/Cmd + P = Toggle preview
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                this.togglePreview();
            }
        });
    }

    /**
     * Toggle sidebar collapse state
     */
    toggleSidebar() {
        if (!this.sidebar) return;

        this.sidebarCollapsed = !this.sidebarCollapsed;
        
        if (this.sidebarCollapsed) {
            this.sidebar.classList.add('collapsed');
        } else {
            this.sidebar.classList.remove('collapsed');
        }

        // Save state
        localStorage.setItem('woow_sidebar_collapsed', this.sidebarCollapsed);

        // Trigger custom event
        this.sidebar.dispatchEvent(new CustomEvent('woow:sidebar:toggle', {
            detail: { collapsed: this.sidebarCollapsed }
        }));

        console.log('[LayoutController] Sidebar toggled:', this.sidebarCollapsed ? 'collapsed' : 'expanded');
    }

    /**
     * Toggle preview collapse state
     */
    togglePreview() {
        if (!this.preview) return;

        this.previewCollapsed = !this.previewCollapsed;
        
        if (this.previewCollapsed) {
            this.preview.classList.add('collapsed');
        } else {
            this.preview.classList.remove('collapsed');
        }

        // Save state
        localStorage.setItem('woow_preview_collapsed', this.previewCollapsed);

        // Trigger custom event
        this.preview.dispatchEvent(new CustomEvent('woow:preview:toggle', {
            detail: { collapsed: this.previewCollapsed }
        }));

        console.log('[LayoutController] Preview toggled:', this.previewCollapsed ? 'collapsed' : 'expanded');
    }

    /**
     * Collapse sidebar
     */
    collapseSidebar() {
        if (!this.sidebar || this.sidebarCollapsed) return;
        this.toggleSidebar();
    }

    /**
     * Expand sidebar
     */
    expandSidebar() {
        if (!this.sidebar || !this.sidebarCollapsed) return;
        this.toggleSidebar();
    }

    /**
     * Collapse preview
     */
    collapsePreview() {
        if (!this.preview || this.previewCollapsed) return;
        this.togglePreview();
    }

    /**
     * Expand preview
     */
    expandPreview() {
        if (!this.preview || !this.previewCollapsed) return;
        this.togglePreview();
    }

    /**
     * Get current layout state
     *
     * @return {Object} Current state
     */
    getState() {
        return {
            sidebarCollapsed: this.sidebarCollapsed,
            previewCollapsed: this.previewCollapsed
        };
    }
}
