<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FlutterwaveService
{

    public function __construct(){

    }

    public function virtualCard($currency, $amount, $name){
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.env('FLW_SECRET_KEY')
        ])->post(env('FLW_BASE_URL').'/v3/virtual-cards', [
            "currency" => $currency,
            "amount" => $amount,
            "billing_name" => $name
        ]);

        return $response;
    }

    
    public function getvirtualCard($card_id){
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.env('FLW_SECRET_KEY')
        ])->get(env('FLW_BASE_URL').'/v3/virtual-cards/'.$card_id);

        return $response;
    }

    public function withdrawFromVirtualCard($card_id, $amount){
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.env('FLW_SECRET_KEY')
        ])->post(env('FLW_BASE_URL').'/v3/virtual-cards/'. $card_id .'/withdraw', [
            "amount" => $amount,
        ]);

        return $response;
    }

    // fund virtual card with payment
    public function fundVirtualCard($card_id, $amount, $debit_currency = 'NGN'){
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.env('FLW_SECRET_KEY')
        ])->post(env('FLW_BASE_URL').'/v3/virtual-cards/'. $card_id .'/fund', [
            "amount" => $amount,
            "debit_currency" => $debit_currency,
        ]);

        return $response;
    }



    public function getPaymentLink($name, $amount, $currency, $redirect_url, $meta, $customer, $customizations){
       
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.env('FLW_SECRET_KEY')
        ])->post(env('FLW_BASE_URL').'/v3/payments', [
            "tx_ref" => $name."-tx-".time(),
            "amount" => $amount,
            "currency" => $currency,
            "redirect_url" => $redirect_url,
            "payment_options" => "card",
            "meta" => $meta,
            "customer" => $customer,
            "customizations" => $customizations
        ]);

        return $response;

    }


    public function getRate($amount, $destination_currency, $source_currency){
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.env('FLW_SECRET_KEY')
            ])->get(env('FLW_BASE_URL').'/v3/transfers/rates', [
                'amount' => $amount,
                'destination_currency' => $destination_currency,
                'source_currency' => $source_currency
            ]);

        return $response;
    }

    public function banks(){
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.env('FLW_SECRET_KEY')
            ])->get(env('FLW_BASE_URL').'/v3/banks/NG');

        return $response;
    }

    public function createVirtualAccount($email, $is_permanent, $name){
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.env('FLW_SECRET_KEY')
        ])->post(env('FLW_BASE_URL').'/v3/virtual-account-numbers', [
            "email" => $email,
            "is_permanent" => $is_permanent,
            "tx_ref" => $name.'-'.time(),
            "narration" => $name,
        ]);

        return $response;
    }

    public function getTransaction($transaction_id){
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.env('FLW_SECRET_KEY')
            ])->get(env('FLW_BASE_URL').'/v3/transactions/'. $transaction_id .'\/verify/');

        return $response;
    }


    function getKey($seckey)
    {
        $hashedkey = md5($seckey);
        $hashedkeylast12 = substr($hashedkey, -12);

        $seckeyadjusted = str_replace("FLWSECK-", "", $seckey);
        $seckeyadjustedfirst12 = substr($seckeyadjusted, 0, 12);

        $encryptionkey = $seckeyadjustedfirst12 . $hashedkeylast12;
        return $encryptionkey;
    }

    function encrypt3Des($data, $key)
    {
        $encData = openssl_encrypt($data, 'DES-EDE3', $key, OPENSSL_RAW_DATA);
        return base64_encode($encData);
    }

    function payviacard($data){
    
        error_reporting(E_ALL);
        ini_set('display_errors',1);
        
        
        $SecKey = env('FLW_SECRET_KEY');
        
        $key = getKey($SecKey); 
        
        $dataReq = json_encode($data);
        
        $post_enc = encrypt3Des( $dataReq, $key );
        
         $response = Http::withHeaders([
            "Content-Type"=> "application/json"
        ])->post(env('RAVE_BASE_URL') . '/flwv3-pug/getpaidx/api/charge', [
            'PBFPubKey' => env('FLW_PUBLIC_KEY'),
            'client' => $post_enc,
            'alg' => '3DES-24'
        ]);
        
        $response = json_decode($response, true);

        return $response;
    }

    public function validate_payment($flwRef, $otp)
    {

        //validate Transaction with OTP
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $response = Http::withHeaders([
            "Content-Type"=> "application/json"
        ])->post(env('RAVE_BASE_URL') . '/flwv3-pug/getpaidx/api/validatecharge', [
            "PBFPubKey" => env('FLW_PUBLIC_KEY'),
            "transaction_reference" => $flwRef,
            "otp" => $otp
        ]);

        
        $response = json_decode($response, true);

        return $response;

    }

    public function verify_payment($txRef)
    {
        //verify payment
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $response = Http::withHeaders([
            "Content-Type"=> "application/json"
        ])->post(env('RAVE_BASE_URL') . "/flwv3-pug/getpaidx/api/v2/verify", [
            'SECKEY' => env('FLW_SECRET_KEY'),
            "txref" => $txRef,
        ]);
       

        $response = json_decode($response, true);

        return $response;

    }



}