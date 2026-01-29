<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuarterApplication;
use App\Models\ScheduledQuarterApplication;
use App\Models\QuarterAllocation;
use App\Models\AuditLog;
use App\Models\Quarter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ScheduledQuarterController extends Controller
{
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
            $quarterController = new QuarterController();
            $quarterApplication = $quarterController->createQuarterApplicationForScheduled($request);
            $this->_createScheduledQuarterApplication($request, $quarterApplication->application_id);
            $quarterController->createQuarterAllocation($quarterApplication->application_id);

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
        $availableQuarters = Quarter::where('quarter_type', 'Scheduled')
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

        return view('scheduledreview', compact('application', 'calculatedGrade', 'gradeSalarySettings', 'availableQuarters'));
    }
}
