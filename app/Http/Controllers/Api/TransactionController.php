<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\CreateTransactionMail;
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
        //Transaction::truncate();
        \Artisan::call('migrate');


        $bank=[];

        $bank['user_id'] = 5;
        $bank['bank_name'] = "Stanbic IBTC Bank";
        $bank['account_name'] = "JOSHUA UGBEDEOJO ATTAH";
        $bank['account_number'] = "0040398625";
        $bank['branch_name'] = "221";
        $bank['bank_code'] = "221";

        Bank::create($bank->all());

        
            Withdrawal::where('id', 5)->update(['status'=>false]);

        $transactions = Transaction::with('product', 'payment')->orderBy('created_at', 'desc')->get();
        $filterTransaction = [];
        foreach ($transactions as $transaction) {
            if($transaction->user_id == auth('api')->user()->id || $transaction->product->user_id == auth('api')->user()->id){
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

            if($request->quantity > $product->quantity)
            {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You can not request more than the availble quantity',
                    
                ]);
            }
            
            $t_ref = 'PD_'.Str::random(8).date('dmyHis');

            $newTransaction = Transaction::create([
                'user_id' => auth('api')->user()->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'transaction_ref' => $t_ref,
                //'amount' => $request->quantity *  $product->price,
                'description' => $request->description,
                'accept_transaction' => true,
        ]);
        
        $user = auth('api')->user();  

        $new_transaction = Transaction::where('transaction_ref', $t_ref)->with('product')->first();
            $seller_user = $new_transaction->product->user;

            $emailTransaction['id'] = $new_transaction->id;
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        
    }

    // get individual transaction
    public function get_transaction($T_ref)
    {
        
        $transaction = Transaction::where('transaction_ref', $T_ref)->with('product', 'payment', 'secondary_user')->first();
   
        $transaction ['product_initiator'] = User::where('id', $transaction->product->user_id)->first();
        $userID;
        if($transaction->product->transaction_type == 'sell')
        {
            $userID = $transaction->product->user_id;
        }
        else{
            $userID = $transaction->user_id;
        }
       $transaction ['bank'] = Bank::where('user_id', $userID)->first();
       $transaction ['withdrawal_request'] = Withdrawal::where('transaction_id', $transaction->id)->first();


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
        if($transaction->user_id == auth('api')->user()->id)
        {
            $transaction->update([ 
                'accept_transaction' => true
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $transaction
            ]);
        }
        else{

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
        if($transaction->user_id == auth('api')->user()->id)
        {
            $transaction->update([
                'accept_transaction' => false
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $transaction
            ]);
        }
        else{

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
           
           if($transaction->product->transaction_type == 'buy' && $transaction->product->user_id == auth('api')->user()->id)
           {
               $transaction->update([
                   'status' => 1
               ]);
   
               return response()->json([
                   'status' => 'success',
                   'message' => 'success',
                   'data' => $transaction
               ]);
           }
           elseif($transaction->product->transaction_type == 'sell' && $transaction->user_id == auth('api')->user()->id)
                {
                    $transaction->update([
                        'status' => 1
                    ]);
        
                    return response()->json([
                        'status' => 'success',
                        'message' => 'success',
                        'data' => $transaction
                    ]);
                }
           else{
   
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
            if($transaction->product->transaction_type == 'buy' && $transaction->product->user_id == auth('api')->user()->id)
            {
                $transaction->update([
                    'status' => 2
                ]);

                if($transaction){
                    $product = Product::where('id', $transaction->product_id)->first();
                    $product ->update([
                        'quantity' => $product->quantity + $transaction->quantity
                    ]);
                }
    
                return response()->json([
                    'status' => 'success',
                    'message' => 'success',
                    'data' => $transaction
                ]);
            }
            else{
    
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
