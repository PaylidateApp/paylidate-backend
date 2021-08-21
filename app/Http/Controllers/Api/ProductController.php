<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\CreateProductMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Product;
use App\Payment;
use Auth;

/**
 * @group  Product management
 *
 * APIs for Product
 */
class ProductController extends Controller
{
    /**
     * Get all Products.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::where('user_id', Auth::user()->id)
            ->orWhere('secondary_user_id', Auth::user()->id)
            ->with('payment', 'secondary_user', 'user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $product
        ]);
    }


    public function create()
    {
    }

    public function accept($id)
    {

        $product = Product::where('id', $id)->update([
            'secondary_user_id' => Auth::user()->id,
            'status' => 1
        ]);

        $product = Product::where('id', $id)->first();

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $product
        ]);
    }

    public function status($id, Request $request)
    {

        $status = $request->status === 'delivered' ? 2 : 3;
        $product = Product::where('id', $id)->update([
            'status' => $status
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $product
        ]);
    }


    /**
     * Create Product
     *
     * The Product creation
     *
     * @bodyParam   name            string  required    Product Name
     * @bodyParam   slug            string  Product     Slug Required if is_payment is true
     * @bodyParam   image           string  Product     Image
     * @bodyParam   product_number  string  Product     Number / Skew
     * @bodyParam   price           string  required    Unit Price of the product
     * @bodyParam   quantity        int     required    Total Unit of product if empty it will default to one(1)
     * @bodyParam   delivery_period string              Possible Dilivery days (5)
     * @bodyParam   clients_email   string              Adds multiple emails to tonify/invite
     * @bodyParam   payment_details array               Required if user is creating and making payment at the same time
     * @bodyParam   payment_details.transaction_id  string      Transaction ID (Sub-property of payment_details)
     * @bodyParam   payment_details.tx_ref          string      Transaction refrence (Sub-property of payment_details)
     * @bodyParam   payment_details.status          string      Transaction status (Sub-property of payment_details)
     * @bodyParam   payment_details.description     string      Transaction Decsription (Sub-property of payment_details)
     * @bodyParam   description     string              Product Description
     *
     *
     * @return [string] message
     */

    public function store(Request $request)
    {
        try {
            $user_id = Auth::user()->id;
            $input              = $request->all();
            $input['user_id']   = $user_id;
            $product            = Product::create($input);

            if ($request->payment_details) {
                Payment::create([
                    'user_id'           => $user_id,
                    'product_id'        => $product->id,
                    'transaction_id'    => $request->payment_details['transaction_id'],
                    'transaction_ref'   => $request->payment_details['tx_ref'],
                    'status'            => $request->payment_details['status'],
                    'description'    => $request->payment_details['description'],
                ]);
            }
            $user = Auth::user();

            Mail::to($user)->send(new CreateProductMail($user, $product));

        } catch (\Throwable $th) {
            Mail::raw($th->getMessage(), function ($message) {
                $message->from('hello@paylidate.com', 'Paylidate');
                $message->to('syflex360@gmail.com');
                $message->subject('Registration mail Failed');
            });
        }

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $product->load('payment', 'secondary_user', 'user')
        ]);
    }

    /**
     * Get Single Product
     *
     *  * @urlParam id string required
     *
     * @return [json] user object
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->with('payment', 'secondary_user', 'user')->first();
        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $product
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
     * Update a Specified Product
     *
     *
     * @urlParam  id string required the id of the product
     *
     * @bodyParam name string
     * @bodyParam product_number string
     * @bodyParam price double
     * @bodyParam description string
     * @bodyParam quantity int
     * @bodyParam description string
     *
     * @return [string] message
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $product = Product::where('id', $id)->update($input)->get();
        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $product
        ]);
    }

    /**
     * Delete the specified product.
     *
     ** @urlParam  id string required the id of the product

     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::where('id', $id)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $product
        ]);
    }

    public function get_product($slug)
    {
        $product = Product::where('slug', $slug)->with('payment', 'secondary_user', 'user')->first();
        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $product
        ]);
    }
}
