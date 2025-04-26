<?php

namespace App\Http\Controllers\user;

use App\Enum\PaymentStatus;
use App\Enum\TransactionStatus;
use App\Http\Controllers\Controller;

use App\Models\Wallet;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\VirtualAccount;
use App\Services\Monnify\GetWalletService;
use App\Services\Monnify\RequeryService;
use App\Services\Monnify\VirtualAccountService;

class WalletController extends Controller
{
    public function walletBalance()
    {
        // $response = (new AccountLookupService())->run($validated);
        $response = (new GetWalletService())->run();
        return response()->json($response);
    }
    //wallet page
    public function index()
    {
        $transactions = Transaction::whereNotNull('status')
                    ->where('userID', auth()->user()->id)
                    ->orderBy('id', 'desc')->get();
                    
        return view('user.wallet.wallet', [
            'transactions' => $transactions,
        ]);
    }
    // wallet topup page
    public function showWalletTopUp()
    {
        $RVAs =  VirtualAccount::where('userID', auth()->user()->id)->where('status', 'Active')->orderBy('id', 'desc')->get();
        return view('user.wallet.wallet-top-up', [
            'RVAs' => $RVAs,
        ]);
    }

    private function getAuthorization()
    {
        $key = base64_encode('MK_PROD_K1XRNL3CWV:TV2JAG0RZQMP4CDNLEX26RFRM3F11D71');
        // echo $key;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.monnify.com/api/v1/auth/login");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Basic $key",
            'Content-type' => 'application/json',
        ));
        // $EPIN_response = curl_exec($ch);
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        // echo $EPIN_response;
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $obj = json_decode($response);
            // echo $response;
            if ($obj->requestSuccessful) {
                if ($obj->responseMessage == 'success') {
                    // $amount = ($obj->data->amount - $obj->data->fees) / 100;
                    return $obj->responseBody->accessToken;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }
    // verify online payments 
    public function transferVerify($reference)
    {
        $token = $this->getAuthorization();
        // verifying payment
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.monnify.com/api/v2/merchant/transactions/query?paymentReference=$reference",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $token,
                "Cache-Control: no-cache",
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        // echo $response;
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $obj = json_decode($response);
            // echo $response;
            if ($obj->requestSuccessful) {
                if ($obj->responseMessage == 'success') {
                    // $amount = ($obj->data->amount - $obj->data->fees) / 100;
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }
    // verify payment
    public function verifyPayment(Request $request)
    {
        $reference = $request->reference;
        $walletId = $request->walletId;
        $userId = $request->userId;
        $userName = $request->userName;
        $userEmail = $request->userEmail;
        $amount = $request->amount;
        $fees = $request->fees;
        $total = $request->total;
        if ($userId == auth()->user()->id && $walletId == auth()->user()->wallet->id) {
            $transferVerify = (new RequeryService())->run($reference);
            // dd($transferVerify);
            if ($transferVerify['status'] && isset($transferVerify['data']['paymentStatus'])) 
            {
                // check wallet
                $wallet = Wallet::find($request->walletId);
                if($wallet)
                {
                    $balanceBefore = $wallet->mainBalance;
                    $newBalance = floatval($amount);
                    $balanceAfter = $balanceBefore + $newBalance;
                }else{
                    return response()->json(['status' => false, 'message' => 'We cannot get your wallet.']);
                }

                if ($transferVerify['data']['paymentStatus'] == PaymentStatus::PAID) 
                {
                    // store the new balance
                    $wallet->mainBalance = $balanceAfter;
                    $wallet->save();
                    // create payment record
                    $payment = Payment::create([ 
                        'userID' => $userId,
                        'walletID' => $walletId,
                        'reference' => $reference,
                        'provider_reference' => $transferVerify['data']['transactionReference'],
                        'gateway' => 'Monnify',
                        'channel' => 'Web Checkout',
                        'balanceBefore' => $balanceBefore,
                        'amount' => $newBalance,
                        'balanceAfter' => $balanceAfter,
                        'fees' => $fees,
                        'total' => $total,
                        'status' => TransactionStatus::SUCCESSFUL,
                        'response' => json_encode($transferVerify)
                    ]);
                    // create transaction record
                    $transaction = Transaction::create([
                        'userID' => $userId,
                        'walletID' => $walletId,
                        'reference' => $reference,
                        'type' => 'Credit',
                        'balanceBefore' => $balanceBefore,
                        'amount' => $newBalance,
                        'balanceAfter' => $balanceAfter,
                        'note' => 'Wallet Funding',
                        'status' => TransactionStatus::SUCCESSFUL,
                    ]);
                    // return true;
                    return response()->json(['status' => true, 'message' => 'Payment is successfull']);
                }elseif(
                    $transferVerify['data']['paymentStatus'] == PaymentStatus::PENDING || 
                    $transferVerify['data']['paymentStatus'] == PaymentStatus::OVERPAID || 
                    $transferVerify['data']['paymentStatus'] == PaymentStatus::PARTIALLY_PAID )
                    {
                        $payment = Payment::create([ 
                            'userID' => $userId,
                            'walletID' => $walletId,
                            'reference' => $reference,
                            'provider_reference' => $transferVerify['data']['transactionReference'],
                            'gateway' => 'Monnify',
                            'channel' => 'Web Checkout',
                            'balanceBefore' => $balanceBefore,
                            'amount' => $newBalance,
                            'balanceAfter' => $balanceAfter,
                            'fees' => $fees,
                            'total' => $total,
                            'status' => $transferVerify['data']['paymentStatus'],
                            'response' => json_encode($transferVerify)
                        ]);
                        return response()->json(['status' => true, 'message' => 'Payment has been recieved.But is' . $transferVerify['data']['paymentStatus']]);
                }elseif(
                    $transferVerify['data']['paymentStatus'] == PaymentStatus::FAILED || 
                    $transferVerify['data']['paymentStatus'] == PaymentStatus::EXPIRED || 
                    $transferVerify['data']['paymentStatus'] == PaymentStatus::CANCELED )
                    {
                        return response()->json(['status' => false, 'message' => 'Payment is unsuccessfull.Try agin later.']);
                }
                else{
                    return response()->json(['status' => false, 'message' => 'We cannot verify this payment.Try agin later.']);
                }
                
            }
            else
            {
                return response()->json(['status' => false, 'message' => 'Sorry, we cannot verify your payment']);
            }
        }
        else
        {
            return response()->json(['status' => false, 'message' => 'There is Error.']);
        }
    }
    // transactions page
    public function showTransactions()
    {
        $transactions = Transaction::whereNotNull('status')
                    ->where('userID', auth()->user()->id)
                    ->orderBy('id', 'desc')->get();
                    
        return view('user.transactions.transactions', [
            'transactions' => $transactions,
        ]);
    }
    // single transaction
    public function showTransaction(Transaction $transaction)
    {
        return view('user.transactions.transaction', [
            'transaction' => $transaction,
        ]);
    }

    // payments page
    public function showPayments()
    {
        $payments = Payment::whereNotNull('status')
                    ->where('userID', auth()->user()->id)
                    ->orderBy('id', 'desc')->get();
                    
        return view('user.payments.payments', [
            'payments' => $payments,
        ]);
    }
    // single payment
    public function showPayment(Payment $payment)
    {
        return view('user.payments.payment', [
            'payment' => $payment,
        ]);
    }

    public function NewRVA()
    {
        $RVA = $this->generateVirtualAccount(auth()->user()->wallet->identifier, auth()->user()->username, auth()->user()->email);
        if($RVA['status'])
        {
            foreach ($RVA['data'] as $account) {
                $virtualAccount = VirtualAccount::create([
                    'userID' => auth()->user()->id,
                    'walletID' => auth()->user()->wallet->id,
                    'provider' => 'Monnify',
                    'accountName' => $account['accountName'],
                    'accountNumber' => $account['accountNumber'],
                    'accountBank' => $account['bankName'],
                    'status' => TransactionStatus::ACTIVE,
                ]); 
            }
            return redirect()->back()->with('success', 'The accounts has been generated');
        }
        else
        {
            return redirect()->back()->with('error', $RVA['message']); 
        }
        
    }

    public function generateVirtualAccount($walletIdentifier, $userName, $userEmail)
    {
        $data = [
            'accountReference' => $walletIdentifier,
            'accountName' => $userName,
            'currencyCode' => 'NGN',
            'contractCode' => env('MONNIFY_CONTRACT_CODE', '245363951467'),
            'customerEmail' => $userEmail,
            'customerName' => $userName,
            "getAllAvailableBanks" => false,
            "preferredBanks" => ["035","50515"]
        ];

        $response = (new VirtualAccountService())->run($data);

        if($response)
        { 
            return $response;
        }
        return false;

    }
    public function createReservedAccount($walletIdentifier, $userName, $userEmail)
    {
        $token = $this->getAuthorization();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.monnify.com/api/v2/bank-transfer/reserved-accounts");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array(
            'accountReference' => $walletIdentifier,
            'accountName' => $userName,
            'currencyCode' => 'NGN',
            'contractCode' => '854262695245',
            'customerEmail' => $userEmail,
            'customerName' => $userName,
            "getAllAvailableBanks" => false,
            "preferredBanks" => ["035","50515"]
        )));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer " . $token,
            "Cache-Control: no-cache",
            "Content-Type: application/json",
        ));
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        // echo $response;
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $obj = json_decode($response);
            if ($obj->requestSuccessful) {
                if ($obj->responseMessage == 'success') {
                    return $obj->responseBody->accounts;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

}
