# WOOW! Admin - Developer Guide

## Table of Contents

1. [Overview](#overview)
2. [Architecture](#architecture)
3. [Palette Data Structure](#palette-data-structure)
4. [Template Data Structure](#template-data-structure)
5. [Adding New Palettes](#adding-new-palettes)
6. [Adding New Templates](#adding-new-templates)
7. [REST API Endpoints](#rest-api-endpoints)
8. [Manager Classes](#manager-classes)
9. [Validation & Testing](#validation--testing)
10. [Best Practices](#best-practices)

---

## Overview

The WOOW! Admin plugin provides a comprehensive system for managing color palettes and design templates. This guide is intended for developers who want to:

- Understand the internal architecture
- Add new palettes or templates
- Integrate with the REST API
- Extend or customize the system

### Key Concepts

**Palette**: A color-focused configuration that sets colors across all sections while maintaining reasonable defaults for other properties.

**Template**: A complete design configuration that sets ALL options including colors, typography, spacing, effects, and more.

**Settings Sections**: The plugin organizes settings into 10 major sections:
1. Color Overrides (7 colors)
2. Admin Bar (25+ options)
3. Admin Menu (15+ options)
4. Dashboard Widgets (10+ options)
5. Form Controls (10+ options)
6. Buttons (10+ options)
7. Backgrounds (6+ options)
8. Typography (10+ options)
9. Effects (8+ options)
10. Login Page (10+ options)


---

## Architecture

### System Components

```
┌─────────────────────────────────────────────────────────────┐
│                     WOOW! Admin Plugin                       │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  ┌──────────────────┐      ┌──────────────────┐            │
│  │  Palette Manager │      │ Template Manager │            │
│  │                  │      │                  │            │
│  │ - Load palettes  │      │ - Load templates │            │
│  │ - Apply palette  │      │ - Apply template │            │
│  │ - Validate data  │      │ - Validate data  │            │
│  └────────┬─────────┘      └────────┬─────────┘            │
│           │                         │                       │
│           └─────────┬───────────────┘                       │
│                     │                                       │
│           ┌─────────▼─────────┐                            │
│           │  Settings Manager │                            │
│           │                   │                            │
│           │ - Store settings  │                            │
│           │ - Merge defaults  │                            │
│           │ - Validate values │                            │
│           └─────────┬─────────┘                            │
│                     │                                       │
│           ┌─────────▼─────────┐                            │
│           │   CSS Generator   │                            │
│           │                   │                            │
│           │ - Generate CSS    │                            │
│           │ - Apply styles    │                            │
│           └───────────────────┘                            │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

### File Structure

```
woow-admin/
├── includes/
│   ├── palettes/
│   │   └── class-woow-palette-manager.php
│   ├── templates/
│   │   └── (existing tab templates)
│   ├── data/
│   │   ├── palettes.php                      (Palette definitions)
│   │   └── templates-data.php                (Template definitions)
│   ├── class-woow-settings.php               (Settings management)
│   ├── class-woow-template-manager.php       (Template management)
│   ├── class-woow-rest-api.php               (REST API endpoints)
│   └── defaults.php                          (Default settings)
├── assets/
│   ├── images/
│   │   └── previews/
│   │       ├── palettes/                     (Palette preview images)
│   │       └── templates/                    (Template preview images)
│   └── src/
│       └── js/
│           └── components/
│               ├── PaletteSelector.js
│               └── TemplateSelector.js
└── docs/
    └── DEVELOPER-GUIDE.md                    (This file)
```


---

## Palette Data Structure

### Complete Palette Schema

A palette is a PHP array with the following structure:

```php
array(
    // Metadata
    'id'            => 'unique_palette_id',      // Required: Unique identifier (snake_case)
    'name'          => 'Display Name',           // Required: Human-readable name
    'description'   => 'Brief description',      // Required: Short description
    'category'      => 'professional',           // Required: professional, creative, minimal, dark, vibrant
    'preview_image' => 'palette-id.png',         // Required: Preview image filename
    'author'        => 'WOOW! Admin',            // Optional: Creator name
    'version'       => '1.0.0',                  // Optional: Palette version
    
    // Quick reference color scheme (for display purposes)
    'colors' => array(
        'primary'    => '#3b82f6',               // Primary color
        'secondary'  => '#1e40af',               // Secondary color
        'accent'     => '#06b6d4',               // Accent color
        'background' => '#f8fafc',               // Background color
        'text'       => '#1e293b',               // Text color
    ),
    
    // Complete settings configuration (ALL 10 sections required)
    'settings' => array(
        'color_overrides'    => array( /* 7 colors */ ),
        'admin_bar'          => array( /* 25+ options */ ),
        'admin_menu'         => array( /* 15+ options */ ),
        'dashboard_widgets'  => array( /* 10+ options */ ),
        'form_controls'      => array( /* 10+ options */ ),
        'buttons'            => array( /* 10+ options */ ),
        'backgrounds'        => array( /* 6+ options */ ),
        'typography'         => array( /* 10+ options */ ),
        'effects'            => array( /* 8+ options */ ),
        'login_page'         => array( /* 10+ options */ ),
    ),
)
```

### Required Settings Sections

#### 1. Color Overrides (7 colors)

```php
'color_overrides' => array(
    'primary_color'   => '#3b82f6',    // Primary brand color
    'secondary_color' => '#1e40af',    // Secondary brand color
    'accent_color'    => '#06b6d4',    // Accent/highlight color
    'success_color'   => '#10b981',    // Success state color
    'warning_color'   => '#f59e0b',    // Warning state color
    'error_color'     => '#ef4444',    // Error state color
    'info_color'      => '#06b6d4',    // Info state color
),
```

#### 2. Admin Bar (25+ options)

```php
'admin_bar' => array(
    'enabled'                => true,
    'background_type'        => 'gradient',        // solid, gradient, glassmorphism
    'background_color'       => '#3b82f6',
    'gradient_start'         => '#3b82f6',
    'gradient_end'           => '#1e40af',
    'base_color'             => '#1e40af',
    'opacity'                => 0.95,              // 0-1 float
    'blur_strength'          => '12',              // Blur in pixels (string)
    'text_color'             => '#ffffff',
    'hover_style'            => 'highlight',       // normal, highlight, glow
    'hover_bg_color'         => '#ffffff',
    'hover_text_color'       => '#1e40af',
    'height'                 => '48',              // Height in pixels (string)
    'width'                  => '100',             // Width value (string)
    'width_unit'             => '%',               // %, px
    'border_radius_mode'     => 'all',             // all, individual
    'border_radius_all'      => '0',               // Border radius (string)
    'font_size'              => '14',              // Font size in pixels (string)
    'font_weight'            => '600',             // Font weight (string)
    'spacing_mode'           => 'all',             // all, individual
    'spacing_all'            => '16',              // Spacing in pixels (string)
    'margin_mode'            => 'all',             // all, individual
    'margin_all'             => '0',               // Margin in pixels (string)
    'glassmorphism'          => true,              // Enable glassmorphism
    'shadow_style'           => 'md',              // none, sm, md, lg, xl
    'position'               => 'fixed',           // fixed, absolute, sticky
    'top_offset'             => '0',               // Top offset in pixels (string)
    'submenu_bg_color'       => '#ffffff',
    'submenu_text_color'     => '#1e293b',
    'submenu_border_radius'  => '8',               // Border radius (string)
    'submenu_font_size'      => '13',              // Font size (string)
    'custom_css'             => '',                // Custom CSS
),
```


#### 3. Admin Menu (15+ options)

```php
'admin_menu' => array(
    'enabled'                => true,
    'background_type'        => 'solid',           // solid, gradient, glassmorphism
    'background_color'       => '#1e40af',
    'gradient_start'         => '#3b82f6',
    'gradient_end'           => '#1e40af',
    'base_color'             => '#1e40af',
    'opacity'                => 0.95,
    'blur_strength'          => '12',
    'text_color'             => '#ffffff',
    'icon_color'             => '#ffffff',
    'hover_bg_color'         => 'rgba(255, 255, 255, 0.1)',
    'hover_text_color'       => '#ffffff',
    'active_bg_color'        => '#3b82f6',
    'active_text_color'      => '#ffffff',
    'border_radius'          => '8',
    'item_spacing'           => '4',
    'font_size'              => '14',
    'font_weight'            => '500',
    'submenu_bg_color'       => '#0f172a',
    'submenu_text_color'     => '#ffffff',
    'submenu_indent'         => '16',
),
```

#### 4. Dashboard Widgets (10 options)

```php
'dashboard_widgets' => array(
    'background_color' => '#ffffff',
    'border_color'     => '#e5e7eb',
    'border_radius'    => '12',
    'box_shadow'       => '0 1px 3px rgba(0, 0, 0, 0.1)',
    'title_color'      => '#1f2937',
    'title_size'       => '18',
    'title_weight'     => '600',
    'text_color'       => '#6b7280',
    'padding'          => '20',
    'margin'           => '16',
),
```

#### 5. Form Controls (10 options)

```php
'form_controls' => array(
    'input_bg'               => '#ffffff',
    'input_border'           => '#d1d5db',
    'input_border_radius'    => '6',
    'input_text_color'       => '#1f2937',
    'input_placeholder_color' => '#9ca3af',
    'input_focus_border'     => '#3b82f6',
    'input_focus_shadow'     => '0 0 0 3px rgba(59, 130, 246, 0.1)',
    'label_color'            => '#374151',
    'label_size'             => '14',
    'label_weight'           => '500',
),
```

#### 6. Buttons (10 options)

```php
'buttons' => array(
    'primary_bg'           => '#3b82f6',
    'primary_text'         => '#ffffff',
    'primary_hover_bg'     => '#2563eb',
    'primary_border_radius' => '6',
    'primary_shadow'       => '0 1px 2px rgba(0, 0, 0, 0.05)',
    'secondary_bg'         => '#6b7280',
    'secondary_text'       => '#ffffff',
    'secondary_hover_bg'   => '#4b5563',
    'danger_bg'            => '#ef4444',
    'danger_text'          => '#ffffff',
    'danger_hover_bg'      => '#dc2626',
),
```

#### 7. Backgrounds (6 options)

```php
'backgrounds' => array(
    'body_bg'           => '#f8fafc',
    'body_pattern'      => 'none',                 // none, dots, grid, diagonal
    'body_pattern_color' => 'rgba(0, 0, 0, 0.02)',
    'content_bg'        => '#ffffff',
    'sidebar_bg'        => '#f1f5f9',
    'header_bg'         => '#ffffff',
),
```

#### 8. Typography (10 options)

```php
'typography' => array(
    'body_font'        => 'Inter',
    'body_size'        => '14',
    'body_line_height' => 1.5,                     // Unitless float
    'body_color'       => '#1e293b',
    'heading_font'     => 'Inter',
    'heading_weight'   => '700',
    'heading_color'    => '#0f172a',
    'h1_size'          => '32',
    'h2_size'          => '24',
    'h3_size'          => '20',
),
```

#### 9. Effects (8 options)

```php
'effects' => array(
    'glassmorphism_enabled' => true,
    'glassmorphism_blur'    => '12',
    'glassmorphism_opacity' => 0.95,               // 0-1 float
    'animations_enabled'    => true,
    'animation_speed'       => '0.3s',
    'hover_scale'           => 1.02,               // Float
    'hover_lift'            => '2',
    'shadow_color'          => 'rgba(0, 0, 0, 0.1)',
),
```

#### 10. Login Page (10 options)

```php
'login_page' => array(
    'background_type'   => 'gradient',             // solid, gradient
    'background_color'  => '#1e40af',
    'gradient_start'    => '#3b82f6',
    'gradient_end'      => '#1e40af',
    'form_bg'           => '#ffffff',
    'form_border_radius' => '12',
    'form_shadow'       => '0 10px 25px rgba(0, 0, 0, 0.1)',
    'logo_url'          => '',
    'button_bg'         => '#3b82f6',
    'button_text'       => '#ffffff',
    'link_color'        => '#3b82f6',
),
```


---

## Template Data Structure

### Complete Template Schema

A template is a PHP array with the following structure:

```php
array(
    // Metadata
    'id'            => 'unique_template_id',     // Required: Unique identifier (snake_case)
    'name'          => 'Display Name',           // Required: Human-readable name
    'description'   => 'Brief description',      // Required: Short description
    'category'      => 'minimal',                // Required: minimal, modern, corporate, creative, dark
    'preview_image' => 'template-id.png',        // Required: Preview image filename
    'author'        => 'WOOW! Admin',            // Optional: Creator name
    'version'       => '1.0.0',                  // Optional: Template version
    'tags'          => array(                    // Optional: Descriptive tags
        'minimal',
        'flat',
        'clean',
        'professional'
    ),
    
    // Design characteristics (for filtering/display)
    'characteristics' => array(
        'glassmorphism' => false,                // true/false
        'gradients'     => false,                // true/false
        'animations'    => 'subtle',             // none, subtle, smooth, playful
        'shadows'       => 'minimal',            // none, minimal, subtle, premium, glow, colorful
        'border_radius' => 'sharp',              // sharp, slight, rounded, very-rounded
    ),
    
    // Complete settings configuration (ALL 10 sections required)
    'settings' => array(
        'color_overrides'    => array( /* 7 colors */ ),
        'admin_bar'          => array( /* 25+ options */ ),
        'admin_menu'         => array( /* 15+ options */ ),
        'dashboard_widgets'  => array( /* 10+ options */ ),
        'form_controls'      => array( /* 10+ options */ ),
        'buttons'            => array( /* 10+ options */ ),
        'backgrounds'        => array( /* 6+ options */ ),
        'typography'         => array( /* 10+ options */ ),
        'effects'            => array( /* 8+ options */ ),
        'login_page'         => array( /* 10+ options */ ),
    ),
)
```

### Differences from Palettes

Templates differ from palettes in several ways:

1. **Completeness**: Templates configure EVERY option, not just colors
2. **Tags**: Templates include descriptive tags for filtering
3. **Characteristics**: Templates define design characteristics for categorization
4. **Typography**: Templates specify exact fonts, sizes, and weights
5. **Effects**: Templates control animations, glassmorphism, and hover effects
6. **Consistency**: All elements follow a unified design language

### Template Categories

- **minimal**: Clean, simple, focused on content
- **modern**: Contemporary design with modern effects
- **corporate**: Professional, business-appropriate
- **creative**: Bold, artistic, vibrant
- **dark**: Dark mode optimized


---

## Adding New Palettes

### Step-by-Step Guide

#### Step 1: Create Palette Definition

Open `includes/data/palettes.php` and add your new palette to the returned array:

```php
return array(
    // ... existing palettes ...
    
    /**
     * Your New Palette
     * 
     * Brief description of the palette's theme and purpose.
     */
    'your_palette_id' => array(
        'id'            => 'your_palette_id',
        'name'          => 'Your Palette Name',
        'description'   => 'Brief description of your palette',
        'category'      => 'professional',  // Choose appropriate category
        'preview_image' => 'your-palette-id.png',
        'author'        => 'Your Name',
        'version'       => '1.0.0',
        
        // Define your color scheme
        'colors' => array(
            'primary'    => '#your-primary-color',
            'secondary'  => '#your-secondary-color',
            'accent'     => '#your-accent-color',
            'background' => '#your-background-color',
            'text'       => '#your-text-color',
        ),
        
        // Configure ALL 10 settings sections
        'settings' => array(
            'color_overrides'    => array( /* ... */ ),
            'admin_bar'          => array( /* ... */ ),
            'admin_menu'         => array( /* ... */ ),
            'dashboard_widgets'  => array( /* ... */ ),
            'form_controls'      => array( /* ... */ ),
            'buttons'            => array( /* ... */ ),
            'backgrounds'        => array( /* ... */ ),
            'typography'         => array( /* ... */ ),
            'effects'            => array( /* ... */ ),
            'login_page'         => array( /* ... */ ),
        ),
    ),
);
```

#### Step 2: Configure All Settings Sections

**IMPORTANT**: You MUST configure ALL options in ALL 10 sections. Use existing palettes as reference.

**Tips for creating cohesive palettes:**

1. **Start with colors**: Define your primary, secondary, and accent colors
2. **Choose a mood**: Decide on the emotional tone (professional, creative, calm, energetic)
3. **Be consistent**: Use your color scheme throughout all sections
4. **Test contrast**: Ensure text is readable on backgrounds (WCAG AA: 4.5:1 minimum)
5. **Consider effects**: Decide if glassmorphism, gradients, or animations fit your theme

#### Step 3: Generate Preview Image

Create a preview image showing your palette applied:

1. Apply your palette in a development environment
2. Navigate to WordPress admin dashboard
3. Take a screenshot (1200x800px recommended)
4. Save as PNG: `assets/images/previews/palettes/your-palette-id.png`

**Preview image should show:**
- Admin bar (top)
- Admin menu (left side)
- Dashboard widget (center)
- Form inputs (visible)
- Buttons (visible)

#### Step 4: Test Your Palette

```bash
# Navigate to plugin directory
cd wp-content/plugins/woow-admin

# Run palette validation test
php test-palette-api.php
```

**Validation checklist:**
- [ ] All 10 sections present
- [ ] All required options configured
- [ ] Color values are valid hex or rgba
- [ ] Numeric values are within valid ranges
- [ ] Preview image exists and is correct size
- [ ] Palette applies without errors
- [ ] CSS generates correctly
- [ ] Visual quality is 8/10 or higher

#### Step 5: Document Your Palette

Add documentation to your palette definition:

```php
/**
 * Your Palette Name
 * 
 * Detailed description of the palette's theme, mood, and use cases.
 * 
 * Theme: [Professional/Creative/Minimal/Dark/Vibrant]
 * Mood: [Trust/Energy/Calm/Bold/etc.]
 * Best for: [Corporate sites/Creative agencies/etc.]
 * 
 * Color Scheme:
 * - Primary: #color (Description)
 * - Secondary: #color (Description)
 * - Accent: #color (Description)
 * 
 * Key Features:
 * - Feature 1
 * - Feature 2
 * - Feature 3
 */
```

### Palette Design Guidelines

#### Color Selection

**Professional Palettes:**
- Use blues, grays, or muted tones
- Maintain high contrast for readability
- Avoid overly bright or saturated colors
- Use subtle gradients if any

**Creative Palettes:**
- Use vibrant, saturated colors
- Experiment with complementary color schemes
- Use bold gradients
- Add playful effects

**Dark Palettes:**
- Use dark backgrounds (#0f172a, #1e293b)
- Use light text (#f1f5f9, #cbd5e1)
- Add neon accents for contrast
- Use glow effects on interactive elements

#### Typography

- **Professional**: Inter, Roboto, system fonts
- **Creative**: Poppins, Montserrat, custom fonts
- **Minimal**: System fonts, clean sans-serif

#### Effects

- **Glassmorphism**: Use for modern, premium feel
- **Gradients**: Use for energy and depth
- **Animations**: Use sparingly for professional, more for creative
- **Shadows**: Subtle for professional, bold for creative


---

## Adding New Templates

### Step-by-Step Guide

#### Step 1: Create Template Definition

Open `includes/data/templates-data.php` and add your new template:

```php
return array(
    // ... existing templates ...
    
    /**
     * Your New Template
     * 
     * Brief description of the template's design philosophy.
     */
    'your_template_id' => array(
        'id'            => 'your_template_id',
        'name'          => 'Your Template Name',
        'description'   => 'Brief description of your template',
        'category'      => 'minimal',  // Choose appropriate category
        'preview_image' => 'your-template-id.png',
        'author'        => 'Your Name',
        'version'       => '1.0.0',
        'tags'          => array('tag1', 'tag2', 'tag3'),
        
        // Define design characteristics
        'characteristics' => array(
            'glassmorphism' => false,
            'gradients'     => false,
            'animations'    => 'subtle',
            'shadows'       => 'minimal',
            'border_radius' => 'sharp',
        ),
        
        // Configure ALL 10 settings sections
        'settings' => array(
            'color_overrides'    => array( /* ... */ ),
            'admin_bar'          => array( /* ... */ ),
            'admin_menu'         => array( /* ... */ ),
            'dashboard_widgets'  => array( /* ... */ ),
            'form_controls'      => array( /* ... */ ),
            'buttons'            => array( /* ... */ ),
            'backgrounds'        => array( /* ... */ ),
            'typography'         => array( /* ... */ ),
            'effects'            => array( /* ... */ ),
            'login_page'         => array( /* ... */ ),
        ),
    ),
);
```

#### Step 2: Define Design Characteristics

Choose characteristics that match your template's design:

**Glassmorphism:**
- `true`: Heavy use of frosted glass effects
- `false`: No glassmorphism

**Gradients:**
- `true`: Uses gradients throughout
- `false`: Flat colors only

**Animations:**
- `'none'`: No animations
- `'subtle'`: Minimal, professional animations
- `'smooth'`: Smooth, polished animations
- `'playful'`: Bold, creative animations

**Shadows:**
- `'none'`: No shadows
- `'minimal'`: Very subtle shadows
- `'subtle'`: Professional shadows
- `'premium'`: Elevated, premium shadows
- `'glow'`: Neon glow effects
- `'colorful'`: Colored shadows

**Border Radius:**
- `'sharp'`: 0px (no rounding)
- `'slight'`: 4-6px
- `'rounded'`: 8-12px
- `'very-rounded'`: 16-24px

#### Step 3: Configure Complete Settings

**CRITICAL**: Templates must configure EVERY option in EVERY section.

**Template Design Principles:**

1. **Consistency**: All elements should follow the same design language
2. **Completeness**: Every option must be set (no defaults)
3. **Cohesion**: Colors, typography, spacing should work together
4. **Uniqueness**: Template should be visually distinct from others (< 20% similarity)
5. **Quality**: Aim for 8/10+ subjective quality rating

#### Step 4: Generate Preview Image

Create a preview image showing your template applied:

1. Apply your template in a development environment
2. Navigate to WordPress admin dashboard
3. Take a screenshot (1200x800px recommended)
4. Save as PNG: `assets/images/previews/templates/your-template-id.png`

#### Step 5: Test Your Template

```bash
# Run template validation test
php test-template-api.php
```

**Validation checklist:**
- [ ] All 10 sections present
- [ ] All required options configured (100+ options)
- [ ] Characteristics defined
- [ ] Tags provided
- [ ] Preview image exists
- [ ] Template applies without errors
- [ ] CSS generates correctly
- [ ] Visual quality is 8/10 or higher
- [ ] Distinct from other templates

### Template Design Patterns

#### Minimal Templates

```php
'characteristics' => array(
    'glassmorphism' => false,
    'gradients'     => false,
    'animations'    => 'none',
    'shadows'       => 'minimal',
    'border_radius' => 'sharp',
),
'settings' => array(
    'admin_bar' => array(
        'background_type' => 'solid',
        'border_radius_all' => '0',
        'glassmorphism' => false,
        'shadow_style' => 'sm',
        // ... minimal styling
    ),
    'effects' => array(
        'glassmorphism_enabled' => false,
        'animations_enabled' => false,
        'hover_scale' => 1.0,
        // ... no effects
    ),
),
```

#### Modern Templates

```php
'characteristics' => array(
    'glassmorphism' => true,
    'gradients'     => true,
    'animations'    => 'smooth',
    'shadows'       => 'premium',
    'border_radius' => 'rounded',
),
'settings' => array(
    'admin_bar' => array(
        'background_type' => 'gradient',
        'border_radius_all' => '12',
        'glassmorphism' => true,
        'shadow_style' => 'lg',
        // ... modern styling
    ),
    'effects' => array(
        'glassmorphism_enabled' => true,
        'glassmorphism_blur' => '16',
        'animations_enabled' => true,
        'hover_scale' => 1.03,
        // ... modern effects
    ),
),
```

#### Dark Templates

```php
'settings' => array(
    'color_overrides' => array(
        'primary_color' => '#8b5cf6',  // Bright accent
        'secondary_color' => '#6366f1',
        // ... neon accents
    ),
    'admin_bar' => array(
        'background_color' => '#0f172a',  // Very dark
        'text_color' => '#f1f5f9',        // Light text
        // ... dark styling
    ),
    'backgrounds' => array(
        'body_bg' => '#0f172a',
        'content_bg' => '#1e293b',
        // ... dark backgrounds
    ),
    'effects' => array(
        'shadow_color' => 'rgba(139, 92, 246, 0.3)',  // Colored glow
        // ... glow effects
    ),
),
```


---

## REST API Endpoints

The plugin provides REST API endpoints for programmatic access to palettes and templates.

### Base URL

```
/wp-json/woow/v1
```

### Authentication

All endpoints require:
- **Capability**: `manage_options` (WordPress administrator)
- **Nonce**: `X-WP-Nonce` header with WordPress REST API nonce

### Palette Endpoints

#### GET /palettes

Retrieve all available palettes.

**Request:**
```bash
curl -X GET \
  'https://example.com/wp-json/woow/v1/palettes' \
  -H 'X-WP-Nonce: YOUR_NONCE'
```

**Response:**
```json
{
  "success": true,
  "palettes": {
    "professional_blue": { /* palette data */ },
    "warm_sunset": { /* palette data */ }
  },
  "count": 10
}
```

#### GET /palettes/{id}

Retrieve a specific palette.

**Request:**
```bash
curl -X GET \
  'https://example.com/wp-json/woow/v1/palettes/professional_blue' \
  -H 'X-WP-Nonce: YOUR_NONCE'
```

**Response:**
```json
{
  "success": true,
  "palette": {
    "id": "professional_blue",
    "name": "Professional Blue",
    "settings": { /* complete settings */ }
  },
  "completeness": {
    "complete": true,
    "missing": [],
    "sections": 10
  }
}
```

#### POST /palettes/{id}/apply

Apply a palette to current settings.

**Request:**
```bash
curl -X POST \
  'https://example.com/wp-json/woow/v1/palettes/professional_blue/apply' \
  -H 'Content-Type: application/json' \
  -H 'X-WP-Nonce: YOUR_NONCE'
```

**Response:**
```json
{
  "success": true,
  "message": "Palette applied successfully",
  "palette_id": "professional_blue",
  "settings": { /* updated settings */ }
}
```

### Template Endpoints

#### GET /templates

Retrieve all available templates.

**Request:**
```bash
curl -X GET \
  'https://example.com/wp-json/woow/v1/templates' \
  -H 'X-WP-Nonce: YOUR_NONCE'
```

**Response:**
```json
{
  "success": true,
  "templates": [
    {
      "id": "modern_minimal",
      "name": "Modern Minimal",
      "characteristics": { /* design characteristics */ }
    }
  ],
  "count": 11
}
```

#### GET /templates/{id}

Retrieve a specific template.

**Request:**
```bash
curl -X GET \
  'https://example.com/wp-json/woow/v1/templates/modern_minimal' \
  -H 'X-WP-Nonce: YOUR_NONCE'
```

**Response:**
```json
{
  "success": true,
  "template": {
    "id": "modern_minimal",
    "name": "Modern Minimal",
    "settings": { /* complete settings */ }
  }
}
```

#### POST /templates/{id}/apply

Apply a template to current settings.

**Request:**
```bash
curl -X POST \
  'https://example.com/wp-json/woow/v1/templates/modern_minimal/apply' \
  -H 'Content-Type: application/json' \
  -H 'X-WP-Nonce: YOUR_NONCE'
```

**Response:**
```json
{
  "success": true,
  "message": "Template applied successfully",
  "template_id": "modern_minimal",
  "settings": { /* updated settings */ }
}
```

### Error Responses

**403 Forbidden:**
```json
{
  "success": false,
  "message": "Invalid or missing nonce"
}
```

**404 Not Found:**
```json
{
  "success": false,
  "message": "Palette not found"
}
```

**500 Internal Server Error:**
```json
{
  "success": false,
  "message": "Error applying palette: {error details}"
}
```

### JavaScript Example

```javascript
// Get all palettes
async function getPalettes() {
  const response = await fetch('/wp-json/woow/v1/palettes', {
    headers: {
      'X-WP-Nonce': wpApiSettings.nonce
    }
  });
  return await response.json();
}

// Apply a palette
async function applyPalette(paletteId) {
  const response = await fetch(`/wp-json/woow/v1/palettes/${paletteId}/apply`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-WP-Nonce': wpApiSettings.nonce
    }
  });
  return await response.json();
}
```

### PHP Example

```php
// Get all templates
$request = new WP_REST_Request( 'GET', '/woow/v1/templates' );
$request->set_header( 'X-WP-Nonce', wp_create_nonce( 'wp_rest' ) );
$response = rest_do_request( $request );
$data = $response->get_data();

// Apply a template
$request = new WP_REST_Request( 'POST', '/woow/v1/templates/modern_minimal/apply' );
$request->set_header( 'X-WP-Nonce', wp_create_nonce( 'wp_rest' ) );
$response = rest_do_request( $request );
$data = $response->get_data();
```


---

## Manager Classes

### WOOW_Palette_Manager

Located: `includes/palettes/class-woow-palette-manager.php`

#### Public Methods

```php
class WOOW_Palette_Manager {
    /**
     * Constructor
     *
     * @param WOOW_Settings $settings Settings manager instance
     */
    public function __construct( WOOW_Settings $settings );
    
    /**
     * Load all palettes from data file
     *
     * @return void
     */
    public function load_palettes(): void;
    
    /**
     * Get all available palettes
     *
     * @return array Array of palette definitions
     */
    public function get_all_palettes(): array;
    
    /**
     * Get single palette by ID
     *
     * @param string $palette_id Palette identifier
     * @return array|null Palette data or null if not found
     */
    public function get_palette( string $palette_id ): ?array;
    
    /**
     * Get palettes by category
     *
     * @param string $category Category name
     * @return array Array of palettes in category
     */
    public function get_palettes_by_category( string $category ): array;
    
    /**
     * Apply palette to current settings
     *
     * @param string $palette_id Palette identifier
     * @return bool True on success, false on failure
     */
    public function apply_palette( string $palette_id ): bool;
    
    /**
     * Check if palette exists
     *
     * @param string $palette_id Palette identifier
     * @return bool True if exists, false otherwise
     */
    public function palette_exists( string $palette_id ): bool;
}
```

#### Usage Example

```php
// Initialize manager
$settings = new WOOW_Settings();
$palette_manager = new WOOW_Palette_Manager( $settings );

// Load palettes
$palette_manager->load_palettes();

// Get all palettes
$palettes = $palette_manager->get_all_palettes();

// Get specific palette
$palette = $palette_manager->get_palette( 'professional_blue' );

// Get palettes by category
$professional_palettes = $palette_manager->get_palettes_by_category( 'professional' );

// Apply palette
$success = $palette_manager->apply_palette( 'professional_blue' );
if ( $success ) {
    echo 'Palette applied successfully!';
}
```

### WOOW_Template_Manager

Located: `includes/class-woow-template-manager.php`

#### Public Methods

```php
class WOOW_Template_Manager {
    /**
     * Constructor
     *
     * @param WOOW_Settings $settings Settings manager instance
     */
    public function __construct( WOOW_Settings $settings );
    
    /**
     * Load all templates from data file
     *
     * @return void
     */
    public function load_templates(): void;
    
    /**
     * Get all available templates
     *
     * @return array Array of template definitions
     */
    public function get_all_templates(): array;
    
    /**
     * Get single template by ID
     *
     * @param string $template_id Template identifier
     * @return array|null Template data or null if not found
     */
    public function get_template( string $template_id ): ?array;
    
    /**
     * Get templates by category
     *
     * @param string $category Category name
     * @return array Array of templates in category
     */
    public function get_templates_by_category( string $category ): array;
    
    /**
     * Apply template to current settings
     *
     * @param string $template_id Template identifier
     * @return bool True on success, false on failure
     */
    public function apply_template( string $template_id ): bool;
    
    /**
     * Check if template exists
     *
     * @param string $template_id Template identifier
     * @return bool True if exists, false otherwise
     */
    public function template_exists( string $template_id ): bool;
}
```

#### Usage Example

```php
// Initialize manager
$settings = new WOOW_Settings();
$template_manager = new WOOW_Template_Manager( $settings );

// Load templates
$template_manager->load_templates();

// Get all templates
$templates = $template_manager->get_all_templates();

// Get specific template
$template = $template_manager->get_template( 'modern_minimal' );

// Get templates by category
$minimal_templates = $template_manager->get_templates_by_category( 'minimal' );

// Apply template
$success = $template_manager->apply_template( 'modern_minimal' );
if ( $success ) {
    echo 'Template applied successfully!';
}
```

### WOOW_Settings

Located: `includes/class-woow-settings.php`

#### Key Methods for Palette/Template Integration

```php
class WOOW_Settings {
    /**
     * Get all settings
     *
     * @return array All plugin settings
     */
    public function get_all_settings(): array;
    
    /**
     * Get specific section settings
     *
     * @param string $section Section name
     * @return array Section settings
     */
    public function get_section( string $section ): array;
    
    /**
     * Update settings
     *
     * @param array $new_settings New settings to merge
     * @return bool True on success, false on failure
     */
    public function update_settings( array $new_settings ): bool;
    
    /**
     * Validate field value
     *
     * @param string $section Section name
     * @param string $key Field key
     * @param mixed $value Field value
     * @return bool True if valid, false otherwise
     */
    public function validate_field( string $section, string $key, $value ): bool;
}
```

### WOOW_Backup_Manager

Located: `includes/class-woow-backup-manager.php`

#### Key Methods

```php
class WOOW_Backup_Manager {
    /**
     * Create backup of current settings
     *
     * @param string $label Backup label
     * @return bool True on success, false on failure
     */
    public function create_backup( string $label = '' ): bool;
    
    /**
     * Restore from backup
     *
     * @param string $backup_id Backup identifier
     * @return bool True on success, false on failure
     */
    public function restore_backup( string $backup_id ): bool;
    
    /**
     * Get all backups
     *
     * @return array Array of backup metadata
     */
    public function get_all_backups(): array;
}
```


---

## Validation & Testing

### Validation Requirements

#### Palette Validation

A valid palette must:

1. **Have all required metadata:**
   - `id` (unique, snake_case)
   - `name` (human-readable)
   - `description` (brief description)
   - `category` (professional, creative, minimal, dark, vibrant)
   - `preview_image` (filename)

2. **Have all 10 settings sections:**
   - color_overrides
   - admin_bar
   - admin_menu
   - dashboard_widgets
   - form_controls
   - buttons
   - backgrounds
   - typography
   - effects
   - login_page

3. **Have all required options in each section** (100+ total options)

4. **Use valid values:**
   - Colors: Valid hex (#rrggbb) or rgba()
   - Numbers: Within valid ranges
   - Strings: Non-empty where required
   - Booleans: true/false

#### Template Validation

A valid template must meet all palette requirements PLUS:

1. **Have characteristics defined:**
   - glassmorphism (boolean)
   - gradients (boolean)
   - animations (string)
   - shadows (string)
   - border_radius (string)

2. **Have tags array** (optional but recommended)

3. **Configure EVERY option** (not just colors)

### Testing Tools

#### Test Scripts

```bash
# Test palette API
php test-palette-api.php

# Test template API
php test-template-api.php

# Test palette integration
php test-palette-integration.php

# Test template integration
php test-template-integration.php
```

#### Unit Tests

```bash
# Run PHPUnit tests
./vendor/bin/phpunit tests/php/PaletteManagerTest.php
./vendor/bin/phpunit tests/php/TemplateManagerTest.php
```

#### Visual Testing

```bash
# Generate visual quality report
php test-palette-visual-quality.php
php test-template-visual-quality.php
```

### Validation Checklist

When adding a new palette or template, verify:

- [ ] **Metadata Complete**: All required fields present
- [ ] **ID Unique**: No conflicts with existing IDs
- [ ] **All Sections Present**: All 10 sections configured
- [ ] **All Options Set**: 100+ options configured
- [ ] **Valid Values**: All values pass validation
- [ ] **Preview Image**: Image exists and is correct size (1200x800px)
- [ ] **Color Contrast**: WCAG AA compliance (4.5:1 minimum)
- [ ] **Visual Quality**: Subjective rating 8/10 or higher
- [ ] **Uniqueness**: < 20% similarity to other palettes/templates
- [ ] **Applies Successfully**: No errors when applying
- [ ] **CSS Generates**: CSS generates without errors
- [ ] **Backup Created**: Automatic backup before application

### Common Validation Errors

#### Missing Section

```
Error: Palette 'your_palette' is missing section 'admin_menu'
```

**Fix**: Add the missing section with all required options.

#### Invalid Color

```
Error: Invalid color value 'blue' for 'primary_color'
```

**Fix**: Use valid hex (#3b82f6) or rgba (rgba(59, 130, 246, 1)) format.

#### Missing Option

```
Error: Required option 'height' missing in section 'admin_bar'
```

**Fix**: Add the missing option with a valid value.

#### Invalid Range

```
Error: Value '150' for 'opacity' must be between 0 and 1
```

**Fix**: Use correct range (0-1 for opacity, not 0-100).

### Performance Testing

```bash
# Run performance tests
php test-performance.php
```

**Performance targets:**
- Palette list loading: < 500ms
- Template list loading: < 500ms
- Palette application: < 2 seconds
- Template application: < 2 seconds
- CSS regeneration: < 1 second


---

## Best Practices

### Design Best Practices

#### Color Selection

1. **Use Color Theory**
   - Complementary colors for contrast
   - Analogous colors for harmony
   - Triadic colors for balance

2. **Ensure Accessibility**
   - WCAG AA: 4.5:1 contrast ratio for normal text
   - WCAG AA: 3:1 contrast ratio for large text
   - Test with color blindness simulators

3. **Consider Context**
   - Professional: Blues, grays, muted tones
   - Creative: Vibrant, saturated colors
   - Dark mode: Light text on dark backgrounds

#### Typography

1. **Font Selection**
   - Professional: Inter, Roboto, system fonts
   - Creative: Poppins, Montserrat, custom fonts
   - Minimal: System fonts for performance

2. **Font Sizes**
   - Body text: 14-16px
   - Headings: 18-32px
   - Labels: 12-14px

3. **Line Height**
   - Body text: 1.5-1.6
   - Headings: 1.2-1.4
   - Tight spacing: 1.3

#### Effects

1. **Glassmorphism**
   - Use blur: 12-16px
   - Use opacity: 0.85-0.95
   - Add subtle borders
   - Use on light backgrounds

2. **Animations**
   - Keep duration short: 0.2-0.4s
   - Use ease-in-out timing
   - Avoid excessive movement
   - Respect prefers-reduced-motion

3. **Shadows**
   - Subtle: 0 1px 3px rgba(0,0,0,0.1)
   - Medium: 0 4px 12px rgba(0,0,0,0.15)
   - Strong: 0 8px 24px rgba(0,0,0,0.2)

### Code Best Practices

#### Data Structure

1. **Use Consistent Formatting**
   ```php
   // Good: Consistent indentation and spacing
   'admin_bar' => array(
       'enabled'         => true,
       'background_type' => 'gradient',
       'text_color'      => '#ffffff',
   ),
   
   // Bad: Inconsistent formatting
   'admin_bar' => array('enabled'=>true,'background_type'=>'gradient',
   'text_color'=>'#ffffff'),
   ```

2. **Add Comments**
   ```php
   /**
    * Professional Blue Palette
    * 
    * Clean corporate design with blue tones that conveys trust.
    * Perfect for business and corporate WordPress admin panels.
    */
   'professional_blue' => array(
       // ... palette data
   ),
   ```

3. **Group Related Options**
   ```php
   // Good: Grouped by functionality
   'admin_bar' => array(
       // Background
       'background_type'  => 'gradient',
       'background_color' => '#3b82f6',
       'gradient_start'   => '#3b82f6',
       'gradient_end'     => '#1e40af',
       
       // Text
       'text_color'       => '#ffffff',
       'font_size'        => '14',
       'font_weight'      => '600',
   ),
   ```

#### Validation

1. **Validate Early**
   ```php
   // Validate palette before applying
   if ( ! $this->palette_exists( $palette_id ) ) {
       return false;
   }
   
   $palette = $this->get_palette( $palette_id );
   if ( ! $this->validate_palette( $palette ) ) {
       return false;
   }
   ```

2. **Provide Clear Error Messages**
   ```php
   // Good: Specific error message
   throw new Exception( "Palette '{$palette_id}' is missing required section 'admin_bar'" );
   
   // Bad: Generic error message
   throw new Exception( "Invalid palette" );
   ```

3. **Use Type Hints**
   ```php
   // Good: Type hints for clarity
   public function apply_palette( string $palette_id ): bool {
       // ...
   }
   
   // Bad: No type hints
   public function apply_palette( $palette_id ) {
       // ...
   }
   ```

#### Performance

1. **Lazy Load Data**
   ```php
   // Good: Load only when needed
   private ?array $palettes = null;
   
   public function get_all_palettes(): array {
       if ( $this->palettes === null ) {
           $this->load_palettes();
       }
       return $this->palettes;
   }
   ```

2. **Cache Results**
   ```php
   // Cache palette data
   $cache_key = 'woow_palettes_v1';
   $palettes = wp_cache_get( $cache_key );
   
   if ( $palettes === false ) {
       $palettes = require WOOW_PLUGIN_DIR . 'includes/data/palettes.php';
       wp_cache_set( $cache_key, $palettes, '', 3600 );
   }
   ```

3. **Batch Operations**
   ```php
   // Good: Single database update
   $updated = array_replace_recursive( $current, $palette['settings'] );
   update_option( 'woow_admin_settings', $updated );
   
   // Bad: Multiple database updates
   foreach ( $palette['settings'] as $section => $options ) {
       update_option( "woow_{$section}", $options );
   }
   ```

### Security Best Practices

1. **Sanitize Input**
   ```php
   $palette_id = sanitize_key( $_POST['palette_id'] );
   ```

2. **Verify Nonces**
   ```php
   if ( ! wp_verify_nonce( $_POST['nonce'], 'woow_apply_palette' ) ) {
       return false;
   }
   ```

3. **Check Capabilities**
   ```php
   if ( ! current_user_can( 'manage_options' ) ) {
       return false;
   }
   ```

4. **Escape Output**
   ```php
   echo esc_html( $palette['name'] );
   echo esc_attr( $palette['id'] );
   echo esc_url( $palette['preview_url'] );
   ```

### Documentation Best Practices

1. **Document Purpose**
   ```php
   /**
    * Professional Blue Palette
    * 
    * Theme: Corporate, professional
    * Mood: Trust, professionalism, clarity
    * Best for: Business sites, corporate admin panels
    * 
    * Key Features:
    * - Blue gradient admin bar with glassmorphism
    * - Clean, professional typography
    * - Subtle effects for polished look
    */
   ```

2. **Document Changes**
   ```php
   /**
    * @version 1.1.0
    * @changelog
    * - 1.1.0: Added submenu styling options
    * - 1.0.0: Initial release
    */
   ```

3. **Provide Examples**
   ```php
   /**
    * Apply a palette
    *
    * @example
    * $manager = new WOOW_Palette_Manager( $settings );
    * $success = $manager->apply_palette( 'professional_blue' );
    */
   ```


---

## Appendix

### Available Palettes

The plugin includes 10 complete color palettes:

| ID | Name | Category | Description |
|----|------|----------|-------------|
| `professional_blue` | Professional Blue | professional | Clean corporate design with blue tones |
| `warm_sunset` | Warm Sunset | creative | Energetic design with warm amber and orange |
| `dark_mode_pro` | Dark Mode Pro | dark | Modern dark mode with purple and cyan accents |
| `nature_green` | Nature Green | creative | Fresh organic design with emerald and green |
| `minimalist_gray` | Minimalist Gray | minimal | Clean minimalist design with neutral gray |
| `vibrant_purple` | Vibrant Purple | vibrant | Bold creative design with purple and pink |
| `ocean_blue` | Ocean Blue | professional | Calm professional design with ocean blues |
| `cherry_red` | Cherry Red | vibrant | Energetic bold design with red tones |
| `monochrome_elite` | Monochrome Elite | professional | Luxury design with black and gold |
| `cyberpunk_neon` | Cyberpunk Neon | dark | Futuristic design with neon colors |

### Available Templates

The plugin includes 11 complete design templates:

| ID | Name | Category | Description |
|----|------|----------|-------------|
| `modern_minimal` | Modern Minimal | minimal | Clean, minimalist design focused on content |
| `glassmorphism_pro` | Glassmorphism Pro | modern | Premium glassmorphism design with frosted glass |
| `dark_dashboard` | Dark Dashboard | dark | Complete dark mode with neon accents |
| `colorful_creative` | Colorful Creative | creative | Vibrant and playful design with multiple colors |
| `corporate_blue` | Corporate Blue | corporate | Professional corporate design with blue tones |
| `material_design` | Material Design | modern | Google Material Design inspired |
| `flat_2` | Flat 2.0 | minimal | Modern flat design |
| `neumorphism` | Neumorphism | modern | Soft UI neumorphic design |
| `retro_wave` | Retro Wave | creative | 80s synthwave aesthetic |
| `nature_inspired` | Nature Inspired | creative | Organic design with natural elements |
| `high_contrast` | High Contrast | minimal | Strong contrast for accessibility |

### File Locations Reference

| File | Purpose |
|------|---------|
| `includes/data/palettes.php` | Palette definitions |
| `includes/data/templates-data.php` | Template definitions |
| `includes/palettes/class-woow-palette-manager.php` | Palette manager class |
| `includes/class-woow-template-manager.php` | Template manager class |
| `includes/class-woow-settings.php` | Settings management |
| `includes/class-woow-rest-api.php` | REST API endpoints |
| `includes/class-woow-backup-manager.php` | Backup management |
| `includes/class-woow-css-generator.php` | CSS generation |
| `includes/defaults.php` | Default settings |
| `assets/images/previews/palettes/` | Palette preview images |
| `assets/images/previews/templates/` | Template preview images |
| `assets/src/js/components/PaletteSelector.js` | Palette selector UI |
| `assets/src/js/components/TemplateSelector.js` | Template selector UI |

### Useful Commands

```bash
# Build assets
npm run build

# Clear cache
./cc.sh

# Run tests
./vendor/bin/phpunit

# Test palette API
php test-palette-api.php

# Test template API
php test-template-api.php

# Generate performance report
php generate-performance-report.php

# Test visual quality
php test-palette-visual-quality.php
php test-template-visual-quality.php
```

### Resources

- **Plugin Documentation**: `docs/README.md`
- **User Guide**: `docs/USER-GUIDE.md`
- **Quick Start**: `docs/QUICK-START.md`
- **Visual Guide**: `docs/VISUAL-GUIDE.md`
- **FAQ**: `docs/FAQ.md`
- **Palette API Documentation**: `PALETTE-API-DOCUMENTATION.md`
- **Template API Documentation**: `TEMPLATE-API-DOCUMENTATION.md`
- **Testing Guide**: `TESTING-GUIDE.md`
- **Error Handling Guide**: `ERROR-HANDLING-GUIDE.md`

### Support

For issues, questions, or feature requests:
- **GitHub**: https://github.com/m3n3sx/woow-admin
- **Documentation**: See plugin README.md
- **Test Scripts**: Use included test files for debugging

### Contributing

When contributing new palettes or templates:

1. Follow the data structure exactly
2. Configure ALL options (100+ settings)
3. Generate preview images (1200x800px)
4. Test thoroughly before submitting
5. Document your design decisions
6. Ensure WCAG AA compliance
7. Aim for 8/10+ visual quality
8. Make it unique (< 20% similarity to existing)

### License

GPL v2 or later

---

**Last Updated**: November 2024  
**Version**: 2.0.0-beta  
**Author**: WOOW! Admin Team

