@extends('layouts.user_body_layout')

@section('title', 'Dashboard - District Secretariat Vavuniya')


@section('content')
    <!-- Cyan/Turquoise Banner Section -->
    <section class="banner">
        @if(Auth::user()->hasPermissionTo('requester'))
            @include('partials.requester_dashboard_layout', ['requesterBookings' => $requesterBookings])
        @else
            <div class="page-header">
                <h2 style="color: rgb(6, 4, 60); font-weight: bold">Pending Booking Approvals</h2>
                <p>Review the pending applications.</p>
            </div>

            <table id="approval-details">
                <thead>
                    <tr>
                        <th>Applicant Name</th>
                        <th>Application Type</th>
                        <th>Submitted Date</th>
                        <th>AO Approval</th>
                        <th>AGA Approval</th>
                        <th>GA Approval</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $booking)
                        <tr @if($booking->is_emergency_booking) style="background-color: yellow;" @endif>
                            <td>{{ $booking->applicant_name }}</td>
                            <td>Hall Booking</td>
                            <td>{{ \Carbon\Carbon::parse($booking->date_created)->format('Y-m-d h:i A') }}</td>
                            <td>{{ ucfirst($booking->administrative_officer_approved) }}</td>
                            <td>{{ ucfirst($booking->additional_government_agent_approved) }}</td>
                            <td>{{ ucfirst($booking->government_agent_approved) }}</td>
                            <td>
                                <button class="action-btn review-btn" data-booking-id="{{ $booking->booking_id }}">Review</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 12px 15px;">No pending bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @endif
    </section>
@endsection
