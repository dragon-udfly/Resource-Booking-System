<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Hall Booking Approval</title>
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

        .footer {
            margin-top: 50px;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>District Secretariat, Vavuniya</h2>
        <p>Resource Management Division</p>
    </div>

    <div class="content">
        <p class="title">Hall Booking Confirmation</p>
        <p><strong>Booking ID:</strong> {{ $booking->booking_id }}</p>
        <p><strong>Date:</strong> {{ $date }}</p>

        <p>Dear {{ $booking->applicant_name }},</p>
        <p>We are pleased to inform you that your request for reserving the hall has been <strong>APPROVED</strong>.</p>

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
                <th>Date</th>
                <td>{{ $booking->event_date }}</td>
            </tr>
            <tr>
                <th>Time</th>
                <td>{{ $booking->event_time }}</td>
            </tr>
            <tr>
                <th>Duration</th>
                <td>{{ $booking->event_duration }} Hours</td>
            </tr>
        </table>

        <p>Please ensure that all payments (if applicable) are settled before the event date. Please bring this letter
            as a proof of booking.</p>
    </div>

    <div class="footer">
        <p>__________________________<br>Government Agent / District Secretary<br>District Secretariat, Vavuniya</p>
    </div>
</body>

</html>