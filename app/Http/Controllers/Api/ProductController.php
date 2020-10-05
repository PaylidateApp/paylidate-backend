<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
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
        $product = Product::where('user_id', Auth::user()->id)->get();
        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $product
        ]);
    }

    
    // public function create()
    // {

    // }
    

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
        $input = $request->all();
        $input['user_id'] = Auth::user()->id;
        $product = Product::create($input);
        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $product
        ]);
    }

    /**
     * Get Single Product
     *
     *  * @urlParam id string required
     * 
     * @return [json] user object
     */
    public function show($id)
    {
        $product = Product::where('id', $id)->get();
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
