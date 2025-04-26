<?php

namespace App\Http\Controllers\user;

use App\Enum\GeneralStatus;
use App\Enum\OrderStatus;
use App\Enum\TransactionStatus;
use App\Http\Controllers\Controller;
use App\Models\Biller;
use App\Models\EnergyCustomer;
use App\Models\User;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Services\EasyAccessAPI\VerifyMeterService;
use App\Services\Switching\ElectricitySwitchingService;
use App\Services\Switching\ServiceProviderResolver;
use App\Services\Transaction\CheckWalletService;
use App\Services\Transaction\DebitWalletService;
use App\Services\Transaction\ReversalService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class ElectricityController extends Controller
{
    //
    public function index()
    {
        $orders = Order::whereNotNull('status')->where('service', 'Electricity')->orderBy('id', 'desc')->get();
        return view('user.buy-electricity', [
            'orders' => $orders,
        ]);
    }

    public function getDISCO($disco)
    {
        if($disco == '01'){ $discoName = 'Eko Electricity';}
        elseif($disco == '02'){ $discoName = 'Ikeja Electricity';}
        elseif($disco == '03'){ $discoName = 'PortHarcourt Electricity';}
        elseif($disco == '04'){ $discoName = 'Kaduna Electricity';}
        elseif($disco == '05'){ $discoName = 'Abuja Electricity';}
        elseif($disco == '06'){ $discoName = 'Ibadan Electricity';}
        elseif($disco == '07'){ $discoName = 'Kano Electricity';}
        elseif($disco == '08'){ $discoName = 'Jos Electricity';}
        elseif($disco == '09'){ $discoName = 'Enugu Electricity';}
        else{ $discoName = 'Benin Electricity';}
        return $discoName;
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

    public function verifyMeter(Request $request)
    {
        // Validation logic here (you can use Laravel validation)
        $credentials = $request->validate([
            'biller' => 'required',
            'meter' => 'required',
            'type' => 'required',
            'amount' => 'required',
        ]);

        // get biller
        $biller = Biller::find($request->biller);
        if (!$biller || $biller->status !== GeneralStatus::ACTIVE) {
            return response()->json(['status' => 'network', 'message' => 'The disco is not available.']);
        }
        $discoVariation = $biller->variation;

        // check existing record 
        $existingCustomer = EnergyCustomer::where([
            ['meterNumber', '=', $request->meter],
            ['disco', '=', $biller->title],
            ['meterType', '=', $request->type],
        ])
        ->orderByRaw('meterNumber = ? DESC', [$request->meter]) // prioritize exact match
        ->first();
        
        if(!$existingCustomer)
        {
            $payload = [
                'company' => $discoVariation,
                'metertype' => $request->type == "PREPAID" ? 01 : 02,
                'meterno' =>$request->meter,
                'amount' => $request->amount,
            ];

            $response = (new VerifyMeterService())->run($payload);
            if($response && $response['status'] = true)
            {
                $customer = EnergyCustomer::create([
                    "userID" => auth('web')->user()->id,
                    "name"=> $response['data']['name'],
                    "meterNumber"=> $request->meter,
                    "meterType"=> $request->type,
                    "disco"=> $biller->title,
                    "address"=> $response['data']['address'],
                    "status"=> GeneralStatus::ACTIVE,
                ]);

                return response()->json(['status' => 'success', 'message' => 'meter verified successfully', 'customer_name' => $response['data']['name']]);
            }else{
                return response()->json(['status' => 'failed', 'message' => 'cannot verify meter number']);
            }
        }
        else
        {
            return response()->json(['status' => 'success', 'message' => 'meter verified successfully', 'customer_name' => $existingCustomer->name]);
        }
    }

    public function buyElectricity(Request $request)
    {
        // Validation logic here (you can use Laravel validation)
        $credentials = $request->validate([
            'user_id' => 'required',
            'disco' => 'required',
            'meterType' => 'required',
            'meterNo' => 'required',
            'amount' => 'required',
            'total' => 'required',
            'package' => 'required',
            'packageName' => 'required',
            'pin' => 'required',
        ]);
        // dd($request);
        $orderCode = $this->generateOrderCode();
        $discoName = $this->getDISCO($request->disco);
        $service = "Electricity";
        $type = "Electricity";
        $today = Carbon::now();
        $orderNote = $type . "|" . $request->packageName . "|" . $request->total;
        $tranNote = $request->packageName . "|" . $request->total;
        // check pin
        $user = User::find($request->user_id);
        if ($user && Hash::check($request->input('pin'), $user->pin)) 
        {
            // Create a new order
            $order = Order::create([
                'user_id' => $request->user_id,
                'package_id' => $request->package,
                'service' => $service,
                'orderCode' => $orderCode,
                'price' => $request->amount,
                'quantity' => '1',
                'total' => $request->total,
                'tokenType' => $request->meterType == '01'?'PrePaid': 'PostPaid',
                'tokenMeterNo' => $request->meterNo,
                'biller' => $discoName,
                'status' => 'Active',
            ]);
            // dd($order);
            if($order)
            {
                $wallet = Wallet::where('user_id', $request->user_id)->first();
                if($wallet && ($wallet->balance >= $request->total))
                {
                    $balanceBefore = $wallet->balance;
                    $balanceAfter = $wallet->balance - floatval($request->total);
                    $wallet->balance = $balanceAfter;
                    if($wallet->save())
                    {
                        $transaction = Transaction::create([
                            'user_id' => $request->user_id,
                            'order_id' => $order->id,
                            'wallet_id' => auth()->user()->wallet->id,
                            'type' => 'Debit',
                            'category' => 'Purchase',
                            'balanceBefore' => $balanceBefore,
                            'amount' => $request->total,
                            'balanceAfter' => $balanceAfter,
                            'note' => $tranNote,
                            'status' => 'Active',
                        ]);
                        if ($transaction) {
                            $delivery = $this->ElectricityAPI($request->disco, $request->meterType , $request->meterNo, $request->total);
                            if($delivery['success'] === true)
                            {
                                $order->update([
                                    'apiDeliveryId' => $delivery['apiDeliveryId'],
                                    'apiResponse' => $delivery['apiResponse'],
                                    'token' => $delivery['token'],
                                    'status' => 'Completed',
                                ]);
                                // return redirect()->route('user.buyElectricity')
                                return redirect()->route('user.showOrder',[$order->id])->with('message', 'You successfully purchase electricity token');
                            }
                            else
                            {
                                $order->update([
                                    'apiDeliveryId' => $delivery['apiDeliveryId'],
                                    'apiResponse' => $delivery['apiResponse'],
                                    'status' => 'Failed',
                                ]);
                                $balanceBefore = $wallet->balance;
                                $balanceAfter = $wallet->balance + floatval($request->total);
                                $wallet->update([
                                    'balance' => $balanceAfter,
                                ]);
                                $transaction = Transaction::create([
                                    'user_id' => $request->user_id,
                                    'order_id' => $order->id,
                                    'wallet_id' => auth()->user()->wallet->id,
                                    'type' => 'Credit',
                                    'balanceBefore' => $balanceBefore,
                                    'amount' => $request->total,
                                    'balanceAfter' => $balanceAfter,
                                    'note' => `Refund for order: $orderCode`,
                                    'status' => 'Active',
                                ]);
                                return redirect()->route('user.buyElectricity')->with('message', 'We cannot process your order at this time');
                            }
                        }else{
                            return redirect()->route('user.buyElectricity')->with('message', 'We cannot perform transaction on your wallet');
                        }
                    }
                    else
                    {
                        return redirect()->route('user.buyElectricity')->with('message', 'We cannot deduct your wallet at this time');
                    }
                }
                else
                {
                    return redirect()->route('user.buyElectricity')->with('message', 'Check your wallet balance.');
                }
            }
            else
            {
                return redirect()->route('user.buyElectricity')->with('message', 'We cannot process your order at this time');
            }
        }
        else
        {
            return redirect()->route('user.buyElectricity')->with('message', 'Check your pin and try again');        
        }
    }

    public function ElectricityAPI($disco, $meterType, $meterNo, $total)
    {
        $jsonData = '{
            "success": "true",
            "message": {
                "code": "000",
                "content": {
                    "transactions": {
                        "status": "delivered",
                        "product_name": "PHED - Port Harcourt Electric",
                        "unique_element": "95300270972",
                        "unit_price": 1000,
                        "quantity": 1,
                        "channel": "api",
                        "discount": null,
                        "type": "Electricity Bill",
                        "convenience_fee": 0,
                        "method": "api",
                        "transactionId": "1610774875299"
                    }
                },
                "response_description": "TRANSACTION SUCCESSFUL",
                "requestId": "ID3985745754",
                "amount": "1000.00",
                "transaction_date": {
                    "date": "2021-01-16 06:27:55.000000",
                    "timezone_type": 3,
                    "timezone": "Africa\/Lagos"
                },
                "purchased_code": "Token : 57880316552890870667",
                "meterNumber": "95300270972",
                "customerName": "IBANGA GATIUS",
                "customerNumber": "95300270972",
                "address": null,
                "token": "23232316552890870667",
                "tokenAmount": "100",
                "tokenValue": "100",
                "receiptNumber": "95300270972",
                "units": "31.8",
                "tariff": "30.23000",
                "energyAmount": null,
                "energyVAT": null
            }
        }';

        $jsonData2 = '{
            "success": "false",
            "message": "Amount Too Low, Minimum Amount is N1000"
        }';
        $authorizationHeader = 'AuthorizationToken: ' . env('EASYACCESS_API_KEY');
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://easyaccessapi.com.ng/api/payelectricity.php",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array(
                'company' => $disco,
                'metertype' => $meterType,
                'meterno' => $meterNo,
                'amount' => $total,
            ),
            CURLOPT_HTTPHEADER => array(
                $authorizationHeader, //replace this with your authorization_token
                "cache-control: no-cache"
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        // echo $response;
        $object = json_decode($response);
        // dd($object->message->requestId);
        if ($object->success == 'true') {
            $response = [
                'success' => true, 
                'token' => $object->message->token, 
                'apiDeliveryId' => $object->message->content->transactions->transactionId,
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
    
    public function vendElectricity(Request $request)
    {
        // Validation logic here (you can use Laravel validation)
        $credentials = $request->validate([
            'userID' => 'required',
            'biller' => 'required',
            'type' => 'required',
            'meter' => 'required',
            'customer' => 'required',
            'amount' => 'required',
            'total' => 'required',
            'pin' => 'required',
        ]);

        // check and confirm user pin
        $user = User::find($request->userID);
        if (!$user || !Hash::check($request->input('pin'), $user->pin)) {
            return response()->json(['status' => 'pin', 'message' => 'Check your pin and try again.']);
        }

        // get biller
        $biller = Biller::find($request->biller);
        if (!$biller || $biller->status !== GeneralStatus::ACTIVE) {
            return response()->json(['status' => 'network', 'message' => 'The disco is not available.']);
        }
        $billerName = $biller->title;
        $billerVariation = $biller->variation;

        // other params
        $reference = generateTransactionReferenceCode();
        $orderCode = generateOrderReferenceCode();
        $service = "Electricity";
        $type = "Electricity";
        $today = Carbon::now();
        $note = $type . " | " . $service . " | " . $billerName . " | " . $request->total;

        // Create a new order
        $order = Order::create([
            'userID' => $request->userID,
            'billerID' => $biller->id,
            'packageID' => null,
            'service' => $service,
            'reference' => $orderCode,
            'price' => $request->amount,
            'quantity' => '1',
            'total' => $request->total,
            'meterType' => $request->type,
            'meterNumber' => $request->meter,
            'meterName' => $request->customer,
            'status' => OrderStatus::INITIATED,
        ]);

        // // get wallet
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
        $providerKey = (new ServiceProviderResolver())->resolve($service, $biller->id);
        
        // payload
        $payload = array(
            'company' => $billerVariation,
            'type' => $request->type,
            'meter' => $request->meter,
            'amount' => $request->amount,
        );

        try 
        {
            $delivery = (new ElectricitySwitchingService($payload, $providerKey))->run();
            
            if($delivery['status'] === GeneralStatus::SUCCESSFUL)
            {
                $order->update([
                    'responseAPI' => $delivery['responseAPI'],
                    'responseMessage' => $delivery['responseMessage'],
                    'responseBody' => json_encode($delivery['responseBody']),
                    'provider' => $delivery['provider'],
                    'token' => $delivery['data']['token'],
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

                return response()->json(['status' => 'pending', 'message' => `You $service purchase is pending`]);
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
}
