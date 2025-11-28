<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    public function handle($request, Closure $next)
    {
        if ($request->routeIs('login') || $request->routeIs('register') || $request->routeIs('home')) {
            return $next($request);
        }
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu!');
        }

        return $next($request);
    }
}