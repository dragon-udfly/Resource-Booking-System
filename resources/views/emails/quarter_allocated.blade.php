<!DOCTYPE html>
<html>

<head>
    <title>Quarter Allocated</title>
</head>

<body>
    <p>Dear {{ $application->officer_name }},</p>

    <p>We are pleased to inform you that a government quarter has been allocated to you.</p>

    <p>
        <strong>Quarter No:</strong> {{ $application->quarterAllocation->quarter->quarter_id ?? 'N/A' }}<br>
        <strong>Within:</strong> {{ $application->quarterAllocation->quarter->location ?? 'N/A' }}
    </p>

    <p>Please find the official allocation letter attached to this email.</p>

    <p>Thank you,<br>
        District Secretariat, Vavuniya</p>
</body>

</html>