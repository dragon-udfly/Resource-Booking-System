<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hall;
use App\Models\HallBooking;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use App\Mail\HallBookingSubmitted;

class HallBookingController extends Controller
{
    /**
     * Show the form for creating a new hall booking.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $halls = Hall::where('current_state', 'available')->get();
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
            'applicant_email' => 'required|email|max:255',
            'applicant_type' => 'required|string',
            'hall_id' => [
                'required',
                'string',
                Rule::exists('hall', 'hall_id'),
            ],
            'programme' => 'required|string|max:200',
            'event_date' => 'required|date',
            'event_time' => 'required|date_format:H:i',
            'participants' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) use ($request) {
                    $hall = Hall::find($request->hall_id);
                    if ($hall && $value > $hall->capacity) {
                        $fail('The number of participants exceeds the capacity of the selected hall (' . $hall->capacity . ').');
                    }
                },
            ],
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

        $bookingData = [
            'booking_id' => $newBookingId,
            'applicant_name' => $request->applicant_name,
            'applicant_email' => $request->applicant_email,
            'applicant_type' => $request->applicant_type,
            'requested_hall_type' => $hall->hall_type,
            'hall_id' => $request->hall_id,
            'programme' => $request->programme,
            'event_date' => $request->event_date,
            'event_time' => $request->event_time,
            'participants' => $request->participants,
            'event_duration' => $request->event_duration,
            'paid_status' => $request->paid_status,
            'is_emergency_booking' => $request->is_emergency_booking,
            'filled_by_nic' => $request->filled_by_nic,
            'filled_by_phone' => $request->filled_by_phone,
            'date_created' => Carbon::now(),
            'date_modified' => Carbon::now(),
        ];

        if ($request->is_emergency_booking) {
            $bookingData['final_approval'] = 'approved';
            $bookingData['administrative_officer_approved'] = 'approved';
            $bookingData['additional_government_agent_approved'] = 'approved';
            $bookingData['government_agent_approved'] = 'approved';
        }

        $booking = HallBooking::create($bookingData);

        AuditLog::create([
            'log_title' => 'New hall booking created ' . $newBookingId,
            'performed_by' => Auth::check() ? Auth::id() : null,
            'details' => Auth::check() ? null : 'Booking by officer with NIC: ' . $request->filled_by_nic,
            'date_performed' => Carbon::now()->toDateString(),
            'time_performed' => Carbon::now()->toTimeString(),
        ]);

        try {
            Mail::to($request->applicant_email)->send(new HallBookingSubmitted($request->applicant_name, $request->programme));
        } catch (\Exception $e) {
            // Log the error or handle it silently so the booking process isn't interrupted
        }

        return redirect()->route('halls.schedule')->with('success', 'Hall booking request submitted successfully!');
    }

    /**
     * Display the hall booking schedule.
     *
     * @return \Illuminate\View\View
     */
    public function showSchedule()
    {
        $bookings = HallBooking::whereHas('hall', function ($query) {
            $query->where('current_state', 'available');
        })->with('hall')->get();
        return view('hallschedule', ['bookings' => $bookings]);
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

    public function updateBooking(Request $request, HallBooking $hallBooking)
    {
        // Authorization: Ensure the authenticated user is the requester of this booking
        if (Auth::user()->nic_number !== $hallBooking->filled_by_nic) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        // Check if any approval is not 'pending'
        if ($hallBooking->administrative_officer_approved !== 'pending' ||
            $hallBooking->additional_government_agent_approved !== 'pending' ||
            $hallBooking->government_agent_approved !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Booking cannot be modified after approval status has changed.'], 403);
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'applicant_name' => 'required|string|max:200',
            'applicant_type' => 'required|string',
            'programme' => 'required|string|max:200',
            'event_date' => 'required|date',
            'event_time' => 'required|date_format:H:i',
            'participants' => 'required|integer',
            'event_duration' => 'required|numeric',
            'paid_status' => 'required|string',
            'is_emergency_booking' => 'required|boolean',
            'hall_id' => [
                'required',
                'string',
                Rule::exists('hall', 'hall_id'),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        // Update booking details
        $hallBooking->update([
            'applicant_name' => $request->applicant_name,
            'applicant_type' => $request->applicant_type,
            'programme' => $request->programme,
            'event_date' => $request->event_date,
            'event_time' => $request->event_time,
            'participants' => $request->participants,
            'event_duration' => $request->event_duration,
            'paid_status' => $request->paid_status,
            'is_emergency_booking' => $request->is_emergency_booking,
            'hall_id' => $request->hall_id,
            'date_modified' => Carbon::now(),
            // Reset approvals to pending after modification
            'administrative_officer_approved' => 'pending',
            'additional_government_agent_approved' => 'pending',
            'government_agent_approved' => 'pending',
        ]);

        // Audit Log
        AuditLog::create([
            'log_title' => 'Hall booking ' . $hallBooking->booking_id . ' modified by requester',
            'performed_by' => Auth::id(),
            'date_performed' => Carbon::now()->toDateString(),
            'time_performed' => Carbon::now()->toTimeString(),
        ]);

        return response()->json(['success' => true, 'message' => 'Booking updated successfully. Approvals reset to pending.']);
    }

    public function destroyBooking(HallBooking $hallBooking)
    {
        // Authorization: Ensure the authenticated user is the requester of this booking
        if (Auth::user()->nic_number !== $hallBooking->filled_by_nic) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        // Check if any approval is not 'pending'
        if ($hallBooking->administrative_officer_approved !== 'pending' ||
            $hallBooking->additional_government_agent_approved !== 'pending' ||
            $hallBooking->government_agent_approved !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Booking cannot be cancelled after approval status has changed.'], 403);
        }

        $bookingId = $hallBooking->booking_id;
        $hallBooking->delete();

        // Audit Log
        AuditLog::create([
            'log_title' => 'Hall booking ' . $bookingId . ' cancelled by requester',
            'performed_by' => Auth::id(),
            'date_performed' => Carbon::now()->toDateString(),
            'time_performed' => Carbon::now()->toTimeString(),
        ]);

        return response()->json(['success' => true, 'message' => 'Booking cancelled successfully.']);
    }
}