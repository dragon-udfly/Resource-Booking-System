# Officer Management User Guide

This guide provides step-by-step instructions for Administrators to manage officer accounts within the District Secretariat Resource Booking System.

---

## Overview
The Officer Management module allows Administrators to create, update, and remove officer accounts. It also includes functionality to manage permissions for different system modules (Halls, Quarters, Approvals) and configure salary ranges for different service grades.

## Accessing Officer Management
1. **Log in** to the system as an **Administrator**.
2. From the **Admin Dashboard**, click on the **Officers** button.
3. You will be directed to the **Officers Management** page, which lists all current officers.

## Adding a New Officer
1. On the **Officers Management** page, click the green **Add Officer** button.
2. Fill in the **Create New Account** form:
    - **First Name & Last Name**: Required.
    - **Designation**: Verify the designation spelling carefully.
    - **Email Address**: Must be unique.
    - **Phone Number**: 10-digit format.
    - **NIC Number**: Unique National Identity Card number.
    - **Passcode**: Temporary password for the user.
3. **Select Permissions**: Check the boxes for the modules this officer is allowed to access/manage.
4. Click **Create Account**.
5. Confirm the action in the popup modal.

> **Note:** The system automatically generates a User ID (e.g., `user001`) which cannot be changed.

## Modifying an Officer Account
1. From the **Officers List** table, locate the officer you wish to edit.
2. Click the yellow **Modify** button in the "Actions" column.
3. Update the necessary details (Name, Email, Phone, Permissions).
    - **Note:** `NIC Number` and `Designation` are read-only to prevent identity mismatches.
4. **Change Passcode (Optional):** If the user forgot their password, enter a new one in the "New Passcode" field.
5. Click **Save Changes**.

## Deleting an Officer Account
> **Warning:** This action is irreversible.

1. Locate the officer in the **Officers List**.
2. Click the red **Delete** button.
3. A confirmation modal will appear displaying the officer's name and ID.
4. Click **Yes, Delete** to confirm.

## Managing Grade Salaries
This feature defines the salary limits for different service grades, which validates Quarter Applications.

1. On the **Officers Management** page, click the blue **Edit Salary Range for Grade** button.
2. You will see a table of grades (e.g., 1 (G I), 2 (G II)).
3. Update the **Minimum Salary** and **Maximum Salary** for each grade.
4. Click **Save Changes**.

## Understanding Permissions
Permissions control what an officer can do in the system:

| Permission | Description |
| :--- | :--- |
| **View Officers** | Can see the list of other officers (Read-only). |
| **View Halls** | Can browse hall details and calendars. |
| **View Quarters** | Can browse quarter availability and allocations. |
| **View Audit Log** | Can inspect system activity logs. |
| **Administrative Officer Approval** | Authorized to give AO Level approval for bookings or quarters. |
| **Additional Government Agent Approval** | Authorized to give AGA Level approval. |
| **Government Agent Approval** | Authorized to give GA Level approval (Final). |
| **Preference** | Can update their own account preferences. |
| **Requester** | Can submit hall/quarter booking requests on their own behalf. |
