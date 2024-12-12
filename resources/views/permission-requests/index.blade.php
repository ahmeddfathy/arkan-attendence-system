@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-clock"></i> Permission Requests
                    </h5>
                    <div>
                        @if(Auth::user()->role !== 'manager')
                            <span class="badge bg-info me-2">
                                Remaining Time: {{ $remainingMinutes }} minutes
                            </span>
                        @endif
                        <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#createPermissionModal">
                            <i class="fas fa-plus"></i> New Request
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                     <th>Employee Name</th>
                                    <th>Date & Time</th>
                                    <th>Duration (mins)</th>
                                    <th>Remaining Minutes</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Manager Response</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            
                                @forelse($requests as $request)
                                    <tr class="request-row">
                                    <td>{{ $request->user_id }}</td>

                                        <td>{{ $request->departure_time }} - {{ $request->return_time }}</td>

                                        <td>{{ $request-> minutes_used }}</td>
                                        <td>{{ $request->remaining_minutes }}</td>
                                        <td>{{ $request->reason }}</td>
                                        <td>
                                            <span class="badge bg-{{ $request->status === 'approved' ? 'success' : ($request->status === 'rejected' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $request->manager_response }}</td>
                                        <td>
                                            @if($request->status === 'pending')
                                                @if(Auth::user()->id === $request->user_id)
                                                    <button class="btn btn-sm btn-primary edit-btn"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editPermissionModal"
                                                            data-request="{{ json_encode($request) }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <form action="{{ route('permission-requests.destroy', $request) }}"
                                                          method="POST"
                                                          class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Are you sure?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                @if(Auth::user()->role === 'manager')
                                                    <button class="btn btn-sm btn-info respond-btn"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#respondModal"
                                                            data-request-id="{{ $request->id }}">
                                                        <i class="fas fa-reply"></i> Respond
                                                    </button>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No permission requests found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $requests->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createPermissionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('permission-requests.store') }}" method="POST" id="createPermissionForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">New Permission Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="departure_time" class="form-label">Departure Time</label>
                        <input type="datetime-local"
                               class="form-control"
                               id="departure_time"
                               name="departure_time"
                               required
                               min="{{ date('Y-m-d\TH:i', strtotime('+1 hour')) }}">
                    </div>
                    <div class="mb-3">
                        <label for="return_time" class="form-label">Return Time</label>
                        <input type="datetime-local"
                               class="form-control"
                               id="return_time"
                               name="return_time"
                               required
                               min="{{ date('Y-m-d\TH:i', strtotime('+1 hour')) }}">
                    </div>
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason</label>
                        <textarea class="form-control"
                                  id="reason"
                                  name="reason"
                                  required
                                  maxlength="255"></textarea>
                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editPermissionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editPermissionForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Permission Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_departure_time" class="form-label">Departure Time</label>
                        <input type="datetime-local"
                               class="form-control"
                               id="edit_departure_time"
                               name="departure_time"
                               required
                               min="{{ date('Y-m-d\TH:i', strtotime('+1 hour')) }}">
                    </div>
                    <div class="mb-3">
                        <label for="edit_return_time" class="form-label">Return Time</label>
                        <input type="datetime-local"
                               class="form-control"
                               id="edit_return_time"
                               name="return_time"
                               required
                               min="{{ date('Y-m-d\TH:i', strtotime('+1 hour')) }}">
                    </div>
                    <div class="mb-3">
                        <label for="edit_reason" class="form-label">Reason</label>
                        <textarea class="form-control"
                                  id="edit_reason"
                                  name="reason"
                                  required
                                  maxlength="255"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Respond Modal -->
<div class="modal fade" id="respondModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="respondForm" method="POST" >
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Respond to Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="radio"
                                       name="status"
                                       id="status_approved"
                                       value="approved"
                                       required>
                                <label class="form-check-label" for="status_approved">
                                    Approve
                                </label>
                            </div>


                            <div class="form-check">
                                <input class="form-check-input"
                                       type="radio"
                                       name="status"
                                       id="status_rejected"
                                       value="rejected">
                                <label class="form-check-label" for="status_rejected">
                                    Reject
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3" id="rejection_reason_container" style="display: none;">
                        <label for="rejection_reason" class="form-label">Rejection Reason</label>
                        <textarea class="form-control"
                                  id="rejection_reason"
                                  name="rejection_reason"
                                  maxlength="255"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Response</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Handling the "Respond" button logic to toggle rejection reason input
    document.querySelectorAll('.respond-btn').forEach(button => {
        button.addEventListener('click', (event) => {
            const requestId = event.target.getAttribute('data-request-id');
            const form = document.getElementById('respondForm');
            form.action = `/permission-requests/respond/${requestId}`;
        });
    });

    // Toggle rejection reason visibility
    document.querySelectorAll('input[name="status"]').forEach(radio => {
        radio.addEventListener('change', (event) => {
            const rejectionReasonContainer = document.getElementById('rejection_reason_container');
            if (event.target.value === 'rejected') {
                rejectionReasonContainer.style.display = 'block';
            } else {
                rejectionReasonContainer.style.display = 'none';
            }
        });
    });
</script>
@endsection
