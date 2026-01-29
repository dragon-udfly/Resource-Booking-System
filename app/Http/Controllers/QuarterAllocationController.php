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

        return view('familyreview', ['application' => $application]);
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
            'nic' => [
                'required',
                'string',
                'max:20',
                Rule::unique('quarter_application', 'nic'),
            ],
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
        ], $messages);

        if ($validator->fails()) {
            return redirect()->route('familyquarter')
                        ->withErrors($validator)
                        ->withInput();
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
            return redirect()->route('bookquarter')->with('success', 'Family quarter application submitted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Family Quarter Application Submission Failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('familyquarter')->with('error', 'An unexpected error occurred. Failed to submit application. Please check the logs.');
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
        $total_mark = $this->calculateFamilyQuarterMark($request);
        return MarkingFamilyQuarter::create([
            'f_application_id' => $f_application_id,
            'f_department' => $request->marking_f_department,
            'f_years_since_application_created' => 0, // Default value
            'f_number_of_dependant' => $request->number_of_dependant,
            'is_dependant_with_disability' => $request->is_dependant_with_disability,
            'f_distance_of_residency' => $request->f_distance_of_residency,
            'f_spacial_reason' => $request->f_spacial_reason,
            'total_mark' => $total_mark,
            'date_calculated' => Carbon::now(),
        ]);
    }

    private function calculateFamilyQuarterMark(Request $request)
    {
        // implement logic
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

    public function downloadPdf(string $applicationId)
    {
        // Load the application with all related data
        $application = QuarterApplication::with([
            'scheduledQuarterApplication',
            'familyQuarterApplication.markingFamilyQuarter',
            'quarterAllocation.quarter', // Include the allocated quarter if any
        ])->findOrFail($applicationId);

        // Determine which view to use based on quarter type
        if ($application->quarter_type === 'Family') {
            $viewName = 'pdf.family_quarter_application_form';

            // Calculate grade based on salary for family applications
            $gradeSalarySettings = \App\Models\GradeSalarySetting::all();
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

            // Prepare data for the view
            $data = [
                'application' => $application,
                'calculatedGrade' => $calculatedGrade,
                'gradeSalarySettings' => $gradeSalarySettings,
            ];
        } else if ($application->quarter_type === 'Scheduled') {
            $viewName = 'pdf.scheduled_quarter_review';

            // Calculate grade based on salary for scheduled applications
            $gradeSalarySettings = \App\Models\GradeSalarySetting::all();
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

            // Get available quarters based on criteria for scheduled applications
            $availableQuarters = \App\Models\Quarter::where('quarter_type', 'Scheduled')
                ->where(function ($query) use ($application) {
                    // Matching Gender
                    if ($application->gender) {
                        $query->where(function($q) use ($application) {
                            $q->whereNull('allowed_gender') // If quarter has no specific gender preference
                              ->orWhere('allowed_gender', $application->gender);
                        });
                    }
                    // Matching Service Grade
                    if ($application->service_grade) {
                        $query->where(function($q) use ($application) {
                            $q->whereNull('service_grade') // If quarter has no specific service grade requirement
                              ->orWhere('service_grade', $application->service_grade);
                        });
                    }
                })
                ->where(function ($query) {
                    // Availability: Unallocated OR has vacancies
                    $query->where('status', 'Unallocated')
                          ->orWhereRaw('occupant_number > current_occupant_number');
                })
                ->get();

            // Prepare data for the view
            $data = [
                'application' => $application,
                'calculatedGrade' => $calculatedGrade,
                'gradeSalarySettings' => $gradeSalarySettings,
                'availableQuarters' => $availableQuarters,
            ];
        } else {
            abort(404, 'Application type not recognized');
        }

        // Generate PDF using DomPDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($viewName, $data);

        // Return the PDF for download
        return $pdf->download('quarter_application_' . $application->application_id . '.pdf');
    }

    public function updateScheduledQuarterReview(Request $request, $id)
    {
        $application = QuarterApplication::with('quarterAllocation')->where('application_id', $id)->firstOrFail();
        $user = Auth::user();
        $quarterAllocation = $application->quarterAllocation;

        if ($request->action === 'Submit') {
            $validated = $request->validate([
                'ao_verified_status' => 'nullable|boolean',
                'ao_note' => 'nullable|string',
                'aga_verified_status' => 'nullable|boolean',
                'aga_note' => 'nullable|string',
            ]);

            $note = 'I reviewed this application on ' . Carbon::now()->format('Y-m-d') . ' at ' . Carbon::now()->format('H:i:s') . '.';

            if ($user->hasPermissionTo('administrative_officer_approval') && $request->has('ao_verified_status')) {
                $quarterAllocation->is_ao_verified = $request->ao_verified_status;
                $quarterAllocation->ao_note = ($quarterAllocation->ao_note ? $quarterAllocation->ao_note . "\n" : '') . $note;

                AuditLog::create([
                    'log_title' => 'Scheduled Quarter Application Reviewed by AO',
                    'performed_by' => $user->id,
                    'details' => 'Application ID: ' . $application->application_id . '. AO Verification: ' . ($request->ao_verified_status ? 'Yes' : 'No'),
                    'date_performed' => Carbon::now()->toDateString(),
                    'time_performed' => Carbon::now()->toTimeString(),
                ]);
            }

            if ($user->hasPermissionTo('additional_government_agent_approval') && $request->has('aga_verified_status')) {
                $quarterAllocation->is_aga_verified = $request->aga_verified_status;
                $quarterAllocation->aga_note = ($quarterAllocation->aga_note ? $quarterAllocation->aga_note . "\n" : '') . $note;

                AuditLog::create([
                    'log_title' => 'Scheduled Quarter Application Reviewed by AGA',
                    'performed_by' => $user->id,
                    'details' => 'Application ID: ' . $application->application_id . '. AGA Verification: ' . ($request->aga_verified_status ? 'Yes' : 'No'),
                    'date_performed' => Carbon::now()->toDateString(),
                    'time_performed' => Carbon::now()->toTimeString(),
                ]);
            }

            $quarterAllocation->save();

            return redirect()->route('dashboard')->with('success', 'Application review has been submitted successfully.');
        }

        // Handle other actions like 'allocate' and 'reject' if necessary in the future
        return redirect()->back()->with('error', 'Invalid action.');
    }
}
