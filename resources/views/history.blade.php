@extends('layouts.user_body_layout')

@section('title', 'History - District Secretariat Vavuniya')

@section('content')
<section class="banner">
    <div class="page-header">
        <h2 style="color: rgb(6, 4, 60); font-weight: bold">Booking History</h2>
        <p>Review processed applications.</p>
    </div>
    <h2 style="text-align: center; color:rgb(34, 60, 4)">Hall Booking Applications</h2>
    <table id="history-table">
        <thead>
            <tr>
                <th>Applicant Name</th>
                <th>Event Date</th>
                <th>Submitted Date</th>
                <th>Final Approval State</th>
                <th>Reason of Rejection</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
                <tr data-booking='{{ json_encode($booking) }}'>
                    <td>{{ $booking->applicant_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->event_date)->format('Y-m-d') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->date_created)->format('Y-m-d h:i A') }}</td>
                    <td>
                        <span style="font-weight: bold; color: {{ $booking->final_approval == 'approved' ? 'green' : 'red' }}">
                            {{ ucfirst($booking->final_approval) }}
                        </span>
                    </td>
                    <td>{{ $booking->reason_of_rejection ?? 'N/A' }}</td>
                    <td>
                         <button class="action-btn review-btn" data-booking-id="{{ $booking->booking_id }}">Review</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">No history found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <br><br><br>
    <h2 style="text-align: center; color:rgb(34, 60, 4)">Qaurters Reservation Applications</h2>
    <table id="history-table">
        <thead>
            <tr>
                <th>Applicant Name</th>
                <th>Event Date</th>
                <th>Submitted Date</th>
                <th>Final Approval State</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
    {{-- Review Overlay (Read Only) --}}
    <div id="history-review-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); z-index: 1001; justify-content: center; align-items: center;">
        <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3); max-width: 800px; width: 90%; position: relative;">
            <button id="history-back-btn" style="position: absolute; top: 10px; left: 10px; background-color: #6c757d; color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer;">Back</button>
            <h3 style="text-align: center; margin-bottom: 20px;">Application Details</h3>
            <div id="history-form-content"></div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                @if(Auth::user()->hasPermissionTo('administrative_officer_approval'))
                    <button type="button" id="history-cancel-btn" class="action-btn" style="background-color: #dc3545; color: white; padding: 10px 20px; display: none;">Cancel Booking</button>
                @endif
                <button type="button" id="history-download-btn" class="action-btn" style="background-color: #29f00f; color: white; padding: 10px 20px;">Download PDF</button>
            </div>
        </div>
    </div>

    <style>
        #history-review-overlay {
            display: flex;
        }
        #history-form-content .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 15px;
        }
        #history-form-content .form-group {
            flex: 1;
            min-width: 250px;
        }
        #history-form-content label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
            font-size: 0.9em;
        }
        #history-form-content p {
            padding: 8px 10px;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 4px;
            min-height: 38px;
        }
    </style>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const historyReviewOverlay = document.getElementById('history-review-overlay');
            const historyBackBtn = document.getElementById('history-back-btn');
            const historyFormContent = document.getElementById('history-form-content');
            const historyDownloadBtn = document.getElementById('history-download-btn');
            const historyCancelBtn = document.getElementById('history-cancel-btn'); // May be null if no permission
            let currentBookingId = null;

            function renderHistoryFields(booking) {
                currentBookingId = booking.booking_id;
                let fieldsHtml = `
                    <div class="form-row">
                        <div class="form-group">
                            <label>Applicant Name</label>
                            <p>${booking.applicant_name}</p>
                        </div>
                        <div class="form-group">
                            <label>Applicant Type</label>
                            <p>${booking.applicant_type}</p>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Hall Type</label>
                            <p>${booking.hall ? booking.hall.hall_type : booking.requested_hall_type || 'N/A'}</p>
                        </div>
                        <div class="form-group">
                            <label>Programme/Event</label>
                            <p>${booking.programme}</p>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Event Date</label>
                            <p>${booking.event_date}</p>
                        </div>
                        <div class="form-group">
                            <label>Event Time</label>
                            <p>${booking.event_time}</p>
                        </div>
                        <div class="form-group">
                            <label>Participants</label>
                            <p>${booking.participants}</p>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Duration (hours)</label>
                            <p>${booking.event_duration}</p>
                        </div>
                        <div class="form-group">
                            <label>Paid Status</label>
                            <p>${booking.paid_status}</p>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Emergency Booking</label>
                            <p>${booking.is_emergency_booking ? 'Yes' : 'No'}</p>
                        </div>
                        <div class="form-group">
                            <label>Approval Status</label>
                            <p style="color: ${booking.final_approval === 'approved' ? 'green' : 'red'}; font-weight: bold;">${booking.final_approval.charAt(0).toUpperCase() + booking.final_approval.slice(1)}</p>
                        </div>
                    </div>
                    ${booking.reason_of_rejection ? `
                    <div class="form-row">
                        <div class="form-group">
                            <label>Reason of Rejection/Cancellation</label>
                            <p style="background-color: #fff3f3; border-color: #f5c6cb; color: #721c24;">${booking.reason_of_rejection}</p>
                        </div>
                    </div>
                    ` : ''}
                    <div id="cancel-reason-container" style="display: none; margin-top: 15px;">
                        <label for="cancel-reason" style="display: block; margin-bottom: 5px; font-weight: bold; color: #dc3545;">Reason for Cancellation <span style="color: red">*</span></label>
                        <textarea id="cancel-reason" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;" rows="3" placeholder="Enter the reason for cancelling this approved booking..."></textarea>
                    </div>
                `;
                historyFormContent.innerHTML = fieldsHtml;

                // Toggle cancel button based on status
                if (historyCancelBtn) {
                    if (booking.final_approval === 'approved') {
                        historyCancelBtn.style.display = 'inline-block';
                    } else {
                        historyCancelBtn.style.display = 'none';
                    }
                }
            }

            document.querySelectorAll('.review-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const row = this.closest('tr');
                    const bookingData = JSON.parse(row.dataset.booking);
                    renderHistoryFields(bookingData);
                    historyReviewOverlay.style.display = 'flex';
                });
            });

            historyBackBtn.addEventListener('click', () => historyReviewOverlay.style.display = 'none');

            // Close overlay when clicking outside
            historyReviewOverlay.addEventListener('click', function(event) {
                if (event.target === historyReviewOverlay) {
                    historyReviewOverlay.style.display = 'none';
                }
            });

             historyDownloadBtn.addEventListener('click', function() {
                if (currentBookingId) {
                    window.location.href = `/hall-bookings/${currentBookingId}/download`;
                }
            });

            if (historyCancelBtn) {
                historyCancelBtn.addEventListener('click', function() {
                    const reasonContainer = document.getElementById('cancel-reason-container');
                    const reasonInput = document.getElementById('cancel-reason');

                    if (reasonContainer.style.display === 'none') {
                        reasonContainer.style.display = 'block';
                        reasonInput.focus();
                        return; // Stop here to let user enter reason
                    }

                    const reason = reasonInput.value.trim();
                    if (!reason) {
                        alert('Please provide a reason for cancellation.');
                        reasonInput.focus();
                        return;
                    }

                    if (confirm('Are you sure you want to cancel this approved booking? This action cannot be undone.')) {
                        fetch(`/hall-bookings/${currentBookingId}/cancel-approved`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ reason: reason })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message);
                                location.reload();
                            } else {
                                alert('Error: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while cancelling the booking.');
                        });
                    }
                });
            }
        });
    </script>
    @endpush

</section>
@endsection
