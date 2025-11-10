/**
 * TabManager Component
 *
 * Handles tab navigation and content switching.
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

export class TabManager {
    /**
     * Constructor
     *
     * @param {WoowAdmin} woowAdmin - Main controller instance
     */
    constructor(woowAdmin) {
        this.woow = woowAdmin;
        this.tabsContainer = null;
        this.tabButtons = [];
        this.tabPanes = [];
        this.activeTab = null;
        this.init();
    }

    /**
     * Initialize tab manager
     */
    init() {
        // Look for sidebar navigation (Figma design)
        this.tabsContainer = document.querySelector('.woow-sidebar-nav');

        if (!this.tabsContainer) {
            // Fallback to old tabs container
            this.tabsContainer = document.querySelector('.woow-tabs');
        }

        if (!this.tabsContainer) {
            console.warn('[TabManager] Navigation container not found');
            return;
        }

        // Get navigation items (sidebar) or tab buttons (old design)
        this.tabButtons = Array.from(
            this.tabsContainer.querySelectorAll('.woow-nav-item, .woow-tab-button')
        );
        this.tabPanes = Array.from(document.querySelectorAll('.woow-tab-pane'));

        // Get initial active tab from URL hash or default
        const hash = window.location.hash.substring(1);
        this.activeTab = hash || this.woow.state.activeTab || 'general';

        this.bindEvents();
        this.showTab(this.activeTab);
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Tab button clicks
        this.tabButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const tabId = button.dataset.tab;

                if (tabId) {
                    this.showTab(tabId);
                }
            });

            // Keyboard navigation
            button.addEventListener('keydown', (e) => {
                this.handleKeyboardNavigation(e);
            });
        });

        // Handle browser back/forward
        window.addEventListener('hashchange', () => {
            const hash = window.location.hash.substring(1);
            if (hash) {
                this.showTab(hash, false); // Don't update hash again
            }
        });
    }

    /**
     * Show specific tab
     *
     * @param {string} tabId - Tab ID to show
     * @param {boolean} updateHash - Whether to update URL hash
     */
    showTab(tabId, updateHash = true) {
        if (!tabId) return;

        // Update active tab in state
        this.activeTab = tabId;
        this.woow.state.activeTab = tabId;

        // Update navigation items/tab buttons
        this.tabButtons.forEach(button => {
            if (button.dataset.tab === tabId) {
                button.classList.add('active', 'woow-tab-active');
                button.setAttribute('aria-selected', 'true');
                button.setAttribute('tabindex', '0');
            } else {
                button.classList.remove('active', 'woow-tab-active');
                button.setAttribute('aria-selected', 'false');
                button.setAttribute('tabindex', '-1');
            }
        });

        // Update tab panes
        this.tabPanes.forEach(pane => {
            if (pane.id === `tab-${tabId}`) {
                pane.classList.add('active', 'woow-tab-pane-active');
                pane.setAttribute('aria-hidden', 'false');
            } else {
                pane.classList.remove('active', 'woow-tab-pane-active');
                pane.setAttribute('aria-hidden', 'true');
            }
        });

        // Update URL hash
        if (updateHash) {
            this.updateHash(tabId);
        }

        console.log('[TabManager] Switched to tab:', tabId);
    }

    /**
     * Update URL hash
     *
     * @param {string} tabId - Tab ID
     */
    updateHash(tabId) {
        if (history.pushState) {
            history.pushState(null, null, `#${tabId}`);
        } else {
            window.location.hash = tabId;
        }
    }

    /**
     * Handle keyboard navigation
     *
     * @param {KeyboardEvent} e - Keyboard event
     */
    handleKeyboardNavigation(e) {
        const currentButton = e.target;
        const currentIndex = this.tabButtons.indexOf(currentButton);

        let nextIndex = currentIndex;

        switch (e.key) {
            case 'ArrowLeft':
            case 'ArrowUp':
                e.preventDefault();
                nextIndex = currentIndex > 0 ? currentIndex - 1 : this.tabButtons.length - 1;
                break;

            case 'ArrowRight':
            case 'ArrowDown':
                e.preventDefault();
                nextIndex = currentIndex < this.tabButtons.length - 1 ? currentIndex + 1 : 0;
                break;

            case 'Home':
                e.preventDefault();
                nextIndex = 0;
                break;

            case 'End':
                e.preventDefault();
                nextIndex = this.tabButtons.length - 1;
                break;

            default:
                return;
        }

        // Focus and activate next tab
        const nextButton = this.tabButtons[nextIndex];
        if (nextButton) {
            nextButton.focus();
            const tabId = nextButton.dataset.tab;
            if (tabId) {
                this.showTab(tabId);
            }
        }
    }

    /**
     * Get active tab ID
     *
     * @returns {string} Active tab ID
     */
    getActiveTab() {
        return this.activeTab;
    }

    /**
     * Get tab button by ID
     *
     * @param {string} tabId - Tab ID
     * @returns {HTMLElement|null} Tab button element
     */
    getTabButton(tabId) {
        return this.tabButtons.find(button => button.dataset.tab === tabId) || null;
    }

    /**
     * Get tab pane by ID
     *
     * @param {string} tabId - Tab ID
     * @returns {HTMLElement|null} Tab pane element
     */
    getTabPane(tabId) {
        return document.getElementById(`tab-${tabId}`) || null;
    }

    /**
     * Enable tab
     *
     * @param {string} tabId - Tab ID to enable
     */
    enableTab(tabId) {
        const button = this.getTabButton(tabId);
        if (button) {
            button.disabled = false;
            button.classList.remove('woow-tab-disabled');
        }
    }

    /**
     * Disable tab
     *
     * @param {string} tabId - Tab ID to disable
     */
    disableTab(tabId) {
        const button = this.getTabButton(tabId);
        if (button) {
            button.disabled = true;
            button.classList.add('woow-tab-disabled');

            // If this is the active tab, switch to first enabled tab
            if (this.activeTab === tabId) {
                const firstEnabled = this.tabButtons.find(b => !b.disabled);
                if (firstEnabled) {
                    this.showTab(firstEnabled.dataset.tab);
                }
            }
        }
    }
}

export default TabManager;
