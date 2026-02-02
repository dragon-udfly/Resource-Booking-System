# Scheduled Quarter Booking System: User Guide & Process Report

## Introduction
This guide provides a comprehensive overview of the Scheduled Quarter Application process, designed for Government Officers (Requesters) and Administrative Staff (Approvers). It details the workflow from submission to final allocation and history management.

---

## Phase 1: Application Submission
**Role:** Government Officer (Requester)

1.  **Access:**
    *   Navigate to the **Homepage**.
    *   Click **Book Quarters**.
    *   Select **Scheduled Quarters**.
2.  **Form Completion:** Fill in the "Application for Scheduled Quarters" with the following essential details:
    *   **Officer Details:** Name, NIC, Designation, Service Grade, Phone, Salary.
    *   **Priority Requests (Optional):**
        *   Transferred Officer details.
        *   Night Duty requirements.
        *   Special Reasons.
    *   **Property Declaration:** Ownership within 5km of Vavuniya.
    *   **Requester Info:** Internal verification NIC and Phone.
3.  **Submission:**
    *   Check the "Confirmation" box.
    *   Click **Submit**.
    *   *Note:* The system will verify your internal NIC permissions before final submission.
4.  **Confirmation:** Upon success, you will see a success message.

---

## Phase 2: Verification & Approval Workflow
The application follows a 3-tier review process.

### Step 1: Administrative Officer (AO) - Verification
*   **Role:** Verifies the accuracy of the application details against physical files.
*   **Action:**
    1.  Login and go to **Dashboard**.
    2.  Locate the application in "Quarters Reservation Applications".
    3.  Click **Review**.
    4.  **Decision:**
        *   **Verify Context:** Select **"Yes"** for 'Administrative Officer Verified'.
        *   **Reject/Flag:** Select **"No"** and add a 'Note' explaining the discrepancy.
    5.  Click **Submit**.

### Step 2: Additional Government Agent (AGA) - Verification
*   **Role:** Secondary verification and endorsement.
*   **Action:**
    *   Reviews the application and AO's verification status.
    *   Selects **"Yes"** or **"No"** for 'AGA Verified'.
    *   Submits the review.

### Step 3: Government Agent (GA) - Allocation
*   **Role:** Final Authority for allocating the quarter.
*   **Action:**
    *   Reviews grades, priority requests, and verification status.
    *   **Allocate:**
        *   Selects an **Available Quarter** from the list.
        *   Sets **Allocation Date** and **Vacate Date**.
        *   Clicks **Allocate Quarter**.
    *   **Reject:**
        *   Clicks **Reject**, providing a reason.

---

## Phase 3: Post-Allocation Management

### 1. Cancellation (Revocation)
*   **Who:** Government Agent (GA) Only.
*   **Condition:** Application status is **Allocated**.
*   **Action:**
    *   In the Review/History page, click **Cancel Allocation**.
    *   Must provide a **GA Note/Reason**.
*   **Result:** The status changes to **Rejected**, and the quarter is freed up (occupancy count decreases).

### 2. Reconsideration
*   **Who:** GA, AGA, or AO.
*   **Condition:** Application status is **Allocated** or **Rejected**.
*   **Action:**
    *   Click **Reconsider**.
    *   Provide a note.
*   **Result:** Status resets to **Pending**, allowing the process to start over or corrections to be made.

---

## Phase 4: History & Deletion
Records are maintained for transparency. Deletion is strictly regulated.

### Deletion Rules
Deletion is only possible if the application is **Pending** and has **NOT** been verified by higher-ups.

*   **Requester:** Can delete ONLY if:
    *   AO Verified = **No/Pending**.
    *   AGA Verified = **No/Pending**.
    *   Status = **Pending**.
*   **Administrative Officer (AO):** Can delete if Status = **Pending**.
*   **Result:** Permanent removal of the application and its data.

---

## Summary Matrix

| Feature | Requester | AO | AGA | GA |
| :--- | :--- | :--- | :--- | :--- |
| **Submit App** | ✅ Yes | ❌ No | ❌ No | ❌ No |
| **Verify Info** | ❌ No | ✅ Yes | ✅ Yes | ❌ No |
| **Allocate Quarter**| ❌ No | ❌ No | ❌ No | ✅ Yes |
| **Cancel Allocation**| ❌ No | ❌ No | ❌ No | ✅ Yes |
| **Reconsider** | ❌ No | ✅ Yes | ✅ Yes | ✅ Yes |
| **Delete (Pending)**| ✅ Yes (Strict) | ✅ Yes | ❌ No | ❌ No |

---
