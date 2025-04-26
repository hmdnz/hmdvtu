<?php

namespace App\Services\Monnify;

use Exception;
use Illuminate\Support\Facades\Log;

class VerifyBVNService extends Base
{
    const PATH_DETAILS = "v1/vas/bvn-details-match";
    const PATH_ACCOUNT_NUMBER = "v1/vas/bvn-account-match";


    /**
     * @throws Exception
     */
    public function informationMatch(array $data): array
    {
        try {
            $response = $this->post(self::PATH_DETAILS, $this->constructPayload($data));
            if ($response && $response['requestSuccessful'] && $response['responseBody']) {
                $newResponse = ['status' => true, 'data' => $response['responseBody']];
                return $newResponse;
            } else {
                return ['status' => false, 'message' => $response['responseMessage'] ?? 'Cannot verify information'];
            }
        } catch (Exception $exception) {
            Log::error('There is error verifying bvn information: ', ["error" => $exception->getMessage()]);
            return ['status' => false, 'message' =>  $exception->getMessage() ?? 'Cannot verify information'];
        }
    }

    /**
     * @throws Exception
     */
    public function accountMatch(array $data): array
    {
        try {
            $response = $this->post(self::PATH_ACCOUNT_NUMBER, $this->constructPayload2($data));
            if ($response && $response['requestSuccessful'] && $response['responseBody']) {
                $newResponse = ['status' => true, 'data' => $response['responseBody']];
                return $newResponse;
            } else {
                return ['status' => false, 'message' => $response['responseMessage'] ?? 'Cannot verify account'];
            }
        } catch (Exception $exception) {
            Log::error('There is error verifying account: ', ["error" => $exception->getMessage()]);
            return ['status' => false, 'message' =>  $exception->getMessage() ?? 'Cannot verify account'];
        }
    }

    /**
     * @param array $data
     * @return array
     */
    protected function constructPayload(array $data): array
    {
        return [
            "name" => $data['name'],
            "bvn" => $data['bvn'],
            "mobileNo" => $data['phone'],
            "dateOfBirth" => date('d-M-Y', strtotime($data['dob'])),
        ];

    }

    /**
     * @param array $data
     * @return array
     */
    protected function constructPayload2(array $data): array
    {
        return [
            "bankCode" =>  $data['bank_code'],
            "accountNumber" =>  $data['account_number'],
            "bvn" =>  $data['bvn'],
        ];
    }
}