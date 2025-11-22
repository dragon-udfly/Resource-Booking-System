<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>District Secretariat - Vavuniya</title>
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

        .login-button:hover:not(:disabled) {
            background-color: #0056b3; /* Darker blue on hover */
            transform: translateY(-1px); /* Slight lift effect */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Subtle shadow */
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
    <section class="banner" style="position: relative;">
        <a href="/" style="position: absolute; top: 20px; left: 20px; padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">Back to Home</a>
        <div style="display: flex; justify-content: center; align-items: center; height: 100%;">
            <div style="background-color: white; padding: 40px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                <h1 style="text-align: center; margin-bottom: 20px;">Login</h1>
                <form id="loginForm">
                    <div style="margin-bottom: 20px;">
                        <label for="username" style="display: block; margin-bottom: 5px;">Username</label>
                        <input type="text" id="username" name="username" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label for="password" style="display: block; margin-bottom: 5px;">Password</label>
                        <input type="password" id="password" name="password" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                    </div>
                    <button type="submit" id="loginButton" disabled class="login-button" style="width: 100%; padding: 10px; background-color: #ff0000; color: white; border: none; border-radius: 5px; cursor: pointer;">Login</button>
                </form>
            </div>
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
    <script>
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const loginButton = document.getElementById('loginButton');

        function validateForm() {
            if (usernameInput.value.trim() !== '' && passwordInput.value.trim() !== '') {
                loginButton.disabled = false;
            } else {
                loginButton.disabled = true;
            }
        }

        usernameInput.addEventListener('input', validateForm);
        passwordInput.addEventListener('input', validateForm);
    </script>
</body>
</html>