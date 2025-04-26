<?php

namespace App\Services\AlrahuzData;

use Exception;

class GetWalletService extends Base
{
    const PATH = "user";

    /**
     * @throws Exception
     */
    public function run()
    {
        try {
            $response = $this->get(self::PATH);
            if ($response) {
                if (isset($response['user']) && $response['user']) {
                    $newResponse = ['status' => true, 'balance' => $response['user']['Account_Balance'], 'message' => 'success'];
                    return $newResponse;
                } else {
                    throw new Exception('failes to get wallet balance');
                }
            }
            throw new Exception("Could not get wallet information");
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}