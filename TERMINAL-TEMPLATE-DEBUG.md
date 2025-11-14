# Terminal Template - Debugging Guide

## Problem
Szablon Terminal nie jest widoczny w interfejsie WOOW! Admin.

## Status Implementacji

### ✅ Zrobione:
1. Metoda `get_terminal_template()` została dodana do `class-woow-template-manager.php`
2. Szablon został dodany do listy w `get_all_templates()`
3. Folder `assets/dist/images/templates/` został utworzony
4. Plik placeholder `terminal.png` został utworzony
5. Cache został wyczyszczony

### 🔍 Kroki Debugowania:

#### 1. Sprawdź, czy szablon jest dostępny w PHP
Uruchom plik testowy:
```
http://twoja-domena.local/wp-content/plugins/woow-admin/test-terminal-template.php
```

Ten skrypt pokaże:
- Listę wszystkich dostępnych szablonów
- Czy szablon Terminal jest w liście
- Szczegóły szablonu Terminal

#### 2. Sprawdź w konsoli przeglądarki
Otwórz DevTools (F12) i sprawdź:
- Czy są błędy JavaScript
- Czy szablon Terminal jest w odpowiedzi API

#### 3. Sprawdź w WordPress Admin
1. Przejdź do: **WOOW! Admin → Templates**
2. Sprawdź, czy widzisz kartę "Terminal"
3. Jeśli nie widzisz, sprawdź konsolę przeglądarki

#### 4. Sprawdź cache WordPress
Wyczyść wszystkie cache:
```bash
cd woow-admin
./cc.sh
```

Lub ręcznie:
```bash
# Wyczyść cache WordPress
wp cache flush

# Wyczyść cache opcji
wp option delete woow_settings
wp option delete woow_custom_templates
```

#### 5. Sprawdź, czy plik został poprawnie zapisany
```bash
cd woow-admin
grep -n "get_terminal_template" includes/class-woow-template-manager.php
```

Powinno pokazać linię z definicją metody.

#### 6. Sprawdź logi błędów
```bash
tail -f /path/to/wordpress/wp-content/debug.log
```

## Możliwe Przyczyny

### 1. Cache nie został wyczyszczony
**Rozwiązanie:**
```bash
cd woow-admin
./cc.sh
# Następnie w przeglądarce: Ctrl+Shift+R
```

### 2. Autoloader nie załadował klasy
**Rozwiązanie:**
Sprawdź, czy klasa `WOOW_Template_Manager` jest poprawnie załadowana:
```php
<?php
require_once('../../../wp-load.php');
var_dump(class_exists('WOOW_Template_Manager'));
```

### 3. Błąd składni PHP
**Rozwiązanie:**
Sprawdź logi błędów PHP:
```bash
php -l includes/class-woow-template-manager.php
```

### 4. Szablon nie jest zwracany przez API
**Rozwiązanie:**
Sprawdź endpoint REST API:
```
GET /wp-json/woow/v1/templates
```

## Szybki Fix

Jeśli szablon nadal nie jest widoczny, spróbuj:

```bash
cd woow-admin

# 1. Wyczyść cache
./cc.sh

# 2. Zrestartuj PHP-FPM (jeśli używasz)
sudo service php8.2-fpm restart

# 3. Wyczyść cache przeglądarki
# Ctrl+Shift+R w przeglądarce

# 4. Sprawdź, czy plik jest poprawny
php -l includes/class-woow-template-manager.php

# 5. Sprawdź, czy metoda istnieje
grep -A 5 "function get_terminal_template" includes/class-woow-template-manager.php
```

## Weryfikacja Końcowa

Po wykonaniu powyższych kroków, szablon Terminal powinien być widoczny w:
1. Liście szablonów w panelu WOOW! Admin
2. Odpowiedzi API `/wp-json/woow/v1/templates`
3. Skrypcie testowym `test-terminal-template.php`

## Kontakt

Jeśli problem nadal występuje, sprawdź:
- Wersję PHP (wymaga PHP 8.0+)
- Wersję WordPressa (wymaga WP 6.0+)
- Czy plugin jest aktywny
- Czy nie ma konfliktów z innymi pluginami
