# Error Handling Flow Diagram

## Complete Application Flow with Error Handling

```
┌─────────────────────────────────────────────────────────────────────┐
│                    USER INITIATES ACTION                             │
│              (Apply Palette or Template)                             │
└────────────────────────────┬────────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────────┐
│                      REST API ENDPOINT                               │
│  /wp-json/woow/v1/palettes/{id}/apply                              │
│  /wp-json/woow/v1/templates/{id}/apply                             │
├─────────────────────────────────────────────────────────────────────┤
│  1. Verify Nonce                                                    │
│     ├─ Valid? → Continue                                            │
│     └─ Invalid? → Return 403 INVALID_NONCE                          │
│                                                                      │
│  2. Sanitize Input (palette_id / template_id)                       │
│                                                                      │
│  3. Initialize Manager + Dependencies                                │
│     - Backup Manager                                                 │
│     - CSS Generator                                                  │
│                                                                      │
│  4. Wrap in try-catch                                               │
└────────────────────────────┬────────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────────┐
│                    MANAGER: apply_palette()                          │
│                    MANAGER: apply_template()                         │
├─────────────────────────────────────────────────────────────────────┤
│  try {                                                               │
│                                                                      │
│    ┌──────────────────────────────────────────────────────────┐   │
│    │ STEP 1: Validate Input                                    │   │
│    │  - Sanitize ID                                            │   │
│    │  - Check not empty                                        │   │
│    │  ├─ Valid? → Continue                                     │   │
│    │  └─ Invalid? → Return INVALID_PALETTE_ID / TEMPLATE_ID   │   │
│    └──────────────────────────────────────────────────────────┘   │
│                             │                                        │
│                             ▼                                        │
│    ┌──────────────────────────────────────────────────────────┐   │
│    │ STEP 2: Get Palette/Template                              │   │
│    │  - Load from data file                                    │   │
│    │  - Check exists                                           │   │
│    │  ├─ Found? → Continue                                     │   │
│    │  └─ Not Found? → Return PALETTE_NOT_FOUND / TEMPLATE_NOT_FOUND│
│    └──────────────────────────────────────────────────────────┘   │
│                             │                                        │
│                             ▼                                        │
│    ┌──────────────────────────────────────────────────────────┐   │
│    │ STEP 3: Validate Completeness                             │   │
│    │  - Check all required sections present                    │   │
│    │  - Check minimum option counts                            │   │
│    │  ├─ Complete? → Continue                                  │   │
│    │  └─ Incomplete? → Return PALETTE_INCOMPLETE / TEMPLATE_INVALID│
│    └──────────────────────────────────────────────────────────┘   │
│                             │                                        │
│                             ▼                                        │
│    ┌──────────────────────────────────────────────────────────┐   │
│    │ STEP 4: Create Backup (CRITICAL)                          │   │
│    │  try {                                                     │   │
│    │    backup_id = backup_manager.create_backup()             │   │
│    │    Log: "Created backup {backup_id}"                      │   │
│    │  } catch (Exception $e) {                                 │   │
│    │    Return BACKUP_FAILED                                   │   │
│    │    ⚠️  DO NOT PROCEED WITHOUT BACKUP                      │   │
│    │  }                                                         │   │
│    └──────────────────────────────────────────────────────────┘   │
│                             │                                        │
│                             ▼                                        │
│    ┌──────────────────────────────────────────────────────────┐   │
│    │ STEP 5: Get Current Settings                              │   │
│    │  - Load from database                                     │   │
│    │  - Check not empty                                        │   │
│    │  ├─ Success? → Continue                                   │   │
│    │  └─ Failed? → throw Exception                             │   │
│    └──────────────────────────────────────────────────────────┘   │
│                             │                                        │
│                             ▼                                        │
│    ┌──────────────────────────────────────────────────────────┐   │
│    │ STEP 6: Merge Settings                                    │   │
│    │  - Palette: current + palette settings                    │   │
│    │  - Template: defaults + current + template settings       │   │
│    │  - Deep merge (array_replace_recursive)                   │   │
│    └──────────────────────────────────────────────────────────┘   │
│                             │                                        │
│                             ▼                                        │
│    ┌──────────────────────────────────────────────────────────┐   │
│    │ STEP 7: Validate Merged Settings                          │   │
│    │  - Run through settings validator                         │   │
│    │  ├─ Valid? → Continue                                     │   │
│    │  └─ Invalid? → throw Exception                            │   │
│    └──────────────────────────────────────────────────────────┘   │
│                             │                                        │
│                             ▼                                        │
│    ┌──────────────────────────────────────────────────────────┐   │
│    │ STEP 8: Update Database                                   │   │
│    │  - Write merged settings to database                      │   │
│    │  ├─ Success? → Continue                                   │   │
│    │  └─ Failed? → throw Exception                             │   │
│    └──────────────────────────────────────────────────────────┘   │
│                             │                                        │
│                             ▼                                        │
│    ┌──────────────────────────────────────────────────────────┐   │
│    │ STEP 9: Regenerate CSS (Non-Critical)                     │   │
│    │  try {                                                     │   │
│    │    css_generator.generate()                               │   │
│    │    Log: "CSS regenerated successfully"                    │   │
│    │  } catch (Exception $e) {                                 │   │
│    │    Log: "Warning: CSS regeneration failed"                │   │
│    │    ⚠️  Continue anyway (non-critical)                     │   │
│    │  }                                                         │   │
│    └──────────────────────────────────────────────────────────┘   │
│                             │                                        │
│                             ▼                                        │
│    ┌──────────────────────────────────────────────────────────┐   │
│    │ STEP 10: Return Success                                   │   │
│    │  return {                                                  │   │
│    │    success: true,                                          │   │
│    │    message: "Applied successfully",                       │   │
│    │    palette_id/template_id: "...",                         │   │
│    │    backup_id: "..."                                       │   │
│    │  }                                                         │   │
│    └──────────────────────────────────────────────────────────┘   │
│                                                                      │
│  } catch (Exception $e) {                                           │
│                                                                      │
│    ┌──────────────────────────────────────────────────────────┐   │
│    │ ERROR HANDLING                                             │   │
│    │                                                            │   │
│    │  1. Log Detailed Error                                    │   │
│    │     - Exception message                                   │   │
│    │     - File and line number                                │   │
│    │     - Full context                                        │   │
│    │                                                            │   │
│    │  2. Attempt Automatic Rollback                            │   │
│    │     if (backup_id exists) {                               │   │
│    │       try {                                                │   │
│    │         rollback_success = restore_backup(backup_id)      │   │
│    │         if (success) {                                     │   │
│    │           Log: "Restored from backup {backup_id}"         │   │
│    │         } else {                                           │   │
│    │           Log: "Failed to restore from backup"            │   │
│    │         }                                                  │   │
│    │       } catch (Exception $restore_error) {                │   │
│    │         Log: "Exception during rollback"                  │   │
│    │       }                                                    │   │
│    │     }                                                      │   │
│    │                                                            │   │
│    │  3. Return Error Response                                 │   │
│    │     return {                                               │   │
│    │       success: false,                                      │   │
│    │       error_code: "APPLICATION_FAILED",                   │   │
│    │       message: "Failed to apply...",                      │   │
│    │       context: {                                           │   │
│    │         error: "...",                                      │   │
│    │         backup_id: "...",                                 │   │
│    │         rollback_success: true/false                      │   │
│    │       }                                                    │   │
│    │     }                                                      │   │
│    └──────────────────────────────────────────────────────────┘   │
│  }                                                                   │
└────────────────────────────┬────────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────────┐
│                    REST API RESPONSE HANDLING                        │
├─────────────────────────────────────────────────────────────────────┤
│  if (result.success) {                                              │
│    - Clear CSS cache                                                │
│    - Return 200 with success response                               │
│    - Include updated settings                                       │
│  } else {                                                            │
│    - Map error_code to HTTP status                                  │
│    - Return error response with context                             │
│  }                                                                   │
└────────────────────────────┬────────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────────┐
│                         USER FEEDBACK                                │
├─────────────────────────────────────────────────────────────────────┤
│  SUCCESS:                                                            │
│    ✅ "Palette 'Professional Blue' applied successfully"            │
│    ✅ Settings updated in UI                                        │
│    ✅ Backup ID stored for potential manual rollback                │
│                                                                      │
│  FAILURE:                                                            │
│    ❌ "Failed to apply palette 'Professional Blue': [reason]"       │
│    ℹ️  "Your previous settings have been restored" (if rollback OK) │
│    ⚠️  Error code for support reference                             │
└─────────────────────────────────────────────────────────────────────┘
```

## Error Code Decision Tree

```
┌─────────────────────────────────────────────────────────────────────┐
│                         ERROR OCCURRED                               │
└────────────────────────────┬────────────────────────────────────────┘
                             │
                             ▼
                    Is input invalid?
                             │
                    ┌────────┴────────┐
                    │                 │
                   YES               NO
                    │                 │
                    ▼                 ▼
         ┌──────────────────┐   Does resource exist?
         │ INVALID_*_ID     │        │
         │ Status: 400      │   ┌────┴────┐
         └──────────────────┘   │         │
                               YES       NO
                                │         │
                                ▼         ▼
                         Is structure  ┌──────────────────┐
                         valid?        │ *_NOT_FOUND      │
                                │      │ Status: 404      │
                         ┌──────┴───┐  └──────────────────┘
                         │          │
                        YES        NO
                         │          │
                         ▼          ▼
                  Can create   ┌──────────────────┐
                  backup?      │ *_INCOMPLETE     │
                         │     │ *_INVALID        │
                  ┌──────┴───┐ │ Status: 400      │
                  │          │ └──────────────────┘
                 YES        NO
                  │          │
                  ▼          ▼
           Can apply    ┌──────────────────┐
           changes?     │ BACKUP_FAILED    │
                  │     │ Status: 500      │
           ┌──────┴───┐ └──────────────────┘
           │          │
          YES        NO
           │          │
           ▼          ▼
      ┌─────────┐  ┌──────────────────┐
      │ SUCCESS │  │ APPLICATION_     │
      │         │  │ FAILED           │
      └─────────┘  │ Status: 500      │
                   │ + Rollback       │
                   └──────────────────┘
```

## Rollback Decision Flow

```
┌─────────────────────────────────────────────────────────────────────┐
│                    EXCEPTION CAUGHT                                  │
└────────────────────────────┬────────────────────────────────────────┘
                             │
                             ▼
                    Was backup created?
                             │
                    ┌────────┴────────┐
                    │                 │
                   YES               NO
                    │                 │
                    ▼                 ▼
         ┌──────────────────┐   ┌──────────────────┐
         │ Attempt Rollback │   │ Log: No backup   │
         │                  │   │ available        │
         └────────┬─────────┘   └────────┬─────────┘
                  │                       │
                  ▼                       │
         try {                            │
           restore_backup()               │
         }                                │
                  │                       │
         ┌────────┴────────┐             │
         │                 │             │
    Rollback           Rollback          │
    Success            Failed            │
         │                 │             │
         ▼                 ▼             ▼
┌──────────────┐  ┌──────────────┐  ┌──────────────┐
│ Log: Success │  │ Log: Failed  │  │ Return error │
│ Settings     │  │ Settings may │  │ with context │
│ restored     │  │ be corrupted │  │              │
└──────┬───────┘  └──────┬───────┘  └──────┬───────┘
       │                 │                 │
       └─────────────────┴─────────────────┘
                         │
                         ▼
              ┌──────────────────────┐
              │ Return error with:   │
              │ - error_code         │
              │ - message            │
              │ - backup_id          │
              │ - rollback_success   │
              └──────────────────────┘
```

## Logging Flow

```
┌─────────────────────────────────────────────────────────────────────┐
│                         OPERATION START                              │
└────────────────────────────┬────────────────────────────────────────┘
                             │
                             ▼
                    ┌──────────────────┐
                    │ Log: Operation   │
                    │ initiated        │
                    └────────┬─────────┘
                             │
                             ▼
                    ┌──────────────────┐
                    │ Log: Backup      │
                    │ created          │
                    └────────┬─────────┘
                             │
                             ▼
                    Operation succeeds?
                             │
                    ┌────────┴────────┐
                    │                 │
                   YES               NO
                    │                 │
                    ▼                 ▼
         ┌──────────────────┐   ┌──────────────────┐
         │ Log: Success     │   │ Log: Exception   │
         │ - Operation name │   │ - Error message  │
         │ - ID applied     │   │ - File & line    │
         │ - Backup ID      │   │ - Full context   │
         └──────────────────┘   └────────┬─────────┘
                                          │
                                          ▼
                                 ┌──────────────────┐
                                 │ Log: Rollback    │
                                 │ attempt          │
                                 └────────┬─────────┘
                                          │
                                          ▼
                                 Rollback succeeds?
                                          │
                                 ┌────────┴────────┐
                                 │                 │
                                YES               NO
                                 │                 │
                                 ▼                 ▼
                      ┌──────────────────┐   ┌──────────────────┐
                      │ Log: Rollback    │   │ Log: Rollback    │
                      │ success          │   │ failed           │
                      └──────────────────┘   └──────────────────┘
```

## Key Principles

1. **Fail Fast** - Validate early, fail before making changes
2. **Backup First** - Always create backup before any modifications
3. **Atomic Operations** - Either complete success or complete rollback
4. **Comprehensive Logging** - Log every step with full context
5. **User-Friendly Messages** - Technical details in logs, friendly messages to users
6. **Graceful Degradation** - Non-critical failures don't block operation
7. **Automatic Recovery** - Rollback happens automatically, no user action needed
