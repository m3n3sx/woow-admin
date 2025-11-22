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
            
            // Find the controlling field (pass the conditional field for context)
            const controlField = this.findControlField(fieldName, field);
            
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
     * Initialize fields with condition triggers (radio buttons and checkboxes)
     * Used for radio button groups and checkbox toggles
     */
    initConditionTriggers() {
        const triggers = document.querySelectorAll('.woow-condition-trigger[data-target]');
        
        triggers.forEach(trigger => {
            const targetName = trigger.dataset.target;
            
            // Determine the value to use for conditional matching
            const getValue = () => {
                if (trigger.type === 'checkbox') {
                    // For checkboxes, use "1" when checked, "0" when unchecked
                    return trigger.checked ? '1' : '0';
                } else {
                    // For radio buttons, use the actual value
                    return trigger.value;
                }
            };
            
            // Initial state
            const initialValue = getValue();
            this.updateConditionalFields(targetName, initialValue, trigger.checked || trigger.type !== 'checkbox');
            
            // Listen for changes
            trigger.addEventListener('change', (e) => {
                const currentValue = getValue();
                
                if (trigger.type === 'checkbox') {
                    // For checkboxes, always update (checked or unchecked)
                    this.updateConditionalFields(targetName, currentValue, true);
                } else if (e.target.checked) {
                    // For radio buttons, only update when checked
                    this.updateConditionalFields(targetName, currentValue, true);
                }
            });
        });
    }

    /**
     * Find control field by name
     * 
     * @param {string} fieldName - Field name to search for
     * @param {HTMLElement} conditionalField - The conditional field element (to determine context)
     * @returns {HTMLElement|null} - Found field element
     */
    findControlField(fieldName, conditionalField = null) {
        console.log(`[ConditionalFields] Searching for field: ${fieldName}`);
        
        // Determine search context from the conditional field's parent tab
        let searchContext = document;
        if (conditionalField) {
            const parentTab = conditionalField.closest('.woow-tab-pane');
            if (parentTab) {
                searchContext = parentTab;
                console.log(`[ConditionalFields] Searching in parent tab:`, parentTab.id);
            }
        }
        
        // Try different name patterns - order matters!
        const patterns = [
            `[name$="[${fieldName}]"]`,  // ends with [fieldName] - most specific
            `[name*="[${fieldName}]"]`,  // contains [fieldName]
            `[name="${fieldName}"]`,     // exact match: background_type
        ];
        
        // Search in context (tab or document)
        for (const pattern of patterns) {
            try {
                const field = searchContext.querySelector(pattern);
                if (field) {
                    console.log(`[ConditionalFields] Found with pattern: ${pattern}`, field.name);
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
        
        const shouldShow = currentValue === expectedValue;
        const wasHidden = field.style.display === 'none' || field.classList.contains('woow-hidden');
        
        if (shouldShow) {
            field.style.display = '';
            field.classList.remove('woow-hidden');
            
            // If field was just shown, trigger input event on all sliders to update their display
            if (wasHidden) {
                const sliders = field.querySelectorAll('input[type="range"].woow-slider');
                sliders.forEach(slider => {
                    // Trigger input event to update display
                    slider.dispatchEvent(new Event('input', { bubbles: true }));
                });
            }
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
