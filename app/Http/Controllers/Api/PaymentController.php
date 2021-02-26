<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Payment;
use Auth;

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

    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function create()
    // {

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
    public function store(Request $request)
    {
        $payment = Payment::create([
            'user_id' => Auth::user()->id,
            'product_id' => $request->id,
            // 'payment_ref' => $request->flw_ref,
            'transaction_id' => $request->transaction_id,
            'transaction_ref' => $request->tx_ref,
            'status' => $request->status,
            // 'description' => $request->description,
       ]);

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $payment
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

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit($id)
    // {
    //     //
    // }

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
}
