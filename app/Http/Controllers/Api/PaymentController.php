<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\AddMoneyMail;
use App\Payment;
use App\UserCard;
use App\User;
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

        $user = new User;
        $response = $user->getTransaction($request->transaction_id);

        // create an instance of UserCard and insert 'first_6digits','last_4digits','issuer','country','type','token','expiry'
        $userCard = new UserCard();
        $userCard->first_6digits =  $response['data']['card']['first_6digits'];
        $userCard->last_4digits = $response['data']['card']['last_4digits'];
        $userCard->issuer = $response['data']['card']['issuer'];
        $userCard->country = $response['data']['card']['country'];
        $userCard->type = $response['data']['card']['type'];
        $userCard->token = $response['data']['card']['token'];
        $userCard->expiry = $response['data']['card']['expiry'];
        $userCard->user_id = Auth::user()->id;
        $userCard->save();

        // get card_id from VirtualCard where id is equal to user-id
        $virtualCard = new VirtualCard;
        $card = $virtualCard->where('user_id', Auth::user()->id)->where('default', 1)->first('card_id');

        // fund virtual card with payment
        $virtualCard->fundVirtualCard($card_id = $card->card_id, $amount = $response['data']['amount'], $debit_currency = $response['data']['currency']);

        // Mail::to(Auth::user())->send(new AddMoneyMail(Auth::user()->name, $response['data']['amount'], $response['data']['currency']));

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $response['data']
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
        $virtual_card = new VirtualCard;



        // get card_id from VirtualCard where id is equal to user-id
        $get_virtual_card_id = $virtual_card->where('user_id', $user->id)->where('default', 1)->first('card_id');

        // get virtual card balance
        $get_virtual_card = $virtual_card->getvirtualCard($get_virtual_card_id->card_id);

        if ($request->amount <= $get_virtual_card['data']['amount']) {

            // withdraw from virtual card
            $withdraw_from_card = $virtual_card->withdrawFromVirtualCard($card_id =  $get_virtual_card_id->card_id, $amount = $request->amount);

            // get product
            $product = Product::where('slug', $request->slug)->first('id');
            Product::where('slug', $request->slug)->update(['payment_status' => 1]);
            if ($user->id != $product->user_id) {
                Product::where('slug', $request->slug)->update(['secondary_user_id' => $user->id]);
            }

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
            'data' => $withdraw_from_card
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

    public function get_rate(Request $request){
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.env('FLW_SECRET_KEY')
            ])->get(env('FLW_BASE_URL').'/v3/transfers/rates', [
                'amount' => '1',
                'destination_currency' => 'USD',
                'source_currency' => 'NGN'
            ]);

        return $response;
    }

    public function banks(Request $request){
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.env('FLW_SECRET_KEY')
            ])->get(env('FLW_BASE_URL').'/v3/banks/NG');

        return $response;
    }
}
