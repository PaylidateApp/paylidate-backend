<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\WalletCreated;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use App\Wallet;
use App\Services\ProvidusNIPService;
use App\Services\WalletService;
use App\WalletHistory;
use App\WalletsettlementId;
use Illuminate\Support\Facades\Mail;

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
            $wallet = Wallet::where('user_id', auth('api')->user()->id)->with('wallet_history')->first();

            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $wallet
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e,
            ]);
        }
    }

    // create a wallet with virtual account
    public function create(Request $request)
    {
        try {

            $request->validate([
                'bvn' => 'required|min:11|max:11'
            ]);

            $wallet = Wallet::where('user_id', auth('api')->user()->id)->first();
            if ($wallet) {
                return response()->json([
                    'status' => 'Eror',
                    'message' => 'This user already has an account',
                ], 400);
            }
            $user = auth('api')->user();
            $wallet = $this->walletService->createWallet($user->id, $user->email, $user->name, $request->bvn);

            if ($wallet['status'] == "error") {

                return response()->json([
                    'status' => 'error',
                    'message' => $wallet["message"]
                ]);
            }

            // send mail mail to user after creation succesful
            Mail::to($user->email)->send(new WalletCreated($user->name));


            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $wallet
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e,
            ], 400);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function creditWalletByFL(Request $request)
    {
        try {
            $request->validate([
                'tx_ref' => 'required|string',
                'narration' => 'required|string',
                'amount' => 'required|numeric'
            ]);

            $user = auth('api')->user();
            $tx_ref = $request->tx_ref;
            $amount = $request->amount;

            $wallet = Wallet::where('user_id', $user->id)->first();

            $settlement = WalletsettlementId::where('settlementId', $tx_ref)->first();

            if ($settlement) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Duplicate Transaction',
                ], 422);
            }

            if (!$wallet || $amount < 1) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Transaction rejected, Amount must be greater than 1 NGN',
                ], 400);
            }

            // storing settlementId
            WalletsettlementId::create([
                'wallet_id' => $wallet->id,
                'settlementId' => $tx_ref,
            ]);

            // credit wallet
            $credited = $this->walletService->creditWalletById($user->id, $amount);

            $history = $this->walletService->walletHistory($user->id, 'credit', $amount, $request->narration, $credited['data']->id, $credited['data']->balance - $amount, $credited['data']->balance);

            if ($history['status'] == 'success') {
                return response()->json([
                    'status' => 'success',
                    'message' => 'success',
                    'data' => $history['data']
                ], 200);
            }

            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'an error occured',
                ],
                400
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e,
            ], 400);
        }
    }
    public function creditWalletByTransfer(Request $request)
    {
        try {

            $tx_ref = $request->data['tx_ref'];
            $amount = $request->data['amount'];
            $settlement_id = $request->data['id'];
            $status = $request->data['status'];

            $virtual_account_number = Wallet::where('tx_ref', $tx_ref)->first();

            //$app_header = getenv("X_AUTH_SIGNATURE");
            $app_header = "BE09BEE831CF262226B426E39BD1092AFGDEYUKNB842076D4174FAC78A2261F9A3D6E59744983B8326B69HD5476N963FE314DFC89635CFA37A40596508DD6EAAB09402C7";
            // rejected for invalid header
            $header = $request->header('verif-hash');
            if ($header != $app_header) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'invalid auth header',
                ], 403);
            }
            if ($status != 'successful') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Transaction not successful',
                ], 400);
            }

            $settlement = WalletsettlementId::where('settlementId', $settlement_id)->first();

            if ($settlement) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Duplicate Transaction',
                ], 401);
            }

            if (!$virtual_account_number || $amount < 1) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Transaction rejected, Amount must be greater than 1 NGN',
                ], 400);
            }

            // storing settlementId
            WalletsettlementId::create([
                'wallet_id' => $virtual_account_number->id,
                'settlementId' => $settlement_id,
            ]);

            // credit wallet
            $this->walletService->creditWalletBytx_ref($tx_ref, $amount);

            //wallet history
            $this->walletService->walletHistory($virtual_account_number->user_id, 'credit', $amount, "Credit wallet by bank transfer", $virtual_account_number->id, $virtual_account_number->balance, $virtual_account_number->balance + $amount);

            return response()->json([
                'status' => 'success',
                'message' => 'success',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e,
            ], 400);
        }
    }

    // debit Wallet
    public function debitWallet(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric',
                'narration' => 'required|string'

            ]);

            $user = auth('api')->user();
            $amount = $request->amount;
            $wallet = $this->walletService->debitWallet($user->id, $amount);
            if ($wallet['status'] === 'error') {
                return response()->json([
                    'status' => 'error',
                    'message' => $wallet['message']

                ], 400);
            }

            if ($wallet['status'] == 'success') {
                $history = $this->walletService->walletHistory($user->id, 'debit', $amount, $request->narration, $wallet['data']->id, $wallet['data']->balance + $amount, $wallet['data']->balance);

                if ($history['status'] == 'success') {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'success',
                        'data' => $history['data']
                    ], 200);
                }
            }
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e,
            ], 400);
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
