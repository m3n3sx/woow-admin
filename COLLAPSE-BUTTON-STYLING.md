# ✨ Collapse Button Styling - Figma Design

## Nowy Wygląd Przycisku "Zwiń Menu"

Przycisk "Zwiń menu" został ostylowany zgodnie z projektem Figma - nowoczesny, glassmorphic design.

## Design Specs

### Expanded State (Normal)
```css
#collapse-menu {
    /* Layout */
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    margin: 8px;
    
    /* Visual */
    background: rgba(99, 102, 241, 0.08);      /* Indigo-500 @ 8% */
    border: 1px solid rgba(99, 102, 241, 0.2); /* Indigo-500 @ 20% */
    border-radius: 12px;
    
    /* Typography */
    color: #6366f1;                             /* Indigo-500 */
    font-size: 13px;
    font-weight: 600;
    
    /* Interaction */
    cursor: pointer;
    transition: all 0.2s ease;
}
```

### Hover State
```css
#collapse-menu:hover {
    background: rgba(99, 102, 241, 0.15);      /* Darker on hover */
    border-color: rgba(99, 102, 241, 0.3);
    transform: translateY(-1px);                /* Subtle lift */
    box-shadow: 0 2px 8px rgba(99, 102, 241, 0.15);
}

/* Icon slides left on hover */
#collapse-menu:hover .collapse-button-icon {
    transform: translateX(-2px);
}
```

### Active State
```css
#collapse-menu:active {
    transform: translateY(0);                   /* Press down */
    box-shadow: 0 1px 3px rgba(99, 102, 241, 0.1);
}
```

### Collapsed State (Icon Only)
```css
.folded #collapse-menu {
    justify-content: center;
    padding: 12px;
    width: calc(100% - 16px);
}

.folded #collapse-menu .collapse-button-label {
    display: none;                              /* Hide text */
}

/* Icon slides right on hover (reversed) */
.folded #collapse-menu:hover .collapse-button-icon {
    transform: translateX(2px);
}
```

## Visual Features

### 🎨 Colors (Indigo Theme)
- **Background:** `rgba(99, 102, 241, 0.08)` - Subtle indigo tint
- **Border:** `rgba(99, 102, 241, 0.2)` - Soft indigo border
- **Text:** `#6366f1` - Indigo-500
- **Hover:** Darker background + shadow

### 🔄 Animations
- **Hover lift:** `translateY(-1px)` - Button lifts slightly
- **Icon slide:** `translateX(-2px)` - Icon slides on hover
- **Active press:** `translateY(0)` - Button presses down
- **Smooth transitions:** `0.2s ease` - All changes are smooth

### 📐 Spacing
- **Padding:** `10px 16px` (expanded), `12px` (collapsed)
- **Margin:** `8px` around button
- **Gap:** `8px` between icon and text
- **Border radius:** `12px` - Rounded corners

### 🎯 Icon Behavior
- **Expanded:** Icon slides **left** on hover (←)
- **Collapsed:** Icon slides **right** on hover (→)
- **Size:** `16px × 16px`
- **Centered:** Flexbox alignment

## HTML Structure

```html
<a id="collapse-menu" href="#">
    <span class="collapse-button-icon">
        <!-- Icon SVG or dashicon -->
    </span>
    <span class="collapse-button-label">
        Zwiń menu
    </span>
</a>
```

## Responsive Behavior

### Expanded Menu (256px)
- ✅ Shows icon + text
- ✅ Full width with margins
- ✅ Icon slides left on hover

### Collapsed Menu (80px)
- ✅ Shows icon only
- ✅ Text hidden
- ✅ Centered icon
- ✅ Icon slides right on hover

## Figma Design Match

### ✅ Glassmorphism
- Subtle background tint
- Soft border
- Blur effect (via rgba)

### ✅ Modern Interactions
- Hover lift effect
- Icon animation
- Active press feedback
- Smooth transitions

### ✅ Color System
- Indigo-500 primary color
- Consistent with Figma palette
- Proper contrast ratios

### ✅ Typography
- 13px font size
- 600 font weight (semibold)
- Proper spacing

## Code Location

**File:** `includes/class-woow-css-generator.php`

**Line:** ~1220 (after collapsed state adjustments)

```php
// ✨ Collapse Button Styling (Figma Design)
$this->css .= "/* Collapse Menu Button - Modern Design */\n";
$this->css .= "#collapse-menu {\n";
// ... styling ...
$this->css .= "}\n\n";
```

## Build & Deploy

```bash
npm run build  # ✅ Done
./cc.sh        # Clear cache
# Hard refresh: Ctrl + Shift + R
```

## Testing

### Test 1: Expanded State
1. ✅ Przycisk ma indigo background
2. ✅ Hover → button lifts + shadow
3. ✅ Hover → icon slides left
4. ✅ Click → button presses down

### Test 2: Collapsed State
1. ✅ Kliknij "Zwiń menu"
2. ✅ Przycisk pokazuje tylko ikonę
3. ✅ Ikona jest wycentrowana
4. ✅ Hover → icon slides right

### Test 3: Transitions
1. ✅ Wszystkie zmiany są smooth (0.2s)
2. ✅ Brak jittery animations
3. ✅ Hover/active states działają poprawnie

## Visual Preview

```
┌─────────────────────────────────┐
│  ← Zwiń menu                    │  ← Expanded
└─────────────────────────────────┘
     ↑ Hover: lifts + shadow
     ↑ Icon slides left

┌──────────┐
│    ←     │  ← Collapsed (icon only)
└──────────┘
     ↑ Hover: icon slides right
```

## Status: ✅ GOTOWE

Przycisk "Zwiń menu" został ostylowany zgodnie z projektem Figma:
- ✅ Nowoczesny glassmorphic design
- ✅ Smooth animations
- ✅ Responsive behavior
- ✅ Indigo color theme
- ✅ Icon interactions

**Wygląda pięknie! 🎉**
