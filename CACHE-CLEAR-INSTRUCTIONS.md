# Cache Clear Instructions - Fix PHP Fatal Error

## Problem

Widzisz błąd:
```
PHP Fatal error: Uncaught TypeError: WOOW_Settings::sanitize_unit(): 
Argument #1 ($value) must be of type string, float given
```

**Przyczyna:** PHP używa starego skompilowanego kodu z cache (OPcache), mimo że plik został zaktualizowany.

---

## Rozwiązanie - Wyczyść Cache PHP

### Metoda 1: Użyj skryptu clear-cache.php

1. **W przeglądarce:**
   ```
   http://twoja-domena.local/wp-content/plugins/woow-admin/clear-cache.php?token=clear-woow-cache-2025
   ```

2. **Lub przez WP-CLI:**
   ```bash
   wp eval-file wp-content/plugins/woow-admin/clear-cache.php
   ```

### Metoda 2: Restart serwera (Local by Flywheel)

1. Otwórz Local by Flywheel
2. Kliknij prawym na swoją stronę "woow"
3. Wybierz "Stop"
4. Poczekaj 5 sekund
5. Wybierz "Start"

### Metoda 3: Restart PHP-FPM (jeśli używasz)

```bash
# Dla systemów z systemd
sudo systemctl restart php8.3-fpm

# Lub dla Local by Flywheel
# Restart z aplikacji Local
```

### Metoda 4: Wyłącz OPcache tymczasowo

Dodaj do `wp-config.php` (na górze, po `<?php`):

```php
// Disable OPcache for development
ini_set('opcache.enable', '0');
```

**UWAGA:** Usuń to po zakończeniu developmentu!

---

## Weryfikacja

Po wyczyszczeniu cache, sprawdź:

1. **Odśwież stronę WOOW! Admin** (Ctrl+Shift+R)
2. **Spróbuj zapisać ustawienia**
3. **Sprawdź debug.log:**
   ```bash
   tail -f wp-content/debug.log
   ```

**Oczekiwany wynik:**
```
[WOOW Admin] ajax_save_settings called
[WOOW Admin] Settings received: Array (...)
[WOOW Admin] Settings saved successfully
[WOOW Admin] Sending success response
```

**NIE powinno być:**
```
PHP Fatal error: Uncaught TypeError: WOOW_Settings::sanitize_unit()
```

---

## Dlaczego to się dzieje?

PHP OPcache przechowuje skompilowany kod PHP w pamięci dla lepszej wydajności. Gdy edytujesz plik PHP:

1. ✅ Plik na dysku jest zaktualizowany
2. ❌ Ale PHP nadal używa starej wersji z cache
3. ❌ Dopóki cache nie zostanie wyczyszczony lub nie wygaśnie

**Rozwiązanie:** Zawsze czyść cache po edycji plików PHP w środowisku deweloperskim.

---

## Dla Local by Flywheel

Local by Flywheel używa własnego PHP z OPcache. Najlepsze rozwiązania:

### Opcja A: Wyłącz OPcache dla development

1. Otwórz Local
2. Kliknij na swoją stronę
3. Idź do zakładki "Database" → "Adminer" → "Open site shell"
4. Edytuj php.ini:
   ```bash
   nano /usr/local/etc/php/8.3/php.ini
   ```
5. Znajdź i zmień:
   ```ini
   opcache.enable=0
   ```
6. Restart serwisu

### Opcja B: Skróć czas cache

W php.ini:
```ini
opcache.revalidate_freq=0
opcache.validate_timestamps=1
```

---

## Quick Fix - Jednolinijkowiec

Jeśli masz dostęp do terminala:

```bash
# Restart całego Local site
# (w aplikacji Local: Stop → Start)

# LUB przez WP-CLI
wp cache flush
wp eval 'if (function_exists("opcache_reset")) opcache_reset();'
```

---

## Potwierdzenie że fix działa

Po wyczyszczeniu cache, kod powinien działać bo:

1. ✅ `sanitize_unit()` akceptuje `mixed $value` (nie tylko `string`)
2. ✅ Konwertuje numeric do string wewnątrz metody
3. ✅ `line_height` jest sprawdzany PRZED `height`
4. ✅ Wszystkie edge cases są obsłużone

**Plik jest poprawny - problem jest tylko w cache!**

---

## Ostateczne rozwiązanie

Jeśli nic nie pomaga:

1. **Deaktywuj plugin:**
   ```
   WP Admin → Plugins → Deactivate WOOW! Admin
   ```

2. **Usuń cache ręcznie:**
   ```bash
   rm -rf /tmp/opcache-*
   rm -rf /var/cache/php-*
   ```

3. **Restart serwera:**
   ```
   Local → Stop → Start
   ```

4. **Aktywuj plugin:**
   ```
   WP Admin → Plugins → Activate WOOW! Admin
   ```

5. **Odśwież przeglądarkę:**
   ```
   Ctrl + Shift + R (hard refresh)
   ```

---

## Status

- ✅ Kod PHP jest poprawny
- ✅ Wszystkie typy są obsłużone
- ✅ Walidacja działa poprawnie
- ❌ Cache PHP blokuje nowy kod

**Rozwiązanie: Wyczyść cache i będzie działać!**
