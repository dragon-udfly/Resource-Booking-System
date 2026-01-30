@extends('layouts.admin_body_layout')

@section('title', 'Audit Log - District Secretariat Vavuniya')

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
        .action-btn { padding: 8px 12px; margin: 2px; border: none; border-radius: 4px; cursor: pointer; color: white; font-size: 0.9em; }
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
            <h2 style="color: rgb(6, 4, 60); font-weight: bold">Audit Log Records</h2>
            <p>Viewing system audit log records as a list of changes and modifications done by users</p>
        </div>
        <div style="width: 90%; max-width: 900px; text-align: left; margin-bottom: 20px;">
            <button id="clear-log-btn" class="action-btn" style="background-color: #dc3545;">Clear Records</button>
        </div>
       <!-- Audit log table -->
        <table id="audit-log">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Log ID</th>
                    <th>Log Title</th>
                    <th>Performed By</th>
                    <th>Performed Date</th>
                    <th>Performed Time</th>
                </tr>
            </thead>
            <tbody>
                @if($auditLogs->isEmpty())
                    <tr>
                        <td colspan="6" style="text-align: center;">No audit log records found.</td>
                    </tr>
                @else
                    @foreach($auditLogs as $index => $log)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $log->audit_log_id }}</td>
                            <td>{{ $log->log_title }}</td>
                            <td>{{ $log->performed_by ?? $log->details }}</td>
                            <td>{{ $log->date_performed }}</td>
                            <td>{{ \Carbon\Carbon::parse($log->time_performed)->format('h:i:s A') }}</td>
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
    .modal-buttons .btn { padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; border: none; color: white; }
    .delete-btn { background-color: #dc3545; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const clearBtn = document.getElementById('clear-log-btn');
    if (clearBtn) {
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
                buttonEl.className = 'btn';
                if(btn.class) buttonEl.classList.add(btn.class);
                buttonEl.addEventListener('click', btn.onClick);
                modalButtons.appendChild(buttonEl);
            });
            modalOverlay.classList.add('active');
        };

        const hideModal = () => {
            modalOverlay.classList.remove('active');
        };

        clearBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const confirmButtons = [
                { text: 'Yes, Clear All', class: 'delete-btn', onClick: () => performClear() },
                { text: 'Cancel', class: 'back-button', onClick: hideModal }
            ];
            showModal('Confirm Clear', 'Are you sure you want to clear all audit log records? This action cannot be undone.', confirmButtons);
        });

        const performClear = async () => {
            showModal('Processing...', 'Clearing audit log, please wait...', []);
            
            const url = "{{ route('auditlog.clear') }}";
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
                    showModal('Success', JSON.parse(responseText).message, [{ text: 'OK', class: 'back-button', onClick: () => window.location.reload() }]);
                }
            } catch (error) {
                console.error('Fetch error:', error);
                showModal('Request Failed', 'Could not connect to the server.', [{ text: 'OK', class: 'back-button', onClick: hideModal }]);
            }
        };

        if(modalOverlay) {
            modalOverlay.addEventListener('click', function(event) {
                if (event.target === modalOverlay) {
                    hideModal();
                }
            });
        }
    }
});
</script>
@endpush