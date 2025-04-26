<!-- Modal -->
<div class="modal fade" id="verifyAccountModal" tabindex="-1" aria-labelledby="verifyAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verifyModalLabel">Verify Identity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="">
                    <div class="col-12 form-group">
                        <label for="">BVN <small class="text-danger">*</small></label>
                        <input type="text" class="form-control" name="bvn" id="bvnInput">
                    </div>
                    <div class="col-12 form-group">
                        <label for="">Bank <small class="text-danger">*</small></label>
                        <select class="form-control" name="bank" id="bankSelect">
                            <option value="">Choose Bank..</option>
                        </select>
                    </div>
                    <div class="col-12 form-group">
                        <label for="">Account Number<small class="text-danger">*</small></label>
                        <input type="text" class="form-control" name="accountNumber" id="accountNumberInput" placeholder="Account number associated to your bvn">
                    </div>
                </form>
                <p><strong>Verification Status:</strong> <span id="verifyStatus"></span></p>
                <p><strong>Response Message:</strong> <span id="responseMessage"></span></p>
                <button id="verifyButton" class="btn primary-btn">
                    <i class="fa fa-sync" id="verifyIcon"></i> <span id="verifyText">Verify</span>
                </button>
                <button id="cancelButton" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i> Cancel
                </button>
            </div>
        </div>
    </div>
</div>