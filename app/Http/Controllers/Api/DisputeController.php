<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use App\Mail\DisputeMail;
use Illuminate\Support\Facades\Mail;
use App\Dispute;
use App\User;
use App\Transaction;
use Auth;

class DisputeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTransactionDisputes($transaction_id)
    {        

        $dispute = Dispute::where('transaction_id', $transaction_id)->with('user', 'transaction')
        ->orderBy('created_at', 'desc')
        ->get();


        if(!$dispute){
            return response()->json([
                'status' => 'Not found',
                'message' => 'Not found',
                
            ], 404);
        }
        $transaction = Transaction::where('id', $transaction_id)->with('product')->first();
        
        if($transaction->user_id == auth('api')->user()->id || $transaction->product->user_id == auth('api')->user()->id  )        
        {
            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $dispute
            ]);

        }

        return response()->json([
            'status' => 'Not allow',
            'message' => 'Unauthorize',
            
        ], 401);
    }
    public function index($transaction_id)
    {
      

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
    public function open_dispute(Request $request)
    {
        $request->validate([
            'subject' => 'required|string',
            'description' => 'required|string',
            //'transaction' => 'required',
            
        ]);

        try {
            $user = auth('api')->user();
            $user_id = $user->id;
            
            $input['subject']   = $request->subject;
            $input['user_id']   = $user_id;
            $input['transaction_id']   = $request->transaction['id'];
            $input['dispute']   = $request->description;           
                      
            $dispute = Dispute::create($input);
            if($dispute){
                $transaction = Transaction::where('id', $request->transaction['id'])->first();
                $transaction->update([
                    'dispute' => true
                ]);

            }

            $total_price = null;
            if(empty($request->transaction['payment']))
            {
                $total_price = $request->transaction['product']['price'] * $request->transaction['quantity'];
            }
            else{
                $total_price = $request->transaction['amount'];
                
            }

            $newTransaction['id'] = $request->transaction['id'];
            $newTransaction['transation_ref'] = $request->transaction['transaction_ref'];
            $newTransaction['product_id'] = $request->transaction['product_id'];
            $newTransaction['product_name'] = $request->transaction['product']['name'];
            $newTransaction['product_number'] = $request->transaction['product']['product_number'];
            $newTransaction['type'] = $request->transaction['product']['type'];
            $newTransaction['total_quantity'] = $request->transaction['quantity'];
            $newTransaction['total_price'] = $total_price;
            $newTransaction['description'] = $request->transaction['description'] ? $request->transaction['description'] : 'No description';

            $user1 = User::where('id', $request->transaction['user_id'])->first();
            $user2 = User::where('id', $request->transaction['product']['user_id'])->first();
            
            
            $admin_email = 'hello@paylidate.com';


            Mail::to($user1->email)->send(new DisputeMail($request->subject, $request->description, $newTransaction, $user1->name));
            Mail::to($user2->email)->send(new DisputeMail($request->subject, $request->description, $newTransaction,  $user2->name));
            Mail::to($admin_email)->send(new DisputeMail($request->subject, $request->description, $newTransaction, 'ADMIN'));

            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $dispute
            ]);

        } 

        catch (Exception $e) {
            return $e;
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return 'dfdwofod';
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
