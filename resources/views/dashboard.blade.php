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
        .disabled-link {
            color: #6c757d !important;
            pointer-events: none;
            cursor: default;
        }

        .banner {
...
        <nav class="navbar">
            <ul class="navbar-left">
                <li id="nav-preference"><a href="/preference">Preference</a></li>
                @if(Auth::user()->permissions && Auth::user()->permissions->view_officers)
                    <li id="nav-officers"><a href="{{ route('officers.index') }}">Officers</a></li>
                @else
                    <li id="nav-officers" class="disabled-link"><a href="#" class="disabled-link">Officers</a></li>
                @endif
                <li id="nav-halls"><a href="#">Halls</a></li>
                <li id="nav-quarter"><a href="#">Quarters</a></li>
                <li id="nav-audit-log"><a href="#">Audit Log</a></li>
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
            <h2 style="color: rgb(6, 4, 60); font-weight: bold">Pending Hall Booking Approvals</h2>
            <p>Review and approve or reject pending hall booking requests.</p>
        </div>

        <table style="width: 90%; margin: 20px auto; border-collapse: collapse; box-shadow: 0 0 15px rgba(0,0,0,0.1); background-color: #fff;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th style="border: 1px solid #ddd; padding: 12px 15px; text-align: left;">Booking ID</th>
                    <th style="border: 1px solid #ddd; padding: 12px 15px; text-align: left;">Applicant Name</th>
                    <th style="border: 1px solid #ddd; padding: 12px 15px; text-align: left;">Hall Type</th>
                    <th style="border: 1px solid #ddd; padding: 12px 15px; text-align: left;">Event Date</th>
                    <th style="border: 1px solid #ddd; padding: 12px 15px; text-align: left;">AO Approval</th>
                    <th style="border: 1px solid #ddd; padding: 12px 15px; text-align: left;">AGA Approval</th>
                    <th style="border: 1px solid #ddd; padding: 12px 15px; text-align: left;">GA Approval</th>
                    <th style="border: 1px solid #ddd; padding: 12px 15px; text-align: left;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bookings as $booking)
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 12px 15px;">{{ $booking->booking_id }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px 15px;">{{ $booking->applicant_name }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px 15px;">{{ $booking->requested_hall_type }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px 15px;">{{ $booking->event_date }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px 15px;">{{ ucfirst($booking->administrative_officer_approved) }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px 15px;">{{ ucfirst($booking->additional_government_agent_approved) }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px 15px;">{{ ucfirst($booking->government_agent_approved) }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px 15px;">
                            <button>Approve</button>
                            <button>Reject</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 12px 15px;">No pending bookings found.</td>
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