<?php

namespace App\Http\Controllers;

use App\Models\WebhookLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function alrahuzdataWebhook(Request $request)
    {
        $provider = 'AlrahuzData';
        // Log the incoming request for debugging
        Log::info('Webhook received from AlrahuzData', ['payload' => $request->all()]);

        try {
            // Validate request
            // $data = $request->validate([
            //     'transaction_id' => 'required|string',
            //     'status' => 'required|string',
            //     'amount' => 'required|numeric',
            //     'reference' => 'required|string',
            //     'payment_method' => 'nullable|string',
            //     'customer_email' => 'nullable|email',
            // ]);

            $data= [
                'provider' => $provider,
                'service' => '',
                'biller' => '',
                'reference' => '',
                'response' => $request->all(),
                'status' => 'Active',
            ];
            // Store webhook response in database
            $webhookLog = WebhookLog::create($data);

            return response()->json(['message' => 'Webhook received successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Webhook handling failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function easyaccessWebhook(Request $request)
    {
        $provider = 'EasyAccessAPI';
        // Log the incoming request for debugging
        Log::info('Webhook received from EasyAccessAPI', ['payload' => $request->all()]);

        try {
            // Validate request
            // $data = $request->validate([
            //     'transaction_id' => 'required|string',
            //     'status' => 'required|string',
            //     'amount' => 'required|numeric',
            //     'reference' => 'required|string',
            //     'payment_method' => 'nullable|string',
            //     'customer_email' => 'nullable|email',
            // ]);

            $data= [
                'provider' => $provider,
                'service' => '',
                'biller' => '',
                'reference' => '',
                'response' => $request->all(),
                'status' => 'Active',
            ];
            // Store webhook response in database
            $webhookLog = WebhookLog::create($data);

            return response()->json(['message' => 'Webhook received successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Webhook handling failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
