<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authorize
{
    /**
     * Handle an incoming request.
     * 
     * Pass the request only for authorized employees.
     * For that middleware work properly, a middleware 
     * parameter must be passed with the name of the role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param string $id
     * @return mixed
     */
    public function handle($request, Closure $next, $id)
    {
        $roles = ['admin' => 1, 'employee' => 2, 'intern' => 3];

        if (Auth::user()->role === $roles[$id]) {
            return $next($request);
        }
        return route('unauthorized');
    }
}
