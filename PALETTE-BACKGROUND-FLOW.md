# Palette Background Integration - Flow Diagram

## Visual Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                     USER APPLIES PALETTE                        │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│  JavaScript: main.js → applyPalette(paletteId)                  │
│  • Shows "Applying palette..." notification                     │
│  • Prepares AJAX request with palette ID                        │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│  AJAX Request to WordPress                                      │
│  • Action: woow_apply_palette                                   │
│  • Data: palette_id, nonce                                      │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│  PHP: WOOW_Palette_Manager::apply_palette()                     │
│  • Validates palette exists                                     │
│  • Checks palette completeness                                  │
│  • Creates backup (if backup manager available)                 │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│  Get Current Settings                                           │
│  • Loads all current plugin settings                            │
│  • Includes current background settings                         │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│  Merge Palette Settings                                         │
│  • Merges palette settings with current settings                │
│  • Palette settings override current settings                   │
│  • Includes ALL 10 sections (including backgrounds)             │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│  Background Settings Merged                                     │
│  ┌───────────────────────────────────────────────────────────┐ │
│  │ OLD BACKGROUND SETTINGS:                                  │ │
│  │ • background_color: #ffffff                               │ │
│  │ • gradient_start: #f0f0f0                                 │ │
│  │ • gradient_end: #e0e0e0                                   │ │
│  └───────────────────────────────────────────────────────────┘ │
│                           ↓ MERGE                               │
│  ┌───────────────────────────────────────────────────────────┐ │
│  │ PALETTE BACKGROUND SETTINGS:                              │ │
│  │ • background_color: #dbeafe                               │ │
│  │ • gradient_start: #dbeafe                                 │ │
│  │ • gradient_end: #e0e7ff                                   │ │
│  │ • gradient_angle: 135                                     │ │
│  │ • type: gradient                                          │ │
│  └───────────────────────────────────────────────────────────┘ │
│                           ↓ RESULT                              │
│  ┌───────────────────────────────────────────────────────────┐ │
│  │ NEW BACKGROUND SETTINGS:                                  │ │
│  │ • background_color: #dbeafe ← UPDATED!                    │ │
│  │ • gradient_start: #dbeafe ← UPDATED!                      │ │
│  │ • gradient_end: #e0e7ff ← UPDATED!                        │ │
│  │ • gradient_angle: 135 ← UPDATED!                          │ │
│  │ • type: gradient ← UPDATED!                               │ │
│  └───────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│  Update Database                                                │
│  • Saves merged settings to WordPress options                   │
│  • All sections updated (including backgrounds)                 │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│  Regenerate CSS                                                 │
│  • WOOW_CSS_Generator::generate()                               │
│  • Generates new CSS with updated background colors             │
│  • Applies gradient with new colors                             │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│  Return Success Response                                        │
│  • success: true                                                │
│  • settings: updated settings (including backgrounds)           │
│  • css: regenerated CSS                                         │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│  JavaScript: Receives Response                                  │
│  • Parses JSON response                                         │
│  • Extracts updated settings                                    │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│  JavaScript: updateFormFields(settings)                         │
│  • Loops through all settings sections                          │
│  • Updates form inputs with new values                          │
│  • Includes background tab inputs:                              │
│    - backgrounds[background_color] ← #dbeafe                    │
│    - backgrounds[gradient_start] ← #dbeafe                      │
│    - backgrounds[gradient_end] ← #e0e7ff                        │
│    - backgrounds[gradient_angle] ← 135                          │
│    - backgrounds[type] ← gradient                               │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│  Background Tab Updates                                         │
│  ┌───────────────────────────────────────────────────────────┐ │
│  │ BACKGROUND CUSTOMIZATION TAB                              │ │
│  │                                                           │ │
│  │ Background Color: [#dbeafe] ← UPDATED!                   │ │
│  │                                                           │ │
│  │ Gradient Start:   [#dbeafe] ← UPDATED!                   │ │
│  │                                                           │ │
│  │ Gradient End:     [#e0e7ff] ← UPDATED!                   │ │
│  │                                                           │ │
│  │ Gradient Angle:   [135°] ← UPDATED!                      │ │
│  │                                                           │ │
│  │ Type: [Gradient ▼] ← UPDATED!                            │ │
│  └───────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│  Live Preview Updates                                           │
│  • Applies new CSS to preview iframe                            │
│  • User sees background gradient change in real-time            │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│  Show Success Notification                                      │
│  • "Palette applied successfully!" ✓                            │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                    USER SEES UPDATED COLORS                     │
│  • Background tab shows new colors                              │
│  • Live preview shows new gradient                              │
│  • All colors coordinated with palette                          │
└─────────────────────────────────────────────────────────────────┘
```

## Data Flow Example

### Before Applying Palette

```javascript
// Current Settings
{
  backgrounds: {
    background_color: '#ffffff',
    gradient_start: '#f0f0f0',
    gradient_end: '#e0e0e0',
    gradient_angle: '90',
    type: 'solid'
  }
}
```

### Palette Data (Professional Blue)

```php
// Palette Settings
'backgrounds' => array(
    'enabled' => true,
    'background_color' => '#dbeafe',
    'background_opacity' => '1',
    'type' => 'gradient',
    'gradient_type' => 'linear',
    'gradient_start' => '#dbeafe',
    'gradient_end' => '#e0e7ff',
    'gradient_angle' => '135',
    'wpbody_content_color' => 'transparent',
    'wpbody_content_opacity' => '1',
)
```

### After Merging

```javascript
// Merged Settings
{
  backgrounds: {
    enabled: true,
    background_color: '#dbeafe',      // ← FROM PALETTE
    background_opacity: '1',          // ← FROM PALETTE
    type: 'gradient',                 // ← FROM PALETTE
    gradient_type: 'linear',          // ← FROM PALETTE
    gradient_start: '#dbeafe',        // ← FROM PALETTE
    gradient_end: '#e0e7ff',          // ← FROM PALETTE
    gradient_angle: '135',            // ← FROM PALETTE
    wpbody_content_color: 'transparent', // ← FROM PALETTE
    wpbody_content_opacity: '1'       // ← FROM PALETTE
  }
}
```

### CSS Generated

```css
/* Generated CSS */
body.wp-admin {
    background: linear-gradient(135deg, #dbeafe, #e0e7ff) !important;
}

#wpbody-content {
    background: transparent !important;
}
```

## Key Points

1. **Automatic Update:** Background colors update automatically when palette is applied
2. **Complete Merge:** All background fields are updated, not just colors
3. **Live Preview:** Changes are visible immediately in live preview
4. **Form Update:** Background tab form fields update to show new values
5. **Persistence:** New settings are saved to database
6. **CSS Regeneration:** New CSS is generated with updated colors
7. **User Control:** Users can still manually adjust colors after applying palette

## Error Handling

```
┌─────────────────────────────────────────────────────────────────┐
│  Error Occurs During Application                                │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│  Automatic Rollback                                             │
│  • Restores from backup (if available)                          │
│  • Reverts to previous settings                                 │
│  • Shows error notification to user                             │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│  User Notified                                                  │
│  • "Failed to apply palette" ✗                                  │
│  • Settings remain unchanged                                    │
└─────────────────────────────────────────────────────────────────┘
```

---

**This flow ensures seamless integration between palettes and background colors!** 🎨✨
