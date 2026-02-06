<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Quarter Allocation Cancellation</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 14px;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .title {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 20px;
            font-size: 16px;
        }

        .content {
            margin-bottom: 20px;
        }

        .footer {
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>District Secretariat, Vavuniya</h2>
        <p>Resource Booking & Quarter Management System</p>
    </div>

    <div class="content">
        <p class="title">NOTIFICATION OF APPLICATION STATUS</p>

        <p><strong>Date:</strong> {{ $date }}</p>

        <p>Dear {{ $application->officer_name }},</p>

        <p>This is to inform you regarding your application for government quarter allocation (Application ID:
            {{ $application->application_id }}).</p>

        <p>Your application status has been updated to:
            <strong>{{ strtoupper($allocation->allocation_status ?? 'CANCELLED') }}</strong>.</p>

        @if($allocation->ga_note)
            <p><strong>Remarks / Reason:</strong><br>
                {{ $allocation->ga_note }}</p>
        @endif

        <p>If you have any queries regarding this decision, please contact the District Secretariat.</p>
    </div>

    <div class="footer">
        <p>..................................................<br>
            Government Agent / District Secretary,<br>
            District Secretariat, Vavuniya.</p>
    </div>
</body>

</html>