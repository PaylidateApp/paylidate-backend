<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        $product = Product::where('user_id', Auth::user()->id)->with('payment','secondary_user','user')->orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $product
        ]);
    }

    
    public function create()
    {

    }

    public function accept($id){   

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

    public function status($id, Request $request){   
        
        $status = $request->status === 'delivered' ? 2 : 3 ;
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
     * @bodyParam name string required 
     * @bodyParam product_number string 
     * @bodyParam price string required
     * @bodyParam quantity int 
     * @bodyParam description string
     * 
     * 
     * @return [string] message
     */    
    public function store(Request $request)
    {
       if ($request->type === 'new') {
            $input              = $request->all();
            $input['user_id']   = Auth::user()->id;
            $product            = Product::create($input);
       } else {
            $product            = Product::where('slug', $request->slug)->first();
       }

        if ($request->payment_details) {
           $payment = Payment::create([
                'user_id'           => Auth::user()->id,
                'product_id'        => $product->id,
                // 'payment_ref'    => $request->payment_details['flw_ref'],
                'transaction_id'    => $request->payment_details['transaction_id'],
                'transaction_ref'   => $request->payment_details['tx_ref'],
                'status'            => $request->payment_details['status'],
                // 'description'    => $request->payment_details['description'],
           ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $product->load('payment','secondary_user','user')
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
        $product = Product::where('slug', $slug)->with('payment','secondary_user','user')->first();
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
}
