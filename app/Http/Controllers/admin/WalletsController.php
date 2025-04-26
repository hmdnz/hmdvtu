<?php

namespace App\Http\Controllers\admin;

use App\Enum\PaymentStatus;
use App\Enum\TransactionStatus;
use App\Models\Admin;
use App\Models\Wallet;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VirtualAccount;
use App\Services\Monnify\RequeryService;
use Illuminate\Support\Facades\Hash;

class WalletsController extends Controller
{
    //
    public function index()
    {
        $wallets = Wallet::whereNotNull('status')
                    ->orderBy('id', 'desc')->get();                    
        $walletsCount = Wallet::whereNotNull('status')->get()->count();
        return view('admin.wallets.all', [
            'wallets' => $wallets,
            'walletCounts' => $walletsCount,
        ]);
    }


    public function walletPayments(Request $request, Wallet $wallet)
    {
        $payments = Payment::whereNotNull('status')
                    ->where('walletID', $wallet->id)
                    ->orderBy('id', 'desc')->get();                    
        $paymentsCount = Payment::whereNotNull('status')
                    ->where('walletID', $wallet->id)
                    ->get()->count();
        return view('admin.wallets.payments', [
            'wallet' => $wallet,
            'payments' => $payments,
            'paymentCounts' => $paymentsCount,
        ]);
    }

    public function walletTransactions(Request $request, Wallet $wallet)
    {
        $transactions = Transaction::whereNotNull('status')
                    ->where('walletID', $wallet->id)
                    ->orderBy('id', 'desc')->get();                    
        $transactionsCount = Transaction::whereNotNull('status')
                    ->where('walletID', $wallet->id)
                    ->get()->count();
        return view('admin.wallets.transactions', [
            'wallet' => $wallet,
            'transactions' => $transactions,
            'transactionCounts' => $transactionsCount,
        ]);
    }

    public function historyPayments(Request $request)
    {
        $payments = Payment::whereNotNull('status')
                    ->orderBy('id', 'desc')->get();                    
        $paymentsCount = Payment::whereNotNull('status')
                    ->get()->count();
        return view('admin.history.payments', [
            'payments' => $payments,
            'paymentCounts' => $paymentsCount,
        ]);
    } 
    
    public function requeryPayment(Request $request)
    {
        $reference = $request->reference;

        // get the payment 
        $payment = Payment::withReference($reference)->first();
        if (!$payment) {
            return response()->json(['status' => false, 'message' => 'Payment not found'], 404);
        }

        // get the wallet
        $wallet = Wallet::find($payment->walletID);
        if($wallet)
        {
            $balanceBefore = $wallet->mainBalance;
            $newBalance = floatval($payment->amount);
            $balanceAfter = $balanceBefore + $newBalance;
        }else{
            return response()->json(['status' => false, 'message' => 'We cannot get your wallet.']);
        }

        $transferVerify = (new RequeryService())->run($reference);

        if ($transferVerify['status'] && isset($transferVerify['data']['paymentStatus'])) 
        {
            if ($transferVerify['data']['paymentStatus'] == PaymentStatus::PAID) 
            {
                // store the new balance
                $wallet->mainBalance = $balanceAfter;
                $wallet->save();
                // create payment record
                $payment->update([
                    'status' => TransactionStatus::SUCCESSFUL,
                ]);
                // create transaction record
                $transaction = Transaction::create([
                    'userID' => $payment->userID,
                    'walletID' => $payment->walletID,
                    'reference' => $reference,
                    'type' => 'Credit',
                    'balanceBefore' => $balanceBefore,
                    'amount' => $newBalance,
                    'balanceAfter' => $balanceAfter,
                    'note' => 'Wallet Funding',
                    'status' => TransactionStatus::SUCCESSFUL,
                ]);
                // return true;
                return response()->json(['status' => true, 'code'=>'Successfull', 'message' => 'Payment is successfull']);
            }
            elseif(
                $transferVerify['data']['paymentStatus'] == PaymentStatus::PENDING || 
                $transferVerify['data']['paymentStatus'] == PaymentStatus::OVERPAID || 
                $transferVerify['data']['paymentStatus'] == PaymentStatus::PARTIALLY_PAID )
                {
                    $payment->update([ 
                        'status' => $transferVerify['data']['paymentStatus'],
                    ]);
                    return response()->json(['status' => true,  'code'=> $transferVerify['data']['paymentStatus'], 'message' => 'Payment has been recieved.But is ' . $transferVerify['data']['paymentStatus']]);
            }elseif(
                $transferVerify['data']['paymentStatus'] == PaymentStatus::FAILED || 
                $transferVerify['data']['paymentStatus'] == PaymentStatus::EXPIRED || 
                $transferVerify['data']['paymentStatus'] == PaymentStatus::CANCELED )
                {
                    $payment->update([ 
                        'status' => PaymentStatus::FAILED,
                    ]);
                    return response()->json(['status' => false, 'code'=>'Failed', 'message' => 'Payment is unsuccessfull.']);
            }else{
                return response()->json(['status' => false, 'code'=>'Failed', 'message' => 'We cannot verify this payment.Try agin later.']);
            }
        }

    }
    
    // single payment
    public function showPayment(Payment $payment)
    {
        return view('admin.history.payment', [
            'payment' => $payment,
        ]);
    }

    public function historyTransactions(Request $request)
    {
        $transactions = Transaction::whereNotNull('status')
                    ->orderBy('id', 'desc')->get();                    
        $transactionsCount = Transaction::whereNotNull('status')
                    ->get()->count();
        return view('admin.history.transactions', [
            'transactions' => $transactions,
            'transactionCounts' => $transactionsCount,
        ]);
    }

    // single transaction
    public function showTransaction(Transaction $transaction)
    {
        return view('admin.history.transaction', [
            'transaction' => $transaction,
        ]);
    }

    public function walletTopUp(Request $request)
    {
        $credentials = $request->validate([
            'adminId' => ['required'],
            'email' => 'required|email',
            'adminPassword' => 'required|min:8',
            'amount' => 'required|numeric',
            'id' => 'required',
        ]);
        // generate reference
        $reference = generatePaymentReferenceCode();
        $admin = Admin::where([
            'id' => $request->adminId,
            'email' => $request->email,
        ])->first();
        
        // If admin is verified, compare hashed passwords
        if ($admin && Hash::check($request->input('adminPassword'), $admin->password)) {
            $wallet = wallet::find($request->id); // Assuming you have the $walletId variable
            if ($wallet) {
                // Update the wallet's record in the wallets table
                $balanceBefore = $wallet->mainBalance;
                $balanceAfter = $wallet->mainBalance + floatval($request->amount);
                $wallet->mainBalance = $balanceAfter;
                if($wallet->save())
                {
                    $payment = Payment::create([ 
                        'userID' => $wallet->userID,
                        'walletID' => $wallet->id,
                        'reference' => $reference,
                        'gateway' => 'ZaumaData',
                        'channel' => 'Admin Wallet',
                        'balanceBefore' => $balanceBefore,
                        'amount' => $request->amount,
                        'balanceAfter' => $balanceAfter,
                        'fees' => '0',
                        'total' => '0',
                        'status' => TransactionStatus::SUCCESSFUL,
                    ]);
                    if($payment)
                    {
                        $transaction = Transaction::create([
                            'userID' => $wallet->userID,
                            'walletID' => $wallet->id,
                            'reference' => $reference,
                            'type' => 'Credit',
                            'balanceBefore' => $balanceBefore,
                            'amount' => $request->amount,
                            'balanceAfter' => $balanceAfter,
                            'note' => 'Wallet Funding',
                            'status' => TransactionStatus::SUCCESSFUL,
                        ]);
                        return redirect()->route('admin.wallets')->with('message', 'wallet has been funded!');
                    }else
                    {
                        return redirect()->route('admin.wallets')->with('message', 'there is an issue!');
                    }
                    
                }
            } else {
                return redirect()->back()->with('message', 'wallet not found');
            }
        } else {
            return redirect()->back()->with('message', 'Admin Verification Failed');
        }
        
        return redirect()->back()->with('message', 'There is an error. Try again');
    }

    public function virtualAccounts()
    {
        $accounts = VirtualAccount::whereNotNull('status')
                    ->orderBy('id', 'desc')->get();                    
        
        $totalAccounts = $accounts->count();

        $activeAccounts = $accounts->where('status', 'Active')->count(); 
        $inactiveAccounts = $accounts->where('status', 'Inactive')->count(); 
                    
        return view('admin.wallets.accounts', compact('accounts', 'totalAccounts', 'activeAccounts', 'inactiveAccounts'));
    }

    public function activateVirtualAccount(Request $request)
    {
        $account = VirtualAccount::find($request->id); 
        if ($account) {
            // Update the status to active
            $account->update([
                'status' => TransactionStatus::ACTIVE,
            ]);
            return response()->json(['status' => true, 'code' => TransactionStatus::ACTIVE, 'message' => 'virtual account activated successfully'], 200);
        } else {
            return response()->json(['status' => false, 'code' => 'Unknown', 'message' => 'virtual account not found'], 200);
        }
        return response()->json(['status' => true, 'code' => 'Unknown', 'message' => 'There is an error. Try again'], 200);
    }

    public function deactivateVirtualAccount(Request $request)
    {
        $account = VirtualAccount::find($request->id); 
        if ($account) {
            // Update the status to active
            $account->update([
                'status' => TransactionStatus::INACTIVE,
            ]);
            return response()->json(['status' => true, 'code' => TransactionStatus::INACTIVE, 'message' => 'virtual account deactivated successfully'], 200);
        } else {
            return response()->json(['status' => false, 'code' => 'Unknown', 'message' => 'virtual account not found'], 200);
        }
        return response()->json(['status' => true, 'code' => 'Unknown', 'message' => 'There is an error. Try again'], 200);
    }

    public function delete(Request $request)
    {
        $credentials = $request->validate([
            'adminId' => ['required'],
            'email' => 'required|email',
            'adminPassword' => 'required|min:8',
        ]);

        $admin = Admin::where([
            'id' => $request->adminId,
            'email' => $request->email,
        ])->first();
        
        // If admin is verified, compare hashed passwords
        if ($admin && Hash::check($request->input('adminPassword'), $admin->password)) {
            $biller = Biller::find($request->id); // Assuming you have the $billerId variable
            if ($biller) {
                // Update the service's record in the services table
                $biller->update([
                    'status' => null,
                ]);
                return redirect()->route('admin.billers')->with('message', 'biller has been deleted!');
                // return response()->json(['message' => 'biller record updated successfully'], 200);
            } else {
                return redirect()->back()->with('message', 'biller not found');
                // return response()->json(['message' => 'biller not found'], 404);
            }
        } else {
            // return response()->json(['message' => 'Admin verification failed'], 401);
            return redirect()->back()->with('message', 'Admin Verification Failed');
        }
        
        return redirect()->back()->with('message', 'There is an error. Try again');
    }
}
