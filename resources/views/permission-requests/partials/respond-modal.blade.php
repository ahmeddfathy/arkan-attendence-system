<div class="modal fade" id="respondModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="respondForm" method="POST">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="modal-title">Respond to Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Response Status</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="status" id="status_approved" value="approved" checked>
                            <label class="btn btn-outline-success" for="status_approved">
                                <i class="fas fa-check me-2"></i>Approve
                            </label>

                            <input type="radio" class="btn-check" name="status" id="status_rejected" value="rejected">
                            <label class="btn btn-outline-danger" for="status_rejected">
                                <i class="fas fa-times me-2"></i>Reject
                            </label>
                        </div>
                    </div>

                    <div id="rejection_reason_container" style="display: none;">
                        <div class="mb-3">
                            <label for="rejection_reason" class="form-label required">Rejection Reason</label>
                            <textarea class="form-control"
                                    id="rejection_reason"
                                    name="rejection_reason"
                                    rows="3"
                                    maxlength="255"
                                    placeholder="Please provide a reason for rejection..."></textarea>
                            <div class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                This reason will be visible to the employee
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Response</button>
                </div>
            </form>
        </div>
    </div>
</div>
