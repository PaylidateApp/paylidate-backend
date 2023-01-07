<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class ProvidusNIPService
{
    public $baseURLBank = 'http://bank_url/api';
    public $baseURL = 'http://154.113.16.142:8088/appdevapi/api/';
    protected $username = 'username';
    protected $password = 'password';
    protected $header = array(
        'Content-Type: application/json',
        'X-Auth-Signature: BE09BEE831CF262226B426E39BD1092AF84DC63076D4174FAC78A2261F9A3D6E59744983B8326B69CDF2963FE314DFC89635CFA37A40596508DD6EAAB09402C7',
        'Client-Id: dGVzdF9Qcm92aWR1cw=='
    );

    public function __construct()
    {
        // $this->baseURLBank = env('BANK_URL');
        // $this->baseURL = env('FLUTTERWAVE_URL');
        // $this->username = env('FLUTTERWAVE_USERNAME');
        // $this->password = env('FLUTTERWAVE_PASSWORD');
    }

    // create new virtual account
    public function createVirtualAccount($account_name)
    {
        try {
            $arr = array('account_name' => $account_name);
            $curl = curl_init();
            $param = json_encode($arr);
            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->baseURL . 'PiPCreateDynamicAccountNumber',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $param,
                CURLOPT_HTTPHEADER => $this->header,
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e
            ]);
        }
    }

    // GET
    // verify Transaction
    // http://bank_url/api/PiPverifyTransaction?session_id=204210202000000500002
    // This endpoint is to verify a transaction by supplying the session id.
    public function verifyTransactionSession_Id($session_id)
    {
        try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->baseURL . 'PiPverifyTransaction?session_id=' . $session_id,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => $this->header,
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            return $response;
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e
            ]);
        }
    }

    // This endpoint is to verify a transaction by supplying the settlement id.
    public function verifyTransactionBySettlementId($settlement_id)
    {
        try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->baseURL . 'PiPverifyTransaction_settlementid?settlement_id=' . $settlement_id,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => $this->header,
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            return $response;
            // return $response;
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e
            ]);
        }
    }

    // get virtual account details
    public function getVirtualAccountDetails($data)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->baseURL . 'PiPGetDynamicAccountNumber', [
            'business_name' => $data['name'],
        ]);

        return $response;
    }


    // 4.1.1 URI
    // Request Path – /GetBVNDetails HTTP method – POST
    // 4.1.2 Header
    // Accept – application/json Content-Type – application/json
    // 4.1.3 JSON Request and Response
    // Request Parameter (json string)
    // { "bvn":"22222222222", "userName":"test", "password":"test"
    // }
    public function getBVNDetails($bvn)
    {
        try {
            $arr = array("bvn" => $bvn, "userName" => $this->username, "password" => $this->password);
            $curl = curl_init();
            $param = json_encode($arr);
            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->baseURL . 'GetBVNDetails',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $param,
                CURLOPT_HTTPHEADER => $this->header,
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e
            ]);
        }
    }


    // 4.2.1 URI
    // Request Path – GetNIPAccount HTTP method – POST
    // 4.2.2 Header
    // Accept – application/json Content-Type – application/json
    // 4.2.3 JSON Request and Response
    // Request Parameter (json string)
    // { "accountNumber":"1018996198", "beneficiaryBank":"110000", "userName":"test", "password":"test"
    // }
    public function getNIPAccount($data)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->baseURL . '/GetNIPAccount', [
            'accountNumber' => $data['account_number'],
            'beneficiaryBank' => $data['bank_code'],
            'userName' => $this->username,
            'password' => $this->password,
        ]);

        return $response;
    }


    // 4.3.1 URI
    // Request Path – /NIPFundTransfer HTTP method – POST
    // 4.3.2 Header
    // Accept – application/json Content-Type – application/json
    // 4.3.3 JSON Request and Response
    // Request Parameter (json string)
    // {
    // "beneficiaryAccountName":"UGBO, CHARLES UMORE", "transactionAmount": "2000.45",
    // "currencyCode":"NGN",
    // "narration":"Testing",
    // "sourceAccountName":"Nnamdi Adebayo Hamzat" , "beneficiaryAccountNumber":"1700313889", "beneficiaryBank":"000013", "transactionReference":"20191119143501", "userName":"test",
    // "password":"test"
    // }
    public function NIPFundTransfer($data)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->baseURL . '/NIPFundTransfer', [
            'beneficiaryAccountName' => $data['beneficiaryAccountName'],
            'transactionAmount' => $data['transactionAmount'],
            'currencyCode' => $data['currencyCode'],
            'narration' => $data['narration'],
            'sourceAccountName' => $data['sourceAccountName'],
            'beneficiaryAccountNumber' => $data['beneficiaryAccountNumber'],
            'beneficiaryBank' => $data['beneficiaryBank'],
            'transactionReference' => $data['transactionReference'],
            'userName' => $this->username,
            'password' => $this->password,
        ]);

        return $response;
    }


    // 4.4.1 URI
    // Request Path – /GetNIPTransactionStatus
    // HTTP method – POST
    // Note: It is advised to always do a status requery on all transaction regardless of the response gotten to know the final status of the transaction (Kindly extend status requery time to 15mins to be sure of final status).
    // 4.4.2 Header
    // Accept – application/json

    // Content-Type – application/json
    // 4.4.3 JSON Request and Response
    // Request Parameter (json string)
    // { "transactionReference":"1234567gkgk", "userName":"test",
    // "password":"test"
    // }
    public function getNIPTransactionStatus($data)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->baseURL . '/GetNIPTransactionStatus', [
            'transactionReference' => $data['transactionReference'],
            'userName' => $this->username,
            'password' => $this->password,
        ]);

        return $response;
    }

    // get NIP GetNIPBanks
    public function getNIPBanks()
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->baseURL . '/GetNIPBanks', []);

        return $response;
    }

    // ProvidusFundTransfer
    public function ProvidusFundTransfer($data)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->baseURL . '/ProvidusFundTransfer', [
            'account_number' => $data['account_number'],
            'amount' => $data['amount'],
            'narration' => $data['narration'],
            'bank_code' => $data['bank_code'],
            'account_name' => $data['account_name'],
        ]);

        return $response;
    }

    // 4.7.1 URI
    // Request Path – /GetProvidusTransactionStatus HTTP method – POST
    // 4.7.2 Header
    // Accept – application/json Content-Type – application/json
    // 4.7.3 JSON Request and Response

    // Request Parameter (json string)
    // {
    // "transactionReference":" 2345677777", "userName":"test",
    // "password":"test"
    // }

    public function GetProvidusTransactionStatus($data)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->baseURL . '/GetProvidusTransactionStatus', [
            'transactionReference' => $data['transactionReference'],
            'userName' => $this->username,
            'password' => $this->password,
        ]);

        return $response;
    }

    // 4.8.1 URI
    // Request Path – /GetProvidusAccount HTTP method – POST
    // 4.8.2 Header
    // Accept – application/json Content-Type – application/json
    // 4.8.3 JSON Request and Response
    // Request Parameter (json string)
    // {
    // "accountNumber":" 1700263070" “userName” : ”test”
    // “password” : ”test”
    // }

    public function GetProvidusAccount($data)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->baseURL . '/GetProvidusAccount', [
            'accountNumber' => $data['accountNumber'],
            'userName' => $this->username,
            'password' => $this->password,
        ]);

        return $response;
    }
}
