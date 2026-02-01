@extends('layouts.admin_body_layout')

@section('title', 'Modify Hall - District Secretariat Vavuniya')

@section('page_styles')
    <style>
        /* Reusing styles from addhall.blade.php for consistency */
        .page-header { text-align: center; margin-bottom: 30px; color: #333; }
        .page-header h2 { font-size: 2.5em; margin-bottom: 10px; }
        .page-header p { font-size: 1.1em; color: #555; }
        .form-container { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); width: 90%; max-width: 900px; margin-top: 20px; }
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
        .delete-btn { background-color: #dc3545; color: white; }
    </style>
@endsection

@section('content')
    <section class="banner">
        <div style="width: 90%; max-width: 900px; text-align: left; margin-bottom: 20px;">
            <a href="{{ route('halls.index') }}" class="btn submit-btn" style="background-color: #6c757d;">Back</a>
        </div>
        <div class="page-header">
            <h2 style="color: rgb(6, 4, 60); font-weight: bold">Modify Hall ({{ $hall->hall_id }})</h2>
            <p>Update the details for the selected hall</p>
        </div>

        <div class="form-container">
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
                        <label for="current_state">Hall Status <span class="required">*</span></label>
                        <select id="current_state" name="current_state" required>
                            <option value="available" @if(old('current_state', $hall->current_state) == 'available') selected @endif>Available</option>
                            <option value="unavailable" @if(old('current_state', $hall->current_state) == 'unavailable') selected @endif>Unavailable</option>
                        </select>
                    </div>
                    <div class="form-group full-width">
                        <label for="special_notice">Special Notice</label>
                        <textarea id="special_notice" name="special_notice" placeholder="Enter notice if the hall is temporary unavailable, including reasons and time period." maxlength="1200">{{ old('special_notice', $hall->special_notice) }}</textarea>
                    </div>
                </div>
                <div class="button-group">
                    <button type="submit" class="submit-btn">Save Changes</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Generic Modal Overlay -->
    <div id="modal-overlay" class="modal-overlay">
        <div class="modal-content">
            <h3 id="modal-title"></h3>
            <p id="modal-message"></p>
            <div id="modal-buttons" class="modal-buttons"></div>
        </div>
    </div>
@endsection

@push('scripts')
<style>
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); display: none; justify-content: center; align-items: center; z-index: 1000; opacity: 0; transition: opacity 0.3s ease; }
    .modal-overlay.active { display: flex; opacity: 1; }
    .modal-content { background: #fff; padding: 30px; border-radius: 8px; text-align: center; max-width: 450px; width: 90%; transform: scale(0.9); transition: transform 0.3s ease; }
    .modal-overlay.active .modal-content { transform: scale(1); }
    .modal-buttons { display: flex; justify-content: center; gap: 20px; margin-top: 20px; }
    .modal-buttons .btn { padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; border: none; color: white; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('modify-hall-form');
    const modalOverlay = document.getElementById('modal-overlay');
    const modalTitle = document.getElementById('modal-title');
    const modalMessage = document.getElementById('modal-message');
    const modalButtons = document.getElementById('modal-buttons');

    const showModal = (title, message, buttons) => {
        modalTitle.textContent = title;
        modalMessage.innerHTML = message;
        modalButtons.innerHTML = '';
        buttons.forEach(btn => {
            const buttonEl = document.createElement('button');
            buttonEl.textContent = btn.text;
            buttonEl.className = `btn ${btn.class}`;
            if(btn.style) buttonEl.style.cssText += btn.style;
            buttonEl.addEventListener('click', btn.onClick);
            modalButtons.appendChild(buttonEl);
        });
        modalOverlay.classList.add('active');
    };

    const hideModal = () => {
        modalOverlay.classList.remove('active');
    };

    // --- Update Form Submission ---
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const confirmButtons = [
                { text: 'Yes, Save Changes', class: 'submit-btn', onClick: () => performUpdate() },
                { text: 'Cancel', class: 'reset-btn', onClick: hideModal }
            ];
            showModal('Confirm Changes', 'Are you sure you want to save these changes?', confirmButtons);
        });

        const performUpdate = async () => {
            showModal('Processing...', 'Saving changes, please wait...', []);
            const formData = new FormData(form);
            const url = form.action;

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': formData.get('_token'), 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body: formData
                });
                const responseText = await response.text();

                if (!response.ok) {
                    let message = `Error: ${response.status} ${response.statusText}`;
                    try { message = JSON.parse(responseText).message || message; } catch (e) {}
                    showModal('Error', message, [{ text: 'OK', class: 'reset-btn', onClick: hideModal }]);
                } else {
                    try {
                        const result = JSON.parse(responseText);
                        showModal('Success', result.message, [
                            { text: 'Continue Editing', class: 'submit-btn', onClick: hideModal },
                            { text: 'View All Halls', class: 'btn', style: 'background-color: #6c757d;', onClick: () => window.location.href = "{{ route('halls.index') }}" }
                        ]);
                    } catch (e) {
                        showModal('Error', 'Received an invalid response from the server.', [{ text: 'OK', class: 'reset-btn', onClick: hideModal }]);
                    }
                }
            } catch (error) {
                console.error('Fetch error:', error);
                showModal('Request Failed', 'Could not connect to the server.', [{ text: 'OK', class: 'reset-btn', onClick: hideModal }]);
            }
        };
    }

    // --- Delete Button Submission ---
    document.body.addEventListener('click', function(e) {
        if (e.target.classList.contains('delete-btn')) {
            const hallId = e.target.dataset.hallId;
            const hallName = e.target.dataset.hallName;
            
            const confirmButtons = [
                { text: 'Yes, Delete', class: 'delete-btn', onClick: () => performDelete(hallId) },
                { text: 'Cancel', class: 'reset-btn', onClick: hideModal }
            ];
            showModal('Confirm Deletion', `Are you sure you want to delete ${hallName} (${hallId})? This action cannot be undone.`, confirmButtons);
        }
    });

    const performDelete = async (hallId) => {
        showModal('Processing...', 'Deleting hall, please wait...', []);
        
        const url = `/halls/${hallId}`;
        const csrfToken = '{{ csrf_token() }}';

        try {
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();
            
            if (!response.ok) {
                showModal('Error', result.message || 'An unknown error occurred.', [{ text: 'OK', class: 'reset-btn', onClick: hideModal }]);
            } else {
                showModal('Success', result.message, [
                    { text: 'View All Halls', class: 'btn', style: 'background-color: #6c757d;', onClick: () => window.location.href = "{{ route('halls.index') }}" }
                ]);
            }
        } catch (error) {
            console.error('Fetch error:', error);
            showModal('Request Failed', 'Could not connect to the server.', [{ text: 'OK', class: 'reset-btn', onClick: hideModal }]);
        }
    };

    if(modalOverlay) {
        modalOverlay.addEventListener('click', function(event) {
            if (event.target === modalOverlay) {
                hideModal();
            }
        });
    }
});
</script>
@endpush
