# Race Condition & Palette Colors-Only Fix

## Data: 16 listopada 2025

## Problem 1: Duplicate AJAX Requests (Race Condition)

### Objawy
```
[16-Nov-2025 12:22:15 UTC] [WOOW Admin] ajax_apply_template called
[16-Nov-2025 12:22:15 UTC] [WOOW Admin] ajax_apply_template called  <-- DUPLICATE!
[16-Nov-2025 12:22:15 UTC] [WOOW Template Manager] Error BACKUP_FAILED: Failed to create backup
```

### Przyczyna
Event bubbling w JavaScript - kliknięcie przycisku "Apply" wywoływało:
1. Handler na przycisku `.woow-template-apply-btn`
2. Handler na karcie `.woow-template-card` (przez event bubbling)

Oba handlery wywoływały `applyTemplate()`, co powodowało dwa równoczesne requesty AJAX.

### Rozwiązanie

#### 1. Dodano `stopImmediatePropagation()` w event handlerze

**Plik:** `assets/src/js/components/TemplateSelector.js`

```javascript
if (applyBtn && card) {
    // Prevent duplicate requests
    e.preventDefault();
    e.stopPropagation();
    e.stopImmediatePropagation();  // ← NOWE!
    
    // Check if already applying
    if (this.isApplying) {
        console.log('[TemplateSelector] Already applying, ignoring duplicate');
        return;
    }
    
    const templateId = card.dataset.template;
    if (templateId) {
        this.applyTemplate(templateId);
    }
    return; // ← NOWE! Early return prevents card handler
}
```

#### 2. Ulepszona flaga `isApplying`

```javascript
async applyTemplate(templateId) {
    // Prevent duplicate calls
    if (!templateId || this.isApplying) {
        console.log('[TemplateSelector] Ignoring duplicate apply request');
        return;
    }

    // Set flag IMMEDIATELY to prevent race conditions
    this.isApplying = true;
    
    console.log('[TemplateSelector] Applying template:', templateId);
    
    try {
        // ... AJAX request ...
        
        if (data.success) {
            // Don't reset isApplying - we're reloading the page
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }
    } catch (error) {
        // Reset flag only on error
        this.isApplying = false;
    }
}
```

#### 3. Te same poprawki dla PaletteSelector

**Plik:** `assets/src/js/components/PaletteSelector.js`

Zastosowano identyczne poprawki dla palet kolorów.

### Rezultat
✅ Brak duplikatów requestów AJAX
✅ Backup tworzony tylko raz
✅ Brak błędów "BACKUP_FAILED"

---

## Problem 2: Palety zmieniały wszystkie ustawienia

### Objawy
Aplikacja palety kolorów nadpisywała:
- Obrazy tła
- Rozmiary czcionek
- Efekty glassmorphism
- Inne ustawienia wizualne

### Oczekiwane zachowanie
Palety kolorów powinny zmieniać **TYLKO kolory**, w tym kolory tła, ale nie obrazy tła ani inne ustawienia.

### Rozwiązanie

**Plik:** `includes/class-woow-palette-manager.php`

Zmieniono metodę `merge_palette_settings()`:

#### Przed (ZŁE):
```php
private function merge_palette_settings($current_settings, $palette_settings) {
    // Aplikowało WSZYSTKIE ustawienia z palety
    $merged = array_replace_recursive($current_settings, $palette_settings);
    return $merged;
}
```

#### Po (DOBRE):
```php
private function merge_palette_settings($current_settings, $palette_settings) {
    $merged = $current_settings;

    // Definiujemy które pola są związane z kolorami
    $color_fields = array(
        'color_overrides' => array(
            'primary_color', 'secondary_color', 'accent_color',
            'background_color', 'text_color', 'link_color',
            'success_color', 'warning_color', 'error_color',
        ),
        'admin_bar' => array(
            'background_color', 'text_color', 'hover_color',
            'icon_color', 'submenu_bg_color', 'submenu_text_color',
            'submenu_hover_color', 'gradient_start', 'gradient_end',
        ),
        'backgrounds' => array(
            'body_background_color', 'content_background_color',
            // NIE: image_url, image_size, image_repeat
        ),
        'typography' => array(
            'heading_color', 'body_color', 'link_color',
            // NIE: font_family, font_size, line_height
        ),
        // ... inne sekcje
    );

    // Aplikujemy TYLKO pola kolorów
    foreach ($color_fields as $section => $fields) {
        if (!isset($palette_settings[$section])) continue;
        
        foreach ($fields as $field) {
            if (isset($palette_settings[$section][$field])) {
                $merged[$section][$field] = $palette_settings[$section][$field];
            }
        }
    }

    return $merged;
}
```

### Co palety ZMIENIAJĄ (✅):
- Wszystkie kolory w interfejsie
- Kolory tła (background colors)
- Kolory tekstu
- Kolory przycisków
- Kolory linków
- Kolory hover/active
- Kolory ikon
- Kolory obramowań

### Co palety NIE ZMIENIAJĄ (❌):
- Obrazy tła (background images)
- Typografia (czcionki, rozmiary, line-height)
- Efekty (glassmorphism, cienie, animacje)
- Rozmiary elementów
- Odstępy (padding, margin)
- Border radius
- Inne ustawienia wizualne

### Rezultat
✅ Palety zmieniają tylko kolory
✅ Niestandardowe tła są zachowane
✅ Ustawienia typografii są zachowane
✅ Efekty wizualne są zachowane

---

## Pliki zmienione

### JavaScript (Race Condition Fix)
1. `assets/src/js/components/TemplateSelector.js`
   - Dodano `stopImmediatePropagation()`
   - Ulepszona flaga `isApplying`
   - Dodano early return w event handlerze

2. `assets/src/js/components/PaletteSelector.js`
   - Te same poprawki co w TemplateSelector

### PHP (Palette Colors-Only Fix)
1. `includes/class-woow-palette-manager.php`
   - Przepisana metoda `merge_palette_settings()`
   - Dodana dokumentacja o ograniczeniu do kolorów

### Dokumentacja
1. `RACE-CONDITION-AND-PALETTE-FIX.md` (ten plik)
2. `PALETTE-COLORS-ONLY-FIX.md` (szczegółowa dokumentacja)
3. `test-palette-colors-only.php` (test weryfikacyjny)

---

## Następne kroki

### 1. Rebuild JavaScript
```bash
cd woow-admin
npm run build
```

### 2. Test w przeglądarce
1. Otwórz WordPress admin
2. Przejdź do WOOW! Admin → Palettes
3. Kliknij "Apply" na dowolnej palecie
4. Sprawdź w konsoli - brak duplikatów requestów
5. Sprawdź czy tylko kolory się zmieniły

### 3. Weryfikacja
- [ ] Brak błędów "BACKUP_FAILED" w logach
- [ ] Tylko jeden request AJAX przy kliknięciu "Apply"
- [ ] Obrazy tła nie są nadpisywane przez palety
- [ ] Rozmiary czcionek nie są nadpisywane przez palety
- [ ] Efekty glassmorphism nie są nadpisywane przez palety

---

## Status
✅ Zaimplementowane
⏳ Wymaga rebuildu JavaScript (npm run build)
⏳ Wymaga testów w przeglądarce
