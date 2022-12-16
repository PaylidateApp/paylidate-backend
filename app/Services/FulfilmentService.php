<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Services\FlutterwaveService;
use App\Fulfilment;
use App\Mail\FulfilmentMail;
use Illuminate\Support\Facades\Mail;

class FulfilmentService
{

    public function initiate_fufilment($seller, $buyer, $t_id)
    {
        function generateFufilment_code() {
            $number = mt_rand(10000, 99999); // better than rand()
        
            // call the same function if the barcode exists already
            if (barcodeNumberExists($number)) {
                return generateFufilment_code();
            }
        
            // otherwise, it's valid and can be used
            return $number;
        }
        
        function barcodeNumberExists($number) {
            return Fulfilment::where('code', $number)->exists();
        }

        Fulfilment::create([
            'user_id' => $buyer->id,
            'transaction_id' => $t_id,
            'code' => generateFufilment_code(),
            'status' => Fulfilment::PENDING
        ]);

        function generate_url($buyer_id, $trx_id)
        {
            $urlHash = base64_encode($buyer_id.":".$trx_id);

            $url = 'paylidate.com/fulfilment/'.$urlHash; 
            return $url;
        }

        Mail::to($buyer->email)->send(new FulfilmentMail($buyer, generateFufilment_code()));
        Mail::to($seller->email)->send(new FulfilmentMail($seller, generate_url($buyer->id, $t_id)));
    }
}