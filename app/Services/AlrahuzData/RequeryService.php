<?php

namespace App\Services\AlrahuzData;

use App\Enum\GeneralStatus;
use App\Enum\OrderStatus;
use Exception;
use Illuminate\Support\Facades\Log;

class RequeryService extends Base
{
    const PATH = "topup/";

    /**
     * @throws Exception
     */
    public function run($reference, $service)
    {
        try {
            if($service == 'Airtime'){ $end = 'topup/';}
            else{ $end = 'data/';}
            $path = $end . $reference;
            $response = $this->get($path);
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
                elseif ($responseBody['Status'] == 'processing') 
                {
                    $data = [
                        "status" => GeneralStatus::PROCESSING,
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
            Log::error("error requerying order:", ['Error' => $exception->getMessage()]);
            return [
                "status" => GeneralStatus::PENDING,
                "responseAPI" => null,
                'responseMessage' => ['Error' => $exception->getMessage()],
                'responseBody' => $response
            ];
        }
    }
    

}