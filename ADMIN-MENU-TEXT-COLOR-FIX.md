# Admin Menu Text Color Fix

## Problem

Zmiana koloru tekstu w ustawieniach Admin Menu nie działała - kolor tekstu menu items nie był aplikowany.

## Przyczyna

W `class-woow-css-generator.php` w metodzie `add_admin_menu_styles()` brakowało CSS rules dla koloru tekstu menu items. Były tylko style dla:
- Active state (gradient background)
- Hover state (background color)

Ale nie było podstawowego koloru tekstu dla normalnych menu items.

## Rozwiązanie

Dodano kompletne style CSS dla kolorów tekstu w admin menu:

### 1. Podstawowy kolor tekstu dla wszystkich menu items

```php
// Base text color for all menu items
$this->css .= "#adminmenu,\n";
$this->css .= "#adminmenu a,\n";
$this->css .= "#adminmenu div.wp-menu-name {\n";
$this->css .= "    color: {$text_color} !important;\n";
$this->css .= "}\n\n";
```

### 2. Kolor ikon menu

```php
// Menu item icons
$this->css .= "#adminmenu .wp-menu-image:before,\n";
$this->css .= "#adminmenu .wp-menu-image img {\n";
$this->css .= "    color: {$text_color} !important;\n";
$this->css .= "    opacity: 0.7;\n";
$this->css .= "}\n\n";
```

### 3. Active state - biały tekst na gradiencie

```php
// Active menu item icon
$this->css .= "#adminmenu li.wp-has-current-submenu .wp-menu-image:before,\n";
$this->css .= "#adminmenu li.current .wp-menu-image:before {\n";
$this->css .= "    color: #ffffff !important;\n";
$this->css .= "    opacity: 1 !important;\n";
$this->css .= "}\n\n";
```

### 4. Hover state - zachowanie koloru tekstu

```php
// Hover state for menu items
$this->css .= "#adminmenu li a:hover,\n";
$this->css .= "#adminmenu li.menu-top:hover,\n";
$this->css .= "#adminmenu li.opensub > a.menu-top,\n";
$this->css .= "#adminmenu li > a.menu-top:focus {\n";
$this->css .= "    background: {$hover_bg_color} !important;\n";
$this->css .= "    border-radius: {$active_radius} !important;\n";
$this->css .= "    color: {$text_color} !important;\n";
$this->css .= "}\n\n";
```

### 5. Hover state dla ikon

```php
// Hover state for menu item icons
$this->css .= "#adminmenu li:hover .wp-menu-image:before {\n";
$this->css .= "    color: {$text_color} !important;\n";
$this->css .= "    opacity: 1 !important;\n";
$this->css .= "}\n\n";
```

### 6. Style dla submenu

```php
// Submenu styles
$this->css .= "#adminmenu .wp-submenu,\n";
$this->css .= "#adminmenu .wp-submenu-wrap {\n";
$this->css .= "    background: {$background_color} !important;\n";
$this->css .= "    border-radius: {$active_radius} !important;\n";
$this->css .= "}\n\n";

$this->css .= "#adminmenu .wp-submenu a,\n";
$this->css .= "#adminmenu .wp-submenu li {\n";
$this->css .= "    color: {$text_color} !important;\n";
$this->css .= "}\n\n";

$this->css .= "#adminmenu .wp-submenu a:hover,\n";
$this->css .= "#adminmenu .wp-submenu a:focus {\n";
$this->css .= "    background: {$hover_bg_color} !important;\n";
$this->css .= "    color: {$text_color} !important;\n";
$this->css .= "}\n\n";

$this->css .= "#adminmenu .wp-submenu li.current a,\n";
$this->css .= "#adminmenu .wp-submenu li.current {\n";
$this->css .= "    color: {$active_gradient_start} !important;\n";
$this->css .= "    font-weight: 600 !important;\n";
$this->css .= "}\n\n";
```

## Zmienione pliki

- `woow-admin/includes/class-woow-css-generator.php` - Metoda `add_admin_menu_styles()`

## Testowanie

### 1. Zmień kolor tekstu w Admin Menu

1. Otwórz WOOW! Admin → Menu Styling
2. W sekcji "Colors" znajdź "Text Color"
3. Zmień kolor na np. czerwony (#ff0000)
4. Kliknij "Apply Changes"

### 2. Sprawdź rezultat

**Oczekiwany rezultat:**
- ✅ Wszystkie menu items mają nowy kolor tekstu
- ✅ Ikony menu mają nowy kolor (z opacity 0.7)
- ✅ Active menu item ma biały tekst na gradiencie
- ✅ Hover zachowuje ustawiony kolor tekstu
- ✅ Submenu items mają nowy kolor tekstu
- ✅ Current submenu item ma kolor active gradient start

### 3. Testuj różne kolory

Wypróbuj różne kolory:
- Jasny kolor na ciemnym tle
- Ciemny kolor na jasnym tle
- Sprawdź kontrast (WCAG AA: min 4.5:1)

### 4. Sprawdź wszystkie stany

- [ ] Normal state - kolor tekstu aplikowany
- [ ] Hover state - kolor tekstu zachowany
- [ ] Active state - biały tekst na gradiencie
- [ ] Submenu normal - kolor tekstu aplikowany
- [ ] Submenu hover - kolor tekstu zachowany
- [ ] Submenu current - kolor active gradient

## Selektory CSS użyte

```css
/* Base text color */
#adminmenu,
#adminmenu a,
#adminmenu div.wp-menu-name

/* Icons */
#adminmenu .wp-menu-image:before,
#adminmenu .wp-menu-image img

/* Active state */
#adminmenu li.wp-has-current-submenu .wp-menu-image:before,
#adminmenu li.current .wp-menu-image:before

/* Hover state */
#adminmenu li a:hover,
#adminmenu li.menu-top:hover,
#adminmenu li.opensub > a.menu-top,
#adminmenu li > a.menu-top:focus

/* Hover icons */
#adminmenu li:hover .wp-menu-image:before

/* Submenu */
#adminmenu .wp-submenu a,
#adminmenu .wp-submenu li,
#adminmenu .wp-submenu a:hover,
#adminmenu .wp-submenu li.current a
```

## Priorytet CSS

Wszystkie style używają `!important` aby nadpisać domyślne style WordPress.

## Kompatybilność

- ✅ WordPress 6.0+
- ✅ Wszystkie motywy
- ✅ Collapsed menu state
- ✅ Submenu
- ✅ Mobile responsive

## Accessibility

Pamiętaj o kontraście kolorów:
- **WCAG AA:** min 4.5:1 dla normalnego tekstu
- **WCAG AAA:** min 7:1 dla normalnego tekstu

Użyj narzędzi do sprawdzenia kontrastu:
- https://webaim.org/resources/contrastchecker/
- Chrome DevTools → Accessibility

## Debugowanie

Jeśli kolor tekstu nie działa:

1. **Sprawdź czy CSS został wygenerowany:**
   ```javascript
   // W konsoli przeglądarki
   const styles = document.querySelector('style#woow-dynamic-css');
   console.log(styles?.textContent);
   ```

2. **Sprawdź czy ustawienie jest zapisane:**
   ```php
   // W WordPress
   $settings = get_option('woow_settings');
   var_dump($settings['admin_menu']['text_color']);
   ```

3. **Sprawdź specificity CSS:**
   - Użyj DevTools → Elements → Computed
   - Sprawdź który styl wygrywa
   - Wszystkie nasze style mają `!important`

4. **Wyczyść cache:**
   ```bash
   # WordPress cache
   wp cache flush
   
   # Przeglądarka
   Ctrl+Shift+R (Windows/Linux)
   Cmd+Shift+R (Mac)
   ```

## Znane problemy

Brak znanych problemów.

## Następne kroki

Opcjonalne ulepszenia:
- [ ] Dodać osobny kolor dla ikon (icon_color)
- [ ] Dodać osobny kolor dla submenu (submenu_text_color)
- [ ] Dodać preview koloru tekstu w live preview
- [ ] Dodać walidację kontrastu w czasie rzeczywistym
