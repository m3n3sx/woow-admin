# CRITICAL FIX: Collect Data from All Tabs

## Problem
`collectFormData()` zbierało dane **tylko z aktywnej zakładki**, pomijając wszystkie inne zakładki, ponieważ sprawdzało `offsetParent !== null` (co jest `null` dla ukrytych elementów).

## Symptom
W logach PHP:
```
[WOOW Admin] Settings received: Array ( [0] => admin_menu [1] => backgrounds [2] => login_page )
```

Brakuje: `admin_bar`, `dashboard_widgets`, `form_controls`, `buttons`, `typography`, `effects`, etc.

## Przyczyna
Kod w `collectFormData()`:
```javascript
const isVisible = input.offsetParent !== null;
if (!isVisible || parentHidden) {
    return; // ❌ Pomija WSZYSTKIE ukryte pola, w tym z nieaktywnych zakładek!
}
```

## Rozwiązanie
Zmieniono logikę, aby:
- ✅ **Zbierać pola ze WSZYSTKICH zakładek** (nawet nieaktywnych)
- ✅ **Pomijać tylko pola warunkowe** (`.woow-conditional`), które są ukryte

```javascript
// Check if this is a conditional field (has .woow-conditional parent)
const conditionalParent = input.closest('.woow-conditional');

if (conditionalParent) {
    // This is a conditional field - check if it's hidden
    const isConditionalHidden = conditionalParent.style.display === 'none' || 
                               conditionalParent.classList.contains('woow-hidden');
    
    if (isConditionalHidden) {
        return; // Skip only hidden conditional fields
    }
}
// For non-conditional fields, always collect (even from inactive tabs)
```

## Testowanie

### Przed naprawą:
```javascript
// Tylko 3 sekcje
{
    admin_menu: {...},
    backgrounds: {...},
    login_page: {...}
}
```

### Po naprawie:
```javascript
// Wszystkie sekcje
{
    admin_bar: {...},
    admin_menu: {...},
    dashboard_widgets: {...},
    form_controls: {...},
    buttons: {...},
    backgrounds: {...},
    typography: {...},
    effects: {...},
    login_page: {...}
}
```

## Jak przetestować

1. Odśwież stronę (Ctrl+Shift+R)
2. Otwórz konsolę (F12)
3. Przejdź do dowolnej zakładki (np. Admin Menu)
4. Zmień jakieś ustawienie
5. Kliknij "Apply Changes"
6. W konsoli sprawdź, czy wszystkie sekcje są wysyłane

Lub sprawdź logi PHP:
```
[WOOW Admin] Settings received: Array ( [0] => admin_bar [1] => admin_menu [2] => dashboard_widgets ... )
```

## Pliki zmienione
- `assets/src/js/main.js` - metoda `collectFormData()`
