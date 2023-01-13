<?php

namespace App\Services;

use App\Refund;
use Illuminate\Http\Request;
use App\Services\FlutterwaveService;
//use App\Mail\RequestRefund;
//use Illuminate\Support\Facades\Mail;
//use App\Bank;
use App\Payment;
//use Auth;

class RefundService {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $flutterwaveService;

    public function __construct()
    {

        $this->flutterwaveService = new FlutterwaveService;
    }

    public function request_Refund(Request $request)
    {

        $request->validate([
            'transaction_id' => 'required|numeric',
            'payment_id' => 'required|numeric',
            'bank_id' => 'required|numeric',
            'narration' => 'required|string',
            'debit_currency' => 'required|string',

        ]);




        try {
            $transaction = Refund::where('transaction_id', $request->transaction_id)->first();
            $payment = Refund::where('payment_id', $request->payment_id)->first();

            if ($transaction || $payment) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You have made a refund request on this transaction already',

                ], 422);
            }

            $user = auth('api')->user();
            $user_id = $user->id;
            $input = $request->all();
            $input['user_id']   = $user_id;
            $input['status']   = false;

            $refund = Refund::create($input);

            //Mail::to($user->email)->send(new RequestWithdrawal($user->name, $request->transaction_id));
            //Mail::to('hello@paylidate.com')->send(new RequestWithdrawal('Admin', $request->transaction_id));
            //Mail::to('holyphilzy@gmail.com')->send(new RequestWithdrawal('Admin', $request->transaction_id));
            //Mail::to('sirlawattah@gmail.com')->send(new RequestWithdrawal('Lawrence', $request->transaction_id));

            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $refund
            ]);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function transfer_to_bank(Request $request)
    {
        //return 'sdof';

        try {
            $response = $this->flutterwaveService->transfer_to_bank($request->all());
            //return $response;  

            if ($response['status'] == 'success') {

                $payment = Payment::where('id', $request->payment_id)->update(['refund' => true]);


                $refund = Refund::find($request->id);
                $refund->update([
                    'status' => true,
                    'f_withdrawal_id' => $response['data']['id']
                ]);




                return response()->json([
                    'status' => $response['status'],
                    'message' => $response['message'],
                    'data' => $response['data']
                ]);
            }
            return response()->json([
                'status' => 'error',
                'message' => $response['message']

            ], 422);
        } catch (\Exception $e) {
            return $e;
        }
    }
}