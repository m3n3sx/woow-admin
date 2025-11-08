/**
 * ImportExport Component
 *
 * Handles settings import/export functionality.
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

export class ImportExport {
    /**
     * Constructor
     *
     * @param {WoowAdmin} woowAdmin - Main controller instance
     */
    constructor(woowAdmin) {
        this.woow = woowAdmin;
        this.exportButton = null;
        this.importButton = null;
        this.importInput = null;
        this.init();
    }

    /**
     * Initialize import/export
     */
    init() {
        this.exportButton = document.querySelector('.woow-export-button');
        this.importButton = document.querySelector('.woow-import-button');
        this.importInput = document.querySelector('.woow-import-input');

        this.bindEvents();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Export button
        if (this.exportButton) {
            this.exportButton.addEventListener('click', (e) => {
                e.preventDefault();
                this.exportSettings();
            });
        }

        // Import button (triggers file input)
        if (this.importButton) {
            this.importButton.addEventListener('click', (e) => {
                e.preventDefault();
                if (this.importInput) {
                    this.importInput.click();
                }
            });
        }

        // Import file input
        if (this.importInput) {
            this.importInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    this.importSettings(file);
                }
                // Reset input so same file can be selected again
                e.target.value = '';
            });
        }
    }

    /**
     * Export settings as JSON file
     */
    async exportSettings() {
        try {
            this.woow.showNotification(
                this.woow.i18n.exporting || 'Exporting settings...',
                'info'
            );

            // Prepare AJAX request
            const data = new FormData();
            data.append('action', 'woow_export_settings');
            data.append('nonce', this.woow.nonce);

            // Send request
            const response = await fetch(this.woow.ajaxUrl, {
                method: 'POST',
                body: data
            });

            const result = await response.json();

            if (result.success && result.data.json) {
                // Create download
                this.downloadJSON(result.data.json, 'woow-admin-settings.json');

                this.woow.showNotification(
                    this.woow.i18n.exportSuccess || 'Settings exported successfully!',
                    'success'
                );
            } else {
                const errorMessage = result.data?.message || this.woow.i18n.exportFailed || 'Failed to export settings';
                this.woow.showNotification(errorMessage, 'error');
            }
        } catch (error) {
            console.error('[ImportExport] Export error:', error);
            this.woow.showNotification(
                this.woow.i18n.networkError || 'Network error. Please try again.',
                'error'
            );
        }
    }

    /**
     * Import settings from JSON file
     *
     * @param {File} file - JSON file to import
     */
    async importSettings(file) {
        try {
            // Read file content
            const content = await this.readFile(file);

            // Validate JSON
            if (!this.validateJSON(content)) {
                this.woow.showNotification(
                    this.woow.i18n.invalidJSON || 'Invalid JSON file. Please check the file format.',
                    'error'
                );
                return;
            }

            // Show confirmation dialog
            const confirmed = confirm(
                this.woow.i18n.importConfirm ||
                'This will replace your current settings. A backup will be created automatically. Continue?'
            );

            if (!confirmed) {
                return;
            }

            this.woow.showNotification(
                this.woow.i18n.importing || 'Importing settings...',
                'info'
            );

            // Prepare AJAX request
            const data = new FormData();
            data.append('action', 'woow_import_settings');
            data.append('nonce', this.woow.nonce);
            data.append('json', content);

            // Send request
            const response = await fetch(this.woow.ajaxUrl, {
                method: 'POST',
                body: data
            });

            const result = await response.json();

            if (result.success) {
                // Update state
                this.woow.state.settings = result.data.settings || this.woow.state.settings;
                this.woow.state.unsavedChanges = false;

                // Update form fields
                this.woow.updateFormFields(result.data.settings);

                // Update preview
                if (result.data.css && this.woow.components.livePreview) {
                    this.woow.components.livePreview.update(result.data.css);
                }

                // Update metrics
                if (result.data.metrics) {
                    this.woow.updateMetrics(result.data.metrics);
                }

                this.woow.showNotification(
                    this.woow.i18n.importSuccess || 'Settings imported successfully!',
                    'success'
                );

                // Suggest page reload
                setTimeout(() => {
                    if (confirm(this.woow.i18n.reloadPrompt || 'Reload page to see all changes?')) {
                        location.reload();
                    }
                }, 1000);
            } else {
                const errorMessage = result.data?.message || this.woow.i18n.importFailed || 'Failed to import settings';
                this.woow.showNotification(errorMessage, 'error');

                // Show validation errors if available
                if (result.data?.errors && Array.isArray(result.data.errors)) {
                    console.error('[ImportExport] Validation errors:', result.data.errors);
                    result.data.errors.forEach(error => {
                        this.woow.showNotification(error, 'error');
                    });
                }
            }
        } catch (error) {
            console.error('[ImportExport] Import error:', error);
            this.woow.showNotification(
                this.woow.i18n.importError || 'Error reading file. Please try again.',
                'error'
            );
        }
    }

    /**
     * Read file content as text
     *
     * @param {File} file - File to read
     * @returns {Promise<string>} File content
     */
    readFile(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();

            reader.onload = (e) => {
                resolve(e.target.result);
            };

            reader.onerror = (e) => {
                reject(new Error('Failed to read file'));
            };

            reader.readAsText(file);
        });
    }

    /**
     * Validate JSON structure
     *
     * @param {string} jsonString - JSON string to validate
     * @returns {boolean} True if valid
     */
    validateJSON(jsonString) {
        try {
            const data = JSON.parse(jsonString);

            // Check if it's an object
            if (typeof data !== 'object' || data === null) {
                return false;
            }

            // Check for required metadata
            if (!data.version || !data.exported_at) {
                console.warn('[ImportExport] Missing metadata in JSON');
            }

            // Check for settings object
            if (!data.settings || typeof data.settings !== 'object') {
                return false;
            }

            return true;
        } catch (error) {
            console.error('[ImportExport] JSON validation error:', error);
            return false;
        }
    }

    /**
     * Download JSON as file
     *
     * @param {string} jsonString - JSON string to download
     * @param {string} filename - Filename for download
     */
    downloadJSON(jsonString, filename) {
        // Create blob
        const blob = new Blob([jsonString], { type: 'application/json' });

        // Create download link
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = filename;

        // Trigger download
        document.body.appendChild(link);
        link.click();

        // Cleanup
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
    }
}

export default ImportExport;
