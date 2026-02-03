# Login System: User Guide & Process Report

## Introduction
This document outlines the **Login Process** for the Resource Booking System. It details the steps for authentication, validation rules, and the redirection logic based on user roles.

---

## Phase 1: Accessing the Login Page
**URL:** `/login`

1.  **Navigation:**
    *   Users can access the login page via the "Log In" link on the Homepage navigation bar.
    *   Direct access via the URL.
2.  **Interface:**
    *   The page features a clean interface with a "Back" button to return to the previous page.
    *   Input fields for **NIC** and **Passcode**.

## Phase 2: Credentials & Submission
**Form Fields:**

1.  **NIC (National Identity Card) Number:**
    *   *Type:* Text
    *   *Required:* Yes
    *   *Description:* The unique identifier for the officer.
2.  **Password:**
    *   *Type:* Password
    *   *Required:* Yes
    *   *Description:* The secure password associated with the user account.

**Client-Side Validation:**
*   A JavaScript script monitors the input fields.
*   The **Login** button is **disabled** by default and only becomes **enabled** when both the NIC and Password fields contain text.
*   The button visual changes (darker blue, shadow) on hover when enabled.

## Phase 3: Redirection & Access Control
The system redirects users based on their assigned **Role**:

*   **Admin Role:**
    *   Redirects to the **Admin Dashboard** (`/admin`).
*   **User/Officer Role:**
    *   Redirects to the **User Dashboard** (`/dashboard`).

## Phase 4: Error Handling
If authentication fails (Invalid NIC or Wrong Passcode):
*    The user is redirected back to the login page.
*   An error message is displayed: *"The provided credentials do not match our records."*
*   The NIC field retains the previously entered value for convenience.

## Summary Matrix

| Feature | Details |
| :--- | :--- |
| **Primary Key** | NIC Number |
| **Security** | Hashed Passcode Verification |
| **Role Handling** | Automatic Redirection (Admin vs. User) |
| **Security Log** | Automatic Audit Log Entry |
| **Error Feedback** | Standard Invalid Credentials Message |
