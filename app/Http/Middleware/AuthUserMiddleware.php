<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthUserMiddleware
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
        if($request->hasHeader('Authorization')) {
            $user = \App\User::where('auth_api', $request->header('Authorization'))->first();

            if($user) {
                Auth::viaRequest($user);

                return $next($request);
            } else {
                return response()->json(['message' => 'You need authorizationn'], 403);
            }

        } else {
            return response()->json(['message' => 'You need authorization'], 403);
        }

    }


}
