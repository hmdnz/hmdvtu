<?php

namespace App\Http\Controllers\user;
use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\Wallet;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    //
    public function index()
    {
        $orders = Order::whereNotNull('status')
                    ->where('userID', auth()->user()->id)
                    ->orderBy('id', 'desc')->get();
                    
        return view('user.orders.orders', [
            'orders' => $orders,
        ]);
    }

    public function showOrder(Request $request, $id)
    {
        $order =  Order::withReference($id)->first();
        if(!$order || $order->status == null || $order->userID !== auth()->user()->id)
        {
            return response()->back()->with('message', 'Order not found');
        }

        return view('user.orders.order', [
            'order' => $order,
        ]);
    }

    public function verifyOrder(Order $order)
    {
        $authorizationHeader = 'Authorization: Token ' . env('ALRAHUZ_API_KEY');
        if($order->service == 'Data')
        {$url = "https://alrahuzdata.com.ng/api/data/" . $order->responseAPI;}
        else
        {$url = "https://alrahuzdata.com.ng/api/topup/" . $order->responseAPI;}

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
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
        // dd($response);
        curl_close($curl);
        $obj = json_decode($response);
        if (property_exists($obj, 'Status')) {
            if ($obj->Status == 'successful') {
                $order->update([
                    "status" => "completed",
                    "responseAPI" => $obj->id,
                    'responseMessage' => $obj->api_response,
                    'responseBody' => $response
                ]);

                return redirect()->route('user.showOrder', [$order->id])->with('message', 'Your order is successful and delivered');
            }elseif ($obj->Status == 'failed') {
                if($order->status == 'pending')
                {
                    $wallet = Wallet::where('userID', $order->userID)->first();
                    $balanceBefore = $wallet->mainBalance;
                    $balanceAfter = $wallet->mainBalance + floatval($order->total);
                    $wallet->update([
                        "mainBalance" => floatval($order->total)
                    ]);
                }

                $order->update([
                    "status" => "failed",
                    "responseAPI" => $obj->id,
                    'responseMessage' => $obj->api_response,
                    'responseBody' => $response
                ]);
                return redirect()->route('user.showOrder', [$order->id])->with('message', 'your order failed. Try again');
            } else {
                $order->update([
                    "status" => "failed",
                    "responseAPI" => $obj->id,
                    'responseMessage' => $obj->api_response,
                    'responseBody' => $response
                ]);

                return redirect()->route('user.showOrder', [$order->id])->with('message', 'your order failed. Try again');
            }
            return $responseData;
        } else {
            $order->update([
                "status" => "unknown",
                "responseAPI" => null,
                'responseMessage' => $obj,
                'responseBody' => $response
            ]);

            return redirect()->route('user.showOrder', [$order->id])->with('message', 'your order is undelivered. Try again');
        }
    }
}
