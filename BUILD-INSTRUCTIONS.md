# Build Instructions - WOOW! Admin

## Zmiany wymagające rebuildu

Zostały wprowadzone następujące zmiany w plikach źródłowych JavaScript:

### 1. Race Condition Fix
- `assets/src/js/components/TemplateSelector.js`
- `assets/src/js/components/PaletteSelector.js`

### 2. Palette Colors-Only Fix
- `includes/class-woow-palette-manager.php` (nie wymaga rebuildu - PHP)

## Metoda 1: Użyj skryptu build.sh

```bash
cd woow-admin
./build.sh
```

## Metoda 2: Użyj npm bezpośrednio

```bash
cd woow-admin
npm run build
```

## Metoda 3: Użyj npx vite

```bash
cd woow-admin
npx vite build
```

## Weryfikacja buildu

Po zbudowaniu sprawdź czy pliki zostały zaktualizowane:

```bash
ls -lh assets/dist/
```

Powinieneś zobaczyć:
- `main.js` - zaktualizowany timestamp
- `style.css` - zaktualizowany timestamp

## Testowanie po buildzie

### 1. Wyczyść cache WordPress
```bash
# Jeśli używasz WP-CLI
wp cache flush

# Lub ręcznie w WordPress admin
# Settings → WOOW! Admin → Clear Cache
```

### 2. Hard refresh w przeglądarce
- Chrome/Firefox: `Ctrl + Shift + R` (Windows/Linux)
- Chrome/Firefox: `Cmd + Shift + R` (Mac)

### 3. Otwórz konsolę przeglądarki (F12)

### 4. Test Race Condition Fix

1. Przejdź do: **WOOW! Admin → Templates**
2. Kliknij "Apply" na dowolnym template
3. Sprawdź w konsoli:
   ```
   ✅ POWINNO BYĆ:
   [TemplateSelector] Applying template: ocean_breeze
   
   ❌ NIE POWINNO BYĆ:
   [TemplateSelector] Applying template: ocean_breeze
   [TemplateSelector] Applying template: ocean_breeze  ← duplikat!
   ```

4. Sprawdź w PHP error log:
   ```
   ✅ POWINNO BYĆ:
   [WOOW Admin] ajax_apply_template called
   [WOOW Admin] Applying template: ocean_breeze
   [WOOW Template Manager] Created backup...
   
   ❌ NIE POWINNO BYĆ:
   [WOOW Admin] ajax_apply_template called
   [WOOW Admin] ajax_apply_template called  ← duplikat!
   [WOOW Template Manager] Error BACKUP_FAILED
   ```

### 5. Test Palette Colors-Only Fix

1. Ustaw niestandardowe tło:
   - **WOOW! Admin → Backgrounds**
   - Upload custom image
   - Save

2. Ustaw niestandardową czcionkę:
   - **WOOW! Admin → Typography**
   - Change body font size to 18px
   - Save

3. Aplikuj paletę:
   - **WOOW! Admin → Palettes**
   - Click "Apply" on any palette

4. Sprawdź:
   ```
   ✅ POWINNO BYĆ:
   - Kolory się zmieniły (tło, tekst, przyciski)
   - Obraz tła NIE zmienił się
   - Rozmiar czcionki NIE zmienił się (nadal 18px)
   
   ❌ NIE POWINNO BYĆ:
   - Obraz tła został usunięty/zmieniony
   - Rozmiar czcionki wrócił do domyślnego
   ```

## Troubleshooting

### Problem: "npm: command not found"
**Rozwiązanie:** Zainstaluj Node.js i npm
```bash
# Ubuntu/Debian
sudo apt install nodejs npm

# macOS
brew install node

# Windows
# Download from https://nodejs.org/
```

### Problem: "node_modules not found"
**Rozwiązanie:** Zainstaluj dependencies
```bash
cd woow-admin
npm install
```

### Problem: Build się nie uruchamia
**Rozwiązanie:** Sprawdź wersję Node.js
```bash
node --version  # Powinno być >= 18.0.0
npm --version   # Powinno być >= 9.0.0
```

### Problem: Zmiany nie są widoczne po buildzie
**Rozwiązanie:**
1. Wyczyść cache przeglądarki (Ctrl+Shift+Delete)
2. Wyczyść cache WordPress
3. Sprawdź czy timestamp pliku się zmienił:
   ```bash
   stat assets/dist/main.js
   ```

### Problem: Błędy w konsoli po buildzie
**Rozwiązanie:**
1. Sprawdź czy build zakończył się sukcesem (brak błędów)
2. Sprawdź czy wszystkie pliki zostały wygenerowane
3. Sprawdź logi Vite podczas buildu

## Pliki wygenerowane przez build

```
assets/dist/
├── main.js          # Główny plik JavaScript (bundled)
└── style.css        # Główny plik CSS (bundled)
```

## Co robi build?

1. **Bundling** - Łączy wszystkie pliki JS w jeden
2. **Minification** - Zmniejsza rozmiar plików
3. **Transpilation** - Konwertuje nowoczesny JS do kompatybilnej wersji
4. **CSS Processing** - Przetwarza i optymalizuje CSS

## Struktura źródłowa

```
assets/src/
├── js/
│   ├── main.js                      # Entry point
│   ├── components/
│   │   ├── TemplateSelector.js     # ✅ ZMIENIONY
│   │   └── PaletteSelector.js      # ✅ ZMIENIONY
│   └── utils/
│       └── Validator.js
└── css/
    └── main.css                     # Entry point
```

## Następne kroki po buildzie

1. ✅ Build zakończony sukcesem
2. ✅ Cache wyczyszczony
3. ✅ Przeglądarka odświeżona
4. ⏳ Testy wykonane
5. ⏳ Wszystko działa poprawnie

## Potrzebujesz pomocy?

Jeśli build nie działa, możesz:
1. Sprawdzić logi błędów
2. Uruchomić `npm install` ponownie
3. Usunąć `node_modules` i `package-lock.json`, potem `npm install`
4. Sprawdzić czy masz odpowiednią wersję Node.js

---

**Data:** 16 listopada 2025
**Wersja:** 2.0.0-beta
