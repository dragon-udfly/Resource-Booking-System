<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hall;
use App\Models\HallBooking;
use App\Models\AuditLog;
use App\Mail\HallBookingApproved;
use App\Mail\HallBookingCancelled;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

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

        Log::info("[Hall Booking] Action: New Booking Submitted | ID: {$newBookingId} | Applicant: {$request->applicant_name}");

        AuditLog::create([
            'log_title' => 'New Hall Booking Application ' . $newBookingId . ' submitted',
            'performed_by' => Auth::id(),
            'details' => Auth::check() ? null : 'Booking by officer with NIC: ' . $request->filled_by_nic,
            'date_performed' => Carbon::now()->toDateString(),
            'time_performed' => Carbon::now()->toTimeString(),
        ]);

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
        $user = Auth::user();

        // 1. Verify Ownership / Permissions check
        // Assuming destroy is primarily for Requester/Owner or an Approver managing cleanups.
        // The original code was strict on Requester Ownership:
        // if ($user->nic_number !== $hallBooking->filled_by_nic) ...
        // We should maintain this strictness for "Requesters" acting as Requesters.
        // But if an AO is deleting (as per new rule), we need to allow it.

        $isRequester = $user->hasPermissionTo('requester');
        $isAO = $user->hasPermissionTo('administrative_officer_approval');

        // Ownership check for Requester
        if ($isRequester && !$isAO && $user->nic_number !== $hallBooking->filled_by_nic) {
            return $this->sendError($request, 'Unauthorized action.');
        }

        // 2. Check Event Date
        $isPastEvent = Carbon::parse($hallBooking->event_date)->startOfDay()->lt(Carbon::today());

        if ($isPastEvent) {
            // Rule: History Cleanup
            // Allow deletion if event passed. 
            // Logic implies Owner can delete their own history. 
            // Can AO delete anyone's history? Plan didn't specify, but implies "Allow users to delete... from THEIR history".
            // So strict ownership for Requesters is good. AO might need to delete old records too?
            // Let's stick to: If you have access (Owner or Approver?), you can delete past events.
            // Given the context of "View History", it's usually the Requester viewing their own.

            // If NOT owner and NOT authorized approver?
            // The route is protected by auth. 
            // Let's assume the previous ownership check covers it.

        } else {
            // Future/Active Event Logic

            if ($isRequester && !$isAO) {
                // PA Rule: Can delete ONLY if all status is 'pending'
                if (
                    $hallBooking->administrative_officer_approved !== 'pending' ||
                    $hallBooking->additional_government_agent_approved !== 'pending' ||
                    $hallBooking->government_agent_approved !== 'pending'
                ) {
                    return $this->sendError($request, 'Booking cannot be cancelled after approval process has started.');
                }
            } elseif ($isAO) {
                // AO Rule: Can delete if Final Status is SET (e.g. they rejected it) AND GA Status is Pending.
                // Interpreting "Final Status is SET" as != 'pending'.
                // Interpreting "GA Status is Pending" as == 'pending'.

                $finalStatusSet = $hallBooking->final_approval !== 'pending';
                $gaPending = $hallBooking->government_agent_approved === 'pending';

                if (!($finalStatusSet && $gaPending)) {
                    return $this->sendError($request, 'Cannot delete: Application must be finalized (e.g. Rejected) but not yet processed by GA.');
                }
            } else {
                // Other roles (e.g. AGA, GA)? Default block or allow?
                // Original code blocked everything non-pending.
                // Let's maintain block for safety unless specified.
                return $this->sendError($request, 'Unauthorized deletion request.');
            }
        }

        try {
            $bookingId = $hallBooking->booking_id;
            $hallBooking->delete();

            // Audit Log
            AuditLog::create([
                'log_title' => 'Hall Booking Application ' . $bookingId . ' deleted by ' . $user->name,
                'performed_by' => Auth::id(),
                'date_performed' => Carbon::now()->toDateString(),
                'time_performed' => Carbon::now()->toTimeString(),
            ]);

            $successMessage = 'Booking deleted successfully.';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => $successMessage]);
            }
            return redirect()->back()->with('success', $successMessage);

        } catch (\Exception $e) {
            Log::error('Booking deletion failed: ' . $e->getMessage());
            return $this->sendError($request, 'An unexpected error occurred while deleting the booking.', 500);
        }
    }

    private function sendError($request, $message, $code = 403)
    {
        if ($request->wantsJson()) {
            return response()->json(['status' => 'error', 'message' => $message], $code);
        }
        return redirect()->back()->with('error', $message);
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

    public function review(HallBooking $hallBooking)
    {
        $user = Auth::user();

        // Check if user has permission to view
        $isApprover = $user->hasPermissionTo('administrative_officer_approval') ||
            $user->hasPermissionTo('additional_government_agent_approval') ||
            $user->hasPermissionTo('government_agent_approval');

        if (!$isApprover && $user->hasPermissionTo('requester')) {
            // Check ownership using NIC number as user_id is not present in HallBooking
            if ($hallBooking->filled_by_nic != $user->nic_number) {
                Log::info('Review Check Failed (NIC Mismatch)', [
                    'user_nic' => $user->nic_number,
                    'booking_nic' => $hallBooking->filled_by_nic,
                ]);
                abort(403, 'Unauthorized access: NIC Mismatch (' . ($hallBooking->filled_by_nic ?? 'null') . ' vs ' . ($user->nic_number ?? 'null') . ')');
            }
        }

        if (
            !$user->hasPermissionTo('requester') &&
            !$user->hasPermissionTo('administrative_officer_approval') &&
            !$user->hasPermissionTo('additional_government_agent_approval') &&
            !$user->hasPermissionTo('government_agent_approval')
        ) {
            abort(403, 'Unauthorized access');
        }

        return view('hall_booking.review', compact('hallBooking'));
    }

    public function showProcessed(HallBooking $hallBooking)
    {
        $user = Auth::user();
        $isApprover = $user->hasPermissionTo('administrative_officer_approval') ||
            $user->hasPermissionTo('additional_government_agent_approval') ||
            $user->hasPermissionTo('government_agent_approval');

        if (!$isApprover && $user->hasPermissionTo('requester')) {
            if ($hallBooking->filled_by_nic != $user->nic_number) {
                abort(403, 'Unauthorized access: You can only view your own bookings.');
            }
        }

        return view('hall_booking.processed', compact('hallBooking'));
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

            Log::info("[Hall Booking] Action: {$role_action} | ID: {$hallBooking->booking_id} | User: {$user->id}");

            // Send Email Notification if Approved by GA
            if ($hallBooking->final_approval === 'approved') {
                EmailController::sendEmail($hallBooking->applicant_email, 'hall_approved', $hallBooking);
            }

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

            Log::info("[Hall Booking] Action: {$role_action} | ID: {$hallBooking->booking_id} | User: {$user->id}");

            // Send Email Notification if Rejected by GA
            if ($hallBooking->final_approval === 'rejected') {
                EmailController::sendEmail($hallBooking->applicant_email, 'hall_cancelled', $hallBooking);
            }

            return response()->json(['success' => true, 'message' => 'Booking rejected successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'You do not have permission to reject this booking or it is already processed.'], 403);
    }

    public function cancelApproved(Request $request, HallBooking $hallBooking)
    {
        $user = Auth::user();
        $isRequester = $user->hasPermissionTo('requester');

        // Reason validation
        $rules = [
            'reason' => ($isRequester) ? 'nullable|string|max:500' : 'required|string|max:500',
        ];
        // AO Reason is optional
        if ($user->hasPermissionTo('administrative_officer_approval')) {
            $rules['reason'] = 'nullable|string|max:500';
        }
        $request->validate($rules);

        // 1. Government Agent (GA) Logic
        // Can ONLY cancel if currently 'approved' (Revoke)
        if ($user->hasPermissionTo('government_agent_approval')) {
            if ($hallBooking->final_approval === 'approved') {
                $hallBooking->final_approval = 'cancelled';
                $hallBooking->reason_of_rejection = $request->reason;
                $hallBooking->save();

                AuditLog::create([
                    'log_title' => 'Approved booking ' . $hallBooking->booking_id . ' cancelled by Government Agent. Reason: ' . $request->reason,
                    'performed_by' => Auth::id(),
                    'date_performed' => Carbon::now()->toDateString(),
                    'time_performed' => Carbon::now()->toTimeString(),
                ]);

                // Send Cancellation Email
                EmailController::sendEmail($hallBooking->applicant_email, 'hall_cancelled', $hallBooking);

                Log::info("[Hall Booking] Action: Cancelled by GA | ID: {$hallBooking->booking_id} | User: {$user->id} | Reason: {$request->reason}");

                return response()->json(['success' => true, 'message' => 'Booking cancelled successfully.']);
            }
            // Logic moved from blade: If not approved, GA cannot cancel (use Reject instead)
            return response()->json(['success' => false, 'message' => 'GA can only cancel finalized bookings. Use Reject for pending ones.'], 422);
        }

        // 2. Requester Logic
        // Can ONLY cancel if ALL approvals are pending (Total Pending)
        if ($isRequester) {
            // Verify ownership
            if ($hallBooking->filled_by_nic !== $user->nic_number) {
                if (!$user->hasPermissionTo('administrative_officer_approval') && !$user->hasPermissionTo('government_agent_approval')) {
                    abort(403, 'Unauthorized access: You can only cancel your own bookings.');
                }
            } else {
                // Strict Check: Must be totally pending
                if (
                    $hallBooking->administrative_officer_approved !== 'pending' ||
                    $hallBooking->additional_government_agent_approved !== 'pending' ||
                    $hallBooking->final_approval !== 'pending'
                ) {

                    return response()->json(['success' => false, 'message' => 'You cannot cancel this booking as it is already being processed.'], 403);
                }

                $hallBooking->final_approval = 'cancelled';
                $hallBooking->reason_of_rejection = 'Cancelled by Requester';
                $hallBooking->save();

                AuditLog::create([
                    'log_title' => 'Booking ' . $hallBooking->booking_id . ' cancelled by Requester.',
                    'performed_by' => Auth::id(),
                    'date_performed' => Carbon::now()->toDateString(),
                    'time_performed' => Carbon::now()->toTimeString(),
                ]);

                return response()->json(['success' => true, 'message' => 'Booking cancelled successfully.']);
            }
        }

        // 3. Administrative Officer (AO) Logic
        // Can ONLY cancel if final_approval is pending
        if ($user->hasPermissionTo('administrative_officer_approval')) {
            if ($hallBooking->final_approval !== 'pending') {
                return response()->json(['success' => false, 'message' => 'You cannot cancel a booking that has already been finalized.'], 403);
            }

            $hallBooking->final_approval = 'cancelled';
            $hallBooking->reason_of_rejection = $request->reason ?? 'Cancelled by AO';
            $hallBooking->save();

            AuditLog::create([
                'log_title' => 'Booking ' . $hallBooking->booking_id . ' cancelled by Administrative Officer.',
                'performed_by' => Auth::id(),
                'date_performed' => Carbon::now()->toDateString(),
                'time_performed' => Carbon::now()->toTimeString(),
            ]);
            return response()->json(['success' => true, 'message' => 'Booking cancelled successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'You do not have permission to cancel this booking.'], 403);
    }

    public function reApprove(Request $request, HallBooking $hallBooking)
    {
        if (Auth::user()->hasPermissionTo('government_agent_approval')) {
            if (in_array($hallBooking->final_approval, ['cancelled', 'rejected'])) {
                // Conflict Check: Prevent re-approval if another active booking exists for the same slot
                $existingBooking = HallBooking::where('hall_id', $hallBooking->hall_id)
                    ->where('event_date', $hallBooking->event_date)
                    ->whereNotIn('final_approval', ['rejected', 'cancelled'])
                    ->where('booking_id', '!=', $hallBooking->booking_id) // Exclude self
                    ->exists();

                if ($existingBooking) {
                    return response()->json(['success' => false, 'message' => 'Cannot re-approve: The hall has already been booked by another application for this date.'], 422);
                }

                $hallBooking->final_approval = 'approved';
                // Reset rejection reason on re-approval
                $hallBooking->reason_of_rejection = null;
                // Ensure specific GA approval column is also set to approved (if it was rejected)
                $hallBooking->government_agent_approved = 'approved';

                $hallBooking->save();

                AuditLog::create([
                    'log_title' => 'Cancelled booking ' . $hallBooking->booking_id . ' re-approved by Government Agent',
                    'performed_by' => Auth::id(),
                    'date_performed' => Carbon::now()->toDateString(),
                    'time_performed' => Carbon::now()->toTimeString(),
                ]);

                // Send Re-Approval Email (Same as Approval)
                EmailController::sendEmail($hallBooking->applicant_email, 'hall_approved', $hallBooking);

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


    public function clearBookings()
    {
        HallBooking::truncate();

        AuditLog::create([
            'log_title' => 'All hall booking records deleted',
            'performed_by' => Auth::id(),
            'date_performed' => Carbon::now()->toDateString(),
            'time_performed' => Carbon::now()->toTimeString(),
        ]);

        Log::warning("[System Action] All Hall Bookings Cleared by User ID: " . Auth::id());

        return redirect()->route('systemsetting')->with('success', 'All hall booking records have been cleared successfully.');
    }

    public function clearRejectedBookings()
    {
        HallBooking::where('final_approval', 'rejected')->delete();

        AuditLog::create([
            'log_title' => 'All rejected hall booking records deleted',
            'performed_by' => Auth::id(),
            'date_performed' => Carbon::now()->toDateString(),
            'time_performed' => Carbon::now()->toTimeString(),
        ]);

        Log::warning("[System Action] Rejected Hall Bookings Cleared by User ID: " . Auth::id());

        return redirect()->route('systemsetting')->with('success', 'All rejected hall booking records have been cleared successfully.');
    }
}