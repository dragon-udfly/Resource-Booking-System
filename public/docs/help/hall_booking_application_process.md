# Hall Booking Application Process: User Guide

## Introduction
This document details the complete lifecycle of a Hall Booking Application within the Resource Booking System. It covers submission, the multi-tier approval workflow, cancellation policies, and special administrative actions.

---

## 1. Application Submission
**Role:** Requester (Any Officer) or Admin (on behalf of an officer).

*   **Process:**
    *   Navigate to **Halls > Book Hall**.
    *   Select a hall, date, and time.
    *   Fill in event details (Participants, Programme, etc.).
    *   **Emergency Check:** If marked as "Emergency Booking", the system *auto-approves* the request immediately (skips manual review).
*   **Outcome:**
    *   Application status set to `Pending` (unless Emergency).
    *   Audit Log entry created: *"New Hall Booking Application [ID] submitted"*.

---

## 2. Approval Workflow
The system enforces a **sequential 3-tier approval hierarchy**. An application must move through these stages in order:

### Tier 1: Administrative Officer (AO)
*   **Action:** Reviews the initial request.
*   **Options:**
    *   **Approve:** Sets `administrative_officer_approved` to `Approved`. Moves application to Tier 2.
    *   **Reject:** Sets status to `Rejected`. Process ends.

### Tier 2: Additional Government Agent (AGA)
*   **Action:** Validates the AO's approval.
*   **Options:**
    *   **Approve:** Sets `additional_government_agent_approved` to `Approved`. Moves application to Tier 3.
    *   **Reject:** Sets status to `Rejected`. Process ends.

### Tier 3: Government Agent (GA)
*   **Action:** Final authority.
*   **Options:**
    *   **Approve:** Sets `government_agent_approved` to `Approved`.
        *   **Outcome:** `final_approval` status becomes `Approved`.
        *   **Email Trigger:** System automatically sends a **"Hall Booking Approved"** email to the applicant.
    *   **Reject:** Sets status to `Rejected`.
        *   **Outcome:** `final_approval` status becomes `Rejected`.

---

## 3. Cancellation Policy
Cancellation rules depend on the user role and the current status of the booking.

### A. Requester (Applicant)
*   **Rule:** Can ONLY cancel if **ALL** approvals are still `Pending`.
*   **Restriction:** Once the AO (Tier 1) has processed it, the requester cannot cancel via the system.

### B. Administrative Officer (AO)
*   **Rule:** Can cancel a booking only if it is NOT yet finalized (i.e., `final_approval` is `Pending`).

### C. Government Agent (GA)
*   **Rule:** Can cancel an **Approved** booking (Revoke).
*   **Process:**
    *   GA selects "Cancel" on an already approved booking.
    *   Must provide a **Reason for Cancellation**.
*   **Outcome:**
    *   `final_approval` set to `Cancelled`.
    *   **Email Trigger:** System sends a **"Hall Booking Cancelled"** email to the applicant with the reason.

---

## 4. Deletion (Data Cleanup)
Deletion permanently removes the record from the database.

*   **Requesters:** Can delete their own applications ONLY if they are strictly `Pending` (no action taken yet).
*   **Admins/Approvers:**
    *   Can delete **Past Events** (History cleanup).
    *   Can delete **Rejected** applications that are no longer needed.
    *   **Constraint:** Cannot delete an active, approved upcoming booking without cancelling it first.

---

## 5. Re-Approval (GA Only)
**Scenario:** A booking was previously Cancelled or Rejected but needs to be reinstated.

*   **Role:** Government Agent (GA) only.
*   **Conflict Check:** The system automatically checks if the hall has been booked by *someone else* for that date/time in the meantime.
    *   *If Conflict:* Re-approval is blocked.
    *   *If Available:* Re-approval proceeds.
*   **Outcome:**
    *   Status is reset to `Approved`.
    *   Rejection reason is cleared.
    *   **Email Trigger:** Sends the **"Hall Booking Approved"** email again.

---

## Summary Matrix: Actions & Emails

| Action | Performed By | Email Sent? | Notes |
| :--- | :--- | :--- | :--- |
| **Submit** | Requester | No | - |
| **Approve** | AO / AGA | No | Internal status update only. |
| **Approve** | **GA** | **YES** | Final approval notification. |
| **Reject** | Any Approver | No | Status shows as Rejected in dashboard. |
| **Cancel** | Requester / AO | No | Only for pending requests. |
| **Cancel** | **GA** | **YES** | Revocation of confirmed booking. |
| **Re-Approve** | **GA** | **YES** | Reinstates booking if slot is free. |
