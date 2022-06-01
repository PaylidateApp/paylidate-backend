<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordMail;

use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\User;
use App\PasswordReset;
use Illuminate\Support\Facades\DB;

class PasswordResetController extends Controller
{
    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        DB::delete('delete from password_resets where email = ?',[$request->email]);
        

        $user = User::where('email', $request->email)->first();
        
        if (!$user)
        return response()->json([
            'status' => 'failed',
            'message' => 'We cannot find a user with that e-mail address.'
        ], 404);
        
        $token = \Str::random(60). $user->id;
        
        // $passwordReset = new PasswordReset;
        // $passwordReset->email = $user->email;
        // $passwordReset->token = $token;
        // $passwordReset->save();

        $passwordReset = passwordReset::create([
           
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
           

        ]);
return response()->json([
                    'status' => 'success',
                    'message' => 'We have e-mailed your password reset link!',
                        
                    ]);
        
        $url = ( 'https://paylidate.com/reset-password/'.$token);         
        
        if ($user && $passwordReset)
        
        
        {
            try {
            Mail::to( $request->email)->send(new ForgotPasswordMail($user, $url));
                return response()->json([
                    'status' => 'success',
                    'message' => 'We have e-mailed your password reset link!',
                        
                    ]);
            } 
            catch (Exception $e) {
               
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email sending error'
                ], 450);
            }

            }
        
    }

    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();
        if (!$passwordReset)
            return response()->json([
                'status' => 'failed',
                'message' => 'This password reset token is invalid.'
            ], 400);

        if (Carbon::parse($passwordReset->created_at)->addMinutes(10)->isPast()) {
            
        DB::delete('delete from password_resets where email = ?',[$passwordReset->email]);

            return response()->json([
                'status' => 'expired',
                'message' => 'Password reset token expired'
            ], 400);
        }

        return response()->json($passwordReset);
    }

    /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed',
            'token' => 'required|string'
        ]);

        $passwordReset = PasswordReset::where('token', $request->token)->first();

        if (!$passwordReset)
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 400);

        $user = User::where('email', $passwordReset->email)->first();
        if (!$user)
            return response()->json([
                'message' => 'We cannot find a user with that e-mail address.'
            ], 400);

        $user->password = bcrypt($request->password);
        $user->save();        
        

        DB::delete('delete from password_resets where email = ?',[$request->email]);

        return response()->json([
            'status' => 'success',            
            'message' => 'Password reset successful. Please login',

        ]);
    
        
    }
}
