<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Services\FlutterwaveService;
use App\Fulfilment;
use App\Mail\FulfilmentMail;
use App\Mail\SellerFulfilmentMail;
use Illuminate\Support\Facades\Mail;

class FulfilmentService
{

    public function initiate_fufilment($seller, $buyer, $t_id, $t_ref)
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

        $code = generateFufilment_code();

        Fulfilment::create([
            'user_id' => $buyer->id,
            'transaction_id' => $t_id,
            'transaction_ref' => $t_ref,
            'code' => $code,
            'status' => Fulfilment::PENDING
        ]);

        function generate_url($buyer_id, $trx_id)
        {
            $urlHash = base64_encode($buyer_id.":".$trx_id);

            $url = 'paylidate.com/fulfillment/'.$urlHash; 
            return $url;
        }

        Mail::to($buyer->email)->send(new FulfilmentMail($buyer, $code));
        Mail::to($seller->email)->send(new SellerFulfilmentMail($seller, generate_url(4, $t_id)));
    }
}