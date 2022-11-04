<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use App\Wallet;
use App\WalletsettlementId;
use Auth;
use App\Services\ProvidusNIPService;
use App\Services\WalletService;

class WalletController extends Controller
{

    protected $walletService;
    protected $providusNIPService;

    public function __construct()
    {

        $this->walletService = new WalletService;
        $this->providusNIPService = new ProvidusNIPService;
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
            if (!$wallet) {
                $wallet = $this->walletService->createWallet(auth('api')->user()->id, auth('api')->user()->name);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'successful',
                'data' => $wallet
            ], 200);
        } catch (\Exception $e) {
            return $e;
        }
    }

    // create a wallet with virtual account
    public function create(Request $request)
    {
        try {

            $wallet = Wallet::where('user_id', auth('api')->user()->id)->first();
            if ($wallet) {
                return response()->json([
                    'status' => 'Eror',
                    'message' => 'This user already has an account',
                ], 400);
            }
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

            $session_id = $request->sessionId;
            $settlement_id = $request->settlementId;

            $virtual_account_number = Wallet::where('account_number', $request->accountNumber)->first();
            $settlement = WalletsettlementId::where('settlementId', $settlement_id)->first();

            $verifyTransaction = $this->providusNIPService->verifyTransactionBySettlementId('204210202000000700001');
            $verifyTransaction = (json_decode($verifyTransaction));

            //return $verifyTransaction->sessionId;

            $app_header = getenv("X_AUTH_SIGNATURE");
            // rejected for invalid header
            $header = $request->header('X-Auth-Signature');
            if ($header != $app_header || !$virtual_account_number || $request->transactionAmount < 1) {
                return response()->json([
                    "requestSuccessful" => true,
                    "sessionId" => $session_id,
                    "responseMessage" => "rejected transaction",
                    "responseCode" => "02"
                ]);
            }

            // duplicate
            if ($settlement) {
                return response()->json([
                    "requestSuccessful" => true,
                    "sessionId" => $session_id,
                    "responseMessage" => "duplicate transaction",
                    "responseCode" => "01"
                ]);
            }


            // storing settlementId
            WalletsettlementId::create([
                'wallet_id' => $virtual_account_number->id,
                'settlementId' => $settlement_id,


            ]);

            // credit wallet
            $this->walletService->creditWalletByAccountNumber($request->accountNumber, $request->transactionAmount);

            return response()->json([
                "requestSuccessful" => true,
                "sessionId" => $session_id,
                "responseMessage" => "success",
                "responseCode" => "00"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "requestSuccessful" => true,
                "sessionId" => $session_id,
                "responseMessage" => "rejected transaction",
                "responseCode" => "02"
            ]);
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
