<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Payment;
use App\UserCard;
use App\VirtualCard;
use App\Product;
use Auth;
use stdClass;

/**
 * @group  Payment management
 *
 * APIs for Payment
 */
class PaymentController extends Controller
{
    /**
     * Get all payments.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Payment = Payment::where('user_id', Auth::user()->id)->get();
        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $Payment->load('product')
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
     * Create Payment
     *
     * the payment creation
     *
     * @bodyParam product_id string required
     * @bodyParam quantity int string
     * @bodyParam type string required  either make-payment/receive-payment
     * @bodyParam status boolean true for paid false un-paid,  false by default
     * @bodyParam expires string expires in a week by default
     * @bodyParam description string
     *
     *
     * @return [string] message
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        // $product = Product::where('slug',$request->slug)->first('id');

    //     Payment::create([
    //         'user_id' => $user->id,
    //         // 'product_id' => 0,
    //         // 'payment_ref' => $request->flw_ref,
    //         'transaction_id' => $request->transaction_id,
    //         'transaction_ref' => $request->tx_ref,
    //         // 'status' => $request->status,
    //         // 'description' => $request->description,
    //    ]);


       $response = Http::withHeaders([
        'Authorization' => 'Bearer '.env('FLW_SECRET_KEY')
        ])->get(env('FLW_BASE_URL').'/v3/transactions/'.$request->transaction_id.'\/verify/');

        // create an instance of UserCard and insert 'first_6digits','last_4digits','issuer','country','type','token','expiry'
        $userCard = new UserCard();
        $userCard->first_6digits =  $response['data']['card']['first_6digits'];
        $userCard->last_4digits = $response['data']['card']['last_4digits'];
        $userCard->issuer = $response['data']['card']['issuer'];
        $userCard->country = $response['data']['card']['country'];
        $userCard->type = $response['data']['card']['type'];
        $userCard->token = $response['data']['card']['token'];
        $userCard->expiry = $response['data']['card']['expiry'];
        $userCard->user_id = $user->id;
        $userCard->save();

        // get card_id from VirtualCard where id is equal to user-id
        $virtualCard = VirtualCard::where('user_id',$user->id)->first('card_id');

        // fund virtual card with payment
        $response1 = Http::withHeaders([
            'Authorization' => 'Bearer '.env('FLW_SECRET_KEY')
        ])->post(env('FLW_BASE_URL').'/v3/virtual-cards/'. $virtualCard->card_id .'\/fund', [
            "amount" => $response['data']['amount'],
            "debit_currency" => 'NGN',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $response1['data']
        ]);
    }



    /**
     * Create Payment
     *
     * the payment creation
     *
     * @bodyParam product_id string required
     * @bodyParam quantity int string
     * @bodyParam type string required  either make-payment/receive-payment
     * @bodyParam status boolean true for paid false un-paid,  false by default
     * @bodyParam expires string expires in a week by default
     * @bodyParam description string
     *
     *
     * @return [string] message
     */
    public function make_payment(Request $request)
    {
        $user = Auth::user();

        // get product
        // $product = Product::where('slug', $request->slug)->first('id');

        // get card_id from VirtualCard where id is equal to user-id
        $virtualCard = VirtualCard::where('user_id', $user->id)->where('default', 1)->first('card_id');

        // get virtual card balance
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.env('FLW_SECRET_KEY')
            ])->get(env('FLW_BASE_URL').'/v3/virtual-cards/'. $virtualCard->card_id);


        if ($request->amount <= $response['data']['amount']) {

            // withdraw from virtual card
            $response1 = Http::withHeaders([
                'Authorization' => 'Bearer '.env('FLW_SECRET_KEY')
            ])->post(env('FLW_BASE_URL').'/v3/virtual-cards/'. $virtualCard->card_id .'/withdraw', [
                "amount" => $request->amount,
            ]);
            
            Product::where('slug', $request->slug)->update(['payment_status' => 1]);

        }else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Amount connot be more than wallet balnce',
                'data' => []
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $response1
        ]);
    }


    /**
     * Get Single Payment
     *
     * @urlParam id string required
     *
     * @return [json] user object
     */
    public function show($id)
    {
        $Payment = Payment::where('id', $id)->get();
        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $Payment->load('product')
        ]);
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
     * Update a Specified Payment
     *
     *
     * @urlParam  id string required the id of the payment
     *
     * @bodyParam quantity int string
     * @bodyParam type string required  either make-payment/receive-payment
     * @bodyParam status boolean true for paid false un-paid,  false by default
     * @bodyParam expires string expires in a week by default
     * @bodyParam description string
     *
     * @return [string] message
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $Payment = Payment::where('id', $id)->update($input)->get();
        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $Payment
        ]);
    }

    /**
     * Delete the specified product.
     *
     * @urlParam  id string required the id of the payment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payment = Payment::where('id', $id)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $payment
        ]);
    }

    public function getPaymentLink(Request $request){
        $user =  Auth::user();
        $meta = new stdClass();
        $meta->consumer_id =  $user->id;
        $meta->consumer_mac = "";

        $customer = new stdClass();
        $customer->email =  $user->email;
        $customer->phonenumber =  $user->phone;
        $customer->name =  $user->name;

        $customizations = new stdClass();
        $customizations->title = "Paylidate Payment";
        $customizations->description = "";
        $customizations->logo = "";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.env('FLW_SECRET_KEY')
        ])->post(env('FLW_BASE_URL').'/v3/payments', [
            "tx_ref" => $user->name."-tx-".time(),
            "amount" => $request->amount,
            "currency" => $request->currency,
            "redirect_url" => $request->redirect_url,
            "payment_options" => "card",
            "meta" => $meta,
            "customer" => $customer,
            "customizations" => $customizations
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $response['data']
        ]);
    }
}
