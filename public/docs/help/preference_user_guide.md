# Preference & Account Settings: User Guide

## Introduction
The **Preference** page allows logged-in officers to manage their personal account details and security settings. This includes updating contact information and changing the system login password.

---

## Phase 1: Accessing the Preference Page
**URL:** `/preference`

1.  **Navigation:**
    *   Log in to the system.
    *   Click on **Preference** in the main navigation menu (Top Left).
    *   *Note:* This option is generally available to authenticated users.

2.  **Interface Overview:**
    *   **Top Bar:** Contains a "Go Back" button and a "Log Out" button.
    *   **User Info:** Displays the logged-in user's First and Last Name.
    *   **Profile Details:** Form to update Email and Contact Number.
    *   **Change Password:** Form to update the login password.

---

## Phase 2: Managing Profile Details
**Form Fields:**

1.  **Email Address:**
    *   *Editable:* Yes
    *   *Required:* Yes
    *   *Description:* Your official email address for system notifications.

2.  **Contact Number:**
    *   *Editable:* Yes
    *   *Required:* Yes
    *   *Description:* Your primary contact number (10 digits).

**Process:**
1.  Modify the **Email Address** or **Contact Number** fields as needed.
2.  Click **Update Profile**.
3.  Confirm the action in the popup modal ("Yes, Save Changes").
4.  Upon success, a confirmation message will appear.

---

## Phase 3: Changing Password
**Form Fields:**

1.  **New Password:**
    *   *Type:* Password (Hidden by default)
    *   *Required:* Yes
    *   *Constraints:* Minimum 4 characters.

2.  **Confirm New Password:**
    *   *Type:* Password
    *   *Required:* Yes
    *   *Constraints:* Must exactly match the "New Password".

**Process:**
1.  Enter your desired new password.
2.  Re-enter the same password in the **Confirm New Password** field.
3.  Click **Change Password**.
4.  Confirm the action in the popup modal.

---

## Phase 4: Confirmation & Processing
Both actions (Profile Update and Password Change) trigger a safety mechanism:

1.  **Confirmation Modal:**
    *   A dialog box appears to confirm your intent (e.g., *"Are you sure you want to update your profile details?"*).
    *   **Yes, Save Changes:** Proceeds with the update.
    *   **Cancel:** Closes the modal.

2.  **Processing:**
    *   The system validates input (e.g., checking password match, email format).
    *   A "Processing..." overlay is shown while the server handles the request.

3.  **Completion:**
    *   **Success:** A success message is displayed (e.g., "Profile details updated successfully.").
    *   **Failure:** An error message is displayed (e.g., "Passwords do not match").

---

## Phase 5: System Actions & Audit Logging
The system automatically tracks these changes for security and accountability:

1.  **Profile Update:**
    *   Updates the `email` and `contact_number` in the database.
    *   Updates the `modified_datetime` timestamp.
    *   **Audit Log:** Records *"User [ID] updated their profile details"*.

2.  **Password Change:**
    *   Hashes and encrypts the new password before storage.
    *   Updates the `modified_datetime` timestamp.
    *   **Audit Log:** Records *"User [ID] changed their passcode"*.

---

## Summary Matrix

| Feature | Details |
| :--- | :--- |
| **Profile Access** | Email & Contact Number Editable |
| **Password Security** | Min 4 Chars |
| **Safety** | Confirmation Modals for all actions |
| **Audit Compliance** | Distinct logs for Profile vs Password changes |
