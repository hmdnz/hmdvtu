<!-- Modal -->
<div class="modal fade" id="activateAccountModal" tabindex="-1" aria-labelledby="activateAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requeryModalLabel">Requery Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Account Name:</strong> <span id="activateAccountName"></span></p>
                <p><strong>Account Number:</strong> <span id="activateAccountNumber"></span></p>
                <p><strong>Account Bank:</strong> <span id="activateAccountBank"></span></p>
                <p><strong>Provider:</strong> <span id="activateAccountProvider"></span></p>
                <p><strong>Status:</strong> <span id="activateAccountStatus"></span></p>
                <hr>
                <p><strong>Response Message:</strong> <span id="responseMessage"></span></p>
                <button id="activateButton" class="btn primary-btn">
                    <i class="fa fa-sync" id="activateIcon"></i> <span id="activateText">Activate</span>
                </button>
                <button id="cancelButton" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i> Cancel
                </button>
            </div>
        </div>
    </div>
</div>