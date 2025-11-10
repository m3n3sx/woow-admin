# AJAX Save Settings Fix Applied

## Problem
The save settings functionality was failing with a 500 Internal Server Error because:

1. **Missing Method**: The `WOOW_Admin::ajax_save_settings()` method was calling `$this->settings->save_settings($settings)` 
2. **Wrong Signature**: The `WOOW_Settings` class only had a private `save_settings()` method with no parameters
3. **Fatal Error**: This caused a PHP fatal error when trying to save, returning HTML error page instead of JSON

## Solution Applied

### 1. Renamed Private Method
Changed the private helper method from `save_settings()` to `persist_settings()` to avoid naming conflict:

```php
private function persist_settings(): bool {
    return update_option( self::OPTION_NAME, $this->settings );
}
```

### 2. Added Public Method
Created a new public `save_settings()` method that accepts settings array:

```php
public function save_settings( array $settings ): bool {
    // Merge with existing settings to preserve structure
    $this->settings = array_replace_recursive( $this->settings, $settings );
    
    // Save to database
    return update_option( self::OPTION_NAME, $this->settings );
}
```

### 3. Updated All Internal Calls
Updated all internal calls from `$this->save_settings()` to `$this->persist_settings()`:
- `update_section()` method
- `apply_palette()` method  
- `apply_template()` method
- `import_settings()` method
- `update_all_settings()` method

## Files Modified
- `woow-admin/includes/class-woow-settings.php`

## Testing Required
1. Open the WOOW! Admin settings page
2. Make any change to a setting
3. Click "Save Changes" button
4. Verify:
   - No console errors
   - Success notification appears
   - Settings are persisted after page reload

## Expected Behavior
- AJAX request to `/wp-admin/admin-ajax.php` should return JSON with `success: true`
- No more 500 errors
- Settings should save correctly to the database
