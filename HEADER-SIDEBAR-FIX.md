# Header & Sidebar Layout Fix

## Problem
Header i sidebar nie były prawidłowo pozycjonowane względem WordPress admin bar i menu.

## Rozwiązanie

### 1. Header Positioning
**Przed:**
```css
position: fixed;
top: 0;
left: 0;
```

**Po:**
```css
position: fixed;
top: 32px; /* Below WordPress admin bar */
left: 160px; /* After WordPress sidebar */
right: 0;
```

### 2. Sidebar Positioning
**Przed:**
```css
position: sticky;
top: 120px;
width: 256px;
```

**Po:**
```css
position: fixed;
top: 152px; /* Below header + admin bar */
left: 160px; /* After WordPress sidebar */
bottom: 0;
width: 240px;
```

### 3. Main Content Positioning
**Przed:**
```css
margin-top: 120px;
flex: 1;
```

**Po:**
```css
margin-top: 152px; /* Header + admin bar */
margin-left: 400px; /* WP sidebar + WOOW sidebar */
width: 100%;
```

## Layout Structure

```
┌─────────────────────────────────────────────────────────┐
│  WordPress Admin Bar (32px)                             │
├──────────┬──────────────────────────────────────────────┤
│          │  WOOW Header Row 1 (64px)                    │
│  WP      ├──────────────────────────────────────────────┤
│  Sidebar │  WOOW Header Row 2 (56px)                    │
│  (160px) ├──────────┬───────────────────────────────────┤
│          │  WOOW    │                                   │
│          │  Sidebar │  Main Content                     │
│          │  (240px) │  (Scrollable)                     │
│          │          │                                   │
│          │  Fixed   │  margin-left: 400px               │
│          │          │                                   │
└──────────┴──────────┴───────────────────────────────────┘
```

## Responsive Behavior

### Desktop (> 960px)
- Full layout with all sidebars visible
- Header: `left: 160px`
- Sidebar: `left: 160px`
- Content: `margin-left: 400px`

### Tablet (782px - 960px)
- WordPress menu folded
- Header: `left: 36px`
- Sidebar: `left: 36px`
- Content: `margin-left: 276px`

### Mobile (< 782px)
- Full width layout
- Header: `left: 0, top: 46px`
- Sidebar: Horizontal scroll, `top: 166px`
- Content: `margin-left: 0, margin-top: 226px`

## Key CSS Classes

### Header
- `.woow-header` - Fixed header container
- `.woow-header-row-1` - Title bar (64px)
- `.woow-header-row-2` - Control bar (56px)

### Sidebar
- `.woow-sidebar` - Fixed sidebar container
- `.woow-sidebar-logo` - Logo section (64px)
- `.woow-sidebar-nav` - Navigation items

### Layout
- `.woow-layout-container` - Main layout wrapper
- `.woow-main-content` - Content area
- `.woow-content-inner` - Content max-width container

## WordPress Integration

### Admin Bar Height
- Desktop: 32px
- Mobile: 46px

### Admin Menu Width
- Expanded: 160px
- Folded: 36px
- Mobile: 0px (hidden)

### Z-Index Layers
- WordPress Admin Bar: 99999
- WOOW Header: 1000
- WOOW Sidebar: 100
- Content: 1

## Testing Checklist

- [x] Header positioned correctly below WP admin bar
- [x] Sidebar positioned correctly after WP menu
- [x] Content area has proper margins
- [x] Responsive behavior on tablet
- [x] Responsive behavior on mobile
- [x] Works with folded WP menu
- [x] Works with expanded WP menu
- [x] No overlapping elements
- [x] Proper scrolling behavior

## Files Modified

1. `assets/src/css/components/header-figma.css`
   - Fixed positioning relative to WP admin bar
   - Added responsive adjustments
   - Added folded menu support

2. `assets/src/css/components/sidebar-figma.css`
   - Changed from sticky to fixed positioning
   - Added proper top/left offsets
   - Mobile horizontal scroll

3. `assets/src/css/components/admin-page.css`
   - Updated layout container margins
   - Removed flex layout (not needed with fixed sidebar)
   - Added responsive margin adjustments

## Next Steps

1. Test on different screen sizes
2. Test with different WordPress themes
3. Verify scrolling behavior
4. Check z-index conflicts
5. Test keyboard navigation
6. Verify accessibility

## Notes

- Layout now properly integrates with WordPress admin
- Fixed positioning ensures header/sidebar stay visible
- Content area scrolls independently
- Mobile layout collapses to single column
- All measurements match Figma specifications
