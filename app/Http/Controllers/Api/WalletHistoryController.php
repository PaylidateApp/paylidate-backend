<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\WalletHistory;
use Illuminate\Http\Request;

class WalletHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $walletHistory = WalletHistory::where('user_id', auth('api')->user()->id)->get();
            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $walletHistory
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e,
            ]);
        }
    }
}
