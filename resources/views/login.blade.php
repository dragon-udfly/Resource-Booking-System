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
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        .login-button:hover:not(:disabled) {
            background-color: #0056b3; /* Darker blue on hover */
            transform: translateY(-1px); /* Slight lift effect */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Subtle shadow */
        }
    </style>
</head>
<body>
    @include('partials.header')

    <!-- Cyan/Turquoise Banner Section -->
    <section class="banner">
        <a href="/" style="top: 20px; left: 20px; padding: 15px 20px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 5px;">Home</a>
        <br />
        <div style="display: flex; justify-content: center; align-items: center; height: 100%;">
            <div style="background-color: white; padding: 40px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                <h1 style="color: rgb(6, 4, 60); text-align: center; margin-bottom: 20px;">Login</h1>
                <form id="loginForm" method="POST" action="{{ route('login.submit') }}">
                    @csrf
                    <div style="margin-bottom: 20px;">
                        <label for="nic_number" style="display: block; margin-bottom: 5px;">NIC</label>
                        <input 
                            type="text" 
                            id="nic_number" 
                            name="nic_number" 
                            style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" 
                            required
                        >
                        @error('nic_number')
                            <span style="color: red; font-size: small; display: block; margin-top: 5px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label for="passcode" style="display: block; margin-bottom: 5px;">Passcode</label>
                        <input 
                            type="password" 
                            id="passcode" 
                            name="passcode" 
                            style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" 
                            required
                        >
                        @error('passcode')
                            <span style="color: red; font-size: small; display: block; margin-top: 5px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <button 
                        type="submit" 
                        id="loginButton" 
                        class="login-button" 
                        style="width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;"
                    >
                        Login
                    </button>
                </form>
            </div>
        </div>
    </section>

   @include('partials.footer')

    <script>
        const usernameInput = document.getElementById('nic_number');
        const passwordInput = document.getElementById('passcode');
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