# Debug: Second Save Not Working

## Problem

Pierwszy zapis działa ✅, ale kolejne próby nie działają ❌

## Możliwe Przyczyny

### 1. Nonce Expiration
WordPress nonce może wygasać po pierwszym użyciu w niektórych konfiguracjach.

### 2. Button State
Przycisk może pozostać disabled po pierwszym zapisie.

### 3. JavaScript State
Stan aplikacji może blokować kolejne requesty.

### 4. Rate Limiting
Choć limit to 60/min, może być błąd w implementacji.

---

## Debugging Steps

### Krok 1: Sprawdź Konsolę Przeglądarki

Po drugim kliknięciu "Apply Changes", sprawdź co pokazuje konsola:

**Oczekiwane logi:**
```javascript
[WOOW Admin] Save button clicked!
[WOOW Admin] saveSettings() called
[WOOW Admin] Current nonce: abc123...
[WOOW Admin] AJAX URL: /wp-admin/admin-ajax.php
[WOOW Admin] Save button disabled
[WOOW Admin] Form data saved to localStorage
[WOOW Admin] Fetch attempt 1/2
```

**Sprawdź:**
- Czy wszystkie logi się pojawiają?
- Czy nonce jest taki sam jak za pierwszym razem?
- Czy pojawia się jakiś błąd?

### Krok 2: Sprawdź Network Tab

1. Otwórz DevTools → Network tab
2. Filtruj po "XHR"
3. Kliknij "Apply Changes" drugi raz
4. Sprawdź czy request jest wysyłany

**Jeśli NIE MA requestu:**
- Problem jest w JavaScript (button disabled, event listener usunięty)

**Jeśli JEST request:**
- Sprawdź Response
- Sprawdź Status Code (200? 500? 403?)

### Krok 3: Sprawdź Debug Log

```bash
tail -f wp-content/debug.log
```

Kliknij "Apply Changes" drugi raz i sprawdź:

**Oczekiwane:**
```
[WOOW Admin] ajax_save_settings called
[WOOW Admin] POST data: Array ( [0] => action [1] => nonce [2] => settings )
[WOOW Admin] Nonce received: abc123...
[WOOW Admin] Nonce verified successfully
[WOOW Admin] Settings received: Array (...)
[WOOW Admin] Settings saved successfully
```

**Jeśli brak logów:**
- Request nie dociera do serwera

**Jeśli "Nonce verification failed":**
- Nonce wygasł lub jest nieprawidłowy

**Jeśli "Rate limit exceeded":**
- Rate limiting blokuje request

---

## Quick Fixes

### Fix 1: Sprawdź czy przycisk jest enabled

W konsoli przeglądarki:
```javascript
const btn = document.querySelector('#woow-save-btn');
console.log('Button disabled:', btn.disabled);
console.log('Button text:', btn.textContent);
```

**Jeśli disabled = true:**
```javascript
btn.disabled = false;
btn.textContent = 'Apply Changes';
```

### Fix 2: Sprawdź czy event listener działa

```javascript
const btn = document.querySelector('#woow-save-btn');
btn.addEventListener('click', () => console.log('CLICK!'));
// Kliknij przycisk - czy widzisz "CLICK!" w konsoli?
```

### Fix 3: Wymuś nowy nonce

Odśwież całą stronę (F5) przed drugim zapisem.

**Jeśli to pomaga:**
- Problem z nonce expiration

### Fix 4: Wyłącz rate limiting tymczasowo

W `class-woow-admin.php`, zmień:
```php
private const RATE_LIMIT = 60;
```
Na:
```php
private const RATE_LIMIT = 999;
```

---

## Typowe Scenariusze

### Scenariusz A: Button pozostaje disabled

**Objawy:**
- Przycisk jest szary
- Tekst "Saving..." nie zmienia się z powrotem
- Kliknięcie nic nie robi

**Przyczyna:**
- JavaScript nie przywraca stanu przycisku po zapisie

**Fix:**
Sprawdź czy w `saveSettings()` jest `finally` block:
```javascript
finally {
    const saveButton = document.querySelector('#woow-save-btn');
    if (saveButton) {
        saveButton.disabled = false;
        this.updateSaveButtonState();
    }
}
```

### Scenariusz B: Nonce wygasa

**Objawy:**
- Pierwszy zapis OK
- Drugi zapis: "Security check failed"
- Debug log: "Nonce verification failed"

**Przyczyna:**
- WordPress nonce może wygasać po użyciu

**Fix:**
Odśwież nonce po każdym zapisie:
```javascript
if (result.success && result.data.new_nonce) {
    this.nonce = result.data.new_nonce;
    console.log('[WOOW Admin] Nonce refreshed:', this.nonce);
}
```

### Scenariusz C: Rate limiting

**Objawy:**
- Pierwszy zapis OK
- Drugi zapis: "Rate limit exceeded"
- Debug log: "Rate limit exceeded"

**Przyczyna:**
- Transient nie jest czyszczony poprawnie

**Fix:**
Zwiększ limit lub wyczyść transient:
```php
delete_transient('woow_rate_limit_' . get_current_user_id());
```

---

## Permanent Fixes

### Fix 1: Ensure Button Re-enables

W `main.js`, upewnij się że `finally` block zawsze wykonuje się:

```javascript
async saveSettings() {
    try {
        // ... save logic ...
    } catch (error) {
        // ... error handling ...
    } finally {
        // ALWAYS re-enable button
        const saveButton = document.querySelector('#woow-save-btn');
        if (saveButton) {
            saveButton.disabled = false;
            this.updateSaveButtonState();
        }
        console.log('[WOOW Admin] Save button re-enabled');
    }
}
```

### Fix 2: Refresh Nonce After Save

W PHP AJAX handler, zwróć nowy nonce:

```php
wp_send_json_success(array(
    'message'  => __('Settings saved successfully', 'woow-admin'),
    'settings' => $updated_settings,
    'css'      => $css,
    'metrics'  => $metrics,
    'new_nonce' => wp_create_nonce('woow_admin_nonce'), // ← Dodaj to
));
```

W JavaScript, użyj nowego nonce:

```javascript
if (result.success) {
    // Update nonce if provided
    if (result.data.new_nonce) {
        this.nonce = result.data.new_nonce;
    }
    // ... rest of success handling ...
}
```

### Fix 3: Better Rate Limiting

Zmień implementację aby nie blokować zbyt agresywnie:

```php
private function check_rate_limit(): bool {
    $user_id = get_current_user_id();
    $key     = 'woow_rate_limit_' . $user_id;
    $count   = (int) get_transient($key);

    // Allow 10 requests per minute (not 60)
    if ($count >= 10) {
        error_log("[WOOW Admin] Rate limit hit: $count requests");
        return false;
    }

    // Increment counter with 60 second expiry
    set_transient($key, $count + 1, 60);
    return true;
}
```

---

## Testing Checklist

Po zastosowaniu fixów, przetestuj:

- [ ] Pierwszy zapis działa
- [ ] Drugi zapis działa
- [ ] Trzeci zapis działa
- [ ] Przycisk wraca do stanu "Apply Changes"
- [ ] Nie ma błędów w konsoli
- [ ] Nie ma błędów w debug.log
- [ ] Nonce nie wygasa
- [ ] Rate limiting nie blokuje

---

## Next Steps

1. **Sprawdź logi** (konsola + debug.log)
2. **Zidentyfikuj scenariusz** (A, B, lub C)
3. **Zastosuj odpowiedni fix**
4. **Przetestuj ponownie**

Daj znać co pokazują logi!
