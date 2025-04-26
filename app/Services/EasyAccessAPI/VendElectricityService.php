<?php

namespace App\Services\EasyAccessAPI;

use App\Enum\GeneralStatus;
use Exception;

class VendElectricityService extends Base
{
    const PATH = "payelectricity.php";

    /**
     * @throws Exception
     */
    public function run($data)
    {
        try {
            // $response = $this->post(self::PATH, $this->constructPayload($data));

            $jsonData = '{
                            "success": "true",
                            "message": {
                                "code": "000",
                                "content": {
                                "transactions": {
                                    "status": "delivered",
                                    "product_name": "KEDCO - Kano Electric",
                                    "unique_element": "57150050060",
                                    "unit_price": "1000",
                                    "quantity": 1,
                                    "service_verification": null,
                                    "channel": "api",
                                    "commission": 10,
                                    "total_amount": 990,
                                    "discount": null,
                                    "type": "Electricity Bill",
                                    "email": "godswillfrancis0@gmail.com",
                                    "phone": "08164383771",
                                    "name": null,
                                    "convinience_fee": "0.00",
                                    "amount": "1000",
                                    "platform": "api",
                                    "method": "api",
                                    "transactionId": "17446440952493605788380201",
                                    "commission_details": {
                                    "amount": 10,
                                    "rate": "1.00",
                                    "rate_type": "percent",
                                    "computation_type": "default"
                                    }
                                }
                                },
                                "response_description": "TRANSACTION SUCCESSFUL",
                                "requestId": "202504141621TXEL113614804",
                                "amount": "1000.00",
                                "transaction_date": "2025-04-14T15:21:35.000000Z",
                                "purchased_code": "Token : 37111625965720319147",
                                "CustomerName": "Sani Hamisu",
                                "CustomerAddress": "NO 1 RINJI  SHOPPING COMPLEX SHARADA, KANO",
                                "DebtTax": null,
                                "DebtAmount": null,
                                "DebtValue": null,
                                "DebtRem": null,
                                "FixedTax": null,
                                "FixedAmount": null,
                                "FixedValue": null,
                                "Amount": 1000,
                                "Tax": null,
                                "Units": 19.55,
                                "Token": "37111625965720319147",
                                "Tariff": null,
                                "Description": null,
                                "Receipt": "250414116409"
                            }
                        }';
    
            $jsonData2 = '{
                "success": "false",
                "message": "Amount Too Low, Minimum Amount is N1000"
            }';

            $response = json_decode($jsonData, true);
            
            if ($response && isset($response['success'])) {
                // handle success = true
                if ($response['success'] === 'true') {
                    $message = $response['message'] ?? [];
    
                    $content = $message['content'] ?? [];
                    $transaction = $content['transactions'] ?? [];
    
                    $dataB = [
                        'status' => $transaction['status'] ?? null,
                        'product' => $transaction['product_name'] ?? null,
                        'transactionId' => $transaction['transactionId'] ?? null,
                        'token' => $message['Token'] ?? null,
                        'units' => $message['Units'] ?? null,
                        'amount' => $response['Amount'] ?? null,
                        'receiptNumber' => $message['Receipt'] ?? null,
                        'tariff' => $message['Tariff'] ?? null,
                    ];

                    $data = [
                        "status" => GeneralStatus::SUCCESSFUL,
                        "responseAPI" => $transaction['transactionId'],
                        'responseMessage' => $message['response_description'] ?? 'Transaction successful.',
                        'data' => $dataB,
                        'responseBody' => $response
                    ];

                    return $data;
                }
    
                // handle known errors (success = false)
                $errorMessage = $response['message'] ?? 'Unknown error.';
                $userMessage = match ($errorMessage) {
                    'Invalid Authorization Token' => 'Authorization failed. Please check your credentials.',
                    'Required parameters cannot be empty' => 'Some fields are missing. Please fill all required inputs.',
                    'Amount Too Low, Minimum Amount is N1000' => 'The minimum allowed amount is ₦1000.',
                    'Insufficient Balance' => 'You do not have enough wallet balance.',
                    default => $errorMessage,
                };
    
                $data = [
                    "status" => GeneralStatus::FAILED,
                    "responseAPI" => null,
                    'responseMessage' => $userMessage,
                    'responseBody' => $response
                ];

                return $data;
            }
    
            // fallback when no proper response structure
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
                'responseBody' => ""
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
            'metertype' => $data['type'],
            'meterno' => $data['meter'],
            'amount' => $data['amount']
        ];
    }
    
}