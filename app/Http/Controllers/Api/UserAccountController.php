<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use App\UserAccount;
use Auth;

/**
 * @group  User Account Management
 *
 * APIs for User Account Management
 */
class UserAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $account = UserAccount::where('user_id', Auth::user()->id)->first();
        $output = false;
        if ($account) {
            $response = Curl::to('https://api.flutterwave.com/v3/virtual-account-numbers/'. $account->ref)
            ->withHeader('Content-Type: application/json')
            ->withHeader('Authorization: Bearer FLWSECK_TEST-2b3f3862386bce594393f94c261f8184-X')
            ->asJson( true )
            ->get();
            $output =  $response['data'];
        }
       

        return response()->json([
            'status' => 'success',
            'data' => $output 
        ]);
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
