@extends('layouts.normal_body_layout')

@section('title', 'Preference - District Secretariat Vavuniya')

@section('page_styles')
    <style>
        .top-buttons {
            width: 90%;
            max-width: 900px;
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

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

        .btn-back {
            background-color: #6c757d;
        }
        .btn-back:hover {
            background-color: #5a6268;
        }

        .btn-logout {
            background-color: #dc3545;
        }
        .btn-logout:hover {
            background-color: #c82333;
        }

        .content-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 900px;
        }

        .info-group {
            margin-bottom: 20px;
            font-size: 1.1em;
        }
        
        .info-group span {
            font-weight: bold;
            color: #333;
        }

        .passcode-wrapper {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        #toggle-passcode {
            padding: 5px 10px;
            font-size: 0.9em;
        }

        .form-container {
            margin-top: 30px;
            border-top: 1px solid #dee2e6;
            padding-top: 20px;
        }

        .form-container h3 {
            margin-bottom: 20px;
            font-size: 1.5em;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .form-group input[type="password"],
        .form-group input[type="text"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1em;
        }

        .input-with-button {
            display: flex;
            align-items: center;
            gap: 10px; /* Space between input and button */
        }
        
        .input-with-button input {
            flex-grow: 1; /* Allow input to take available space */
        }

        .submit-btn {
            background-color: #007bff;
            color: white;
        }
        .submit-btn:hover {
            background-color: #0056b3;
        }

    </style>
@endsection

@section('content')
    @auth
    <section class="banner">
        <div class="top-buttons">
            <a href="#" onclick="history.back(); return false;" class="btn btn-back">Go Back</a>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-logout">Log Out</button>
            </form>
        </div>

        <div class="content-container">
            @if (session('success'))
                <div class="alert alert-success" style="background-color: #d4edda; border-color: #c3e6cb; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 4px; border: 1px solid transparent;">
                    {{ session('success') }}
                </div>
            @endif

            <div class="info-group">
                <h3>Name: <span id="show-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span></h3>
            </div>

            <div class="form-container">
                <h3>Change Passcode</h3>
                <form action="{{ route('password.change') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="new_passcode">New Passcode</label>
                        <div class="input-with-button">
                            <input type="password" id="new_passcode" name="new_passcode" required>
                            <button type="button" class="btn btn-back toggle-passcode-visibility" data-target="new_passcode">Show</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="new_passcode_confirmation">Confirm New Passcode</label>
                        <div class="input-with-button">
                            <input type="password" id="new_passcode_confirmation" name="new_passcode_confirmation" required>
                            <button type="button" class="btn btn-back toggle-passcode-visibility" data-target="new_passcode_confirmation">Show</button>
                        </div>
                    </div>
                    <button type="submit" class="btn submit-btn">Save Changes</button>
                </form>
            </div>
        </div>
    </section>
    @endauth

    @guest
        <p>You must be logged in to view this page. <a href="{{ route('login') }}">Click here to log in.</a></p>
    @endguest
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleButtons = document.querySelectorAll('.toggle-passcode-visibility');

            toggleButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const targetId = this.dataset.target;
                    const targetInput = document.getElementById(targetId);

                    if (targetInput.type === 'password') {
                        targetInput.type = 'text';
                        this.textContent = 'Hide';
                    } else {
                        targetInput.type = 'password';
                        this.textContent = 'Show';
                    }
                });
            });
        });
    </script>
@endpush