<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = DB::select('select * from users');
       // $sel = "ogeneoyore@gmail.com";
       // $userme = User::where('email', $sel)->first();
       // $pas = bcrypt("ogeneoyore@123");
       // $userme->update([
        //    'password' => $pas
        //]);
        return $users;
        DB::delete('delete from withdrawals');
       

        DB::insert('insert into withdrawals (user_id, transaction_id, payment_id, bank_id, narration, debit_currency, f_withdrawal_id, status, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [1, 63, 34, 1, 'Payment for Test Product', 'NGN', 28844659, true, '2022-07-04 09:22:10', '2022-07-04 09:22:10']);
        DB::insert('insert into withdrawals (user_id, transaction_id, payment_id, bank_id, narration, debit_currency, f_withdrawal_id, status, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [5, 30, 1, 1, 'Payment for Photography and Videography', 'NGN', 28844859, true, '2022-07-04 09:22:10', '2022-07-04 09:22:10']);
        DB::insert('insert into withdrawals (user_id, transaction_id, payment_id, bank_id, narration, debit_currency, f_withdrawal_id, status, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [3, 67, 35, 4, 'Payment for Design copy', 'NGN', 28869297, true, '2022-07-04 09:22:10', '2022-07-04 09:22:10']);
        DB::insert('insert into withdrawals (user_id, transaction_id, payment_id, bank_id, narration, debit_currency, f_withdrawal_id, status, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [14, 68, 36, 5, 'Payment for Music', 'NGN', 28877769, true, '2022-07-04 09:22:10', '2022-07-04 09:22:10']);
        DB::insert('insert into withdrawals (user_id, transaction_id, payment_id, bank_id, narration, debit_currency, f_withdrawal_id, status, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [22, 73, 39, 6, 'Payment for Ideal men body cream', 'NGN', 29063990, true, '2022-07-04 09:22:10', '2022-07-04 09:22:10']);
        DB::insert('insert into withdrawals (user_id, transaction_id, payment_id, bank_id, narration, debit_currency, f_withdrawal_id, status, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [33, 77, 40, 7, 'Payment for Real Estate Theme"', 'NGN', 29655266, true, '2022-07-04 09:22:10', '2022-07-04 09:22:10']);

      
        /*         DB::update(
            'update migrations set batch = 6 where id = ?',
            ['29']
        );
        DB::update(
            'update migrations set batch = 6 where id = ?',
            ['30']
        );
        DB::update(
            'update migrations set batch = 6 where id = ?',
            ['31']
        ); */

        // \Artisan::call('migrate');

    $migrations = DB::select('select * from migrations');
    $wallets = DB::select('select * from wallets');
    $users = DB::select('select * from users');
    $products = DB::select('select * from products');
    $transactions = DB::select('select * from transactions');
    $payments = DB::select('select * from payments');
    $disputes = DB::select('select * from disputes');
    $withdrawals = DB::select('select * from withdrawals');
    $banks = DB::select('select * from banks');
 
        return [$users, $migrations, $wallets, $products, $transactions, $payments, $disputes, $withdrawals, $banks];

    }
    public function indexx1($id)
    {

        if($id == 12345){

            DB::insert('insert into withdrawals (user_id, transaction_id, payment_id, bank_id, narration, debit_currency, f_withdrawal_id, status) values (?, ?, ?, ?, ?, ?, ?, ?)', 
            [33, 77, 40, 7, 'Payment for Real Estate Theme', 'NGN', 29655266, true]);
            return 'good';
        }
        else{
            return 'bad';

        }
        
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
        $user = User::find(Auth::user()->id);
        $user->name =  $request->name;
        $user->email =  $request->name;
        $user->password =  $request->name;
        $user->phone =  $request->name;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $user
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
