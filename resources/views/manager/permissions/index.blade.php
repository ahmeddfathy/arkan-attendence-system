    @extends('layouts.app')

    @section('content')
    <div class="container">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Manager Permissions Overview</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Departure Time</th>
                                <th>Return Time</th>
                                <th>Returned On Time</th>
                                <th>Minutes Used</th>
                                <th>Remaining Minutes</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($permissions as $permission)
                                <tr class="{{ !$permission->returned_on_time ? 'table-danger' : '' }}">
                                    <td>{{ $permission->user->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($permission->departure_time)->format('H:i') }}</td>
    <td>{{ \Carbon\Carbon::parse($permission->return_time)->format('H:i') }}</td>

    
                                    <td>
                                        <span class="badge {{ $permission->returned_on_time ? 'bg-success' : 'bg-danger' }}">
                                            {{ $permission->returned_on_time ? 'YES' : 'NO' }}
                                        </span>
                                    </td>
                                    <td>{{ $permission->minutes_used }}</td>
                                    <td>{{ $permission->remaining_minutes }}</td>
                                    <td>
                                        <span class="badge bg-{{ $permission->status === 'approved' ? 'success' : ($permission->status === 'rejected' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($permission->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button"
                                                class="btn btn-sm btn-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#responseModal{{ $permission->id }}">
                                            Modify Response
                                        </button>
                                        @if($permission->status !== 'pending')
                                            <form action="{{ route('manager.permissions.delete-response', $permission) }}"
                                                method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this response?')">
                                                    Delete Response
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Response Modal -->
                                <div class="modal fade" id="responseModal{{ $permission->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('manager.permissions.update-response', $permission) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Update Response</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Status</label>
                                                        <select name="status" class="form-select" required>
                                                            <option value="approved" {{ $permission->status === 'approved' ? 'selected' : '' }}>
                                                                Approve
                                                            </option>
                                                            <option value="rejected" {{ $permission->status === 'rejected' ? 'selected' : '' }}>
                                                                Reject
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Response Message</label>
                                                        <textarea name="manager_response"
                                                                class="form-control"
                                                                required>{{ $permission->manager_response }}</textarea>
                                                    </div>
                                                    @if(!$permission->returned_on_time)
                                                        <div class="alert alert-danger">
                                                            <i class="fas fa-exclamation-triangle"></i>
                                                            Warning: User did not return on time. Approving this request will be marked as a manager mistake.
                                                        </div>
                                                    @endif
                                                    @if($permission->remaining_minutes <= 0)
                                                        <div class="alert alert-warning">
                                                            <i class="fas fa-exclamation-circle"></i>
                                                            Warning: User has no remaining minutes for this month.
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update Response</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No permission requests found for today.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add any additional JavaScript functionality here
        });
    </script>
    @endsection
