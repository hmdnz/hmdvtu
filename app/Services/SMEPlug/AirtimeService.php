<?php

namespace App\Services\SMEPlug;

use App\Enum\GeneralStatus;
use App\Enum\OrderStatus;
use Exception;
use Illuminate\Support\Facades\Log;

class AirtimeService extends Base
{
    const PATH = "v1/airtime/purchase";
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
            if ($response && $response['status'] && isset($response['data'])) {
                $data = [
                    "status" => GeneralStatus::SUCCESSFUL,
                    "responseAPI" => $response['data']['reference'],
                    'responseMessage' => $response['data']['msg'],
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
            // throw new Exception($exception->getMessage());
            // log the error for debugging
            Log::error("API Request Failed: " . $exception->getMessage());

            return [
                "status" => GeneralStatus::FAILED,
                "responseAPI" => null,
                "responseMessage" => "An error occurred while processing your request.",
                "responseBody" => [
                    "error" => $exception->getMessage()
                ]
            ];
        }
    }
    /*
     * @param array $data
     * @return array
     */
    protected function constructPayload(): array
    {
        return [
            "network_id" => (int) $this->getNetworkID($this->data['operator']),
            "amount" => $this->data['amount'],
            "phone" => strval($this->data['recipient']),
            "customer_reference" => $this->data['reference'],
        ];
    }

}