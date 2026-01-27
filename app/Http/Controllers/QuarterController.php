<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quarter;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;
use App\Models\QuarterApplication;
use App\Models\FamilyQuarterApplication;
use App\Models\MarkingFamilyQuarter;
use App\Models\QuarterAllocation;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class QuarterController extends Controller
{
    public function create()
    {
        $quarters = Quarter::all();
        return view('bookquarter', ['quarters' => $quarters]);
    }
    /**
     * Store a newly created quarter in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'quarter_type' => ['required', Rule::in(['Family', 'Scheduled'])],
            'service_grade' => ['nullable', Rule::in(['1', '2', '3', '4', '5', '5A'])],
            'status' => ['required', Rule::in(['Unallocated', 'Allocated', 'Repair', 'Demolished'])],
            'old_quarter_no' => 'nullable|string|max:50',
            'new_quarter_no' => 'nullable|string|max:50',
            'location' => 'required|string|max:100',
            'occupant_number' => 'nullable|integer',
            'allowed_gender' => ['nullable', Rule::in(['Male', 'Female'])],
            'special_notice' => 'nullable|string',
            'current_occupant_number' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            Log::error('Quarter creation validation failed', $validator->errors()->toArray());
            return redirect()->route('addquarter')
                        ->withErrors($validator)
                        ->withInput();
        }

        // Create the quarter
        try {
            // Generate the custom primary key
            $lastQuarter = Quarter::orderBy('quarter_id', 'desc')->first();
            $nextIdNumber = 1;
            if ($lastQuarter) {
                if (preg_match('/(\d+)$/', $lastQuarter->quarter_id, $matches)) {
                    $lastIdNumber = (int) $matches[1];
                    $nextIdNumber = $lastIdNumber + 1;
                }
            }
            $newQuarterId = 'quarter' . str_pad($nextIdNumber, 3, '0', STR_PAD_LEFT);

            $data = $request->only([
                'old_quarter_no',
                'new_quarter_no',
                'quarter_type',
                'service_grade',
                'status',
                'location',
                'occupant_number',
                'allowed_gender',
                'special_notice',
                'current_occupant_number',
            ]);
            $data['quarter_id'] = $newQuarterId;

            $data['occupant_number'] = $data['occupant_number'] ?? 0;
            $data['current_occupant_number'] = $data['current_occupant_number'] ?? 0;

            $data['date_created'] = now();
            $data['date_modified'] = now();

            Quarter::create($data);

            AuditLog::create([
                'log_title' => 'Added New Quarter ' . $newQuarterId,
                'performed_by' => Auth::id(),
                'date_performed' => Carbon::now()->toDateString(),
                'time_performed' => Carbon::now()->toTimeString(),
            ]);

            return redirect()->route('quarters.index')->with('success', 'Quarter added successfully with ID ' . $newQuarterId . '!');
        
        } catch (QueryException $e) {
            Log::error('Failed to create quarter (QueryException): ' . $e->getMessage());
            
            // Provide a more specific error message
            $errorMessage = 'Database error: Failed to add quarter.';
            if (str_contains($e->getMessage(), 'Unknown column')) {
                $errorMessage .= ' Please ensure database migrations are up to date.';
            } else if (str_contains($e->getMessage(), 'Incorrect integer value')) {
                $errorMessage .= ' The `quarter_id` might be incorrectly configured. Please ensure migrations have run.';
            } else {
                $errorMessage .= ' Check logs for details.';
            }

            return redirect()->route('addquarter')
                        ->with('error', $errorMessage)
                        ->withInput();

        } catch (\Exception $e) {
            Log::error('Failed to create quarter (Exception): ' . $e);
            
            return redirect()->route('addquarter')
                        ->with('error', 'An unexpected error occurred. Failed to add quarter.')
                        ->withInput();
        }
    }

    public function index()
    {
        $quarters = Quarter::all();
        return view('quarters', ['quarters' => $quarters]);
    }

    public function edit(Quarter $quarter)
    {
        return view('modifyquarter', ['quarter' => $quarter]);
    }

    public function update(Request $request, Quarter $quarter)
    {
        $request->validate([
            'quarter_type' => ['required', Rule::in(['Family', 'Scheduled'])],
            'service_grade' => ['nullable', Rule::in(['1', '2', '3', '4', '5', '5A'])],
            'status' => ['required', Rule::in(['Unallocated', 'Allocated', 'Repair', 'Demolished'])],
            'old_quarter_no' => 'nullable|string|max:50',
            'new_quarter_no' => 'nullable|string|max:50',
            'location' => 'required|string|max:100',
            'occupant_number' => 'nullable|integer',
            'allowed_gender' => ['nullable', Rule::in(['Male', 'Female'])],
            'special_notice' => 'nullable|string',
            'current_occupant_number' => 'nullable|integer',
        ]);

        $quarter->update([
            'quarter_type' => $request->quarter_type,
            'service_grade' => $request->service_grade,
            'status' => $request->status,
            'old_quarter_no' => $request->old_quarter_no,
            'new_quarter_no' => $request->new_quarter_no,
            'location' => $request->location,
            'occupant_number' => $request->occupant_number ?? 0,
            'allowed_gender' => $request->allowed_gender,
            'special_notice' => $request->special_notice,
            'current_occupant_number' => $request->current_occupant_number ?? 0,
            'date_modified' => Carbon::now(),
        ]);

        AuditLog::create([
            'log_title' => 'Modified Quarter ' . $quarter->quarter_id,
            'performed_by' => Auth::id(),
            'date_performed' => Carbon::now()->toDateString(),
            'time_performed' => Carbon::now()->toTimeString(),
        ]);

        return redirect()->route('quarters.index')->with('success', 'Quarter updated successfully!');
    }

    public function destroy(Quarter $quarter)
    {
        try {
            $quarterId = $quarter->quarter_id;
            $quarter->delete();

            AuditLog::create([
                'log_title' => 'Deleted Quarter ' . $quarterId,
                'performed_by' => Auth::id(),
                'date_performed' => Carbon::now()->toDateString(),
                'time_performed' => Carbon::now()->toTimeString(),
            ]);

            return redirect()->route('quarters.index')->with('success', 'Quarter deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete quarter: ' . $e->getMessage());
            return redirect()->route('quarters.index')->with('error', 'Failed to delete quarter.');
        }
    }

    public function seeQuarters()
    {
        $quarters = Quarter::all();
        return view('seequarters', ['quarters' => $quarters]);
    }

    public function showOccupantDetails()
    {
        // For now, no data is passed as we don't have a way to get occupant info
        return view('occupantdetails');
    }

    public function bookFamilyQuarters()
    {
        return view('familyquarter');
    }

    public function bookScheduledQuarters()
    {
        return view('scheduledquarter');
    }

    public function storeFamilyQuarters(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'officer_name' => 'required|string|max:255',
            'nic' => 'required|string|max:20|unique:quarter_application,nic',
            'dob' => 'required|date',
            'designation' => 'required|string|max:100',
            'gender' => ['required', Rule::in(['Male', 'Female'])],
            'service_and_grade' => ['required', Rule::in(['1', '2', '3', '4', '5', '5A'])],
            'permanent_address' => 'required|string|max:1200',
            'temporary_address' => 'nullable|string|max:1200',
            'phone_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'monthly_salary' => 'required|numeric',
            'f_date_of_last_salary_increment' => 'required|date',
            'date_of_assumption_of_duties' => 'required|date',
            'f_transformed_officer' => 'nullable|string',
            'f_marital_status' => ['nullable', Rule::in(['Married', 'Widowed', 'Divorced', 'Separated'])],
            'f_is_spouse_employed' => 'nullable|boolean',
            'f_spouse_designation' => 'nullable|string|max:100',
            'f_spouse_department_office' => 'nullable|string|max:255',
            'f_spouse_monthly_salary' => 'nullable|numeric',
            'f_spouse_last_increment_date' => 'nullable|date',
            'f_children_details_description' => 'nullable|string|max:2000',
            'f_property_ownership_details' => 'nullable|string|max:2000',
            'f_previous_government_quarter_duration' => 'nullable|integer',
            'marking_f_department' => ['required', Rule::in(['Officers_attached_under_the_Ministry_of_Home_Affairs', 'Officers_attached_to_District_and_Divisional_Secretariats', 'Other_Officers'])],
            'number_of_dependant' => ['required', Rule::in(['01_person', '02_person', '03_person', '04_person', '05_or_above_05_person'])],
            'is_dependant_with_disability' => 'required|boolean',
            'f_distance_of_residency' => ['required', Rule::in(['Out_District_above_100km', 'Out_District_between_51km_and_100km', 'Out_District_between_26km_and_50km', 'Out_District_below_25km', 'Out_of_Urban_Council_Area_above_30km', 'Out_of_Urban_Council_Area_between_00km_and_30km'])],
            'filled_by_nic' => 'required|string',
            'filled_by_phone' => 'required|string',
            'confirm_details' => 'required|accepted',
            'f_spacial_reason' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return redirect()->route('familyquarter')
                        ->withErrors($validator)
                        ->withInput();
        }

        DB::beginTransaction();
        try {
            // 1. Create QuarterApplication
            $application_id = 'QA' . Str::uuid();
            $quarterApplication = QuarterApplication::create([
                'application_id' => $application_id,
                'quarter_type' => 'Family',
                'officer_name' => $request->officer_name,
                'gender' => $request->gender,
                'nic' => $request->nic,
                'designation' => $request->designation,
                'service_grade' => $request->service_and_grade,
                'permanent_address' => $request->permanent_address,
                'temporary_address' => $request->temporary_address,
                'monthly_salary' => $request->monthly_salary,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'date_of_assumption_of_duties' => $request->date_of_assumption_of_duties,
                'date_created' => Carbon::now(),
                'date_modified' => Carbon::now(),
            ]);

            // 2. Create FamilyQuarterApplication
            $f_application_id = 'FQA' . Str::uuid();
            FamilyQuarterApplication::create([
                'f_application_id' => $f_application_id,
                'application_id' => $application_id,
                'f_dob' => $request->dob,
                'f_date_of_last_salary_increment' => $request->f_date_of_last_salary_increment,
                'f_marital_status' => $request->f_marital_status,
                'f_is_spouse_employed' => $request->f_is_spouse_employed,
                'f_spouse_designation' => $request->f_spouse_designation,
                'f_spouse_department_office' => $request->f_spouse_department_office,
                'f_spouse_monthly_salary' => $request->f_spouse_monthly_salary,
                'f_spouse_last_increment_date' => $request->f_spouse_last_increment_date,
                'f_children_details_description' => $request->f_children_details_description,
                'f_property_ownership_details' => $request->f_property_ownership_details,
                'f_previous_government_quarter_duration' => $request->f_previous_government_quarter_duration,
                'f_transformed_officer' => $request->f_transformed_officer,
            ]);

            // 3. Create MarkingFamilyQuarter
            $total_mark = $this->calculateFamilyQuarterMark($request);
            $marking_data = [
                'f_application_id' => $f_application_id,
                'f_department' => $request->marking_f_department,
                'f_years_since_application_created' => 0, // Default value as requested
                'f_number_of_dependant' => $request->number_of_dependant,
                'is_dependant_with_disability' => $request->is_dependant_with_disability,
                'f_distance_of_residency' => $request->f_distance_of_residency,
                'f_spacial_reason' => $request->f_spacial_reason,
                'total_mark' => $total_mark,
                'date_calculated' => Carbon::now(),
            ];
            Log::info('Attempting to create MarkingFamilyQuarter with data:', $marking_data);
            MarkingFamilyQuarter::create($marking_data);

            // 4. Create QuarterAllocation
            QuarterAllocation::create([
                'application_id' => $application_id,
                'quarter_id' => null, // quarter_id is now nullable
                'allocation_status' => 'pending',
            ]);
            
            AuditLog::create([
                'log_title' => 'New Family Quarter Application Submitted: ' . $application_id,
                'performed_by' => Auth::check() ? Auth::id() : null,
                'details' => 'Requester NIC: ' . $request->filled_by_nic,
                'date_performed' => Carbon::now()->toDateString(),
                'time_performed' => Carbon::now()->toTimeString(),
            ]);

            DB::commit();
            return redirect()->route('bookquarter')->with('success', 'Family quarter application submitted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Family Quarter Application Submission Failed: ' . $e->getMessage());
            return redirect()->route('familyquarter')->with('error', 'An unexpected error occurred. Failed to submit application.');
        }
    }

    public function verifyRequester(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nic_number' => 'required|string|max:50',
            'contact_number' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $user = User::where('nic_number', $request->nic_number)
                               ->where('contact_number', $request->contact_number)
                               ->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Invalid NIC or Contact Number.']);
        }

        if (!$user->hasPermissionTo('requester')) {
            return response()->json(['success' => false, 'message' => 'You do not have permission to make this request.']);
        }

        return response()->json(['success' => true, 'message' => 'Requester verified successfully.']);
    }

    private function calculateFamilyQuarterMark(Request $request)
    {
        $total_mark = 0;

        // Department marks
        $department_marks = [
            'Officers_attached_under_the_Ministry_of_Home_Affairs' => 30,
            'Officers_attached_to_District_and_Divisional_Secretariats' => 25,
            'Other_Officers' => 20,
        ];
        $total_mark += $department_marks[$request->marking_f_department] ?? 0;

        // Dependant marks
        $dependant_marks = [
            '01_person' => 5,
            '02_person' => 10,
            '03_person' => 15,
            '04_person' => 20,
            '05_or_above_05_person' => 25,
        ];
        $total_mark += $dependant_marks[$request->number_of_dependant] ?? 0;

        // Disability mark
        if ($request->is_dependant_with_disability == '1') { // 1 for Yes
            $total_mark += 10;
        }

        // Distance marks
        $distance_marks = [
            'Out_District_above_100km' => 25,
            'Out_District_between_51km_and_100km' => 20,
            'Out_District_between_26km_and_50km' => 15,
            'Out_District_below_25km' => 10,
            'Out_of_Urban_Council_Area_above_30km' => 5,
            'Out_of_Urban_Council_Area_between_00km_and_30km' => 0,
        ];
        $total_mark += $distance_marks[$request->f_distance_of_residency] ?? 0;

        return $total_mark;
    }

    public function markingScheme()
    {
        return view('markingscheme');
    }

    public function updateMarkingScheme(Request $request)
    {
        // For now, we'll just redirect back with a success message.
        // Later, you can add the logic to update the marking scheme in the database.
        return redirect()->route('marking-scheme.edit')->with('success', 'Marking scheme updated successfully!');
    }
}
