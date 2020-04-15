<?php

namespace App\Http\Middleware;

use Closure;

class IsAdmin
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
        if (auth()->user()->role === 1) {
            return $next($request);
        }
        
        return response()->json([
            'success' => false,
            'message' => "You don't have the necessary authorization to perform this action",
        ], 401);
    }
}
