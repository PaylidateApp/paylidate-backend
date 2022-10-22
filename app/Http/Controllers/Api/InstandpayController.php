<?php

namespace App\Http\Controllers\Api;
require __DIR__ . '/twilio-php-main/src/Twilio/autoload.php';

use App\Instandpay;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Services\FlutterwaveService;
use App\Services\ProvidusNIPService;
use Illuminate\Support\Facades\DB;
use Twilio\Rest\Client;


class InstandpayController extends Controller
{

    protected $flutterwaveService;
    protected $providusNIPService;

    public function __construct(){

        $this->flutterwaveService = new FlutterwaveService;
        $this->providusNIPService = new ProvidusNIPService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $data['name'] = 'CustomerWize';
        // return $this->providusNIPService->getBVNDetails('22222222222');


        //return $response;
        $account_sid = getenv("TWILIO_SID");
        $auth_token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_number = getenv("TWILIO_NUMBER");
        return [$account_sid, $auth_token,$twilio_number];
        //$client = new Client($account_sid, $auth_token);
        // try{

        //     $message = $client->messages->create('+2347034511900', ['from' => $twilio_number, 'body' => 'testing instant pay last']);
        //     return $message;
        // }
        
        // catch (\Exception $e) {

        //     return response()->json([
        //         'status' => 'error',
        //         'message' => $e
        //     ]);
        // }
    }
    public function receive()
    {
        $transaction = Instandpay::where('receiver_id', auth('api')->user()->id)
        ->orderBy('created_at', 'desc')
        ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $transaction
        ], 200);
    }

    public function send()
    {
        $transaction = Instandpay::where('user_id', auth('api')->user()->id)
        ->orderBy('created_at', 'desc')
        ->get();


        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $transaction
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function verify_user($phone_number)
    {
        if(strlen($phone_number) != 11){
            return response()->json([
                'status' => 'Error',
                'message' => 'Receiver is not a paylidate user',
               
            ], 400);
        }
        $reciver = User::where('phone', $phone_number)->first();
        if(!$reciver){
            return response()->json([
                'status' => 'Error',
                'message' => 'Receiver is not a paylidate user',
               
            ], 400);
        }

        if(!$reciver->phone == $phone_number){
            return response()->json([
                'status' => 'Error',
                'message' => 'You can not send money to yourself',
               
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'name' => $reciver->name
        ], 200);
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
            'payment_ref' => 'required|string',
            
        ]);
        //$str = substr($request->receiver_number, 1);
        //return $str;
        $receiver = User::where('phone', $request->receiver_number)->first();
        if(!$receiver){
            return response()->json([
                'status' => 'Error',
                'message' => 'Receiver is not a paylidate user',
                
            ], 400);
            
        }
        if(auth('api')->user()->phone == $request->receiver_number){
            return response()->json([
                'status' => 'Error',
                'message' => 'You can not send money to yourself',
                
            ], 400);
        }
        
        try{
            $payment_ref = Instandpay::where('payment_ref', $request->payment_ref)->first();
            if($payment_ref){
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Invalid payment_ref',
                   
                ], 400);
            }
        $input = $request->all();
        $input['withdrawal_pin']   = random_int(100000, 999999);
        $input['link_token']   = 'PD_IP_' . Str::random(4) . date('dmyHis');
        $input['tracking_id']   = random_int(100000, 999999). date('dmyHis');
        $input['user_id']   = auth('api')->user()->id;
        $input['sender_name']   = auth('api')->user()->name;
        $input['receiver_name']   = $receiver->name;
        $input['receiver_id']   = $receiver->id;
        
        //return $transfer;

        $body = 'Paylidate payment of NGN'. $input['amount'].' from '. $input['sender_name']. '. Visit https://paylidate.com/recieve-instant-funds/'. $input['link_token'].' to withdraw. Your withdrawal pin is '. $input['withdrawal_pin']. '. Tracking id '. $input['tracking_id'];

        // $account_sid = getenv("TWILIO_SID");
        // $auth_token = getenv("TWILIO_AUTH_TOKEN");
        // $twilio_number = getenv("TWILIO_NUMBER");
        $account_sid = "ACe205346b3af18acd07295b3c0f38b3f0";
        $auth_token = "d498da28bbee4d04dee31f0c1eba0f9c";
        $twilio_number = "+18608132858";
        $client = new Client($account_sid, $auth_token);
        
        $frmNum = '+234' . substr($request->receiver_number, 1);
       // $message = $client->messages->create($frmNum, ['from' => $twilio_number, 'body' => $body]);
        $client->messages->create($frmNum, ['from' => $twilio_number, 'body' => $body]);
        

        // Http::withHeaders([
        //     'Accept' => 'application/json',
        // ])->post(
        //     'https://www.bulksmsnigeria.com/api/v2/sms/create',
        //     [   
        //             'api_token'=> '7XyAWuScqHNoALX5xvDKPl9YUlEKsR5tT2pTjKIf9SDnrqXUgdi1nYLBwgIG',
        //             'to'=> $input['receiver_number'],
        //             'from'=> 'Paylidate',
        //             'body'=> $body,                    
            
        //     ]
        // );
        $transfer = Instandpay::create($input);

     
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

    // Verify user information
    public function verify(Request $request)
    {
        $request->validate([
            
            'withdrawal_pin' => 'required|numeric',
            'link_token' => 'required|string',
            'bank_code' => 'required|numeric',         
            'account_number' => 'required|numeric',
            
        ]);
        //return $request->all();
        $user = auth('api')->user();
        try{
        $instandpay = Instandpay::where('link_token', $request->link_token)->first();
        if($user->id != $instandpay->receiver_id){
            return response()->json([
                'status' => 'error',
                'message' => 'unauthorized',
            ], 401);
        }
        if($instandpay && $instandpay->withdrawal_pin == $request->withdrawal_pin){
            $response = $this->flutterwaveService->verifyBankAccountNumber($request->account_number, $request->bank_code);
        
                //'data' => $response['status']
            //  if($response['status'] != 'success'){
            //      return response()->json([
            //          'status' => 'error',
            //          'message' => 'Sorry, that account number is invalid, please check and try again',
                    
            //      ], 400);
            //  }
            if($instandpay->status != true){

                $instandpay->update([
                    'bank_code' => $request->bank_code,
                    'account_name' =>  $request->account_name,
                    'account_number' => $request->account_number,
                    'bank_name' => $request->bank_name
                ]);
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $instandpay
            ], 200);
        }

        return response()->json([
            'status' => 'Error',
            'message' => 'Invalid withdrawal pin',
           
        ], 400);
        }
        catch (\Exception $e) {
            return $e;
        }

    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'id' => 'required',            
        ]);
        //return "efdwfd";
        try{
        $instandpay = Instandpay::where('id', $request->id)->first();
        if(!$instandpay){
            return response()->json([
                'status' => 'Error',
                'message' => 'Invalid withdrawal code',
                
            ], 400);
        }

        if($instandpay["status"] == true){
            return response()->json([
                'status' => 'Error',
                'message' => 'This money has been withdrawn',
                
            ], 400);
        }
        $input = [];
        $input['account_bank']   = $instandpay['bank_code'];
        $input['account_number']   = $instandpay['account_number'];
        $input['amount']   = $instandpay['amount'];
        $input['narration']   = $instandpay['description'];
        
        $response = $this->flutterwaveService->transfer_to_bank($input);
           // return $response;  

            if ($response['status'] == 'success') {
            $instandpay->update([
                
                'status' => true
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Transfer successful',
                'data' => $instandpay
            ], 200);
        }
        
        return response()->json([
            'status' => 'error',
            'message' => 'Transfer Error',
            
        ], 400);
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
