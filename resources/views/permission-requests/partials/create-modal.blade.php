<div class="modal fade" id="createPermissionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('permission-requests.store') }}" method="POST" id="createPermissionForm">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="modal-title">New Permission Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if(Auth::user()->role === 'manager')
                    <div class="mb-4">
                        <label class="form-label fw-bold">Request Type</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="registration_type" id="self_registration" value="self" checked>
                            <label class="btn btn-outline-primary" for="self_registration">
                                <i class="fas fa-user me-2"></i>For Myself
                            </label>
                            
                            <input type="radio" class="btn-check" name="registration_type" id="other_registration" value="other">
                            <label class="btn btn-outline-primary" for="other_registration">
                                <i class="fas fa-users me-2"></i>For Employee
                            </label>
                        </div>
                    </div>

                    <div class="mb-4" id="employee_select_container">
                        <label for="user_id" class="form-label">Select Employee</label>
                        <select name="user_id" id="user_id" class="form-select">
                            <option value="" disabled selected>Choose an employee...</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label for="departure_time" class="form-label">Departure Time</label>
                        <input type="datetime-local"
                               class="form-control"
                               id="departure_time"
                               name="departure_time"
                               required
                               min="{{ date('Y-m-d\TH:i') }}">
                    </div>

                    <div class="mb-3">
                        <label for="return_time" class="form-label">Return Time</label>
                        <input type="datetime-local"
                               class="form-control"
                               id="return_time"
                               name="return_time"
                               required
                               min="{{ date('Y-m-d\TH:i') }}">
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason</label>
                        <textarea class="form-control"
                                  id="reason"
                                  name="reason"
                                  required
                                  rows="3"
                                  maxlength="255"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>