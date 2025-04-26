<!-- Modal -->
<div class="modal fade" id="deactivateAccountModal" tabindex="-1" aria-labelledby="deactivateAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deactivateAccountModalLabel">Deactivate Virtual Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Account Name:</strong> <span id="deactivateAccountName"></span></p>
                <p><strong>Account Number:</strong> <span id="deactivateAccountNumber"></span></p>
                <p><strong>Account Bank:</strong> <span id="deactivateAccountBank"></span></p>
                <p><strong>Provider:</strong> <span id="deactivateAccountProvider"></span></p>
                <p><strong>Status:</strong> <span id="deactivateAccountStatus"></span></p>
                <hr>
                <p><strong>Response Message:</strong> <span id="responseMessage"></span></p>
                <button id="deactivateButton" class="btn primary-btn">
                    <i class="fa fa-sync" id="deactivateIcon"></i> <span id="deactivateText">Deactivate</span>
                </button>
                <button id="cancelButton" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i> Cancel
                </button>
            </div>
        </div>
    </div>
</div>