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
        .submit-btn, .reset-btn, .btn { padding: 12px 25px; border: none; border-radius: 5px; cursor: pointer; font-size: 1em; font-weight: bold; text-decoration: none; }
        .submit-btn { background-color: #007bff; color: white; }
        .reset-btn { background-color: #6c757d; color: white; }
    </style>
</head>
<body>
   @include('partials.header_nav')

    <section class="banner">
        <div style="width: 90%; max-width: 900px; text-align: left; margin-bottom: 20px;">
            <a href="#" onclick="history.back(); return false;" class="btn submit-btn" style="background-color: #6c757d;">Back</a>
        </div>
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

    @include('partials.footer')

    <script>
        document.getElementById('modify-hall-form').addEventListener('submit', function(event) {
            if (!confirm('Are you sure you want to save these changes?')) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
