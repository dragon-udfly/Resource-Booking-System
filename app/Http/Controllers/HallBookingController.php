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
use Barryvdh\DomPDF\Facade\Pdf;

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

        // Check for existing booking for the same hall and date (excluding rejected and cancelled ones)
        $existingBooking = HallBooking::where('hall_id', $request->hall_id)
            ->where('event_date', $request->event_date)
            ->whereNotIn('final_approval', ['rejected', 'cancelled'])
            ->exists();

        if ($existingBooking) {
            return redirect()->back()->withErrors(['hall_id' => 'The selected hall is already booked for this date.'])->withInput();
        }

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
            'log_title' => 'New Hall Booking Application ' . $newBookingId . ' submitted',
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
        $bookings = HallBooking::whereNotIn('final_approval', ['rejected', 'cancelled'])
            ->whereHas('hall', function ($query) {
                $query->where('current_state', 'available');
            })->with('hall')->get();

        $upcomingBookings = HallBooking::whereNotIn('final_approval', ['rejected', 'cancelled'])
            ->whereHas('hall', function ($query) {
                $query->where('current_state', 'available');
            })
            ->where('event_date', '>=', Carbon::today()->toDateString())
            ->orderBy('event_date', 'asc')
            ->orderBy('event_time', 'asc')
            ->with('hall')
            ->get();

        return view('hallschedule', ['bookings' => $bookings, 'upcomingBookings' => $upcomingBookings]);
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
        if (
            $hallBooking->administrative_officer_approved !== 'pending' ||
            $hallBooking->additional_government_agent_approved !== 'pending' ||
            $hallBooking->government_agent_approved !== 'pending'
        ) {
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

        // Check for existing booking for the same hall and date (excluding current booking and rejected/cancelled ones)
        $existingBooking = HallBooking::where('hall_id', $request->hall_id)
            ->where('event_date', $request->event_date)
            ->whereNotIn('final_approval', ['rejected', 'cancelled'])
            ->where('booking_id', '!=', $hallBooking->booking_id)
            ->exists();

        if ($existingBooking) {
            return response()->json(['success' => false, 'message' => 'The selected hall is already booked for this date.'], 422);
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
            'log_title' => 'Hall Booking Application ' . $hallBooking->booking_id . ' details updated by requester',
            'performed_by' => Auth::id(),
            'date_performed' => Carbon::now()->toDateString(),
            'time_performed' => Carbon::now()->toTimeString(),
        ]);

        return response()->json(['success' => true, 'message' => 'Booking updated successfully. Approvals reset to pending.']);
    }

    public function destroyBooking(Request $request, HallBooking $hallBooking)
    {
        // Authorization: Ensure the authenticated user is the requester of this booking
        if (Auth::user()->nic_number !== $hallBooking->filled_by_nic) {
            $errorMessage = 'Unauthorized action.';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $errorMessage], 403);
            }
            return redirect()->back()->with('error', $errorMessage);
        }

        // Check if any approval is not 'pending'
        if (
            $hallBooking->administrative_officer_approved !== 'pending' ||
            $hallBooking->additional_government_agent_approved !== 'pending' ||
            $hallBooking->government_agent_approved !== 'pending'
        ) {
            $errorMessage = 'Booking cannot be cancelled after approval process has started.';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $errorMessage], 403);
            }
            return redirect()->back()->with('error', $errorMessage);
        }

        try {
            $bookingId = $hallBooking->booking_id;
            $hallBooking->delete();

            // Audit Log
            AuditLog::create([
                'log_title' => 'Hall Booking Application ' . $bookingId . ' cancelled by requester',
                'performed_by' => Auth::id(),
                'date_performed' => Carbon::now()->toDateString(),
                'time_performed' => Carbon::now()->toTimeString(),
            ]);

            $successMessage = 'Booking cancelled successfully.';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => $successMessage]);
            }
            return redirect()->back()->with('success', $successMessage);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Booking deletion failed: ' . $e->getMessage());
            $errorMessage = 'An unexpected error occurred while deleting the booking.';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $errorMessage], 500);
            }
            return redirect()->back()->with('error', $errorMessage);
        }
    }

    public function downloadPDF(HallBooking $hallBooking)
    {
        $data = [
            'booking' => $hallBooking,
            'date' => Carbon::now()->format('Y-m-d')
        ];

        $pdf = Pdf::loadView('pdf.hall_booking_form', $data);
        return $pdf->download('hall_booking_Application_' . $hallBooking->booking_id . '.pdf');
    }

    public function approve(HallBooking $hallBooking)
    {
        $user = Auth::user();
        $updated = false;
        $role_action = 'Approved';

        if ($user->hasPermissionTo('administrative_officer_approval') && $hallBooking->administrative_officer_approved === 'pending') {
            $hallBooking->administrative_officer_approved = 'approved';
            $role_action = 'Administrative Officer Approval granted';
            $updated = true;
        } elseif ($user->hasPermissionTo('additional_government_agent_approval') && $hallBooking->additional_government_agent_approved === 'pending') {
            $hallBooking->additional_government_agent_approved = 'approved';
            $role_action = 'Additional Government Agent Approval granted';
            $updated = true;
        } elseif ($user->hasPermissionTo('government_agent_approval') && $hallBooking->government_agent_approved === 'pending') {
            $hallBooking->government_agent_approved = 'approved';
            // Only the Government Agent's approval sets the final status to approved
            $hallBooking->final_approval = 'approved';
            $role_action = 'Government Agent Approval granted';
            $updated = true;
        }

        if ($updated) {
            $hallBooking->save();

            AuditLog::create([
                'log_title' => $role_action . ' for booking ' . $hallBooking->booking_id,
                'performed_by' => $user->user_id,
                'date_performed' => Carbon::now()->toDateString(),
                'time_performed' => Carbon::now()->toTimeString(),
            ]);

            return response()->json(['success' => true, 'message' => 'Booking approved successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'You do not have permission to approve this booking or it is already processed.'], 403);
    }

    public function reject(HallBooking $hallBooking)
    {
        $user = Auth::user();
        $updated = false;
        $role_action = 'Rejected';

        if ($user->hasPermissionTo('administrative_officer_approval') && $hallBooking->administrative_officer_approved === 'pending') {
            $hallBooking->administrative_officer_approved = 'rejected';
            $role_action = 'Administrative Officer Rejection';
            $updated = true;
        } elseif ($user->hasPermissionTo('additional_government_agent_approval') && $hallBooking->additional_government_agent_approved === 'pending') {
            $hallBooking->additional_government_agent_approved = 'rejected';
            $role_action = 'Additional Government Agent Rejection';
            $updated = true;
        } elseif ($user->hasPermissionTo('government_agent_approval') && $hallBooking->government_agent_approved === 'pending') {
            $hallBooking->government_agent_approved = 'rejected';
            // Only the Government Agent's rejection sets the final status to rejected (per specific requirement)
            $hallBooking->final_approval = 'rejected';
            $role_action = 'Government Agent Rejection';
            $updated = true;
        }

        if ($updated) {
            $hallBooking->save();

            AuditLog::create([
                'log_title' => $role_action . ' for booking ' . $hallBooking->booking_id,
                'performed_by' => $user->user_id,
                'date_performed' => Carbon::now()->toDateString(),
                'time_performed' => Carbon::now()->toTimeString(),
            ]);

            return response()->json(['success' => true, 'message' => 'Booking rejected successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'You do not have permission to reject this booking or it is already processed.'], 403);
    }

    public function cancelApproved(Request $request, HallBooking $hallBooking)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        if (Auth::user()->hasPermissionTo('government_agent_approval')) {
            if ($hallBooking->final_approval === 'approved') {
                $hallBooking->final_approval = 'cancelled';
                $hallBooking->reason_of_rejection = $request->reason;
                // Reset internal approvals just in case, or leave them as history? 
                // Typically cancellation keeps the record that it WAS approved but now cancelled.
                $hallBooking->save();

                AuditLog::create([
                    'log_title' => 'Approved booking ' . $hallBooking->booking_id . ' cancelled by Government Agent. Reason: ' . $request->reason,
                    'performed_by' => Auth::id(),
                    'date_performed' => Carbon::now()->toDateString(),
                    'time_performed' => Carbon::now()->toTimeString(),
                ]);

                return response()->json(['success' => true, 'message' => 'Booking cancelled successfully.']);
            }
            return response()->json(['success' => false, 'message' => 'This booking is not currently approved.'], 422);
        }
        return response()->json(['success' => false, 'message' => 'You do not have permission to cancel this booking.'], 403);
    }

    public function reApprove(Request $request, HallBooking $hallBooking)
    {
        if (Auth::user()->hasPermissionTo('government_agent_approval')) {
            if ($hallBooking->final_approval === 'cancelled') {
                $hallBooking->final_approval = 'approved';
                $hallBooking->reason_of_rejection = null;
                $hallBooking->save();

                AuditLog::create([
                    'log_title' => 'Cancelled booking ' . $hallBooking->booking_id . ' re-approved by Government Agent',
                    'performed_by' => Auth::id(),
                    'date_performed' => Carbon::now()->toDateString(),
                    'time_performed' => Carbon::now()->toTimeString(),
                ]);

                return response()->json(['success' => true, 'message' => 'Booking re-approved successfully.']);
            }
            return response()->json(['success' => false, 'message' => 'This booking is not currently cancelled.'], 422);
        }
        return response()->json(['success' => false, 'message' => 'You do not have permission to re-approve this booking.'], 403);
    }

    public function showHistory()
    {
        $bookings = HallBooking::where('final_approval', '!=', 'pending')
            ->orderBy('date_created', 'desc')
            ->get();

        $user = Auth::user();
        $canManageBookings = $user->hasPermissionTo('administrative_officer_approval') ||
            $user->hasPermissionTo('additional_government_agent_approval') ||
            $user->hasPermissionTo('government_agent_approval');

        return view('history', [
            'bookings' => $bookings,
            'canManageBookings' => $canManageBookings,
        ]);
    }
}