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
                <tr data-booking='{{ json_encode($booking) }}'>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $booking->applicant_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->event_date)->format('Y-m-d') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->date_created)->format('Y-m-d h:i A') }}</td>
                    <td>
                        <span style="font-weight: bold; color: {{ $booking->final_approval == 'approved' ? 'green' : 'red' }}">
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
                    <td colspan="7" style="text-align: center;">No hall booking history found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <br><br><br>
        <h2 style="text-align: center; color:rgb(34, 60, 4)">Quarters Reservation Applications</h2>
        <table id="quarters-history-table" class="history-table-styles">
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
                <tr>
                    <td colspan="6" style="text-align: center;">Loading quarter history...</td>
                </tr>
            </tbody>
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
            /* General table styles to be shared */
            .history-table-styles, #history-table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
                font-size: 0.9em;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            }
            .history-table-styles thead tr, #history-table thead tr {
                background-color: #009879;
                color: #ffffff;
                text-align: left;
            }
            .history-table-styles th, .history-table-styles td, #history-table th, #history-table td {
                padding: 12px 15px;
                border: 1px solid #dddddd;
            }
            .history-table-styles tbody tr, #history-table tbody tr {
                border-bottom: 1px solid #dddddd;
            }
            .history-table-styles tbody tr:nth-of-type(even), #history-table tbody tr:nth-of-type(even) {
                background-color: #f3f3f3;
            }
            .history-table-styles tbody tr:last-of-type, #history-table tbody tr:last-of-type {
                border-bottom: 2px solid #009879;
            }
            .history-table-styles tbody tr.active-row, #history-table tbody tr.active-row {
                font-weight: bold;
                color: #009879;
            }
    
            #history-review-overlay { display: flex; }
            #history-form-content .form-row { display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 15px; }
            #history-form-content .form-group { flex: 1; min-width: 250px; }
            #history-form-content label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; font-size: 0.9em; }
            #history-form-content p { padding: 8px 10px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 4px; min-height: 38px; }
        </style>
    
        @push('scripts')
        <style>
            /* Status-specific styles for the quarter history table */
            .status-allocated { color: green; font-weight: bold; }
            .status-rejected { color: red; font-weight: bold; }
            .status-cancelled { color: grey; font-weight: bold; }
            .action-cell .action-btn { margin-right: 5px; }
        </style>
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ... (existing hall booking script)
    
            // New Logic for Quarter History
            const quartersHistoryBody = document.getElementById('quarters-history-body');
    
            fetch("{{ route('history.quarters') }}")
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    quartersHistoryBody.innerHTML = ''; // Clear loading message
                    if (data.length === 0) {
                        quartersHistoryBody.innerHTML = '<tr><td colspan="6" style="text-align: center;">No processed quarter applications found.</td></tr>';
                        return;
                    }
    
                    data.forEach((item, index) => {
                        const row = document.createElement('tr');
                        const app = item.quarter_application;
                        if (!app) return; // Skip if relation is missing
    
                        let viewUrl = '#';
                        if (app.quarter_type === 'Scheduled') {
                            viewUrl = "{{ route('history.view_scheduled', ['id' => ':id']) }}".replace(':id', app.application_id);
                        } else if (app.quarter_type === 'Family') {
                            viewUrl = "{{ route('history.view_family', ['id' => ':id']) }}".replace(':id', app.application_id);
                        }
                        
                        const status = item.allocation_status || 'pending';
                        const statusClass = `status-${status.toLowerCase()}`;
                        const downloadUrl = "{{ route('quarter.download-pdf', ['id' => ':id']) }}".replace(':id', app.application_id);
                        const submittedDate = new Date(app.date_created).toLocaleDateString();
    
                        row.innerHTML = `
                            <td>${index + 1}</td>
                            <td>${app.officer_name || 'N/A'}</td>
                            <td>${submittedDate}</td>
                            <td>${app.quarter_type || 'N/A'}</td>
                            <td><span class="${statusClass}">${status.charAt(0).toUpperCase() + status.slice(1)}</span></td>
                            <td class="action-cell">
                                <a href="${viewUrl}" class="action-btn" style="background-color: #007bff; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none;">View</a>
                                <a href="${downloadUrl}" class="action-btn" style="background-color: #28a745; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none;" target="_blank">Download</a>
                            </td>
                        `;
                        quartersHistoryBody.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('Error fetching quarter history:', error);
                    quartersHistoryBody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: red;">Failed to load quarter history.</td></tr>';
                });
            
            // Hall history script (assuming it's here)
            const historyReviewOverlay = document.getElementById('history-review-overlay');
            const historyBackBtn = document.getElementById('history-back-btn');
            // ... rest of hall history script
        });
        </script>
        @endpush
    </section>
    @endsection
    