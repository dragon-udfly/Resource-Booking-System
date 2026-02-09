# Technical Developer Guide: Application Submission Process

This document provides a technical walkthrough of the submission logic for **Hall Bookings** and **Quarter Applications** within the system.

## 1. Hall Booking Submission

The hall booking submission is handled by the `HallBookingController`.

### Use Case Diagram
![Hall Booking Submission](./images/uc_hall_booking_submission.png)

### Backend Logic (`HallBookingController@store`)

1. **Validation**: 
   - Ensures all applicant details (NIC, Phone, Email) are present.
   - Validates `hall_id` existence.
   - **Capacity Check**: A custom validator ensures the number of participants does not exceed the selected hall's capacity.
2. **Conflict Detection**:
   - The system checks for existing bookings for the same `hall_id` and `event_date` that are NOT `rejected` or `cancelled`.
3. **ID Generation**:
   - Generates a unique `booking_id` using the prefix `bookH` followed by a 3-digit padded number (e.g., `bookH001`).
4. **Data Persistence**:
   - Creates a new `HallBooking` record.
   - **Emergency Logic**: If `is_emergency_booking` is set to `true`, the application is automatically marked as `approved` across all levels (AO, AGA, GA).
5. **Auditing**:
   - Logs the creation in the `AuditLog` table for tracking.

---

## 2. Quarter Application Submission

Quarter applications (Family and Scheduled) are managed by the `QuarterAllocationController`.

### Use Case Diagram
![Quarter Application Submission](./images/uc_requester_quarter_application_submit.png)

### shared Submission Framework
Both application types follow a multi-table storage strategy wrapped in a database transaction to ensure data integrity.

### Family Quarter Submission (`storeFamilyQuarters`)
1. **Validation**: Extensive validation of officer details and marking criteria (distance, dependants, etc.).
2. **Data Structure**:
   - **Base Application**: Creates a record in `quarter_application`.
   - **Family Details**: Creates a record in `family_quarter_application` linked via `application_id`.
   - **Marking Data**: Creates a record in `marking_family_quarter` for GA review.
   - **Allocation Record**: Initializes a `quarter_allocation` record with a `pending` status.
3. **Transactional Safety**: If any step fails, the entire submission is rolled back.

### Scheduled Quarter Submission (`storeScheduledQuarters`)
1. **Validation**: Focuses on priority requests (transfer, night duty) and property ownership.
2. **Data Structure**:
   - **Base Application**: Creates a record in `quarter_application`.
   - **Scheduled Details**: Creates a record in `scheduled_quarter_application`.
   - **Allocation Record**: Initializes a `quarter_allocation` record.
5. **Auditing**: Records the submission in `AuditLog` with the requester's NIC.

## Developer Tips
- Always check the `AuditLog` table to verify if a submission was successfully registered by the system.
- For Quarter Applications, ensure you check the `allocation_status` in the `quarter_allocation` table to track the progress of the application.
