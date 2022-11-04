<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Services\ProvidusNIPService;
use App\Wallet;


class WalletService
{
    protected $providusNIPService;
    public function __construct()
    {
        $this->providusNIPService = new ProvidusNIPService;
    }

    // create a virtual account and a user wallet
    public function createWallet($user_id, $account_name)
    {
        try {
            $accountRresponse = $this->providusNIPService->createVirtualAccount($account_name); // creating a virtual account number
            $accountRresponse = (json_decode($accountRresponse));

            // creat a wallet for a 
            // return [
            //     $accountRresponse->initiationTranRef,
            //     $accountRresponse->account_name,
            //     $accountRresponse->account_number
            // ];
            $response = Wallet::create([
                'user_id' => $user_id,
                'account_name' => $accountRresponse->account_name,
                'account_number' => $accountRresponse->account_number,
            ]);
            return $response;
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e
            ];
        }
    }

    // credit Wallet by user Id
    public function creditWalletById($user_id, $amount)
    {
        try {
            if ($amount < 1) {
                abort(400, "amount must be greater than 0");
            }
            $wallet = Wallet::where('user_id', $user_id)->first();
            $balance =  $wallet->balance + $amount;
            $wallet->update([
                'balance' => $balance
            ]);

            return $wallet;
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e
            ];
        }
    }

    // credit Wallet by user wallect account number
    public function creditWalletByAccountNumber($account_number, $amount)
    {
        try {
            $wallet = Wallet::where('account_number', $account_number)->first();
            $balance =  $wallet->balance + $amount;
            $wallet->update([
                'balance' => $balance
            ]);

            return $wallet;
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e
            ];
        }
    }


    // debit Wallet
    public function debitWallet($user_id, $amount)
    {
        try {
            if ($amount < 1) {
                abort(400, "amount must be greater than 0");
            }
            $wallet = Wallet::where('user_id', $user_id)->first();
            if ($wallet->balance - $amount < 0) {
                abort(400, "insufficient fund");
            }
            $balance =  $wallet->balance - $amount;
            $wallet->update([
                'balance' => $balance
            ]);

            return $wallet;
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e
            ];
        }
    }

    // Transfer wallet bonus to main wallet
    public function transferBonusToWallet($user_id, $amount)
    {
        try {
            if ($amount < 1) {
                abort(400, "amount must be greater than 0");
            }
            $wallet = Wallet::where('user_id', $user_id)->first();
            if ($wallet->bonus - $amount < 0) {
                abort(400, "insufficient bonus");
            }
            $balance =  $wallet->balance + $amount;
            $wallet->update([
                'balance' => $balance,
                'bonus' => $wallet->bonus - $amount
            ]);

            return $wallet;
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e
            ];
        }
    }

    // debit Wallet bonus
    public function debitWalletBonus($user_id, $amount)
    {
        try {
            if ($amount < 1) {
                abort(400, "amount must be greater than 0");
            }
            $wallet = Wallet::where('user_id', $user_id)->first();
            if ($wallet->bonus - $amount < 0) {
                abort(400, "insufficient bonus");
            }

            $wallet->update([
                'bonus' => $wallet->bonus - $amount
            ]);

            return $wallet;
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e
            ];
        }
    }

    // credit Wallet bonus by user Id
    public function creditWalletBonusById($user_id, $amount)
    {
        try {
            if ($amount < 1) {
                abort(400, "amount must be greater than 0");
            }
            $wallet = Wallet::where('user_id', $user_id)->first();


            $wallet->update([
                'bonus' => $wallet->bonus + $amount
            ]);

            return $wallet;
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e
            ];
        }
    }

    // credit Wallet bonus Account Number
    public function creditWalletBonusByAccountNumber($account_number, $amount)
    {
        try {
            if ($amount < 1) {
                abort(400, "amount must be greater than 0");
            }
            $wallet = Wallet::where('account_number', $account_number)->first();


            $wallet->update([
                'bonus' => $wallet->bonus + $amount
            ]);

            return $wallet;
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e
            ];
        }
    }
}
