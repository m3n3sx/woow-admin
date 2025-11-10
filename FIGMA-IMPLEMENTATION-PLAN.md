# WOOW! Admin - Figma Design Implementation Plan

## Status: Frontend Fixed ✅
Zakładki działają! Teraz dopracowujemy wygląd zgodnie z projektem Figma.

## Źródło projektu Figma
https://github.com/m3n3sx/Designwordpressadminplugin.git

## Kluczowe elementy do implementacji z Figma

### 1. Layout Structure
- **Split-screen layout**: Lewa strona (40%) - ustawienia, Prawa strona (60%) - live preview
- **Draggable divider** między panelami
- **Responsive breakpoints**: 1024px (collapse to tabs), 768px (mobile stack)

### 2. Color System (z Figma)
```css
/* Primary Colors */
--primary: #6366f1 (Indigo 500)
--primary-dark: #4f46e5 (Indigo 600)
--primary-light: #818cf8 (Indigo 400)

/* Background */
--bg-light: #f8fafc (Slate 50)
--bg-dark: #0f172a (Slate 900)

/* Card/Surface */
--surface-light: #ffffff
--surface-dark: #1e293b (Slate 800)

/* Border */
--border-light: #e2e8f0 (Slate 200)
--border-dark: #334155 (Slate 700)

/* Text */
--text-primary: #0f172a (Slate 900)
--text-secondary: #64748b (Slate 500)
--text-muted: #94a3b8 (Slate 400)
```

### 3. Typography Scale
```css
/* Headings */
H1: 32px / 700 / 1.3 / -0.02em
H2: 24px / 700 / 1.3 / -0.01em
H3: 20px / 600 / 1.4 / -0.01em

/* Body */
Body: 15px / 400 / 1.6
Small: 13px / 400 / 1.5

/* UI Elements */
Button/Label: 14px / 600 / 1.5
Caption: 12px / 500 / 1.4
```

### 4. Spacing System (Base: 4px)
```css
--space-xs: 4px
--space-sm: 8px
--space-md: 16px
--space-lg: 24px
--space-xl: 32px
--space-2xl: 48px
--space-3xl: 64px
```

### 5. Border Radius
```css
--radius-sm: 8px
--radius-md: 10px
--radius-lg: 12px
--radius-xl: 16px
--radius-2xl: 20px
--radius-3xl: 24px (główne karty)
--radius-full: 9999px (circles)
```

### 6. Glassmorphism Effect
```css
/* Standard Glass */
backdrop-filter: blur(8px);
background: rgba(255, 255, 255, 0.8);
border: 1px solid rgba(255, 255, 255, 0.4);

/* Strong Glass */
backdrop-filter: blur(12px);
background: rgba(255, 255, 255, 0.9);
border: 1px solid rgba(255, 255, 255, 0.4);

/* Subtle Glass */
backdrop-filter: blur(6px);
background: rgba(255, 255, 255, 0.6);
border: 1px solid rgba(255, 255, 255, 0.3);
```

### 7. Shadow System
```css
/* Elevation Shadows */
--shadow-sm: 0 1px 3px 0 rgba(0,0,0,0.1), 0 1px 2px -1px rgba(0,0,0,0.1);
--shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -2px rgba(0,0,0,0.1);
--shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -4px rgba(0,0,0,0.1);
--shadow-xl: 0 20px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1);
--shadow-2xl: 0 25px 50px -12px rgba(0,0,0,0.25);
```

### 8. Component Specifications

#### Header
- Height: 64px
- Background: glass-strong
- Border-radius: 24px (top corners)
- Padding: 16px 24px
- Shadow: shadow-lg
- Sticky: top-0

#### Tab Navigation
- Height: 56px
- Background: glass-strong
- Border-radius: 16px
- Gap between tabs: 4px
- Active tab: gradient (indigo-500 to purple-600)
- Hover: bg-primary/5

#### Sidebar (Left Panel)
- Width: 40% (min 400px)
- Background: glass-subtle
- Border-radius: 24px
- Padding: 24px
- Overflow: auto

#### Main Content (Right Panel)
- Width: 60% (min 600px)
- Background: transparent
- Padding: 24px

#### Cards
- Background: glass-strong
- Border-radius: 24px
- Padding: 24px
- Gap: 24px
- Shadow: shadow-lg
- Hover: shadow-xl + translateY(-2px)

#### Buttons
- Height: 40px
- Padding: 10px 16px
- Border-radius: 12px
- Font: 14px / 600
- Transition: 200ms
- Primary: gradient (indigo-500 to purple-600)
- Secondary: glass with border
- Hover: scale(1.02) + shadow

#### Form Inputs
- Height: 40px
- Padding: 10px 14px
- Border-radius: 12px
- Border: 1px solid rgba(255,255,255,0.4)
- Background: glass
- Focus: ring-4 ring-primary/20

#### Color Picker
- Size: 48px × 48px
- Border-radius: 12px
- Border: 2px solid border-color
- Hover: ring-4 ring-primary/10

#### Palette Cards
- Grid: 2 columns (desktop), 1 column (mobile)
- Card size: 280px × 320px
- Border-radius: 16px
- Hover: elevation + scale(1.02)
- Color swatches: 48px circles

### 9. Animations & Transitions
```css
/* Default Transition */
transition: all 200ms cubic-bezier(0.4, 0, 0.2, 1);

/* Hover Effects */
.card:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-xl);
}

.button:hover {
  transform: scale(1.02);
}

/* Fade In */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Slide In */
@keyframes slideIn {
  from { transform: translateX(-100%); }
  to { transform: translateX(0); }
}
```

### 10. Responsive Breakpoints
```css
/* Mobile */
@media (max-width: 767px) {
  - Stack panels vertically
  - Single column grids
  - 48px touch targets
  - Simplified preview
}

/* Tablet */
@media (min-width: 768px) and (max-width: 1023px) {
  - 2-column grids
  - Collapsible sidebar
  - Reduced spacing
}

/* Desktop */
@media (min-width: 1024px) {
  - Full split-screen layout
  - 2-3 column grids
  - All features visible
}

/* Wide */
@media (min-width: 1600px) {
  - 3-column grids
  - Expanded preview
  - More whitespace
}
```

## Pliki do aktualizacji

1. ✅ `assets/src/css/variables.css` - Zaktualizować zmienne zgodnie z Figma
2. ✅ `assets/src/css/components/admin-page.css` - Layout split-screen
3. ✅ `assets/src/css/components/tabs.css` - Styl zakładek
4. ✅ `assets/src/css/components/cards.css` - Karty z glassmorphism
5. ✅ `assets/src/css/components/buttons.css` - Przyciski z gradientem
6. ✅ `assets/src/css/components/forms.css` - Inputy i formularze
7. ✅ `assets/src/css/utilities/glassmorphism.css` - Efekty szkła
8. ✅ `assets/src/css/responsive.css` - Media queries

## Kolejność implementacji

1. **Zmienne CSS** - Zaktualizować wszystkie zmienne zgodnie z Figma
2. **Layout** - Implementować split-screen z draggable divider
3. **Komponenty** - Zaktualizować style wszystkich komponentów
4. **Animacje** - Dodać transitions i hover effects
5. **Responsive** - Dostosować do wszystkich breakpointów
6. **Polish** - Dopracować detale (shadows, spacing, typography)

## Następne kroki

1. Zaktualizuj `variables.css` z wartościami z Figma
2. Zaimplementuj split-screen layout w `admin-page.css`
3. Dodaj glassmorphism effects do wszystkich komponentów
4. Przetestuj na różnych rozdzielczościach
5. Dopracuj animacje i transitions

---

**Status**: Gotowe do implementacji! 🚀
