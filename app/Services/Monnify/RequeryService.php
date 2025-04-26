<?php

namespace App\Services\Monnify;

use Exception;
use Illuminate\Support\Facades\Log;

class RequeryService extends Base
{
    const PATH = "v2/merchant/transactions/query";


    /**
     * @throws Exception
     */
    public function run(string $reference): array
    {
        $path = self::PATH;
        $data = [
            "paymentReference" => $reference
        ];

        try {
            $response = $this->get($path, $data);
            if ($response && $response['requestSuccessful'] && $response['responseBody']) {
                $newResponse = ['status' => true, 'data' => $response['responseBody']];
                return $newResponse;
            } else {
                return ['status' => false, 'message' => $response['responseMessage'] ?? 'Cannot verify transaction'];
            }
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

}