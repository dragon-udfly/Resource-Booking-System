# UserController Documentation

## Overview
The `UserController` manages user authentication, authorization, account management, and dashboard functionality. It handles user login/logout, profile management, permission assignments, and provides the main dashboard interface.

## Namespace
```php
namespace App\Http\Controllers;
```

## Dependencies
- `Illuminate\Http\Request`
- `App\Models\User`
- `App\Models\UserPermission`
- `App\Models\AuditLog`
- `App\Models\HallBooking`
- `App\Models\QuarterApplication`
- `App\Models\QuarterAllocation`
- `Illuminate\Support\Facades\Auth`
- `Illuminate\Support\Facades\Session`
- `Illuminate\Support\Str`
- `Carbon\Carbon`
- `Illuminate\Support\Facades\Hash`
- `Illuminate\Support\Facades\Validator`
- `Illuminate\Support\Facades\DB`
- `Illuminate\Support\Facades\Log`
- `App\Models\GradeSalarySetting`
- `App\Services\MarkingCalculatorService`

## Methods

### login(Request $request)
Handles user login authentication.

**Parameters:** 
- `$request` - HTTP request containing login credentials

**Validation rules:**
- `nic_number` - required
- `passcode` - required

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** POST `/login`

### logout(Request $request)
Handles user logout.

**Parameters:** 
- `$request` - HTTP request

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** POST `/logout`

### showDashboard()
Displays the user dashboard based on user role and permissions.

**Returns:** `\Illuminate\View\View`

**Route:** GET `/dashboard`

**View:** `dashboard`

### index()
Displays a listing of users.

**Returns:** `\Illuminate\View\View`

**Route:** GET `/users`

**View:** `officers`

### seeOfficers()
Displays a listing of officers for users with appropriate permissions.

**Authorization:** Requires 'view_officers' permission

**Returns:** `\Illuminate\View\View`

**Route:** GET `/users/view`

**View:** `seeofficers`

### create()
Shows the form for creating a new user account.

**Returns:** `\Illuminate\View\View`

**Route:** GET `/users/create`

**View:** `createaccount`

### store(Request $request)
Stores a newly created user account in storage.

**Parameters:** 
- `$request` - HTTP request containing user details

**Validation rules:**
- `first_name` - required, string, max:200
- `last_name` - required, string, max:200
- `nic_number` - required, string, max:50, unique:user
- `passcode` - required, string, min:4, max:255
- `email` - required, string, email, max:200, unique:user
- `contact_number` - required, string, max:10, unique:user
- `designation` - optional, string, max:200
- `permissions` - optional, array

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** POST `/users`

### edit(User $user)
Shows the form for editing the specified user.

**Parameters:**
- `$user` - The user instance to edit

**Returns:** `\Illuminate\View\View`

**Route:** GET `/users/{user}/edit`

**View:** `modifyaccount`

### update(Request $request, User $user)
Updates the specified user in storage.

**Parameters:**
- `$request` - HTTP request containing updated user details
- `$user` - The user instance to update

**Validation rules:**
- `first_name` - required, string, max:200
- `last_name` - required, string, max:200
- `designation` - optional, string, max:200
- `email` - required, string, email, max:200, unique:user with current user ID
- `contact_number` - required, string, max:10, unique:user with current user ID
- `passcode` - optional, string, min:4, confirmed
- `permissions` - optional, array

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** PUT `/users/{user}`

### destroy(Request $request, User $user)
Removes the specified user from storage.

**Parameters:**
- `$request` - HTTP request
- `$user` - The user instance to delete

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** DELETE `/users/{user}`

### changePassword(Request $request)
Changes the current user's password.

**Parameters:** 
- `$request` - HTTP request containing new password

**Validation rules:**
- `new_passcode` - required, string, min:4, confirmed

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** POST `/users/change-password`

### showAuditLog()
Displays the audit log to admin users.

**Returns:** `\Illuminate\View\View`

**Route:** GET `/audit-log`

**View:** `auditlog`

### seeAuditLog()
Displays the audit log to users with appropriate permissions.

**Authorization:** Requires 'view_audit_log' permission

**Returns:** `\Illuminate\View\View`

**Route:** GET `/audit-log/view`

**View:** `seeaudtilog`

### clearAuditLog(Request $request)
Clears all audit log records.

**Parameters:** 
- `$request` - HTTP request

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** POST `/audit-log/clear`

### clearUsers()
Clears all non-admin user records.

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** POST `/users/clear`

### showGradeSalary()
Displays the grade salary settings page.

**Returns:** `\Illuminate\View\View`

**Route:** GET `/grade-salary`

**View:** `gradesalary`

### updateGradeSalary(Request $request)
Updates the grade salary settings.

**Parameters:** 
- `$request` - HTTP request containing grade salary updates

**Validation rules:** Dynamic validation based on grade fields

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** PUT `/grade-salary`

## Key Features

1. **Authentication System:** Secure login/logout with hashed passwords
2. **Role-Based Access Control:** Different permissions for different user types
3. **Dashboard Personalization:** Customized dashboard based on user role and permissions
4. **Account Management:** Full CRUD operations for user accounts
5. **Permission System:** Flexible permission assignment to users
6. **Audit Logging:** All user actions are logged for accountability
7. **Password Management:** Secure password hashing and change functionality
8. **Grade Salary Management:** Administration of grade and salary settings
9. **Session Management:** Proper session handling and security
10. **Data Validation:** Comprehensive validation for all user inputs
11. **Automatic ID Generation:** Generates unique user IDs in the format 'userXXX'
12. **Application Integration:** Connects with hall booking and quarter allocation systems