<?php

namespace App\Services\AlrahuzData;

use App\Enum\GeneralStatus;
use App\Enum\OrderStatus;
use Exception;

class DataService extends Base
{
    const PATH = "data/";
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
        try {
            $response = $this->post(self::PATH, $this->constructPayload());
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
            "network" => (int) $this->getNetworkID($this->data['network']),
            "mobile_number" => strval($this->data['beneficiary']),
            "Ported_number" => $this->data['ported'],
            "plan" => (int) $this->data['plan'],
        ];
    }

}