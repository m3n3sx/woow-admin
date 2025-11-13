# AdminMenu Defaults Comparison: 4de3336 vs Current

## Key Differences Found

### 1. Text Color
**4de3336:** `'text_color' => '#0f172a'` (dark slate)
**Current:** `'text_color' => '#64748b'` (gray)

❌ **PROBLEM:** Text jest jaśniejszy (szary zamiast ciemnego)

### 2. Hover Text Color
**4de3336:** `'hover_text_color' => '#6366f1'` (indigo)
**Current:** `'hover_text_color' => '#0f172a'` (dark slate)

❌ **PROBLEM:** Hover text jest ciemny zamiast indigo

### 3. Border Radius
**4de3336:** `'border_radius' => '12'` (single value)
**Current:** `'border_radius_all' => '24'` (doubled!)

❌ **PROBLEM:** Border radius jest 2x większy (24px zamiast 12px)

### 4. Shadow Style
**4de3336:** Brak explicit shadow_style (używa default)
**Current:** `'shadow_style' => 'lg'`

⚠️ **MOŻLIWY PROBLEM:** Większy cień

### 5. Opacity
**4de3336:** `'opacity' => 0.9`
**Current:** `'opacity' => 0.8`

⚠️ **MOŻLIWY PROBLEM:** Bardziej przezroczyste

### 6. Border Color
**4de3336:** `'border_color' => '#e2e8f0'` (solid)
**Current:** `'border_color' => 'rgba(226, 232, 240, 0.5)'` (semi-transparent)

⚠️ **MOŻLIWY PROBLEM:** Bardziej przezroczysty border

### 7. Hover Background
**4de3336:** `'hover_bg_color' => '#f8fafc'` (solid)
**Current:** `'hover_bg_color' => 'rgba(248, 250, 252, 0.8)'` (semi-transparent)

⚠️ **MOŻLIWY PROBLEM:** Bardziej przezroczyste hover

### 8. Margins
**4de3336:** Brak margin settings
**Current:** 
```php
'margin_mode' => 'individual',
'margin_top' => '16',
'margin_right' => '0',
'margin_bottom' => '16',
'margin_left' => '16',
```

⚠️ **NOWA FUNKCJA:** Dodane marginesy

### 9. Submenu
**4de3336:** Brak submenu settings
**Current:**
```php
'submenu_bg_color' => 'rgba(255, 255, 255, 0.98)',
'submenu_text_color' => '#0f172a',
'submenu_hover_bg_color' => '#f1f5f9',
'submenu_border_radius' => '12',
```

⚠️ **NOWA FUNKCJA:** Dodane submenu styling

## Critical Fixes Needed

### Fix 1: Text Colors
```php
// CHANGE FROM:
'text_color' => '#64748b',
'hover_text_color' => '#0f172a',

// CHANGE TO:
'text_color' => '#0f172a',
'hover_text_color' => '#6366f1',
```

### Fix 2: Border Radius
```php
// CHANGE FROM:
'border_radius_all' => '24',

// CHANGE TO:
'border_radius_all' => '12',
```

### Fix 3: Opacity (Optional)
```php
// CHANGE FROM:
'opacity' => 0.8,

// CHANGE TO:
'opacity' => 0.9,
```

### Fix 4: Border Color (Optional)
```php
// CHANGE FROM:
'border_color' => 'rgba(226, 232, 240, 0.5)',

// CHANGE TO:
'border_color' => '#e2e8f0',
```

### Fix 5: Hover Background (Optional)
```php
// CHANGE FROM:
'hover_bg_color' => 'rgba(248, 250, 252, 0.8)',

// CHANGE TO:
'hover_bg_color' => '#f8fafc',
```

## Visual Impact

### Text Color Change
- **Before:** Gray text (#64748b) - less readable
- **After:** Dark slate text (#0f172a) - more readable

### Hover Text Color Change
- **Before:** Dark text on hover (#0f172a) - boring
- **After:** Indigo text on hover (#6366f1) - modern, colorful

### Border Radius Change
- **Before:** 24px - very rounded, "bubbly"
- **After:** 12px - subtle rounded, professional

## Implementation

Apply these changes to `includes/defaults.php`:

```php
'admin_menu' => array(
    'enabled' => true,
    
    // Background
    'background_type' => 'solid',
    'background_color' => '#ffffff',
    'glass_base_color' => '#ffffff',
    'gradient_start' => '#ffffff',
    'gradient_end' => '#f8fafc',
    'border_color' => '#e2e8f0',  // ← FIXED: solid instead of rgba
    
    // Text
    'text_color' => '#0f172a',  // ← FIXED: dark slate instead of gray
    'hover_text_color' => '#6366f1',  // ← FIXED: indigo instead of dark
    
    // Hover
    'hover_bg_color' => '#f8fafc',  // ← FIXED: solid instead of rgba
    'hover_style' => 'normal',
    
    // Active (Gradient or Solid)
    'active_bg_type' => 'gradient',
    'active_bg_solid' => '#6366f1',
    'active_bg_start' => '#6366f1',
    'active_bg_end' => '#8b5cf6',
    'active_text_color' => '#ffffff',
    
    // Dimensions
    'width' => '256',
    'item_height' => '48',
    
    // Border Radius
    'border_radius_mode' => 'all',
    'border_radius_all' => '12',  // ← FIXED: 12 instead of 24
    'border_radius_top_left' => '12',  // ← FIXED
    'border_radius_top_right' => '12',  // ← FIXED
    'border_radius_bottom_right' => '12',  // ← FIXED
    'border_radius_bottom_left' => '12',  // ← FIXED
    
    // Item Border Radius (for menu items)
    'item_border_radius' => '12',
    
    // Typography
    'font_size' => '14',
    'font_weight' => '600',
    
    // Glassmorphism
    'glassmorphism' => true,
    'opacity' => 0.9,  // ← FIXED: 0.9 instead of 0.8
    'blur_strength' => '12',
    
    // Shadow
    'shadow_style' => 'sm',  // ← OPTIONAL: sm instead of lg for subtler shadow
    
    // ... rest stays the same ...
),
```

## Testing After Fix

1. Delete settings: `wp option delete woow_admin_settings`
2. Refresh browser
3. Check adminmenu:
   - [ ] Text is dark slate (#0f172a)
   - [ ] Hover text is indigo (#6366f1)
   - [ ] Border radius is 12px (subtle)
   - [ ] Looks like commit 4de3336

## Summary

**Main Issues:**
1. ❌ Text color too light (gray instead of dark)
2. ❌ Hover text wrong color (dark instead of indigo)
3. ❌ Border radius too large (24px instead of 12px)

**Fix:** Update defaults.php with correct values from commit 4de3336

**Impact:** AdminMenu will look like "Modern Minimal" template from Figma
