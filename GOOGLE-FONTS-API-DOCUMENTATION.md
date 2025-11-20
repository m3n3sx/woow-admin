# WOOW_Google_Fonts Class Documentation

## Overview

The `WOOW_Google_Fonts` class manages Google Fonts integration for the WOOW! Admin plugin. It provides a curated library of 50+ popular Google Fonts organized by category, with methods for font validation, URL generation, and CSS integration.

## Features

- **51 Popular Google Fonts** organized into 4 categories:
  - Sans-Serif (20 fonts): Inter, Roboto, Open Sans, Lato, Montserrat, Poppins, etc.
  - Serif (15 fonts): Playfair Display, Merriweather, Lora, PT Serif, etc.
  - Monospace (8 fonts): Roboto Mono, Source Code Pro, Fira Code, JetBrains Mono, etc.
  - Handwriting (8 fonts): Pacifico, Dancing Script, Caveat, Satisfy, etc.

- **Font Weight Support**: Each font includes its available weights (100-900)
- **URL Generation**: Automatic Google Fonts API URL generation with proper encoding
- **Display Swap**: All URLs include `display=swap` parameter for optimal loading
- **Fallback Stacks**: Category-specific system font fallbacks
- **Validation**: Font name and weight validation
- **Preconnect Support**: HTML link tags for performance optimization

## Class Methods

### Core Methods

#### `get_fonts(): array`
Returns all available fonts with their properties.

```php
$google_fonts = new WOOW_Google_Fonts();
$all_fonts = $google_fonts->get_fonts();
// Returns: ['Inter' => ['category' => 'sans-serif', 'weights' => [100, 200, ...]], ...]
```

#### `get_font( string $font_name ): ?array`
Gets a specific font by name.

```php
$inter = $google_fonts->get_font('Inter');
// Returns: ['category' => 'sans-serif', 'weights' => [100, 200, 300, ...]]
```

#### `get_available_weights( string $font_name ): array`
Gets available weights for a specific font.

```php
$weights = $google_fonts->get_available_weights('Roboto');
// Returns: [100, 300, 400, 500, 700, 900]
```

#### `get_font_url( string $font_name, array $weights = [] ): string`
Generates Google Fonts API URL for a font with specified weights.

```php
$url = $google_fonts->get_font_url('Inter', [400, 600, 700]);
// Returns: "https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap"
```

**Features:**
- Validates font exists in library
- Filters weights to only include available ones
- Defaults to weight 400 if none specified
- Properly encodes font names with spaces (e.g., "Open Sans" → "Open+Sans")
- Always includes `display=swap` parameter

### Validation Methods

#### `is_valid_font( string $font_name ): bool`
Checks if a font name exists in the library.

```php
$is_valid = $google_fonts->is_valid_font('Inter'); // true
$is_invalid = $google_fonts->is_valid_font('NonExistent'); // false
```

#### `get_font_category( string $font_name ): ?string`
Gets the category of a font.

```php
$category = $google_fonts->get_font_category('Inter');
// Returns: "sans-serif"
```

### Organization Methods

#### `get_fonts_by_category(): array`
Returns fonts organized by category.

```php
$by_category = $google_fonts->get_fonts_by_category();
// Returns: [
//   'sans-serif' => ['Inter' => [...], 'Roboto' => [...], ...],
//   'serif' => ['Playfair Display' => [...], ...],
//   'monospace' => ['Roboto Mono' => [...], ...],
//   'handwriting' => ['Pacifico' => [...], ...]
// ]
```

### CSS Helper Methods

#### `get_font_family_css( string $font_name ): string`
Generates complete font-family CSS value with fallbacks.

```php
$css = $google_fonts->get_font_family_css('Inter');
// Returns: "Inter, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif"
```

#### `get_fallback_stack( string $category ): string`
Gets system font fallback stack for a category.

```php
$fallback = $google_fonts->get_fallback_stack('sans-serif');
// Returns: "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif"
```

### HTML Generation Methods

#### `get_preconnect_links(): string`
Generates HTML preconnect link tags for performance.

```php
$preconnect = $google_fonts->get_preconnect_links();
// Returns:
// <link rel="preconnect" href="https://fonts.googleapis.com">
// <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
```

#### `get_font_link( string $font_name, array $weights = [] ): string`
Generates HTML link tag for loading a font.

```php
$link = $google_fonts->get_font_link('Poppins', [400, 600]);
// Returns: <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap">
```

## Usage Examples

### Example 1: Load a Font in Admin Panel

```php
$google_fonts = new WOOW_Google_Fonts();

// Get font URL
$font_url = $google_fonts->get_font_url('Inter', [400, 600, 700]);

// Add to WordPress admin head
add_action('admin_head', function() use ($font_url) {
    echo '<link rel="stylesheet" href="' . esc_url($font_url) . '">';
});
```

### Example 2: Generate CSS with Fallbacks

```php
$google_fonts = new WOOW_Google_Fonts();

// Get complete font-family CSS
$body_font = $google_fonts->get_font_family_css('Inter');
$heading_font = $google_fonts->get_font_family_css('Playfair Display');

// Generate CSS
$css = "
body {
    font-family: {$body_font};
}

h1, h2, h3, h4, h5, h6 {
    font-family: {$heading_font};
}
";
```

### Example 3: Validate User Input

```php
$google_fonts = new WOOW_Google_Fonts();

// Validate font selection
$user_font = $_POST['body_font'] ?? 'system';

if ($user_font !== 'system' && !$google_fonts->is_valid_font($user_font)) {
    wp_die('Invalid font selected');
}

// Validate weights
$user_weights = $_POST['body_weights'] ?? [400];
$available_weights = $google_fonts->get_available_weights($user_font);
$valid_weights = array_intersect($user_weights, $available_weights);
```

### Example 4: Build Font Selector Dropdown

```php
$google_fonts = new WOOW_Google_Fonts();
$fonts_by_category = $google_fonts->get_fonts_by_category();

echo '<select name="body_font">';
echo '<option value="system">System Default</option>';

foreach ($fonts_by_category as $category => $fonts) {
    echo '<optgroup label="' . esc_attr(ucfirst($category)) . '">';
    foreach ($fonts as $name => $data) {
        echo '<option value="' . esc_attr($name) . '">' . esc_html($name) . '</option>';
    }
    echo '</optgroup>';
}

echo '</select>';
```

### Example 5: Optimize Performance with Preconnect

```php
$google_fonts = new WOOW_Google_Fonts();

// Add preconnect hints in <head>
add_action('admin_head', function() use ($google_fonts) {
    echo $google_fonts->get_preconnect_links();
}, 1); // Priority 1 to load early
```

## Font Library

### Sans-Serif Fonts (20)
Inter, Roboto, Open Sans, Lato, Montserrat, Poppins, Raleway, Nunito, Ubuntu, Work Sans, Rubik, Nunito Sans, Source Sans Pro, Oswald, Mukta, Barlow, Quicksand, Karla, Oxygen, Manrope

### Serif Fonts (15)
Playfair Display, Merriweather, Lora, PT Serif, Crimson Text, Libre Baskerville, Cormorant Garamond, EB Garamond, Spectral, Bitter, Cardo, Alegreya, Vollkorn, Arvo, Rokkitt

### Monospace Fonts (8)
Roboto Mono, Source Code Pro, Fira Code, JetBrains Mono, IBM Plex Mono, Space Mono, Inconsolata, Courier Prime

### Handwriting Fonts (8)
Pacifico, Dancing Script, Caveat, Satisfy, Kalam, Indie Flower, Shadows Into Light, Permanent Marker

## Requirements Validation

This implementation satisfies the following requirements from the specification:

- ✅ **Requirement 1.1**: 50+ popular Google Fonts organized by category
- ✅ **Requirement 1.2**: Font loading from Google Fonts API
- ✅ **Requirement 5.2**: display=swap parameter for optimal loading
- ✅ **Requirement 7.4**: Proper URL encoding to prevent injection attacks
- ✅ **Requirement 8.1**: Fonts organized into categories (sans-serif, serif, monospace, handwriting)
- ✅ **Requirement 8.4**: 50+ most popular Google Fonts based on usage

## Security Considerations

1. **URL Encoding**: Font names are properly encoded using `str_replace()` for spaces
2. **Validation**: All font names validated against whitelist (FONT_LIBRARY)
3. **Weight Filtering**: Only valid weights for each font are allowed
4. **No User Input**: Font library is hardcoded constant, not user-modifiable
5. **Escaping**: All HTML output methods use proper escaping (esc_url, esc_attr)

## Performance Considerations

1. **Preconnect Hints**: `get_preconnect_links()` enables early DNS resolution
2. **Display Swap**: All URLs include `display=swap` to prevent FOIT (Flash of Invisible Text)
3. **Weight Optimization**: Only requested weights are loaded
4. **Fallback Stacks**: System fonts used as fallbacks for instant rendering
5. **Minimal Library**: Curated list of 51 fonts keeps class lightweight

## Testing

Run the test suite:

```bash
php test-google-fonts.php
```

Expected output: All 15 tests should pass ✅

## Integration with WOOW! Admin

The class is automatically loaded via Composer's classmap autoloader. To use in other classes:

```php
$google_fonts = new WOOW_Google_Fonts();
```

No additional initialization required.

## Future Enhancements

Potential improvements for future versions:

1. **Variable Fonts**: Support for variable font technology
2. **Font Subsetting**: Character set selection (Latin, Cyrillic, etc.)
3. **Font Pairing**: AI-powered font combination suggestions
4. **Custom Fonts**: Support for self-hosted fonts
5. **Font Preview**: Generate preview images for font selection UI
6. **Caching**: Cache font URLs to reduce processing
7. **Full Library**: Expand to include all 1000+ Google Fonts with search/filter

## Version History

- **2.0.0** (2025-01-19): Initial implementation with 51 fonts across 4 categories
