/**
 * PaletteSelector Component
 *
 * Handles palette grid display and selection.
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
        this.palettes = [];
        this.activePaletteId = null;
        this.init();
    }

    /**
     * Initialize palette selector
     */
    init() {
        this.container = document.querySelector('.woow-palette-grid');

        if (!this.container) {
            console.warn('[PaletteSelector] Palette grid not found');
            return;
        }

        // Get palettes data from window object
        this.palettes = window.woowAdminData?.palettes || [];
        this.activePaletteId = window.woowAdminData?.activePalette || null;

        this.bindEvents();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        if (!this.container) return;

        // Delegate click events to palette cards
        this.container.addEventListener('click', (e) => {
            const card = e.target.closest('.woow-palette-card');

            if (card) {
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
    }

    /**
     * Select and apply palette
     *
     * @param {string} paletteId - Palette ID to select
     */
    async selectPalette(paletteId) {
        if (!paletteId) return;

        // Update active indicator immediately for better UX
        this.updateActiveIndicator(paletteId);

        // Apply palette via main controller
        const success = await this.woow.applyPalette(paletteId);

        if (success) {
            this.activePaletteId = paletteId;
        } else {
            // Revert active indicator if failed
            this.updateActiveIndicator(this.activePaletteId);
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

        this.palettes = palettes;
        this.container.innerHTML = '';

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
        card.setAttribute('role', 'button');
        card.setAttribute('tabindex', '0');
        card.setAttribute('aria-label', `Apply ${palette.name} palette`);

        // Create color swatches
        const colorsDiv = document.createElement('div');
        colorsDiv.className = 'woow-palette-colors';

        const colorKeys = ['primary', 'secondary', 'background', 'card', 'foreground'];
        colorKeys.forEach(key => {
            if (palette.colors && palette.colors[key]) {
                const swatch = document.createElement('div');
                swatch.className = 'woow-palette-color';
                swatch.style.backgroundColor = palette.colors[key];
                swatch.setAttribute('title', `${key}: ${palette.colors[key]}`);
                colorsDiv.appendChild(swatch);
            }
        });

        // Create info section
        const infoDiv = document.createElement('div');
        infoDiv.className = 'woow-palette-info';

        const nameEl = document.createElement('h3');
        nameEl.className = 'woow-palette-name';
        nameEl.textContent = palette.name;

        const descEl = document.createElement('p');
        descEl.className = 'woow-palette-description';
        descEl.textContent = palette.description || '';

        infoDiv.appendChild(nameEl);
        infoDiv.appendChild(descEl);

        // Assemble card
        card.appendChild(colorsDiv);
        card.appendChild(infoDiv);

        return card;
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
}

export default PaletteSelector;
