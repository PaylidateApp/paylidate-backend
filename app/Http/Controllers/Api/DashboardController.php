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
            $payments_received = Transaction::where('user_id', $user->id)->value('amount')->sum()->get();
            $payments_made = Payment::where('user_id', $user->id)->value('balance_after')->sum()->get();
            $referer = Referer::where('user_id', $user->id)->value('amount')->sum()->get();
            $balance = Wallet::where('user_id', $user->id)->value('amount')->sum()->get();

            $data = [
                "status" => "200",
                "details" => [
                    "payment_received" => $payments_received,
                    "payment_made" => $payments_made,
                    "refer" => $referer,
                    "balance" => $balance,
                ]
            ];

            return response()->json($data);

        } catch (\Exception $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => $e
            ], 400);
        }
    }
}
