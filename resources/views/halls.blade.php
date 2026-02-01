@extends('layouts.admin_body_layout')

@section('title', 'Halls - District Secretariat Vavuniya')

@section('page_styles')
    <style>
        .page-header { text-align: center; margin-bottom: 30px; color: #333; }
        .page-header h2 { font-size: 2.5em; margin-bottom: 10px; }
        .page-header p { font-size: 1.1em; color: #555; }
        table { width: 90%; margin: 20px auto; border-collapse: collapse; box-shadow: 0 0 15px rgba(0,0,0,0.1); background-color: #fff; }
        th, td { border: 1px solid #ddd; padding: 12px 15px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; color: #333; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #f1f1f1; }
        .add-officer-btn { display: inline-block; padding: 12px 25px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .action-btn { padding: 8px 12px; margin: 2px; border: none; border-radius: 4px; cursor: pointer; color: white; font-size: 0.9em; text-decoration: none; display: inline-block; }
        .modify-btn { background-color: #ffc107; color: #333; }
        .delete-btn { background-color: #dc3545; }
        .btn { padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 1em; font-weight: bold; text-decoration: none; color: white; }
        .back-button { background-color: #6c757d; }
    </style>
@endsection

@section('content')
    <section class="banner">
         <div style="width: 90%; max-width: 900px; text-align: left; margin-bottom: 20px;">
            <a href="#" onclick="history.back(); return false;" class="btn back-button">Back</a>
        </div>
        <div class="page-header">
            <h2 style="color: rgb(6, 4, 60); font-weight: bold">Halls List</h2>
            <p>Manage Halls by modifying or deleting entries</p>
        </div>

        <div style="text-align: center; margin-bottom: 20px;">
            <a href="{{ route('halls.create') }}" class="add-officer-btn">Add Hall</a>
        </div>

        <table id="hall-details">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Hall ID</th>
                    <th>Hall Type</th>
                    <th>Capacity</th>
                    <th>Special Notice</th>
                    <th>Hall Status</th>
                    <th>Date Created</th>
                    <th>Date Modified</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if($halls->isEmpty())
                    <tr>
                        <td colspan="9" style="text-align: center;">No halls found.</td>
                    </tr>
                @else
                    @foreach($halls as $index => $hall)
                        <tr data-hall-row="{{ $hall->hall_id }}">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $hall->hall_id }}</td>
                            <td>{{ $hall->hall_type }}</td>
                            <td>{{ $hall->capacity }}</td>
                            <td>{{ $hall->special_notice }}</td>
                            <td>{{ $hall->current_state }}</td>
                            <td>{{ \Carbon\Carbon::parse($hall->date_created)->format('Y-m-d h:i A') }}</td>
                            <td>{{ $hall->date_modified ? \Carbon\Carbon::parse($hall->date_modified)->format('Y-m-d h:i A') : 'N/A' }}</td>
                            <td>
                                <a href="{{ route('halls.edit', $hall) }}" class="action-btn modify-btn">Modify</a>
                                <button class="action-btn delete-btn" data-hall-id="{{ $hall->hall_id }}" data-hall-name="{{ $hall->hall_type }}">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
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
    .modal-buttons .btn { padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; border:none; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalOverlay = document.getElementById('modal-overlay');
    if (!modalOverlay) return;

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
            buttonEl.addEventListener('click', btn.onClick);
            modalButtons.appendChild(buttonEl);
        });
        modalOverlay.classList.add('active');
    };

    const hideModal = () => {
        modalOverlay.classList.remove('active');
    };

    document.body.addEventListener('click', function(e) {
        if (e.target.classList.contains('delete-btn')) {
            const hallId = e.target.dataset.hallId;
            const hallName = e.target.dataset.hallName;
            
            const confirmButtons = [
                { text: 'Yes, Delete', class: 'delete-btn action-btn', onClick: () => performDelete(hallId) },
                { text: 'Cancel', class: 'back-button', onClick: hideModal }
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

            const responseText = await response.text();
            
            if (!response.ok) {
                let message = `Error: ${response.status} ${response.statusText}`;
                try { message = JSON.parse(responseText).message || message; } catch (e) {}
                showModal('Error', message, [{ text: 'OK', class: 'back-button', onClick: hideModal }]);
            } else {
                const result = JSON.parse(responseText);
                const row = document.querySelector(`tr[data-hall-row="${hallId}"]`);
                if (row) {
                    row.style.transition = 'opacity 0.5s ease';
                    row.style.opacity = '0';
                    setTimeout(() => row.remove(), 500);
                }
                showModal('Success', result.message, [{ text: 'OK', class: 'back-button', onClick: hideModal }]);
            }
        } catch (error) {
            console.error('Fetch error:', error);
            showModal('Request Failed', 'Could not connect to the server.', [{ text: 'OK', class: 'back-button', onClick: hideModal }]);
        }
    };
});
</script>
@endpush