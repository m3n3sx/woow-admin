# Type Mismatch Fix - sanitize_unit() TypeError

## Date: 2025-11-10
## Status: ✅ FIXED

---

## Problem

After fixing the AJAX 500 error, a new TypeError appeared:

```
PHP Fatal error: Uncaught TypeError: WOOW_Settings::sanitize_unit(): 
Argument #1 ($value) must be of type string, float given, 
called in /wp-content/plugins/woow-admin/includes/class-woow-settings.php on line 961
```

**Error Details:**
- Method: `sanitize_unit()`
- Expected: `string`
- Received: `float` (value: 1.3)
- Context: Validating line_height values

---

## Root Cause

The validation logic was treating `line_height` as a unit value (like "48px") when it should be a unitless float (like 1.3).

### Issue 1: Incorrect Pattern Matching
```php
// This matched "line_height" because it contains "height"
elseif (strpos($key, 'height') !== false) {
    if (!$this->sanitize_unit($value)) { // ❌ Expects string with unit
        // Error!
    }
}
```

### Issue 2: Strict Type Hints
```php
public function sanitize_unit(string $value) { // ❌ Only accepts string
    // But line_height sends float: 1.3
}
```

---

## Solution Applied

### Fix 1: Check line_height Before height

**File:** `woow-admin/includes/class-woow-settings.php`

```php
// Check line_height first (unitless float)
if (strpos($key, 'line_height') !== false) {
    if (!is_numeric($value) || $value < 1.0 || $value > 3.0) {
        $is_valid = false;
        $error_message = "Line height must be between 1.0 and 3.0";
    }
}
// Then check height (unit value)
elseif (strpos($key, 'height') !== false || ...) {
    // Convert to string if numeric
    $value_str = is_numeric($value) ? (string)$value : $value;
    if (!$this->sanitize_unit($value_str)) {
        $is_valid = false;
        $error_message = "Invalid unit format";
    }
}
```

### Fix 2: Accept Mixed Types in sanitize_unit()

```php
/**
 * Sanitize unit value (px, rem, em, %)
 *
 * @param mixed $value Unit value (string or numeric)
 * @return string|false Sanitized unit or false if invalid
 */
public function sanitize_unit($value) {
    // Convert to string if numeric
    if (is_numeric($value)) {
        $value = (string)$value;
    }
    
    if (!is_string($value)) {
        return false;
    }
    
    $value = trim($value);

    // Validate unit format
    if (preg_match('/^-?\d+(\.\d+)?(px|rem|em|%|vh|vw)$/', $value)) {
        return $value;
    }

    return false;
}
```

### Fix 3: Accept Mixed Types in sanitize_color()

```php
/**
 * Sanitize color value
 *
 * @param mixed $color Color value (string expected)
 * @return string|false Sanitized color or false if invalid
 */
public function sanitize_color($color) {
    // Must be a string
    if (!is_string($color)) {
        return false;
    }
    
    $color = trim($color);
    // ... validation ...
}
```

---

## Key Changes

1. ✅ Added specific check for `line_height` before generic `height` check
2. ✅ Validate line_height as unitless float (1.0 - 3.0 range)
3. ✅ Convert numeric values to strings before calling `sanitize_unit()`
4. ✅ Changed type hints from `string` to `mixed` for flexibility
5. ✅ Added type checking inside methods for safety

---

## Testing

### Test 1: Save with line_height
```javascript
// In browser console after making a change:
// Should now save successfully without TypeError
```

**Expected Result:**
- No PHP Fatal Error
- Settings save successfully
- Line height values preserved as floats

### Test 2: Check Debug Log
```bash
tail -f wp-content/debug.log
```

**Expected:**
```
[WOOW Admin] ajax_save_settings called
[WOOW Admin] Settings received: Array (...)
[WOOW Admin] Settings saved successfully
[WOOW Admin] Sending success response
```

**NOT Expected:**
```
PHP Fatal error: Uncaught TypeError: WOOW_Settings::sanitize_unit()
```

---

## Field Type Validation Matrix

| Field Pattern | Type | Validation | Example |
|--------------|------|------------|---------|
| `line_height` | float | 1.0 - 3.0 | `1.3` |
| `opacity` | float | 0.0 - 1.0 | `0.9` |
| `*color*`, `*_bg`, `*_text` | string | #hex or rgba() | `#6366f1` |
| `height`, `width`, `size`, etc. | string | number + unit | `48px` |
| `enabled`, `glassmorphism` | boolean | true/false | `true` |

---

## Files Modified

- `woow-admin/includes/class-woow-settings.php`
  - Updated `validate_settings()` method
  - Updated `sanitize_unit()` method signature
  - Updated `sanitize_color()` method signature

---

## Success Criteria

✅ **No TypeError when saving settings**
✅ **Line height values validate correctly**
✅ **Unit values still validate correctly**
✅ **Color values still validate correctly**
✅ **Settings save successfully**

---

## Related Issues

- Original issue: AJAX 500 Error (fixed in previous commit)
- This issue: Type mismatch in validation (fixed in this commit)

---

## Conclusion

The type mismatch has been resolved by:
1. Checking for `line_height` specifically before generic `height` pattern
2. Accepting mixed types in sanitization methods
3. Converting numeric values to strings when needed

Settings should now save successfully without type errors.
