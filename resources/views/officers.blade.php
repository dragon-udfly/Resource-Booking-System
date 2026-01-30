@extends('layouts.admin_body_layout')

@section('title', 'Officers - District Secretariat Vavuniya')

@section('page_styles')
    <style>
        .page-header { text-align: center; margin-bottom: 30px; color: #333; }
        table { width: 90%; margin: 20px auto; border-collapse: collapse; box-shadow: 0 0 15px rgba(0,0,0,0.1); background-color: #fff; }
        th, td { border: 1px solid #ddd; padding: 12px 15px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; color: #333; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #f1f1f1; }
        .add-officer-btn { display: inline-block; padding: 12px 25px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .action-btn { padding: 8px 12px; margin: 2px; border: none; border-radius: 4px; cursor: pointer; color: white; font-size: 0.9em; }
        .modify-btn { background-color: #ffc107; color: #333; text-decoration: none; }
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
            <h2 style="color: rgb(6, 4, 60); font-weight: bold">Officers Management</h2>
            <p>Add officer to system and edit salary ranges for quarters</p>
        </div>

        <div style="text-align: center; margin-bottom: 20px;">
            <a href="createaccount" class="add-officer-btn">Add Officer</a>
            <a href="{{ route('gradesalary.index') }}" class="add-officer-btn" style="background-color:#2372d9">Edit Salary Range for Grade</a>
        </div>

        <div class="page-header">
            <h2 style="color: rgb(6, 4, 60); font-weight: bold">Officers List</h2>
            <p>Manage officers by modifying or deleting entries</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr data-user-row="{{ $user->user_id }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->user_id }}</td>
                    <td>{{ "{$user->first_name} {$user->last_name}" }}</td>
                    <td>{{ $user->designation }}</td>
                    <td>
                        <a href="{{ route('users.edit', $user) }}" class="action-btn modify-btn">Modify</a>
                        <button class="action-btn delete-btn" data-user-id="{{ $user->user_id }}" data-user-name="{{ "{$user->first_name} {$user->last_name}" }}">Delete</button>
                    </td>
                </tr>
                @endforeach
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
    .modal-buttons .btn { padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
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
            const userId = e.target.dataset.userId;
            const userName = e.target.dataset.userName;
            
            const confirmButtons = [
                { text: 'Yes, Delete', class: 'delete-btn', onClick: () => performDelete(userId) },
                { text: 'Cancel', class: 'back-button', onClick: hideModal }
            ];
            showModal('Confirm Deletion', `Are you sure you want to delete the account for ${userName} (${userId})? This action cannot be undone.`, confirmButtons);
        }
    });

    const performDelete = async (userId) => {
        showModal('Processing...', 'Deleting account, please wait...', []);
        
        const url = `/users/${userId}`;
        const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}';

        try {
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                }
            });

            const result = await response.json();

            if (!response.ok) {
                const errorButtons = [{ text: 'OK', class: 'back-button', onClick: hideModal }];
                showModal('Error', result.message || 'An unknown error occurred.', errorButtons);
            } else {
                // On success, remove the row from the table
                const row = document.querySelector(`tr[data-user-row="${userId}"]`);
                if (row) {
                    row.style.transition = 'opacity 0.5s ease';
                    row.style.opacity = '0';
                    setTimeout(() => row.remove(), 500);
                }
                const successButtons = [{ text: 'OK', class: 'back-button', onClick: hideModal }];
                showModal('Success', result.message, successButtons);
            }
        } catch (error) {
            console.error('Fetch error:', error);
            const errorButtons = [{ text: 'OK', class: 'back-button', onClick: hideModal }];
            showModal('Request Failed', 'Could not connect to the server.', errorButtons);
        }
    };
});
</script>
@endpush
