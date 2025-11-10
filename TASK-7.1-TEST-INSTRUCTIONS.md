# Task 7.1: Color Input Defaults - Test Instructions

## Overview
This document provides step-by-step instructions for testing that all color inputs have default values and display correctly without validation errors.

## Test Environment
- **Browser:** Chrome, Firefox, or Safari (latest version)
- **WordPress:** Admin dashboard with WOOW Admin plugin activated
- **Console:** Browser Developer Tools console open (F12)

## Pre-Test Checklist
- [ ] WOOW Admin plugin is activated
- [ ] Browser developer tools are open (F12)
- [ ] Console tab is visible
- [ ] No existing console errors before starting

---

## Test 1: Admin Bar Tab Color Inputs

### Steps:
1. Navigate to **WordPress Admin → WOOW Admin**
2. Click on the **Admin Bar** tab
3. Scroll through the entire tab
4. Inspect each color input field

### Expected Results:
✅ **All color inputs should display colors (not empty)**

| Field Name | Expected Default | Status |
|------------|------------------|--------|
| Background Color | #1e293b (dark slate) | ⬜ |
| Gradient Start | #1e293b (dark slate) | ⬜ |
| Gradient End | #0f172a (darker slate) | ⬜ |
| Text Color | #ffffff (white) | ⬜ |
| Hover Background | #ffffff (white) | ⬜ |
| Hover Text Color | #ffffff (white) | ⬜ |

### Validation:
- [ ] All color inputs show a color preview (colored square)
- [ ] No color inputs are empty or show default browser gray
- [ ] Each input has a corresponding text field showing the hex value
- [ ] Text fields display valid hex codes (e.g., #1e293b)
- [ ] No console errors appear

### Console Check:
```
Expected: No errors
Look for: "does not conform to #rrggbb" ❌ (should NOT appear)
```

---

## Test 2: Admin Menu Tab Color Inputs

### Steps:
1. Click on the **Admin Menu** tab (or **Menu** tab)
2. Scroll through the entire tab
3. Inspect each color input field

### Expected Results:
✅ **All color inputs should display colors (not empty)**

| Field Name | Expected Default | Status |
|------------|------------------|--------|
| Background Color | rgba(255,255,255,0.9) → #ffffff | ⬜ |
| Text Color | #0f172a (dark slate) | ⬜ |
| Hover Background | rgba(99,102,241,0.05) → #6366f1 | ⬜ |
| Active Gradient Start | #6366f1 (indigo) | ⬜ |
| Active Gradient End | #8b5cf6 (purple) | ⬜ |

### Validation:
- [ ] All color inputs show a color preview
- [ ] Background color shows white (#ffffff)
- [ ] Text color shows dark slate (#0f172a)
- [ ] Gradient colors show indigo and purple
- [ ] No console errors appear

### Console Check:
```
Expected: No errors
Look for: "does not conform to #rrggbb" ❌ (should NOT appear)
```

---

## Test 3: Buttons Tab Color Inputs

### Steps:
1. Click on the **Buttons** tab (or **Universal Buttons** tab)
2. Scroll through the entire tab
3. Inspect each color input field

### Expected Results:
✅ **All color inputs should display colors (not empty)**

| Field Name | Expected Default | Status |
|------------|------------------|--------|
| Primary Background | #6366f1 (indigo) | ⬜ |
| Primary Text | #ffffff (white) | ⬜ |
| Secondary Border | #e2e8f0 (light gray) | ⬜ |
| Secondary Text | #6366f1 (indigo) | ⬜ |
| Destructive Background | #ef4444 (red) | ⬜ |
| Destructive Text | #ffffff (white) | ⬜ |

### Validation:
- [ ] All color inputs show a color preview
- [ ] Primary button colors show indigo and white
- [ ] Secondary button colors show light gray and indigo
- [ ] Destructive button colors show red and white
- [ ] No console errors appear

### Console Check:
```
Expected: No errors
Look for: "does not conform to #rrggbb" ❌ (should NOT appear)
```

---

## Test 4: Browser Console Error Check

### Steps:
1. Open Browser Developer Tools (F12)
2. Go to the **Console** tab
3. Clear the console (click trash icon or press Ctrl+L)
4. Navigate through all three tabs (Admin Bar, Menu, Buttons)
5. Check for any errors

### Expected Results:
✅ **No validation errors in console**

### Common Errors to Look For:
❌ **Should NOT appear:**
- "does not conform to #rrggbb"
- "Invalid color format"
- "Color input validation failed"
- "Uncaught TypeError"
- "Cannot read property 'value' of null"

✅ **Acceptable messages:**
- "[WOOW] Settings loaded"
- "[WOOW] Preview initialized"
- "[WOOW] Tab switched to: admin_bar"

### Console Screenshot:
Take a screenshot of the console showing no errors.

---

## Test 5: HTML Attribute Verification

### Steps:
1. Right-click on any color input
2. Select **Inspect** or **Inspect Element**
3. Check the HTML attributes

### Expected HTML Structure:
```html
<input 
    type="color" 
    name="admin_bar[background_color]" 
    value="#1e293b"
    data-default="#1e293b"
    class="woow-color-input"
/>
```

### Validation Checklist:
- [ ] `type="color"` attribute present
- [ ] `value` attribute present with valid hex color
- [ ] `data-default` attribute present with valid hex color
- [ ] `value` and `data-default` match (for fresh install)
- [ ] No empty `value=""` attributes

---

## Test 6: Color Picker Functionality

### Steps:
1. Click on any color input
2. Browser color picker should open
3. Select a different color
4. Verify the change is reflected

### Expected Results:
- [ ] Color picker opens when clicking input
- [ ] Selected color updates the input value
- [ ] Text field next to color input updates with new hex value
- [ ] No errors in console when changing colors

---

## Test 7: Reset Button Functionality

### Steps:
1. Change a color input to a different value
2. Click the reset button (↺) next to the color input
3. Verify color returns to default

### Expected Results:
- [ ] Reset button is visible next to each color input
- [ ] Clicking reset restores the `data-default` value
- [ ] Color input updates visually
- [ ] Text field updates with default hex value

---

## Test Results Summary

### Overall Status:
- [ ] ✅ All Admin Bar color inputs have default values
- [ ] ✅ All Admin Menu color inputs have default values
- [ ] ✅ All Buttons color inputs have default values
- [ ] ✅ No console errors detected
- [ ] ✅ All HTML attributes are correct
- [ ] ✅ Color picker functionality works
- [ ] ✅ Reset buttons work correctly

### Pass Criteria:
**Test PASSES if:**
- All color inputs display colors (not empty)
- No "does not conform to #rrggbb" errors in console
- All inputs have `value` and `data-default` attributes
- Color picker opens and works correctly

**Test FAILS if:**
- Any color input is empty on page load
- Console shows validation errors
- Missing `value` or `data-default` attributes
- Color picker doesn't open or work

---

## Troubleshooting

### Issue: Color inputs are empty
**Solution:** Check that PHP templates have:
```php
$defaults = array(
    'background_color' => '#1e293b',
    // ... other defaults
);
$admin_bar = array_merge($defaults, $this->settings->get_section('admin_bar') ?? array());
```

### Issue: Console shows "does not conform to #rrggbb"
**Solution:** Check that color inputs have valid hex values:
```php
value="<?php echo esc_attr(WOOW_Admin::rgba_to_hex($admin_bar['background_color'])); ?>"
```

### Issue: data-default attribute missing
**Solution:** Add to all color inputs:
```php
data-default="#1e293b"
```

---

## Automated Test

For automated testing, open the test file in a browser:
```
woow-admin/tests/manual/test-color-inputs.html
```

This will run all tests automatically and display results.

---

## Test Completion

**Tested By:** _________________  
**Date:** _________________  
**Browser:** _________________  
**WordPress Version:** _________________  
**Result:** ⬜ PASS  ⬜ FAIL  

**Notes:**
_________________________________________________________________
_________________________________________________________________
_________________________________________________________________

**Screenshots Attached:** ⬜ Yes  ⬜ No

---

## Requirements Verified

This test verifies the following requirements:
- ✅ Requirement 1.1: Color inputs populated with current or default values
- ✅ Requirement 1.2: Value attribute added to all color inputs
- ✅ Requirement 1.3: Data-default attribute added for reset functionality
- ✅ Requirement 1.4: Default values used when no saved value exists
- ✅ Requirement 1.5: Color values formatted as 6-character hex codes

**All requirements met:** ⬜ Yes  ⬜ No
