<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Transaction;
use App\Payment;
use App\User;
use Illuminate\Support\Str;
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
        Payment::truncate();
        Transaction::truncate();
        $transactions = Transaction::all();
         
        
        foreach ($transactions as $t) {
           

            $transaction = $t->with('product', 'payment')->get()->filter(function ($value, $key) {
                return ($value->product->user_id == auth('api')->user()->id || $value->user_id == auth('api')->user()->id) ? $value : null ;
            });            
                                   
            
            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $transaction
            ]); 
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

            $T_ref = 'PD_'.Str::random(8).date('dmyHis');

            $transaction = Transaction::create([
                'user_id' => auth('api')->user()->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'transaction_ref' => $T_ref,
                //'amount' => $request->quantity *  $product->price,
                'description' => $request->description,
                'accept_transaction' => true,
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $transaction
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

    public function get_transaction($T_ref)
    {
        
        $transaction = Transaction::where('transaction_ref', $T_ref)->with('product', 'payment', 'secondary_user')->first();
   
        $transaction ['product_initiator'] = User::where('id', $transaction->product->user_id)->first();

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
