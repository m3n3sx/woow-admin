# Border Radius Mode Implementation

## Feature Description

Added border radius mode selector for Admin Bar with two options:
- **All Corners**: Single slider controlling all four corners (default: 24px)
- **Individual Corners**: Four separate sliders for each corner (top-left, top-right, bottom-right, bottom-left)

When "All Corners" is selected, individual corner sliders are hidden using conditional fields.

## Implementation Steps

### Step 1: Defaults (includes/defaults.php)

Added new fields to admin_bar section:
```php
'border_radius_mode' => 'all', // all or individual
'border_radius_all' => '24', // When mode is 'all'
'border_radius_top_left' => '24',
'border_radius_top_right' => '24',
'border_radius_bottom_right' => '24',
'border_radius_bottom_left' => '24',
```

### Step 2: Template (includes/templates/tabs/admin-bar-tab.php)

Replaced single border_radius slider with:

1. **Mode Selector** (radio buttons):
   - "All Corners" (default)
   - "Individual Corners"
   - Uses `woow-condition-trigger` class with `data-target="border_radius_mode"`

2. **All Corners Slider** (conditional):
   - Shows when mode = "all"
   - Range: 0-50px, step: 2
   - Uses `data-condition="border_radius_mode"` and `data-value="all"`

3. **Individual Corner Sliders** (conditional):
   - Shows when mode = "individual"
   - Four sliders: top-left, top-right, bottom-right, bottom-left
   - Each: Range 0-50px, step: 2
   - Wrapped in div with `data-condition="border_radius_mode"` and `data-value="individual"`

### Step 3: JavaScript Validation (assets/src/js/utils/validator.js)

Added to FIELD_TYPE_MAP:
```javascript
'border_radius_mode': FIELD_TYPES.KEYWORD,
'admin_bar.border_radius_mode': FIELD_TYPES.KEYWORD,

'border_radius_all': FIELD_TYPES.PERCENTAGE,
'border_radius_top_left': FIELD_TYPES.PERCENTAGE,
'border_radius_top_right': FIELD_TYPES.PERCENTAGE,
'border_radius_bottom_right': FIELD_TYPES.PERCENTAGE,
'border_radius_bottom_left': FIELD_TYPES.PERCENTAGE,
// ... with admin_bar. prefix
```

Added to VALID_KEYWORDS:
```javascript
'border_radius_mode': ['all', 'individual'],
```

**Note**: Border radius values are PERCENTAGE type (unitless numbers), unit is added in CSS generation.

### Step 4: PHP Validation (includes/class-woow-settings.php)

Updated mode selector validation:
```php
elseif ( $key === 'spacing_mode' || $key === 'margin_mode' || $key === 'border_radius_mode' ) {
    if ( ! in_array( $value, array( 'all', 'individual' ), true ) ) {
        $is_valid = false;
        $error_message = "Invalid mode (expected 'all' or 'individual')";
    }
}
```

Updated unitless number validation:
```php
elseif ( strpos( $key, 'spacing_' ) !== false || 
         strpos( $key, 'margin_' ) !== false || 
         strpos( $key, 'border_radius_' ) !== false ) {
    if ( ! is_numeric( $value ) || $value < 0 ) {
        $is_valid = false;
        $error_message = "Value must be a positive number";
    }
}
```

### Step 5: CSS Generation (includes/class-woow-css-generator.php)

Replaced single border_radius variable with mode-based logic:

```php
// Border Radius - handle mode (all or individual)
$border_radius_mode = $bar['border_radius_mode'] ?? 'all';
if ( $border_radius_mode === 'all' ) {
    $border_radius_all = $bar['border_radius_all'] ?? '24';
    $border_radius = $border_radius_all . 'px';
} else {
    // Individual corners
    $border_radius_top_left = $bar['border_radius_top_left'] ?? '24';
    $border_radius_top_right = $bar['border_radius_top_right'] ?? '24';
    $border_radius_bottom_right = $bar['border_radius_bottom_right'] ?? '24';
    $border_radius_bottom_left = $bar['border_radius_bottom_left'] ?? '24';
    $border_radius = "{$border_radius_top_left}px {$border_radius_top_right}px {$border_radius_bottom_right}px {$border_radius_bottom_left}px";
}
```

CSS output:
- Mode "all": `border-radius: 24px !important;`
- Mode "individual": `border-radius: 24px 24px 24px 24px !important;` (top-left, top-right, bottom-right, bottom-left)

### Step 6: Conditional Fields Enhancement (assets/src/js/main.js)

Updated `setupConditionalFields()` to support both formats:

**Old format** (still supported):
```html
<div class="woow-conditional" data-show-when="background_type=gradient">
```

**New format** (recommended):
```html
<div class="woow-conditional-field" data-condition="border_radius_mode" data-value="all">
```

Updated selector:
```javascript
const conditionalFields = document.querySelectorAll('.woow-conditional, .woow-conditional-field');
```

Updated condition parsing:
```javascript
if (field.dataset.showWhen) {
    // Old format
    [fieldName, expectedValue] = field.dataset.showWhen.split('=');
} else if (field.dataset.condition && field.dataset.value) {
    // New format
    fieldName = field.dataset.condition;
    expectedValue = field.dataset.value;
}
```

## Field Types Reference

### Mode Selector
- **Type**: KEYWORD
- **Values**: 'all', 'individual'
- **Storage**: String
- **Validation**: Must be one of allowed values

### Border Radius Values
- **Type**: PERCENTAGE (unitless number)
- **Range**: 0-50
- **Storage**: String (e.g., '24')
- **CSS**: Unit added during generation (e.g., '24px')
- **Validation**: Must be positive number

## Critical Fix: data-type="unitless" Required

**Problem**: Initial implementation had `data-unit="px"` without `data-type="unitless"`, causing JavaScript to append "px" to values (e.g., "24px"). PHP validation expected unitless numbers (e.g., "24").

**Solution**: Added `data-type="unitless"` to all border_radius range inputs:
```html
<input 
    type="range" 
    name="admin_bar[border_radius_all]"
    data-type="unitless"  <!-- CRITICAL: Prevents double unit -->
    data-unit="px"        <!-- For display only -->
/>
```

This ensures:
- JavaScript collects: `"24"` (unitless)
- PHP validates: `"24"` (positive number) ✅
- CSS generates: `"24px"` (unit added)

## Testing Checklist

- [x] Fresh install - defaults work (mode: 'all', value: 24)
- [x] Mode selector switches between "all" and "individual"
- [x] Individual sliders hidden when mode = "all"
- [x] Individual sliders visible when mode = "individual"
- [x] All corners slider hidden when mode = "individual"
- [x] All corners slider visible when mode = "all"
- [x] Values save correctly for both modes (unitless)
- [x] CSS generates correctly:
  - Mode "all": single value with px
  - Mode "individual": four values with px in correct order
- [x] Validation accepts valid values (unitless numbers)
- [x] Validation rejects invalid values
- [x] Live preview updates on change
- [x] No console errors
- [x] No validation errors on save

## Files Modified

1. `woow-admin/includes/defaults.php` - Added border_radius_mode and individual corner defaults
2. `woow-admin/includes/templates/tabs/admin-bar-tab.php` - Added mode selector and conditional sliders
3. `woow-admin/assets/src/js/utils/validator.js` - Added field type mappings and valid keywords
4. `woow-admin/includes/class-woow-settings.php` - Added PHP validation for mode and values
5. `woow-admin/includes/class-woow-css-generator.php` - Added mode-based CSS generation
6. `woow-admin/assets/src/js/main.js` - Enhanced conditional fields to support new format

## Build Command

```bash
node node_modules/vite/bin/vite.js build
```

## Usage Example

### All Corners Mode (Default)
```
Mode: All Corners
Value: 24px
Result: border-radius: 24px;
```

### Individual Corners Mode
```
Mode: Individual Corners
Top Left: 24px
Top Right: 12px
Bottom Right: 24px
Bottom Left: 12px
Result: border-radius: 24px 12px 24px 12px;
```

## CSS Order

CSS border-radius shorthand order:
1. Top-left
2. Top-right
3. Bottom-right
4. Bottom-left

## Future Enhancements

- [ ] Add visual preview showing which corner is which
- [ ] Add "link corners" button to sync all values in individual mode
- [ ] Add preset radius values (sharp, rounded, pill)
- [ ] Add unit selector (px, rem, %) for border radius

## Date
November 12, 2025

## Status
✅ **COMPLETED** - Feature implemented, tested, and documented
