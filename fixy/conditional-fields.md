# Conditional Fields Implementation

## Overview
Conditional fields allow showing/hiding form fields based on the value of another field (controller field).

## Usage in HTML

### Basic Syntax
```php
<!-- Controller Field -->
<select name="admin_bar[background_type]" class="woow-select">
    <option value="solid">Solid Color</option>
    <option value="gradient">Gradient</option>
    <option value="glass">Glassmorphism</option>
</select>

<!-- Conditional Field - Shows only when background_type = "solid" -->
<div class="woow-form-group woow-conditional" data-show-when="background_type=solid">
    <label>Background Color</label>
    <input type="color" name="admin_bar[background_color]" />
</div>

<!-- Conditional Field - Shows only when background_type = "gradient" -->
<div class="woow-form-group woow-conditional" data-show-when="background_type=gradient">
    <label>Gradient Start</label>
    <input type="color" name="admin_bar[gradient_start]" />
</div>

<div class="woow-form-group woow-conditional" data-show-when="background_type=gradient">
    <label>Gradient End</label>
    <input type="color" name="admin_bar[gradient_end]" />
</div>
```

### Attributes
- `class="woow-conditional"` - Required class to mark field as conditional
- `data-show-when="field_name=expected_value"` - Condition to show the field

### Condition Format
```
data-show-when="field_name=expected_value"
```

Where:
- `field_name` - Name of the controller field (without section prefix)
- `expected_value` - Value that triggers visibility

## Supported Controller Field Types

### Select Dropdown
```php
<select name="section[field_name]">
    <option value="option1">Option 1</option>
    <option value="option2">Option 2</option>
</select>

<!-- Shows when option1 is selected -->
<div class="woow-conditional" data-show-when="field_name=option1">
    ...
</div>
```

### Radio Buttons
```php
<input type="radio" name="section[field_name]" value="yes" />
<input type="radio" name="section[field_name]" value="no" />

<!-- Shows when "yes" is selected -->
<div class="woow-conditional" data-show-when="field_name=yes">
    ...
</div>
```

### Checkbox
```php
<input type="checkbox" name="section[field_name]" value="1" />

<!-- Shows when checkbox is checked -->
<div class="woow-conditional" data-show-when="field_name=1">
    ...
</div>

<!-- Shows when checkbox is unchecked -->
<div class="woow-conditional" data-show-when="field_name=0">
    ...
</div>
```

## JavaScript Implementation

The conditional fields logic is in `assets/src/js/main.js`:

```javascript
setupConditionalFields() {
    // 1. Find all conditional fields
    const conditionalFields = document.querySelectorAll('.woow-conditional');
    
    // 2. Parse conditions and find controller fields
    // 3. Attach event listeners to controller fields
    // 4. Update visibility on change
}
```

### How It Works
1. On page load, finds all `.woow-conditional` elements
2. Parses `data-show-when` attribute to identify controller field
3. Finds the controller field by name
4. Sets initial visibility based on current value
5. Listens for changes on controller field
6. Shows/hides conditional fields with smooth animation

## CSS Styling

Conditional fields have smooth transitions:

```css
.woow-conditional {
  transition: opacity 0.2s ease, max-height 0.3s ease;
  overflow: hidden;
}

.woow-conditional[style*="display: none"] {
  opacity: 0;
  max-height: 0;
  margin: 0;
  padding: 0;
}

.woow-conditional-visible {
  opacity: 1;
  max-height: 1000px;
}
```

## Examples

### Example 1: Background Type Selector
```php
<!-- Controller -->
<select name="admin_bar[background_type]">
    <option value="solid">Solid</option>
    <option value="gradient">Gradient</option>
</select>

<!-- Solid options (visible when solid selected) -->
<div class="woow-conditional" data-show-when="background_type=solid">
    <input type="color" name="admin_bar[background_color]" />
</div>

<!-- Gradient options (visible when gradient selected) -->
<div class="woow-conditional" data-show-when="background_type=gradient">
    <input type="color" name="admin_bar[gradient_start]" />
</div>
<div class="woow-conditional" data-show-when="background_type=gradient">
    <input type="color" name="admin_bar[gradient_end]" />
</div>
```

### Example 2: Enable/Disable Feature
```php
<!-- Controller -->
<input type="checkbox" name="admin_bar[glassmorphism]" value="1" />

<!-- Options visible only when glassmorphism is enabled -->
<div class="woow-conditional" data-show-when="glassmorphism=1">
    <label>Blur Strength</label>
    <input type="range" name="admin_bar[blur_strength]" />
</div>

<div class="woow-conditional" data-show-when="glassmorphism=1">
    <label>Opacity</label>
    <input type="range" name="admin_bar[opacity]" />
</div>
```

### Example 3: Multiple Conditions (Same Field)
```php
<!-- Controller -->
<select name="admin_bar[position]">
    <option value="fixed">Fixed</option>
    <option value="absolute">Absolute</option>
    <option value="sticky">Sticky</option>
</select>

<!-- Different fields for different positions -->
<div class="woow-conditional" data-show-when="position=fixed">
    <label>Top Offset</label>
    <input type="number" name="admin_bar[top_offset]" />
</div>

<div class="woow-conditional" data-show-when="position=absolute">
    <label>Z-Index</label>
    <input type="number" name="admin_bar[z_index]" />
</div>

<div class="woow-conditional" data-show-when="position=sticky">
    <label>Sticky Threshold</label>
    <input type="number" name="admin_bar[sticky_threshold]" />
</div>
```

## Limitations

1. **Single Condition Only**: Each conditional field can have only one condition
   - ❌ Cannot do: `data-show-when="field1=value1 AND field2=value2"`
   - ✅ Can do: Nest conditional fields for complex logic

2. **Simple Equality**: Only supports `=` operator
   - ❌ Cannot do: `data-show-when="width>100"`
   - ✅ Can do: `data-show-when="width_unit=px"`

3. **Same Section**: Controller and conditional fields should be in same section
   - Works best when both fields are in same form section

## Troubleshooting

### Field Not Hiding/Showing
1. Check controller field name matches condition
2. Verify `woow-conditional` class is present
3. Check browser console for JavaScript errors
4. Ensure controller field exists in DOM

### Animation Not Smooth
1. Check CSS is loaded
2. Verify no conflicting styles
3. Check `max-height` value is sufficient

### Multiple Fields Not Working
1. Each conditional field needs its own `woow-conditional` wrapper
2. Don't wrap multiple fields in single conditional div

## Best Practices

1. **Wrap Each Field Separately**
   ```php
   <!-- ✅ Good -->
   <div class="woow-conditional" data-show-when="type=gradient">
       <label>Start Color</label>
       <input type="color" name="gradient_start" />
   </div>
   <div class="woow-conditional" data-show-when="type=gradient">
       <label>End Color</label>
       <input type="color" name="gradient_end" />
   </div>
   
   <!-- ❌ Bad -->
   <div class="woow-conditional" data-show-when="type=gradient">
       <label>Start Color</label>
       <input type="color" name="gradient_start" />
       <label>End Color</label>
       <input type="color" name="gradient_end" />
   </div>
   ```

2. **Use Descriptive Values**
   ```php
   <!-- ✅ Good -->
   <option value="gradient">Gradient</option>
   data-show-when="background_type=gradient"
   
   <!-- ❌ Bad -->
   <option value="1">Gradient</option>
   data-show-when="background_type=1"
   ```

3. **Provide Defaults**
   Always ensure controller field has a default value so conditional fields show correctly on page load.

## Status
✅ Implemented and working
✅ Supports select, radio, checkbox
✅ Smooth animations
✅ Multiple conditions per controller
✅ Auto-initializes on page load
