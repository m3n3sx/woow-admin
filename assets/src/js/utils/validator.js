/**
 * Field type definitions
 */
const FIELD_TYPES = {
    // Colors
    COLOR: 'color',
    
    // Numbers
    OPACITY: 'opacity',           // 0-1 float
    LINE_HEIGHT: 'lineheight',    // Unitless number (1.0-3.0)
    SIZE: 'size',                 // With unit (px, %, em, rem)
    PERCENTAGE: 'percentage',     // 0-100
    
    // Keywords
    KEYWORD: 'keyword',           // cover, contain, auto, etc.
    
    // Strings
    TEXT: 'text',
    URL: 'url',
    
    // Boolean
    BOOLEAN: 'boolean',
};

/**
 * Field type mapping
 * Maps field names to their types
 */
const FIELD_TYPE_MAP = {
    // Opacity fields
    'opacity': FIELD_TYPES.OPACITY,
    'admin_bar.opacity': FIELD_TYPES.OPACITY,
    'admin_menu.opacity': FIELD_TYPES.OPACITY,
    'dashboard_widgets.opacity': FIELD_TYPES.OPACITY,
    
    // Line height fields
    'h1_line_height': FIELD_TYPES.LINE_HEIGHT,
    'h2_line_height': FIELD_TYPES.LINE_HEIGHT,
    'h3_line_height': FIELD_TYPES.LINE_HEIGHT,
    'body_line_height': FIELD_TYPES.LINE_HEIGHT,
    'link_line_height': FIELD_TYPES.LINE_HEIGHT,
    
    // Image fields
    'image_size': FIELD_TYPES.KEYWORD,
    'image_repeat': FIELD_TYPES.KEYWORD,
    'image_position': FIELD_TYPES.KEYWORD,
    'image_attachment': FIELD_TYPES.KEYWORD,
    
    // Unit selectors (not size values)
    'width_unit': FIELD_TYPES.KEYWORD,
    'admin_bar.width_unit': FIELD_TYPES.KEYWORD,
    
    // Width fields
    'admin_bar.width': FIELD_TYPES.PERCENTAGE,  // 0-100 (percent or px depending on unit)
    'admin_menu.width': FIELD_TYPES.LINE_HEIGHT,  // 160-320 (px, unitless)
    'admin_menu.item_height': FIELD_TYPES.LINE_HEIGHT,  // 36-64 (px, unitless)
    
    // Spacing fields (unitless, unit added in CSS)
    'spacing_all': FIELD_TYPES.PERCENTAGE,
    'spacing_top': FIELD_TYPES.PERCENTAGE,
    'spacing_right': FIELD_TYPES.PERCENTAGE,
    'spacing_bottom': FIELD_TYPES.PERCENTAGE,
    'spacing_left': FIELD_TYPES.PERCENTAGE,
    'admin_bar.spacing_all': FIELD_TYPES.PERCENTAGE,
    'admin_bar.spacing_top': FIELD_TYPES.PERCENTAGE,
    'admin_bar.spacing_right': FIELD_TYPES.PERCENTAGE,
    'admin_bar.spacing_bottom': FIELD_TYPES.PERCENTAGE,
    'admin_bar.spacing_left': FIELD_TYPES.PERCENTAGE,
    
    // Margin fields (unitless, unit added in CSS)
    'margin_all': FIELD_TYPES.PERCENTAGE,
    'margin_top': FIELD_TYPES.PERCENTAGE,
    'margin_right': FIELD_TYPES.PERCENTAGE,
    'margin_bottom': FIELD_TYPES.PERCENTAGE,
    'margin_left': FIELD_TYPES.PERCENTAGE,
    'admin_bar.margin_all': FIELD_TYPES.PERCENTAGE,
    'admin_bar.margin_top': FIELD_TYPES.PERCENTAGE,
    'admin_bar.margin_right': FIELD_TYPES.PERCENTAGE,
    'admin_bar.margin_bottom': FIELD_TYPES.PERCENTAGE,
    'admin_bar.margin_left': FIELD_TYPES.PERCENTAGE,
    
    // Mode selectors
    'spacing_mode': FIELD_TYPES.KEYWORD,
    'margin_mode': FIELD_TYPES.KEYWORD,
    'border_radius_mode': FIELD_TYPES.KEYWORD,
    'admin_bar.spacing_mode': FIELD_TYPES.KEYWORD,
    'admin_bar.margin_mode': FIELD_TYPES.KEYWORD,
    'admin_bar.border_radius_mode': FIELD_TYPES.KEYWORD,
    
    // Submenu fields (unitless, unit added in CSS)
    'submenu_border_radius': FIELD_TYPES.PERCENTAGE,
    'submenu_font_size': FIELD_TYPES.PERCENTAGE,
    'admin_bar.submenu_border_radius': FIELD_TYPES.PERCENTAGE,
    'admin_bar.submenu_font_size': FIELD_TYPES.PERCENTAGE,
    
    // Border radius fields (unitless, unit added in CSS)
    'border_radius_all': FIELD_TYPES.PERCENTAGE,
    'border_radius_top_left': FIELD_TYPES.PERCENTAGE,
    'border_radius_top_right': FIELD_TYPES.PERCENTAGE,
    'border_radius_bottom_right': FIELD_TYPES.PERCENTAGE,
    'border_radius_bottom_left': FIELD_TYPES.PERCENTAGE,
    'admin_bar.border_radius_all': FIELD_TYPES.PERCENTAGE,
    
    // Admin Menu fields (unitless, unit added in CSS)
    'admin_menu.width': FIELD_TYPES.PERCENTAGE,
    'admin_menu.item_height': FIELD_TYPES.PERCENTAGE,
    'admin_menu.border_radius_mode': FIELD_TYPES.KEYWORD,
    'admin_menu.border_radius_all': FIELD_TYPES.PERCENTAGE,
    'admin_menu.border_radius_top_left': FIELD_TYPES.PERCENTAGE,
    'admin_menu.border_radius_top_right': FIELD_TYPES.PERCENTAGE,
    'admin_menu.border_radius_bottom_right': FIELD_TYPES.PERCENTAGE,
    'admin_menu.border_radius_bottom_left': FIELD_TYPES.PERCENTAGE,
    'admin_menu.item_border_radius': FIELD_TYPES.PERCENTAGE,
    'admin_menu.font_size': FIELD_TYPES.PERCENTAGE,
    'admin_menu.font_weight': FIELD_TYPES.KEYWORD,
    'admin_menu.opacity': FIELD_TYPES.OPACITY,
    'admin_menu.blur_strength': FIELD_TYPES.PERCENTAGE,
    'admin_menu.shadow_style': FIELD_TYPES.KEYWORD,
    'admin_menu.spacing_mode': FIELD_TYPES.KEYWORD,
    'admin_menu.spacing_all': FIELD_TYPES.PERCENTAGE,
    'admin_menu.spacing_top': FIELD_TYPES.PERCENTAGE,
    'admin_menu.spacing_right': FIELD_TYPES.PERCENTAGE,
    'admin_menu.spacing_bottom': FIELD_TYPES.PERCENTAGE,
    'admin_menu.spacing_left': FIELD_TYPES.PERCENTAGE,
    'admin_menu.margin_mode': FIELD_TYPES.KEYWORD,
    'admin_menu.margin_all': FIELD_TYPES.PERCENTAGE,
    'admin_menu.margin_top': FIELD_TYPES.PERCENTAGE,
    'admin_menu.margin_right': FIELD_TYPES.PERCENTAGE,
    'admin_menu.margin_bottom': FIELD_TYPES.PERCENTAGE,
    'admin_menu.margin_left': FIELD_TYPES.PERCENTAGE,
    'admin_menu.icon_size': FIELD_TYPES.PERCENTAGE,
    'admin_menu.submenu_border_radius': FIELD_TYPES.PERCENTAGE,
    'admin_menu.background_type': FIELD_TYPES.KEYWORD,
    'admin_menu.hover_style': FIELD_TYPES.KEYWORD,
    'admin_menu.active_bg_type': FIELD_TYPES.KEYWORD,
    'admin_bar.border_radius_top_left': FIELD_TYPES.PERCENTAGE,
    'admin_bar.border_radius_top_right': FIELD_TYPES.PERCENTAGE,
    'admin_bar.border_radius_bottom_right': FIELD_TYPES.PERCENTAGE,
    'admin_bar.border_radius_bottom_left': FIELD_TYPES.PERCENTAGE,
    
    // Size fields (need unit)
    'height': FIELD_TYPES.SIZE,
    'width': FIELD_TYPES.SIZE,
    'font_size': FIELD_TYPES.SIZE,
    'padding': FIELD_TYPES.SIZE,
    'margin': FIELD_TYPES.SIZE,
    'border_radius': FIELD_TYPES.SIZE,
    'blur_strength': FIELD_TYPES.SIZE,
    'item_height': FIELD_TYPES.SIZE,
    'max_height': FIELD_TYPES.SIZE,
    
    // Color fields
    'background_color': FIELD_TYPES.COLOR,
    'text_color': FIELD_TYPES.COLOR,
    'border_color': FIELD_TYPES.COLOR,
    'hover_bg_color': FIELD_TYPES.COLOR,
    'active_bg_start': FIELD_TYPES.COLOR,
    'active_bg_end': FIELD_TYPES.COLOR,
    
    // URLs
    'image_url': FIELD_TYPES.URL,
    'custom_logo': FIELD_TYPES.URL,
    
    // Booleans
    'use_gradient': FIELD_TYPES.BOOLEAN,
    'glassmorphism': FIELD_TYPES.BOOLEAN,
    'enable_animations': FIELD_TYPES.BOOLEAN,
};

/**
 * Valid keywords for KEYWORD type
 */
const VALID_KEYWORDS = {
    'image_size': ['cover', 'contain', 'auto', 'initial', 'inherit'],
    'image_repeat': ['repeat', 'repeat-x', 'repeat-y', 'no-repeat', 'space', 'round'],
    'image_position': ['center', 'top', 'bottom', 'left', 'right', 'top left', 'top right', 'bottom left', 'bottom right'],
    'image_attachment': ['scroll', 'fixed', 'local'],
    'width_unit': ['%', 'px'],
    'spacing_mode': ['all', 'individual'],
    'margin_mode': ['all', 'individual'],
    'border_radius_mode': ['all', 'individual'],
};

/**
 * Validator class
 */
class Validator {
    /**
     * Validate a single field
     * 
     * @param {*} value - Field value
     * @param {string} key - Field key (e.g., 'admin_bar.opacity')
     * @returns {*} Validated/converted value
     * @throws {Error} If validation fails
     */
    static validate(value, key) {
        const fieldType = this.getFieldType(key);
        
        switch (fieldType) {
            case FIELD_TYPES.OPACITY:
                return this.validateOpacity(value, key);
                
            case FIELD_TYPES.LINE_HEIGHT:
                return this.validateLineHeight(value, key);
                
            case FIELD_TYPES.SIZE:
                return this.validateSize(value, key);
                
            case FIELD_TYPES.PERCENTAGE:
                return this.validatePercentage(value, key);
                
            case FIELD_TYPES.KEYWORD:
                return this.validateKeyword(value, key);
                
            case FIELD_TYPES.COLOR:
                return this.validateColor(value, key);
                
            case FIELD_TYPES.URL:
                return this.validateURL(value, key);
                
            case FIELD_TYPES.BOOLEAN:
                return this.validateBoolean(value, key);
                
            case FIELD_TYPES.TEXT:
            default:
                return value; // Text fields pass through
        }
    }
    
    /**
     * Get field type from key
     */
    static getFieldType(key) {
        // Try exact match first
        if (FIELD_TYPE_MAP[key]) {
            return FIELD_TYPE_MAP[key];
        }
        
        // Try matching field name (last part after dot)
        const fieldName = key.split('.').pop();
        if (FIELD_TYPE_MAP[fieldName]) {
            return FIELD_TYPE_MAP[fieldName];
        }
        
        // Default to text
        return FIELD_TYPES.TEXT;
    }
    
    /**
     * Validate opacity (0-1 float)
     */
    static validateOpacity(value, key) {
        let opacity;
        
        // If value is 0-100 (from range slider), convert to 0-1
        if (typeof value === 'number' && value > 1 && value <= 100) {
            opacity = value / 100;
        } else {
            opacity = parseFloat(value);
        }
        
        if (isNaN(opacity)) {
            throw new Error(`Invalid opacity for '${key}': ${value}. Must be a number.`);
        }
        
        if (opacity < 0 || opacity > 1) {
            throw new Error(`Opacity for '${key}' must be between 0 and 1. Got: ${opacity}`);
        }
        
        return opacity;
    }
    
    /**
     * Validate line-height (unitless number)
     * Also used for pixel values like width (160-320) and height (36-64)
     */
    static validateLineHeight(value, key) {
        const lineHeight = parseFloat(value);
        
        if (isNaN(lineHeight)) {
            throw new Error(`Invalid value for '${key}': ${value}. Must be a number.`);
        }
        
        // For actual line-height fields (0.5-5.0 range)
        if (key.includes('line_height')) {
            if (lineHeight < 0.5 || lineHeight > 5.0) {
                throw new Error(`Line-height for '${key}' should be between 0.5 and 5.0. Got: ${lineHeight}`);
            }
        }
        // For width/height fields (allow larger values for pixels)
        else if (key.includes('width') || key.includes('height')) {
            if (lineHeight < 0 || lineHeight > 2000) {
                throw new Error(`Value for '${key}' should be between 0 and 2000. Got: ${lineHeight}`);
            }
        }
        // For other unitless fields (reasonable range)
        else {
            if (lineHeight < 0 || lineHeight > 500) {
                throw new Error(`Value for '${key}' should be between 0 and 500. Got: ${lineHeight}`);
            }
        }
        
        return lineHeight;
    }
    
    /**
     * Validate size (with unit)
     */
    static validateSize(value, key) {
        // If value is empty, return empty string
        if (!value || value === '') {
            return '';
        }
        
        // If value already has unit, validate it
        if (typeof value === 'string' && /^\d+(\.\d+)?(px|%|em|rem|vh|vw)$/.test(value)) {
            return value;
        }
        
        // If value is number, add 'px'
        const numValue = parseFloat(value);
        if (!isNaN(numValue)) {
            return numValue + 'px';
        }
        
        throw new Error(`Invalid size format for '${key}': ${value}. Expected number with unit (e.g., '50px', '100%').`);
    }
    
    /**
     * Validate percentage (0-100)
     */
    static validatePercentage(value, key) {
        const percent = parseFloat(value);
        
        if (isNaN(percent)) {
            throw new Error(`Invalid percentage for '${key}': ${value}. Must be a number.`);
        }
        
        if (percent < 0 || percent > 100) {
            throw new Error(`Percentage for '${key}' must be between 0 and 100. Got: ${percent}`);
        }
        
        return percent;
    }
    
    /**
     * Validate keyword (from predefined list)
     */
    static validateKeyword(value, key) {
        const fieldName = key.split('.').pop();
        const validKeywords = VALID_KEYWORDS[fieldName];
        
        if (!validKeywords) {
            // No validation list, accept any keyword
            return value;
        }
        
        const lowerValue = (value || '').toString().toLowerCase();
        
        if (!validKeywords.includes(lowerValue)) {
            throw new Error(`Invalid keyword for '${key}': ${value}. Must be one of: ${validKeywords.join(', ')}`);
        }
        
        return lowerValue;
    }
    
    /**
     * Validate color (#rrggbb or rgba())
     */
    static validateColor(value, key) {
        if (!value) {
            throw new Error(`Color for '${key}' cannot be empty.`);
        }
        
        // Hex color (#rrggbb or #rgb)
        if (/^#[0-9A-Fa-f]{3}([0-9A-Fa-f]{3})?$/.test(value)) {
            // Convert #rgb to #rrggbb
            if (value.length === 4) {
                return '#' + value[1] + value[1] + value[2] + value[2] + value[3] + value[3];
            }
            return value.toLowerCase();
        }
        
        // RGBA color
        if (/^rgba?\([\d\s,.]+\)$/.test(value)) {
            return value;
        }
        
        throw new Error(`Invalid color format for '${key}': ${value}. Expected #rrggbb or rgba().`);
    }
    
    /**
     * Validate URL
     */
    static validateURL(value, key) {
        if (!value || value === '') {
            return ''; // Empty URL is OK
        }
        
        try {
            new URL(value);
            return value;
        } catch (e) {
            throw new Error(`Invalid URL for '${key}': ${value}`);
        }
    }
    
    /**
     * Validate boolean
     */
    static validateBoolean(value, key) {
        if (typeof value === 'boolean') {
            return value;
        }
        
        if (value === 'true' || value === '1' || value === 1) {
            return true;
        }
        
        if (value === 'false' || value === '0' || value === 0 || value === '') {
            return false;
        }
        
        throw new Error(`Invalid boolean for '${key}': ${value}. Expected true/false.`);
    }
    
    /**
     * Validate entire settings object
     * 
     * @param {Object} settings - Settings object
     * @returns {Object} Validated settings
     * @throws {Error} If validation fails
     */
    static validateSettings(settings) {
        const validated = {};
        const errors = [];
        
        for (const [section, sectionSettings] of Object.entries(settings)) {
            validated[section] = {};
            
            for (const [key, value] of Object.entries(sectionSettings)) {
                const fullKey = `${section}.${key}`;
                
                try {
                    validated[section][key] = this.validate(value, fullKey);
                } catch (error) {
                    errors.push({
                        key: fullKey,
                        value: value,
                        error: error.message
                    });
                }
            }
        }
        
        if (errors.length > 0) {
            throw new ValidationError('Validation failed', errors);
        }
        
        return validated;
    }
    
    /**
     * Validate all settings and return detailed result
     * (Non-throwing version for UI validation)
     * 
     * @param {Object} settings - Settings object
     * @returns {Object} Validation result with valid, errors, validFields
     */
    static validateAll(settings) {
        const validated = {};
        const errors = [];
        const validFields = [];
        
        for (const [section, sectionSettings] of Object.entries(settings)) {
            validated[section] = {};
            
            for (const [key, value] of Object.entries(sectionSettings)) {
                const fullKey = `${section}.${key}`;
                
                try {
                    validated[section][key] = this.validate(value, fullKey);
                    validFields.push(fullKey);
                } catch (error) {
                    errors.push({
                        field: fullKey,
                        key: fullKey,
                        value: value,
                        message: error.message,
                        error: error.message
                    });
                }
            }
        }
        
        return {
            valid: errors.length === 0,
            errors: errors,
            validFields: validFields,
            validated: validated
        };
    }
}

/**
 * Custom ValidationError
 */
class ValidationError extends Error {
    constructor(message, errors) {
        super(message);
        this.name = 'ValidationError';
        this.errors = errors;
    }
}

export { Validator, ValidationError, FIELD_TYPES };
