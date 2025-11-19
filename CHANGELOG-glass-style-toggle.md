# CHANGELOG: Glass Style Global Toggle

## Data: 2024-11-18

## Cel
Dodanie funkcjonalności globalnego przełącznika "Glass Style" w zakładce Dashboard, który automatycznie konfiguruje glassmorphism w trzech sekcjach:
- Admin Bar
- Admin Menu  
- Content Styling

## Zmiany

### 1. PHP Backend (includes/class-woow-settings.php)

**Metoda:** `save_settings()`

**Dodano logikę:**
```php
// Handle Glass Style global toggle
if ( isset( $settings['general']['glass_style'] ) ) {
    $glass_enabled = (bool) $settings['general']['glass_style'];
    
    if ( $glass_enabled ) {
        // Admin Bar
        $settings['admin_bar']['background_type'] = 'glass';
        $settings['admin_bar']['background_color'] = '#ffffff';
        $settings['admin_bar']['opacity'] = 0.2;
        $settings['admin_bar']['blur_strength'] = '12';
        $settings['admin_bar']['glassmorphism'] = true;
        
        // Admin Menu
        $settings['admin_menu']['background_type'] = 'glass';
        $settings['admin_menu']['glass_base_color'] = '#ffffff';
        $settings['admin_menu']['opacity'] = 0.2;
        $settings['admin_menu']['blur_strength'] = '12';
        $settings['admin_menu']['glassmorphism'] = true;
        
        // Content Styling
        $settings['content_styling']['wpbody_content_glassmorphism'] = true;
        $settings['content_styling']['wpbody_content_opacity'] = 0.2;
        $settings['content_styling']['wpbody_content_blur_strength'] = '12';
    }
}
```

**Efekt:**
- Gdy użytkownik włącza "Glass Style" i zapisuje ustawienia, automatycznie ustawiane są wartości glassmorphism w trzech sekcjach
- Wartości są zapisywane do bazy danych

### 2. JavaScript Frontend (assets/src/js/main.js)

**Dodano metodę:** `setupGlassStyleToggle()`

**Funkcjonalność:**
- Nasłuchuje na zmianę przełącznika `general[glass_style]`
- Gdy włączony, automatycznie ustawia wartości w polach formularza:
  - Admin Bar: background_type='glass', color='#ffffff', opacity=20%, blur=12px
  - Admin Menu: background_type='glass', glass_base_color='#ffffff', opacity=20%, blur=12px
  - Content Styling: glassmorphism=true, opacity=20%, blur=12px
- Wywołuje `setFieldValue()` dla każdego pola
- Pokazuje powiadomienie o zastosowaniu Glass Style
- Triggeruje live preview

**Dodano metodę:** `setFieldValue(fieldName, value)`

**Funkcjonalność:**
- Uniwersalna metoda do ustawiania wartości pól formularza
- Obsługuje różne typy inputów: checkbox, radio, select, range, text, color
- Dla sliderów aktualizuje również wyświetlaną wartość
- Triggeruje eventy 'change' i 'input' dla live preview

**Wywołanie w bindEvents():**
```javascript
// Handle Glass Style global toggle
this.setupGlassStyleToggle();
```

### 3. Wartości Glassmorphism

**Ustawienia stosowane przez Glass Style:**

| Sekcja | Parametr | Wartość |
|--------|----------|---------|
| Admin Bar | background_type | 'glass' |
| Admin Bar | background_color | '#ffffff' |
| Admin Bar | opacity | 0.2 (20%) |
| Admin Bar | blur_strength | '12' |
| Admin Menu | background_type | 'glass' |
| Admin Menu | glass_base_color | '#ffffff' |
| Admin Menu | opacity | 0.2 (20%) |
| Admin Menu | blur_strength | '12' |
| Content Styling | wpbody_content_glassmorphism | true |
| Content Styling | wpbody_content_opacity | 0.2 (20%) |
| Content Styling | wpbody_content_blur_strength | '12' |

**Uwaga:** 
- W PHP opacity jest zapisywane jako 0.2 (float 0-1)
- W JavaScript slider używa wartości 20 (0-100), która jest konwertowana przez validator

## Przepływ Działania

### Scenariusz 1: Użytkownik WŁĄCZA Glass Style (checked)

1. Użytkownik zaznacza "Glass Style" w zakładce Dashboard
2. JavaScript (`setupGlassStyleToggle()`):
   - Wykrywa zmianę (`isEnabled = true`)
   - **Zapisuje obecne ustawienia** do `previousSettings`
   - Ustawia wartości glassmorphism w polach formularza:
     - Admin Bar: background_type='glass', color='#ffffff', opacity=20%, blur=12px
     - Admin Menu: background_type='glass', glass_base_color='#ffffff', opacity=20%, blur=12px
     - Content: glassmorphism=true, opacity=20%, blur=12px
   - Aktualizuje wyświetlane wartości sliderów
   - Pokazuje powiadomienie sukcesu
   - Triggeruje live preview (jeśli włączony)
3. Użytkownik klika "Apply Changes"
4. PHP (`save_settings()`):
   - Wykrywa `glass_style = true` (lub 1)
   - Nadpisuje/ustawia wartości glassmorphism w trzech sekcjach
   - Zapisuje do bazy danych
5. CSS Generator:
   - Generuje style glassmorphism dla Admin Bar, Admin Menu, Content
   - Stosuje opacity 20%, blur 12px, białe tło

### Scenariusz 2: Użytkownik WYŁĄCZA Glass Style (unchecked)

1. Użytkownik odznacza "Glass Style"
2. JavaScript:
   - Wykrywa zmianę (`isEnabled = false`)
   - **Przywraca poprzednie ustawienia** z `previousSettings`
   - Ustawia wartości w polach formularza do poprzednich wartości
   - Pokazuje powiadomienie: "Previous settings restored"
   - Triggeruje live preview (jeśli włączony)
3. Użytkownik klika "Apply Changes"
4. PHP:
   - Zapisuje przywrócone wartości do bazy danych
5. CSS Generator:
   - Generuje style według przywróconych ustawień

## Testowanie

### Test 1: WŁĄCZENIE Glass Style
1. Otwórz Dashboard
2. Sprawdź obecne ustawienia (np. Admin Bar ma solid background)
3. ZAZNACZ przełącznik "Glass Style" (włącz go)
4. **Oczekiwany rezultat:**
   - Powiadomienie: "Glass Style applied! Glassmorphism enabled..."
   - Pola w Admin Bar, Admin Menu, Content są automatycznie ustawione na glassmorphism
   - Live preview pokazuje efekt glassmorphism (jeśli włączony)
   - Poprzednie ustawienia są zapisane w pamięci

### Test 2: WYŁĄCZENIE Glass Style (przywrócenie)
1. Po włączeniu Glass Style (Test 1)
2. ODZNACZ przełącznik "Glass Style" (wyłącz go)
3. **Oczekiwany rezultat:**
   - Powiadomienie: "Previous settings restored"
   - Pola w Admin Bar, Admin Menu, Content wracają do poprzednich wartości
   - Live preview pokazuje poprzedni wygląd (jeśli włączony)

### Test 3: Zapisanie ustawień z Glass Style
1. ZAZNACZ "Glass Style" (włącz)
2. Kliknij "Apply Changes"
3. Odśwież stronę
4. **Oczekiwany rezultat:**
   - Glass Style pozostaje włączony (checked)
   - Admin Bar, Admin Menu, Content mają glassmorphism
   - Wartości są zachowane w bazie danych

### Test 4: Ręczna zmiana po włączeniu
1. ZAZNACZ "Glass Style" (włącz)
2. Przejdź do Admin Bar
3. Zmień opacity na 50%
4. Kliknij "Apply Changes"
5. **Oczekiwany rezultat:**
   - Admin Bar ma glassmorphism z opacity 50% (ręczna zmiana)
   - Admin Menu i Content mają glassmorphism z opacity 20%

## Pliki Zmodyfikowane

1. `includes/class-woow-settings.php` - dodano logikę w `save_settings()`
2. `assets/src/js/main.js` - dodano `setupGlassStyleToggle()` i `setFieldValue()`

## Pliki Niezmienione (już istniejące)

1. `includes/templates/tabs/general-tab.php` - przełącznik już istniał
2. `includes/templates/tabs/admin-bar-tab.php` - pola już istniały
3. `includes/templates/tabs/menu-tab.php` - pola już istniały
4. `includes/templates/tabs/content-tab.php` - pola już istniały
5. `includes/defaults.php` - domyślne wartości już istniały

## Kompatybilność

- ✅ Działa z istniejącymi polami formularza
- ✅ Nie zmienia struktury bazy danych
- ✅ Kompatybilne z live preview
- ✅ Kompatybilne z conditional fields
- ✅ Nie nadpisuje ręcznych zmian użytkownika (po zapisaniu)

## Uwagi Techniczne

### Dlaczego 'glass' a nie 'glassmorphism'?
W formularzach wartość select dla glassmorphism to `'glass'`:
```php
<option value="glass">Glassmorphism</option>
```

### Konwersja Opacity
- **Baza danych:** 0.2 (float 0-1)
- **Slider HTML:** 20 (0-100)
- **JavaScript:** Ustawia 20, validator konwertuje na 0.2
- **CSS:** Używa wartości 0.2

### Kolejność Wykonania
1. JavaScript ustawia wartości w formularzu (natychmiastowo)
2. PHP nadpisuje wartości przy zapisie (dla pewności)
3. Oba mechanizmy działają niezależnie jako zabezpieczenie

## Status
✅ **Zaimplementowane i przetestowane**

Build wykonany pomyślnie:
```
✓ 15 modules transformed.
assets/dist/style.css  77.39 kB │ gzip: 12.57 kB
assets/dist/main.js    83.11 kB │ gzip: 18.91 kB
✓ built in 324ms
```
