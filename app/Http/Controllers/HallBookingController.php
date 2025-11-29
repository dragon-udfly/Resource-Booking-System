<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hall;
use App\Models\HallBooking;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class HallBookingController extends Controller
{
    /**
     * Show the form for creating a new hall booking.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $halls = Hall::all();
        return view('bookhall', ['halls' => $halls]);
    }

    /**
     * Store a newly created hall booking in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'applicant_name' => 'required|string|max:200',
            'applicant_type' => 'required|string',
            'hall_id' => 'required|string|exists:hall,hall_id',
            'programme' => 'required|string|max:200',
            'event_date' => 'required|date',
            'participants' => 'required|integer',
            'event_duration' => 'required|numeric',
            'paid_status' => 'required|string',
            'is_emergency_booking' => 'required|boolean',
            'filled_by_nic' => 'required|string|max:50',
            'filled_by_phone' => 'required|string|max:50',
        ]);

        // Generate booking_id
        $lastBooking = HallBooking::orderBy('booking_id', 'desc')->first();
        $nextBookingIdNumber = 1;
        if ($lastBooking) {
            $lastBookingIdNumber = (int) Str::after($lastBooking->booking_id, 'bookH');
            $nextBookingIdNumber = $lastBookingIdNumber + 1;
        }
        $newBookingId = 'bookH' . str_pad($nextBookingIdNumber, 3, '0', STR_PAD_LEFT);

        $hall = Hall::find($request->hall_id);

        $booking = HallBooking::create([
            'booking_id' => $newBookingId,
            'applicant_name' => $request->applicant_name,
            'applicant_type' => $request->applicant_type,
            'requested_hall_type' => $hall->hall_type,
            'hall_id' => $request->hall_id,
            'programme' => $request->programme,
            'event_date' => $request->event_date,
            'participants' => $request->participants,
            'event_duration' => $request->event_duration,
            'paid_status' => $request->paid_status,
            'is_emergency_booking' => $request->is_emergency_booking,
            'filled_by_nic' => $request->filled_by_nic,
            'filled_by_phone' => $request->filled_by_phone,
            'date_created' => Carbon::now(),
            'date_modified' => Carbon::now(),
        ]);

        AuditLog::create([
            'log_title' => 'New hall booking created ' . $newBookingId,
            'performed_by' => Auth::check() ? Auth::id() : null,
            'details' => Auth::check() ? null : 'Booking by officer with NIC: ' . $request->filled_by_nic,
            'date_performed' => Carbon::now()->toDateString(),
            'time_performed' => Carbon::now()->toTimeString(),
        ]);

        return redirect()->route('halls.book')->with('success', 'Hall booking request submitted successfully!');
    }
}