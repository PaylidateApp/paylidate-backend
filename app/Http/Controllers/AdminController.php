<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Transaction;
use App\Dispute;
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

  
    // Dispute section

    public function getTransactionDisputes($transaction_id)
    {        

        $dispute = Dispute::where('transaction_id', $transaction_id)->with('user', 'transaction')
        ->orderBy('dispute_solved')
        ->get();


        if(!$dispute){
            return response()->json([
                'status' => 'Not found',
                'message' => 'Not found',
                
            ], 404);
        }
        $transaction = Transaction::where('id', $transaction_id)->with('product')->first();
        
        if(auth('api')->user()->is_admin == true)        
        {
            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $dispute
            ]);

        }

        return response()->json([
            'status' => 'Not allow',
            'message' => 'Unauthorize',
            
        ], 401);
    }

    public function resolveDispute(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'transaction_id' => 'required',
            
        ]);

        if(auth('api')->user()->is_admin == true)  {
            return response()->json([
                'status' => 'Not allow',
                'message' => 'Unauthorize',
                
            ], 401);
        }

        Dispute::where('id', $request->id)->update([
            'dispute_solved' => true
        ]);
        $dispute = Dispute::where('id', $request->id)->with('user', 'transaction')->get();

        $dispute_presence = Dispute::where([['transaction_id', '=', $request->transaction_id], ['dispute_solved', '=', false]])->first();


        if($dispute_presence){

            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $dispute
            ]);
        }
        else{
            
            Transaction::where('id', $request->transaction_id)->update([
                'dispute' => false
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $dispute
            ]);
        }
        

        //Mail::to($user)->send(new CreateProductMail($user, $product));
    }
}
