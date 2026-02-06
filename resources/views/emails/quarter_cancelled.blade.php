<!DOCTYPE html>
<html>

<head>
    <title>Quarter Allocation Cancelled</title>
</head>

<body>
    <p>Dear {{ $application->officer_name }},</p>

    <p>This email is to inform you regarding your application for government quarter allocation.</p>

    <p>Status: <strong>Cancelled / Rejected</strong></p>

    @if($application->quarterAllocation->ga_note)
        <p><strong>Reason/Note:</strong> {{ $application->quarterAllocation->ga_note }}</p>
    @endif

    <p>Please find the formal letter attached to this email.</p>

    <p>Thank you,<br>
        District Secretariat, Vavuniya<br>
        Resource Booking System
    </p>
</body>

</html>