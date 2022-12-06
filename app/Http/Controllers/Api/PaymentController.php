<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\AddMoneyMail;
use App\Payment;
use App\Referer;
use App\Services\FlutterwaveService;
use App\Product;
use App\Transaction;
use Auth;
use Illuminate\Validation\ValidationException;
use stdClass;
use Exception;

/**
 * @group  Payment management
 *
 * APIs for Payment
 */
class PaymentController extends Controller
{
    protected $flutterwaveService;

    public function __construct()
    {

        $this->flutterwaveService = new FlutterwaveService;
    }
    /**
     * Get all payments.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Payment = Payment::where('user_id', auth('api')->user()->id)->get();
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
    // public function store(Request $request)
    // {

    //     $payment = Payment::create([
    //         'user_id' => auth('api')->user()->id,
    //         'payment_ref' => $request->flw_T_ref,
    //         'payment_id' => $request->flw_T_id,
    //         'transaction_id' => $request->transaction_id,
    //         //'transaction_ref' => $request->tx_ref,
    //         // 'status' => $request->status,
    //         'description' => $request->description,
    //    ]);


    //    $response = $this->flutterwaveService->verify_payment( $request->flw_T_id);



    //    if ($response['data']['status'] === "successful"
    //     && $response['data']['amount'] === $payment->transaction->amount
    //     && $response['data']['currency'] === $payment->currency) {

    //         $verified_payment = Payment::where('id', $payment->id)->update([
    //             'verified' => true //payment verified
    //         ]);

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'success',
    //             'data' => $verified_payment
    //         ]);
    //     } 

    //     else {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Payment not verified',            
    //         ]);
    //     }

    // }



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

        try {

            $old_payment = Payment::where('payment_id', $request->payment_id)->first();

            if ($old_payment && $old_payment->verified == true) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid payment verification',
                ]);
            }

            if ($old_payment) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This Payment has been verified',
                ]);
            }

            $payment = Payment::create([
                'user_id' => auth('api')->user()->id,
                'payment_ref' => $request->payment_ref,
                'payment_id' => $request->payment_id,
                'transaction_id' => $request->transaction_id,
                'verified' => true,
                // 'status' => $request->status,
                'description' => $request->description,
            ]);

            $product = product::where('id', $payment->transaction->product_id)->first();

            if ($payment) {
                $product->update([
                    'quantity' => $product->quantity - $payment->transaction->quantity

                ]);

                $transaction_amount = $payment->transaction->quantity *  $product->price;
                $referral_amount = $payment->transaction->quantity * $product->referral_amount;
                $payment->transaction->update([
                    'amount' => $transaction_amount - $referral_amount,

                ]);

                Referer::where('id', $payment->transaction->referer_id)->update([
                    'amount' => $referral_amount,
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $payment
            ]);
        } catch (Exception $e) {
            //return $e;
            return response()->json([
                'status' => 'error',
                'message' => 'An error occured while trying to verify your payment. Please contact paylidate.com ',
            ]);
        }
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



    // Initiate card payment
    public function pay_with_card(Request $request)
    {
        $user = auth('api')->user();
        $meta = new stdClass();
        $meta->consumer_id =  $user->name;
        $meta->consumer_mac = "";
        $name = explode(' ', $user->name);
        $lastName = "";
        if (count($name) > 1) {
            $lastName = $name[0];
        }

        if (strlen($request->cardno)) {
        }

        $data = array(

            'PBFPubKey' => env('FLW_PUBLIC_KEY'),
            'cardno' => $request->cardno,
            'currency' => $request->currency,
            'country' => $request->country,
            'cvv' => strval($request->cvv),
            'amount' => $request->amount,
            'expiryyear' => $request->expiryyear,
            'expirymonth' => $request->expirymonth,
            'pin' => $request->pin,
            'email' => $user->email,
            'phonenumber' => $user->phone,
            "firstname" => $name[0],
            "lastname" => $lastName,
            'txRef' => 'PD' . $user->id . date('dmyHis'),
            'meta' => $meta,
            'redirect_url' => $request->redirect_url,

        );


        try {

            //return 'helllooo';

            $response = $this->flutterwaveService->payviacard($data);


            if (empty($response['data']) || $response["status"] != "success" || isset($response['data']['code'])) {
                return $response;
                return response()->json([
                    'status' => 'error',
                    'message' => 'An error occured while trying to initiate transaction. Please try again',

                ], 417);
            }

            if ($response["status"] == "success" && isset($response["data"]['authurl']) && $response["data"]['authurl'] != 'N/A') {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment initiated successfully',
                    'data' => $response['data']
                ]);
            }


            if ($response["status"] == "success" && $response["data"]["suggested_auth"] == "PIN") {
                // $new_data = [...$data];
                $data["suggested_auth"] = "PIN";
                $response = $this->flutterwaveService->payviacard($data);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment initiated successfully',
                    'data' => $response['data']
                ]);
            }

            if ($response["status"] == "success" && $response["data"]["suggested_auth"] == "NOAUTH_INTERNATIONAL") {

                throw ValidationException::withMessages([
                    'error' => ['This card can not be charge']
                ]);


                $data["suggested_auth"] = "NOAUTH_INTERNATIONAL";
                $data["billingzip"] = "07205";
                $data["billingcity"] = "Hillside";
                $data["billingaddress"] = "470 Mundet PI";
                $data["billingstate"] = "NJ";
                $data["billingcountry"] = "US";


                $response = $this->flutterwaveService->payviacard($data);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment initiated successfully',
                    'data' => $response['data']
                ]);

                return $response;
            }
        } catch (Exception $e) {
            return $e;

            return response()->json([
                'status' => 'error',
                'message' => 'An error occured while trying to initiate transaction. Please try again',

            ], 417);
        }
    }


    public function validate_payment(Request $request)
    {
        $response = $this->flutterwaveService->validate_payment($request->flwRef, $request->otp);

        return response()->json([
            'status' => 'success',
            'message' => 'Payment validation successful',
            'data' => $response['data']
        ]);
    }

    public function verify_payment(Request $request)
    {

        $response = $this->flutterwaveService->verify_payment($request->flw_T_id);

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $response['data']
        ]);
    }

    public function get_rate(Request $request)
    {
        $response = $this->flutterwaveService->getRate($request->amount, $request->destination_currency, $request->source_currency);

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $response['data']
        ]);
    }

    public function banks()
    {
        $response = $this->flutterwaveService->banks();

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $response['data']
        ]);
    }

    public function payemnts_received()
    {
        $user = auth('api')->user();
        if(!$user){
            return response()->json([
                'status'    => 'error',
                'message'   => 'Please Log in'
            ], 401);
        }
        try {
            $response = Transaction::where('user_id', $user->id)
                        ->orderBy('desc')
                        ->get();

            return response()->json([
                'status'    => 'success',
                'message'   => 'success',
                'data'      => $response['data']
            ], 200);

        } 
        catch(\Exception $e) {
            return response()->json([
                'status'    => 'success',
                'message'   => $e
            ], 400);
        }
    }
}
