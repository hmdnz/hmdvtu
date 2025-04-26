<?php

namespace App\Http\Controllers;

use App\Enum\OrderStatus;
use App\Enum\PaymentStatus;
use App\Enum\TransactionStatus;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Transaction;
use App\Services\AlrahuzData\GetWalletService as AlrahuzDataGetWalletService;
use App\Services\BulkSMSNigeria\GetWalletService;
use App\Services\EasyAccessAPI\GetWalletService as EasyAccessAPIGetWalletService;
use App\Services\Monnify\GetWalletService as MonnifyGetWalletService;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    //
    public function getBulkSMSBalance()
    {
        $response = (new GetWalletService())->run();

        return response()->json($response);
    }

    public function getMonnifyBalance()
    {
        $response = (new MonnifyGetWalletService())->run();

        return response()->json($response);
    }

    public function getAlrahuzDataBalance()
    {
        $response = (new AlrahuzDataGetWalletService())->run();

        return response()->json($response);
    }

    public function getEasyAccessBalance()
    {
        $response = (new EasyAccessAPIGetWalletService())->run();

        return response()->json($response);
    }

    public function getInternalTotal()
    {
        $paymentSum = Payment::where('status', TransactionStatus::SUCCESSFUL)->sum('amount'); 

        $transactionSum = Transaction::where('status', TransactionStatus::SUCCESSFUL)->sum('amount'); 

        $orderSum = Order::where('status', OrderStatus::COMPLETED)->sum('total'); 

        // Return the sums as a JSON response
        return response()->json([
            'status' => true,
            'message' => 'Total fetched successfully',
            'payment_total' => $paymentSum,
            'transaction_total' => $transactionSum,
            'order_total' => $orderSum,
        ]);

    }

}
