/**
 * PaletteSelector Component
 *
 * Handles palette grid display, preview images, category filtering, and application.
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

export class PaletteSelector {
    /**
     * Constructor
     *
     * @param {WoowAdmin} woowAdmin - Main controller instance
     */
    constructor(woowAdmin) {
        this.woow = woowAdmin;
        this.container = null;
        this.filterContainer = null;
        this.palettes = [];
        this.filteredPalettes = [];
        this.activePaletteId = null;
        this.activeCategory = 'all';
        this.isApplying = false;
        this.init();
    }

    /**
     * Initialize palette selector
     */
    init() {
        this.container = document.querySelector('.woow-palette-grid');
        this.filterContainer = document.querySelector('.woow-palette-filters');

        if (!this.container) {
            console.warn('[PaletteSelector] Palette grid not found');
            return;
        }

        // Get palettes data from window object
        // Ensure palettes is always an array
        const palettesData = window.woowAdminData?.palettes;
        
        if (Array.isArray(palettesData)) {
            this.palettes = palettesData;
        } else if (palettesData && typeof palettesData === 'object') {
            // Convert object to array if needed
            this.palettes = Object.values(palettesData);
        } else {
            this.palettes = [];
        }
        
        this.filteredPalettes = [...this.palettes];
        this.activePaletteId = window.woowAdminData?.activePalette || null;

        // Initialize category filters if container exists
        if (this.filterContainer) {
            this.initializeFilters();
        }

        this.bindEvents();
        
        // Render palettes
        this.render(this.filteredPalettes);
        
        console.log('[PaletteSelector] Initialized with', this.palettes.length, 'palettes');
    }

    /**
     * Initialize category filters
     */
    initializeFilters() {
        if (!this.filterContainer) return;
        
        // Extract unique categories from palettes
        const categories = ['all'];
        
        if (Array.isArray(this.palettes) && this.palettes.length > 0) {
            const uniqueCategories = new Set(
                this.palettes
                    .map(p => p && p.category)
                    .filter(Boolean)
            );
            categories.push(...uniqueCategories);
        }
        
        // Create filter buttons
        this.filterContainer.innerHTML = '';
        
        categories.forEach(category => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'woow-filter-btn';
            button.dataset.category = category;
            button.textContent = this.formatCategoryName(category);
            
            if (category === 'all') {
                button.classList.add('active');
            }
            
            this.filterContainer.appendChild(button);
        });
        
        console.log('[PaletteSelector] Initialized filters for categories:', categories);
    }

    /**
     * Format category name for display
     *
     * @param {string} category - Category slug
     * @returns {string} Formatted category name
     */
    formatCategoryName(category) {
        if (category === 'all') return 'All Palettes';
        
        return category
            .split('_')
            .map(word => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ');
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        if (!this.container) return;

        // Delegate click events to palette cards
        this.container.addEventListener('click', (e) => {
            const card = e.target.closest('.woow-palette-card');
            const applyBtn = e.target.closest('.woow-palette-apply-btn');

            if (applyBtn && card) {
                // Prevent duplicate requests
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                
                // Check if already applying
                if (this.isApplying) {
                    console.log('[PaletteSelector] Already applying a palette, ignoring duplicate request');
                    return;
                }
                
                const paletteId = card.dataset.palette;
                if (paletteId) {
                    this.applyPalette(paletteId);
                }
                return; // Important: return early to prevent card click handler
            } else if (card) {
                e.preventDefault();
                const paletteId = card.dataset.palette;
                if (paletteId) {
                    this.selectPalette(paletteId);
                }
            }
        });

        // Handle keyboard navigation
        this.container.addEventListener('keydown', (e) => {
            const card = e.target.closest('.woow-palette-card');

            if (card && (e.key === 'Enter' || e.key === ' ')) {
                e.preventDefault();
                const paletteId = card.dataset.palette;

                if (paletteId) {
                    this.selectPalette(paletteId);
                }
            }
        });

        // Category filter buttons
        if (this.filterContainer) {
            this.filterContainer.addEventListener('click', (e) => {
                const filterBtn = e.target.closest('.woow-filter-btn');
                
                if (filterBtn) {
                    e.preventDefault();
                    const category = filterBtn.dataset.category;
                    this.filterByCategory(category);
                }
            });
        }
    }

    /**
     * Filter palettes by category
     *
     * @param {string} category - Category to filter by ('all' for no filter)
     */
    filterByCategory(category) {
        this.activeCategory = category;
        
        // Update active filter button
        if (this.filterContainer) {
            const buttons = this.filterContainer.querySelectorAll('.woow-filter-btn');
            buttons.forEach(btn => {
                if (btn.dataset.category === category) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }
        
        // Filter palettes
        if (category === 'all') {
            this.filteredPalettes = [...this.palettes];
        } else {
            this.filteredPalettes = this.palettes.filter(p => p.category === category);
        }
        
        // Re-render grid
        this.render(this.filteredPalettes);
        
        console.log('[PaletteSelector] Filtered to category:', category, '- showing', this.filteredPalettes.length, 'palettes');
    }

    /**
     * Select palette (visual selection only, doesn't apply)
     *
     * @param {string} paletteId - Palette ID to select
     */
    selectPalette(paletteId) {
        if (!paletteId) return;

        // Update active indicator
        this.updateActiveIndicator(paletteId);
        
        console.log('[PaletteSelector] Selected palette:', paletteId);
    }

    /**
     * Apply palette to settings
     *
     * @param {string} paletteId - Palette ID to apply
     */
    async applyPalette(paletteId) {
        // Prevent duplicate calls
        if (!paletteId || this.isApplying) {
            console.log('[PaletteSelector] Ignoring duplicate apply request:', paletteId);
            return;
        }

        // Set flag immediately to prevent race conditions
        this.isApplying = true;
        
        // Find the palette data
        const palette = this.getPaletteById(paletteId);
        
        if (!palette) {
            console.error('[PaletteSelector] Palette not found:', paletteId);
            this.showNotification('Palette not found', 'error');
            this.isApplying = false;
            return;
        }

        // Show loading state
        this.setApplyingState(paletteId, true);
        
        console.log('[PaletteSelector] Applying palette:', paletteId);
        
        try {
            // Send AJAX request to apply palette
            const response = await fetch(this.woow.ajaxUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'woow_apply_palette',
                    nonce: this.woow.nonce,
                    palette_id: paletteId
                })
            });

            const data = await response.json();

            if (data.success) {
                // Update active palette
                this.activePaletteId = paletteId;
                this.updateActiveIndicator(paletteId);
                
                // Show success notification
                this.showNotification(`Palette "${palette.name}" applied successfully!`, 'success');
                
                console.log('[PaletteSelector] Palette applied successfully:', paletteId);
                
                // Reload page to show new styles
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                throw new Error(data.data?.message || 'Failed to apply palette');
            }
        } catch (error) {
            console.error('[PaletteSelector] Error applying palette:', error);
            this.showNotification(error.message || 'Failed to apply palette', 'error');
            this.setApplyingState(paletteId, false);
            this.isApplying = false; // Reset flag on error
        }
        // Note: Don't reset isApplying on success since we're reloading the page
    }

    /**
     * Set applying state for a palette card
     *
     * @param {string} paletteId - Palette ID
     * @param {boolean} isApplying - Whether palette is being applied
     */
    setApplyingState(paletteId, isApplying) {
        const card = this.container.querySelector(`[data-palette="${paletteId}"]`);
        
        if (card) {
            const applyBtn = card.querySelector('.woow-palette-apply-btn');
            
            if (applyBtn) {
                if (isApplying) {
                    applyBtn.disabled = true;
                    applyBtn.textContent = 'Applying...';
                    card.classList.add('woow-palette-applying');
                } else {
                    applyBtn.disabled = false;
                    applyBtn.textContent = 'Apply';
                    card.classList.remove('woow-palette-applying');
                }
            }
        }
    }

    /**
     * Update active palette indicator
     *
     * @param {string} paletteId - Palette ID to mark as active
     */
    updateActiveIndicator(paletteId) {
        if (!this.container) return;

        const cards = this.container.querySelectorAll('.woow-palette-card');

        cards.forEach(card => {
            if (card.dataset.palette === paletteId) {
                card.classList.add('woow-palette-active');
                card.setAttribute('aria-selected', 'true');
            } else {
                card.classList.remove('woow-palette-active');
                card.setAttribute('aria-selected', 'false');
            }
        });
    }

    /**
     * Render palette grid from data
     *
     * @param {Array} palettes - Array of palette objects
     */
    render(palettes) {
        if (!this.container) return;

        this.container.innerHTML = '';

        if (palettes.length === 0) {
            const emptyMessage = document.createElement('div');
            emptyMessage.className = 'woow-palette-empty';
            emptyMessage.textContent = 'No palettes found in this category.';
            this.container.appendChild(emptyMessage);
            return;
        }

        palettes.forEach(palette => {
            const card = this.createPaletteCard(palette);
            this.container.appendChild(card);
        });

        // Update active indicator
        if (this.activePaletteId) {
            this.updateActiveIndicator(this.activePaletteId);
        }
    }

    /**
     * Create palette card element
     *
     * @param {Object} palette - Palette object
     * @returns {HTMLElement} Palette card element
     */
    createPaletteCard(palette) {
        const card = document.createElement('div');
        card.className = 'woow-palette-card';
        card.dataset.palette = palette.id;
        card.dataset.category = palette.category || '';
        card.setAttribute('role', 'button');
        card.setAttribute('tabindex', '0');
        card.setAttribute('aria-label', `${palette.name} palette`);

        // Create preview image if available
        if (palette.preview_image) {
            const previewDiv = document.createElement('div');
            previewDiv.className = 'woow-palette-preview';
            
            const previewImg = document.createElement('img');
            previewImg.src = this.getPreviewImageUrl(palette.preview_image);
            previewImg.alt = `${palette.name} preview`;
            previewImg.loading = 'lazy';
            previewImg.onerror = () => {
                // Fallback to color swatches if image fails to load
                previewDiv.innerHTML = '';
                const colorsDiv = this.createColorSwatches(palette);
                previewDiv.appendChild(colorsDiv);
            };
            
            previewDiv.appendChild(previewImg);
            card.appendChild(previewDiv);
        } else {
            // Fallback to color swatches
            const colorsDiv = this.createColorSwatches(palette);
            card.appendChild(colorsDiv);
        }

        // Create info section
        const infoDiv = document.createElement('div');
        infoDiv.className = 'woow-palette-info';

        const nameEl = document.createElement('h3');
        nameEl.className = 'woow-palette-name';
        nameEl.textContent = palette.name;

        const descEl = document.createElement('p');
        descEl.className = 'woow-palette-description';
        descEl.textContent = palette.description || '';

        // Create category badge
        if (palette.category) {
            const categoryBadge = document.createElement('span');
            categoryBadge.className = 'woow-palette-category';
            categoryBadge.textContent = this.formatCategoryName(palette.category);
            infoDiv.appendChild(categoryBadge);
        }

        infoDiv.appendChild(nameEl);
        infoDiv.appendChild(descEl);

        // Create apply button
        const applyBtn = document.createElement('button');
        applyBtn.type = 'button';
        applyBtn.className = 'woow-palette-apply-btn';
        applyBtn.textContent = 'Apply';
        applyBtn.setAttribute('aria-label', `Apply ${palette.name} palette`);
        
        infoDiv.appendChild(applyBtn);

        // Assemble card
        card.appendChild(infoDiv);

        return card;
    }

    /**
     * Create color swatches element
     *
     * @param {Object} palette - Palette object
     * @returns {HTMLElement} Color swatches container
     */
    createColorSwatches(palette) {
        const colorsDiv = document.createElement('div');
        colorsDiv.className = 'woow-palette-colors';

        const colorKeys = ['primary', 'secondary', 'accent', 'background', 'text'];
        colorKeys.forEach(key => {
            if (palette.colors && palette.colors[key]) {
                const swatch = document.createElement('div');
                swatch.className = 'woow-palette-color';
                swatch.style.backgroundColor = palette.colors[key];
                swatch.setAttribute('title', `${key}: ${palette.colors[key]}`);
                colorsDiv.appendChild(swatch);
            }
        });

        return colorsDiv;
    }

    /**
     * Get preview image URL
     *
     * @param {string} filename - Preview image filename
     * @returns {string} Full URL to preview image
     */
    getPreviewImageUrl(filename) {
        const baseUrl = window.woowAdminData?.pluginUrl || '';
        return `${baseUrl}/assets/images/previews/palettes/${filename}`;
    }

    /**
     * Show notification message
     *
     * @param {string} message - Notification message
     * @param {string} type - Notification type (success, error, info)
     */
    showNotification(message, type = 'info') {
        // Use main controller's notification system if available
        if (this.woow && typeof this.woow.showNotification === 'function') {
            this.woow.showNotification(message, type);
        } else {
            // Fallback to console
            console.log(`[PaletteSelector] ${type.toUpperCase()}: ${message}`);
        }
    }

    /**
     * Get active palette ID
     *
     * @returns {string|null} Active palette ID
     */
    getActivePaletteId() {
        return this.activePaletteId;
    }

    /**
     * Get palette by ID
     *
     * @param {string} paletteId - Palette ID
     * @returns {Object|null} Palette object or null
     */
    getPaletteById(paletteId) {
        return this.palettes.find(p => p.id === paletteId) || null;
    }

    /**
     * Get all palettes
     *
     * @returns {Array} Array of all palettes
     */
    getAllPalettes() {
        return this.palettes;
    }

    /**
     * Get filtered palettes
     *
     * @returns {Array} Array of filtered palettes
     */
    getFilteredPalettes() {
        return this.filteredPalettes;
    }
}

export default PaletteSelector;
