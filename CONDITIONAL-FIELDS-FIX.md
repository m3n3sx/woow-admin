# Conditional Fields Fix - Backgrounds Tab

## Problem
Warunkowe ukrywanie pól w zakładce Backgrounds nie działało poprawnie. Wszystkie pola były widoczne niezależnie od wybranej wartości w polu "Effect Type".

## Przyczyna
1. **Nieprawidłowa składnia w HTML**: Używano `data-show-when="#bg-type-select=gradient"` z selektorem ID (`#`), podczas gdy ConditionalFields.js oczekiwał tylko nazwy pola
2. **Brak priorytetyzacji kontekstu**: Skrypt szukał pól w całym dokumencie zamiast w aktywnej zakładce

## Rozwiązanie

### 1. Poprawiono składnię w backgrounds-tab.php

**Przed:**
```php
<div class="woow-conditional" data-show-when="#bg-type-select=gradient">
```

**Po:**
```php
<div class="woow-conditional" data-show-when="type=gradient">
```

**Zmieniono:**
- Usunięto `#` z selektora
- Używamy nazwy pola `type` zamiast ID `#bg-type-select`
- ConditionalFields.js automatycznie znajdzie pole `backgrounds[type]`

### 2. Ulepszono findControlField() w ConditionalFields.js

**Dodano:**
- Priorytetyzację aktywnej zakładki (search context)
- Lepsze logowanie dla debugowania
- Wsparcie dla ID jako fallback

**Kod:**
```javascript
findControlField(fieldName) {
    // Get current active tab to prioritize fields from that section
    const activeTab = document.querySelector('.woow-tab-pane:not([style*="display: none"])');
    const searchContext = activeTab || document;
    
    // Try different name patterns
    const patterns = [
        `[name*="[${fieldName}]"]`,  // backgrounds[type]
        `[name="${fieldName}"]`,      // type
        `[name$="[${fieldName}]"]`,   // ends with [type]
        `#${fieldName}`               // ID selector as fallback
    ];
    
    for (const pattern of patterns) {
        const field = searchContext.querySelector(pattern);
        if (field) {
            return field;
        }
    }
    
    return null;
}
```

## Jak to działa

### Przepływ działania:

1. **Inicjalizacja:**
   - ConditionalFields.js znajduje wszystkie elementy z `data-show-when`
   - Dla każdego elementu parsuje warunek (np. `type=gradient`)

2. **Wyszukiwanie pola kontrolnego:**
   - Najpierw szuka w aktywnej zakładce
   - Próbuje różnych wzorców nazw: `backgrounds[type]`, `type`, itp.
   - Zwraca pierwsze znalezione pole

3. **Aktualizacja widoczności:**
   - Porównuje aktualną wartość pola z oczekiwaną
   - Jeśli pasuje → pokazuje pole (`display: ''`)
   - Jeśli nie pasuje → ukrywa pole (`display: none`)

4. **Nasłuchiwanie zmian:**
   - Dodaje event listener na pole kontrolne
   - Przy każdej zmianie aktualizuje widoczność

### Przykład:

```html
<!-- Pole kontrolne -->
<select name="backgrounds[type]">
    <option value="none">None</option>
    <option value="gradient">Gradient</option>
    <option value="image">Image</option>
</select>

<!-- Pole warunkowe - pokazuje się gdy type=gradient -->
<div class="woow-conditional" data-show-when="type=gradient">
    <!-- Ustawienia gradientu -->
</div>

<!-- Pole warunkowe - pokazuje się gdy type=image -->
<div class="woow-conditional" data-show-when="type=image">
    <!-- Ustawienia obrazka -->
</div>
```

## Testowanie

### Plik testowy: test-conditional-backgrounds.html

Stworzono standalone HTML do testowania logiki:
- Symuluje strukturę zakładki Backgrounds
- Zawiera uproszczoną wersję ConditionalFields
- Pokazuje logi w konsoli i na stronie
- Pozwala na łatwe debugowanie

### Jak przetestować:

1. Otwórz `test-conditional-backgrounds.html` w przeglądarce
2. Zmień wartość w "Background Type"
3. Obserwuj:
   - Gradient Settings → pokazuje się dla "Gradient Overlay"
   - Image Settings → pokazuje się dla "Background Image"
   - Oba ukrywają się dla "None"
4. Sprawdź logi w konsoli przeglądarki

### Testy w pluginie:

1. Otwórz panel WOOW! Admin
2. Przejdź do zakładki "Backgrounds"
3. Zmień "Effect Type":
   - **None** → wszystkie dodatkowe pola ukryte
   - **Gradient Overlay** → pokazują się pola gradientu
   - **Background Image** → pokazują się pola obrazka
4. Sprawdź konsolę przeglądarki (F12) dla logów

## Pliki zmienione

1. **woow-admin/includes/templates/tabs/backgrounds-tab.php**
   - Zmieniono `data-show-when="#bg-type-select=gradient"` → `data-show-when="type=gradient"`
   - Zmieniono `data-show-when="#bg-type-select=image"` → `data-show-when="type=image"`

2. **woow-admin/assets/src/js/components/ConditionalFields.js**
   - Dodano priorytetyzację aktywnej zakładki w `findControlField()`
   - Dodano wsparcie dla ID jako fallback
   - Ulepszone logowanie

3. **woow-admin/test-conditional-backgrounds.html** (nowy)
   - Standalone test dla warunkowych pól

4. **woow-admin/CONDITIONAL-FIELDS-FIX.md** (ten plik)
   - Dokumentacja naprawy

## Wzorzec dla innych zakładek

Jeśli chcesz dodać warunkowe pola w innych zakładkach, użyj tego wzorca:

```php
<!-- Pole kontrolne (select, checkbox, radio) -->
<select name="section[field_name]" class="woow-select">
    <option value="option1">Option 1</option>
    <option value="option2">Option 2</option>
</select>

<!-- Pole warunkowe -->
<div class="woow-conditional" data-show-when="field_name=option1">
    <!-- Zawartość pokazywana gdy field_name === 'option1' -->
</div>

<div class="woow-conditional" data-show-when="field_name=option2">
    <!-- Zawartość pokazywana gdy field_name === 'option2' -->
</div>
```

**Ważne:**
- Używaj tylko nazwy pola (bez sekcji, bez `#`)
- ConditionalFields automatycznie znajdzie pole w aktywnej zakładce
- Wartość w `data-show-when` musi dokładnie pasować do wartości opcji

## Status
✅ **Naprawione i przetestowane**

Data: 2024-11-14
