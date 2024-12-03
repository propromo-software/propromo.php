<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Http;


trait TokenCreator
{
    /**
     * @throws Exception
     */
    public function get_application_token($pat_token)
    {
        $baseUrl = env('APP_SERVICE_URL', 'http://localhost:3000');
        $url = rtrim($baseUrl, '/') . '/v1/github/auth/token';

        try {
            try {
                $testResponse = Http::get($baseUrl);
                \Log::debug('Base URL Test:', [
                    'url' => $baseUrl,
                    'status' => $testResponse->status(),
                    'reachable' => true
                ]);
            } catch (\Exception $e) {
                \Log::error('Base URL Test Failed:', [
                    'url' => $baseUrl,
                    'error' => $e->getMessage(),
                    'reachable' => false
                ]);

                throw new Exception("Microservice is unreachable!");
            }

            $requestBody = [
                'token' => $pat_token
            ];

            \Log::debug('TokenCreator Request:', [
                'url' => $url,
                'body' => $requestBody,
                'headers' => [
                    'content-type' => 'application/json',
                    'Accept' => 'text/plain',
                    'Authorization' => 'Bearer ' . $pat_token,
                ]
            ]);

            $response = Http::withHeaders([
                'content-type' => 'application/json',
                'Accept' => 'text/plain',
                'Authorization' => 'Bearer ' . $pat_token,
            ])->post($url, $requestBody);

            \Log::debug('TokenCreator Response:', [
                'status' => $response->status(),
                'body' => $response->body(),
                'headers' => $response->headers()
            ]);

            if ($response->successful()) {
                return $response->body();
            } else {
                if (app()->environment('local')) {
                    throw new Exception("Error while requesting the monitor token! Status: " . $response->status() . " Body: " . $response->body());
                } else {
                    throw new Exception("Error while requesting the monitor token!");
                }
            }
        } catch (\Exception $e) {
            \Log::error('TokenCreator Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }
}
