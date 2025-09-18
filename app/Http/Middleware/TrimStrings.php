<?php

namespace App\Http\Middleware;

use Closure;

class TrimStrings
{
    public function handle($request, Closure $next)
    {
        // Recorrer todos los inputs y hacer trim
        $request->merge(array_map(function ($value) {
            return is_string($value) ? trim($value) : $value;
        }, $request->all()));

        return $next($request);
    }
}
