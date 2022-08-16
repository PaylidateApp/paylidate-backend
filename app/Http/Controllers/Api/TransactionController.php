<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\CreateTransactionMail;
use App\Mail\ReportTransaction;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Transaction;
use App\Withdrawal;
use App\Bank;
use App\Payment;
use App\User;
use Illuminate\Support\Str;
use Auth;
use App\Product;
use App\Referer;
use Carbon\Carbon;
use App\Refund;

/**
 * @group  Transaction management
 *
 * APIs for Transaction
 */
class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Transaction::truncate()
        // \Artisan::call('migrate');


        $transactions = Transaction::with('product', 'payment',)->orderBy('created_at', 'desc')->get();
        $filterTransaction = [];
        foreach ($transactions as $transaction) {
            if ($transaction->user_id == auth('api')->user()->id || $transaction->product->user_id == auth('api')->user()->id) {
                $transaction['referral'] = Referer::where('id', $transaction->referer_id)->first();
                array_push($filterTransaction, $transaction);
                continue;
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $filterTransaction
        ]);
    }


    public function store(Request $request)
    {

        $product = Product::where('id', $request->product_id)->first();

        if ($request->quantity > $product->quantity) {
            return response()->json([
                'status' => 'error',
                'message' => 'You can not request more than the availble quantity',

            ]);
        }

        $refer_user = User::where('referral_token', $request->referral_token)->first();
        //return $request->all();

        if ($request->referral_token == auth('api')->user()->referral_token) {
            return response()->json([
                'status' => 'error',
                'message' => 'You cannot use your referral link to create a product',

            ], 401);
        }

        $referral_id = null;
        // this block of code check if there is referal banus, then it create a referal for that transaction
        if ($refer_user) {

            $referral = Referer::create([
                'user_id' => $refer_user->id,
                'amount' => 0.00,

            ]);

            $referral_id = $referral->id;
        }

        $t_ref = 'PD_' . Str::random(8) . date('dmyHis');

        Transaction::create([
            'user_id' => auth('api')->user()->id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'transaction_ref' => $t_ref,
            'referer_id' => $referral_id,
            'description' => $request->description,
            'accept_transaction' => true,
        ]);

        $user = auth('api')->user();

        $new_transaction = Transaction::where('transaction_ref', $t_ref)->with('product', 'referral')->first();
        $seller_user = $new_transaction->product->user;

        $emailTransaction['id'] = $new_transaction->id;
        $emailTransaction['referral'] = $new_transaction->referral;
        $emailTransaction['transaction_ref'] = $t_ref;
        $emailTransaction['product_id'] = $new_transaction->product_id;
        $emailTransaction['product_name'] = $new_transaction->product->name;
        $emailTransaction['product_number'] = $new_transaction->product->product_number;
        $emailTransaction['type'] = $new_transaction->product->type;
        $emailTransaction['total_quantity'] = $new_transaction->quantity;
        $emailTransaction['total_price'] = $new_transaction->product->price * $new_transaction->quantity;
        $emailTransaction['description'] = $new_transaction->description ? $new_transaction->description : 'No description';

        Mail::to($user->email)->send(new CreateTransactionMail($user, $emailTransaction));
        Mail::to($seller_user->email)->send(new CreateTransactionMail($seller_user, $emailTransaction));


        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $new_transaction
        ]);
    }


    // get individual transaction
    public function get_transaction($T_ref)
    {

        $transaction = Transaction::where('transaction_ref', $T_ref)->with('product', 'payment', 'secondary_user')->first();

        if ($transaction->transaction_reported_at && $transaction->status == 3 && Carbon::parse($transaction->transaction_reported_at)->addHours(24)->isPast()) {

                $transaction->update([
                    'status' => 3
                ]);                
            
        }
        
        $transaction['product_initiator'] = User::where('id', $transaction->product->user_id)->first();
        $userID;
        if ($transaction->product->transaction_type == 'sell') {
            $userID = $transaction->product->user_id;
        } else {
            $userID = $transaction->user_id;
        }
        
        $seller =User::where('id', $userID)->first();

        $transaction['seller_email'] = $seller->email;            
        $transaction['referral'] = Referer::where('id', $transaction->referer_id)->first();
        $transaction['bank'] = Bank::where('user_id', auth('api')->user()->id)->first();
        $transaction['withdrawal_request'] = Withdrawal::where('transaction_id', $transaction->id)->first();
        $transaction['refund'] = Refund::where('transaction_id', $transaction->id)->first();


        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $transaction
        ]);
    }

    // accept transaction
    public function accept($id)
    {
        $transaction = Transaction::where('id', $id)->first();
        if ($transaction->user_id == auth('api')->user()->id) {
            $transaction->update([
                'accept_transaction' => true
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $transaction
            ]);
        } else {

            return response()->json([
                'status' => 'error',
                'message' => 'Not Allow',

            ], 401);
        }

        //Mail::to($user)->send(new CreateProductMail($user, $product));

    }


    // Decline transaction
    public function decline($id)
    {
        $transaction = Transaction::where('id', $id)->first();
        if ($transaction->user_id == auth('api')->user()->id) {
            $transaction->update([
                'accept_transaction' => false
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $transaction
            ]);
        } else {

            return response()->json([
                'status' => 'error',
                'message' => 'Not Allow',

            ], 401);
        }

        //Mail::to($user)->send(new CreateProductMail($user, $product));

    }

    // confirm transaction completion
    public function confirm($id)
    {
        $transaction = Transaction::where('id', $id)->with('product')->first();
        
            // This condition checks the id if the authenticated user is one buying thr poduct
        if ($transaction->product->transaction_type == 'buy' && $transaction->product->user_id == auth('api')->user()->id) {
            $transaction->update([
                'status' => 1
            ]);


            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $transaction
            ]);
        } elseif ($transaction->product->transaction_type == 'sell' && $transaction->user_id == auth('api')->user()->id) {
            $transaction->update([
                'status' => 1
            ]);
            Referer::where('id', $transaction->referer_id)->update([
                'transaction_status' => true
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $transaction
            ]);
        } else {

            return response()->json([
                'status' => 'error',
                'message' => 'Not Allow',

            ], 401);
        }

        //Mail::to($user)->send(new CreateProductMail($user, $product));

    }


    // Report Transaction
    public function reportTransaction($id, Request $request)
    {
        $request->validate([
            
            'sellerEmail' => 'required|string|email',
            'report' => 'required|string|min:5',
            
        ]);
        $transaction = Transaction::where('id', $id)->first();
        if (($transaction->product->transaction_type == 'buy' && $transaction->product->user_id == auth('api')->user()->id) || ($transaction->product->transaction_type == 'sell' && $transaction->user_id == auth('api')->user()->id)) {
            $transaction->update([
                'status' => 3
            ]);
            
            if($transaction){
                
            $user = User::where('email', $request->sellerEmail)->first();
             Mail::to($request->sellerEmail)->send(new ReportTransaction($user->name, $transaction->transaction_reff, 'report', $request->report));
             
             Mail::to('hello@paylidate.com')->send(new ReportTransaction('Admin', $transaction->transaction_ref, 'report', $request->report));
             Mail::to('holyphilzy@gmail.com')->send(new ReportTransaction('Admin', $transaction->transaction_ref, 'report', $request->report));
             Mail::to('sirlawattah@gmail.com')->send(new ReportTransaction('Lawrence', $transaction->transaction_ref, 'report', $request->report));
             }

            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $transaction
            ]);
        } else {

            return response()->json([
                'status' => 'error',
                'message' => 'Not Allow',

            ], 401);
        }

        //Mail::to($user)->send(new CreateProductMail($user, $product));

    }

    // resolve transaction report
    public function resloveReport($id, $sellerEmail)
    {
        $transaction = Transaction::where('id', $id)->first();
        if (($transaction->product->transaction_type == 'buy' && $transaction->product->user_id == auth('api')->user()->id) || ($transaction->product->transaction_type == 'sell' && $transaction->user_id == auth('api')->user()->id)) {
            $transaction->update([
                'status' => 2
            ]);
            
            $user = User::where('email', $sellerEmail)->first();
            Mail::to($sellerEmail)->send(new ReportTransaction($user->name, $transaction->transaction_reff, 'resolve'));

            Mail::to('hello@paylidate.com')->send(new ReportTransaction('Admin', $transaction->transaction_ref, 'resolve'));
            Mail::to('holyphilzy@gmail.com')->send(new ReportTransaction('Admin', $transaction->transaction_ref, 'resolve'));
            Mail::to('sirlawattah@gmail.com')->send(new ReportTransaction('Lawrence', $transaction->transaction_ref, 'resolve'));


            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $transaction
            ]);
        } else {

            return response()->json([
                'status' => 'error',
                'message' => 'Not Allow',

            ], 401);
        }

        //Mail::to($user)->send(new CreateProductMail($user, $product));

    }
    // cancel transaction
    public function cancel($id)
    {
        $transaction = Transaction::where('id', $id)->first();
        if (($transaction->product->transaction_type == 'buy' && $transaction->product->user_id == auth('api')->user()->id) || ($transaction->product->transaction_type == 'sell' && $transaction->user_id == auth('api')->user()->id)) {
            $transaction->update([
                'status' => 2
            ]);

            if ($transaction) {
                $product = Product::where('id', $transaction->product_id)->first();
                $product->update([
                    'quantity' => $product->quantity + $transaction->quantity
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $transaction
            ]);
        } else {

            return response()->json([
                'status' => 'error',
                'message' => 'Not Allow',

            ], 401);
        }

        //Mail::to($user)->send(new CreateProductMail($user, $product));

    }


    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
