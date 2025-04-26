<?php

namespace App\Services\EasyAccessAPI;

use Exception;

class VerifyMeterService extends Base
{
    const PATH = "verifyelectricity.php";

    /**
     * @throws Exception
     */
    public function run($data)
    {
        try {
            $response = $this->post(self::PATH, $data);
            $responseData = 
            '{
                "success": "true",
                "message": {
                    "code": "000",
                    "content": {
                    "Customer_Name": "MRS ANIEMA EKONG ANTIA",
                    "Address": "2A NKARIKA STR.OFF N/A",
                    "Min_Purchase_Amount": 0,
                    "Customer_Arrears": "52388.8",
                    "MAX_Purchase_Amount": "",
                    "MeterNumber": "NOMETER",
                    "Meter_Type": "POSTPAID",
                    "Last_Purchase_Days": "",
                    "Customer_Phone": "841256924301",
                    "WrongBillersCode": false
                    }
                }
            }';
            // $response = json_decode($responseData, true);

            if ($response) {
                if (isset($response['success']) && $response['success'] === 'true') {
                    // meter verified successfully
                    $content = $response['message']['content'];
                
                    $customerName = $content['Customer_Name'] ?? 'N/A';
                    $address = $content['Address'] ?? 'N/A';
                    $phone = $content['Customer_Phone'] ?? 'N/A';
                    $arrears = $content['Customer_Arrears'] ?? '0.00';
                
                    $data = [
                        "name" => $customerName,
                        "address" => $address,
                        "phone" => $phone,
                        "arrears" => $arrears,
                    ];

                    // You can return, display, or log the success info
                    return [
                        'status' => true,
                        'message' => 'Meter verified successfully.',
                        'data' => $data,
                        'response' => $response
                    ];
                
                } else {
                    // meter verification failed
                    $errorMessage = $response['message'] ?? 'Unknown error occurred.';
                
                    // Optional: map known error messages to better readable formats
                    switch ($errorMessage) {
                        case 'Invalid Authorization Token':
                            $userMessage = 'Authorization failed. Please check your credentials.';
                            break;
                        case 'Required parameters cannot be empty':
                            $userMessage = 'Some fields are missing. Please provide all required data.';
                            break;
                        case 'Amount Too Low, Minimum Amount is N1000':
                            $userMessage = 'Amount entered is too low. Minimum is ₦1000.';
                            break;
                        case 'Invalid or Wrong Meter Number, Check Again!':
                            $userMessage = 'Invalid meter number. Please verify and try again.';
                            break;
                        default:
                            $userMessage = $errorMessage;
                            break;
                    }
                
                    // You can return this for JSON API or use it in a Blade view
                    return [
                        'status' => false,
                        'message' => $userMessage,
                        'raw_error' => $errorMessage,
                        'response' => $response
                    ];
                }

            }
        } catch (Exception $exception) {
            return [
                'status' => false,
                'message' => 'Error verifying meter: ' . $exception->getMessage(),
                'response' => null
            ];
        }
    }
}