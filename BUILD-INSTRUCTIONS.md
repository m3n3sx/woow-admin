# Build Instructions

## Problem
Środowisko ma problemy z wykonywaniem npm/vite bezpośrednio.

## Rozwiązanie

### Opcja 1: Build lokalnie (zalecane)
```bash
cd /home/ooxo/Local\ Sites/woow/app/public/wp-content/plugins/woow-admin
npm run build
```

### Opcja 2: Użyj npx bezpośrednio
```bash
cd /home/ooxo/Local\ Sites/woow/app/public/wp-content/plugins/woow-admin
npx vite build
```

### Opcja 3: Ręczny build przez terminal
1. Otwórz terminal w katalogu wtyczki
2. Uruchom: `npm run build`
3. Poczekaj na zakończenie buildu
4. Odśwież stronę WordPress admin

## Co zostało zmienione

### CSS Files
1. `assets/src/css/components/header-figma.css`
   - Zmieniono `position: fixed` → `position: relative`
   - Dodano `margin: -20px -20px 20px -20px`

2. `assets/src/css/components/sidebar-figma.css`
   - Zmieniono `position: fixed` → `position: sticky`
   - Dodano `max-height: calc(100vh - 32px)`

3. `assets/src/css/components/admin-page.css`
   - Dodano `display: flex` dla `.woow-layout-container`
   - Zmieniono marginesy
   - Dodano responsive breakpoints

### PHP Files
1. `includes/class-woow-admin.php`
   - Usunięto zduplikowaną metodę `enqueue_admin_assets()`

## Weryfikacja

Po zbudowaniu sprawdź:
1. Czy plik `assets/dist/style.css` został zaktualizowany
2. Czy plik `assets/dist/main.js` został zaktualizowany
3. Czy rozmiar plików się zmienił

```bash
ls -lh assets/dist/
```

Powinno pokazać:
- `style.css` - około 73 KB
- `main.js` - około 33 KB

## Testowanie

1. Odśwież stronę WordPress admin
2. Przejdź do: `wp-admin/admin.php?page=woow-admin`
3. Sprawdź czy:
   - Header wyświetla się poprawnie
   - Sidebar jest obok contentu (nie fixed)
   - Content scrolluje się niezależnie
   - Brak nakładania się na WordPress admin bar/menu

## Troubleshooting

### Jeśli build nie działa:
```bash
# Sprawdź czy node_modules istnieją
ls node_modules/

# Jeśli nie, zainstaluj zależności
npm install

# Spróbuj ponownie
npm run build
```

### Jeśli nadal nie działa:
Możesz ręcznie skopiować zawartość plików CSS do `assets/dist/style.css`:

1. Otwórz `assets/src/css/main.css`
2. Skopiuj wszystkie importy
3. Ręcznie połącz wszystkie pliki CSS w jeden
4. Zapisz jako `assets/dist/style.css`

## Expected Output

```
✓ 10 modules transformed.
assets/dist/style.css  73.50 kB │ gzip: 11.84 kB
assets/dist/main.js    33.23 kB │ gzip:  8.03 kB
✓ built in 228ms
```

## Status

- ✅ CSS changes made
- ✅ PHP duplicate removed
- ⏳ Build pending
- ⏳ Testing pending

Po wykonaniu buildu, wtyczka powinna działać poprawnie!
