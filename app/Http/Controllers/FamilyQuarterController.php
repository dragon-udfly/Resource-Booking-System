<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuarterApplication;
use App\Models\FamilyQuarterApplication;
use App\Models\MarkingFamilyQuarter;
use App\Models\QuarterAllocation;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Str;

class FamilyQuarterController extends Controller
{
    public function bookFamilyQuarters()
    {
        return view('familyquarter');
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
            $quarterController = new QuarterController();
            $quarterApplication = $quarterController->createQuarterApplication($request);
            $familyQuarterApplication = $this->_createFamilyQuarterApplication($request, $quarterApplication->application_id);
            $this->_createMarkingFamilyQuarter($request, $familyQuarterApplication->f_application_id);
            $quarterController->createQuarterAllocation($quarterApplication->application_id);

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

    public function showFamilyQuarterReview($id)
    {
        $application = QuarterApplication::with([
            'familyQuarterApplication.markingFamilyQuarter',
            'quarterAllocation'
        ])->where('application_id', $id)->firstOrFail();

        return view('familyreview', ['application' => $application]);
    }

    private function calculateFamilyQuarterMark(Request $request)
    {
        $total_mark = 0;

        $department_marks = [
            'Officers_attached_under_the_Ministry_of_Home_Affairs' => 30,
            'Officers_attached_to_District_and_Divisional_Secretariats' => 25,
            'Other_Officers' => 20,
        ];
        $total_mark += $department_marks[$request->marking_f_department] ?? 0;

        $dependant_marks = [
            '01_person' => 5,
            '02_person' => 10,
            '03_person' => 15,
            '04_person' => 20,
            '05_or_above_05_person' => 25,
        ];
        $total_mark += $dependant_marks[$request->number_of_dependant] ?? 0;

        if ($request->is_dependant_with_disability == '1') { 
            $total_mark += 10;
        }

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
}
