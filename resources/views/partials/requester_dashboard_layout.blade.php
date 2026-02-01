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
                    <button class="action-btn review-btn" data-booking-id="{{ $booking->booking_id }}">Review</button>
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
                    <button class="action-btn review-quarter-btn"
                        data-application-id="{{ $application->application_id }}">Review</button>
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
<div id="review-overlay"
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); z-index: 1001; justify-content: center; align-items: center;">
    <div
        style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3); max-width: 800px; width: 90%; position: relative;">
        <button id="overlay-back-btn"
            style="position: absolute; top: 10px; left: 10px; background-color: #6c757d; color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer;">Back</button>
        <h3 style="text-align: center; margin-bottom: 20px;">Review/Modify Application</h3>
        <form id="overlay-form">
            {{-- Form fields will be dynamically populated here --}}
            <div id="overlay-form-content"></div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                <button type="submit" id="overlay-modify-btn" class="action-btn"
                    style="background-color: #007bff; color: white;">Modify</button>
                <button type="button" id="overlay-cancel-btn" class="action-btn"
                    style="background-color: #dc3545; color: white;">Cancel Booking</button>
                <button type="button" id="overlay-download-btn" class="action-btn"
                    style="background-color: #29f00f; color: white;">Download</button>
            </div>
        </form>
    </div>
</div>

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
            const reviewOverlay = document.getElementById('review-overlay');
            const overlayBackBtn = document.getElementById('overlay-back-btn');
            const overlayFormContent = document.getElementById('overlay-form-content');
            const overlayModifyBtn = document.getElementById('overlay-modify-btn');
            const overlayCancelBtn = document.getElementById('overlay-cancel-btn');
            const overlayDownloadBtn = document.getElementById('overlay-download-btn');
            const overlayForm = document.getElementById('overlay-form');

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


            // Function to render form fields dynamically
            async function renderFormFields(booking) { // Made async
                console.log("renderFormFields: Starting...", booking); // Debugging log
                currentBookingId = booking.booking_id; // Set current booking ID

                let hallsOptions = '';
                try {
                    const response = await fetch('{{ route('halls.available') }}');
                    const halls = await response.json();
                    halls.forEach(hall => {
                        const selected = hall.hall_id === booking.hall_id ? 'selected' : '';
                        hallsOptions += `<option value="${hall.hall_id}" ${selected}>${hall.hall_type} (Capacity: ${hall.capacity})</option>`;
                    });
                } catch (error) {
                    console.error('renderFormFields: Error fetching halls:', error);
                    showInfoOverlay('Failed to load hall options. Please try again.');
                    overlayFormContent.innerHTML = '<p style="color: red;">Failed to load hall options.</p>'; // Provide feedback
                    return; // Stop execution if halls cannot be fetched
                }

                let fieldsHtml = `
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" name="booking_id" value="${booking.booking_id}">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="applicant_name">Applicant Name</label>
                                <input type="text" id="applicant_name" name="applicant_name" value="${booking.applicant_name}" required>
                            </div>
                            <div class="form-group">
                                <label for="applicant_type">Applicant Type</label>
                                <select id="applicant_type" name="applicant_type" required>
                                    <option value="Internal" ${booking.applicant_type === 'Internal' ? 'selected' : ''}>Internal</option>
                                    <option value="External" ${booking.applicant_type === 'External' ? 'selected' : ''}>External</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="hall_id">Hall Type</label>
                                <select id="hall_id" name="hall_id" required>
                                    ${hallsOptions}
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="programme">Programme/Event</label>
                                <input type="text" id="programme" name="programme" value="${booking.programme}" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="event_date">Event Date</label>
                                <input type="date" id="event_date" name="event_date" value="${booking.event_date}" required>
                            </div>
                             <div class="form-group">
                                <label for="event_time">Event Time</label>
                                <input type="time" id="event_time" name="event_time" value="${booking.event_time}" required>
                            </div>
                            <div class="form-group">
                                <label for="participants">Number of Participants</label>
                                <input type="number" id="participants" name="participants" value="${booking.participants}" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="event_duration">Event Duration (hours)</label>
                                <input type="number" id="event_duration" name="event_duration" step="0.1" value="${booking.event_duration}" required>
                            </div>
                            <div class="form-group">
                                <label for="paid_status">Paid Status</label>
                                <select id="paid_status" name="paid_status" required>
                                    <option value="Not Required" ${booking.paid_status === 'Not Required' ? 'selected' : ''}>Not Required</option>
                                    <option value="Yes" ${booking.paid_status === 'Yes' ? 'selected' : ''}>Yes</option>
                                    <option value="Pending" ${booking.paid_status === 'Pending' ? 'selected' : ''}>Pending</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="is_emergency_booking">Emergency Booking</label>
                                <select id="is_emergency_booking" name="is_emergency_booking" required>
                                    <option value="0" ${booking.is_emergency_booking == 0 ? 'selected' : ''}>No</option>
                                    <option value="1" ${booking.is_emergency_booking == 1 ? 'selected' : ''}>Yes</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="filled_by_nic">Requester Officer's NIC</label>
                                <input type="text" id="filled_by_nic" name="filled_by_nic" value="${booking.filled_by_nic}" readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="filled_by_phone">Requester Officer's Phone</label>
                                <input type="tel" id="filled_by_phone" name="filled_by_phone" value="${booking.filled_by_phone}" readonly>
                            </div>
                        </div>
                    `;
                overlayFormContent.innerHTML = fieldsHtml;
                console.log("renderFormFields: Finished."); // Debugging log
            }

            // Function to open the review overlay
            async function openReviewOverlay(booking) { // Made async
                console.log("openReviewOverlay: Starting...", booking); // Debugging log
                try {
                    console.log("openReviewOverlay: Awaiting renderFormFields..."); // Debugging log
                    await renderFormFields(booking); // Await rendering
                    console.log("openReviewOverlay: renderFormFields completed."); // Debugging log

                    // Check if any approval is not 'pending' to disable modify button
                    const isApproved = booking.administrative_officer_approved !== 'pending' ||
                        booking.additional_government_agent_approved !== 'pending' ||
                        booking.government_agent_approved !== 'pending';

                    if (isApproved) {
                        overlayModifyBtn.disabled = true;
                        overlayModifyBtn.style.opacity = 0.5; // Visual cue for disabled
                        overlayModifyBtn.style.cursor = 'not-allowed';
                    } else {
                        overlayModifyBtn.disabled = false;
                        overlayModifyBtn.style.opacity = 1;
                        overlayModifyBtn.style.cursor = 'pointer';
                    }

                    reviewOverlay.style.display = 'flex';
                    console.log("openReviewOverlay: Overlay displayed."); // Debugging log

                } catch (e) {
                    console.error("openReviewOverlay: Error during overlay display:", e);
                    showInfoOverlay("Error displaying review form. Please check console for details.");
                }
            }

            // Function to close the review overlay
            function closeReviewOverlay() {
                reviewOverlay.style.display = 'none';
                overlayFormContent.innerHTML = ''; // Clear form content
                currentBookingId = null;
            }

            // Event listener for all "Review" buttons for hall bookings
            document.querySelectorAll('.review-btn').forEach(button => {
                button.addEventListener('click', function () {
                    console.log("Review button clicked!"); // Debugging log
                    const row = this.closest('tr');
                    if (!row) {
                        console.error("Parent row not found for review button.");
                        showInfoOverlay("Error: Could not find booking data for review.");
                        return;
                    }
                    const bookingJson = row.dataset.booking;
                    if (!bookingJson) {
                        console.error("No 'data-booking' attribute found on row.");
                        showInfoOverlay("Error: Booking data is missing for review.");
                        return;
                    }

                    try {
                        const bookingData = JSON.parse(bookingJson);
                        console.log("Parsed Booking Data:", bookingData); // Debugging log
                        openReviewOverlay(bookingData);
                    } catch (e) {
                        console.error("Error parsing booking data:", e);
                        showInfoOverlay("Error: Could not parse booking data. Please check console for details.");
                    }
                });
            });

            // Event listener for all "Review" buttons for quarter applications
            document.querySelectorAll('.review-quarter-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const applicationId = this.dataset.applicationId;
                    if (applicationId) {
                        // Get the quarter type from the row
                        const row = this.closest('tr');
                        const quarterType = row.querySelector('td:nth-child(4)').textContent.trim();

                        // Route to the appropriate review page based on quarter type
                        if (quarterType.toLowerCase() === 'scheduled') {
                            window.location.href = `/scheduled-quarter-application/${applicationId}/review`;
                        } else if (quarterType.toLowerCase() === 'family') {
                            window.location.href = `/family-quarter-application/${applicationId}/review`;
                        } else {
                            console.error('Unknown quarter type:', quarterType);
                            showInfoOverlay('Error: Unknown quarter application type.');
                        }
                    }
                });
            });

            // Event listener for the "Back" button in the overlay
            overlayBackBtn.addEventListener('click', closeReviewOverlay);

            // Close overlay when clicking outside the content box
            reviewOverlay.addEventListener('click', function (event) {
                if (event.target === reviewOverlay) {
                    closeReviewOverlay();
                }
            });

            // --- Modify Button Logic ---
            overlayModifyBtn.addEventListener('click', function (event) {
                event.preventDefault(); // Prevent default form submission
                if (this.disabled) {
                    return; // Do nothing if disabled
                }

                showGlobalConfirmation('Are you sure you want to modify this booking? Approvals will be reset to pending.', function () {
                    const formData = new FormData(overlayForm);
                    const data = Object.fromEntries(formData.entries());

                    fetch(`{{ url('hall-bookings') }}/${currentBookingId}`, {
                        method: 'POST', // Use POST for Laravel's PATCH emulation
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    })
                        .then(response => response.json())
                        .then(result => {
                            if (result.success) {
                                showInfoOverlay(result.message); // Use info overlay
                                closeReviewOverlay();
                                location.reload(); // Refresh page to update table
                            } else {
                                showInfoOverlay('Error: ' + result.message); // Use info overlay
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showInfoOverlay('An error occurred while modifying the booking. Please try again.'); // Use info overlay
                        });
                });
            });

            // --- Cancel Button Logic ---
            overlayCancelBtn.addEventListener('click', function (event) {
                event.preventDefault(); // Prevent default button action
                showGlobalConfirmation('Are you sure you want to cancel this booking? This action cannot be undone.', function () {
                    fetch(`{{ url('hall-bookings') }}/${currentBookingId}`, {
                        method: 'POST', // Use POST for Laravel's DELETE emulation
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ _method: 'DELETE' })
                    })
                        .then(response => response.json())
                        .then(result => {
                            if (result.success) {
                                showInfoOverlay(result.message); // Use info overlay
                                closeReviewOverlay();
                                location.reload(); // Refresh page to update table
                            } else {
                                showInfoOverlay('Error: ' + result.message); // Use info overlay
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showInfoOverlay('An error occurred while cancelling the booking. Please try again.'); // Use info overlay
                        });
                });
            });

            // --- Download Button Logic ---
            overlayDownloadBtn.addEventListener('click', function (event) {
                event.preventDefault();
                if (currentBookingId) {
                    // Redirect to the download route
                    window.location.href = `{{ url('hall-bookings') }}/${currentBookingId}/download`;
                } else {
                    showInfoOverlay("No booking selected for download.");
                }
            });
        });
    </script>
@endpush