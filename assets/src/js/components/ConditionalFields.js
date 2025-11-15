/**
 * Conditional Fields Component
 * 
 * Handles showing/hiding fields based on other field values
 * 
 * @package WoowAdmin
 * @since 1.0.0
 */

export class ConditionalFields {
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
     * Initialize conditional fields
     */
    init() {
        // Use a small delay to ensure DOM is fully ready
        setTimeout(() => {
            // Handle select/checkbox conditionals (data-show-when)
            this.initShowWhenFields();
            
            // Handle radio button conditionals (data-condition-trigger)
            this.initConditionTriggers();
            
            console.log('[ConditionalFields] Initialized');
        }, 100);
    }

    /**
     * Initialize fields with data-show-when attribute
     * Used for select dropdowns and checkboxes
     */
    initShowWhenFields() {
        const conditionalFields = document.querySelectorAll('.woow-conditional[data-show-when]');
        
        console.log(`[ConditionalFields] Found ${conditionalFields.length} conditional fields`);
        
        conditionalFields.forEach(field => {
            const condition = field.dataset.showWhen; // e.g., "background_type=solid"
            const [fieldName, expectedValue] = condition.split('=');
            
            console.log(`[ConditionalFields] Processing: ${fieldName} = ${expectedValue}`);
            
            // Find the controlling field
            let controlField = this.findControlField(fieldName);
            
            if (!controlField) {
                // Try with section prefix if not found
                controlField = this.findControlField(`admin_menu[${fieldName}]`);
            }
            
            if (controlField) {
                console.log(`[ConditionalFields] Found control field for ${fieldName}:`, controlField.name, '=', controlField.value);
                
                // Initial state
                this.updateShowWhenField(field, controlField, expectedValue);
                
                // Listen for changes
                controlField.addEventListener('change', () => {
                    this.updateShowWhenField(field, controlField, expectedValue);
                });
            } else {
                console.warn(`[ConditionalFields] Control field not found for: ${fieldName}`);
            }
        });
    }

    /**
     * Initialize fields with condition triggers (radio buttons)
     * Used for radio button groups
     */
    initConditionTriggers() {
        const triggers = document.querySelectorAll('.woow-condition-trigger[data-target]');
        
        triggers.forEach(trigger => {
            const targetName = trigger.dataset.target;
            
            // Initial state
            this.updateConditionalFields(targetName, trigger.value, trigger.checked);
            
            // Listen for changes
            trigger.addEventListener('change', (e) => {
                if (e.target.checked) {
                    this.updateConditionalFields(targetName, e.target.value, true);
                }
            });
        });
    }

    /**
     * Find control field by name
     * 
     * @param {string} fieldName - Field name to search for
     * @returns {HTMLElement|null} - Found field element
     */
    findControlField(fieldName) {
        // Get current active tab to prioritize fields from that section
        const activeTab = document.querySelector('.woow-tab-pane:not([style*="display: none"])');
        const searchContext = activeTab || document;
        
        // Try different name patterns - order matters!
        const patterns = [
            `[name*="[${fieldName}]"]`,  // admin_menu[background_type], backgrounds[type]
            `[name$="[${fieldName}]"]`,  // ends with [fieldName]
            `[name="${fieldName}"]`,     // exact match: background_type
        ];
        
        console.log(`[ConditionalFields] Searching for field: ${fieldName}`);
        
        // First try in active tab
        if (activeTab) {
            for (const pattern of patterns) {
                try {
                    const field = activeTab.querySelector(pattern);
                    if (field) {
                        console.log(`[ConditionalFields] Found in active tab with pattern: ${pattern}`, field.name);
                        return field;
                    }
                } catch (e) {
                    console.warn(`[ConditionalFields] Invalid selector pattern: ${pattern}`, e.message);
                }
            }
        }
        
        // Then try in entire document
        for (const pattern of patterns) {
            try {
                const field = document.querySelector(pattern);
                if (field) {
                    console.log(`[ConditionalFields] Found in document with pattern: ${pattern}`, field.name);
                    return field;
                }
            } catch (e) {
                console.warn(`[ConditionalFields] Invalid selector pattern: ${pattern}`, e.message);
            }
        }
        
        // Try ID selector as last resort (with validation)
        if (fieldName && !fieldName.includes('[') && !fieldName.includes(']')) {
            try {
                const field = document.getElementById(fieldName);
                if (field) {
                    console.log(`[ConditionalFields] Found by ID: ${fieldName}`, field);
                    return field;
                }
            } catch (e) {
                console.warn(`[ConditionalFields] Error searching by ID: ${fieldName}`, e.message);
            }
        }
        
        console.warn(`[ConditionalFields] Field not found: ${fieldName}`);
        return null;
    }

    /**
     * Update visibility of show-when field
     * 
     * @param {HTMLElement} field - Field to show/hide
     * @param {HTMLElement} controlField - Field that controls visibility
     * @param {string} expectedValue - Expected value to show field
     */
    updateShowWhenField(field, controlField, expectedValue) {
        let currentValue;
        
        if (controlField.type === 'checkbox') {
            // For checkboxes, value is "1" when checked, "0" when unchecked
            currentValue = controlField.checked ? '1' : '0';
        } else {
            // For select and other inputs
            currentValue = controlField.value;
        }
        
        if (currentValue === expectedValue) {
            field.style.display = '';
            field.classList.remove('woow-hidden');
        } else {
            field.style.display = 'none';
            field.classList.add('woow-hidden');
        }
    }

    /**
     * Update conditional fields based on trigger
     * 
     * @param {string} targetName - Name of the condition target
     * @param {string} value - Current value
     * @param {boolean} isChecked - Whether trigger is checked
     */
    updateConditionalFields(targetName, value, isChecked) {
        const conditionalFields = document.querySelectorAll(
            `.woow-conditional-field[data-condition="${targetName}"]`
        );
        
        conditionalFields.forEach(field => {
            const expectedValue = field.dataset.value;
            
            if (isChecked && value === expectedValue) {
                field.style.display = '';
                field.classList.remove('woow-hidden');
            } else if (isChecked && value !== expectedValue) {
                field.style.display = 'none';
                field.classList.add('woow-hidden');
            }
        });
    }

    /**
     * Refresh all conditional fields
     * Useful after dynamic content changes
     */
    refresh() {
        this.initShowWhenFields();
        this.initConditionTriggers();
    }
}
