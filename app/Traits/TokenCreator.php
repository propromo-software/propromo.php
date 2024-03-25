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
        $url = $_ENV['APP_SERVICE_URL'] . '/v1/github/auth/token';

        $response = Http::withHeaders([
            'content-type' => 'application/json',
            'Accept' => 'text/plain',
            'Authorization' => 'Bearer ' . $pat_token,
        ])->post($url);

        if ($response->successful()) {
            return $response->body();
        } else {
            throw new Exception("Error while requesting the monitor token!");
        }
    }
}
