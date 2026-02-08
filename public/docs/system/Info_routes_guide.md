# Developer Guide: Laravel Routes Management

This document explains the routing architecture of the application, defined in `routes/web.php`. It covers how to add, modify, and secure routes.

## 1. Routing Structure Overview

The application uses three main layers of routing:
1.  **Public Routes**: Accessible by anyone (visitors).
2.  **Protected User Routes**: Accessible only by authenticated users.
3.  **Administrative Routes**: Accessible only by authenticated Administrators.

---

## 2. Public Routes
These routes do not require any login and are used for landing pages and the login process.

| Path | Name | Controller Action |
| :--- | :--- | :--- |
| `/` | `home` | `home.blade.php` |
| `/login` | `login` | `UserController@login` |
| `/help` | `help` | `FileController@showHelp` |
| `/about` | `about` | `FileController@showAbout` |

---

## 3. Route Middlewares

The application uses two primary custom middleware groups:

### A. `auth`
Ensures the user is logged in. 
- **Scope**: Includes Dashboard, User Preferences, Internal Memos, and Booking/Quarter applications.

### B. `admin`
Ensures the user has the 'Admin' role.
- **Scope**: User management, Hall/Quarter CRUD, Audit Logs, and System Settings (Backup/Restore).

---

## 4. Key Route Groups

### Administrative Panel (`middleware: ['auth', 'admin']`)
- **User Management**: `createaccount`, `officers.index`, `users.edit`.
- **Resource Management**: `halls.store`, `quarters.update`, `marking-scheme.edit`.
- **System Maintenance**: `systemsetting`, `settings.backup.db`, `settings.restore.db`.
- **Audit Logs**: `auditlog`.

### Booking & Allocation (`middleware: ['auth']`)
- **Hall Bookings**: `hall_bookings.approve`, `hall_bookings.reject`, `hall_bookings.review`.
- **Quarter Allocations**: `family-quarter.allocate`, `scheduled-quarter.review`.
- **Internal Memos**: `memo.index`, `memo.send`.

---

## 5. Route Naming Conventions

Routes should always be named to ensure that `route()` helpers in Blade files don't break if URLs change.

**Pattern**: `[resource].[action]`
- `halls.index` -> List halls
- `halls.create` -> Show create form
- `halls.store` -> Handle form submission

---

## 6. How to Add a New Route

1.  **Identify the Controller**: Find the relevant controller or create a new one using `php artisan make:controller`.
2.  **Select the Group**: Decide if the route needs `auth` or `auth, admin` protection.
3.  **Define the Route**:
    ```php
    Route::get('/my-new-feature', [MyController::class, 'index'])->name('feature.index');
    ```
4.  **Add logic to Controller**: Implement the `index` method in `MyController`.
5.  **Create View**: Create the corresponding Blade file in `resources/views/`.

---

## 7. Security Best Practices
- **Never** place sensitive management routes outside the `admin` middleware group.
- **Always** use Named Routes for internal linking.
- **Internal Checks**: Even if a route is in the `auth` group, controllers often perform secondary permission checks (e.g., `Auth::user()->hasPermissionTo('government_agent_approval')`).
