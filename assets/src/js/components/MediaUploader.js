/**
 * Media Uploader Component
 * 
 * Handles WordPress media library integration for image uploads
 * 
 * @package WoowAdmin
 * @since 1.0.0
 */

export class MediaUploader {
    /**
     * Constructor
     * 
     * @param {WoowAdmin} app - Main application instance
     */
    constructor(app) {
        this.app = app;
        this.init();
    }

    /**
     * Initialize media uploader
     */
    init() {
        this.bindEvents();
        console.log('[MediaUploader] Initialized');
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Handle all upload buttons
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('woow-upload-image') || 
                e.target.closest('.woow-upload-image')) {
                e.preventDefault();
                
                const button = e.target.classList.contains('woow-upload-image') 
                    ? e.target 
                    : e.target.closest('.woow-upload-image');
                
                this.openMediaUploader(button);
            }
        });
    }

    /**
     * Open WordPress media uploader
     * 
     * @param {HTMLElement} button - Upload button element
     */
    openMediaUploader(button) {
        const targetSelector = button.dataset.target;
        
        if (!targetSelector) {
            console.error('[MediaUploader] No target specified');
            return;
        }

        const targetInput = document.querySelector(targetSelector);
        
        if (!targetInput) {
            console.error('[MediaUploader] Target input not found:', targetSelector);
            return;
        }

        // Check if wp.media is available
        if (typeof wp === 'undefined' || typeof wp.media === 'undefined') {
            console.error('[MediaUploader] WordPress media library not available');
            this.app.showNotification('Media library not available', 'error');
            return;
        }

        // Create media frame
        const frame = wp.media({
            title: 'Select or Upload Image',
            button: {
                text: 'Use this image'
            },
            multiple: false,
            library: {
                type: 'image'
            }
        });

        // When an image is selected
        frame.on('select', () => {
            const attachment = frame.state().get('selection').first().toJSON();
            
            // Set the URL in the hidden input
            targetInput.value = attachment.url;
            
            // Trigger change event
            targetInput.dispatchEvent(new Event('change', { bubbles: true }));
            
            // Show preview
            this.showPreview(button, attachment.url);
            
            // Mark as changed
            if (this.app.components.headerController) {
                this.app.components.headerController.incrementChanges();
            }
            
            console.log('[MediaUploader] Image selected:', attachment.url);
        });

        // Open the media frame
        frame.open();
    }

    /**
     * Show image preview
     * 
     * @param {HTMLElement} button - Upload button
     * @param {string} imageUrl - Image URL
     */
    showPreview(button, imageUrl) {
        const container = button.closest('.woow-image-upload');
        
        if (!container) return;

        // Remove existing preview
        const existingPreview = container.querySelector('.woow-image-preview');
        if (existingPreview) {
            existingPreview.remove();
        }

        // Create new preview
        const preview = document.createElement('img');
        preview.src = imageUrl;
        preview.className = 'woow-image-preview';
        preview.style.maxWidth = '100%';
        preview.style.marginTop = '10px';
        preview.style.borderRadius = '8px';
        preview.style.border = '1px solid #e2e8f0';
        
        // Add remove button
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'button woow-remove-image';
        removeBtn.textContent = 'Remove';
        removeBtn.style.marginTop = '8px';
        removeBtn.style.marginLeft = '8px';
        
        removeBtn.addEventListener('click', (e) => {
            e.preventDefault();
            this.removeImage(button);
        });

        // Insert preview after button
        button.parentNode.insertBefore(preview, button.nextSibling);
        button.parentNode.insertBefore(removeBtn, preview.nextSibling);
    }

    /**
     * Remove image
     * 
     * @param {HTMLElement} button - Upload button
     */
    removeImage(button) {
        const targetSelector = button.dataset.target;
        const targetInput = document.querySelector(targetSelector);
        
        if (targetInput) {
            targetInput.value = '';
            targetInput.dispatchEvent(new Event('change', { bubbles: true }));
        }

        const container = button.closest('.woow-image-upload');
        if (container) {
            const preview = container.querySelector('.woow-image-preview');
            const removeBtn = container.querySelector('.woow-remove-image');
            
            if (preview) preview.remove();
            if (removeBtn) removeBtn.remove();
        }

        // Mark as changed
        if (this.app.components.headerController) {
            this.app.components.headerController.incrementChanges();
        }
    }
}
