<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - District Secretariat Vavuniya</title>
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

        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 900px;
            margin-top: 20px;
        }

        .form-info {
            background-color: #e9f7ef;
            border-left: 5px solid #28a745;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            color: #218838;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            flex: 1;
            min-width: 280px; /* Ensure fields don't get too small */
        }

        .form-group label #p_p {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="tel"],
        .form-group input[type="passcode"],
        .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1em;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            flex: 0 0 auto; /* Prevent checkboxes from stretching */
            min-width: unset; /* Override min-width from form-group */
        }

        .checkbox-group input[type="checkbox"] {
            width: auto; /* Reset width */
            margin-right: 10px;
            transform: scale(1.2); /* Make checkbox slightly larger */
        }

        .checkbox-group label {
            margin-bottom: 0; /* Remove bottom margin */
            font-weight: normal; /* Reset font-weight */
            color: #555;
        }

        .required {
            color: #dc3545;
            margin-left: 5px;
        }

        .button-group {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
        }

        .submit-btn, .reset-btn {
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .submit-btn {
            background-color: #007bff;
            color: white;
        }

        .submit-btn:hover {
            background-color: #0056b3;
            transform: translateY(-1px);
        }

        .reset-btn {
            background-color: #6c757d;
            color: white;
        }

        .reset-btn:hover {
            background-color: #5a6268;
            transform: translateY(-1px);
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
            <img src="icons/left_logo.png" alt="Sri Lanka government logo" class="logo-left">
            <div class="header-content">
                <h1>District Secretariat - Vavuniya</h1>
                <h2>Hall and Quarters Booking System - Administrator</h2>
            </div>
            <img src="icons/right_logo.png" alt="district Secretariat vavuniya logo" class="logo-right">
        </div>
        <nav class="navbar">
            <ul class="navbar-left">
                <li><a href="/document-history">Document History</a></li>
                <li><a href="/preference">Preference</a></li>
                <li><a href="/admin">Panel</a></li>
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
            <h2 style="color: rgb(6, 4, 60); font-weight: bold">Create New Account</h2>
            <p>Fill in the details below to create a new user account</p>
        </div>

        <div class="form-container">
            <div class="form-info">
                <p>Fields marked with <span style="color: #ff0000;">*</span> are required. Please ensure all information is accurate before submitting.</p>
            </div>

            <form action="/admin/accounts/store" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name <span class="required">*</span></label>
                        <input type="text" id="first_name" name="first_name" placeholder="Enter first name" required>
                    </div>

                    <div class="form-group">
                        <label for="last_name">Last Name <span class="required">*</span></label>
                        <input type="text" id="last_name" name="last_name" placeholder="Enter last name" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Designation <span class="required">*</span></label>
                        <input type="text" id="designation" name="designation" placeholder="Enter designation" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email Address <span class="required">*</span></label>
                        <input type="email" id="email" name="email" placeholder="Enter email address" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Phone Number <span class="required">*</span></label>
                        <input type="tel" id="phone" name="phone" placeholder="Enter phone number" required>
                    </div>

                    <div class="form-group">
                        <label for="nic">NIC Number <span class="required">*</span></label>
                        <input type="text" id="nic" name="nic" placeholder="Enter NIC number" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="passcode">Passcode <span class="required">*</span></label>
                        <input type="passcode" id="passcode" name="passcode" placeholder="Enter passcode" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="status">Account Status <span class="required">*</span></label>
                        <select id="status" name="status" required>
                            <option value="active">Select status</option>
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <p id="p_p" style="color:#ff0000">Permissions</p><br />
                <div id="permissions" class="form-row">
                    <div id="permission1" class="form-group checkbox-group">
                        <input type="checkbox" id="view_officers" name="permissions[]" value="view_officers">
                        <label for="view_officers">View Officers</label>
                    </div>
                    <div id="permission2" class="form-group checkbox-group">
                        <input type="checkbox" id="view_halls" name="permissions[]" value="view_halls">
                        <label for="view_halls">View Halls</label>
                    </div>
                    <div id="permission3" class="form-group checkbox-group">
                        <input type="checkbox" id="view_quarters" name="permissions[]" value="view_quarters">
                        <label for="view_quarters">View Quarters</label>
                    </div>
                    <div id="permission4" class="form-group checkbox-group">
                        <input type="checkbox" id="view_auditlog" name="permissions[]" value="view_auditlog">
                        <label for="view_auditlog">View Audit Log</label>
                    </div>
                    <div id="permission5" class="form-group checkbox-group">
                        <input type="checkbox" id="administrative_officer_approval" name="permissions[]" value="administrative_officer_approval">
                        <label for="administrative_officer_approval">Administrative Officer Approval</label>
                    </div>
                    <div id="permission6" class="form-group checkbox-group">
                        <input type="checkbox" id="aditional_government_agent_approval" name="permissions[]" value="aditional_government_agent_approval">
                        <label for="aditional_government_agent_approval">Additional Government Agent Approval</label>
                    </div>
                    <div id="permission7" class="form-group checkbox-group">
                        <input type="checkbox" id="government_agent_approval" name="permissions[]" value="government_agent_approval">
                        <label for="government_agent_approval">Government Agent Approval</label>
                    </div>
                    <div id="permission8" class="form-group checkbox-group">
                        <input type="checkbox" id="preference" name="permissions[]" value="preference">
                        <label for="preference">Preference</label>
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit" class="submit-btn">Create Account</button>
                    <button type="reset" class="reset-btn">Reset Form</button>
                </div>
            </form>
        </div>
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