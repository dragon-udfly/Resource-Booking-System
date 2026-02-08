# Technical Manual: Email Service & Controller

## Overview
The Email System in the Resource Booking System is a centralized service designed to handle transactional emails, particularly those requiring dynamic PDF attachments (e.g., Approval Letters, Cancellation Notices). 

It is built using Laravel's **Mailable** classes and **DomPDF** for on-the-fly PDF generation.

## Architecture
The system follows a synchronous dispatch pattern:

1.  **Trigger**: A Controller (e.g., `HallBookingController`) acts as the trigger.
2.  **Dispatcher**: `App\Http\Controllers\EmailController` serves as the centralized dispatcher.
3.  **Mailable**: `App\Mail\*` classes handle message construction and PDF generation.
4.  **Transport**: Laravel's default `Mail` facade sends the email via the configured SMTP transport.

---

## 1. The Email Controller
**File:** `app/Http/Controllers/EmailController.php`

This controller provides a static utility method `sendEmail` to abstract away the complexity of instantiating specific Mailable classes. It standardizes error handling and logging.

### Method: `sendEmail`
```php
public static function sendEmail($recipientEmail, $type, $data)
```

#### Parameters
*   `$recipientEmail` (string): The destination email address.
*   `$type` (string): A unique key identifying the email template (see Supported Types).
*   `$data` (mixed): The model or data array required by the specific Mailable (e.g., `HallBooking` object).

#### Returns
*   `boolean`: `true` on success, `false` on failure.

#### Supported Types
| Type Key | Description | Mailable Class | Required Data |
| :--- | :--- | :--- | :--- |
| `hall_approved` | Hall Booking Approval | `HallBookingApproved` | `HallBooking` Model |
| `hall_cancelled` | Hall Booking Cancellation | `HallBookingCancelled` | `HallBooking` Model |
| `quarter_allocated` | Quarter Allocation Letter | `QuarterAllocated` | `QuarterAllocation` Model |
| `quarter_cancelled` | Allocation Revocation | `QuarterAllocationCancelled` | `QuarterAllocation` Model |

---

## 2. Mailables & PDF Generation
**Directory:** `app/Mail/`

Each transactional email has a dedicated Mailable class. A key feature of this system is **In-Memory PDF Generation**. Instead of saving a temporary file to disk, the PDF is generated in the constructor and passed as raw data to the email attachment.

### Example: `HallBookingApproved`
```php
class HallBookingApproved extends Mailable
{
    public $booking;
    protected $pdfOutput; // Stores raw PDF binary data

    public function __construct(HallBooking $booking)
    {
        $this->booking = $booking;

        // 1. Prepare View Data
        $data = [
            'booking' => $this->booking,
            'date' => Carbon::now()->format('Y-m-d')
        ];

        // 2. Generate PDF using DomPDF (Barryvdh wrapper)
        // 'output()' returns the raw string content of the PDF
        $this->pdfOutput = Pdf::loadView('pdf.hall_booking_approval_letter', $data)->output();
    }

    public function attachments(): array
    {
        // 3. Attach using raw data
        return [
            Attachment::fromData(fn() => $this->pdfOutput, 'filename.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
```

---

## 3. Templates (Views)

The system uses two sets of Blade views: one for the email body and one for the PDF attachment.

| Mailable | Email Body View | PDF Attachment View |
| :--- | :--- | :--- |
| **HallBookingApproved** | `emails.hall_booking_approved` | `pdf.hall_booking_approval_letter` |
| **HallBookingCancelled** | `emails.hall_booking_cancelled` | `pdf.hall_booking_cancellation_letter` |
| **QuarterAllocated** | `emails.quarter_allocated` | `pdf.quarter_allocation_letter` |
| **QuarterAllocationCancelled** | `emails.quarter_cancelled` | `pdf.quarter_cancellation_letter` |

*   **Email Body Location:** `resources/views/emails/`
*   **PDF Template Location:** `resources/views/pdf/`

---

## 4. Usage Guide

To send an email from any part of the application, simply import the controller and call the static method.

### Example: Approving a Hall Booking
In `HallBookingController.php`:

```php
use App\Http\Controllers\EmailController;

public function approve(Request $request, $id)
{
    $booking = HallBooking::findOrFail($id);

    // ... (Approval Logic) ...

    // Trigger Email Notification
    $emailSent = EmailController::sendEmail(
        $booking->email,        // Recipient
        'hall_approved',        // Type
        $booking                // Data (Model)
    );

    if ($emailSent) {
        // Log success...
    }
}
```

## 5. Troubleshooting

*   **Log Files:** Check `storage/logs/laravel.log`. The `EmailController` logs explicit "Success" and "Failed" messages with context.
*   **Configuration:** Ensure `.env` has correct SMTP settings (`MAIL_MAILER`, `MAIL_HOST`, etc.).
*   **PDF Issues:** If PDF generation fails, ensure existing images in PDF views use absolute paths or `public_path()` helpers, as DomPDF requires local paths.
