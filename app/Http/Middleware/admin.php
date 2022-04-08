<?php

namespace App\Http\Middleware;

use Closure;
use Auth;


class admin
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
        if( !auth('api')->user()->is_admin){

            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
                
            ], 403);
        }
        
        return $next($request);


    }
}
