@extends($layout ?? 'layouts.admin_body_layout')

@section('page_styles')
    <style>
        /* CUSTOM STYLES (No Bootstrap) */
        .container {
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        /* Cards */
        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
            overflow: hidden;
        }

        .card-header {
            padding: 15px 20px;
            font-weight: bold;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header.primary {
            background-color: #007bff;
        }

        .card-header.success {
            background-color: #28a745;
        }

        .card-header.secondary {
            background-color: #6c757d;
        }

        .card-body {
            padding: 20px;
        }

        /* Tables */
        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .row-unread {
            background-color: #e3f2fd;
            font-weight: bold;
        }

        /* Badges */
        .badge {
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 0.85em;
            font-weight: bold;
            display: inline-block;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-success {
            background-color: #28a745;
            color: white;
        }

        .badge-danger {
            background-color: #dc3545;
            color: white;
        }

        .badge-light {
            background-color: #f8f9fa;
            color: #212529;
        }

        /* Buttons */
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.2s;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-outline-primary {
            background-color: transparent;
            border: 1px solid #007bff;
            color: #007bff;
        }

        .btn-outline-primary:hover {
            background-color: #007bff;
            color: white;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .text-end {
            text-align: right;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .form-control,
        .form-select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 14px;
            resize: vertical;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }

        .col-half {
            width: 50%;
            padding: 0 10px;
            box-sizing: border-box;
        }

        .col-full {
            width: 100%;
            padding: 0 10px;
            box-sizing: border-box;
        }

        /* Custom Modal */
        .custom-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
        }

        .custom-modal.show {
            display: flex;
        }

        .modal-content {
            background-color: #fff;
            padding: 0;
            border-radius: 8px;
            width: 60%;
            max-width: 700px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #aaa;
        }

        .close-btn:hover {
            color: #000;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            padding: 15px 20px;
            border-top: 1px solid #eee;
            text-align: right;
        }

        /* Utilities */
        .d-none {
            display: none !important;
        }

        .text-center {
            text-align: center;
        }

        .mt-3 {
            margin-top: 15px;
        }

        .mb-3 {
            margin-bottom: 15px;
        }

        .me-2 {
            margin-right: 10px;
        }

        .text-muted {
            color: #6c757d;
        }

        .bg-light {
            background-color: #f8f9fa;
        }

        .p-3 {
            padding: 15px;
        }

        .border {
            border: 1px solid #dee2e6;
        }

        .rounded {
            border-radius: 4px;
        }
    </style>
@endsection

@section('content')
    <div class="container">

        <!-- INBOX SECTION -->
        <div class="card">
            <div class="card-header primary">
                <span>Received Memos (Inbox)</span>
                <div>
                    <span class="badge badge-light me-2">{{ $receivedMemos->count() }} Messages</span>
                    <button class="btn btn-danger btn-sm" onclick="confirmClearRead()"
                        style="padding: 2px 8px; font-size: 0.8em;">Clear Read</button>
                </div>
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>From</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="inboxTableBody">
                            @include('partials.memo_inbox_rows')
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- COMPOSE SECTION -->
        <div class="card" style="border: 1px solid #28a745;">
            <div class="card-header success">
                <span>Compose New Memo</span>
            </div>
            <div class="card-body">
                <form action="{{ route('memo.send') }}" method="POST" id="composeForm">
                    @csrf
                    <div class="row">
                        <div class="col-half form-group">
                            <label for="receiver_id" class="form-label">To (Officer)</label>
                            <select name="receiver_id" id="receiver_id" class="form-select" required>
                                <option value="" selected disabled>Select Designation - Officer Name</option>
                                @foreach($users as $user)
                                    <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-half form-group">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" name="subject" id="subject" class="form-control" required
                                placeholder="Enter memo subject...">
                        </div>
                        <div class="col-full form-group">
                            <label for="body" class="form-label">Description / Message</label>
                            <textarea name="body" id="body" class="form-control" rows="4" required
                                placeholder="Type your message here..."></textarea>
                        </div>
                        <div class="col-full text-end">
                            <button type="submit" class="btn btn-success" id="triggerSendModalBtn">Send Memo</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- SENT MESSAGES SECTION -->
        <div class="card">
            <div class="card-header secondary">
                <span>Sent Memos</span>
                <button class="btn btn-danger btn-sm" onclick="confirmClearSent()"
                    style="padding: 2px 8px; font-size: 0.8em;">Clear Sent</button>
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>To</th>
                                <th>Subject</th>
                                <th>Status (Their Response)</th>
                            </tr>
                        </thead>
                        <tbody id="sentTableBody">
                            @include('partials.memo_sent_rows')
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- Custom Modal: View Memo -->
    <div id="viewMemoModal" class="custom-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Internal Memo</h5>
                <button class="close-btn" onclick="closeModal('viewMemoModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong>From:</strong> <span id="modalSender"></span><br>
                    <strong>Date:</strong> <span id="modalDate"></span>
                </div>
                <div class="mb-3">
                    <h3 id="modalSubject" style="color: #007bff; margin-top: 0;"></h3>
                </div>
                <div class="p-3 bg-light border rounded">
                    <p id="modalBody" style="white-space: pre-wrap; margin: 0;"></p>
                </div>

                <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

                <div id="responseSection" class="text-center d-none">
                    <p class="mb-3"><strong>Please provide your response:</strong></p>
                    <button class="btn btn-success me-2 response-btn" data-value="1">YES</button>
                    <button class="btn btn-danger response-btn" data-value="0">NO</button>
                </div>
                <div id="statusDisplay" class="text-center d-none">
                    <strong>Current Status: </strong> <span id="statusBadge"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('viewMemoModal')">Close</button>
            </div>
        </div>
    </div>

    <!-- Custom Modal: Confirm Send -->
    <div id="confirmSendModal" class="custom-modal">
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Send</h5>
                <button class="close-btn" onclick="closeModal('confirmSendModal')">&times;</button>
            </div>
            <div class="modal-body">
                Are you sure you want to send this memo?
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('confirmSendModal')">Cancel</button>
                <button class="btn btn-success" id="finalSendBtn">Yes, Send</button>
            </div>
        </div>
    </div>

    <!-- Custom Modal: Confirm Clear Sent -->
    <div id="confirmClearSentModal" class="custom-modal">
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Clear</h5>
                <button class="close-btn" onclick="closeModal('confirmClearSentModal')">&times;</button>
            </div>
            <div class="modal-body">
                Are you sure you want to clear all <strong>RESOLVED</strong> memos from your outbox? Pending memos will not
                be cleared.
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('confirmClearSentModal')">Cancel</button>
                <button class="btn btn-danger" id="finalClearSentBtn">Yes, Clear</button>
            </div>
        </div>
    </div>

    <!-- Custom Modal: Confirm Clear Read -->
    <div id="confirmClearReadModal" class="custom-modal">
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Clear</h5>
                <button class="close-btn" onclick="closeModal('confirmClearReadModal')">&times;</button>
            </div>
            <div class="modal-body">
                Are you sure you want to clear all <strong>RESOLVED</strong> memos from your inbox? Pending memos will not
                be cleared.
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('confirmClearReadModal')">Cancel</button>
                <button class="btn btn-danger" id="finalClearReadBtn">Yes, Clear</button>
            </div>
        </div>
    </div>

    <script>
        // --- Modal Utilities ---
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('show');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('show');
        }

        // Close modal if clicking outside content
        window.onclick = function (event) {
            if (event.target.classList.contains('custom-modal')) {
                event.target.classList.remove('show');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // --- Sending Logic ---
            const composeForm = document.getElementById('composeForm');
            const triggerSendBtn = document.getElementById('triggerSendModalBtn');
            const finalSendBtn = document.getElementById('finalSendBtn');

            triggerSendBtn.addEventListener('click', function (e) {
                e.preventDefault();
                // Basic validation
                if (composeForm.checkValidity()) {
                    openModal('confirmSendModal');
                } else {
                    composeForm.reportValidity();
                }
            });

            finalSendBtn.addEventListener('click', function () {
                composeForm.submit();
            });

            // --- Viewing Logic ---
            const viewButtons = document.querySelectorAll('.view-memo-btn');
            const modalSender = document.getElementById('modalSender');
            const modalDate = document.getElementById('modalDate');
            const modalSubject = document.getElementById('modalSubject');
            const modalBody = document.getElementById('modalBody');
            const responseSection = document.getElementById('responseSection');
            const statusDisplay = document.getElementById('statusDisplay');
            const statusBadge = document.getElementById('statusBadge');

            // --- Event Delegation for Dynamic "Open" Buttons ---
            document.addEventListener('click', function (e) {
                if (e.target && e.target.classList.contains('view-memo-btn')) {
                    const btn = e.target;
                    const memoId = btn.getAttribute('data-id');
                    currentMemoId = memoId; // Store for valid response

                    // Clear previous
                    document.getElementById('modalSubject').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
                    document.getElementById('modalSender').innerText = '';
                    document.getElementById('modalDate').innerText = '';
                    document.getElementById('modalBody').innerText = '';
                    document.getElementById('responseSection').classList.add('d-none');
                    document.getElementById('statusDisplay').classList.add('d-none');

                    openModal('viewMemoModal');

                    fetch(`/internal-memo/${memoId}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.error) {
                                alert(data.error);
                                closeModal('viewMemoModal');
                                return;
                            }

                            // Auto-decrypt done by accessor
                            document.getElementById('modalSubject').innerText = data.subject;
                            document.getElementById('modalSender').innerText = data.sender;
                            document.getElementById('modalDate').innerText = data.date;
                            document.getElementById('modalBody').innerText = data.body;

                            // Show Response Buttons or Status
                            if (data.can_respond) {
                                document.getElementById('responseSection').classList.remove('d-none');
                            } else {
                                let statusText = 'Pending';
                                let statusClass = 'badge badge-warning';

                                if (data.status == 1) {
                                    statusText = 'OK / Agreed';
                                    statusClass = 'badge badge-success';
                                } else if (data.status == 0) {
                                    statusText = 'No / Disagreed';
                                    statusClass = 'badge badge-danger';
                                }

                                const statusBadge = document.getElementById('statusBadge');
                                statusBadge.className = `badge ${statusClass}`;
                                statusBadge.innerText = statusText;
                                document.getElementById('statusDisplay').classList.remove('d-none');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Failed to load memo details.');
                            closeModal('viewMemoModal');
                        });
                }
            });

            // --- Real-time Polling for Inbox ---
            setInterval(function () {
                fetch('{{ route("memo.fetch_inbox") }}')
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('inboxTableBody').innerHTML = html;
                    })
                    .catch(err => console.error('Failed to fetch new memos', err));
            }, 5000); // Poll every 5 seconds

            // --- Real-time Polling for Outbox (Status Updates) ---
            setInterval(function () {
                fetch('{{ route("memo.fetch_outbox") }}')
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('sentTableBody').innerHTML = html;
                    })
                    .catch(err => console.error('Failed to fetch sent memos', err));
            }, 5000); // Poll every 5 seconds

            // --- Respond Logic ---
            const responseBtns = document.querySelectorAll('.response-btn');
            responseBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const value = this.getAttribute('data-value');
                    const actionName = value == 1 ? 'YES' : 'NO';

                    if (confirm(`Are you sure you want to respond with "${actionName}"?`)) {
                        fetch(`/internal-memo/${currentMemoId}/respond`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ status: value })
                        })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    // alert(data.message); // Removed as per request
                                    location.reload();
                                } else {
                                    alert('Error: ' + data.message);
                                }
                            })
                            .catch(err => {
                                console.error(err);
                                alert('An unexpected error occurred.');
                            });
                    }
                });
            });
            // --- Clear Memos Logic ---
            window.confirmClearRead = function () {
                openModal('confirmClearReadModal');
            };

            document.getElementById('finalClearReadBtn').addEventListener('click', function () {
                fetch('{{ route("memo.clear_read") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('An unexpected error occurred.');
                    });
            });

            window.confirmClearSent = function () {
                openModal('confirmClearSentModal');
            };

            document.getElementById('finalClearSentBtn').addEventListener('click', function () {
                fetch('{{ route("memo.clear_sent") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('An unexpected error occurred.');
                    });
            });
        });
    </script>
@endsection