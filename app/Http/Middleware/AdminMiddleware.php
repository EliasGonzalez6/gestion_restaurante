<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Maneja la solicitud entrante.
     */
    public function handle(Request $request, Closure $next)
    {
        // Verifica que el usuario esté logueado y sea Supervisor o Gerente
        if (Auth::check() && in_array(Auth::user()->roles_id, [3, 4])) {
            return $next($request);
        }

        // Si no tiene permiso, retorna 403 o redirige
        abort(403, 'Acceso no autorizado');
    }
}
