<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuarterApplication;
use App\Models\FamilyQuarterApplication;
use App\Models\MarkingFamilyQuarter;
use App\Models\ScheduledQuarterApplication;
use App\Models\QuarterAllocation;
use App\Models\AuditLog;
use App\Models\MarkingScheme;
use App\Models\Quarter;
use App\Models\User;
use App\Models\GradeSalarySetting;
use App\Services\MarkingCalculatorService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Str;

class QuarterAllocationController extends Controller
{
    public function bookFamilyQuarters()
    {
        return view('familyquarter');
    }

    public function showFamilyQuarterReview($id)
    {
        $application = QuarterApplication::with([
            'familyQuarterApplication.markingFamilyQuarter',
            'quarterAllocation'
        ])->where('application_id', $id)->firstOrFail();

        // 1. Fetch all GradeSalarySetting records
        $gradeSalarySettings = \App\Models\GradeSalarySetting::all();
        $calculatedGrade = 'N/A';

        // 2. Implement logic to determine the calculatedGrade
        $applicantMonthlySalary = $application->monthly_salary;

        if ($applicantMonthlySalary !== null) {
            foreach ($gradeSalarySettings as $setting) {
                if ($applicantMonthlySalary >= $setting->min_salary && $applicantMonthlySalary <= $setting->max_salary) {
                    $calculatedGrade = $setting->grade;
                    break;
                }
            }
        }

        // 3. Retrieve available quarters based on criteria
        $quarterQuery = Quarter::where('quarter_type', 'Family');

        // Apply gender filter if application has gender
        if (!empty($application->gender)) {
            $quarterQuery->where(function ($query) use ($application) {
                $query->whereNull('allowed_gender')
                    ->orWhere('allowed_gender', $application->gender);
            });
        }

        // Apply service grade filter if application has service grade
        if (!empty($application->service_grade)) {
            $quarterQuery->where(function ($query) use ($application) {
                $query->whereNull('service_grade')
                    ->orWhere('service_grade', $application->service_grade);
            });
        }

        // Apply availability filter
        $quarterQuery->where(function ($query) {
            $query->where('status', 'Unallocated')
                ->orWhereRaw('occupant_number > current_occupant_number');
        });

        $availableQuarters = $quarterQuery->get();

        // Calculate marking breakdown dynamically
        $markingCalculator = new MarkingCalculatorService();
        $markingBreakdown = $markingCalculator->calculateDynamicScore($application->familyQuarterApplication);

        return view('familyreview', [
            'application' => $application,
            'calculatedGrade' => $calculatedGrade,
            'gradeSalarySettings' => $gradeSalarySettings,
            'availableQuarters' => $availableQuarters,
            'markingBreakdown' => $markingBreakdown,
        ]);
    }

    public function allocateFamilyQuarter(Request $request, $id)
    {
        $application = QuarterApplication::with('quarterAllocation')->where('application_id', $id)->firstOrFail();
        if (!$application->quarterAllocation) {
            return redirect()->back()->with('error', 'Application allocation record not found.');
        }

        $user = Auth::user();

        // Authorization check
        if (!$user->hasPermissionTo('government_agent_approval')) {
            return redirect()->back()->with('error', 'You do not have permission to allocate quarters.');
        }

        DB::beginTransaction();
        try {
            // 1. Validation
            $validated = $request->validate([
                'ga_approval_status' => 'required|in:1',
                'selected_quarter' => 'required|exists:quarters,quarter_id',
                'ga_note' => 'nullable|string|max:2000',
                'f_special_reason' => 'nullable|string|max:2000',
                'f_special_reason_marks' => 'nullable|integer|min:0|max:10',
            ], [
                'ga_approval_status.required' => 'GA approval status is required.',
                'ga_approval_status.in' => 'GA approval must be set to "Yes" to allocate.',
                'selected_quarter.required' => 'You must select a quarter to allocate.',
                'f_special_reason_marks.max' => 'Special reason marks cannot exceed 10.',
            ]);

            $quarterAllocation = $application->quarterAllocation;

            // 2. Fetch Selected Quarter & Check Availability
            $quarter = Quarter::findOrFail($request->selected_quarter);

            // Availability Check: Only checking status as per simple family rule
            if ($quarter->status !== 'Unallocated') {
                return redirect()->back()->with('error', 'The selected quarter is already allocated.');
            }

            // 3. Update Quarter Allocation Record
            $quarterAllocation->allocation_status = 'allocated';
            $quarterAllocation->allocation_date = Carbon::now();
            $quarterAllocation->vacate_date = Carbon::now()->addYears(5);
            $quarterAllocation->quarter_id = $request->selected_quarter; // ASSIGN QUARTER ID

            if ($request->ga_note) {
                $quarterAllocation->ga_note = $request->ga_note;
            }

            // 4. Update Special Reason Fields (Marking)
            if ($application->familyQuarterApplication && $application->familyQuarterApplication->markingFamilyQuarter) {
                $marking = $application->familyQuarterApplication->markingFamilyQuarter;

                if ($request->has('f_special_reason')) {
                    $marking->f_special_reason = $request->f_special_reason;
                }

                if ($request->has('f_special_reason_marks')) {
                    $marking->f_special_reason_marks = $request->f_special_reason_marks;
                }
                $marking->save();
            }

            // 5. Update Quarter Status (No occupancy increment)
            $quarter->status = 'Allocated';
            $quarter->save();

            $quarterAllocation->save();

            AuditLog::create([
                'log_title' => 'GA Allocated Family Quarter',
                'performed_by' => $user->id,
                'details' => "App ID: {$id} - Assigned Quarter: {$quarter->quarter_id}",
                'date_performed' => Carbon::now()->toDateString(),
                'time_performed' => Carbon::now()->toTimeString(),
            ]);

            DB::commit();

            $successMessage = 'Family quarter allocated successfully to ' . $quarter->quarter_id;
            return redirect()->route('dashboard')->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Family Quarter Allocation Failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    public function rejectFamilyQuarter(Request $request, $id)
    {
        $application = QuarterApplication::with('quarterAllocation')->where('application_id', $id)->firstOrFail();
        if (!$application->quarterAllocation) {
            return redirect()->back()->with('error', 'Application allocation record not found.');
        }

        $user = Auth::user();

        // Authorization check
        if (!$user->hasPermissionTo('government_agent_approval')) {
            return redirect()->back()->with('error', 'You do not have permission to reject applications.');
        }

        DB::beginTransaction();
        try {
            // Validation: GA Note is mandatory
            $request->validate([
                'ga_note' => 'required|string|max:2000',
            ], [
                'ga_note.required' => 'A rejection note is required.',
            ]);

            $quarterAllocation = $application->quarterAllocation;

            // 1. Update Allocation Record
            $quarterAllocation->allocation_status = 'rejected';

            // Save old quarter ID to free it up
            $quarterId = $quarterAllocation->quarter_id;

            // Clear assignment details
            $quarterAllocation->quarter_id = null;
            $quarterAllocation->allocation_date = null;
            $quarterAllocation->vacate_date = null;

            $quarterAllocation->ga_note = trim(($quarterAllocation->ga_note ?? '') . "\n" . $request->ga_note);

            // Update special reason marks if provided
            if ($application->familyQuarterApplication && $application->familyQuarterApplication->markingFamilyQuarter) {
                if ($request->has('f_special_reason_marks')) {
                    $application->familyQuarterApplication->markingFamilyQuarter->f_special_reason_marks = $request->f_special_reason_marks;
                    $application->familyQuarterApplication->markingFamilyQuarter->save();
                }
            }

            $quarterAllocation->save();

            // 2. Update Quarter Status (No occupancy decrement)
            if ($quarterId) {
                $quarter = Quarter::find($quarterId);
                // Only reset status if it exists
                if ($quarter) {
                    $quarter->status = 'Unallocated';
                    $quarter->save();
                }
            }

            AuditLog::create([
                'log_title' => 'GA Rejected Family Application',
                'performed_by' => $user->id,
                'details' => "App ID: {$id} - Rejected",
                'date_performed' => Carbon::now()->toDateString(),
                'time_performed' => Carbon::now()->toTimeString(),
            ]);

            DB::commit();

            return redirect()->route('dashboard')->with('success', 'Application rejected successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Family Quarter Rejection Failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    public function updateFamilyQuarterReview(Request $request, $id)
    {
        $application = QuarterApplication::with('quarterAllocation')->where('application_id', $id)->firstOrFail();
        if (!$application->quarterAllocation) {
            return redirect()->back()->with('error', 'Application allocation record not found.');
        }

        $user = Auth::user();
        $quarterAllocation = $application->quarterAllocation;

        DB::beginTransaction();
        try {
            $action = $request->input('action');
            Log::info('Family Review Action: ' . $action, ['user_id' => $user->id, 'inputs' => $request->all()]);

            $successMessage = 'Application update submitted successfully.';

            // Handling Submit Action (AO/AGA Verification)
            // Note: Allocation and Rejection are now handled by separate methods
            if ($action === 'Submit') {
                $validated = $request->validate([
                    'ao_verified_status' => 'nullable|in:0,1',
                    'aga_verified_status' => 'nullable|in:0,1',
                    'ao_note' => 'nullable|string',
                    'aga_note' => 'nullable|string',
                ]);

                if ($user->hasPermissionTo('administrative_officer_approval') && $request->has('ao_verified_status')) {
                    $quarterAllocation->is_ao_verified = $validated['ao_verified_status'];
                    $quarterAllocation->ao_note = $request->ao_note;
                    AuditLog::create([
                        'log_title' => 'AO Reviewed Family App',
                        'performed_by' => $user->id,
                        'details' => "App ID: {$id}, Verified: " . ($validated['ao_verified_status'] ? 'Yes' : 'No'),
                        'date_performed' => Carbon::now()->toDateString(),
                        'time_performed' => Carbon::now()->toTimeString(),
                    ]);
                }

                if ($user->hasPermissionTo('additional_government_agent_approval') && $request->has('aga_verified_status')) {
                    $quarterAllocation->is_aga_verified = $validated['aga_verified_status'];
                    $quarterAllocation->aga_note = $request->aga_note;
                    AuditLog::create([
                        'log_title' => 'AGA Reviewed Family App',
                        'performed_by' => $user->id,
                        'details' => "App ID: {$id}, Verified: " . ($validated['aga_verified_status'] ? 'Yes' : 'No'),
                        'date_performed' => Carbon::now()->toDateString(),
                        'time_performed' => Carbon::now()->toTimeString(),
                    ]);
                }
            } elseif ($action === 'Reject' && $user->hasPermissionTo('additional_government_agent_approval')) {
                // Keep AGA Reject here as it's separate from GA Reject
                $quarterAllocation->allocation_status = 'rejected';
                if ($request->aga_note) {
                    $quarterAllocation->aga_note = trim(($quarterAllocation->aga_note ?? '') . "\n" . $request->aga_note);
                }
                $successMessage = 'Application rejected successfully.';
                AuditLog::create([
                    'log_title' => 'AGA Rejected Family Application',
                    'performed_by' => $user->id,
                    'details' => "App ID: {$id} - Rejected",
                    'date_performed' => Carbon::now()->toDateString(),
                    'time_performed' => Carbon::now()->toTimeString(),
                ]);
            } elseif ($action === 'Delete' && $user->hasPermissionTo('administrative_officer_approval')) {
                $quarterAllocation->is_ao_verified = null;
                $quarterAllocation->is_aga_verified = null;
                $quarterAllocation->allocation_status = 'pending';
                $successMessage = 'Verification reset successfully.';
                AuditLog::create([
                    'log_title' => 'AO Reset Family Application Verification',
                    'performed_by' => $user->id,
                    'details' => "App ID: {$id} - Verification reset",
                    'date_performed' => Carbon::now()->toDateString(),
                    'time_performed' => Carbon::now()->toTimeString(),
                ]);
            } elseif ($action === 'Cancel' && $user->hasPermissionTo('requester')) {
                if ($quarterAllocation->is_ao_verified === 0 && $quarterAllocation->is_aga_verified === 0 && $quarterAllocation->allocation_status === 'pending') {
                    $quarterAllocation->allocation_status = 'cancelled';
                    $successMessage = 'Application cancelled successfully.';
                    AuditLog::create([
                        'log_title' => 'Requester Cancelled Family Application',
                        'performed_by' => $user->id,
                        'details' => "App ID: {$id} - Cancelled",
                        'date_performed' => Carbon::now()->toDateString(),
                        'time_performed' => Carbon::now()->toTimeString(),
                    ]);
                } else {
                    return redirect()->back()->with('error', 'Cannot cancel application. Conditions not met.');
                }
            }

            $quarterAllocation->save();
            DB::commit();

            // Return JSON for AJAX requests, redirect for regular form submissions
            // Debug info removed


            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['status' => 'success', 'message' => $successMessage, 'redirect_url' => route('dashboard')]);
            }
            return redirect()->route('dashboard')->with('success', $successMessage);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Family Quarter Review Update Failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            // Return JSON for AJAX requests, redirect for regular form submissions
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'An unexpected error occurred: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    public function storeFamilyQuarters(Request $request)
    {
        $messages = [
            'nic.unique' => 'An application with this NIC has already been submitted. Please check your previous applications.',
            'confirm_details.required' => 'You must confirm that the details are correct.',
            'confirm_details.accepted' => 'You must confirm that the details are correct.',
        ];

        $validator = Validator::make($request->all(), [
            'officer_name' => 'required|string|max:255',
            'nic' => ['required', 'string', 'max:20', Rule::unique('quarter_application', 'nic')],
            'dob' => 'required|date',
            'designation' => 'required|string|max:100',
            'gender' => ['required', Rule::in(['Male', 'Female'])],
            'service_and_grade' => ['required', Rule::in(['1', '2', '3', '4', '5', '5A'])],
            'permanent_address' => 'required|string|max:1200',
            'phone_number' => 'required|string|max:20',
            'monthly_salary' => 'required|numeric',
            'f_date_of_last_salary_increment' => 'required|date',
            'date_of_assumption_of_duties' => 'required|date',
            'marking_f_department' => ['required', Rule::in(['Officers_attached_under_the_Ministry_of_Home_Affairs', 'Officers_attached_to_District_and_Divisional_Secretariats', 'Other_Officers'])],
            'number_of_dependant' => ['required', Rule::in(['01_person', '02_person', '03_person', '04_person', '05_or_above_05_person'])],
            'is_dependant_with_disability' => 'required|boolean',
            'f_distance_of_residency' => ['required', Rule::in(['Out_District_above_100km', 'Out_District_between_51km_and_100km', 'Out_District_between_26km_and_50km', 'Out_District_below_25km', 'Out_of_Urban_Council_Area_above_30km', 'Out_of_Urban_Council_Area_between_00km_and_30km'])],
            'filled_by_nic' => 'required|string',
            'filled_by_phone' => 'required|string',
            'confirm_details' => 'required|accepted',
        ], $messages);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Validation failed.', 'errors' => $validator->errors()], 422);
            }
            return redirect()->route('familyquarter')->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $quarterApplication = $this->createQuarterApplication($request);
            $familyQuarterApplication = $this->_createFamilyQuarterApplication($request, $quarterApplication->application_id);
            $this->_createMarkingFamilyQuarter($request, $familyQuarterApplication->f_application_id);
            $this->createQuarterAllocation($quarterApplication->application_id);

            AuditLog::create([
                'log_title' => 'New Family Quarter Application Submitted: ' . $quarterApplication->application_id,
                'performed_by' => Auth::check() ? Auth::id() : null,
                'details' => 'Requester NIC: ' . $request->filled_by_nic,
                'date_performed' => Carbon::now()->toDateString(),
                'time_performed' => Carbon::now()->toTimeString(),
            ]);

            DB::commit();

            $successMessage = 'Family quarter application submitted successfully!';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => $successMessage]);
            }
            return redirect()->route('bookquarter')->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Family Quarter Application Submission Failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            $errorMessage = 'An unexpected error occurred. Failed to submit application.';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $errorMessage], 500);
            }
            return redirect()->route('familyquarter')->with('error', $errorMessage);
        }
    }

    private function _createFamilyQuarterApplication(Request $request, $application_id)
    {
        return FamilyQuarterApplication::create([
            'f_application_id' => 'FQA' . Str::uuid(),
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
    }

    private function _createMarkingFamilyQuarter(Request $request, $f_application_id)
    {
        return MarkingFamilyQuarter::create([
            'f_application_id' => $f_application_id,
            'f_department' => $request->marking_f_department,
            'f_number_of_dependant' => $request->number_of_dependant,
            'is_dependant_with_disability' => $request->is_dependant_with_disability,
            'f_distance_of_residency' => $request->f_distance_of_residency,
            'f_special_reason' => $request->f_special_reason,
        ]);
    }



    public function markingScheme()
    {
        $marking_schemes = MarkingScheme::all()->groupBy('marking_title');
        return view('markingscheme', compact('marking_schemes'));
    }

    public function updateMarkingScheme(Request $request)
    {
        $request->validate([
            'marks' => 'required|array',
            'marks.*' => 'required|numeric|min:0',
        ]);

        foreach ($request->marks as $option => $mark) {
            MarkingScheme::where('marking_option', $option)->update([
                'defined_mark' => $mark,
                'date_modified' => Carbon::now(),
            ]);
        }

        return redirect()->route('marking-scheme.edit')->with('success', 'Marking scheme updated successfully!');
    }

    public function bookScheduledQuarters()
    {
        return view('scheduledquarter');
    }

    public function storeScheduledQuarters(Request $request)
    {
        $messages = [
            'nic.unique' => 'An application with this NIC has already been submitted for a scheduled quarter. Please check your previous applications.',
            'confirm_details.required' => 'You must confirm that the details are correct.',
            'confirm_details.accepted' => 'You must confirm that the details are correct.',
        ];

        $validator = Validator::make($request->all(), [
            'officer_name' => 'required|string|max:255',
            'nic' => [
                'required',
                'string',
                'max:20',
                Rule::unique('quarter_application', 'nic'),
            ],
            'designation' => 'required|string|max:100',
            'gender' => ['required', Rule::in(['Male', 'Female'])],
            'service_and_grade' => ['required', Rule::in(['1', '2', '3', '4', '5', '5A'])],
            'permanent_address' => 'required|string|max:1200',
            'temporary_address' => 'nullable|string|max:1200',
            'phone_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'monthly_salary' => 'required|numeric',
            'date_of_assumption_of_duties' => 'required|date',
            'sq_transfered_officer_priority_request' => 'nullable|string|max:2000',
            'sq_night_duty_priority_request' => 'nullable|string|max:2000',
            'sq_other_special_reason_priority_request' => 'nullable|string|max:2000',
            'sq_property_ownership_details' => 'nullable|string|max:2000',
            'filled_by_nic' => 'required|string',
            'filled_by_phone' => 'required|string',
            'confirm_details' => 'required|accepted',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->route('scheduledquarter')
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $quarterApplication = $this->createQuarterApplicationForScheduled($request);
            $this->_createScheduledQuarterApplication($request, $quarterApplication->application_id);
            $this->createQuarterAllocation($quarterApplication->application_id);

            AuditLog::create([
                'log_title' => 'New Scheduled Quarter Application Submitted: ' . $quarterApplication->application_id,
                'performed_by' => Auth::check() ? Auth::id() : null,
                'details' => 'Requester NIC: ' . $request->filled_by_nic,
                'date_performed' => Carbon::now()->toDateString(),
                'time_performed' => Carbon::now()->toTimeString(),
            ]);

            DB::commit();
            return redirect()->route('bookquarter')->with('success', 'Scheduled quarter application submitted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Scheduled Quarter Application Submission Failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('scheduledquarter')->with('error', 'An unexpected error occurred. Failed to submit application. Please check the logs.');
        }
    }

    private function _createScheduledQuarterApplication(Request $request, $application_id)
    {
        return ScheduledQuarterApplication::create([
            'sq_application_id' => 'SQA' . Str::uuid(),
            'application_id' => $application_id,
            'sq_transfered_officer_priority_request' => $request->sq_transfered_officer_priority_request,
            'sq_night_duty_priority_request' => $request->sq_night_duty_priority_request,
            'sq_other_special_reason_priority_request' => $request->sq_other_special_reason_priority_request,
            'sq_property_ownership_details' => $request->sq_property_ownership_details,
        ]);
    }

    public function showScheduledQuarterReview($id)
    {
        $application = QuarterApplication::with([
            'scheduledQuarterApplication',
            'quarterAllocation'
        ])->where('application_id', $id)
            ->where('quarter_type', 'Scheduled') // Filter only scheduled quarter applications
            ->firstOrFail();

        // 1. Fetch all GradeSalarySetting records
        $gradeSalarySettings = GradeSalarySetting::all();
        $calculatedGrade = 'N/A';

        // 2. Implement logic to determine the calculatedGrade
        $applicantMonthlySalary = $application->monthly_salary;

        if ($applicantMonthlySalary !== null) {
            foreach ($gradeSalarySettings as $setting) {
                if ($applicantMonthlySalary >= $setting->min_salary && $applicantMonthlySalary <= $setting->max_salary) {
                    $calculatedGrade = $setting->grade;
                    break;
                }
            }
        }

        // 3. Retrieve available quarters based on criteria
        $quarterQuery = Quarter::where('quarter_type', 'Scheduled');

        // Apply gender filter if application has gender
        if (!empty($application->gender)) {
            $quarterQuery->where(function ($query) use ($application) {
                $query->whereNull('allowed_gender')
                    ->orWhere('allowed_gender', $application->gender);
            });
        }

        // Apply service grade filter if application has service grade
        if (!empty($application->service_grade)) {
            $quarterQuery->where(function ($query) use ($application) {
                $query->whereNull('service_grade')
                    ->orWhere('service_grade', $application->service_grade);
            });
        }

        // Apply availability filter
        $quarterQuery->where(function ($query) {
            $query->where('status', 'Unallocated')
                ->orWhereRaw('occupant_number > current_occupant_number');
        });

        $availableQuarters = $quarterQuery->get();

        return view('scheduledreview', compact('application', 'calculatedGrade', 'gradeSalarySettings', 'availableQuarters'));
    }

    public function createQuarterApplication(Request $request)
    {
        return QuarterApplication::create([
            'application_id' => 'QA' . Str::uuid(),
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
    }

    public function createQuarterAllocation($application_id)
    {
        return QuarterAllocation::create([
            'application_id' => $application_id,
            'quarter_id' => null,
            'allocation_status' => 'pending',
        ]);
    }

    public function createQuarterApplicationForScheduled(Request $request)
    {
        return QuarterApplication::create([
            'application_id' => 'QA' . Str::uuid(),
            'quarter_type' => 'Scheduled',
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
            ->whereHas('permissions', function ($query) {
                $query->where('requester', 1);
            })
            ->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Invalid NIC or Contact Number or you do not have permission to make this request.']);
        }

        return response()->json(['success' => true, 'message' => 'Requester verified successfully.']);
    }

    public function allocateQuarter(Request $request, $id)
    {
        $action = $request->input('submit_action');

        // Handle Reject action for GA
        if ($action === 'reject') {
            return $this->rejectScheduledQuarterApplication($request, $id);
        }

        // Handle Allocate action for GA
        if ($action === 'allocate' && Auth::user()->hasPermissionTo('government_agent_approval')) {
            // 1. Validation
            $validator = Validator::make($request->all(), [
                'selected_quarter' => 'required|exists:quarters,quarter_id',
                'ga_approval_status' => 'required|in:1',
                'ga_note' => 'nullable|string|max:2000',
            ], [
                'selected_quarter.required' => 'Please select a quarter to allocate.',
                'ga_approval_status.in' => 'To allocate, GA Approval must be Yes.',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
            }

            DB::beginTransaction();
            try {
                $quarter = Quarter::findOrFail($request->selected_quarter);

                // Availability Check
                if ($quarter->status !== 'Unallocated' && ($quarter->current_occupant_number >= $quarter->occupant_number)) {
                    return response()->json(['status' => 'error', 'message' => 'The selected quarter is no longer available (Full Capacity).'], 422);
                }

                $application = QuarterApplication::with('quarterAllocation')->findOrFail($id);
                $quarterAllocation = $application->quarterAllocation;

                if (!$quarterAllocation) {
                    return response()->json(['status' => 'error', 'message' => 'Allocation record not found.'], 404);
                }

                // Update Allocation Record
                $quarterAllocation->allocation_status = 'allocated';
                $quarterAllocation->allocation_date = Carbon::now();
                $quarterAllocation->vacate_date = Carbon::now()->addYears(5);
                $quarterAllocation->quarter_id = $quarter->quarter_id;
                $quarterAllocation->ga_note = $request->ga_note;
                $quarterAllocation->ga_approval_status = 1;
                $quarterAllocation->save();

                // Update Quarter Occupancy
                $quarter->current_occupant_number += 1;
                if ($quarter->current_occupant_number >= $quarter->occupant_number) {
                    $quarter->status = 'Allocated';
                }
                $quarter->save();

                AuditLog::create([
                    'log_title' => 'Scheduled Quarter Allocated',
                    'performed_by' => Auth::id(),
                    'details' => "Application ID: {$id} allocated to Quarter {$quarter->quarter_id}",
                    'date_performed' => Carbon::now()->toDateString(),
                    'time_performed' => Carbon::now()->toTimeString(),
                ]);

                DB::commit();

                return response()->json(['status' => 'success', 'message' => 'Scheduled quarter allocated successfully!', 'redirect_url' => route('dashboard')]);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Scheduled Quarter Allocation Failed: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString(),
                ]);
                return response()->json(['status' => 'error', 'message' => 'An unexpected error occurred.'], 500);
            }
        }

        // Handle AO/AGA Submit action (verification only, no quarter allocation)
        if (
            Auth::user()->hasPermissionTo('administrative_officer_approval') ||
            Auth::user()->hasPermissionTo('additional_government_agent_approval')
        ) {

            DB::beginTransaction();
            try {
                $application = QuarterApplication::with('quarterAllocation')->findOrFail($id);
                $quarterAllocation = $application->quarterAllocation;

                if (!$quarterAllocation) {
                    return response()->json(['status' => 'error', 'message' => 'Application allocation record not found.'], 404);
                }

                // Handle AO submission
                if (Auth::user()->hasPermissionTo('administrative_officer_approval')) {
                    $validator = Validator::make($request->all(), [
                        'ao_verified_status' => 'required|in:0,1',
                        'ao_note' => 'required_if:ao_verified_status,0|nullable|string|max:2000',
                    ], [
                        'ao_verified_status.required' => 'Please select Yes or No for Administrative Officer Verified.',
                        'ao_note.required_if' => 'Administrative Officer Note is required when verification is set to No.',
                    ]);

                    if ($validator->fails()) {
                        return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
                    }

                    $quarterAllocation->is_ao_verified = $request->ao_verified_status;
                    $quarterAllocation->ao_note = $request->ao_note;
                    $quarterAllocation->save();

                    AuditLog::create([
                        'log_title' => 'Scheduled Quarter AO Verification',
                        'performed_by' => Auth::id(),
                        'details' => "Application ID: {$id} - AO verified: " . ($request->ao_verified_status == 1 ? 'Yes' : 'No'),
                        'date_performed' => Carbon::now()->toDateString(),
                        'time_performed' => Carbon::now()->toTimeString(),
                    ]);
                }

                // Handle AGA submission
                if (Auth::user()->hasPermissionTo('additional_government_agent_approval')) {
                    $validator = Validator::make($request->all(), [
                        'aga_verified_status' => 'required|in:0,1',
                        'aga_note' => 'required_if:aga_verified_status,0|nullable|string|max:2000',
                    ], [
                        'aga_verified_status.required' => 'Please select Yes or No for Additional Government Agent Verified.',
                        'aga_note.required_if' => 'Additional Government Agent Note is required when verification is set to No.',
                    ]);

                    if ($validator->fails()) {
                        return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
                    }

                    $quarterAllocation->is_aga_verified = $request->aga_verified_status;
                    $quarterAllocation->aga_note = $request->aga_note;
                    $quarterAllocation->save();

                    AuditLog::create([
                        'log_title' => 'Scheduled Quarter AGA Verification',
                        'performed_by' => Auth::id(),
                        'details' => "Application ID: {$id} - AGA verified: " . ($request->aga_verified_status == 1 ? 'Yes' : 'No'),
                        'date_performed' => Carbon::now()->toDateString(),
                        'time_performed' => Carbon::now()->toTimeString(),
                    ]);
                }

                DB::commit();

                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['status' => 'success', 'message' => 'Verification submitted successfully!', 'redirect_url' => route('dashboard')]);
                }
                return redirect()->route('dashboard')->with('success', 'Verification submitted successfully!');

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Scheduled Quarter Verification Failed: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString(),
                ]);

                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['status' => 'error', 'message' => 'An unexpected server error occurred. Please check the logs.'], 500);
                }
                return redirect()->back()->with('error', 'An unexpected server error occurred. Please check the logs.');
            }
        }

        // This block should not be reached if 'allocate' action is handled above
        // and AO/AGA verification is handled.
        // If it is reached, it implies an invalid state or missing action.
        return response()->json(['status' => 'error', 'message' => 'Invalid action or insufficient permissions.'], 400);
    }

    private function rejectScheduledQuarterApplication(Request $request, $id)
    {
        // 1. Authorization
        if (!Auth::user()->hasPermissionTo('government_agent_approval')) {
            return response()->json(['status' => 'error', 'message' => 'You do not have permission to perform this action.'], 403);
        }

        // 2. Validation
        $validator = Validator::make($request->all(), [
            'ga_approval_status' => 'required|in:0',
            'ga_note' => 'required|string|max:2000', // GA Note is mandatory
        ], [
            'ga_approval_status.in' => 'To reject an application, GA approval must be set to "No".',
            'ga_note.required' => 'A rejection note is required.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        DB::beginTransaction();
        try {
            $application = QuarterApplication::with('quarterAllocation')->findOrFail($id);
            $quarterAllocation = $application->quarterAllocation;

            if (!$quarterAllocation) {
                return response()->json(['status' => 'error', 'message' => 'Application allocation record not found.'], 404);
            }

            // 3. Status Update & Quarter Release
            $oldStatus = $quarterAllocation->allocation_status;
            $quarterId = $quarterAllocation->quarter_id;

            $quarterAllocation->allocation_status = 'rejected';
            // Use append strategy if note exists, otherwise just set it
            $quarterAllocation->ga_note = $request->ga_note;
            $quarterAllocation->quarter_id = null; // Clear assignment
            $quarterAllocation->allocation_date = null;
            $quarterAllocation->vacate_date = null;
            $quarterAllocation->save();

            // Release Occupancy if applicable works for Scheduled Logic (decrement is allowed here)
            if ($quarterId) {
                $quarter = Quarter::find($quarterId);
                if ($quarter && $quarter->current_occupant_number > 0) {
                    $quarter->decrement('current_occupant_number');
                    $quarter->refresh();
                    if ($quarter->current_occupant_number < $quarter->occupant_number) {
                        $quarter->status = 'Unallocated';
                        $quarter->save();
                    }
                }
            }

            // 4. Update Audit Log
            AuditLog::create([
                'log_title' => 'Scheduled Quarter Application Rejected',
                'performed_by' => Auth::id(),
                'details' => "Application ID: {$id} rejected by GA",
                'date_performed' => Carbon::now()->toDateString(),
                'time_performed' => Carbon::now()->toTimeString(),
            ]);

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Application rejected successfully!', 'redirect_url' => route('dashboard')]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Scheduled Quarter Rejection Failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['status' => 'error', 'message' => 'An unexpected server error occurred. Please check the logs.'], 500);
        }
    }

    public function downloadPdf(string $applicationId)
    {
        // Load the application with all related data
        $application = QuarterApplication::with([
            'scheduledQuarterApplication',
            'familyQuarterApplication.markingFamilyQuarter',
            'quarterAllocation.quarter',
        ])->findOrFail($applicationId);

        // --- Universal Data Preparation ---

        // Calculate grade based on salary (common for both types)
        $gradeSalarySettings = GradeSalarySetting::all();
        $calculatedGrade = 'N/A';
        $applicantMonthlySalary = $application->monthly_salary;
        if ($applicantMonthlySalary !== null) {
            foreach ($gradeSalarySettings as $setting) {
                if ($applicantMonthlySalary >= $setting->min_salary && $applicantMonthlySalary <= $setting->max_salary) {
                    $calculatedGrade = $setting->grade;
                    break;
                }
            }
        }

        // --- Allocation-Dependent Logic ---
        $allocatedQuarter = null;
        $availableQuarters = collect(); // Default to empty collection

        if ($application->quarterAllocation && $application->quarterAllocation->allocation_status === 'allocated') {
            // If allocated, get the specific quarter from the loaded relationship
            $allocatedQuarter = $application->quarterAllocation->quarter;
        } else {
            // If not allocated, find available quarters based on type
            $quarterType = $application->quarter_type; // 'Family' or 'Scheduled'

            $quarterQuery = Quarter::where('quarter_type', $quarterType);

            // Apply filtering criteria based on the application
            if (!empty($application->gender)) {
                $quarterQuery->where(function ($query) use ($application) {
                    $query->whereNull('allowed_gender')
                        ->orWhere('allowed_gender', $application->gender);
                });
            }

            if (!empty($application->service_grade)) {
                $quarterQuery->where(function ($query) use ($application) {
                    $query->whereNull('service_grade')
                        ->orWhere('service_grade', $application->service_grade);
                });
            }

            // Robust availability filter
            $quarterQuery->where(function ($query) {
                $query->where('status', 'Unallocated')
                    ->orWhereRaw('occupant_number > IFNULL(current_occupant_number, 0)');
            });

            $availableQuarters = $quarterQuery->get();
        }

        // --- View and Data Assignment ---

        $viewName = '';
        if ($application->quarter_type === 'Family') {
            $viewName = 'pdf.family_quarter_application_form';
        } else if ($application->quarter_type === 'Scheduled') {
            $viewName = 'pdf.scheduled_quarter_review';
        } else {
            abort(404, 'Application type not recognized');
        }

        $data = [
            'application' => $application,
            'calculatedGrade' => $calculatedGrade,
            'gradeSalarySettings' => $gradeSalarySettings, // Kept for consistency if view uses it
            'availableQuarters' => $availableQuarters,
            'allocatedQuarter' => $allocatedQuarter,
        ];

        // Generate PDF using DomPDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($viewName, $data);

        // Return the PDF for download
        return $pdf->download('quarter_application_' . $application->application_id . '.pdf');
    }

    public function showQuarterHistory()
    {
        $processedApplications = \App\Models\QuarterAllocation::with(['quarterApplication', 'quarter'])
            ->where('allocation_status', '!=', 'pending')
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json($processedApplications);
    }

    public function showProcessedScheduled($id)
    {
        $application = QuarterApplication::with(['scheduledQuarterApplication', 'quarterAllocation.quarter'])
            ->where('application_id', $id)
            ->where('quarter_type', 'Scheduled')
            ->firstOrFail();

        // 1. Fetch all GradeSalarySetting records
        $gradeSalarySettings = GradeSalarySetting::all();
        $calculatedGrade = 'N/A';

        // 2. Implement logic to determine the calculatedGrade
        $applicantMonthlySalary = $application->monthly_salary;

        if ($applicantMonthlySalary !== null) {
            foreach ($gradeSalarySettings as $setting) {
                if ($applicantMonthlySalary >= $setting->min_salary && $setting->max_salary === null) { // Handle open-ended max_salary
                    $calculatedGrade = $setting->grade;
                    break;
                } elseif ($applicantMonthlySalary >= $setting->min_salary && $applicantMonthlySalary <= $setting->max_salary) {
                    $calculatedGrade = $setting->grade;
                    break;
                }
            }
        }

        return view('showprocessedscheduled', compact('application', 'calculatedGrade'));
    }

    public function showProcessedFamily($id)
    {
        $application = QuarterApplication::with(['familyQuarterApplication.markingFamilyQuarter', 'quarterAllocation.quarter'])
            ->where('application_id', $id)
            ->where('quarter_type', 'Family')
            ->firstOrFail();

        // Calculate grade based on salary
        $gradeSalarySettings = GradeSalarySetting::all();
        $calculatedGrade = 'N/A';
        $applicantMonthlySalary = $application->monthly_salary;
        if ($applicantMonthlySalary !== null) {
            foreach ($gradeSalarySettings as $setting) {
                if ($applicantMonthlySalary >= $setting->min_salary && $setting->max_salary === null) {
                    $calculatedGrade = $setting->grade;
                    break;
                } elseif ($applicantMonthlySalary >= $setting->min_salary && $applicantMonthlySalary <= $setting->max_salary) {
                    $calculatedGrade = $setting->grade;
                    break;
                }
            }
        }

        return view('showprocessedfamily', compact('application', 'calculatedGrade'));
    }

    public function restoreScheduledQuarterApplication($id)
    {
        // 1. Authorization
        if (!Auth::user()->hasPermissionTo('government_agent_approval')) {
            return redirect()->back()->with('error', 'You do not have permission to perform this action.');
        }

        DB::beginTransaction();
        try {
            $application = QuarterApplication::with('quarterAllocation')->findOrFail($id);
            $quarterAllocation = $application->quarterAllocation;

            if (!$quarterAllocation) {
                return redirect()->back()->with('error', 'Application allocation record not found.');
            }

            // Only allow restore if status is rejected
            if ($quarterAllocation->allocation_status !== 'rejected') {
                return redirect()->back()->with('error', 'Only rejected applications can be restored.');
            }

            $oldStatus = $quarterAllocation->allocation_status;

            // Reset allocation to pending status
            $quarterAllocation->allocation_status = 'pending';
            $quarterAllocation->quarter_id = null;
            $quarterAllocation->allocation_date = null;
            $quarterAllocation->vacate_date = null;
            $quarterAllocation->save();

            // Create audit log
            AuditLog::create([
                'log_title' => 'Scheduled Quarter Application Restored',
                'performed_by' => Auth::id(),
                'details' => "Application ID: {$id} restored from {$oldStatus} to pending by GA",
                'date_performed' => Carbon::now()->toDateString(),
                'time_performed' => Carbon::now()->toTimeString(),
            ]);

            DB::commit();

            return redirect()->route('history')->with('success', 'Application successfully restored to pending status!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Scheduled Quarter Restore Failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'An unexpected error occurred. Please check the logs.');
        }
    }

    public function cancelAllocation(Request $request, $id)
    {
        // Authorization - only GA can cancel allocations
        if (!Auth::user()->hasPermissionTo('government_agent_approval')) {
            return redirect()->back()->with('error', 'You do not have permission to perform this action.');
        }

        // Validate GA note is provided
        $request->validate([
            'ga_note' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $application = QuarterApplication::with('quarterAllocation.quarter')->findOrFail($id);
            $quarterAllocation = $application->quarterAllocation;

            if (!$quarterAllocation) {
                return redirect()->back()->with('error', 'Application allocation record not found.');
            }

            // Only allow cancellation if status is allocated
            if ($quarterAllocation->allocation_status !== 'allocated') {
                return redirect()->back()->with('error', 'Only allocated applications can be cancelled.');
            }

            $oldStatus = $quarterAllocation->allocation_status;
            $quarterId = $quarterAllocation->quarter_id;

            // Update allocation status to rejected and clear allocation details
            $quarterAllocation->allocation_status = 'rejected';
            $quarterAllocation->ga_note = $request->ga_note;

            // Clear allocation details as requested
            $quarterAllocation->quarter_id = null;
            $quarterAllocation->allocation_date = null;
            $quarterAllocation->vacate_date = null;

            $quarterAllocation->save();

            // If a quarter was assigned, update the quarter's current occupant number
            if ($quarterId) {
                $quarter = Quarter::find($quarterId);
                if ($quarter && $quarter->current_occupant_number > 0) {
                    $quarter->decrement('current_occupant_number');

                    // If quarter becomes available again (not full), update status
                    // Reload to get fresh occupant count after decrement
                    $quarter->refresh();
                    // Global handling for Family Quarters with 0 capacity (implicitly 1)
                    $maxOccupants = ($quarter->quarter_type === 'Family' && $quarter->occupant_number === 0) ? 1 : $quarter->occupant_number;

                    if ($quarter->current_occupant_number < $maxOccupants) {
                        $quarter->status = 'Unallocated';
                        $quarter->save();
                    }
                }
            }

            // Create audit log
            AuditLog::create([
                'log_title' => 'Quarter Allocation Cancelled',
                'performed_by' => Auth::id(),
                'details' => "Application ID: {$id} allocation cancelled from {$oldStatus} to rejected by GA. Quarter: {$quarterId} released.",
                'date_performed' => Carbon::now()->toDateString(),
                'time_performed' => Carbon::now()->toTimeString(),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Quarter allocation cancelled successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Quarter Allocation Cancellation Failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'An unexpected error occurred. Please check the logs.');
        }
    }



    /**
     * Delete a scheduled quarter application.
     * Requester: Can delete only if is_ao_verified=0, is_aga_verified=0, allocation_status=pending
     * Administrative Officer: Can delete if allocation_status=pending
     */
    public function deleteScheduledQuarterApplication($id)
    {
        // Check permissions
        if (!Auth::user()->hasPermissionTo('requester') && !Auth::user()->hasPermissionTo('administrative_officer_approval')) {
            return response()->json(['success' => false, 'message' => 'You do not have permission to delete this application.'], 403);
        }

        DB::beginTransaction();
        try {
            $application = QuarterApplication::with(['quarterAllocation', 'scheduledQuarterApplication'])->findOrFail($id);
            $quarterAllocation = $application->quarterAllocation;

            if (!$quarterAllocation) {
                return response()->json(['success' => false, 'message' => 'Application allocation record not found.'], 404);
            }

            // Role-based validation
            if (Auth::user()->hasPermissionTo('requester')) {
                // Requester: Must meet all conditions
                if ($quarterAllocation->is_ao_verified != 0) {
                    return response()->json(['success' => false, 'message' => 'Cannot delete: Application has been verified by Administrative Officer.'], 400);
                }
                if ($quarterAllocation->is_aga_verified != 0) {
                    return response()->json(['success' => false, 'message' => 'Cannot delete: Application has been verified by Additional Government Agent.'], 400);
                }
                if ($quarterAllocation->allocation_status !== 'pending') {
                    return response()->json(['success' => false, 'message' => 'Cannot delete: Application status must be pending.'], 400);
                }
            } elseif (Auth::user()->hasPermissionTo('administrative_officer_approval')) {
                // Administrative Officer: Only check status
                if ($quarterAllocation->allocation_status !== 'pending') {
                    return response()->json(['success' => false, 'message' => 'Cannot delete: Application status must be pending.'], 400);
                }
            }

            // Store details for audit log
            $officerName = $application->officer_name;
            $nic = $application->nic;

            // Delete records in correct order (child to parent)
            $quarterAllocation->delete();
            if ($application->scheduledQuarterApplication) {
                $application->scheduledQuarterApplication->delete();
            }
            $application->delete();

            // Create audit log
            AuditLog::create([
                'log_title' => 'Scheduled Quarter Application Deleted',
                'performed_by' => Auth::id(),
                'details' => "Application ID: {$id}, Officer: {$officerName}, NIC: {$nic} deleted by " . Auth::user()->name,
                'date_performed' => Carbon::now()->toDateString(),
                'time_performed' => Carbon::now()->toTimeString(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Application deleted successfully!',
                'redirect_url' => route('dashboard')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Scheduled Quarter Application Deletion Failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['success' => false, 'message' => 'An unexpected error occurred. Please check the logs.'], 500);
        }
    }

    /**
     * Delete a family quarter application.
     * Requester: Can delete only if is_ao_verified=0, is_aga_verified=0, allocation_status=pending
     * Administrative Officer: Can delete if allocation_status=pending
     */
    public function deleteFamilyQuarterApplication($id)
    {
        // Check permissions
        if (!Auth::user()->hasPermissionTo('requester') && !Auth::user()->hasPermissionTo('administrative_officer_approval')) {
            return response()->json(['success' => false, 'message' => 'You do not have permission to delete this application.'], 403);
        }

        DB::beginTransaction();
        try {
            $application = QuarterApplication::with(['quarterAllocation', 'familyQuarterApplication'])->findOrFail($id);
            $quarterAllocation = $application->quarterAllocation;

            if (!$quarterAllocation) {
                return response()->json(['success' => false, 'message' => 'Application allocation record not found.'], 404);
            }

            // Role-based validation
            if (Auth::user()->hasPermissionTo('requester')) {
                // Requester: Must meet all conditions
                if ($quarterAllocation->is_ao_verified != 0) {
                    return response()->json(['success' => false, 'message' => 'Cannot delete: Application has been verified by Administrative Officer.'], 400);
                }
                if ($quarterAllocation->is_aga_verified != 0) {
                    return response()->json(['success' => false, 'message' => 'Cannot delete: Application has been verified by Additional Government Agent.'], 400);
                }
                if ($quarterAllocation->allocation_status !== 'pending') {
                    return response()->json(['success' => false, 'message' => 'Cannot delete: Application status must be pending.'], 400);
                }
            } elseif (Auth::user()->hasPermissionTo('administrative_officer_approval')) {
                // Administrative Officer: Only check status
                if ($quarterAllocation->allocation_status !== 'pending') {
                    return response()->json(['success' => false, 'message' => 'Cannot delete: Application status must be pending.'], 400);
                }
            }

            // Store details for audit log
            $officerName = $application->officer_name;
            $nic = $application->nic;

            // Delete records in correct order (child to parent)
            $quarterAllocation->delete();
            if ($application->familyQuarterApplication) {
                $application->familyQuarterApplication->delete();
            }
            $application->delete();

            // Create audit log
            AuditLog::create([
                'log_title' => 'Family Quarter Application Deleted',
                'performed_by' => Auth::id(),
                'details' => "Application ID: {$id}, Officer: {$officerName}, NIC: {$nic} deleted by " . Auth::user()->name,
                'date_performed' => Carbon::now()->toDateString(),
                'time_performed' => Carbon::now()->toTimeString(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Application deleted successfully!',
                'redirect_url' => route('dashboard')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Family Quarter Application Deletion Failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['success' => false, 'message' => 'An unexpected error occurred. Please check the logs.'], 500);
        }
    }
}
