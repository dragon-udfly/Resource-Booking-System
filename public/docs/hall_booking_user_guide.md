# Hall Booking System: User Guide & Process Report

## Introduction
This guide provides a complete overview of the Hall Booking Application lifecycle, including Submission, Approval, Cancellation, and History Management. It is designed to help Users, Administrative Officers (AO), Additional Government Agents (AGA), and Government Agents (GA) understand their specific roles and capabilities.

---

## Phase 1: Application Submission
**Role:** Personal Assistant (Requester) or Public User

1.  **Access:** From the **Homepage**, click the **Book Hall** button.
2.  **Schedule View:** You will be taken to the *Hall Schedule* page to check availability.
3.  **Initiate:** Click the blue **Book Hall** button in the top menu.
4.  **Form:** Fill in the "Hall Booking Form" with the following details:
    *   **Applicant Details:** Name, Email, Type (Internal/External).
    *   **Hall Details:** Hall Type, Programme/Event Name.
    *   **Schedule:** Date, Time, Duration.
    *   **Participants:** Number of people.
    *   **Payment:** Paid Status, Emergency Booking.
    *   **Officer Info:** NIC, Phone.
    *   **Confirmation:** Check the "I filled this form..." box.
5.  **Submit:** Click **Submit for Approval**.
6.  **Confirmation:** You will see a green "Success" message on the form page.
7.  **Review:**
    *   Login to the System.
    *   Navigate to your **Dashboard**.
    *   Find the **"Submitted Forms"** section.
    *   Your booking will appear in the **Hall Booking Applications** table with status columns for AO, AGA, and GA.

---

## Phase 2: Approval Workflow
The application must pass through a strict hierarchy of approvals.

### Step 1: Administrative Officer (AO)
*   **Action:** Reviews the application for completeness and availability.
*   **Decision:**
    *   **Approve:** Select "Yes" and Submit. Application moves to AGA.
    *   **Reject:** Select "No" and Submit. Application status becomes **Rejected**.

### Step 2: Additional Government Agent (AGA)
*   **Action:** Validates the AO's recommendation.
*   **Decision:**
    *   **Approve:** Select "Yes" and Submit. Application moves to GA.
    *   **Reject:** Select "No" and Submit. Status becomes **Rejected**.

### Step 3: Government Agent (GA) - Final Authority
*   **Action:** Grants final approval.
*   **Decision:**
    *   **Approve:** Select "Yes" -> **Finalize**. Status becomes **Approved**.
    *   **Reject:** Select "No" -> **Enter Reason** -> **Finalize**. Status becomes **Rejected**.

---

## Phase 3: Post-Approval Management (Cancellation)
If an application is active (Future Event), it can be cancelled under specific rules.

### Scenario A: Requester wants to Cancel
*   **Condition:** Application must be **100% Pending** (No officer has touched it yet).
*   **Action:** Go to Dashboard -> Click "Review" -> Click "Cancel Booking".
*   **Result:** Booking becomes Cancelled.
*   **Restriction:** If AO/AGA has already approved, the Requester **cannot** cancel. They must contact the GA.

### Scenario B: Government Agent (GA) wants to Cancel
*   **Condition:** Application is **Approved** but the event has not happened yet (e.g., Emergency cancellation).
*   **Action:**
    1.  Go to **History**.
    2.  Click **View** on the Approved Booking.
    3.  Click **Cancel Booking**.
    4.  Enter **Reason** (Required).
    5.  Confirm.
*   **Result:** Status changes to **Cancelled**.

---

## Special Feature: Emergency Booking
Requesters can flag an application as an **Emergency**.

*   **How to Flag:** Select **"Yes"** for "Emergency Booking" in the booking form.
*   **Effect:**
    *   The application row appears **Highlighted in Yellow** on the Officer Dashboard.
    *   The text **"(Emergency Booking)"** is appended to the programme name in the public schedule.
*   **Approval:**
    *   It follows the **Standard Approval Workflow** (AO -> AGA -> GA).
    *   The highlighting serves as a visual cue for officers to prioritize the review.
    *   **Note:** Determining if a request qualifies as an emergency is up to the approving officers.

---

## Phase 4: History & Archival (Deletion)
Records are kept for audit purposes. **Deletion** (Permanent Removal) is restricted to specific Cleanup scenarios.

### Rule 1: History Cleanup (Past Events)
*   **Who:** Any User (Owner/Approver).
*   **Condition:** The Event Date has **passed** (Yesterday or earlier).
*   **Action:**
    1.  Go to **History**.
    2.  Click **View**.
    3.  Click **Delete Record**.
    4.  Confirm in the modal.
*   **Result:** The record is permanently removed from the database.

### Rule 2: Administrative Cleanup (Rejected Applications)
*   **Who:** Administrative Officer (AO) Only.
*   **Condition:**
    1.  Application was **Rejected** (Final Status is set).
    2.  GA has **NOT** processed it yet (GA Status is Pending).
*   **Action:** Go to History -> View -> Delete Record.
*   **Result:** Removes the rejected application to declutter the system.

### Rule 3: Protection (No Deletion)
*   **Scenario:** A Personal Assistant tries to delete a future approved booking.
*   **Result:** **Option Hidden**. PAs cannot destroy audit trails for upcoming official events. They must use Cancellation (if applicable) or wait until the event passes.

---

## Summary Matrix

| Action | Requester (PA) | AO | GA |
| :--- | :--- | :--- | :--- |
| **Submit** | ✅ Yes | ❌ No | ❌ No |
| **Approve** | ❌ No | ✅ Yes (Step 1) | ✅ Yes (Final) |
| **Cancel (Pending)** | ✅ Yes | ✅ Yes | ✅ Yes |
| **Cancel (Approved)** | ❌ No | ❌ No | ✅ Yes (with Reason) |
| **Delete (Past)** | ✅ Yes | ✅ Yes | ✅ Yes |
| **Delete (Future)** | ❌ No | ✅ Yes (If Rejected) | ❌ No |
