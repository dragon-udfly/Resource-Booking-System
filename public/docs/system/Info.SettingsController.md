# SettingsController Documentation

## Overview
The `SettingsController` manages system settings, email configuration, backup/restore operations, and system monitoring. It provides administrative functions for maintaining the system and its data.

## Namespace
```php
namespace App\Http\Controllers;
```

## Dependencies
- `Illuminate\Http\Request`
- `Illuminate\Support\Facades\Mail`
- `Illuminate\Support\Facades\Log`
- `Illuminate\Support\Facades\DB`

## Methods

### index()
Displays the system settings page.

**Returns:** `\Illuminate\View\View`

**Route:** GET `/settings`

**View:** `systemsetting`

### testEmail(Request $request)
Sends a test email using the provided details.

**Parameters:** 
- `$request` - HTTP request containing email details

**Validation rules:**
- `test_email` - required, must be a valid email
- `subject` - required, string, max:255
- `email-body` - required, string

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** POST `/settings/test-email`

### systemStatus(Request $request)
Displays the system status log page with filters.

**Parameters:** 
- `$request` - HTTP request containing filter options

**Authorization:** Admin or Government Agent only

**Returns:** `\Illuminate\View\View`

**Route:** GET `/settings/status`

**View:** `systemstatus`

### backupDatabase()
Performs a full database backup.

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** POST `/settings/backup/database`

### backupHalls()
Backs up hall records.

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** POST `/settings/backup/halls`

### backupQuarters()
Backs up quarter records.

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** POST `/settings/backup/quarters`

### backupOfficers()
Backs up officer records.

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** POST `/settings/backup/officers`

### backupHallBookings()
Backs up hall booking records.

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** POST `/settings/backup/hall-bookings`

### backupScheduledApplications()
Backs up scheduled quarter application records.

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** POST `/settings/backup/scheduled-applications`

### backupFamilyApplications()
Backs up family quarter application records.

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** POST `/settings/backup/family-applications`

### backupGradeSalary()
Backs up grade salary settings.

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** POST `/settings/backup/grade-salary`

### backupMarkingScheme()
Backs up marking scheme records.

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** POST `/settings/backup/marking-scheme`

### backupMemos()
Backs up memo records.

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** POST `/settings/backup/memos`

### executeBackup()
Internal method to execute backup operations (private method).

### restoreDatabase(Request $request)
Restores the database from a backup file.

**Parameters:** 
- `$request` - HTTP request containing backup file

**Validation rules:**
- `backup_file` - required, must be a file with .sql extension, max 50MB

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** POST `/settings/restore/database`

### restoreMemos(Request $request)
Restores memo records from a backup file.

**Parameters:** 
- `$request` - HTTP request containing backup file

**Validation rules:**
- `backup_file` - required, must be a file with .sql or .csv extension, max 50MB

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** POST `/settings/restore/memos`

### importMemoSql()
Internal method to import memos from SQL file (private method).

### importMemoCsv()
Internal method to import memos from CSV file (private method).

### restoreHalls(Request $request)
Restores hall records from a backup file.

**Parameters:** 
- `$request` - HTTP request containing backup file

**Returns:** Result of handleSpecificRestore()

**Route:** POST `/settings/restore/halls`

### restoreQuarters(Request $request)
Restores quarter records from a backup file.

**Parameters:** 
- `$request` - HTTP request containing backup file

**Returns:** Result of handleSpecificRestore()

**Route:** POST `/settings/restore/quarters`

### restoreOfficers(Request $request)
Restores officer records from a backup file.

**Parameters:** 
- `$request` - HTTP request containing backup file

**Returns:** Result of handleSpecificRestore()

**Route:** POST `/settings/restore/officers`

### restoreGradeSalary(Request $request)
Restores grade salary settings from a backup file.

**Parameters:** 
- `$request` - HTTP request containing backup file

**Returns:** Result of handleSpecificRestore()

**Route:** POST `/settings/restore/grade-salary`

### restoreMarkingScheme(Request $request)
Restores marking scheme records from a backup file.

**Parameters:** 
- `$request` - HTTP request containing backup file

**Returns:** Result of handleSpecificRestore()

**Route:** POST `/settings/restore/marking-scheme`

### handleSpecificRestore()
Internal method to handle specific table restores (private method).

### importSql()
Internal method to import from SQL file (private method).

### importCsv()
Internal method to import from CSV file (private method).

## Key Features

1. **Comprehensive Backup System:** Supports full database and selective table backups
2. **Email Testing:** Allows testing of email configuration
3. **System Monitoring:** Provides system status logs with filtering options
4. **Secure Restore Operations:** Includes integrity checks for restore operations
5. **Multiple Format Support:** Supports both SQL and CSV formats for some operations
6. **Audit Logging:** All system operations are logged for accountability
7. **Configurable Paths:** Uses environment variables for database tools
8. **File Validation:** Validates file types and sizes before processing
9. **Integrity Checks:** Performs integrity checks during restore operations
10. **Permission Controls:** Restricts access to authorized users