/**
 * TemplateGallery Component
 *
 * Handles template grid display and selection.
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

export class TemplateGallery {
    /**
     * Constructor
     *
     * @param {WoowAdmin} woowAdmin - Main controller instance
     */
    constructor(woowAdmin) {
        this.woow = woowAdmin;
        this.container = null;
        this.templates = [];
        this.activeTemplateId = null;
        this.init();
    }

    /**
     * Initialize template gallery
     */
    init() {
        this.container = document.querySelector('.woow-template-grid');

        if (!this.container) {
            console.warn('[TemplateGallery] Template grid not found');
            return;
        }

        // Get templates data from window object
        this.templates = window.woowAdminData?.templates || [];
        this.activeTemplateId = window.woowAdminData?.activeTemplate || null;

        this.bindEvents();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        if (!this.container) return;

        // Delegate click events to template cards
        this.container.addEventListener('click', (e) => {
            const card = e.target.closest('.woow-template-card');

            if (card) {
                e.preventDefault();
                const templateId = card.dataset.template;

                if (templateId) {
                    this.selectTemplate(templateId);
                }
            }
        });

        // Handle keyboard navigation
        this.container.addEventListener('keydown', (e) => {
            const card = e.target.closest('.woow-template-card');

            if (card && (e.key === 'Enter' || e.key === ' ')) {
                e.preventDefault();
                const templateId = card.dataset.template;

                if (templateId) {
                    this.selectTemplate(templateId);
                }
            }
        });
    }

    /**
     * Select and apply template
     *
     * @param {string} templateId - Template ID to select
     */
    async selectTemplate(templateId) {
        if (!templateId) return;

        // Update active indicator immediately for better UX
        this.updateActiveIndicator(templateId);

        // Apply template via main controller
        const success = await this.woow.applyTemplate(templateId);

        if (success) {
            this.activeTemplateId = templateId;
        } else {
            // Revert active indicator if failed
            this.updateActiveIndicator(this.activeTemplateId);
        }
    }

    /**
     * Update active template indicator
     *
     * @param {string} templateId - Template ID to mark as active
     */
    updateActiveIndicator(templateId) {
        if (!this.container) return;

        const cards = this.container.querySelectorAll('.woow-template-card');

        cards.forEach(card => {
            if (card.dataset.template === templateId) {
                card.classList.add('woow-template-active');
                card.setAttribute('aria-selected', 'true');
            } else {
                card.classList.remove('woow-template-active');
                card.setAttribute('aria-selected', 'false');
            }
        });
    }

    /**
     * Render template grid from data
     *
     * @param {Array} templates - Array of template objects
     */
    render(templates) {
        if (!this.container) return;

        this.templates = templates;
        this.container.innerHTML = '';

        templates.forEach(template => {
            const card = this.createTemplateCard(template);
            this.container.appendChild(card);
        });

        // Update active indicator
        if (this.activeTemplateId) {
            this.updateActiveIndicator(this.activeTemplateId);
        }
    }

    /**
     * Create template card element
     *
     * @param {Object} template - Template object
     * @returns {HTMLElement} Template card element
     */
    createTemplateCard(template) {
        const card = document.createElement('div');
        card.className = 'woow-template-card';
        card.dataset.template = template.id;
        card.setAttribute('role', 'button');
        card.setAttribute('tabindex', '0');
        card.setAttribute('aria-label', `Apply ${template.name} template`);

        // Create thumbnail
        if (template.thumbnail) {
            const thumbnailDiv = document.createElement('div');
            thumbnailDiv.className = 'woow-template-thumbnail';

            const img = document.createElement('img');
            img.src = template.thumbnail;
            img.alt = template.name;
            img.loading = 'lazy';

            thumbnailDiv.appendChild(img);
            card.appendChild(thumbnailDiv);
        }

        // Create info section
        const infoDiv = document.createElement('div');
        infoDiv.className = 'woow-template-info';

        const nameEl = document.createElement('h3');
        nameEl.className = 'woow-template-name';
        nameEl.textContent = template.name;

        const descEl = document.createElement('p');
        descEl.className = 'woow-template-description';
        descEl.textContent = template.description || '';

        infoDiv.appendChild(nameEl);
        infoDiv.appendChild(descEl);

        // Create features list if available
        if (template.features && Array.isArray(template.features) && template.features.length > 0) {
            const featuresDiv = document.createElement('div');
            featuresDiv.className = 'woow-template-features';

            const featuresList = document.createElement('ul');
            template.features.slice(0, 3).forEach(feature => {
                const li = document.createElement('li');
                li.textContent = feature;
                featuresList.appendChild(li);
            });

            featuresDiv.appendChild(featuresList);
            infoDiv.appendChild(featuresDiv);
        }

        card.appendChild(infoDiv);

        return card;
    }

    /**
     * Get active template ID
     *
     * @returns {string|null} Active template ID
     */
    getActiveTemplateId() {
        return this.activeTemplateId;
    }

    /**
     * Get template by ID
     *
     * @param {string} templateId - Template ID
     * @returns {Object|null} Template object or null
     */
    getTemplateById(templateId) {
        return this.templates.find(t => t.id === templateId) || null;
    }
}

export default TemplateGallery;
