<?php

namespace App\Http\Controllers\Api;

use App\Dispute;
use App\Http\Controllers\Controller;
use App\Payment;
use Illuminate\Http\Request;
use App\Product;
use App\Referer;
use App\Refund;
use App\Transaction;
use App\User;
use App\Wallet;
use App\Withdrawal;

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

            // $payments_rec = Transaction::where('user_id', $user->id)->where('status', 1)->with('product')->get();
            // foreach($payments_rec as $payment){
            //     $amount=0;
            //     if($payment->transaction_type == 'sell'){
            //         $amount += $payment->amount;
            //         return $amount;
            //     }
            //     return $payments_received = $amount;
            // }


            // $payments_m = Transaction::where('user_id', $user->id)->where('status', 1)->with('product')->get();
            // foreach($payments_m as $payment){
            //     $amount=0;
            //     if($payment->transaction_type == 'buy'){
            //         $amount += $payment->amount;
            //         return $amount;
            //     }
            //     return $payments_made = $amount;
            // }




            $payments_received = Transaction::where('user_id', $user->id)->where('status', 1)->sum('amount');
            
            $payments_made = Payment::where('user_id', $user->id)->with('transaction')->get();
            $payments_m = $payments_made->reduce(function ($carry, $item) {
                    return $carry += empty($item->transaction->amount) ? 0 : $item->transaction->amount;
                
            },0);

            $referer = Referer::where('user_id', $user->id)->sum('amount');
            $balance = Wallet::where('user_id', $user->id)->get('balance');
            $bonus = Wallet::where('user_id', $user->id)->get('bonus');

            //Notification
            $active_disputes = Dispute::where('user_id', $user->id)->where('dispute_solved', false)->count();
            $pending_withdrawals = Withdrawal::where('user_id', $user->id)->where('status', false)->count();
            $active_transaction = Transaction::where('user_id', $user->id)->where('status', 0)->count();
            $pending_refunds = Refund::where('user_id', $user->id)->where('status', false)->count();

            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data'    => [
                    'payments_received' => $payments_received,
                    'payments_made' => $payments_m,
                    'balance' => $balance,
                    'bonus'   => $bonus,
                    'refer'   => $referer,
                    'active_dsiputes' => $active_disputes,
                    'pending_withdrawals' => $pending_withdrawals,
                    'active_transaction' => $active_transaction,
                    'pending_refunds' => $pending_refunds
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => $e
            ], 400);
        }
    }
}
