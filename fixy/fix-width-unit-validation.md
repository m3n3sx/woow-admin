# Fix: Admin Bar Width Unit Validation Error

## Problem
The `admin_bar.width_unit` field is being validated as a SIZE field (expecting numbers with units like "50px") instead of a KEYWORD field (accepting unit strings like "%" or "px").

**Error:**
```
Invalid size format for 'admin_bar.width_unit': %. Expected number with unit (e.g., '50px', '100%').
```

## Root Cause
The validator's `getFieldType()` method matches field names by their last part after the dot. Since `width_unit` contains "width", it was being matched to the SIZE type instead of KEYWORD type.

## Solution

### Step 1: Add width_unit to FIELD_TYPE_MAP
File: `woow-admin/assets/src/js/utils/validator.js`

Added explicit mapping for width_unit fields as KEYWORD type:

```javascript
// Unit selectors (not size values)
'width_unit': FIELD_TYPES.KEYWORD,
'admin_bar.width_unit': FIELD_TYPES.KEYWORD,
```

### Step 2: Add valid keywords for width_unit
Added validation list for width_unit to accept only "%" or "px":

```javascript
const VALID_KEYWORDS = {
    'image_size': ['cover', 'contain', 'auto', 'initial', 'inherit'],
    'image_repeat': ['repeat', 'repeat-x', 'repeat-y', 'no-repeat', 'space', 'round'],
    'image_position': ['center', 'top', 'bottom', 'left', 'right', 'top left', 'top right', 'bottom left', 'bottom right'],
    'image_attachment': ['scroll', 'fixed', 'local'],
    'width_unit': ['%', 'px'],  // ← ADDED
};
```

### Step 3: Fix PHP validation
File: `woow-admin/includes/class-woow-settings.php`

Added exception for `width_unit` before the general width/height validation:

```php
} elseif ( $key === 'width_unit' ) {
    // Unit selector - accepts "%" or "px" as keywords
    if ( ! in_array( $value, array( '%', 'px' ), true ) ) {
        $is_valid = false;
        $error_message = "Invalid unit (expected '%' or 'px')";
    }
} elseif ( strpos( $key, 'height' ) !== false || strpos( $key, 'width' ) !== false || ...
```

This ensures `width_unit` is validated as a keyword field before the general width validation catches it.

### Step 4: Rebuild assets
```bash
cd woow-admin
npm run build
```

## Testing
1. Open WOOW! Admin settings
2. Go to Admin Bar tab
3. Change width unit between "%" and "px"
4. Save settings
5. Verify no validation errors appear

## Expected Result
✅ Width unit selector validates correctly as KEYWORD field
✅ Accepts "%" and "px" values
✅ No "Invalid size format" errors
✅ Settings save successfully

## Status
✅ FIXED - Validator now correctly handles width_unit as KEYWORD type
