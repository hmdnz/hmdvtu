<!-- Modal -->
<div class="modal fade" id="requeryPaymentModal" tabindex="-1" aria-labelledby="requeryPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requeryModalLabel">Requery Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Reference:</strong> <span id="paymentReference"></span></p>
                
                <p><strong>Payment Status:</strong> <span id="paymentStatus"></span></p>
                <p><strong>Payment Message:</strong> <span id="paymentMessage"></span></p>
                <button id="requeryButton" class="btn primary-btn">
                    <i class="fa fa-sync"></i> Requery
                </button>
                <button id="requeryButton" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i> Cancel
                </button>
            </div>
        </div>
    </div>
</div>