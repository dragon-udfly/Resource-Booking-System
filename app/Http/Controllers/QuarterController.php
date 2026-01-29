<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
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
use App\Models\ScheduledQuarterApplication; 
use App\Models\GradeSalarySetting; 
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

            // Increment number_of_quarters in grade_salary_settings
            $serviceGrade = $request->service_grade;
            if ($serviceGrade) {
                $gradeSetting = GradeSalarySetting::where('grade', $serviceGrade)->first();

                $gradeMapping = [
                    '1' => '1 (G I)',
                    '2' => '2 (G II)',
                    '3' => '3 (G III)',
                    '4' => '4 (G IV)',
                    '5' => '5 (G V)',
                    '5A' => '5A', 
                ];
                $mappedGrade = $gradeMapping[$serviceGrade] ?? null;

                if ($mappedGrade) {
                    $gradeSetting = GradeSalarySetting::where('grade', $mappedGrade)->first();
                    if ($gradeSetting) {
                        $gradeSetting->increment('number_of_quarters');
                        AuditLog::create([
                            'log_title' => "Incremented number of quarters for Grade {$mappedGrade}",
                            'performed_by' => Auth::id(),
                            'date_performed' => Carbon::now()->toDateString(),
                            'time_performed' => Carbon::now()->toTimeString(),
                            'details' => "Quarter ID: {$newQuarterId}",
                        ]);
                    } else {
                        Log::warning("GradeSalarySetting not found for service_grade: {$mappedGrade}");
                    }
                } else {
                    Log::warning("No mapped grade found for service_grade: {$serviceGrade}");
                }
            }

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
            'quarter_id' => null, // quarter_id is nullable
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
                               ->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Invalid NIC or Contact Number.']);
        }

        if (!$user->hasPermissionTo('requester')) {
            return response()->json(['success' => false, 'message' => 'You do not have permission to make this request.']);
        }

        return response()->json(['success' => true, 'message' => 'Requester verified successfully.']);
    }

    public function markingScheme()
    {
        $marking_schemes = \App\Models\MarkingScheme::all()->groupBy('marking_title');
        return view('markingscheme', compact('marking_schemes'));
    }

    public function updateMarkingScheme(Request $request)
    {
        $request->validate([
            'marks' => 'required|array',
            'marks.*' => 'required|numeric|min:0',
        ]);

        foreach ($request->marks as $option => $mark) {
            \App\Models\MarkingScheme::where('marking_option', $option)->update([
                'defined_mark' => $mark,
                'date_modified' => \Carbon\Carbon::now(),
            ]);
        }

        return redirect()->route('marking-scheme.edit')->with('success', 'Marking scheme updated successfully!');
    }

    public function downloadPdf(string $applicationId)
    {
        $application = QuarterApplication::with([
            'familyQuarterApplication.markingFamilyQuarter',
            'scheduledQuarterApplication',
            'quarterAllocation.quarter' // Eager load the quarter details
        ])->where('application_id', $applicationId)->firstOrFail();

        // Replicate calculatedGrade logic from showScheduledQuarterReview
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

        $data = [
            'application' => $application,
            'calculatedGrade' => $calculatedGrade, // Pass calculatedGrade to the view
            'date' => Carbon::now()->format('Y-m-d')
        ];
        
        if ($application->quarter_type === 'Family') {
            $viewName = 'pdf.family_quarter_application_form';
        } else {
            // Use the new, more detailed view for scheduled quarters
            $viewName = 'pdf.scheduled_quarter_review';
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($viewName, $data);
        return $pdf->download('quarter_application_' . $application->application_id . '.pdf');
    }

    public function submitStageVerification(Request $request, string $applicationId)
    {
        $quarterAllocation = QuarterAllocation::where('application_id', $applicationId)->firstOrFail();

        if (Auth::user()->hasPermissionTo('administrative_officer_approval')) {
            $quarterAllocation->allocation_status = 'pending_aga_review'; 
            $logTitle = 'Quarter Application ' . $applicationId . ' submitted by AO for AGA review';
        } elseif (Auth::user()->hasPermissionTo('additional_government_agent_approval')) {
            if (!$quarterAllocation->is_aga_verified) {
                return redirect()->back()->with('error', 'Please verify the application first.');
            }
            $quarterAllocation->allocation_status = 'pending_ga_approval';
            $logTitle = 'Quarter Application ' . $applicationId . ' submitted by AGA for GA approval';
        } else {
            return redirect()->back()->with('error', 'You do not have permission to perform this action.');
        }
        
        $quarterAllocation->date_modified = Carbon::now();
        $quarterAllocation->save();

        AuditLog::create([
            'log_title' => $logTitle,
            'performed_by' => Auth::id(),
            'date_performed' => Carbon::now()->toDateString(),
            'time_performed' => Carbon::now()->toTimeString(),
        ]);

        return redirect()->back()->with('success', 'Application submitted for next stage verification successfully!');
    }

    public function processGaAction(Request $request, string $applicationId)
    {
        Log::info('processGaAction called with request:', $request->all());

        if (!Auth::user()->hasPermissionTo('government_agent_approval')) {
            return redirect()->back()->with('error', 'You do not have permission to perform this action.');
        }

        $validator = Validator::make($request->all(), [
            'action' => ['required', Rule::in(['allocate', 'reject'])],
            'ga_note' => 'nullable|string|max:2000',
            'ga_approval_status' => ['required', Rule::in(['1', '0'])],
            'selected_quarter' => 'required_if:action,allocate|exists:quarters,quarter_id',
        ], [
            'selected_quarter.required_if' => 'You must select an available quarter to allocate.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $quarterAllocation = QuarterAllocation::where('application_id', $applicationId)->firstOrFail();
            
            // Common updates
            $quarterAllocation->ga_note = $request->ga_note;
            $quarterAllocation->date_modified = Carbon::now();
            
            $logTitle = '';

            if ($request->action === 'allocate' && $request->ga_approval_status == '1') {
                $selectedQuarter = Quarter::findOrFail($request->selected_quarter);

                // Update QuarterAllocation record
                $quarterAllocation->quarter_id = $selectedQuarter->quarter_id;
                $quarterAllocation->allocation_status = 'allocated';
                $quarterAllocation->allocation_date = Carbon::now();
                $quarterAllocation->vacate_date = Carbon::now()->addYears(5);
                
                // Update the Quarter record
                $selectedQuarter->status = 'Allocated';
                $selectedQuarter->increment('current_occupant_number');
                $selectedQuarter->save();
                
                $logTitle = 'Quarter Application ' . $applicationId . ' allocated to Quarter ' . $selectedQuarter->quarter_id . ' by GA';

            } elseif ($request->action === 'reject' || $request->ga_approval_status == '0') {
                $quarterAllocation->allocation_status = 'rejected';
                $logTitle = 'Quarter Application ' . $applicationId . ' rejected by GA';
            } else {
                // This case handles if action is 'allocate' but ga_approval_status is '0' (No)
                // Or any other unexpected combination. Treat as rejection for safety.
                $quarterAllocation->allocation_status = 'rejected';
                $logTitle = 'Quarter Application ' . $applicationId . ' rejected by GA as approval was not granted.';
            }
            
            $quarterAllocation->save();

            AuditLog::create([
                'log_title' => $logTitle,
                'performed_by' => Auth::id(),
                'details' => 'GA Note: ' . $request->ga_note,
                'date_performed' => Carbon::now()->toDateString(),
                'time_performed' => Carbon::now()->toTimeString(),
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Application processed successfully by Government Agent!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to process GA action for application {$applicationId}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'An unexpected error occurred while processing the action. Please check logs.');
        }
    }
}
