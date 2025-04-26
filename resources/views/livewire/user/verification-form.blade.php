<div class="container">
    @if($status == 'unverified')
        <h5 class="card-title text-center">Stage 1: Verify Account</h5>
        <form id="registrationForm" wire:submit.prevent="sendOTP">
            @csrf
            <div class="row" id="inputFields">
                <div class="col-md-12">
                    <p>Make sure you are ready to recieve an OTP to verify your account</p>
                </div>
                <div class="col-lg-12" >
                    <div class="form-group">
                        <label for="phone" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" wire:model="email" value="{{ $email }}" id="emailContact" readonly>
                        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-12 d-flex justify-content-center ">
                    <button class="btn primary-btn col-12 mt-3" type="submit" wire:loading.attr="disabled" wire:target="sendOTP">
                        <span wire:loading.remove wire:target="sendOTP">Send OTP</span>
                        <span wire:loading wire:target="sendOTP">Sending...</span>
                    </button>
                </div>
            </div>
        </form>
    @elseif($status == 'otp_sent')
        <h5 class="card-title text-center">Stage 1: Verify Account</h5>
        <p class="text-center text-danger">{{ $message ?? ''}}</p>
        <form wire:submit.prevent="verifyOTP">
            @csrf
            <div class="row" id="verificationCodeFields">
                <div class="col-md-12">
                    <P>Check your inbox or spam on email, OTP token has been sent to you email/phone number</P>
                </div>
                <div class="col-lg-12 mt-3">
                    <div class="form-group">
                        <label for="verification_code" class="form-label">Verification Code:</label>
                        <input type="text" class="form-control" id="verification_code" wire:model="verificationCode" name="verificationCode"> 
                        @error('verificationCode') <span class="text-danger">{{ $message }}</span> @enderror                                           
                    </div>
                </div>
                <div class="col-lg-12 text-center my-4">
                    <div id="timer" class="hidden"></div>
                </div>
                <div class="col-md-12 d-flex justify-content-center ">
                    <button class="btn primary-btn mt-3 mx-2" type="submit" wire:loading.attr="disabled" wire:target="verifyOTP">
                        <span wire:loading.remove wire:target="verifyOTP">Verify Account</span>
                        <span wire:loading wire:target="verifyOTP">Verifying...</span>
                    </button>
                    <button class="btn secondary-btn mt-3 mx-2" type="button" wire:click="resendOTP" wire:loading.attr="disabled" wire:target="resendOTP">
                        <span wire:loading.remove wire:target="resendOTP">Resend</span>
                        <span wire:loading wire:target="resendOTP">Resending...</span>
                    </button>
                </div>
            </div>
        </form>
    @elseif($status == 'otp_verified')
        <h5 class="card-title text-center">Stage 2: Verify Identity</h5>
        <form id="registrationForm" wire:submit.prevent="verifyIdentity">
            @csrf
            <div class="row">
                <div class="col-lg-12" >
                    <div class="form-group">
                        <label for="phone" class="form-label">Choose Identity Type</label>
                        <select name="identityType" wire:model="identityType" class="form-control" required>
                            <option value="">Choose</option>
                            <option>BVN</option>
                            <option>NIN</option>
                        </select>
                        @error('identityType') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-lg-12" >
                    <div class="form-group">
                        <label for="identityNumber" class="form-label">Identity Number</label>
                        <input type="text" class="form-control" name="identityNumber" wire:model="identityNumber" value="{{ old('nin') }}" required>
                        @error('identityNumber')
                        <span class="text-danger small">{{ $message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12 d-flex justify-content-center ">
                    <button class="btn primary-btn col-12 mt-3" type="submit" wire:loading.attr="disabled" wire:target="verifyIdentity">
                        <span wire:loading.remove wire:target="verifyIdentity">Verify Account</span>
                        <span wire:loading wire:target="verifyIdentity">Verifying...</span>
                    </button>
                </div>
            </div>
        </form>
    @elseif($status == 'identity_verified')
        <h5 class="card-title text-center">Stage 3: Set Pin</h5>
        <form wire:submit.prevent="setPin">
            @csrf
            <div class="form-group">
                <label for="newPin" class="form-label">New Pin</label>
                <input type="password" class="form-control" name="pin" wire:model="pin" maxlength="4" id="newPin" required>
                @error('pin')<span class="text-danger small">{{ $message}}</span>@enderror
            </div>
            <div class="form-group">
                <label for="confirmNewPin" class="form-label">Confirm New Pin</label>
                <input type="password" class="form-control" name="pin_confirmation" wire:model="pin_confirmation" maxlength="4" id="confirmNewPin" required>
                @error('pin_confirmation')<span class="text-danger small">{{ $message}}</span>@enderror
            </div>
            <div class="d-flex justify-content-center ">
                <button class="btn primary-btn col-12 mt-3" type="submit" wire:loading.attr="disabled" wire:target="setPin">
                    <span wire:loading.remove wire:target="setPin">Set pin</span>
                    <span wire:loading wire:target="setPin">Submitting...</span>
                </button>
            </div>
        </form>
    @else
        <div id="successFull" class="topup-success-message text-center">
            <center>
                <div class="checkmark">
                    <span class="fa-stack fa-3x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-check fa-stack-1x fa-inverse"></i>
                    </span>
                </div>
                <div class="topup-message">
                    <h3 class="message-title">Congratulations!</h3>
                    <p class="message-content">You have verified your account and completed your KYC.</p>
                    <a href="{{ route('user.dashboard')}}" id="identityButton" class="btn primary-btn">Dashboard</a>
                </div>
            </center>
        </div>
    @endif
</div>