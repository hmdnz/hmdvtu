<?php

namespace App\Http\Controllers\user;

use App\Enum\TransactionStatus;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Payment;
use App\Models\Referrals;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ReferralController extends Controller
{
    //
    public function index()
    {
        $referrals = Referrals::with(['user'])->whereNotNull('status')
                    ->where('referrer', auth('web')->user()->id)
                    ->orderBy('id', 'desc')->get();

        $pendingReferrals = Referrals::where('status', 'Pending')
        ->where('referrer', auth('web')->user()->id)
        ->orderBy('id', 'desc')->get();

        // Count active referrals
        $countPendingReferrals = $pendingReferrals->count();

        // Get the sum of commissions from pending referrals
        $sumOfCommissions = $pendingReferrals->sum('commission');
        return view('user.referrals', compact('referrals', 'countPendingReferrals', 'sumOfCommissions'));
        // return view('user.referrals');
    }

    public function generateRandomNumbers()
    {
        $min = 100000; // Minimum 6-digit number
        $max = 999999; // Maximum 6-digit number
        // Generate a random number within the specified range
        $randomNumber = rand($min, $max);
        return $randomNumber;
    }

    public function transfer(Request $request)
    {
        $credentials = $request->validate([
            'userId' => ['required'],
            'walletId' => ['required'],
            'email' => 'required|email',
            'amount' => 'required',
            'password' => 'required|min:8',
        ]);
        
        $user = User::where([
            'id' => $request->userId,
            'email' => $request->email,
        ])->first();
        
        $wallet = Wallet::where('userID', auth('web')->user()->id)->first();

        if($wallet->referralBalance != $request->amount || $request->amount < 20){
            return redirect()->back()->with('message', 'Your referral bonus is not up to N500');
        }

        // if ($referrals->isEmpty() || $sumOfCommissions >= 500) {
        //     return redirect()->back()->with('message', 'You dont have active referrals or the bonus is not up to N500');
        // }

        // If user is verified, compare hashed passwords
        if ($user && Hash::check($request->input('password'), $user->password)) 
        {
            
            $wallet = Wallet::find($request->walletId); // Assuming you have the $walletId variable
            if ($wallet) 
            {
                $reference = generatePaymentReferenceCode();
                $amount = floatval($request->amount);
                $fees = 0;
                // Update the wallet's MAIN BALANCE in the users table
                $balanceBefore = $wallet->mainBalance;
                $balanceAfter = $wallet->mainBalance + floatval($amount);
                $wallet->mainBalance = $balanceAfter;
                // Update the wallet's REFERRAL BALANCE in the users table
                $referralBalanceAfter = $wallet->referralBalance - floatval($amount);
                $wallet->referralBalance = $referralBalanceAfter;
                if($wallet->save())
                {
                    $payment = Payment::create([ 
                        'userID' => $request->userId,
                        'walletID' => $request->walletId,
                        'reference' => $reference,
                        'gateway' => 'System',
                        'channel' => 'Referral',
                        'balanceBefore' => $balanceBefore,
                        'amount' => $amount,
                        'balanceAfter' => $balanceAfter,
                        'fees' => $fees,
                        'total' => $amount,
                        'status' => TransactionStatus::SUCCESSFUL,
                    ]);
                    if($payment)
                    {
                        $transaction = Transaction::create([
                            'userID' => $request->userId,
                            'walletID' => $request->walletId,
                            'type' => 'Credit',
                            'balanceBefore' => $balanceBefore,
                            'amount' => $amount,
                            'balanceAfter' => $balanceAfter,
                            'note' => 'Referral Commission',
                            'status' => TransactionStatus::SUCCESSFUL,
                        ]);
                        // return true;
                        return redirect()->route('user.referrals')->with('message', 'Transfer is successfull');
                    }
                    // return true;
                    return redirect()->route('user.referrals')->with('message', 'Transfer is successfull but no transaction');
                }else
                {
                    // return false;
                    return redirect()->route('user.referrals')->with('message', 'There are some issues related to your wallet. Contact Admin');
                }
            } 
            else 
            {
                return redirect()->route('user.referrals')->with('message', 'Wallet not found');
            }
        } else {
            return redirect()->back()->with('message', 'User Verification Failed');
        }
        
       }
}
