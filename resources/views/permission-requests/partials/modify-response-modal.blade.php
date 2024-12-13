<div class="modal fade" id="modifyResponseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="modifyResponseForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header border-0">
                    <h5 class="modal-title">Modify Response</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label">Update Status</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="status" id="modify_status_approved" value="approved">
                            <label class="btn btn-outline-success" for="modify_status_approved">
                                <i class="fas fa-check me-2"></i>Approved
                            </label>

                            <input type="radio" class="btn-check" name="status" id="modify_status_rejected" value="rejected">
                            <label class="btn btn-outline-danger" for="modify_status_rejected">
                                <i class="fas fa-times me-2"></i>Rejected
                            </label>
                        </div>
                    </div>

                    <div class="mb-3" id="modify_reason_container" style="display: none;">
                        <label for="modify_reason" class="form-label">Rejection Reason</label>
                        <textarea class="form-control"
                                  id="modify_reason"
                                  name="rejection_reason"
                                  rows="3"
                                  maxlength="255"
                                  placeholder="Please provide a reason for rejection..."></textarea>
                        <div class="form-text text-muted">
                            This will update the rejection reason shown to the employee
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
