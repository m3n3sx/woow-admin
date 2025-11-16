# Task 34: Backup Integration - Implementation Complete

## Overview
Implemented comprehensive backup creation before palette/template application with descriptive names, metadata storage, and automatic rollback on failure.

## Changes Made

### 1. Enhanced WOOW_Backup_Manager (`includes/class-woow-backup-manager.php`)

#### Added `restore_latest()` Method
```php
/**
 * Restore the most recent backup
 *
 * Convenience method to restore the latest backup without needing to know the ID.
 * Useful for automatic rollback scenarios.
 *
 * @return bool True on success, false on failure.
 */
public function restore_latest(): bool {
    $backups = $this->get_backups();

    if ( empty( $backups ) ) {
        error_log( '[WOOW Admin] No backups available to restore' );
        return false;
    }

    // Get the most recent backup (backups are already sorted newest first)
    $latest_backup = $backups[0];

    return $this->restore_backup( $latest_backup['id'] );
}
```

**Purpose**: Provides a convenient way to restore the most recent backup without needing to know the backup ID. Essential for automatic rollback scenarios when palette/template application fails.

**Features**:
- Automatically gets the most recent backup
- Returns false if no backups exist
- Logs errors for debugging
- Uses existing `restore_backup()` method

### 2. Enhanced WOOW_Template_Manager (`includes/class-woow-template-manager.php`)

#### Added Dependency Injection Properties
```php
/**
 * Backup manager instance
 *
 * @var WOOW_Backup_Manager|null
 */
private ?WOOW_Backup_Manager $backup_manager = null;

/**
 * CSS generator instance
 *
 * @var WOOW_CSS_Generator|null
 */
private ?WOOW_CSS_Generator $css_generator = null;
```

#### Added Setter Methods
```php
/**
 * Set backup manager instance
 *
 * @param WOOW_Backup_Manager $backup_manager Backup manager instance.
 * @return void
 */
public function set_backup_manager( WOOW_Backup_Manager $backup_manager ): void {
    $this->backup_manager = $backup_manager;
}

/**
 * Set CSS generator instance
 *
 * @param WOOW_CSS_Generator $css_generator CSS generator instance.
 * @return void
 */
public function set_css_generator( WOOW_CSS_Generator $css_generator ): void {
    $this->css_generator = $css_generator;
}
```

#### Improved `apply_template()` Method

**Before**: Created new backup manager instance inside method
```php
// OLD CODE
$backup_manager = new WOOW_Backup_Manager( $this->settings );
$backup_manager->create_backup( 'before_template_' . $template_id );
```

**After**: Uses injected backup manager with better error handling
```php
// NEW CODE
if ( $this->backup_manager !== null ) {
    try {
        $template_name = $template['name'] ?? $template_id;
        $backup_label = 'before_template_' . $template_id;
        $backup_id = $this->backup_manager->create_backup( $backup_label );
        error_log( "[WOOW Admin] Created backup before applying template '{$template_name}': {$backup_id}" );
    } catch ( Exception $e ) {
        error_log( '[WOOW Admin] Failed to create backup: ' . $e->getMessage() );
        // Continue anyway - backup failure shouldn't prevent template application
    }
} else {
    error_log( '[WOOW Admin] Warning: Backup manager not set, applying template without backup' );
}
```

**Improvements**:
- Uses dependency injection instead of creating new instance
- Stores backup ID for rollback
- Includes template name in log messages
- Gracefully handles backup failure
- Warns if backup manager not set
- Better error logging

**Rollback on Failure**:
```php
catch ( Exception $e ) {
    error_log( '[WOOW Admin] Exception during template application: ' . $e->getMessage() );
    
    // Attempt to restore from backup
    if ( $this->backup_manager !== null && $backup_id !== null ) {
        try {
            $this->backup_manager->restore_backup( $backup_id );
            error_log( '[WOOW Admin] Restored from backup after failure' );
        } catch ( Exception $restore_error ) {
            error_log( '[WOOW Admin] Failed to restore from backup: ' . $restore_error->getMessage() );
        }
    }
    
    return false;
}
```

### 3. Updated WOOW_REST_API (`includes/class-woow-rest-api.php`)

#### Fixed Template Manager Instantiation

**Before**: Created new instances in each endpoint
```php
// OLD CODE
if ( $this->template_manager === null ) {
    $this->template_manager = new WOOW_Template_Manager( $this->settings );
}
```

**After**: Uses injected instance
```php
// NEW CODE
if ( $this->template_manager === null ) {
    return new WP_REST_Response(
        array(
            'success' => false,
            'message' => 'Template manager not initialized',
        ),
        500
    );
}
```

**Why This Matters**:
- Ensures backup manager is properly injected
- Prevents creating instances without dependencies
- Provides clear error messages
- Maintains consistency with palette manager

### 4. Main Plugin File (`woow-admin.php`)

The dependency injection is already properly set up:

```php
// Create instances with dependency injection
$settings          = new WOOW_Settings();
$cache             = new WOOW_Cache_Manager();
$css_generator     = new WOOW_CSS_Generator( $settings );
$backup_manager    = new WOOW_Backup_Manager( $settings );
$template_manager  = new WOOW_Template_Manager( $settings );
$palette_manager   = new WOOW_Palette_Manager( $settings );

// Set dependencies for palette manager
$palette_manager->set_backup_manager( $backup_manager );
$palette_manager->set_css_generator( $css_generator );

// Set dependencies for template manager
$template_manager->set_backup_manager( $backup_manager );
$template_manager->set_css_generator( $css_generator );
```

**This ensures**:
- Single backup manager instance shared across managers
- Proper dependency injection
- Consistent backup behavior

## Backup Naming Convention

### Palette Application
```
Label: before_palette_{palette_id}
Example: before_palette_professional_blue
```

### Template Application
```
Label: before_template_{template_id}
Example: before_template_modern_minimal
```

### Backup Metadata
Each backup includes:
```php
array(
    'id'        => 'woow_backup_1234567890',
    'timestamp' => 1234567890,
    'label'     => 'before_palette_professional_blue',
    'settings'  => array( /* all settings */ ),
    'metadata'  => array(
        'version'   => '1.0.0',
        'user_id'   => 1,
        'user_name' => 'Admin User',
        'site_url'  => 'https://example.com',
    ),
)
```

## Backup Workflow

### Successful Application
```
1. User applies palette/template
2. Backup manager creates backup with descriptive label
3. Backup ID stored for potential rollback
4. Settings updated
5. CSS regenerated
6. Success returned
```

### Failed Application
```
1. User applies palette/template
2. Backup manager creates backup with descriptive label
3. Backup ID stored
4. Settings update fails (exception thrown)
5. Backup manager restores from backup ID
6. Error logged
7. Failure returned
```

## Testing

### Test Files Created

1. **test-backup-integration.php**
   - Comprehensive integration test
   - Tests backup creation before palette/template application
   - Verifies backup metadata
   - Tests restore_latest() method
   - Checks dependency injection

2. **test-backup-methods.php**
   - Simple method verification
   - Checks all required methods exist
   - Verifies method signatures
   - No WordPress dependency

### Manual Testing Steps

1. **Test Palette Application with Backup**:
   ```php
   $palette_manager->apply_palette('professional_blue');
   // Check: Backup created with label 'before_palette_professional_blue'
   ```

2. **Test Template Application with Backup**:
   ```php
   $template_manager->apply_template('modern_minimal');
   // Check: Backup created with label 'before_template_modern_minimal'
   ```

3. **Test Backup Restoration**:
   ```php
   $backup_manager->restore_latest();
   // Check: Most recent backup restored
   ```

4. **Test Backup Statistics**:
   ```php
   $stats = $backup_manager->get_stats();
   // Check: Count, size, oldest, newest
   ```

## Requirements Satisfied

### Requirement 27.3: Backup Before Application
✅ **Implemented**: Both palette and template managers create backups before applying changes

### Requirement 27.4: Descriptive Backup Names
✅ **Implemented**: Backups use descriptive labels:
- `before_palette_{palette_id}`
- `before_template_{template_id}`

### Requirement 27.4: Backup Metadata
✅ **Implemented**: Each backup stores:
- Timestamp
- Label
- User information
- Plugin version
- Site URL
- Complete settings snapshot

### Automatic Rollback
✅ **Implemented**: Both managers automatically restore from backup on failure

## Benefits

1. **Safety**: Users can always revert changes
2. **Traceability**: Descriptive labels show what triggered each backup
3. **Debugging**: Metadata helps troubleshoot issues
4. **Automatic Recovery**: Failed applications automatically rollback
5. **Consistency**: Same backup behavior for palettes and templates
6. **Maintainability**: Dependency injection makes testing easier

## Error Handling

### Backup Creation Failure
- Logs error but continues with application
- Rationale: Backup failure shouldn't prevent legitimate changes

### Application Failure
- Attempts to restore from backup
- Logs both application and restoration errors
- Returns false to indicate failure

### No Backup Manager
- Logs warning
- Continues with application
- Useful for testing scenarios

## Future Enhancements

1. **Backup Comparison**: Show diff between current settings and backup
2. **Backup Export**: Allow downloading backups as JSON
3. **Backup Import**: Allow uploading backup files
4. **Scheduled Backups**: Automatic daily/weekly backups
5. **Backup Compression**: Reduce storage space for large backups

## Conclusion

Task 34 is **COMPLETE**. The backup integration is fully implemented with:
- ✅ Backup creation before palette/template application
- ✅ Descriptive backup names with metadata
- ✅ Automatic rollback on failure
- ✅ Proper dependency injection
- ✅ Comprehensive error handling
- ✅ Detailed logging for debugging

All requirements (27.3, 27.4) are satisfied.
