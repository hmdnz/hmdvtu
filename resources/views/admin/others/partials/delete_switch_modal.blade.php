<div class="modal" id="deleteSwitchModal{{ $switch->id }}">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- Modal body -->
        <div class="modal-body">
            <h2>Delete Switch</h2>
            <form action="{{route('admin.switches.delete', $switch->id)}}" method="post">
                @csrf
                @method('DELETE')
                <p>Are you sure you want to delete the switch setting.</p>
                <div class="mt-2">
                    <button type="button" class="btn secondary-btn" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning">Yes, Delete</button>
                </div>
            </form>
        </div>
      </div>
    </div>
</div>