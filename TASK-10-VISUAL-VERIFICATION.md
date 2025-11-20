# Task 10: Visual Verification Guide

## How to Manually Verify Settings Persistence

This guide provides step-by-step instructions for manually verifying that typography font settings save and load correctly.

## Prerequisites

1. WordPress installation with WOOW! Admin plugin active
2. Admin access to WordPress dashboard
3. Browser with developer tools (for debugging if needed)

## Test 1: Body Font Persistence (Requirement 1.4)

### Steps:
1. Navigate to **WOOW! Admin → Typography Tab**
2. Locate the **Body Font** section
3. Click the **Font Family** dropdown
4. Select a Google Font (e.g., "Inter")
5. Check some font weights (e.g., 400, 600, 700)
6. Click **Save Settings** button
7. Wait for success message
8. **Refresh the page** (F5 or Ctrl+R)
9. Navigate back to **Typography Tab**

### Expected Result:
- ✅ Body Font dropdown shows "Inter"
- ✅ Weight checkboxes 400, 600, 700 are checked
- ✅ Other weights remain unchecked

### If Test Fails:
- Check browser console for JavaScript errors
- Check WordPress debug log for PHP errors
- Verify nonce is being sent with AJAX request
- Confirm user has `manage_options` capability

## Test 2: Heading Font Persistence (Requirement 2.5)

### Steps:
1. Navigate to **WOOW! Admin → Typography Tab**
2. Locate the **Heading Font** section
3. Click the **Font Family** dropdown
4. Select a different Google Font (e.g., "Playfair Display")
5. Check different weights (e.g., 600, 700, 800)
6. Click **Save Settings** button
7. Wait for success message
8. **Refresh the page**
9. Navigate back to **Typography Tab**

### Expected Result:
- ✅ Heading Font dropdown shows "Playfair Display"
- ✅ Weight checkboxes 600, 700, 800 are checked
- ✅ Body font remains "Inter" (from Test 1)
- ✅ Body weights remain 400, 600, 700

### If Test Fails:
- Verify both fonts are stored independently
- Check that heading font didn't overwrite body font
- Confirm validation accepts both fonts

## Test 3: Weight Array Persistence (Requirement 4.4)

### Steps:
1. Navigate to **WOOW! Admin → Typography Tab**
2. In **Body Font** section, select "Roboto"
3. Check **all available weights**: 300, 400, 500, 600, 700, 800, 900
4. Click **Save Settings**
5. **Refresh the page**
6. Navigate back to **Typography Tab**

### Expected Result:
- ✅ All 7 weight checkboxes are checked
- ✅ No weights are missing
- ✅ No extra weights are checked

### Additional Test:
1. Uncheck some weights (e.g., leave only 400, 700)
2. Save settings
3. Refresh page
4. **Expected:** Only 400 and 700 are checked

## Test 4: System Default Persistence

### Steps:
1. Navigate to **WOOW! Admin → Typography Tab**
2. In **Body Font** section, select "System Default"
3. In **Heading Font** section, select "System Default"
4. Click **Save Settings**
5. **Refresh the page**
6. Navigate back to **Typography Tab**

### Expected Result:
- ✅ Body Font shows "System Default"
- ✅ Heading Font shows "System Default"
- ✅ Default weights [400, 600, 700] are checked

## Test 5: Reset Functionality (Requirement 6.3)

### Steps:
1. Navigate to **WOOW! Admin → Typography Tab**
2. Set custom fonts:
   - Body Font: "Open Sans"
   - Heading Font: "Montserrat"
   - Custom weights for both
3. Click **Save Settings**
4. Verify fonts are saved (refresh and check)
5. Click **Reset Settings** button (in header)
6. Confirm reset in dialog
7. Wait for success message
8. Navigate back to **Typography Tab**

### Expected Result:
- ✅ Body Font reset to "System Default"
- ✅ Heading Font reset to "System Default"
- ✅ Weights reset to [400, 600, 700]
- ✅ All other typography settings reset to defaults

## Test 6: Settings Load on Tab Access (Requirement 6.5)

### Steps:
1. Navigate to **WOOW! Admin → Typography Tab**
2. Set specific configuration:
   - Body Font: "Lato"
   - Body Weights: 300, 400, 700
   - Heading Font: "Merriweather"
   - Heading Weights: 400, 700, 900
   - H1 Size: 36px
   - Body Size: 16px
3. Click **Save Settings**
4. Navigate to **different tab** (e.g., Admin Bar)
5. Navigate back to **Typography Tab**

### Expected Result:
- ✅ Body Font: "Lato"
- ✅ Body Weights: 300, 400, 700 checked
- ✅ Heading Font: "Merriweather"
- ✅ Heading Weights: 400, 700, 900 checked
- ✅ H1 Size: 36px
- ✅ Body Size: 16px
- ✅ All settings loaded correctly without page refresh

## Test 7: Invalid Data Handling

### Steps:
1. Open browser developer tools (F12)
2. Navigate to **WOOW! Admin → Typography Tab**
3. In console, try to save invalid data:
```javascript
// This should fail validation
jQuery.post(ajaxurl, {
    action: 'woow_save_settings',
    nonce: woowAdminData.nonce,
    settings: JSON.stringify({
        typography: {
            body_font: 'InvalidFont123',
            body_weights: [150, 250] // Invalid weights
        }
    })
});
```

### Expected Result:
- ✅ Validation error returned
- ✅ Settings NOT saved
- ✅ Previous valid settings retained
- ✅ Error message displayed

## Test 8: Concurrent Font Selection

### Steps:
1. Navigate to **WOOW! Admin → Typography Tab**
2. Set **same font** for both:
   - Body Font: "Roboto"
   - Heading Font: "Roboto"
3. Set **different weights**:
   - Body Weights: 400, 600
   - Heading Weights: 700, 900
4. Click **Save Settings**
5. **Refresh the page**
6. Navigate back to **Typography Tab**

### Expected Result:
- ✅ Both fonts show "Roboto"
- ✅ Body weights: 400, 600 checked
- ✅ Heading weights: 700, 900 checked
- ✅ Weights stored independently despite same font

## Test 9: Empty Weight Selection

### Steps:
1. Navigate to **WOOW! Admin → Typography Tab**
2. Select a Google Font (e.g., "Inter")
3. **Uncheck all weight checkboxes**
4. Click **Save Settings**
5. Check generated CSS (view page source or inspect element)

### Expected Result:
- ✅ Font URL includes default weight (400)
- ✅ No error occurs
- ✅ Font loads correctly with fallback weight

## Test 10: Database Verification

### Steps (for developers):
1. Access WordPress database (phpMyAdmin or similar)
2. Find `wp_options` table
3. Locate row where `option_name = 'woow_admin_settings'`
4. View `option_value` (serialized PHP array)
5. Look for `typography` section

### Expected Structure:
```php
array(
    'typography' => array(
        'body_font' => 'Inter',
        'heading_font' => 'Playfair Display',
        'body_weights' => array(400, 600, 700),
        'heading_weights' => array(600, 700, 800),
        // ... other typography settings
    ),
    // ... other sections
)
```

## Debugging Tips

### If Settings Don't Save:

1. **Check Browser Console:**
   - Look for JavaScript errors
   - Verify AJAX request is sent
   - Check response for errors

2. **Check WordPress Debug Log:**
   ```php
   // In wp-config.php
   define('WP_DEBUG', true);
   define('WP_DEBUG_LOG', true);
   ```
   - Check `wp-content/debug.log`
   - Look for validation errors
   - Check for database errors

3. **Verify Nonce:**
   - Ensure nonce is included in AJAX request
   - Check nonce is valid (not expired)
   - Verify nonce action matches

4. **Check Permissions:**
   - User must have `manage_options` capability
   - Verify current user can access settings

### If Settings Don't Load:

1. **Check Database:**
   - Verify option exists in `wp_options`
   - Check option value is valid serialized array
   - Ensure typography section exists

2. **Check Defaults:**
   - Verify `includes/defaults.php` has typography section
   - Check default values are correct
   - Ensure merge logic works

3. **Check Template:**
   - Verify `typography-tab.php` calls `get_section('typography')`
   - Check form fields use correct names
   - Ensure values are properly escaped

## Success Criteria

All tests should pass with these results:
- ✅ Body font saves and loads correctly
- ✅ Heading font saves and loads independently
- ✅ Weight arrays persist accurately
- ✅ System default persists correctly
- ✅ Reset returns to defaults
- ✅ Settings load on tab access
- ✅ Invalid data is rejected
- ✅ Same font for body/heading works
- ✅ Empty weights handled gracefully
- ✅ Database structure is correct

## Conclusion

If all tests pass, Task 10 (Settings Persistence) is verified as working correctly. The save/load mechanism properly handles typography font settings for all requirements:
- 1.4: Body font persistence ✅
- 2.5: Heading font persistence ✅
- 4.4: Weight selection persistence ✅
- 6.3: Reset functionality ✅
- 6.5: Settings load on tab access ✅
