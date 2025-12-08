<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureRole
{
    /**
     * Verifica que el usuario autenticado tenga alguno de los roles indicados.
     * Uso: ->middleware('role:ADMIN')  o  ->middleware('role:ADMIN,ENFERMERIA')
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role->name ?? null;

        // admite uno o varios roles: role:ADMIN  o role:ADMIN,ENFERMERIA
        if (!$userRole || !in_array($userRole, $roles, true)) {
            abort(403, 'No autorizado.');
        }

        return $next($request);
    }
}
