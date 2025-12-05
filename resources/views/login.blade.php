@extends('layouts.normal_body_layout')

@section('title', 'District Secretariat - Vavuniya')

@section('page_styles')
    <style>
        .login-button:hover:not(:disabled) {
            background-color: #0056b3; /* Darker blue on hover */
            transform: translateY(-1px); /* Slight lift effect */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Subtle shadow */
        }

        /* Generic button styles */
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            font-weight: bold;
            text-decoration: none;
            color: white;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        /* Specific back button styles */
        .back-button {
            background-color: #6c757d;
        }
        .back-button:hover {
            background-color: #5a6268;
            transform: translateY(-1px);
        }
    </style>
@endsection

@section('content')
    <!-- Cyan/Turquoise Banner Section -->
    <section class="banner">
        <div style="width: 90%; max-width: 900px; text-align: left; margin-bottom: 20px;">
            <a href="#" onclick="history.back(); return false;" class="btn back-button">Back</a>
        </div>
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
@endsection

@push('scripts')
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
@endpush
