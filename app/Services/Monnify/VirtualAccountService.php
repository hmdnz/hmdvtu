<?php

namespace App\Services\Monnify;

use Exception;
use Illuminate\Support\Facades\Log;

class VirtualAccountService extends Base
{
    const PATH = "v2/bank-transfer/reserved-accounts";


    /**
     * @throws Exception
     */
    public function run(array $data): array
    {
        try {
            $response = $this->post(self::PATH, $data);
            if ($response && $response['requestSuccessful'] && $response['responseBody']['accounts']) {
                $newResponse = ['status' => true, 'data' => $response['responseBody']['accounts']];
                return $newResponse;
            } else {
                // throw new Exception("Cannot generate virtual account");
                return ['status' => false, 'message' => $response['responseMessage'] ?? 'Cannot generate virtual account'];
            }
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @param array $data
     * @return array
     */
    // protected function constructPayload(array $data): array
    // {
    //     return [
    //         "business_name" => $data['business_name'],
    //         "bvn" => $data['bvn'],
    //         "phone_number" => $data['phone_number'],
    //         "dob" => date('Y-m-d', strtotime($data['dob'])),
    //         "business_type" => "Main",
    //         "type" => "Corporate",
    //         "currency" => "NGN"
    //     ];
    // }
}