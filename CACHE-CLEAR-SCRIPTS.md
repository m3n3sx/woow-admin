# Cache Clear Scripts

## Szybkie użycie

### Pełne czyszczenie (z informacjami)
```bash
cd woow-admin
./clear-cache.sh
```

### Szybkie czyszczenie (bez outputu)
```bash
cd woow-admin
./cc.sh
```

## Co jest czyszczone?

### 1. WordPress Transients
- Wszystkie transients w bazie danych
- WOOW! Admin specific transients (`woow_generated_css`, `woow_settings_cache`)

### 2. Object Cache
- Redis/Memcached cache (jeśli zainstalowany)
- WordPress object cache

### 3. Rewrite Rules
- Flush rewrite rules

### 4. PHP OPcache
- Opcache reset (jeśli dostępny)

### 5. Cache Files
- `wp-content/cache/*` directory

## Wymagania

### Opcjonalne (ale zalecane):
- **WP-CLI** - do czyszczenia transients i cache
  ```bash
  # Sprawdź czy masz WP-CLI
  wp --version
  
  # Instalacja (jeśli nie masz)
  curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
  chmod +x wp-cli.phar
  sudo mv wp-cli.phar /usr/local/bin/wp
  ```

### Bez WP-CLI:
Skrypty będą działać, ale pominą niektóre operacje (transients, object cache).

## Przykłady użycia

### Po zmianie CSS/JS
```bash
cd woow-admin
npm run build
./cc.sh
```

### Po zmianie ustawień PHP
```bash
cd woow-admin
./clear-cache.sh
```

### Automatyczne czyszczenie po build
Dodaj do `package.json`:
```json
{
  "scripts": {
    "build": "vite build",
    "build:clean": "vite build && ./cc.sh"
  }
}
```

Użycie:
```bash
npm run build:clean
```

## Troubleshooting

### Skrypt nie działa
```bash
# Upewnij się że jest wykonywalny
chmod +x clear-cache.sh
chmod +x cc.sh

# Uruchom bezpośrednio przez bash
bash clear-cache.sh
```

### Brak uprawnień
```bash
# Jeśli potrzebujesz sudo
sudo ./clear-cache.sh

# Lub zmień właściciela cache
sudo chown -R $USER:$USER wp-content/cache
```

### WP-CLI wymaga --allow-root
Skrypty już zawierają `--allow-root` flag.

### Cache nadal nie jest czyszczony

1. **Sprawdź czy masz plugin cache:**
   - WP Super Cache
   - W3 Total Cache
   - WP Rocket
   - LiteSpeed Cache
   
   Wyczyść cache przez panel admina tych pluginów.

2. **Sprawdź server cache:**
   - Nginx FastCGI cache
   - Varnish
   - Cloudflare
   
   Skontaktuj się z hostem lub wyczyść przez panel.

3. **Sprawdź browser cache:**
   ```
   Chrome/Edge: Ctrl+Shift+R (Cmd+Shift+R na Mac)
   Firefox: Ctrl+Shift+Delete
   Safari: Cmd+Option+E
   ```

4. **Użyj Incognito/Private mode:**
   - Otwórz stronę w trybie prywatnym
   - Jeśli działa, to problem jest w cache przeglądarki

## Aliasy (opcjonalne)

Dodaj do `~/.bashrc` lub `~/.zshrc`:

```bash
# WOOW! Admin cache clear
alias woow-cc='cd /path/to/woow-admin && ./cc.sh'
alias woow-clear='cd /path/to/woow-admin && ./clear-cache.sh'
```

Użycie:
```bash
woow-cc          # Szybkie czyszczenie
woow-clear       # Pełne czyszczenie
```

## Integracja z IDE

### VS Code
Dodaj do `.vscode/tasks.json`:
```json
{
  "version": "2.0.0",
  "tasks": [
    {
      "label": "Clear Cache",
      "type": "shell",
      "command": "./cc.sh",
      "options": {
        "cwd": "${workspaceFolder}/woow-admin"
      },
      "problemMatcher": []
    }
  ]
}
```

Użycie: `Ctrl+Shift+P` → "Run Task" → "Clear Cache"

### PhpStorm
1. Settings → Tools → External Tools
2. Add new tool:
   - Name: Clear WOOW Cache
   - Program: `$ProjectFileDir$/woow-admin/cc.sh`
   - Working directory: `$ProjectFileDir$/woow-admin`

Użycie: Tools → External Tools → Clear WOOW Cache

## Automatyzacja

### Git Hook (po pull)
Utwórz `.git/hooks/post-merge`:
```bash
#!/bin/bash
echo "Clearing cache after git pull..."
cd woow-admin && ./cc.sh
```

```bash
chmod +x .git/hooks/post-merge
```

### Cron (co godzinę)
```bash
# Edytuj crontab
crontab -e

# Dodaj linię
0 * * * * cd /path/to/woow-admin && ./cc.sh > /dev/null 2>&1
```

## Logi

Skrypty nie tworzą logów. Jeśli chcesz logować:

```bash
./clear-cache.sh 2>&1 | tee cache-clear.log
```

## Bezpieczeństwo

- Skrypty NIE usuwają `advanced-cache.php` ani `object-cache.php`
- Skrypty NIE modyfikują plików wtyczki
- Skrypty NIE usuwają ustawień z bazy danych
- Bezpieczne do użycia w production

## Performance

- Pełne czyszczenie: ~2-5 sekund
- Szybkie czyszczenie: ~1-2 sekundy

## Wsparcie

Jeśli masz problemy:
1. Sprawdź czy skrypt jest wykonywalny (`ls -la *.sh`)
2. Sprawdź logi WordPress (`wp-content/debug.log`)
3. Sprawdź logi serwera
4. Spróbuj ręcznie przez WP-CLI: `wp cache flush`
