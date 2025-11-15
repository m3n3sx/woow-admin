# Login Page - Naprawione opcje i przycisk upload

## Problemy
1. ❌ Nie działały pola kolorów (background_color, gradient_start, gradient_end)
2. ❌ Nie działał przycisk "Upload Image"
3. ❌ Conditional fields używały nieprawidłowego formatu

## Rozwiązania

### 1. Naprawiono pola kolorów

**Przed:**
```php
<input type="color" name="login_page[background_color]" 
    value="<?php echo esc_attr( WOOW_Admin::rgba_to_hex(...) ); ?>" />
<input type="text" value="<?php echo esc_attr( $login['background_color'] ); ?>" 
    class="woow-color-text" />  <!-- ❌ Brak name! -->
```

**Po:**
```php
<input type="color" name="login_page[background_color]" 
    value="<?php echo esc_attr( $login['background_color'] ?? '#f8fafc' ); ?>" />
<input type="text" name="login_page[background_color]"  <!-- ✅ Dodano name -->
    value="<?php echo esc_attr( $login['background_color'] ?? '#f8fafc' ); ?>" 
    class="woow-color-text" placeholder="#f8fafc" />
```

### 2. Naprawiono conditional fields

**Przed:**
```php
<div class="woow-conditional" data-show-when="#login-bg-type=color">
```

**Po:**
```php
<div class="woow-conditional" data-show-when="login_page[background_type]=color">
```

### 3. Dodano Media Uploader Component

**Nowy plik:** `assets/src/js/components/MediaUploader.js`

Funkcje:
- ✅ Otwiera WordPress Media Library
- ✅ Pozwala wybrać obraz
- ✅ Pokazuje podgląd wybranego obrazu
- ✅ Dodaje przycisk "Remove" do usunięcia obrazu
- ✅ Zapisuje URL obrazu w hidden input

**Użycie:**
```html
<input type="hidden" name="login_page[logo_url]" id="login-logo-url" />
<button type="button" class="button woow-upload-image" data-target="#login-logo-url">
    Upload Logo
</button>
```

### 4. Dodano wp_enqueue_media()

**Plik:** `includes/class-woow-admin.php`

```php
public function enqueue_admin_assets( string $hook ): void {
    // ...
    
    // Enqueue WordPress media library
    wp_enqueue_media();  // ✅ Dodano
    
    // ...
}
```

## Testowanie

### Test 1: Kolory
1. Odśwież stronę (Ctrl+Shift+R)
2. Przejdź do Login Page
3. Zmień "Background Type" na "Solid Color"
4. Wybierz kolor (np. niebieski #3b82f6)
5. Kliknij "Apply Changes"
6. Sprawdź, czy kolor się zapisał

### Test 2: Gradient
1. Zmień "Background Type" na "Gradient"
2. Ustaw Start Color: #6366f1
3. Ustaw End Color: #8b5cf6
4. Kliknij "Apply Changes"
5. Sprawdź, czy gradient się zapisał

### Test 3: Upload Logo
1. Kliknij "Upload Logo"
2. Powinna otworzyć się WordPress Media Library
3. Wybierz obraz lub wgraj nowy
4. Kliknij "Use this image"
5. Powinien pojawić się podgląd obrazu
6. Kliknij "Apply Changes"
7. Sprawdź, czy logo się zapisało

### Test 4: Upload Background Image
1. Zmień "Background Type" na "Image"
2. Kliknij "Upload Image"
3. Wybierz obraz
4. Powinien pojawić się podgląd
5. Kliknij "Apply Changes"
6. Sprawdź, czy obraz tła się zapisał

## Pliki zmienione

1. **includes/templates/tabs/login-tab.php**
   - Naprawiono pola kolorów (dodano `name` do text input)
   - Naprawiono conditional fields (zmieniono format `data-show-when`)

2. **assets/src/js/components/MediaUploader.js** (NOWY)
   - Komponent obsługujący WordPress Media Library
   - Podgląd obrazów
   - Przycisk usuwania obrazów

3. **assets/src/js/main.js**
   - Dodano import MediaUploader
   - Dodano inicjalizację MediaUploader

4. **includes/class-woow-admin.php**
   - Dodano `wp_enqueue_media()` w metodzie `enqueue_admin_assets()`

## Funkcje MediaUploader

### openMediaUploader(button)
Otwiera WordPress Media Library dla danego przycisku.

### showPreview(button, imageUrl)
Pokazuje podgląd wybranego obrazu.

### removeImage(button)
Usuwa obraz i czyści wartość w input.

## Przykład użycia w innych miejscach

```html
<!-- Hidden input przechowuje URL -->
<input type="hidden" name="section[image_field]" id="my-image" value="" />

<!-- Przycisk upload -->
<button type="button" class="button woow-upload-image" data-target="#my-image">
    Upload Image
</button>

<!-- Opcjonalny podgląd (jeśli obraz już istnieje) -->
<?php if ( ! empty( $value ) ) : ?>
    <img src="<?php echo esc_url( $value ); ?>" class="woow-image-preview" />
<?php endif; ?>
```

## Wymagania

- WordPress 5.0+ (dla wp.media API)
- jQuery (już załadowany przez WordPress)

## Notatki

- MediaUploader automatycznie dodaje przycisk "Remove" po wybraniu obrazu
- Podgląd obrazu ma automatyczne style (max-width: 100%, border-radius, border)
- Komponent działa z dowolną liczbą przycisków upload na stronie
- Każdy przycisk musi mieć atrybut `data-target` wskazujący na ID hidden input
