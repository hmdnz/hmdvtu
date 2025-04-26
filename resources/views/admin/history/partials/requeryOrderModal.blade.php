<!-- Modal -->
<div class="modal fade" id="requeryOrderModal" tabindex="-1" aria-labelledby="requeryOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requeryModalLabel">Requery Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Reference:</strong> <span id="orderReference"></span></p>
                
                <p><strong>Order Status:</strong> <span id="orderStatus"></span></p>
                <p><strong>Response Message:</strong> <span id="responseMessage"></span></p>
                <button id="requeryButton" class="btn primary-btn">
                    <i class="fa fa-sync" id="requeryIcon"></i> <span id="requeryText">Requery</span>
                </button>
                <button id="cancelButton" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i> Cancel
                </button>
            </div>
        </div>
    </div>
</div>