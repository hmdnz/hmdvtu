<?php

namespace App\Services\SMEPlug;

use App\Enum\GeneralStatus;
use App\Enum\OrderStatus;
use Exception;
use Illuminate\Support\Facades\Log;

class RequeryService extends Base
{
    const PATH = "v1/transactions/";


    /**
     * @throws Exception
     */
    public function run($reference)
    {
        try {
            $path = self::PATH . $reference;
            $response = $this->get($path);
            if ($response && $response['status'] && ($response['status'] == 'success' || $response['status'] == 'Success')) {
                $data = [
                    "status" => GeneralStatus::SUCCESSFUL,
                    "responseAPI" => $response['ref'],
                    'responseMessage' => $response['response'],
                    'responseBody' => $response
                ];
            }
            else{
                $data = [
                    "status" => GeneralStatus::FAILED,
                    "responseAPI" => null,
                    'responseMessage' => $response['msg'],
                    'responseBody' => $response
                ];
            }
            return $data;
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