<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Account - District Secretariat Vavuniya</title>
    <link href="{{ asset('icons/right_logo.png') }}" rel='icon' type='image/png'>
    <style>
        /* Reusing styles for consistency */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; }
        .header { background-color: #f8f9fa; display: flex; flex-direction: column; align-items: center; border-bottom: 3px solid #ddd; }
        .header-main { display: flex; align-items: center; justify-content: space-between; width: 100%; }
        .logo-left { width: 110px; height: 22vh; margin-left: 70px; }
        .header-content { flex: 1; text-align: center; padding: 0 10px; }
        .header-content h1 { font-size: 40px; font-weight: bold; color: #000; padding-bottom: 20px; }
        .header-content h2 { font-size: 25px; font-weight: normal; color: #333; }
        .logo-right { width: 130px; height: 22vh; margin-right: 70px; }
        .navbar { display: flex; justify-content: space-between; align-items: center; width: 100%; padding: 10px 20px; background-color: #e9ecef; border-top: 1px solid #dee2e6; }
        .navbar ul { list-style: none; display: flex; margin: 0; padding: 0; }
        .navbar li { margin-right: 20px; }
        .navbar a { text-decoration: none; color: #007bff; font-weight: bold; }
        .navbar a:hover { color: #0056b3; }
        .navbar-right { margin-left: auto; }
        .banner { background: linear-gradient(180deg, #7dd3d9 0%, #a8e6ea 100%); min-height: 58vh; width: 100%; display: flex; flex-direction: column; align-items: center; padding: 20px; }
        .page-header { text-align: center; margin-bottom: 30px; color: #333; }
        .page-header h2 { font-size: 2.5em; margin-bottom: 10px; }
        .page-header p { font-size: 1.1em; color: #555; }
        .form-container { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); width: 90%; max-width: 900px; margin-top: 20px; }
        .form-row { display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 20px; }
        .form-group { flex: 1; min-width: 280px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; }
        .form-group input[type="text"], .form-group input[type="email"], .form-group input[type="tel"], .form-group input[type="password"] { width: 100%; padding: 10px 12px; border: 1px solid #ced4da; border-radius: 4px; font-size: 1em; }
        .form-group input[readonly] { background-color: #e9ecef; }
        .required { color: #dc3545; margin-left: 5px; }
        .checkbox-group {
            display: flex;
            align-items: center;
            flex: 0 0 auto;
            min-width: unset;
        }
        .checkbox-group input[type="checkbox"] {
            width: auto;
            margin-right: 10px;
        }
        .checkbox-group label {
            margin-bottom: 0;
            font-weight: normal;
        }
        .input-with-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .input-with-toggle input {
            flex-grow: 1;
        }
        .button-group { display: flex; justify-content: flex-end; gap: 15px; margin-top: 30px; }
        .submit-btn { padding: 12px 25px; border: none; border-radius: 5px; cursor: pointer; font-size: 1em; font-weight: bold; background-color: #007bff; color: white; text-decoration: none; }
        .footer { background-color: #000; height: 17vh; width: 100%; color: white; text-align: center; padding-top: 20px; }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-main">
            <img src="{{ asset('icons/left_logo.png') }}" alt="Sri Lanka government logo" class="logo-left">
            <div class="header-content">
                <h1>District Secretariat - Vavuniya</h1>
                <h2>Hall and Quarters Booking System - Administrator</h2>
            </div>
            <img src="{{ asset('icons/right_logo.png') }}" alt="district Secretariat vavuniya logo" class="logo-right">
        </div>
        <nav class="navbar">
            <ul class="navbar-left">
                <li><a href="#">Document History</a></li>
                <li><a href="{{ route('preference') }}">Preference</a></li>
                <li><a href="/admin">Panel</a></li>
            </ul>
            <ul class="navbar-right">
                @auth
                <li id="loggedin_user" style="color: rgb(6, 4, 60); font-weight: bold">
                    {{ Auth::user()->designation }}, {{ Auth::user()->first_name }}
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: #007bff; font-weight: bold; cursor: pointer; font-size: 1em; padding: 0; text-decoration: none;">Log Out</button>
                    </form>
                </li>
                @endauth
            </ul>
        </nav>
    </header>

    <section class="banner">
        <div style="width: 90%; max-width: 900px; text-align: left; margin-bottom: 20px;">
            <a href="#" onclick="history.back(); return false;" class="btn submit-btn" style="background-color: #6c757d;">Back</a>
        </div>
        <div class="page-header">
            <h2 style="color: rgb(6, 4, 60); font-weight: bold">Modify Officer Account ({{ $user->user_id }})</h2>
            <p>Update the details for the selected officer</p>
        </div>

        <div class="form-container">
            @if ($errors->any())
                <div class="alert alert-danger" style="background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="modify-user-form" action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name <span class="required">*</span></label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name <span class="required">*</span></label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="designation">Designation(Read Only)</label>
                        <input type="text" id="designation" name="designation" value="{{ old('designation', $user->designation) }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address <span class="required">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="contact_number">Phone Number</label>
                        <input type="tel" id="contact_number" name="contact_number" value="{{ old('contact_number', $user->contact_number) }}">
                    </div>
                    <div class="form-group">
                        <label for="nic_number">NIC Number (Read Only)</label>
                        <input type="text" id="nic_number" name="nic_number" value="{{ $user->nic_number }}" readonly>
                    </div>
                </div>

                <hr style="margin: 30px 0;">

                <h4 style="margin-bottom: 15px;">Change Passcode</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label for="passcode">New Passcode</label>
                        <div class="input-with-toggle">
                            <input type="password" id="passcode" name="passcode">
                            <button type="button" class="btn submit-btn" style="background-color: #6c757d;" data-target="passcode">Show</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="passcode_confirmation">Confirm New Passcode</label>
                        <div class="input-with-toggle">
                            <input type="password" id="passcode_confirmation" name="passcode_confirmation">
                            <button type="button" class="btn submit-btn" style="background-color: #6c757d;" data-target="passcode_confirmation">Show</button>
                        </div>
                    </div>
                </div>

                <hr style="margin: 30px 0;">
                
                <h4 style="margin-bottom: 15px;">Permissions</h4>
                <div id="permissions" class="form-row">
                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="view_officers" name="permissions[]" value="view_officers" @if($user->permissions && $user->permissions->view_officers) checked @endif>
                        <label for="view_officers">View Officers</label>
                    </div>
                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="view_halls" name="permissions[]" value="view_halls" @if($user->permissions && $user->permissions->view_halls) checked @endif>
                        <label for="view_halls">View Halls</label>
                    </div>
                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="view_quarters" name="permissions[]" value="view_quarters" @if($user->permissions && $user->permissions->view_quarters) checked @endif>
                        <label for="view_quarters">View Quarters</label>
                    </div>
                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="view_audit_log" name="permissions[]" value="view_audit_log" @if($user->permissions && $user->permissions->view_audit_log) checked @endif>
                        <label for="view_audit_log">View Audit Log</label>
                    </div>
                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="administrative_officer_approval" name="permissions[]" value="administrative_officer_approval" @if($user->permissions && $user->permissions->administrative_officer_approval) checked @endif>
                        <label for="administrative_officer_approval">Administrative Officer Approval</label>
                    </div>
                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="additional_government_agent_approval" name="permissions[]" value="additional_government_agent_approval" @if($user->permissions && $user->permissions->additional_government_agent_approval) checked @endif>
                        <label for="additional_government_agent_approval">Additional Government Agent Approval</label>
                    </div>
                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="government_agent_approval" name="permissions[]" value="government_agent_approval" @if($user->permissions && $user->permissions->government_agent_approval) checked @endif>
                        <label for="government_agent_approval">Government Agent Approval</label>
                    </div>
                     <div class="form-group checkbox-group">
                        <input type="checkbox" id="account_setting" name="permissions[]" value="account_setting" @if($user->permissions && $user->permissions->account_setting) checked @endif>
                        <label for="account_setting">Preference</label>
                    </div>
                </div>

                <div class="button-group">

                    <button type="submit" class="submit-btn">Save Changes</button>
                </div>
            </form>
        </div>
    </section>

    <footer class="footer">
        <p>&copy; 2025 District Secretariat, Vavuniya. All Rights Reserved.</p>
    </footer>

    <script>
        document.getElementById('modify-user-form').addEventListener('submit', function(event) {
            if (!confirm('Are you sure you want to save these changes?')) {
                event.preventDefault();
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const toggleButtons = document.querySelectorAll('.input-with-toggle .btn');

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
</body>
</html>
