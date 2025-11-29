<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Hall - District Secretariat Vavuniya</title>
    <link href="{{ asset('icons/right_logo.png') }}" rel='icon' type='image/png'>
    <style>
        /* Reusing styles from addhall.blade.php for consistency */
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
        .form-info { background-color: #e9f7ef; border-left: 5px solid #28a745; padding: 15px; margin-bottom: 20px; border-radius: 4px; color: #218838; }
        .form-row { display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 20px; }
        .form-group { flex: 1; min-width: 280px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; }
        .form-group input[type="text"], .form-group input[type="number"], .form-group select, .form-group textarea { width: 100%; padding: 10px 12px; border: 1px solid #ced4da; border-radius: 4px; font-size: 1em; }
        .form-group textarea { min-height: 100px; resize: vertical; }
        .form-group.full-width { flex: 1 1 100%; }
        .required { color: #dc3545; margin-left: 5px; }
        .button-group { display: flex; justify-content: flex-end; gap: 15px; margin-top: 30px; }
        .submit-btn, .reset-btn { padding: 12px 25px; border: none; border-radius: 5px; cursor: pointer; font-size: 1em; font-weight: bold; }
        .submit-btn { background-color: #007bff; color: white; }
        .reset-btn { background-color: #6c757d; color: white; }
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
                        <button type="submit" style="background: none; border: none; color: #007bff; font-weight: bold; cursor: pointer; font-size: 1em; padding: 0;">Log Out</button>
                    </form>
                </li>
                @endauth
            </ul>
        </nav>
    </header>

    <section class="banner">
        <div class="page-header">
            <h2 style="color: rgb(6, 4, 60); font-weight: bold">Modify Hall ({{ $hall->hall_id }})</h2>
            <p>Update the details for the selected hall</p>
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

            <form id="modify-hall-form" action="{{ route('halls.update', $hall) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="form-row">
                    <div class="form-group">
                        <label for="hall_type">Hall Type <span class="required">*</span></label>
                        <input type="text" id="hall_type" name="hall_type" value="{{ old('hall_type', $hall->hall_type) }}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="capacity">Capacity (People) <span class="required">*</span></label>
                        <input type="number" id="capacity" name="capacity" value="{{ old('capacity', $hall->capacity) }}" required>
                    </div>
                    <div class="form-group full-width">
                        <label for="description">Description <span class="required">*</span></label>
                        <textarea id="description" name="description" required>{{ old('description', $hall->description) }}</textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="booking_state">Booking Status <span class="required">*</span></label>
                        <select id="booking_state" name="booking_state" required>
                            <option value="available" @if(old('booking_state', $hall->booking_state) == 'available') selected @endif>Available</option>
                            <option value="booked" @if(old('booking_state', $hall->booking_state) == 'booked') selected @endif>Booked</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="current_state">Hall Status <span class="required">*</span></label>
                        <select id="current_state" name="current_state" required>
                            <option value="available" @if(old('current_state', $hall->current_state) == 'available') selected @endif>Available</option>
                            <option value="unavailable" @if(old('current_state', $hall->current_state) == 'unavailable') selected @endif>Unavailable</option>
                        </select>
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
        document.getElementById('modify-hall-form').addEventListener('submit', function(event) {
            if (!confirm('Are you sure you want to save these changes?')) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
