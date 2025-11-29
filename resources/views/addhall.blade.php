<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Hall - District Secretariat Vavuniya</title>
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

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .form-group input[type="text"],
        .form-group input[type="tel"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1em;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical; /* Allow vertical resizing */
        }

        .form-group.full-width {
            flex: 1 1 100%; /* Take full width */
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
                <li><a href="/account-setting">Preference</a></li>
            </ul>
            <ul class="navbar-right">
                <li>Admin, Thanuharan V.</li>
                <li><a href="/logout">Log Out</a></li>
            </ul>
        </nav>
    </header>

    <!-- Cyan/Turquoise Banner Section -->
    <section class="banner">
        <div class="page-header">
            <h2>Add New Hall</h2>
            <p>Fill in the details below to add a new hall to the system</p>
        </div>

        <div class="form-container">
            <div class="form-info">
                <p>Fields marked with <span style="color: #ff0000;">*</span> are required. Please ensure all information is accurate before submitting.</p>
            </div>

            <form action="/admin/accounts/store" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="hall_title">Hall Title <span class="required">*</span></label>
                        <input type="text" id="hall_title" name="hall_title" placeholder="Enter hall title" required>
                    </div>

                    <div class="form-group">
                        <label for="hall_type">Hall Type <span class="required">*</span></label>
                        <select id="hall_type" name="hall_type" required>
                            <option value="">Select hall type</option>
                            <option value="auditorium">Auditorium</option>
                            <option value="conference">Conference Hall</option>
                            <option value="training">Training Unit</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="location">Hall Location <span class="required">*</span></label>
                        <input type="text" id="location" name="location" placeholder="Enter location" required>
                    </div>
                    <div class="form-group">
                        <label for="capacity">Capacity (People) <span class="required">*</span></label>
                        <input type="text" id="capacity" name="capacity" placeholder="Enter seating capacity" required>
                    </div>
                    <div class="form-group full-width">
                        <label for="description">Description <span class="required">*</span></label>
                        <textarea id="description" name="description" placeholder="Enter detailed description of the hall" required></textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="chairs">Available Chairs <span class="required">*</span></label>
                        <input type="text" id="chairs" name="chairs" placeholder="Enter available chairs" required>
                    </div>
                    <div class="form-group">
                        <label for="contact_person">Contact Person <span class="required">*</span></label>
                        <input type="text" id="contact_person" name="contact_person" placeholder="Enter person to contact" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="status">Hall Status <span class="required">*</span></label>
                        <select id="status" name="status" required>
                            <option value="available">Select status</option>
                            <option value="available" selected>Available</option>
                            <option value="unavailable">Unavailable</option>
                            <option value="booked">Booked</option>
                        </select>
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit" class="submit-btn">Add Hall</button>
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