# WOOW! Admin - Palette REST API Documentation

## Overview

The WOOW! Admin plugin provides REST API endpoints for managing color palettes programmatically. These endpoints allow external applications, scripts, or integrations to retrieve palette information and apply palettes to the WordPress admin interface.

**Base URL:** `/wp-json/woow/v1`

**Authentication:** All endpoints require the user to have the `manage_options` capability (typically administrators).

**Nonce Verification:** POST endpoints require a valid WordPress REST API nonce in the `X-WP-Nonce` header.

---

## Endpoints

### 1. Get All Palettes

Retrieves a list of all available color palettes with their metadata.

**Endpoint:** `GET /wp-json/woow/v1/palettes`

**Authentication:** Required (`manage_options` capability)

**Parameters:** None

**Response:**

```json
{
  "success": true,
  "palettes": {
    "professional_blue": {
      "id": "professional_blue",
      "name": "Professional Blue",
      "description": "Clean corporate design with blue tones",
      "category": "professional",
      "preview_image": "professional-blue.png",
      "preview_url": "https://example.com/wp-content/plugins/woow-admin/assets/images/previews/palettes/professional-blue.png",
      "colors": {
        "primary": "#3b82f6",
        "secondary": "#1e40af",
        "accent": "#06b6d4",
        "background": "#f8fafc",
        "text": "#1e293b"
      },
      "settings": {
        "color_overrides": { /* ... */ },
        "admin_bar": { /* ... */ },
        "admin_menu": { /* ... */ },
        "dashboard_widgets": { /* ... */ },
        "form_controls": { /* ... */ },
        "buttons": { /* ... */ },
        "backgrounds": { /* ... */ },
        "typography": { /* ... */ },
        "effects": { /* ... */ },
        "login_page": { /* ... */ }
      }
    },
    "warm_sunset": { /* ... */ },
    "dark_mode_pro": { /* ... */ }
    // ... 7 more palettes
  },
  "count": 10
}
```

**Status Codes:**
- `200 OK` - Success
- `403 Forbidden` - Invalid nonce or insufficient permissions
- `500 Internal Server Error` - Failed to load palettes

**Example Request (JavaScript):**

```javascript
fetch('/wp-json/woow/v1/palettes', {
  method: 'GET',
  headers: {
    'Content-Type': 'application/json',
    'X-WP-Nonce': wpApiSettings.nonce
  }
})
.then(response => response.json())
.then(data => {
  console.log('Available palettes:', data.palettes);
  console.log('Total count:', data.count);
});
```

**Example Request (cURL):**

```bash
curl -X GET \
  'https://example.com/wp-json/woow/v1/palettes' \
  -H 'X-WP-Nonce: YOUR_NONCE_HERE' \
  -H 'Cookie: wordpress_logged_in_HASH=YOUR_COOKIE'
```

---

### 2. Get Single Palette

Retrieves detailed information about a specific palette, including completeness validation.

**Endpoint:** `GET /wp-json/woow/v1/palettes/{id}`

**Authentication:** Required (`manage_options` capability)

**Parameters:**
- `id` (string, required) - Palette identifier (e.g., `professional_blue`)

**Response:**

```json
{
  "success": true,
  "palette": {
    "id": "professional_blue",
    "name": "Professional Blue",
    "description": "Clean corporate design with blue tones",
    "category": "professional",
    "preview_image": "professional-blue.png",
    "preview_url": "https://example.com/wp-content/plugins/woow-admin/assets/images/previews/palettes/professional-blue.png",
    "colors": {
      "primary": "#3b82f6",
      "secondary": "#1e40af",
      "accent": "#06b6d4",
      "background": "#f8fafc",
      "text": "#1e293b"
    },
    "settings": {
      "color_overrides": { /* 7 color options */ },
      "admin_bar": { /* 25+ options */ },
      "admin_menu": { /* 15+ options */ },
      "dashboard_widgets": { /* 10+ options */ },
      "form_controls": { /* 10+ options */ },
      "buttons": { /* 10+ options */ },
      "backgrounds": { /* 6+ options */ },
      "typography": { /* 10+ options */ },
      "effects": { /* 8+ options */ },
      "login_page": { /* 10+ options */ }
    }
  },
  "completeness": {
    "complete": true,
    "missing": [],
    "sections": 10
  }
}
```

**Status Codes:**
- `200 OK` - Success
- `403 Forbidden` - Invalid nonce or insufficient permissions
- `404 Not Found` - Palette not found
- `500 Internal Server Error` - Failed to load palette

**Example Request (JavaScript):**

```javascript
fetch('/wp-json/woow/v1/palettes/professional_blue', {
  method: 'GET',
  headers: {
    'Content-Type': 'application/json',
    'X-WP-Nonce': wpApiSettings.nonce
  }
})
.then(response => response.json())
.then(data => {
  if (data.success) {
    console.log('Palette:', data.palette);
    console.log('Is complete:', data.completeness.complete);
  }
});
```

**Example Request (cURL):**

```bash
curl -X GET \
  'https://example.com/wp-json/woow/v1/palettes/professional_blue' \
  -H 'X-WP-Nonce: YOUR_NONCE_HERE' \
  -H 'Cookie: wordpress_logged_in_HASH=YOUR_COOKIE'
```

---

### 3. Apply Palette

Applies a color palette to the WordPress admin interface. This endpoint:
1. Validates the palette exists and is complete
2. Creates a backup of current settings
3. Merges palette settings with current settings
4. Regenerates CSS
5. Clears CSS cache

**Endpoint:** `POST /wp-json/woow/v1/palettes/{id}/apply`

**Authentication:** Required (`manage_options` capability)

**Nonce:** Required in `X-WP-Nonce` header

**Parameters:**
- `id` (string, required) - Palette identifier (e.g., `professional_blue`)

**Response (Success):**

```json
{
  "success": true,
  "message": "Palette applied successfully",
  "palette_id": "professional_blue",
  "settings": {
    "color_overrides": { /* updated settings */ },
    "admin_bar": { /* updated settings */ },
    "admin_menu": { /* updated settings */ },
    // ... all 10 sections with updated values
  }
}
```

**Response (Error):**

```json
{
  "success": false,
  "message": "Palette not found"
}
```

**Status Codes:**
- `200 OK` - Palette applied successfully
- `400 Bad Request` - Failed to apply palette (validation error)
- `403 Forbidden` - Invalid/missing nonce or insufficient permissions
- `404 Not Found` - Palette not found
- `500 Internal Server Error` - Exception during application

**Example Request (JavaScript):**

```javascript
fetch('/wp-json/woow/v1/palettes/professional_blue/apply', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-WP-Nonce': wpApiSettings.nonce
  }
})
.then(response => response.json())
.then(data => {
  if (data.success) {
    console.log('Palette applied:', data.palette_id);
    console.log('Updated settings:', data.settings);
    // Reload page to see changes
    window.location.reload();
  } else {
    console.error('Failed to apply palette:', data.message);
  }
});
```

**Example Request (cURL):**

```bash
curl -X POST \
  'https://example.com/wp-json/woow/v1/palettes/professional_blue/apply' \
  -H 'Content-Type: application/json' \
  -H 'X-WP-Nonce: YOUR_NONCE_HERE' \
  -H 'Cookie: wordpress_logged_in_HASH=YOUR_COOKIE'
```

**Example Request (PHP):**

```php
$request = new WP_REST_Request( 'POST', '/woow/v1/palettes/professional_blue/apply' );
$request->set_header( 'X-WP-Nonce', wp_create_nonce( 'wp_rest' ) );
$response = rest_do_request( $request );
$data = $response->get_data();

if ( $data['success'] ) {
    echo 'Palette applied successfully!';
} else {
    echo 'Error: ' . $data['message'];
}
```

---

## Security

### Authentication

All endpoints require the user to be authenticated and have the `manage_options` capability. This is typically restricted to administrators.

### Nonce Verification

POST endpoints (palette application) require a valid WordPress REST API nonce in the `X-WP-Nonce` header. This prevents CSRF attacks.

**Getting a nonce in JavaScript:**

```javascript
// WordPress automatically provides wpApiSettings.nonce
const nonce = wpApiSettings.nonce;

// Or create one manually
const nonce = wp.apiFetch.nonceMiddleware.nonce;
```

**Creating a nonce in PHP:**

```php
$nonce = wp_create_nonce( 'wp_rest' );
```

### Input Sanitization

All input parameters are sanitized:
- Palette IDs are sanitized using `sanitize_key()`
- All data is validated before processing
- SQL injection protection via WordPress database abstraction

### Error Handling

The API provides detailed error messages while avoiding exposure of sensitive information:
- Generic error messages for security-related failures
- Detailed validation errors for development/debugging
- All errors are logged to WordPress error log

---

## Available Palettes

The plugin includes 10 complete color palettes:

1. **professional_blue** - Clean corporate design with blue tones
2. **warm_sunset** - Energetic design with warm amber and orange colors
3. **dark_mode_pro** - Modern dark mode with purple and cyan accents
4. **nature_green** - Fresh organic design with green tones
5. **minimalist_gray** - Simple neutral design focused on content
6. **vibrant_purple** - Bold creative design with purple and pink
7. **ocean_blue** - Calm professional design with ocean-inspired blues
8. **cherry_red** - Energetic bold design with red tones
9. **monochrome_elite** - Luxury design with black and gold
10. **cyberpunk_neon** - Futuristic design with neon colors on dark background

Each palette configures 100+ options across 10 sections:
- Color Overrides (7 colors)
- Admin Bar (25+ options)
- Admin Menu (15+ options)
- Dashboard Widgets (10+ options)
- Form Controls (10+ options)
- Buttons (10+ options)
- Backgrounds (6+ options)
- Typography (10+ options)
- Effects (8+ options)
- Login Page (10+ options)

---

## Error Codes

| Status Code | Meaning | Common Causes |
|------------|---------|---------------|
| 200 | Success | Request completed successfully |
| 400 | Bad Request | Invalid palette ID, validation failed |
| 403 | Forbidden | Missing/invalid nonce, insufficient permissions |
| 404 | Not Found | Palette ID doesn't exist |
| 500 | Internal Server Error | Exception during processing, file system error |

---

## Rate Limiting

WordPress REST API has built-in rate limiting. For high-volume applications, consider:
- Caching palette list responses
- Implementing client-side throttling
- Using WordPress transients for frequently accessed data

---

## Best Practices

### 1. Cache Palette List

```javascript
// Cache palette list for 5 minutes
let cachedPalettes = null;
let cacheTime = 0;

async function getPalettes() {
  const now = Date.now();
  if (cachedPalettes && (now - cacheTime) < 300000) {
    return cachedPalettes;
  }
  
  const response = await fetch('/wp-json/woow/v1/palettes', {
    headers: { 'X-WP-Nonce': wpApiSettings.nonce }
  });
  
  cachedPalettes = await response.json();
  cacheTime = now;
  return cachedPalettes;
}
```

### 2. Handle Errors Gracefully

```javascript
async function applyPalette(paletteId) {
  try {
    const response = await fetch(`/wp-json/woow/v1/palettes/${paletteId}/apply`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-WP-Nonce': wpApiSettings.nonce
      }
    });
    
    const data = await response.json();
    
    if (!data.success) {
      throw new Error(data.message || 'Failed to apply palette');
    }
    
    return data;
  } catch (error) {
    console.error('Error applying palette:', error);
    // Show user-friendly error message
    alert('Failed to apply palette. Please try again.');
    throw error;
  }
}
```

### 3. Verify Palette Exists Before Applying

```javascript
async function safeApplyPalette(paletteId) {
  // First, check if palette exists
  const response = await fetch(`/wp-json/woow/v1/palettes/${paletteId}`, {
    headers: { 'X-WP-Nonce': wpApiSettings.nonce }
  });
  
  if (response.status === 404) {
    throw new Error('Palette not found');
  }
  
  const data = await response.json();
  
  // Check completeness
  if (!data.completeness.complete) {
    console.warn('Palette is incomplete:', data.completeness.missing);
  }
  
  // Apply palette
  return applyPalette(paletteId);
}
```

### 4. Create Backup Before Applying

The API automatically creates a backup before applying a palette, but you can also create manual backups:

```javascript
// Create manual backup before applying palette
await fetch('/wp-json/woow/v1/backups', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-WP-Nonce': wpApiSettings.nonce
  },
  body: JSON.stringify({
    label: 'before_palette_change'
  })
});

// Then apply palette
await applyPalette('professional_blue');
```

---

## Testing

Use the included test script to verify endpoints:

```bash
# Navigate to plugin directory
cd wp-content/plugins/woow-admin

# Run test script (requires WordPress to be loaded)
php test-palette-api.php
```

Or access via browser:
```
https://your-site.com/wp-content/plugins/woow-admin/test-palette-api.php
```

---

## Support

For issues, questions, or feature requests:
- GitHub: https://github.com/m3n3sx/woow-admin
- Documentation: See plugin README.md

---

## Changelog

### Version 2.0.0-beta
- Added complete REST API support for palettes
- Implemented GET /palettes endpoint
- Implemented GET /palettes/{id} endpoint
- Implemented POST /palettes/{id}/apply endpoint
- Added nonce verification for security
- Added automatic backup creation before palette application
- Added completeness validation
- Added preview URL generation

---

## License

GPL v2 or later
