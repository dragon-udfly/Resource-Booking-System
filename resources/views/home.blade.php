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
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px solid #ddd;
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

        .banner {
            background: linear-gradient(180deg, #7dd3d9 0%, #a8e6ea 100%);
            height: 58vh;
            width: 100%;
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
        <img src="icons/left_logo.png" alt="Sri Lanka government logo" class="logo-left">
        <div class="header-content">
            <h1>District Secretariat - Vavuniya</h1>
            <h2>Hall and Quarters Booking System</h2>
        </div>
        <img src="icons/right_logo.png" alt="district Secretariat vavuniya logo" class="logo-right">
    </header>

    <!-- Cyan/Turquoise Banner Section -->
    <section class="banner">
        <div style="text-align: center; padding-top: 100px;">
            <h1 style="color: rgb(6, 4, 60); font-size: 3em;">Welcome to the Resource Booking System</h1>
            <p style="color: rgb(71, 66, 85); font-size: 1.2em; margin-top: 20px;">Please log in to approve hall booking and quarter reservation applications.</p>
            <a href="/login" style="display: inline-block; margin-top: 30px; padding: 15px 30px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; font-size: 1.2em;">Login</a>
            <a href="{{ route('halls.book') }}" style="display: inline-block; margin-top: 30px; padding: 15px 30px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; font-size: 1.2em; margin-left: 10px;">Book Hall</a>
            <a href="/book-quarter" style="display: inline-block; margin-top: 30px; padding: 15px 30px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; font-size: 1.2em; margin-left: 10px;">Book Quarter</a>

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