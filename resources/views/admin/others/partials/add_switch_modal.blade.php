<!-- add switch modal -->
<div class="modal" id="addSwitchModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- Modal body -->
        <div class="modal-body">
          <h2>Add Provider Switch</h2>
          <form action="{{ route('admin.switches.store') }}" method="POST">
            @csrf
        
            <div class="form-group">
                <label for="context_type">Context Type:</label>
                <select name="context_type" class="form-control" id="context_type" required onchange="toggleContextFields(this.value)">
                    <option value="">-- Select --</option>
                    <option value="category">Category</option>
                    <option value="biller">Biller</option>
                    <option value="service">Service</option>
                </select>
            </div>
        
            <div id="category_input" class="form-group" style="display: none;">
                <label>Category Title:</label>
                <select name="category_title" class="form-control">
                    <option value="">Choose Category</option>
                    <option value="SME">SME</option>
                    <option value="COPORATE">COPORATE</option>
                    <option value="SPECIAL">SPECIAL</option>
                    <option value="CG">CG</option>
                    <option value="GIFTING">GIFTING</option>
                    <option value="CG_LITE">CG_LIGHT</option>
                    <option value="DIRECT">DIRECT</option>
                    <option value="VTU">VTU</option>
                    <option value="SHARE">SHARE</option>
                    <option value="STARTIMES">STARTIMES</option>
                    <option value="GOTV">GOTV</option>
                    <option value="DSTV">DSTV</option>
                    <option value="NECO">NECO</option>
                    <option value="WAEC">WAEC</option>
                    <option value="NBAIS">NBAIS</option>
                    <option value="NABTEB">NABTEB</option>
                </select>

                <br>
                <label>Biller for Category:</label>
                <select name="context_id_biller_category" class="form-control">
                    @foreach($billers as $biller)
                        <option value="{{ $biller->id }}">{{ $biller->title }}</option>
                    @endforeach
                </select>
            </div>
        
            <div id="biller_input" class="form-group" style="display: none;">
                <label>Select Biller:</label>
                <select class="form-control" name="context_id_biller">
                    @foreach($billers as $biller)
                        <option value="{{ $biller->id }}">{{ $biller->title }}</option>
                    @endforeach
                </select>
            </div>
        
            <div id="service_input" class="form-group" >
                <label>Select Service:</label>
                <select class="form-control" name="context_id_service" required>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}">{{ $service->title }}</option>
                    @endforeach
                </select>
            </div>
        
            <div class="form-group">
                <label>Select Service Provider:</label>
                <select class="form-control" name="service_provider_id" required>
                    <option value="">Select Provider</option>
                    @foreach($providers as $provider)
                        <option value="{{ $provider->id }}">{{ $provider->title }}</option>
                    @endforeach
                </select>
            </div>
        
            <button type="submit" class="btn btn-warning">Save</button>
        </form>
        
        </div>
        

        <!-- Modal footer -->
        <div class="modal-footer">
        </div>
      </div>
    </div>
</div>

<script>
    function toggleContextFields(type) {
        document.getElementById('category_input').style.display = (type === 'category') ? 'block' : 'none';
        document.getElementById('biller_input').style.display = (type === 'biller') ? 'block' : 'none';
        // document.getElementById('service_input').style.display = (type === 'service') ? 'block' : 'none';
    }
</script>