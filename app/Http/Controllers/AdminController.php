<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Product;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function users()
    {
        
        $users = User::with('Prouduct')->orderBy('name', 'ASC')->get();
        //$users = User::where('id', '!=', auth('api')->user()->id)->orderBy('name', 'ASC')->get();
        
        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $users
        ]);

        
    }


    public function userBtwnDate($startDate, $endDate)
    {
        $userBtwnDate = User::whereBetween('created_at', [$startDate, $endDate])->get();

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $userBtwnDate
        ]);
    }

    

    public function numbers_of_users()
    {
        $userCount = User::count();

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => ['Total_registered_users' => $userCount]
        ]);
    }


    public function totalTransations()
    {
        $totalTransations = Product::count();

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => ['Total_Transations' => $totalTransations]
        ]);
    }

    public function transations()
    {
        $transations = Product::all();

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => ['Transations' => $transations]
        ]);
    }

    public function index()
    {
        return view('welcome');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
