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
use App\Models\Service;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Services\Switching\DataSwitchingService;
use App\Services\Switching\ServiceProviderResolver;
use App\Services\Transaction\CheckWalletService;
use App\Services\Transaction\DebitWalletService;
use App\Services\Transaction\ReversalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use function PHPUnit\Framework\throwException;

class DataController extends Controller
{
    //
    public function index()
    {
        $billers = Biller::where('service', 'General')->where('status', 'Active')->orderBy('id', 'asc')->get();
        return view('user.services.buy-data', [
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

    public function getNetworkId($operatorName)
    {
        if ($operatorName == 'MTN') {
            $network = '1';
        } elseif ($operatorName == 'GLO') {
            $network = '02';
        } elseif ($operatorName == '9MOBILE') {
            $network = '03';
        } elseif ($operatorName == 'AIRTEL') {
            $network = '04';
        } elseif ($operatorName == 'GOTV') {
            $network = '01';
        } elseif ($operatorName == 'DSTV') {
            $network = '02';
        }else {
            $network = '03';
        }
        return $network;
    }

    public function vendData(Request $request)
    {
        // Validation logic here (you can use Laravel validation)
        $credentials = $request->validate([
            'userID' => 'required',
            'recipient' => 'required',
            'networkID' => 'required',
            'packageID' => 'required',
            'category' => 'required',
            'amount' => 'required',
            'total' => 'required',
        ]);
        
        // check and confirm user pin
        $user = User::find($request->userID);
        if (!$user || !Hash::check($request->input('pin'), $user->pin)) {
            return response()->json(['status' => 'pin', 'message' => 'Check your pin and try again.']);
        }

        // get biller
        $biller = Biller::find($request->networkID);
        if (!$biller || $biller->status !== GeneralStatus::ACTIVE) {
            return response()->json(['status' => 'network', 'message' => 'The network is not available.']);
        }
        $billerName = $biller->title;

        // get package
        $package = Package::find($request->packageID);
        if (!$package || $package->status !== GeneralStatus::ACTIVE) {
            return response()->json(['status' => 'package', 'message' => 'The package is not available.']);
        }
        $packageName = $package->title;
        $packagePlan = $package->planID;
        $packageCategory = $package->planType ?? $request->category;
        // other params
        $reference = generateTransactionReferenceCode();
        $orderCode = generateOrderReferenceCode();
        $network = $this->getNetworkId($request->billerName);
        $service = "Data"; $type = "Data";
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
            'beneficiary' => $request->recipient,
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
        $providerKey = (new ServiceProviderResolver())->resolve($service, $biller->id, $packageCategory);
        
        // payload
        $dataPayload = array(
            "network"=> $billerName,
            "plan"=> $packagePlan,
            "beneficiary"=> $request->recipient,
            "reference"=> $orderCode,
            "ported"=> true,
        );
        try 
        {
            $delivery = (new DataSwitchingService($dataPayload, $providerKey))->run();
            
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

                return response()->json(['status' => 'success', 'message' => 'You successfully purchase Data']);
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

                return response()->json(['status' => 'pending', 'message' => 'You data purchase is pending']);
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

    public function buyData(Request $request)
    {
        // Validation logic here (you can use Laravel validation)
        $credentials = $request->validate([
            'user_id' => 'required',
            'biller' => 'required',
            'billerName' => 'required',
            'category' => 'required',
            'package' => 'required',
            'packageName' => 'required',
            'number' => 'required',
            'total' => 'required',
        ]);
        // check and confirm user pin
        $user = User::find($request->user_id);
        if (!$user || !Hash::check($request->input('pin'), $user->pin)) {
            return redirect()->route('user.buyData')->with('message', 'Check your pin and try again');
        }
        // other params
        $reference = generateTransactionReferenceCode();
        $orderCode = generateOrderReferenceCode();
        $network = $this->getNetworkId($request->billerName);
        $service = "Data"; $type = "Data";
        $today = Carbon::now();
        $note = $type . " | " . $request->packageName . " | " . $request->total;
        
        // get biller
        $biller = Biller::find($request->biller);

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
            'beneficiary' => $request->number,
            'status' => OrderStatus::INITIATED,
        ]);

        // get wallet
        $wallet = Wallet::where('userID', $request->user_id)->first();
        if(!$order && !$wallet )
        {
            return redirect()->route('user.buyData')->with('message', 'There is issue with your wallet. Contact Admin');
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
                'reference' => $reference,
                'type' => 'Debit',
                'category' => 'Purchase',
                'balanceBefore' => $balanceBefore,
                'amount' => $request->total,
                'balanceAfter' => $balanceAfter,
                'note' => $note,
                'status' => TransactionStatus::PENDING
            ]);
        }
        else
        {
            return redirect()->route('user.buyData')->with('message', 'Insufficient Funds in Your Wallet');
        }

        // default provider
        $provider = Service::where('title', $service)->first();
        if($provider && $provider->providerID)
        {
            $providerKey = $provider->provider->key;
        }
        $providerKey = $providerKey ?? 'AlrahuzData';
        
        // payload
        $dataPayload = array(
            "network"=> $request->billerName,
            "plan"=> $request->dataPlan,
            "beneficiary"=> $request->number,
            "reference"=> $orderCode,
            "ported"=> true,
        );
        try 
        {
            // $delivery = $this->dataAPI($network, $request->dataPlan ,$request->number,$orderCode);
            $delivery = (new DataSwitchingService($dataPayload, $providerKey))->run();
            // dd($delivery);
            if($delivery['status'] === GeneralStatus::SUCCESSFUL)
            {
                $order->update([
                    'responseAPI' => $delivery['responseAPI'],
                    'responseMessage' => $delivery['responseMessage'],
                    'responseBody' => $delivery['responseBody'],
                    'status' => OrderStatus::COMPLETED,
                ]);

                $transaction->update([
                    'status' => TransactionStatus::SUCCESSFUL
                ]);


                return redirect()->route('user.showOrder',[$order->reference])->with('message', 'You successfully purchase data');
            }
            elseif($delivery['status'] === GeneralStatus::PENDING || $delivery['status'] === GeneralStatus::PROCESSING)
            {
                // $maxAttempts = 4; // Maximum retries
                // $attempt = 0;
                // $pendingStatus = true;

                // while ($attempt < $maxAttempts && $pendingStatus) {
                //     $requery = $this->requeryOrder($order);

                //     if($requery['success'] === true )
                //     {
                //         $order->update([
                //             'responseAPI' => $requery['responseAPI'],
                //             'responseMessage' => $requery['responseMessage'],
                //             'responseBody' => $requery['responseBody'],
                //             'status' => OrderStatus::COMPLETED,
                //         ]);
                //         $transaction->update([
                //             'status' => TransactionStatus::SUCCESSFUL
                //         ]);

                //         return redirect()->route('user.showOrder',[$order->reference])->with('message', 'You successfully purchase data');
                //     }

                //     // Check if order is still pending
                //     if ($requery['status'] !== true) {
                //         $pendingStatus = false; // Stop retrying
                //         break;
                //     }

                //     // Sleep for 5 seconds before retrying
                //     sleep(5);
                //     $attempt++;
                // }

                $order->update([
                    'responseAPI' => $delivery['responseAPI'],
                    'responseMessage' => $delivery['responseMessage'],
                    'responseBody' => $delivery['responseBody'],
                    'status' => OrderStatus::PENDING,
                ]);

                // $balanceAfter = $wallet->mainBalance + floatval($request->total);
                // $wallet->update([
                //     'balance' => $balanceAfter,
                // ]);
                // $transaction->update([
                //     'status' => TransactionStatus::FAILED
                // ]);
                return redirect()->route('user.showOrder',[$order->reference])->with('message', 'Your order is pending');
            }
            else
            {
                $order->update([
                    'responseAPI' => $delivery['responseAPI'],
                    'responseMessage' => json_encode($delivery['responseMessage']),
                    'responseBody' => $delivery['responseBody'],
                    'status' => OrderStatus::FAILED,
                ]);

                // reversed transaction
                (new ReversalService($transaction, $user, $delivery))->run();
                // $balanceBefore = $wallet->mainBalance ;
                // $balanceAfter = $wallet->mainBalance + floatval($request->total);
                // $wallet->update([
                //     'mainBalance' => $balanceAfter,
                // ]);
                // $transaction->update([
                //     'status' => TransactionStatus::FAILED
                // ]);

                // // REVERSE THE MONEY
                // // $this->reversalProcess($order, $transaction);
                // $transaction = Transaction::create([
                //     'userID' => $user->id,
                //     'orderID' => $order->id,
                //     'walletID' => $wallet->id,
                //     'reference' => generateTransactionReferenceCode(),
                //     'provider_reference' => $reference,
                //     'type' => 'Credit',
                //     'category' => 'Reversal',
                //     'balanceBefore' => $balanceBefore,
                //     'amount' => $request->total,
                //     'balanceAfter' => $balanceAfter,
                //     'note' => 'Reversal for order: ' . $note,
                //     'status' => TransactionStatus::SUCCESSFUL
                // ]);
                
                return redirect()->route('user.buyData')->with('message', 'We cannot process your order at this time');
            }
        } catch (\Throwable $th) {
            
            $order->update([
                'responseAPI' => null,
                'responseMessage' => null,
                'responseBody' => 'try-catche error',
                'status' => OrderStatus::FAILED,
            ]);
            $balanceBefore = $wallet->mainBalance ;
            $balanceAfter = $wallet->mainBalance + floatval($request->total);
            $wallet->update([
                'mainBalance' => $balanceAfter,
            ]);
            $transaction->update([
                'status' => TransactionStatus::FAILED
            ]);

            $transaction = Transaction::create([
                'userID' => $user->id,
                'orderID' => $order->id,
                'walletID' => $wallet->id,
                'reference' => generateTransactionReferenceCode(),
                'provider_reference' => $reference,
                'type' => 'Credit',
                'category' => 'Reversal',
                'balanceBefore' => $balanceBefore,
                'amount' => $request->total,
                'balanceAfter' => $balanceAfter,
                'note' => 'Reversal for order: ' . $note,
                'status' => TransactionStatus::SUCCESSFUL
            ]);
            report($th); // Log the exception
            throw $th; // Rethrow the exception to see it in debugging
            return redirect()->route('user.buyData')->with('message', 'There is an error. Try again');
        }

    }

    // public function buyData(Request $request)
    // {
    //     // Validation logic here (you can use Laravel validation)
    //     $credentials = $request->validate([
    //         'user_id' => 'required',
    //         'biller' => 'required',
    //         'billerName' => 'required',
    //         'category' => 'required',
    //         'package' => 'required',
    //         'packageName' => 'required',
    //         'number' => 'required',
    //         'total' => 'required',
    //     ]);

    //     // check and confirm user pin
    //     $user = User::find($request->user_id);
    //     if (!$user || !Hash::check($request->input('pin'), $user->pin)) {
    //         return redirect()->route('user.buyData')->with('message', 'Check your pin and try again');
    //     }
    //     // other params
    //     $reference = generateTransactionReferenceCode();
    //     $orderCode = generateOrderReferenceCode();
    //     $network = $this->getNetworkId($request->billerName);
    //     $service = "Data"; $type = "Data";
    //     $today = Carbon::now();
    //     $note = $type . " | " . $request->packageName . " | " . $request->total;
        
    //     // get biller
    //     $biller = Biller::find($request->biller);

    //     // Create a new order
    //     $order = Order::create([
    //         'userID' => $request->user_id,
    //         'billerID' => $request->biller,
    //         'packageID' => $request->package,
    //         'service' => $service,
    //         'reference' => $orderCode,
    //         'price' => $request->total,
    //         'quantity' => '1',
    //         'total' => $request->total,
    //         'beneficiary' => $request->number,
    //         'status' => OrderStatus::INITIATED,
    //     ]);

    //     // get wallet
    //     $wallet = Wallet::where('userID', $request->user_id)->first();
    //     if(!$order && !$wallet )
    //     {
    //         return redirect()->route('user.buyData')->with('message', 'There is issue with your wallet. Contact Admin');
    //     }

    //     // confirm the available funds and make pending transaction
    //     if($wallet->mainBalance >= $request->total)
    //     {
    //         $balanceBefore = $wallet->mainBalance;
    //         $balanceAfter = $wallet->mainBalance - floatval($request->total);
    //         $wallet->mainBalance = $balanceAfter;
    //         $wallet->save();
    //         // new transaction
    //         $transaction = Transaction::create([
    //             'userID' => $user->id,
    //             'orderID' => $order->id,
    //             'walletID' => $wallet->id,
    //             'reference' => $reference,
    //             'type' => 'Debit',
    //             'category' => 'Purchase',
    //             'balanceBefore' => $balanceBefore,
    //             'amount' => $request->total,
    //             'balanceAfter' => $balanceAfter,
    //             'note' => $note,
    //             'status' => TransactionStatus::PENDING
    //         ]);
    //     }
    //     else
    //     {
    //         return redirect()->route('user.buyData')->with('message', 'Insufficient Funds in Your Wallet');
    //     }

    //     try 
    //     {
    //         $delivery = $this->dataAPI($network, $request->dataPlan ,$request->number,$orderCode);
    //         // dd($delivery);
    //         if($delivery['success'] === true)
    //         {
    //             $order->update([
    //                 'responseAPI' => $delivery['apiDeliveryId'],
    //                 'responseMessage' => $delivery['apiResponse'],
    //                 'responseBody' => $delivery['response'],
    //                 'status' => OrderStatus::COMPLETED,
    //             ]);

    //             $transaction->update([
    //                 'status' => TransactionStatus::SUCCESSFUL
    //             ]);


    //             return redirect()->route('user.showOrder',[$order->reference])->with('message', 'You successfully purchase data');
    //         }
    //         elseif($delivery['success'] === 'pending')
    //         {
    //             $maxAttempts = 4; // Maximum retries
    //             $attempt = 0;
    //             $pendingStatus = true;

    //             while ($attempt < $maxAttempts && $pendingStatus) {
    //                 $requery = $this->requeryOrder($order);

    //                 if($requery['success'] === true )
    //                 {
    //                     $order->update([
    //                         'responseAPI' => $requery['apiDeliveryId'],
    //                         'responseMessage' => $requery['apiResponse'],
    //                         'responseBody' => $requery['response'],
    //                         'status' => OrderStatus::COMPLETED,
    //                     ]);
    //                     $transaction->update([
    //                         'status' => TransactionStatus::SUCCESSFUL
    //                     ]);

    //                     return redirect()->route('user.showOrder',[$order->reference])->with('message', 'You successfully purchase data');
    //                 }

    //                 // Check if order is still pending
    //                 if ($requery['status'] !== true) {
    //                     $pendingStatus = false; // Stop retrying
    //                     break;
    //                 }

    //                 // Sleep for 5 seconds before retrying
    //                 sleep(5);
    //                 $attempt++;
    //             }
                    
    //             $order->update([
    //                 'responseAPI' => $delivery['apiDeliveryId'],
    //                 'responseMessage' => $delivery['apiResponse'],
    //                 'responseBody' => $delivery['response'],
    //                 'status' => OrderStatus::PENDING,
    //             ]);

    //             // $balanceAfter = $wallet->mainBalance + floatval($request->total);
    //             // $wallet->update([
    //             //     'balance' => $balanceAfter,
    //             // ]);
    //             // $transaction->update([
    //             //     'status' => TransactionStatus::FAILED
    //             // ]);
    //             return redirect()->route('user.showOrder',[$order->reference])->with('message', 'Your order is pending');
    //         }
    //         else
    //         {
    //             $order->update([
    //                 'responseAPI' => $delivery['apiDeliveryId'],
    //                 'responseMessage' => json_encode($delivery['apiResponse']),
    //                 'responseBody' => $delivery['response'],
    //                 'status' => OrderStatus::FAILED,
    //             ]);
    //             $balanceBefore = $wallet->mainBalance ;
    //             $balanceAfter = $wallet->mainBalance + floatval($request->total);
    //             $wallet->update([
    //                 'mainBalance' => $balanceAfter,
    //             ]);
    //             $transaction->update([
    //                 'status' => TransactionStatus::FAILED
    //             ]);

    //             // REVERSE THE MONEY
    //             // $this->reversalProcess($order, $transaction);
    //             $transaction = Transaction::create([
    //                 'userID' => $user->id,
    //                 'orderID' => $order->id,
    //                 'walletID' => $wallet->id,
    //                 'reference' => generateTransactionReferenceCode(),
    //                 'provider_reference' => $reference,
    //                 'type' => 'Credit',
    //                 'category' => 'Reversal',
    //                 'balanceBefore' => $balanceBefore,
    //                 'amount' => $request->total,
    //                 'balanceAfter' => $balanceAfter,
    //                 'note' => 'Reversal for order: ' . $note,
    //                 'status' => TransactionStatus::SUCCESSFUL
    //             ]);
                
    //             return redirect()->route('user.buyData')->with('message', 'We cannot process your order at this time');
    //         }
    //     } catch (\Throwable $th) {
            
    //         $order->update([
    //             'responseAPI' => null,
    //             'responseMessage' => null,
    //             'responseBody' => 'try-catche error',
    //             'status' => OrderStatus::FAILED,
    //         ]);
    //         $balanceBefore = $wallet->mainBalance ;
    //         $balanceAfter = $wallet->mainBalance + floatval($request->total);
    //         $wallet->update([
    //             'mainBalance' => $balanceAfter,
    //         ]);
    //         $transaction->update([
    //             'status' => TransactionStatus::FAILED
    //         ]);

    //         $transaction = Transaction::create([
    //             'userID' => $user->id,
    //             'orderID' => $order->id,
    //             'walletID' => $wallet->id,
    //             'reference' => generateTransactionReferenceCode(),
    //             'provider_reference' => $reference,
    //             'type' => 'Credit',
    //             'category' => 'Reversal',
    //             'balanceBefore' => $balanceBefore,
    //             'amount' => $request->total,
    //             'balanceAfter' => $balanceAfter,
    //             'note' => 'Reversal for order: ' . $note,
    //             'status' => TransactionStatus::SUCCESSFUL
    //         ]);
    //         report($th); // Log the exception
    //         throw $th; // Rethrow the exception to see it in debugging
    //         return redirect()->route('user.buyData')->with('message', 'There is an error. Try again');
    //     }

    // }


    private function dataAPI($operator, $dataPlan, $recipient, $reference)
    {
        $data = array(
            "network" => $operator,
            "mobile_number" => $recipient,
            "plan" => $dataPlan,
            "Ported_number" => true
        );
        $authorizationHeader = 'Authorization: Token ' . env('ALRAHUZ_API_KEY');

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://alrahuzdata.com.ng/api/data/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                $authorizationHeader,
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $obj = json_decode($response);
        if (property_exists($obj, 'Status')) {
            if ($obj->Status == 'successful') {
                $responseData = [
                    'success' => true, 
                    'apiDeliveryId' => $obj->id, 
                    'apiResponse' => $obj->api_response,
                    'response' => $response
                ];
            } else {
                $responseData = [
                    'success' => 'pending', 
                    'apiDeliveryId' => $obj->id, 
                    'apiResponse' => 'There is an error. Try again later or contact support.',
                    'response' => $response
                ];
            }
            return $responseData;
        } else {
            $response3 = [
                'success' => false, 
                'apiDeliveryId' => null, 
                'apiResponse' => $obj,
                'response' => $response
            ];
            // return $response3;
            return $response3;
        }
    }

    
    public function requeryOrder($order)
    {
        $authorizationHeader = 'Authorization: Token ' . env('ALRAHUZ_API_KEY');

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://alrahuzdata.com.ng/api/data/" . $order->responseAPI,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                $authorizationHeader,
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $obj = json_decode($response);
        if (property_exists($obj, 'Status')) {
            if ($obj->Status == 'successful') {
                $responseData = [
                    'success' => true, 
                    'apiDeliveryId' => $obj->id, 
                    'apiResponse' => $obj->api_response,
                    'response' => $response
                ];
            } else {
                $responseData = [
                    'success' => 'pending', 
                    'apiDeliveryId' => $obj->id, 
                    'apiResponse' => 'There is an error. Try again later or contact support.',
                    'response' => $response
                ];
            }
            return $responseData;
        } else {
            $response3 = [
                'success' => false, 
                'apiDeliveryId' => null, 
                'apiResponse' => $obj,
                'response' => $response
            ];
            // return $response3;
            return $response3;
        }
    }
}
