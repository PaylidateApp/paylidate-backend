<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class FlutterwaveService
{

    public $baseURL = 'http://bank_url/api';

    public function __construct()
    {
    }

    // create new virtual account
    public function createVirtualAccount($data)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->baseURL . 'PiPCreateDynamicAccountNumber', [
            'account_name' => $data['name'],
        ]);

        return $response->json();
    }

    // get virtual account details
    public function getVirtualAccountDetails($data)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->baseURL . 'PiPGetDynamicAccountNumber', [
            'business_name' => $data['name'],
        ]);

        return $response->json();
    }

}