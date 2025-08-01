<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsUserAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth('api')->user()) {
            return $next($request);
        } else {
            return response()->json([
                'status' => 401,
                'msg' => 'Valida de nuevo',
                'error' => 'Acceso no autorizado'
            ], 401);
        }
    }
}
