# Template REST API - Quick Reference

## Base URL
```
/wp-json/woow/v1
```

## Authentication
All requests require:
```javascript
headers: {
  'X-WP-Nonce': wpApiSettings.nonce
}
```

## Endpoints

### 1. List All Templates
```javascript
GET /templates

// Response
{
  "success": true,
  "templates": [...],
  "count": 11
}
```

### 2. Get Single Template
```javascript
GET /templates/{id}

// Example
GET /templates/modern_minimal

// Response
{
  "success": true,
  "template": {
    "id": "modern_minimal",
    "name": "Modern Minimal",
    "preview_url": "...",
    "settings": { ... }
  }
}
```

### 3. Apply Template
```javascript
POST /templates/{id}/apply

// Example
POST /templates/glassmorphism_pro/apply

// Response
{
  "success": true,
  "message": "Template applied successfully",
  "template_id": "glassmorphism_pro",
  "settings": { ... }
}
```

## Available Templates

| ID | Name | Category |
|----|------|----------|
| `modern_minimal` | Modern Minimal | minimal |
| `glassmorphism_pro` | Glassmorphism Pro | modern |
| `dark_dashboard` | Dark Dashboard | dark |
| `colorful_creative` | Colorful Creative | creative |
| `corporate_blue` | Corporate Blue | corporate |
| `material_design` | Material Design | modern |
| `flat_2` | Flat 2.0 | minimal |
| `neumorphism` | Neumorphism | modern |
| `retro_wave` | Retro Wave | creative |
| `nature_inspired` | Nature Inspired | creative |
| `high_contrast` | High Contrast | minimal |

## Quick Examples

### Fetch API
```javascript
// Get all templates
const response = await fetch('/wp-json/woow/v1/templates', {
  headers: { 'X-WP-Nonce': wpApiSettings.nonce }
});
const data = await response.json();
console.log(data.templates);

// Apply template
const response = await fetch('/wp-json/woow/v1/templates/modern_minimal/apply', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-WP-Nonce': wpApiSettings.nonce
  }
});
const data = await response.json();
if (data.success) {
  window.location.reload();
}
```

### jQuery
```javascript
// Get template
$.ajax({
  url: '/wp-json/woow/v1/templates/dark_dashboard',
  method: 'GET',
  beforeSend: xhr => xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce),
  success: data => console.log(data.template)
});

// Apply template
$.ajax({
  url: '/wp-json/woow/v1/templates/glassmorphism_pro/apply',
  method: 'POST',
  beforeSend: xhr => xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce),
  success: data => {
    alert(data.message);
    location.reload();
  }
});
```

## Error Codes

| Code | Status | Description |
|------|--------|-------------|
| `invalid_nonce` | 403 | Nonce verification failed |
| `template_not_found` | 404 | Template ID doesn't exist |
| `apply_failed` | 400 | Failed to apply template |
| `exception` | 500 | Server error occurred |

## Testing

Test file: `/wp-content/plugins/woow-admin/test-template-api.php`

Run all tests to verify:
- ✅ Get all templates
- ✅ Get single template
- ✅ Apply template
- ✅ Error handling

## Notes

- Backup is automatically created before applying
- CSS cache is cleared after successful application
- All endpoints require `manage_options` capability
- Template IDs use underscores (e.g., `modern_minimal`)
- Preview images use hyphens (e.g., `modern-minimal.png`)
