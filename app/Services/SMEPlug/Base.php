<?php

namespace App\Services\SMEPlug;

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
        $this->base_url = env('SMEPLUG_API_URL');
        $this->apiKey = env('SMEPLUG_PUBLIC_KEY');
        $this->secretKey = env('SMEPLUG_SECRET_KEY');
    }

    /**
     * Regenerate and store the API token
     *
     * @throws Exception
     */
    private function getToken(): string
    {
        $key = base64_encode($this->apiKey .":". $this->secretKey);
        try {
            $response = Http::withHeaders([
                "Accept" => "application/json",
                "Content-Type" => "application/json",
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
    public function post(string $path, $data = null)
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
    private function sendRequest(string $method, string $path, $data = null)
    {
        $endpoint = "{$this->base_url}/{$path}";
        try {
            $response = Http::withHeaders([
                "Accept" => "application/json",
                "Content-Type" => "application/json",
                "Authorization" => "Bearer {$this->secretKey}",
            ])->$method($endpoint, $data);

            $this->logApiCall($endpoint, $data, $response->json());
            
            return $response->json();
        } catch (Exception $exception) {
            throw new Exception("API request failed: " . $exception->getMessage());
        }
    }

    // get network/biller id
    public function getNetworkID($biller): string
    {
        switch ($biller) {
            case "MTN" :
                $provider = "1";
                break;
            case "AIRTEL" :
                $provider = "2";
                break;
            case "9MOBILE" :
                $provider = "3";
                break;
            case "GLO" :
                $provider = "4";
                break;
            default:
                $provider = "";
                break;
        }

        return $provider;
    }

    
    // get plan api id
    public function getPlanID($plan): string
    {
        switch ($plan) {
            case "MTN" :
                $provider = "1";
                break;
            case "GLO" :
                $provider = "2";
                break;
            case "9MOBILE" :
                $provider = "3";
                break;
            case "AIRTEL" :
                $provider = "4";
                break;
            case "SMILE" :
                $provider = "5";
                break;
            case "DSTV" :
                $provider = "DSTV";
                break;
            case "STARTIMES" :
                $provider = "Startimes";
                break;
            default:
                $provider = "";
                break;
        }

        return $provider;
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
