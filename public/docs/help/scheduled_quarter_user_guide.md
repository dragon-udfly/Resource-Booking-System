# Scheduled Quarter Booking System: User Guide & Process Report

## Introduction
This guide provides a comprehensive overview of the Scheduled Quarter Application process, designed for Government Officers (Requesters) and Administrative Staff (Approvers). It details the workflow from submission to final allocation and history management.

---

## Phase 1: Application Submission
**Role:** Government Officer (Requester)

1.  **Access:**
    *   Logged in users can navigate to the **Homepage**.
    *   Or navigate to the **Home**.
    *   Click **Book Quarters**.
    *   Select **Scheduled Quarters**.
2.  **Form Completion:** Fill in the "Application for Scheduled Quarters" with the following details:
    *   **Officer Details:** Name, NIC, Designation, Gender, Service & Grade, Permanent Address, Temporary Address, Phone, Email, Monthly Salary, Date of Assumption of Duties.
    *   **Priority Requests (Optional):**
        *   **Transferred Officer:** Provide description if applicable.
        *   **Night Duty:** Provide description if applicable.
        *   **Other Special Reasons:** Provide description if applicable.
    *   **Property Declaration:** Details of any house/land owned within 5km of Vavuniya.
    *   **Requester Details:** Valid NIC and Phone Number of the officer interacting with the system (Must have 'Requester' permission).
3.  **Submission:**
    *   Check the "Confirmation" box.
    *   Click **Submit**.
    *   *Note:* The system will verify the Requester's NIC and privileges before final submission.
4.  **Confirmation:** Upon success, you will see a success message.

---

## Phase 2: Verification & Approval Workflow
The application follows a 3-tier review process accessible via the **Dashboard**.

### Common Feature: Download PDF
*   All authorized users can download the application details as a PDF by clicking the **Download** button on the review page.

### Step 1: Administrative Officer (AO) - Verification
*   **Role:** Verifies the accuracy of the application details against physical files.
*   **Action:**
    1.  Login and go to **Dashboard**.
    2.  Locate the application in "Quarters Reservation Applications".
    3.  Click **Review**.
    4.  **Decision:**
        *   **Verify:** Select **"Yes"** for 'Administrative Officer Verified'.
        *   **Flag/Query:** Select **"No"** and add a mandatory 'Note' explaining the discrepancy.
    5.  Click **Submit**.

### Step 2: Additional Government Agent (AGA) - Verification
*   **Role:** Secondary verification and endorsement.
*   **Action:**
    *   Reviews the application details and AO's verification status.
    *   Selects **"Yes"** or **"No"** for 'AGA Verified'.
    *   Adds a note if necessary.
    *   Submits the review.

### Step 3: Government Agent (GA) - Allocation
*   **Role:** Final Authority for allocating the quarter.
*   **Action:**
    *   Reviews grades, salaries, priority requests, and verifications.
    *   Checks the list of "Available Scheduled Quarters" (filtered by gender and grade).
    *   **Allocate:**
        *   Selects **"Yes"** for 'Government Agent Approved'.
        *   Selects a specific **Quarter** from the available list.
        *   Clicks **Allocate**.
        *   *System Action:* Status becomes **Allocated**. Allocation Date is set to now. Vacate Date is set to 5 years from now. Occupancy count of the quarter increases.
    *   **Reject:**
        *   Selects **"No"** for 'Government Agent Approved'.
        *   Adds a mandatory **GA Note** (Reason for rejection).
        *   Clicks **Reject**.
        *   *System Action:* Status becomes **Rejected**.

---

## Phase 3: Post-Allocation Management
Managed via the **Processed Scheduled Quarter Application** page (accessible from History).

### 1. Cancellation (Revocation)
*   **Who:** Government Agent (GA) Only.
*   **Condition:** Application status is **Allocated**.
*   **Action:**
    *   Click **Cancel Allocation**.
    *   Provide a mandatory **Reason (GA Note)**.
    *   Confirm the action.
*   **Result:**
    *   Status changes to **Rejected**.
    *   The quarter is released (occupancy count decreases).
    *   Allocation details (dates, assigned quarter) are cleared.

### 2. Restore to Pending (Reconsideration)
*   **Who:** GA, AGA, or AO.
*   **Condition:** Application status is **Rejected**. (Note: If an application is 'Allocated' and needs reconsideration, it must be Cancelled first).
*   **Action:**
    *   Click **Restore to Pending**.
    *   Provide a mandatory **Restoration Note**.
    *   Confirm the action.
*   **Result:**
    *   Status resets to **Pending**.
    *   The application returns to the Dashboard for re-verification and re-allocation.

---

## Phase 4: Deletion
Deletion is strictly regulated to maintain audit trails.

### Deletion Rules
Deletion is **only** possible if the application is effectively "new" and untouched by approvals.

*   **Requester:** Can delete ONLY if:
    *   Status = **Pending**.
    *   AO Verified = **Pending** (Not verified yet).
    *   AGA Verified = **Pending** (Not verified yet).
*   **Administrative Officer (AO):** Can delete if Status = **Pending** (regardless of verification state).
*   **Result:** Permanent removal of the application, allocation record, and all associated data.

---

## Summary Matrix

| Feature | Requester | AO | AGA | GA |
| :--- | :--- | :--- | :--- | :--- |
| **Submit App** | ✅ Yes | ❌ No | ❌ No | ❌ No |
| **Verify Info** | ❌ No | ✅ Yes | ✅ Yes | ❌ No |
| **Allocate Quarter**| ❌ No | ❌ No | ❌ No | ✅ Yes |
| **Cancel Allocation**| ❌ No | ❌ No | ❌ No | ✅ Yes |
| **Restore (from Rejected)** | ❌ No | ✅ Yes | ✅ Yes | ✅ Yes |
| **Delete (Pending)**| ✅ Yes (Strict) | ✅ Yes | ❌ No | ❌ No |
| **Download PDF** | ✅ Yes | ✅ Yes | ✅ Yes | ✅ Yes |

---
