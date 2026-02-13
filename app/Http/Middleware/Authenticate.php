<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class Authenticate
{
    public function handle(Request $request, Closure $next)
    {
        // Skip auth untuk health check
        if ($request->path() === 'health') {
            return $next($request);
        }

        if (!Session::has('authenticated')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
