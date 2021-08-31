<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

Route::namespace('Api')->group(function () {

    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
    Route::get('signup/activate/{token}', 'AuthController@signupActivate');
    Route::get('check/email/{email}', 'AuthController@check_email');
    Route::get('product/{slug}', 'ProductController@get_product');

    Route::Post('get-rate', 'PaymentController@get_rate');

    // password reset routes
    Route::group(['middleware' => 'api', 'prefix' => 'password'], function () {
        Route::post('create', 'PasswordResetController@create');
        Route::get('find/{token}', 'PasswordResetController@find');
        Route::post('reset', 'PasswordResetController@reset');
    });

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
        Route::post('user/update', 'AuthController@update');

        // Route::resource('users', 'UsersController');
        // Route::post('user/avatar', 'UsersController@avatar');
        // Route::get('user/post', 'UsersController@post');
        // Route::post('user/validate', 'UsersController@validate_password');

        Route::resource('product', 'ProductController');

        Route::get('product/accept/{id}', 'ProductController@accept');
        Route::get('product/status/{id}', 'ProductController@status');

        Route::get('product/status/delivery/{id}', 'ProductController@delivery');
        Route::get('product/status/delivered/{id}', 'ProductController@delivered');
        Route::get('product/status/recieved/{id}', 'ProductController@recieved');
        Route::get('product/status/canceled/{id}', 'ProductController@canceled');

        Route::resource('payment', 'PaymentController');
        Route::resource('card', 'CardController');
        Route::post('payment/link', 'PaymentController@getPaymentLink');
        Route::post('make-payment', 'PaymentController@make_payment');
        Route::resource('transaction', 'TransactionController');
        Route::resource('account', 'UserAccountController');

        Route::post('fund', 'CardController@fund');
    });
});

