# Conditional Fields - Quick Reference

## How to Use Conditional Fields in Menu Tab

### 1. Show/Hide Based on Select/Checkbox Value

**HTML:**
```php
<!-- Control field (select or checkbox) -->
<select name="admin_menu[background_type]" class="woow-select">
    <option value="solid">Solid Color</option>
    <option value="gradient">Gradient</option>
    <option value="glass">Glassmorphism</option>
</select>

<!-- Conditional field - shows when background_type = solid -->
<div class="woow-conditional" data-show-when="background_type=solid">
    <input type="color" name="admin_menu[background_color]" />
</div>

<!-- Conditional field - shows when background_type = gradient -->
<div class="woow-conditional" data-show-when="background_type=gradient">
    <input type="color" name="admin_menu[gradient_start]" />
</div>
```

**How it works:**
- The component searches for a field named `background_type`
- When the field value changes, it shows/hides matching conditional fields
- Multiple fields can use the same condition

### 2. Show/Hide Based on Radio Button Selection

**HTML:**
```php
<!-- Radio button group (triggers) -->
<div class="woow-radio-group">
    <label>
        <input 
            type="radio" 
            name="admin_menu[border_radius_mode]" 
            value="all"
            class="woow-condition-trigger"
            data-target="border_radius_mode"
        />
        <span>All Corners</span>
    </label>
    <label>
        <input 
            type="radio" 
            name="admin_menu[border_radius_mode]" 
            value="individual"
            class="woow-condition-trigger"
            data-target="border_radius_mode"
        />
        <span>Individual Corners</span>
    </label>
</div>

<!-- Conditional field - shows when border_radius_mode = all -->
<div class="woow-conditional-field" data-condition="border_radius_mode" data-value="all">
    <input type="range" name="admin_menu[border_radius_all]" />
</div>

<!-- Conditional field - shows when border_radius_mode = individual -->
<div class="woow-conditional-field" data-condition="border_radius_mode" data-value="individual">
    <input type="range" name="admin_menu[border_radius_top_left]" />
</div>
```

**How it works:**
- Radio buttons with `woow-condition-trigger` class trigger conditionals
- `data-target` attribute specifies which condition to trigger
- Conditional fields use `data-condition` and `data-value` attributes
- Only one value can be shown at a time per condition

### 3. Show/Hide Based on Checkbox State

**HTML:**
```php
<!-- Checkbox control -->
<input 
    type="checkbox" 
    name="admin_menu[submenu_inherit_styles]" 
    value="1"
/>

<!-- Conditional field - shows when checkbox is UNCHECKED (value = 0) -->
<div class="woow-conditional" data-show-when="submenu_inherit_styles=0">
    <!-- Custom submenu fields -->
</div>
```

**How it works:**
- Checkboxes use value `1` when checked, `0` when unchecked
- Use `data-show-when="field_name=0"` to show when unchecked
- Use `data-show-when="field_name=1"` to show when checked

## Field Search Logic

The component searches for control fields in this order:

1. **Active tab first** - Prioritizes fields in the currently visible tab
2. **Entire document** - Falls back to searching the whole page

For each location, it tries these patterns:

1. `[name*="[fieldName]"]` - Matches `admin_menu[fieldName]`
2. `[name$="[fieldName]"]` - Ends with `[fieldName]`
3. `[name="fieldName"]` - Exact match
4. `#fieldName` - ID selector

## CSS Classes

| Class | Purpose |
|-------|---------|
| `.woow-conditional` | Container for show-when fields |
| `.woow-conditional-field` | Container for condition-triggered fields |
| `.woow-hidden` | Applied when field is hidden |
| `.woow-condition-trigger` | Applied to radio buttons that trigger conditionals |

## Data Attributes

| Attribute | Used On | Purpose |
|-----------|---------|---------|
| `data-show-when` | `.woow-conditional` | Specifies condition: `fieldName=value` |
| `data-target` | `.woow-condition-trigger` | Specifies which condition to trigger |
| `data-condition` | `.woow-conditional-field` | Specifies which condition to listen for |
| `data-value` | `.woow-conditional-field` | Specifies which value to show for |

## Examples from Menu Tab

### Background Type Conditionals
```php
<!-- Control -->
<select name="admin_menu[background_type]">
    <option value="solid">Solid Color</option>
    <option value="gradient">Gradient</option>
    <option value="glass">Glassmorphism</option>
</select>

<!-- Conditional fields -->
<div class="woow-conditional" data-show-when="background_type=solid">
    <!-- Background Color field -->
</div>
<div class="woow-conditional" data-show-when="background_type=gradient">
    <!-- Gradient Start/End fields -->
</div>
<div class="woow-conditional" data-show-when="background_type=glass">
    <!-- Base Color, Opacity, Blur Strength fields -->
</div>
```

### Border Radius Mode Conditionals
```php
<!-- Control -->
<input type="radio" name="admin_menu[border_radius_mode]" value="all" 
       class="woow-condition-trigger" data-target="border_radius_mode" />
<input type="radio" name="admin_menu[border_radius_mode]" value="individual" 
       class="woow-condition-trigger" data-target="border_radius_mode" />

<!-- Conditional fields -->
<div class="woow-conditional-field" data-condition="border_radius_mode" data-value="all">
    <!-- All Corners slider -->
    <!-- Menu Item Border Radius slider -->
</div>
<div class="woow-conditional-field" data-condition="border_radius_mode" data-value="individual">
    <!-- Individual corner sliders -->
</div>
```

### Submenu Inherit Styles Conditional
```php
<!-- Control -->
<input type="checkbox" name="admin_menu[submenu_inherit_styles]" value="1" />

<!-- Conditional field - shows when NOT inherited -->
<div class="woow-conditional" data-show-when="submenu_inherit_styles=0">
    <!-- Custom submenu color/size fields -->
</div>
```

## Debugging

### Enable Debug Logging
Open browser console and look for `[ConditionalFields]` messages:

```
[ConditionalFields] Initialized
[ConditionalFields] Found 16 conditional fields
[ConditionalFields] Processing: background_type = solid
[ConditionalFields] Found control field for background_type: admin_menu[background_type] = solid
```

### Common Issues

**"Control field not found"**
- Check field name matches exactly
- Ensure field is in the same tab or document
- Try using full name with section: `admin_menu[fieldName]`

**Fields not showing/hiding**
- Check `data-show-when` or `data-condition` attribute values
- Verify field name in attribute matches actual field name
- Check browser console for errors

**Radio buttons not working**
- Ensure all radio buttons have `class="woow-condition-trigger"`
- Ensure all have `data-target="fieldName"` with same value
- Verify conditional fields have matching `data-condition` value

## Performance Notes

- Minimal DOM queries - only on initialization
- Event listeners only on control fields
- CSS display property for showing/hiding (no reflows)
- 100ms initialization delay ensures DOM is ready
- No impact on form submission or validation

---

**Last Updated:** November 14, 2025
