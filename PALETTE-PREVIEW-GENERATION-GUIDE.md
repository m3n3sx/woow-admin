# Palette Preview Image Generation Guide

## Overview

This guide provides step-by-step instructions for generating preview images for all 10 WOOW! Admin color palettes. Each preview image must be **1200x800px** and showcase the palette applied to the WordPress admin interface.

## Prerequisites

- WordPress installation with WOOW! Admin plugin active
- Admin access to WordPress
- Browser with developer tools (Chrome, Firefox, or Edge recommended)
- Image editing software (optional, for cropping)

## Quick Start

### Method 1: Using the Helper Script (Recommended)

1. **Access the helper script:**
   ```
   http://your-site.local/wp-content/plugins/woow-admin/generate-palette-previews.php
   ```

2. **Follow the on-screen instructions:**
   - Click "Apply & Screenshot" for each palette
   - Take screenshot at 1200x800px
   - Save to `assets/images/previews/palettes/`
   - Return and proceed to next palette

3. **Track progress:**
   - The helper shows which previews are complete
   - Progress bar indicates overall completion

### Method 2: Manual Process

If you prefer manual control, follow the detailed steps below.

## Detailed Step-by-Step Instructions

### Step 1: Set Up Browser Viewport

1. Open your WordPress admin dashboard
2. Press **F12** to open Developer Tools
3. Click the **Device Toolbar** icon (or press Ctrl+Shift+M / Cmd+Shift+M)
4. Set viewport dimensions:
   - **Width:** 1200px
   - **Height:** 800px
5. Set zoom to **100%**

### Step 2: Apply Each Palette

For each of the 10 palettes, follow these steps:

#### Palette 1: Professional Blue

1. **Apply the palette:**
   ```php
   // Via PHP (in WordPress admin or test script)
   $palette_manager->apply_palette('professional_blue');
   ```
   
   OR use the helper script button

2. **Navigate to:** `wp-admin/index.php` (Dashboard)

3. **Verify the palette is applied:**
   - Admin bar should be blue gradient
   - Admin menu should be dark blue
   - Dashboard widgets should have blue accents

4. **Take screenshot:**
   - Use browser screenshot tool (Ctrl+Shift+S in Firefox)
   - Or use built-in screenshot in DevTools
   - Ensure entire 1200x800px viewport is captured

5. **Save the image:**
   - Filename: `professional-blue.png`
   - Location: `woow-admin/assets/images/previews/palettes/`
   - Format: PNG
   - No compression

#### Palette 2: Warm Sunset

1. Apply palette: `warm_sunset`
2. Screenshot filename: `warm-sunset.png`
3. Verify warm orange/amber colors throughout

#### Palette 3: Dark Mode Pro

1. Apply palette: `dark_mode_pro`
2. Screenshot filename: `dark-mode-pro.png`
3. Verify dark backgrounds with purple/cyan accents

#### Palette 4: Nature Green

1. Apply palette: `nature_green`
2. Screenshot filename: `nature-green.png`
3. Verify green/emerald tones throughout

#### Palette 5: Minimalist Gray

1. Apply palette: `minimalist_gray`
2. Screenshot filename: `minimalist-gray.png`
3. Verify neutral gray tones, minimal effects

#### Palette 6: Vibrant Purple

1. Apply palette: `vibrant_purple`
2. Screenshot filename: `vibrant-purple.png`
3. Verify purple/pink vibrant colors

#### Palette 7: Ocean Blue

1. Apply palette: `ocean_blue`
2. Screenshot filename: `ocean-blue.png`
3. Verify sky blue/cyan ocean tones

#### Palette 8: Cherry Red

1. Apply palette: `cherry_red`
2. Screenshot filename: `cherry-red.png`
3. Verify red/orange bold colors

#### Palette 9: Monochrome Elite

1. Apply palette: `monochrome_elite`
2. Screenshot filename: `monochrome-elite.png`
3. Verify black/white with gold accents

#### Palette 10: Cyberpunk Neon

1. Apply palette: `cyberpunk_neon`
2. Screenshot filename: `cyberpunk-neon.png`
3. Verify dark background with neon cyan/purple/pink

## Screenshot Composition Guidelines

Each preview image should show:

### Required Elements (Must be visible)

1. **Admin Bar (Top)**
   - Full width
   - Logo/branding visible
   - Menu items visible
   - User info visible

2. **Admin Menu (Left Side)**
   - At least 5-6 menu items visible
   - Hover state on one item (optional)
   - Submenu visible (optional)

3. **Dashboard Content (Center)**
   - At least 2 dashboard widgets
   - Widget titles visible
   - Some content in widgets

4. **Form Elements (If visible)**
   - Input fields
   - Buttons
   - Any form controls

### Composition Tips

- **Center the most important elements** (admin bar and menu)
- **Show variety** - different widget types if possible
- **Avoid clutter** - don't show too many overlapping elements
- **Good lighting** - ensure colors are clearly visible
- **No personal data** - avoid showing real user names, emails, etc.

## Screenshot Tools

### Browser Built-in Tools

**Chrome/Edge:**
1. Open DevTools (F12)
2. Press Ctrl+Shift+P (Cmd+Shift+P on Mac)
3. Type "screenshot"
4. Select "Capture screenshot" or "Capture full size screenshot"

**Firefox:**
1. Open DevTools (F12)
2. Click the camera icon in the toolbar
3. Or press Ctrl+Shift+S

**Safari:**
1. Enable Develop menu (Preferences > Advanced)
2. Develop > Show Web Inspector
3. Use screenshot tool in inspector

### Third-Party Tools (Optional)

- **Lightshot** - Quick screenshot with editing
- **Greenshot** - Advanced screenshot tool
- **Snagit** - Professional screenshot software
- **ShareX** - Free, open-source screenshot tool

## Image Specifications

### Technical Requirements

- **Dimensions:** Exactly 1200x800px
- **Format:** PNG (not JPG)
- **Color Space:** sRGB
- **Bit Depth:** 24-bit (8 bits per channel)
- **Compression:** None or lossless
- **File Size:** Typically 100-500KB per image

### Quality Checklist

- [ ] Image is sharp and clear (no blur)
- [ ] Colors are accurate (match what you see in browser)
- [ ] No compression artifacts
- [ ] No watermarks or overlays
- [ ] Proper dimensions (1200x800px exactly)
- [ ] Saved as PNG format
- [ ] Correct filename (kebab-case)

## Verification

After generating all preview images, verify:

### File Structure
```
woow-admin/
└── assets/
    └── images/
        └── previews/
            └── palettes/
                ├── professional-blue.png      ✓
                ├── warm-sunset.png            ✓
                ├── dark-mode-pro.png          ✓
                ├── nature-green.png           ✓
                ├── minimalist-gray.png        ✓
                ├── vibrant-purple.png         ✓
                ├── ocean-blue.png             ✓
                ├── cherry-red.png             ✓
                ├── monochrome-elite.png       ✓
                └── cyberpunk-neon.png         ✓
```

### Verification Script

Run this command to verify all images exist:

```bash
cd woow-admin/assets/images/previews/palettes/
ls -lh *.png | wc -l
# Should output: 10
```

Or use this PHP script:

```php
<?php
$preview_dir = __DIR__ . '/assets/images/previews/palettes/';
$required_previews = [
    'professional-blue.png',
    'warm-sunset.png',
    'dark-mode-pro.png',
    'nature-green.png',
    'minimalist-gray.png',
    'vibrant-purple.png',
    'ocean-blue.png',
    'cherry-red.png',
    'monochrome-elite.png',
    'cyberpunk-neon.png',
];

$missing = [];
foreach ($required_previews as $file) {
    if (!file_exists($preview_dir . $file)) {
        $missing[] = $file;
    }
}

if (empty($missing)) {
    echo "✅ All 10 palette preview images are present!\n";
} else {
    echo "❌ Missing preview images:\n";
    foreach ($missing as $file) {
        echo "   - $file\n";
    }
}
?>
```

## Troubleshooting

### Issue: Palette not applying

**Solution:**
1. Clear WordPress cache
2. Clear browser cache (Ctrl+Shift+Delete)
3. Hard refresh (Ctrl+Shift+R)
4. Check if palette was applied successfully (check database)

### Issue: Screenshot is wrong size

**Solution:**
1. Verify DevTools viewport is set to 1200x800px
2. Ensure zoom is at 100%
3. Use browser's built-in screenshot tool (not external)
4. Crop image to exact dimensions if needed

### Issue: Colors look different in screenshot

**Solution:**
1. Ensure browser color profile is sRGB
2. Disable any browser extensions that modify colors
3. Check monitor calibration
4. Use PNG format (not JPG which can alter colors)

### Issue: Image file is too large

**Solution:**
1. Use PNG-8 instead of PNG-24 if colors allow
2. Use lossless compression tools:
   - `pngquant` (command line)
   - `TinyPNG` (online)
   - `ImageOptim` (Mac)
3. Ensure no unnecessary metadata is included

## Automation (Advanced)

For automated screenshot generation, you can use:

### Puppeteer (Node.js)

```javascript
const puppeteer = require('puppeteer');

async function generatePalettePreview(paletteId) {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    
    // Set viewport
    await page.setViewport({ width: 1200, height: 800 });
    
    // Navigate to admin with palette applied
    await page.goto(`http://your-site.local/wp-admin/?palette=${paletteId}`);
    
    // Wait for styles to load
    await page.waitForSelector('.woow-admin-styled');
    
    // Take screenshot
    await page.screenshot({
        path: `assets/images/previews/palettes/${paletteId}.png`,
        fullPage: false
    });
    
    await browser.close();
}

// Generate all previews
const palettes = [
    'professional-blue',
    'warm-sunset',
    // ... etc
];

for (const palette of palettes) {
    await generatePalettePreview(palette);
}
```

### Playwright (Node.js)

```javascript
const { chromium } = require('playwright');

async function generatePreviews() {
    const browser = await chromium.launch();
    const page = await browser.newPage();
    
    await page.setViewportSize({ width: 1200, height: 800 });
    
    const palettes = ['professional-blue', 'warm-sunset', /* ... */];
    
    for (const palette of palettes) {
        // Apply palette via API
        await page.goto(`http://your-site.local/wp-admin/admin-ajax.php?action=apply_palette&palette=${palette}`);
        
        // Navigate to dashboard
        await page.goto('http://your-site.local/wp-admin/');
        
        // Wait for render
        await page.waitForTimeout(1000);
        
        // Screenshot
        await page.screenshot({
            path: `assets/images/previews/palettes/${palette}.png`
        });
    }
    
    await browser.close();
}

generatePreviews();
```

## Time Estimate

- **Manual method:** ~30-45 minutes for all 10 palettes
- **With helper script:** ~20-30 minutes
- **Automated method:** ~5-10 minutes (after setup)

## Next Steps

After generating all palette preview images:

1. ✅ Verify all 10 images exist
2. ✅ Check image quality and dimensions
3. ✅ Commit images to version control
4. ✅ Update tasks.md to mark subtasks as complete
5. ✅ Proceed to Task 27: Generate template preview images

## Support

If you encounter issues:

1. Check the troubleshooting section above
2. Review the helper script output
3. Verify palette data in `includes/data/palettes.php`
4. Check WordPress error logs
5. Test palette application manually

---

**Generated:** 2024
**Version:** 1.0.0
**Task:** 26 - Generate preview images for palettes
