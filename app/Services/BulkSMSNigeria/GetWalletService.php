<?php

namespace App\Services\BulkSMSNigeria;

use Exception;

class GetWalletService extends Base
{
    const PATH = "v2/balance";

    /**
     * @throws Exception
     */
    public function run()
    {
        try {
            $response = $this->get(self::PATH);
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
}