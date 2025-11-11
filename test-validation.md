# Test Validation - Quick Check

## Po rebuild sprawdź:

### 1. Hard Refresh przeglądarki
```
Ctrl + Shift + R (Windows/Linux)
Cmd + Shift + R (Mac)
```

### 2. Otwórz konsolę (F12) i sprawdź:

**Powinno być:**
```
[WOOW Admin] Initialized successfully
[WOOW Admin] Conditional fields initialized: 23
```

**NIE powinno być:**
```
Uncaught TypeError: ...
Cannot read property 'validateAll' ...
```

### 3. Kliknij "Save Changes" i sprawdź logi:

**Powinno być:**
```
[WOOW Admin] saveSettings() called
[WOOW Admin] Current nonce: ...
[WOOW Admin] Form data saved to localStorage
[WOOW Admin] Fetch attempt 1/2
```

**Jeśli są błędy walidacji:**
```
[WOOW Admin] Validation error: {field: '...', message: '...', value: '...'}
```

### 4. Sprawdź Network tab (F12 → Network):

Kliknij Save i zobacz request do `admin-ajax.php`:
- **Status:** powinien być 200 (nie 500)
- **Response:** powinien zawierać `{"success":true,...}`

### 5. Jeśli nadal nie działa:

**A. Sprawdź czy plik jest załadowany:**
```javascript
// W konsoli przeglądarki:
window.woowAdmin
// Powinno pokazać obiekt, nie undefined
```

**B. Sprawdź czy validator jest dostępny:**
```javascript
// W konsoli:
window.woowAdmin.validator
// Powinno pokazać klasę Validator
```

**C. Sprawdź timestamp pliku:**
```bash
ls -lh assets/dist/main.js
# Powinien być z dzisiejszej daty 19:xx
```

## Jeśli widzisz konkretny błąd, skopiuj go tutaj!

Przykład:
```
Uncaught TypeError: Cannot read property 'validateAll' of undefined
    at WoowAdmin.saveSettings (main.js:27)
```

Lub:
```
[WOOW Admin] Validation error: {field: 'admin_bar.submenu_border_radius', ...}
```
