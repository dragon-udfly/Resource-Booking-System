<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>District Secretariat - Vavuniya</title>
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
            height: 65vh;
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
                <li><a href="/auditlog">Audit Log</a></li>
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
        <div style="display: flex; justify-content: center; align-items: center; height: 100%; gap: 80px;">
            <a href="/officers" style="display: block; padding: 20px 40px; background-color: #007bff; color: white; text-decoration: none; border-radius: 8px; font-size: 1.5em; text-align: center; transition: background-color 0.3s ease;">Officers</a>
            <a href="/quarters" style="display: block; padding: 20px 40px; background-color: #007bff; color: white; text-decoration: none; border-radius: 8px; font-size: 1.5em; text-align: center; transition: background-color 0.3s ease;">Quarters</a>
            <a href="/halls" style="display: block; padding: 20px 40px; background-color: #007bff; color: white; text-decoration: none; border-radius: 8px; font-size: 1.5em; text-align: center; transition: background-color 0.3s ease;">Halls</a>
        </div>
    </section>

    @include('partials.footer')
</body>
</html>