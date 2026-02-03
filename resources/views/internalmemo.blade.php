@extends('layouts.admin_body_layout')

@section('page_styles')
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <!-------------------- INBOX SECTION -------------------->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-inbox me-2"></i> Received Memos (nbox)</h5>
                        <span class="badge bg-light text-dark">{{ $receivedMemos->count() }} Messages</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
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
                                        <tr class="{{ $memo->is_read ? '' : 'table-info' }}"
                                            style="{{ $memo->is_read ? '' : 'font-weight: bold; background-color: #f0f8ff;' }}">
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
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @elseif($memo->status == 1)
                                                    <span class="badge bg-success">Yes / Agreed</span>
                                                @elseif($memo->status == 2)
                                                    <span class="badge bg-danger">No / Disagreed</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary view-memo-btn"
                                                    data-id="{{ $memo->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#memoModal">
                                                    Open
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                                No received memos found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-------------------- COMPOSE SECTION -------------------->
                <div class="card mb-4 shadow-sm border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-pen-nib me-2"></i> Compose New Memo</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('memo.send') }}" method="POST" id="composeForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="receiver_id" class="form-label">To (Officer)</label>
                                    <select name="receiver_id" id="receiver_id" class="form-select" required>
                                        <option value="" selected disabled>Select Designation - Officer Name</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="subject" class="form-label">Subject</label>
                                    <input type="text" name="subject" id="subject" class="form-control" required
                                        placeholder="Enter memo subject...">
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="body" class="form-label">Description / Message</label>
                                    <textarea name="body" id="body" class="form-control" rows="4" required
                                        placeholder="Type your message here..."></textarea>
                                </div>
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-success px-4" id="sendMemoBtn">
                                        <i class="fas fa-paper-plane me-2"></i> Send Memo
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-------------------- SENT MESSAGES SECTION -------------------->
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-paper-plane me-2"></i> Sent Memos</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
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
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @elseif($memo->status == 1)
                                                    <span class="badge bg-success">Yes / Agreed</span>
                                                @elseif($memo->status == 2)
                                                    <span class="badge bg-danger">No / Disagreed</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">No sent memos.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal for Viewing Memo -->
    <div class="modal fade" id="memoModal" tabindex="-1" aria-labelledby="memoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="memoModalLabel">Internal Memo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>From:</strong> <span id="modalSender"></span><br>
                        <strong>Date:</strong> <span id="modalDate"></span>
                    </div>
                    <div class="mb-4">
                        <h5 id="modalSubject" class="text-primary"></h5>
                    </div>
                    <div class="p-3 bg-light border rounded">
                        <p id="modalBody" style="white-space: pre-wrap;"></p>
                    </div>

                    <hr>

                    <div id="responseSection" class="text-center mt-3 d-none">
                        <p class="mb-2"><strong>Please provide your response:</strong></p>
                        <button class="btn btn-success me-2 response-btn" data-value="1"><i class="fas fa-check me-1"></i>
                            YES</button>
                        <button class="btn btn-danger response-btn" data-value="2"><i class="fas fa-times me-1"></i>
                            NO</button>
                    </div>
                    <div id="statusDisplay" class="text-center mt-3 d-none">
                        <strong>Current Status: </strong> <span id="statusBadge"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Compose Form Confirmation
            const composeForm = document.getElementById('composeForm');
            composeForm.addEventListener('submit', function (e) {
                if (!confirm('Are you sure you want to send this memo?')) {
                    e.preventDefault();
                }
            });

            // View Memo Logic
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

                    // Reset Modal
                    modalSender.textContent = 'Loading...';
                    modalSubject.textContent = '';
                    modalBody.textContent = '';
                    responseSection.classList.add('d-none');
                    statusDisplay.classList.add('d-none');

                    // Fetch details
                    fetch(`/internal-memo/${memoId}`)
                        .then(response => response.json())
                        .then(data => {
                            modalSender.textContent = data.sender;
                            modalDate.textContent = data.date;
                            modalSubject.textContent = data.subject;
                            modalBody.textContent = data.body;

                            // Show response buttons only if authorized and status is pending (0)
                            if (data.can_respond) {
                                responseSection.classList.remove('d-none');
                                statusDisplay.classList.add('d-none');
                            } else {
                                responseSection.classList.add('d-none');
                                statusDisplay.classList.remove('d-none');

                                // Set status badge text
                                let statusText = 'Pending';
                                let statusClass = 'badge bg-warning text-dark';

                                if (data.status == 1) {
                                    statusText = 'Yes / Agreed';
                                    statusClass = 'badge bg-success';
                                } else if (data.status == 2) {
                                    statusText = 'No / Disagreed';
                                    statusClass = 'badge bg-danger';
                                }

                                statusBadge.className = statusClass;
                                statusBadge.textContent = statusText;
                            }

                            // Mark row as read visually immediately (optional, page reload updates it properly)
                            const row = btn.closest('tr');
                            row.classList.remove('table-info');
                            row.style.fontWeight = 'normal';
                            row.style.backgroundColor = '';
                        });
                });
            });

            // Response Logic
            const responseButtons = document.querySelectorAll('.response-btn');
            responseButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const value = this.getAttribute('data-value'); // 1 = Yes, 2 = No
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
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert(data.message);
                                    location.reload(); // Reload to reflect changes
                                } else {
                                    alert('Error: ' + data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('An unexpected error occurred.');
                            });
                    }
                });
            });
        });
    </script>
    @section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @endsection
@endsection