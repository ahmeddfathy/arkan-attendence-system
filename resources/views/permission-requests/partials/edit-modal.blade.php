<div class="modal fade" id="editPermissionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editPermissionForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header border-0">
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
                               min="{{ date('Y-m-d\TH:i') }}">
                    </div>
                    <div class="mb-3">
                        <label for="edit_return_time" class="form-label">Return Time</label>
                        <input type="datetime-local"
                               class="form-control"
                               id="edit_return_time"
                               name="return_time"
                               required
                               min="{{ date('Y-m-d\TH:i') }}">
                    </div>
                    <div class="mb-3">
                        <label for="edit_reason" class="form-label">Reason</label>
                        <textarea class="form-control"
                                  id="edit_reason"
                                  name="reason"
                                  required
                                  rows="3"
                                  maxlength="255"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Request</button>
                </div>
            </form>
        </div>
    </div>
</div>