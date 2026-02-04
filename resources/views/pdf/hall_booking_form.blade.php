<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Hall Booking Application</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            line-height: 1.6;
            color: #333;
            font-size: 10px;
            margin: 0;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 0 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #0056b3;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #0056b3;
            font-weight: bold;
        }

        .header h2 {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #555;
            font-weight: normal;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #0056b3;
            border-bottom: 1px solid #0056b3;
            padding-bottom: 4px;
            margin-top: 15px;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .details-grid {
            margin-bottom: 10px;
            overflow: hidden;
        }

        .grid-row {
            display: flex;
            width: 100%;
            margin-bottom: 6px;
            gap: 15px;
        }

        .grid-item {
            flex: 1;
            min-width: 0;
            /* Allows flex items to shrink below content size */
        }

        .grid-item.full-width {
            width: 100%;
            flex: none;
        }

        .grid-item-label {
            display: block;
            color: #495057;
            font-weight: bold;
            font-size: 9px;
            margin-bottom: 2px;
            padding: 2px 0;
        }

        .grid-item-value {
            display: block;
            padding: 4px 6px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 3px;
            font-size: 10px;
            min-height: 18px;
            word-wrap: break-word;
        }

        .section-content {
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .section-content p {
            margin-bottom: 5px;
            font-size: 10px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 8px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            page-break-inside: avoid;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-approved {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 9px;
        }

        .table th,
        .table td {
            border: 1px solid #dee2e6;
            padding: 6px;
            text-align: left;
        }

        .table th {
            background-color: #e9ecef;
            font-weight: bold;
        }

        .table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .signature-box {
            float: left;
            width: 33%;
            text-align: center;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>District Secretariat - Vavuniya</h1>
            <h2>Resource Management System</h2>
        </div>

        <div class="section-title">Hall Booking Application Form</div>

        <div class="details-grid">
            <div class="grid-row">
                <div class="grid-item"><span class="grid-item-label">Applicant Name:</span> <span
                        class="grid-item-value">{{ $booking->applicant_name }}</span></div>
                <div class="grid-item"><span class="grid-item-label">Applicant Type:</span> <span
                        class="grid-item-value">{{ $booking->applicant_type }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item"><span class="grid-item-label">Hall Type:</span> <span
                        class="grid-item-value">{{ $booking->requested_hall_type ?? $booking->hall->hall_type }}</span>
                </div>
                <div class="grid-item"><span class="grid-item-label">Programme / Event:</span> <span
                        class="grid-item-value">{{ $booking->programme }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item"><span class="grid-item-label">Event Date:</span> <span
                        class="grid-item-value">{{ \Carbon\Carbon::parse($booking->event_date)->format('Y-m-d') }}</span>
                </div>
                <div class="grid-item"><span class="grid-item-label">Event Time:</span> <span
                        class="grid-item-value">{{ \Carbon\Carbon::parse($booking->event_time)->format('h:i A') }}</span>
                </div>
            </div>
            <div class="grid-row">
                <div class="grid-item"><span class="grid-item-label">Duration:</span> <span
                        class="grid-item-value">{{ $booking->event_duration }} Hours</span></div>
                <div class="grid-item"><span class="grid-item-label">Number of Participants:</span> <span
                        class="grid-item-value">{{ $booking->participants }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item"><span class="grid-item-label">Paid Status:</span> <span
                        class="grid-item-value">{{ $booking->paid_status }}</span></div>
                <div class="grid-item"><span class="grid-item-label">Emergency Booking:</span> <span
                        class="grid-item-value">{{ $booking->is_emergency_booking ? 'Yes' : 'No' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item full-width"><span class="grid-item-label">Final Approval Status:</span> <span
                        class="grid-item-value">
                        <span class="status-badge status-{{ $booking->final_approval }}">
                            {{ ucfirst($booking->final_approval) }}
                        </span>
                    </span></div>
            </div>
        </div>

        <div class="footer">
            <p>Generated on: {{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }} | Page <span
                    class="pageNumber"></span>/<span class="totalPages"></span><br>Resource Booking System - Vavuniya
            </p>
        </div>
    </div>
</body>

</html>