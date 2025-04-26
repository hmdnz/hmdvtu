<?php

namespace App\Services\EasyAccessAPI;

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
        $this->base_url = env('EASYACCESS_URL');
        $this->apiKey = env('EASYACCESS_API_KEY');
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
                "AuthorizationToken"=>"{$this->apiKey}"
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
