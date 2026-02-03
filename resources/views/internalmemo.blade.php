@extends('layouts.admin_body_layout')

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
                <span class="badge badge-light">{{ $receivedMemos->count() }} Messages</span>
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
                        <tbody>
                            @forelse($receivedMemos as $memo)
                                <tr class="{{ $memo->is_read ? '' : 'row-unread' }}">
                                    <td>{{ $memo->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        @if($memo->sender)
                                            {{ $memo->sender->designation }} - {{ $memo->sender->first_name }}
                                        @else
                                            <span class="text-muted">Unknown Sender</span>
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($memo->subject, 50) }}</td>
                                    <td>
                                        @if($memo->status == 0)
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($memo->status == 1)
                                            <span class="badge badge-success">Yes / Agreed</span>
                                        @elseif($memo->status == 2)
                                            <span class="badge badge-danger">No / Disagreed</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-outline-primary view-memo-btn"
                                            data-id="{{ $memo->id }}">Open</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center" style="padding: 30px; color: #777;">
                                        No received memos found.
                                    </td>
                                </tr>
                            @endforelse
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
                        <tbody>
                            @forelse($sentMemos as $memo)
                                <tr>
                                    <td>{{ $memo->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        @if($memo->receiver)
                                            {{ $memo->receiver->designation }} - {{ $memo->receiver->first_name }}
                                        @else
                                            <span class="text-muted">Unknown</span>
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($memo->subject, 50) }}</td>
                                    <td>
                                        @if($memo->status == 0)
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($memo->status == 1)
                                            <span class="badge badge-success">Yes / Agreed</span>
                                        @elseif($memo->status == 2)
                                            <span class="badge badge-danger">No / Disagreed</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center" style="padding: 30px; color: #777;">No sent memos.</td>
                                </tr>
                            @endforelse
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
                    <button class="btn btn-danger response-btn" data-value="2">NO</button>
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

            let currentMemoId = null;

            viewButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const memoId = this.getAttribute('data-id');
                    currentMemoId = memoId;

                    // Reset UI
                    modalSender.textContent = 'Loading...';
                    modalSubject.textContent = '';
                    modalBody.textContent = '';
                    responseSection.classList.add('d-none');
                    statusDisplay.classList.add('d-none');

                    openModal('viewMemoModal');

                    fetch(`/internal-memo/${memoId}`)
                        .then(response => response.json())
                        .then(data => {
                            modalSender.textContent = data.sender;
                            modalDate.textContent = data.date;
                            modalSubject.textContent = data.subject;
                            modalBody.textContent = data.body;

                            // Check Response Capability
                            if (data.can_respond) {
                                responseSection.classList.remove('d-none');
                                statusDisplay.classList.add('d-none');
                            } else {
                                responseSection.classList.add('d-none');
                                statusDisplay.classList.remove('d-none');

                                let statusText = 'Pending';
                                let statusClass = 'badge badge-warning';

                                if (data.status == 1) {
                                    statusText = 'Yes / Agreed';
                                    statusClass = 'badge badge-success';
                                } else if (data.status == 2) {
                                    statusText = 'No / Disagreed';
                                    statusClass = 'badge badge-danger';
                                }

                                statusBadge.className = statusClass;
                                statusBadge.textContent = statusText;
                            }

                            // Mark row read locally
                            const row = btn.closest('tr');
                            row.classList.remove('row-unread');
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Failed to load memo details.');
                            closeModal('viewMemoModal');
                        });
                });
            });

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
                                    alert(data.message);
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
        });
    </script>
@endsection