<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use App\Wallet;
use Auth;
use App\Services\walletService;

class AccountController extends Controller
{

    protected $walletService;

    public function __construct()
    {

        $this->walletService = new walletService;
    }


    public function index()
    {

        // $response = Curl::to('https://api.flutterwave.com/v3/virtual-account-numbers/'. $account->ref)
        //     ->withHeader('Content-Type: application/json')
        //     ->withHeader('Authorization: Bearer FLWSECK_TEST-2b3f3862386bce594393f94c261f8184-X')
        //     ->asJson( true )
        //     ->get();

        try {
            $wallet = Wallet::where('user_id', auth('api')->user()->id)->first();
            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $wallet
            ], 200);
        } catch (\Exception $e) {
            return $e;
        }
    }

    // create a wallet with virtual account
    public function create()
    {
        try {

            $wallet = $this->walletService->createWallet(auth('api')->user()->id, auth('api')->user()->name);
            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $wallet
            ], 201);
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function creditWalletByTransfer(Request $request)
    {
        try {
            $virtual_account_number = Wallet::where('id', $request->id)->first();
            $session_id = $request->sessionId;

            // rejected for invalid header
            $header = $request->header('X-Auth-Signature');
            if ($header != "dvfvbvshj" || !$virtual_account_number) {
                return response()->json([
                    "requestSuccessful" => true,
                    "sessionId" => $session_id,
                    "responseMessage" => "rejected transaction",
                    "responseCode" => "02"
                ]);
            }

            // duplicate
            $duplicate = true;
            if ($duplicate) {
                return response()->json([
                    "requestSuccessful" => true,
                    "sessionId" => $session_id,
                    "responseMessage" => "duplicate transaction",
                    "responseCode" => "01"
                ]);
            }

            // credit wallet
            $creditWallet = $this->walletService->creditWalletByAccountNumber($request->accountNumber, $request->transactionAmount);

            $successful = true;
            if ($successful) {
                return response()->json([
                    "requestSuccessful" => true,
                    "sessionId" => $session_id,
                    "responseMessage" => "success",
                    "responseCode" => "00"
                ]);
            }
        } catch (\Exception $e) {
            return $e;
        }
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
