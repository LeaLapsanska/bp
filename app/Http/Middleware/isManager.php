<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;

class isManager
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
        $user = JWTAuth::parseToken()->authenticate();

        if(!$user->isManager)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not manager.'
            ], 401);
        }


        return $next($request);
    }
}
