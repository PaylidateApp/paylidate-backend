<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Transaction;
use App\Dispute;
use App\User;
use App\Product;
use App\Referer;
use App\Refund;
use App\Wallet;

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

    

    public function numbers_of()
    {
        $userCount = User::count();
        $totalTransations = Transaction::where('status', 1)->count();
        $totalTransationsAmount = Transaction::where('status', 1)->sum('amount');
        $disputeCount = Dispute::count();
        $refundCount = Refund::count();
        $referralCount = Referer::count();
        $totalWalletAmount = Wallet::get()->sum('amount');
        $listOfUsers = User::get();
        $listOfTransactions = Transaction::get();

        $disputes = Dispute::with('user', 'transaction', 'dispute_chat')->orderBy('dispute_solved')->get();

        // $disputes = [];
        // $disputes['initiator'] = User::where('id', $listOfDisputes->user_id)->get('name');
        // $disputes['receiver'] = User::where('id', $listOfDisputes->user_id)->get('name');
        // $disputes['dispute'] = Dispute::get();
        // return response()->json(['data' => $disputes], 200);

        

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => [
                'Total_registered_users' => $userCount,
                'Total_Transactions_completed' => $totalTransations,
                'Total_Transactions_completed_amount' => $totalTransationsAmount,
                'Total_Dispute' => $disputeCount,
                'Total_Refund' => $refundCount,
                'Total_Referral' => $referralCount,
                'total_Wallet_Amount' => $totalWalletAmount,
                'list_Of_Users' => $listOfUsers,
                'list_Of_Transactions' => $listOfTransactions,
                'list_Of_Disputes' => $disputes,
            ]
        ], 200);
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

    public function total_of()
    {
        $transations = Transaction::all();

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => [
                'Transations' => $transations
            ]
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
