# Error Handling Quick Reference

## Error Response Format

All manager methods return standardized arrays:

```php
// Success
array(
    'success'    => true,
    'message'    => 'Operation completed successfully',
    'palette_id' => 'professional_blue',
    'backup_id'  => 'backup_1234567890',
)

// Failure
array(
    'success'    => false,
    'error_code' => 'PALETTE_NOT_FOUND',
    'message'    => 'Palette "xyz" not found',
    'context'    => array(
        'palette_id'       => 'xyz',
        'backup_id'        => 'backup_1234567890',
        'rollback_success' => true,
    ),
)
```

## Error Codes

| Code | Status | Description |
|------|--------|-------------|
| `INVALID_PALETTE_ID` | 400 | Invalid palette identifier |
| `INVALID_TEMPLATE_ID` | 400 | Invalid template identifier |
| `PALETTE_NOT_FOUND` | 404 | Palette doesn't exist |
| `TEMPLATE_NOT_FOUND` | 404 | Template doesn't exist |
| `PALETTE_INCOMPLETE` | 400 | Missing required sections |
| `TEMPLATE_INVALID` | 400 | Invalid structure |
| `BACKUP_FAILED` | 500 | Backup creation failed |
| `APPLICATION_FAILED` | 500 | Application process failed |
| `INVALID_NONCE` | 403 | Security token invalid |
| `UNEXPECTED_ERROR` | 500 | Unexpected exception |

## Usage Examples

### Applying a Palette

```php
// Initialize manager
$palette_manager = new WOOW_Palette_Manager( $settings );
$palette_manager->set_backup_manager( $backup_manager );
$palette_manager->set_css_generator( $css_generator );

// Apply palette
$result = $palette_manager->apply_palette( 'professional_blue' );

// Check result
if ( $result['success'] ) {
    echo 'Success: ' . $result['message'];
    echo 'Backup ID: ' . $result['backup_id'];
} else {
    echo 'Error: ' . $result['message'];
    echo 'Error Code: ' . $result['error_code'];
    if ( isset( $result['context']['rollback_success'] ) ) {
        echo 'Rollback: ' . ( $result['context']['rollback_success'] ? 'Success' : 'Failed' );
    }
}
```

### Applying a Template

```php
// Initialize manager
$template_manager = new WOOW_Template_Manager( $settings );
$template_manager->set_backup_manager( $backup_manager );
$template_manager->set_css_generator( $css_generator );

// Apply template
$result = $template_manager->apply_template( 'modern_minimal' );

// Handle result (same as palette)
if ( $result['success'] ) {
    // Success handling
} else {
    // Error handling
}
```

### REST API Usage

```javascript
// Apply palette via REST API
fetch('/wp-json/woow/v1/palettes/professional_blue/apply', {
    method: 'POST',
    headers: {
        'X-WP-Nonce': wpApiSettings.nonce,
        'Content-Type': 'application/json'
    }
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        console.log('Success:', data.message);
        console.log('Backup ID:', data.backup_id);
    } else {
        console.error('Error:', data.message);
        console.error('Error Code:', data.error_code);
        if (data.context.rollback_success) {
            console.log('Settings restored from backup');
        }
    }
})
.catch(error => {
    console.error('Network error:', error);
});
```

## Error Handling Checklist

When implementing new features:

- [ ] Wrap operations in try-catch blocks
- [ ] Create backup before making changes
- [ ] Validate input before processing
- [ ] Use standardized error response format
- [ ] Log errors with full context
- [ ] Attempt automatic rollback on failure
- [ ] Return user-friendly error messages
- [ ] Include error code for programmatic handling
- [ ] Test all error scenarios

## Logging Best Practices

```php
// ✅ GOOD - Detailed context
error_log( sprintf(
    '[Component] Error %s: %s | Context: %s',
    $error_code,
    $message,
    wp_json_encode( $context )
) );

// ✅ GOOD - Exception details
error_log( sprintf(
    '[Component] Exception: %s (File: %s, Line: %d)',
    $e->getMessage(),
    $e->getFile(),
    $e->getLine()
) );

// ❌ BAD - No context
error_log( 'Error: ' . $message );
```

## Common Patterns

### Pattern 1: Validate Input

```php
$palette_id = sanitize_key( $palette_id );
if ( empty( $palette_id ) ) {
    return $this->error_response(
        'INVALID_PALETTE_ID',
        'Invalid palette identifier provided',
        array( 'palette_id' => $palette_id )
    );
}
```

### Pattern 2: Check Existence

```php
$palette = $this->get_palette( $palette_id );
if ( $palette === null ) {
    return $this->error_response(
        'PALETTE_NOT_FOUND',
        sprintf( 'Palette "%s" not found', $palette_id ),
        array( 'palette_id' => $palette_id )
    );
}
```

### Pattern 3: Create Backup

```php
try {
    $backup_id = $this->backup_manager->create_backup( 'before_operation' );
} catch ( Exception $e ) {
    return $this->error_response(
        'BACKUP_FAILED',
        'Failed to create backup before operation',
        array( 'error' => $e->getMessage() )
    );
}
```

### Pattern 4: Attempt Rollback

```php
catch ( Exception $e ) {
    error_log( 'Operation failed: ' . $e->getMessage() );
    
    $rollback_success = false;
    if ( $backup_id !== null ) {
        try {
            $rollback_success = $this->backup_manager->restore_backup( $backup_id );
        } catch ( Exception $restore_error ) {
            error_log( 'Rollback failed: ' . $restore_error->getMessage() );
        }
    }
    
    return $this->error_response(
        'APPLICATION_FAILED',
        'Operation failed: ' . $e->getMessage(),
        array(
            'error'            => $e->getMessage(),
            'backup_id'        => $backup_id,
            'rollback_success' => $rollback_success,
        )
    );
}
```

## Testing Commands

```bash
# Test error handling
php woow-admin/test-error-handling.php

# Check error logs
tail -f /path/to/wordpress/wp-content/debug.log | grep "WOOW"

# Test REST API errors
curl -X POST http://localhost/wp-json/woow/v1/palettes/invalid_id/apply \
  -H "X-WP-Nonce: YOUR_NONCE" \
  -H "Content-Type: application/json"
```

## Debugging Tips

1. **Check Error Logs** - All errors are logged with context
2. **Verify Backup Created** - Look for backup creation log entry
3. **Check Rollback Status** - Error response includes rollback success flag
4. **Test with Invalid Data** - Verify validation catches bad input
5. **Monitor Database** - Ensure settings restored after rollback

## Support Resources

- **Full Documentation:** `ERROR-HANDLING-IMPLEMENTATION.md`
- **Error Flow Diagram:** `ERROR-HANDLING-FLOW.md`
- **Test File:** `test-error-handling.php`
- **Requirements:** `.kiro/specs/complete-palettes-templates/requirements.md` (27.5, 30.4)
