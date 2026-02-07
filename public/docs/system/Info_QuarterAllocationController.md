# QuarterAllocationController Documentation

## Overview
The `QuarterAllocationController` manages all aspects of quarter allocation processes including family and scheduled quarter applications, reviews, allocations, and cancellations. It handles the complete lifecycle of quarter allocation requests from submission to final allocation/rejection.

## Namespace
```php
namespace App\Http\Controllers;
```

## Dependencies
- `Illuminate\Http\Request`
- `App\Models\QuarterApplication`
- `App\Models\FamilyQuarterApplication`
- `App\Models\MarkingFamilyQuarter`
- `App\Models\ScheduledQuarterApplication`
- `App\Models\QuarterAllocation`
- `App\Models\AuditLog`
- `App\Models\MarkingScheme`
- `App\Models\Quarter`
- `App\Models\User`
- `App\Models\GradeSalarySetting`
- `App\Services\MarkingCalculatorService`
- `Illuminate\Support\Facades\Auth`
- `Illuminate\Support\Facades\DB`
- `Illuminate\Support\Facades\Log`
- `Illuminate\Support\Facades\Validator`
- `Illuminate\Validation\Rule`
- `Carbon\Carbon`
- `Illuminate\Support\Str`

## Methods

### bookFamilyQuarters()
Shows the form for booking family quarters.

**Returns:** `\Illuminate\View\View`

**Route:** GET `/quarters/family`

**View:** `familyquarter`

### showFamilyQuarterReview($id)
Shows the review page for a family quarter application.

**Parameters:**
- `$id` - The application ID to review

**Returns:** `\Illuminate\View\View`

**Route:** GET `/quarters/family/{id}/review`

**View:** `familyreview`

### allocateFamilyQuarter(Request $request, $id)
Allocates a family quarter to an application.

**Parameters:**
- `$request` - HTTP request containing allocation details
- `$id` - The application ID to allocate

**Authorization:** Government Agent only

**Returns:** JSON response or redirect response

**Route:** POST `/quarters/family/{id}/allocate`

### rejectFamilyQuarter(Request $request, $id)
Rejects a family quarter application.

**Parameters:**
- `$request` - HTTP request containing rejection details
- `$id` - The application ID to reject

**Authorization:** Government Agent only

**Returns:** JSON response or redirect response

**Route:** POST `/quarters/family/{id}/reject`

### updateFamilyQuarterReview(Request $request, $id)
Updates the review status of a family quarter application.

**Parameters:**
- `$request` - HTTP request containing review updates
- `$id` - The application ID to update

**Authorization:** Based on user's permission level (AO, AGA, Requester)

**Returns:** JSON response or redirect response

**Route:** POST `/quarters/family/{id}/update`

### storeFamilyQuarters(Request $request)
Stores a newly created family quarter application.

**Parameters:** 
- `$request` - HTTP request containing application details

**Validation rules:** Comprehensive validation for family quarter application fields

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** POST `/quarters/family`

### markingScheme()
Displays the marking scheme configuration.

**Returns:** `\Illuminate\View\View`

**Route:** GET `/marking-scheme`

**View:** `markingscheme`

### updateMarkingScheme(Request $request)
Updates the marking scheme configuration.

**Parameters:**
- `$request` - HTTP request containing updated marks

**Validation rules:**
- `marks` - required, array
- `marks.*` - required, numeric, min:0

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** PUT `/marking-scheme`

### bookScheduledQuarters()
Shows the form for booking scheduled quarters.

**Returns:** `\Illuminate\View\View`

**Route:** GET `/quarters/scheduled`

**View:** `scheduledquarter`

### storeScheduledQuarters(Request $request)
Stores a newly created scheduled quarter application.

**Parameters:** 
- `$request` - HTTP request containing application details

**Validation rules:** Comprehensive validation for scheduled quarter application fields

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** POST `/quarters/scheduled`

### showScheduledQuarterReview($id)
Shows the review page for a scheduled quarter application.

**Parameters:**
- `$id` - The application ID to review

**Returns:** `\Illuminate\View\View`

**Route:** GET `/quarters/scheduled/{id}/review`

**View:** `scheduledreview`

### createQuarterApplication(Request $request)
Creates a new quarter application record.

**Parameters:**
- `$request` - HTTP request containing application details

**Returns:** Created QuarterApplication model instance

### createQuarterAllocation($application_id)
Creates a new quarter allocation record.

**Parameters:**
- `$application_id` - The application ID to link to allocation

**Returns:** Created QuarterAllocation model instance

### createQuarterApplicationForScheduled(Request $request)
Creates a new scheduled quarter application record.

**Parameters:**
- `$request` - HTTP request containing application details

**Returns:** Created QuarterApplication model instance

### verifyRequester(Request $request)
Verifies if a requester is valid based on NIC and contact number.

**Parameters:**
- `$request` - HTTP request containing NIC and contact number

**Returns:** JSON response with verification status

**Route:** POST `/quarters/verify-requester`

### allocateScheduledQuarter(Request $request, $id)
Allocates a scheduled quarter to an application or handles verification.

**Parameters:**
- `$request` - HTTP request containing allocation details
- `$id` - The application ID to allocate

**Authorization:** Government Agent, Administrative Officer, or Additional Government Agent

**Returns:** JSON response or redirect response

**Route:** POST `/quarters/scheduled/{id}/allocate`

### downloadPdf(string $applicationId)
Downloads an application as a PDF.

**Parameters:**
- `$applicationId` - The application ID to download

**Returns:** PDF download response

**Route:** GET `/quarters/{applicationId}/download`

### showQuarterHistory()
Shows the history of processed quarter applications.

**Returns:** JSON response with processed applications

**Route:** GET `/quarters/history`

### showProcessedScheduled($id)
Shows processed scheduled quarter application details.

**Parameters:**
- `$id` - The application ID to show

**Returns:** `\Illuminate\View\View`

**Route:** GET `/quarters/scheduled/{id}/processed`

**View:** `showprocessedscheduled`

### showProcessedFamily($id)
Shows processed family quarter application details.

**Parameters:**
- `$id` - The application ID to show

**Returns:** `\Illuminate\View\View`

**Route:** GET `/quarters/family/{id}/processed`

**View:** `showprocessedfamily`

### restoreQuarterApplication(Request $request, $id)
Restores a rejected quarter application to pending status.

**Parameters:**
- `$request` - HTTP request containing restoration details
- `$id` - The application ID to restore

**Authorization:** Government Agent, Additional Government Agent, or Administrative Officer

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** POST `/quarters/{id}/restore`

### cancelScheduledQuarter(Request $request, $id)
Cancels a scheduled quarter allocation.

**Parameters:**
- `$request` - HTTP request containing cancellation details
- `$id` - The application ID to cancel

**Authorization:** Government Agent only

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** POST `/quarters/scheduled/{id}/cancel`

### cancelFamilyQuarter(Request $request, $id)
Cancels a family quarter allocation.

**Parameters:**
- `$request` - HTTP request containing cancellation details
- `$id` - The application ID to cancel

**Authorization:** Government Agent only

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** POST `/quarters/family/{id}/cancel`

### deleteScheduledQuarterApplication($id)
Deletes a scheduled quarter application.

**Parameters:**
- `$id` - The application ID to delete

**Authorization:** Requester or Administrative Officer with appropriate conditions

**Returns:** JSON response

**Route:** DELETE `/quarters/scheduled/{id}/delete`

### deleteFamilyQuarterApplication($id)
Deletes a family quarter application.

**Parameters:**
- `$id` - The application ID to delete

**Authorization:** Requester or Administrative Officer with appropriate conditions

**Returns:** JSON response

**Route:** DELETE `/quarters/family/{id}/delete`

### clearRejectedScheduledApplications()
Clears all rejected scheduled quarter applications.

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** POST `/quarters/scheduled/clear-rejected`

### clearRejectedFamilyApplications()
Clears all rejected family quarter applications.

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** POST `/quarters/family/clear-rejected`

## Key Features

1. **Dual Quarter Types:** Supports both family and scheduled quarter applications
2. **Marking System:** Implements a comprehensive marking system for family quarters
3. **Multi-Level Approval:** AO → AGA → GA approval workflow
4. **Gender Compatibility:** Enforces gender compatibility for quarters
5. **Occupancy Management:** Tracks and manages quarter occupancy limits
6. **Grade Calculation:** Automatically calculates service grade based on salary
7. **PDF Generation:** Applications can be downloaded as PDFs
8. **Audit Logging:** All actions are logged for accountability
9. **Role-Based Access Control:** Different permissions for different user types
10. **Flexible Allocation:** Supports both automatic and manual allocation processes
11. **Restoration Capability:** Allows restoration of rejected applications
12. **Cancellation Support:** Enables cancellation of allocated quarters