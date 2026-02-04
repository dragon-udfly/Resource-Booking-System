<!DOCTYPE html>
<html>

<head>
    <title>Hall Booking Approved</title>
</head>

<body>
    <p>Dear {{ $booking->applicant_name }},</p>

    <p>Your booking request (Program: <strong>{{ $booking->programme }}</strong>) for the
        <strong>{{ $booking->hall->hall_type ?? 'Hall' }}</strong> on <strong>{{ $booking->event_date }}</strong> has
        been approved.
    </p>

    <p>Please find the official approval letter attached to this email.</p>

    <p>Thank you,<br>
        District Secretariat, Vavuniya<br>
        Resource Booking System.</p>
</body>

</html>