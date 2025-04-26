<?php

namespace App\Services\Monnify;

use Exception;

class GetWalletService extends Base
{
    const PATH = "v2/disbursements/wallet-balance";

    /**
     * @throws Exception
     */
    public function run()
    {
        $data = [
            "accountNumber" => "8065931337"
        ];
        try {
            $response = $this->get(self::PATH, $data);
            if ($response) {
                // $response = json_decode($response);
                if ($response['requestSuccessful'] && isset($response['responseBody']) ) {
                    $newResponse = ['status' => true, 'balance' => $response['responseBody']['availableBalance'], 'message' => $response['responseMessage']];
                    return $newResponse;
                } else if (isset($response['responseMessage'])) {
                    throw new Exception($response['responseMessage']);
                }
            }
            throw new Exception("Could not get wallet information");
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}