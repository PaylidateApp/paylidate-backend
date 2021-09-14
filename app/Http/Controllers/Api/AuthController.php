<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\RegistrationMail;
use Illuminate\Support\Facades\Mail;
use Validator;
use App\User;
use App\Wallet;
use App\UserAccount;
use Auth;
use App\VirtualCard;
use Carbon\Carbon;

/**
 * @group  Authentication management
 *
 * APIs for Authenticating users
 */
class AuthController extends Controller
{
    /**
     * Create user
     *
     * the user signup routs
     *
     * @bodyParam name string required the full name of the user
     * @bodyParam email string required the email of the user , this value is unige
     * @bodyParam phone string required the valide phone number of the user, this value is unige
     * @bodyParam password string required the users prefered password
     * @bodyParam password_confirmation string required the confirmation password. must be thesame as the password
     *
     *
     * @return [string] message
     */
    public function signup(Request $request)
    {

        $messages = [
            'name.required'    => 'Enter full name!',
            'email.required' => 'Enter an e-mail address!',
            'email' => 'E-mail address exist!',
            'phone' => 'unique',
            'phone' => 'Phone number exist!',
            'password.required'    => 'Password is required',
            'password_confirmation' => 'The :password and :password_confirmation must match.'
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ], $messages);


        $user = User::where('email', $request->get('email'))->first();

        if ($user) {
            return response()->json([
                'status' => 'exist',
                'message' => 'User already exist. please login',
            ], 409);
        } elseif ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 406);
        } else {
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);

            Wallet::create([
                'user_id' => $user->id,
            ]);

            // if (env('ENABLE_VIRTUAL_ACCOUNT_ON_REGISTRATION', false)) {
            //     $ref = '';
            //     $user_account = new UserAccount;
            //     $virtual_account = $user_account->createVirtualAccount($email = $request->email, $is_permanent = false, $name = $request->name);

            //     if ($virtual_account['status'] == 'success') {
            //             $ref =  $virtual_account['data']['order_ref'];
            //         }

            //     UserAccount::create([
            //         'user_id' => $user->id,
            //         'ref' => $ref
            //     ]);
            // }

            $user_virtual_card = new VirtualCard;

            // create NGN virtual card for transactions
            $virtual_card = $user_virtual_card->virtualCard($currency = 'NGN', $ammount = '150', $name = $request->name);
            $naira_card_id = null;

            if ($virtual_card['status'] == 'success') {
                $naira_card_id = $virtual_card['data']['id'];
                VirtualCard::create([
                    'user_id' => $user->id,
                    'card_id' => $virtual_card['data']['id'],
                    'account_id' => $virtual_card['data']['account_id'],
                    'currency' => $virtual_card['data']['currency'],
                    'default' => 1,
                ]);
            }else {
                // Mail::raw($virtual_card['message'], function ($message) {
            }

           // create USD virtual card for transactions

            // $virtual_card = $user_virtual_card->virtualCard($currency = 'USD', $ammount = '1', $name = $request->name);
            // $dollar_card_id = null;
            // if ($virtual_card['status'] == 'success') {
            //     $dollar_card_id = $virtual_card['data']['id'];
            //     VirtualCard::create([
            //         'user_id' => $user->id,
            //         'card_id' => $virtual_card['data']['id'],
            //         'account_id' => $virtual_card['data']['account_id'],
            //         'currency' => $virtual_card['data']['currency'],
            //         'default' => false,
            //     ]);
            // }else {

            // }

            // withdraw from virtual card
            $withdraw = $user_virtual_card->withdrawFromVirtualCard($card_id = $naira_card_id, $ammount = '145');
            // $withdraw = $user_virtual_card->withdrawFromVirtualCard($card_id = $dollar_card_id, $ammount = '1');

            Mail::to($user)->send(new RegistrationMail($user));

            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->save();

            return response()->json([
                'status' => 'success',
                'message' => 'User created',
                'access_token' => $tokenResult->accessToken,
                'data' => $user->load('wallet'),
                // 'account' => $virtual_account['data']
            ]);
        }
    }

    /**
     * Login user and create token
     *
     * @bodyParam email string required the email of the user
     * @bodyParam password string required the users prefered password
     * @bodyParam  remember_me boolean
     *
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = request(['email', 'password']);
        $credentials['active'] = 1;
        $credentials['deleted_at'] = null;

        if (!Auth::attempt($credentials))
            return response()->json([
                'status' => 'failed',
                'message' => 'Unauthorized'
            ], 401);

        if (!Auth::user()->active)
            return response()->json([
                'status' => 'failed',
                'message' => 'Your account is not activated'
            ], 401);

        $user = User::where('id', Auth::user()->id)->with('wallet')->first();

        // $user_account = new UserAccount;
        // $account = $user_account->where('user_id', Auth::user()->id)->first();


        // if ($account && $account->ref) {
        //     $response = $user_account->getVirtualAccount($account->ref);

        //     if ($response['status'] == 'success') {
        //             $account['account'] = $response['data'];
        //         }
        // }

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(4);
        $token->save();

        return response()->json([
            'status' => 'success',
            'access_token' => $tokenResult->accessToken,
            'message' => 'login successful',
            'data' => $user->load('wallet'),
            // 'account' => $account
        ]);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @authenticated
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out'
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @authenticated
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        $user = User::where('id', Auth::user()->id)->with('wallet')->first();
        $account = UserAccount::where('user_id', Auth::user()->id)->first();

        if ($account && $account->ref) {
            $user_object = new User;
            $virtual_account = $user_object->getVirtualAccount($account->ref);

            if ($virtual_account['status'] == 'success') {
                    $account['account'] = $virtual_account['data'];
                }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'user fetched',
            'data' => $user->load('wallet'),
            'account'=> $account
        ]);
    }


    /**
     * User Activation
     *
     * * @urlParam  token string required the token sent to the users email address
     *
     * @return [json] user object
     */
    public function signupActivate($token)
    {
        $user = User::where('activation_token', $token)->first();
        if (!$user) {
            return response()->json([
                'status' => 'failed',
                'message' => 'This activation token is invalid.'
            ], 404);
        }

        $user->active = true;
        $user->activation_token = '';
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'user activated',
            'data' => $user->load('wallet')
        ]);
    }


    /**
     * Update user
     *
     *
     * @bodyParam name string required the full name of the user
     * @bodyParam email string required the email of the user , this value is unige
     * @bodyParam phone string required the valide phone number of the user, this value is unige
     *
     *
     * @return [string] message
     */
    public function update(Request $request)
    {
        $input = $request->all();
        $id = Auth::user()->id;
        $user = User::where('id', $id)->update($input);
        $user = Auth::user();

        return response()->json([
            'status' => 'success',
            'message' => 'User updated',
            'data' => $user->load('wallet'),
        ]);

    }

    public function check_email($email){
        $user = User::where('email', $email)->first();

        if ($user) {
            return response()->json([
                'status' => 'true',
                'message' => 'User exist.',
            ]);
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'User doesnot exist.'
            ], 406);
        }

    }

}
