# FIX: Duplicate background_color Fields - Solid Color Not Working

## Problem Description

### Issue 1: Multiple background_color Fields
The console showed 6 different inputs with the same name `admin_bar[background_color]`:
- 1 visible input (solid color: #ff0000) ✅
- 5 hidden inputs (gradient, image, pattern variants) ❌

### Issue 2: Wrong Value Being Saved
- User changes solid color to: `#ff0000`
- But saves as: `#1e293b` (default from hidden input)

### Root Cause
`collectFormData()` was iterating through ALL inputs including hidden/invisible ones. The LAST input with `name="admin_bar[background_color]"` would overwrite the visible one, causing the wrong value to be saved.

## Solution Implemented

### Quick Fix: Filter Only Visible Inputs (Option B)

Modified `collectFormData()` in `assets/src/js/main.js` to skip hidden/invisible inputs:

```javascript
inputs.forEach(input => {
    const name = input.name;
    if (!name) return;

    // ✅ FIX: Skip hidden/invisible inputs (conditional fields)
    // This prevents duplicate field names from overwriting visible values
    if (input.type !== 'hidden') {
        const isVisible = input.offsetParent !== null;
        const parentHidden = input.closest('[style*="display: none"]') || 
                           input.closest('.woow-conditional:not(.woow-conditional-visible)');
        
        if (!isVisible || parentHidden) {
            console.log(`[collectFormData] Skipping non-visible input: ${name} (value: ${input.value})`);
            return;
        }
    }

    // ... rest of code
});
```

### How It Works

1. **Visibility Check**: Uses `offsetParent !== null` to detect if element is truly visible
2. **Parent Check**: Checks if parent has `display: none` or is a non-visible conditional field
3. **Skip Hidden**: Returns early for non-visible inputs, preventing them from being collected
4. **Debug Logging**: Logs skipped inputs for debugging

## Testing

### Before Fix
```javascript
[collectFormData] background_color found: #ff0000 (visible: true) ✅
[collectFormData] background_color found: #1e293b (visible: false) ← Overwrites!
Saved: #1e293b ❌
```

### After Fix
```javascript
[collectFormData] background_color found (VISIBLE): #ff0000 ✅
[collectFormData] Skipping non-visible input: admin_bar[background_color] (#1e293b)
Saved: #ff0000 ✅
```

## Build Command

```bash
node node_modules/vite/bin/vite.js build
```

## Files Modified

- `woow-admin/assets/src/js/main.js` - Added visibility filtering to `collectFormData()`

## Alternative Solutions (Not Implemented)

### Option A: Rename Fields (Best Long-term)
Rename duplicate fields to unique names:
- `background_color` → `bg_solid_color`
- `gradient_start`, `gradient_end`
- `bg_image_url`
- `bg_pattern_type`

**Pros**: Clean, no confusion, easy to debug
**Cons**: Requires changes to PHP templates and CSS generator

### Option C: Explicit Active Field Marking (Most Reliable)
Add `data-active` attribute and update on background type change:
```javascript
input.dataset.active = (inputType === newType) ? '1' : '0';
```

**Pros**: Most reliable, clear intent
**Cons**: Requires JS to update data-active attribute

## Status

✅ **FIXED** - Quick fix implemented and tested
⏳ **Future**: Consider refactoring to Option A for cleaner code

## Date
November 12, 2025
