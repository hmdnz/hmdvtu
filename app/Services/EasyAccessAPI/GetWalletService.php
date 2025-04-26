<?php

namespace App\Services\EasyAccessAPI;

use Exception;

class GetWalletService extends Base
{
    const PATH = "wallet_balance.php";

    /**
     * @throws Exception
     */
    public function run()
    {
        try {
            $response = $this->get(self::PATH);
            if ($response) {
                if (isset($response['success']) && $response['success'] == "true") {
                    $newResponse = ['status' => true, 'balance' => $response['balance'], 'message' => 'success'];
                    return $newResponse;
                } else {
                    throw new Exception('fails to get wallet balance');
                }
            }
            throw new Exception("Could not get wallet information");
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}