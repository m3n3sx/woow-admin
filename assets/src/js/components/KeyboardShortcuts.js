/**
 * KeyboardShortcuts Component
 *
 * Handles keyboard shortcuts for common actions.
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

export class KeyboardShortcuts {
    /**
     * Constructor
     *
     * @param {WoowAdmin} woowAdmin - Main controller instance
     */
    constructor(woowAdmin) {
        this.woow = woowAdmin;
        this.shortcuts = new Map();
        this.init();
    }

    /**
     * Initialize keyboard shortcuts
     */
    init() {
        this.registerShortcuts();
        this.bindEvents();
    }

    /**
     * Register all keyboard shortcuts
     */
    registerShortcuts() {
        // Palette shortcuts: Ctrl+Shift+1 through Ctrl+Shift+0
        for (let i = 1; i <= 10; i++) {
            const key = i === 10 ? '0' : String(i);
            this.register(`ctrl+shift+${key}`, () => {
                this.applyPaletteByIndex(i - 1);
            }, `Apply palette ${i}`);
        }

        // Save settings: Ctrl+S
        this.register('ctrl+s', () => {
            this.woow.saveSettings();
        }, 'Save settings');

        // Export settings: Ctrl+E
        this.register('ctrl+e', () => {
            if (this.woow.components.importExport) {
                this.woow.components.importExport.exportSettings();
            }
        }, 'Export settings');

        // Toggle preview: Ctrl+P
        this.register('ctrl+p', () => {
            if (this.woow.components.livePreview) {
                this.woow.components.livePreview.toggle();
            }
        }, 'Toggle preview');

        // Restore last backup: Ctrl+Z (when not in input)
        this.register('ctrl+z', () => {
            this.restoreLastBackup();
        }, 'Restore last backup', true); // Only when not in input
    }

    /**
     * Register a keyboard shortcut
     *
     * @param {string} combo - Key combination (e.g., 'ctrl+s')
     * @param {Function} callback - Callback function
     * @param {string} description - Description of shortcut
     * @param {boolean} requireNotInput - Only trigger when not in input field
     */
    register(combo, callback, description = '', requireNotInput = false) {
        this.shortcuts.set(combo, {
            callback,
            description,
            requireNotInput
        });
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        document.addEventListener('keydown', (e) => {
            this.handleKeyPress(e);
        });
    }

    /**
     * Handle key press event
     *
     * @param {KeyboardEvent} e - Keyboard event
     */
    handleKeyPress(e) {
        // Build key combination string
        const combo = this.getKeyCombo(e);

        // Check if shortcut exists
        const shortcut = this.shortcuts.get(combo);

        if (!shortcut) {
            return;
        }

        // Check if we're in an input field
        const isInput = this.isInputElement(e.target);

        // Skip if shortcut requires not being in input and we are in input
        if (shortcut.requireNotInput && isInput) {
            return;
        }

        // Prevent default action
        e.preventDefault();

        // Execute callback
        try {
            shortcut.callback(e);
            console.log('[KeyboardShortcuts] Executed:', combo);
        } catch (error) {
            console.error('[KeyboardShortcuts] Error executing shortcut:', error);
        }
    }

    /**
     * Get key combination string from event
     *
     * @param {KeyboardEvent} e - Keyboard event
     * @returns {string} Key combination string
     */
    getKeyCombo(e) {
        const parts = [];

        if (e.ctrlKey || e.metaKey) parts.push('ctrl');
        if (e.altKey) parts.push('alt');
        if (e.shiftKey) parts.push('shift');

        // Get key name
        const key = e.key.toLowerCase();
        parts.push(key);

        return parts.join('+');
    }

    /**
     * Check if element is an input field
     *
     * @param {HTMLElement} element - Element to check
     * @returns {boolean} True if input element
     */
    isInputElement(element) {
        const tagName = element.tagName.toLowerCase();
        const isEditable = element.isContentEditable;

        return (
            tagName === 'input' ||
            tagName === 'textarea' ||
            tagName === 'select' ||
            isEditable
        );
    }

    /**
     * Apply palette by index
     *
     * @param {number} index - Palette index (0-9)
     */
    async applyPaletteByIndex(index) {
        const palettes = window.woowAdminData?.palettes || [];

        if (index < 0 || index >= palettes.length) {
            console.warn('[KeyboardShortcuts] Invalid palette index:', index);
            return;
        }

        const palette = palettes[index];
        if (!palette) {
            return;
        }

        this.woow.showNotification(
            `Applying ${palette.name}...`,
            'info'
        );

        await this.woow.applyPalette(palette.id);
    }

    /**
     * Restore last backup
     */
    async restoreLastBackup() {
        const confirmed = confirm(
            this.woow.i18n.restoreConfirm ||
            'Restore the last backup? This will replace your current settings.'
        );

        if (!confirmed) {
            return;
        }

        try {
            this.woow.showNotification(
                this.woow.i18n.restoring || 'Restoring backup...',
                'info'
            );

            // Prepare AJAX request
            const data = new FormData();
            data.append('action', 'woow_restore_backup');
            data.append('nonce', this.woow.nonce);
            data.append('backup_id', 'latest'); // Restore latest backup

            // Send request
            const response = await fetch(this.woow.ajaxUrl, {
                method: 'POST',
                body: data
            });

            const result = await response.json();

            if (result.success) {
                this.woow.showNotification(
                    this.woow.i18n.restoreSuccess || 'Backup restored successfully!',
                    'success'
                );

                // Reload page to reflect changes
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                const errorMessage = result.data?.message || this.woow.i18n.restoreFailed || 'Failed to restore backup';
                this.woow.showNotification(errorMessage, 'error');
            }
        } catch (error) {
            console.error('[KeyboardShortcuts] Restore error:', error);
            this.woow.showNotification(
                this.woow.i18n.networkError || 'Network error. Please try again.',
                'error'
            );
        }
    }

    /**
     * Get all registered shortcuts
     *
     * @returns {Array} Array of shortcut objects
     */
    getShortcuts() {
        const shortcuts = [];

        this.shortcuts.forEach((value, key) => {
            shortcuts.push({
                combo: key,
                description: value.description
            });
        });

        return shortcuts;
    }

    /**
     * Show shortcuts help dialog
     */
    showHelp() {
        const shortcuts = this.getShortcuts();

        let helpText = 'Keyboard Shortcuts:\n\n';

        shortcuts.forEach(shortcut => {
            const combo = shortcut.combo.toUpperCase().replace(/\+/g, ' + ');
            helpText += `${combo}: ${shortcut.description}\n`;
        });

        alert(helpText);
    }
}

export default KeyboardShortcuts;
