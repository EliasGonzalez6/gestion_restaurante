<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && in_array(Auth::user()->roles_id, [3, 4])) {
            return $next($request);
        }

        abort(403, 'Acceso no autorizado');
    }
}
