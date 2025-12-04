@extends('layouts.user_body_layout')

@section('title', 'Dashboard - District Secretariat Vavuniya')

@section('page_styles')
    <style>
        .page-header {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .page-header h2 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .page-header p {
            font-size: 1.1em;
            color: #555;
        }

        table {
            width: 90%; /* Adjust table width */
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            background-color: #fff;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px 15px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .action-btn {
            padding: 8px 12px;
            margin: 2px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
            font-size: 0.9em;
            transition: background-color 0.3s ease;
        }

        .action-btn.approve {
            background-color: #28a745;
        }
        .action-btn.approve:hover {
            background-color: #218838;
        }
        .action-btn.reject {
            background-color: #dc3545;
        }
        .action-btn.reject:hover {
            background-color: #c82333;
        }
    </style>
@endsection

@section('content')
    <!-- Cyan/Turquoise Banner Section -->
    <section class="banner">
        <div class="page-header">
            <h2 style="color: rgb(6, 4, 60); font-weight: bold">Pending Booking Approvals</h2>
            <p>Review the pending applications.</p>
        </div>

        <table id="approval-details">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Applicant Name</th>
                    <th>Application Type</th>
                    <th>AO Approval</th>
                    <th>AGA Approval</th>
                    <th>GA Approval</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bookings as $booking)
                    <tr>
                        <td>{{ $booking->booking_id }}</td>
                        <td>{{ $booking->applicant_name }}</td>
                        <td>Hall Booking</td>
                        <td>{{ ucfirst($booking->administrative_officer_approved) }}</td>
                        <td>{{ ucfirst($booking->additional_government_agent_approved) }}</td>
                        <td>{{ ucfirst($booking->government_agent_approved) }}</td>
                        <td>
                            <button class="action-btn approve">Approve</button>
                            <button class="action-btn reject">Reject</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 12px 15px;">No pending bookings found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
@endsection
