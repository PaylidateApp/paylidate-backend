<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use Auth;

/**
 * @group  Product management
 *
 * APIs for Producs
 */
class ProductController extends Controller
{
     /**
     * Display a listing of the resource.
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

    
    public function create()
    {

    }

    /**
     * Create Product
     *
     * 
     * @bodyParam name string required the full name of the user
     * @bodyParam price string required 
     * @bodyParam quantity int required 
     * @bodyParam type string  
     * @bodyParam description 
     * 
     * 
     * @return \Illuminate\Http\Response
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
     * single product
     *
     *  @urlParam  id string required the id of the product
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
     * Update the specified Product
     *
     * 
     * @urlParam  id string required the id of the product
     * @bodyParam price string 
     * @bodyParam quantity int 
     * @bodyParam type string  
     * @bodyParam description string 
     * 
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
