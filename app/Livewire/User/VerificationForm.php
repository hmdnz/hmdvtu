<?php

namespace App\Livewire\User;

use App\Enum\TransactionStatus;
use App\Models\Referrals;
use App\Models\User;
use App\Models\Wallet;
use App\Notifications\SendOtpNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use PhpParser\Node\Stmt\Return_;

class VerificationForm extends Component
{
    
    public $userId, $user;
    public $message, $status = 'unverified'; // Default status
    public $email, $verificationCode, $identityType, $identityNumber;
    public $pin, $pin_confirmation;
    public $loadingStep1 = false, $loadingResend= false, $loadingStep2 = false, $loadingStep3 = false, $loadingStep4 = false; 

    public function mount()
    {
        $this->email = auth('web')->user()->email;
        $this->userId = auth('web')->user()->id;
        $user = User::find($this->userId);
        $this->user = $user;

        if($user->isVerified == 1){ $this->status = 'otp_sent';}
        elseif($user->isVerified == 2){ $this->status = 'otp_verified';}
        elseif($user->isVerified == 3){ $this->status = 'identity_verified';}
        elseif($user->isVerified == 4){ $this->status = 'completed';}
        else{ $this->status = 'unverified';}
    }

    public function generateOTP()
    {
        $code = generateOTP();
        // Save the verification code to the user model
        $user = User::find($this->userId);
        $this->user = $user;
        $user->token = $code;
        $user->generated_at = Carbon::now();
        $user->isVerified = 1; 
        if($user->save())
        {
            $user->notify(new SendOtpNotification($code));
            
        }
        return true;
    }

    public function sendOTP()
    {
        $this->loadingStep1 = true;
        // Validation for the first form
        $this->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $this->generateOTP();

        $this->loadingStep1 = false;
        // Logic to send OTP
        session()->flash('message', 'OTP has been sent to your email.');
        $this->status = 'otp_sent';
    }

    public function resendOTP()
    {
        $this->loadingResend = true;

        $this->generateOTP();

        $this->loadingResend = false;
        $this->message = 'OTP has been resent.';
        session()->flash('message', 'OTP has been resent.');
        $this->status = 'otp_sent';
    }

    public function verifyOTP()
    {
        $this->message = '';
        $this->loadingStep1 = true;
        // Validation for the OTP form
        $this->validate([
            'verificationCode' => 'required|digits:6', // Ensure it's a 6-digit OTP
        ]);

        // Check if the verification code matches
        if ($this->user->token !== $this->verificationCode) {
            $this->status = 'otp_sent'; $this->message = 'Invalid verification code';
            return redirect()->back()->with('message', 'Invalid verification code');
        }
        // Calculate the expiration time (25 minutes after generated_at)
        $expirationTime = Carbon::parse($this->user->generated_at)->addMinutes(2);
        // Check if the verification code is expired
        if (Carbon::now()->gt($expirationTime)) {
            $this->status = 'otp_sent'; $this->message = 'Verification code has expired';
            return redirect()->back()->with('message', 'Verification code has expired');
        }else{
            $user= User::find($this->user->id);
            // Update the user's verified_at timestamp
            $user->update(['verified_at' => now()]);
            $user->update(['isVerified' => 2]);
            
        }
        $this->message = '';
        $this->loadingStep2 = false;
        // Logic to verify OTP
        session()->flash('message', 'Your account has been verified successfully.');
        $this->status = 'otp_verified';
    }

    public function verifyIdentity()
    {
        $this->loadingStep3 = true;
        // Validation for the OTP form
        $this->validate([
            'identityType' => 'required', 
            'identityNumber' => 'required|digits:11', 
        ]);

        $user= User::find($this->user->id);
        // verify id
        if($this->identityType == 'NIN')
        {
         $user->update(['nin' => $this->identityNumber]);
         $user->update(['isVerified' => 3]);
        } 
        else
        {
         $user->update(['bvn' => $this->identityNumber]);
         $user->update(['isVerified' => 3]);
        } 
        $this->loadingStep3 = false;
        // Logic to verify OTP
        session()->flash('message', 'Your account has been verified successfully.');
        $this->status = 'identity_verified';
    }

    public function setPin()
    {
        $this->loadingStep4 = true;
        // Validation for the OTP form
        $this->validate([
            'pin' => 'required|confirmed|min:4', 
        ]);

        $user= User::find($this->user->id);
        $user->update([
            'pin' => Hash::make($this->pin),
            'isVerified' => 4 // set pin
        ]);
        $this->loadingStep4 = false;
        
        // settled the referral
        if($user->referralID)
        {
            $referralID = $user->referralID;
            
            $referral = Referrals::find($referralID);
            $referrer = User::find($referral->referrer);
            $wallet = Wallet::find($referrer->wallet->id);
            $previousBalance = $wallet->referralBalance;
            $newBalance = $previousBalance + $referral->commission;
            $wallet->update(['referralBalance' => $newBalance]);
            $referral->update(['status' => TransactionStatus::SETTLED]);
            
        }
        

        session()->flash('message', 'Your account has been verified successfully.');
        $this->status = 'completed';
    }


    public function render()
    {
        return view('livewire.user.verification-form');
    }
}
