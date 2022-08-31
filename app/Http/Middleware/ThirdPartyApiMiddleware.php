<?php

namespace App\Http\Middleware;
use App\User;

use Closure;

class ThirdPartyApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $header = $request->header('Authorization');
        $token = $request->bearerToken();
        if(!isset($header) || !isset($token)){

            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',

            ], 401);

        }


        $user = User::where('API_key', $token);
        if(!$user){
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden',

            ], 403);
        }

        return $next($request);
    }
}
