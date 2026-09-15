<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'anda tidak memiliki izin akses');
        } else {
            return $next($request);
        }

        return $next($request);
    }
}