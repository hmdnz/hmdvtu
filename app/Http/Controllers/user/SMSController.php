<?php

namespace App\Http\Controllers\user;

use App\Enum\GeneralStatus;
use App\Enum\OrderStatus;
use App\Enum\TransactionStatus;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Biller;
use App\Models\Package;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Services\BulkSMSNigeria\sendSMSService;
use App\Services\Switching\ServiceProviderResolver;
use App\Services\Switching\SMSSwitchingService;
use App\Services\Transaction\CheckWalletService;
use App\Services\Transaction\DebitWalletService;
use App\Services\Transaction\ReversalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SMSController extends Controller
{
    //
    public function index()
    {
        $orders = Order::whereNotNull('status')->where('service', 'Bulk SMS')->orderBy('id', 'desc')->get();
        return view('user.services.buy-sms', [
            'orders' => $orders,
        ]);
    }

    
    public function generateOrderCode()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $codeLength = 15;

        $orderCode = '';
        $charactersLength = strlen($characters);

        for ($i = 0; $i < $codeLength; $i++) {
            $orderCode .= $characters[rand(0, $charactersLength - 1)];
        }

        return $orderCode;
    }

    public function vendSMS(Request $request)
    {
        // Validation logic here (you can use Laravel validation)
        $credentials = $request->validate([
            'userID' => 'required',
            'senderID' => 'required',
            'numbers' => 'required',
            'message' => 'required',
            'package' => 'required',
            'total' => 'required',
            'pin' => 'required',
        ]);

        // check and confirm user pin
        $user = User::find($request->userID);
        if (!$user || !Hash::check($request->input('pin'), $user->pin)) {
            return response()->json(['status' => 'pin', 'message' => 'Check your pin and try again.']);
        }

        // // get biller
        // $biller = Biller::find($request->networkID);
        // if (!$biller || $biller->status !== GeneralStatus::ACTIVE) {
        //     return response()->json(['status' => 'network', 'message' => 'The network is not available.']);
        // }
        // $billerName = $biller->title;

        // get package
        $package = Package::find($request->package);
        if (!$package || $package->status !== GeneralStatus::ACTIVE) {
            return response()->json(['status' => 'package', 'message' => 'The package is not available.']);
        }
        $packageName = $package->title;
        $packagePlan = $package->planID;

        // other params
        $reference = generateTransactionReferenceCode();
        $orderCode = generateOrderReferenceCode();
        $service = "Bulk SMS";
        $type = "Bulk SMS";
        $today = Carbon::now();
        $note = $type . " | " . $packageName . " | " . $request->total;

        // Create a new order
        $order = Order::create([
            'userID' => $request->userID,
            'billerID' => null,
            'packageID' => $package->id,
            'service' => $service,
            'reference' => $orderCode,
            'price' => $request->total,
            'quantity' => '1',
            'total' => $request->total,
            'beneficiary' => $request->numbers,
            'sender' => $request->senderID,
            'message' => $request->message,
            'status' => OrderStatus::INITIATED,
        ]);

        // get wallet
        $wallet = (new CheckWalletService(null, $user->id))->confirmFunds($request->total);
        if(!$wallet )
        {
            return response()->json(['status' => 'wallet', 'message' => 'You have insufficient Balance.']);
        }

        // debit wallet
        $debitWallet = (new DebitWalletService($wallet))->debitWallet($request->total);
        if(!$debitWallet )
        {
            return response()->json(['status' => 'wallet', 'message' => 'We cannot debit your wallet.']);
        }

        // new transaction
        $transaction = Transaction::create([
            'userID' => $user->id,
            'orderID' => $order->id,
            'walletID' => $wallet->id,
            'reference' => $reference,
            'type' => 'Debit',
            'category' => 'Purchase',
            'balanceBefore' => $debitWallet['balanceBefore'],
            'amount' => $debitWallet['amount'],
            'balanceAfter' => $debitWallet['balanceAfter'],
            'note' => $note,
            'status' => TransactionStatus::PENDING
        ]);

        // default provider
        $providerKey = (new ServiceProviderResolver())->resolve($service);
        
        // payload
        $dataPayload = array(
            "sender"=> $request->senderID,
            "message"=> $request->message,
            "beneficiary"=> $request->numbers
        );

        try 
        {
            $delivery = (new SMSSwitchingService($dataPayload, $providerKey))->run();
            
            if($delivery['status'] === GeneralStatus::SUCCESSFUL)
            {
                $order->update([
                    'responseAPI' => $delivery['responseAPI'],
                    'responseMessage' => $delivery['responseMessage'],
                    'responseBody' => json_encode($delivery['responseBody']),
                    'provider' => $delivery['provider'],
                    'status' => OrderStatus::COMPLETED,
                ]);

                $transaction->update([
                    'status' => TransactionStatus::SUCCESSFUL
                ]);

                return response()->json(['status' => 'success', 'message' => 'You successfully purchase '. $service]);
            }
            elseif($delivery['status'] === GeneralStatus::PENDING || $delivery['status'] === GeneralStatus::PROCESSING)
            {

                $order->update([
                    'responseAPI' => $delivery['responseAPI'],
                    'responseMessage' => $delivery['responseMessage'],
                    'responseBody' => json_encode($delivery['responseBody']),
                    'provider' => $delivery['provider'],
                    'status' => OrderStatus::PENDING,
                ]);

                return response()->json(['status' => 'pending', 'message' => 'You SMS purchase is pending']);
            }
            else
            {
                $order->update([
                    'responseAPI' => $delivery['responseAPI'],
                    'responseMessage' => json_encode($delivery['responseMessage']),
                    'responseBody' => json_encode($delivery['responseBody']),
                    'provider' => $delivery['provider'],
                    'status' => OrderStatus::FAILED,
                ]);

                // reverse transaction
                (new ReversalService($transaction, $user, $delivery))->run();
                
                return response()->json(['status' => 'failed', 'message' => 'We cannot process your order at this time']);
            }
        } catch (\Throwable $th) {
            // update order
            $order->update([
                'responseAPI' => null,
                'responseMessage' => null,
                'responseBody' => 'try-catche error',
                'status' => OrderStatus::FAILED,
            ]);
            // reverse transaction
            (new ReversalService($transaction, $user, []))->run();
             // Log the exception
            report($th);

            return response()->json(['status' => 'failed', 'message' => 'There is an error. Try again']);
        }


    }

    public function buySMS(Request $request)
    {
        // Validation logic here (you can use Laravel validation)
        $credentials = $request->validate([
            'user_id' => 'required',
            'senderID' => 'required',
            'numbers' => 'required',
            'message' => 'required',
            'package' => 'required',
            'packageName' => 'required',
            'pin' => 'required',
        ]);

        // check and confirm user pin
        $user = User::find($request->user_id);
        if (!$user || !Hash::check($request->input('pin'), $user->pin)) {
            return redirect()->route('user.buySMS')->with('message', 'Check your pin and try again');
        }

        // other parameters
        $reference = generateTransactionReferenceCode();
        $orderCode = generateOrderReferenceCode();
        $service = "Bulk SMS";
        $type = "Bulk SMS";
        $today = Carbon::now();
        $note = $type . " | " . $request->packageName . " | " . $request->total;

        // Create a new order
        $order = Order::create([
            'userID' => $request->user_id,
            'billerID' => $request->biller,
            'packageID' => $request->package,
            'service' => $service,
            'reference' => $orderCode,
            'price' => $request->total,
            'quantity' => '1',
            'total' => $request->total,
            'beneficiary' => $request->numbers,
            'sender' => $request->senderID,
            'message' => $request->message,
            'status' => OrderStatus::INITIATED,
        ]);

        // get wallet
        $wallet = (new CheckWalletService(null, $user->id))->confirmFunds($request->total);
        if(!$wallet )
        {
            return response()->json(['status' => 'wallet', 'message' => 'You have insufficient Balance.']);
        }

        // debit wallet
        $debitWallet = (new DebitWalletService($wallet))->debitWallet($request->total);
        if(!$debitWallet )
        {
            return response()->json(['status' => 'wallet', 'message' => 'We cannot debit your wallet.']);
        }

        // new transaction
        $transaction = Transaction::create([
            'userID' => $user->id,
            'orderID' => $order->id,
            'walletID' => $wallet->id,
            'reference' => $reference,
            'type' => 'Debit',
            'category' => 'Purchase',
            'balanceBefore' => $debitWallet['balanceBefore'],
            'amount' => $debitWallet['amount'],
            'balanceAfter' => $debitWallet['balanceAfter'],
            'note' => $note,
            'status' => TransactionStatus::PENDING
        ]);

        // get wallet
        // $wallet = Wallet::where('userID', $request->user_id)->first();
        // if(!$order && !$wallet )
        // {
        //     return redirect()->route('user.buySMS')->with('message', 'There is issue with your wallet. Contact Admin');
        // }

        // // confirm the available funds and make pending transaction
        // if($wallet->mainBalance >= $request->total)
        // {
        //     $balanceBefore = $wallet->mainBalance;
        //     $balanceAfter = $wallet->mainBalance - floatval($request->total);
        //     $wallet->mainBalance = $balanceAfter;
        //     $wallet->save();
        //     // new transaction
        //     $transaction = Transaction::create([
        //         'userID' => $user->id,
        //         'orderID' => $order->id,
        //         'walletID' => $wallet->id,
        //         'type' => 'Debit',
        //         'balanceBefore' => $balanceBefore,
        //         'amount' => $request->total,
        //         'balanceAfter' => $balanceAfter,
        //         'note' => $note,
        //         'status' => TransactionStatus::PENDING
        //     ]);
        // }
        // else
        // {
        //     return redirect()->route('user.buySMS')->with('message', 'Insufficient Funds in Your Wallet');
        // }

        // dd($order, $transaction);

        try {
            // $delivery = $this->BulkSMSAPI($request->senderID, $request->message , $request->numbers);
            $data = [
                'sender'=>$request->senderID,
                'recipients'=>$request->numbers,
                'message'=>$request->message,
                'reference'=>$orderCode
            ];

            $delivery = (new sendSMSService())->run($data);
            dd($delivery);
            if($delivery['success'] === true)
            {
                $order->update([
                    'responseAPI' => $delivery['apiDeliveryId'],
                    'responseMessage' => $delivery['apiResponse'],
                    'responseBody' => $delivery['response'],
                    'status' => OrderStatus::COMPLETED,
                ]);

                $transaction->update([
                    'status' => TransactionStatus::SUCCESSFUL
                ]);

                return redirect()->route('user.showOrder',[$order->id])->with('message', 'You successfully purchase BulkSMS');
            }
            elseif($delivery['success'] === 'pending')
            {
                $requery = $this->requeryOrder($order);
                $order->update([
                    'responseAPI' => $requery['apiDeliveryId'],
                    'responseMessage' => $requery['apiResponse'],
                    'responseBody' => $requery['response'],
                    'status' => OrderStatus::PENDING,
                ]);
                return redirect()->route('user.showOrder',[$order->id])->with('message', 'You successfully purchase Airtime');
            }
            else
            {
                $order->update([
                    'responseAPI' => $delivery['apiDeliveryId'],
                    'responseMessage' => $delivery['apiResponse'],
                    'responseBody' => $delivery['response'],
                    'status' => OrderStatus::FAILED,
                ]);
                $balanceAfter = $wallet->mainBalance + floatval($request->total);
                $wallet->update([
                    'balance' => $balanceAfter,
                ]);
                $transaction->update([
                    'status' => TransactionStatus::FAILED
                ]);
                return redirect()->route('user.buySMS')->with('message', 'We cannot process your order at this time');
            }
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('user.buySMS')->with('message', 'There is an error. Try again');
        }

    }

    private function BulkSMSAPI($senderId, $message, $receipients)
    {
        $headers = array('Content-Type: application/x-www-form-urlencoded');
        $url = 'https://www.bulksmsnigeria.com/api/v1/sms/create';
        
        if (is_array($arr_params)) {
            $final_url_data = http_build_query($arr_params, '', '&');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $final_url_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);
        curl_close($ch);
        $obj = json_decode($response);
        if ($obj->data->status == 'success') {
            // $response2 = array('success' => true, 'apiDeliveryId' => $obj->id, 'apiResponse' => $obj->api_response); 
            $response = [
                'success' => true, 
                'apiDeliveryId' => '', 
                'apiResponse' => 'success',
                'response' => $response
            ];
        } else {
            // $response2 = array('success' => false, 'apiDeliveryId' => $obj->id, 'apiResponse' => 'There is an error. Try again later or contact support.');
            $response = [
                'success' => false, 
                'apiDeliveryId' => '', 
                'apiResponse' => 'There is an error. Try again later or contact support.',
                'response' => $response
            ];
        }
        return $response;
    }
}
