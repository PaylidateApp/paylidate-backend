<?php

namespace App\Http\Controllers\Api;

use App\DisputeChat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DisputeChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($dispute_id)
    {
        $chats =  DisputeChat::where('dispute_id', $dispute_id)->with('user')->first();
        if($chats){            
            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $chats
            ]);
        }
        else{
            return response()->json([
                'status' => 'error',
                'message' => 'No record not found',

            ], 404);
        }
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
        $request->validate([
            
            'transaction_id' => 'required|numeric',
            'dispute_id' => 'required|numeric',
            'message' => 'required|string',
            
        ]);

        try{
        $disputeChat = DisputeChat::create([
            'user_id' => auth('api')->user()->id,
            'transaction_id' => $request->transaction_id,
            'dispute_id' => $request->dispute_id,
            'message' => $request->message,

        ]);
        if($disputeChat){            
            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $disputeChat
            ]);
        }
        else{
            return response()->json([
                'status' => 'error',
                'message' => 'An error occured. Chat not sent',

            ], 400);
        }

        } 
            
            catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DisputeChat  $disputeChat
     * @return \Illuminate\Http\Response
     */
    public function show(DisputeChat $disputeChat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DisputeChat  $disputeChat
     * @return \Illuminate\Http\Response
     */
    public function edit(DisputeChat $disputeChat)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DisputeChat  $disputeChat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DisputeChat $disputeChat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DisputeChat  $disputeChat
     * @return \Illuminate\Http\Response
     */
    public function destroy(DisputeChat $disputeChat)
    {
        //
    }
}
