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

class QuarterController extends Controller
{
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
            'quarter_type' => 'required|string|max:50',
            'status' => 'required|string|max:50',
            'old_quarter_no' => 'nullable|string|max:50',
            'new_quarter_no' => 'nullable|string|max:50',
            'location' => 'required|string|max:100',
            'occupant_number' => 'nullable|integer',
            'allowed_gender' => 'nullable|string|max:20',
            'special_notice' => 'nullable|string',
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
                'status',
                'location',
                'occupant_number',
                'allowed_gender',
                'special_notice',
            ]);
            $data['quarter_id'] = $newQuarterId;

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
            'quarter_type' => 'required|string|max:50',
            'status' => 'required|string|max:50',
            'old_quarter_no' => 'nullable|string|max:50',
            'new_quarter_no' => 'nullable|string|max:50',
            'location' => 'required|string|max:100',
            'occupant_number' => 'nullable|integer',
            'allowed_gender' => 'nullable|string|max:20',
            'special_notice' => 'nullable|string',
        ]);

        $quarter->update([
            'quarter_type' => $request->quarter_type,
            'status' => $request->status,
            'old_quarter_no' => $request->old_quarter_no,
            'new_quarter_no' => $request->new_quarter_no,
            'location' => $request->location,
            'occupant_number' => $request->occupant_number,
            'allowed_gender' => $request->allowed_gender,
            'special_notice' => $request->special_notice,
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
}
