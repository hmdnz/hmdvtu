<?php

namespace App\Http\Controllers\user;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Biller;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DataCardController extends Controller
{
    //
    public function index()
    {
        $billers = Biller::where('status', 'Active')->orderBy('id', 'asc')->get();
        $orders = Order::whereNotNull('status')
                    ->where('service', 'Data Card')
                    ->where('user_id', auth()->user()->id)
                    ->orderBy('id', 'desc')->get();
        return view('user.buy-datacard', [
            'billers' => $billers,
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

    public function buyDataCard(Request $request)
    {
        // Validation logic here (you can use Laravel validation)
        $credentials = $request->validate([
            'user_id' => 'required',
            'biller' => 'required',
            'billerName' => 'required',
            'dataPlan' => 'required',
            'package' => 'required',
            'packageName' => 'required',
            'quantity' => 'required',
            'total' => 'required',
            'pin' => 'required',
        ]);
        $orderCode = $this->generateOrderCode();
        $network = $this->getNetworkId($request->billerName);
        $service = "Data Card";
        $type = "Data Card";
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
                'biller_id' => $request->biller,
                'package_id' => $request->package,
                'service' => $service,
                'orderCode' => $orderCode,
                'price' => $request->total,
                'quantity' => '1',
                'total' => $request->total,
                'beneficiary' => $request->number,
                'status' => 'Active',
            ]);
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
                            'balanceBefore' => $balanceBefore,
                            'amount' => $request->total,
                            'balanceAfter' => $balanceAfter,
                            'note' => $tranNote,
                            'status' => 'Active',
                        ]);
                        if ($transaction) {
                            $biller = Biller::find($request->biller);
                            // var_dump($biller->variation);
                            $delivery = $this->dataCardAPI($network, $request->dataPlan ,$request->quantity);
                            if($delivery['success'] === true)
                            {
                                $order->update([
                                    'apiDeliveryId' => $delivery['apiDeliveryId'],
                                    'apiResponse' => $delivery['apiResponse'],
                                    'status' => 'Completed',
                                ]);
                                // return redirect()->route('user.buyDataCard')
                                return redirect()->route('user.showOrder',[$order->id])->with('message', 'You successfully purchase data');
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
                                return redirect()->route('user.buyDataCard')->with('message', 'We cannot process your order at this time');
                            }
                        }else{
                            return redirect()->route('user.buyDataCard')->with('message', 'We cannot perform transaction on your wallet');
                        }
                    }
                    else
                    {
                        return redirect()->route('user.buyDataCard')->with('message', 'We cannot deduct your wallet at this time');
                    }
                }
                else
                {
                    return redirect()->route('user.buyDataCard')->with('message', 'There is issue with your wallet. Contact Admin');
                }
            }
            else
            {
                return redirect()->route('user.buyDataCard')->with('message', 'We cannot process your order at this time');
            }
        }
        else
        {
            return redirect()->route('user.buyDataCard')->with('message', 'Check your pin and try again');        
        }
    }

    private function dataCardAPI($network, $dataPlan, $quantity)
    {
        $data = array(
            "plan" => $dataPlan,
            "quantity" => $quantity, 
            "name_on_card" => "AA Rasheed Data"
        );
        $authorizationHeader = 'Authorization: Token ' . env('ALRAHUZ_API_KEY');

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://alrahuzdata.com.ng/api/data-card/",
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
        // echo $response;
        // dd($response);
        $obj = json_decode($response);
        if (property_exists($obj, 'Status')) {
            if ($obj->Status == 'successful') {
                // $response2 = array('success' => true, 'apiDeliveryId' => $obj->id, 'apiResponse' => $obj->api_response); 
                $response2 = array('success' => true, 'apiDeliveryId' => $obj->id, 'apiResponse' => $obj->api_response, 'pin' => $obj->pin, 'product' => 'DataCard ' . $dataPlan); 
                $response = [
                    'success' => true, 
                    'apiDeliveryId' => $obj->id, 
                    'apiResponse' => $obj->api_response,
                    'pin' => $obj->pin,
                ];
            } else {
                // $response2 = array('success' => false, 'apiDeliveryId' => $obj->id, 'apiResponse' => 'There is an error. Try again later or contact support.');
                $response = [
                    'success' => false, 
                    'apiDeliveryId' => $obj->id, 
                    'apiResponse' => 'There is an error. Try again later or contact support.'
                ];
            }
            return $response;
        } else {
            // var_dump($obj);
            $response3 = [
                'success' => false, 
                'apiDeliveryId' => null, 
                'apiResponse' => $obj->error[0],
            ];
            // return $response3;
            return $response3;
        }
    }
}
