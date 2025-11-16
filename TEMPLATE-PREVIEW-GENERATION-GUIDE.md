# Template Preview Image Generation Guide

## Overview

This guide provides step-by-step instructions for generating preview images for all 11 WOOW! Admin design templates. Each preview image must be **1200x800px** and showcase the template applied to the WordPress admin interface.

## Prerequisites

- WordPress installation with WOOW! Admin plugin active
- Admin access to WordPress
- Browser with developer tools (Chrome, Firefox, or Edge recommended)
- Image editing software (optional, for cropping)

## Quick Start

### Method 1: Using the Helper Script (Recommended)

1. **Access the helper script:**
   ```
   http://your-site.local/wp-content/plugins/woow-admin/generate-template-previews.php
   ```

2. **Follow the on-screen instructions:**
   - Click "Apply & Screenshot" for each template
   - Take screenshot at 1200x800px
   - Save to `assets/images/previews/templates/`
   - Return and proceed to next template

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

### Step 2: Apply Each Template

For each of the 11 templates, follow these steps:

#### Template 1: Modern Minimal

1. **Apply the template:**
   ```php
   // Via PHP (in WordPress admin or test script)
   $template_manager->apply_template('modern_minimal');
   ```
   
   OR use the helper script button

2. **Navigate to:** `wp-admin/index.php` (Dashboard)

3. **Verify the template is applied:**
   - Admin bar should be white/light gray
   - Admin menu should be minimal styling
   - Dashboard widgets should have clean, flat design
   - No glassmorphism effects
   - Sharp corners (0px border radius)

4. **Take screenshot:**
   - Use browser screenshot tool (Ctrl+Shift+S in Firefox)
   - Or use built-in screenshot in DevTools
   - Ensure entire 1200x800px viewport is captured

5. **Save the image:**
   - Filename: `modern-minimal.png`
   - Location: `woow-admin/assets/images/previews/templates/`
   - Format: PNG
   - No compression

#### Template 2: Glassmorphism Pro

1. Apply template: `glassmorphism_pro`
2. Screenshot filename: `glassmorphism-pro.png`
3. Verify:
   - Heavy glassmorphism effects everywhere
   - Gradient backgrounds
   - Strong blur effects (16px+)
   - Floating elements with premium shadows

#### Template 3: Dark Dashboard

1. Apply template: `dark_dashboard`
2. Screenshot filename: `dark-dashboard.png`
3. Verify:
   - Dark backgrounds (#0f172a, #1e293b)
   - Light text (#f1f5f9)
   - Neon accent colors
   - Strong contrast
   - Glow effects on interactive elements

#### Template 4: Colorful Creative

1. Apply template: `colorful_creative`
2. Screenshot filename: `colorful-creative.png`
3. Verify:
   - Multiple vibrant colors
   - Gradients on multiple elements
   - Rounded corners (16px+)
   - Playful animations
   - Bold typography

#### Template 5: Corporate Blue

1. Apply template: `corporate_blue`
2. Screenshot filename: `corporate-blue.png`
3. Verify:
   - Blue tones throughout
   - Clean lines
   - Professional fonts
   - Subtle effects
   - Trust-building elements

#### Template 6: Material Design

1. Apply template: `material_design`
2. Screenshot filename: `material-design.png`
3. Verify:
   - Material Design shadow system (elevation)
   - Floating action button styling
   - Material color palette
   - Card-based layout

#### Template 7: Flat 2.0

1. Apply template: `flat_2`
2. Screenshot filename: `flat-2.png`
3. Verify:
   - Flat colors, no gradients
   - Bold typography
   - Geometric shapes
   - Bright, saturated colors
   - No shadows

#### Template 8: Neumorphism

1. Apply template: `neumorphism`
2. Screenshot filename: `neumorphism.png`
3. Verify:
   - Soft shadow effects (embossed)
   - Subtle depth
   - Monochrome base colors
   - Tactile feel
   - Raised/inset elements

#### Template 9: Retro Wave

1. Apply template: `retro_wave`
2. Screenshot filename: `retro-wave.png`
3. Verify:
   - Neon colors (cyan, purple, pink)
   - Gradient backgrounds
   - Grid patterns (if visible)
   - Retro typography
   - Glow effects

#### Template 10: Nature Inspired

1. Apply template: `nature_inspired`
2. Screenshot filename: `nature-inspired.png`
3. Verify:
   - Green tones and earth colors
   - Organic shapes
   - Soft shadows
   - Natural textures
   - Calm mood

#### Template 11: High Contrast

1. Apply template: `high_contrast`
2. Screenshot filename: `high-contrast.png`
3. Verify:
   - Strong contrast (7:1+ ratio)
   - Large font sizes (16px+)
   - Clear borders on all elements
   - Accessible color combinations
   - Visible focus indicators

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

## Template Characteristics Reference

### Design Characteristics to Verify

| Template | Glassmorphism | Gradients | Animations | Shadows | Border Radius |
|----------|---------------|-----------|------------|---------|---------------|
| Modern Minimal | ❌ No | ❌ No | ❌ None | Minimal | Sharp (0px) |
| Glassmorphism Pro | ✅ Yes | ✅ Yes | Subtle | Premium | Rounded |
| Dark Dashboard | ✅ Yes | ✅ Yes | Subtle | Strong | Rounded |
| Colorful Creative | ✅ Yes | ✅ Yes | Playful | Medium | Very Rounded |
| Corporate Blue | ❌ No | ✅ Yes | Subtle | Subtle | Rounded |
| Material Design | ❌ No | ✅ Yes | Material | Elevation | Rounded |
| Flat 2.0 | ❌ No | ❌ No | None | None | Rounded |
| Neumorphism | ❌ No | ❌ No | Subtle | Soft | Rounded |
| Retro Wave | ✅ Yes | ✅ Yes | Retro | Glow | Rounded |
| Nature Inspired | ✅ Yes | ✅ Yes | Subtle | Soft | Organic |
| High Contrast | ❌ No | ❌ No | None | Strong | Sharp |

## Verification

After generating all preview images, verify:

### File Structure
```
woow-admin/
└── assets/
    └── images/
        └── previews/
            └── templates/
                ├── modern-minimal.png         ✓
                ├── glassmorphism-pro.png      ✓
                ├── dark-dashboard.png         ✓
                ├── colorful-creative.png      ✓
                ├── corporate-blue.png         ✓
                ├── material-design.png        ✓
                ├── flat-2.png                 ✓
                ├── neumorphism.png            ✓
                ├── retro-wave.png             ✓
                ├── nature-inspired.png        ✓
                └── high-contrast.png          ✓
```

### Verification Script

Run this command to verify all images exist:

```bash
cd woow-admin/assets/images/previews/templates/
ls -lh *.png | wc -l
# Should output: 11
```

Or use this PHP script:

```php
<?php
$preview_dir = __DIR__ . '/assets/images/previews/templates/';
$required_previews = [
    'modern-minimal.png',
    'glassmorphism-pro.png',
    'dark-dashboard.png',
    'colorful-creative.png',
    'corporate-blue.png',
    'material-design.png',
    'flat-2.png',
    'neumorphism.png',
    'retro-wave.png',
    'nature-inspired.png',
    'high-contrast.png',
];

$missing = [];
foreach ($required_previews as $file) {
    if (!file_exists($preview_dir . $file)) {
        $missing[] = $file;
    }
}

if (empty($missing)) {
    echo "✅ All 11 template preview images are present!\n";
} else {
    echo "❌ Missing preview images:\n";
    foreach ($missing as $file) {
        echo "   - $file\n";
    }
}
?>
```

## Troubleshooting

### Issue: Template not applying

**Solution:**
1. Clear WordPress cache
2. Clear browser cache (Ctrl+Shift+Delete)
3. Hard refresh (Ctrl+Shift+R)
4. Check if template was applied successfully (check database)
5. Verify template data in `includes/data/templates-data.php`

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

### Issue: Template characteristics not visible

**Solution:**
1. Verify template was applied correctly
2. Check CSS generation (regenerate if needed)
3. Clear all caches (browser, WordPress, plugin)
4. Inspect elements to verify styles are applied
5. Check for JavaScript errors in console

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

async function generateTemplatePreview(templateId) {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    
    // Set viewport
    await page.setViewport({ width: 1200, height: 800 });
    
    // Navigate to admin with template applied
    await page.goto(`http://your-site.local/wp-admin/?template=${templateId}`);
    
    // Wait for styles to load
    await page.waitForSelector('.woow-admin-styled');
    await page.waitForTimeout(2000); // Wait for animations
    
    // Take screenshot
    await page.screenshot({
        path: `assets/images/previews/templates/${templateId}.png`,
        fullPage: false
    });
    
    await browser.close();
}

// Generate all previews
const templates = [
    'modern-minimal',
    'glassmorphism-pro',
    'dark-dashboard',
    'colorful-creative',
    'corporate-blue',
    'material-design',
    'flat-2',
    'neumorphism',
    'retro-wave',
    'nature-inspired',
    'high-contrast',
];

for (const template of templates) {
    await generateTemplatePreview(template);
}
```

### Playwright (Node.js)

```javascript
const { chromium } = require('playwright');

async function generatePreviews() {
    const browser = await chromium.launch();
    const page = await browser.newPage();
    
    await page.setViewportSize({ width: 1200, height: 800 });
    
    const templates = [
        'modern-minimal',
        'glassmorphism-pro',
        // ... etc
    ];
    
    for (const template of templates) {
        // Apply template via API
        await page.goto(`http://your-site.local/wp-admin/admin-ajax.php?action=apply_template&template=${template}`);
        
        // Navigate to dashboard
        await page.goto('http://your-site.local/wp-admin/');
        
        // Wait for render
        await page.waitForTimeout(2000);
        
        // Screenshot
        await page.screenshot({
            path: `assets/images/previews/templates/${template}.png`
        });
    }
    
    await browser.close();
}

generatePreviews();
```

## Time Estimate

- **Manual method:** ~45-60 minutes for all 11 templates
- **With helper script:** ~30-40 minutes
- **Automated method:** ~10-15 minutes (after setup)

## Quality Assurance

Before considering previews complete:

### Visual Quality Checklist

For each template preview:

- [ ] All template characteristics are visible
- [ ] Colors match the template definition
- [ ] Typography is clearly visible
- [ ] Effects (glassmorphism, shadows, etc.) are apparent
- [ ] Layout is clean and professional
- [ ] No UI glitches or rendering issues
- [ ] Image is sharp and high quality

### Comparison Checklist

- [ ] Each template looks visually distinct from others
- [ ] No two templates look too similar (< 20% similarity)
- [ ] Each template represents its design philosophy
- [ ] Preview accurately represents what users will get

## Next Steps

After generating all template preview images:

1. ✅ Verify all 11 images exist
2. ✅ Check image quality and dimensions
3. ✅ Verify each template's characteristics are visible
4. ✅ Compare templates to ensure visual distinction
5. ✅ Commit images to version control
6. ✅ Update tasks.md to mark task 27 as complete
7. ✅ Proceed to next task in implementation plan

## Support

If you encounter issues:

1. Check the troubleshooting section above
2. Review the helper script output
3. Verify template data in `includes/data/templates-data.php`
4. Check WordPress error logs
5. Test template application manually
6. Verify CSS generation is working correctly

## Related Files

- **Helper Script:** `generate-template-previews.php`
- **Placeholder Generator:** `create-template-placeholder-previews.php`
- **Template Data:** `includes/data/templates-data.php`
- **Template Manager:** `includes/class-woow-template-manager.php`
- **Preview Directory:** `assets/images/previews/templates/`

---

**Generated:** 2024
**Version:** 1.0.0
**Task:** 27 - Generate preview images for templates
