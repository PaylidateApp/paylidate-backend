<?php

namespace App\Http\Controllers\Api;

use App\ReferralWidrawal;
use App\Services\FlutterwaveService;
use App\Referer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ReferralWidrawalController extends Controller
{
    protected $flutterwaveService;

    public function __construct()
    {

        $this->flutterwaveService = new FlutterwaveService;
    }



    public function index()
    {
        try {
            $withdrawalRequests = ReferralWidrawal::with('user', 'bank')->orderBy('status')->get();


            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $withdrawalRequests
            ]);
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
    public function request_withdrawal(Request $request)
    {

        $user = auth('api')->user();
        $referal = Referer::where([['user_id', '=', $user->id], ['withdrawal_status', '=', false], ['amount', '>', 0]])->get();
        if ($referal->sum('amount') < 3000) {
            return response()->json([
                'status' => 'error',
                'message' => 'Amount must not be less than NGN 3,000',

            ], 400);
        }

        $ref_withdrawal = $request->all();
        $ref_withdrawal['user_id'] = $user->id;
        $ref_withdrawal['bank_id'] = $user->bank->id;
        $ref_withdrawal['amount'] = $referal->sum('amount');
        $ref_withdrawal['status'] = false;

        $referral_widrawal = ReferralWidrawal::create($ref_withdrawal);

        if ($referral_widrawal)
            $referal_bonus = Referer::where([['user_id', '=', $user->id], ['withdrawal_status', '=', false], ['amount', '>', 0]])->update([
                'withdrawal_status' => true
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $referal_bonus
        ]);
    }



    public function transfer_to_bank(Request $request)
    {
        //return 'sdof';

        try {
            $response = $this->flutterwaveService->transfer_to_bank($request->account_bank, $request->account_number, $request->amount, $request->narration, $request->currency, $request->reference, $request->debit_currency);
            //return $response;  

            if ($response['status'] == 'success') {



                $withdrawal = ReferralWidrawal::find($request->id);
                $withdrawal->update([
                    'status' => true,
                    'f_withdrawal_id' => $response['data']['id']
                ]);

                Referer::where('transfer_status', false)->update([
                    'transfer_status' => true
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
     * Display the specified resource.
     *
     * @param  \App\ReferralWidrawal  $referralWidrawal
     * @return \Illuminate\Http\Response
     */
    public function show(ReferralWidrawal $referralWidrawal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ReferralWidrawal  $referralWidrawal
     * @return \Illuminate\Http\Response
     */
    public function edit(ReferralWidrawal $referralWidrawal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ReferralWidrawal  $referralWidrawal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReferralWidrawal $referralWidrawal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ReferralWidrawal  $referralWidrawal
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReferralWidrawal $referralWidrawal)
    {
        //
    }
}
