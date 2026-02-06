# Family Quarter Application Process: User Guide

## Introduction
This document details the lifecycle of a **Family Quarter Application** within the Resource Booking System. These quarters are allocated based on a **Merit/Marking System** rather than a simple queue.

---

## 1. Application Submission
**Role:** Requester (Officer)
*   **Process:**
    *   Navigate to **Quarters > Family Quarters**.
    *   Fill in the detailed application form (Salary, Service Grade, Dependents, Spouse Details, etc.).
    *   **Marking Calculation:** The system *automatically* calculates a score (Total Marks) based on the input data (Seniority, Salary, Dependents, etc.).
*   **Outcome:**
    *   Application submitted with status `Pending`.
    *   Audit Log: *"New Family Quarter Application Submitted"*.

---

## 2. Verification & Review Workflow
Unlike Hall Bookings, Quarter applications require **Verification** of documents/claims before **Allocation**.

### Tier 1: Administrative Officer (AO)
*   **Action:** Verify the accuracy of the application details (Service letter, salary slips).
*   **Options:**
    *   **Verify (Yes):** Sets `is_ao_verified` to `1`.
    *   **Verify (No):** Sets `is_ao_verified` to `0` and adds a Note.

### Tier 2: Additional Government Agent (AGA)
*   **Action:** Secondary verification.
*   **Options:**
    *   **Verify (Yes):** Sets `is_aga_verified` to `1`.
    *   **Verify (No):** Sets `is_aga_verified` to `0` and adds a Note.

### Tier 3: Government Agent (GA) - Allocation
*   **Action:** Final decision Maker. The GA views the list sorted by **Total Marks** (Highest priority first).
*   **Options:**
    *   **Allocate:**
        *   Selects a specific **Quarter Unit** (e.g., C-12) from the available list.
        *   Adds optional notes or special reason marks.
        *   **Outcome:** Status becomes `Allocated`. Email sent to applicant.
    *   **Reject:**
        *   Sets status to `Rejected` with a mandatory note.
        *   **Outcome:** Email sent to applicant.

---

## 3. Special Features
### A. Dynamic Marking System
*   The system assigns marks for:
    *   Salary Scale
    *   Service Years (Assumption of Duties)
    *   Number of Dependents
    *   Distance from Residency
    *   Spouse's Employment Status
*   **Updates:** If the GA approves a "Special Reason", additional marks (up to 10) can be added to the score during the review.

### B. Gender Filtering
*   The system filters available quarters based on the applicant's gender to ensure appropriate placement (if the quarter has gender restrictions).

---

## 4. Cancellation & Deletion

### A. Requester (Cancellation)
*   **Rule:** Can cancel the application **ONLY** if:
    *   AO Verification is Pending (not strict Yes/No yet).
    *   AGA Verification is Pending.
    *   Final Status is `Pending`.
*   **Note:** Once an officer starts verifying, the requester cannot pull back the application to prevent data inconsistency during review.

### B. Government Agent (Revocation)
*   **Rule:** The GA can **Cancel** an *already allocated* quarter.
*   **Process:** Provides a "Reason for Cancellation".
*   **Outcome:**
    *   Quarter is freed (status returns to `Unallocated`).
    *   Application status becomes `Rejected` (Cancelled).
    *   Occupancy counter does *not* decrement (Family quarters are single-unit occupancies).

### C. Deletion (Cleanup)
*   **Requester:** Can delete only if *strictly pending* (no verifications started).
*   **Approvers:** Can delete pending applications or clear out rejected history.

---

## 5. Restoration (Re-open)
**Scenario:** An application was Rejected but needs to be reconsidered.
*   **Roles:** GA, AGA, or AO.
*   **Action:** Can **Restore** a `Rejected` application back to `Pending`.
*   **Outcome:** Allows the verification/allocation process to restart without the user re-submitting.

---

## Summary Matrix

| Action | Role | Email Sent? | Logic |
| :--- | :--- | :--- | :--- |
| **Submit** | Requester | No | Marks calculated automatically. |
| **Verify** | AO / AGA | No | Updates verification status flags. |
| **Allocate** | **GA** | **YES** | specific Quarter Unit assigned. |
| **Reject** | GA | **YES** | Application closed. |
| **Cancel** | GA | **YES** | Revokes an existing allocation. |
| **Restore** | Approvers | No | Moves `Rejected` -> `Pending`. |
