<?php

namespace App\Services\Monnify;

use Exception;
use Illuminate\Support\Facades\Log;

class GetBanksService extends Base
{
    const PATH = "v1/banks";


    /**
     * @throws Exception
     */
    public function run(): array
    {
        try {
            $response = $this->get(self::PATH);
            if ($response && $response['requestSuccessful'] && $response['responseBody']) {
                $newResponse = ['status' => true, 'data' => $response['responseBody']];
                return $newResponse;
            } else {
                return ['status' => false, 'message' => $response['responseMessage'] ?? 'Cannot fetch banks'];
            }
        } catch (Exception $exception) {
            Log::error('There is error fetching banks: ', ["error" => $exception->getMessage()]);
            return ['status' => false, 'message' =>  $exception->getMessage() ?? 'Cannot fetch banks'];
        }
    }

   
}