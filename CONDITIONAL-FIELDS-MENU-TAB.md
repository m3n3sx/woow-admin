# Menu Styling Tab - Conditional Fields Implementation

## Overview
Implemented comprehensive conditional field logic for the Menu Styling tab to improve UX by showing/hiding fields based on user selections.

## Changes Made

### 1. Background Type Conditionals
**File:** `includes/templates/tabs/menu-tab.php`

When user selects "Background Type":
- **Solid Color**: Shows only "Background Color" + "Text Color"
- **Gradient**: Shows "Gradient Start Color", "Gradient End Color" + "Text Color"
- **Glassmorphism**: Shows "Base Color", "Opacity", "Blur Strength" + "Text Color"

**Implementation:**
```php
<!-- Solid Color Option -->
<div class="woow-form-group woow-conditional" data-show-when="background_type=solid">

<!-- Gradient Options -->
<div class="woow-form-group woow-conditional" data-show-when="background_type=gradient">

<!-- Glassmorphism Options -->
<div class="woow-form-group woow-conditional" data-show-when="background_type=glass">
```

### 2. Active Item Background Type Conditionals
When user selects "Active Item Background Type":
- **Solid**: Shows only "Active Background Color" + "Active Text Color"
- **Gradient**: Shows "Active Gradient Start", "Active Gradient End" + "Active Text Color"

**Implementation:**
```php
<!-- Solid Color Option - Only visible when solid -->
<div class="woow-form-group woow-conditional" data-show-when="active_bg_type=solid">

<!-- Gradient Options - Only visible when gradient -->
<div class="woow-form-row woow-conditional" data-show-when="active_bg_type=gradient">
```

### 3. Submenu Inherit Styles Conditional
When "Inherit styles from main menu" is enabled:
- Hides all custom submenu styling options
- Shows only "Distance from Menu" (always visible)

**Implementation:**
```php
<!-- Custom Submenu Styles - Hidden when inherit is enabled -->
<div class="woow-conditional" data-show-when="submenu_inherit_styles=0">
    <!-- All submenu color, size, and styling fields -->
</div>
```

### 4. Border Radius Mode - Radio Buttons
**Changed from:** Dropdown select
**Changed to:** Radio button group

**Implementation:**
```php
<div class="woow-radio-group">
    <label class="woow-radio-label">
        <input type="radio" name="admin_menu[border_radius_mode]" value="all" 
               class="woow-condition-trigger" data-target="border_radius_mode" />
        <span>All Corners (Uniform)</span>
    </label>
    <label class="woow-radio-label">
        <input type="radio" name="admin_menu[border_radius_mode]" value="individual" 
               class="woow-condition-trigger" data-target="border_radius_mode" />
        <span>Individual Corners</span>
    </label>
</div>
```

When "All Corners" is selected:
- Shows "Border Radius (All Corners)" slider
- Shows "Menu Item Border Radius" slider
- Hides individual corner sliders

When "Individual Corners" is selected:
- Hides "Border Radius (All Corners)" slider
- Hides "Menu Item Border Radius" slider
- Shows individual corner sliders (Top Left, Top Right, Bottom Right, Bottom Left)

**Implementation:**
```php
<!-- All Corners (Uniform) -->
<div class="woow-form-group woow-conditional-field" data-condition="border_radius_mode" data-value="all">

<!-- Individual Corners -->
<div class="woow-conditional-field" data-condition="border_radius_mode" data-value="individual">

<!-- Item Border Radius - Visible only when All Corners mode -->
<div class="woow-form-group woow-conditional-field" data-condition="border_radius_mode" data-value="all">
```

### 5. Spacing (Padding) Mode Conditionals
When "Spacing Mode" is selected:
- **All Sides**: Shows only "Padding (All Sides)" slider
- **Individual Sides**: Shows individual sliders (Top, Right, Bottom, Left)

**Implementation:**
```php
<!-- All Sides (Uniform) - Only visible when "All Sides" mode -->
<div class="woow-form-group woow-conditional-field" data-condition="spacing_mode" data-value="all">

<!-- Individual Sides - Only visible when "Individual Sides" mode -->
<div class="woow-conditional-field" data-condition="spacing_mode" data-value="individual">
```

### 6. Margin Mode Conditionals
When "Margin Mode" is selected:
- **All Sides**: Shows only "Margin (All Sides)" slider
- **Individual Sides**: Shows individual sliders (Top, Right, Bottom, Left)

**Implementation:**
```php
<!-- All Sides (Uniform) - Only visible when "All Sides" mode -->
<div class="woow-form-group woow-conditional-field" data-condition="margin_mode" data-value="all">

<!-- Individual Sides - Only visible when "Individual Sides" mode -->
<div class="woow-conditional-field" data-condition="margin_mode" data-value="individual">
```

## Technical Details

### Conditional Field Attributes

**For Select/Checkbox Controls:**
```html
<div class="woow-conditional" data-show-when="field_name=value">
```
- Used for select dropdowns and checkboxes
- Hides/shows based on exact value match
- Automatically handles change events

**For Radio Button Controls:**
```html
<input class="woow-condition-trigger" data-target="field_name" />
<div class="woow-conditional-field" data-condition="field_name" data-value="value">
```
- Used for radio button groups
- Hides/shows based on selected radio value
- Multiple fields can be controlled by same radio group

### JavaScript Implementation
The conditional logic is handled by `ConditionalFields` class in `assets/src/js/components/ConditionalFields.js`:

1. **initShowWhenFields()** - Handles `data-show-when` attributes
   - Searches for control fields by name
   - Tries both prefixed (`admin_menu[fieldName]`) and unprefixed (`fieldName`) patterns
   - Sets up change event listeners for dynamic updates

2. **initConditionTriggers()** - Handles `data-condition-trigger` attributes
   - Finds all radio button triggers
   - Updates conditional fields based on selected value
   - Handles initial state on page load

3. **updateShowWhenField()** - Updates visibility for show-when fields
   - Compares current field value with expected value
   - Shows/hides field using CSS display property
   - Handles both checkbox and select inputs

4. **updateConditionalFields()** - Updates visibility for condition-triggered fields
   - Updates all fields with matching condition
   - Shows/hides based on trigger value match
   - Applies/removes CSS classes for styling

5. **findControlField()** - Robust field search
   - Searches in active tab first, then entire document
   - Tries multiple selector patterns for flexibility
   - Handles both prefixed and unprefixed field names
   - Logs search results for debugging

### CSS Classes
- `.woow-conditional` - Container for show-when fields
- `.woow-conditional-field` - Container for condition-triggered fields
- `.woow-hidden` - Applied when field should be hidden
- `.woow-condition-trigger` - Applied to radio buttons that trigger conditionals

## Testing Checklist

- [x] Background Type conditionals work (Solid, Gradient, Glassmorphism)
- [x] Active Item Background Type conditionals work (Solid, Gradient)
- [x] Submenu inherit styles hides custom fields
- [x] Border Radius Mode radio buttons display correctly
- [x] Border Radius individual/all corners toggle works
- [x] Spacing Mode individual/all sides toggle works
- [x] Margin Mode individual/all sides toggle works
- [x] Build completes without errors
- [x] ConditionalFields component finds fields correctly
- [x] Field search handles both prefixed and unprefixed names
- [x] Initialization delay ensures DOM is ready

## Browser Compatibility
- Chrome/Edge: ✓
- Firefox: ✓
- Safari: ✓
- Mobile browsers: ✓

## Performance Impact
- Minimal - uses CSS display property for hiding/showing
- No additional API calls
- Event listeners only on control fields
- Efficient DOM queries with specific selectors

## Improvements Made to ConditionalFields Component

### 1. Enhanced Field Search
- Added fallback search with section prefix (`admin_menu[fieldName]`)
- Searches in active tab first, then entire document
- Multiple selector patterns for maximum compatibility
- Better logging for debugging

### 2. Initialization Timing
- Added 100ms delay to ensure DOM is fully ready
- Prevents race conditions with dynamic content
- Ensures all fields are available when searching

### 3. Better Error Handling
- Graceful fallback when field not found
- Detailed console logging for debugging
- Doesn't break if some fields are missing

## Future Enhancements
- Add smooth transitions when showing/hiding fields
- Add field validation based on visibility state
- Add keyboard navigation for radio groups
- Add accessibility improvements (ARIA labels)
- Add support for nested conditionals
- Add support for multiple condition triggers

---

**Last Updated:** November 14, 2025
**Status:** Complete and tested
**Build Status:** ✓ Successful
