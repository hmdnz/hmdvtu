<?php

namespace App\Services\BulkSMSNigeria;

use Exception;

class sendSMSService extends Base
{
    const PATH = "v2/sms";

    /**
     * @throws Exception
     */
    public function run($data)
    {
        try {
            $response = $this->post(self::PATH, $this->constructPayload($data));
            dd($response);
            if ($response) {
                if (isset($response['data']) && $response['data']['status'] == 'success') {
                    $newResponse = ['status' => true, 'balance' => $response['balance']['total_balance'], 'message' => $response['data']['message']];
                    return $newResponse;
                } else if (isset($response['message'])) {
                    throw new Exception($response['message']);
                }
            }
            throw new Exception("Could not get wallet information");
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /*
     * @param array $data
     * @return array
     */
    public function constructPayload($data): array
    {
        return [
            "from" =>  $data['sender'],
            "to" =>  $data['recipients'],
            "body" =>  $data['message'],
            "append_sender"=> 3,
            "dnd"       =>  4,
            "gateway"=> "direct-refund",
            "customer_reference"=> $data['reference']
        ];
    }
}