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
                <th>No.</th>
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
                <tr data-booking='{{ json_encode($booking) }}' data-hall-row="{{ $booking->booking_id }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $booking->applicant_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->event_date)->format('Y-m-d') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->date_created)->format('Y-m-d h:i A') }}</td>
                    <td>
                        <span style="font-weight: bold; color: {{ $booking->final_approval == 'approved' ? 'green' : 'red'}}">
                            {{ ucfirst($booking->final_approval) }}
                        </span>
                    </td>
                    <td>{{ $booking->reason_of_rejection ?? 'N/A' }}</td>
                    <td class="action-cell">
                         <button class="action-btn review-btn" style="background-color: #007bff; color: white; padding: 5px 10px; border-radius: 5px; border: none; cursor: pointer; margin-right: 5px;" data-booking-id="{{ $booking->booking_id }}">Review</button>
                         <a href="{{ route('hall_bookings.download', ['hallBooking' => $booking->booking_id]) }}" class="action-btn" style="background-color: #28a745; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none;" target="_blank">Download</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">No records available</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <br><br><br>
    <h2 style="text-align: center; color:rgb(34, 60, 4)">Quarters Reservation Applications</h2>
    <table id="quarters-history-table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Applicant Name</th>
                <th>Submitted Date</th>
                <th>Requested Quarter Type</th>
                <th>Approval Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="quarters-history-body">
            <tr><td colspan="6" style="text-align: center;">No records available</td></tr>
        </tbody>
    </table>
    
    {{-- Review Overlay (Read Only) --}}
    <div id="history-review-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); z-index: 1001; justify-content: center; align-items: center;">
        <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3); max-width: 800px; width: 90%; position: relative;">
            <button id="history-back-btn" class="action-btn" style="position: absolute; top: 10px; left: 10px; background-color: #6c757d;">Back</button>
            <h3 style="text-align: center; margin-bottom: 20px;">Application Details</h3>
            <div id="history-form-content"></div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                <button type="button" id="history-delete-btn" class="action-btn" style="background-color: #dc3545; display: none;">Delete Record</button>
                @if(Auth::user()->hasPermissionTo('administrative_officer_approval'))
                    <button type="button" id="history-cancel-btn" class="action-btn" style="background-color: #ffc107; color: #333; display: none;">Cancel Booking</button>
                @endif
            </div>
        </div>
    </div>

    <!-- Generic Modal for confirmations -->
    <div id="modal-overlay" class="modal-overlay" style="z-index: 1002;">
        <div class="modal-content">
            <h3 id="modal-title"></h3>
            <p id="modal-message"></p>
            <div id="modal-buttons" class="modal-buttons"></div>
        </div>
    </div>

    <style>
        .history-table-styles, #history-table, #quarters-history-table { width: 100%; border-collapse: collapse; margin: 20px 0; font-size: 0.9em; box-shadow: 0 0 20px rgba(0, 0, 0, 0.15); }
        .history-table-styles thead tr, #history-table thead tr, #quarters-history-table thead tr { background-color: #009879; color: #ffffff; text-align: left; }
        .history-table-styles th, .history-table-styles td, #history-table th, #history-table td, #quarters-history-table th, #quarters-history-table td { padding: 12px 15px; border: 1px solid #dddddd; }
        .history-table-styles tbody tr, #history-table tbody tr, #quarters-history-table tbody tr { border-bottom: 1px solid #dddddd; }
        .history-table-styles tbody tr:nth-of-type(even), #history-table tbody tr:nth-of-type(even), #quarters-history-table tbody tr:nth-of-type(even) { background-color: #f3f3f3; }
        #history-review-overlay { display: none; }
        #history-form-content .form-row { display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 15px; }
        #history-form-content .form-group { flex: 1; min-width: 250px; }
        #history-form-content label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; font-size: 0.9em; }
        #history-form-content p { padding: 8px 10px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 4px; min-height: 38px; margin: 0; }
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); display: none; justify-content: center; align-items: center; opacity: 0; transition: opacity 0.3s ease; }
        .modal-overlay.active { display: flex; opacity: 1; }
        .modal-content { background: #fff; padding: 30px; border-radius: 8px; text-align: center; max-width: 450px; width: 90%; }
        .modal-buttons { display: flex; justify-content: center; gap: 20px; margin-top: 20px; }
    </style>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- START: Generic Modal Logic ---
    const modalOverlay = document.getElementById('modal-overlay');
    const modalTitle = document.getElementById('modal-title');
    const modalMessage = document.getElementById('modal-message');
    const modalButtons = document.getElementById('modal-buttons');

    const showModal = (title, message, buttons) => {
        modalTitle.textContent = title;
        modalMessage.innerHTML = message;
        modalButtons.innerHTML = '';
        buttons.forEach(btn => {
            const buttonEl = document.createElement('button');
            buttonEl.textContent = btn.text;
            buttonEl.className = 'btn';
            if(btn.class) buttonEl.classList.add(btn.class);
            buttonEl.addEventListener('click', btn.onClick);
            modalButtons.appendChild(buttonEl);
        });
        modalOverlay.classList.add('active');
    };
    const hideModal = () => modalOverlay.classList.remove('active');
    // --- END: Generic Modal Logic ---

    // --- START: Hall Booking History Logic ---
    const historyReviewOverlay = document.getElementById('history-review-overlay');
    const historyBackBtn = document.getElementById('history-back-btn');
    const historyFormContent = document.getElementById('history-form-content');
    const historyDownloadBtn = document.getElementById('history-download-btn');
    const historyCancelBtn = document.getElementById('history-cancel-btn');
    const historyDeleteBtn = document.getElementById('history-delete-btn');
    let currentBookingId = null;

    function renderHistoryFields(booking) {
        currentBookingId = booking.booking_id;
        const hallName = booking.hall ? booking.hall.hall_type : (booking.requested_hall_type || 'N/A');
        
        historyFormContent.innerHTML = `
            <div class="form-row">
                <div class="form-group"><label>Applicant Name</label><p>${booking.applicant_name}</p></div>
                <div class="form-group"><label>Booked Hall</label><p>${hallName}</p></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Programme/Event</label><p>${booking.programme}</p></div>
                <div class="form-group"><label>Participants</label><p>${booking.participants}</p></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Event Date & Time</label><p>${booking.event_date} at ${booking.event_time}</p></div>
                <div class="form-group"><label>Approval Status</label><p style="font-weight: bold; text-transform: capitalize; color: ${booking.final_approval === 'approved' ? 'green' : (booking.final_approval === 'rejected' ? 'red' : 'grey')};">${booking.final_approval}</p></div>
            </div>
            ${booking.reason_of_rejection ? `<div class="form-row"><div class="form-group" style="flex-basis: 100%"><label>Reason of Rejection/Cancellation</label><p style="background-color: #fff3f3;">${booking.reason_of_rejection}</p></div></div>` : ''}
            <div id="cancel-reason-container" style="display: none; margin-top: 15px;"><label for="cancel-reason" style="font-weight: bold; color: #dc3545;">Reason for Cancellation*</label><textarea id="cancel-reason" style="width: 100%;" rows="3"></textarea></div>
        `;

        if (historyCancelBtn) historyCancelBtn.style.display = (booking.final_approval === 'approved') ? 'inline-block' : 'none';

        // Conditional Delete Button Logic
        const today = new Date();
        const eventDate = new Date(booking.event_date);
        today.setHours(0, 0, 0, 0);

        if (eventDate < today) {
            historyDeleteBtn.style.display = 'inline-block';
            historyDeleteBtn.disabled = false;
            historyDeleteBtn.title = 'Delete this record.';
        } else {
            historyDeleteBtn.style.display = 'inline-block'; // Keep it visible but disabled
            historyDeleteBtn.disabled = true;
            historyDeleteBtn.title = 'Records can only be deleted after the event date has passed.';
        }
    }

    document.querySelectorAll('.review-btn').forEach(button => button.addEventListener('click', function () {
        renderHistoryFields(JSON.parse(this.closest('tr').dataset.booking));
        historyReviewOverlay.style.display = 'flex';
    }));

    historyBackBtn.addEventListener('click', () => historyReviewOverlay.style.display = 'none');
    historyDownloadBtn.addEventListener('click', () => { if (currentBookingId) window.open(`/hall-bookings/${currentBookingId}/download`, '_blank'); });

    if (historyCancelBtn) { /* Existing Cancel Logic */ }

    // New Delete Logic
    if (historyDeleteBtn) {
        historyDeleteBtn.addEventListener('click', function() {
            if (this.disabled) return;
            showModal('Confirm Deletion', 'Are you sure you want to permanently delete this booking record?', [
                { text: 'Yes, Delete', class: 'delete-btn', onClick: () => performDelete(currentBookingId) },
                { text: 'Cancel', class: 'back-button', onClick: hideModal }
            ]);
        });
    }

    const performDelete = async (bookingId) => {
        showModal('Processing...', 'Deleting record, please wait...', []);
        const url = `/hall-bookings/${bookingId}`;
        try {
            const response = await fetch(url, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const result = await response.json();
            if (!response.ok) {
                showModal('Error', result.message || 'An unknown error occurred.', [{ text: 'OK', class: 'back-button', onClick: hideModal }]);
            } else {
                const row = document.querySelector(`tr[data-hall-row="${bookingId}"]`);
                if (row) row.remove();
                historyReviewOverlay.style.display = 'none';
                showModal('Success', result.message, [{ text: 'OK', class: 'back-button', onClick: hideModal }]);
            }
        } catch (error) {
            showModal('Request Failed', 'Could not connect to the server.', [{ text: 'OK', class: 'back-button', onClick: hideModal }]);
        }
    };
    // --- END: Hall Booking History Logic ---

    // --- START: Quarter History Logic ---
    const quartersHistoryBody = document.getElementById('quarters-history-body');
    fetch("{{ route('history.quarters') }}")
        .then(response => response.ok ? response.json() : Promise.reject('Network response was not ok'))
        .then(data => {
            quartersHistoryBody.innerHTML = '';
            if (data.length === 0) {
                quartersHistoryBody.innerHTML = '<tr><td colspan="6" style="text-align: center;">No processed quarter applications found.</td></tr>';
                return;
            }
            data.forEach((item, index) => {
                const row = document.createElement('tr');
                const app = item.quarter_application;
                if (!app) return;
                let viewUrl = app.quarter_type === 'Scheduled' ? "{{ route('history.view_scheduled', ['id' => ':id']) }}".replace(':id', app.application_id) : "{{ route('history.view_family', ['id' => ':id']) }}".replace(':id', app.application_id);
                const status = item.allocation_status || 'pending';
                const statusClass = `status-${status.toLowerCase()}`;
                const downloadUrl = "{{ route('quarter.download-pdf', ['id' => ':id']) }}".replace(':id', app.application_id);
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${app.officer_name || 'N/A'}</td>
                    <td>${new Date(app.date_created).toLocaleDateString()}</td>
                    <td>${app.quarter_type || 'N/A'}</td>
                    <td><span class="${statusClass}">${status.charAt(0).toUpperCase() + status.slice(1)}</span></td>
                    <td class="action-cell">
                        <a href="${viewUrl}" class="action-btn" style="background-color: #007bff;">View</a>
                        <a href="${downloadUrl}" class="action-btn" style="background-color: #28a745;" target="_blank">Download</a>
                    </td>
                `;
                quartersHistoryBody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error fetching quarter history:', error);
            quartersHistoryBody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: red;">Failed to load quarter history.</td></tr>';
        });
    // --- END: Quarter History Logic ---
});
</script>
@endpush