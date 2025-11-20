# Typography Validation Implementation

## Overview
This document describes the PHP validation and sanitization implementation for the Google Fonts Typography feature in WOOW! Admin.

## Implementation Date
2025-01-19

## Files Modified
- `woow-admin/includes/class-woow-settings.php`

## Requirements Addressed
- **Requirement 7.1**: Font name validation (whitelist approved fonts)
- **Requirement 7.2**: Weight validation (numeric, 100-900 range)
- **Requirement 7.3**: Invalid font data rejection with error messages
- **Requirement 7.5**: Input sanitization before database storage

## Implementation Details

### 1. Font Name Validation

**Location**: `class-woow-settings.php` - `validate_settings()` method (lines ~1350-1370)

**Validation Logic**:
```php
// Typography keyword fields
elseif ( $section === 'typography' && ( 
    $key === 'body_font' || 
    $key === 'heading_font' || 
    $key === 'heading_weight'
) ) {
    // These are keyword values
    if ( ! is_string( $value ) ) {
        $is_valid = false;
        $error_message = "Value must be a string";
    }
    
    // Validate font names against approved Google Fonts library
    if ( ( $key === 'body_font' || $key === 'heading_font' ) && $value !== 'system' ) {
        // Load Google Fonts class if not already loaded
        if ( ! class_exists( 'WOOW_Google_Fonts' ) ) {
            require_once WOOW_PLUGIN_DIR . 'includes/class-woow-google-fonts.php';
        }
        
        $google_fonts = new WOOW_Google_Fonts();
        if ( ! $google_fonts->is_valid_font( $value ) ) {
            $is_valid = false;
            $error_message = "Invalid font name (not in approved Google Fonts library)";
        }
    }
}
```

**Features**:
- Validates that font names are strings
- Allows 'system' as a special value (uses system fonts)
- Checks font names against the approved Google Fonts library (50+ fonts)
- Uses `WOOW_Google_Fonts::is_valid_font()` method for validation
- Returns clear error message for invalid fonts

**Approved Fonts**:
The validation uses the font library defined in `WOOW_Google_Fonts` class, which includes:
- 20 Sans-Serif fonts (Inter, Roboto, Open Sans, Lato, Montserrat, Poppins, etc.)
- 15 Serif fonts (Playfair Display, Merriweather, Lora, PT Serif, etc.)
- 8 Monospace fonts (Roboto Mono, Source Code Pro, Fira Code, etc.)
- 8 Handwriting/Display fonts (Pacifico, Dancing Script, Caveat, etc.)

### 2. Weight Validation

**Location**: `class-woow-settings.php` - `validate_settings()` method (lines ~1370-1390)

**Validation Logic**:
```php
// Typography weight arrays validation
elseif ( $section === 'typography' && ( 
    $key === 'body_weights' || 
    $key === 'heading_weights'
) ) {
    // These must be arrays of numeric weight values
    if ( ! is_array( $value ) ) {
        $is_valid = false;
        $error_message = "Value must be an array of font weights";
    } else {
        // Validate each weight in the array
        foreach ( $value as $weight ) {
            if ( ! is_numeric( $weight ) || $weight < 100 || $weight > 900 || $weight % 100 !== 0 ) {
                $is_valid = false;
                $error_message = "Font weights must be numeric values between 100 and 900 in increments of 100";
                break;
            }
        }
    }
}
```

**Features**:
- Validates that weights are provided as arrays
- Checks each weight is numeric
- Ensures weights are in the range 100-900
- Validates weights are in increments of 100 (100, 200, 300, ..., 900)
- Returns clear error message for invalid weights

**Valid Weights**: 100, 200, 300, 400, 500, 600, 700, 800, 900

### 3. Font Name Sanitization

**Location**: `class-woow-settings.php` - `sanitize_value()` method (lines ~1440-1460)

**Sanitization Logic**:
```php
// Typography font name sanitization
if ( $key === 'body_font' || $key === 'heading_font' ) {
    // Sanitize as text field
    $sanitized = sanitize_text_field( $value );
    
    // Validate against approved fonts
    if ( $sanitized !== 'system' ) {
        if ( ! class_exists( 'WOOW_Google_Fonts' ) ) {
            require_once WOOW_PLUGIN_DIR . 'includes/class-woow-google-fonts.php';
        }
        
        $google_fonts = new WOOW_Google_Fonts();
        if ( ! $google_fonts->is_valid_font( $sanitized ) ) {
            // Invalid font - return 'system' as fallback
            return 'system';
        }
    }
    
    return $sanitized;
}
```

**Features**:
- Uses WordPress `sanitize_text_field()` to remove HTML tags and dangerous characters
- Validates sanitized value against approved fonts
- Falls back to 'system' if font is invalid
- Prevents XSS attacks and SQL injection
- Ensures only safe, approved fonts are stored in database

### 4. Weight Array Sanitization

**Location**: `class-woow-settings.php` - `sanitize_value()` method (lines ~1460-1490)

**Sanitization Logic**:
```php
// Typography weight arrays sanitization
if ( $key === 'body_weights' || $key === 'heading_weights' ) {
    // Ensure it's an array
    if ( ! is_array( $value ) ) {
        return [400]; // Default to regular weight
    }
    
    // Sanitize each weight
    $sanitized_weights = [];
    foreach ( $value as $weight ) {
        $weight = intval( $weight );
        // Validate range and increment
        if ( $weight >= 100 && $weight <= 900 && $weight % 100 === 0 ) {
            $sanitized_weights[] = $weight;
        }
    }
    
    // If no valid weights, return default
    if ( empty( $sanitized_weights ) ) {
        return [400];
    }
    
    // Remove duplicates and sort
    $sanitized_weights = array_unique( $sanitized_weights );
    sort( $sanitized_weights );
    
    return $sanitized_weights;
}
```

**Features**:
- Ensures value is an array (defaults to [400] if not)
- Converts each weight to integer using `intval()`
- Filters out invalid weights (outside 100-900 range or not in increments of 100)
- Removes duplicate weights
- Sorts weights in ascending order
- Falls back to [400] if no valid weights remain
- Prevents malformed data from being stored

## Security Considerations

### XSS Prevention
- All font names are sanitized using `sanitize_text_field()`
- HTML tags and scripts are stripped before validation
- Only whitelisted fonts from approved library are accepted

### SQL Injection Prevention
- Font names are validated against a whitelist
- Weights are converted to integers
- No user input is directly concatenated into queries

### Data Integrity
- Invalid fonts automatically fall back to 'system'
- Invalid weights automatically fall back to [400]
- Ensures database always contains valid, safe data

## Error Handling

### Validation Errors
When validation fails, the system returns:
```php
[
    'valid' => false,
    'errors' => [
        [
            'field' => 'typography.body_font',
            'message' => 'Invalid font name (not in approved Google Fonts library)',
            'value' => 'InvalidFont'
        ]
    ]
]
```

### Sanitization Fallbacks
- Invalid font names → 'system'
- Invalid weight arrays → [400]
- Non-array weights → [400]
- Empty weight arrays → [400]

## Testing

### Test File
A comprehensive test file was created: `test-typography-validation.php`

### Test Coverage
1. **Valid Font Names**: system, Inter, Roboto, Playfair Display
2. **Invalid Font Names**: Comic Sans, InvalidFont, empty string
3. **Valid Weights**: 100, 400, 700, 900
4. **Invalid Weights**: 50, 450, 1000, non-numeric
5. **Valid Weight Arrays**: [400, 600, 700], [300, 400, 500]
6. **Invalid Weight Arrays**: [400, 450, 700], [100, 200, 1000]
7. **Font Sanitization**: Valid fonts, invalid fonts, XSS attempts
8. **Weight Array Sanitization**: Valid arrays, invalid arrays, duplicates, empty arrays

### Syntax Validation
Both modified files pass PHP syntax validation:
```bash
php -l woow-admin/includes/class-woow-settings.php
# No syntax errors detected

php -l woow-admin/includes/class-woow-google-fonts.php
# No syntax errors detected
```

## Integration with Existing System

### Validation Flow
1. User submits typography settings via AJAX
2. Settings passed to `WOOW_Settings::validate_settings()`
3. Font names validated against Google Fonts library
4. Weight arrays validated for correct format and range
5. If validation fails, error messages returned to user
6. If validation passes, settings proceed to sanitization

### Sanitization Flow
1. Validated settings passed to `WOOW_Settings::sanitize_value()`
2. Font names sanitized and re-validated
3. Weight arrays sanitized and normalized
4. Invalid values replaced with safe defaults
5. Sanitized settings stored in database

### CSS Generation
After validation and sanitization, the CSS Generator uses the validated fonts:
- Font names are guaranteed to be valid Google Fonts or 'system'
- Weight arrays are guaranteed to contain valid weights (100-900, increments of 100)
- No additional validation needed in CSS generation

## Compatibility

### WordPress Version
- Compatible with WordPress 5.8+
- Uses standard WordPress sanitization functions

### PHP Version
- Compatible with PHP 8.0+
- Uses strict type declarations in Google Fonts class
- No strict types in Settings class for flexibility

### Existing Features
- Does not break existing validation for other settings
- Follows same validation pattern as other sections
- Integrates seamlessly with existing error handling

## Future Enhancements

### Potential Improvements
1. **Weight Availability Check**: Validate that selected weights are available for the specific font
2. **Font Subset Validation**: Add validation for character subsets (Latin, Cyrillic, etc.)
3. **Variable Font Support**: Add validation for variable font axes
4. **Custom Font Upload**: Extend validation to support custom uploaded fonts
5. **Font Pairing Validation**: Validate font combinations for readability

### Performance Optimization
1. **Cache Font Library**: Cache the font library to avoid repeated file reads
2. **Lazy Load Google Fonts Class**: Only load when typography settings are being validated
3. **Batch Validation**: Validate multiple fonts in a single operation

## Conclusion

The typography validation implementation provides:
- ✅ Robust font name validation against approved library
- ✅ Comprehensive weight validation (range and format)
- ✅ Secure input sanitization preventing XSS and injection
- ✅ Clear error messages for debugging
- ✅ Safe fallback values for invalid input
- ✅ Integration with existing validation system
- ✅ No syntax errors or breaking changes

All requirements (7.1, 7.2, 7.3, 7.5) have been successfully implemented.
