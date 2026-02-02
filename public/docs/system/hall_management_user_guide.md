# Hall Management User Guide

This guide details the procedures for Administrators to manage Hall resources within the system.

---

## Overview
The Hall Management module is used to maintain the inventory of halls available for booking. Admins can define capacity, status, and provide descriptions for public viewing.

## Accessing Hall Management
1. **Log in** as an Administrator.
2. From the Admin Dashboard, click on **Manage Halls** (or similar navigation item depending on dashboard layout).
3. You will see the **Hall List** displaying all registered halls.

## Adding a New Hall
1. Click the **Add Hall** button.
2. Fill in the **Add New Hall** form:
    - **Hall Type**: E.g., "Conference Hall", "Auditorium".
    - **Capacity**: Maximum number of people (Integer).
    - **Description**: Detailed text about facilities (A/C, Projector, etc.).
    - **Hall Status**: Set to "Available" or "Unavailable" initially.
    - **Special Notice**: Optional. Use this to display warnings (e.g., "Under Renovation") to users.
3. Click **Add Hall**.
4. Confirm the action in the popup modal.

> **System Note:** A `Hall ID` (e.g., `hall001`) is automatically generated.

## Modifying a Hall
1. Locate the hall in the **Hall List**.
2. Click the **Modify** button.
3. Update fields as necessary. You can change the Status to "Unavailable" here to temporarily stop bookings.
4. Click **Save Changes**.

## Deleting a Hall
> **Warning:** Deleting a hall is permanent.

1. Click the **Delete** button next to the hall.
2. Confirm the deletion in the warning modal.
   - *Note:* If the hall has active bookings, the system may prevent deletion to preserve data integrity.

## Clearing All Data
*Located in System Settings*
- Use the **Clear All Halls** function with extreme caution. It removes **ALL** hall records from the database.
