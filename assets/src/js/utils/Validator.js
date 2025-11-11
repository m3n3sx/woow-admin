/**
 * Validator Utility
 *
 * Provides type-specific validation for form fields.
 *
 * @package WoowAdmin
 * @since 1.0.0
 */

export class Validator {
    /**
     * Constructor
     */
    constructor() {
        // Type mapping for field names
        this.typeMap = {
            // Opacity fields
            'opacity': 'opacity',
            
            // Line-height fields
            'h1_line_height': 'line-height',
            'h2_line_height': 'line-height',
            'h3_line_height': 'line-height',
            'h4_line_height': 'line-height',
            'h5_line_height': 'line-height',
            'h6_line_height': 'line-height',
            'body_line_height': 'line-height',
            'line_height': 'line-height',
            
            // Keyword fields (CSS keywords)
            'image_size': 'keyword',
            'image_repeat': 'keyword',
            'image_position': 'keyword',
            'background_size': 'keyword',
            'background_repeat': 'keyword',
            'background_position': 'keyword',
            
            // Size fields (require units)
            'blur_strength': 'size',
            'height': 'size',
            'width': 'size',
            'font_size': 'size',
            'h1_font_size': 'size',
            'h2_font_size': 'size',
            'h3_font_size': 'size',
            'h4_font_size': 'size',
            'h5_font_size': 'size',
            'h6_font_size': 'size',
            'body_font_size': 'size',
            'padding': 'size',
            'padding_top': 'size',
            'padding_right': 'size',
            'padding_bottom': 'size',
            'padding_left': 'size',
            'margin': 'size',
            'margin_top': 'size',
            'margin_right': 'size',
            'margin_bottom': 'size',
            'margin_left': 'size',
            'border_radius': 'size',
            'border_width': 'size',
            'min_height': 'size',
            'max_height': 'size',
            'min_width': 'size',
            'max_width': 'size',
            
            // Color fields
            'background_color': 'color',
            'text_color': 'color',
            'hover_bg_color': 'color',
            'hover_text_color': 'color',
            'active_bg_color': 'color',
            'active_text_color': 'color',
            'active_bg_start': 'color',
            'active_bg_end': 'color',
            'border_color': 'color',
            'link_color': 'color',
            'link_hover_color': 'color',
            'h1_color': 'color',
            'h2_color': 'color',
            'h3_color': 'color',
            'h4_color': 'color',
            'h5_color': 'color',
            'h6_color': 'color',
            'body_color': 'color',
            'primary_bg_color': 'color',
            'primary_text_color': 'color',
            'secondary_bg_color': 'color',
            'secondary_text_color': 'color',
            'destructive_bg_color': 'color',
            'destructive_text_color': 'color',
            'input_bg_color': 'color',
            'input_border_color': 'color',
            'input_text_color': 'color',
            'input_focus_color': 'color',
            'gradient_start': 'color',
            'gradient_end': 'color'
        };
        
        // Valid CSS keywords
        this.validKeywords = [
            'cover', 'contain', 'auto', 'none',
            'no-repeat', 'repeat', 'repeat-x', 'repeat-y',
            'center', 'top', 'bottom', 'left', 'right',
            'top-left', 'top-right', 'bottom-left', 'bottom-right'
        ];
    }

    /**
     * Get field type based on field key
     *
     * @param {string} key - Field key (e.g., 'admin_bar.opacity' or 'opacity')
     * @returns {string} Field type
     */
    getFieldType(key) {
        // Extract field name from nested keys (e.g., 'admin_bar.opacity' -> 'opacity')
        // Also handle bracket notation: 'admin_bar[opacity]' -> 'opacity'
        let fieldName = key;
        
        if (key.includes('.')) {
            fieldName = key.split('.').pop();
        } else if (key.includes('[')) {
            const match = key.match(/\[([^\]]+)\]$/);
            if (match) {
                fieldName = match[1];
            }
        }
        
        // Remove any remaining brackets
        fieldName = fieldName.replace(/\[|\]/g, '');
        
        // Return mapped type or 'default'
        return this.typeMap[fieldName] || 'default';
    }

    /**
     * Validate value based on field type
     *
     * @param {*} value - Value to validate
     * @param {string} key - Field key
     * @returns {Object} Validation result { valid: boolean, value: *, error?: string }
     */
    validateValue(value, key) {
        const fieldType = this.getFieldType(key);
        
        switch (fieldType) {
            case 'opacity':
                return this.validateOpacity(value);
                
            case 'line-height':
                return this.validateLineHeight(value);
                
            case 'keyword':
                return this.validateKeyword(value);
                
            case 'size':
                return this.validateSize(value);
                
            case 'color':
                return this.validateColor(value);
                
            default:
                // No specific validation - accept as-is
                return { valid: true, value };
        }
    }

    /**
     * Validate opacity value (0-1 float)
     * Accepts 0-100 from range sliders and converts to 0-1
     *
     * @param {*} value - Value to validate
     * @returns {Object} Validation result
     */
    validateOpacity(value) {
        let opacity = parseFloat(value);
        
        if (isNaN(opacity)) {
            return {
                valid: false,
                error: 'Opacity must be a number'
            };
        }
        
        // If value is 0-100 (from range slider), convert to 0-1
        if (opacity > 1 && opacity <= 100) {
            opacity = opacity / 100;
        }
        
        if (opacity < 0 || opacity > 1) {
            return {
                valid: false,
                error: 'Opacity must be between 0 and 1'
            };
        }
        
        return { valid: true, value: opacity };
    }

    /**
     * Validate line-height value (unitless 0.5-5.0)
     *
     * @param {*} value - Value to validate
     * @returns {Object} Validation result
     */
    validateLineHeight(value) {
        // Remove any units if present (should be unitless)
        let cleanValue = String(value).replace(/px|em|rem|%/gi, '').trim();
        const lineHeight = parseFloat(cleanValue);
        
        if (isNaN(lineHeight) || lineHeight < 0.5 || lineHeight > 5.0) {
            return {
                valid: false,
                error: 'Line-height must be between 0.5 and 5.0 (unitless)'
            };
        }
        
        return { valid: true, value: lineHeight };
    }

    /**
     * Validate CSS keyword value
     *
     * @param {*} value - Value to validate
     * @returns {Object} Validation result
     */
    validateKeyword(value) {
        const keyword = String(value).toLowerCase().trim();
        
        if (!this.validKeywords.includes(keyword)) {
            return {
                valid: false,
                error: `Invalid keyword: ${value}`
            };
        }
        
        return { valid: true, value: keyword };
    }

    /**
     * Validate size value (must have unit: px, %, em, rem)
     *
     * @param {*} value - Value to validate
     * @returns {Object} Validation result
     */
    validateSize(value) {
        const sizePattern = /^\d+(\.\d+)?(px|%|em|rem)$/;
        const valueStr = String(value).trim();
        
        if (!sizePattern.test(valueStr)) {
            return {
                valid: false,
                error: `Invalid size format: ${value}. Must include unit (px, %, em, rem)`
            };
        }
        
        return { valid: true, value: valueStr };
    }

    /**
     * Validate color value (hex or rgba)
     *
     * @param {*} value - Value to validate
     * @returns {Object} Validation result
     */
    validateColor(value) {
        const valueStr = String(value).trim();
        
        // Hex color validation (3 or 6 digits)
        const hexPattern = /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/;
        
        // RGB/RGBA validation
        const rgbaPattern = /^rgba?\(\s*\d+\s*,\s*\d+\s*,\s*\d+\s*(,\s*[\d.]+\s*)?\)$/;
        
        if (!hexPattern.test(valueStr) && !rgbaPattern.test(valueStr)) {
            return {
                valid: false,
                error: `Invalid color format: ${value}. Use hex (#RRGGBB) or rgba()`
            };
        }
        
        return { valid: true, value: valueStr };
    }

    /**
     * Validate multiple fields and collect errors
     *
     * @param {Object} data - Data object to validate
     * @returns {Object} Validation result { valid: boolean, errors: Array, validFields: Array }
     */
    validateAll(data) {
        const errors = [];
        const validFields = [];
        
        // Flatten nested object structure
        const flattenData = (obj, prefix = '') => {
            const result = {};
            
            for (const key in obj) {
                if (obj.hasOwnProperty(key)) {
                    const value = obj[key];
                    const fullKey = prefix ? `${prefix}.${key}` : key;
                    
                    if (typeof value === 'object' && value !== null && !Array.isArray(value)) {
                        Object.assign(result, flattenData(value, fullKey));
                    } else {
                        result[fullKey] = value;
                    }
                }
            }
            
            return result;
        };
        
        const flatData = flattenData(data);
        
        // Validate each field
        for (const key in flatData) {
            if (flatData.hasOwnProperty(key)) {
                const value = flatData[key];
                const result = this.validateValue(value, key);
                
                if (result.valid) {
                    validFields.push(key);
                } else {
                    errors.push({
                        field: key,
                        value: value,
                        message: result.error
                    });
                }
            }
        }
        
        return {
            valid: errors.length === 0,
            errors,
            validFields
        };
    }
}

export default Validator;
