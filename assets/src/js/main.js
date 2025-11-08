/**
 * WOOW! Admin - Main JavaScript Controller
 *
 * Central controller managing state and component coordination.
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

import { ColorPicker } from './components/ColorPicker.js';
import { LivePreview } from './components/LivePreview.js';
import { PaletteSelector } from './components/PaletteSelector.js';
import { TemplateGallery } from './components/TemplateGallery.js';
import { ImportExport } from './components/ImportExport.js';
import { TabManager } from './components/TabManager.js';
import { KeyboardShortcuts } from './components/KeyboardShortcuts.js';

/**
 * Main WoowAdmin Controller Class
 */
class WoowAdmin {
    /**
     * Constructor
     */
    constructor() {
        // State management
        this.state = {
            settings: window.woowAdminData?.settings || {},
            activeTab: 'general',
            previewMode: 'desktop',
            unsavedChanges: false
        };

        // Component instances
        this.components = {};

        // Debounce timer
        this.debounceTimer = null;

        // AJAX configuration
        this.ajaxUrl = window.woowAdminData?.ajaxUrl || '/wp-admin/admin-ajax.php';
        this.nonce = window.woowAdminData?.nonce || '';
        this.i18n = window.woowAdminData?.i18n || {};

        // Initialize
        this.init();
    }

    /**
     * Initialize the application
     */
    init() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.initComponents());
        } else {
            this.initComponents();
        }
    }

    /**
     * Initialize all components
     */
    initComponents() {
        try {
            // Initialize components
            this.components.colorPicker = new ColorPicker(this);
            this.components.livePreview = new LivePreview(this);
            this.components.paletteSelector = new PaletteSelector(this);
            this.components.templateGallery = new TemplateGallery(this);
            this.components.importExport = new ImportExport(this);
            this.components.tabManager = new TabManager(this);
            this.components.keyboardShortcuts = new KeyboardShortcuts(this);

            // Bind global events
            this.bindEvents();

            console.log('[WOOW Admin] Initialized successfully');
        } catch (error) {
            console.error('[WOOW Admin] Initialization error:', error);
            this.showNotification('Failed to initialize WOOW! Admin', 'error');
        }
    }

    /**
     * Bind global event listeners
     */
    bindEvents() {
        // Save button
        const saveButton = document.querySelector('.woow-save-button');
        if (saveButton) {
            saveButton.addEventListener('click', (e) => {
                e.preventDefault();
                this.saveSettings();
            });
        }

        // Track form changes
        const form = document.querySelector('.woow-admin-form');
        if (form) {
            form.addEventListener('change', () => {
                this.state.unsavedChanges = true;
                this.updateSaveButtonState();
            });

            form.addEventListener('input', () => {
                this.state.unsavedChanges = true;
                this.updateSaveButtonState();
                this.debouncedPreview();
            });
        }

        // Warn about unsaved changes
        window.addEventListener('beforeunload', (e) => {
            if (this.state.unsavedChanges) {
                e.preventDefault();
                e.returnValue = this.i18n.unsavedChanges || 'You have unsaved changes. Are you sure you want to leave?';
                return e.returnValue;
            }
        });
    }

    /**
     * Update save button state based on unsaved changes
     */
    updateSaveButtonState() {
        const saveButton = document.querySelector('.woow-save-button');
        if (saveButton) {
            if (this.state.unsavedChanges) {
                saveButton.classList.add('woow-button-primary');
                saveButton.classList.remove('woow-button-secondary');
                saveButton.textContent = this.i18n.saveChanges || 'Save Changes';
            } else {
                saveButton.classList.remove('woow-button-primary');
                saveButton.classList.add('woow-button-secondary');
                saveButton.textContent = this.i18n.saved || 'Saved';
            }
        }
    }

    /**
     * Collect form data from all tabs
     *
     * @returns {Object} Form data
     */
    collectFormData() {
        const formData = {};
        const form = document.querySelector('.woow-admin-form');

        if (!form) {
            return formData;
        }

        // Collect all inputs, selects, and textareas
        const inputs = form.querySelectorAll('input, select, textarea');

        inputs.forEach(input => {
            const name = input.name;
            if (!name) return;

            // Parse name to get section and key (e.g., "admin_bar[height]")
            const match = name.match(/^([^\[]+)\[([^\]]+)\]$/);
            if (!match) return;

            const [, section, key] = match;

            // Initialize section if needed
            if (!formData[section]) {
                formData[section] = {};
            }

            // Get value based on input type
            if (input.type === 'checkbox') {
                formData[section][key] = input.checked;
            } else if (input.type === 'radio') {
                if (input.checked) {
                    formData[section][key] = input.value;
                }
            } else if (input.type === 'range') {
                const unit = input.dataset.unit || '';
                formData[section][key] = input.value + unit;
            } else {
                formData[section][key] = input.value;
            }
        });

        return formData;
    }

    /**
     * Show notification toast
     *
     * @param {string} message - Notification message
     * @param {string} type - Notification type (success, error, warning, info)
     */
    showNotification(message, type = 'info') {
        // Remove existing notifications
        const existing = document.querySelectorAll('.woow-toast');
        existing.forEach(toast => toast.remove());

        // Create toast element
        const toast = document.createElement('div');
        toast.className = `woow-toast woow-toast-${type}`;
        toast.textContent = message;

        // Add to DOM
        document.body.appendChild(toast);

        // Trigger animation
        setTimeout(() => toast.classList.add('woow-toast-show'), 10);

        // Auto-dismiss after 3 seconds
        setTimeout(() => {
            toast.classList.remove('woow-toast-show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    /**
     * Debounced preview update
     */
    debouncedPreview() {
        clearTimeout(this.debounceTimer);
        this.debounceTimer = setTimeout(() => {
            this.updateLivePreview();
        }, 300); // 300ms debounce
    }

    /**
     * Update live preview with current settings
     */
    async updateLivePreview() {
        try {
            const formData = this.collectFormData();

            const data = new FormData();
            data.append('action', 'woow_preview_css');
            data.append('nonce', this.nonce);
            data.append('settings', JSON.stringify(formData));

            const response = await fetch(this.ajaxUrl, {
                method: 'POST',
                body: data
            });

            const result = await response.json();

            if (result.success) {
                // Update preview
                if (this.components.livePreview) {
                    this.components.livePreview.update(result.data.css);
                }

                // Update metrics if available
                if (result.data.metrics) {
                    this.updateMetrics(result.data.metrics);
                }
            } else {
                console.error('[WOOW Admin] Preview error:', result.data);
            }
        } catch (error) {
            console.error('[WOOW Admin] Preview error:', error);
        }
    }

    /**
     * Update performance metrics display
     *
     * @param {Object} metrics - Performance metrics
     */
    updateMetrics(metrics) {
        const metricsContainer = document.querySelector('.woow-metrics');
        if (!metricsContainer) return;

        if (metrics.generation_time) {
            const timeEl = metricsContainer.querySelector('.woow-metric-time');
            if (timeEl) {
                timeEl.textContent = `${metrics.generation_time}ms`;
            }
        }

        if (metrics.css_size) {
            const sizeEl = metricsContainer.querySelector('.woow-metric-size');
            if (sizeEl) {
                const sizeKB = (metrics.css_size / 1024).toFixed(2);
                sizeEl.textContent = `${sizeKB}KB`;
            }
        }

        if (metrics.cache_hit !== undefined) {
            const cacheEl = metricsContainer.querySelector('.woow-metric-cache');
            if (cacheEl) {
                cacheEl.textContent = metrics.cache_hit ? 'Hit' : 'Miss';
            }
        }
    }

    /**
     * Save settings to server
     *
     * @returns {Promise<boolean>} Success status
     */
    async saveSettings() {
        try {
            // Show loading state
            const saveButton = document.querySelector('.woow-save-button');
            if (saveButton) {
                saveButton.disabled = true;
                saveButton.textContent = this.i18n.saving || 'Saving...';
            }

            // Collect form data
            const formData = this.collectFormData();

            // Prepare AJAX request
            const data = new FormData();
            data.append('action', 'woow_save_settings');
            data.append('nonce', this.nonce);
            data.append('settings', JSON.stringify(formData));

            // Send request
            const response = await fetch(this.ajaxUrl, {
                method: 'POST',
                body: data
            });

            const result = await response.json();

            if (result.success) {
                // Update state
                this.state.settings = result.data.settings || formData;
                this.state.unsavedChanges = false;

                // Update UI
                this.updateSaveButtonState();
                this.showNotification(
                    this.i18n.settingsSaved || 'Settings saved successfully!',
                    'success'
                );

                // Update preview with new CSS
                if (result.data.css && this.components.livePreview) {
                    this.components.livePreview.update(result.data.css);
                }

                // Update metrics
                if (result.data.metrics) {
                    this.updateMetrics(result.data.metrics);
                }

                return true;
            } else {
                // Handle error
                const errorMessage = result.data?.message || this.i18n.saveFailed || 'Failed to save settings';
                this.showNotification(errorMessage, 'error');

                // Show validation errors if available
                if (result.data?.errors && Array.isArray(result.data.errors)) {
                    result.data.errors.forEach(error => {
                        console.error('[WOOW Admin] Validation error:', error);
                    });
                }

                return false;
            }
        } catch (error) {
            console.error('[WOOW Admin] Save error:', error);
            this.showNotification(
                this.i18n.networkError || 'Network error. Please try again.',
                'error'
            );
            return false;
        } finally {
            // Restore button state
            const saveButton = document.querySelector('.woow-save-button');
            if (saveButton) {
                saveButton.disabled = false;
                this.updateSaveButtonState();
            }
        }
    }

    /**
     * Apply color palette
     *
     * @param {string} paletteId - Palette ID to apply
     * @returns {Promise<boolean>} Success status
     */
    async applyPalette(paletteId) {
        try {
            // Show loading notification
            this.showNotification(
                this.i18n.applyingPalette || 'Applying palette...',
                'info'
            );

            // Prepare AJAX request
            const data = new FormData();
            data.append('action', 'woow_apply_palette');
            data.append('nonce', this.nonce);
            data.append('palette_id', paletteId);

            // Send request
            const response = await fetch(this.ajaxUrl, {
                method: 'POST',
                body: data
            });

            const result = await response.json();

            if (result.success) {
                // Update state with new settings
                this.state.settings = result.data.settings || this.state.settings;
                this.state.unsavedChanges = true;

                // Update form fields with new values
                this.updateFormFields(result.data.settings);

                // Update preview with new CSS
                if (result.data.css && this.components.livePreview) {
                    this.components.livePreview.update(result.data.css);
                }

                // Update metrics
                if (result.data.metrics) {
                    this.updateMetrics(result.data.metrics);
                }

                // Update save button state
                this.updateSaveButtonState();

                // Show success notification
                this.showNotification(
                    this.i18n.paletteApplied || 'Palette applied successfully!',
                    'success'
                );

                return true;
            } else {
                const errorMessage = result.data?.message || this.i18n.paletteApplyFailed || 'Failed to apply palette';
                this.showNotification(errorMessage, 'error');
                return false;
            }
        } catch (error) {
            console.error('[WOOW Admin] Apply palette error:', error);
            this.showNotification(
                this.i18n.networkError || 'Network error. Please try again.',
                'error'
            );
            return false;
        }
    }

    /**
     * Update form fields with new settings
     *
     * @param {Object} settings - Settings object
     */
    updateFormFields(settings) {
        if (!settings) return;

        Object.keys(settings).forEach(section => {
            const sectionData = settings[section];
            if (typeof sectionData !== 'object') return;

            Object.keys(sectionData).forEach(key => {
                const value = sectionData[key];
                const input = document.querySelector(`[name="${section}[${key}]"]`);

                if (!input) return;

                if (input.type === 'checkbox') {
                    input.checked = Boolean(value);
                } else if (input.type === 'radio') {
                    if (input.value === value) {
                        input.checked = true;
                    }
                } else if (input.type === 'range') {
                    // Remove unit suffix for range inputs
                    const numericValue = String(value).replace(/[^0-9.]/g, '');
                    input.value = numericValue;

                    // Update value display
                    const valueSpan = input.nextElementSibling;
                    if (valueSpan && valueSpan.classList.contains('woow-slider-value')) {
                        const unit = input.dataset.unit || '';
                        valueSpan.textContent = numericValue + unit;
                    }
                } else {
                    input.value = value;

                    // Sync color text input if it's a color picker
                    if (input.type === 'color') {
                        const textInput = input.nextElementSibling;
                        if (textInput && textInput.classList.contains('woow-color-text')) {
                            textInput.value = value;
                        }
                    }
                }

                // Trigger change event to update conditional fields
                input.dispatchEvent(new Event('change', { bubbles: true }));
            });
        });
    }

    /**
     * Apply design template
     *
     * @param {string} templateId - Template ID to apply
     * @returns {Promise<boolean>} Success status
     */
    async applyTemplate(templateId) {
        try {
            // Show loading notification
            this.showNotification(
                this.i18n.applyingTemplate || 'Applying template...',
                'info'
            );

            // Prepare AJAX request
            const data = new FormData();
            data.append('action', 'woow_apply_template');
            data.append('nonce', this.nonce);
            data.append('template_id', templateId);

            // Send request
            const response = await fetch(this.ajaxUrl, {
                method: 'POST',
                body: data
            });

            const result = await response.json();

            if (result.success) {
                // Update state with new settings
                this.state.settings = result.data.settings || this.state.settings;
                this.state.unsavedChanges = true;

                // Update form fields with new values
                this.updateFormFields(result.data.settings);

                // Update preview with new CSS
                if (result.data.css && this.components.livePreview) {
                    this.components.livePreview.update(result.data.css);
                }

                // Update metrics
                if (result.data.metrics) {
                    this.updateMetrics(result.data.metrics);
                }

                // Update save button state
                this.updateSaveButtonState();

                // Show success notification
                this.showNotification(
                    this.i18n.templateApplied || 'Template applied successfully!',
                    'success'
                );

                return true;
            } else {
                const errorMessage = result.data?.message || this.i18n.templateApplyFailed || 'Failed to apply template';
                this.showNotification(errorMessage, 'error');
                return false;
            }
        } catch (error) {
            console.error('[WOOW Admin] Apply template error:', error);
            this.showNotification(
                this.i18n.networkError || 'Network error. Please try again.',
                'error'
            );
            return false;
        }
    }
}

// Initialize when script loads
const woowAdmin = new WoowAdmin();

// Export for global access
window.woowAdmin = woowAdmin;

export default WoowAdmin;
