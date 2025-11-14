# Submenu Field Validation Fix

## Problem
New submenu styling fields were causing validation errors:
- `submenu_border_radius`
- `submenu_font_size`
- `submenu_item_height`
- `submenu_item_border_radius`

**Error:** "Percentage for 'admin_menu.submenu_border_radius' must be between 0 and 100. Got: 12"

## Root Cause
Fields were mapped to `PERCENTAGE` type (0-100 range) when they should be `NUMBER` type (any positive number, unitless).

These fields store unitless numbers (e.g., "12") and the unit (e.g., "px") is added during CSS generation.

## Solution

### 1. JavaScript Validation (Validator.js)
Changed field type mappings from `PERCENTAGE` to `NUMBER`:

```javascript
// BEFORE
'admin_menu.submenu_border_radius': FIELD_TYPES.PERCENTAGE,
'admin_menu.submenu_font_size': FIELD_TYPES.PERCENTAGE,
'admin_menu.submenu_item_border_radius': FIELD_TYPES.PERCENTAGE,

// AFTER
'admin_menu.submenu_border_radius': FIELD_TYPES.NUMBER,
'admin_menu.submenu_font_size': FIELD_TYPES.NUMBER,
'admin_menu.submenu_item_border_radius': FIELD_TYPES.NUMBER,
```

### 2. PHP Validation (class-woow-settings.php)
Added new submenu fields to admin_menu unitless validation:

```php
// BEFORE
elseif ( $section === 'admin_menu' && ( 
    $key === 'width' || 
    $key === 'item_height' || 
    $key === 'item_border_radius' || 
    $key === 'font_size' || 
    $key === 'blur_strength' || 
    $key === 'icon_size' || 
    $key === 'submenu_border_radius' 
) ) {

// AFTER
elseif ( $section === 'admin_menu' && ( 
    $key === 'width' || 
    $key === 'item_height' || 
    $key === 'item_border_radius' || 
    $key === 'font_size' || 
    $key === 'blur_strength' || 
    $key === 'icon_size' || 
    $key === 'submenu_border_radius' || 
    $key === 'submenu_item_height' || 
    $key === 'submenu_font_size' || 
    $key === 'submenu_item_border_radius' 
) ) {
```

Also added `submenu_font_weight` to keyword validation:

```php
elseif ( $section === 'admin_menu' && ( 
    $key === 'font_weight' || 
    $key === 'shadow_style' || 
    $key === 'background_type' || 
    $key === 'hover_style' || 
    $key === 'submenu_font_weight'  // NEW
) ) {
```

## Field Type Decision

**Rule:** If a field stores a unitless number and the unit is added in CSS generation, use `NUMBER` type.

### NUMBER Type Fields (Unitless)
- Store: `"12"` (just the number)
- CSS: `border-radius: 12px;` (unit added)
- Validation: Any positive number
- Examples: `submenu_border_radius`, `submenu_font_size`, `item_height`

### PERCENTAGE Type Fields
- Store: `"50"` (0-100 range)
- CSS: `width: 50%;` or used in calculations
- Validation: 0-100 range
- Examples: `spacing_all`, `margin_all`, `blur_strength`

### SIZE Type Fields (With Unit)
- Store: `"48px"` (number + unit)
- CSS: `height: 48px;` (used as-is)
- Validation: Number with unit
- Examples: `height`, `width` (when unit is included)

## Testing
1. ✅ Build: `npm run build`
2. ✅ Clear cache: `bash cc.sh`
3. ✅ Test save with submenu fields
4. ✅ Verify no validation errors
5. ✅ Check CSS generation includes proper units

## Files Modified
- `assets/src/js/utils/Validator.js` - Changed field type mappings
- `includes/class-woow-settings.php` - Added PHP validation rules
- `CHANGELOG.md` - Documented the fix

## Status
✅ **FIXED** - All submenu fields now validate correctly as unitless numbers.
