<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Payment;
use Illuminate\Http\Request;
use App\Product;
use App\Referer;
use App\Transaction;
use App\User;
use App\Wallet;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth('api')->user();
        if(!$user){
            return response()->json([
                'status'    => 'error',
                'message'   => 'please Log In'
            ], 401);
        }

        try {
            $payment_received = Transaction::where('user_id', $user->id)->get();
            $payments_made = Payment::where('user_id', $user->id)->get();
            $referer = Referer::where('user_id', $user->id)
                                ->orderBy('created_at', 'desc')->get();
            $balance = Wallet::where('user_id', $user->id)->get(['bonus', 'balance']);


            return response()->json([
                'status'    => 'success',
                'message'   => 'success',
                'data'      => [$payment_received, $payments_made, $referer, $balance]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => $e
            ], 400);
        }
    }
}
