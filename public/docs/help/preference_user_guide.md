# Preference & Account Settings: User Guide

## Introduction
The **Preference** page allows logged-in officers to manage their account security by changing their system password.

---

## Phase 1: Accessing the Preference Page
**URL:** `/preference`

1.  **Navigation:**
    *   Log in to the system.
    *   Click on **Preference** in the main navigation menu (Top Left).
    *   *Note:* This option is only available to users with the 'Account Setting' permission.

2.  **Interface Overview:**
    *   **Top Bar:** Contains a "Go Back" button and a "Log Out" button.
    *   **User Info:** Displays the logged-in user's First and Last Name.
    *   **Action Area:** "Change Password" form.

---

## Phase 2: Changing Password
**Form Fields:**

1.  **New Password:**
    *   *Type:* Password (Hidden by default)
    *   *Required:* Yes
    *   *Constraints:* Minimum 4 characters.
2.  **Confirm New Password:**
    *   *Type:* Password (Hidden by default)
    *   *Required:* Yes
    *   *Constraints:* Must exactly match the "New Password".

**Features:**
*   **Show/Hide Toggle:** Each password field has a **Show** button to reveal the typed text for verification.

**Process:**
1.  Enter your desired new password in the **New Password** field.
2.  Re-enter the same password in the **Confirm New Password** field.
3.  Click **Save Changes**.

---

## Phase 3: Confirmation & Processing
Upon clicking "Save Changes", a safety mechanism is triggered:

1.  **Confirmation Modal:**
    *   A dialog box appears asking: *"Are you sure you want to change your passcode?"*
    *   Options:
        *   **Yes, Save Changes:** Proceeds with the update.
        *   **Cancel:** Closes the modal without making changes.

2.  **Processing:**
    *   If confirmed, the system securely sends the request to the server.
    *   **Validation:** The server checks if the password meets the minimum length (4 characters) and matches the confirmation.

3.  **Completion:**
    *   **Success:** A success message is displayed ("Passcode changed successfully."), and the form is reset.
    *   **Failure:** An error message is displayed (e.g., "Validation failed" if passwords don't match).

---

## Phase 4: System Actions
Behind the scenes, the system performs the following actions upon a successful update:

1.  **Encryption:** The new password is heavily encrypted (hashed) before being stored.
2.  **Timestamp:** The user's "Modified Date/Time" is updated.
3.  **Audit Log:** An entry is created in the system audit log (Title: *"User [ID] changed their passcode"*).

---

## Summary Matrix

| Feature | Details |
| :--- | :--- |
| **Permission Required** | Account Setting |
| **Min Password Length** | 4 Characters |
| **Security** | Confirmation Modal + Hash Encryption |
| **Audit Compliance** | Automatic Logging of Changes |
