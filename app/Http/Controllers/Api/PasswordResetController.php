<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\User;
use App\PasswordReset;

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

        $user = User::where('email', $request->email)->first();

        if (!$user)
            return response()->json([
                'status' => 'failed',
                'message' => 'We cannot find a user with that e-mail address.'
            ]);

        $token = \Str::random(60);

        $passwordReset = new PasswordReset;
        $passwordReset->email = $user->email;
        $passwordReset->token = $token;
        $passwordReset->save();
         

        if ($user && $passwordReset)
            $user->notify(new PasswordResetRequest($token)); 

        return response()->json([
            'status' => 'success',
            'message' => 'We have e-mailed your password reset link!'
        ]);
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

        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return response()->json([
                'status' => 'failed',
                'message' => 'This password reset token is invalid.'
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

        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();

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
        PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->update(['token' => '']);
        $user->notify(new PasswordResetSuccess($passwordReset));
        return response()->json($user);
    }
}