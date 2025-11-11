# FIX: Border Radius Validation Error - Missing data-type="unitless"

## Problem

After implementing border radius mode feature, validation errors appeared:

```
[WOOW Admin] Validation failed: Array (
    [0] => Array (
        [field] => admin_bar.border_radius_top_left
        [message] => Value must be a positive number
        [value] => 26px  ← Has unit!
    )
    [1] => Array (
        [field] => admin_bar.border_radius_top_right
        [message] => Value must be a positive number
        [value] => 0px  ← Has unit!
    )
    ...
)
```

## Root Cause

Range inputs had `data-unit="px"` but were missing `data-type="unitless"`:

```html
<!-- ❌ WRONG - JavaScript adds "px" to value -->
<input 
    type="range" 
    name="admin_bar[border_radius_all]"
    data-unit="px"
/>
<!-- Result: value = "24px" → PHP validation fails -->
```

## Why This Happens

When a range slider has `data-unit` but NOT `data-type="unitless"`:

1. **JavaScript collectFormData()** sees `data-unit="px"`
2. Appends unit to value: `"24"` → `"24px"`
3. Sends to server: `border_radius_all: "24px"`
4. **PHP validation** expects unitless number
5. Checks: `is_numeric("24px")` → `false` ❌
6. Validation fails: "Value must be a positive number"

## Solution

Added `data-type="unitless"` to ALL border_radius range inputs:

```html
<!-- ✅ CORRECT - JavaScript keeps value unitless -->
<input 
    type="range" 
    name="admin_bar[border_radius_all]"
    data-type="unitless"  <!-- Prevents unit from being appended -->
    data-unit="px"        <!-- Only for display in UI -->
/>
<!-- Result: value = "24" → PHP validation passes ✅ -->
```

## Files Modified

### woow-admin/includes/templates/tabs/admin-bar-tab.php

Added `data-type="unitless"` to 5 inputs:

1. `border_radius_all` (all corners mode)
2. `border_radius_top_left` (individual mode)
3. `border_radius_top_right` (individual mode)
4. `border_radius_bottom_right` (individual mode)
5. `border_radius_bottom_left` (individual mode)

## Data Flow

### Before Fix
```
HTML Input:
  data-unit="px" (no data-type)
  
JavaScript collectFormData():
  value = input.value + unit
  value = "24" + "px" = "24px"
  
PHP Validation:
  is_numeric("24px") → false ❌
  Error: "Value must be a positive number"
```

### After Fix
```
HTML Input:
  data-type="unitless"
  data-unit="px"
  
JavaScript collectFormData():
  if (dataType === 'unitless') {
    value = input.value  // No unit appended
  }
  value = "24"
  
PHP Validation:
  is_numeric("24") → true ✅
  Validation passes
  
CSS Generation:
  $border_radius = "24" . "px"
  Result: "24px"
```

## Key Lesson

**When storing unitless values that get units added in CSS generation:**

1. ✅ Add `data-type="unitless"` to HTML input
2. ✅ Add `data-unit="px"` for UI display only
3. ✅ Store as unitless number in database
4. ✅ Add unit in CSS generator

**Pattern:**
```html
<input 
    type="range"
    name="section[field]"
    data-type="unitless"  <!-- REQUIRED for unitless fields -->
    data-unit="px"        <!-- For display only -->
/>
```

## Related Fields Using This Pattern

All these fields use `data-type="unitless"`:

- `spacing_all`, `spacing_top`, `spacing_right`, `spacing_bottom`, `spacing_left`
- `margin_all`, `margin_top`, `margin_right`, `margin_bottom`, `margin_left`
- `border_radius_all`, `border_radius_top_left`, `border_radius_top_right`, `border_radius_bottom_right`, `border_radius_bottom_left`
- `submenu_border_radius`, `submenu_font_size`
- `width` (when unit is separate field)

## Build Command

```bash
node node_modules/vite/bin/vite.js build
```

## Testing

1. Change border radius value
2. Save settings
3. Check console - no validation errors ✅
4. Check database - value stored as "24" (unitless) ✅
5. Check generated CSS - value used as "24px" ✅

## Date
November 12, 2025

## Status
✅ **FIXED** - All border_radius inputs now have `data-type="unitless"`
