# Palette Colors-Only Fix

## Problem
Palety kolorów aplikowały wszystkie ustawienia (tła, typografię, efekty), co było nieprawidłowe.

## Rozwiązanie
Palety teraz zmieniają **TYLKO kolory**, w tym:

### ✅ Co palety ZMIENIAJĄ:
- Wszystkie kolory w interfejsie
- Kolory tła (background colors)
- Kolory tekstu
- Kolory przycisków
- Kolory linków
- Kolory hover/active
- Kolory ikon
- Kolory obramowań

### ❌ Co palety NIE ZMIENIAJĄ:
- Obrazy tła (background images)
- Typografia (czcionki, rozmiary, line-height)
- Efekty (glassmorphism, cienie, animacje)
- Rozmiary elementów
- Odstępy (padding, margin)
- Border radius
- Inne ustawienia wizualne

## Implementacja

### Zmieniona metoda: `merge_palette_settings()`

**Przed:**
```php
// Aplikowała wszystkie ustawienia z palety
$merged = array_replace_recursive( $current_settings, $normalized_palette );
```

**Po:**
```php
// Aplikuje tylko pola związane z kolorami
$color_fields = array(
    'color_overrides' => array('primary_color', 'secondary_color', ...),
    'admin_bar' => array('background_color', 'text_color', ...),
    // ... tylko pola kolorów
);

foreach ( $color_fields as $section => $fields ) {
    foreach ( $fields as $field ) {
        if ( isset( $palette_settings[ $section ][ $field ] ) ) {
            $merged[ $section ][ $field ] = $palette_settings[ $section ][ $field ];
        }
    }
}
```

## Sekcje i pola kolorów

### color_overrides
- primary_color
- secondary_color
- accent_color
- background_color
- text_color
- link_color
- success_color
- warning_color
- error_color

### admin_bar
- background_color
- text_color
- hover_color
- icon_color
- submenu_bg_color
- submenu_text_color
- submenu_hover_color
- gradient_start
- gradient_end

### admin_menu
- background_color
- text_color
- hover_bg_color
- hover_text_color
- active_bg_color
- active_text_color
- icon_color
- separator_color
- submenu_bg_color
- submenu_text_color
- submenu_hover_bg_color
- submenu_hover_text_color

### dashboard_widgets
- background_color
- border_color
- title_color
- text_color
- link_color

### form_controls
- input_bg_color
- input_border_color
- input_text_color
- input_focus_border_color
- label_color

### buttons
- primary_bg_color
- primary_text_color
- primary_hover_bg_color
- secondary_bg_color
- secondary_text_color
- secondary_hover_bg_color

### backgrounds
- body_background_color
- content_background_color
- ❌ NIE: image_url, image_size, image_repeat, image_position

### typography
- heading_color
- body_color
- link_color
- ❌ NIE: font_family, font_size, line_height

### effects
- ❌ Brak pól kolorów - sekcja pomijana

### login_page
- background_color
- form_bg_color
- form_text_color
- button_bg_color
- button_text_color
- link_color

## Testowanie

### Test 1: Aplikuj paletę
```php
// Przed aplikacją
$before = get_option('woow_settings');
$bg_image_before = $before['backgrounds']['image_url'];
$font_size_before = $before['typography']['body_size'];

// Aplikuj paletę
$palette_manager->apply_palette('ocean_breeze');

// Po aplikacji
$after = get_option('woow_settings');
$bg_image_after = $after['backgrounds']['image_url'];
$font_size_after = $after['typography']['body_size'];

// Sprawdź
assert($bg_image_before === $bg_image_after); // ✅ Obraz tła nie zmieniony
assert($font_size_before === $font_size_after); // ✅ Rozmiar czcionki nie zmieniony
assert($before['admin_bar']['background_color'] !== $after['admin_bar']['background_color']); // ✅ Kolor zmieniony
```

### Test 2: Zachowanie ustawień
```php
// Ustaw niestandardowe tło
update_option('woow_settings', array(
    'backgrounds' => array(
        'image_url' => 'https://example.com/my-bg.jpg',
        'body_background_color' => '#000000',
    ),
));

// Aplikuj paletę
$palette_manager->apply_palette('sunset_glow');

$settings = get_option('woow_settings');

// Sprawdź
assert($settings['backgrounds']['image_url'] === 'https://example.com/my-bg.jpg'); // ✅ Obraz zachowany
assert($settings['backgrounds']['body_background_color'] !== '#000000'); // ✅ Kolor zmieniony
```

## Korzyści

1. **Intuicyjne zachowanie** - Palety robią to, czego użytkownik oczekuje
2. **Zachowanie personalizacji** - Niestandardowe tła, czcionki i efekty nie są nadpisywane
3. **Szybka zmiana kolorystyki** - Bez utraty innych ustawień
4. **Zgodność z nazwą** - "Paleta kolorów" zmienia kolory, nie wszystko

## Pliki zmienione

- `includes/class-woow-palette-manager.php` - Metoda `merge_palette_settings()`

## Data implementacji
16 listopada 2025

## Status
✅ Zaimplementowane i przetestowane
