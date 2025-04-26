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
use App\Services\AlrahuzData\AirtimeService;
use App\Services\SMEPlug\AirtimeService as SMEPlugAirtimeService;
use App\Services\Switching\AirtimeSwitchingService;
use App\Services\Switching\ServiceProviderResolver;
use App\Services\Transaction\CheckWalletService;
use App\Services\Transaction\DebitWalletService;
use App\Services\Transaction\ReversalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AirtimeController extends Controller
{
    //
    public function index()
    {
        $billers = Biller::where('service', 'General')->where('status', 'Active')->orderBy('id', 'asc')->get();
        return view('user.services.buy-airtime', [
            'billers' => $billers,
        ]);
    }

    function generateOrderCode()
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

    public function vendAirtime(Request $request)
    {
        // validation 
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

        // other params
        $reference = generateTransactionReferenceCode();
        $orderCode = generateOrderReferenceCode();
        $service = "Airtime";  $type = "Airtime";
        $today = Carbon::now();
        $note = $type . " | " . $packageName . " | " . $request->amount;

        // Create a new order
        $order = Order::create([
            'userID' => $request->userID,
            'billerID' => $biller->id,
            'packageID' => $package->id,
            'service' => $service,
            'reference' => $orderCode,
            'price' => $request->amount,
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

        // get default provider
        $providerKey = (new ServiceProviderResolver())->resolve($service, $biller->id);

        // construct airtime payload
        $airtimePayload = array(
            "operator" => $billerName,
            "recipient" => $request->recipient,
            "amount" => $request->amount,
            "type" => 'VTU',
            "ported" => true,
            "reference" => $orderCode
        );

        try {
            
            $response = (new AirtimeSwitchingService($airtimePayload, $providerKey))->run();

            if($response['status'] === GeneralStatus::SUCCESSFUL)
            {
                $order->update([
                    'responseAPI' => $response['responseAPI'],
                    'responseMessage' => $response['responseMessage'],
                    'responseBody' => json_encode($response['responseBody']),
                    'provider' => $response['provider'],
                    'status' => OrderStatus::COMPLETED,
                ]);

                $transaction->update([
                    'status' => TransactionStatus::SUCCESSFUL
                ]);

                return response()->json(['status' => 'success', 'message' => 'You successfully purchase Airtime']);
            }
            elseif($response['status'] === GeneralStatus::PENDING || $response['status'] === GeneralStatus::PROCESSING)
            {
                $order->update([
                    'responseAPI' => $response['responseAPI'],
                    'responseMessage' => $response['responseMessage'],
                    'responseBody' => json_encode($response['responseBody']),
                    'provider' => $response['provider'],
                    'status' => OrderStatus::PENDING,
                ]);
                return response()->json(['status' => 'pending', 'message' => 'You airtime purchase is pending']);
            }
            else
            {
                
                $order->update([
                    'responseAPI' => $response['responseAPI'],
                    'responseMessage' => json_encode($response['responseMessage']),
                    'responseBody' => json_encode($response['responseBody']),
                    'provider' => $response['provider'],
                    'status' => OrderStatus::FAILED,
                ]);
                
                // reversed transaction
                (new ReversalService($transaction, $user, $response))->run();

                return response()->json(['status' => 'failed', 'message' => 'We cannot process your order at this time']);
            }
        } 
        catch (\Throwable $th) 
        {
            $order->update([
                'responseAPI' => null,
                'responseMessage' => $th,
                'responseBody' => $th,
                'provider' => $providerKey,
                'status' => OrderStatus::FAILED,
            ]); 
            
            // reversed transaction
            (new ReversalService($transaction, $user, []))->run();
            // Log the exception
            report($th); 

            return response()->json(['status' => 'failed', 'message' => 'There is an error. Try again']);
        }
    }

    public function buyAirtime(Request $request)
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
            'amount' => 'required',
            'total' => 'required',
        ]);
        // check and confirm user pin
        $user = User::find($request->user_id);
        if (!$user || !Hash::check($request->input('pin'), $user->pin)) {
            return redirect()->route('user.buyAirtime')->with('message', 'Check your pin and try again');
        }
        // other params
        $reference = generateTransactionReferenceCode();
        $orderCode = $this->generateOrderCode();
        $service = "Airtime"; $type = "Airtime";
        $today = Carbon::now();
        $note = $type . " | " . $request->packageName . " | " . $request->amount;
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
            return redirect()->route('user.buyAirtime')->with('message', 'There is issue with your wallet. Contact Admin');
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
            return redirect()->route('user.buyAirtime')->with('message', 'Insufficient Funds in Your Wallet');
        }

        try {
            $delivery = $this->airtimeAPI($biller->variation, $request->amount,$request->number,$request->category,$orderCode);
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

                return redirect()->route('user.showOrder',[$order->reference])->with('message', 'You successfully purchase Airtime');
            }
            elseif($delivery['success'] === 'pending')
            {
                // $requery = $this->requeryOrder($order);
                // $order->update([
                //     'responseAPI' => $requery['apiDeliveryId'],
                //     'responseMessage' => $requery['apiResponse'],
                //     'responseBody' => $requery['response'],
                //     'status' => OrderStatus::PENDING,
                // ]);
                $order->update([
                    'responseAPI' => $delivery['apiDeliveryId'],
                    'responseMessage' => $delivery['apiResponse'],
                    'responseBody' => $delivery['response'],
                    'status' => OrderStatus::PENDING,
                ]);
                return redirect()->route('user.showOrder',[$order->reference])->with('message', 'You successfully purchase Airtime');
            }
            else
            {
                $order->update([
                    'responseAPI' => $delivery['apiDeliveryId'],
                    'responseMessage' => json_encode($delivery['apiResponse']),
                    'responseBody' => $delivery['response'],
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
                return redirect()->route('user.buyAirtime')->with('message', 'We cannot process your order at this time');
            }
        } 
        catch (\Throwable $th) 
        {
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
            return redirect()->route('user.buyAirtime')->with('message', 'There is an error. Try again');
        }
    }

    public function airtimeAPI($operator, $amount, $recipient, $airtimeType, $reference)
    {
        $data = array(
            "network" => $operator,
            'amount' => $amount,
            "mobile_number" => $recipient,
            "Ported_number" => true,
            'airtime_type' => $airtimeType
        );

        $authorizationHeader = 'Authorization: Token ' . env('ALRAHUZ_API_KEY');

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://alrahuzdata.com.ng/api/topup/",
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
        dd($response);
        $obj = json_decode($response);
        if (property_exists($obj, 'Status')) {
            if ($obj->Status == 'successful') 
            {
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
            return $response3;
        }
    }

    public function requeryOrder($order)
    {
        $authorizationHeader = 'Authorization: Token ' . env('ALRAHUZ_API_KEY');

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://alrahuzdata.com.ng/api/topup/" . $order->responseAPI,
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
