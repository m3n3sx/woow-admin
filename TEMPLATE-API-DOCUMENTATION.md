# WOOW! Admin - Template REST API Documentation

## Overview

The Template REST API provides endpoints for managing and applying design templates in the WOOW! Admin plugin. All endpoints require `manage_options` capability and support nonce verification for security.

**Base URL:** `/wp-json/woow/v1`

---

## Authentication

All endpoints require:
- **Capability:** `manage_options` (WordPress administrator)
- **Nonce:** Include `X-WP-Nonce` header with WordPress REST API nonce

### Getting a Nonce

```javascript
// In WordPress admin context
const nonce = wpApiSettings.nonce;

// Or create one
const nonce = wp.create_nonce('wp_rest');
```

---

## Endpoints

### 1. Get All Templates

Retrieves all available design templates with metadata and preview URLs.

**Endpoint:** `GET /wp-json/woow/v1/templates`

**Headers:**
```
X-WP-Nonce: {nonce}
```

**Response (200 OK):**
```json
{
  "success": true,
  "templates": [
    {
      "id": "modern_minimal",
      "name": "Modern Minimal",
      "description": "Clean, minimalist design focused on content",
      "category": "minimal",
      "preview_image": "modern-minimal.png",
      "preview_url": "https://example.com/wp-content/plugins/woow-admin/assets/images/previews/templates/modern-minimal.png",
      "author": "WOOW! Admin",
      "version": "1.0.0",
      "tags": ["minimal", "flat", "clean", "professional"],
      "characteristics": {
        "glassmorphism": false,
        "gradients": false,
        "animations": "subtle",
        "shadows": "minimal",
        "border_radius": "sharp"
      },
      "settings": {
        "color_overrides": { ... },
        "admin_bar": { ... },
        "admin_menu": { ... },
        "dashboard_widgets": { ... },
        "form_controls": { ... },
        "buttons": { ... },
        "backgrounds": { ... },
        "typography": { ... },
        "effects": { ... },
        "login_page": { ... }
      }
    },
    // ... more templates
  ],
  "count": 11
}
```

**Error Response (403 Forbidden):**
```json
{
  "success": false,
  "message": "Invalid nonce"
}
```

**Error Response (500 Internal Server Error):**
```json
{
  "success": false,
  "message": "Failed to load templates: {error details}"
}
```

**Example Usage:**

```javascript
// Fetch API
fetch('/wp-json/woow/v1/templates', {
  method: 'GET',
  headers: {
    'X-WP-Nonce': wpApiSettings.nonce
  }
})
.then(response => response.json())
.then(data => {
  if (data.success) {
    console.log(`Found ${data.count} templates`);
    data.templates.forEach(template => {
      console.log(`- ${template.name} (${template.id})`);
    });
  }
});

// jQuery
$.ajax({
  url: '/wp-json/woow/v1/templates',
  method: 'GET',
  beforeSend: function(xhr) {
    xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
  },
  success: function(data) {
    console.log('Templates:', data.templates);
  }
});
```

---

### 2. Get Single Template

Retrieves a specific template by ID with full configuration details.

**Endpoint:** `GET /wp-json/woow/v1/templates/{id}`

**Parameters:**
- `id` (string, required): Template ID (e.g., `modern_minimal`, `glassmorphism_pro`)

**Headers:**
```
X-WP-Nonce: {nonce}
```

**Response (200 OK):**
```json
{
  "success": true,
  "template": {
    "id": "modern_minimal",
    "name": "Modern Minimal",
    "description": "Clean, minimalist design focused on content",
    "category": "minimal",
    "preview_image": "modern-minimal.png",
    "preview_url": "https://example.com/wp-content/plugins/woow-admin/assets/images/previews/templates/modern-minimal.png",
    "author": "WOOW! Admin",
    "version": "1.0.0",
    "tags": ["minimal", "flat", "clean", "professional"],
    "characteristics": {
      "glassmorphism": false,
      "gradients": false,
      "animations": "subtle",
      "shadows": "minimal",
      "border_radius": "sharp"
    },
    "settings": {
      "color_overrides": {
        "primary_color": "#6b7280",
        "secondary_color": "#374151",
        "accent_color": "#3b82f6",
        "success_color": "#10b981",
        "warning_color": "#f59e0b",
        "error_color": "#ef4444",
        "info_color": "#06b6d4"
      },
      "admin_bar": {
        "enabled": true,
        "background_type": "solid",
        "background_color": "#ffffff",
        "text_color": "#1f2937",
        "height": "40",
        "border_radius_all": "0",
        "glassmorphism": false,
        "shadow_style": "sm"
        // ... all admin bar options
      },
      "admin_menu": {
        // ... all admin menu options
      },
      "dashboard_widgets": {
        // ... all dashboard widget options
      },
      "form_controls": {
        // ... all form control options
      },
      "buttons": {
        // ... all button options
      },
      "backgrounds": {
        // ... all background options
      },
      "typography": {
        // ... all typography options
      },
      "effects": {
        // ... all effect options
      },
      "login_page": {
        // ... all login page options
      }
    }
  }
}
```

**Error Response (404 Not Found):**
```json
{
  "success": false,
  "message": "Template not found"
}
```

**Error Response (403 Forbidden):**
```json
{
  "success": false,
  "message": "Invalid nonce"
}
```

**Example Usage:**

```javascript
// Fetch API
fetch('/wp-json/woow/v1/templates/modern_minimal', {
  method: 'GET',
  headers: {
    'X-WP-Nonce': wpApiSettings.nonce
  }
})
.then(response => response.json())
.then(data => {
  if (data.success) {
    console.log('Template:', data.template.name);
    console.log('Settings:', data.template.settings);
  }
});

// Axios
axios.get('/wp-json/woow/v1/templates/glassmorphism_pro', {
  headers: {
    'X-WP-Nonce': wpApiSettings.nonce
  }
})
.then(response => {
  const template = response.data.template;
  console.log(`${template.name}: ${template.description}`);
});
```

---

### 3. Apply Template

Applies a design template to the current settings. Creates a backup before applying and regenerates CSS.

**Endpoint:** `POST /wp-json/woow/v1/templates/{id}/apply`

**Parameters:**
- `id` (string, required): Template ID to apply

**Headers:**
```
X-WP-Nonce: {nonce}
Content-Type: application/json
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Template applied successfully",
  "template_id": "modern_minimal",
  "settings": {
    "color_overrides": { ... },
    "admin_bar": { ... },
    "admin_menu": { ... },
    "dashboard_widgets": { ... },
    "form_controls": { ... },
    "buttons": { ... },
    "backgrounds": { ... },
    "typography": { ... },
    "effects": { ... },
    "login_page": { ... }
  }
}
```

**Error Response (404 Not Found):**
```json
{
  "success": false,
  "message": "Template not found"
}
```

**Error Response (403 Forbidden):**
```json
{
  "success": false,
  "message": "Invalid or missing nonce"
}
```

**Error Response (400 Bad Request):**
```json
{
  "success": false,
  "message": "Failed to apply template"
}
```

**Error Response (500 Internal Server Error):**
```json
{
  "success": false,
  "message": "Error applying template: {error details}"
}
```

**Example Usage:**

```javascript
// Fetch API
fetch('/wp-json/woow/v1/templates/modern_minimal/apply', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-WP-Nonce': wpApiSettings.nonce
  }
})
.then(response => response.json())
.then(data => {
  if (data.success) {
    console.log('Template applied!');
    console.log('New settings:', data.settings);
    // Reload page to see changes
    window.location.reload();
  } else {
    console.error('Failed:', data.message);
  }
});

// jQuery with confirmation
function applyTemplate(templateId) {
  if (!confirm(`Apply template "${templateId}"? A backup will be created.`)) {
    return;
  }

  $.ajax({
    url: `/wp-json/woow/v1/templates/${templateId}/apply`,
    method: 'POST',
    beforeSend: function(xhr) {
      xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
    },
    success: function(data) {
      alert('Template applied successfully!');
      location.reload();
    },
    error: function(xhr) {
      alert('Error: ' + xhr.responseJSON.message);
    }
  });
}
```

---

## Available Templates

The plugin includes 11 predefined templates:

| ID | Name | Category | Description |
|----|------|----------|-------------|
| `modern_minimal` | Modern Minimal | minimal | Clean, minimalist design focused on content |
| `glassmorphism_pro` | Glassmorphism Pro | modern | Full glassmorphism effect, premium look |
| `dark_dashboard` | Dark Dashboard | dark | Complete dark mode setup |
| `colorful_creative` | Colorful Creative | creative | Vibrant, creative, playful |
| `corporate_blue` | Corporate Blue | corporate | Professional corporate design |
| `material_design` | Material Design | modern | Google Material Design inspired |
| `flat_2` | Flat 2.0 | minimal | Modern flat design |
| `neumorphism` | Neumorphism | modern | Soft UI neumorphic design |
| `retro_wave` | Retro Wave | creative | 80s synthwave aesthetic |
| `nature_inspired` | Nature Inspired | creative | Organic design with natural elements |
| `high_contrast` | High Contrast | minimal | Strong contrast for accessibility |

---

## Security

### Nonce Verification

All endpoints verify the WordPress REST API nonce:

```php
$nonce = $request->get_header( 'X-WP-Nonce' );
if ( ! $nonce || ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
    return new WP_REST_Response(
        array(
            'success' => false,
            'message' => 'Invalid or missing nonce',
        ),
        403
    );
}
```

### Capability Check

All endpoints require `manage_options` capability:

```php
public function check_permissions(): bool {
    return current_user_can( 'manage_options' );
}
```

### Input Sanitization

Template IDs are sanitized using `sanitize_key()`:

```php
'args' => array(
    'id' => array(
        'required'          => true,
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_key',
    ),
),
```

---

## Error Handling

### Automatic Backup

Before applying a template, a backup is automatically created:

```php
$backup_manager = new WOOW_Backup_Manager( $this->settings );
$backup_manager->create_backup( 'before_template_' . $template_id );
```

### Rollback on Failure

If template application fails, the system attempts to restore from the backup:

```php
try {
    $result = $this->template_manager->apply_template( $template_id );
} catch ( Exception $e ) {
    // Restore from backup
    $backup_manager->restore_latest();
    
    return new WP_REST_Response(
        array(
            'success' => false,
            'message' => 'Error applying template: ' . $e->getMessage(),
        ),
        500
    );
}
```

### CSS Cache Clearing

After successful template application, the CSS cache is cleared:

```php
if ( $result ) {
    $cache = new WOOW_Cache_Manager();
    $cache->delete( 'woow_css' );
}
```

---

## Complete Example: Template Selector Component

```javascript
class TemplateSelector {
  constructor() {
    this.apiUrl = '/wp-json/woow/v1';
    this.nonce = wpApiSettings.nonce;
    this.templates = [];
  }

  async loadTemplates() {
    try {
      const response = await fetch(`${this.apiUrl}/templates`, {
        headers: {
          'X-WP-Nonce': this.nonce
        }
      });
      
      const data = await response.json();
      
      if (data.success) {
        this.templates = data.templates;
        this.renderTemplates();
      }
    } catch (error) {
      console.error('Failed to load templates:', error);
    }
  }

  renderTemplates() {
    const container = document.getElementById('template-grid');
    
    container.innerHTML = this.templates.map(template => `
      <div class="template-card" data-id="${template.id}">
        <img src="${template.preview_url}" alt="${template.name}">
        <h3>${template.name}</h3>
        <p>${template.description}</p>
        <button onclick="templateSelector.applyTemplate('${template.id}')">
          Apply Template
        </button>
      </div>
    `).join('');
  }

  async applyTemplate(templateId) {
    if (!confirm('Apply this template? A backup will be created.')) {
      return;
    }

    try {
      const response = await fetch(`${this.apiUrl}/templates/${templateId}/apply`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-WP-Nonce': this.nonce
        }
      });
      
      const data = await response.json();
      
      if (data.success) {
        alert('Template applied successfully!');
        window.location.reload();
      } else {
        alert('Error: ' + data.message);
      }
    } catch (error) {
      console.error('Failed to apply template:', error);
      alert('Failed to apply template');
    }
  }
}

// Initialize
const templateSelector = new TemplateSelector();
templateSelector.loadTemplates();
```

---

## Testing

Use the included test file to verify the API:

1. Access: `/wp-content/plugins/woow-admin/test-template-api.php`
2. Run all tests to verify endpoints work correctly
3. Check console for detailed responses

---

## Changelog

### Version 1.0.0
- Initial implementation of template REST API
- Added GET `/templates` endpoint
- Added GET `/templates/{id}` endpoint
- Added POST `/templates/{id}/apply` endpoint
- Implemented nonce verification
- Added automatic backup creation
- Added CSS cache clearing
- Added error handling and rollback

---

## Support

For issues or questions:
- Check the test file: `test-template-api.php`
- Review error logs: WordPress debug log
- Verify nonce is valid and not expired
- Ensure user has `manage_options` capability
