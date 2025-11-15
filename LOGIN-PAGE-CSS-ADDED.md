# Login Page - CSS Generation Added

## Problem
Zmiana koloru tła na stronie logowania nie działała, ponieważ **CSS dla login page nie był generowany**.

## Rozwiązanie

### 1. Dodano metodę `add_login_page_styles()` w CSS Generator

**Plik:** `includes/class-woow-css-generator.php`

Metoda generuje CSS dla:
- ✅ Background (solid color, gradient, image)
- ✅ Login form (glassmorphism, border-radius, shadow)
- ✅ Custom logo
- ✅ Button styles (dziedziczone z Universal Buttons)
- ✅ Input styles (dziedziczone z Form Controls)
- ✅ Custom CSS

**Przykład wygenerowanego CSS:**
```css
/* Login Page Styling */
body.login {
    background: linear-gradient(135deg, #6366f1, #8b5cf6) !important;
    min-height: 100vh !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}

#loginform,
.login form {
    background: rgba(255, 255, 255, 0.95) !important;
    backdrop-filter: blur(12px) !important;
    border-radius: 24px !important;
    padding: 32px !important;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1) !important;
}
```

### 2. Dodano wywołanie w metodzie `generate()`

```php
if ( $this->settings->get_option( 'login_page.enabled', false ) ) {
    $this->add_login_page_styles();
}
```

### 3. Dodano hook `login_enqueue_scripts`

**Plik:** `includes/class-woow-admin.php`

```php
// Inject CSS for login page
add_action( 'login_enqueue_scripts', array( $this, 'inject_login_css' ) );
```

### 4. Dodano metodę `inject_login_css()`

```php
public function inject_login_css(): void {
    // Check if login page customization is enabled
    $login_settings = $this->settings->get_section( 'login_page' );
    if ( empty( $login_settings['enabled'] ) ) {
        return;
    }

    // Generate CSS
    $css = $this->css_generator->generate();

    // Output CSS
    if ( ! empty( $css ) ) {
        echo '<style id="woow-login-custom-css" type="text/css">';
        echo wp_strip_all_tags( $css );
        echo '</style>';
    }
}
```

## Funkcje

### Background Types

**Solid Color:**
```php
background: #f8fafc !important;
```

**Gradient:**
```php
background: linear-gradient(135deg, #6366f1, #8b5cf6) !important;
```

**Image:**
```php
background-image: url('image.jpg') !important;
background-size: cover !important;
background-position: center !important;
```

### Glassmorphism

Gdy włączone:
```php
background: rgba(255, 255, 255, 0.95) !important;
backdrop-filter: blur(12px) !important;
-webkit-backdrop-filter: blur(12px) !important;
```

### Custom Logo

Gdy ustawione:
```php
.login h1 a {
    background-image: url('logo.png') !important;
    background-size: contain !important;
    width: 320px !important;
    height: 84px !important;
}
```

### Inherit Styles

**Buttons** (gdy włączone):
- Kolor tła z Universal Buttons
- Kolor tekstu z Universal Buttons
- Border radius z Universal Buttons
- Hover effects

**Inputs** (gdy włączone):
- Border color z Form Controls
- Border radius z Form Controls
- Focus color z Form Controls
- Focus shadow

## Testowanie

### 1. Włącz Login Page Styling
1. Przejdź do WOOW! Admin → Login Page
2. Włącz "Apply custom styles to login page"
3. Kliknij "Apply Changes"

### 2. Test Solid Color
1. Zmień "Background Type" na "Solid Color"
2. Wybierz kolor (np. niebieski #3b82f6)
3. Kliknij "Apply Changes"
4. Przejdź do `/wp-login.php`
5. Sprawdź, czy tło jest niebieskie

### 3. Test Gradient
1. Zmień "Background Type" na "Gradient"
2. Start Color: #6366f1
3. End Color: #8b5cf6
4. Kliknij "Apply Changes"
5. Przejdź do `/wp-login.php`
6. Sprawdź, czy tło ma gradient

### 4. Test Image
1. Zmień "Background Type" na "Image"
2. Kliknij "Upload Image" i wybierz obraz
3. Kliknij "Apply Changes"
4. Przejdź do `/wp-login.php`
5. Sprawdź, czy tło ma obraz

### 5. Test Glassmorphism
1. Włącz "Enable glassmorphism for login form"
2. Ustaw Blur Strength: 12px
3. Kliknij "Apply Changes"
4. Przejdź do `/wp-login.php`
5. Sprawdź, czy formularz ma efekt szkła

## Pliki zmienione

1. **includes/class-woow-css-generator.php**
   - Dodano metodę `add_login_page_styles()`
   - Dodano wywołanie w `generate()`

2. **includes/class-woow-admin.php**
   - Dodano hook `login_enqueue_scripts`
   - Dodano metodę `inject_login_css()`

## Wymagania

- Login Page Styling musi być włączone (`enabled = true`)
- CSS jest generowany tylko gdy `login_page.enabled` jest true
- CSS jest cachowany (24h) - wyczyść cache po zmianach

## Czyszczenie cache

Jeśli zmiany nie są widoczne:

1. **Wyczyść cache WordPress:**
```php
wp_cache_flush();
```

2. **Wyczyść cache przeglądarki:**
- Ctrl+Shift+Delete
- Ctrl+Shift+R (hard refresh)

3. **Lub użyj:**
```
wp-content/plugins/woow-admin/force-refresh.php
```

## Debugowanie

### Sprawdź, czy CSS jest generowany:

1. Przejdź do `/wp-login.php`
2. Wyświetl źródło strony (Ctrl+U)
3. Szukaj: `<!-- WOOW! Admin Login Page Styles -->`
4. Sprawdź, czy CSS jest obecny

### Sprawdź, czy hook działa:

```php
// Dodaj do functions.php
add_action('login_enqueue_scripts', function() {
    error_log('Login enqueue scripts fired');
}, 999);
```

### Sprawdź ustawienia:

```php
$settings = get_option('woow_admin_settings');
var_dump($settings['login_page']);
```

## Znane problemy

### Problem: CSS nie jest stosowany
**Rozwiązanie:** Sprawdź, czy `login_page.enabled` jest `true`

### Problem: Zmiany nie są widoczne
**Rozwiązanie:** Wyczyść cache (WordPress + przeglądarka)

### Problem: Gradient nie działa
**Rozwiązanie:** Sprawdź, czy `background_type` jest ustawiony na `gradient`

### Problem: Logo nie wyświetla się
**Rozwiązanie:** Sprawdź, czy `logo_url` zawiera prawidłowy URL
