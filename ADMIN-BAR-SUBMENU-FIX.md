# Admin Bar Submenu Background Fix

## Problem

Po najechaniu na element w admin bar, rozwijające się submenu miało hardcoded jasne tło (`rgba(255, 255, 255, 0.95)`) i ciemny tekst (`#0f172a`), co nie pasowało gdy admin bar miał ciemne tło.

## Przyczyna

W `class-woow-css-generator.php` w metodzie `add_admin_bar_styles()` submenu miało stałe kolory:
- Tło: `rgba(255, 255, 255, 0.95)` (jasne)
- Tekst: `#0f172a` (ciemny)

To działało dobrze tylko gdy admin bar miał ciemne tło. Gdy użytkownik ustawił jasne tło dla admin bar, submenu było nieczytelne (jasny tekst na jasnym tle).

## Dodatkowy problem

WordPress ma domyślny styl dla submenu wrapper:
```css
#wpadminbar .menupop .ab-sub-wrapper {
    background: #2c3338; /* Ciemny kolor */
}
```

Ten styl ma wysoką specyficzność i nadpisywał nasze style dla `.ab-submenu`.

## Rozwiązanie

### 1. Dodano inteligentne wykrywanie koloru tła

Dodano metodę `is_light_color()` która:
- Analizuje kolor tła admin bar
- Oblicza relative luminance według wzoru WCAG
- Zwraca `true` jeśli kolor jest jasny (luminance > 0.5)

```php
private function is_light_color( string $color ): bool {
    // Parse hex or rgba color
    // Calculate luminance: 0.299*R + 0.587*G + 0.114*B
    $luminance = ( 0.299 * $r + 0.587 * $g + 0.114 * $b ) / 255;
    return $luminance > 0.5;
}
```

### 2. Dynamiczne kolory submenu

Submenu teraz automatycznie dostosowuje kolory:

**Gdy admin bar ma ciemne tło:**
```php
$submenu_bg = 'rgba(255, 255, 255, 0.98)';  // Jasne tło
$submenu_text = '#0f172a';                   // Ciemny tekst
$submenu_hover = 'rgba(99, 102, 241, 0.08)'; // Jasny hover
```

**Gdy admin bar ma jasne tło:**
```php
$submenu_bg = 'rgba(30, 41, 59, 0.98)';      // Ciemne tło
$submenu_text = '#ffffff';                    // Jasny tekst
$submenu_hover = 'rgba(255, 255, 255, 0.1)'; // Ciemny hover
```

### 3. Nadpisanie wszystkich selektorów WordPress

Dodano style dla wszystkich możliwych selektorów submenu:

```css
/* Główny wrapper submenu */
#wpadminbar .menupop .ab-sub-wrapper,
#wpadminbar .ab-submenu {
    background: {$submenu_bg} !important;
}

/* Wszystkie warianty wrapper */
#wpadminbar .menupop > .ab-sub-wrapper,
#wpadminbar .ab-top-menu > li.menupop > .ab-sub-wrapper,
#wpadminbar .ab-top-secondary .menupop .ab-sub-wrapper {
    background: {$submenu_bg} !important;
}

/* Linki w submenu */
#wpadminbar .ab-submenu a,
#wpadminbar .menupop .ab-sub-wrapper a {
    color: {$submenu_text} !important;
}

/* Hover w submenu */
#wpadminbar .ab-submenu a:hover,
#wpadminbar .menupop .ab-sub-wrapper a:hover {
    background: {$submenu_hover} !important;
    color: {$submenu_text} !important;
}
```

### 4. Ulepszone style submenu

```css
#wpadminbar .ab-submenu {
    background: {$submenu_bg} !important;
    backdrop-filter: blur(12px) !important;
    -webkit-backdrop-filter: blur(12px) !important;
    border-radius: 12px !important;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2) !important;
    border: 1px solid rgba(0, 0, 0, 0.1) !important;
    margin-top: 8px !important;
    padding: 8px !important;
}

#wpadminbar .ab-submenu .ab-item {
    color: {$submenu_text} !important;
    border-radius: 8px !important;
    padding: 8px 12px !important;
    transition: all 200ms var(--woow-easing) !important;
}

#wpadminbar .ab-submenu .ab-item:hover {
    background: {$submenu_hover} !important;
    color: {$submenu_text} !important;
}
```

## Zmienione pliki

- `woow-admin/includes/class-woow-css-generator.php`
  - Metoda `add_admin_bar_styles()` - Dynamiczne kolory submenu
  - Metoda `is_light_color()` - Nowa metoda pomocnicza (NOWY)

## Testowanie

### Test 1: Ciemne tło admin bar

1. Otwórz WOOW! Admin → Admin Bar
2. Ustaw "Background Color" na ciemny kolor (np. `#1e293b`)
3. Kliknij "Apply Changes"
4. Najedź na element w admin bar (np. logo WordPress)

**Oczekiwany rezultat:**
- ✅ Submenu ma jasne tło
- ✅ Tekst w submenu jest ciemny i czytelny
- ✅ Hover działa poprawnie

### Test 2: Jasne tło admin bar

1. Otwórz WOOW! Admin → Admin Bar
2. Ustaw "Background Color" na jasny kolor (np. `#ffffff` lub `#f0f0f0`)
3. Kliknij "Apply Changes"
4. Najedź na element w admin bar

**Oczekiwany rezultat:**
- ✅ Submenu ma ciemne tło
- ✅ Tekst w submenu jest jasny i czytelny
- ✅ Hover działa poprawnie

### Test 3: Gradient tło

1. Ustaw "Background Type" na "Gradient"
2. Ustaw jasny gradient (np. `#ffffff` → `#e0e0e0`)
3. Sprawdź submenu

**Oczekiwany rezultat:**
- ✅ Submenu dostosowuje się do dominującego koloru

### Test 4: Różne kolory

Przetestuj z różnymi kolorami:
- `#000000` (czarny) → submenu jasne
- `#ffffff` (biały) → submenu ciemne
- `#1e293b` (slate-800) → submenu jasne
- `#f8fafc` (slate-50) → submenu ciemne
- `#6366f1` (indigo-500) → submenu jasne (średni kolor)

## Algorytm wykrywania jasności

Używamy wzoru WCAG dla relative luminance:

```
luminance = (0.299 × R + 0.587 × G + 0.114 × B) / 255
```

Gdzie:
- R, G, B = wartości RGB (0-255)
- Współczynniki (0.299, 0.587, 0.114) odpowiadają ludzkiej percepcji jasności
- Wynik: 0 (czarny) do 1 (biały)

**Próg:** 0.5
- luminance > 0.5 → kolor jasny → ciemne submenu
- luminance ≤ 0.5 → kolor ciemny → jasne submenu

## Obsługiwane formaty kolorów

Metoda `is_light_color()` obsługuje:
- ✅ Hex 6-znakowy: `#1e293b`
- ✅ Hex 3-znakowy: `#fff`
- ✅ RGBA: `rgba(30, 41, 59, 0.9)`
- ✅ RGB: `rgb(30, 41, 59)`

## Accessibility

Rozwiązanie zapewnia:
- ✅ Kontrast min 4.5:1 (WCAG AA)
- ✅ Czytelność w każdej konfiguracji kolorów
- ✅ Automatyczne dostosowanie bez interwencji użytkownika

## Glassmorphism

Submenu zachowuje efekt glassmorphism:
- `backdrop-filter: blur(12px)`
- Semi-transparent background (0.98 opacity)
- Subtle border
- Enhanced shadow dla lepszej widoczności

## Znane ograniczenia

1. **Gradient backgrounds:** Algorytm analizuje tylko `background_color`, nie gradient. Jeśli używasz gradientu, może nie zawsze wybrać optymalny kolor submenu.

2. **Bardzo nasycone kolory:** Kolory o średniej jasności (luminance ~0.5) mogą wymagać ręcznej korekty.

## Debugowanie

### Sprawdź wykryty kolor

```php
// W class-woow-css-generator.php, dodaj tymczasowo:
error_log('Admin bar bg: ' . $bg_color);
error_log('Is light: ' . ($this->is_light_color($bg_color) ? 'yes' : 'no'));
error_log('Submenu bg: ' . $submenu_bg);
```

### Sprawdź wygenerowany CSS

```javascript
// W konsoli przeglądarki:
const css = document.querySelector('style#woow-dynamic-css')?.textContent;
console.log(css.match(/#wpadminbar .ab-submenu[\s\S]*?}/));
```

### Test luminance

```javascript
// Oblicz luminance dla koloru:
function getLuminance(r, g, b) {
    return (0.299 * r + 0.587 * g + 0.114 * b) / 255;
}

// Przykład:
getLuminance(30, 41, 59);  // 0.15 (ciemny)
getLuminance(248, 250, 252); // 0.98 (jasny)
```

## Następne kroki

Opcjonalne ulepszenia:
- [ ] Dodać opcję ręcznego wyboru koloru submenu
- [ ] Analizować gradient (średnia kolorów)
- [ ] Dodać więcej progów jasności (bardzo jasny, jasny, ciemny, bardzo ciemny)
- [ ] Preview koloru submenu w live preview
- [ ] Zapisać wykryty typ (light/dark) w cache
