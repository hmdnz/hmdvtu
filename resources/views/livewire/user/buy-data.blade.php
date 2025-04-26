<div class="container">
    <form id="data_topup_form" action="{{route('user.data')}}" method="post">
        @csrf
        <input type="hidden" name="user_id" class="form-control" value="{{ auth()->user()->id }}" required>
        <div class="row">
            <div class="form-group col-md-12">
                <label class="form-label" for="fname">Choose a Network</label>
                <select class="form-control" name="biller" id="network" required>
                    <option selected value="">Choose..</option>
                    @foreach($billers as $biller)
                    <option value="{{$biller->id}}">{{$biller->title}}</option>
                    @endforeach
                </select>
                <input type="hidden" name="billerName" class="form-control" id="billerName" required>
                @error('biller')
                <span class="text-danger small">{{ $message}}</span>
                @enderror
            </div>
            <div class="form-group col-md-12">
                <label class="form-label" for="fname">Choose a Category</label>
                <select class="form-control" name="category" id="category">
                    <option selected value="">Choose..</option>
                </select>
                @error('category')
                <span class="text-danger small">{{ $message}}</span>
                @enderror
            </div>
            <div class="form-group col-md-12">
                <label class="form-label" for="fname">Choose a Package</label>
                <select class="form-control" name="package" id="newPackage">
                    <option selected value="">Choose..</option>
                </select>
                <input type="hidden" name="packageName" class="form-control" id="packageName" required>
                <input type="hidden" name="dataPlan" class="form-control" id="dataPlan" required>
                @error('package')
                <span class="text-danger small">{{ $message}}</span>
                @enderror
            </div>

            <div class="form-group col-md-12">
                <label class="form-label" for="fname">Phone Number</label><br>
                <input type="text" class="form-control" name="number" id="numbers" required />
                <a role="button" onclick="contactPicker()" class="small text-primary float-end">Select Phone Number</a>
                @error('number')
                <span class="text-danger small">{{ $message}}</span>
                @enderror
            </div>                                        
            <input type="hidden" name="total" class="form-control" id="total" required>
            
            <div class="form-group col-md-12">
                <label class="form-label" for="pin">Pin</label><br>
                <input type="password" name="pin" class="form-control" maxlength="4" id="pin" required />
                @error('pin')
                <span class="text-danger small">{{ $message}}</span>
                @enderror
            </div>

        </div>
        <button  type="submit" id="buy-data-button" class="btn secondary-btn col-12"><span id='buyDataButtonpaySpan'></span> Recharge Now (Cost: &#8358;<span id="total-span"></span>)</button>
    </form>
</div>