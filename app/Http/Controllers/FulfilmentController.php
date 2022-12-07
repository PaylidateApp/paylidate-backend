<?php

namespace App\Http\Controllers;

use App\Fulfilment;
use Illuminate\Http\Request;

class FulfilmentController extends Controller
{

    public function generate_code()
    {
        function generateBarcodeNumber() {
            $number = mt_rand(10000, 99999); // better than rand()
        
            // call the same function if the barcode exists already
            if (barcodeNumberExists($number)) {
                return generateBarcodeNumber();
            }
        
            // otherwise, it's valid and can be used
            return $number;
        }
        
        function barcodeNumberExists($number) {
            // query the database and return a boolean
            // for instance, it might look like this in Laravel
            return Fulfilment::where('code', $number)->exists();
        }
    }
}
