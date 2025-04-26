<?php

namespace App\Http\Controllers\user;

use App\Enum\GeneralStatus;
use App\Enum\OrderStatus;
use App\Enum\TransactionStatus;
use App\Http\Controllers\Controller;
use App\Models\Biller;
use App\Models\CableCustomer;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Package;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Services\EasyAccessAPI\VerifySmartCardService;
use App\Services\Switching\CableSwitchingService;
use App\Services\Switching\ServiceProviderResolver;
use App\Services\Transaction\CheckWalletService;
use App\Services\Transaction\DebitWalletService;
use App\Services\Transaction\ReversalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CableController extends Controller
{
    //
    public function index()
    {
        $billers = Biller::where('service', 'Cable')->where('status', 'Active')->orderBy('id', 'asc')->get();
        return view('user.services.buy-cable', [
            'billers' => $billers,
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

    public function verifyIUC($iuc, $biller)
    {
        // get biller
        $biller = Biller::find($biller);
        if (!$biller || $biller->status !== GeneralStatus::ACTIVE) {
            return response()->json(['status' => 'network', 'message' => 'The BILLER is not available.']);
        }
        $discoVariation = $biller->variation;

        // check existing record 
        $existingCustomer = CableCustomer::where([
            ['smartcard', '=', $iuc],
            ['biller', '=', $biller->title],
        ])
        ->orderByRaw('smartcard = ? DESC', [$iuc]) // prioritize exact match
        ->first();
        
        if(!$existingCustomer)
        {
            $payload = [
                'company' => $discoVariation,
                'iucno' =>$iuc,
            ];

            $response = (new VerifySmartCardService())->run($payload);
            if($response && $response['status'] = true)
            {
                $customer = CableCustomer::create([
                    "userID" => auth('web')->user()->id,
                    "name"=> $response['data']['name'],
                    "smartcard"=> $iuc,
                    "biller"=> $biller->title,
                    "address"=> 'N/A',
                    "status"=> GeneralStatus::ACTIVE,
                ]);

                return response()->json(['status' => 'success', 'message' => 'smartcard verified successfully', 'customer_name' => $response['data']['name']]);
            }else{
                return response()->json(['status' => 'failed', 'message' => 'cannot verify smartcard number']);
            }
        }
        else
        {
            return response()->json(['status' => 'success', 'message' => 'smartcard verified successfully', 'customer_name' => $existingCustomer->name]);
        }
    }

    public function vendCable(Request $request)
    {
        // Validation logic here (you can use Laravel validation)
        $credentials = $request->validate([
            'userID' => 'required',
            'biller' => 'required',
            'package' => 'required',
            'smartcard' => 'required',
            'customer' => 'required',
            'amount' => 'required',
            'total' => 'required',
        ]);
        
        // check and confirm user pin
        $user = User::find($request->userID);
        if (!$user || !Hash::check($request->input('pin'), $user->pin)) {
            return response()->json(['status' => 'pin', 'message' => 'Check your pin and try again.']);
        }

        // get biller
        $biller = Biller::find($request->biller);
        if (!$biller || $biller->status !== GeneralStatus::ACTIVE) {
            return response()->json(['status' => 'network', 'message' => 'The biller is not available.']);
        }
        $billerName = $biller->title;
        $billerVariation = $biller->variation;

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
        $service = "Cable"; $type = "Cable";
        $today = Carbon::now();
        $note = $type . " | " . $packageName . " | " . $request->total;
        
        // Create a new order
        $order = Order::create([
            'userID' => $request->userID,
            'billerID' => $biller->id,
            'packageID' => $package->id,
            'service' => $service,
            'reference' => $orderCode,
            'price' => $request->total,
            'quantity' => '1',
            'total' => $request->total,
            'meterNumber' => $request->smartcard,
            'meterName' => $request->customer,
            'status' => OrderStatus::INITIATED,
        ]);

        // get wallet
        $wallet = (new CheckWalletService(null, $user->id))->confirmFunds($request->total);
        // dd($wallet);
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
        $providerKey = (new ServiceProviderResolver())->resolve($service, $biller->id);
        
        // payload
        $dataPayload = array(
            "company"=> $billerVariation,
            "smartcard"=> $request->smartcard,
            "plan"=> $packagePlan
        );
        
        try 
        {
            $delivery = (new CableSwitchingService($dataPayload, $providerKey))->run();
            
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

                return response()->json(['status' => 'success', 'message' => 'You successfully purchase Cable Subscription']);
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

                return response()->json(['status' => 'pending', 'message' => 'You cable purchase is pending']);
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

    public function buyCable(Request $request)
    {
        // Validation logic here (you can use Laravel validation)
        $credentials = $request->validate([
            'userID' => 'required',
            'billerID' => 'required',
            'meterType' => 'required',
            'meterNumber' => 'required',
            'packageID' => 'required',
            'pin' => 'required',
            'planID' => 'required',
            'packageName' => 'required',
            'total' => 'required',
        ]);

        // check and confirm user pin
        $user = User::find($request->userID);
        if (!$user || !Hash::check($request->input('pin'), $user->pin)) {
            return redirect()->route('user.buyData')->with('message', 'Check your pin and try again');
        }
        // other params
        $orderCode = $this->generateOrderCode();
        $service = "Cable"; $type = "DSTV";
        $today = Carbon::now();
        $note = $service . " | " . $type . " | " . $request->packageName . " | " . $request->total;
        
        // get biller & package
        $biller = Biller::find($request->billerID);
        $package = Package::find($request->packageID);

        // Create a new order
        $order = Order::create([
            'userID' => $request->userID,
            'billerID' => $request->billerID,
            'packageID' => $request->packageID,
            'service' => $service,
            'reference' => $orderCode,
            'price' => $request->total,
            'meterNumber' => $request->meterNumber,
            'quantity' => '1',
            'total' => $request->total,
            'status' => OrderStatus::INITIATED,
        ]);

        
        // get wallet
        $wallet = Wallet::where('userID', $request->userID)->first();
        if(!$order && !$wallet )
        {
            return redirect()->back()->with('message', 'There is issue with your wallet. Contact Admin');
        }

        // confirm the available funds and make pending transaction
        if($wallet->mainBalance >= $request->total)
        {
            $balanceBefore = $wallet->mainBalance;
            $balanceAfter = $wallet->mainBalance - floatval($request->total);
            $wallet->mainBalance = $balanceAfter;
            $wallet->save();
            // new transaction
            $transaction = Transaction::create([
                'userID' => $user->id,
                'orderID' => $order->id,
                'walletID' => $wallet->id,
                'type' => 'Debit',
                'category' => 'Purchase',
                'balanceBefore' => $balanceBefore,
                'amount' => $request->total,
                'balanceAfter' => $balanceAfter,
                'note' => $note,
                'status' => TransactionStatus::PENDING,
            ]);
        }
        else
        {
            return redirect()->back()->with('message', 'Insufficient Funds in Your Wallet');
        }
        
        try 
        {

            $delivery = $this->CableAPI($biller->title, $request->meterNumber, $request->planID);
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


                return redirect()->route('user.showOrder',[$order->id])->with('message', 'You successfully purchase Cable');
            }
            elseif($delivery['success'] === 'pending')
            {
                $requery = $this->requeryOrder($order);
                if($requery['success'] === true )
                {
                    $order->update([
                        'responseAPI' => $requery['apiDeliveryId'],
                        'responseMessage' => $requery['apiResponse'],
                        'responseBody' => $requery['response'],
                        'status' => OrderStatus::PENDING,
                    ]);
                    return redirect()->route('user.showOrder',[$order->id])->with('message', 'Your purchase for Cable is pending');
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
                    return redirect()->route('user.showOrder',[$order->id])->with('message', 'Your order is pending');
                }
            }
            else
            {
                $order->update([
                    'responseAPI' => $delivery['apiDeliveryId'],
                    'responseMessage' => $delivery['apiResponse'],
                    'responseBody' => $delivery['response'],
                    'status' => 'Failed',
                ]);
                $balanceAfter = $wallet->mainBalance + floatval($request->total);
                $wallet->update([
                    'balance' => $balanceAfter,
                ]);
                $transaction->update([
                    'status' => 'Failed'
                ]);
                return redirect()->back()->with('message', 'We cannot process your order at this time');
            }
        } catch (\Throwable $th) {
            
            return redirect()->route('user.buyCable')->with('message', 'There is an error. Try again');
        }

        // dd($request);
        // $orderCode = $this->generateOrderCode();
        // $service = "Cable";
        // $type = "Cable";
        // $today = Carbon::now();
        // $orderNote = $type . "|" . $request->packageName . "|" . $request->total;
        // $tranNote = $request->packageName . "|" . $request->total;
        // // check pin
        // $user = User::find($request->user_id);
        // if ($user && Hash::check($request->input('pin'), $user->pin)) 
        // {
        //     // Create a new order
        //     $order = Order::create([
        //         'user_id' => $request->user_id,
        //         'package_id' => $request->package,
        //         'service' => $service,
        //         'orderCode' => $orderCode,
        //         'price' => $request->total,
        //         'quantity' => '1',
        //         'total' => $request->total,
        //         'tokenMeterNo' => $request->iuc,
        //         'biller' => $request->company,
        //         'status' => 'Active',
        //     ]);
        //     // dd($order);
        //     if($order)
        //     {
        //         $wallet = Wallet::where('user_id', $request->user_id)->first();
        //         if($wallet && ($wallet->balance >= $request->total))
        //         {
        //             $balanceBefore = $wallet->balance;
        //             $balanceAfter = $wallet->balance - floatval($request->total);
        //             $wallet->balance = $balanceAfter;
        //             if($wallet->save())
        //             {
        //                 $transaction = Transaction::create([
        //                     'user_id' => $request->user_id,
        //                     'order_id' => $order->id,
        //                     'wallet_id' => auth()->user()->wallet->id,
        //                     'type' => 'Debit',
        //                     'balanceBefore' => $balanceBefore,
        //                     'amount' => $request->total,
        //                     'balanceAfter' => $balanceAfter,
        //                     'note' => $tranNote,
        //                     'status' => 'Active',
        //                 ]);
        //                 if ($transaction) {
        //                     $delivery = $this->CableAPI($request->disco, $request->meterType , $request->meterNo, $request->total);
        //                     if($delivery['success'] === true)
        //                     {
        //                         $order->update([
        //                             'apiDeliveryId' => $delivery['apiDeliveryId'],
        //                             'apiResponse' => $delivery['apiResponse'],
        //                             'status' => 'Completed',
        //                         ]);
        //                         // return redirect()->route('user.buyCable')
        //                         return redirect()->route('user.showOrder',[$order->id])->with('message', 'You successfully purchase Cable token');
        //                     }
        //                     else
        //                     {
        //                         $order->update([
        //                             'apiDeliveryId' => $delivery['apiDeliveryId'],
        //                             'apiResponse' => $delivery['apiResponse'],
        //                             'status' => 'Failed',
        //                         ]);
        //                         $balanceBefore = $wallet->balance;
        //                         $balanceAfter = $wallet->balance + floatval($request->total);
        //                         $wallet->update([
        //                             'balance' => $balanceAfter,
        //                         ]);
        //                         $transaction = Transaction::create([
        //                             'user_id' => $request->user_id,
        //                             'order_id' => $order->id,
        //                             'wallet_id' => auth()->user()->wallet->id,
        //                             'type' => 'Credit',
        //                             'balanceBefore' => $balanceBefore,
        //                             'amount' => $request->total,
        //                             'balanceAfter' => $balanceAfter,
        //                             'note' => `Refund for order: $orderCode`,
        //                             'status' => 'Active',
        //                         ]);
        //                         return redirect()->route('user.buyCable')->with('message', 'We cannot process your order at this time');
        //                     }
        //                 }else{
        //                     return redirect()->route('user.buyCable')->with('message', 'We cannot perform transaction on your wallet');
        //                 }
        //             }
        //             else
        //             {
        //                 return redirect()->route('user.buyCable')->with('message', 'We cannot deduct your wallet at this time');
        //             }
        //         }
        //         else
        //         {
        //             return redirect()->route('user.buyCable')->with('message', 'Check your wallet balance.');
        //         }
        //     }
        //     else
        //     {
        //         return redirect()->route('user.buyCable')->with('message', 'We cannot process your order at this time');
        //     }
        // }
        // else
        // {
        //     return redirect()->route('user.buyCable')->with('message', 'Check your pin and try again');        
        // }
    }

    public function CableAPI($company, $iuc, $plan)
    {
        $jsonData = '{
            "success": "true",
            "message": "TV Subscription was Successful",
            "company": "GOTV",
            "package": "GOTV Jolli - N3950",
            "iucno": "7032054653",
            "amount": 2800,
            "transaction_date": "15-01-2021 07:10:10 am",
            "reference_no": "ID9330298041",
            "status": "Successful"
        }';

        $jsonData2 = '{
            "success": "false",
            "message": "Amount Too Low, Minimum Amount is N1000"
        }';
        $authorizationHeader = 'AuthorizationToken: ' . env('EASYACCESS_API_KEY');
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://easyaccessapi.com.ng/api/paytv.php",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array(
                'company' =>$company,
                'iucno' => $iuc,
                'package' =>$plan,
            ),
            CURLOPT_HTTPHEADER => array(
                $authorizationHeader, //replace this with your authorization_token
                "cache-control: no-cache"
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        dd($response);
        print_r($response);
        $object = json_decode($response);
        // dd($object->message->requestId);
        if ($object->success == 'true') {
            $response = [
                'success' => true, 
                'apiDeliveryId' => $object->reference_no,
                'apiResponse' => 'success'
            ];
        } else {
            $response = [
                'success' => false, 
                'apiDeliveryId' => '', 
                'apiResponse' => isset($object->message) ? $object->message: 'There is an error.'
            ];
        }
        // dd($response);
        return $response;
    }
       
}
