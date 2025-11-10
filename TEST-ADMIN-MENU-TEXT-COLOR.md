# Test Admin Menu Text Color - Quick Guide

## Szybki test

### 1. Otwórz ustawienia
- Przejdź do WOOW! Admin
- Kliknij zakładkę "Menu Styling"

### 2. Zmień kolor tekstu
- Znajdź sekcję "Colors"
- Kliknij na "Text Color"
- Wybierz wyraźny kolor (np. czerwony #ff0000)
- Kliknij "Apply Changes"

### 3. Sprawdź WordPress Admin Menu
- Przejdź do dowolnej strony WordPress Admin (np. Dashboard)
- **Oczekiwany rezultat:** Menu po lewej stronie ma nowy kolor tekstu

## Szczegółowe testy

### ✅ Test 1: Normalny stan menu
- [ ] Wszystkie menu items mają nowy kolor
- [ ] Ikony mają nowy kolor (lekko przezroczyste)
- [ ] Tekst jest czytelny

### ✅ Test 2: Hover state
- [ ] Najedź na menu item
- [ ] Kolor tekstu pozostaje taki sam
- [ ] Ikona staje się bardziej widoczna

### ✅ Test 3: Active state
- [ ] Aktywny menu item ma gradient background
- [ ] Tekst aktywnego item jest biały (nie zmienia się)
- [ ] Ikona aktywnego item jest biała

### ✅ Test 4: Submenu
- [ ] Rozwiń submenu (np. Posts → All Posts)
- [ ] Submenu items mają nowy kolor tekstu
- [ ] Hover na submenu działa
- [ ] Current submenu item ma kolor active gradient

### ✅ Test 5: Collapsed menu
- [ ] Kliknij "Collapse menu" w WordPress
- [ ] Kolor ikon pozostaje taki sam
- [ ] Tooltip pokazuje się poprawnie

## Przykładowe kolory do testowania

| Kolor | Hex | Użycie |
|-------|-----|--------|
| Czarny | #000000 | Jasne tło |
| Biały | #ffffff | Ciemne tło |
| Czerwony | #ff0000 | Test kontrastu |
| Niebieski | #0066cc | Profesjonalny |
| Zielony | #00aa00 | Przyjazny |
| Szary | #666666 | Neutralny |

## Debugowanie

### Problem: Kolor się nie zmienia

1. **Sprawdź console na błędy:**
   - F12 → Console
   - Szukaj błędów AJAX

2. **Sprawdź czy CSS został wygenerowany:**
   ```javascript
   // W console
   document.querySelector('style#woow-dynamic-css')?.textContent.includes('adminmenu')
   ```

3. **Wyczyść cache:**
   - Ctrl+Shift+R (hard refresh)
   - Lub Settings → Clear All Caches

4. **Sprawdź zapisane ustawienie:**
   - DevTools → Application → Local Storage
   - Lub sprawdź w bazie danych

### Problem: Kolor jest ale słabo widoczny

1. **Sprawdź kontrast:**
   - Użyj https://webaim.org/resources/contrastchecker/
   - Min 4.5:1 dla WCAG AA

2. **Zmień kolor tła menu:**
   - Jasne tło → ciemny tekst
   - Ciemne tło → jasny tekst

## Sukces!

Jeśli wszystkie testy przeszły, kolor tekstu admin menu działa poprawnie! ✅

## Zgłaszanie problemów

Jeśli coś nie działa:
1. Zrób screenshot
2. Sprawdź console na błędy
3. Zapisz ustawienia które testujesz
4. Sprawdź wersję WordPress i przeglądarki
