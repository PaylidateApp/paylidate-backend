<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class VirtualCard extends Model
{
    protected $fillable = [
        'user_id','card_id','account_id','currency','label','default'
    ];

    function virtualCard($currency, $amount, $name){
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.env('FLW_SECRET_KEY')
        ])->post(env('FLW_BASE_URL').'/v3/virtual-cards', [
            "currency" => $currency,
            "amount" => $amount,
            "billing_name" => $name
        ]);

        return $response;
    }

    function getvirtualCard($card_id){
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.env('FLW_SECRET_KEY')
        ])->get(env('FLW_BASE_URL').'/v3/virtual-cards/'.$card_id);

        return $response;
    }

    function withdrawFromVirtualCard($card_id, $amount){
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.env('FLW_SECRET_KEY')
        ])->post(env('FLW_BASE_URL').'/v3/virtual-cards/'. $card_id .'/withdraw', [
            "amount" => $amount,
        ]);

        return $response;
    }

    // fund virtual card with payment
    function fundVirtualCard($card_id, $amount, $debit_currency = 'NGN'){
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.env('FLW_SECRET_KEY')
        ])->post(env('FLW_BASE_URL').'/v3/virtual-cards/'. $card_id .'\/fund', [
            "amount" => $amount,
            "debit_currency" => $debit_currency,
        ]);

        return $response;
    }
}
