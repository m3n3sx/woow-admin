# Test: Admin Menu Background Color

## Problem
Zmiana koloru tła w admin menu nie działa.

## Kroki testowania

### 1. Sprawdź aktualny stan
Otwórz w przeglądarce: `wp-content/plugins/woow-admin/debug-menu-color.php`

To pokaże:
- ✅ Wartość w bazie danych
- ✅ Wartość zwracaną przez Settings class
- ✅ Wygenerowany CSS
- ✅ Test walidacji kolorów

### 2. Sprawdź konsolę przeglądarki
1. Otwórz WordPress Admin → WOOW! Admin
2. Otwórz Developer Tools (F12)
3. Przejdź do zakładki "Console"
4. Przejdź do Admin Menu tab
5. Zmień "Background Type" na "Solid Color"
6. Zmień kolor na czerwony (#ff0000)
7. Kliknij "Apply Changes"

**Szukaj w konsoli:**
```
[collectFormData] background_color found (VISIBLE): {
    section: "admin_menu",
    value: "#ff0000",
    visible: true,
    ...
}
```

### 3. Sprawdź Network tab
1. W Developer Tools przejdź do zakładki "Network"
2. Zmień kolor i kliknij "Apply Changes"
3. Znajdź request do `admin-ajax.php`
4. Sprawdź "Payload" - czy zawiera `admin_menu[background_color]`?

### 4. Sprawdź PHP logs
```bash
tail -f /path/to/wordpress/wp-content/debug.log
```

Szukaj:
```
[WOOW Admin] Validation error: admin_menu.background_color
```

## Możliwe przyczyny

### A. Pole jest ukryte (nie zbierane przez JavaScript)
**Symptom:** W konsoli nie ma `[collectFormData] background_color found`

**Rozwiązanie:**
- Sprawdź, czy `background_type` jest ustawiony na `solid`
- Sprawdź, czy ConditionalFields działa (pole powinno być widoczne)

### B. Walidacja odrzuca wartość
**Symptom:** W PHP logs jest błąd walidacji

**Rozwiązanie:**
- Sprawdź, czy metoda `sanitize_color()` akceptuje format koloru
- Uruchom `test-color-validation.php` aby przetestować walidację

### C. Wartość jest zapisywana, ale CSS nie jest generowany
**Symptom:** W bazie danych jest prawidłowa wartość, ale CSS ma inny kolor

**Rozwiązanie:**
- Sprawdź `class-woow-css-generator.php` linię ~720
- Sprawdź, czy `$background_color` jest używany w CSS

### D. CSS jest nadpisywany przez inny styl
**Symptom:** CSS jest prawidłowy, ale menu ma inny kolor

**Rozwiązanie:**
- Sprawdź w Developer Tools → Elements → Computed styles
- Szukaj, który styl ma wyższą specyficzność

## Quick Fix

Jeśli problem nadal występuje, spróbuj:

1. **Wyczyść cache:**
```bash
rm -rf wp-content/cache/*
```

2. **Przebuduj assets:**
```bash
cd wp-content/plugins/woow-admin
npm run build
```

3. **Wymuś odświeżenie CSS:**
- Ctrl+Shift+R (hard refresh)
- Lub wyczyść cache przeglądarki

4. **Reset ustawień:**
- W WOOW! Admin kliknij "Reset"
- Ustaw kolor ponownie

## Oczekiwany rezultat

Po zmianie koloru na czerwony (#ff0000):
- ✅ Konsola pokazuje: `background_color found (VISIBLE): { value: "#ff0000" }`
- ✅ Network pokazuje: payload zawiera `admin_menu[background_color]: "#ff0000"`
- ✅ PHP logs: brak błędów walidacji
- ✅ `debug-menu-color.php` pokazuje: `background_color: #ff0000`
- ✅ CSS zawiera: `background: #ff0000 !important;`
- ✅ Menu ma czerwone tło
