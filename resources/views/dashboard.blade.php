<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - District Secretariat Vavuniya</title>
    <link href='icons/right_logo.png' rel='icon' type='image/png'>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
        }

        .header {
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-bottom: 3px solid #ddd;
        }

        .header-main {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .logo-left {
            width: 110px;
            height: 22vh;
            margin-left: 70px;
        }

        .header-content {
            flex: 1;
            text-align: center;
            padding: 0 10px;
        }

        .header-content h1 {
            font-size: 40px;
            font-weight: bold;
            color: #000;
            padding-bottom: 20px;
        }

        .header-content h2 {
            font-size: 25px;
            font-weight: normal;
            color: #333;
        }

        .logo-right {
            width: 130px;
            height: 22vh;
            margin-right: 70px;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding: 10px 20px;
            background-color: #e9ecef; /* Light grey background for navbar */
            border-top: 1px solid #dee2e6;
        }

        .navbar ul {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
        }

        .navbar li {
            margin-right: 20px;
        }

        .navbar li:last-child {
            margin-right: 0;
        }

        .navbar a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        .navbar a:hover {
            color: #0056b3;
        }

        .navbar-right {
            margin-left: auto; /* Pushes right items to the right */
        }

        .banner {
            background: linear-gradient(180deg, #7dd3d9 0%, #a8e6ea 100%);
            min-height: 58vh; /* Use min-height instead of fixed height */
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

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


        .footer {
            background-color: #000;
            height: 17vh;
            width: 100%;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-main">
            <img src="{{ asset('icons/left_logo.png') }}" alt="Sri Lanka government logo" class="logo-left">
            <div class="header-content">
                <h1>District Secretariat - Vavuniya</h1>
                <h2>Hall and Quarters Booking System</h2>
            </div>
            <img src="{{ asset('icons/right_logo.png') }}" alt="district Secretariat vavuniya logo" class="logo-right">
        </div>
        <nav class="navbar">
            <ul class="navbar-left">
                <li id="nav-preference"><a href="/preference">Preference</a></li>
                <li id="nav-officers"><a href="#">Officers</a></li>
                <li id="nav-halls"><a href="{{ route('halls.index') }}">Halls</a></li>
                <li id="nav-quarter"><a href="#">Quarters</a></li>
                <li id="nav-audit-log"><a href="{{ route('auditlog') }}">Audit Log</a></li>
            </ul>
            <ul class="navbar-right">
                <li id="loggedin_user" style="color: rgb(6, 4, 60); font-weight: bold">
                    @auth
                    <span id="designation">{{ Auth::user()->designation }}</span>, 
                    <span id="first_name">{{ Auth::user()->first_name }}</span>
                    @endauth
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: #007bff; font-weight: bold; cursor: pointer; font-size: 1em; padding: 0;">Log Out</button>
                    </form>
                </li>
            </ul>
        </nav>
    </header>

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

    <!-- Black Footer Section -->
    <footer class="footer" style="color: white; text-align: center; padding-top: 20px;">
        <p>&copy; 2025 District Secretariat, Vavuniya. All Rights Reserved.</p>
        <p style="margin-top: 10px;">
            <a href="/privacy" style="color: white; text-decoration: none; margin: 0 10px;">Privacy and Policy</a>
            |
            <a href="/agreement" style="color: white; text-decoration: none; margin: 0 10px;">User Agreement</a>
        </p>
    </footer>
</body>
</html>