<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Mail\WalletCreated;
use App\Services\FulfilmentService;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // DB::table('migrations')
        //     ->where('id', 10)
        //     ->update(['batch' => 10]);
        // DB::table('users')
        //     ->where('id', 98)

        //DB::table('wallets')->where('id', 6)->update(['balance' => 0.00]);
    //    Mail::to('sirlawattah@gmail.com')->send(new WalletCreated('Lawrence Attah'));
   //     Mail::to('enyoojoblessing2020@gmail.com')->send(new WalletCreated('Enyo Cakes and  Pasteries'));
       // Mail::to('ojahjoyegbianije@gmail.com')->send(new WalletCreated('Joy Egbianije Ojah'));
      //  Mail::to('viisiomedia@gmail.com')->send(new WalletCreated('Viisio Media'));
      //  Mail::to('holyphilzy@gmail.com')->send(new WalletCreated('Philemon Shekari'));

        // \Artisan::call('migrate:rollback --step=1');
        //\Artisan::call('migrate');

        // DB::table('users')
        //     ->where('id', 97)
        //     ->update(['password' => bcrypt('123DF_puo>ghc'), 'email' => 'designme60@gmail.com']);

        $users = DB::select('select * from users');
        $wallets = DB::select('select * from wallets');
        $migrattion = DB::select('select * from migrations');

        return [$users, $wallets];
        $Instandpay = DB::select('select * from migrations');


        // $emailTransaction['id'] = 1;
        // $emailTransaction['referral'] = 1223;
        // $emailTransaction['transaction_ref'] = 'dummy_rerf';
        // $emailTransaction['product_id'] = 4;
        // $emailTransaction['product_name'] = 'bag';
        // $emailTransaction['product_number'] = 11112332;
        // $emailTransaction['type'] = 'product';
        // $emailTransaction['total_quantity'] = 2;
        // $emailTransaction['total_price'] = 1000;
        // $emailTransaction['description'] = 'purple and red';

        // (new FulfilmentService())->initiate_fufilment('segun8428@gmail.com', 'DAve2', 'segun8428@gmail.com', 'buyer', 5, 1, 'dumm_rerf', $emailTransaction);
    }
    public function indexx1($id)
    {

        if ($id == 12345) {

            DB::insert(
                'insert into withdrawals (user_id, transaction_id, payment_id, bank_id, narration, debit_currency, f_withdrawal_id, status) values (?, ?, ?, ?, ?, ?, ?, ?)',
                [33, 77, 40, 7, 'Payment for Real Estate Theme', 'NGN', 29655266, true]
            );
            return 'good';
        } else {
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
