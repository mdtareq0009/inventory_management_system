<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserAccessMiddleware
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
        if(array_search(request()->path(), auth()->user()->permissions) > -1 || in_array(auth()->user()->role, ['Admin', 'Super Admin'])){
            return $next($request);
        } else {
            return redirect()->route('dashboard');
        }
    }
}
