@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="d-flex">
            <div class="me-2">
                <i class="fas fa-exclamation-circle fa-lg"></i>
            </div>
            <div>
                <ul class="mb-0 ps-0" style="list-style: none;">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-primary text-white border-0 d-flex justify-content-between align-items-center py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clock fa-lg me-2"></i>
                        <h5 class="mb-0">Permission Requests</h5>
                    </div>
                    <div class="d-flex align-items-center">


                        @if(Auth::user()->role !== 'manager')
                        <div class="me-3">
                            <div class="d-flex align-items-center  bg-opacity-25 rounded-pill px-3 py-1" style="background-color: red;">
                                <i class="fas fa-hourglass-half me-2"></i>

                                <span>Remaining: {{ $remainingMinutes }} minutes</span>
                            </div>
                        </div>
                        @endif
                        <button type="button" class="btn btn-light btn-sm d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#createPermissionModal">
                            <i class="fas fa-plus me-2"></i>
                            <span>New Request</span>
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0">Employee</th>
                                    <th class="border-0">Date & Time</th>
                                    <th class="border-0">Duration</th>
                                    <th class="border-0">Remaining</th>
                                    <th class="border-0">Reason</th>
                                    <th class="border-0">Rejected Reason</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requests as $request)
                                <tr class="request-row">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                <i class="fas fa-user text-primary"></i>
                                            </div>
                                            <div>
                                                <span class="d-block">{{ $request->user->name ?? 'Unknown' }}</span>
                                                <small class="text-muted">
                                                    Remaining: {{ $request->remaining_minutes }} mins
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <small class="text-muted">Departure:</small>
                                            <span>{{ \Carbon\Carbon::parse($request->departure_time)->format('M d, Y H:i') }}</span>
                                            <small class="text-muted">Return:</small>
                                            <span>{{ \Carbon\Carbon::parse($request->return_time)->format('M d, Y H:i') }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $request->minutes_used }} mins</td>
                                    <td>{{ $request->remaining_minutes }} mins</td>
                                    <td>
                                        <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $request->reason }}">
                                            {{ $request->reason }}
                                        </span>

                                    </td>
                                    <td>
                                            @if($request->status === 'rejected' && $request->rejection_reason)
                                        <div class="mt-1">
                                            <small class="text-danger">
                                                <i class="fas fa-info-circle me-1"></i>
                                                {{ $request->rejection_reason }}
                                            </small>
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = [
                                                'pending' => 'bg-warning',
                                                'approved' => 'bg-success',
                                                'rejected' => 'bg-danger'
                                            ][$request->status] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $statusClass }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            @if($request->status === 'pending')
                                                @if(Auth::user()->id === $request->user_id)
                                                    <button class="btn btn-sm btn-outline-primary edit-btn"
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
                                                                class="btn btn-sm btn-outline-danger"
                                                                onclick="return confirm('Are you sure you want to delete this request?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                @if(Auth::user()->role === 'manager')
                                                    <button class="btn btn-sm btn-outline-info respond-btn"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#respondModal"
                                                            data-request-id="{{ $request->id }}">
                                                        <i class="fas fa-reply me-1"></i> Respond
                                                    </button>
                                                @endif
                                            @endif
                                            @if(Auth::user()->role === 'manager' && $request->status !== 'pending')
                                                <div class="btn-group">
                                                    <button class="btn btn-sm btn-outline-warning modify-response-btn"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modifyResponseModal"
                                                            data-request-id="{{ $request->id }}"
                                                            data-status="{{ $request->status }}"
                                                            data-reason="{{ $request->rejection_reason }}">
                                                        <i class="fas fa-edit me-1"></i> Modify
                                                    </button>
                                                    <form action="{{ route('permission-requests.reset-status', $request) }}"
                                                          method="POST"
                                                          class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                                class="btn btn-sm btn-outline-secondary"
                                                                onclick="return confirm('Reset this request to pending status?')">
                                                            <i class="fas fa-undo"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="fas fa-inbox fa-2x mb-3"></i>
                                        <p class="mb-0">No permission requests found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        {{ $requests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('permission-requests.partials.create-modal')
@include('permission-requests.partials.edit-modal')
@include('permission-requests.partials.respond-modal')
@include('permission-requests.partials.modify-response-modal')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Handle status radio changes for both respond and modify modals
    ['status', 'modify_status'].forEach(prefix => {
        document.querySelectorAll(`input[name="${prefix}"]`).forEach(radio => {
            radio.addEventListener('change', function() {
                const containerId = `${prefix === 'status' ? 'rejection' : 'modify'}_reason_container`;
                const container = document.getElementById(containerId);
                const textarea = container.querySelector('textarea');

                if (this.value === 'rejected') {
                    container.style.display = 'block';
                    textarea.setAttribute('required', 'required');
                } else {
                    container.style.display = 'none';
                    textarea.removeAttribute('required');
                    textarea.value = '';
                }
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
    // Handle Edit Permission Modal
    document.querySelectorAll('.edit-permission-btn').forEach(button => {
        button.addEventListener('click', function() {
            const requestId = this.dataset.requestId;
            const departureTime = this.dataset.departureTime;
            const returnTime = this.dataset.returnTime;
            const reason = this.dataset.reason;

            const form = document.getElementById('editPermissionForm');
            form.action = `/permission-requests/${requestId}`;

            // تعبئة الحقول بالقيم الحالية
            document.getElementById('edit_departure_time').value = departureTime;
            document.getElementById('edit_return_time').value = returnTime;
            document.getElementById('edit_reason').value = reason;
        });
    });
});


    // Handle respond button clicks
    document.querySelectorAll('.respond-btn').forEach(button => {
        button.addEventListener('click', function() {
            const requestId = this.dataset.requestId;
            const form = document.getElementById('respondForm');
            form.action = `/permission-requests/${requestId}/update-status`;

            // Reset form
            form.reset();
            document.getElementById('rejection_reason_container').style.display = 'none';
            document.getElementById('rejection_reason').removeAttribute('required');
        });
    });

    // Handle modify response button clicks
    document.querySelectorAll('.modify-response-btn').forEach(button => {
        button.addEventListener('click', function() {
            const requestId = this.dataset.requestId;
            const status = this.dataset.status;
            const reason = this.dataset.reason;

            const form = document.getElementById('modifyResponseForm');
            form.action = `/permission-requests/${requestId}/modify`;

            // Set the correct radio button
            document.getElementById(`modify_status_${status}`).checked = true;

            // Show/hide rejection reason based on status
            const container = document.getElementById('modify_reason_container');
            const textarea = document.getElementById('modify_reason');

            if (status === 'rejected') {
                container.style.display = 'block';
                textarea.setAttribute('required', 'required');
                textarea.value = reason || '';
            } else {
                container.style.display = 'none';
                textarea.removeAttribute('required');
                textarea.value = '';
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {

function handleModifyStatusChange() {
    const radios = document.querySelectorAll('input[name="modify_status"]');
    const container = document.getElementById('modify_reason_container');
    const textarea = container.querySelector('textarea');

    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'rejected') {
                container.style.display = 'block';
                textarea.setAttribute('required', 'required');
            } else {
                container.style.display = 'none';
                textarea.removeAttribute('required');
            }
        });


        if (radio.checked) {
            radio.dispatchEvent(new Event('change'));
        }
    });
}


handleModifyStatusChange();
});


// Initialize form validation
const forms = document.querySelectorAll('.modal form');
forms.forEach(form => {
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });
});

// Handle employee selection for manager requests
if (document.getElementById('self_registration')) {
    document.querySelectorAll('input[name="registration_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const userSelect = document.getElementById('user_id');
            const container = document.getElementById('employee_select_container');

            if (this.value === 'self') {
                container.style.display = 'none';
                userSelect.value = userSelect.querySelector(`option[value="${currentUserId}"]`).value;
            } else {
                container.style.display = 'block';
                userSelect.value = '';
            }
        });
    });
}

</script>
@endpush
@endsection
