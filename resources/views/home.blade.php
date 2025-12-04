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

        /* BANNER STYLES */
        .banner {
            background: linear-gradient(180deg, #7dd3d9 0%, #a8e6ea 100%);
            flex-grow: 1; /* Allows the banner to fill the remaining vertical space */
            width: 100%;
            padding: 20px;
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
    
    @include('partials.header')

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

   @include('partials.footer')
</body>
</html>