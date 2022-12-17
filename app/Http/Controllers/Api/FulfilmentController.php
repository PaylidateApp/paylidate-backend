<?php

namespace App\Http\Controllers\Api;

use App\Fulfilment;
use App\Http\Controllers\Controller;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;

class FulfilmentController extends Controller
{
    public function get_transaction($hash)
    {
        $urlHash = explode(":", base64_decode($hash));

        $transaction_details = Transaction::where('id', $urlHash[1])->with('product')->first();

        $product_number = $transaction_details->product->product_number;

        $product_description = $transaction_details->product->description ? $transaction_details->product->description : 'No description';

        $product_name = $transaction_details->product->name;

        $buyers_s_d = $transaction_details->description ? $transaction_details->description : 'No description';

        $buyers_name = User::where('id', $urlHash[0])->first('name');

        $sellers_name = $transaction_details->product->user->name;

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => [
                'product_number' => $product_number,
                'product_name' => $product_name,
                'product_description' => $product_description,
                'sellers_name' => $sellers_name,
                'buyers_name' => $buyers_name,
                'buyers_s&d' => $buyers_s_d,
            ]
            ], 200);
    }

    public function confirm_fufilment($hash, Request $request)
    {
        $urlHash = explode(":", base64_decode($hash));

        $fulfilment = Fulfilment::where('transaction_id', $urlHash[1])->first('code');

        if($request->code == $fulfilment){
            Fulfilment::where('transaction_id', $urlHash[1])->where('user_id', $urlHash[0])->update([
                'status' => Fulfilment::SUCCESSFUL
            ]);
            Transaction::where('id', $urlHash[1])->update([
                'status' => 1
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid Code'
        ], 400);
    }
}
