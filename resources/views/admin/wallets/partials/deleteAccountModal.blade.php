<!-- Modal -->
<div class="modal fade" id="activateAccountModal" tabindex="-1" aria-labelledby="activateAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requeryModalLabel">Requery Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Account Name:</strong> <span id="accountName"></span></p>
                <p><strong>Account Number:</strong> <span id="accountNumber"></span></p>
                <p><strong>Account Bank:</strong> <span id="accountBank"></span></p>
                <p><strong>Provider:</strong> <span id="accountProvider"></span></p>
                <p><strong>Status:</strong> <span id="accountStatus"></span></p>
                <hr>
                <p><strong>Response Message:</strong> <span id="responseMessage"></span></p>
                <p><strong>Response Message:</strong> <span id="responseMessage"></span></p>
                <p><strong>Response Message:</strong> <span id="responseMessage"></span></p>
                <button id="requeryButton" class="btn primary-btn">
                    <i class="fa fa-sync"></i> Activate
                </button>
                <button id="requeryButton" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i> Cancel
                </button>
            </div>
        </div>
    </div>
</div>