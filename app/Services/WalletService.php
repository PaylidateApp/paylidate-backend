<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Services\FlutterwaveService;
use App\Wallet;
use App\WalletHistory;

class WalletService
{
    protected $FlutterwaveService;
    public function __construct()
    {
        $this->FlutterwaveService = new FlutterwaveService;
    }

    // create a virtual account and a user wallet
    public function createWallet($user_id, $email, $name, $bvn)
    {
        $tx_ref = 'PD' . '_' . $user_id . time();
        try {
            $accountResponse = $this->FlutterwaveService->createVirtualAccount($email, $name, $tx_ref, $bvn); // creating a virtual account number
            //  return $accountResponse;
            if ($accountResponse->status !== 'success') {
                return [
                    'status' => 'error',
                    'message' => $accountResponse->message,

                ];
            }
            $accountResponse = $accountResponse->data;
            //return $accountResponse->order_ref;
            $response = Wallet::create([
                'user_id' => $user_id,
                'tx_ref' => $tx_ref,
                'order_ref' => $accountResponse->order_ref,
                'account_number' => $accountResponse->account_number,
                'bank_name' => $accountResponse->bank_name,
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
                return [
                    'status' => 'error',
                    'message' => 'amount must be greater than 0'
                ];
            }
            $wallet = Wallet::where('user_id', $user_id)->first();
            $balance =  $wallet->balance + $amount;
            $wallet->update([
                'balance' => $balance
            ]);

            return [
                'status' => 'success',
                'data' => $wallet
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e
            ];
        }
    }

    // credit Wallet by user wallect transaction ref
    public function creditWalletBytx_ref($tx_ref, $amount)
    {
        try {
            $wallet = Wallet::where('tx_ref', $tx_ref)->first();
            $balance =  $wallet->balance + $amount;
            $wallet->update([
                'balance' => $balance
            ]);

            return [
                'status' => 'success',
                'data' => $wallet
            ];
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

                return [
                    'status' => 'error',
                    'message' => 'amount must be greater than 0 NGN'
                ];
            }
            $wallet = Wallet::where('user_id', $user_id)->first();
            if ($wallet->balance - $amount < 0) {

                return [
                    'status' => 'error',
                    'message' => 'insufficient fund'
                ];
            }
            $balance =  $wallet->balance - $amount;
            $wallet->update([
                'balance' => $balance
            ]);

            return [
                'status' => 'success',
                'data' => $wallet
            ];
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
                return [
                    'status' => 'error',
                    'message' => 'amount must be greater than 0'
                ];
            }
            $wallet = Wallet::where('user_id', $user_id)->first();
            if ($wallet->bonus - $amount < 0) {
                return [
                    'status' => 'error',
                    'message' => 'insufficient bonus'
                ];
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
                return [
                    'status' => 'error',
                    'message' => 'amount must be greater than 0 NGN'
                ];
            }
            $wallet = Wallet::where('user_id', $user_id)->first();
            if ($wallet->bonus - $amount < 0) {
                return [
                    'status' => 'error',
                    'message' => 'insufficient bonus'
                ];
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

                return [
                    'status' => 'error',
                    'message' => 'amount must be greater than 0'
                ];
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
                return [
                    'status' => 'error',
                    'message' => 'amount must be greater than 0'
                ];
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

    // Wallet history
    public function walletHistory($user_id, $type, $amount, $narration, $wallet_id, $balance_before, $balance_after)
    {
        try {

            $response = WalletHistory::create([
                'user_id' => $user_id,
                'wallet_id' => $wallet_id,
                'type' => $type,
                'amount' => $amount,
                'narration' => $narration,
                'balance_before' => $balance_before,
                'balance_after' => $balance_after,
            ]);
            return [
                'status' => 'success',
                'data' => $response
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e
            ];
        }
    }
}
