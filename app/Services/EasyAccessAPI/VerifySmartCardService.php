<?php

namespace App\Services\EasyAccessAPI;

use Exception;

class VerifySmartCardService extends Base
{
    const PATH = "verifytv.php";

    /**
     * @throws Exception
     */
    public function run($data)
    {
        try {
            $response = $this->post(self::PATH, $data);
            if ($response) {
                if (isset($response['success']) && $response['success'] === 'true') {
                    // Smartcard verified successfully
                    $content = $response['message']['content'];
                
                    $customerName = $content['Customer_Name'] ?? 'N/A';
                    $status = $content['Status'] ?? 'N/A';
                    $type = $content['Customer_Type'] ?? 'N/A';
                    $number = $content['Customer_Number'] ?? 'N/A';
                
                    $data = [
                        "name" => $customerName,
                        "number" => $number,
                        "type" => $type,
                        "status" => $status,
                    ];

                    // You can return, display, or log the success info
                    return [
                        'status' => true,
                        'message' => 'Smartcard verified successfully.',
                        'data' => $data,
                        'response' => $response
                    ];
                
                } else {
                    // Smartcard verification failed
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
                        case 'Invalid or Wrong Smartcard Number, Check Again!':
                            $userMessage = 'Invalid Smartcard number. Please verify and try again.';
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
                'message' => 'Error verifying smartcard: ' . $exception->getMessage(),
                'response' => null
            ];
        }
    }
}