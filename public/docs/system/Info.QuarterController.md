# QuarterController Documentation

## Overview
The `QuarterController` manages all aspects of quarter management including creating, viewing, editing, and deleting quarters. It handles the complete lifecycle of quarter records in the system and provides interfaces for quarter allocation.

## Namespace
```php
namespace App\Http\Controllers;
```

## Dependencies
- `Illuminate\Database\Eloquent\ModelNotFoundException`
- `Illuminate\Http\Request`
- `App\Models\Quarter`
- `App\Models\AuditLog`
- `Illuminate\Support\Facades\Auth`
- `Illuminate\Support\Facades\Validator`
- `Illuminate\Support\Facades\Log`
- `Carbon\Carbon`
- `Illuminate\Database\QueryException`
- `Illuminate\Validation\Rule`
- `App\Models\QuarterApplication`
- `App\Models\FamilyQuarterApplication`
- `App\Models\MarkingFamilyQuarter`
- `App\Models\QuarterAllocation`
- `App\Models\ScheduledQuarterApplication`
- `App\Models\GradeSalarySetting`
- `App\Models\User`
- `Illuminate\Support\Str`
- `Illuminate\Support\Facades\DB`

## Methods

### create()
Shows the form for creating or viewing quarters.

**Returns:** `\Illuminate\View\View`

**Route:** GET `/quarters/create`

**View:** `bookquarter`

### store(Request $request)
Stores a newly created quarter in storage.

**Parameters:** 
- `$request` - HTTP request containing quarter details

**Validation rules:**
- `quarter_type` - required, must be 'Family' or 'Scheduled'
- `service_grade` - optional, must be in ['1', '2', '3', '4', '5', '5A']
- `status` - required, must be in ['Unallocated', 'Allocated', 'Repair', 'Demolished']
- `old_quarter_no` - optional, string, max:50
- `new_quarter_no` - optional, string, max:50
- `location` - required, string, max:100
- `occupant_number` - optional, integer
- `allowed_gender` - optional, must be 'Male' or 'Female'
- `special_notice` - optional, string
- `current_occupant_number` - optional, integer

**Returns:** `\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse`

**Route:** POST `/quarters`

### index()
Displays a listing of the quarters.

**Returns:** `\Illuminate\View\View`

**Route:** GET `/quarters`

**View:** `quarters`

### edit(Quarter $quarter)
Shows the form for editing the specified quarter.

**Parameters:**
- `$quarter` - The quarter instance to edit

**Returns:** `\Illuminate\View\View`

**Route:** GET `/quarters/{quarter}/edit`

**View:** `modifyquarter`

### update(Request $request, Quarter $quarter)
Updates the specified quarter in storage.

**Parameters:**
- `$request` - HTTP request containing updated quarter details
- `$quarter` - The quarter instance to update

**Validation rules:**
- `quarter_type` - required, must be 'Family' or 'Scheduled'
- `service_grade` - optional, must be in ['1', '2', '3', '4', '5', '5A']
- `status` - required, must be in ['Unallocated', 'Allocated', 'Repair', 'Demolished']
- `old_quarter_no` - optional, string, max:50
- `new_quarter_no` - optional, string, max:50
- `location` - required, string, max:100
- `occupant_number` - optional, integer
- `allowed_gender` - optional, must be 'Male' or 'Female'
- `special_notice` - optional, string
- `current_occupant_number` - optional, integer

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** PUT `/quarters/{quarter}`

### destroy(Request $request, Quarter $quarter)
Removes the specified quarter from storage.

**Parameters:**
- `$request` - HTTP request
- `$quarter` - The quarter instance to delete

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** DELETE `/quarters/{quarter}`

### seeQuarters()
Displays a listing of the quarters for viewing by users.

**Returns:** `\Illuminate\View\View`

**Route:** GET `/quarters/view`

**View:** `seequarters`

### showOccupantDetails()
Displays details of occupied quarters with their allocations and application information.

**Returns:** `\Illuminate\View\View`

**Route:** GET `/quarters/occupants`

**View:** `occupantdetails`

### createQuarterApplication(Request $request)
Creates a new quarter application record.

**Parameters:**
- `$request` - HTTP request containing application details

**Returns:** Created QuarterApplication model instance

## Key Features

1. **Automatic ID Generation:** Generates unique quarter IDs in the format 'quarterXXX'
2. **Quarter Type Management:** Supports both Family and Scheduled quarter types
3. **Gender Compatibility:** Enforces gender restrictions for quarters
4. **Occupancy Tracking:** Manages current and maximum occupant numbers
5. **Service Grade Association:** Links quarters to specific service grades
6. **Audit Logging:** All quarter operations are logged for accountability
7. **Flexible Status Management:** Supports various quarter states (Unallocated, Allocated, Repair, Demolished)
8. **Grade Salary Integration:** Updates grade salary settings when quarters are created
9. **JSON Response Support:** Can return JSON responses for API-like interactions
10. **Comprehensive Validation:** Validates all quarter attributes before saving
11. **Occupant Details View:** Shows detailed information about occupied quarters