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

    public function initiate_fufilment($seller_mail, $seller_name, $buyer_mail, $buyer_name, $buyer_id, $t_id, $t_ref, $transaction_details)
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

        try {
            $code = generateFufilment_code();

        Fulfilment::create([
            'user_id' => $buyer_id,
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

        Mail::to($buyer_mail)->send(new FulfilmentMail($buyer_name, $code, $transaction_details));
        Mail::to($seller_mail)->send(new SellerFulfilmentMail($seller_name, generate_url($buyer_id, $t_id), $transaction_details));

        } catch (\Throwable $th) {
            throw $th;
        }
    }
}