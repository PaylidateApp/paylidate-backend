<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Http;
use App\User;
use App\VirtualCard;
use Auth;
use stdClass;
use App\Services\FlutterwaveService;

class CardController extends Controller
{
    /**
     * @group  Virtual card
     *
     * APIs for Virtual card
     */

    protected $flutterwaveService;

    public function __construct(){

        $this->flutterwaveService = new FlutterwaveService;
    }

    public function index()
    {
        $virtual_card = new VirtualCard;
        $cards = $virtual_card->where('user_id', auth('api')->user()->id)->get();
        $new_cards = $cards;

        //return $cards;
        if ($cards) {
            foreach ($cards as $key => $card) {
                $response = $this->flutterwaveService->getvirtualCard($card->card_id);
                $new_cards[$key]->data = $response['data'];
                

            }
        }


        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $new_cards
        ]);
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
     * Create Virtual Card
     *
     * The Virtual Card creation
     *
     * @bodyParam   currency        string  required    NG or USD
     * @bodyParam   amount          number  required    at least 150 for NG and 1 for USD
     * @bodyParam   label           string              Card Name
     * @bodyParam   default         boolean             Make card default (if empty it's default to 0)
     *
     *
     * @return [string] message
     */

    public function store(Request $request)
    {
        $amount = $request->currency == 'NGN' ? ($request->amount - 100) : ($request->amount - 1);
        $response = $this->flutterwaveService->virtualCard($currency = $request->currency, $ammount = $amount, $name = auth('api')->user()->name);

        try {
            if ($response['status'] == 'success') {
                VirtualCard::create([
                    'user_id' => auth('api')->user()->id,
                    'card_id' => $response['data']['id'],
                    'account_id' => $response['data']['account_id'],
                    'currency' => $response['data']['currency'],
                    'label' => $request->label,
                    // 'default' => $request->default,
                ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'success',
                    'data' => $response['data']
                ]);
            }else {
                return response()->json([
                    'status' => 'error',
                    'message' => $response
                ], 406);
            }
        }         
        catch (\Throwable $th) {
            Mail::raw($th->getMessage(), function ($message) {
                $message->from('hello@paylidate.com', 'Paylidate');
                $message->to('syflex360@gmail.com');
                $message->subject('Unable to save created card');
            });
        } 

    }


    /**
     * fund account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function fund(Request $request)
    {
        $response = $this->flutterwaveService->fundVirtualCard($request->virtual_card_id, $request->amount);

            if ($response['status'] == 'success') {
                return response()->json([
                    'status' => 'success',
                    'message' => 'success',
                    'data' => $response['data']
                ]);
            }else {
                return response()->json([
                    'status' => 'error',
                    'message' => $response
                ], 406);
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
