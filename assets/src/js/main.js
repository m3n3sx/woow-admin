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
import { HeaderController } from './components/HeaderController.js';
import { LayoutController } from './components/LayoutController.js';
import { Validator } from './utils/Validator.js';

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
            unsavedChanges: false,
            realtimeEnabled: true // Real-time preview enabled by default
        };

        // Component instances
        this.components = {};

        // Validator (static class reference)
        this.validator = Validator;

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
            this.components.headerController = new HeaderController(this);
            this.components.layoutController = new LayoutController(this);
            this.components.colorPicker = new ColorPicker(this);
            this.components.livePreview = new LivePreview(this);
            this.components.paletteSelector = new PaletteSelector(this);
            this.components.templateGallery = new TemplateGallery(this);
            this.components.importExport = new ImportExport(this);
            this.components.tabManager = new TabManager(this);
            this.components.keyboardShortcuts = new KeyboardShortcuts(this);

            // Check for unsaved data and offer to restore
            this.checkUnsavedData();

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
        const saveButton = document.querySelector('#woow-save-btn');
        console.log('[WOOW Admin] Save button found:', saveButton);
        if (saveButton) {
            saveButton.addEventListener('click', (e) => {
                e.preventDefault();
                console.log('[WOOW Admin] Save button clicked!');
                this.saveSettings();
            });
            console.log('[WOOW Admin] Save button event listener attached');
        } else {
            console.error('[WOOW Admin] Save button NOT found!');
        }

        // Reset button
        const resetButton = document.querySelector('#woow-reset-btn');
        if (resetButton) {
            resetButton.addEventListener('click', (e) => {
                e.preventDefault();
                this.resetSettings();
            });
        }

        // Real-time toggle
        const realtimeToggle = document.querySelector('#woow-realtime-toggle');
        if (realtimeToggle) {
            realtimeToggle.addEventListener('change', (e) => {
                this.state.realtimeEnabled = e.target.checked;
                console.log('[WOOW Admin] Real-time mode:', this.state.realtimeEnabled ? 'ON' : 'OFF');
                
                // If enabled, trigger immediate preview
                if (this.state.realtimeEnabled) {
                    this.updateLivePreview();
                }
            });
            
            // Set initial state
            this.state.realtimeEnabled = realtimeToggle.checked;
        }

        // Track form changes
        const form = document.querySelector('#woow-settings-form');
        if (form) {
            form.addEventListener('change', () => {
                this.state.unsavedChanges = true;
                this.updateSaveButtonState();
                
                // Trigger live preview if real-time is enabled
                if (this.state.realtimeEnabled) {
                    this.debouncedPreview();
                }
            });

            form.addEventListener('input', () => {
                this.state.unsavedChanges = true;
                this.updateSaveButtonState();
                
                // Trigger live preview if real-time is enabled
                if (this.state.realtimeEnabled) {
                    this.debouncedPreview();
                }
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
        
        // Handle width unit change for admin bar
        this.setupWidthUnitHandler();
        
        // Handle conditional fields visibility
        this.setupConditionalFields();
    }
    
    /**
     * Setup width unit handler for admin bar width slider
     */
    setupWidthUnitHandler() {
        const widthUnitRadios = document.querySelectorAll('input[name="admin_bar[width_unit]"]');
        const widthSlider = document.querySelector('input[name="admin_bar[width]"]');
        const widthValue = widthSlider?.nextElementSibling;
        
        if (!widthUnitRadios.length || !widthSlider) return;
        
        widthUnitRadios.forEach(radio => {
            radio.addEventListener('change', (e) => {
                const unit = e.target.value;
                
                // Update slider attributes based on unit
                if (unit === '%') {
                    widthSlider.min = '50';
                    widthSlider.max = '100';
                    widthSlider.step = '5';
                    if (parseInt(widthSlider.value) > 100) {
                        widthSlider.value = '100';
                    }
                } else if (unit === 'px') {
                    widthSlider.min = '800';
                    widthSlider.max = '1920';
                    widthSlider.step = '50';
                    if (parseInt(widthSlider.value) < 800) {
                        widthSlider.value = '1200';
                    }
                }
                
                // Update data-unit attribute
                widthSlider.dataset.unit = unit;
                
                // Update value display
                if (widthValue && widthValue.classList.contains('woow-slider-value')) {
                    widthValue.textContent = widthSlider.value + unit;
                }
                
                // Trigger change event for live preview
                widthSlider.dispatchEvent(new Event('change', { bubbles: true }));
            });
        });
        
        // Update value display on slider input
        if (widthSlider && widthValue) {
            widthSlider.addEventListener('input', () => {
                const unit = widthSlider.dataset.unit || '%';
                widthValue.textContent = widthSlider.value + unit;
            });
        }
    }
    
    /**
     * Setup conditional fields visibility
     * Shows/hides fields based on data-show-when attribute
     */
    setupConditionalFields() {
        // Find all conditional fields (both old and new class names)
        const conditionalFields = document.querySelectorAll('.woow-conditional, .woow-conditional-field');
        
        if (!conditionalFields.length) return;
        
        // Parse conditions and group by controller field
        const conditions = new Map();
        
        conditionalFields.forEach(field => {
            // Support both data-show-when and data-condition/data-value formats
            let fieldName, expectedValue;
            
            if (field.dataset.showWhen) {
                // Old format: data-show-when="background_type=gradient"
                const condition = field.dataset.showWhen;
                [fieldName, expectedValue] = condition.split('=');
            } else if (field.dataset.condition && field.dataset.value) {
                // New format: data-condition="background_type" data-value="gradient"
                fieldName = field.dataset.condition;
                expectedValue = field.dataset.value;
            }
            
            if (!fieldName || !expectedValue) return;
            
            // Find controller field (select, radio, checkbox)
            const controllerField = document.querySelector(`[name*="[${fieldName}]"]`);
            if (!controllerField) return;
            
            // Store condition
            if (!conditions.has(controllerField)) {
                conditions.set(controllerField, []);
            }
            conditions.set(controllerField, [...conditions.get(controllerField), { field, expectedValue }]);
        });
        
        // Function to update visibility
        const updateVisibility = (controllerField) => {
            const fieldConditions = conditions.get(controllerField);
            if (!fieldConditions) return;
            
            // Get current value
            let currentValue;
            if (controllerField.type === 'checkbox') {
                currentValue = controllerField.checked ? '1' : '0';
            } else if (controllerField.type === 'radio') {
                const checkedRadio = document.querySelector(`[name="${controllerField.name}"]:checked`);
                currentValue = checkedRadio ? checkedRadio.value : '';
            } else {
                currentValue = controllerField.value;
            }
            
            // Update each conditional field
            fieldConditions.forEach(({ field, expectedValue }) => {
                if (currentValue === expectedValue) {
                    field.style.display = '';
                    field.classList.add('woow-conditional-visible');
                } else {
                    field.style.display = 'none';
                    field.classList.remove('woow-conditional-visible');
                }
            });
        };
        
        // Attach event listeners to controller fields
        conditions.forEach((fieldConditions, controllerField) => {
            // Initial visibility
            updateVisibility(controllerField);
            
            // Listen for changes
            if (controllerField.type === 'radio') {
                // For radio buttons, listen to all with same name
                const radioGroup = document.querySelectorAll(`[name="${controllerField.name}"]`);
                radioGroup.forEach(radio => {
                    radio.addEventListener('change', () => updateVisibility(controllerField));
                });
            } else {
                controllerField.addEventListener('change', () => updateVisibility(controllerField));
            }
        });
        
        console.log('[WOOW Admin] Conditional fields initialized:', conditionalFields.length);
    }

    /**
     * Update save button state based on unsaved changes
     */
    updateSaveButtonState() {
        const saveButton = document.querySelector('#woow-save-btn');
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
        const form = document.querySelector('#woow-settings-form');

        if (!form) {
            return formData;
        }

        // Collect all inputs, selects, and textareas
        const inputs = form.querySelectorAll('input, select, textarea');

        inputs.forEach(input => {
            const name = input.name;
            if (!name) return;

            // ✅ FIX: Skip hidden/invisible inputs (conditional fields)
            // This prevents duplicate field names from overwriting visible values
            if (input.type !== 'hidden') {
                const isVisible = input.offsetParent !== null;
                const parentHidden = input.closest('[style*="display: none"]') || 
                                   input.closest('.woow-conditional:not(.woow-conditional-visible)');
                
                if (!isVisible || parentHidden) {
                    console.log(`[collectFormData] Skipping non-visible input: ${name} (value: ${input.value})`);
                    return;
                }
            }

            // Parse name to get section and key (e.g., "admin_bar[height]")
            const match = name.match(/^([^\[]+)\[([^\]]+)\]$/);
            if (!match) return;

            const [, section, key] = match;
            
            // Debug: log background_color (only visible ones now)
            if (key === 'background_color') {
                console.log('[collectFormData] background_color found (VISIBLE):', {
                    value: input.value,
                    type: input.type,
                    visible: input.offsetParent !== null,
                    display: window.getComputedStyle(input).display
                });
            }

            // Initialize section if needed
            if (!formData[section]) {
                formData[section] = {};
            }

            // Get value based on input type with proper type conversion
            let value;

            // Task 3.2: Checkbox handling
            if (input.type === 'checkbox') {
                value = input.checked; // Boolean
            } 
            // Radio button handling
            else if (input.type === 'radio') {
                if (input.checked) {
                    value = input.value;
                } else {
                    return; // Skip unchecked radio buttons
                }
            } 
            // Task 3.3: Range slider handling with type conversion
            else if (input.type === 'range') {
                const dataType = input.dataset.type;
                
                if (dataType === 'opacity') {
                    // Convert 0-100 range to 0-1 float
                    value = parseFloat(input.value) / 100;
                } else if (dataType === 'unitless') {
                    // Unitless value (e.g., width when unit is separate field)
                    value = input.value;
                } else {
                    // Append unit to value
                    const unit = input.dataset.unit || '';
                    value = input.value + unit;
                }
            } 
            // Task 3.4: Number input handling with type conversion
            else if (input.type === 'number') {
                const dataType = input.dataset.type;
                
                if (dataType === 'unitless') {
                    // Line-height: unitless float
                    value = parseFloat(input.value);
                } else {
                    // Size with unit
                    const unit = input.dataset.unit || 'px';
                    value = input.value + unit;
                }
            } 
            // Task 3.5: Select and text input handling (use as-is)
            else {
                value = input.value;
            }

            // Task 3.6: Store in nested object structure
            formData[section][key] = value;
        });

        return formData;
    }

    /**
     * Show notification toast
     *
     * @param {string} message - Notification message
     * @param {string} type - Notification type (success, error, warning, info)
     * @param {Object} options - Additional options (duration, dismissible, action)
     */
    showNotification(message, type = 'info', options = {}) {
        const {
            duration = 3000,
            dismissible = true,
            action = null
        } = options;

        // Remove existing notifications
        const existing = document.querySelectorAll('.woow-toast');
        existing.forEach(toast => toast.remove());

        // Icon mapping
        const icons = {
            success: `<svg class="woow-toast-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M16.6667 5L7.50004 14.1667L3.33337 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>`,
            error: `<svg class="woow-toast-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15 5L5 15M5 5L15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>`,
            warning: `<svg class="woow-toast-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 6V10M10 14H10.01M18 10C18 14.4183 14.4183 18 10 18C5.58172 18 2 14.4183 2 10C2 5.58172 5.58172 2 10 2C14.4183 2 18 5.58172 18 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>`,
            info: `<svg class="woow-toast-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 9V13M10 6H10.01M18 10C18 14.4183 14.4183 18 10 18C5.58172 18 2 14.4183 2 10C2 5.58172 5.58172 2 10 2C14.4183 2 18 5.58172 18 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>`
        };

        // Create toast element
        const toast = document.createElement('div');
        toast.className = `woow-toast woow-toast-${type}`;

        // Build toast content
        let content = `
            <div class="woow-toast-content">
                ${icons[type] || icons.info}
                <span class="woow-toast-message">${message}</span>
            </div>
        `;

        // Add action button if provided
        if (action) {
            content += `
                <button class="woow-toast-action" data-action="${action.id}">
                    ${action.label}
                </button>
            `;
        }

        // Add dismiss button if dismissible
        if (dismissible) {
            content += `
                <button class="woow-toast-dismiss" aria-label="Dismiss">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            `;
        }

        toast.innerHTML = content;

        // Add to DOM
        document.body.appendChild(toast);

        // Bind dismiss button
        if (dismissible) {
            const dismissBtn = toast.querySelector('.woow-toast-dismiss');
            if (dismissBtn) {
                dismissBtn.addEventListener('click', () => {
                    toast.classList.remove('woow-toast-show');
                    setTimeout(() => toast.remove(), 300);
                });
            }
        }

        // Bind action button
        if (action) {
            const actionBtn = toast.querySelector('.woow-toast-action');
            if (actionBtn) {
                actionBtn.addEventListener('click', () => {
                    if (typeof action.callback === 'function') {
                        action.callback();
                    }
                    toast.classList.remove('woow-toast-show');
                    setTimeout(() => toast.remove(), 300);
                });
            }
        }

        // Trigger animation
        setTimeout(() => toast.classList.add('woow-toast-show'), 10);

        // Auto-dismiss after duration (if not 0)
        if (duration > 0) {
            setTimeout(() => {
                toast.classList.remove('woow-toast-show');
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }
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

            // Update preview with settings object directly
            if (this.components.livePreview && this.components.livePreview.isEnabled()) {
                await this.components.livePreview.updatePreview(formData);
            }

            // Also generate CSS for backward compatibility and real-time mode
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
                // If real-time mode is enabled, update current page
                if (this.state.realtimeEnabled) {
                    this.injectLiveCSS(result.data.css);
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
            // Don't throw - allow operation to continue
        }
    }

    /**
     * Inject CSS directly into current page for live preview
     *
     * @param {string} css - CSS to inject
     */
    injectLiveCSS(css) {
        // Get or create live preview style element
        let styleEl = document.getElementById('woow-live-preview-css');
        
        if (!styleEl) {
            styleEl = document.createElement('style');
            styleEl.id = 'woow-live-preview-css';
            styleEl.type = 'text/css';
            document.head.appendChild(styleEl);
        }

        // Update CSS content
        styleEl.textContent = css;

        console.log('[WOOW Admin] Live CSS injected to current page');
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
        console.log('[WOOW Admin] saveSettings() called');
        console.log('[WOOW Admin] Current nonce:', this.nonce);
        console.log('[WOOW Admin] AJAX URL:', this.ajaxUrl);
        
        try {
            // Notify header controller
            if (this.components.headerController) {
                this.components.headerController.onSaveStart();
            }

            // Show loading state
            const saveButton = document.querySelector('#woow-save-btn');
            if (saveButton) {
                saveButton.disabled = true;
                saveButton.textContent = this.i18n.saving || 'Saving...';
                console.log('[WOOW Admin] Save button disabled');
            }

            // Collect form data
            const formData = this.collectFormData();

            // Save form data to localStorage before attempting save
            this.saveFormDataToStorage(formData);

            // Validate form data
            const validationResult = this.validator.validateAll(formData);

            // If validation fails, show errors and attempt partial save
            if (!validationResult.valid) {
                console.warn('[WOOW Admin] Validation errors found:', validationResult.errors);
                
                // Show validation errors to user
                validationResult.errors.forEach(error => {
                    console.error('[WOOW Admin] Validation error:', {
                        field: error.field,
                        message: error.message,
                        value: error.value
                    });
                    this.showFieldError(error.field, error.message);
                });

                // Extract valid fields for partial save
                const validFormData = this.extractValidFields(formData, validationResult.validFields);
                
                // If we have valid fields, attempt partial save
                if (Object.keys(validFormData).length > 0) {
                    const validFieldCount = validationResult.validFields.length;
                    const errorCount = validationResult.errors.length;
                    
                    console.log(`[WOOW Admin] Attempting partial save: ${validFieldCount} valid fields, ${errorCount} errors`);
                    
                    // Prepare AJAX request with valid fields only
                    const partialData = new FormData();
                    partialData.append('action', 'woow_save_settings');
                    partialData.append('nonce', this.nonce);
                    partialData.append('settings', JSON.stringify(validFormData));

                    // Send request with retry logic
                    let partialResponse;
                    try {
                        partialResponse = await this.retryFetch(
                            () => fetch(this.ajaxUrl, {
                                method: 'POST',
                                body: partialData
                            }),
                            1, // Retry once
                            1000 // Wait 1 second before retry
                        );
                    } catch (networkError) {
                        console.error('[WOOW Admin] Network error during partial save:', networkError);
                        
                        this.showNotification(
                            'Network error during partial save. Please try again.',
                            'error'
                        );
                        
                        // Notify header controller of error
                        if (this.components.headerController) {
                            this.components.headerController.onSaveError();
                        }
                        
                        return false;
                    }

                    const result = await partialResponse.json();

                    if (result.success) {
                        // Update state with saved settings
                        this.state.settings = { ...this.state.settings, ...validFormData };
                        
                        // Show partial success notification
                        this.showNotification(
                            `Saved ${validFieldCount} fields. ${errorCount} field(s) have errors - please fix them.`,
                            'warning'
                        );

                        // Update preview with new CSS
                        if (result.data.css && this.components.livePreview) {
                            this.components.livePreview.update(result.data.css);
                        }

                        // Keep error messages visible
                        // Don't clear field errors - user needs to fix them
                        
                        return false; // Still return false because validation failed
                    }
                }

                // Show notification about validation errors
                this.showNotification(
                    `Found ${validationResult.errors.length} validation error(s). Please check the highlighted fields.`,
                    'error'
                );

                // Notify header controller of error
                if (this.components.headerController) {
                    this.components.headerController.onSaveError();
                }

                return false;
            }

            // Prepare AJAX request
            const data = new FormData();
            data.append('action', 'woow_save_settings');
            data.append('nonce', this.nonce);
            data.append('settings', JSON.stringify(formData));

            // Send request with retry logic
            let response;
            try {
                response = await this.retryFetch(
                    () => fetch(this.ajaxUrl, {
                        method: 'POST',
                        body: data
                    }),
                    1, // Retry once
                    1000 // Wait 1 second before retry
                );
                console.log('[WOOW Admin] AJAX response received, status:', response.status);
            } catch (networkError) {
                console.error('[WOOW Admin] Network error after retry:', networkError);
                
                // Notify header controller of error
                if (this.components.headerController) {
                    this.components.headerController.onSaveError();
                }
                
                // Show network error with retry button
                this.showNotification(
                    'Network error. Check your connection and try again.',
                    'error'
                );
                
                return false;
            }

            const result = await response.json();

            if (result.success) {
                // Update state
                this.state.settings = result.data.settings || formData;
                this.state.unsavedChanges = false;

                // Clear any field errors
                this.clearFieldErrors();

                // Clear saved form data from localStorage
                this.clearSavedFormData();

                // Notify header controller of success
                if (this.components.headerController) {
                    this.components.headerController.onSaveSuccess();
                }

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
                // Notify header controller of error
                if (this.components.headerController) {
                    this.components.headerController.onSaveError();
                }

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
            // Notify header controller of error
            if (this.components.headerController) {
                this.components.headerController.onSaveError();
            }

            console.error('[WOOW Admin] Save error:', error);
            this.showNotification(
                this.i18n.networkError || 'Network error. Please try again.',
                'error'
            );
            return false;
        } finally {
            // Restore button state
            const saveButton = document.querySelector('#woow-save-btn');
            if (saveButton) {
                saveButton.disabled = false;
                this.updateSaveButtonState();
            }
        }
    }

    /**
     * Reset all settings to defaults
     */
    async resetSettings() {
        // Confirm with user
        if (!confirm('Are you sure you want to reset all settings to defaults? This cannot be undone.')) {
            return;
        }

        try {
            console.log('[WOOW Admin] Reset settings called');
            console.log('[WOOW Admin] Nonce:', this.nonce);
            console.log('[WOOW Admin] AJAX URL:', this.ajaxUrl);
            
            // Show loading state
            const resetButton = document.querySelector('#woow-reset-btn');
            if (resetButton) {
                resetButton.disabled = true;
                resetButton.textContent = 'Resetting...';
            }

            // Prepare AJAX request
            const data = new FormData();
            data.append('action', 'woow_reset_settings');
            data.append('nonce', this.nonce);
            
            console.log('[WOOW Admin] FormData prepared:', {
                action: 'woow_reset_settings',
                nonce: this.nonce
            });

            // Send request
            const response = await fetch(this.ajaxUrl, {
                method: 'POST',
                body: data
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification('Settings reset to defaults successfully!', 'success');
                
                // Reload page to show default values
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                this.showNotification(result.data || 'Failed to reset settings', 'error');
            }
        } catch (error) {
            console.error('[WOOW Admin] Reset error:', error);
            this.showNotification('Network error. Please try again.', 'error');
        } finally {
            // Restore button state
            const resetButton = document.querySelector('#woow-reset-btn');
            if (resetButton) {
                resetButton.disabled = false;
                resetButton.innerHTML = '<span class="dashicons dashicons-image-rotate"></span> Reset';
            }
        }
    }

    /**
     * Show field-specific error
     *
     * @param {string} field - Field key (e.g., 'admin_bar.opacity')
     * @param {string} message - Error message
     */
    showFieldError(field, message) {
        // Convert field key to input name format
        // 'admin_bar.opacity' -> 'admin_bar[opacity]'
        const parts = field.split('.');
        let inputName = field;
        
        if (parts.length === 2) {
            inputName = `${parts[0]}[${parts[1]}]`;
        }

        // Find the input element
        const input = document.querySelector(`[name="${inputName}"]`);
        
        if (!input) {
            console.warn(`[WOOW Admin] Could not find input for field: ${field}`);
            return;
        }

        // Add error class
        input.classList.add('woow-field-error');

        // Find or create error message element
        let errorEl = input.parentElement.querySelector('.woow-error-message');
        
        if (!errorEl) {
            errorEl = document.createElement('span');
            errorEl.className = 'woow-error-message';
            input.parentElement.appendChild(errorEl);
        }

        errorEl.textContent = message;
    }

    /**
     * Clear all field errors
     */
    clearFieldErrors() {
        // Remove error classes
        const errorInputs = document.querySelectorAll('.woow-field-error');
        errorInputs.forEach(input => {
            input.classList.remove('woow-field-error');
        });

        // Remove error messages
        const errorMessages = document.querySelectorAll('.woow-error-message');
        errorMessages.forEach(msg => {
            msg.remove();
        });
    }

    /**
     * Extract valid fields from form data based on validation result
     *
     * @param {Object} formData - Complete form data
     * @param {Array} validFields - Array of valid field keys (e.g., ['admin_bar.opacity', 'admin_bar.height'])
     * @returns {Object} Form data with only valid fields
     */
    extractValidFields(formData, validFields) {
        const validData = {};
        
        // Convert validFields array to a Set for faster lookup
        const validFieldsSet = new Set(validFields);
        
        // Iterate through form data sections
        for (const section in formData) {
            if (!formData.hasOwnProperty(section)) continue;
            
            const sectionData = formData[section];
            if (typeof sectionData !== 'object') continue;
            
            // Check each field in the section
            for (const key in sectionData) {
                if (!sectionData.hasOwnProperty(key)) continue;
                
                const fieldKey = `${section}.${key}`;
                
                // If this field is valid, include it
                if (validFieldsSet.has(fieldKey)) {
                    if (!validData[section]) {
                        validData[section] = {};
                    }
                    validData[section][key] = sectionData[key];
                }
            }
        }
        
        return validData;
    }

    /**
     * Retry fetch request with exponential backoff
     *
     * @param {Function} fetchFn - Function that returns a fetch promise
     * @param {number} maxRetries - Maximum number of retries (default: 1)
     * @param {number} delay - Initial delay in milliseconds (default: 1000)
     * @returns {Promise} Fetch response
     */
    async retryFetch(fetchFn, maxRetries = 1, delay = 1000) {
        let lastError;
        
        for (let attempt = 0; attempt <= maxRetries; attempt++) {
            try {
                console.log(`[WOOW Admin] Fetch attempt ${attempt + 1}/${maxRetries + 1}`);
                const response = await fetchFn();
                return response;
            } catch (error) {
                lastError = error;
                console.error(`[WOOW Admin] Fetch attempt ${attempt + 1} failed:`, error);
                
                // If this was the last attempt, throw the error
                if (attempt === maxRetries) {
                    throw error;
                }
                
                // Wait before retrying
                console.log(`[WOOW Admin] Retrying in ${delay}ms...`);
                await new Promise(resolve => setTimeout(resolve, delay));
            }
        }
        
        throw lastError;
    }

    /**
     * Save form data to localStorage
     *
     * @param {Object} formData - Form data to save
     */
    saveFormDataToStorage(formData) {
        try {
            const dataToSave = {
                timestamp: Date.now(),
                data: formData
            };
            localStorage.setItem('woow_unsaved_settings', JSON.stringify(dataToSave));
            console.log('[WOOW Admin] Form data saved to localStorage');
        } catch (error) {
            console.error('[WOOW Admin] Failed to save form data to localStorage:', error);
        }
    }

    /**
     * Clear saved form data from localStorage
     */
    clearSavedFormData() {
        try {
            localStorage.removeItem('woow_unsaved_settings');
            console.log('[WOOW Admin] Cleared saved form data from localStorage');
        } catch (error) {
            console.error('[WOOW Admin] Failed to clear saved form data:', error);
        }
    }

    /**
     * Check for unsaved data and offer to restore
     */
    checkUnsavedData() {
        try {
            const savedData = localStorage.getItem('woow_unsaved_settings');
            
            if (!savedData) {
                return;
            }

            const parsed = JSON.parse(savedData);
            const timestamp = parsed.timestamp;
            const data = parsed.data;

            // Check if data is less than 24 hours old
            const ageInHours = (Date.now() - timestamp) / (1000 * 60 * 60);
            
            if (ageInHours > 24) {
                // Data is too old, clear it
                this.clearSavedFormData();
                return;
            }

            // Ask user if they want to restore
            const restore = confirm(
                'You have unsaved changes from a previous session. Would you like to restore them?'
            );

            if (restore) {
                // Restore form data
                this.updateFormFields(data);
                this.state.unsavedChanges = true;
                this.updateSaveButtonState();
                
                this.showNotification(
                    'Previous session data restored. Review and save when ready.',
                    'info'
                );
            } else {
                // User declined, clear the saved data
                this.clearSavedFormData();
            }
        } catch (error) {
            console.error('[WOOW Admin] Failed to check unsaved data:', error);
            // Clear corrupted data
            this.clearSavedFormData();
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
