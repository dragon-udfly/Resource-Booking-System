# Quarter Management User Guide

This guide details the procedures for Administrators to manage Government Quarters (Housing) within the system.

## Table of Contents
1. [Overview](#overview)
2. [Adding a New Quarter](#adding-a-new-quarter)
3. [Modifying Quarter Details](#modifying-quarter-details)
4. [Deleting a Quarter](#deleting-a-quarter)
5. [Understanding Quarter Metadata](#understanding-quarter-metadata)

---

## Overview
Quarter Management involves maintaining the database of government quarters available for allocation. This includes Family Quarters, Scheduled Quarters, and Chummary units.

## Adding a New Quarter
1. Navigate to **Quarter Management**.
2. Click **Add Quarter**.
3. Complete the form:
    - **Quarter Type**: "Family" or "Scheduled".
    - **Service Grade**: The grade eligible for this quarter (1, 2, 3, etc.).
    - **Status**: "Unallocated", "Allocated", "Repair", "Demolished".
    - **Location**: Physical address.
    - **Occupant Numbers**: Important for Chummary (shared) quarters.
4. Click **Add Quarter** and confirm.

> **Note:** Adding a quarter with a specific Grade automatically updates the potential inventory count in the Salary-Grade settings.

## Modifying Quarter Details
1. In the **Quarter List**, click **Modify**.
2. Update details such as Status (e.g., changing from "Allocated" to "Repair").
3. Updating **Service Grade** here will reflect in future eligibility checks.
4. Click **Update Quarter**.

## Deleting a Quarter
1. Click **Delete** in the list view.
2. Confirm deletion.
   - *Error Handling:* If the quarter is currently linked to an active Allocation, the system may prevent deletion to ensure records are kept.

## Understanding Quarter Metadata
- **Old/New Quarter No:** Used to map legacy physical file numbers to the new system.
- **Allowed Gender:** Specific to shared quarters (Chummary), ensuring male/female specific allocations.
- **Occupant Number:** The max capacity of a shared unit.
