# WOOW! Admin - Error Handling & Rollback Guide

## Overview

This document describes the comprehensive error handling and automatic rollback system implemented for palette and template application in WOOW! Admin plugin.

## Architecture

### Error Handling Flow

```
User Action (Apply Palette/Template)
    ↓
Validation (ID, Structure, Completeness)
    ↓
Backup Creation (Automatic)
    ↓
Settings Merge & Validation
    ↓
Database Update
    ↓
CSS Regeneration
    ↓
Success Response
    
    [If ANY step fails]
    ↓
Automatic Rollback (Restore from Backup)
    ↓
Error Response with Context
```

## Error Codes

### Palette Manager Error Codes

| Error Code | Description | HTTP Status | User Action |
|------------|-------------|-------------|-------------|
| `INVALID_PALETTE_ID` | Palette ID is empty or invalid | 400 | Check palette selection |
| `PALETTE_NOT_FOUND` | Palette does not exist | 404 | Select a different palette |
| `PALETTE_INCOMPLETE` | Palette missing required sections | 400 | Contact support |
| `BACKUP_FAILED` | Failed to create backup | 500 | Check disk space, try again |
| `APPLICATION_FAILED` | General application failure | 500 | Check logs, contact support |

### Template Manager Error Codes

| Error Code | Description | HTTP Status | User Action |
|------------|-------------|-------------|-------------|
| `INVALID_TEMPLATE_ID` | Template ID is empty or invalid | 400 | Check template selection |
| `TEMPLATE_NOT_FOUND` | Template does not exist | 404 | Select a different template |
| `TEMPLATE_INVALID` | Template has invalid structure | 400 | Contact support |
| `BACKUP_FAILED` | Failed to create backup | 500 | Check disk space, try again |
| `APPLICATION_FAILED` | General application failure | 500 | Check logs, contact support |

### REST API Error Codes

| Error Code | Description | HTTP Status | User Action |
|------------|-------------|-------------|-------------|
| `INVALID_NONCE` | Security token invalid/missing | 403 | Refresh page and try again |
| `MANAGER_NOT_INITIALIZED` | Internal initialization error | 500 | Contact support |
| `UNEXPECTED_ERROR` | Unhandled exception occurred | 500 | Check logs, contact support |

## Response Format

### Success Response

```json
{
  "success": true,
  "message": "Palette 'Professional Blue' applied successfully",
  "palette_id": "professional_blue",
  "backup_id": "woow_backup_1234567890",
  "settings": { ... }
}
```

### Error Response

```json
{
  "success": false,
  "error_code": "BACKUP_FAILED",
  "message": "Failed to create backup before applying palette 'Professional Blue'",
  "context": {
    "palette_id": "professional_blue",
    "error": "Disk quota exceeded",
    "rollback_success": false
  }
}
```

## Automatic Rollback

### When Rollback Occurs

Automatic rollback is triggered when:
1. Settings update fails
2. Settings validation fails after merge
3. Any exception is thrown during application
4. Database write operation fails

### Rollback Process

```php
try {
    // 1. Create backup
    $backup_id = $backup_manager->create_backup('before_palette_xyz');
    
    // 2. Apply changes
    $settings->update_all_settings($new_settings);
    
    // 3. Regenerate CSS
    $css_generator->generate();
    
    return success_response();
    
} catch (Exception $e) {
    // 4. Automatic rollback
    if ($backup_id) {
        $backup_manager->restore_backup($backup_id);
    }
    
    return error_response();
}
```

### Rollback Guarantees

- **Backup Created First**: Backup is always created before any changes
- **Atomic Restore**: Settings are restored in a single operation
- **CSS Regeneration**: CSS is regenerated after restore
- **Logged Operations**: All rollback operations are logged

## Error Logging

### Log Format

```
[WOOW Palette Manager] Error PALETTE_NOT_FOUND: Palette "xyz" not found | Context: {"palette_id":"xyz"}
```

### Log Levels

1. **Error**: Critical failures requiring attention
2. **Warning**: Non-critical issues (e.g., CSS regeneration failure)
3. **Info**: Successful operations

### Log Locations

- **PHP Error Log**: `/wp-content/debug.log` (if WP_DEBUG_LOG enabled)
- **Server Error Log**: Varies by hosting environment

## User-Friendly Messages

### Message Guidelines

1. **Clear**: Explain what went wrong
2. **Actionable**: Tell user what to do next
3. **Non-Technical**: Avoid jargon when possible
4. **Reassuring**: Mention rollback if applicable

### Examples

❌ **Bad**: "Exception in apply_palette: undefined index"
✅ **Good**: "Failed to apply palette. Your previous settings have been restored."

❌ **Bad**: "BACKUP_FAILED"
✅ **Good**: "Unable to create backup. Please check available disk space and try again."

## Frontend Integration

### JavaScript Error Handling

```javascript
async function applyPalette(paletteId) {
    try {
        const response = await fetch('/wp-json/woow/v1/palettes/' + paletteId + '/apply', {
            method: 'POST',
            headers: {
                'X-WP-Nonce': wpApiSettings.nonce
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showSuccessNotification(data.message);
        } else {
            showErrorNotification(data.message, data.error_code);
        }
        
    } catch (error) {
        showErrorNotification(
            'Network error. Please check your connection and try again.',
            'NETWORK_ERROR'
        );
    }
}
```

### Error Display

```javascript
function showErrorNotification(message, errorCode) {
    // Show user-friendly message
    const notification = {
        type: 'error',
        message: message,
        actions: getActionsForError(errorCode)
    };
    
    displayNotification(notification);
}

function getActionsForError(errorCode) {
    const actions = {
        'INVALID_NONCE': [
            { label: 'Refresh Page', action: () => location.reload() }
        ],
        'BACKUP_FAILED': [
            { label: 'Try Again', action: () => retryLastAction() },
            { label: 'Contact Support', action: () => openSupportDialog() }
        ],
        'PALETTE_NOT_FOUND': [
            { label: 'Browse Palettes', action: () => navigateToPalettes() }
        ]
    };
    
    return actions[errorCode] || [
        { label: 'Try Again', action: () => retryLastAction() }
    ];
}
```

## Testing Error Scenarios

### Manual Testing

1. **Invalid Palette ID**
   ```bash
   curl -X POST http://site.local/wp-json/woow/v1/palettes/invalid_id/apply \
     -H "X-WP-Nonce: YOUR_NONCE"
   ```

2. **Disk Full Scenario**
   - Fill disk to capacity
   - Attempt to apply palette
   - Verify backup failure error
   - Verify no changes applied

3. **Database Failure**
   - Temporarily disable database writes
   - Attempt to apply template
   - Verify rollback occurs

### Automated Testing

```php
// Test automatic rollback
public function test_palette_application_rollback_on_failure() {
    // Get current settings
    $original_settings = $this->settings->get_all_settings();
    
    // Mock settings update to fail
    $this->settings->shouldReceive('update_all_settings')
        ->once()
        ->andReturn(false);
    
    // Attempt to apply palette
    $result = $this->palette_manager->apply_palette('professional_blue');
    
    // Verify failure
    $this->assertFalse($result['success']);
    $this->assertEquals('APPLICATION_FAILED', $result['error_code']);
    
    // Verify settings unchanged (rollback occurred)
    $current_settings = $this->settings->get_all_settings();
    $this->assertEquals($original_settings, $current_settings);
}
```

## Recovery Procedures

### Manual Recovery

If automatic rollback fails:

1. **Via Admin Interface**
   - Navigate to WOOW! Admin → Backups
   - Select most recent backup
   - Click "Restore"

2. **Via WP-CLI**
   ```bash
   wp option get woow_backup_index
   wp option get woow_backup_1234567890
   wp option update woow_admin_settings --format=json < backup.json
   ```

3. **Via Database**
   ```sql
   -- List backups
   SELECT * FROM wp_options WHERE option_name LIKE 'woow_backup_%';
   
   -- Restore from backup
   UPDATE wp_options 
   SET option_value = (SELECT option_value FROM wp_options WHERE option_name = 'woow_backup_1234567890')
   WHERE option_name = 'woow_admin_settings';
   ```

### Emergency Reset

If all else fails:

```php
// Reset to defaults
delete_option('woow_admin_settings');
// Plugin will recreate with defaults on next load
```

## Best Practices

### For Developers

1. **Always Use Try-Catch**: Wrap risky operations in try-catch blocks
2. **Log Contextually**: Include relevant context in error logs
3. **Validate Early**: Validate inputs before creating backups
4. **Test Rollback**: Regularly test rollback scenarios
5. **Monitor Logs**: Set up log monitoring for production

### For Users

1. **Check Disk Space**: Ensure adequate disk space before applying
2. **Test First**: Test palettes/templates on staging site
3. **Keep Backups**: Don't delete recent backups
4. **Report Issues**: Report errors with error codes to support

## Monitoring & Alerts

### Recommended Monitoring

1. **Error Rate**: Track error responses per hour
2. **Rollback Rate**: Monitor automatic rollback frequency
3. **Backup Failures**: Alert on backup creation failures
4. **Disk Space**: Monitor available disk space

### Alert Thresholds

- Error rate > 5% of requests
- Rollback rate > 2% of applications
- Backup failures > 0
- Disk space < 100MB

## Support Information

### When Contacting Support

Include:
1. Error code
2. Error message
3. Palette/template ID
4. Recent error log entries
5. WordPress version
6. PHP version
7. Available disk space

### Debug Mode

Enable debug mode for detailed logging:

```php
// wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
define('WOOW_DEBUG', true); // Extra verbose logging
```

## Changelog

### Version 1.0.0 (Current)
- Initial implementation of comprehensive error handling
- Automatic rollback on failures
- Standardized error codes and messages
- User-friendly error responses
- Detailed error logging with context

---

**Last Updated**: 2024
**Maintained By**: WOOW! Admin Development Team
