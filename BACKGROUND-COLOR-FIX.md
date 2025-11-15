# Admin Menu Background Color - Problem Fixed

## Problem
Zmiana koloru tła w admin menu nie działała.

## Przyczyna
Metoda `sanitize_color()` w klasie `WOOW_Settings` nie obsługiwała wszystkich formatów kolorów:
- ❌ Brak obsługi hex z alpha (8 znaków): `#ffffffff`
- ❌ Brak obsługi named colors: `transparent`, `white`, etc.
- ❌ Regex dla rgba był case-sensitive (nie akceptował `RGBA`)

## Rozwiązanie
Zaktualizowano metodę `sanitize_color()` w `includes/class-woow-settings.php`:

```php
public function sanitize_color( $color ) {
    if ( ! is_string( $color ) ) {
        return false;
    }
    
    $color = trim( $color );

    // ✅ Hex color validation (3, 6, or 8 characters for alpha)
    if ( preg_match( '/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6}|[A-Fa-f0-9]{8})$/', $color ) ) {
        return $color;
    }

    // ✅ RGB/RGBA validation (case-insensitive)
    if ( preg_match( '/^rgba?\(\s*\d+\s*,\s*\d+\s*,\s*\d+\s*(,\s*[\d.]+\s*)?\)$/i', $color ) ) {
        return $color;
    }

    // ✅ Named colors support
    $named_colors = array(
        'transparent', 'white', 'black', 'red', 'green', 'blue',
        'yellow', 'cyan', 'magenta', 'gray', 'grey'
    );
    if ( in_array( strtolower( $color ), $named_colors, true ) ) {
        return $color;
    }

    return false;
}
```

## Zmiany
1. **Hex z alpha**: Dodano `[A-Fa-f0-9]{8}` do regex
2. **Named colors**: Dodano whitelist podstawowych kolorów
3. **Case-insensitive**: Dodano flagę `/i` do regex rgba

## Testowanie
Po tej zmianie:
- ✅ Kolory hex (#ffffff) działają
- ✅ Kolory rgba (rgba(255,255,255,0.9)) działają
- ✅ Kolory hex z alpha (#ffffffff) działają
- ✅ Named colors (transparent, white) działają

## Jak przetestować
1. Przejdź do Admin Menu → Background
2. Zmień "Background Type" na "Solid Color"
3. Wybierz kolor z color pickera
4. Kliknij "Apply Changes"
5. Sprawdź, czy kolor się zmienił w menu

## Pliki zmienione
- `includes/class-woow-settings.php` - zaktualizowano metodę `sanitize_color()`
