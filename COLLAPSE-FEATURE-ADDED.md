# Collapse Feature for Sidebar and Preview Panel

## Zmiany

Dodano funkcjonalność collapse/expand dla:
1. **Sidebar nawigacji wtyczki** (`.woow-sidebar`)
2. **Panelu Live Preview** (`.woow-preview-container`)

## Implementacja

### 1. Ustawienia w Settings Tab

**Plik:** `includes/templates/tabs/settings-tab.php`

Dodano nową sekcję "Interface Layout" z opcjami:
- `advanced[sidebar_collapsed]` - Domyślny stan sidebar (zwinięty/rozwinięty)
- `advanced[preview_collapsed]` - Domyślny stan preview (zwinięty/rozwinięty)

### 2. Przyciski Toggle w HTML

**Plik:** `includes/templates/admin-page.php`

#### Sidebar Toggle:
```html
<button type="button" class="woow-sidebar-toggle" aria-label="Toggle sidebar">
    <span class="dashicons dashicons-arrow-left-alt2"></span>
</button>
```

#### Preview Toggle:
```html
<button type="button" class="woow-preview-toggle" aria-label="Toggle preview">
    <span class="dashicons dashicons-arrow-right-alt2"></span>
</button>
```

### 3. Style CSS

#### Sidebar Styles (`assets/src/css/components/sidebar-figma.css`)

**Przycisk toggle:**
```css
.woow-sidebar-toggle {
    position: absolute;
    right: 8px;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    transition: all 200ms;
}
```

**Collapsed state:**
```css
.woow-sidebar.collapsed {
    width: 80px;
}

.woow-sidebar.collapsed .woow-logo-text,
.woow-sidebar.collapsed .woow-nav-label,
.woow-sidebar.collapsed .woow-nav-badge {
    opacity: 0;
    width: 0;
    overflow: hidden;
}

.woow-sidebar.collapsed .woow-sidebar-toggle .dashicons {
    transform: rotate(180deg);
}
```

#### Preview Styles (`assets/src/css/components/preview.css`)

**Przycisk toggle:**
```css
.woow-preview-toggle {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    transition: all 200ms;
}
```

**Collapsed state:**
```css
.woow-preview-container.collapsed {
    width: 60px;
}

.woow-preview-container.collapsed .woow-preview-body {
    display: none;
}

.woow-preview-container.collapsed .woow-preview-toggle .dashicons {
    transform: rotate(180deg);
}
```

### 4. JavaScript Controller

**Nowy plik:** `assets/src/js/components/LayoutController.js`

#### Funkcjonalności:
- Toggle sidebar: `toggleSidebar()`
- Toggle preview: `togglePreview()`
- Zapisywanie stanu w localStorage
- Skróty klawiszowe:
  - `Ctrl/Cmd + B` - Toggle sidebar
  - `Ctrl/Cmd + P` - Toggle preview
- Custom events: `woow:sidebar:toggle`, `woow:preview:toggle`

#### Metody publiczne:
```javascript
collapseSidebar()
expandSidebar()
collapsePreview()
expandPreview()
getState() // Zwraca { sidebarCollapsed, previewCollapsed }
```

### 5. Integracja z Main.js

**Plik:** `assets/src/js/main.js`

```javascript
import { LayoutController } from './components/LayoutController.js';

// W initComponents():
this.components.layoutController = new LayoutController(this);
```

## Użycie

### Dla użytkowników:

1. **Kliknij przycisk toggle** w logo sidebar lub w headerze preview
2. **Użyj skrótów klawiszowych:**
   - `Ctrl/Cmd + B` - Zwiń/rozwiń sidebar
   - `Ctrl/Cmd + P` - Zwiń/rozwiń preview
3. **Ustaw domyślny stan** w Settings → Interface Layout

### Dla deweloperów:

```javascript
// Dostęp do kontrolera
const layout = woowAdmin.components.layoutController;

// Toggle
layout.toggleSidebar();
layout.togglePreview();

// Bezpośrednie sterowanie
layout.collapseSidebar();
layout.expandPreview();

// Sprawdź stan
const state = layout.getState();
console.log(state.sidebarCollapsed); // true/false
```

## Animacje

Wszystkie przejścia używają:
```css
transition: width 300ms cubic-bezier(0.4, 0, 0.2, 1);
```

Ikony obracają się o 180° przy zmianie stanu:
```css
transform: rotate(180deg);
```

## Persystencja

Stan collapse jest zapisywany w localStorage:
- `woow_sidebar_collapsed` - Stan sidebar
- `woow_preview_collapsed` - Stan preview

Stan jest przywracany przy każdym załadowaniu strony.

## Responsive

Na urządzeniach mobilnych (< 1024px):
- Sidebar automatycznie przełącza się w tryb responsywny
- Preview może być całkowicie ukryty

## Zgodność

- ✅ Zgodne z Figma UI Specification
- ✅ Używa istniejących zmiennych CSS
- ✅ Zachowuje glassmorphism effects
- ✅ Accessibility compliant (aria-labels, keyboard navigation)
- ✅ Smooth animations (prefers-reduced-motion support)

## Testing

### Testy manualne:

1. Kliknij przycisk toggle sidebar - powinien zwinąć się do 80px
2. Kliknij przycisk toggle preview - powinien zwinąć się do 60px
3. Odśwież stronę - stan powinien być zachowany
4. Użyj Ctrl+B i Ctrl+P - powinny działać skróty
5. Sprawdź animacje - powinny być płynne
6. Sprawdź na mobile - powinno działać responsywnie

### Console logs:

```
[LayoutController] Initialized
[LayoutController] Sidebar toggled: collapsed
[LayoutController] Preview toggled: expanded
```

## Pliki zmienione

1. `includes/templates/tabs/settings-tab.php` - Dodano opcje Interface Layout
2. `includes/templates/admin-page.php` - Dodano przyciski toggle
3. `assets/src/css/components/sidebar-figma.css` - Style collapse sidebar
4. `assets/src/css/components/preview.css` - Style collapse preview
5. `assets/src/js/components/LayoutController.js` - Nowy kontroler (NOWY PLIK)
6. `assets/src/js/main.js` - Import i inicjalizacja LayoutController

## Build

Po zmianach uruchom:
```bash
npm run build
```

Lub w trybie development:
```bash
npm run dev
```

## Następne kroki

Opcjonalne ulepszenia:
- [ ] Dodać animację "slide" zamiast tylko width transition
- [ ] Dodać tooltips na przyciskach toggle
- [ ] Dodać opcję "auto-collapse" po określonym czasie bezczynności
- [ ] Dodać więcej opcji szerokości collapsed state
- [ ] Zapisywać stan w ustawieniach wtyczki zamiast localStorage
