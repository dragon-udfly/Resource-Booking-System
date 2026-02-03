# Implementation Plan - Email Notifications & PDFs

## Goal Description
Implement automated email notifications with PDF attachments for key events in the Hall Booking and Quarter Allocation lifecycle.
Events to cover:
1.  **Hall Booking Approved**: Email + Approval Letter PDF.
2.  **Hall Booking Cancelled (after approval)**: Email + Cancellation Letter PDF.
3.  **Quarter Allocated**: Email + Allocation Letter PDF.
4.  **Quarter Allocation Cancelled**: Email + Cancellation Letter PDF.

## Proposed Changes

### 1. Mailable Classes
Create four new Mailable classes in `app/Mail`:
-   `HallBookingApproved`: Generates approval PDF and attaches it.
-   `HallBookingCancelled`: Generates cancellation PDF and attaches it.
-   `QuarterAllocated`: Generates allocation PDF and attaches it.
-   `QuarterAllocationCancelled`: Generates cancellation PDF and attaches it.

### 2. PDF Views
Create/Update Blade views for the PDF content in `resources/views/pdf`:
-   `hall_booking_approval_letter.blade.php`: Official approval letter.
-   `hall_booking_cancellation_letter.blade.php`: Cancellation notice with reasons.
-   `quarter_allocation_letter.blade.php`: Allocation details (Quarter No, Locations, etc.).
-   `quarter_cancellation_letter.blade.php`: Notice of revocation/cancellation.

### 3. Centralized Email Logic
#### [NEW] [EmailController.php](file:///c:/Users/User/Desktop/Desktop_User/WORKLOAD/Resource-Booking-System/app/Http/Controllers/EmailController.php)
-   **Method**: `public static function sendEmail($recipientEmail, $type, $data)`
-   **Responsibility**:
    1.  Accept email parameters.
    2.  Based on `$type` (e.g., 'hall_approved', 'quarter_allocated'):
        -   Generate the specific PDF.
        -   Select the correct Mailable class.
        -   Send email via `Mail` facade.
    -   Handles all `try-catch` logging for email failures.

### 4. Controller Integration
Refactor existing controllers to call `EmailController::sendEmail` instead of handling Mail logic directly.

#### [HallBookingController.php](file:///c:/Users/User/Desktop/Desktop_User/WORKLOAD/Resource-Booking-System/app/Http/Controllers/HallBookingController.php)
-   **Method `approve`**: Call `EmailController::sendEmail($email, 'hall_approved', $bookingData)`.
-   **Method `cancelApproved`**: Call `EmailController::sendEmail($email, 'hall_cancelled', $bookingData)`.

#### [QuarterAllocationController.php](file:///c:/Users/User/Desktop/Desktop_User/WORKLOAD/Resource-Booking-System/app/Http/Controllers/QuarterAllocationController.php)
-   **Methods `allocate...`**: Call `EmailController::sendEmail($email, 'quarter_allocated', $allocationData)`.
-   **Methods `cancel...`**: Call `EmailController::sendEmail($email, 'quarter_cancelled', $allocationData)`.

## Verification Plan
1.  **Mock Email Sending**: Use `log` driver or Mailtrap to verify emails are sent without spamming real users during test.
2.  **PDF Content Check**: Verify that the attached PDFs contain correct dynamic data (Names, Dates, ID numbers).
3.  **Flow Verification**:
    -   Approve a Hall Booking -> Check for Email.
    -   Cancel that Booking -> Check for Cancellation Email.
    -   Allocate a Quarter -> Check for Email.
    -   Cancel that Allocation -> Check for Cancellation Email.
