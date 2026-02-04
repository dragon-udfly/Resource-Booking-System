# System Settings Administration Guide

## Overview
The **System Settings** page allows Administrators to manage critical system configurations and perform maintenance tasks. This guide details the features available in this section, including Email Testing and Data Cleanup (Danger Zone).

**Access**: Dashboard > System Settings
**Role Required**: Administrator

---

## 1. Email Configuration Test
This section allows you to verify that the system is correctly configured to send emails (e.g., booking notifications, verification codes).

### How to Use
1.  **Recipient Email**: Enter a valid email address where you want to receive the test message.
2.  **Subject**: Enter a subject line for the test email (e.g., "System Test").
3.  **Message Body**: Enter the content of the email.
    *   *Note*: The text area is vertically resizable to accommodate longer messages.
4.  **Send**: Click the **Send Test Email** button.

### Feedback
- **Success**: A green alert box will appear confirming the email was sent.
- **Failure**: A red alert box will appear with error details. This usually indicates an issue with the `.env` configuration (e.g., incorrect SMTP host, port, or password).

---

## 2. Danger Zone
> [!WARNING]
> **CRITICAL WARNING**: Actions in this section are **DESTROUCTIVE** and **IRREVERSIBLE**. Once data is cleared, it cannot be recovered. Proceed with extreme caution.

This section provides tools to bulk-delete records for system maintenance or resetting.

### Available Actions

| Action | Description | Consequence |
| :--- | :--- | :--- |
| **Clear Audit Log Records** | Deletes the entire history of user actions stored in the Audit Log. | You will lose visibility into past system usage and modifications. |
| **Clear Hall Booking Details** | Deletes **ALL** hall booking records (Pending, Approved, Rejected). | All historical and active booking data will be erased. |
| **Clear Rejected Hall Applications** | Deletes only **Rejected** hall booking applications. | Cleans up the database without affecting active or approved bookings. |
| **Clear Rejected Scheduled Quarters** | Deletes only **Rejected** applications for Scheduled Quarters. | Cleans up allocation history for scheduled quarters. |
| **Clear Rejected Family Quarters** | Deletes only **Rejected** applications for Family Quarters. | Cleans up allocation history for family quarters. |
| **Clear User Details** | Deletes **ALL Non-Admin** user accounts. | Regular users (applicants/requesters) will be deleted. Admin accounts remain safe. |

### Execution Process
1.  Identify the action you wish to perform.
2.  Click the red **Clear** button next to the description.
3.  A **Confirmation Overlay** will appear asking you to confirm the specific action.
4.  Click **Yes, Clear It** to proceed, or **Cancel** to abort.

---

## Technical Notes
- **Email Configuration**: The actual SMTP settings (Host, Port, Username, Password) are read from the server's environment (`.env`) file. These cannot be changed from the UI for security reasons.
- **Audit Logging**: Actions taken in the Danger Zone (clearing data) are themselves recorded in the Audit Log (unless you just cleared the Audit Log itself).
