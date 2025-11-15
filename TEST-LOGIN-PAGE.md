# Test Login Page - Diagnostyka

## Kroki testowania

### 1. Wyczyść cache przeglądarki
**WAŻNE:** Musisz wyczyścić cache!

**Chrome/Edge:**
- Ctrl+Shift+Delete
- Wybierz "Cached images and files"
- Kliknij "Clear data"

**Firefox:**
- Ctrl+Shift+Delete
- Wybierz "Cache"
- Kliknij "Clear Now"

**Lub użyj Hard Refresh:**
- Ctrl+Shift+R (Windows/Linux)
- Cmd+Shift+R (Mac)

### 2. Sprawdź konsolę przeglądarki
1. Otwórz WordPress Admin → WOOW! Admin
2. Naciśnij F12 (Developer Tools)
3. Przejdź do zakładki "Console"
4. Szukaj:
   ```
   [MediaUploader] Initialized
   ```

Jeśli NIE widzisz tej linii:
- ❌ JavaScript nie został załadowany
- ❌ Lub cache przeglądarki nie został wyczyszczony

### 3. Test pól kolorów
1. Przejdź do Login Page
2. Zmień "Background Type" na "Solid Color"
3. Pole "Background Color" powinno się pokazać
4. Zmień kolor na czerwony (#ff0000)
5. Kliknij "Apply Changes"
6. Sprawdź w konsoli:
   ```
   [collectFormData] background_color found (VISIBLE): {
       section: "login_page",
       value: "#ff0000",
       ...
   }
   ```

### 4. Test przycisku Upload
1. Kliknij "Upload Logo"
2. Sprawdź w konsoli:
   - Jeśli widzisz: `[MediaUploader] No target specified` → Problem z atrybutem data-target
   - Jeśli widzisz: `[MediaUploader] WordPress media library not available` → wp.media nie jest załadowany
   - Jeśli nic się nie dzieje → Event listener nie działa

3. Powinna otworzyć się WordPress Media Library
4. Wybierz obraz
5. Sprawdź w konsoli:
   ```
   [MediaUploader] Image selected: http://...
   ```

### 5. Sprawdź, czy wp.media jest dostępny
W konsoli przeglądarki wpisz:
```javascript
typeof wp
typeof wp.media
```

Powinno zwrócić:
```
"object"
"function"
```

Jeśli zwraca `"undefined"`:
- ❌ WordPress Media Library nie jest załadowany
- ❌ Sprawdź, czy `wp_enqueue_media()` jest wywołany

### 6. Sprawdź Network tab
1. Otwórz Developer Tools → Network
2. Odśwież stronę (F5)
3. Znajdź request do `main.js`
4. Sprawdź:
   - Status: 200 OK
   - Size: ~63 KB
   - Time: < 1s

Jeśli plik jest mniejszy niż 60 KB:
- ❌ Stary plik jest w cache
- ❌ Wyczyść cache i spróbuj ponownie

## Częste problemy

### Problem 1: "Nic się nie dzieje po kliknięciu Upload"
**Przyczyna:** Cache przeglądarki
**Rozwiązanie:** 
1. Ctrl+Shift+Delete
2. Wyczyść cache
3. Ctrl+Shift+R (hard refresh)

### Problem 2: "Media Library nie otwiera się"
**Przyczyna:** wp.media nie jest załadowany
**Rozwiązanie:**
1. Sprawdź w konsoli: `typeof wp.media`
2. Jeśli `undefined`, sprawdź czy `wp_enqueue_media()` jest w kodzie
3. Sprawdź plik: `includes/class-woow-admin.php` linia ~214

### Problem 3: "Kolory nie zapisują się"
**Przyczyna:** Brak `name` w text input
**Rozwiązanie:**
1. Sprawdź kod źródłowy strony (Ctrl+U)
2. Znajdź: `<input type="text" name="login_page[background_color]"`
3. Jeśli brak `name` → plik template nie został zaktualizowany

### Problem 4: "Conditional fields nie działają"
**Przyczyna:** Nieprawidłowy format `data-show-when`
**Rozwiązanie:**
1. Sprawdź kod źródłowy
2. Powinno być: `data-show-when="login_page[background_type]=color"`
3. NIE: `data-show-when="#login-bg-type=color"`

## Weryfikacja plików

### Sprawdź, czy pliki zostały zaktualizowane:

```bash
# Data modyfikacji plików
stat -c '%y %n' includes/templates/tabs/login-tab.php
stat -c '%y %n' assets/dist/main.js
stat -c '%y %n' includes/class-woow-admin.php
```

Wszystkie powinny mieć datę dzisiejszą (2025-11-15).

### Sprawdź zawartość plików:

```bash
# Czy wp_enqueue_media() jest w pliku?
grep -n "wp_enqueue_media" includes/class-woow-admin.php

# Czy MediaUploader jest w zbudowanym JS?
grep -c "MediaUploader" assets/dist/main.js

# Czy pola mają prawidłowy name?
grep 'name="login_page\[background_color\]"' includes/templates/tabs/login-tab.php
```

## Jeśli nadal nie działa

1. **Wyłącz wszystkie inne pluginy** - może być konflikt
2. **Zmień motyw na domyślny** (Twenty Twenty-Four)
3. **Sprawdź logi PHP** - może być błąd PHP
4. **Sprawdź logi JavaScript** - może być błąd JS

## Logi do sprawdzenia

### PHP logs:
```bash
tail -f /path/to/wordpress/wp-content/debug.log
```

### JavaScript console:
- Otwórz Developer Tools (F12)
- Zakładka "Console"
- Szukaj czerwonych błędów

## Kontakt

Jeśli problem nadal występuje, wyślij:
1. Screenshot konsoli przeglądarki
2. Screenshot Network tab (main.js request)
3. Output z: `typeof wp.media`
4. Output z: `grep "wp_enqueue_media" includes/class-woow-admin.php`
