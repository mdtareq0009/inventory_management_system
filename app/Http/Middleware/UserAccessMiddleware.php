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
        $getPer = auth()->user()->permissions;
        $getPer = preg_replace('/[^A-Za-z0-9\-\,\_]/', '', $getPer);
        $permissions =explode(',', $getPer);
        if(array_search(request()->path(), $permissions ?? []) > -1 || in_array(auth()->user()->role, ['Admin', 'General'])){
            return $next($request);
        } else {
            return redirect()->route('dashboard');
        }
    }
}
