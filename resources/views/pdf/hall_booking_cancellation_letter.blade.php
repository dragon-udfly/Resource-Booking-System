<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Hall Booking Cancellation</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 14px;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            text-decoration: underline;
            color: #d9534f;
            /* Red color for cancellation notice */
        }

        .content {
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .reason-box {
            border: 1px solid #d9534f;
            background-color: #f9f2f2;
            padding: 10px;
            margin-top: 10px;
            border-radius: 4px;
        }

        .footer {
            margin-top: 50px;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>District Secretariat, Vavuniya</h2>
        <p>Resource Booking System</p>
    </div>

    <div class="content">
        <p class="title">CANCELLATION OF HALL BOOKING</p>
        <p><strong>Date:</strong> {{ $date }}</p>

        <p>Dear {{ $booking->applicant_name }},</p>
        <p>We regret to inform you that your previously approved booking for the event/programme
            <strong>{{ $booking->programme }}</strong> has been <strong style="color: #d9534f;">CANCELLED</strong> by
            the Government Agent.</p>

        <table class="table">
            <tr>
                <th>Event / Programme</th>
                <td>{{ $booking->programme }}</td>
            </tr>
            <tr>
                <th>Hall Name</th>
                <td>{{ $booking->hall->hall_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Reserved Date</th>
                <td>{{ $booking->event_date }}</td>
            </tr>
            <tr>
                <th>Reserved Time</th>
                <td>{{ $booking->event_time }}</td>
            </tr>
        </table>

        <p><strong>Reason for Cancellation:</strong></p>
        <div class="reason-box">
            {{ $reason }}
        </div>

        <p>Any payments made regarding this booking will be handled as per the standard refund procedures. Please
            contact the District Secretariat office for further information.</p>

        <p>
            Generated on: {{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }}<br>Resource Booking System - Vavuniya
        </p>
    </div>

</body>

</html>