<?php

namespace App\Services\Monnify;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Enum\CacheKey;
use App\Libraries\Utilities;
use Exception;
use Illuminate\Support\Facades\Log;

class Base
{
    protected string $base_url;
    protected string $apiKey;
    protected string $secretKey;

    public function __construct()
    {
        $this->base_url = env('MONNIFY_LIVE_URL');
        $this->apiKey = env('MONNIFY_LIVE_API_KEY');
        $this->secretKey = env('MONNIFY_LIVE_SECRET_KEY');
    }

    /**
     * Get a valid API token from cache or regenerate if expired
     *
     * @throws Exception
     */
    // public function getToken(): string
    // {
    //     return Cache::remember(CacheKey::BELLBASS_TOKEN, now()->addMinutes(55), function () {
    //         return $this->regenerateToken();
    //     });
    // }
    // private function getAuthorization()
    // {
    //     $key = base64_encode('MK_PROD_K1XRNL3CWV:TV2JAG0RZQMP4CDNLEX26RFRM3F11D71');
    //     // echo $key;
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, "");
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    //     curl_setopt($ch, CURLOPT_HEADER, FALSE);
    //     curl_setopt($ch, CURLOPT_POST, TRUE);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    //         "Authorization: Basic $key",
    //         'Content-type' => 'application/json',
    //     ));
    //     // $EPIN_response = curl_exec($ch);
    //     $response = curl_exec($ch);
    //     $err = curl_error($ch);
    //     curl_close($ch);
    //     // echo $EPIN_response;
    //     if ($err) {
    //         echo "cURL Error #:" . $err;
    //     } else {
    //         $obj = json_decode($response);
    //         // echo $response;
    //         if ($obj->requestSuccessful) {
    //             if ($obj->responseMessage == 'success') {
    //                 // $amount = ($obj->data->amount - $obj->data->fees) / 100;
    //                 return $obj->responseBody->accessToken;
    //             } else {
    //                 return false;
    //             }
    //         } else {
    //             return false;
    //         }
    //     }
    // }

    /**
     * Regenerate and store the API token
     *
     * @throws Exception
     */
    private function getToken(): string
    {
        // $key = base64_encode("MK_TEST_NTETJ39W2Z:B7XAQCFKP2L41H0UWE4U98XZP0JU5F5H");
        $key = base64_encode($this->apiKey .":". $this->secretKey);
        try {
            $response = Http::withHeaders([
                "Accept" => "application/json",
                "Content-Type" => "application/json",
                "Authorization" => "Basic $key",
            ])->post("{$this->base_url}/v1/auth/login");

            // Decode the JSON response
            $data = $response->json();

            // Check if the request was successful and the token exists
            if ($response->successful() && isset($data['responseBody']['accessToken'])) {
                return $data['responseBody']['accessToken'];
            }

            throw new Exception("Failed to generate API token.");
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * Send a POST request with authentication
     */
    public function post(string $path, array $data = [])
    {
        return $this->sendRequest('post', $path, $data);
    }

    /**
     * Send a GET request with authentication
     */
    public function get(string $path, $data = [])
    {
        return $this->sendRequest('get', $path, $data);
    }

    /**
     * Send an API request
     */
    private function sendRequest(string $method, string $path, array $data = [])
    {
        $endpoint = "{$this->base_url}/{$path}";

        try {
            $response = Http::withHeaders([
                "Accept" => "application/json",
                "Content-Type" => "application/json",
                "Authorization" => "Bearer {$this->getToken()}",
            ])->$method($endpoint, $data);

            $this->logApiCall($endpoint, $data, $response->json());

            return $response->json();
        } catch (Exception $exception) {
            throw new Exception("API request failed: " . $exception->getMessage());
        }
    }

    /**
     * Log API calls
     */
    private function logApiCall(string $endpoint, array $request, array $response)
    {
        Log::info([
            'endpoint' => $endpoint,
            'request' => json_encode($request),
            'response' => json_encode($response),
        ]);
    }
}
