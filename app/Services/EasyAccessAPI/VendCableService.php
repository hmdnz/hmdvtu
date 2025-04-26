<?php

namespace App\Services\EasyAccessAPI;

use App\Enum\GeneralStatus;
use Exception;

class VendCableService extends Base
{
    const PATH = "paytv.php";

    /**
     * @throws Exception
     */
    public function run($data)
    {
        try {
            $response = $this->post(self::PATH, $this->constructPayload($data));
            
            // $jsonError1 = 
            //     '{
            //         "success": "false",
            //         "message": "Invalid Authorization Token",
            //         "auto_refund_status": "failed"
            //     }';

            // $jsonError2 = 
            //     '{
            //         "success": "false",
            //         "message": "Required parameters cannot be empty",
            //         "auto_refund_status": "failed"
            //     }';

            // $jsonError3 = 
            //     '{
            //         "success": "false",
            //         "message": "Insufficient Balance",
            //         "auto_refund_status": "failed"
            //     }';

            // $jsonSuccess = 
            //     '{
            //     "success": "true",
            //     "message": "TV Subscription was Successful",
            //     "company": "GOTV",
            //     "package": "GOTV Smallie - N1900",
            //     "iucno": "8072608642",
            //     "amount": "1900",
            //     "transaction_date": "14-04-2025 05:00:08 pm",
            //     "reference_no": "202504141700TXTV2332885090",
            //     "status": "Successful"
            //     }
            //     ';

            // $response = json_decode($jsonSuccess, true);

            if ($response && isset($response['success'])) {
                // successful
                if ($response['success'] === 'true') {
                    $dataB = [
                        'message' => $response['message'] ?? 'TV Subscription was Successful',
                        'company' => $response['company'] ?? null,
                        'package' => $response['package'] ?? null,
                        'iucno' => $response['iucno'] ?? null,
                        'amount' => $response['amount'] ?? null,
                        'transaction_date' => $response['transaction_date'] ?? null,
                        'reference_no' => $response['reference_no'] ?? null,
                        'status' => $response['status'] ?? null,
                    ];

                    return [
                        "status" => GeneralStatus::SUCCESSFUL,
                        "responseAPI" => $response['reference_no'] ?? null,
                        'responseMessage' => $response['message'] ?? 'Subscription Successful',
                        'data' => $dataB,
                        'responseBody' => $response
                    ];
                }

                // failed cases
                $errorMessage = $response['message'] ?? 'Unknown error.';
                $userMessage = match ($errorMessage) {
                    'Invalid Authorization Token' => 'Authorization failed. Please check your credentials.',
                    'Required parameters cannot be empty' => 'Some required fields are missing. Please review your input.',
                    'Insufficient Balance' => 'You do not have enough balance in your EasyAccessAPI wallet.',
                    default => $errorMessage,
                };

                return [
                    "status" => GeneralStatus::FAILED,
                    "responseAPI" => null,
                    'responseMessage' => $userMessage,
                    'responseBody' => $response
                ];
            }

            // fallback for unexpected structure
            return [
                "status" => GeneralStatus::FAILED,
                "responseAPI" => null,
                'responseMessage' => 'Empty or malformed API response.',
                'responseBody' => $response
            ];

        } catch (Exception $exception) {
            return [
                "status" => GeneralStatus::FAILED,
                "responseAPI" => null,
                'responseMessage' => 'Error processing transaction: ' . $exception->getMessage(),
                'responseBody' => []
            ];
        }
    }


    /*
     * @param array $data
     * @return array
     */
    protected function constructPayload($data): array
    {
        return [
            'company' => $data['company'],
            'iucno' => $data['smartcard'],
            'package' => $data['plan']
        ];
    }
    
}