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

        .banner {
            background: linear-gradient(180deg, #7dd3d9 0%, #a8e6ea 100%);
            min-height: 65vh; /* Use min-height instead of fixed height */
            width: 100%;
        }

        div a {
            display: block; 
            padding: 20px 40px; 
            background-color: #007bff; 
            color: white; 
            text-decoration: none; 
            border-radius: 8px; 
            font-size: 1.5em; 
            text-align: center; 
            transition: background-color 0.3s ease;
        }

        .banner div {
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100%; 
            gap: 80px; 
            display: flex; 
            flex-direction: row; 
            padding: 20px;
        }
    </style>
</head>
<body>
    @include('partials.header_nav')
    
    <!-- Cyan/Turquoise Banner Section -->
    <section class="banner">
        <div>
            <a href="/officers">Officers</a>
            <a href="/quarters">Quarters</a>
            <a href="/halls">Halls</a>
        </div>
    </section>

    @include('partials.footer')
</body>
</html>