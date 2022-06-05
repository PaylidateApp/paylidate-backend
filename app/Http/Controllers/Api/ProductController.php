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

    public function paid($id)
    {

        $product = Product::where('slug', $id)->update([
            'payment_status' => 1
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

    public function delivery($id, Request $request)
    {
        $product = Product::where('id', $id)->update([
            'delivery_status' => 1 //in transit
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $product
        ]);
        
    }

    public function recieved($id, Request $request)
    {
        $product = Product::where('id', $id)->update([
            'delivery_status' => 3 //in transit
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $product
        ]);
        //Mail::to($user)->send(new CreateProductMail($user, $product));

    }

    public function canceled($id, Request $request)
    {
        $product = Product::where('id', $id)->update([
            'delivery_status' => 4 //canceled
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $product
        ]);

        //Mail::to($user)->send(new CreateProductMail($user, $product));

    }


    public function open_dispute($id, Request $request)
    {
        $product = Product::where('id', $id)->update([
            'dispute' => 1 //open dispute
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $product
        ]);

        //Mail::to($user)->send(new CreateProductMail($user, $product));

    }
    public function resolve_dispute($id, Request $request)
    {
        $product = Product::where('id', $id)->update([
            'dispute' => 2 //resolve dispute
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $product
        ]);

        //Mail::to($user)->send(new CreateProductMail($user, $product));

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

        
        if($request->transaction_type == 'buy'){
            $request->validate([
                'seller_email' => 'required|string|email',                
                
            ]);
        }
        
        try {
            $user = auth('api')->user();
            $user_id = $user->id;
            $input = $request->all();
            $input['user_id']   = $user_id;
            $input['slug']   = date('dmyHis');
            $input['product_number']   = date('dmyHis');  

                
            
            $product = Product::create($input);
            
            
            
            if($product && $product->transaction_type == 'buy'){
                
                $secondary_user = User::where('email', $request->get('seller_email'))->first();
                $transaction['product_id'] = $product->id;
                if($secondary_user){
                    
                    $transaction['user_id'] = $secondary_user->id;
                }
                else{
                    
                    $input['email'] = $request['seller_email'];
                    $input['password'] = 'defualt';
                    $new_user = User::create($input);

                    $secondary_user['email'] = $new_user->email; 
                    $transaction['user_id'] = $new_user->id;                   
                   
                }
                $transaction['quantity'] = $product->quantity;
                $transaction['transaction_ref'] = 'PD_'.Str::random(8).date('dmyHis');
                //$transaction['amount'] = $product->quantity * $product->price;
                $new_transaction = Transaction::create($transaction);
                
                $newTransaction = Transaction::where('id', $new_transaction->id)->with('product')->first();

            $emailTransaction['id'] = $new_transaction->id;
            $emailTransaction['transation_ref'] = $new_transaction->transaction_ref;
            $emailTransaction['product_id'] = $new_transaction->product_id;
            $emailTransaction['product_name'] = $new_transaction->product->name;
            $emailTransaction['product_number'] = $new_transaction->product->product_number;
            $emailTransaction['type'] = $new_transaction->product->type;
            $emailTransaction['total_quantity'] = $new_transaction->quantity;
            $emailTransaction['total_price'] = $new_transaction->product->price * $new_transaction->quantity;
            $emailTransaction['description'] = $new_transaction->description ? $new_transaction->description : 'No description';


            Mail::to($secondary_user['email'])->send(new SellerAcceptTransactionMail($secondary_user['email'], $user->email, $newTransaction));
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

        } 

        catch (Exception $e) {
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

    // updating a product
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


    // deleting a product only if it doesn't have a transaction
    public function destroy($id)
    {
        
        $product = Product::where('id', $id)->get();

        if($product->transaction){
        
        return response()->json([
            'status' => 'error',
            'message' => 'You can not delete this Product'
        ], 401);

        }
        else{
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
