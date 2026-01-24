<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quarter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

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
                // More robust way to extract number
                $lastIdNumber = (int) substr(strrchr($lastQuarter->quarter_id, 'r'), 1);
                $nextIdNumber = $lastIdNumber + 1;
            }
            $newQuarterId = 'quarter' . str_pad($nextIdNumber, 3, '0', STR_PAD_LEFT);

            $data = $request->only([
                'old_quarter_no',
                'new_quarter_no',
                'quarter_type',
                'status',
                'location',
            ]);
            $data['quarter_id'] = $newQuarterId;

            Quarter::create($data);

            Log::info('New quarter created successfully with ID: ' . $newQuarterId);

            return redirect()->route('addquarter')->with('success', 'Quarter added successfully with ID ' . $newQuarterId . '!');
        
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
            Log::error('Failed to create quarter (Exception): ' . $e->getMessage());
            
            return redirect()->route('addquarter')
                        ->with('error', 'An unexpected error occurred. Failed to add quarter.')
                        ->withInput();
        }
    }
}
