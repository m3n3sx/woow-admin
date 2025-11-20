/**
 * GoogleFontsLoader Component
 *
 * Handles dynamic loading of Google Fonts for preview and application.
 * Implements font caching, error handling, and preview isolation.
 *
 * @package WoowAdmin
 * @since 2.0.0
 */

export class GoogleFontsLoader {
    /**
     * Constructor
     *
     * @param {WoowAdmin} woowAdmin - Main controller instance
     */
    constructor(woowAdmin) {
        this.woow = woowAdmin;
        
        // Font cache to prevent duplicate requests
        this.loadedFonts = new Map();
        
        // Preview state
        this.previewActive = {
            body: false,
            heading: false
        };
        
        this.init();
    }

    /**
     * Initialize the font loader
     */
    init() {
        console.log('[GoogleFontsLoader] Initializing...');
        
        // Bind font selector change events
        this.bindFontSelectors();
        
        // Bind weight checkbox change events
        this.bindWeightCheckboxes();
        
        // Bind preview button events
        this.bindPreviewButtons();
        
        // Load default fonts on page load
        this.loadDefaultFonts();
        
        console.log('[GoogleFontsLoader] Initialized successfully');
    }

    /**
     * Bind font selector change events
     */
    bindFontSelectors() {
        const fontSelectors = document.querySelectorAll('.woow-font-selector');
        
        fontSelectors.forEach(selector => {
            selector.addEventListener('change', (e) => {
                const fontType = e.target.dataset.fontType;
                const fontName = e.target.value;
                
                console.log(`[GoogleFontsLoader] Font changed: ${fontType} -> ${fontName}`);
                
                // If preview is active, update it
                if (this.previewActive[fontType]) {
                    this.updatePreview(fontType, fontName);
                }
            });
        });
    }

    /**
     * Bind weight checkbox change events
     */
    bindWeightCheckboxes() {
        const weightCheckboxes = document.querySelectorAll('input[name="typography[body_weights][]"], input[name="typography[heading_weights][]"]');
        
        weightCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', (e) => {
                // Determine font type from input name
                const fontType = e.target.name.includes('body_weights') ? 'body' : 'heading';
                
                console.log(`[GoogleFontsLoader] Weights changed for ${fontType}`);
                
                // If preview is active, reload font with new weights
                if (this.previewActive[fontType]) {
                    const selector = document.querySelector(`.woow-font-selector[data-font-type="${fontType}"]`);
                    if (selector) {
                        this.updatePreview(fontType, selector.value);
                    }
                }
            });
        });
    }

    /**
     * Bind preview button events
     */
    bindPreviewButtons() {
        // Preview buttons
        const previewButtons = document.querySelectorAll('.woow-font-preview-btn');
        
        previewButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const fontType = e.target.dataset.fontType;
                this.showPreview(fontType);
            });
        });
        
        // Close buttons
        const closeButtons = document.querySelectorAll('.woow-font-preview-close');
        
        closeButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const fontType = e.target.dataset.fontType;
                this.hidePreview(fontType);
            });
        });
    }

    /**
     * Load default fonts on page load
     * Loads fonts that are already selected in the form
     */
    async loadDefaultFonts() {
        console.log('[GoogleFontsLoader] Loading default fonts...');
        
        try {
            // Get body font
            const bodySelector = document.querySelector('.woow-font-selector[data-font-type="body"]');
            if (bodySelector && bodySelector.value !== 'system') {
                await this.loadFont(bodySelector.value, 'body');
            }
            
            // Get heading font
            const headingSelector = document.querySelector('.woow-font-selector[data-font-type="heading"]');
            if (headingSelector && headingSelector.value !== 'system') {
                await this.loadFont(headingSelector.value, 'heading');
            }
            
            console.log('[GoogleFontsLoader] Default fonts loaded');
        } catch (error) {
            console.error('[GoogleFontsLoader] Error loading default fonts:', error);
            // Don't throw - allow page to continue functioning
        }
    }

    /**
     * Load a font dynamically
     *
     * @param {string} fontName - Name of the font to load
     * @param {string} fontType - Type of font ('body' or 'heading')
     * @returns {Promise<void>}
     */
    async loadFont(fontName, fontType) {
        // Skip system fonts
        if (fontName === 'system' || !fontName) {
            console.log(`[GoogleFontsLoader] Skipping system font for ${fontType}`);
            return;
        }
        
        // Check cache
        const cacheKey = `${fontName}-${fontType}`;
        if (this.loadedFonts.has(cacheKey)) {
            console.log(`[GoogleFontsLoader] Font already loaded (cached): ${fontName}`);
            return;
        }
        
        try {
            // Get selected weights
            const weights = this.getSelectedWeights(fontType);
            
            // Build font URL
            const fontUrl = this.buildFontUrl(fontName, weights);
            
            if (!fontUrl) {
                console.warn(`[GoogleFontsLoader] Could not build URL for font: ${fontName}`);
                return;
            }
            
            console.log(`[GoogleFontsLoader] Loading font: ${fontName} with weights:`, weights);
            
            // Load font via link element
            await this.loadFontLink(fontUrl, fontName);
            
            // Cache the loaded font
            this.loadedFonts.set(cacheKey, {
                fontName,
                weights,
                url: fontUrl,
                loadedAt: Date.now()
            });
            
            console.log(`[GoogleFontsLoader] Font loaded successfully: ${fontName}`);
        } catch (error) {
            console.error(`[GoogleFontsLoader] Error loading font ${fontName}:`, error);
            // Log error but don't throw - graceful degradation to system fonts
            this.logError(fontName, error);
        }
    }

    /**
     * Load font via link element
     *
     * @param {string} fontUrl - Google Fonts API URL
     * @param {string} fontName - Font name for error reporting
     * @returns {Promise<void>}
     */
    loadFontLink(fontUrl, fontName) {
        return new Promise((resolve, reject) => {
            // Check if link already exists
            const existingLink = document.querySelector(`link[href="${fontUrl}"]`);
            if (existingLink) {
                console.log(`[GoogleFontsLoader] Font link already exists: ${fontName}`);
                resolve();
                return;
            }
            
            // Create link element
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = fontUrl;
            
            // Set timeout for loading
            const timeout = setTimeout(() => {
                console.warn(`[GoogleFontsLoader] Font loading timeout: ${fontName}`);
                // Don't reject - allow fallback to system fonts
                resolve();
            }, 5000);
            
            // Handle successful load
            link.onload = () => {
                clearTimeout(timeout);
                console.log(`[GoogleFontsLoader] Font link loaded: ${fontName}`);
                resolve();
            };
            
            // Handle load error
            link.onerror = (error) => {
                clearTimeout(timeout);
                console.error(`[GoogleFontsLoader] Font link error: ${fontName}`, error);
                // Don't reject - allow fallback to system fonts
                resolve();
            };
            
            // Append to head
            document.head.appendChild(link);
        });
    }

    /**
     * Build Google Fonts API URL
     *
     * @param {string} fontName - Font name
     * @param {Array<number>} weights - Array of font weights
     * @returns {string} Google Fonts API URL
     */
    buildFontUrl(fontName, weights = [400]) {
        if (!fontName || fontName === 'system') {
            return '';
        }
        
        // Ensure we have at least one weight
        if (!weights || weights.length === 0) {
            weights = [400];
        }
        
        // Sort weights
        const sortedWeights = [...weights].sort((a, b) => a - b);
        
        // Encode font name (spaces become +)
        const encodedFontName = fontName.replace(/\s+/g, '+');
        
        // Build weights parameter
        const weightsParam = sortedWeights.join(';');
        
        // Construct URL with display=swap
        const url = `https://fonts.googleapis.com/css2?family=${encodedFontName}:wght@${weightsParam}&display=swap`;
        
        console.log(`[GoogleFontsLoader] Built URL: ${url}`);
        
        return url;
    }

    /**
     * Get selected weights for a font type
     *
     * @param {string} fontType - Font type ('body' or 'heading')
     * @returns {Array<number>} Array of selected weights
     */
    getSelectedWeights(fontType) {
        const checkboxName = fontType === 'body' 
            ? 'typography[body_weights][]' 
            : 'typography[heading_weights][]';
        
        const checkboxes = document.querySelectorAll(`input[name="${checkboxName}"]:checked`);
        
        const weights = Array.from(checkboxes).map(cb => parseInt(cb.value, 10));
        
        // Default to [400] if no weights selected
        if (weights.length === 0) {
            return [400];
        }
        
        return weights;
    }

    /**
     * Show preview panel for a font type
     *
     * @param {string} fontType - Font type ('body' or 'heading')
     */
    async showPreview(fontType) {
        console.log(`[GoogleFontsLoader] Showing preview for ${fontType}`);
        
        // Get selected font
        const selector = document.querySelector(`.woow-font-selector[data-font-type="${fontType}"]`);
        if (!selector) {
            console.error(`[GoogleFontsLoader] Font selector not found for ${fontType}`);
            return;
        }
        
        const fontName = selector.value;
        
        // Load font if not system
        if (fontName !== 'system') {
            await this.loadFont(fontName, fontType);
        }
        
        // Apply font to preview panel
        this.applyFont(fontName, fontType);
        
        // Show preview panel
        const previewPanel = document.querySelector(`.woow-font-preview-panel[data-font-type="${fontType}"]`);
        if (previewPanel) {
            previewPanel.style.display = 'block';
            this.previewActive[fontType] = true;
        }
    }

    /**
     * Hide preview panel for a font type
     *
     * @param {string} fontType - Font type ('body' or 'heading')
     */
    hidePreview(fontType) {
        console.log(`[GoogleFontsLoader] Hiding preview for ${fontType}`);
        
        const previewPanel = document.querySelector(`.woow-font-preview-panel[data-font-type="${fontType}"]`);
        if (previewPanel) {
            previewPanel.style.display = 'none';
            this.previewActive[fontType] = false;
        }
    }

    /**
     * Update preview when font or weights change
     *
     * @param {string} fontType - Font type ('body' or 'heading')
     * @param {string} fontName - Font name
     */
    async updatePreview(fontType, fontName) {
        console.log(`[GoogleFontsLoader] Updating preview: ${fontType} -> ${fontName}`);
        
        // Load font if not system
        if (fontName !== 'system') {
            await this.loadFont(fontName, fontType);
        }
        
        // Apply font to preview panel
        this.applyFont(fontName, fontType);
    }

    /**
     * Apply font to preview panel
     *
     * @param {string} fontName - Font name
     * @param {string} fontType - Font type ('body' or 'heading')
     */
    applyFont(fontName, fontType) {
        const previewPanel = document.querySelector(`.woow-font-preview-panel[data-font-type="${fontType}"]`);
        if (!previewPanel) {
            console.error(`[GoogleFontsLoader] Preview panel not found for ${fontType}`);
            return;
        }
        
        // Get preview samples container
        const samplesContainer = previewPanel.querySelector('.woow-font-preview-samples');
        if (!samplesContainer) {
            console.error(`[GoogleFontsLoader] Preview samples container not found`);
            return;
        }
        
        // Apply font-family
        if (fontName === 'system') {
            // Use system fonts
            samplesContainer.style.fontFamily = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
        } else {
            // Use Google Font with fallbacks
            const fallback = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
            
            // Quote font name if it contains spaces
            const quotedName = fontName.includes(' ') ? `"${fontName}"` : fontName;
            
            samplesContainer.style.fontFamily = `${quotedName}, ${fallback}`;
        }
        
        console.log(`[GoogleFontsLoader] Applied font to preview: ${fontName}`);
    }

    /**
     * Log error without disrupting UI
     *
     * @param {string} fontName - Font name that failed
     * @param {Error} error - Error object
     */
    logError(fontName, error) {
        // Log to console for debugging
        console.error(`[GoogleFontsLoader] Font loading failed: ${fontName}`, {
            message: error.message,
            stack: error.stack,
            timestamp: new Date().toISOString()
        });
        
        // Could send to error tracking service here
        // But don't show error to user - graceful degradation to system fonts
    }

    /**
     * Clear font cache
     * Useful for testing or when fonts need to be reloaded
     */
    clearCache() {
        console.log('[GoogleFontsLoader] Clearing font cache');
        this.loadedFonts.clear();
    }

    /**
     * Get cache statistics
     *
     * @returns {Object} Cache statistics
     */
    getCacheStats() {
        return {
            size: this.loadedFonts.size,
            fonts: Array.from(this.loadedFonts.keys())
        };
    }
}
