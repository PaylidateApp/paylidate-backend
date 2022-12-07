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
            $payments_received = Transaction::where('user_id', $user->id)->value('amount')->sum();
            $payments_made = Payment::where('user_id', $user->id)->value('balance_after')->sum();
            $referer = Referer::where('user_id', $user->id)->value('amount')->sum();
            $balance = Wallet::where('user_id', $user->id)->value('balance')->get();
            $bonus = Wallet::where('user_id', $user->id)->value('bonus')->get();

            $data = [
                "status" => "200",
                "details" => [
                    "payment_received" => $payments_received,
                    "payment_made" => $payments_made,
                    "refer" => $referer,
                    "balance" => $balance,
                    "bonus" => $bonus,
                ]
            ];

            return response()->json($data, 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => $e
            ], 400);
        }
    }
}
