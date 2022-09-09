<?php

namespace App\Http\Controllers\Api;

use App\Refund;
use Illuminate\Http\Request;
use App\Services\FlutterwaveService;
use App\Http\Controllers\Controller;
//use App\Mail\RequestRefund;
//use Illuminate\Support\Facades\Mail;
//use App\Bank;
use App\Payment;
//use Auth;

class RefundController extends Controller
{
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


    public function index()
    {
        try {
            $RefundRequests = Refund::with('user', 'transaction', 'payment', 'bank')->orderBy('created_at')->get();


            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $RefundRequests
            ]);
        } catch (\Exception $e) {
            return $e;
        }
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
     * @param  \App\Refund  $refund
     * @return \Illuminate\Http\Response
     */
    public function show(Refund $refund)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Refund  $refund
     * @return \Illuminate\Http\Response
     */
    public function edit(Refund $refund)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Refund  $refund
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Refund $refund)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Refund  $refund
     * @return \Illuminate\Http\Response
     */
    public function destroy(Refund $refund)
    {
        //
    }
}
