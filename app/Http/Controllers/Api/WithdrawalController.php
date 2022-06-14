<?php

namespace App\Http\Controllers\Api;

use App\Withdrawal;
use App\Services\FlutterwaveService;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Auth;
use Illuminate\Http\Request;



class WithdrawalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $flutterwaveService;

    public function __construct(){

        $this->flutterwaveService = new FlutterwaveService;
    }


    public function index()
    {
        $withdrawal_requests = Withdrawal::with('user', 'transaction', 'payment', 'bank')->orderBy('created_at')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $withdrawal_requests
        ]); 
        
    }

    public function request_withdrawal(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|numeric|unique:transactions',
            'payment_id' => 'required|numeric|unique:payment',
            'user_bank_id' => 'required|numeric',
            'narration' => 'required|string',
            'debit_currency' => 'required|string',           
            
        ]);
            $user = auth('api')->user();
            $user_id = $user->id;
            $input = $request->all();
            $input['user_id']   = $user_id;
            
            $withdrawal = Withdrawal::create($input);
            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $withdrawal
            ]);
    }

    public function transfer_to_bank(Request $request){
        //return 'sdof';
        try{
        $response = $this->flutterwaveService->transfer_to_bank($request->account_bank, $request->account_number, $request->amount, $request->narration, $request->currency, $request->reference, $request->callback_url, $request->debit_currency);
          //return $response;  
        return response()->json([
            'status' => $response['status'],
            'message' => $response['message'],
            'data' => $response['data']
        ]);
        }
        catch (\Exception $e) {
            return $e;
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Withdrawal  $withdrawal
     * @return \Illuminate\Http\Response
     */
    public function show(Withdrawal $withdrawal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Withdrawal  $withdrawal
     * @return \Illuminate\Http\Response
     */
    public function edit(Withdrawal $withdrawal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Withdrawal  $withdrawal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Withdrawal $withdrawal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Withdrawal  $withdrawal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Withdrawal $withdrawal)
    {
        //
    }
}
