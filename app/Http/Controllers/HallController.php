<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hall;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class HallController extends Controller
{
    /**
     * Show the form for creating a new hall.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('addhall');
    }

    /**
     * Store a newly created hall in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'hall_type' => 'required|string|max:200',
            'capacity' => 'required|integer',
            'description' => 'required|string|max:1200',
            'hall_status' => 'required|string',
            'special_notice' => 'nullable|string',
        ]);

        // Generate hall_id
        $lastHall = Hall::orderBy('hall_id', 'desc')->first();
        $nextHallIdNumber = 1;
        if ($lastHall) {
            $lastHallIdNumber = (int) Str::after($lastHall->hall_id, 'hall');
            $nextHallIdNumber = $lastHallIdNumber + 1;
        }
        $newHallId = 'hall' . str_pad($nextHallIdNumber, 3, '0', STR_PAD_LEFT);

        $hall = Hall::create([
            'hall_id' => $newHallId,
            'hall_type' => $request->hall_type,
            'capacity' => $request->capacity,
            'description' => $request->description,
            'current_state' => $request->hall_status,
            'special_notice' => $request->special_notice,
            'date_created' => Carbon::now(),
            'date_modified' => Carbon::now(),
        ]);

        AuditLog::create([
            'log_title' => 'Added New Hall ' . $newHallId,
            'performed_by' => Auth::id(),
            'date_performed' => Carbon::now()->toDateString(),
            'time_performed' => Carbon::now()->toTimeString(),
        ]);

        return redirect()->route('halls.index')->with('success', 'Hall added successfully!');
    }

    /**
     * Display a listing of the halls.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $halls = Hall::all();
        return view('halls', ['halls' => $halls]);
    }

    /**
     * Display a listing of the halls for viewing by users.
     *
     * @return \Illuminate\View\View
     */
    public function seeHalls()
    {
        if (Auth::check() && Auth::user()->hasPermissionTo('view_halls')) {
            $halls = Hall::all();
            return view('seehalls', ['halls' => $halls]);
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to access this page.');
    }

    /**
     * Show the form for editing the specified hall.
     *
     * @param  \App\Models\Hall  $hall
     * @return \Illuminate\View\View
     */
    public function edit(Hall $hall)
    {
        return view('modifyhall', ['hall' => $hall]);
    }

    /**
     * Update the specified hall in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Hall  $hall
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Hall $hall)
    {
        $request->validate([
            'hall_type' => 'required|string|max:200',
            'capacity' => 'required|integer',
            'description' => 'required|string|max:1200',
            'current_state' => 'required|string',
            'special_notice' => 'nullable|string',
        ]);

        $hall->update([
            'hall_type' => $request->hall_type,
            'capacity' => $request->capacity,
            'description' => $request->description,
            'current_state' => $request->current_state,
            'special_notice' => $request->special_notice,
            'date_modified' => Carbon::now(),
        ]);

        AuditLog::create([
            'log_title' => 'Modified Hall ' . $hall->hall_id,
            'performed_by' => Auth::id(),
            'date_performed' => Carbon::now()->toDateString(),
            'time_performed' => Carbon::now()->toTimeString(),
        ]);

        return redirect()->route('halls.index')->with('success', 'Hall updated successfully!');
    }

    /**
     * Remove the specified hall from storage.
     *
     * @param  \App\Models\Hall  $hall
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Hall $hall)
    {
        $hallId = $hall->hall_id;
        $hall->delete();

        AuditLog::create([
            'log_title' => 'Deleted Hall ' . $hallId,
            'performed_by' => Auth::id(),
            'date_performed' => Carbon::now()->toDateString(),
            'time_performed' => Carbon::now()->toTimeString(),
        ]);

        return redirect()->route('halls.index')->with('success', 'Hall deleted successfully!');
    }

    /**
     * Display an overview of all halls.
     *
     * @return \Illuminate\View\View
     */
    public function showOverview()
    {
        $halls = Hall::all();
        return view('halloverview', ['halls' => $halls]);
    }

    /**
     * Get a list of available halls.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailableHalls()
    {
        $halls = Hall::where('current_state', 'available')->get(['hall_id', 'hall_type', 'capacity']);
        return response()->json($halls);
    }

    public function clearHalls()
    {
        Hall::truncate();

        AuditLog::create([
            'log_title' => 'All hall records deleted',
            'performed_by' => Auth::id(),
            'date_performed' => Carbon::now()->toDateString(),
            'time_performed' => Carbon::now()->toTimeString(),
        ]);

        return redirect()->route('systemsetting')->with('success', 'All hall records have been cleared successfully.');
    }
}