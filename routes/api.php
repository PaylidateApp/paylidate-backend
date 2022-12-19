<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ThirdPartyApiMiddleware;
use PHPUnit\Util\Getopt;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


// This endpoint is to be consume by flutterwave.
// It is for credting wallet by transfer
Route::namespace('Api')->group(
    function () {
        Route::post('settlement_notif', 'WalletController@creditWalletByTransfer');
    }
);

Route::group(['prefix' => 'api'], function () {

    Route::namespace('Api')->group(function () {

        Route::get('artisan-command/{password}', 'ArtisanCommandController@index');

        //Route::get('transfer', 'WithdrawalController@transfer_to_bank');

        //Route::post('instant-pay/tranfer1', 'InstandpayController@transfer');
        Route::get('instant-get', 'InstandpayController@index');

        Route::post('login', 'AuthController@login');
        Route::post('signup', 'AuthController@signup');


        Route::get('signup/activate/{token}', 'AuthController@signupActivate');
        Route::get('check/email/{email}', 'AuthController@check_email');
        Route::get('product/{slug}', 'ProductController@get_product');


        Route::get('transaction/{T_ref}', 'TransactionController@get_transaction');

        // Fulfilment Route
        Route::get('fulfilment/abcdef', 'FulfilmentController@static');
        Route::post('fulfilment/abcdef', 'FulfilmentController@static_post');
        Route::get('fulfilment/{hash}', 'FulfilmentController@get_transaction');
        Route::post('fulfilment/{hash}', 'FulfilmentController@confirm_fufilment');


        Route::group(['prefix' => 'password'], function () {
            Route::post('create', 'PasswordResetController@create');
            Route::get('find/{token}', 'PasswordResetController@find');
            Route::post('reset', 'PasswordResetController@reset');
        });

        Route::Post('get-rate', 'PaymentController@get_rate');
        Route::get('get-banks', 'PaymentController@banks');

        // password reset routes    
        Route::post('verify-account', 'BankController@verify_account_number');
        Route::group(['middleware' => 'auth:api'], function () {
            Route::get('logout', 'AuthController@logout');
            Route::get('user', 'AuthController@user');
            Route::post('user/update', 'AuthController@update');

            Route::post('resendVerificationEmail/{email}', 'AuthController@resendVerificationEmail');
            Route::post('verifyEmail/{token}', 'AuthController@verifyEmail');

            // Route::resource('users', 'UsersController');
            // Route::post('user/avatar', 'UsersController@avatar');
            // Route::get('user/post', 'UsersController@post');
            // Route::post('user/validate', 'UsersController@validate_password');

            Route::post('send-dispute-chat', 'DisputeChatController@store');

            Route::post('open-dispute', 'DisputeController@open_dispute');
            Route::post('resolve-dispute', 'DisputeController@resolve_dispute');
            Route::get('transaction-disputes/{transaction_id}', 'DisputeController@getTransactionDisputes');

            Route::get('product/resolve-dispute/{id}', 'ProductController@resolve_dispute');

            // wallet routes
            Route::get('get-wallet', 'WalletController@index');
            Route::post('create-wallet', 'WalletController@create');
            Route::post('credit-wallet', 'WalletController@creditWalletByFL');
            Route::post('debit-wallet', 'WalletController@debitWallet');
            Route::get('get-wallet-history', 'WalletHistoryController@index');
            // Route::get('get-wallet-balance', 'WalletController@getBalance');
            // Route::get('get-wallet-bonus', 'WalletController@getBonus');


            Route::resource('product', 'ProductController');

            Route::get('product/accept/{id}', 'ProductController@accept');
            Route::get('product/status/{id}', 'ProductController@status');
            Route::post('product/update/{id}', 'ProductController@update');

            Route::get('product/status/delivery/{id}', 'ProductController@delivery');
            Route::get('product/status/delivered/{id}', 'ProductController@delivered');
            Route::get('product/status/recieved/{id}', 'ProductController@recieved');
            Route::get('product/status/canceled/{id}', 'ProductController@canceled');

            Route::post('instant-pay/tranfer', 'InstandpayController@transfer');
            Route::post('instant/tranfer', 'InstandpayController@transfer');
            Route::post('instant-pay/verify', 'InstandpayController@verify');
            Route::post('instant-pay/withdraw', 'InstandpayController@withdraw');
            Route::get('instant-pay/history-send', 'InstandpayController@send');
            Route::get('instant-pay/history-receive', 'InstandpayController@receive');
            Route::get('verify-number/{phone_number}', 'InstandpayController@verify_user');

            Route::post('transaction/accept/{id}', 'TransactionController@accept');
            Route::post('transaction/decline/{id}', 'TransactionController@decline');
            Route::post('transaction/confirm/{id}', 'TransactionController@confirm');
            Route::post('transaction/cancel/{id}', 'TransactionController@cancel');
            Route::post('transaction/report-transaction/{id}', 'TransactionController@reportTransaction');
            Route::post('transaction/reslove-report/{id}/{sellerEemail}', 'TransactionController@resloveReport');

            Route::resource('payment', 'PaymentController');
            Route::post('make-payment', 'PaymentController@make_payment');
            // Route::get('payments-received', 'PaymentController@payments_received');

            Route::resource('card', 'CardController');
            //Route::post('payment/link', 'PaymentController@getPaymentLink');
            Route::resource('transaction', 'TransactionController');
            Route::resource('account', 'UserAccountController');

            Route::post('fund', 'CardController@fund');

            Route::resource('user-bank', 'BankController');

            //Dashboard Endpoints
            Route::get('dashboard', 'DashboardController@index');


            // Route::resource('withdraw', 'WithdrawalController');
            Route::get('withdraw-requests', 'WithdrawalController@index');
            Route::post('request-withdrawal', 'WithdrawalController@request_withdrawal');
            Route::post('transfer-to-bank', 'WithdrawalController@transfer_to_bank');


            Route::get('refund-requests', 'RefundController@index');
            Route::post('request-refund', 'RefundController@request_Refund');
            Route::post('transfer-to-buyer-bank', 'RefundController@transfer_to_bank');


            Route::get('referral-bonus', 'RefererController@index');
            Route::get('referral', 'RefererController@get_referer');

            Route::post('request-referral-bonus-withdrawal', 'ReferralWidrawalController@request_withdrawal');
            Route::get('referral-withdraw-requests', 'ReferralWidrawalController@index');
            Route::post('transfer-referral-bonus-to-bank', 'ReferralWidrawalController@transfer_to_bank');
        });
    });
    Route::get('get-users', 'UserController@index');
    Route::get('get-users/{id}', 'UserController@indexx');



    Route::group(['middleware' => 'auth:api', 'prefix' => 'admin'], function () {
        Route::get('users', 'AdminController@users');
        Route::get('users/{startDate}/{endDate}', 'AdminController@userBtwnDate');
        Route::get('users/total', 'AdminController@numbers_of_users');
    });



    Route::namespace('ThirdPartyApi')->group(function () {


        Route::group(['prefix' => 'v1'], function () {

            Route::middleware([ThirdPartyApiMiddleware::class])->group(function () {

                Route::get('get-users', 'AccountController@index');
            });
        });
    });
});
