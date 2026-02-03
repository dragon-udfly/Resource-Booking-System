# Family Quarter Booking System: User Guide & Process Report

## Introduction
This guide provides a comprehensive overview of the **Family Quarter Application** process. It is designed for Government Officers (Requesters) applying for quarters and Administrative Staff (Approvers) managing the allocation.

---

## Phase 1: Application Submission
**Role:** Government Officer (Requester)

1.  **Access:**
    *   Logged in users can navigate to the **Homepage**.
    *   Or navigate to the **Home**.
    *   Click **Book Quarters**.
    *   Select **Family Quarters**.
2.  **Form Completion:** Fill in the "Application for Family Quarters" with the following details:
    *   **Officer Details:** Name, NIC, DOB, Designation, Gender, Service & Grade, Permanent Address, Temporary Address, Phone, Email, Monthly Salary, Date of Last Salary Increment, Date of Assumption of Duties.
    *   **Transferred Officer:** Provide description/order details if applicable.
    *   **Spouse Details:** Marital Status, Employment Status (Govt/Non-Govt), Designation, Department/Office, Monthly Salary, Increment Date.
    *   **Children Details:** Name, Age, Grade, School for all children.(for readability: Name at age of Age, studying in Grade, School)
    *   **Property Declaration:** Details of any house/land owned within Vavuniya District by applicant, spouse, or minor children.
    *   **Previous Quarters:** Duration of any previous stay in government quarters.
    *   **Marking Scheme Data:**
        *   Applicant's Department (Ministry/District Secretariat/Other).
        *   Number of Dependents.
        *   Dependents with Disability (Yes/No).
        *   Distance of Residency (from Workspace).
    *   **Special Reasons:** Any special circumstances to be considered by the GA.
    *   **Requester Details:** Valid NIC and Phone Number of the officer interacting with the system (Must have 'Requester' permission).
3.  **Submission:**
    *   Check the "Confirmation" box.
    *   Click **Submit**.
    *   *Note:* The system will verify the Requester's NIC and privileges.
4.  **Confirmation:** Upon success, you will see a success message.

---

## Phase 2: Verification & Approval Workflow
The application follows a 3-tier review process accessible via the **Dashboard**.

### Common Feature: Download PDF
*   All authorized users can download the application details as a PDF by clicking the **Download** button on the review page.

### Step 1: Administrative Officer (AO) - Verification
*   **Role:** First-level verification of documents and details.
*   **Action:**
    1.  Login and go to **Dashboard**.
    2.  Locate application in "Quarters Reservation Applications" and click **Review**.
    3.  **Check Marking:** Review the system-calculated marks.
    4.  **Decision:**
        *   **Verify:** Select **"Yes"** for 'Administrative Officer Verified'.
        *   **Flag/Query:** Select **"No"** and add a mandatory 'Note'.
    5.  Click **Submit**.
    *   *Note:* AO can **Delete** pending applications if they are incorrect or duplicates.

### Step 2: Additional Government Agent (AGA) - Verification
*   **Role:** Secondary verification and endorsement.
*   **Action:**
    *   Reviews application and AO's status.
    *   Selects **"Yes"** or **"No"** for 'AGA Verified'.
    *   Adds a note if necessary.
    *   Submits the review.

### Step 3: Government Agent (GA) - Allocation
*   **Role:** Final Authority for allocating the quarter.
*   **Action:**
    *   Reviews all details, including the **Total Mark** breakdown (Seniority, Dependents, Distance, etc.).
    *   **Special Marks:** Can manually add up to 10 marks for "Special Reasons".
    *   Checks "Available Family Quarters" table.
    *   **Allocate:**
        *   Selects **"Yes"** for 'Government Agent Approved'.
        *   Selects a specific **Quarter** from the available list.
        *   Clicks **Allocate**.
        *   *Result:* Status -> **Allocated**.
    *   **Reject:**
        *   Selects **"No"** for 'Government Agent Approved'.
        *   Adds a mandatory **GA Note** (Reason for rejection).
        *   Clicks **Reject**.
        *   *Result:* Status -> **Rejected**.

---

## Phase 3: Post-Allocation Management
Managed via the **History** page.

1.  **Access:**
    *   Click **History** in the main navigation menu.
    *   Locate the application in the **Quarters Reservation Applications** table.
    *   Click **View** to open the processed application details.

### 1. Cancellation (Revocation)
*   **Who:** Government Agent (GA) Only.
*   **Condition:** Application status is **Allocated**.
*   **Action:**
    *   In the **History** view, click **Cancel Allocation**.
    *   Provide a mandatory **Reason (GA Note)**.
    *   Confirm.
*   **Result:** Status changes to **Rejected** and quarter is released.

### 2. Restore to Pending
*   **Who:** GA, AGA, or AO.
*   **Condition:** Application status is **Rejected**.
*   **Action:**
    *   In the **History** view, click **Restore to Pending**.
    *   Provide a mandatory **Restoration Note**.
    *   Confirm.
*   **Result:** Status resets to **Pending**. Application moves back to **Dashboard** for re-processing.

---

## Phase 4: Deletion
Deletion is performed via the **Dashboard** for pending applications.

*   **Access:** Go to **Dashboard** -> Click **Review** on the application.
*   **Requester:** Can delete ONLY if Status is **Pending** AND it has NOT been verified by AO/AGA.
*   **Administrative Officer (AO):** Can delete if Status is **Pending**.
*   **Result:** Permanent removal of record.

---

## Summary Matrix

| Feature | Requester | AO | AGA | GA |
| :--- | :--- | :--- | :--- | :--- |
| **Submit App** | ✅ Yes | ❌ No | ❌ No | ❌ No |
| **Verify Info** | ❌ No | ✅ Yes | ✅ Yes | ❌ No |
| **Allocate Quarter**| ❌ No | ❌ No | ❌ No | ✅ Yes |
| **Manual Marks** | ❌ No | ❌ No | ❌ No | ✅ Yes |
| **Cancel Allocation**| ❌ No | ❌ No | ❌ No | ✅ Yes |
| **Restore (from Rejected)** | ❌ No | ✅ Yes | ✅ Yes | ✅ Yes |
| **Delete (Pending)**| ✅ Yes (Strict) | ✅ Yes | ❌ No | ❌ No |
