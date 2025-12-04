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
            height: 65vh;
            width: 100%;
        }
    </style>
</head>
<body>
    @include('partials.header_nav')
    
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