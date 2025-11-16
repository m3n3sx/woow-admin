# JSON Response Error Fix

## Issue
JavaScript error when applying palettes:
```
SyntaxError: Unexpected token '<', "<br /><fo"... is not valid JSON
```

## Root Cause
PHP errors/warnings were being output before the JSON response in AJAX handlers, causing HTML (`<br />` tags) to be included in the response, which breaks JSON parsing.

## Solution Applied

### 1. Clean Output Buffer in AJAX Handlers
Added output buffer cleaning at the start of AJAX handlers to prevent any stray output:

**File:** `includes/class-woow-admin.php`

```php
public function ajax_apply_palette(): void {
    // Clean output buffer to prevent any stray output
    if ( ob_get_level() > 0 ) {
        ob_clean();
    }
    
    try {
        // ... rest of the method
    }
}

public function ajax_apply_template(): void {
    // Clean output buffer to prevent any stray output
    if ( ob_get_level() > 0 ) {
        ob_clean();
    }
    
    try {
        // ... rest of the method
    }
}
```

### 2. Better Error Handling in JavaScript
Added better error handling and logging in the JavaScript to help diagnose JSON parsing issues:

**File:** `assets/src/js/main.js`

```javascript
async applyPalette(paletteId) {
    try {
        // ... prepare request ...

        const response = await fetch(this.ajaxUrl, {
            method: 'POST',
            body: data
        });

        // Check if response is OK
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        // Try to parse JSON with error handling
        let result;
        try {
            result = await response.json();
        } catch (parseError) {
            console.error('[PaletteSelector] JSON parse error:', parseError);
            const text = await response.text();
            console.error('[PaletteSelector] Response text:', text.substring(0, 500));
            throw new Error('Invalid JSON response from server. Check console for details.');
        }

        // ... rest of the method ...
    } catch (error) {
        console.error('[PaletteSelector] Error applying palette:', error);
        this.showNotification(
            this.i18n.networkError || 'Network error. Please try again.',
            'error'
        );
        return false;
    }
}
```

## Additional Recommendations

### 1. Check PHP Error Reporting
Ensure PHP errors are logged but not displayed:

```php
// In wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors', 0);
```

### 2. Check for Plugin Conflicts
Some plugins may output content during AJAX requests. To test:
1. Disable all other plugins
2. Test palette application
3. Re-enable plugins one by one to identify conflicts

### 3. Check Theme Functions
Ensure your theme's `functions.php` doesn't output anything during AJAX requests.

### 4. Check Server Configuration
Some server configurations may add output (like PHP notices/warnings). Check:
- PHP error_reporting level
- Output buffering settings
- Any server-level error handlers

## Testing

After applying this fix:

1. **Clear browser cache** (Ctrl+Shift+R or Cmd+Shift+R)
2. **Clear WordPress cache** if using a caching plugin
3. **Test palette application:**
   - Go to WOOW! Admin → Palettes
   - Click "Apply" on any palette
   - Check browser console for errors
   - Verify palette applies successfully

4. **Check error logs:**
   ```bash
   tail -f wp-content/debug.log
   ```

## Status

✅ **Fixed** - Output buffer cleaning added to AJAX handlers  
✅ **Enhanced** - Better error handling and logging in JavaScript  
✅ **Built** - JavaScript rebuilt with changes  

## Files Modified

1. `includes/class-woow-admin.php` - Added output buffer cleaning
2. `assets/src/js/main.js` - Enhanced error handling
3. `assets/dist/main.js` - Rebuilt JavaScript

## Next Steps

If the issue persists after this fix:

1. Check the browser console for the full error message
2. Check `wp-content/debug.log` for PHP errors
3. Temporarily disable other plugins to check for conflicts
4. Verify WordPress and PHP versions are compatible

---

**Fix Applied:** November 16, 2024  
**Status:** Ready for testing
