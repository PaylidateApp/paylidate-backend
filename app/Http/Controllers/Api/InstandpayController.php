<?php

namespace App\Http\Controllers\Api;

use App\Instandpay;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Services\FlutterwaveService;


class InstandpayController extends Controller
{

    protected $flutterwaveService;

    public function __construct(){

        $this->flutterwaveService = new FlutterwaveService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $input['otp']   = random_int(100000, 999999);
        $body = 'Paylidate payment from Philemon. Visit https://paylidate.com/payments/hy853g65 to withdraw. Your withdrawal pin is 769056';
        //return $body ;

        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->post(
            'https://www.bulksmsnigeria.com/api/v2/sms/create',
            [   
                    'api_token'=> '7XyAWuScqHNoALX5xvDKPl9YUlEKsR5tT2pTjKIf9SDnrqXUgdi1nYLBwgIG',
                    'to'=> '08091552738',
                    'from'=> 'Paylidate',
                    'body'=> $body,
                    
            
            ]
        );

        return $response; 
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
    public function transfer(Request $request)
    {        
        $request->validate([
            
            'receiver_number' => 'required|numeric',
            'sender_email' => 'required|string|email',
            'amount' => 'required|numeric',
            'sender_name' => 'required|string|min:3',
            
        ]);

        try{
        $input = $request->all();
        $input['otp']   = random_int(100000, 999999);
        $input['link_token']   = 'PD_IP_' . Str::random(4) . date('dmyHis');
        $input['tracking_id']   = random_int(100000, 999999);


        $transfer = Instandpay::create($input);

        $body = 'Paylidate payment of NGN'. $input['amount'].' from '. $input['sender_name']. '. Visit https://paylidate.com/payments/'. $input['link_token'].' to withdraw. Your withdrawal pin is 769056';

        Http::withHeaders([
            'Accept' => 'application/json',
        ])->post(
            'https://www.bulksmsnigeria.com/api/v2/sms/create',
            [   
                    'api_token'=> '7XyAWuScqHNoALX5xvDKPl9YUlEKsR5tT2pTjKIf9SDnrqXUgdi1nYLBwgIG',
                    'to'=> '09079603505',
                    'from'=> 'Paylidate',
                    'body'=> $body,
                    
            
            ]
        );

        //return $response;
        
        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $transfer
        ], 200);
        }
        catch (\Exception $e) {
            return $e;
        }

    }

    public function verify(Request $request)
    {
        $request->validate([
            
            'otp' => 'required|numeric',
            'link_token' => 'required|string',
            'bank_code' => 'required|numeric',         
            'account_number' => 'required|numeric',
                     
        ]);
        try{
        $instandpay = Instandpay::where('link_token', $request->link_token)->first();
        if($instandpay && $instandpay->otp == $request->otp){
            $response = $this->flutterwaveService->verifyBankAccountNumber($request->account_number, $request->bank_code);
        

                //'data' => $response['status']
            if($response['status'] != 'success'){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Sorry, that account number is invalid, please check and try again',
                    
                ], 400);
            }
            
            $withdrawal_pin = date('dmyHis');
            $instandpay->update([
                'withdrawal_pin' => $withdrawal_pin,
                'bank_code' => $request->bank_code,
                'account_name' =>  $response['data']['account_name'],
                'account_number' => $request->account_number
            ]);
            
            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $instandpay
            ], 200);
        }

        return response()->json([
            'status' => 'Error',
            'message' => 'Invalid otp',
           
        ], 400);
        }
        catch (\Exception $e) {
            return $e;
        }

    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'withdrawal_pin' => 'required|numeric',            
        ]);
        try{
        $instandpay = Instandpay::where('withdrawal_pin', $request->withdrawal_pin)->first();
        if(!$instandpay){
            return response()->json([
                'status' => 'Error',
                'message' => 'Invalid withdrawal code',
                
            ], 400);
        }
        $input = [];
        $input['account_bank']   = $instandpay['bank_code'];
        $input['account_number']   = $instandpay['account_number'];
        $input['amount']   = $instandpay['amount'];
        $input['narration']   = $instandpay['description'];
        
        $response = $this->flutterwaveService->transfer_to_bank($input);
            //return $response;  

            if ($response['status'] == 'success') {
            $instandpay->update([
                
                'status' => true
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Transfer successful',
            'data' => $response['data']
        ], 200);
        }
        catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Instandpay  $instandpay
     * @return \Illuminate\Http\Response
     */
    public function edit(Instandpay $instandpay)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Instandpay  $instandpay
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Instandpay $instandpay)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Instandpay  $instandpay
     * @return \Illuminate\Http\Response
     */
    public function destroy(Instandpay $instandpay)
    {
        //
    }
}
