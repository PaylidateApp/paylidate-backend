<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\CreateProductMail;
use App\Mail\SellerAcceptTransactionMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Product;
use App\Transaction;
use App\User;
use Illuminate\Support\Str;
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
        // $product = Product::where('user_id', auth('api')->user()->id)
        //     ->orWhere('user_id', auth('api')->user()->id)
        //     ->with('payment', 'secondary_user', 'user')
        //     ->orderBy('created_at', 'desc')
        //     ->get();
        $product = Product::where('user_id', auth('api')->user()->id)
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
            'user_id' => auth('api')->user()->id
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $product
        ]);

        //Mail::to($user)->send(new CreateProductMail($user, $product));

    }



    // Product available (enable or disable) 
    public function status($id, Request $request)
    {
        $product = Product::where('id', $id)->update([
            'product_status' => $request->status
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $product
        ]);
    }

    public function delivered($id, Request $request)
    {
        $product = Product::where('id', $id)->update([
            'delivery_status' => 2 //delivered
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $product
        ]);
        //Mail::to($user)->send(new CreateProductMail($user, $product));

    }

     // creating a product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'type' => 'required|string',
            'transaction_type' => 'required|string',

        ]);


        if ($request->transaction_type == 'buy') {
            $request->validate([
                'seller_email' => 'required|string|email',

            ]);

            $input['referral_amount'] = 0.00;
        }

        if (!empty($request->referral_amount) && $request->referral_amount >= $request->price) {
            return response()->json([
                'status' => 'error',
                'message' => 'Referral bonus must be less than product price',

            ], 400);
        }

        try {
            $user = auth('api')->user();
            $user_id = $user->id;
            $input = $request->all();
            $input['user_id']   = $user_id;
            $input['slug']   = date('dmyHis');
            $input['product_number']   = date('dmyHis');


            $product = Product::create($input);


            if ($product && $product->transaction_type == 'buy') {

                $secondary_user = User::where('email', $request->get('seller_email'))->first();
                $transaction['product_id'] = $product->id;
                if ($secondary_user) {

                    $transaction['user_id'] = $secondary_user->id;
                } else {

                    $input['email'] = $request['seller_email'];
                    $input['password'] = 'defualt';
                    $new_user = User::create($input);

                    $secondary_user['email'] = $new_user->email;
                    $transaction['user_id'] = $new_user->id;
                }
                $transaction['quantity'] = $product->quantity;
                $t_ref = 'PD_' . Str::random(8) . date('dmyHis');
                $transaction['transaction_ref'] = $t_ref;
                //$transaction['amount'] = $product->quantity * $product->price;
                Transaction::create($transaction);

                $new_transaction = Transaction::where('transaction_ref', $t_ref)->with('product')->first();

                $emailTransaction['id'] = $new_transaction->id;
                $emailTransaction['transaction_ref'] = $t_ref;
                $emailTransaction['product_id'] = $new_transaction->product_id;
                $emailTransaction['product_name'] = $new_transaction->product->name;
                $emailTransaction['product_number'] = $new_transaction->product->product_number;
                $emailTransaction['type'] = $new_transaction->product->type;
                $emailTransaction['total_quantity'] = $new_transaction->quantity;
                $emailTransaction['total_price'] = $new_transaction->product->price * $new_transaction->quantity;
                $emailTransaction['description'] = $new_transaction->description ? $new_transaction->description : 'No description';


                Mail::to($secondary_user['email'])->send(new SellerAcceptTransactionMail($secondary_user['email'], $user->email, $emailTransaction));
                Mail::to($user)->send(new CreateProductMail($user, $product));

                return response()->json([
                    'status' => 'success',
                    'message' => 'success',
                    'data' => $product
                ]);
            }



            Mail::to($user)->send(new CreateProductMail($user, $product));

            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return $e;
        }
        // catch (\Throwable $th) {
        //     Mail::raw($th->getMessage(), function ($message) {
        //         $message->from('hello@paylidate.com', 'Paylidate');
        //         $message->to('syflex360@gmail.com');
        //         $message->subject('Registration mail Failed');
        //     });
        // }


    }

    // getting single product
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->with('payment', 'secondary_user', 'user')->first();
        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $product
        ]);
    }


    // updating a product
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'type' => 'required|string',

        ]);
        $input = $request->all();
        if($input['transaction_type']){
            unset($input['transaction_type']);
        }
        if($input['referral_amount']){
            if($input['referral_amount'] >= $input['price']){
                return response()->json([
                    'status' => 'error',
                    'message' => 'referral amount can not be greater than or equal to product price'
                ], 400);
            }
        }
        $product = Product::where('id', $id)->first();
        if($product->user_id != auth('api')->user()->id){
            return response()->json([
                'status' => 'Unauthorized',
                'message' => 'You are not allow to edit the product'
            ], 401);
        }


        // checking if the transaction is buy
        if($product->transaction_type == 'buy')
        {
            return response()->json([
                'status' => 'error',
                'message' => 'You not allow to edit a product you are buying'
            ], 401);
        }
        $product->update($input);
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


    // deleting a product only if it doesn't have a transaction
    public function destroy($id)
    {

        $product = Product::where('id', $id)->get();

        if ($product->transaction) {

            return response()->json([
                'status' => 'error',
                'message' => 'You can not delete this Product'
            ], 401);
        } else {
            $product->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'success',

            ]);
        }
    }

    // getting a single product 
    public function get_product($slug)
    {
        $product = Product::where('slug', $slug)->with('transaction')->first();
        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $product
        ]);
    }
}
