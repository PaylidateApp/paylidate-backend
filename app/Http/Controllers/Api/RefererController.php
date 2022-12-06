<?php

namespace App\Http\Controllers\Api;

use App\Referer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RefererController extends Controller
{


    public function index()
    {

        $user = auth('api')->user();
        $referal_bonus = Referer::where([['user_id', '=', $user->id], ['amount', '>', 0.00], ['transaction_status', '=', true]])
            ->with('transaction', 'user')
            ->orderBy('withdrawal_status')
            ->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => [$referal_bonus, $user->bank]
        ]);

        //$transactions = Referer::with('transaction')->orderBy('created_at', 'desc')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
     * @param  \App\Referer  $referer
     * @return \Illuminate\Http\Response
     */
    public function show(Referer $referer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Referer  $referer
     * @return \Illuminate\Http\Response
     */
    public function edit(Referer $referer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Referer  $referer
     * @return \Illuminate\Http\Response
     */
    // this function updates the status of the refferal whether withdrawn on not
    public function update(Request $request)
    {
        $user = auth('api')->user();

        if ($user->id != $request->user_id) {

            return response()->json([
                'status' => 'error',
                'message' => 'Not allow',

            ], 402);
        }

        $referal_bonus = Referer::where('id', $request->id)->update([
            'withdrawal_status' => $request->withdrawal_status  // null= no request, 0= request pendding, 1= withdrawal complete
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $referal_bonus
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Referer  $referer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Referer $referer)
    {
        //
    }

    public function get_referer(){
        $user = auth('api')->user();

        if(!$user){
            return response()->json([
                'status' => 'error',
                'message' => 'Please Log in'
            ],401);
        }
        $referer = Referer::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $referer['data']
        ], 200);

    }
}
