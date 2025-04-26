<?php

namespace App\Http\Controllers\admin;

use App\Enum\GeneralStatus;
use App\Enum\OrderStatus;
use App\Enum\TransactionStatus;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Services\Switching\RequerySwitchingService;
use App\Services\Transaction\ReversalService;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class AdminOrdersController extends Controller
{
    //

    public function historyOrders(Request $request)
    {
        $orders = Order::whereNotNull('status')
                    ->orderBy('id', 'desc')->get();                    
        $ordersCount = Order::whereNotNull('status')
                    ->get()->count();
        return view('admin.history.orders', [
            'orders' => $orders,
            'orderCounts' => $ordersCount,
        ]);
    }

    public function showOrder(Order $order)
    {
        return view('admin.history.order', [
            'order' => $order,
        ]);
    }

    public function requeryOrder(Request $request, $reference)
    {
        // get order
        $order =  Order::withReference($reference)->first();
        if (!$order) {
            return response()->json(['status' => false, 'code' => 'Unknown', 'message' => 'Order not found'], 404);
        }
        // get transaction
        $transaction = Transaction::where('orderID', $order->id)->first();
        // get user
        $user = User::where('id', $order->userID)->first();
        // get provider reference and provider
        $provider_reference = $order->responseAPI ?? $order->reference;
        $provider = $order->provider;
        $service = $order->service;

        // Initiated order without provider reference
        if($order->status == OrderStatus::INITIATED && (isEmpty($order->responseAPI) || isNull($order->responseAPI) || $order->responseAPI == "" ))
        {
            // update order
            $order->update([
                'responseAPI' => null,
                'responseMessage' => null,
                'responseBody' => 'try-catche error',
                'status' => OrderStatus::FAILED,
            ]);
            // reverse transaction
            (new ReversalService($transaction, $user, []))->run();

            return response()->json(['status' => false, 'code' => OrderStatus::FAILED, 'message' => 'This order is failed and it is reversed'], 200);
        }

        try {
            $response = (new RequerySwitchingService($provider_reference, $provider, $service))->run();
            if($response)
            {
                if($response['status'] === GeneralStatus::SUCCESSFUL)
                {
                    $order->update([
                        'responseAPI' => $response['responseAPI'],
                        'responseMessage' => $response['responseMessage'],
                        'responseBody' => json_encode($response['responseBody']),
                        'status' => OrderStatus::COMPLETED,
                    ]);

                    $transaction->update([
                        'status' => TransactionStatus::SUCCESSFUL
                    ]);

                    return response()->json(['status' => true, 'code' => OrderStatus::SUCCESSFUL, 'message' => 'The order is successful']);
                }
                elseif($response['status'] === GeneralStatus::PENDING || $response['status'] === GeneralStatus::PROCESSING)
                {
                    $order->update([
                        'responseAPI' => $response['responseAPI'],
                        'responseMessage' => $response['responseMessage'],
                        'responseBody' => json_encode($response['responseBody']),
                        'status' => OrderStatus::PENDING,
                    ]);
                    return response()->json(['status' => true, 'code' => OrderStatus::PENDING,  'message' => 'The order is pending']);
                }
                else
                {
                    
                    $order->update([
                        'responseAPI' => $response['responseAPI'],
                        'responseMessage' => json_encode($response['responseMessage']),
                        'responseBody' => json_encode($response['responseBody']),
                        'status' => OrderStatus::FAILED,
                    ]);
                    
                    // reversed transaction
                    (new ReversalService($transaction, $user, $response))->run();

                    return response()->json(['status' => false, 'code' => OrderStatus::FAILED,  'message' => 'The order failed']);
                }
            }

            return response()->json(['status' => false, 'code' => OrderStatus::UNKNOWN,  'message' => 'State of unknown']);

        } catch (\Exception $exception) {
            // log the error
            Log::error("Error trying to requery order: ", ['error' => $exception->getMessage()]);
            // update the order
            $order->update([
                'responseAPI' => null,
                'responseMessage' => null,
                'responseBody' => json_encode($exception),
                'status' => OrderStatus::FAILED,
            ]);
            // reversed transaction
            (new ReversalService($transaction, $user, []))->run();

            return response()->json(['status' => false, 'code' => OrderStatus::FAILED,  'message' => 'The order failed']);
        }

    }

    public function requeryAirtime(Request $request, $reference)
    {
        $order =  Order::withReference($reference)->first();
        if (!$order) {
            return response()->json(['status' => false, 'code' => 'Unknown', 'message' => 'Order not found'], 404);
        }

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
        dd($response);
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
