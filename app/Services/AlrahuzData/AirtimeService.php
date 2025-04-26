<?php

namespace App\Services\AlrahuzData;

use App\Enum\GeneralStatus;
use App\Enum\OrderStatus;
use Exception;

class AirtimeService extends Base
{
    const PATH = "topup/";
    protected $data;

    public function __construct(
        array $data,
    )
    {
        parent::__construct();
        $this->data = $data;
    }

    /**
     * @throws Exception
     */
    public function run()
    {
        // $authorizationHeader = 'Authorization: Token ' . env('ALRAHUZ_API_KEY');
        // $data = $this->constructPayload();
        // // dd($data);
        // $curl = curl_init();
        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => "https://alrahuzdata.com.ng/api/topup/",
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => "",
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => "POST",
        //     CURLOPT_POSTFIELDS => json_encode($data),
        //     CURLOPT_HTTPHEADER => array(
        //         $authorizationHeader,
        //         'Content-Type: application/json'
        //     ),
        // ));
        // $response = curl_exec($curl);
        // curl_close($curl);
        // $obj = json_decode($response);
        // dd($response, $data);
        // if (property_exists($obj, 'Status')) {
        //     if ($obj->Status == 'successful') 
        //     {
        //         $responseData = [
        //             'success' => true, 
        //             'apiDeliveryId' => $obj->id, 
        //             'apiResponse' => $obj->api_response,
        //             'response' => $response
        //         ];
        //     } else {
        //         $responseData = [
        //             'success' => 'pending', 
        //             'apiDeliveryId' => $obj->id, 
        //             'apiResponse' => 'There is an error. Try again later or contact support.',
        //             'response' => $response
        //         ];
        //     }
        //     return $responseData;
        // } else {
        //     $response3 = [
        //         'success' => false, 
        //         'apiDeliveryId' => null, 
        //         'apiResponse' => $obj,
        //         'response' => $response
        //     ];
        //     return $response3;
        // }
        try {
            // dd();
            $response = $this->post(self::PATH, $this->constructPayload());
            // dd($response);
            if ($response && isset($response['Status'])) {
                $responseBody = $response;
                if ($responseBody['Status'] == 'successful') 
                {
                    $data = [
                        "status" => GeneralStatus::SUCCESSFUL,
                        "responseAPI" => $responseBody['id'],
                        'responseMessage' => $responseBody['api_response'],
                        'responseBody' => $response
                    ];
                }
                elseif ($responseBody['Status'] == 'pending') 
                {
                    $data = [
                        "status" => GeneralStatus::PENDING,
                        "responseAPI" => $responseBody['id'],
                        'responseMessage' => $responseBody['api_response'],
                        'responseBody' => $response
                    ];
                } 
                elseif ($responseBody['Status'] == 'processing') 
                {
                    $data = [
                        "status" => GeneralStatus::PROCESSING,
                        "responseAPI" => $responseBody['id'],
                        'responseMessage' => $responseBody['api_response'],
                        'responseBody' => $response
                    ];
                } else 
                {
                    $data = [
                        "status" => GeneralStatus::FAILED,
                        "responseAPI" => $responseBody['id'],
                        'responseMessage' => $responseBody['api_response'],
                        'responseBody' => $response
                    ];
                }
                return $data;
            }
            $data = [
                "status" => GeneralStatus::FAILED,
                "responseAPI" => null,
                'responseMessage' => $response,
                'responseBody' => $response
            ];
            return $data;
            // throw new Exception("Could not vend airtime");
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
    /*
     * @param array $data
     * @return array
     */
    protected function constructPayload(): array
    {
        return [
            "network" => (int) $this->getNetworkID($this->data['operator']),
            "amount" => $this->data['amount'],
            "mobile_number" => strval($this->data['recipient']),
            "Ported_number" => $this->data['ported'],
            "airtime_type" => $this->data['type'],
        ];
    }

}