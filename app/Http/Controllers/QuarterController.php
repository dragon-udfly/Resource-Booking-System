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
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
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
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
            }
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

            $successMessage = 'Quarter added successfully with ID ' . $newQuarterId . '!';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => $successMessage]);
            }
            return redirect()->route('quarters.index')->with('success', $successMessage);

        } catch (\Exception $e) {
            Log::error('Failed to create quarter (Exception): ' . $e->getMessage());
            $errorMessage = 'An unexpected error occurred. Failed to add quarter.';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $errorMessage], 500);
            }
            return redirect()->route('addquarter')
                ->with('error', $errorMessage)
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
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Validation failed.', 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
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

            $successMessage = 'Quarter updated successfully!';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => $successMessage]);
            }
            return redirect()->route('quarters.index')->with('success', $successMessage);

        } catch (\Exception $e) {
            Log::error('Quarter update failed: ' . $e->getMessage());
            $errorMessage = 'An unexpected error occurred while updating the quarter.';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $errorMessage], 500);
            }
            return redirect()->back()->with('error', $errorMessage)->withInput();
        }
    }

    public function destroy(Request $request, Quarter $quarter)
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

            $successMessage = 'Quarter ' . $quarterId . ' deleted successfully.';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => $successMessage]);
            }
            return redirect()->route('quarters.index')->with('success', $successMessage);

        } catch (\Exception $e) {
            Log::error('Failed to delete quarter: ' . $e->getMessage());
            $errorMessage = 'Failed to delete quarter. It may be in use.';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $errorMessage], 500);
            }
            return redirect()->route('quarters.index')->with('error', $errorMessage);
        }
    }

    public function seeQuarters()
    {
        $quarters = Quarter::all();
        return view('seequarters', ['quarters' => $quarters]);
    }

    public function showOccupantDetails()
    {
        // Fetch all allocated quarters with their allocations and application details
        $allocations = \App\Models\QuarterAllocation::with(['quarter', 'application'])
            ->where('allocation_status', 'allocated')
            ->whereNotNull('quarter_id')
            ->get();

        return view('occupantdetails', compact('allocations'));
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
}
