<div class="col-md-9 mx-auto">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="header-title">
                <h4 class="card-title">Airtime Top Up</h4>
            </div>
        </div>
        <div class="card-body">
            <div class="container">
                @if(session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form wire:submit.prevent="submit">
                    @csrf
                    <input type="hidden" wire:model="user_id" value="{{ auth()->user()->id }}">

                    <div class="alert alert-danger" id="error_message" style="display:none"></div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="form-label">Choose a Network</label>
                            <select class="form-control" wire:model="biller" required>
                                <option selected value="">Choose..</option>
                                @foreach($billers as $biller)
                                    <option value="{{ $biller->id }}">{{ $biller->title }}</option>
                                @endforeach
                            </select>
                            @error('biller') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group col-md-12">
                            <label class="form-label">Amount</label>
                            <input type="number" wire:model="amount" class="form-control" required>
                            @error('amount') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group col-md-12">
                            <label class="form-label">Choose a Category</label>
                            <select class="form-control" wire:model="category" required>
                                <option selected value="">Choose..</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group col-md-12">
                            <label class="form-label">Choose a Package</label>
                            <select class="form-control" wire:model="package" required>
                                <option selected value="">Choose..</option>
                                @foreach($packages as $pack)
                                    <option value="{{ $pack->id }}">{{ $pack->name }}</option>
                                @endforeach
                            </select>
                            @error('package') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group col-md-12">
                            <label class="form-label">Phone Number</label>
                            <input type="text" wire:model="number" class="form-control" required>
                            @error('number') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group col-md-12">
                            <label class="form-label">Pin</label>
                            <input type="password" wire:model="pin" class="form-control" maxlength="4" required>
                            @error('pin') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn secondary-btn col-12">
                        Recharge Now
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
