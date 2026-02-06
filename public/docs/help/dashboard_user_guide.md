# User Dashboard & Navigation: User Guide

## Introduction
The **Dashboard** is the central command center for the Resource Booking System. Its layout and content depend entirely on your assigned **Permissions**.

*   **Administrative & Oversight Roles:** (GA, AGA, AO, Requester) View a comprehensive queue of all pending system applications.
*   **Standard Users:** View a simplified or blank interface depending on access rights.

---

## 1. Navigation Bar
The top navigation bar appears on every page for quick access.

### Key Links
*   **Preference:** Manage personal account settings (Email, Contact, Password).
*   **Dashboard:** Returns to this main view.
*   **Officers:** (Permission: `view_officers`) Directory of registered officers.
*   **Halls:** (Permission: `view_halls`) Hall calendar and booking details.
*   **Quarters:** (Permission: `view_quarters`) Quarter availability and details.
*   **History:** (Permission: `form_history`) Archive of processed applications (Approved/rejected).
*   **Audit Log:** (Permission: `view_audit_log`) System activity records.

### Account Controls (Right Side)
*   **User Info:** Displays your Rank/Designation and Name.
*   **Log Out:** Securely ends your session.

---

## 2. Pending Approvals Dashboard
**Audience:** Users with `GA Approval`, `AGA Approval`, `AO Approval`, or `Requester` permissions.

This dashboard acts as a **Work Queue**, displaying all applications currently awaiting action in the system.

### A. Layout & Tools
*   **Fixed Side Menu:** A floating menu on the right allows quick scrolling between sections (Hall, Family, Scheduled).
*   **Banner:** Displays "Pending Booking Approvals".

### B. Hall Booking Applications
*   **Content:** All hall bookings with `Pending` status.
*   **Columns:** Applicant Name, Submitted Date, Approval Status (AO/AGA/GA).
*   **Actions:**
    *   **Review:** Click to view full details and perform approval/rejection.
    *   **Emergency:** Rows highlighted in **Yellow** indicate emergency requests.

### C. Family Quarter Applications
*   **Content:** Family quarter requests waiting for allocation.
*   **Sorting:** Automatically sorted by **Total Marks** (Highest priority at the top).
*   **Columns:**
    *   **Applicant:** Name, Grade, Designation.
    *   **Total Mark:** Calculated score based on seniority/criteria.
    *   **Verifications:** Status triggers for AO and AGA steps.
*   **Actions:** Click **Review** to verify documents or approve allocation.

### D. Scheduled Quarter Applications
*   **Content:** Scheduled quarter requests waiting for allocation.
*   **Columns:** Applicant details and verification status chain.
*   **Actions:** Click **Review** to process the application.

---

## 3. Standard User Dashboard
**Audience:** Users without approval/requester permissions.

*   Standard users will predominantly use the **Navigation Bar** to access specific forms (e.g., "Halls" -> "Book Hall").
*   The main dashboard area may remain empty as their primary interactions happen via the specific functional pages (Halls/Quarters) rather than a central approval queue.

---

## Summary Matrix

| Feature | Admin / Approver / Requester View | Standard View |
| :--- | :--- | :--- |
| **Primary Scope** | **System-Wide** Pending Queue | Functional Access via Menu |
| **Hall Bookings** | View All Pending Bookings | N/A (See History for own) |
| **Family Apps** | View All Pending (Sorted by Marks) | N/A (See History for own) |
| **Scheduled Apps** | View All Pending | N/A (See History for own) |
| **Emergency** | Highlighted Yellow | N/A |
