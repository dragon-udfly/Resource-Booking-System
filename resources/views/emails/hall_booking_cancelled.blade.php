<!DOCTYPE html>
<html>

<head>
    <title>Booking Cancelled</title>
</head>

<body>
    <h2>Booking Cancellation Notice</h2>
    <p>Dear {{ $booking->applicant_name }},</p>

    <p>We regret to inform you that your approved booking for the event <strong>{{ $booking->programme }}</strong> on
        {{ $booking->event_date }} has been <strong>CANCELLED</strong> by the District Secretariat administration.</p>

    <p><strong>Reason for Cancellation:</strong> {{ $reason }}</p>

    <p>Please find the official cancellation letter attached to this email for more details.</p>

    <p>We apologize for any inconvenience caused.</p>

    <br>
    <p>Regards,<br>District Secretariat, Vavuniya</p>
</body>

</html>