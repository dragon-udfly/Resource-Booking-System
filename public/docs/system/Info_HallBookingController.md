# HallBookingController Documentation

## Overview
The `HallBookingController` manages all aspects of hall booking operations including creating, reviewing, approving, rejecting, and managing hall booking applications. It handles the complete lifecycle of hall booking requests from submission to final approval/rejection.

## Namespace
```php
namespace App\Http\Controllers;
```

## Dependencies
- `Illuminate\Http\Request`
- `App\Models\Hall`
- `App\Models\HallBooking`
- `App\Models\AuditLog`
- `App\Models\User`
- `Illuminate\Support\Facades\Auth`
- `Illuminate\Support\Str`
- `Carbon\Carbon`
- `Illuminate\Validation\Rule`
- `Illuminate\Support\Facades\Validator`
- `Illuminate\Support\Facades\Log`
- `Barryvdh\DomPDF\Facade\Pdf`

## Methods

### create()
Shows the form for creating a new hall booking.

**Returns:** `\Illuminate\View\View`

**Route:** GET `/hall-bookings/create`

**View:** `bookhall`

### store(Request $request)
Stores a newly created hall booking in storage.

**Parameters:** 
- `$request` - HTTP request containing booking details

**Validation rules:**
- `applicant_name` - required, string, max:200
- `applicant_email` - required, email, max:255
- `applicant_type` - required, string
- `hall_id` - required, string, must exist in hall table
- `programme` - required, string, max:200
- `event_date` - required, date
- `event_time` - required, date format H:i
- `participants` - required, integer, must not exceed hall capacity
- `event_duration` - required, numeric
- `paid_status` - required, string
- `is_emergency_booking` - required, boolean
- `filled_by_nic` - required, string, max:50
- `filled_by_phone` - required, string, max:50

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** POST `/hall-bookings`

### showSchedule()
Displays the hall booking schedule.

**Returns:** `\Illuminate\View\View`

**Route:** GET `/hall-bookings/schedule`

**View:** `hallschedule`

### verifyRequester(Request $request)
Verifies if a requester is valid based on NIC and contact number.

**Parameters:** 
- `$request` - HTTP request containing NIC and contact number

**Returns:** JSON response with success status

**Route:** POST `/hall-bookings/verify-requester`

### updateBooking(Request $request, HallBooking $hallBooking)
Updates an existing hall booking.

**Parameters:**
- `$request` - HTTP request containing updated booking details
- `$hallBooking` - The hall booking instance to update

**Authorization:** Only the original requester can update their booking

**Returns:** JSON response with success status

**Route:** PUT `/hall-bookings/{hallBooking}/update`

### destroyBooking(Request $request, HallBooking $hallBooking)
Deletes a hall booking.

**Parameters:**
- `$request` - HTTP request
- `$hallBooking` - The hall booking instance to delete

**Authorization:** 
- Requesters can delete their own bookings if all statuses are pending
- Administrative Officers can delete bookings if final status is set and GA status is pending

**Returns:** JSON response or redirect response

**Route:** DELETE `/hall-bookings/{hallBooking}/destroy`

### downloadPDF(HallBooking $hallBooking)
Downloads the hall booking application as a PDF.

**Parameters:**
- `$hallBooking` - The hall booking instance to download

**Returns:** PDF download response

**Route:** GET `/hall-bookings/{hallBooking}/download`

### review(HallBooking $hallBooking)
Shows the review page for a hall booking.

**Parameters:**
- `$hallBooking` - The hall booking instance to review

**Authorization:** 
- Requesters can only review their own bookings
- Approvers can review all bookings

**Returns:** `\Illuminate\View\View`

**Route:** GET `/hall-bookings/{hallBooking}/review`

### showProcessed(HallBooking $hallBooking)
Shows processed hall booking details.

**Parameters:**
- `$hallBooking` - The processed hall booking instance

**Authorization:** 
- Requesters can only view their own bookings
- Approvers can view all bookings

**Returns:** `\Illuminate\View\View`

**Route:** GET `/hall-bookings/{hallBooking}/processed`

### approve(HallBooking $hallBooking)
Approves a hall booking application.

**Parameters:**
- `$hallBooking` - The hall booking instance to approve

**Authorization:** Based on user's permission level (AO, AGA, GA)

**Returns:** JSON response with success status

**Route:** POST `/hall-bookings/{hallBooking}/approve`

### reject(HallBooking $hallBooking)
Rejects a hall booking application.

**Parameters:**
- `$hallBooking` - The hall booking instance to reject

**Authorization:** Based on user's permission level (AO, AGA, GA)

**Returns:** JSON response with success status

**Route:** POST `/hall-bookings/{hallBooking}/reject`

### cancelApproved(Request $request, HallBooking $hallBooking)
Cancels an approved hall booking.

**Parameters:**
- `$request` - HTTP request containing cancellation reason
- `$hallBooking` - The hall booking instance to cancel

**Authorization:** Government Agent, Administrative Officer, or Requester (under specific conditions)

**Returns:** JSON response with success status

**Route:** POST `/hall-bookings/{hallBooking}/cancel-approved`

### reApprove(Request $request, HallBooking $hallBooking)
Re-approves a cancelled hall booking.

**Parameters:**
- `$request` - HTTP request
- `$hallBooking` - The hall booking instance to re-approve

**Authorization:** Government Agent only

**Returns:** JSON response with success status

**Route:** POST `/hall-bookings/{hallBooking}/re-approve`

### showHistory()
Shows booking history.

**Returns:** `\Illuminate\View\View`

**Route:** GET `/hall-bookings/history`

**View:** `history`

### clearBookings()
Clears all hall booking records.

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** POST `/hall-bookings/clear-all`

### clearRejectedBookings()
Clears all rejected hall booking records.

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** POST `/hall-bookings/clear-rejected`

## Key Features

1. **Emergency Booking Support:** Emergency bookings are automatically approved
2. **Multi-Level Approval System:** AO → AGA → GA approval workflow
3. **Conflict Detection:** Prevents double-booking of halls on the same date
4. **Audit Logging:** All actions are logged for accountability
5. **PDF Generation:** Applications can be downloaded as PDFs
6. **Role-Based Access Control:** Different permissions for different user types
7. **Email Notifications:** Automatic notifications for approved bookings