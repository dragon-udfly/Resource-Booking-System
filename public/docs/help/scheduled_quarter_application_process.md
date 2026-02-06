# Scheduled Quarter Application Process: User Guide

## Introduction
This document details the lifecycle of a **Scheduled Quarter Application**. Unlike Family Quarters (which use marks), Scheduled Quarters are often allocated based on specific service needs (transfers, night duties) and availability.

---

## 1. Application Submission
**Role:** Requester (Officer)
*   **Process:**
    *   Navigate to **Quarters > Scheduled Quarters**.
    *   Fill in the form (Transfer details, Priority requests, etc.).
*   **Outcome:**
    *   Application submitted with status `Pending`.
    *   Audit Log entry created.

---

## 2. Verification & Review Workflow

### Tier 1: Administrative Officer (AO)
*   **Verification:** Checks if the officer is eligible for a scheduled quarter.
*   **Action:** Sets `AO Verified` to **Yes** or **No**.

### Tier 2: Additional Government Agent (AGA)
*   **Verification:** Secondary check of priority claims (e.g., Night Duty).
*   **Action:** Sets `AGA Verified` to **Yes** or **No**.

### Tier 3: Government Agent (GA) - Allocation
*   **Action:** Allocates a specific bed/unit.
*   **Constraint: Gender Compatibility**
    *   The system **Strictly Enforces** gender matching.
    *   *Example:* If a Quarter is marked for "Females", a Male applicant *cannot* be allocated to it. The system will block the action.
*   **Constraint: Capacity**
    *   Scheduled quarters often have shared occupancy (multiple beds).
    *   System checks `Current Occupants < Max Occupants`. If full, allocation is blocked.
*   **Outcome:**
    *   **Allocate:** Increments the quarter's occupant count. Status set to `Allocated`. Email sent.
    *   **Reject:** Status set to `Rejected`. Email sent.

---

## 3. Cancellation & Deletion

### A. Requester (Cancellation)
*   **Rule:** Can delete/cancel only if the application is untouched (AO and AGA verifications are both `0` or `Pending`).

### B. Government Agent (Revocation)
*   **Action:** Can cancel an allocated spot.
*   **Logic:**
    *   Updates status to `Rejected`.
    *   **Decrements** the Quarter's occupant count (freeing up a bed).
    *   If occupant count drops below max, quarter status reverts to `Unallocated` (Available).
    *   **Email:** Sends cancellation notification.

---

## 4. Restoration
*   **Roles:** Approvers (GA/AGA/AO).
*   **Action:** Restore a `Rejected` application to `Pending`.
*   **Use Case:** An officer was rejected due to lack of space, but a bed became available later. The application can be restored instead of asking them to re-apply.

---

## Summary Matrix

| Feature | Family Quarter | Scheduled Quarter |
| :--- | :--- | :--- |
| **Selection Basis** | **Merit (Marks)** | **Need / Classification** |
| **Gender Rule** | Filters list (Soft) | **Strict Block** on Mismatch |
| **Occupancy** | Single Unit (Family) | **Multi-Occupancy** (Shared) |
| **Revocation** | Frees Unit | Decrements Occupant Count |
| **Restoration** | Available | Available |
