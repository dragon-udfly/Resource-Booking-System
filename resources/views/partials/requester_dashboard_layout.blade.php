<div class="page-header">
    <h2 style="color: rgb(6, 4, 60); font-weight: bold">Submitted Forms</h2>
    <p>Review the status of your hall and quarter booking requests.</p>
</div>
<h2 style="text-align: center; color:rgb(34, 60, 4)">Hall Booking Applications</h2>
<table id="requester-bookings-table">
    <thead>
        <tr>
            <th>Applicant Name</th>
            <th>Submitted Date</th>
            <th>Event Date</th>
            <th>AO Approval</th>
            <th>AGA Approval</th>
            <th>GA Approval</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($requesterBookings as $booking)
            <tr data-booking='{{ json_encode($booking) }}'>
                <td>{{ ucfirst($booking->applicant_name) }}</td>
                <td>{{ \Carbon\Carbon::parse($booking->date_created)->format('Y-m-d h:i A') }}</td>
                <td>{{ \Carbon\Carbon::parse($booking->event_date)->format('Y-m-d') }}</td>
                <td>{{ ucfirst($booking->administrative_officer_approved) }}</td>
                <td>{{ ucfirst($booking->additional_government_agent_approved) }}</td>
                <td>{{ ucfirst($booking->government_agent_approved) }}</td>
                <td>
                    <a href="{{ route('hall_bookings.review', $booking->booking_id) }}" class="action-btn review-link-btn"
                        style="text-decoration: none; text-align: center;">Review</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 12px 15px;">No submitted forms found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<br />
<br />
<h2 style="text-align: center; color:rgb(34, 60, 4)">Quarters Reservation Applications</h2>
<table id="requester-quarters-table">
    <thead>
        <tr>
            <th>Applicant Name</th>
            <th>Designation</th>
            <th>Submitted Date</th>
            <th>Type</th>
            <th>AO Verification</th>
            <th>AGA Verification</th>
            <th>GA Approval</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($quarterApplications as $application)
            <tr>
                <td>{{ $application->officer_name }}</td>
                <td>{{ $application->designation }}</td>
                <td>{{ \Carbon\Carbon::parse($application->date_created)->format('Y-m-d h:i A') }}</td>
                <td>{{ $application->quarter_type }}</td>
                <td>
                    @if($application->quarterAllocation)
                        @if($application->quarterAllocation->is_ao_verified === 1)
                            <span style="color: green; font-weight: bold;">Yes</span>
                        @elseif($application->quarterAllocation->is_ao_verified === 0)
                            <span style="color: red; font-weight: bold;">No</span>
                        @else
                            <span style="color: gray;">Pending</span>
                        @endif
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    @if($application->quarterAllocation)
                        @if($application->quarterAllocation->is_aga_verified === 1)
                            <span style="color: green; font-weight: bold;">Yes</span>
                        @elseif($application->quarterAllocation->is_aga_verified === 0)
                            <span style="color: red; font-weight: bold;">No</span>
                        @else
                            <span style="color: gray;">Pending</span>
                        @endif
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $application->quarterAllocation ? ucfirst($application->quarterAllocation->allocation_status) : 'N/A' }}
                </td>
                <td>
                    @if(strtolower($application->quarter_type) === 'scheduled')
                        <a href="{{ route('scheduled-quarter.review', $application->application_id) }}"
                            class="action-btn review-quarter-btn" style="text-decoration: none; text-align: center;">Review</a>
                    @elseif(strtolower($application->quarter_type) === 'family')
                        <a href="{{ route('family-quarter.review', $application->application_id) }}"
                            class="action-btn review-quarter-btn" style="text-decoration: none; text-align: center;">Review</a>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 12px 15px;">No pending quarter applications found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- Transparent Overlay for Review/Modify --}}
{{-- Review Overlay Removed --}}

{{-- Global Confirmation Overlay --}}
<div id="global-confirmation-overlay"
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); z-index: 1002; justify-content: center; align-items: center;">
    <div
        style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3); max-width: 400px; text-align: center;">
        <p id="global-confirmation-message" style="font-size: 1.2em; color: #333; margin-bottom: 20px;"></p>
        <div style="display: flex; justify-content: center; gap: 10px;">
            <button id="global-confirm-btn"
                style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 1em;">Confirm</button>
            <button id="global-cancel-btn"
                style="background-color: #6c757d; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 1em;">Cancel</button>
        </div>
    </div>
</div>


<style>
    #review-overlay,
    #global-confirmation-overlay {
        display: flex;
    }

    /* Add styles for table and overlay if needed, similar to existing dashboard styles */

    /* Specific styles for the review button to ensure visibility */
    .action-btn.review-btn,
    .action-btn.review-quarter-btn {
        display: inline-block;
        padding: 8px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        color: white;
        font-size: 0.9em;
        transition: background-color 0.3s ease;
        background-color: #007bff;
        /* Example background color */
    }

    .action-btn.review-btn:hover,
    .action-btn.review-quarter-btn:hover {
        background-color: #0056b3;
        /* Example hover color */
    }

    /* Styles for the dynamically generated form fields within the overlay */
    #overlay-form-content .form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 15px;
    }

    #overlay-form-content .form-group {
        flex: 1;
        min-width: 250px;
        /* Adjust as needed */
    }

    #overlay-form-content label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: #555;
        font-size: 0.9em;
    }

    #overlay-form-content input[type="text"],
    #overlay-form-content input[type="number"],
    #overlay-form-content input[type="date"],
    #overlay-form-content input[type="tel"],
    #overlay-form-content select {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        font-size: 1em;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    #overlay-form-content input[readonly] {
        background-color: #e9ecef;
        cursor: not-allowed;
    }

    #overlay-form-content input:focus,
    #overlay-form-content select:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
</style>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {


            // Global confirmation overlay elements
            const globalConfirmationOverlay = document.getElementById('global-confirmation-overlay');
            const globalConfirmationMessage = document.getElementById('global-confirmation-message');
            const globalConfirmBtn = document.getElementById('global-confirm-btn');
            const globalCancelBtn = document.getElementById('global-cancel-btn');

            let currentBookingId = null; // To keep track of the booking being acted upon
            let confirmActionCallback = null; // To store the function to call if confirmed

            // Function to show global confirmation overlay (with Confirm/Cancel buttons)
            function showGlobalConfirmation(message, callback) {
                globalConfirmationMessage.textContent = message;
                globalConfirmBtn.textContent = 'Confirm';
                globalConfirmBtn.style.display = 'inline-block';
                globalCancelBtn.style.display = 'inline-block';
                globalConfirmationOverlay.style.display = 'flex';
                confirmActionCallback = callback;
            }

            // Function to show a simple info/error overlay (with only an OK button)
            function showInfoOverlay(message) {
                globalConfirmationMessage.textContent = message;
                globalConfirmBtn.textContent = 'OK';
                globalConfirmBtn.style.display = 'inline-block';
                globalCancelBtn.style.display = 'none'; // Hide cancel button for info messages
                globalConfirmationOverlay.style.display = 'flex';
                confirmActionCallback = null; // No action needed for 'OK'
            }

            // Function to hide global confirmation overlay
            function hideGlobalConfirmation() {
                globalConfirmationOverlay.style.display = 'none';
                globalConfirmationMessage.textContent = '';
                confirmActionCallback = null;
            }

            // Global confirmation button event listeners
            globalConfirmBtn.addEventListener('click', function () {
                if (confirmActionCallback) {
                    confirmActionCallback();
                }
                hideGlobalConfirmation();
            });

            globalCancelBtn.addEventListener('click', hideGlobalConfirmation);







        });
    </script>
@endpush