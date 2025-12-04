<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to the Resource Booking System - District Secretariat</title>    
    <link href='icons/right_logo.png' rel='icon' type='image/png'>
    <style>
        /* BASE STYLES from the implied layout */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            min-height: 100vh; /* Ensure full viewport height */
            display: flex;
            flex-direction: column;
            background-color: #f4f4f4; /* Added body background color */
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

        /* BANNER STYLES */
        .banner {
            background: linear-gradient(180deg, #7dd3d9 0%, #a8e6ea 100%);
            flex-grow: 1; /* Allows the banner to fill the remaining vertical space */
            width: 100%;
            padding: 20px;
        }
        
        .footer {
            background-color: #000;
            width: 100%;
        }

        /* HOMEPAGE UNIQUE STYLES (Content and Buttons) */
        .content-area { 
            text-align: center; 
            padding-top: 100px;
        }
        .main-title { 
            color: rgb(6, 4, 60); 
            font-size: 3em; 
        }
        .sub-text { 
            color: rgb(71, 66, 85); 
            font-size: 1.2em; 
            margin-top: 20px; 
        }
        .action-button {
            display: inline-block; 
            margin-top: 30px; 
            padding: 15px 30px; 
            background-color: #007bff; 
            color: white; 
            text-decoration: none; 
            border-radius: 5px; 
            font-size: 1.2em;
            transition: background-color 0.3s, transform 0.2s;
        }
        .action-button:hover {
            background-color: #0056b3;
            transform: scale(1.02);
        }
    </style>
</head>
<body>
    
    <!-- HEADER -->
    <header class="header">
        <img src="{{ asset('icons/left_logo.png') }}" alt="Sri Lanka government logo" class="logo-left">
        <div class="header-content">
            <h1>District Secretariat - Vavuniya</h1>
            <h2>Hall and Quarters Booking System</h2>
        </div>
        <img src="{{ asset('icons/right_logo.png') }}" alt="district Secretariat vavuniya logo" class="logo-right">
    </header>

    <!-- MAIN CONTENT SECTION -->
    <section class="banner">
        <div class="content-area">
            <h1 class="main-title">Welcome to the Resource Booking System</h1>
            <p class="sub-text">Please log in to approve hall booking and quarter reservation applications.</p>
            
            <!-- Login Button -->
            <a href="/login" class="action-button">Login</a>
            
            <!-- Book Hall Button -->
            <a href="{{ route('halls.schedule') }}" class="action-button" style="margin-left: 10px;">Book Hall</a>
            
            <!-- Book Quarter Button -->
            <a href="/book-quarter" class="action-button" style="margin-left: 10px;">Book Quarter</a>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer" style="color: white; text-align: center; padding-top: 20px;">
        <p>&copy; 2025 District Secretariat, Vavuniya. All Rights Reserved.</p>
        <p style="margin-top: 10px;">
        </p>
    </footer>
</body>
</html>