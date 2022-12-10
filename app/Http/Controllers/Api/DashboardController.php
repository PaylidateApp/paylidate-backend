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

            $payments_rec = Transaction::where('status', 1)
            ->join('products', 'transactions.product_id', '=', 'products.id')
            ->where('products.transaction_type', '=', 'sell')
            ->where('transactions.user_id', $user->id)
            ->select('transactions.amount')
            ->get();

            $payments_received = $payments_rec->sum('amount');
            
                        
            $payments_m = Transaction::where('status', 1)
            ->join('products', 'transactions.product_id', '=', 'products.id')
            ->where('products.transaction_type', '=', 'buy')
            ->where('transactions.user_id', $user->id)
            ->select('transactions.amount')
            ->get();
            $payments_made = $payments_m->sum('amount');

            $referer = Referer::where('user_id', $user->id)->sum('amount');
            $balance = Wallet::where('user_id', $user->id)->first('balance');
            $ba = empty($balance) ? 0 : $balance;
            $bonus = Wallet::where('user_id', $user->id)->first('bonus');
            $bo = empty($bonus) ? 0 : $bonus;

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
                    'payments_made' => $payments_made,
                    'balance' => $ba,
                    'bonus'   => $bo,
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
