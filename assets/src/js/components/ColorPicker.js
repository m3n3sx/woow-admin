/**
 * ColorPicker Component
 *
 * Handles color picker interactions, validation, and synchronization.
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

export class ColorPicker {
    /**
     * Constructor
     *
     * @param {WoowAdmin} woowAdmin - Main controller instance
     */
    constructor(woowAdmin) {
        this.woow = woowAdmin;
        this.init();
    }

    /**
     * Initialize color pickers
     */
    init() {
        const colorGroups = document.querySelectorAll('.woow-color-picker-group');

        colorGroups.forEach(group => {
            const colorInput = group.querySelector('.woow-color-input');
            const textInput = group.querySelector('.woow-color-text');
            const resetButton = group.querySelector('.woow-color-reset');

            if (colorInput && textInput) {
                this.bindColorInput(colorInput, textInput);
                this.bindTextInput(textInput, colorInput);
            }

            if (resetButton) {
                this.bindResetButton(resetButton, colorInput, textInput);
            }
        });
    }

    /**
     * Bind color input events
     *
     * @param {HTMLInputElement} colorInput - Color input element
     * @param {HTMLInputElement} textInput - Text input element
     */
    bindColorInput(colorInput, textInput) {
        colorInput.addEventListener('input', (e) => {
            const hexColor = e.target.value;
            // Check if text input already has rgba value - preserve it
            const currentValue = textInput.value.trim();
            if (currentValue.startsWith('rgba(')) {
                // Extract opacity from current rgba value
                const opacityMatch = currentValue.match(/rgba\([^,]+,[^,]+,[^,]+,\s*([\d.]+)\)/);
                const opacity = opacityMatch ? opacityMatch[1] : '0.9';
                // Convert hex to rgba with preserved opacity
                const rgb = this.hexToRgb(hexColor);
                if (rgb) {
                    textInput.value = `rgba(${rgb.r}, ${rgb.g}, ${rgb.b}, ${opacity})`;
                } else {
                    textInput.value = hexColor;
                }
            } else {
                // No rgba value yet, just use hex
                textInput.value = hexColor;
            }
            this.woow.debouncedPreview();
        });

        colorInput.addEventListener('change', (e) => {
            const hexColor = e.target.value;
            // Check if text input already has rgba value - preserve it
            const currentValue = textInput.value.trim();
            if (currentValue.startsWith('rgba(')) {
                // Extract opacity from current rgba value
                const opacityMatch = currentValue.match(/rgba\([^,]+,[^,]+,[^,]+,\s*([\d.]+)\)/);
                const opacity = opacityMatch ? opacityMatch[1] : '0.9';
                // Convert hex to rgba with preserved opacity
                const rgb = this.hexToRgb(hexColor);
                if (rgb) {
                    textInput.value = `rgba(${rgb.r}, ${rgb.g}, ${rgb.b}, ${opacity})`;
                } else {
                    textInput.value = hexColor;
                }
            } else {
                // No rgba value yet, just use hex
                textInput.value = hexColor;
            }
        });
    }

    /**
     * Bind text input events
     *
     * @param {HTMLInputElement} textInput - Text input element
     * @param {HTMLInputElement} colorInput - Color input element
     */
    bindTextInput(textInput, colorInput) {
        textInput.addEventListener('change', (e) => {
            const color = e.target.value.trim();

            if (this.validate(color)) {
                colorInput.value = color;
                textInput.classList.remove('woow-input-error');
                this.woow.debouncedPreview();
            } else {
                textInput.classList.add('woow-input-error');
                this.woow.showNotification(
                    this.woow.i18n.invalidColor || 'Invalid color format. Use hex (#RRGGBB or #RGB).',
                    'error'
                );
                // Revert to color input value
                textInput.value = colorInput.value;
            }
        });

        textInput.addEventListener('blur', (e) => {
            // Remove error class on blur
            textInput.classList.remove('woow-input-error');
        });
    }

    /**
     * Bind reset button events
     *
     * @param {HTMLButtonElement} resetButton - Reset button element
     * @param {HTMLInputElement} colorInput - Color input element
     * @param {HTMLInputElement} textInput - Text input element
     */
    bindResetButton(resetButton, colorInput, textInput) {
        resetButton.addEventListener('click', (e) => {
            e.preventDefault();

            const defaultColor = colorInput.dataset.default || '#6366f1';

            colorInput.value = defaultColor;
            textInput.value = defaultColor;

            // Trigger change event
            colorInput.dispatchEvent(new Event('change', { bubbles: true }));

            this.woow.debouncedPreview();
        });
    }

    /**
     * Validate color format
     *
     * @param {string} color - Color string to validate
     * @returns {boolean} True if valid
     */
    validate(color) {
        // Hex color validation (3 or 6 digits)
        const hexPattern = /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/;

        // RGB/RGBA validation
        const rgbPattern = /^rgba?\(\s*\d+\s*,\s*\d+\s*,\s*\d+\s*(,\s*[\d.]+\s*)?\)$/;

        return hexPattern.test(color) || rgbPattern.test(color);
    }

    /**
     * Convert hex color to RGB
     *
     * @param {string} hex - Hex color string
     * @returns {Object|null} RGB object {r, g, b} or null if invalid
     */
    hexToRgb(hex) {
        // Remove # if present
        hex = hex.replace(/^#/, '');

        // Expand shorthand (e.g., #fff -> #ffffff)
        if (hex.length === 3) {
            hex = hex.split('').map(char => char + char).join('');
        }

        if (hex.length !== 6) {
            return null;
        }

        const r = parseInt(hex.substring(0, 2), 16);
        const g = parseInt(hex.substring(2, 4), 16);
        const b = parseInt(hex.substring(4, 6), 16);

        if (isNaN(r) || isNaN(g) || isNaN(b)) {
            return null;
        }

        return { r, g, b };
    }

    /**
     * Convert RGB to hex
     *
     * @param {number} r - Red value (0-255)
     * @param {number} g - Green value (0-255)
     * @param {number} b - Blue value (0-255)
     * @returns {string} Hex color string
     */
    rgbToHex(r, g, b) {
        const toHex = (n) => {
            const hex = Math.max(0, Math.min(255, Math.round(n))).toString(16);
            return hex.length === 1 ? '0' + hex : hex;
        };

        return `#${toHex(r)}${toHex(g)}${toHex(b)}`;
    }

    /**
     * Get color value from input
     *
     * @param {HTMLInputElement} input - Color input element
     * @returns {string} Color value
     */
    getValue(input) {
        return input.value;
    }

    /**
     * Set color value to input
     *
     * @param {HTMLInputElement} input - Color input element
     * @param {string} color - Color value to set
     */
    setValue(input, color) {
        if (!this.validate(color)) {
            console.warn('[ColorPicker] Invalid color:', color);
            return;
        }

        input.value = color;

        // Update text input if it exists
        const group = input.closest('.woow-color-picker-group');
        if (group) {
            const textInput = group.querySelector('.woow-color-text');
            if (textInput) {
                textInput.value = color;
            }
        }

        // Trigger change event
        input.dispatchEvent(new Event('change', { bubbles: true }));
    }
}

export default ColorPicker;
