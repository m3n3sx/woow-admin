/**
 * TemplateSelector Component
 *
 * Handles template grid display, preview images, category filtering, and application.
 * Updated to use new data structure from templates-data.php.
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

export class TemplateSelector {
    /**
     * Constructor
     *
     * @param {WoowAdmin} woowAdmin - Main controller instance
     */
    constructor(woowAdmin) {
        this.woow = woowAdmin;
        this.container = null;
        this.filterContainer = null;
        this.templates = [];
        this.filteredTemplates = [];
        this.activeTemplateId = null;
        this.activeCategory = 'all';
        this.isApplying = false;
        this.init();
    }

    /**
     * Initialize template selector
     */
    init() {
        this.container = document.querySelector('.woow-template-grid');
        this.filterContainer = document.querySelector('.woow-template-filters');

        if (!this.container) {
            console.warn('[TemplateSelector] Template grid not found');
            return;
        }

        // Get templates data from window object
        // Ensure templates is always an array
        const templatesData = window.woowAdminData?.templates;
        
        if (Array.isArray(templatesData)) {
            this.templates = templatesData;
        } else if (templatesData && typeof templatesData === 'object') {
            // Convert object to array if needed
            this.templates = Object.values(templatesData);
        } else {
            this.templates = [];
        }
        
        this.filteredTemplates = [...this.templates];
        this.activeTemplateId = window.woowAdminData?.activeTemplate || null;

        // Initialize category filters if container exists
        if (this.filterContainer) {
            this.initializeFilters();
        }

        this.bindEvents();
        
        console.log('[TemplateSelector] Initialized with', this.templates.length, 'templates');
    }

    /**
     * Initialize category filters
     */
    initializeFilters() {
        if (!this.filterContainer) return;
        
        // Extract unique categories from templates
        const categories = ['all'];
        
        if (Array.isArray(this.templates) && this.templates.length > 0) {
            const uniqueCategories = new Set(
                this.templates
                    .map(t => t && t.category)
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
        
        console.log('[TemplateSelector] Initialized filters for categories:', categories);
    }

    /**
     * Format category name for display
     *
     * @param {string} category - Category slug
     * @returns {string} Formatted category name
     */
    formatCategoryName(category) {
        if (category === 'all') return 'All Templates';
        
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

        // Delegate click events to template cards
        this.container.addEventListener('click', (e) => {
            const card = e.target.closest('.woow-template-card');
            const applyBtn = e.target.closest('.woow-template-apply-btn');

            if (applyBtn && card) {
                // Prevent duplicate requests
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                
                // Check if already applying
                if (this.isApplying) {
                    console.log('[TemplateSelector] Already applying a template, ignoring duplicate request');
                    return;
                }
                
                const templateId = card.dataset.template;
                if (templateId) {
                    this.applyTemplate(templateId);
                }
                return; // Important: return early to prevent card click handler
            } else if (card) {
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
     * Filter templates by category
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
        
        // Filter templates
        if (category === 'all') {
            this.filteredTemplates = [...this.templates];
        } else {
            this.filteredTemplates = this.templates.filter(t => t.category === category);
        }
        
        // Re-render grid
        this.render(this.filteredTemplates);
        
        console.log('[TemplateSelector] Filtered to category:', category, '- showing', this.filteredTemplates.length, 'templates');
    }

    /**
     * Select template (visual selection only, doesn't apply)
     *
     * @param {string} templateId - Template ID to select
     */
    selectTemplate(templateId) {
        if (!templateId) return;

        // Update active indicator
        this.updateActiveIndicator(templateId);
        
        console.log('[TemplateSelector] Selected template:', templateId);
    }

    /**
     * Apply template to settings
     *
     * @param {string} templateId - Template ID to apply
     */
    async applyTemplate(templateId) {
        // Prevent duplicate calls
        if (!templateId || this.isApplying) {
            console.log('[TemplateSelector] Ignoring duplicate apply request:', templateId);
            return;
        }

        // Set flag immediately to prevent race conditions
        this.isApplying = true;
        
        // Find the template data
        const template = this.getTemplateById(templateId);
        
        if (!template) {
            console.error('[TemplateSelector] Template not found:', templateId);
            this.showNotification('Template not found', 'error');
            this.isApplying = false;
            return;
        }

        // Show loading state
        this.setApplyingState(templateId, true);
        
        console.log('[TemplateSelector] Applying template:', templateId);
        
        try {
            // Send AJAX request to apply template
            const response = await fetch(this.woow.ajaxUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'woow_apply_template',
                    nonce: this.woow.nonce,
                    template_id: templateId
                })
            });

            const data = await response.json();

            if (data.success) {
                // Update active template
                this.activeTemplateId = templateId;
                this.updateActiveIndicator(templateId);
                
                // Show success notification
                this.showNotification(`Template "${template.name}" applied successfully!`, 'success');
                
                console.log('[TemplateSelector] Template applied successfully:', templateId);
                
                // Reload page to show new styles
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                throw new Error(data.data?.message || 'Failed to apply template');
            }
        } catch (error) {
            console.error('[TemplateSelector] Error applying template:', error);
            this.showNotification(error.message || 'Failed to apply template', 'error');
            this.setApplyingState(templateId, false);
            this.isApplying = false; // Reset flag on error
        }
        // Note: Don't reset isApplying on success since we're reloading the page
    }

    /**
     * Set applying state for a template card
     *
     * @param {string} templateId - Template ID
     * @param {boolean} isApplying - Whether template is being applied
     */
    setApplyingState(templateId, isApplying) {
        const card = this.container.querySelector(`[data-template="${templateId}"]`);
        
        if (card) {
            const applyBtn = card.querySelector('.woow-template-apply-btn');
            
            if (applyBtn) {
                if (isApplying) {
                    applyBtn.disabled = true;
                    applyBtn.textContent = 'Applying...';
                    card.classList.add('woow-template-applying');
                } else {
                    applyBtn.disabled = false;
                    applyBtn.textContent = 'Apply';
                    card.classList.remove('woow-template-applying');
                }
            }
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

        this.container.innerHTML = '';

        if (templates.length === 0) {
            const emptyMessage = document.createElement('div');
            emptyMessage.className = 'woow-template-empty';
            emptyMessage.textContent = 'No templates found in this category.';
            this.container.appendChild(emptyMessage);
            return;
        }

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
        card.dataset.category = template.category || '';
        card.setAttribute('role', 'button');
        card.setAttribute('tabindex', '0');
        card.setAttribute('aria-label', `${template.name} template`);

        // Create preview image if available
        if (template.preview_image) {
            const previewDiv = document.createElement('div');
            previewDiv.className = 'woow-template-preview';
            
            const previewImg = document.createElement('img');
            previewImg.src = this.getPreviewImageUrl(template.preview_image);
            previewImg.alt = `${template.name} preview`;
            previewImg.loading = 'lazy';
            previewImg.onerror = () => {
                // Fallback to placeholder if image fails to load
                previewDiv.innerHTML = '';
                const placeholderDiv = this.createPlaceholder(template);
                previewDiv.appendChild(placeholderDiv);
            };
            
            previewDiv.appendChild(previewImg);
            card.appendChild(previewDiv);
        } else {
            // Fallback to placeholder
            const placeholderDiv = this.createPlaceholder(template);
            card.appendChild(placeholderDiv);
        }

        // Create info section
        const infoDiv = document.createElement('div');
        infoDiv.className = 'woow-template-info';

        // Create category badge
        if (template.category) {
            const categoryBadge = document.createElement('span');
            categoryBadge.className = 'woow-template-category';
            categoryBadge.textContent = this.formatCategoryName(template.category);
            infoDiv.appendChild(categoryBadge);
        }

        const nameEl = document.createElement('h3');
        nameEl.className = 'woow-template-name';
        nameEl.textContent = template.name;

        const descEl = document.createElement('p');
        descEl.className = 'woow-template-description';
        descEl.textContent = template.description || '';

        infoDiv.appendChild(nameEl);
        infoDiv.appendChild(descEl);

        // Create characteristics badges if available
        if (template.characteristics && typeof template.characteristics === 'object') {
            const characteristicsDiv = document.createElement('div');
            characteristicsDiv.className = 'woow-template-characteristics';
            
            // Show key characteristics
            const keyChars = ['glassmorphism', 'gradients', 'animations'];
            keyChars.forEach(key => {
                if (template.characteristics[key] !== undefined) {
                    const badge = document.createElement('span');
                    badge.className = 'woow-template-char-badge';
                    
                    const value = template.characteristics[key];
                    if (typeof value === 'boolean') {
                        if (value) {
                            badge.textContent = key.charAt(0).toUpperCase() + key.slice(1);
                            characteristicsDiv.appendChild(badge);
                        }
                    } else {
                        badge.textContent = `${key}: ${value}`;
                        characteristicsDiv.appendChild(badge);
                    }
                }
            });
            
            if (characteristicsDiv.children.length > 0) {
                infoDiv.appendChild(characteristicsDiv);
            }
        }

        // Create apply button
        const applyBtn = document.createElement('button');
        applyBtn.type = 'button';
        applyBtn.className = 'woow-template-apply-btn';
        applyBtn.textContent = 'Apply';
        applyBtn.setAttribute('aria-label', `Apply ${template.name} template`);
        
        infoDiv.appendChild(applyBtn);

        // Assemble card
        card.appendChild(infoDiv);

        return card;
    }

    /**
     * Create placeholder element for templates without preview images
     *
     * @param {Object} template - Template object
     * @returns {HTMLElement} Placeholder container
     */
    createPlaceholder(template) {
        const placeholderDiv = document.createElement('div');
        placeholderDiv.className = 'woow-template-placeholder';
        
        // Add template name as placeholder text
        const placeholderText = document.createElement('div');
        placeholderText.className = 'woow-template-placeholder-text';
        placeholderText.textContent = template.name;
        
        placeholderDiv.appendChild(placeholderText);
        
        return placeholderDiv;
    }

    /**
     * Get preview image URL
     *
     * @param {string} filename - Preview image filename
     * @returns {string} Full URL to preview image
     */
    getPreviewImageUrl(filename) {
        const baseUrl = window.woowAdminData?.pluginUrl || '';
        return `${baseUrl}/assets/images/previews/templates/${filename}`;
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
            console.log(`[TemplateSelector] ${type.toUpperCase()}: ${message}`);
        }
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

    /**
     * Get all templates
     *
     * @returns {Array} Array of all templates
     */
    getAllTemplates() {
        return this.templates;
    }

    /**
     * Get filtered templates
     *
     * @returns {Array} Array of filtered templates
     */
    getFilteredTemplates() {
        return this.filteredTemplates;
    }
}

export default TemplateSelector;
