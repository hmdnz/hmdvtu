<?php

namespace App\Services\SMEPlug;

use App\Enum\GeneralStatus;
use App\Enum\OrderStatus;
use Exception;

class DataService extends Base
{
    const PATH = "v1/data/purchase";
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
            "network_id" => (int) $this->getNetworkID($this->data['network']),
            "plan_id" => $this->data['plan'],
            "phone" => strval($this->data['beneficiary']),
            "customer_reference" => $this->data['reference'],
        ];
    }

}