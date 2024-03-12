<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Http;


trait TokenCreator {
    /**
     * @throws Exception
     */
    public function getApplicationToken($pat){
        $url = $_ENV['APP_SERVICE_URL'] . '/v1/github/auth/token';

        $response = Http::withHeaders([
            'content-type' => 'application/json',
            'Accept' => 'text/plain',
            'Authorization' => 'Bearer '.$pat,
        ])->post($url);

        if($response->successful())
        {
            return $response->body();
        }else{
            throw new Exception("Error while fetching: " . $response);
        }

    }
}
